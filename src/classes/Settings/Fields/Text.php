<?php
/**
 * Settings Text Field
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Settings\Fields;

use EasyWatermark\Settings\Field;

/**
 * Text class
 */
class Text extends Field {

	/**
	 * Renders Field
	 *
	 * @return string
	 */
	public function render_field() {
		return sprintf( '<input type="text" id="%s" name="%s" value="%s" />', $this->get_id(), $this->get_name(), $this->get_value() );
	}

	/**
	 * Sanitizes value
	 *
	 * @param  mixed $value Field value to sanitize.
	 * @return mixed
	 */
	public function sanitize( $value ) {
		return sanitize_text_field( wp_unslash( $value ) );
	}
}
