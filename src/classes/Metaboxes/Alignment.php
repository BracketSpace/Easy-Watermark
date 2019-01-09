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
class Alignment extends Metabox {

	/**
	 * Inits metabox
	 *
	 * @return void
	 */
	public function init() {
		$this->id    = 'alignment';
		$this->title = __( 'Alignment' );
	}

	/**
	 * Renders metabox content
	 *
	 * @param  object  $post  current pot
	 * @return void
	 */
	public function content( $post ) {
		$watermark = Watermark::get( $post );

		echo new View( 'edit-screen/metaboxes/alignment', $watermark->get_params() );
	}

}
