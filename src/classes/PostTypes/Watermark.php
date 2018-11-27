<?php
/**
 * Watermark post type class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\PostTypes;

use EasyWatermark\Traits\Hookable;
use EasyWatermark\Core\View;

/**
 * Watermark post type class
 */
class Watermark {

	use Hookable;

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
			'labels'          => $labels,
			'description'     => __( 'Watermarks', 'easy-watermark' ),
			'public'          => false,
			'show_ui'         => true,
			'capability_type' => [ 'watermark', 'watermarks' ],
			'has_archive'     => false,
			'hierarchical'    => false,
			'menu_icon'       => 'dashicons-media-text',
			'menu_position'   => null,
			'supports'        => [ 'title' ],
		];

		register_post_type( 'watermark', $args );

	}

	/**
	 * Removes default publish metabox
	 *
	 * @filter post_updated_messages
	 * @param  array  $messages
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
			isset( $_GET['revision'] ) ? sprintf( __( 'Watermark restored to revision from %s', 'easy-watermark' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			__( 'Watermark saved.', 'easy-watermark' ),
			__( 'Watermark saved.', 'easy-watermark' ),
			__( 'Watermark submitted.', 'easy-watermark' ),
			sprintf(
				__( 'Watermark scheduled for: <strong>%1$s</strong>.', 'easy-watermark' ),
				date_i18n( __( 'M j, Y @ G:i', 'easy-watermark' ), strtotime( $post->post_date ) )
			),
			__( 'Watermark draft updated.', 'easy-watermark' )
		];

		return $messages;
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
	 * Changes default publish metabox, removes slug metabox
	 *
	 * @action do_meta_boxes
	 * @return void
	 */
	public function setup_metaboxes() {
		global $post;

		remove_meta_box( 'submitdiv', 'watermark', 'side' );
		remove_meta_box( 'slugdiv', 'watermark', 'normal' );

		if ( 2 > $this->get_watermarks_count() || 'publish' == $post->post_status ) {
			add_meta_box( 'submitdiv', __( 'Save' ), [ $this, 'save_meta_box' ], 'watermark', 'side', 'high' );
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

		if ( 2 <= $this->get_watermarks_count() && 'publish' != $post->post_status ) {
			// Force one column
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

		if ( 'publish' == $post->post_status ) {
			return;
		}

		if ( 2 <= $this->get_watermarks_count() && isset( $_wp_post_type_features['watermark']['title'] ) ) {
			unset( $_wp_post_type_features['watermark']['title'] );
		}
	}

	/**
	 * Renders Save meta box content
	 *
	 * @param  object  $post
	 * @return void
	 */
	public function save_meta_box( $post ) {
		echo new View( 'submitdiv', [
			'post'  => $post,
			'count' => $this->get_watermarks_count()
		] );
	}
}
