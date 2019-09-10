<?php
/**
 * Settings Checkbox Field
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Settings\Fields;

use EasyWatermark\Settings\Field;

/**
 * Checkbox class
 */
class Checkbox extends Field {

	/**
	 * Renders Field
	 *
	 * @return string
	 */
	public function render_field() {
		$atts   = checked( $this->get_value(), true, false );
		$toggle = $this->get( 'toggle' );

		if ( $toggle ) {
			$atts .= " data-toggle=\"{$toggle}\"";
		}

		return sprintf(
			'<input type="checkbox" id="%s" name="%s" value="1" %s />',
			esc_attr( $this->get_id() ),
			esc_attr( $this->get_name() ),
			$atts
		);
	}

	/**
	 * Sanitizes value
	 *
	 * @param  mixed $value Field value to sanitize.
	 * @return mixed
	 */
	public function sanitize( $value ) {
		return ( '1' === $value );
	}
}
