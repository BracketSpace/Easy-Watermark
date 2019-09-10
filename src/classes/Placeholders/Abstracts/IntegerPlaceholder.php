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
abstract class IntegerPlaceholder extends Placeholder {

	/**
	 * Value type
	 *
	 * @var string
	 */
	protected $value_type = 'integer';

	/**
	 * Checks if the value is the correct type
	 *
	 * @param  mixed $value placeholder value.
	 * @return boolean
	 */
	public function validate( $value ) {
		return filter_var( (int) $value, FILTER_VALIDATE_INT ) !== false;
	}

	/**
	 * Sanitizes the merge tag value
	 *
	 * @param  mixed $value placeholder value.
	 * @return mixed        sanitized value
	 */
	public function sanitize( $value ) {
		return intval( $value );
	}
}
