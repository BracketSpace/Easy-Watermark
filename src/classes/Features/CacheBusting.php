<?php
/**
 * Auto Watermark switch class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Features;

use EasyWatermark\Settings\Section;
use EasyWatermark\Settings\Fields\SwitchField;
use EasyWatermark\Traits\Hookable;
use EasyWatermark\Watermark\Watermark;

/**
 * Auto Watermark switch class
 */
class CacheBusting {

	use Hookable;

	/**
	 * Setting field
	 *
	 * @var SwitchField
	 */
	private $switch;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->hook();
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

		$description = implode( '<br/>', [
			esc_html_x(
				'This feature will add version parameter to image file url. This will prevent browser from using cached image.',
				'"Cache busting" setting description line 1',
				'easy-watermark'
			),
			esc_html_x(
				'Without this option turned on browser might display original image which was cached before instead of the watermarked one.',
				'"Cache busting" setting description line 2',
				'easy-watermark'
			),
			esc_html_x(
				'Turn this off only if it causes problems in your environment (e.g. images do not show at all).',
				'"Cache busting" setting description line 3',
				'easy-watermark'
			),
		] );

		$this->switch = new SwitchField( [
			'label'       => esc_html__( 'Cache busting', 'easy-watermark' ),
			'slug'        => 'cache_busting',
			'default'     => true,
			'description' => $description,
		] );

		$section->add_field( $this->switch );

	}

	/**
	 * Check if this feature is active
	 *
	 * @return boolean
	 */
	private function is_active() {
		/**
		 * The SwitchField object should be always available by the time this method
		 * is used. Hovewer there has been an issue reported that the
		 * `wp_get_attachment_image_src` method caused a fatal error because
		 * $this->switch was null. Just for safety, check if the SwitchField object
		 * is created, if not return `true` - the default value.
		 */
		return $this->switch ? true === $this->switch->get_value() : true;
	}

	/**
	 * Adds attachment version to the URL
	 *
	 * @param  string  $url Attachment url.
	 * @param  integer $attachment_id Attachment ID.
	 * @return string
	 */
	public function add_attachment_version( $url, $attachment_id ) {

		$version = get_post_meta( $attachment_id, '_ew_attachment_version', true );

		if ( ! $version ) {
			return $url;
		}

		return $url . '?v=' . $version;

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

		if ( $this->is_active() ) {
			if ( is_array( $image ) && ! empty( $image ) && is_string( $image[0] ) ) {
				$image[0] = $this->add_attachment_version( $image[0], $attachment_id );
			} elseif ( is_string( $image ) ) {
				$image = $this->add_attachment_version( $image, $attachment_id );
			}
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

		if ( $this->is_active() ) {
			foreach ( $sources as &$source ) {
				$source['url'] = $this->add_attachment_version( $source['url'], $attachment_id );
			}
		}

		return $sources;

	}
}
