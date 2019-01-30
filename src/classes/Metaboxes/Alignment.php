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
}
