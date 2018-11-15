<?php
/**
 * @package easy-watermark
 */

 namespace EasyWatermark;

 use EasyWatermark\Core\Plugin as EasyWatermark;

/**
 * Helper function for startup errors
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$error = function( $message, $subtitle = '', $title = '' ) {
  $title = $title ?: __( 'Easy Watermark &rsaquo; Error', 'easy-watermark' );
	$footer = '<a href="https://wordpress.org/support/plugin/easy-watermark">Support </a>';
  $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
  wp_die( $message, $title );
};

/**
 * Composer autoloader file
 */
$autoloader = dirname( __FILE__ ) . '/../vendor/autoload.php';

/**
 * Check if the composer vendors are installed
 */
if ( ! file_exists( $autoloader ) ) {
	$error( 'If you are a developer, please run: `<code>composer install</code>`. Otherwies contact us for help.', 'The plugin vendors are missing.' );
}

/**
 * Require composer autoload
 */
require $autoloader;

/**
 * Requirements check
 */
$requirements = new \underDEV_Requirements( __( 'Easy Watermark', 'easy-watermark' ), [
	'php'      => '5.4.0',
	'wp'       => '4.6',
	'dochooks' => true,
	'gd'       => true
] );

/**
 * Check if ReflectionObject returns proper docblock comments for methods.
 */
if ( method_exists( $requirements, 'add_check' ) ) {
	$requirements->add_check(	'dochooks', require 'inc/requirements/dochooks.php' );
	$requirements->add_check(	'gd', require 'inc/requirements/gd.php' );
}

if ( ! $requirements->satisfied() ) {
	add_action( 'admin_notices', array( $requirements, 'notice' ) );
	return;
}

$plugin = EasyWatermark::get();

do_action( 'ew_load', $plugin );
