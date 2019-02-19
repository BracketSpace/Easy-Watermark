<?php
/**
 * AttachmentProcessor Manager
 *
 * @package easy-watermark
 */

namespace EasyWatermark\AttachmentProcessor;

use underDEV\Utils\Singleton;
use WP_Error;

/**
 * Manager class
 */
class Manager extends Singleton {

	/**
	 * AttachmentProcessor instances
	 *
	 * @var array
	 */
	private $processors = [];

	/**
	 * Available processors
	 *
	 * @var array
	 */
	private $available_processors = [];

	/**
	 * Constructor
	 */
	protected function __construct() {

		$this->register_processor( 'gd', __( 'GD', 'easy-watermark' ), 'EasyWatermark\\AttachmentProcessor\\AttachmentProcessorGD' );

		do_action( 'easy_watermark/processor_manager_init', $this );

	}

	/**
	 * Registers available attachment processor
	 *
	 * @param  string $type  AttachmentProcessor type.
	 * @param  string $label AttachmentProcessor nice name to show in admin area.
	 * @param  string $class AttachmentProcessor class name.
	 * @return WP_Error|true
	 */
	public function register_processor( $type, $label, $class ) {

		if ( ! class_exists( $class ) ) {
			/* translators: attachment processor class name. */
			return new WP_Error( 'invalid_processor_class', sprintf( __( 'Attachment processor class "%s" does not exist.' ), $class ) );
		}

		if ( ! is_subclass_of( $class, 'EasyWatermark\AttachmentProcessor\AttachmentProcessor' ) ) {
			/* translators: attachment processor class name. */
			return new WP_Error( 'invalid_processor_class', sprintf( __( 'Attachment processor class "%s" must extend EasyWatermark\\AttachmentProcessor\\AttachmentProcessor class.' ), $class ) );
		}

		$this->available_processors[ $type ] = [
			'label' => $label,
			'class' => $class,
		];

		return true;

	}

	/**
	 * Returns AttachmentProcessor instance of given type
	 *
	 * @param  string $type AttachmentProcessor type.
	 * @return AttachmentProcessor|WP_Error
	 */
	public function get_processor( $type ) {

		if ( ! array_key_exists( $type, $this->processors ) ) {
			return $this->create_processor( $type );
		}

		return $this->processors[ $type ];

	}

	/**
	 * Returns AttachmentProcessor instance of given type
	 *
	 * @param  string $type AttachmentProcessor type.
	 * @return AttachmentProcessor
	 */
	private function create_processor( $type ) {

		if ( ! array_key_exists( $type, $this->available_processors ) ) {
			/* translators: attachment processor type. */
			return new WP_Error( 'invalid_processor_type', sprintf( __( 'Attachment processor of type "%s" cannot be created.' ), $type ) );
		}

		$class = $this->available_processors[ $type ]['class'];

		return new $class();

	}

	/**
	 * Returns available processors
	 *
	 * @return array
	 */
	public function get_available_processors() {
		return $this->available_processors;
	}
}
