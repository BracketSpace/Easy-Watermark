<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes;

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
}
