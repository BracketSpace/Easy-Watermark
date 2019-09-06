<?php
/**
 * Settings class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Dashboard;

use EasyWatermark\Core\View;
use EasyWatermark\Settings\Settings as SettingsAPI;
use EasyWatermark\Traits\Hookable;
use EasyWatermark\Watermark\Watermark;

/**
 * Settings class
 */
class Settings {

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
		if ( isset( $_GET['settings-updated'] ) && isset( $_GET['tab'] ) && 'settings' === $_GET['tab'] ) {
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			echo new View( 'notices/success', [
				'message' => __( 'Settings saved.', 'easy-watermark' ),
			] );
			// phpcs:enable
		}
	}

	/**
	 * Adds options page
	 *
	 * @filter easy-watermark/dashboard/tabs 100
	 *
	 * @param  array $tabs Tabs.
	 * @return array
	 */
	public function add_tab( $tabs ) {

		$tabs['settings'] = __( 'Settings', 'easy-watermark' );
		return $tabs;

	}

	/**
	 * Displats options page content
	 *
	 * @action easy-watermark/dashboard/content/settings
	 *
	 * @return void
	 */
	public function settings_page() {

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo new View( 'dashboard/settings-page', [
			'sections' => SettingsAPI::get()->get_sections(),
		] );
		// phpcs:enable

	}
}
