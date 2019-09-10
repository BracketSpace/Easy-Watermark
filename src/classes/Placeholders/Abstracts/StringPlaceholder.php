<?php
/**
 * String placeholder class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders\Abstracts;

/**
 * String placeholder class
 */
abstract class StringPlaceholder extends Placeholder {

	/**
	 * Value type
	 *
	 * @var string
	 */
	protected $value_type = 'string';

	/**
	 * Checks if the value is the correct type
	 *
	 * @param  mixed $value placeholder value.
	 * @return boolean
	 */
	public function validate( $value ) {
		return is_string( $value );
	}

	/**
	 * Sanitizes the merge tag value
	 *
	 * @param  mixed $value placeholder value.
	 * @return mixed        sanitized value
	 */
	public function sanitize( $value ) {
		return sanitize_text_field( $value );
	}
}
