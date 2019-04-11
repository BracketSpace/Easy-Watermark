<?php
/**
 * Manager
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Core;

use underDEV\Utils\Singleton;
use WP_Error;

/**
 * Manager class
 */
abstract class Manager extends Singleton {

	/**
	 * Instances
	 *
	 * @var array
	 */
	protected $objects = [];

	/**
	 * Available classes
	 *
	 * @var array
	 */
	protected $available_classes = [];

	/**
	 * Available classes
	 *
	 * @var array
	 */
	protected $default_classes = [];

	/**
	 * Error messages
	 *
	 * @var array
	 */
	protected $error_messages = [];

	/**
	 * Parent class for created objects
	 *
	 * @var string
	 */
	protected $parent_class = false;

	/**
	 * Interface for created objects
	 *
	 * @var string
	 */
	protected $interface = false;

	/**
	 * Constructor
	 */
	protected function __construct() {

		$this->error_messages = array_merge( [
			/* translators: %s: class name. */
			'invalid_class'           => __( 'Class "%s" does not exist.' ),
			/* translators: %1$s: child class name, %2$s: parent class name. */
			'invalid_class_parent'    => __( 'Class "%1$s" must extend %2$s.' ),
			/* translators: %1$s: child class name, %2$s: parent class name. */
			'invalid_class_interface' => __( 'Class "%1$s" must implement %2$s interface.' ),
			/* translators: %s: object type. */
			'invalid_type'            => __( 'Object of type "%s" cannot be created.' ),
		], $this->error_messages );

		foreach ( $this->default_classes as $key => $config ) {
			$this->register( $key, $config['label'], $config['class'] );
		}

	}

	/**
	 * Registers available class
	 *
	 * @param  string $type  Object type.
	 * @param  string $label Object nice name to show in admin area.
	 * @param  string $class Object class name.
	 * @return WP_Error|true
	 */
	public function register( $type, $label, $class ) {

		if ( ! class_exists( $class ) ) {
			return new WP_Error( 'invalid_class', sprintf( $this->error_messages['invalid_class'], $class ) );
		}

		if ( false !== $this->parent_class ) {
			$valid = $this->validate_parent( $class );

			if ( is_wp_error( $valid ) ) {
				return $valid;
			}
		}

		if ( false !== $this->interface ) {
			$valid = $this->validate_interface( $class );

			if ( is_wp_error( $valid ) ) {
				return $valid;
			}
		}

		$this->available_classes[ $type ] = [
			'label' => $label,
			'class' => $class,
		];

		return true;

	}

	/**
	 * Validates class parent
	 *
	 * @param  string $class  Class name.
	 * @return WP_Error|true
	 */
	protected function validate_parent( $class ) {

		if ( ! empty( $this->parent_class ) && ! is_subclass_of( $class, $this->parent_class ) ) {
			return new WP_Error( 'invalid_class_parent', sprintf( $this->error_messages['invalid_class_parent'], $class, $this->parent_class ) );
		}

		return true;

	}

	/**
	 * Validates class interface
	 *
	 * @param  string $class  Class name.
	 * @return WP_Error|true
	 */
	protected function validate_interface( $class ) {

		if ( ! in_array( $this->interface, class_implements( $class ), true ) ) {
			return new WP_Error( 'invalid_class_interface', sprintf( $this->error_messages['invalid_class_interface'], $class, $this->interface ) );
		}

		return true;

	}

	/**
	 * Returns instance of given type
	 *
	 * @param  string $type Object type.
	 * @return Object|WP_Error
	 */
	public function get_object( $type ) {

		if ( ! array_key_exists( $type, $this->objects ) ) {
			return $this->create( $type );
		}

		return $this->objects[ $type ];

	}

	/**
	 * Returns instance of given type
	 *
	 * @param  string $type Object type.
	 * @return Object
	 */
	protected function create( $type ) {

		if ( ! array_key_exists( $type, $this->available_classes ) ) {
			return new WP_Error( 'invalid_type', sprintf( $this->error_messages['invalid_type'], $type ) );
		}

		$class = $this->available_classes[ $type ]['class'];

		return new $class();

	}

	/**
	 * Returns available classes
	 *
	 * @return array
	 */
	public function get_available_objects() {
		return $this->available_classes;
	}
}
