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
class Submitdiv extends WatermarkMetabox {

	/**
	 * Inits metabox
	 *
	 * @return void
	 */
	public function init() {
		$this->id       = 'submitdiv';
		$this->title    = __( 'Save' );
		$this->position = 'side';
		$this->hide     = false;
	}

	/**
	 * Metabox setup
	 *
	 * @action do_meta_boxes
	 *
	 * @return void
	 */
	public function setup() {
		remove_meta_box( 'submitdiv', 'watermark', 'side' );
		remove_meta_box( 'slugdiv', 'watermark', 'normal' );

		parent::setup();
	}
}
