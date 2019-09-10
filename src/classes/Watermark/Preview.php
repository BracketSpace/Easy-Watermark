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
	public function show( $type, $watermark_id, $format, $size ) {

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

		do_action( 'easy-watermark/print-preview', $type, $format, $size );

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

		$this->handler->print_preview( $watermark, $format, $size );
	}

}
