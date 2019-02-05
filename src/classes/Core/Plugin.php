<?php
/**
 * Core plugin class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Core;

use EasyWatermark\Metaboxes;
use EasyWatermark\PostTypes\Watermark as WatermarkPostType;
use EasyWatermark\Traits\Hookable;
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

		register_activation_hook( EW_FILE_PATH, [ Installer::class, 'activate' ] );
		register_deactivation_hook( EW_FILE_PATH, [ Installer::class, 'deactivate' ] );
		register_uninstall_hook( EW_FILE_PATH, [ Installer::class, 'uninstall' ] );

		$this->hook();

		new WatermarkPostType();
		new Assets();

		$this->setup_metaboxes();

	}

	/**
	 * Creates metabox objects
	 *
	 * @return  void
	 */
	private function setup_metaboxes() {
		new Metaboxes\Submitdiv();
		new Metaboxes\WatermarkContent();
		new Metaboxes\TextOptions();
		new Metaboxes\Alignment();
		new Metaboxes\Scaling();
		new Metaboxes\ApplyingRules();
	}

	/**
	 * Initiates plugin
	 *
	 * @action  init
	 *
	 * @return  void
	 */
	public function init() {

		$last_version = get_option( $this->slug . '-version' );
		if ( $this->version !== $last_version ) {
			// Version has changed. Update.
			Installer::update( $last_version );
		}
	}

	/**
	 * Returns plugin name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Returns plugin slug
	 *
	 * @return string
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * Returns plugin version
	 *
	 * @return string
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}
}
