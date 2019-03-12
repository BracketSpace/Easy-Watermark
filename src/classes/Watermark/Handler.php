<?php
/**
 * Handler class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Watermark;

use EasyWatermark\Backup\BackupperInterface;
use EasyWatermark\Backup\Manager as BackupManager;
use EasyWatermark\Core\Settings;
use EasyWatermark\AttachmentProcessor;
use EasyWatermark\Metaboxes\Attachment\Watermarks;
use WP_Error;

/**
 * Handler class
 */
class Handler {

	/**
	 * If set to true, watermarks will not be applied.
	 *
	 * @var boolean
	 */
	private $lock = false;

	/**
	 * AttachmentProcessor instance
	 *
	 * @var AttachmentProcessor
	 */
	private $processor;

	/**
	 * Backupper instance
	 *
	 * @var Backupper
	 */
	private $backupper;

	/**
	 * Settings instance
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * Temporarily stored meta for newly uploaded attachment
	 *
	 * @var Settings
	 */
	private $tmp_meta;

	/**
	 * Constructor
	 */
	public function __construct() {

		new Ajax( $this );
		new Hooks( $this );

		$this->settings = Settings::get();

		if ( true === $this->settings->backup && $this->settings->backupper ) {
			$backupper = $this->settings->backupper;
		} else {
			// If backup is not enabled load local backuper for temporary backups.
			$backupper = 'local';
		}

		$this->backupper = BackupManager::get()->get_backupper( $backupper );

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
			'gd' => 'EasyWatermark\AttachmentProcessor\AttachmentProcessorGD',
		];

		return apply_filters( 'easy_watermark/image_processors', $processors );

	}

	/**
	 * Returns image processor to use
	 *
	 * @return AttachmentProcessor
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

  	global $post;

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

		$types = apply_filters( 'easy_watermark/watermark_types', $types );

		$watermarks = $this->get_watermarks();

		foreach ( $watermarks as $watermark ) {
			if ( $watermark->ID === $post->ID ) {
				continue;
			}

			if ( array_key_exists( $watermark->type, $types ) ) {
				$types[$watermark->type]['available'] = false;
			}
		}

		return $types;

	}

	/**
	 * Applies single watermark
	 *
	 * @param  integer $attachment_id Attachment id.
	 * @param  integer $watermark_id  Watermark id.
	 * @return boolean|WP_Error
	 */
	public function apply_single_watermark( $attachment_id, $watermark_id ) {

		if ( true === $this->lock ) {
			return true;
		}

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

		if ( true === $this->lock ) {
			return true;
		}

		$watermarks = $this->get_watermarks();

		return $this->apply_watermarks( $attachment_id, $watermarks );

	}

	/**
	 * Applies single watermark
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @param  array   $watermarks    Array of Watermark objects.
	 * @param  array   $meta          Attachment metadata.
	 * @return true|WP_Error
	 */
	public function apply_watermarks( $attachment_id, $watermarks, $meta = [] ) {

		if ( true === $this->lock ) {
			return true;
		}

		if ( empty( $watermarks ) ) {
			return true;
		}

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

		$this->do_backup( $attachment_id );

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
					if ( is_wp_error( $result ) ) {
						/* translators: watermark name, image size, original error message. */
						$error->add( 'watermark_error', sprintf( __( 'Watermark "%1$s" couldn\'t be applied for "%2$s" image size: %3$s', 'easy-watermark' ), Watermark::get( $watermark_id )->post_title, $size, $result->get_error_message() ) );
					}
				}
			}
		}

		$error_messages = $error->get_error_messages();
		$has_error      = ! empty( $error_messages );

		if ( false === $this->settings->backup || true === $has_error ) {
			$this->clean_backup( $attachment_id );
		}

		if ( empty( $error_messages ) ) {
			update_post_meta( $attachment_id, '_ew_applied_watermarks', $applied_watermarks );
			update_post_meta( $attachment_id, '_ew_attachment_version', time() );

			return true;
		}

		$this->restore_backup( $attachment_id, $meta );

		return $error;

	}

	/**
	 * Performs attachment backup
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @return true|WP_Error
	 */
	public function do_backup( $attachment_id ) {

		if ( ! $this->backupper instanceof BackupperInterface ) {
			return;
		}

		$has_backup = get_post_meta( $attachment_id, '_ew_has_backup', true );

		if ( '1' === $has_backup ) {
			return;
		}

		$backed_up = $this->backupper->backup( $attachment_id );

		if ( is_wp_error( $backed_up ) ) {
			return $backed_up;
		}

		update_post_meta( $attachment_id, '_ew_has_backup', true );

		return true;

	}

	/**
	 * Restores attachment backup
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @param  array   $old_meta      Attachment metadata array.
	 * @return true|WP_Error
	 */
	public function restore_backup( $attachment_id, $old_meta ) {

		if ( ! $this->backupper instanceof BackupperInterface ) {
			return;
		}

		// Set lock to prevent regenerated thumbnails from being watermarked.
		$this->lock = true;

		$restored = $this->backupper->restore( $attachment_id );

		if ( is_wp_error( $restored ) ) {
			return $restored;
		}

		$current_file = get_attached_file( $attachment_id );
		$filebasename = wp_basename( $current_file );

		foreach ( $old_meta['sizes'] as $size => $image ) {
			$file = str_replace( $filebasename, wp_basename( $image['file'] ), $current_file );
			unlink( $file );
		}

		$meta = wp_generate_attachment_metadata( $attachment_id, $current_file );

		wp_update_attachment_metadata( $attachment_id, $meta );

		update_post_meta( $attachment_id, '_ew_attachment_version', time() );
		delete_post_meta( $attachment_id, '_ew_applied_watermarks' );

		$this->lock = false;

		return true;

	}

	/**
	 * Removes attachment backup
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @return true|WP_Error
	 */
	public function clean_backup( $attachment_id ) {

		if ( ! $this->backupper instanceof BackupperInterface ) {
			return;
		}

		$result = $this->backupper->clean( $attachment_id );

		if ( is_wp_error( $restored ) ) {
			return $restored;
		}

		delete_post_meta( $attachment_id, '_ew_has_backup' );

		return true;

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
