<?php
/**
 * Hookable trait allows to hook actions from doc comment.
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Traits;

use EasyWatermark\Core\Hooks;

trait Hookable {

	/**
	 * Whether current object is hooked
	 *
	 * @var boolean
	 */
	protected $hooked = false;

	/**
	 * Pattern for doc hooks
	 *
	 * @var string
	 */
	protected $pattern = '#\* @(?P<type>filter|action|shortcode)\s+(?P<name>[a-z0-9\-\.\/_]+)(\s+(?P<priority>\d+))?#';

	/**
	 * Add actions/filters/shortcodes from the methods of a class based on DocBlocks
	 */
	public function hook() {

		if ( true === $this->hooked ) {
			return;
		}

		$this->hooked = true;

		$reflector = new \ReflectionObject( $this );
		$hooks     = Hooks::get();

		$hooks->add_object( $this );

		foreach ( $reflector->getMethods() as $method ) {

			$doc       = $method->getDocComment();
			$arg_count = $method->getNumberOfParameters();

			if ( preg_match_all( $this->pattern, $doc, $matches, PREG_SET_ORDER ) ) {

				foreach ( $matches as $match ) {

					$type = $match['type'];
					$name = $match['name'];

					$priority = empty( $match['priority'] ) ? 10 : intval( $match['priority'] );
					$callback = [ $this, $method->getName() ];

					$function = sprintf( '\add_%s', $type );

					$retval = \call_user_func( $function, $name, $callback, $priority, $arg_count );

					$hooks->add_hook( $this, [
						'name'      => $name,
						'type'      => $type,
						'callback'  => $method->getName(),
						'priority'  => $priority,
						'arg_count' => $arg_count,
					] );
				}
			}
		}

	}

	/**
	 * Removes the added DocBlock hooks
	 */
	public function unhook() {

		$class_name = get_class( $this );
		$reflector  = new \ReflectionObject( $this );

		foreach ( $reflector->getMethods() as $method ) {

			$doc = $method->getDocComment();

			if ( preg_match_all( $this->pattern, $doc, $matches, PREG_SET_ORDER ) ) {

				foreach ( $matches as $match ) {

					$type = $match['type'];
					$name = $match['name'];

					$priority = empty( $match['priority'] ) ? 10 : intval( $match['priority'] );
					$callback = array( $this, $method->getName() );

					call_user_func( "remove_{$type}", $name, $callback, $priority );

				}
			}
		}

		$this->hooked = false;

	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->unhook();
	}
}
