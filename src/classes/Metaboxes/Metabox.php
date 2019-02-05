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
abstract class Metabox {

	use Hookable;

	/**
	 * Metabox id
	 *
	 * @var  string
	 */
	protected $id;

	/**
	 * Metabox title
	 *
	 * @var  string
	 */
	protected $title;

	/**
	 * Metabox position (normal|side|advanced)
	 *
	 * @var  string
	 */
	protected $position = 'normal';

	/**
	 * Metabox priority
	 *
	 * @var  string
	 */
	protected $priority = 'high';

	/**
	 * Whether to initially hide metabox
	 *
	 * @var  bool
	 */
	protected $hide = true;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->hook();
		$this->init();
	}

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
			add_meta_box( $this->id, $this->title, [ $this, 'content' ], 'watermark', $this->position, $this->priority );
		}
	}

	/**
	 * Hides metabox
	 *
	 * @filter hidden_meta_boxes
	 *
	 * @param  array  $hidden Hidden metaboxes.
	 * @param  object $screen Current screen.
	 * @return bool
	 */
	public function hide( $hidden, $screen ) {
		if ( true === $this->hide && 'watermark' === $screen->id ) {
			array_push( $hidden, $this->id );
		}

		return $hidden;
	}

	/**
	 * Inits metabox
	 *
	 * @return void
	 */
	abstract public function init();

	/**
	 * Renders metabox content
	 *
	 * @param  object $post Current post.
	 * @return void
	 */
	public function content( $post ) {
		$watermark = Watermark::get( $post );

		// phpcs:ignore
		echo new View( 'edit-screen/metaboxes/' . $this->id, array_merge( [
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
