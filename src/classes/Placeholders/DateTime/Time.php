<?php
/**
 * Abstract placeholder
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders\DateTime;

use EasyWatermark\Placeholders\Abstracts\StringPlaceholder;

/**
 * Abstract placeholder
 */
class Time extends StringPlaceholder {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->slug    = 'time';
		$this->name    = __( 'Current time', 'easy-watermark' );
		$this->example = date_i18n( get_option( 'time_format' ) );

	}

	/**
	 * Resolves placeholder
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return string
	 */
	public function resolve( $resolver ) {
		return date_i18n( get_option( 'time_format' ) );
	}
}
