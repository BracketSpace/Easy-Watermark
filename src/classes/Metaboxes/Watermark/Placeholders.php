<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes\Watermark;

use EasyWatermark\Metaboxes\WatermarkMetabox;
use EasyWatermark\Placeholders\Resolver;

/**
 * Metabox class
 */
class Placeholders extends WatermarkMetabox {

	/**
	 * Metabox position (normal|side|advanced)
	 *
	 * @var  string
	 */
	protected $position = 'side';

	/**
	 * Inits metabox
	 *
	 * @return void
	 */
	public function init() {
		$this->id    = 'placeholders';
		$this->title = __( 'Placeholders' );
	}

	/**
	 * Prepares params for metabox view
	 *
	 * @param  array  $params Params.
	 * @param  object $post Current post.
	 * @return array
	 */
	public function prepare_params( $params, $post ) {

		$resolver = Resolver::get();

		$params['placeholders'] = $resolver->get_placeholders();

		return $params;

	}
}
