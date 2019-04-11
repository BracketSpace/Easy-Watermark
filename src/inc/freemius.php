<?php
/**
 * Freemius Integration
 *
 * @package easy-watermark
 */

/**
 * Helper function for easy Freemius SDK access.
 */
function ew_fs() {
	global $ew_fs;

	if ( ! isset( $ew_fs ) ) {
		// Include Freemius SDK.
		require_once EW_DIR_PATH . '/freemius/start.php';

		$ew_fs = fs_dynamic_init( array(
			'id'             => '2801',
			'slug'           => 'easy-watermark',
			'type'           => 'plugin',
			'public_key'     => 'pk_f13faf5a5fdb7e7b8bd3b78646f15',
			'is_premium'     => false,
			'has_addons'     => false,
			'has_paid_plans' => false,
			'menu'           => array(
				'slug'    => 'easy-watermark',
				'account' => false,
				'support' => false,
				'contact' => false,
				'parent'  => array(
					'slug' => 'options-general.php',
				),
			),
		) );
	}

	return $ew_fs;
}
