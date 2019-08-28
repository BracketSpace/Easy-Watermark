<?php
/**
 * AjaxHandler class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Watermark;

use EasyWatermark\Traits\Hookable;
use EasyWatermark\Metaboxes\Attachment\Watermarks;

/**
 * AjaxHandler class
 */
class Ajax {

	use Hookable;

	/**
	 * Watermark Handler instance
	 *
	 * @var Handler
	 */
	private $watermark_handler;

	/**
	 * Constructor
	 *
	 * @param Handler $handler Handler instance.
	 */
	public function __construct( $handler ) {

		$this->hook();

		$this->watermark_handler = $handler;

	}

	/**
	 * Applies single watermark
	 *
	 * @action wp_ajax_easy-watermark/apply_single
	 *
	 * @return void
	 */
	public function apply_single_watermark() {

		if ( ! isset( $_REQUEST['watermark'] ) || ! isset( $_REQUEST['attachment_id'] ) ) {
			wp_send_json_error( [
				'message' => __( 'Something went wrong. Please try again.', 'easy-watermark' ),
			] );
		}

		$watermark_id = intval( $_REQUEST['watermark'] );

		check_ajax_referer( 'apply_single-' . $watermark_id, 'nonce' );

		if ( ! current_user_can( 'apply_watermark' ) ) {
			wp_send_json_error( [
				'message' => __( 'You don\'t have permission to apply watermarks.', 'easy-watermark' ),
			] );
		}

		$attachment_id   = intval( $_REQUEST['attachment_id'] );
		$result          = $this->watermark_handler->apply_single_watermark( $attachment_id, $watermark_id );
		$version         = get_post_meta( $attachment_id, '_ew_attachment_version', true );
		$success_message = __( 'Watermark has been applied.', 'easy-watermark' );

		$this->send_response( $result, $version, $attachment_id, $success_message );

	}

	/**
	 * Applies all watermarks
	 *
	 * @action wp_ajax_easy-watermark/apply_all
	 *
	 * @return void
	 */
	public function apply_all_watermarks() {

		check_ajax_referer( 'apply_all', 'nonce' );

		if ( ! isset( $_REQUEST['attachment_id'] ) ) {
			wp_send_json_error( [
				'message' => __( 'Something went wrong. Please try again.', 'easy-watermark' ),
			] );
		}

		if ( ! current_user_can( 'apply_watermark' ) ) {
			wp_send_json_error( [
				'messages' => [
					__( 'You don\'t have permission to apply watermarks.', 'easy-watermark' ),
				],
			] );
		}

		$attachment_id   = intval( $_REQUEST['attachment_id'] );
		$result          = $this->watermark_handler->apply_all_watermarks( $attachment_id );
		$version         = get_post_meta( $attachment_id, '_ew_attachment_version', true );
		$success_message = __( 'All watermarks has been applied.', 'easy-watermark' );

		$this->send_response( $result, $version, $attachment_id, $success_message );

	}

	/**
	 * Restores backup
	 *
	 * @action wp_ajax_easy-watermark/restore_backup
	 *
	 * @return void
	 */
	public function restore_backup() {

		check_ajax_referer( 'restore_backup', 'nonce' );

		if ( ! isset( $_REQUEST['attachment_id'] ) ) {
			wp_send_json_error( [
				'message' => __( 'Something went wrong. Please try again.', 'easy-watermark' ),
			] );
		}

		if ( ! current_user_can( 'apply_watermark' ) ) {
			wp_send_json_error( [
				'messages' => [
					__( 'You don\'t have permission to restore backup.', 'easy-watermark' ),
				],
			] );
		}

		$attachment_id   = intval( $_REQUEST['attachment_id'] );
		$meta            = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );
		$result          = $this->watermark_handler->restore_backup( $attachment_id, $meta );
		$version         = get_post_meta( $attachment_id, '_ew_attachment_version', true );
		$success_message = __( 'Original image has been restored.', 'easy-watermark' );

		$this->send_response( $result, $version, $attachment_id, $success_message );

	}

	/**
	 * Temporarily saves watermark settings
	 *
	 * @action wp_ajax_easy-watermark/autosave
	 *
	 * @return void
	 */
	public function autosave() {

		check_ajax_referer( 'watermark_autosave', 'nonce' );

		if ( ! isset( $_REQUEST['watermark'] ) || ! isset( $_REQUEST['post_ID'] ) ) {
			wp_send_json_error( [
				'message' => __( 'No watermark data to save.', 'easy-watermark' ),
			] );
		}

		$post_id = intval( $_REQUEST['post_ID'] );

		// phpcs:ignore
		$result = update_post_meta( $post_id, '_ew_tmp_params', $_REQUEST['watermark'] );

		if ( false === $result ) {
			wp_send_json_error( [
				'message' => __( 'Something went wrong while saving temporary data.', 'easy-watermark' ),
			] );
		}

		wp_send_json_success( $result );

	}

	/**
	 * Sends response
	 *
	 * @param  mixed   $result          Watermarking result.
	 * @param  string  $version         Attachment version.
	 * @param  integer $attachment_id   Attachment ID.
	 * @param  string  $success_message Success message.
	 * @return void
	 */
	protected function send_response( $result, $version, $attachment_id, $success_message ) {

		$response_data = [
			'metaboxContent'    => (string) Watermarks::get_content( get_post( $attachment_id ) ),
			'result'            => $result,
			'attachmentVersion' => $version,
			'hasBackup'         => get_post_meta( $attachment_id, '_ew_has_backup', true ),
		];

		if ( is_wp_error( $result ) ) {
			$messages = $result->get_error_messages();
			$count    = count( $messages );

			/* translators: errors count. */
			$error_message = sprintf( _n( '%s error has occured.', '%s errors has occured.', $count, 'easy-watermark' ), $count );

			$response_data['message'] = $error_message . '<ul><li>' . implode( '</li><li>', $messages ) . '</li></ul>';

			wp_send_json_error( $response_data );
		} else {
			$response_data['message'] = $success_message;

			wp_send_json_success( $response_data );
		}

	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}
}
