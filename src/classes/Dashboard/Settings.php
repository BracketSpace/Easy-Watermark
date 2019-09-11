<?php
/**
 * Settings class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Dashboard;

use EasyWatermark\Core\View;
use EasyWatermark\Settings\Settings as SettingsAPI;

/**
 * Settings class
 */
class Settings extends Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->permission = 'manage_options';
		parent::__construct( __( 'Settings', 'easy-watermark' ), null, 80 );
	}

	/**
	 * Display admin notices
	 *
	 * @action easy-watermark/dashboard/settings/notices
	 *
	 * @return void
	 */
	public function admin_notices() {
		// phpcs:disable WordPress.Security
		if ( isset( $_GET['settings-updated'] ) ) {
			echo new View( 'notices/success', [
				'message' => __( 'Settings saved.', 'easy-watermark' ),
			] );
		}
		// phpcs:enable
	}

	/**
	 * Prepares arguments for view
	 *
	 * @filter easy-watermark/dashboard/settings/view-args
	 *
	 * @param  array $args View args.
	 * @return array
	 */
	public function view_args( $args ) {
		return [
			'sections' => SettingsAPI::get()->get_sections(),
		];
	}
}
