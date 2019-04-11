<?php
/**
 * Hooks class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Watermark;

use EasyWatermark\AttachmentProcessor;
use EasyWatermark\Metaboxes\Attachment\Watermarks;
use EasyWatermark\Traits\Hookable;

/**
 * Hooks class
 */
class Hooks {

	use Hookable;

	/**
	 * Watermark Handler instance
	 *
	 * @var Handler
	 */
	private $handler;

	/**
	 * Constructor
	 *
	 * @param Handler $handler Handler instance.
	 */
	public function __construct( $handler ) {

		$this->hook();

		$this->handler = $handler;

	}

	/**
	 * Cleans backup on attachment removal
	 *
	 * @action delete_attachment
	 *
	 * @param  integer $attachment_id Image attachment ID.
	 * @return void
	 */
	public function delete_attachment( $attachment_id ) {

		$has_backup = get_post_meta( $attachment_id, '_ew_has_backup', true );

		if ( '1' === $has_backup ) {
			$this->handler->clean_backup( $attachment_id );
		}

	}

	/**
	 * Adds attachment version to the url
	 *
	 * @filter wp_get_attachment_image_src
	 *
	 * @param  array|false  $image         Either array with src, width & height, icon src, or false.
	 * @param  integer      $attachment_id Image attachment ID.
	 * @param  string|array $size          Size of image. Image size or array of width and height values
	 *                                    (in that order). Default 'thumbnail'.
	 * @param  bool         $icon          Whether the image should be treated as an icon. Default false.
	 * @return array|false
	 */
	public function wp_get_attachment_image_src( $image, $attachment_id, $size, $icon ) {

		if ( false === $image ) {
			return false;
		}

		if ( is_array( $image ) && ! empty( $image ) && is_string( $image[0] ) ) {
			$image[0] = $this->handler->add_attachment_version( $image[0], $attachment_id );
		} elseif ( is_string( $image ) ) {
			$image = $this->handler->add_attachment_version( $image, $attachment_id );
		}

		return $image;

	}

	/**
	 * Adds attachment version to the 'srcset' urls
	 *
	 * @filter wp_calculate_image_srcset
	 *
	 * @param  array   $sources       One or more arrays of source data to include in the 'srcset'.
	 * @param  array   $size_array    Array of width and height values in pixels (in that order).
	 * @param  string  $image_src     The 'src' of the image.
	 * @param  array   $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
	 * @param  integer $attachment_id Image attachment ID or 0.
	 * @return array
	 */
	public function wp_calculate_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {

		foreach ( $sources as &$source ) {
			$source['url'] = $this->handler->add_attachment_version( $source['url'], $attachment_id );
		}

		return $sources;

	}

	/**
	 * Applies watermarks after upload
	 *
	 * @filter wp_generate_attachment_metadata
	 *
	 * @param  array   $metadata      Attachment metadata.
	 * @param  integer $attachment_id Attachment ID.
	 * @return array
	 */
	public function wp_generate_attachment_metadata( $metadata, $attachment_id ) {

		$all_watermarks = $this->handler->get_watermarks();

		$watermarks = [];

		foreach ( $all_watermarks as $watermark ) {
			if ( ! $watermark->auto_add ) {
				continue;
			}

			if ( ! $watermark->auto_add_all && ! current_user_can( 'apply_watermark' ) ) {
				continue;
			}

			$mime_type = get_post_mime_type( $attachment_id );

			if ( ! in_array( $mime_type, $watermark->image_types, true ) ) {
				continue;
			}

			$attachment = get_post( $attachment_id );

			if ( $attachment->post_parent > 0 ) {
				$post_type = get_post_type( $attachment->post_parent );
			} else {
				$post_type = 'unattached';
			}

			if ( ! in_array( $post_type, $watermark->post_types, true ) ) {
				continue;
			}

			$watermarks[] = $watermark;
		}

		$this->handler->apply_watermarks( $attachment_id, $watermarks, $metadata );

		return $metadata;

	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}
}
