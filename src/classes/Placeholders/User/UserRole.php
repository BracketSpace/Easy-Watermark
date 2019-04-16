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
class UserRole extends StringPlaceholder {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->slug    = 'user_role';
		$this->name    = __( 'User role', 'easy-watermark' );
		$this->example = __( 'Subscriber', 'easy-watermark' );

	}

	/**
	 * Resolves placeholder
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return string
	 */
	public function resolve( $resolver ) {

		$user = wp_get_current_user();

		$roles = array_map(
			function ( $role ) {
				$role_object = get_role( $role );
				return translate_user_role( ucfirst( $role_object->name ) );
			},
			$user->roles
		);

		return implode( ', ', $roles );

	}
}
