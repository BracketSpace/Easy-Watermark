<?php
/**
 * Settings Dropdown Field
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Settings\Fields;

use EasyWatermark\Settings\Field;

/**
 * Dropdown class
 */
class Dropdown extends Field {

	/**
	 * Returns options
	 *
	 * @return array
	 */
	public function get_options() {
		return $this->get( 'options', [] );
	}


	/**
	 * Renders Field
	 *
	 * @return string
	 */
	public function render_field() {
		$options = $this->get_options();
		$value   = $this->get_value();

		foreach ( $options as $option_value => & $option ) {
			$option = sprintf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $option_value ),
				selected( $option_value, $value, false ),
				esc_html( $option )
			);
		}

		$options = implode( '', $options );

		return sprintf( '<select id="%s" name="%s">%s</select>', $this->get_id(), $this->get_name(), $options );
	}

	/**
	 * Sanitizes value
	 *
	 * @param  mixed $value Field value to sanitize.
	 * @return mixed
	 */
	public function sanitize( $value ) {
		$value = sanitize_text_field( wp_unslash( $value ) );

		if ( array_key_exists( $value, $this->get_options() ) ) {
			return $value;
		} else {
			return $this->get( 'default' );
		}
	}
}
