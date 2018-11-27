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
	}

	/**
	 * Loads admin scripts/styles
	 *
	 * @action admin_enqueue_scripts 30
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_style( 'ew-admin-style' );
	}

	private function asset_url( $type, $file ) {
		return EW_DIR_URL . '/assets/dist/' . $type . '/' . $file;
	}

}
