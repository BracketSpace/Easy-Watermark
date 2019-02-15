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
class Scaling extends WatermarkMetabox {

	/**
	 * Inits metabox
	 *
	 * @return void
	 */
	public function init() {
		$this->id    = 'scaling';
		$this->title = __( 'Scaling' );
	}
}
