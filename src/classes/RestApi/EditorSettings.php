<?php
/**
 * Editor Settings REST API controller class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\RestApi;

use EasyWatermark\Traits\Hookable;
use WP_Error;
use WP_REST_Server;

/**
 * Editor Settings
 */
class EditorSettings {

	use Hookable;

	/**
	 * Namespace
	 *
	 * @var string
	 */
	private $namespace;

	/**
	 * Settings option name
	 *
	 * @var string
	 */
	private $option_name;

	/**
	 * Constructor
	 *
	 * @param Plugin $plugin Plugin instance.
	 */
	public function __construct( $plugin ) {
		$this->namespace   = $plugin->get_slug();
		$this->option_name = "{$this->namespace}-editor-settings";
		$this->hook();
	}

	/**
	 * Register REST route
	 *
	 * @action rest_api_init
	 *
	 * @return void
	 */
	public function register_rest_route() {
		$namespace = "{$this->namespace}/v1";
		$route     = '/editor-settings';

		register_rest_route( $namespace, $route, [
			'methods'             => WP_REST_Server::EDITABLE,
			'callback'            => [ $this, 'update_settings' ],
			'permission_callback' => [ $this, 'update_settings_permission_check' ],
			'args'                => [
				'fullscreen_mode' => [
					'default' => false,
				],
				'is_sidebar_open' => [
					'default' => true,
				],
			],
		] );
	}

	/**
	 * Checks whether a given request has permission to read editor settings.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|bool   True if the request has read access, WP_Error object otherwise.
	 */
	public function update_settings_permission_check( $request ) {
		$post_type = get_post_type_object( 'watermark' );

		if ( ! current_user_can( $post_type->cap->edit_posts ) ) {
			return new WP_Error(
				'rest_cannot_edit',
				__( 'Sorry, you are not allowed to edit watermark editor settings.', 'easy-watermark' ),
				[ 'status' => rest_authorization_required_code() ]
			);
		}

		return true;
	}

	/**
	 * Saves editor settings.
	 *
	 * @since 1.0.0
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function update_settings( $request ) {
		$settings = get_option( $this->option_name, [] );

		foreach ( $request->get_json_params() as $key => $value ) {
			if ( is_array( $value ) && array_key_exists( $key, $settings ) ) {
				$settings[ $key ] = array_merge( $settings[ $key ], $value );
			} else {
				$settings[ $key ] = $value;
			}
		}

		update_option( $this->option_name, $settings );

		return rest_ensure_response( $settings );
	}
}
