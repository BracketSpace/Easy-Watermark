<?php
/**
 * Image helper
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Helpers;

class Image {
	public static function getAvailableSizes() {
		global $_wp_additional_image_sizes;

		$size_names = apply_filters( 'image_size_names_choose', array(
			'thumbnail'    => __( 'Thumbnail' ),
			'medium'       => __( 'Medium' ),
			'medium_large' => __( 'Intermediate' ),
			'large'        => __( 'Large' ),
			'full'         => __( 'Full Size' )
		) );

		$available_sizes = get_intermediate_image_sizes();
		array_push( $available_sizes, 'full' );

		$sizes = [];
		foreach( $available_sizes as $size ) {
			if ( array_key_exists( $size, $size_names ) ) {
				$sizes[$size] = $size_names[$size];
			} else {
				$sizes[$size] = $size;
			}
		}

		return $sizes;
	}

	public static function getAvailableMimeTypes() {
		return [
			'image/jpeg' => 'JPEG',
			'image/png'  => 'PNG',
			'image/gif'  => 'GIF'
		];
	}
}
