<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes\Watermark;

use EasyWatermark\Core\View;
use EasyWatermark\Helpers\Image;
use EasyWatermark\Metaboxes\WatermarkMetabox;
use EasyWatermark\Watermark\Watermark;


/**
 * Metabox class
 */
class ApplyingRules extends WatermarkMetabox {

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
		echo new View( 'edit-screen/metaboxes/' . $this->post_type . '/' . $this->id, array_merge( [
			'available_image_sizes' => Image::get_available_sizes(),
			'available_mime_types'  => Image::get_available_mime_types(),
			'available_post_types'  => get_post_types( [ 'public' => true ], 'objects' ),
		], $watermark->get_params() ) );
	}
}
