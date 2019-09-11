<?php
/**
 * Settings class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Dashboard;

use EasyWatermark\Core\Plugin;
use EasyWatermark\Core\View;
use EasyWatermark\Helpers\Image as ImageHelper;

/**
 * Settings class
 */
class Tools extends Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( __( 'Tools', 'easy-watermark' ), null, 20 );
	}

	/**
	 * Display admin notices
	 *
	 * @action easy-watermark/dashboard/settings/notices
	 *
	 * @return void
	 */
	public function admin_notices() {
		// phpcs:disable WordPress.Security
		if ( isset( $_GET['settings-updated'] ) ) {
			echo new View( 'notices/success', [
				'message' => __( 'Settings saved.', 'easy-watermark' ),
			] );
		}
		// phpcs:enable
	}

	/**
	 * Prepares arguments for view
	 *
	 * @filter easy-watermark/dashboard/tools/view-args
	 *
	 * @param  array $args View args.
	 * @return array
	 */
	public function view_args( $args ) {

		global $wpdb;

		$handler = Plugin::get()->get_watermark_handler();

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$backup_count = (int) $wpdb->get_var( "SELECT COUNT( post_id ) FROM {$wpdb->postmeta} WHERE meta_key = '_ew_has_backup'" );

		return [
			'watermarks'   => $handler->get_watermarks(),
			'backup_count' => $backup_count,
			'attachments'  => $this->get_attachments(),
		];
	}

	/**
	 * Gets attachments available for watermarking
	 *
	 * @param  string $mode Mode (watermark|restore).
	 * @return array
	 */
	private function get_attachments( $mode = 'watermark' ) {

		$mime_types = ImageHelper::get_available_mime_types();
		$result     = [];
		$posts      = get_posts( [
			'post_type'      => 'attachment',
			'post_mime_type' => array_keys( $mime_types ),
			'numberposts'    => -1,
		] );

		foreach ( $posts as $post ) {
			if ( get_post_meta( $post->ID, '_ew_used_as_watermark', true ) ) {
				// Skip images used as watermark.
				continue;
			}

			if ( 'restore' === $mode && ! get_post_meta( $post->ID, '_ew_has_backup', true ) ) {
				// In 'restore' mode skip items without backup.
				continue;
			}

			$result[] = [
				'id'    => $post->ID,
				'title' => $post->post_title,
			];
		}

		return $result;

	}

	/**
	 * Prepares arguments for view
	 *
	 * @action wp_ajax_easy-watermark/tools/get-attachments
	 *
	 * @return void
	 */
	public function ajax_get_attachments() {

		check_ajax_referer( 'get_attachments', 'nonce' );

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$mode   = isset( $_REQUEST['mode'] ) ? $_REQUEST['mode'] : null;
		$result = $this->get_attachments( $mode );

		wp_send_json_success( $result );

	}
}
