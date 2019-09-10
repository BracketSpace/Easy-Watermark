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
class AttachmentID extends IntegerPlaceholder {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->slug    = 'attachment_id';
		$this->name    = __( 'Attachment ID', 'easy-watermark' );
		$this->example = '196';

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
		return $resolver->get_attachment()->ID;
	}
}
