<?php
/**
 * Abstract placeholder
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders\Abstracts;

/**
 * Abstract placeholder
 */
abstract class Placeholder {

	/**
	 * Code pattern
	 *
	 * @var string
	 */
	protected $code_pattern = '%%%s%%';

	/**
	 * Slug
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Example
	 *
	 * @var string
	 */
	protected $example;

	/**
	 * Resolved value
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * Value type
	 *
	 * @var string
	 */
	protected $value_type;

	/**
	 * Does this placeholder need reset?
	 *
	 * @var boolean
	 */
	protected $resetable = false;

	/**
	 * Returns placeholder slug
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Returns placeholder name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Returns placeholder example
	 *
	 * @return string
	 */
	public function get_example() {
		return $this->example;
	}

	/**
	 * Returns placeholder value type
	 *
	 * @return string
	 */
	public function get_value_type() {
		return $this->value_type;
	}

	/**
	 * Returns placeholder code
	 *
	 * @return string
	 */
	public function get_code() {
		return sprintf( $this->code_pattern, $this->slug );
	}

	/**
	 * Tells whether placeholder is already resolved
	 *
	 * @return boolean
	 */
	public function is_resolved() {
		return ( null !== $this->value );
	}

	/**
	 * Tells whether placeholder is resetable
	 *
	 * @return boolean
	 */
	public function is_resetable() {
		return $this->resetable;
	}

	/**
	 * Tells whether placeholder is valid
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return boolean
	 */
	public function is_valid( $resolver ) {
		return true;
	}

	/**
	 * Resets resolved value
	 *
	 * @return void
	 */
	public function reset() {

		if ( $this->is_resetable() ) {
			$this->value = null;
			$this->data  = [];
		}

	}

	/**
	 * Returns resolved value
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return mixed
	 */
	public function get_value( $resolver ) {

		if ( ! $this->is_resolved() ) {
			if ( ! $this->is_valid( $resolver ) ) {
				return $this->get_code();
			}

			$value = $this->resolve( $resolver );

			if ( ! empty( $value ) && ! $this->validate( $value ) ) {
				$error_type = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? E_USER_ERROR : E_USER_NOTICE;
				trigger_error( 'Resolved value is a wrong type', $error_type );
			}

			$this->value = apply_filters( 'easy-watermark/placeholder/resolve', $this->sanitize( $value ) );
		}

		return $this->value;

	}

	/**
	 * Resolves placeholder
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return string
	 */
	abstract public function resolve( $resolver );

	/**
	 * Checks if the value is the correct type
	 *
	 * @param  mixed $value placeholder value.
	 * @return boolean
	 */
	abstract public function validate( $value );

	/**
	 * Sanitizes the merge tag value
	 *
	 * @param  mixed $value placeholder value.
	 * @return mixed        sanitized value
	 */
	abstract public function sanitize( $value );
}
