<?php
/**
 * Watermark post type class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\PostTypes;

use EasyWatermark\Core\Plugin;
use EasyWatermark\Core\View;
use EasyWatermark\Traits\Hookable;
use EasyWatermark\Watermark\Watermark as WatermarkObject;

/**
 * Watermark post type class
 */
class Watermark {

	use Hookable;

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
			'labels'        => $labels,
			'description'   => __( 'Watermarks', 'easy-watermark' ),
			'public'        => false,
			'show_ui'       => true,
			'has_archive'   => false,
			'hierarchical'  => false,
			'menu_icon'     => 'dashicons-media-text',
			'menu_position' => null,
			'supports'      => [ 'title' ],
			'map_meta_cap'  => true,
			'capabilities'  => [
				'edit_post'           => 'edit_watermark',
				'edit_posts'          => 'edit_watermarks',
				'edit_others_posts'   => 'edit_others_watermarks',
				'delete_posts'        => 'delete_watermarks',
				'delete_others_posts' => 'delete_others_watermarks',
			],
		];

		register_post_type( 'watermark', $args );

	}

	/**
	 * Sets watermark update messages
	 *
	 * @filter post_updated_messages
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
		global $post;

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
	 * Changes redirect location after watermark restoration from trash
	 *
	 * @action wp_redirect
	 *
	 * @param  string $location Location.
	 * @return string
	 */
	public function redirect( $location ) {
		global $post;

		if ( 'watermark' === $post->post_type ) {
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
			echo new View( 'notices/watermarks-number-exceeded-error' ); // phpcs:ignore
		}

		if ( isset( $_REQUEST['ew-limited'] ) && $_REQUEST['ew-limited'] ) { // phpcs:ignore

			echo new View( 'notices/untrash-error' ); // phpcs:ignore

			$_SERVER['REQUEST_URI'] = remove_query_arg( [ 'ew-limited' ], $_SERVER['REQUEST_URI'] ); // phpcs:ignore
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
	 * Adds watermark type selector
	 *
	 * @action edit_form_after_title
	 *
	 * @param  WP_Post $post Post object.
	 * @return void
	 */
	public function edit_form_after_title( $post ) {
		if ( 'watermark' === get_current_screen()->id && ( 2 > $this->get_watermarks_count() || 'publish' === $post->post_status ) ) {
			$watermark         = WatermarkObject::get( $post );
			$watermark_handler = Plugin::get()->get_watermark_handler();

 			// phpcs:disable
			echo new View( 'edit-screen/watermark-type-selector', [
				'watermark_types' => $watermark_handler->get_watermark_types(),
				'selected_type'   => $watermark->type,
			] );
			// phpcs:enable
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
			$watermark_data = WatermarkObject::parse_params( $postarr['watermark'] );

			$data['post_content'] = wp_json_encode( $watermark_data );
		}

		return $data;
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}
}
