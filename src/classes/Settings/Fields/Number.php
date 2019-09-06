<?php
/**
 * Settings Number Field
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Settings\Fields;

use EasyWatermark\Settings\Field;

/**
 * Number class
 */
class Number extends Field {

	/**
	 * Renders Field
	 *
	 * @return string
	 */
	public function render_field() {
		$args = [
			'min'  => $this->get( 'min' ),
			'max'  => $this->get( 'max' ),
			'step' => $this->get( 'step' ),
		];

		foreach ( $args as $name => & $value ) {
			if ( null !== $value ) {
				$value = " {$name}=\"{$value}\"";
			}
		}

		$args = implode( ' ', $args );

		return sprintf(
			'<input type="number" id="%s" name="%s" value="%s" %s />',
			esc_attr( $this->get_id() ),
			esc_attr( $this->get_name() ),
			esc_attr( $this->get_value() ),
			$args // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	/**
	 * Sanitizes value
	 *
	 * @param  mixed $value Field value to sanitize.
	 * @return mixed
	 */
	public function sanitize( $value ) {
		$value = sanitize_text_field( wp_unslash( $value ) );

		if ( is_numeric( $value ) ) {
			$min = $this->get( 'min' );
			$max = $this->get( 'max' );

			if ( null !== $min && $value < $min ) {
				$value = $min;
			}

			if ( null !== $max && $value > $max ) {
				$value = $max;
			}

			return $value;
		}
	}
}
