<?php
/**
 * Local Backupper
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Backup;

use EasyWatermark\Traits\Hookable;
use WP_Error;

/**
 * LocalBackupper class
 */
class LocalBackupper implements BackupperInterface {

	use Hookable;

	/**
	 * Backup dir inside wp-content
	 *
	 * @var string
	 */
	protected $backup_dir;

	/**
	 * Constructor
	 */
	public function __construct() {

		$this->hook();

		$backup_dir = 'ew-backup';

		$this->backup_dir = apply_filters( 'easy-watermark/local-backupper-dir', $backup_dir );

	}

	/**
	 * Creates backup
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @return true|WP_Error
	 */
	public function backup( $attachment_id ) {

		$filepath = get_attached_file( $attachment_id );

		$new_filepath = $this->get_target_filepath( $filepath );

		if ( is_wp_error( $new_filepath ) ) {
			return $new_filepath;
		}

		$result = copy( $filepath, $new_filepath );

		if ( false === $result ) {
			return new WP_Error( 'backup_error', __( 'Could not copy attachment file to backup directory.', 'easy-watermark' ) );
		}

		update_post_meta( $attachment_id, '_ew_backup_file', $new_filepath );

		return true;

	}

	/**
	 * Restores backup
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @return boolean
	 */
	public function restore( $attachment_id ) {

		$backup_file  = get_post_meta( $attachment_id, '_ew_backup_file', true );
		$current_file = get_attached_file( $attachment_id );

		$restored = rename( $backup_file, $current_file );

		if ( false === $restored ) {
			return new WP_Error( 'backup_error', __( 'Could not restore attachment.', 'easy-watermark' ) );
		}

		delete_post_meta( $attachment_id, '_ew_backup_file' );

		return true;

	}

	/**
	 * Removes backup
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @return true|WP_Error
	 */
	public function clean( $attachment_id ) {

		$backup_file = get_post_meta( $attachment_id, '_ew_backup_file', true );

		if ( is_file( $backup_file ) ) {
			$removed = unlink( $backup_file );

			if ( false === $removed ) {
				return new WP_Error( 'backup_error', __( 'Could not remove backup file.', 'easy-watermark' ) );
			}

			delete_post_meta( $attachment_id, '_ew_backup_file' );
		}

		return true;

	}

	/**
	 * Returns backup filepath
	 *
	 * @param  string $filepath Original file path.
	 * @return string|WP_Error
	 */
	protected function get_target_filepath( $filepath ) {

		$upload_dir = wp_upload_dir();
		$filename   = wp_basename( $filepath );

		$subdir = str_replace( [ $upload_dir['basedir'], $filename ], '', $filepath );

		$backup_dir = $this->get_backup_dir( $subdir );

		if ( is_wp_error( $backup_dir ) ) {
			return $backup_dir;
		}

		return wp_normalize_path( $backup_dir . $filename );

	}

	/**
	 * Returns backup dir
	 *
	 * @param  string|null $path Path to append.
	 * @return string|WP_Error
	 */
	protected function get_backup_dir( $path = null ) {

		$backup_dir = trailingslashit( WP_CONTENT_DIR . '/' . $this->backup_dir . '/' . ltrim( $path, '/' ) );

		if ( ! is_dir( $backup_dir ) ) {
			$created = mkdir( $backup_dir, 0755, true );

			if ( ! $created ) {
				return new WP_Error( 'backup_error', __( 'Could not create backup directory.', 'easy-watermark' ) );
			}
		}

		return $backup_dir;

	}
}
