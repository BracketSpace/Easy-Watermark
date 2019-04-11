<?php
/**
 * Legacy file for updating from previous version
 *
 * @package easy-watermark
 */

/**
 * Activates plugin with new file, deactivates the old file.
 */
add_action( 'admin_init', function() {

	$new_file = dirname( __FILE__ ) . '/easy-watermark.php';

	deactivate_plugins( __FILE__ );
	activate_plugin( $new_file, $_SERVER['REQUEST_URI'] ); //phpcs:ignore

	// Remove this file after new plugin activtion.
	unlink( __FILE__ );
} );
