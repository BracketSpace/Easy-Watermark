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
class Submitdiv extends Metabox {

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

	/**
	 * Renders metabox content
	 *
	 * @param  object  $post  current pot
	 * @return void
	 */
	public function content( $post ) {
		echo new View( 'edit-screen/metaboxes/submitdiv', [
			'post'  => $post
		] );
	}
}
