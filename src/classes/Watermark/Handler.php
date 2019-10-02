<?php
/**
 * Handler class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Watermark;

use EasyWatermark\AttachmentProcessor;
use EasyWatermark\AttachmentProcessor\Manager as ProcessorManager;
use EasyWatermark\Backup\BackupperInterface;
use EasyWatermark\Backup\Manager as BackupManager;
use EasyWatermark\Settings\Settings;
use EasyWatermark\Helpers\Image as ImageHelper;
use EasyWatermark\Metaboxes\Attachment\Watermarks;
use EasyWatermark\Placeholders\Resolver;
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
	 * Placeholders Resolver instance
	 *
	 * @var Resolver
	 */
	private $resolver;

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

		$backup    = $this->settings->get_setting( 'backup/backup' );
		$backupper = $this->settings->get_setting( 'backup/backupper' );

		if ( false === $backup || ! $backupper ) {
			// If backup is not enabled load local backuper for temporary backups.
			$backupper = 'local';
		}

		$this->backupper = BackupManager::get()->get_object( $backupper );
		$this->processor = ProcessorManager::get()->get_object( 'gd' );
		$this->resolver  = Resolver::get();

		$this->processor->set_param( 'jpeg_quality', $this->settings->get_setting( 'general/jpeg_quality' ) );

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
	 * Returns available watermark types
	 *
	 * @return array
	 */
	public function get_watermark_types() {

		global $post;

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

		$types = apply_filters( 'easy-watermark/watermark-types', $types );

		$watermarks = $this->get_watermarks();

		foreach ( $watermarks as $watermark ) {
			if ( $post && $watermark->ID === $post->ID ) {
				continue;
			}

			if ( array_key_exists( $watermark->type, $types ) ) {
				$types[ $watermark->type ]['available'] = false;
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
	 * @return bool|WP_Error
	 */
	public function apply_watermarks( $attachment_id, $watermarks, $meta = [] ) {

		if ( true === $this->lock ) {
			return false;
		}

		if ( empty( $watermarks ) ) {
			return false;
		}

		$used_as_watermark = get_post_meta( $attachment_id, '_ew_used_as_watermark', true );

		if ( $used_as_watermark ) {
			return false;
		}

		$attachment           = get_post( $attachment_id );
		$available_mime_types = ImageHelper::get_available_mime_types();

		if ( ! array_key_exists( $attachment->post_mime_type, $available_mime_types ) ) {
			return false;
		}

		$error = new WP_Error();

		$this->resolver->reset();
		$this->resolver->set_attachment( $attachment );

		$applied_watermarks = get_post_meta( $attachment_id, '_ew_applied_watermarks', true );

		if ( ! is_array( $applied_watermarks ) ) {
			$applied_watermarks = [];
		}

		$watermarks = array_filter( $watermarks, function( $watermark ) use ( $applied_watermarks ) {
			return ! array_key_exists( $watermark->ID, $applied_watermarks );
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

					if ( 'text' === $watermark->type ) {
							$watermark->text = $this->resolver->resolve( $watermark->text );
					}

					$this->processor->add_watermark( $watermark );

					if ( ! array_key_exists( $watermark->ID, $applied_watermarks ) ) {
						$applied_watermarks[ $watermark->ID ] = $watermark->post_title;
					}
				}
			}

			if ( true === $apply ) {
				$image_file = str_replace( $baename, wp_basename( $image['file'] ), $filepath );

				$this->processor
					->set_file( $image_file )
					->set_param( 'image_type', $image['mime-type'] );

				$results = $this->processor->process();

				$this->processor->clean();

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
		if ( false === $this->settings->get_setting( 'backup/backup' ) || true === $has_error ) {
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
	 * @return bool|WP_Error
	 */
	public function do_backup( $attachment_id ) {

		if ( ! $this->backupper instanceof BackupperInterface ) {
			return false;
		}

		$has_backup = get_post_meta( $attachment_id, '_ew_has_backup', true );

		if ( '1' === $has_backup ) {
			return false;
		}

		$result = $this->backupper->backup( $attachment_id );

		if ( is_wp_error( $result ) ) {
			return $result;
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

		$has_backup = get_post_meta( $attachment_id, '_ew_has_backup', true );

		if ( '1' !== $has_backup ) {
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
			file_exists( $file ) && unlink( $file );
		}

		wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $current_file ) );

		update_post_meta( $attachment_id, '_ew_attachment_version', time() );
		delete_post_meta( $attachment_id, '_ew_applied_watermarks' );
		delete_post_meta( $attachment_id, '_ew_has_backup' );

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

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		delete_post_meta( $attachment_id, '_ew_has_backup' );

		return true;

	}

	/**
	 * Prints text preview
	 *
	 * @param  Watermark $watermark Watermark object.
	 * @param  string    $format       Preview format (jpg|png).
	 * @return void
	 */
	public function print_text_preview( $watermark, $format ) {

		if ( 'text' !== $watermark->type ) {
			return;
		}

		if ( $watermark->text ) {
			$watermark->text = $this->resolver->resolve( $watermark->text );
		} else {
			$watermark->text = sprintf( '{%s}', _x( 'no_text', 'Placeholder for watermark text preview if no text specified.', 'easy-watermark' ) );
		}

		$result = $this->processor->print_text_preview( $watermark, $format );

		if ( ! is_wp_error( $result ) ) {
			exit;
		}

	}

	/**
	 * Prints watermark preview
	 *
	 * @param  Watermark $watermark Watermark object.
	 * @param  string    $format    Preview format (jpg|png).
	 * @param  string    $size      Preview image size.
	 * @return void
	 */
	public function print_preview( $watermark, $format, $size ) {

		$attachment_id = get_option( '_ew_preview_image_id' );

		if ( ! $attachment_id ) {
			return;
		}

		if ( ! in_array( $size, $watermark->image_sizes, true ) ) {
			$watermark = null;
		}

		$attachment = get_post( $attachment_id );

		if ( 'text' === $watermark->type ) {
			$this->resolver->set_attachment( $attachment );
			$watermark->text = $this->resolver->resolve( $watermark->text );
		}

		$meta = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );

		$filepath = get_attached_file( $attachment_id );
		$sizes    = $meta['sizes'];
		$baename  = wp_basename( $meta['file'] );

		$sizes['full'] = [
			'file'      => $meta['file'],
			'mime-type' => $attachment->post_mime_type,
		];

		if ( ! array_key_exists( $size, $sizes ) ) {
			return false;
		}

		$image      = $sizes[ $size ];
		$image_file = str_replace( $baename, wp_basename( $image['file'] ), $filepath );

		$this->processor->set_file( $image_file );

		$result = $this->processor->print_preview( $watermark, $format );

		if ( ! is_wp_error( $result ) ) {
			exit;
		}

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
