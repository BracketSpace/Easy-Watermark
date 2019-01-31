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
class Scaling extends Metabox {

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
