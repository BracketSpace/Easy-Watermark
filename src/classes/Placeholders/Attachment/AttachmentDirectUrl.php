<?php
/**
 * Abstract placeholder
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders\Attachment;

use EasyWatermark\Placeholders\Abstracts\UrlPlaceholder;

/**
 * Abstract placeholder
 */
class AttachmentDirectUrl extends UrlPlaceholder {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->slug    = 'attachment_direct_url';
		$this->name    = __( 'Attachment direct URL', 'easy-watermark' );
		$this->example = __( 'http://example.com/wp-content/uploads/2018/02/forest-landscape.jpg', 'easy-watermark' );

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
		return wp_get_attachment_url( $resolver->get_attachment()->ID );
	}
}
