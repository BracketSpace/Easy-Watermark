<?php
/**
 * Backupper Factory
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Backup;

use EasyWatermark\Core\Manager as AbstractManager;
use underDEV\Utils\Singleton;
use WP_Error;

/**
 * BackupperFactory class
 */
class Manager extends AbstractManager {

	/**
	 * Parent class for created objects
	 *
	 * @var string
	 */
	protected $interface = 'EasyWatermark\Backup\BackupperInterface';
	/**
	 * Constructor
	 */
	protected function __construct() {

		$backuppers = [
			'local' => [
				'label' => __( 'Local Backup', 'easy-watermark' ),
				'class' => 'EasyWatermark\\Backup\\LocalBackupper',
			],
		];

		$this->default_classes = apply_filters( 'easy_watermark/available_backuppers', $backuppers );

		$this->error_messages = [
			/* translators: %s: class name. */
			'invalid_class'           => __( 'Backupper class "%s" does not exist.' ),
			/* translators: %1$s: child class name, %2$s: parent class name. */
			'invalid_class_interface' => __( 'Backupper "%1$s" must implement %2$s interface.' ),
			/* translators: %s: object type. */
			'invalid_type'            => __( 'Backupper of type "%s" cannot be created.' ),
		];

		parent::__construct();
	}
}
