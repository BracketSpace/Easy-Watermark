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
		$version = get_option( Plugin::get()->getSlug() . '-version', false );

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

	}

	/**
	 * Deactivates plugin
	 *
	 * @return void
	 */
	public static function deactivate() {

		$admin = get_role( 'administrator' );

		$admin->remove_cap( 'edit_watermark' );
		$admin->remove_cap( 'read_watermark' );
		$admin->remove_cap( 'delete_watermark' );
		$admin->remove_cap( 'edit_watermarks' );
		$admin->remove_cap( 'edit_others_watermarks' );
		$admin->remove_cap( 'publish_watermarks' );
		$admin->remove_cap( 'read_private_watermarks' );

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
		delete_option( Plugin::get()->getSlug() . '-version' );
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
