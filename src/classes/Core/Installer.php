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
			 // First activation
			 self::install();
		 }
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
		 delete_option( Plugin::get()->getSlug() . '-version' );
	 }

	 /**
	  * Updates plugin
		*
		* @param  string $from previous active version
		* @return void
		*/
	 public static function update( $from ) {
	 }
 }
