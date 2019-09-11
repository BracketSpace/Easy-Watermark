<?php
/**
 * Watermark class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Watermark;

/**
 * Watermark class
 */
class Watermark {

	/**
	 * Default params
	 *
	 * @var array
	 */
	private static $defaults = [
		'type'            => null,
		'attachment_id'   => null,
		'mime_type'       => null,
		'url'             => null,
		'text'            => '',
		'auto_add'        => true,
		'auto_add_all'    => true,
		'scaling_mode'    => 'none',
		'scale_down_only' => false,
		'scale'           => 100,
		'font'            => null,
		'text_color'      => '#000000',
		'text_size'       => 24,
		'text_angle'      => 0,
		'opacity'         => 100,
		'alignment'       => 'center',
		'offset'          => [
			'x' => [
				'value' => 0,
				'unit'  => 'px',
			],
			'y' => [
				'value' => 0,
				'unit'  => 'px',
			],
		],
		'image_types'     => [
			'image/jpeg',
			'image/png',
			'image/gif',
		],
		'image_sizes'     => [
			'medium',
			'medium_large',
			'large',
			'full',
		],
		'post_types'      => [
			'unattached',
			'post',
			'page',
		],
	];

	/**
	 * Instances
	 *
	 * @var  array
	 */
	private static $instances = [];

	/**
	 * Factory method for watermark instances.
	 * Creates one instance for post id.
	 *
	 * @param  mixed $post Post ID or WP_Post instance.
	 * @return mixed
	 */
	public static function get( $post ) {

		if ( is_numeric( $post ) ) {
			$post = get_post( $post );
		}

		if ( ! $post instanceof \WP_Post || 'watermark' !== $post->post_type ) {
			return false;
		}

		if ( ! isset( self::$instances[ $post->ID ] ) ) {
			self::$instances[ $post->ID ] = new self( $post );
		}

		return self::$instances[ $post->ID ];

	}

	/**
	 * Gets all watermarks
	 *
	 * @return array
	 */
	public static function get_all() {

		$posts = get_posts( [
			'post_type'   => 'watermark',
			'numberposts' => -1,
		] );

		foreach ( $posts as $post ) {
			self::get( $post );
		}

		return self::$instances;

	}

	/**
	 * Builds complete params array to save in post content
	 *
	 * @param  array $params Params array.
	 * @return array
	 */
	public static function parse_params( $params ) {

		foreach ( self::$defaults as $key => $value ) {
			if ( ! array_key_exists( $key, $params ) ) {
				if ( 'array' === gettype( self::$defaults[ $key ] ) ) {
					$params[ $key ] = [];
				} else {
					$params[ $key ] = false;
				}
			}
		}

		return $params;

	}

	/**
	 * Returns defaults array
	 *
	 * @return array
	 */
	public static function get_defaults() {
		return self::$defaults;
	}

	/**
	 * Original post object
	 *
	 * @var object
	 */
	private $post;

	/**
	 * Watermark configuration params
	 *
	 * @var array
	 */
	private $params;

	/**
	 * Watermark configuration params
	 *
	 * @var array
	 */
	private $tmp_params;

	/**
	 * Constructor
	 *
	 * @param  WP_Post $post Post object.
	 * @return void
	 */
	public function __construct( $post ) {

		$this->post = $post;

		$this->params = $post->post_content ? json_decode( $post->post_content, true ) : [];
		$this->params = wp_parse_args( $this->params, self::$defaults );

	}

	/**
	 * Switches params to temporary if exist
	 *
	 * @return mixed
	 */
	public function use_temporary_params() {

		$tmp_params = get_post_meta( $this->post->ID, '_ew_tmp_params', true );

		if ( $tmp_params ) {
			$this->params = wp_parse_args( $tmp_params, self::$defaults );
		}

	}

	/**
	 * Getter for watermark config params
	 *
	 * @param  string $key Param name.
	 * @return mixed
	 */
	public function get_param( $key ) {

		if ( isset( $this->params[ $key ] ) ) {
			return $this->params[ $key ];
		}

	}

	/**
	 * Setter for watermark config params
	 *
	 * @param  string $key   Param name.
	 * @param  string $value Param value.
	 * @return void
	 */
	public function set_param( $key, $value ) {
		$this->params[ $key ] = $value;
	}

	/**
	 * Getter for watermark config params
	 *
	 * @return array watermark config params
	 */
	public function get_params() {
		return $this->params;
	}

	/**
	 * Magic method for more WordPress feel
	 *
	 * Allows to do:
	 *      echo $watermark->post_title;
	 *
	 * @param  string $key Param name or WP_Post field.
	 * @return mixed
	 */
	public function __get( $key ) {

		if ( isset( $this->post->$key ) ) {
			return $this->post->$key;
		}

		return $this->get_param( $key );

	}

	/**
	 * Magic setter for watermark params
	 *
	 * @param  string $key   Param name.
	 * @param  mixed  $value Param value.
	 * @return void
	 */
	public function __set( $key, $value ) {
		$this->set_param( $key, $value );
	}
}
