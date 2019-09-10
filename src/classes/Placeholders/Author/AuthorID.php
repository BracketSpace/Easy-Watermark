<?php
/**
 * Abstract placeholder
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders\Author;

use EasyWatermark\Placeholders\Abstracts\IntegerPlaceholder;

/**
 * Abstract placeholder
 */
class AuthorID extends IntegerPlaceholder {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->slug    = 'author_id';
		$this->name    = __( 'Author ID', 'easy-watermark' );
		$this->example = '47';

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
		return $user->ID;

	}
}
