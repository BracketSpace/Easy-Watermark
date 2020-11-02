<?php
/**
 * Watermark post type class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Watermark;

use EasyWatermark\Core\Plugin;
use EasyWatermark\Core\View;
use EasyWatermark\Helpers\Image as ImageHelper;
use EasyWatermark\Traits\Hookable;
use EasyWatermark\Watermark\Watermark;

/**
 * Watermark post type class
 */
class PostType {

	use Hookable;

	/**
	 * Post type
	 *
	 * @var  string
	 */
	private $post_type = 'watermark';

	/**
	 * Is watermark untrashed?
	 *
	 * @var  bool
	 */
	private $untrashed = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->hook();
	}

	/**
	 * Registers custom post type
	 *
	 * @action init
	 * @return void
	 */
	public function register() {

		$labels = [
			'name'               => _x( 'Watermarks', 'post type general name', 'easy-watermark' ),
			'singular_name'      => _x( 'Watermark', 'post type singular name', 'easy-watermark' ),
			'menu_name'          => _x( 'Watermarks', 'admin menu', 'easy-watermark' ),
			'name_admin_bar'     => _x( 'Watermark', 'add new on admin bar', 'easy-watermark' ),
			'add_new'            => _x( 'Add New', 'Watermark', 'easy-watermark' ),
			'add_new_item'       => __( 'Add New Watermark', 'easy-watermark' ),
			'new_item'           => __( 'New Watermark', 'easy-watermark' ),
			'edit_item'          => __( 'Edit Watermark', 'easy-watermark' ),
			'view_item'          => __( 'View Watermark', 'easy-watermark' ),
			'all_items'          => __( 'All Watermarks', 'easy-watermark' ),
			'search_items'       => __( 'Search Watermarks', 'easy-watermark' ),
			'parent_item_colon'  => __( 'Parent Watermarks:', 'easy-watermark' ),
			'not_found'          => __( 'No watermarks found.', 'easy-watermark' ),
			'not_found_in_trash' => __( 'No watermarks found in Trash.', 'easy-watermark' ),
		];

		$args = [
			'labels'       => $labels,
			'description'  => __( 'Watermarks', 'easy-watermark' ),
			'public'       => false,
			'hierarchical' => false,
			'map_meta_cap' => true,
			'show_in_rest' => true,
			'rest_base'    => 'watermarks',
			'capabilities' => [
				'edit_post'           => 'edit_watermark',
				'edit_posts'          => 'edit_watermarks',
				'edit_others_posts'   => 'edit_others_watermarks',
				'delete_posts'        => 'delete_watermarks',
				'delete_others_posts' => 'delete_others_watermarks',
			],
		];

		// phpcs:ignore WordPress.NamingConventions.ValidPostTypeSlug.NotStringLiteral
		register_post_type( $this->post_type, $args );

		register_rest_field( 'watermark', 'config', [
			'get_callback' => [ $this, 'config_get_callback' ],
		] );

		register_rest_field( 'watermark', 'objects', [
			'get_callback' => [ $this, 'objects_get_callback' ],
		] );

	}

	/**
	 * Prepares watermark config field for REST API response
	 *
	 * @since 1.0.0
	 * @param  array $data Response data.
	 * @return array
	 */
	public function config_get_callback( $data ) {
		$defaults = Watermark::get_defaults();

		if ( null === $data['content']['raw'] ) {
			return $defaults;
		}

		$data = json_decode( $data['content']['raw'], true );

		if ( ! is_array( $data ) ) {
			return $defaults;
		}

		if ( array_key_exists( 'config', $data ) ) {
			$config = $data['config'];
		} else {
			$config = $this->get_config( $data );
		}

		return $config;
	}

	/**
	 * Parses watermark image sizes
	 *
	 * @since 1.0.0
	 * @param  array $sizes Image sizes.
	 * @return array
	 */
	private function parse_image_sizes( $sizes ) {
		$result = ImageHelper::get_available_sizes();

		array_walk( $result, function( &$item, $key ) use ( $sizes ) {
			$item = [
				'label'   => $item,
				'checked' => in_array( $key, $sizes, true ),
			];
		} );

		return $result;
	}

	/**
	 * Gets watermark config
	 *
	 * @since 1.0.0
	 * @param  array $data Response data.
	 * @return array
	 */
	public function get_config( $data ) {
		$defaults = Watermark::get_defaults();
		$config   = array_intersect_key( $data, $defaults );

		return array_merge( $defaults, $config );
	}

	/**
	 * Prepares watermark config field for REST API response
	 *
	 * @since 1.0.0
	 * @param  array $data Response data.
	 * @return array
	 */
	public function objects_get_callback( $data ) {
		$data = json_decode( $data['content']['raw'], true );

		if ( array_key_exists( 'objects', $data ) ) {
			$objects = $data['objects'];
		} else {
			$objects = $this->get_objects( $data );
		}

		return $objects;
	}

	/**
	 * Gets watermark objects
	 *
	 * @since 1.0.0
	 * @param  array $data Response data.
	 * @return array
	 */
	public function get_objects( $data ) {
		if ( ! array_key_exists( 'type', $data ) ) {
			return [];
		}

		$defaults = Watermark::get_defaults();
		$exclude  = [];

		if ( 'image' === $data['type'] ) {
			$exclude = [ 'text', 'font', 'text_color', 'text_size', 'text_angle' ];
		} else {
			$exclude = [ 'attachment_id', 'mime_type', 'url' ];
		}

		$object = array_diff_key( $data, $defaults, array_flip( $exclude ) );

		return [ $object ];
	}

	/**
	 * Filters parent file to highlight top level menu item as active
	 *
	 * @filter parent_file
	 *
	 * @since 1.0.0
	 * @param  string $parent_file Parent file.
	 * @return string
	 */
	public function parent_file( $parent_file ) {
		global $current_screen, $submenu_file;

		if ( $current_screen->post_type === $this->post_type ) {
			$parent_file  = 'tools.php';
			$submenu_file = 'easy-watermark';
		}

		return $parent_file;
	}

	/**
	 * Removes temporary params on watermark edit screen
	 *
	 * @action current_screen
	 *
	 * @return void
	 */
	public function current_screen() {
		if ( 'watermark' === get_current_screen()->id ) {
			// phpcs:disable WordPress.Security.NonceVerification.Recommended
			if ( isset( $_REQUEST['post'] ) && isset( $_REQUEST['action'] ) && 'edit' === $_REQUEST['action'] ) {
				delete_post_meta( intval( $_REQUEST['post'] ), '_ew_tmp_params' );
			}
			// phpcs:enable
		}
	}

	/**
	 * Sets watermark update messages
	 *
	 * @filter post_updated_messages
	 *
	 * @param  array $messages Watermark update messages.
	 * @return array
	 */
	public function post_updated_messages( $messages ) {
		global $post;

		$messages['watermark'] = [
			'',
			__( 'Watermark updated.', 'easy-watermark' ),
			__( 'Custom field updated.', 'easy-watermark' ),
			__( 'Custom field deleted.', 'easy-watermark' ),
			__( 'Watermark updated.', 'easy-watermark' ),
			isset( $_GET['revision'] ) ? sprintf( __( 'Watermark restored to revision from %s', 'easy-watermark' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false, //phpcs:ignore
			__( 'Watermark saved.', 'easy-watermark' ),
			__( 'Watermark saved.', 'easy-watermark' ),
			__( 'Watermark submitted.', 'easy-watermark' ),
			sprintf(
				__( 'Watermark scheduled for: <strong>%1$s</strong>.', 'easy-watermark' ), //phpcs:ignore
				date_i18n( __( 'M j, Y @ G:i', 'easy-watermark' ), strtotime( $post->post_date ) )
			),
			__( 'Watermark draft updated.', 'easy-watermark' ),
		];

		return $messages;
	}

	/**
	 * Sets watermark bulk update messages
	 *
	 * @filter bulk_post_updated_messages
	 *
	 * @param  array $messages Bulk update messages.
	 * @param  array $counts   Counts.
	 * @return array
	 */
	public function bulk_post_updated_messages( $messages, $counts ) {
		$messages['watermark'] = [
			/* translators: updated watermarks number */
			'updated'   => _n( '%s watermark updated.', '%s watermarks updated.', $counts['updated'], 'easy-watermark' ),
			'locked'    => ( 1 === $counts['locked'] ) ? __( '1 watermarkt not updated, somebody is editing it.', 'easy-watermark' ) :
									/* translators: not updated watermarks number */
								_n( '%s watermark not updated, somebody is editing it.', '%s watermarks not updated, somebody is editing them.', $counts['locked'], 'easy-watermark' ),
			/* translators: deleted watermarks number */
			'deleted'   => _n( '%s watermark permanently deleted.', '%s watermarks permanently deleted.', $counts['deleted'], 'easy-watermark' ),
			/* translators: moved to trash watermarks number */
			'trashed'   => _n( '%s watermark moved to the Trash.', '%s watermarks moved to the Trash.', $counts['trashed'], 'easy-watermark' ),
			/* translators: restored from trash watermarks number */
			'untrashed' => _n( '%s watermark restored from the Trash.', '%s watermarks restored from the Trash.', $counts['untrashed'], 'easy-watermark' ),
		];

		return $messages;
	}

	/**
	 * Checks if watermark has been untrashed
	 *
	 * @action untrashed_post
	 *
	 * @param  integer $post_id Post ID.
	 * @return void
	 */
	public function untrashed_post( $post_id ) {
		global $post;

		if ( 'watermark' === $post->post_type ) {
			$this->untrashed = true;
		}
	}

	/**
	 * Removes watermark ID from attachment meta
	 *
	 * @action delete_post
	 *
	 * @param  integer $post_id Post ID.
	 * @return void
	 */
	public function delete_post( $post_id ) {
		$post = get_post( $post_id );

		if ( 'watermark' === $post->post_type ) {
			$watermark = Watermark::get( $post );

			if ( $watermark->attachment_id ) {
				$this->remove_watrmark_from_meta( $watermark->attachment_id, $post_id );
			}
		}
	}

	/**
	 * Changes redirect location after watermark restoration from trash
	 *
	 * @action wp_redirect
	 *
	 * @param  string $location Location.
	 * @return string
	 */
	public function redirect( $location ) {
		global $post;

		if ( $post && 'watermark' === $post->post_type ) {
			if ( false !== strpos( $location, 'untrashed=1' ) && ! $this->untrashed ) {
				$location = add_query_arg( [
					'ew-limited' => '1',
				], remove_query_arg( 'untrashed', $location ) );
			}
		}

		return $location;
	}

	/**
	 * Prints admin notices
	 *
	 * @action admin_notices
	 *
	 * @return void
	 */
	public function admin_notices() {
		global $post;

		if ( 'watermark' === get_current_screen()->id && 2 <= $this->get_watermarks_count() && 'publish' !== $post->post_status ) {
			View::get( 'notices/watermarks-number-exceeded-error' )->display();
		}

		// phpcs:ignore WordPress.Security
		if ( isset( $_REQUEST['ew-limited'] ) && $_REQUEST['ew-limited'] ) {
			View::get( 'notices/untrash-error' )->display();
		}
	}

	/**
	 * Filters row actions for watermark post type
	 *
	 * @filter post_row_actions
	 *
	 * @param  array   $actions Row actions.
	 * @param  WP_Post $post    Post object.
	 * @return array
	 */
	public function post_row_actions( $actions, $post ) {
		if ( 'watermark' === $post->post_type ) {
			if ( 2 <= $this->get_watermarks_count() && isset( $actions['untrash'] ) ) {
				unset( $actions['untrash'] );
			}

			$watermark_types = Plugin::get()->get_watermark_handler()->get_watermark_types();
			$watermark       = Watermark::get( $post );

			if ( array_key_exists( $watermark->type, $watermark_types ) && false === $watermark_types[ $watermark->type ]['available'] ) {
				unset( $actions['untrash'] );
			}
		}

		return $actions;
	}

	/**
	 * Filters watermark bulk actions
	 *
	 * @filter bulk_actions-edit-watermark
	 *
	 * @param  array $actions Bulk actions.
	 * @return array
	 */
	public function bulk_actions( $actions ) {
		if ( isset( $actions['untrash'] ) ) {
			unset( $actions['untrash'] );
		}

		return $actions;
	}

	/**
	 * Returns watermarks count
	 *
	 * @return object
	 */
	public function get_watermarks_count() {
		return wp_count_posts( 'watermark' )->publish;
	}

	/**
	 * Hides screen options on watermark editing screen
	 *
	 * @filter screen_options_show_screen
	 *
	 * @param  bool   $show_screen Whether to show Screen Options tab.
	 * @param  object $screen      Current WP_Screen instance.
	 * @return bool
	 */
	public function screen_options_show_screen( $show_screen, $screen ) {
		if ( 'watermark' === $screen->id ) {
			return false;
		}

		return $show_screen;
	}

	/**
	 * Adds hidden field for attachment id storage
	 *
	 * @action edit_form_top
	 *
	 * @param  WP_Post $post Post object.
	 * @return void
	 */
	public function edit_form_top( $post ) {
		if ( 'watermark' === get_current_screen()->id && ( 2 > $this->get_watermarks_count() || 'publish' === $post->post_status ) ) {
			$watermark = Watermark::get( $post );

			View::get( 'edit-screen/attachment-id-field', [
				'attachment_id' => $watermark->attachment_id,
			] )->display();
		}
	}

	/**
	 * Adds watermark type selector
	 *
	 * @action edit_form_after_title
	 *
	 * @param  WP_Post $post Post object.
	 * @return void
	 */
	public function edit_form_after_title( $post ) {
		if ( 'watermark' === get_current_screen()->id && ( 2 > $this->get_watermarks_count() || 'publish' === $post->post_status ) ) {
			$watermark         = Watermark::get( $post );
			$watermark_handler = Plugin::get()->get_watermark_handler();

			View::get( 'edit-screen/watermark-type-selector', [
				'watermark_types' => $watermark_handler->get_watermark_types(),
				'selected_type'   => $watermark->type,
			] )->display();
		}
	}

	/**
	 * Watermark edit screen columns setup
	 *
	 * @filter get_user_option_screen_layout_watermark
	 *
	 * @param  integer $columns User setup columns.
	 * @return integer
	 */
	public function setup_columns( $columns ) {
		global $post;

		if ( 2 <= $this->get_watermarks_count() && 'publish' !== $post->post_status ) {
			// Force one column.
			return 1;
		}

		return $columns;
	}

	/**
	 * Watermark edit screen title support setup
	 *
	 * @action edit_form_top
	 *
	 * @return void
	 */
	public function change_title_support() {
		global $_wp_post_type_features, $post;

		if ( 'publish' === $post->post_status ) {
			return;
		}

		if ( 2 <= $this->get_watermarks_count() && isset( $_wp_post_type_features['watermark']['title'] ) ) {
			unset( $_wp_post_type_features['watermark']['title'] );
		}
	}

	/**
	 * Filters whether a post untrashing should take place.
	 *
	 * @filter pre_untrash_post
	 *
	 * @param  null    $untrash Whether to go forward with untrashing.
	 * @param  WP_Post $post    Post object.
	 * @return bool
	 */
	public function pre_untrash_post( $untrash, $post ) {

		if ( 'watermark' === $post->post_type && 2 <= $this->get_watermarks_count() ) {
			return true;
		}

		return $untrash;

	}

	/**
	 * Stores serialized watermark data in post content
	 *
	 * @filter wp_insert_post_data
	 *
	 * @param  array $data    An array of slashed post data.
	 * @param  array $postarr An array of sanitized, but otherwise unmodified post data.
	 * @return array
	 */
	public function wp_insert_post_data( $data, $postarr ) {

		if ( 'watermark' === $data['post_type'] && isset( $postarr['watermark'] ) ) {
			$watermark_data = Watermark::parse_params( $postarr['watermark'] );

			$data['post_content'] = wp_json_encode( $watermark_data, JSON_UNESCAPED_UNICODE );

			$old_attachment_id = isset( $postarr['ew-previous-attachment-id'] ) ? $postarr['ew-previous-attachment-id'] : false;
			$new_attachment_id = $postarr['watermark']['attachment_id'];

			if ( $old_attachment_id !== $new_attachment_id ) {
				if ( is_numeric( $old_attachment_id ) ) {
					$this->remove_watrmark_from_meta( $old_attachment_id, $postarr['ID'] );
				}

				$this->add_watrmark_to_meta( $new_attachment_id, $postarr['ID'] );
			}

			delete_post_meta( $postarr['ID'], '_ew_tmp_params' );
		}

		return $data;

	}

	/**
	 * Prepares watermark content for REST API response
	 *
	 * @filter rest_prepare_watermark
	 *
	 * @since 1.0.0
	 * @param WP_REST_Response $response The response object.
	 * @param WP_Post          $post     Post object.
	 * @param WP_REST_Request  $request  Request object.
	 * @return WP_REST_Response|WP_Error
	 */
	public function rest_prepare_watermark( $response, $post, $request ) {
		if ( 'edit' !== $request->get_param( 'context' ) ) {
			return new \WP_Error( 'rest_no_route', __( 'No route was found matching the URL and request method' ), [ 'status' => 404 ] );
		}

		$data = $response->get_data();

		unset( $data['content'] );

		$response->set_data( $data );

		return $response;
	}

	/**
	 * Prepares watermark for database.
	 *
	 * @filter rest_pre_insert_watermark
	 *
	 * @since 1.0.0
	 * @param  stdClass        $prepared_post    Post data.
	 * @param  WP_REST_Request $request  Request object.
	 * @return stdClass
	 */
	public function rest_pre_insert_watermark( $prepared_post, $request ) {
		$watermark_data = [
			'config'  => $request->get_param( 'config' ),
			'objects' => $request->get_param( 'objects' ),
		];

		$prepared_post->post_content = wp_json_encode( $watermark_data, JSON_UNESCAPED_UNICODE );

		return $prepared_post;
	}

	/**
	 * Add watermark to attachment meta
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @param  integer $watermark_id  Watermark ID.
	 * @return void
	 */
	private function add_watrmark_to_meta( $attachment_id, $watermark_id ) {

		$meta = get_post_meta( $attachment_id, '_ew_used_as_watermark', true );

		if ( ! is_array( $meta ) ) {
			$meta = [];
		}

		if ( ! in_array( $watermark_id, $meta, true ) ) {
			$meta[] = $watermark_id;
			update_post_meta( $attachment_id, '_ew_used_as_watermark', $meta );
		}

	}

	/**
	 * Remove watermark to attachment meta
	 *
	 * @param  integer $attachment_id Attachment ID.
	 * @param  integer $watermark_id  Watermark ID.
	 * @return void
	 */
	private function remove_watrmark_from_meta( $attachment_id, $watermark_id ) {

		$meta = get_post_meta( $attachment_id, '_ew_used_as_watermark', true );

		if ( is_array( $meta ) && in_array( $watermark_id, $meta, true ) ) {
			$key = array_search( $watermark_id, $meta, true );
			unset( $meta[ $key ] );

			if ( empty( $meta ) ) {
				delete_post_meta( $attachment_id, '_ew_used_as_watermark' );
			} else {
				update_post_meta( $attachment_id, '_ew_used_as_watermark', $meta );
			}
		}

	}
}
