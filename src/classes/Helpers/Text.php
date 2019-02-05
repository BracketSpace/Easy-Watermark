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
}
