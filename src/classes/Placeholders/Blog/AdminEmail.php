<?php
/**
 * Abstract placeholder
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders\Blog;

use EasyWatermark\Placeholders\Abstracts\EmailPlaceholder;

/**
 * Abstract placeholder
 */
class AdminEmail extends EmailPlaceholder {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->slug    = 'admin_email';
		$this->name    = __( 'Admin email', 'easy-watermark' );
		$this->example = __( 'admin@example.com', 'easy-watermark' );

	}

	/**
	 * Resolves placeholder
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return string
	 */
	public function resolve( $resolver ) {
		return get_bloginfo( 'admin_email' );
	}
}
