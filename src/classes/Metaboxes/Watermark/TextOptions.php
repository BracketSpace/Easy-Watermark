<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes\Watermark;

use EasyWatermark\Core\View;
use EasyWatermark\Helpers\Text;
use EasyWatermark\Metaboxes\WatermarkMetabox;
use EasyWatermark\Watermark\Watermark;

/**
 * Metabox class
 */
class TextOptions extends WatermarkMetabox {

	/**
	 * Inits metabox
	 *
	 * @return void
	 */
	public function init() {
		$this->id    = 'text-options';
		$this->title = __( 'Text Options' );
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

		$params['available_fonts'] = Text::get_available_fonts();

		return array_merge( $params, $watermark->get_params() );
	}
}
