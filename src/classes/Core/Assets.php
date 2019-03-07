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

		wp_enqueue_style( 'wp-color-picker' );
		wp_register_style( 'ew-admin-style', $this->asset_url( 'styles', 'easy-watermark.css' ), [], $this->asset_version( 'styles', 'easy-watermark.css' ) );
		wp_register_script( 'ew-admin-script', $this->asset_url( 'scripts', 'easy-watermark.js' ), [ 'jquery', 'wp-color-picker' ], $this->asset_version( 'scripts', 'easy-watermark.js' ), true );

	}

	/**
	 * Loads admin scripts/styles
	 *
	 * @action admin_enqueue_scripts 30
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts() {

		$current_screen = get_current_screen();

		if ( 'watermark' === $current_screen->id ) {
			wp_enqueue_media();
		}

		if ( in_array( $current_screen->id, [ 'watermark', 'attachment', 'settings_page_easy-watermark' ], true ) ) {
			wp_enqueue_style( 'ew-admin-style' );
		}

		if ( in_array( $current_screen->id, [ 'watermark', 'attachment' ], true ) ) {
			wp_enqueue_script( 'ew-admin-script' );

			wp_localize_script( 'ew-admin-script', 'ew', [
				'currentScreen'       => $current_screen->id,
				'genericErrorMessage' => __( 'Something went wrong. Please refresh the page and try again.', 'easy-watermark' ),
			] );
		}

	}

	/**
	 * Returns asset url
	 *
	 * @param  string $type Asset type.
	 * @param  string $file Filename.
	 * @return string
	 */
	private function asset_url( $type, $file ) {
		return EW_DIR_URL . 'assets/dist/' . $type . '/' . $file;
	}

	/**
	 * Returns asset version
	 *
	 * @param  string $type Asset type.
	 * @param  string $file Filename.
	 * @return string|void
	 */
	private function asset_version( $type, $file ) {

		$path = EW_DIR_PATH . 'assets/dist/' . $type . '/' . $file;

		if ( is_file( $path ) ) {
			return filemtime( $path );
		}

	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}

}
