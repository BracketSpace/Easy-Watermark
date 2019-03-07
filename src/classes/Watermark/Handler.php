<?php
/**
 * Handler class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Watermark;

use EasyWatermark\ImageProcessor;
use EasyWatermark\Metaboxes\Attachment\Watermarks;
use WP_Error;

/**
 * Handler class
 */
class Handler {

	/**
	 * ImageProcessor instance
	 *
	 * @var ImageProcessor
	 */
	private $processor;

	/**
	 * Constructor
	 */
	public function __construct() {

		new Ajax( $this );
		new Hooks( $this );

	}

	/**
	 * Returns array of all published watermarks
	 *
	 * @return array
	 */
	public function get_watermarks() {

		$posts = get_posts( [
			'post_type'      => 'watermark',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		] );

		$watermarks = [];

		foreach ( $posts as $post ) {
			$watermarks[] = Watermark::get( $post );
		}

		return $watermarks;

	}

	/**
	 * Returns all image processors
	 *
	 * @return array
	 */
	public function get_all_image_processors() {

		$processors = [
			'gd' => 'EasyWatermark\ImageProcessor\ImageProcessorGD',
		];

		return apply_filters( 'easy_watermark/image_processors', $processors );

	}

	/**
	 * Returns image processor to use
	 *
	 * @return ImageProcessor
	 */
	public function get_image_processor() {

		if ( ! $this->processor ) {
			$processors = $this->get_all_image_processors();

			foreach ( $processors as $processor ) {
				if ( $processor::is_available() ) {
					$this->processor = new $processor();
					break;
				}
			}
		}

		return $this->processor;

	}

	/**
	 * Returns available watermark types
	 *
	 * @return array
	 */
	public function get_watermark_types() {

		$this->get_image_processor();

		$types = [
			'text'  => [
				'label'     => __( 'Text', 'easy-watermark' ),
				'available' => true,
			],
			'image' => [
				'label'     => __( 'Image', 'easy-watermark' ),
				'available' => true,
			],
		];

		return apply_filters( 'easy_watermark/watermark_types', $types );

	}

	/**
	 * Applies single watermark
	 *
	 * @param  integer $attachment_id Attachment id.
	 * @param  integer $watermark_id  Watermark id.
	 * @return boolean|WP_Error
	 */
	public function apply_single_watermark( $attachment_id, $watermark_id ) {

		$watermark = Watermark::get( $watermark_id );

		if ( 'publish' !== $watermark->post_status ) {
			return false;
		}

		return $this->apply_watermarks( $attachment_id, [ $watermark ] );

	}

	/**
	 * Applies single watermark
	 *
	 * @param  integer $attachment_id Attachment id.
	 * @return boolean|WP_Error
	 */
	public function apply_all_watermarks( $attachment_id ) {

		$watermarks = $this->get_watermarks();

		return $this->apply_watermarks( $attachment_id, $watermarks );

	}

	/**
	 * Applies single watermark
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @param  array   $watermarks    Array of Watermark objects.
	 * @param  array   $meta          Attachment metadata.
	 * @return boolean|WP_Error
	 */
	public function apply_watermarks( $attachment_id, $watermarks, $meta = [] ) {

		$processor  = $this->get_image_processor();
		$attachment = get_post( $attachment_id );
		$error      = new WP_Error();

		$applied_watermarks = get_post_meta( $attachment_id, '_ew_applied_watermarks', true );

		if ( ! is_array( $applied_watermarks ) ) {
			$applied_watermarks = [];
		}

		$watermarks = array_filter( $watermarks, function( $watermark ) use ( $applied_watermarks ) {
			return ! in_array( $watermark->ID, $applied_watermarks, true );
		} );

		if ( empty( $meta ) ) {
			$meta = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );
		}

		if ( ! $meta ) {
			$error->add( 'empty_metadata', __( 'You try to watermark an item that doesn\'t exist. Please refresh the page and try again.', 'easy-watermark' ) );

			return $error;
		}

		$filepath = get_attached_file( $attachment_id );
		$sizes    = $meta['sizes'];
		$baename  = wp_basename( $meta['file'] );

		$sizes['full'] = [
			'file'      => $meta['file'],
			'mime-type' => $attachment->post_mime_type,
		];

		$this->perform_backup( $attachment_id );

		foreach ( $sizes as $size => $image ) {
			$apply = false;

			foreach ( $watermarks as $watermark ) {
				if ( in_array( $size, $watermark->image_sizes, true ) ) {
					$apply = true;
					$processor->add_watermark( $watermark );
					$applied_watermarks[] = $watermark->ID;
				}
			}

			if ( true === $apply ) {
				$image_file = str_replace( $baename, wp_basename( $image['file'] ), $filepath );

				$processor->set_file( $image_file )
									->set_param( 'image_type', $image['mime-type'] );

				$results = $processor->process();

				$processor->clean();

				foreach ( $results as $watermark_id => $result ) {
					if ( false === $result ) {
						/* translators: watermark name. */
						$error->add( 'watermark_error', sprintf( __( 'Watermark "%1$s" couldn\'t be applied for "%2$s" image size.', 'easy-watermark' ), Watermark::get( $watermark_id )->post_title, $size ) );
					}
				}
			}
		}

		$error_messages = $error->get_error_messages();

		if ( empty( $error_messages ) ) {
			update_post_meta( $attachment_id, '_ew_applied_watermarks', $applied_watermarks );
			update_post_meta( $attachment_id, '_ew_attachment_version', time() );

			return true;
		}

		$this->restore_backup( $attachment_id );

		return $error;

	}

	/**
	 * Performs attachment backup
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @return void
	 */
	public function perform_backup( $attachment_id ) {

	}

	/**
	 * Restores attachment backup
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @return void
	 */
	public function restore_backup( $attachment_id ) {

	}

	/**
	 * Adds attachment version to the URL
	 *
	 * @param  string  $url Attachment url.
	 * @param  integer $attachment_id Attachment ID.
	 * @return string
	 */
	public function add_attachment_version( $url, $attachment_id ) {

		$version = get_post_meta( $attachment_id, '_ew_attachment_version', true );

		if ( ! $version ) {
			return $url;
		}

		return $url . '?v=' . $version;

	}
}
