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
	 * @var string plugin name
	 */
	private $name = null;

	/**
	 * @var string plugin slug
	 */
	private $slug = null;

	/**
	 * @var string plugin version
	 */
	private $version = null;

	/**
	 * Constructor
	 */
	protected function __construct() {

		$data = \get_file_data( EW_FILE_PATH, [
			'name' => 'Plugin Name',
			'version' => 'Version'
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

	private function setup_metaboxes() {
		new Metaboxes\Submitdiv();
		new Metaboxes\WatermarkContent();
		new Metaboxes\Alignment();
	}

	/**
	 * Initiates plugin
	 *
	 * @action  init
	 *
	 * @return  void
	 */
	public function init() {
		if ( $this->version != ( $lastVersion = get_option( $this->slug . '-version' ) ) ) {
			// Version has changed. Update
			Installer::update( $lastVersion );
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
