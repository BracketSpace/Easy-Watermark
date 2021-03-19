<?php
/**
 * Abstract placeholder
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Placeholders\Blog;

use EasyWatermark\Placeholders\Abstracts\UrlPlaceholder;

/**
 * Abstract placeholder
 */
class BlogUrl extends UrlPlaceholder {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->slug    = 'blog_url';
		$this->name    = __( 'Blog URL', 'easy-watermark' );
		$this->example = __( 'http://example.com', 'easy-watermark' );

	}

	/**
	 * Resolves placeholder
	 *
	 * @param  Resolver $resolver Placeholders resolver instance.
	 * @return string
	 */
	public function resolve( $resolver ) {
		return home_url();
	}
}
