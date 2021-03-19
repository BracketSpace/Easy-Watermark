<?php
/**
 * Hooks class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Watermark;

use EasyWatermark\Helpers\Image as ImageHelper;
use EasyWatermark\Traits\Hookable;

/**
 * Hooks class
 */
class Hooks {

	use Hookable;

	/**
	 * Watermark Handler instance
	 *
	 * @var Handler
	 */
	private $handler;

	/**
	 * Constructor
	 *
	 * @param Handler $handler Handler instance.
	 */
	public function __construct( $handler ) {

		$this->hook();

		$this->handler = $handler;

	}

	/**
	 * Cleans backup on attachment removal
	 *
	 * @action delete_attachment
	 *
	 * @param  integer $attachment_id Image attachment ID.
	 * @return void
	 */
	public function delete_attachment( $attachment_id ) {

		$has_backup = get_post_meta( $attachment_id, '_ew_has_backup', true );

		if ( '1' === $has_backup ) {
			$this->handler->clean_backup( $attachment_id );
		}

	}

	/**
	 * Applies watermarks after upload
	 *
	 * @filter wp_generate_attachment_metadata
	 *
	 * @param  array   $metadata      Attachment metadata.
	 * @param  integer $attachment_id Attachment ID.
	 * @return array
	 */
	public function wp_generate_attachment_metadata( $metadata, $attachment_id ) {

		$auto_watermark = true;

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		if ( isset( $_REQUEST['auto_watermark'] ) ) {
			$auto_watermark = filter_var( wp_unslash( $_REQUEST['auto_watermark'] ), FILTER_VALIDATE_BOOLEAN );
		}
		// phpcs:enable

		if ( ! $auto_watermark ) {
			return $metadata;
		}

		$all_watermarks = $this->handler->get_watermarks();

		$watermarks = [];

		foreach ( $all_watermarks as $watermark ) {
			if ( ! $watermark->auto_add ) {
				continue;
			}

			if ( ! $watermark->auto_add_all && ! current_user_can( 'apply_watermark' ) ) {
				continue;
			}

			$mime_type = get_post_mime_type( $attachment_id );

			if ( ! in_array( $mime_type, $watermark->image_types, true ) ) {
				continue;
			}

			$attachment = get_post( $attachment_id );

			if ( $attachment->post_parent > 0 ) {
				$post_type = get_post_type( $attachment->post_parent );
			} else {
				$post_type = 'unattached';
			}

			if ( ! in_array( $post_type, $watermark->post_types, true ) ) {
				continue;
			}

			$watermarks[] = $watermark;
		}

		$this->handler->apply_watermarks( $attachment_id, $watermarks, $metadata );

		return $metadata;

	}

	/**
	 * Filters the attachment data prepared for JavaScript.
	 *
	 * @filter wp_prepare_attachment_for_js
	 *
	 * @param array       $response   Array of prepared attachment data.
	 * @param WP_Post     $attachment Attachment object.
	 * @param array|false $meta       Array of attachment meta data, or false if there is none.
	 */
	public function wp_prepare_attachment_for_js( $response, $attachment, $meta ) {
		if ( false !== strpos( $attachment->post_mime_type, '/' ) ) {
			list( $type ) = explode( '/', $attachment->post_mime_type );
		} else {
			$type = $attachment->post_mime_type;
		}

		if ( 'image' !== $type ) {
			return $response;
		}

		$response['nonces']['watermark'] = wp_create_nonce( 'watermark' );
		$response['usedAsWatermark']     = get_post_meta( $attachment->ID, '_ew_used_as_watermark', true ) ? true : false;
		$response['hasBackup']           = get_post_meta( $attachment->ID, '_ew_has_backup', true ) ? true : false;

		$meta            = wp_get_attachment_metadata( $attachment->ID );
		$available_sizes = ImageHelper::get_available_sizes();
		$has_all_sizes   = true;
		$real_sizes      = [];

		foreach ( $available_sizes as $size => $label ) {
			if ( 'full' === $size ) {
				continue;
			}

			if ( ! array_key_exists( $size, $meta['sizes'] ) ) {
				$has_all_sizes = false;
				break;
			}

			$size_meta      = $meta['sizes'][ $size ];
			$attachment_url = wp_get_attachment_url( $attachment->ID );
			$base_url       = str_replace( wp_basename( $attachment_url ), '', $attachment_url );

			$real_sizes[ $size ] = [
				'height'      => $size_meta['height'],
				'width'       => $size_meta['width'],
				'url'         => $base_url . $size_meta['file'],
				'orientation' => $size_meta['height'] > $size_meta['width'] ? 'portrait' : 'landscape',
			];
		}

		$response['hasAllSizes'] = $has_all_sizes;

		if ( $has_all_sizes ) {
			$real_sizes['full'] = $response['sizes']['full'];

			$response['realSizes'] = $real_sizes;
		}

		return $response;
	}

	/**
	 * Adds bulk actions
	 *
	 * @filter bulk_actions-upload
	 *
	 * @param array $bulk_actions Bulk actions.
	 * @return array
	 */
	public function bulk_actions( $bulk_actions ) {
		$bulk_actions['watermark'] = __( 'Watermark' );
		$bulk_actions['restore']   = __( 'Restore original images' );

		return $bulk_actions;
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}
}
