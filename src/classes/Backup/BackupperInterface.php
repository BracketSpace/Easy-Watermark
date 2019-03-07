<?php
/**
 * Backupper interface
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Backup;

/**
 * Backupper interface
 */
interface BackupperInterface {

	/**
	 * Creates backup
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @return true|WP_Error
	 */
	public function backup( $attachment_id );

	/**
	 * Restores backup
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @return true|WP_Error
	 */
	public function restore( $attachment_id );

	/**
	 * Removes backup
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @return true|WP_Error
	 */
	public function clean( $attachment_id );
}
