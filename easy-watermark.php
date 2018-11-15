<?php
/**
 * Plugin Name:     Easy Watermark
 * Description:     This plugin can automatically add image and text watermark to pictures as they are uploaded to wordpress media library. You can also watermark existing images manually (all at once or an every single image). Watermark image can be a png, gif (alpha channel supported in both cases) or jpg. It's also possibile to set watermark opacity (doesn't apply to png with alpha channel). For text watermark you can select font, set color, size, angel and opacity.
 * Author:          BracketSpace
 * Author URI:      https://bracketspace.com/
 * Text Domain:     easy-watermark
 * Domain Path:     /languages
 * Version:         1.0.0
 * License:			    GPLv2 or later
 *
 * @package         easy-watermark
 */

/**
 * Base dir
 */
define( 'EW_BASE', dirname( __FILE__) );

/**
 * Require bootstrap file
 */
require 'src/bootstrap.php';
