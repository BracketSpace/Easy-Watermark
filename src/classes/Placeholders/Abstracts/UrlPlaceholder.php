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
abstract class UrlPlaceholder extends Placeholder {

	/**
	 * Value type
	 *
	 * @var string
	 */
	protected $value_type = 'url';

	/**
	 * Checks if the value is the correct type
	 *
	 * @param  mixed $value placeholder value.
	 * @return boolean
	 */
	public function validate( $value ) {
		return empty( $value ) || filter_var( $value, FILTER_VALIDATE_URL ) !== false;
	}

	/**
	 * Sanitizes the merge tag value
	 *
	 * @param  mixed $value placeholder value.
	 * @return mixed        sanitized value
	 */
	public function sanitize( $value ) {
		return esc_url( $value );
	}
}
