<?php
/**
 * Core plugin class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Core;

use EasyWatermark\AttachmentProcessor\AttachmentProcessorGD;
use EasyWatermark\Backup\Manager as BackupManager;
use EasyWatermark\Dashboard\Dashboard;
use EasyWatermark\Features;
use EasyWatermark\Metaboxes;
use EasyWatermark\Placeholders\Defaults as DefaultPlaceholders;
use EasyWatermark\Settings\Settings;
use EasyWatermark\Traits\Hookable;
use EasyWatermark\Watermark\Handler;
use EasyWatermark\Watermark\Preview;
use EasyWatermark\Watermark\Watermark;
use EasyWatermark\Watermark\PostType as WatermarkPostType;
use Micropackage\Singleton\Singleton;

/**
 * Main plugin class
 */
class Plugin extends Singleton {

	use Hookable;

	/**
	 * Plugin name
	 *
	 * @var string
	 */
	private $name = null;

	/**
	 * Plugin slug
	 *
	 * @var string
	 */
	private $slug = null;

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	private $version = null;

	/**
	 * Watermark Handler instance
	 *
	 * @var Handler
	 */
	private $watermark_handler;

	/**
	 * Constructor
	 */
	protected function __construct() {

		$data = \get_file_data( EW_FILE_PATH, [
			'name'    => 'Plugin Name',
			'version' => 'Version',
		], 'plugin' );

		$this->name    = $data['name'];
		$this->slug    = dirname( plugin_basename( EW_FILE_PATH ) );
		$this->version = $data['version'];

		register_activation_hook( EW_FILE_PATH, [ 'EasyWatermark\Core\Installer', 'activate' ] );
		register_deactivation_hook( EW_FILE_PATH, [ 'EasyWatermark\Core\Installer', 'deactivate' ] );

		if ( ! ew_dochooks_enabled() ) {
			add_action( 'plugins_loaded', [ $this, 'setup' ] );
		}

		$this->hook();

		BackupManager::get();

		do_action( 'ew_load', $this );

	}

	/**
	 * Creates nessesary instances
	 *
	 * @action  plugins_loaded
	 *
	 * @return  void
	 */
	public function setup() {

		new DefaultPlaceholders();

		new Features\AutoWatermarkSwitch();
		new Features\CacheBusting();
		new Features\SrcsetFilter();
		new Features\WatermarkPreview( $this );

		new WatermarkPostType();
		new Dashboard();
		new Assets( $this );

		$this->get_watermark_handler();

		Settings::get();

		$this->setup_metaboxes();

		if ( ! ew_dochooks_enabled() ) {
			Hooks::get()->load_hooks();
		}

	}

	/**
	 * Creates metabox objects
	 *
	 * @return  void
	 */
	private function setup_metaboxes() {

		new Metaboxes\Watermark\Submitdiv();
		new Metaboxes\Watermark\WatermarkContent();
		new Metaboxes\Watermark\TextOptions();
		new Metaboxes\Watermark\Alignment();
		new Metaboxes\Watermark\Scaling();
		new Metaboxes\Watermark\ApplyingRules();
		new Metaboxes\Watermark\Preview();
		new Metaboxes\Watermark\Placeholders();

		if ( current_user_can( 'apply_watermark' ) ) {
			new Metaboxes\Attachment\Watermarks();
		}

	}

	/**
	 * Initiates plugin
	 *
	 * @action  init
	 *
	 * @return  void
	 */
	public function init() {

		add_rewrite_tag( '%easy_watermark_preview%', '([^./-]+)' );
		add_rewrite_tag( '%format%', '(jpg|png)' );
		add_rewrite_tag( '%watermark_id%', '([0-9]+)' );
		add_rewrite_tag( '%image_size%', '([^./-]+)' );

		add_rewrite_rule(
			'easy-watermark-preview/([^/.-]+)-([0-9]+)-([^/.]+)?',
			'index.php?easy_watermark_preview=$matches[1]&watermark_id=$matches[2]&image_size=$matches[3]',
			'top'
		);

		add_rewrite_rule(
			'easy-watermark-preview/([^/.-]+)-([0-9]+)?',
			'index.php?easy_watermark_preview=$matches[1]&watermark_id=$matches[2]',
			'top'
		);

		$last_version = get_option( $this->slug . '-version' );
		if ( $this->version !== $last_version ) {
			// Version has changed. Update.
			$settings = Settings::get();
			Installer::update( $last_version, $settings->get_settings() );
		}

		if ( ! get_option( 'easy-watermark-first-booted' ) ) {
			flush_rewrite_rules();
			update_option( 'easy-watermark-first-booted', true );
		}

	}

	/**
	 * Returns plugin name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Returns plugin slug
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Returns plugin version
	 *
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Returns plugin version
	 *
	 * @return string
	 */
	public function get_watermark_handler() {

		if ( ! $this->watermark_handler ) {
			$this->watermark_handler = new Handler();
		}

		return $this->watermark_handler;

	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}
}
