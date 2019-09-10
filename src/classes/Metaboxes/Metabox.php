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
	protected $priority = 'default';

	/**
	 * Whether to initially hide metabox
	 *
	 * @var  bool
	 */
	protected $hide = false;

	/**
	 * Post type
	 *
	 * @var  string
	 */
	protected $post_type = 'post';

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
		add_meta_box( $this->id, $this->title, [ $this, 'content' ], $this->post_type, $this->position, $this->priority );
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
		if ( true === $this->hide && $this->post_type === $screen->id ) {
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
	 * Prepares params for metabox view
	 *
	 * @param  array  $params Params.
	 * @param  object $post Current post.
	 * @return array
	 */
	public function prepare_params( $params, $post ) {
		return $params;
	}

	/**
	 * Renders metabox content
	 *
	 * @param  object $post Current post.
	 * @return void
	 */
	public function content( $post ) {

		$params = $this->prepare_params( [
			'post' => $post,
		], $post );

		// phpcs:ignore
		echo new View( 'edit-screen/metaboxes/' . $this->post_type . '/' . $this->id, $params );

	}

	/**
	 * Returns watermarks count
	 *
	 * @return object
	 */
	public function get_watermarks_count() {
		return wp_count_posts( 'watermark' )->publish;
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}
}
