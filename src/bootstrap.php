<?php
/**
 * Plugin bootstrap file
 *
 * @package easy-watermark
 */

namespace EasyWatermark;

use EasyWatermark\Core\Plugin as EasyWatermark;
use EasyWatermark\Vendor\Micropackage\Requirements\Requirements;
use const EW_DIR_PATH;

/**
 * Helper function for startup errors
 *
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$ew_error = function( $message, $subtitle = '', $title = '' ) {
	$title   = $title ?: __( 'Easy Watermark &rsaquo; Error', 'easy-watermark' );
	$footer  = '<a href="https://wordpress.org/support/plugin/easy-watermark">Support </a>';
	$message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
	wp_die( $message, $title ); // phpcs:ignore
};

require EW_DIR_PATH . '/src/inc/functions.php';

/**
 * Composer autoloader file
 */
$autoloader = EW_DIR_PATH . '/vendor/autoload.php';

/**
 * Check if the composer vendors are installed
 */
if ( ! file_exists( $autoloader ) ) {
	$title       = esc_html__( 'Easy Watermark &rsaquo; Error', 'easy-watermark' );
	$support_url = 'https://wordpress.org/support/plugin/easy-watermark';

	wp_die( sprintf(
		'<h1>%s<br><small>%s</small></h1><p>%s</p><p>%s</p>',
		$title, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		esc_html__( 'The plugin vendors are missing.', 'easy-watermark' ),
		esc_html__( 'If you are a developer, please run: `<code>composer install</code>`. Otherwies contact us for help.', 'easy-watermark' ),
		sprintf(
			'<a href="%s">%s</a>',
			esc_url( $support_url ),
			esc_html__( 'Support', 'easy-watermark' )
		)
	), $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Require composer autoload
 */
require $autoloader;

/**
 * Requirements check
 */
$requirements = new Requirements( __( 'Easy Watermark', 'easy-watermark' ), [
	'php'            => '5.6.0',
	'wp'             => '5.0',
	'php_extensions' => [ 'gd' ],
] );

if ( ! $requirements->satisfied() ) {
	$requirements->print_notice();
	return;
}

$plugin = EasyWatermark::get();
