<?php
/**
 * Preview class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Features;

use EasyWatermark\Helpers\Image as ImageHelper;
use EasyWatermark\Traits\Hookable;
use EasyWatermark\Watermark\Watermark;

/**
 * Watermark class
 */
class WatermarkPreview {

	use Hookable;

	/**
	 * Watermark Handler instance
	 *
	 * @var Handler
	 */
	private $handler;

	/**
	 * Build watermark preview URL
	 *
	 * @param  string $type         Watermark type.
	 * @param  int    $watermark_id Watermark ID.
	 * @param  string $size         Image size.
	 * @return string               Preview URL.
	 */
	public static function get_url( $type, $watermark_id, $size = null ) {
		$args = [
			't' => time(),
		];

		if ( get_option( 'permalink_structure' ) ) {
			$base = sprintf(
				'easy-watermark-preview/%s-%s',
				$type,
				$size ? sprintf( '%s-%s', $watermark_id, $size ) : $watermark_id
			);
		} else {
			$base = 'index.php';

			$args['easy_watermark_preview'] = $type;
			$args['watermark_id']           = $watermark_id;

			if ( $size ) {
				$args['image_size'] = $size;
			}
		}

		return add_query_arg( $args, home_url( $base ) );
	}

	/**
	 * Constructor
	 *
	 * @param  EasyWatermark\Core\Plugin $plugin Plugin instance.
	 */
	public function __construct( $plugin ) {
		$this->handler = $plugin->get_watermark_handler();

		$this->hook();
	}

	/**
	 * Initiates plugin
	 *
	 * @action  parse_request
	 *
	 * @param   WP $wp WP object.
	 * @return  void
	 */
	public function parse_request( $wp ) {

		if ( ! array_key_exists( 'easy_watermark_preview', $wp->query_vars ) ) {
			return;
		}

		$type         = $wp->query_vars['easy_watermark_preview'];
		$watermark_id = $wp->query_vars['watermark_id'];
		$size         = isset( $wp->query_vars['image_size'] ) ? $wp->query_vars['image_size'] : 'full';

		$this->show( $type, $watermark_id, 'png', $size );

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
