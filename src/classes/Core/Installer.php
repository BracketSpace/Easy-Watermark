<?php
/**
 * Installer class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Core;

use EasyWatermark\Watermark\Watermark;

/**
 * Helper class providing install, uninstall, update methods
 */
class Installer {

	/**
	 * Watermarked attachments
	 *
	 * @var array
	 */
	private static $watermarked_attachments = [];

	/**
	 * Activates plugin
	 *
	 * @return void
	 */
	public static function activate() {

		$version = get_option( Plugin::get()->get_slug() . '-version', false );

		if ( ! $version ) {
			// First activation.
			self::install();
		}

		$admin = get_role( 'administrator' );

		$admin->add_cap( 'edit_watermark' );
		$admin->add_cap( 'edit_watermarks' );
		$admin->add_cap( 'edit_others_watermarks' );
		$admin->add_cap( 'delete_watermarks' );
		$admin->add_cap( 'delete_others_watermarks' );
		$admin->add_cap( 'apply_watermark' );

		$editor = get_role( 'editor' );

		$admin->add_cap( 'edit_watermark' );
		$admin->add_cap( 'edit_watermarks' );
		$admin->add_cap( 'edit_others_watermarks' );
		$admin->add_cap( 'delete_watermarks' );
		$admin->add_cap( 'delete_others_watermarks' );
		$admin->add_cap( 'apply_watermark' );

		$author = get_role( 'author' );

		$admin->add_cap( 'edit_watermark' );
		$admin->add_cap( 'edit_watermarks' );
		$admin->add_cap( 'delete_watermarks' );
		$admin->add_cap( 'apply_watermark' );

	}

	/**
	 * Deactivates plugin
	 *
	 * @return void
	 */
	public static function deactivate() {
		delete_option( 'easy-watermark-first-booted' );
		flush_rewrite_rules();
	}

	/**
	 * Installs plugin
	 * This method is called on the first activation
	 *
	 * @return void
	 */
	public static function install() {}

	/**
	 * Uninstalls plugin
	 *
	 * @return void
	 */
	public static function uninstall() {

		delete_option( Plugin::get()->get_slug() . '-version' );

		$roles = get_editable_roles();

		foreach ( $roles as $role => $details ) {
			$role = get_role( $role );

			$role->remove_cap( 'edit_watermark' );
			$role->remove_cap( 'read_watermark' );
			$role->remove_cap( 'delete_watermark' );
			$role->remove_cap( 'edit_watermarks' );
			$role->remove_cap( 'edit_others_watermarks' );
			$role->remove_cap( 'publish_watermarks' );
			$role->remove_cap( 'read_private_watermarks' );
			$role->remove_cap( 'apply_watermark' );
		}

		$watermarks = Watermark::get_all();

		foreach ( $watermarks as $watermark ) {
			$result = wp_delete_post( $watermark->ID, true );
		}

	}

	/**
	 * Updates plugin
	 *
	 * @param  string $from     Previous active version.
	 * @param  array  $defaults Default settings.
	 * @return void
	 */
	public static function update( $from, $defaults ) {

		update_option( Plugin::get()->get_slug() . '-version', Plugin::get()->get_version() );

		if ( version_compare( $from, '1.0.0', '>=' ) ) {
			self::update_attachment_meta();
			return;
		}

		$plugin_slug = Plugin::get()->get_slug();
		$settings    = [];

		if ( version_compare( $from, '0.1.1', '>' ) ) {
			$settings['general'] = get_option( $plugin_slug . '-settings-general' );
			$settings['image']   = get_option( $plugin_slug . '-settings-image' );
			$settings['text']    = get_option( $plugin_slug . '-settings-text' );

			delete_option( $plugin_slug . '-settings-general' );
			delete_option( $plugin_slug . '-settings-image' );
			delete_option( $plugin_slug . '-settings-text' );
		} else {
			$old_settings = get_option( $plugin_slug . '-settings' );

			delete_option( $plugin_slug . '-settings' );

			$general = [
				'auto_add'    => $old_settings['auto_add'],
				'image_types' => $old_settings['image_types'],
			];

			switch ( $from ) {
				case '0.1.1':
					$image = [
						'watermark_url'  => $old_settings['image']['url'],
						'watermark_id'   => $old_settings['image']['id'],
						'watermark_path' => $old_settings['image']['path'],
						'watermark_mime' => $old_settings['image']['mime'],
						'position_x'     => $old_settings['image']['position_x'],
						'position_y'     => $old_settings['image']['position_y'],
						'offset_x'       => $old_settings['image']['offset_x'],
						'offset_y'       => $old_settings['image']['offset_y'],
						'opacity'        => $old_settings['image']['opacity'],
					];
					break;
				default:
					$image = [
						'watermark_url'  => $old_settings['image']['url'],
						'watermark_id'   => $old_settings['image']['id'],
						'watermark_path' => $old_settings['image']['path'],
						'watermark_mime' => $old_settings['image']['mime'],
						'position_x'     => $old_settings['image']['position-horizontal'],
						'position_y'     => $old_settings['image']['position-vert'],
						'offset_x'       => $old_settings['image']['offset-horizontal'],
						'offset_y'       => $old_settings['image']['offset-vert'],
						'opacity'        => $old_settings['image']['alpha'],
					];
					break;
			}

			$settings = [
				'general' => $general,
				'image'   => $image,
				'text'    => [],
			];

			delete_option( $plugin_slug . '-settings' );
		}

		$settings['image']['alignment'] = self::get_alignment( $settings['image']['position_x'], $settings['image']['position_y'] );
		$settings['text']['alignment']  = self::get_alignment( $settings['text']['position_x'], $settings['text']['position_y'] );

		$watermark_defaults = Watermark::get_defaults();

		$watermark_defaults['auto_add'] = $settings['general']['auto_add'];

		if ( isset( $settings['general']['auto_add_perm'] ) ) {
			$watermark_defaults['auto_add_all'] = $settings['general']['auto_add_perm'];
		}

		if ( isset( $settings['general']['image_types'] ) ) {
			$watermark_defaults['image_types'] = $settings['general']['image_types'];
		}

		if ( isset( $settings['general']['image_sizes'] ) ) {
			$watermark_defaults['image_sizes'] = $settings['general']['image_sizes'];
		}

		if ( isset( $settings['general']['allowed_post_types'] ) ) {
			$watermark_defaults['post_types'] = $settings['general']['allowed_post_types'];
		}

		if ( isset( $settings['general']['jpg_quality'] ) ) {
			$defaults['general']['jpeg_quality'] = $settings['general']['jpg_quality'];
		}

		update_option( Plugin::get()->get_slug() . '-settings', $defaults );

		if ( isset( $settings['image']['watermark_id'] ) && ! empty( $settings['image']['watermark_id'] ) ) {
			self::insert_image_watermark( $watermark_defaults, $settings );
		}

		if ( ! empty( $settings['text']['text'] ) ) {
			self::insert_text_watermark( $watermark_defaults, $settings );
		}

		self::update_backup_info();

	}

	/**
	 * Update attachment meta
	 *
	 * @return void
	 */
	private static function update_attachment_meta() {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$meta = $wpdb->get_results( $wpdb->prepare( "SELECT `post_id`, `meta_value` FROM {$wpdb->postmeta} WHERE `meta_key` = %s", '_ew_applied_watermarks' ) );

		foreach ( $meta as $entry ) {
			$value = maybe_unserialize( $entry->meta_value );

			if ( is_array( $value ) ) {
				$new_value = [];

				foreach ( $value as $watermark_id ) {
					$watermark = Watermark::get( $watermark_id );

					if ( $watermark ) {
						$new_value[ $watermark_id ] = $watermark->post_title;
					}
				}

				if ( $new_value ) {
					update_post_meta( $entry->post_id, '_ew_applied_watermarks', $new_value );
				} else {
					delete_post_meta( $entry->post_id, '_ew_applied_watermarks' );
				}
			}
		}
	}

	/**
	 * Updates backup info
	 *
	 * @return void
	 */
	private static function update_backup_info() {

		$attachments = get_posts( [
			'posts_per_page' => -1,
			'post_type'      => 'attachment',
			'meta_query'     => [
				[
					'key'     => '_ew_backup_file',
					'compare' => 'EXISTS',
				],
			],
		] );

		foreach ( $attachments as $attachment ) {
			update_post_meta( $attachment->ID, '_ew_has_backup', true );
		}

	}

	/**
	 * Updates backup info
	 *
	 * @return array
	 */
	private static function get_watermarked_attachments() {

		if ( ! self::$watermarked_attachments ) {
			self::$watermarked_attachments = get_posts( [
				'posts_per_page' => -1,
				'post_type'      => 'attachment',
				'meta_key'       => '_ew_watermarked',
				'meta_value'     => '1',
			] );
		}

		return self::$watermarked_attachments;

	}

	/**
	 * Inserts image watermark based on previous version settings
	 *
	 * @param  array $defaults Default watermark params.
	 * @param  array $settings Watermark settings.
	 * @return integer
	 */
	private static function insert_image_watermark( $defaults, $settings ) {

		$image_settings = [
			'type'          => 'image',
			'attachment_id' => $settings['image']['watermark_id'],
			'mime_type'     => $settings['image']['watermark_mime'],
			'url'           => $settings['image']['watermark_url'],
			'alignment'     => $settings['image']['alignment'],
			'opacity'       => $settings['image']['opacity'],
			'offset'        => [
				'x' => [
					'value' => intval( $settings['image']['offset_x'] ),
					'unit'  => ( '%' === substr( $settings['image']['offset_x'], -1 ) ) ? '%' : 'px',
				],
				'y' => [
					'value' => intval( $settings['image']['offset_y'] ),
					'unit'  => ( '%' === substr( $settings['image']['offset_y'], -1 ) ) ? '%' : 'px',
				],
			],
		];

		if ( isset( $settings['image']['scale_mode'] ) ) {
			$image_settings['scaling_mode'] = $settings['image']['scale_mode'];

			if ( 'fit' === $image_settings['scaling_mode'] ) {
				$image_settings['scaling_mode'] = 'contain';
			}

			if ( 'fill' === $image_settings['scaling_mode'] ) {
				$image_settings['scaling_mode'] = 'cover';
			}
		}

		if ( isset( $settings['image']['scale_to_smaller'] ) ) {
			$image_settings['scale_down_only'] = $settings['image']['scale_to_smaller'];
		}

		if ( isset( $settings['image']['scale'] ) ) {
			$image_settings['scale'] = $settings['image']['scale'];
		}

		$image_settings = array_merge( $defaults, $image_settings );

		$watermark_id = wp_insert_post( [
			'post_title'  => __( 'Image Watermark', 'easy-watermark' ),
			'post_type'   => 'watermark',
			'post_status' => 'publish',
			'watermark'   => $image_settings,
		] );

		if ( in_array( $settings['general']['watermark_type'], [ '1', '3' ], true ) ) {
			$attachments = self::get_watermarked_attachments();

			foreach ( $attachments as $attachment ) {
				self::add_applied_watermark( $attachment->ID, $watermark_id );
			}
		}

		return $watermark_id;

	}

	/**
	 * Inserts text watermark based on previous version settings
	 *
	 * @param  array $defaults Default watermark params.
	 * @param  array $settings Watermark settings.
	 * @return integer
	 */
	private static function insert_text_watermark( $defaults, $settings ) {

		$text_settings = [
			'type'       => 'text',
			'text'       => $settings['text']['text'],
			'font'       => $settings['text']['font'],
			'text_color' => $settings['text']['color'],
			'text_size'  => $settings['text']['size'],
			'text_angle' => $settings['text']['angle'],
			'opacity'    => $settings['text']['opacity'],
			'alignment'  => $settings['text']['alignment'],
			'offset'     => [
				'x' => [
					'value' => intval( $settings['text']['offset_x'] ),
					'unit'  => ( '%' === substr( $settings['text']['offset_x'], -1 ) ) ? '%' : 'px',
				],
				'y' => [
					'value' => intval( $settings['text']['offset_y'] ),
					'unit'  => ( '%' === substr( $settings['text']['offset_y'], -1 ) ) ? '%' : 'px',
				],
			],
		];

		$text_settings = array_merge( $defaults, $text_settings );

		$watermark_id = wp_insert_post( [
			'post_title'  => __( 'Text Watermark', 'easy-watermark' ),
			'post_type'   => 'watermark',
			'post_status' => 'publish',
			'watermark'   => $text_settings,
		] );

		if ( in_array( $settings['general']['watermark_type'], [ '2', '3' ], true ) ) {
			$attachments = self::get_watermarked_attachments();

			foreach ( $attachments as $attachment ) {
				self::add_applied_watermark( $attachment->ID, $watermark_id );
			}
		}

		return $watermark_id;

	}

	/**
	 * Add watermark to attachment meta
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @param  integer $watermark_id  Watermark ID.
	 * @return void
	 */
	private static function add_applied_watermark( $attachment_id, $watermark_id ) {

		$meta = get_post_meta( $attachment_id, '_ew_applied_watermarks', true );

		if ( ! is_array( $meta ) ) {
			$meta = [];
		}

		if ( ! in_array( $watermark_id, $meta, true ) ) {
			$meta[] = $watermark_id;
			update_post_meta( $attachment_id, '_ew_applied_watermarks', $meta );
		}

	}

	/**
	 * Computes alignment based on position_x and position_y
	 *
	 * @param  string $x Horizontal position.
	 * @param  string $y Vertical position.
	 * @return string
	 */
	private static function get_alignment( $x, $y ) {
		$alignment = null;

		if ( 'mdl' === $y || 'middle' === $y ) {
			$y = null;
		}

		if ( 'btm' === $y ) {
			$y = 'bottom';
		}

		if ( 'lft' === $x ) {
			$x = 'left';
		}

		if ( 'ctr' === $x || 'center' === $x ) {
			$x = null;
		}

		if ( 'rgt' === $x ) {
			$x = 'right';
		}

		$alignment = $y . '-' . $x;

		if ( null === $x && null === $y ) {
			$alignment = 'center';
		} else {
			$alignment = trim( $y . '-' . $x, '-' );
		}

		return $alignment;

	}
}
