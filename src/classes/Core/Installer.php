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

		add_rewrite_rule( 'easy-watermark-preview/([^/.-]+)-([0-9]+)-([^/.]+).(jpg|png)?', 'index.php?easy_watermark_preview=$matches[1]&watermark_id=$matches[2]&image_size=$matches[3]&format=$matches[4]', 'top' );
		add_rewrite_rule( 'easy-watermark-preview/([^/.-]+)-([0-9]+).(jpg|png)?', 'index.php?easy_watermark_preview=$matches[1]&watermark_id=$matches[2]&format=$matches[3]', 'top' );
		flush_rewrite_rules();

	}

	/**
	 * Deactivates plugin
	 *
	 * @return void
	 */
	public static function deactivate() {

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

	}

	/**
	 * Updates plugin
	 *
	 * @param  string $from     Previous active version.
	 * @param  array  $defaults Default settings.
	 * @return void
	 */
	public static function update( $from, $defaults ) {

		$plugin_slug = Plugin::get()->get_slug();
		$settings    = [];

		if ( version_compare( $from, '0.1.1', '>' ) ) {
			$settings['general'] = get_option( $plugin_slug . '-settings-general' );
			$settings['image']   = get_option( $plugin_slug . '-settings-image' );
			$settings['text']    = get_option( $plugin_slug . '-settings-text' );
		} else {
			$old_settings = get_option( $plugin_slug . '-settings' );

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
			$defaults['jpeg_quality'] = $settings['general']['jpg_quality'];
		}

		self::insert_image_watermark( $watermark_defaults, $settings );

		if ( ! empty( $settings['text']['text'] ) ) {
			self::insert_text_watermark( $watermark_defaults, $settings );
		}

		update_option( Plugin::get()->get_slug() . '-settings', $defaults );

		update_option( Plugin::get()->get_slug() . '-version', Plugin::get()->get_version() );

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

		return wp_insert_post( [
			'post_title'  => __( 'Image Watermark', 'easy-watermark' ),
			'post_type'   => 'watermark',
			'post_status' => 'publish',
			'watermark'   => $image_settings,
		] );

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

		return wp_insert_post( [
			'post_title'  => __( 'Text Watermark', 'easy-watermark' ),
			'post_type'   => 'watermark',
			'post_status' => 'publish',
			'watermark'   => $text_settings,
		] );

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
