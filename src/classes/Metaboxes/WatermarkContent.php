<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes;

use EasyWatermark\Core\View;
use EasyWatermark\Watermark\Watermark;

/**
 * Metabox class
 */
class WatermarkContent extends Metabox {

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
	 * Renders metabox content
	 *
	 * @param  object  $post  current pot
	 * @return void
	 */
	public function content( $post ) {
		$watermark = Watermark::get( $post );

		echo new View( 'edit-screen/metaboxes/content', $watermark->get_params() );
	}
}
