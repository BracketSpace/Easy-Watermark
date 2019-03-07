<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes;

use EasyWatermark\Traits\Hookable;

/**
 * Metabox class
 */
abstract class AttachmentMetabox extends Metabox {

	use Hookable;

	/**
	 * Post type
	 *
	 * @var  string
	 */
	protected $post_type = 'attachment';
}
