<?php
/**
 * Assets class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Core;

use EasyWatermark\Helpers\Image as ImageHelper;
use EasyWatermark\Traits\Hookable;
use const EW_DIR_URL;
use const EW_DIR_PATH;

/**
 * Assets class
 */
class Assets {

	use Hookable;

	/**
	 * Flag whether assets has been registered already
	 *
	 * @var boolean
	 */
	private $registered = false;

	/**
	 * Plugin slug
	 *
	 * @var string
	 */
	private $plugin_slug;

	/**
	 * Watermark handler instance
	 *
	 * @var \EasyWatermark\Watermark\Handler
	 */
	private $handler;

	/**
	 * Constructor
	 *
	 * @param Plugin $plugin Plugin instance.
	 */
	public function __construct( Plugin $plugin ) {

		$this->hook();

		$this->handler     = $plugin->get_watermark_handler();
		$this->plugin_slug = $plugin->get_slug();

	}

	/**
	 * Registers admin scripts/styles
	 *
	 * @action admin_enqueue_scripts 20
	 *
	 * @return void
	 */
	public function register_admin_scripts() {

		if ( true === $this->registered ) {
			return;
		}

		$assets = $this->get_assets_list();

		if ( class_exists( 'FileBird' ) && wp_script_is( 'njt-filebird-upload-libray-scripts' ) ) {
			/**
			 * Add dependency to load FileBird script before ours.
			 *
			 * This script is not used in list mode, so we need to check if it is enqueued first.
			 */
			$assets['uploader']['dependencies'][] = 'njt-filebird-upload-libray-scripts';
		}

		foreach ( $assets as $asset => $data ) {
			$in_footer = true;

			if ( 'uploader' === $asset ) {
				$in_footer = false;
			}

			if ( false !== $this->asset_path( 'scripts', "{$asset}.js" ) ) {
				wp_register_script( "ew-{$asset}", $this->asset_url( 'scripts', "{$asset}.js" ), $data['dependencies'], $data['version'], $in_footer );
			}

			if ( false !== $this->asset_path( 'styles', "{$asset}.css" ) ) {
				wp_register_style( "ew-{$asset}", $this->asset_url( 'styles', "{$asset}.css" ), [], $data['version'] );
			}
		}

		$this->registered = true;

	}

	/**
	 * Loads admin scripts/styles
	 *
	 * @action admin_enqueue_scripts 30
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts() {

		$current_screen = get_current_screen();
		$enqueue        = false;
		$localize       = [];

		switch ( $current_screen->id ) {
			case 'attachment':
				$enqueue  = 'attachment-edit';
				$localize = [
					'genericErrorMessage' => __( 'Something went wrong. Please refresh the page and try again.', 'easy-watermark' ),
				];
				break;
			case 'tools_page_easy-watermark':
				// phpcs:disable WordPress.Security.NonceVerification
				if ( isset( $_GET['watermark'] ) || ( isset( $_GET['action'] ) && 'new' === $_GET['action'] ) ) {
					wp_enqueue_media();

					if ( isset( $_GET['watermark'] ) ) {
						$watermark_id = intval( $_GET['watermark'] );
						// phpcs:enable
					} else {
						$watermark    = get_default_post_to_edit( 'watermark', true );
						$watermark_id = $watermark->ID;
					}

					$post_types = array_merge(
						[
							'unattached' => [
								'name'  => 'unattached',
								'label' => __( 'Unattached', 'easy-watermark' ),
							],
						],
						array_filter(
							get_post_types( [ 'public' => true ], 'objects' ),
							function( $key ) {
								return 'attachment' !== $key;
							},
							ARRAY_FILTER_USE_KEY
						)
					);

					$permission_settings_url = add_query_arg( [
						'page' => $this->plugin_slug,
						'tab'  => 'permissions',
					], admin_url( 'tools.php' ) );

					$enqueue  = 'watermark-editor';
					$localize = [
						'watermarkID'           => $watermark_id,
						'namespace'             => $this->plugin_slug,
						'imageSizes'            => ImageHelper::get_available_sizes(),
						'mimeTypes'             => ImageHelper::get_available_mime_types(),
						'postTypes'             => $post_types,
						'editorSettings'        => apply_filters( 'easy-watermark/get-editor-settings', get_option( "{$this->plugin_slug}-editor-settings", [] ) ),
						'permissionSettingsURL' => $permission_settings_url,
					];
					break;
				}

				$enqueue  = 'dashboard';
				$localize = [
					'nonce' => wp_create_nonce( 'get_attachments' ),
					'i18n'  => [
						/* translators: watermark name */
						'deleteConfirmation'         => sprintf( __( 'You are about to permanently delete "%s". Are you sure?', 'easy-watermark' ), '{watermarkName}' ),
						'noItemsToWatermark'         => __( 'There are no images eligible for watermarking.', 'easy-watermark' ),
						'noItemsToRestore'           => __( 'There are no backed up images.', 'easy-watermark' ),
						/* translators: watermarked images number */
						'watermarkingStatus'         => sprintf( __( 'Watermarked %s images', 'easy-watermark' ), '{counter}' ),
						/* translators: watermarked images number */
						'restoringStatus'            => sprintf( __( 'Restored %s images', 'easy-watermark' ), '{counter}' ),
						/* translators: watermarked images number */
						'watermarkingSuccessMessage' => sprintf( __( 'Successfully watermarked %s images.', 'easy-watermark' ), '{procesed}' ),
						/* translators: watermarked images number */
						'restoringSuccessMessage'    => sprintf( __( 'Successfully restored %s images.', 'easy-watermark' ), '{procesed}' ),
						/* translators: %1&s - image title, %2&s - error content */
						'bulkActionErrorMessage'     => sprintf( __( 'An error occured while processing %1$s: %2$s', 'easy-watermark' ), '{imageTitle}', '{error}' ),
					],
				];
				break;
			case 'upload':
				if ( ! current_user_can( 'apply_watermark' ) ) {
					break;
				}

				$this->wp_enqueue_media();
				$enqueue  = 'media-library';
				$localize = [
					'watermarks'           => $this->get_watermarks(),
					'mime'                 => ImageHelper::get_available_mime_types(),
					'applyAllNonce'        => wp_create_nonce( 'apply_all' ),
					'applySingleNonces'    => $this->get_watermark_nonces(),
					'restoreBackupNonce'   => wp_create_nonce( 'restore_backup' ),
					'attachmentsInfoNonce' => wp_create_nonce( 'attachments_info' ),
					'i18n'                 => [
						'noItemsSelected'                => __( 'No items selected', 'easy-watermark' ),
						'watermarkModeToggleButtonLabel' => __( 'Watermark Selected', 'easy-watermark' ),
						'watermarkButtonLabel'           => __( 'Watermark', 'easy-watermark' ),
						'restoreButtonLabel'             => __( 'Restore original images', 'easy-watermark' ),
						'cancelLabel'                    => __( 'Cancel', 'easy-watermark' ),
						'selectWatermarkLabel'           => __( 'Select Watermark', 'easy-watermark' ),
						'allWatermarksLabel'             => __( 'All Watermarks', 'easy-watermark' ),
						'notSupported'                   => _x( 'Not supported', 'label for unsupported attachment type (other than image)', 'easy-watermark' ),
						'usedAsWatermark'                => _x( 'Used as watermark', 'label for image used as watermark', 'easy-watermark' ),
						'noBackupAvailable'              => _x( 'No backup available', 'label for attachments which has no backup to restore', 'easy-watermark' ),
						'watermarkingNoItems'            => __( 'None from the selected items qualified for watermarking.', 'easy-watermark' ),
						'restoringNoItems'               => __( 'No backup available for any of selected items.', 'easy-watermark' ),
						/* translators: watermarked images number */
						'watermarkingStatus'             => sprintf( __( 'Watermarked %s images', 'easy-watermark' ), '{counter}' ),
						/* translators: watermarked images number */
						'restoringStatus'                => sprintf( __( 'Restored %s images', 'easy-watermark' ), '{counter}' ),
						/* translators: watermarked images number */
						'watermarkingSuccessMessage'     => sprintf( __( 'Successfully watermarked %s images.', 'easy-watermark' ), '{procesed}' ),
						/* translators: watermarked images number */
						'restoringSuccessMessage'        => sprintf( __( 'Successfully restored %s images.', 'easy-watermark' ), '{procesed}' ),
						/* translators: %1&s - image title, %2&s - error content */
						'bulkActionErrorMessage'         => sprintf( __( 'An error occured while processing %1$s: %2$s', 'easy-watermark' ), '{imageTitle}', '{error}' ),
					],
				];
				break;
			case 'watermark':
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_media();
				$enqueue  = 'watermark-edit';
				$localize = [
					'autosaveNonce'     => wp_create_nonce( 'watermark_autosave' ),
					'previewImageNonce' => wp_create_nonce( 'preview_image' ),
				];
				break;
		}

		if ( $enqueue ) {
			$this->enqueue_asset( $enqueue, $localize );
		}

	}

	/**
	 * Loads scripts/styles altering WordPress media library
	 *
	 * @action wp_enqueue_media
	 *
	 * @return void
	 */
	public function wp_enqueue_media() {

		// In block editor wp_enqueue_media runs before admin_enqueue_scripts, so the scripts are not registered by now.
		$this->register_admin_scripts();
		$this->enqueue_asset( 'uploader', [
			'autoWatermark' => true,
		] );

	}

	/**
	 * Enqueues script/style and localizes if necessary
	 *
	 * @param  string $asset_name Asset name.
	 * @param  array  $localize   Localize data.
	 * @return void
	 */
	private function enqueue_asset( $asset_name, $localize = [] ) {

		$asset_name = 'ew-' . $asset_name;

		wp_enqueue_style( $asset_name );
		wp_enqueue_script( $asset_name );

		$localize['i18n'] = array_merge( isset( $localize['i18n'] ) ? $localize['i18n'] : [], [
			'yes'                 => __( 'Yes', 'easy-watermark' ),
			'ok'                  => __( 'OK', 'easy-watermark' ),
			'no'                  => __( 'Cancel', 'easy-watermark' ),
			'genericErrorMessage' => __( 'Something went wrong. Please refresh the page and try again.', 'easy-watermark' ),
		] );

		wp_localize_script( $asset_name, 'ew', $localize );

	}

	/**
	 * Returns asset url
	 *
	 * @param  string $type Asset type.
	 * @param  string $file Filename.
	 * @return string
	 */
	private function asset_url( $type, $file ) {
		return EW_DIR_URL . 'assets/dist/' . $type . '/' . $file;
	}

	/**
	 * Returns asset path
	 *
	 * @param  string $type Asset type.
	 * @param  string $file Filename.
	 * @return string
	 */
	private function asset_path( $type = null, $file = null ) {

		$path = EW_DIR_PATH . 'assets/dist/';

		if ( is_string( $type ) ) {
			$path .= "$type/";
		}

		if ( is_string( $file ) ) {
			$path .= $file;
		}

		if ( ! file_exists( $path ) ) {
			return false;
		}

		return $path;

	}

	/**
	 * Returns asset version
	 *
	 * @param  string $type Asset type.
	 * @param  string $file Filename.
	 * @return string|false
	 */
	private function asset_version( $type, $file ) {

		$path = $this->asset_path( $type, $file );

		if ( $path ) {
			return filemtime( $path );
		}

		return false;

	}

	/**
	 * Returns assets array
	 *
	 * @return array
	 */
	private function get_assets_list() {

		$path   = $this->asset_path( 'scripts' );
		$files  = scandir( $path );
		$assets = [];

		foreach ( $files as $file ) {
			if ( in_array( $file, [ '.', '..' ], true ) ) {
				continue;
			}

			$parts = explode( '.', $file );

			if ( 3 !== count( $parts ) || 'asset' !== $parts[1] || 'php' !== $parts[2] ) {
				continue;
			}

			$assets[ $parts[0] ] = include $this->asset_path( 'scripts', $file );
		}

		return $assets;

	}

	/**
	 * Returns watermarks list
	 *
	 * @return array
	 */
	private function get_watermarks() {

		$watermarks      = $this->handler->get_watermarks();
		$watermarks_list = [];

		foreach ( $watermarks as $watermark ) {
			$watermarks_list[ $watermark->ID ] = $watermark->post_title;
		}

		return $watermarks_list;

	}

	/**
	 * Returns watermarks list
	 *
	 * @return array
	 */
	private function get_watermark_nonces() {

		$watermarks = $this->handler->get_watermarks();
		$nonces     = [];

		foreach ( $watermarks as $watermark ) {
			$nonces[ $watermark->ID ] = wp_create_nonce( 'apply_single-' . $watermark->ID );
		}

		return $nonces;

	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}

}
