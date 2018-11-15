<?php
/**
 * Core plugin class
 *
 * @package talentfunl
 */

 namespace EasyWatermark\Core;

 use EasyWatermark\Traits\Hookable;
 use underDEV\Utils\Singleton;

/**
 * Main plugin class
 */
class Plugin extends Singleton {
	use Hookable;

	/**
	 * Main plugin class
	 */
	protected function __construct() {

	}
}
