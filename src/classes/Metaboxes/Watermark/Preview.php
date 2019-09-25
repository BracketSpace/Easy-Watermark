<?php
/**
 * Metabox class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Metaboxes\Watermark;

use EasyWatermark\Core\View;
use EasyWatermark\Helpers\Image as ImageHelper;
use EasyWatermark\Metaboxes\WatermarkMetabox;
use EasyWatermark\Traits\Hookable;
use EasyWatermark\Watermark\Watermark;

/**
 * Metabox class
 */
class Preview extends WatermarkMetabox {

	use Hookable;

	/**
	 * Metabox position (normal|side|advanced)
	 *
	 * @var  string
	 */
	protected $position = 'side';

	/**
	 * Inits metabox
	 *
	 * @return void
	 */
	public function init() {
		$this->id    = 'preview';
		$this->title = __( 'Preview' );

		$this->hook();
	}

	/**
	 * Prepares params for metabox view
	 *
	 * @param  array  $params Params.
	 * @param  object $post Current post.
	 * @return array
	 */
	public function prepare_params( $params, $post ) {
		$watermark = Watermark::get( $post );

		$preview_image_id = get_option( '_ew_preview_image_id' );

		if ( $preview_image_id ) {
			$attachment = get_post( $preview_image_id );

			if ( null === $attachment ) {
				// Invalid attachment ID.
				$preview_image_id = false;
				delete_option( '_ew_preview_image_id' );
			}
		}

		$params['select_label'] = __( 'Select preview image', 'easy-watermark' );
		$params['change_label'] = __( 'Change preview image', 'easy-watermark' );
		$params['link_label']   = $preview_image_id ? $params['change_label'] : $params['select_label'];
		$params['has_image']    = (bool) $preview_image_id;

		$base_image_src  = site_url( 'easy-watermark-preview/image-%s-%s.png?t=%s' );
		$images          = [];
		$available_sizes = ImageHelper::get_available_sizes();
		$timestamp       = time();

		foreach ( $available_sizes as $size => $label ) {
			$src            = sprintf( $base_image_src, $post->ID, $size, $timestamp );
			$images[ $src ] = $label;
		}

		$params['images'] = $images;
		$params['popup']  = $this->get_preview_popup( $post->ID );

		return array_merge( $params, $watermark->get_params() );
	}

	/**
	 * Handles preview image selection
	 *
	 * @action wp_ajax_easy-watermark/preview_image
	 *
	 * @return void
	 */
	public function ajax_preview_image() {

		check_ajax_referer( 'preview_image', 'nonce' );

		if ( ! isset( $_REQUEST['attachment_id'] ) ) {
			wp_send_json_error( [
				'message' => __( 'No attachment id.', 'easy-watermark' ),
			] );
		}

		if ( ! isset( $_REQUEST['watermark_id'] ) ) {
			wp_send_json_error( [
				'message' => __( 'No watermark id.', 'easy-watermark' ),
			] );
		}

		$attachment_id = intval( $_REQUEST['attachment_id'] );
		$watermark_id  = intval( $_REQUEST['watermark_id'] );

		$result = update_option( '_ew_preview_image_id', $attachment_id );

		if ( true === $result ) {
			wp_send_json_success( [
				'popup' => (string) $this->get_preview_popup( $watermark_id ),
			] );
		}

		wp_send_json_error( [
			'message' => __( 'Saving preview image failed.', 'easy-watermark' ),
		] );

	}

	/**
	 * Returns preview popup content
	 *
	 * @param  integer $watermark_id Watermark ID.
	 * @return View|null
	 */
	public function get_preview_popup( $watermark_id ) {

		$preview_image_id = get_option( '_ew_preview_image_id' );

		$base_image_src  = site_url( 'easy-watermark-preview/image-%s-%s.png?t=%s' );
		$images          = [];
		$sizes           = [];
		$available_sizes = ImageHelper::get_available_sizes();
		$timestamp       = time();

		if ( $preview_image_id ) {
			$meta  = get_post_meta( $preview_image_id, '_wp_attachment_metadata', true );
			$sizes = $meta['sizes'];
		}

		foreach ( $available_sizes as $size => $label ) {
			if ( 'full' === $size || array_key_exists( $size, $sizes ) ) {
				$src            = sprintf( $base_image_src, $watermark_id, $size, $timestamp );
				$images[ $src ] = $label;
			}
		}

		return new View( 'edit-screen/metaboxes/watermark/preview-popup', [
			'images' => $images,
		] );
	}
}
