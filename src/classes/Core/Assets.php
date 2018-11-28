<?php
/**
 * Assets class
 *
 * @package easy-watermark
 */

 namespace EasyWatermark\Core;

 use EasyWatermark\Traits\Hookable;

/**
 * Assets class
 */
class Assets {

	use Hookable;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->hook();
	}

	/**
	 * Registers admin scripts/styles
	 *
	 * @action admin_enqueue_scripts 20
	 *
	 * @return void
	 */
	public function register_admin_scripts() {

		wp_register_style( 'ew-admin-style', $this->asset_url( 'styles', 'easy-watermark.css' ), [], '1.0' );
		wp_register_script( 'ew-admin-script', $this->asset_url( 'scripts', 'easy-watermark.js' ), [ 'jquery' ], '1.0' );

	}

	/**
	 * Loads admin scripts/styles
	 *
	 * @action admin_enqueue_scripts 30
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts() {

		if ( 'watermark' == get_current_screen()->id ) {
			wp_enqueue_style( 'ew-admin-style' );

			wp_enqueue_media();
			wp_enqueue_script( 'ew-admin-script' );
		}

	}

	private function asset_url( $type, $file ) {
		return EW_DIR_URL . '/assets/dist/' . $type . '/' . $file;
	}

}
