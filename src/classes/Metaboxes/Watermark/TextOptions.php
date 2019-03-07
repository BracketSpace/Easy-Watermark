<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes\Watermark;

use EasyWatermark\Metaboxes\WatermarkMetabox;

use EasyWatermark\Core\View;
use EasyWatermark\Helpers\Text;
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
	 * Renders metabox content
	 *
	 * @param  object $post Current post.
	 * @return void
	 */
	public function content( $post ) {
		$watermark = Watermark::get( $post );

		// phpcs:ignore
		echo new View( 'edit-screen/metaboxes/' . $this->post_type . '/' . $this->id, array_merge( [
			'available_fonts' => Text::get_available_fonts(),
		], $watermark->get_params() ) );
	}
}
