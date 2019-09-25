<?php
/**
 * Auto Watermark switch class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Features;

use EasyWatermark\Core\Settings;
use EasyWatermark\Core\Plugin;
use EasyWatermark\Settings\Section;
use EasyWatermark\Settings\Fields\SwitchField;
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
	 * Setting field
	 *
	 * @var SwitchField
	 */
	private $switch;

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
	 * Registers settings
	 *
	 * @action easy-watermark/settings/register/general
	 *
	 * @param  Section $section Settings section.
	 * @return void
	 */
	public function register_settings( $section ) {

		$label = sprintf(
			'%s <p class="description">%s</p>',
			esc_html__( 'Filter srcset', 'easy-watermark' ),
			esc_html_x( 'for watermarked images', 'Continuation of "Filter srcset" setting label.', 'easy-watermark' )
		);

		$description = implode( '<br/>', [
			esc_html_x(
				'Srcset attribute contains information about other image sizes and lets the browser decide which image to display based on the screen size.',
				'"Filter srcset" setting description line 1',
				'easy-watermark'
			),
			esc_html_x(
				'This is good in general but it might cause problems if some watermarks are applied only to certain image sizes.',
				'"Filter srcset" setting description line 2',
				'easy-watermark'
			),
			esc_html_x(
				'With this option enabled srcset attribute will only contain image sizes watermarked the same way.',
				'"Filter srcset" setting description line 3',
				'easy-watermark'
			),
		] );

		$this->switch = new SwitchField( [
			'label'       => $label,
			'slug'        => 'filter_srcset',
			'default'     => true,
			'description' => $description,
		] );

		$section->add_field( $this->switch );

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

		if ( ! $this->switch ) {
			// Don't do anything if settings have not been loaded yet.
			return $image_meta;
		}

		if ( true === $this->switch->get_value() && isset( $image_meta['sizes'] ) && is_array( $image_meta['sizes'] ) ) {
			$applied_watermarks = get_post_meta( $attachment_id, '_ew_applied_watermarks', true );

			if ( is_array( $applied_watermarks ) ) {
				$current_size  = $this->get_current_size( $image_src, $image_meta );
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
	 * @param  array  $meta      Image meta.
	 * @return string|false
	 */
	private function get_current_size( $image_src, $meta ) {

		$image_src = basename( $image_src );
		$pos       = strpos( $image_src, '?v=' );

		if ( $pos ) {
			$image_src = substr( $image_src, 0, $pos );
		}

		if ( false !== strpos( $meta['file'], $image_src ) ) {
			return 'full';
		}

		foreach ( $meta['sizes'] as $size => $params ) {
			if ( $image_src === $params['file'] ) {
				return $size;
			}
		}

		return false;

	}

	/**
	 * Filters image sizes
	 *
	 * @param  array  $watermarks   Applied watermarks.
	 * @param  string $current_size Currently displayed image size.
	 * @return array
	 */
	private function get_allowed_sizes( $watermarks, $current_size ) {

		$sizes = get_intermediate_image_sizes();

		foreach ( $watermarks as $watermark_id ) {
			$watermark = Watermark::get( $watermark_id );

			if ( ! $watermark ) {
				continue;
			}

			$should_be = in_array( $current_size, $watermark->image_sizes, true );

			foreach ( $sizes as $key => $size ) {
				$is = in_array( $size, $watermark->image_sizes, true );

				if ( ! ( $is * $should_be ) && ( $is || $should_be ) ) {
					unset( $sizes[ $key ] );
				}
			}
		}

		return $sizes;

	}
}
