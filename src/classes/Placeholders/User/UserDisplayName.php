<?php
/**
 * Abstract placeholder
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders\User;

use EasyWatermark\Placeholders\Abstracts\StringPlaceholder;

/**
 * Abstract placeholder
 */
class UserDisplayName extends StringPlaceholder {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->slug    = 'user_display_name';
		$this->name    = __( 'User display name', 'easy-watermark' );
		$this->example = __( 'John - fast finegrs - Doe', 'easy-watermark' );

	}

	/**
	 * Resolves placeholder
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return string
	 */
	public function resolve( $resolver ) {

		$user = wp_get_current_user();
		return $user->display_name;

	}
}
