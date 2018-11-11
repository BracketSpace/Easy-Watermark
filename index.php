<?php
/*
Plugin Name:	Easy Watermark
Description:	This plugin can automatically add image and text watermark to pictures as they are uploaded to wordpress media library. You can also watermark existing images manually (all at once or an every single image). Watermark image can be a png, gif (alpha channel supported in both cases) or jpg. It's also possibile to set watermark opacity (doesn't apply to png with alpha channel). For text watermark you can select font, set color, size, angel and opacity.
Version:		0.7.0
Author:		BracketSpace
Author URI:	https://bracketspace.com/
License:		GPLv2 or later
License URI:	http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: easy-watermark
*/

// Define flag, that we have plugin loaded
define('EASY_WATERMARK', true);

define('EWDS', DIRECTORY_SEPARATOR);
define('EWBASE', dirname(__FILE__));
define('EWLIB', EWBASE . EWDS . 'lib');
define('EWCLASSES', EWBASE . EWDS . 'classes');
define('EWVIEWS', EWBASE . EWDS . 'views');

// Require all needed files
require_once EWCLASSES . EWDS . 'class-ew-pluggable.php';
require_once EWCLASSES . EWDS . 'class-ew-plugin-core.php';
require_once EWCLASSES . EWDS . 'class-ew-plugin.php';
require_once EWCLASSES . EWDS . 'class-ew-settings.php';
require_once EWLIB . EWDS . 'EasyWatermark.php';
require_once EWBASE . EWDS . 'freemius.php';

// Initiate plugin
EW_Plugin::init();
