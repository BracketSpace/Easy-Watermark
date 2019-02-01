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
	 * @param  object $post  current pot
	 * @return void
	 */
	public function content( $post ) {
		$watermark = Watermark::get( $post );

		echo new View( 'edit-screen/metaboxes/' . $this->id, array_merge( [
			'available_image_sizes' => Image::getAvailableSizes(),
			'available_mime_types'  => Image::getAvailableMimeTypes(),
			'available_post_types'  => get_post_types( [ 'public' => true ], 'objects' ),
		], $watermark->get_params() ) );
	}
}
