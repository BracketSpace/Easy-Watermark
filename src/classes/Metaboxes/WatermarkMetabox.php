<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes;

use EasyWatermark\Core\View;
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

		if ( 2 > $this->get_watermarks_count() || 'publish' === $post->post_status ) {
			parent::setup();
		}
	}

	/**
	 * Renders metabox content
	 *
	 * @param  object $post Current post.
	 * @return void
	 */
	public function content( $post ) {
		$watermark = Watermark::get( $post );

		// phpcs:ignore
		echo new View( 'edit-screen/metaboxes/' . $this->post_type . '/' . $this->id, array_merge( [
			'post' => $post,
		], $watermark->get_params() ) );
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
