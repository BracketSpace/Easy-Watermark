<?php
/**
 * Settings Field
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Settings;

use EasyWatermark\Core\View;

/**
 * Field class
 */
abstract class Field {

	/**
	 * Field label
	 *
	 * @var string
	 */
	protected $label;

	/**
	 * Field slug
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Array of params
	 *
	 * @var array
	 */
	protected $params = [];

	/**
	 * Field value
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * Section
	 *
	 * @var Section
	 */
	protected $section;

	/**
	 * Constructor
	 *
	 * @param string $label   Field label.
	 * @param string $slug    Field slug.
	 * @param string $default Default value.
	 */
	public function __construct( $label, $slug = null, $default = null ) {
		if ( is_array( $label ) ) {
			$params = $label;

			if ( isset( $params['label'] ) ) {
				$label = $params['label'];
				unset( $params['label'] );
			}

			if ( isset( $params['slug'] ) ) {
				$slug = $params['slug'];
				unset( $params['slug'] );
			}

			$this->set( $params );
		} elseif ( $default ) {
			$this->set( 'default', $default );
		}

		$this->set_label( $label );
		$this->set_slug( is_string( $slug ) ? $slug : sanitize_title( $label ) );
	}

	/**
	 * Sets name
	 *
	 * @param string $label Field label.
	 * @return void
	 */
	public function set_label( $label ) {
		$this->label = $label;
	}

	/**
	 * Gets name
	 *
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * Sets slug
	 *
	 * @param string $slug Field slug.
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
	 * Sets param
	 *
	 * @param  mixed  $key   Param key.
	 * @param  string $value Param value.
	 * @return void
	 */
	public function set( $key, $value = null ) {
		if ( null === $value && is_array( $key ) ) {
			$params = $key;

			foreach ( $params as $key => $value ) {
				$this->set( $key, $value );
			}
		} else {
			$this->params[ $key ] = $value;
		}
	}

	/**
	 * Gets param value
	 *
	 * @param  string $key     Param key.
	 * @param  string $default Default param value.
	 * @return string
	 */
	public function get( $key, $default = null ) {
		return isset( $this->params[ $key ] ) ? $this->params[ $key ] : $default;
	}

	/**
	 * Sets value
	 *
	 * @param string $value Field value.
	 * @return void
	 */
	public function set_value( $value ) {
		$this->value = $value;
	}

	/**
	 * Gets value
	 *
	 * @return string
	 */
	public function get_value() {
		return null !== $this->value ? $this->value : $this->get( 'default' );
	}

	/**
	 * Gets layout
	 *
	 * @return string
	 */
	public function get_layout() {
		return $this->get( 'layout', 'two-column' );
	}

	/**
	 * Sets section
	 *
	 * @param Section $section Section instance.
	 * @throws \Exception If section is not instance of Section class.
	 * @return void
	 */
	public function set_section( $section ) {
		if ( ! $section instanceof Section ) {
			/* translators: section variable type */
			throw new \Exception( sprintf( __( 'Section must be an instance of EasyWatermark\\Settings\\Section. %s given', 'easy-watermark' ), gettype( $section ) ) );
		}

		$this->section = $section;
	}

	/**
	 * Gets field id
	 *
	 * @return string
	 */
	public function get_id() {
		return "ew-field-{$this->section->get_slug()}-{$this->get_slug()}";
	}

	/**
	 * Gets field name
	 *
	 * @return string
	 */
	public function get_name() {
		return "{$this->section->get_option_key()}[{$this->section->get_slug()}][{$this->get_slug()}]";
	}

	/**
	 * Renders Field
	 *
	 * @return void
	 */
	public function render() {
		$layout = $this->get_layout();

		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo new View( "dashboard/settings/field-{$layout}", [
			'field' => $this,
		] );
		// phpcs:enable
	}

	/**
	 * Renders Field
	 *
	 * @return string
	 */
	abstract public function render_field();

	/**
	 * Sanitizes value
	 *
	 * @param  mixed $value Field value to sanitize.
	 * @return mixed
	 */
	abstract public function sanitize( $value );
}
