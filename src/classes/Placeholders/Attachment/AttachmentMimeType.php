<?php
/**
 * Abstract placeholder
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders\Attachment;

use EasyWatermark\Placeholders\Abstracts\StringPlaceholder;

/**
 * Abstract placeholder
 */
class AttachmentMimeType extends StringPlaceholder {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->slug    = 'attachment_mime_type';
		$this->name    = __( 'Attachment MIME type', 'easy-watermark' );
		$this->example = 'image/jpeg';

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
		return $resolver->get_attachment()->post_mime_type;
	}
}
