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
class Dashboard {

	use Hookable;

	/**
	 * Page hook
	 *
	 * @var string
	 */
	private $page_hook;

	/**
	 * Tabs
	 *
	 * @var array
	 */
	private $tabs;

	/**
	 * Current tab
	 *
	 * @var string
	 */
	private $current_tab;

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->hook();

		new Watermarks();
		new Settings();
		new Permissions();
		new Tools();

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
			'apply_watermark',
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

		$current_tab = $this->get_current_tab();
		$tabs        = $this->get_tabs();
		$args        = apply_filters( "easy-watermark/dashboard/{$current_tab}/view-args", [] );
		$content     = new View( "dashboard/pages/{$current_tab}", $args );

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo new View( 'dashboard/wrap', [
			'tabs'        => $tabs,
			'current_tab' => $current_tab,
			'content'     => $content,
		] );
		// phpcs:enable

	}

	/**
	 * Display admin notices
	 *
	 * @action admin_notices
	 *
	 * @return void
	 */
	public function admin_notices() {
		if ( get_current_screen()->id !== $this->get_page_hook() ) {
			return;
		}

		$tab = $this->get_current_tab();

		do_action( 'easy-watermark/dashboard/notices', $tab );
		do_action( "easy-watermark/dashboard/{$tab}/notices" );
	}

	/**
	 * Returns tabs array
	 *
	 * @return array
	 */
	private function get_tabs() {
		if ( ! $this->tabs ) {
			$this->tabs = apply_filters( 'easy-watermark/dashboard/tabs', [] );

			uasort( $this->tabs, function( $a, $b ) {
				return $a['priority'] - $b['priority'];
			} );
		}

		return $this->tabs;
	}

	/**
	 * Returns tabs array
	 *
	 * @return array
	 */
	private function get_current_tab() {
		if ( ! $this->current_tab ) {
			$tabs = $this->get_tabs();

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : null;

			if ( null === $tab || ! array_key_exists( $tab, $tabs ) ) {
				reset( $tabs );
				$tab = key( $tabs );
			}

			$this->current_tab = $tab;
		}

		return $this->current_tab;
	}
}
