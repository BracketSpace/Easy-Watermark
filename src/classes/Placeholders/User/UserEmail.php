<?php
/**
 * Abstract placeholder
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders\User;

use EasyWatermark\Placeholders\Abstracts\EmailPlaceholder;

/**
 * Abstract placeholder
 */
class UserEmail extends EmailPlaceholder {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->slug    = 'user_email';
		$this->name    = __( 'User email', 'easy-watermark' );
		$this->example = __( 'john.doe@example.com', 'easy-watermark' );

	}

	/**
	 * Resolves placeholder
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return string
	 */
	public function resolve( $resolver ) {

		$user = wp_get_current_user();
		return $user->user_email;

	}
}
