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
class Permissions {

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
	 * @action admin_init
	 *
	 * @return void
	 */
	public function setup_permissions() {

		// phpcs:disable WordPress.Security.ValidatedSanitizedInput
		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'easy-watermark-permissions' ) ) {
			return;
		}

		if ( ! isset( $_REQUEST['permissions'] ) ) {
			return;
		}

		$permissions = $_REQUEST['permissions'];

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

		$redirect_url = add_query_arg( [ 'settings-updated' => true ], $_REQUEST['_wp_http_referer'] );

		wp_safe_redirect( $redirect_url );
		exit;
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
		if ( get_current_screen()->id !== $this->dashboard->get_page_hook() ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['settings-updated'] ) && isset( $_GET['tab'] ) && 'permissions' === $_GET['tab'] ) {
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			echo new View( 'notices/success', [
				'message' => __( 'Permissions saved.', 'easy-watermark' ),
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

		$tabs['permissions'] = __( 'Permissions', 'easy-watermark' );
		return $tabs;

	}

	/**
	 * Displats options page content
	 *
	 * @action easy-watermark/dashboard/content/permissions
	 *
	 * @return void
	 */
	public function permissions_page() {

		$watermarks = Watermark::get_all();

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo new View( 'dashboard/permissions-page', [
			'roles' => $this->get_roles(),
		] );
		// phpcs:enable

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
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}
}
