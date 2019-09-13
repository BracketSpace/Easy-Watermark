<?php
/**
 * Auto Watermark switch class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Features;

use EasyWatermark\Core\View;
use EasyWatermark\Traits\Hookable;

/**
 * Auto Watermark switch class
 */
class AutoWatermarkSwitch {

	use Hookable;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->hook();
	}

	/**
	 * Displays switch to turn off auto watermarking in upload ui
	 *
	 * @action pre-plupload-upload-ui
	 *
	 * @return void
	 */
	public function pre_plupload_upload_ui() {

		if ( ! is_admin() || 'media' !== get_current_screen()->id ) {
			// phpcs:ignore
			echo new View( 'upload/switch' );
		}

	}

	/**
	 * Displays notice about Auto Watermark feature
	 *
	 * @action admin_notices
	 *
	 * @return void
	 */
	public function admin_notices() {

		if ( 'media' === get_current_screen()->id ) {
			// phpcs:ignore
			echo new View( 'notices/auto-watermark-warning' );
		}

	}
}
