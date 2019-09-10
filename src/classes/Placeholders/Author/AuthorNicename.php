<?php
/**
 * Abstract placeholder
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders\Author;

use EasyWatermark\Placeholders\Abstracts\StringPlaceholder;

/**
 * Abstract placeholder
 */
class AuthorNicename extends StringPlaceholder {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->slug    = 'author_nicename';
		$this->name    = __( 'Author nicename', 'easy-watermark' );
		$this->example = __( 'Johhnie', 'easy-watermark' );

	}

	/**
	 * Tells whether placeholder is valid
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return boolean
	 */
	public function is_valid( $resolver ) {
		return (bool) $resolver->get_attachment();
	}

	/**
	 * Resolves placeholder
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return string
	 */
	public function resolve( $resolver ) {

		$user = get_user_by( 'id', $resolver->get_attachment()->post_author );
		return $user->user_nicename;

	}
}
