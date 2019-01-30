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
	 * @param  array  instances
	 */
	private static $instances = [];

	/**
	 * Factory method for watermark instances.
	 * Creates one instance for post id.
	 *
	 * @return object
	 */
	public static function get( $post ) {
		if ( is_numeric( $post ) ) {
			$post = get_post( $id );
		} else if ( ! $post instanceof \WP_Post ) {
			return false;
		}

		if ( ! isset( self::$instances[$post->ID] ) ) {
			self::$instances[$post->ID] = new self( $post );
		}

		return self::$instances[$post->ID];
	}

	/**
	 * @param  object  original post
	 */
	private $post;

	/**
	 * @param  array  watermark configuration params
	 */
	private $params;

	/**
	 * @param  array
	 */
	private static $defaults = [
		'type'          => null,
		'attachment_id' => null,
		'mime_type'     => null,
		'url'           => null,
		'text'          => '',
		'offset'        => [
			'x' => [
				'value' => 0,
				'unit'  => 'px'
			],
			'y' => [
				'value' => 0,
				'unit'  => 'px'
			]
		],
		'auto_add'    => true,
		'image_types' => [
			'image/jpeg',
			'image/png',
			'image/gif'
		],
		'image_sizes' => [
			'medium',
			'medium_large',
			'large',
			'full'
		],
		'post_types' => [
			'unattached',
			'post',
			'page'
		]
	];

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct( $post ) {
		$this->post = $post;

		$this->params = $post->post_content ? json_decode( $post->post_content, true ) : [];
		$this->params = wp_parse_args( $this->params, self::$defaults );
	}

	/**
	 * Getter for watermark config params
	 *
	 * @param  string  $key  param name
	 * @return mixed
	 */
	public function get_param( $key ) {
		if ( isset( $this->params[ $key ] ) ) {
			return $this->params[ $key ];
		}
	}

	/**
	 * Getter for watermark config params
	 *
	 * @return array  watermark config params
	 */
	public function get_params() {
		return $this->params;
	}

	/**
	 * Magic method for more wordpress feel
	 *
	 * Allows to do:
	 * 		echo $watermark->post_title;
	 *
	 * @param  string  $key  param name|post field
	 * @return mixed
	 */
	public function __get( $key ) {
		if ( isset( $this->post->$key ) ) {
			return $this->post->$key;
		}

		return $this->get_param( $key );
	}

	public static function parse_params( $params ) {
		foreach ( self::$defaults as $key => $value ) {
			if ( ! array_key_exists( $key, $params ) ) {
				$params[$key] = false;
			}
		}

		return $params;
	}
}
