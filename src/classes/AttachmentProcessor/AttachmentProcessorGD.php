<?php
/**
 * GD Image Processor
 *
 * @package easy-watermark
 */

namespace EasyWatermark\AttachmentProcessor;

use EasyWatermark\Helpers\Text;
use EasyWatermark\Watermark\Watermark;
use WP_Error;

/**
 * GD Image Processor
 */
class AttachmentProcessorGD extends AttachmentProcessor {

	/**
	 * Finfo instance
	 *
	 * @var object
	 */
	private $finfo;

	/**
	 * Is FreeType extension enabled?
	 *
	 * @var boolean
	 */
	private $is_freetype_enabled;

	/**
	 * Allowed image types
	 *
	 * @var array
	 */
	private $allowed_types = [ 'jpeg', 'png', 'gif' ];

	/**
	 * Input image
	 *
	 * @var resource
	 */
	private $input_image;

	/**
	 * Output image
	 *
	 * @var resource
	 */
	private $output_image;

	/**
	 * Input image type
	 *
	 * @var string
	 */
	private $image_type;

	/**
	 * Image size
	 *
	 * @var array
	 */
	private $image_size;

	/**
	 * Watermark image
	 *
	 * @var resource
	 */
	private $watermark_image;

	/**
	 * Constructor
	 *
	 * @param string $file Image file.
	 * @param array  $params     Params.
	 */
	public function __construct( $file = null, $params = [] ) {

		$gdinfo                    = gd_info();
		$this->is_freetype_enabled = $gdinfo['FreeType Support'];

		parent::__construct( $file, $params );

	}

	/**
	 * Checks if the processor can be used in particular system
	 *
	 * @return boolean
	 */
	public static function is_available() {

		if ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Filters available watermark types
	 *
	 * @filter easy-watermark/watermark-types
	 *
	 * @param  array $types Available watermark types.
	 * @return array
	 */
	public function available_watermark_types( $types ) {

		if ( ! $this->is_freetype_enabled && array_key_exists( 'text', $types ) ) {
			unset( $types['text'] );
		}

		return $types;

	}

	/**
	 * Processes image
	 *
	 * @param  boolean $save Whether to save output or print it.
	 * @return array
	 */
	public function process( $save = true ) {

		$output_image = $this->get_output_image();

		if ( ! $output_image ) {
			return false;
		}

		$result = [];

		foreach ( $this->watermarks as $watermark ) {
			$result[ $watermark->ID ] = $this->apply_watermark( $watermark );
		}

		$output = ( true === $save ) ? $this->image_file : null;

		$this->prepare_output( $output );

		return $result;

	}

	/**
	 * Prints image directly to the browser
	 *
	 * @param  Watermark|null $watermark Watermark to apply for the preview.
	 * @param  string         $format    Output format.
	 * @return void
	 */
	public function print_preview( $watermark = null, $format = 'jpg' ) {

		if ( $watermark instanceof Watermark ) {
			$this->add_watermark( $watermark );
		}

		if ( 'jpg' === $format ) {
			$format = 'jpeg';
		}

		$this->image_type = $format;

		$this->process( false );

	}

	/**
	 * Prints image with text preview
	 *
	 * @param  Watermark $watermark Text watermark for preview.
	 * @param  string    $format    Output format.
	 * @return WP_Error|void
	 */
	public function print_text_preview( $watermark, $format = 'png' ) {

		if ( ! $watermark instanceof Watermark ) {
			return new WP_Error( 'invalid_watermark_type', __( 'Watermark should be instance of Easywatermark\Watermark\Watermark.', 'easy-watermark' ) );
		}

		if ( 'text' !== $watermark->type ) {
			return new WP_Error( 'invalid_watermark_type', __( 'Text preview can be only generated for text watermark.', 'easy-watermark' ) );
		}

		if ( ! $watermark->text ) {
			return new WP_Error( 'empty_watermark_text', __( 'Watermark text not specified.', 'easy-watermark' ) );
		}

		$font = Text::get_font_path( $watermark->font );

		if ( ! $font ) {
			/* translators: font name */
			return new WP_Error( 'font_not_found', sprintf( __( 'Font "%s" could not be found. ', 'easy-watermark' ), $watermark->font ) );
		}

		$text_size = $this->calculate_text_size( $watermark->text_size, $watermark->text_angle, $font, $watermark->text );

		$this->output_image = imagecreatetruecolor( $text_size['width'], $text_size['height'] );

		if ( false === $this->output_image ) {
			return new WP_Error( 'gd_error', __( 'Could not create output image.', 'easy-watermark' ) );
		}

		imagealphablending( $this->output_image, false );
		imagesavealpha( $this->output_image, true );

		$color_transparent = imagecolorallocatealpha( $this->output_image, 255, 255, 255, 127 );

		imagefill( $this->output_image, 0, 0, $color_transparent );

		$watermark->alignment = 'top-left';
		$watermark->offset    = [
			'x' => [
				'value' => 0,
				'unit'  => 'px',
			],
			'y' => [
				'value' => 0,
				'unit'  => 'px',
			],
		];

		if ( 'jpg' === $format ) {
			$format = 'jpeg';
		}

		$this->image_type = $format;

		$this->apply_text_watermark( $watermark );

		$this->prepare_output();

	}

	/**
	 * Processes image
	 *
	 * @return resource
	 */
	public function get_input_image() {

		if ( ! $this->input_image ) {
			$type = $this->get_param( 'image_type' );

			$result = $this->create_image( $this->image_file, $type );

			if ( ! $result ) {
				return false;
			}

			list( $this->input_image, $this->image_type ) = $result;
		}

		return $this->input_image;

	}

	/**
	 * Processes image
	 *
	 * @return boolean|resource
	 */
	public function get_output_image() {

		if ( ! $this->output_image ) {
			$input_image = $this->get_input_image();

			if ( ! $input_image ) {
				$this->output_image = false;
			} else {
				$image_size = $this->get_input_image_size();

				// Create blank image.
				$this->output_image = imagecreatetruecolor( $image_size['width'], $image_size['height'] );

				if ( ! $this->output_image ) {
					return false;
				}

				if ( 'png' === $this->image_type && $this->is_alpha_png( $this->image_file ) ) {
					// Preserve opacity for png images.
					imagealphablending( $this->output_image, false );
					imagesavealpha( $this->output_image, true );
				}

				imagecopy(
					$this->output_image,
					$this->input_image,
					0, 0, 0, 0,
					$image_size['width'],
					$image_size['height']
				);

				// Destroy Input Image to save memory.
				imagedestroy( $this->input_image );
				unset( $this->input_image );

				imagealphablending( $this->output_image, true );
			}
		}

		return $this->output_image;

	}

	/**
	 * Creates GD image
	 *
	 * @param  string $file_path Path to image file.
	 * @param  string $type      MIME type of the image.
	 * @return mixed
	 */
	private function create_image( $file_path, $type ) {

		$type = $this->detect_image_type( $type, $file_path );

		if ( ! in_array( $type, $this->allowed_types, true ) ) {
			return false;
		}

		$func_name = 'imagecreatefrom' . $type;

		$image = call_user_func( $func_name, $file_path );

		if ( false === $image ) {
			return false;
		}

		if ( 'png' === $type && $this->is_alpha_png( $file_path ) ) {
			imagealphablending( $image, false );
			imagesavealpha( $image, true );
		}

		return [ $image, $type ];

	}

	/**
	 * Applies watermark
	 *
	 * @param  Watermark $watermark Watermark object.
	 * @return mixed
	 */
	private function apply_watermark( $watermark ) {

		switch ( $watermark->type ) {
			case 'image':
				return $this->apply_image_watermark( $watermark );
			case 'text':
				return $this->apply_text_watermark( $watermark );
		}

	}

	/**
	 * Applies image watermark
	 *
	 * @param  Watermark $watermark Watermark object.
	 * @return mixed
	 */
	private function apply_image_watermark( $watermark ) {

		$output_image = $this->get_output_image();

		if ( ! $output_image ) {
			return new WP_Error( 'gd_error', __( 'Could not create output image. Please check your server configuration.', 'easy-watermark' ) );
		}

		$watermark_file = get_attached_file( $watermark->attachment_id );

		if ( ! $watermark_file || ! is_file( $watermark_file ) ) {
			return new WP_Error( 'watermark_file_not_found', __( 'Watermark image file does not exist.', 'easy-watermark' ) );
		}

		$result = $this->create_image( $watermark_file, $watermark->mime_type );

		if ( ! $result ) {
			return new WP_Error( 'gd_error', __( 'Something went wrong while processing watermark image.', 'easy-watermark' ) );
		}

		list( $watermark_image, $watermark_type ) = $result;

		$watermark_size = $this->calculate_image_size( $watermark_image );

		$image_size = $this->get_input_image_size();

		$scaling_mode = $watermark->scaling_mode;

		if ( 'contain' === $scaling_mode || 'cover' === $scaling_mode ) {
			$image_ratio     = $image_size['width'] / $image_size['height'];
			$watermark_ratio = $watermark_size['width'] / $watermark_size['height'];

			if ( ( 'cover' === $scaling_mode && $watermark_ratio < $image_ratio )
				|| ( 'contain' === $scaling_mode && $watermark_ratio > $image_ratio ) ) {
				$scaling_mode     = 'fit_to_width';
				$watermark->scale = 100;
			} else {
				$scaling_mode     = 'fit_to_height';
				$watermark->scale = 100;
			}
		}

		if ( 'fit_to_width' === $scaling_mode && ( ! $watermark->scale_down_only || $image_size['width'] < $watermark_size['width'] ) ) {
			$scale      = $image_size['width'] / $watermark_size['width'];
			$new_width  = $image_size['width'] * $watermark->scale / 100;
			$new_height = $watermark_size['height'] * $scale * $watermark->scale / 100;
		} elseif ( 'fit_to_height' === $scaling_mode && ( ! $watermark->scale_down_only || $image_size['height'] < $watermark_size['height'] ) ) {
			$scale      = $image_size['height'] / $watermark_size['height'];
			$new_width  = $watermark_size['width'] * $scale * $watermark->scale / 100;
			$new_height = $image_size['height'] * $watermark->scale / 100;
		}

		if ( isset( $new_width ) ) {
			$tmp_image = imagecreatetruecolor( $new_width, $new_height );

			if ( ! $tmp_image ) {
				return new WP_Error( 'gd_error', __( 'Could not create temporary image. Please check your server configuration.', 'easy-watermark' ) );
			}

			if ( 'png' === $watermark_type && $this->is_alpha_png( $watermark_file ) ) {
				imagealphablending( $tmp_image, false );
				imagesavealpha( $tmp_image, true );
			}

			imagecopyresampled(
				$tmp_image,
				$watermark_image,
				0, 0, 0, 0,
				$new_width, $new_height,
				$watermark_size['width'], $watermark_size['height']
			);

			// Clean memory.
			imagedestroy( $watermark_image );

			$watermark_image          = $tmp_image;
			$watermark_size['width']  = $new_width;
			$watermark_size['height'] = $new_height;

			unset( $tmp_image, $new_width, $nwe_height );
		}

		// Compute watermark offset.
		$offset_x = $this->compute_offset( $this->get_position( $watermark->alignment, 'x' ), $watermark->offset['x'], $image_size['width'], $watermark_size['width'] );
		$offset_y = $this->compute_offset( $this->get_position( $watermark->alignment, 'y' ), $watermark->offset['y'], $image_size['height'], $watermark_size['height'] );

		// Prepare params for copying function.
		$params = [
			$output_image,
			$watermark_image,
			$offset_x,
			$offset_y,
			0,
			0,
			$watermark_size['width'],
			$watermark_size['height'],
		];

		if ( 'png' === $watermark_type && $this->is_alpha_png( $watermark_file ) ) {
			// Watermark is PNG with alpha channel, use imagecopy.
			$func_name = 'imagecopy';
		} else {
			// Use imagecopymerge with opacity param for other images.
			$func_name = 'imagecopymerge';
			$params[]  = $watermark->opacity;
		}

		// Copy watermark to output image.
		$result = call_user_func_array( $func_name, $params );

		if ( true === $result ) {
			return true;
		}

		return new WP_Error( 'gd_error', __( 'Something went wrong while applying watermark image.', 'easy-watermark' ) );

	}

	/**
	 * Applies text watermark
	 *
	 * @param  Watermark $watermark Watermark object.
	 * @return mixed
	 */
	private function apply_text_watermark( $watermark ) {

		$output_image = $this->get_output_image();

		if ( ! $output_image ) {
			return new WP_Error( 'gd_error', __( 'Could not create output image. Please check your server configuration.', 'easy-watermark' ) );
		}

		$font = Text::get_font_path( $watermark->font );

		if ( ! $font ) {
			/* translators: font name */
			return new WP_Error( 'font_not_found', sprintf( __( 'Font "%s" could not be found. ', 'easy-watermark' ), $watermark->font ) );
		}

		$text_size  = $this->calculate_text_size( $watermark->text_size, $watermark->text_angle, $font, $watermark->text );
		$image_size = $this->get_input_image_size();

		$color_rgb = $this->get_rgb_color( $watermark->text_color );
		$color     = imagecolorallocatealpha(
			$output_image,
			$color_rgb['red'],
			$color_rgb['green'],
			$color_rgb['blue'],
			127 * ( 100 - $watermark->opacity ) / 100
		);

		if ( false === $color ) {
			return new WP_Error( 'gd_error', __( 'Something went wrong while allocating text color.', 'easy-watermark' ) );
		}

		$offset_x = $this->compute_offset( $this->get_position( $watermark->alignment, 'x' ), $watermark->offset['x'], $image_size['width'], $text_size['width'] );
		$offset_y = $this->compute_offset( $this->get_position( $watermark->alignment, 'y' ), $watermark->offset['y'], $image_size['height'], $text_size['height'] );

		$result = imagettftext(
			$output_image,
			$watermark->text_size,
			$watermark->text_angle,
			$offset_x - $text_size['delta_x'], $offset_y - $text_size['delta_y'],
			$color,
			$font,
			$watermark->text
		);

		if ( false === $result ) {
			return new WP_Error( 'gd_error', __( 'Something went wrong while applying watermark text.', 'easy-watermark' ) );
		}

		return true;

	}

	/**
	 * Detects mime type using php finfo object
	 *
	 * @param  string $type      MIME type.
	 * @param  string $file_path File path.
	 * @return string
	 */
	private function detect_image_type( $type, $file_path = null ) {

		if ( empty( $type ) && $file_path ) {
			// Get finfo object to detect mime types.
			$finfo = $this->get_finfo();
			$type  = $finfo->file( $file_path );
		}

		if ( ! $type ) {
			return false;
		}

		if ( 0 === strpos( $type, 'image/' ) ) {
			$type = substr( $type, 6 );
		}

		if ( 'jpg' === $type ) {
			$type = 'jpeg';
		}

		return $type;

	}

	/**
	 * Returns input image size
	 *
	 * @return array
	 */
	private function get_input_image_size() {

		if ( ! $this->image_size ) {
			$this->image_size = $this->calculate_image_size( $this->input_image );
		}

		return $this->image_size;

	}

	/**
	 * Returns image size
	 *
	 * @param  resource $image Image to calculate dimensions.
	 * @return array
	 */
	private function calculate_image_size( $image ) {

		if ( ! is_resource( $image ) ) {
			return false;
		}

		return [
			'width'  => imagesx( $image ),
			'height' => imagesy( $image ),
		];

	}

	/**
	 * Returns size of text bounding box width x and y distance from font baseline
	 *
	 * @param  integer $font_size Font size.
	 * @param  integer $angle     Text angle.
	 * @param  string  $font      Path to font file.
	 * @param  string  $text      Text.
	 * @return array
	 */
	private function calculate_text_size( $font_size, $angle, $font, $text ) {

		$bb = imagettfbbox( $font_size, $angle, $font, $text );

		$max_x = max( $bb[0], $bb[2], $bb[4], $bb[6] );
		$min_x = min( $bb[0], $bb[2], $bb[4], $bb[6] );
		$width = $max_x - $min_x;

		$max_y  = max( $bb[1], $bb[3], $bb[5], $bb[7] );
		$min_y  = min( $bb[1], $bb[3], $bb[5], $bb[7] );
		$height = $max_y - $min_y;

		return [
			'width'   => $width,
			'height'  => $height,
			'delta_x' => $min_x,
			'delta_y' => $min_y,
		];

	}

	/**
	 * Returns finfo object
	 *
	 * @return object
	 */
	private function get_finfo() {

		if ( ! $this->finfo instanceof \finfo ) {
			$this->finfo = new \finfo( FILEINFO_MIME_TYPE );
		}

		return $this->finfo;

	}

	/**
	 * Verifies if png file has alpha chanel
	 *
	 * @param  string $file_path File path.
	 * @return boolean
	 */
	public function is_alpha_png( $file_path ) {

		/**
		 * Color type of png image stored at 25 byte:
		 * 0 - greyscale
		 * 2 - RGB
		 * 3 - RGB with palette
		 * 4 - greyscale + alpha
		 * 6 - RGB + alpha
		 */
		$content = file_get_contents( $file_path, false, null, 25, 1 ); // phpcs:ignore

		if ( false === $content ) {
			return false;
		}

		$color_byte = ord( $content );

		return ( 6 === $color_byte || 4 === $color_byte );

	}

	/**
	 * Saves or outputs created image
	 *
	 * @param  string $output Output file.
	 * @return boolean
	 */
	private function prepare_output( $output = null ) {

		if ( ! $this->image_type || ! in_array( $this->image_type, $this->allowed_types, true ) ) {
			return false;
		}

		if ( null === $output ) {
			// Return image directly to the browser.
			header( 'Content-Type: image/' . $this->image_type );
		}

		$params = [
			$this->output_image,
			$output,
		];

		if ( 'jpeg' === $this->image_type ) {
			$params[] = $this->get_param( 'jpeg_quality', -1 );
		}

		$func_name = 'image' . $this->image_type;

		$result = call_user_func_array( $func_name, $params );

		return $result;

	}

	/**
	 * Performs cleaning
	 *
	 * @return void
	 */
	public function clean() {

		parent::clean();

		if ( $this->watermark_image ) {
			imagedestroy( $this->watermark_image );
			$this->watermark_image = null;
		}

		if ( $this->output_image ) {
			imagedestroy( $this->output_image );
			$this->output_image = null;
		}

		$this->image_size  = null;
		$this->input_image = null;

	}

	/**
	 * Destructor
	 */
	public function __destruct() {

		$this->clean();

	}
}
