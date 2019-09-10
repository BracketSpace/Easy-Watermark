<?php
/**
 * View class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Core;

use underDEV\Utils\Singleton;

/**
 * View class
 */
class Hooks extends Singleton {

	/**
	 * Hooked objects
	 *
	 * @var  array
	 */
	private $objects = [];

	/**
	 * Adds object.
	 *
	 * @param  object $object Object.
	 * @return void
	 */
	public function add_object( $object ) {

		$class_name = get_class( $object );

		if ( ! isset( $this->objects[ $class_name ] ) ) {
			$this->objects[ $class_name ] = [
				'instance' => $object,
				'hooks'    => [],
			];
		}

	}

	/**
	 * Adds hook.
	 *
	 * @param  object $object Object.
	 * @param  array  $data   Hook data.
	 * @return void
	 */
	public function add_hook( $object, $data ) {

		$class_name = get_class( $object );

		if ( ! isset( $this->objects[ $class_name ] ) ) {
			$this->add_object( $object );
		}

		$this->objects[ $class_name ]['hooks'][] = $data;

	}

	/**
	 * Gets hooked objects.
	 *
	 * @return array
	 */
	public function get_hooked_objects() {
		return $this->objects;
	}

	/**
	 * Gets hooked objects.
	 *
	 * @return void
	 */
	public function load_hooks() {
		$hooks_file = EW_DIR_PATH . '/src/inc/hooks.php';

		if ( file_exists( $hooks_file ) ) {
			include_once $hooks_file;
		}
	}
}
