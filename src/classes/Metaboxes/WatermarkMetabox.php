<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes;

use EasyWatermark\Traits\Hookable;
use EasyWatermark\Watermark\Watermark;

/**
 * Metabox class
 */
abstract class WatermarkMetabox extends Metabox {

	use Hookable;

	/**
	 * Whether to initially hide metabox
	 *
	 * @var  bool
	 */
	protected $hide = true;

	/**
	 * Post type
	 *
	 * @var  string
	 */
	protected $post_type = 'watermark';

	/**
	 * Metabox setup
	 *
	 * @action do_meta_boxes
	 *
	 * @return void
	 */
	public function setup() {
		global $post;

		if ( $post && ( 2 > $this->get_watermarks_count() || 'publish' === $post->post_status ) ) {
			parent::setup();
		}
	}

	/**
	 * Prepares params for metabox view
	 *
	 * @param  array  $params Params.
	 * @param  object $post Current post.
	 * @return array
	 */
	public function prepare_params( $params, $post ) {
		$watermark = Watermark::get( $post );

		return array_merge( $params, $watermark->get_params() );
	}

	/**
	 * Returns watermarks count
	 *
	 * @return object
	 */
	public function get_watermarks_count() {
		return wp_count_posts( 'watermark' )->publish;
	}
}
