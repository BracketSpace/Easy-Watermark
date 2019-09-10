<?php
/**
 * Backupper Factory
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Backup;

use EasyWatermark\Core\Manager as AbstractManager;
use EasyWatermark\Settings\Settings;
use EasyWatermark\Settings\Section;
use EasyWatermark\Settings\Fields\SwitchField;
use EasyWatermark\Settings\Fields\Dropdown;
use EasyWatermark\Traits\Hookable;
use underDEV\Utils\Singleton;
use WP_Error;

/**
 * BackupperFactory class
 */
class Manager extends AbstractManager {

	use Hookable;

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

		$this->hook();

		$backuppers = [
			'local' => [
				'label' => __( 'Local Backup', 'easy-watermark' ),
				'class' => 'EasyWatermark\\Backup\\LocalBackupper',
			],
		];

		$this->default_classes = apply_filters( 'easy-watermark/available-backuppers', $backuppers );

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

	/**
	 * Registers settings section
	 *
	 * @action easy-watermark/settings/register
	 *
	 * @param  Settings $settings Settings object.
	 * @return void
	 */
	public function register_settings_section( $settings ) {
		$settings->add_section( new Section( __( 'Backup', 'easy-watermark' ), 'backup' ) );
	}

	/**
	 * Registers settings fields
	 *
	 * @action easy-watermark/settings/register/backup
	 *
	 * @param  Section $section Settings section.
	 * @return void
	 */
	public function register_settings_fields( $section ) {

		$section->add_field( new SwitchField( [
			'label'   => esc_html__( 'Enable backup', 'easy-watermark' ),
			'slug'    => 'backup',
			'default' => true,
			'layout'  => 'one-column',
			'toggle'  => 'backup',
		] ) );

		$backuppers = $this->get_available_objects();
		$options    = [];

		foreach ( $backuppers as $backupper => $details ) {
			$options[ $backupper ] = $details['label'];
		}

		$default = array_key_exists( 'local', $backuppers ) ? 'local' : null;

		$section->add_field( new Dropdown( [
			'label'   => esc_html__( 'Backupper', 'easy-watermark' ),
			'slug'    => 'backupper',
			'options' => $options,
			'default' => $default,
			'group'   => 'backup',
		] ) );

	}
}
