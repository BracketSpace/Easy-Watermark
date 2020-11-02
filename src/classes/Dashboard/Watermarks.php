<?php
/**
 * Settings class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Dashboard;

use EasyWatermark\Core\View;
use EasyWatermark\Traits\Hookable;
use EasyWatermark\Watermark\Watermark;

/**
 * Settings class
 */
class Watermarks extends Page {
	use Hookable;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( __( 'Watermarks', 'easy-watermark' ), null, 10 );
	}

	/**
	 * Display admin notices
	 *
	 * @action easy-watermark/dashboard/watermarks/notices
	 *
	 * @return void
	 */
	public function admin_notices() {

		// phpcs:disable WordPress.Security.NonceVerification
		if ( isset( $_GET['deleted'] ) ) {
			View::get( 'notices/success', [
				'message' => esc_html__( 'Watermark has been deleted.', 'easy-watermark' ),
			] )->display();
		}

	}

	/**
	 * Prepares arguments for view
	 *
	 * @filter easy-watermark/dashboard/watermarks/view-args
	 *
	 * @return array
	 */
	public function view_args() {
		return [
			'watermarks' => Watermark::get_all(),
		];
	}
}
