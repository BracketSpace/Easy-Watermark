<?php
/**
 * Function for GD library test
 *
 * @package easy-watermark
 */

/**
 * Check if GD library is enabled.
 */
return function( $comparsion, $r ) {
	if ( true !== $comparsion ) {
		return;
	}

	if ( ! extension_loaded( 'gd' ) || ! function_exists( 'gd_info' ) ) {
		$r->add_error( __( 'GD library installed and activated', 'easy-watermark' ) );
	}
};
