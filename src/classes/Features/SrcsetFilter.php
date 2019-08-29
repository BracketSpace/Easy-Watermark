<?php
/**
 * Auto Watermark switch class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Features;

use EasyWatermark\Core\Settings;
use EasyWatermark\Core\Plugin;
use EasyWatermark\Traits\Hookable;
use EasyWatermark\Watermark\Watermark;
use EasyWatermark\Watermark\Handler;

/**
 * Auto Watermark switch class
 */
class SrcsetFilter {

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
	 * @param Plugin $plugin Plugin instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->hook();
		$this->handler = $plugin->get_watermark_handler();
	}

	/**
	 * Filters srcset image sizes to use only the ones watermarked the same way
	 *
	 * @filter wp_calculate_image_srcset_meta 1000
	 *
	 * @param array  $image_meta    The image meta data as returned by 'wp_get_attachment_metadata()'.
	 * @param array  $size_array    Array of width and height values in pixels (in that order).
	 * @param string $image_src     The 'src' of the image.
	 * @param int    $attachment_id The image attachment ID or 0 if not supplied.
	 * @return array
	 */
	public function wp_calculate_image_srcset_meta( $image_meta, $size_array, $image_src, $attachment_id ) {

		if ( true === Settings::get()->filter_srcset && isset( $image_meta['sizes'] ) && is_array( $image_meta['sizes'] ) ) {
			$applied_watermarks = get_post_meta( $attachment_id, '_ew_applied_watermarks', true );

			if ( is_array( $applied_watermarks ) ) {
				$current_size  = $this->get_current_size( $image_src, $image_meta['sizes'] );
				$allowed_sizes = $this->get_allowed_sizes( $applied_watermarks, $current_size );

				$image_meta['sizes'] = array_filter( $image_meta['sizes'], function( $key ) use ( $allowed_sizes ) {
					return in_array( $key, $allowed_sizes, true );
				}, ARRAY_FILTER_USE_KEY );
			}
		}

		return $image_meta;

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
	 * Gets image size for the image src
	 *
	 * @param  string $image_src The 'src' of the image.
	 * @param  array  $sizes     Image sizes.
	 * @return string|false
	 */
	private function get_current_size( $image_src, $sizes ) {

		$image_src = basename( $image_src );

		foreach ( $sizes as $size => $params ) {
			if ( $image_src === $params['file'] ) {
				return $size;
			}
		}

		return false;

	}

	/**
	 * Filters applied watermarks to return only ones applied to the image size
	 *
	 * @param  array  $watermarks   Applied watermarks.
	 * @param  string $current_size Currently displayed image size.
	 * @return array
	 */
	private function get_allowed_sizes( $watermarks, $current_size ) {

		$sizes = [];

		foreach ( $watermarks as $watermark_id ) {
			$watermark = Watermark::get( $watermark_id );

			if ( in_array( $current_size, $watermark->image_sizes, true ) ) {
				$sizes[] = $watermark->image_sizes;
			}
		}

		if ( 1 === count( $sizes ) ) {
			return $sizes[0];
		}

		return $sizes ? call_user_func_array( 'array_intersect', $sizes ) : [];

	}
}
