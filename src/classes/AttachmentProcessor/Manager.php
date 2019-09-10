<?php
/**
 * AttachmentProcessor Manager
 *
 * @package easy-watermark
 */

namespace EasyWatermark\AttachmentProcessor;

use EasyWatermark\Core\Manager as AbstractManager;
use underDEV\Utils\Singleton;
use WP_Error;

/**
 * Manager class
 */
class Manager extends AbstractManager {

	/**
	 * Parent class for created objects
	 *
	 * @var string
	 */
	protected $parent_class = 'EasyWatermark\AttachmentProcessor\AttachmentProcessor';

	/**
	 * Constructor
	 */
	protected function __construct() {

		$processors = [
			'gd' => [
				'label' => __( 'GD', 'easy-watermark' ),
				'class' => 'EasyWatermark\\AttachmentProcessor\\AttachmentProcessorGD',
			],
		];

		$this->default_classes = apply_filters( 'easy-watermark/available-processors', $processors );

		$this->error_messages = [
			/* translators: %s: class name. */
			'invalid_class'        => __( 'Attachment processor class "%s" does not exist.' ),
			/* translators: %1$s: child class name, %2$s: parent class name. */
			'invalid_class_parent' => __( 'Attachment processor "%1$s" must extend %2$s.' ),
			/* translators: %s: object type. */
			'invalid_type'         => __( 'Attachment processor of type "%s" cannot be created.' ),
		];

		parent::__construct();
	}
}
