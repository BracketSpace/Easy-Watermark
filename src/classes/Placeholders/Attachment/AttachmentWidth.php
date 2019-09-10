<?php
/**
 * Abstract placeholder
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders\Attachment;

use EasyWatermark\Placeholders\Abstracts\IntegerPlaceholder;

/**
 * Abstract placeholder
 */
class AttachmentWidth extends IntegerPlaceholder {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->slug    = 'attachment_width';
		$this->name    = __( 'Attachment width', 'easy-watermark' );
		$this->example = '640';

	}

	/**
	 * Tells whether placeholder is valid
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return boolean
	 */
	public function is_valid( $resolver ) {
		return (bool) $resolver->get_attachment();
	}

	/**
	 * Resolves placeholder
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return string
	 */
	public function resolve( $resolver ) {
		$meta = wp_get_attachment_metadata( $resolver->get_attachment()->ID );
		return $meta['width'];
	}
}
