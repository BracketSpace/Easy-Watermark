<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes\Attachment;

use EasyWatermark\Core\Plugin;
use EasyWatermark\Core\View;
use EasyWatermark\Metaboxes\AttachmentMetabox;

/**
 * Metabox class
 */
class Watermarks extends AttachmentMetabox {

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
		$this->id    = 'watermarks';
		$this->title = __( 'Watermarks', 'easy-watermark' );
	}

	/**
	 * Renders metabox content
	 *
	 * @param  object $post Current post.
	 * @return void
	 */
	public function content( $post ) {
		// phpcs:ignore
		echo self::get_content( $post );
	}

	/**
	 * Renders metabox content
	 *
	 * @param  object $post Current post.
	 * @return View
	 */
	public static function get_content( $post ) {

		$watermark_handler  = Plugin::get()->get_watermark_handler();
		$watermarks         = $watermark_handler->get_watermarks();
		$applied_watermarks = get_post_meta( $post->ID, '_ew_applied_watermarks', true );
		$has_backup         = get_post_meta( $post->ID, '_ew_has_backup', true );

		if ( ! is_array( $applied_watermarks ) ) {
			$applied_watermarks = [];
		}

		$all_applied   = true;
		$watermark_ids = [];

		foreach ( $watermarks as $watermark ) {
			$watermark_ids[] = $watermark->ID;

			if ( array_key_exists( $watermark->ID, $applied_watermarks ) ) {
				$watermark->is_applied = true;
			} else {
				$watermark->is_applied = false;
				$all_applied           = false;
			}
		}

		$removed_watermarks = [];

		foreach ( $applied_watermarks as $id => $name ) {
			if ( ! in_array( $id, $watermark_ids, true ) ) {
				$removed_watermarks[] = $name;
			}
		}

		$used_as_watermark = get_post_meta( $post->ID, '_ew_used_as_watermark', true );

		if ( $used_as_watermark ) {
			return new View( 'edit-screen/metaboxes/attachment/used-as-watermark', [
				'used_as_watermark' => $used_as_watermark,
			] );
		}

		// phpcs:ignore
		return new View( 'edit-screen/metaboxes/attachment/watermarks', [
			'post'               => $post,
			'watermarks'         => $watermarks,
			'applied_watermarks' => $applied_watermarks,
			'removed_watermarks' => $removed_watermarks,
			'all_applied'        => $all_applied,
			'has_backup'         => $has_backup,
		] );

	}
}
