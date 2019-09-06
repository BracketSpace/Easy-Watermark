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
class Watermarks {

	use Hookable;

	/**
	 * Dashboard page
	 *
	 * @var Page
	 */
	private $dashboard;

	/**
	 * Constructor
	 *
	 * @param Page $dashboard Dashboard page.
	 */
	public function __construct( Page $dashboard ) {

		$this->hook();
		$this->dashboard = $dashboard;

	}

	/**
	 * Display admin notices
	 *
	 * @action admin_notices
	 *
	 * @return void
	 */
	public function admin_notices() {
		if ( get_current_screen()->id !== $this->dashboard->get_page_hook() ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['deleted'] ) && '1' === $_GET['deleted'] ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo new View( 'notices/success', [
				'message' => esc_html__( 'Watermark has been deleted.', 'easy-watermark' ),
			] );
		}
	}

	/**
	 * Adds options page
	 *
	 * @filter easy-watermark/dashboard/tabs 10
	 *
	 * @param  array $tabs Tabs.
	 * @return array
	 */
	public function add_tab( $tabs ) {

		$tabs['watermarks'] = __( 'Watermarks', 'easy-watermark' );
		return $tabs;

	}

	/**
	 * Displats options page content
	 *
	 * @action easy-watermark/dashboard/content/watermarks
	 *
	 * @return void
	 */
	public function watermarks_page() {

		$watermarks = Watermark::get_all();

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo new View( 'dashboard/watermarks-page', [
			'watermarks'       => $watermarks,
			'watermarks_count' => wp_count_posts( 'watermark' )->publish,
		] );
		// phpcs:enable

	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}
}
