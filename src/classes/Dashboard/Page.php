<?php
/**
 * Settings class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Dashboard;

use EasyWatermark\Core\View;
use EasyWatermark\Traits\Hookable;

/**
 * Settings class
 */
class Page {

	use Hookable;

	/**
	 * Page hook
	 *
	 * @var string
	 */
	private $page_hook;

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->hook();

		new Watermarks( $this );
		new Settings( $this );
		new Permissions( $this );

	}

	/**
	 * Adds options page
	 *
	 * @action admin_menu
	 *
	 * @return void
	 */
	public function add_options_page() {

		$this->page_hook = add_management_page(
			__( 'Easy Watermark', 'easy-watermark' ),
			__( 'Easy Watermark', 'easy-watermark' ),
			'manage_options',
			'easy-watermark',
			[ $this, 'page_content' ]
		);

	}

	/**
	 * Returns page hook
	 *
	 * @return string
	 */
	public function get_page_hook() {
		return $this->page_hook;
	}

	/**
	 * Displats options page content
	 *
	 * @return void
	 */
	public function page_content() {

		$tabs = $this->get_tabs();

		reset( $tabs );

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : key( $tabs );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo new View( 'dashboard/wrap', [
			'tabs'        => $tabs,
			'current_tab' => $current_tab,
		] );
		// phpcs:enable

	}

	/**
	 * Returns tabs array
	 *
	 * @return array
	 */
	private function get_tabs() {
		return apply_filters( 'easy-watermark/dashboard/tabs', [] );
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}
}
