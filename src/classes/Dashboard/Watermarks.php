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
		// phpcs:disable WordPress.Security
		if ( isset( $_GET['deleted'] ) ) {
			echo new View( 'notices/success', [
				'message' => esc_html__( 'Watermark has been deleted.', 'easy-watermark' ),
			] );
		}
		// phpcs:enable
	}

	/**
	 * Prepares arguments for view
	 *
	 * @filter easy-watermark/dashboard/watermarks/view-args
	 *
	 * @param  array $args View args.
	 * @return array
	 */
	public function view_args( $args ) {
		$watermarks = Watermark::get_all();

		return [
			'watermarks'       => $watermarks,
			'watermarks_count' => wp_count_posts( 'watermark' )->publish,
		];
	}
}
