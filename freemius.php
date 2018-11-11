<?php

// Create a helper function for easy SDK access.
function ew_fs() {
    global $ew_fs;

    if ( ! isset( $ew_fs ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $ew_fs = fs_dynamic_init( array(
            'id'                  => '2801',
            'slug'                => 'easy-watermark',
            'type'                => 'plugin',
            'public_key'          => 'pk_f13faf5a5fdb7e7b8bd3b78646f15',
            'is_premium'          => false,
            'has_addons'          => false,
            'has_paid_plans'      => false,
            'menu'                => array(
                'slug'           => 'easy-watermark-settings',
                'account'        => false,
                'support'        => false,
                'contact'        => false,
                'parent'         => array(
                    'slug' => 'options-general.php',
                ),
            ),
        ) );
    }

    return $ew_fs;
}
