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
	 * Prepares params for metabox view
	 *
	 * @param  array  $params Params.
	 * @param  object $post Current post.
	 * @return array
	 */
	public function prepare_params( $params, $post ) {
		$watermark = Watermark::get( $post );

		return array_merge( $params, $watermark->get_params(), [
			'available_image_sizes' => Image::get_available_sizes(),
			'available_mime_types'  => Image::get_available_mime_types(),
			'available_post_types'  => get_post_types( [ 'public' => true ], 'objects' ),
		] );
	}
}
