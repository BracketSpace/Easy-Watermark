<?php
/**
 * Preview class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Watermark;

use EasyWatermark\Helpers\Image as ImageHelper;

/**
 * Watermark class
 */
class Preview {

	/**
	 * Watermark Handler instance
	 *
	 * @var Handler
	 */
	private $handler;

	/**
	 * Constructor
	 *
	 * @param  Handler $handler Handler object.
	 */
	public function __construct( $handler ) {
		$this->handler = $handler;
	}

	/**
	 * Prints preview
	 *
	 * @param  string  $type         Preview type.
	 * @param  integer $watermark_id Watermark id.
	 * @param  string  $format       Preview format (jpg|png).
	 * @param  string  $size         Image size.
	 * @return void
	 */
	public function print( $type, $watermark_id, $format, $size ) {

		$watermark = Watermark::get( $watermark_id );

		$watermark->use_temporary_params();

		switch ( $type ) {
			case 'text':
				$this->print_text_preview( $watermark, $format );
				break;
			case 'image':
				$this->print_image_preview( $watermark, $format, $size );
				break;
		}

		do_action( 'easy_watermark/print_preview', $type, $format, $size );

	}

	/**
	 * Prints text preview
	 *
	 * @param  Watermark $watermark Watermark object.
	 * @param  string    $format    Preview format (jpg|png).
	 * @return void
	 */
	public function print_text_preview( $watermark, $format ) {
		$this->handler->print_text_preview( $watermark, $format );
	}

	/**
	 * Prints text preview
	 *
	 * @param  Watermark $watermark Watermark object.
	 * @param  string    $format    Preview format (jpg|png).
	 * @param  string    $size      Image size.
	 * @return void|false
	 */
	public function print_image_preview( $watermark, $format, $size ) {

		$available_sizes = ImageHelper::get_available_sizes();

		if ( ! array_key_exists( $size, $available_sizes ) ) {
			return false;
		}

		$attachment_id = get_option( '_ew_preview_image_id' );

		if ( ! $attachment_id ) {
			return false;
		}

		$attachment = get_post( $attachment_id );

		$meta = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );

		$filepath = get_attached_file( $attachment_id );
		$sizes    = $meta['sizes'];
		$baename  = wp_basename( $meta['file'] );

		$sizes['full'] = [
			'file'      => $meta['file'],
			'mime-type' => $attachment->post_mime_type,
		];

		if ( ! array_key_exists( $size, $sizes ) ) {
			return false;
		}

		$image      = $sizes[ $size ];
		$image_file = str_replace( $baename, wp_basename( $image['file'] ), $filepath );

		if ( ! in_array( $size, $watermark->image_sizes, true ) ) {
			$watermark = null;
		}

		$this->handler->print_preview( $watermark, $format, $image_file );
	}

}
