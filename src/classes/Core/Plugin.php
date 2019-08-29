<?php
/**
 * Core plugin class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Core;

use EasyWatermark\AttachmentProcessor\AttachmentProcessorGD;
use EasyWatermark\Backup\Manager as BackupManager;
use EasyWatermark\Features\AutoWatermarkSwitch;
use EasyWatermark\Metaboxes;
use EasyWatermark\Placeholders\Defaults as DefaultPlaceholders;
use EasyWatermark\Traits\Hookable;
use EasyWatermark\Watermark\Handler;
use EasyWatermark\Watermark\Preview;
use EasyWatermark\Watermark\Watermark;
use EasyWatermark\Watermark\PostType as WatermarkPostType;
use underDEV\Utils\Singleton;

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
		register_uninstall_hook( EW_FILE_PATH, [ 'EasyWatermark\Core\Installer', 'uninstall' ] );

		$this->hook();

		// Init Freemius.
		ew_fs();

		// Signal that SDK was initiated.
		do_action( 'ew_fs_loaded' );

		BackupManager::get();

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

		$this->get_watermark_handler();

		new WatermarkPostType();
		new Assets( $this );
		new AutoWatermarkSwitch();

		$settings = Settings::get();

		$last_version = get_option( $this->slug . '-version' );
		if ( $this->version !== $last_version ) {
			// Version has changed. Update.
			Installer::update( $last_version, $settings->get_settings() );
		}

		$this->setup_metaboxes();

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

	}

	/**
	 * Initiates plugin
	 *
	 * @action  parse_request
	 *
	 * @param   WP $wp WP object.
	 * @return  void
	 */
	public function parse_request( $wp ) {

		if ( ! array_key_exists( 'easy_watermark_preview', $wp->query_vars ) ) {
			return;
		}

		$preview      = new Preview( $this->get_watermark_handler() );
		$type         = $wp->query_vars['easy_watermark_preview'];
		$watermark_id = $wp->query_vars['watermark_id'];
		$format       = $wp->query_vars['format'];
		$size         = isset( $wp->query_vars['image_size'] ) ? $wp->query_vars['image_size'] : 'full';

		$preview->show( $type, $watermark_id, $format, $size );

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
