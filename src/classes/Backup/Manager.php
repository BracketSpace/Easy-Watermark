<?php
/**
 * Backupper Factory
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Backup;

use underDEV\Utils\Singleton;
use WP_Error;

/**
 * BackupperFactory class
 */
class Manager extends Singleton {

	/**
	 * Backupper instances
	 *
	 * @var array
	 */
	private $backuppers = [];

	/**
	 * Available backuppers
	 *
	 * @var array
	 */
	private $available_backuppers = [];

	/**
	 * Constructor
	 */
	protected function __construct() {

		$this->register_backupper( 'local', __( 'Local Backup', 'easy-watermark' ), 'EasyWatermark\\Backup\\LocalBackupper' );
		$this->register_backupper( 'other', __( 'Other Backup', 'easy-watermark' ), 'EasyWatermark\\Backup\\OtherBackupper' );

		do_action( 'easy_watermark/backupper_manager_init', $this );

	}

	/**
	 * Registers available backupper
	 *
	 * @param  string $type  Backupper type.
	 * @param  string $label Backupper nice name to show in admin area.
	 * @param  string $class Backupper class name.
	 * @return WP_Error|true
	 */
	public function register_backupper( $type, $label, $class ) {

		if ( ! class_exists( $class ) ) {
			/* translators: backupper class name. */
			return new WP_Error( 'invalid_backupper_class', sprintf( __( 'Backupper class "%s" does not exist.' ), $class ) );
		}

		if ( ! in_array( 'EasyWatermark\Backup\BackupperInterface', class_implements( $class ), true ) ) {
			/* translators: backupper class name. */
			return new WP_Error( 'invalid_backupper_class', sprintf( __( 'Backupper class "%s" must implement EasyWatermark\\Backup\\Backupper interface.' ), $class ) );
		}

		$this->available_backuppers[ $type ] = [
			'label' => $label,
			'class' => $class,
		];

		return true;

	}

	/**
	 * Returns Backupper instance of given type
	 *
	 * @param  string $type Backupper type.
	 * @return Backupper|WP_Error
	 */
	public function get_backupper( $type ) {

		if ( ! array_key_exists( $type, $this->backuppers ) ) {
			return $this->create_backupper( $type );
		}

		return $this->backuppers[ $type ];

	}

	/**
	 * Returns Backupper instance of given type
	 *
	 * @param  string $type Backupper type.
	 * @return Backupper
	 */
	private function create_backupper( $type ) {

		if ( ! array_key_exists( $type, $this->available_backuppers ) ) {
			/* translators: backupper type. */
			return new WP_Error( 'invalid_backupper_type', sprintf( __( 'Backupper of type "%s" cannot be created.' ), $type ) );
		}

		$class = $this->available_backuppers[ $type ]['class'];

		return new $class();

	}

	/**
	 * Returns available backuppers
	 *
	 * @return array
	 */
	public function get_available_backuppers() {
		return $this->available_backuppers;
	}
}
