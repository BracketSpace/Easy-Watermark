<?php
/**
 * Text helper
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Helpers;

/**
 * Text helper
 */
class Text {
	/**
	 * Returns available fonts
	 *
	 * @return array
	 */
	public static function get_available_fonts() {
		return [
			'Arial.ttf'           => 'Arial',
			'Arial_Black.ttf'     => 'Arial Black',
			'Comic_Sans_MS.ttf'   => 'Comic Sans MS',
			'Courier_New.ttf'     => 'Courier New',
			'Georgia.ttf'         => 'Georgia',
			'Impact.ttf'          => 'Impact',
			'Tahoma.ttf'          => 'Tahoma',
			'Times_New_Roman.ttf' => 'Times New Roman',
			'Trebuchet_MS.ttf'    => 'Trebuchet MS',
			'Verdana.ttf'         => 'Verdana',
		];
	}

	/**
	 * Returns font file path
	 *
	 * @param  string $font Font name.
	 * @return string
	 */
	public static function get_font_path( $font ) {

		if ( file_exists( $font ) && is_file( $font ) ) {
			$path = $font;
		} else {
			$path = EW_DIR_PATH . 'assets/dist/fonts/' . $font;

			if ( ! file_exists( $path ) || ! is_file( $path ) ) {
				$path = null;
			}
		}

		return apply_filters( 'easy-watermark/font-path', $path, $font );

	}
}
