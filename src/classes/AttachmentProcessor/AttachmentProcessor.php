<?php
/**
 * Image Processor
 *
 * @package easy-watermark
 */

namespace EasyWatermark\AttachmentProcessor;

use EasyWatermark\Watermark\Watermark;
/**
 * AttachmentProcessor abstract class
 */
abstract class AttachmentProcessor {

	/**
	 * Image file path
	 *
	 * @var string
	 */
	protected $image_file;

	/**
	 * Array of watermark objects
	 *
	 * @var array
	 */
	protected $watermarks;

	/**
	 * Params
	 *
	 * @var array
	 */
	protected $params;

	/**
	 * Constructor
	 *
	 * @param string $file Image file.
	 * @param array  $params     Params.
	 */
	public function __construct( $file = null, $params = [] ) {

		$this->image_file = $file;
		$this->params     = $params;

	}

	/**
	 * Sets image file
	 *
	 * @param  string $file Image file.
	 * @return AttachmentProcessor
	 */
	public function set_file( $file ) {

		$this->image_file = $file;
		return $this;

	}

	/**
	 * Sets params array
	 *
	 * @param  array $params Params.
	 * @return void|WP_Error
	 */
	public function set_params( $params ) {

		if ( ! is_array( $params ) ) {
			return new \WP_Error( 'invalid_params_type', __( 'Params should be an array.', 'easy-watermark' ) );
		}

		$this->params = $params;

	}

	/**
	 * Sets single param
	 *
	 * @param  string $key   Param name.
	 * @param  string $value Param value.
	 * @return AttachmentProcessor
	 */
	public function set_param( $key, $value ) {

		$this->params[ $key ] = $value;
		return $this;

	}

	/**
	 * Returns single param value
	 *
	 * @param  string $key     Param name.
	 * @param  string $default Default value to return if param is not set.
	 * @return mixed
	 */
	public function get_param( $key, $default = null ) {

		if ( array_key_exists( $key, $this->params ) ) {
			return $this->params[ $key ];
		}

		return $default;

	}

	/**
	 * Adds watermark configuration
	 *
	 * @param  Watermark $watermark Watermark object.
	 * @return void|WP_Error
	 */
	public function add_watermark( $watermark ) {

		if ( ! $watermark instanceof Watermark ) {
			return new \WP_Error( 'invalid_watermark_type', __( 'Watermark should be an instance of EasyWatermark\Watermark\Watermark class.', 'easy-watermark' ) );
		}

		$this->watermarks[] = $watermark;

	}

	/**
	 * Computes offset
	 *
	 * @param  string  $position  Watrermark position.
	 * @param  array   $offset    Watermark offset array [value => int, unit => string].
	 * @param  numeric $image_dim Image dimension (width or height).
	 * @param  numeric $watermark_dim Watermark dimension (width or height).
	 * @return integer
	 */
	protected function compute_offset( $position, $offset, $image_dim, $watermark_dim ) {

		if ( '%' === $offset['unit'] ) {
			// Percentage offset.
			$offset['value'] = round( ( $offset['value'] / 100 ) * $image_dim );
		}

		switch ( $position ) {
			case 'start':
				$result = $offset['value'];
				break;
			case 'center':
				$result = ( ( $image_dim - $watermark_dim ) / 2 ) + $offset['value'];
				break;
			case 'end':
				$result = $image_dim - $watermark_dim - $offset['value'];
				break;
		}

		return (int) $result;

	}

	/**
	 * Returns watermark position (horizontal or vertical) based on alignment
	 *
	 * @param  string $alignment Watrermark alignment.
	 * @param  string $axis      X or Y.
	 * @return string
	 */
	protected function get_position( $alignment, $axis ) {

		if ( 'x' === $axis ) {
			$start_key = 'left';
			$end_key   = 'right';
		} else {
			$start_key = 'top';
			$end_key   = 'bottom';
		}

		if ( false !== strpos( $alignment, $start_key ) ) {
			return 'start';
		} elseif ( false !== strpos( $alignment, $end_key ) ) {
			return 'end';
		} else {
			return 'center';
		}

	}

	/**
	 * Changes hexadecimal color string into RGB array.
	 *
	 * @param  string $color Hexacedimal color string.
	 * @return array
	 */
	public function get_rgb_color( $color ) {

		if ( 0 === strpos( $color, '#' ) ) {
			$color = substr( $color, 1 );
		}

		$i = ( 3 === strlen( $color ) ) ? 1 : 2;

		$r = substr( $color, 0, $i );
		$g = substr( $color, $i, $i );
		$b = substr( $color, ( 2 * $i ), $i );

		if ( 1 === $i ) {
			$r .= $r;
			$g .= $g;
			$b .= $b;
		}

		return [
			'red'   => hexdec( $r ),
			'green' => hexdec( $g ),
			'blue'  => hexdec( $b ),
		];

	}

	/**
	 * Performs cleaning
	 *
	 * @return void
	 */
	public function clean() {
		$this->watermarks = [];
	}

	/**
	 * Checks if the processor can be used in particular system
	 *
	 * @return boolean
	 */
	public static function is_available() {
		return false;
	}

	/**
	 * Processes image
	 *
	 * @return array
	 */
	abstract public function process();

	/**
	 * Prints image directly to the browser
	 *
	 * @param  Watermark|null $watermark Watermark to apply for the preview.
	 * @return void
	 */
	abstract public function print_preview( $watermark = null );

	/**
	 * Prints image with text preview
	 *
	 * @param  Watermark $watermark Text watermark for preview.
	 * @param  string    $format    Output image format.
	 * @return void
	 */
	abstract public function print_text_preview( $watermark, $format = 'png' );
}
