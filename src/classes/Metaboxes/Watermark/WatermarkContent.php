<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes\Watermark;

use EasyWatermark\Metaboxes\WatermarkMetabox;

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
}
