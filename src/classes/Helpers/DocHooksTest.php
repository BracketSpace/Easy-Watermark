<?php
/**
 * Helper class for testing docHooks support
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Helpers;

/**
 * EasyWatermarkDocHookTest class
 */
final class DocHooksTest {
	/**
	 * DocHooks check cache
	 *
	 * @var bool|null
	 */
	public static $enabled = null;

	/**
	 * Test method
	 *
	 * @action test 10
	 * @return void
	 */
	public function test_method() {}

	/**
	 * Test docHooks support
	 *
	 * @return bool
	 */
	public static function enabled() : bool {
		if ( null === self::$enabled ) {
			$reflector = new \ReflectionObject( new self() );

			self::$enabled = false;

			foreach ( $reflector->getMethods() as $method ) {
				$doc = $method->getDocComment();

				if ( (bool) strpos( $doc, '@action' ) ) {
					self::$enabled = true;
					break;
				}
			}
		}

		return self::$enabled;
	}
}
