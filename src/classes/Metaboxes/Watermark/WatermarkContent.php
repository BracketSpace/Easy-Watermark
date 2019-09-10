<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes\Watermark;

use EasyWatermark\Metaboxes\WatermarkMetabox;
use EasyWatermark\Watermark\Watermark;

/**
 * Metabox class
 */
class WatermarkContent extends WatermarkMetabox {

	/**
	 * Inits metabox
	 *
	 * @return void
	 */
	public function init() {
		$this->id    = 'watermark-content';
		$this->title = __( 'Watermark' );
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

		$attachment = get_post( $watermark->attachment_id );

		if ( ! $attachment ) {
			$watermark->attachment_id = null;
			$watermark->url           = null;
			$watermark->mime_type     = null;
		}

		return $watermark->get_params();
	}
}
