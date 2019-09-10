<?php
/**
 * Resolver class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders;

use EasyWatermark\Placeholders\Abstracts\Placeholder;
use underDEV\Utils\Singleton;
use WP_Error;

/**
 * Resolver class
 */
class Resolver extends Singleton {

	/**
	 * Placeholder instances
	 *
	 * @var array
	 */
	protected $placeholders = [];

	/**
	 * Regex pattern for placeholders
	 *
	 * @var string
	 */
	private $placeholder_pattern = '/\%([^\%]*)\%/';

	/**
	 * Attachment being processed
	 *
	 * @var array
	 */
	protected $attachment;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->load_placeholders();

	}

	/**
	 * Loads placeholders
	 *
	 * @return void
	 */
	public function load_placeholders() {
		do_action( 'easy-watermark/placeholders/load', $this );
	}

	/**
	 * Adds single placeholder instance
	 *
	 * @param  Placeholder $placeholder Placeholder instance.
	 * @return WP_Error|true
	 */
	public function add_placeholder( $placeholder ) {

		if ( ! $placeholder instanceof Placeholder ) {
			return new WP_Error( __( 'Placeholder must be an instance of abstract Placeholder class.', 'easy-watermark' ) );
		}

		$this->placeholders[ $placeholder->get_slug() ] = $placeholder;

	}

	/**
	 * Returns array of registered placeholders
	 *
	 * @return array
	 */
	public function get_placeholders() {
		return $this->placeholders;
	}

	/**
	 * Resolves placeholders for given value
	 *
	 * @param  string $value Value to resolve.
	 * @return string
	 */
	public function resolve( $value ) {

		$value = apply_filters( 'easy-watermark/placeholders/resolving', $value, $this );

		$resolved = preg_replace_callback( $this->placeholder_pattern, array( $this, 'resolve_match' ), $value );

		$resolved = apply_filters( 'easy-watermark/placeholders/resolved', $resolved, $this );

		return $resolved;

	}

	/**
	 * Resolves placeholder with a real value
	 *
	 * @param  array $matches Matches from preg_replace.
	 * @return string
	 */
	public function resolve_match( $matches ) {

		$placeholder_slug = $matches[1];

		if ( ! isset( $this->placeholders[ $placeholder_slug ] ) ) {
			return $matches[0];
		}

		$resolved = apply_filters( 'easy-watermark/placeholder/resolved', $this->placeholders[ $placeholder_slug ]->get_value( $this ), $this->placeholders[ $placeholder_slug ] );

		return $resolved;

	}

	/**
	 * Sets attachment data
	 *
	 * @param  array $attachment Attachment data.
	 * @return void
	 */
	public function set_attachment( $attachment ) {
		$this->attachment = $attachment;
	}

	/**
	 * Gets attachment data
	 *
	 * @return array
	 */
	public function get_attachment() {
		return $this->attachment;
	}

	/**
	 * Resets resolved placeholders
	 *
	 * @return void
	 */
	public function reset() {

		foreach ( $this->placeholders as $placeholder ) {
			$placeholder->reset();
		}

	}
}
