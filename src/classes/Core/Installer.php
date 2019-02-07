<?php
/**
 * Installer class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Core;

/**
 * Helper class providing install, uninstall, update methods
 */
class Installer {
	/**
	 * Activates plugin
	 *
	 * @return void
	 */
	public static function activate() {
		$version = get_option( Plugin::get()->get_slug() . '-version', false );

		if ( ! $version ) {
			// First activation.
			self::install();
		}

		$admin = get_role( 'administrator' );

		$admin->add_cap( 'edit_watermark' );
		$admin->add_cap( 'read_watermark' );
		$admin->add_cap( 'delete_watermark' );
		$admin->add_cap( 'edit_watermarks' );
		$admin->add_cap( 'edit_others_watermarks' );
		$admin->add_cap( 'publish_watermarks' );
		$admin->add_cap( 'read_private_watermarks' );
		$admin->add_cap( 'apply_watermark' );

		$editor = get_role( 'editor' );

		$editor->add_cap( 'edit_watermark' );
		$editor->add_cap( 'read_watermark' );
		$editor->add_cap( 'delete_watermark' );
		$editor->add_cap( 'edit_watermarks' );
		$editor->add_cap( 'edit_others_watermarks' );
		$editor->add_cap( 'publish_watermarks' );
		$editor->add_cap( 'read_private_watermarks' );
		$editor->add_cap( 'apply_watermark' );

		$author = get_role( 'author' );

		$author->add_cap( 'edit_watermark' );
		$author->add_cap( 'read_watermark' );
		$author->add_cap( 'delete_watermark' );
		$author->add_cap( 'edit_watermarks' );
		$author->add_cap( 'publish_watermarks' );
		$author->add_cap( 'apply_watermark' );

	}

	/**
	 * Deactivates plugin
	 *
	 * @return void
	 */
	public static function deactivate() {

	}

	/**
	 * Installs plugin
	 * This method is called on the first activation
	 *
	 * @return void
	 */
	public static function install() {
	}

	/**
	 * Uninstalls plugin
	 *
	 * @return void
	 */
	public static function uninstall() {

		delete_option( Plugin::get()->get_slug() . '-version' );

		$roles = get_editable_roles();

		foreach ( $roles as $role => $details ) {
			$role = get_role( $role );

			$role->remove_cap( 'edit_watermark' );
			$role->remove_cap( 'read_watermark' );
			$role->remove_cap( 'delete_watermark' );
			$role->remove_cap( 'edit_watermarks' );
			$role->remove_cap( 'edit_others_watermarks' );
			$role->remove_cap( 'publish_watermarks' );
			$role->remove_cap( 'read_private_watermarks' );
			$role->remove_cap( 'apply_watermark' );
		}

	}

	/**
	 * Updates plugin
	 *
	 * @param  string $from previous active version.
	 * @return void
	 */
	public static function update( $from ) {
	}
}
