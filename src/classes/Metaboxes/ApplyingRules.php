<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes;

use EasyWatermark\Core\View;
use EasyWatermark\Helpers\Image;
use EasyWatermark\Watermark\Watermark;


/**
 * Metabox class
 */
class ApplyingRules extends Metabox {

	/**
	 * Inits metabox
	 *
	 * @return void
	 */
	public function init() {
		$this->id    = 'applying-rules';
		$this->title = __( 'Applying Rules', 'easy-watermark' );
	}

	/**
	 * Renders metabox content
	 *
	 * @param  object $post  current post.
	 * @return void
	 */
	public function content( $post ) {
		$watermark = Watermark::get( $post );

		// phpcs:ignore
		echo new View( 'edit-screen/metaboxes/' . $this->id, array_merge( [
			'available_image_sizes' => Image::get_available_sizes(),
			'available_mime_types'  => Image::get_available_mime_types(),
			'available_post_types'  => get_post_types( [ 'public' => true ], 'objects' ),
		], $watermark->get_params() ) );
	}
}
