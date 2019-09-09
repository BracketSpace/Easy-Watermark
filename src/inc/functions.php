<?php
/**
 * Function for dochooks test
 *
 * @package easy-watermark
 */

/**
 * Checks if the DocHooks are enabled and working.
 *
 * @return boolean
 */
function ew_dochooks_enabled() {
	if ( ! class_exists( 'EasyWatermarkDocHookTest' ) ) {
		/**
		 * EasyWatermarkDocHookTest class
		 */
		class EasyWatermarkDocHookTest {
			/**
			 * Test method
			 *
			 * @action test 10
			 * @return void
			 */
			public function test_method() {}
		}
	}

	$reflector = new \ReflectionObject( new EasyWatermarkDocHookTest() );

	foreach ( $reflector->getMethods() as $method ) {
		$doc = $method->getDocComment();
		return (bool) strpos( $doc, '@action' );
	}
}
