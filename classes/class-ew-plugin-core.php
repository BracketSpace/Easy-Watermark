<?php
/**
 * @copyright:	Wojtek Szałkiewicz
 * @license:	GPLv2 or later
 * 
 * This class is a base class for wordpress plugins.
 * It's a part of package in which you found it.
 * See readme.txt for more information.
 */

class EW_Plugin_Core extends EW_Pluggable
{
	/**
	 * @var string  plugin name
	 */
	protected static $pluginName = 'Easy Watermark';

	/**
	 * @var string  plugin slug used in setting names etc.
	 */
	protected static $pluginSlug = 'easy-watermark';

	/**
	 * @var string  plugin version
	 */
	protected static $version = '0.6.0';

	/**
	 * @var string	plugin class name
	 */
	protected static $className = 'EW_Plugin';

	/**
	 * @var string  main plugin file
	 */
	protected static $mainFile = 'index.php';

	/**
 	 * Initiates plugin by creating an object of inheriting class,
 	 * registers activation and uninstall hooks,
	 * checks version and executes upgrade function if needed.
	 *
	 * @return object
	 */
	public static function init(){
		// Register install and uninstall methods
		register_activation_hook(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . self::$mainFile, array(self::$className, 'install'));
		register_uninstall_hook(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . self::$mainFile, array(self::$className, 'uninstall'));

		$version = get_option(self::$pluginSlug . '-version');
		if(!empty($version) & version_compare($version, self::$version, '<')){
			// Version from database is lower than current, upgrade...
			self::upgrade($version);
		}

		// Load plugin textdomain
		load_plugin_textdomain(self::$pluginSlug, false, '/'.self::$pluginSlug.'/languages');

		// Create object of plugin class (inheritign this one)
		$className = self::$className;
		return new $className();
	}

	/**
 	 * Returns plugin name
	 *
	 * @return string
	 */
	public static function getName(){
		return self::$pluginName;
	}

	/**
 	 * Returns plugin slug
	 *
	 * @return string
	 */
	public static function getSlug(){
		return self::$pluginSlug;
	}


	/**
 	 * Returns plugin version
	 *
	 * @return string
	 */
	public static function getVersion(){
		return self::$version;
	}

	/**
 	 * Method run when activating plugin
	 *
	 * @return void
	 */
	public static function install(){}

	/**
 	 * Method run when removing plugin
	 *
	 * @return void
	 */
	public static function uninstall(){}

	/**
 	 * Method run when plugin version stored in WP options
	 * is lower than current version.
	 *
	 * @param  string  previously installed version
	 * @return void
	 */
	protected static function upgrade($version){}
}
