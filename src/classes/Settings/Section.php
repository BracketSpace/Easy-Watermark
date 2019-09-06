<?php
/**
 * Settings section
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Settings;

use EasyWatermark\Core\View;

/**
 * Section class
 */
class Section {

	/**
	 * Section name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Section slug
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Settings instance
	 *
	 * @var Settings
	 */
	protected $settings;

	/**
	 * Fields collection
	 *
	 * @var array
	 */
	protected $fields = [];

	/**
	 * Constructor
	 *
	 * @param string $name Section name.
	 * @param string $slug Section slug.
	 */
	public function __construct( $name, $slug = null ) {
		$this->set_name( $name );
		$this->set_slug( is_string( $slug ) ? $slug : sanitize_title( $name ) );

		do_action( "easy-watermark/settings/register/{$this->get_slug()}", $this );
	}

	/**
	 * Sets name
	 *
	 * @param string $name Section name.
	 * @return void
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}

	/**
	 * Gets name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Sets slug
	 *
	 * @param string $slug Section slug.
	 * @return void
	 */
	public function set_slug( $slug ) {
		$this->slug = $slug;
	}

	/**
	 * Gets slug
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Sets settings instance
	 *
	 * @param  Settings $settings Settings instance.
	 * @throws \Exception If $settings is not instance of Settings class.
	 * @return void
	 */
	public function set_settings( $settings ) {
		if ( ! $settings instanceof Settings ) {
			/* translators: field variable type */
			throw new \Exception( sprintf( __( 'Settings must be an instance of EasyWatermark\\Settings\\Settings. %s given', 'easy-watermark' ), gettype( $settings ) ) );
		}

		$this->settings = $settings;
	}

	/**
	 * Adds field
	 *
	 * @param Field $field Field instance.
	 * @throws \Exception If field is not instance of Field class.
	 * @return void
	 */
	public function add_field( $field ) {
		if ( ! $field instanceof Field ) {
			/* translators: field variable type */
			throw new \Exception( sprintf( __( 'Field must be an instance of EasyWatermark\\Settings\\Field. %s given', 'easy-watermark' ), gettype( $field ) ) );
		}

		$field->set_section( $this );
		$this->fields[ $field->get_slug() ] = $field;
	}

	/**
	 * Gets field object
	 *
	 * @param  string $slug Field slug.
	 * @return Field|false
	 */
	public function get_field( $slug ) {
		return isset( $this->fields[ $slug ] ) ? $this->fields[ $slug ] : false;
	}

	/**
	 * Gets array of fields
	 *
	 * @return array
	 */
	public function get_fields() {
		return $this->fields;
	}

	/**
	 * Gets option key
	 *
	 * @return string|false
	 */
	public function get_option_key() {
		return $this->settings ? $this->settings->get_option_key() : false;
	}

	/**
	 * Renders section
	 *
	 * @return void
	 */
	public function render() {
		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo new View( 'dashboard/settings/section', [
			'name'   => $this->get_name(),
			'fields' => $this->get_fields(),
		] );
		// phpcs:enable
	}
}
