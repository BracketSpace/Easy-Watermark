<?php
/**
 * Settings class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Core;

use EasyWatermark\Backup\Manager as BackupManager;
use EasyWatermark\Core\View;
use EasyWatermark\Traits\Hookable;
use underDEV\Utils\Singleton;

/**
 * Settings class
 */
class Settings extends Singleton {

	use Hookable;

	/**
	 * Default settings
	 *
	 * @var array
	 */
	private $defaults = [
		'jpeg_quality' => 75,
		'backup'       => true,
		'backupper'    => 'local',
	];

	/**
	 * Settings
	 *
	 * @var array
	 */
	private $settings = [];

	/**
	 * Option key
	 *
	 * @var string
	 */
	private $option_key;

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->hook();

		$this->option_key = Plugin::get()->get_slug() . '-settings';

		$this->settings = wp_parse_args( get_option( $this->option_key ), $this->defaults );

	}

	/**
	 * Adds options page
	 *
	 * @action admin_menu
	 *
	 * @return void
	 */
	public function add_options_page() {

		add_options_page(
			__( 'Easy Watermark', 'easy-watermark' ),
			__( 'Easy Watermark', 'easy-watermark' ),
			'manage_options',
			'ew-settings',
			[ $this, 'settings_page' ]
		);

	}

	/**
	 * Displats options page content
	 *
	 * @return void
	 */
	public function settings_page() {

		$settings = $this->get_settings();

		$status = new View( 'settings/status' );

		$general = new View( 'settings/general', $settings );

		$backup = new View( 'settings/backup', [
			'backup'             => $settings['backup'],
			'selected_backupper' => $settings['backupper'],
			'backuppers'         => BackupManager::get()->get_available_backuppers(),
		] );

		$permissions = new View( 'settings/permissions', [
			'roles' => $this->get_roles(),
		] );

		// phpcs:disable
		echo new View( 'settings-page', [
			'status'      => $status,
			'general'     => $general,
			'backup'     => $backup,
			'permissions' => $permissions,
		] );
		// phpcs:enable

	}

	/**
	 * Registers settings
	 *
	 * @action admin_init
	 *
	 * @return void
	 */
	public function register_settings() {

		register_setting(
			$this->option_key,
			$this->option_key,
			[ $this, 'sanitize_settings' ]
		);

	}

	/**
	 * Sanitizes settings
	 *
	 * @param  array $settings Settings array.
	 * @return array
	 */
	public function sanitize_settings( $settings ) {

		if ( ! is_numeric( $settings['jpeg_quality'] ) ) {
			$settings['jpeg_quality'] = $this->defaults['jpeg_quality'];
		} elseif ( 0 > $settings['jpeg_quality'] ) {
			$settings['jpeg_quality'] = 0;
		} elseif ( 100 < $settings['jpeg_quality'] ) {
			$settings['jpeg_quality'] = 100;
		}

		if ( isset( $settings['backup'] ) && '1' === $settings['backup'] ) {
			$settings['backup'] = true;
		} else {
			$settings['backup'] = false;
		}

		if ( isset( $settings['backupper'] ) ) {
			$settings['backupper'] = sanitize_text_field( $settings['backupper'] );
		}

		$this->setup_permissions( $settings['permissions'] );
		unset( $settings['permissions'] );

		return $settings;

	}

	/**
	 * Sets up role capabilities
	 *
	 * @param  array $permissions Permissions for each role.
	 * @return void
	 */
	private function setup_permissions( $permissions ) {

		$roles = $this->get_roles();

		foreach ( $roles as $role_name => $details ) {

			$role = get_role( $role_name );

			$can_create = ( isset( $permissions[ $role_name ]['create'] ) && '1' === $permissions[ $role_name ]['create'] );
			$can_edit   = ( isset( $permissions[ $role_name ]['edit'] ) && '1' === $permissions[ $role_name ]['edit'] );
			$can_apply  = ( isset( $permissions[ $role_name ]['apply'] ) && '1' === $permissions[ $role_name ]['apply'] );

			$role->add_cap( 'edit_watermark', $can_create );
			$role->add_cap( 'edit_watermarks', $can_create );
			$role->add_cap( 'delete_watermark', $can_create );

			$role->add_cap( 'edit_others_watermarks', $can_edit );
			$role->add_cap( 'delete_others_watermarks', $can_edit );

			$role->add_cap( 'apply_watermark', $can_apply );

		}

	}

	/**
	 * Returns settings array
	 *
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * Returns user roles array array
	 *
	 * @return array
	 */
	public function get_roles() {

		$all_roles = get_editable_roles();

		$roles = [];
		foreach ( $all_roles as $role => $details ) {
			if ( 'administrator' === $role ) {
				continue;
			}

			if ( isset( $details['capabilities']['upload_files'] ) && true === $details['capabilities']['upload_files'] ) {
				$roles[ $role ] = array_merge( $details, [
					'can_create' => ( isset( $details['capabilities']['edit_watermark'] ) && true === $details['capabilities']['edit_watermark'] ),
					'can_edit'   => ( isset( $details['capabilities']['edit_others_watermarks'] ) && true === $details['capabilities']['edit_others_watermarks'] ),
					'can_apply'  => ( isset( $details['capabilities']['apply_watermark'] ) && true === $details['capabilities']['apply_watermark'] ),
				] );
			}
		}

		return $roles;

	}

	/**
	 * Creates settings link on plugins list
	 *
	 * @filter plugin_action_links_easy-watermark/easy-watermark.php
	 *
	 * @param  array  $links Action links.
	 * @param  string $file  Plugin file.
	 * @return array
	 */
	public function plugin_action_links( $links, $file ) {

		return array_merge( [
			'<a href="options-general.php?page=easy-watermark">' . __( 'Settings' ) . '</a>',
		], $links );

	}

	/**
	 * Magic method for accessing settings
	 *
	 * @param  string $key Settings key.
	 * @return mixed
	 */
	public function __get( $key ) {
		if ( array_key_exists( $key, $this->settings ) ) {
			return $this->settings[ $key ];
		}
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}
}
