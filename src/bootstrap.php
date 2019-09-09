<?php
/**
 * Plugin bootstrap file
 *
 * @package easy-watermark
 */

namespace EasyWatermark;

use EasyWatermark\Core\Plugin as EasyWatermark;

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
	$ew_error( 'If you are a developer, please run: `<code>composer install</code>`. Otherwies contact us for help.', 'The plugin vendors are missing.' );
}

/**
 * Require composer autoload
 */
require $autoloader;

/**
 * Requirements check
 */
$requirements = new \underDEV_Requirements( __( 'Easy Watermark', 'easy-watermark' ), [
	'php'            => '5.6.0',
	'wp'             => '4.6',
	'php_extensions' => [ 'gd' ],
] );

if ( ! $requirements->satisfied() ) {
	add_action( 'admin_notices', array( $requirements, 'notice' ) );
	return;
}

/**
 * Require Freemius integration file
 */
require 'inc/freemius.php';

$plugin = EasyWatermark::get();
