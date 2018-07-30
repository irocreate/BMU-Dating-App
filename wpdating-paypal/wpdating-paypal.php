<?php

! defined( 'WPDATING_PAYPAL_ABSPATH' ) ? define( 'WPDATING_PAYPAL_ABSPATH', plugin_dir_path( __FILE__ ) ) : null;
! defined( 'WPDATING_PAYPAL_URL' ) ? define( 'WPDATING_PAYPAL_URL', plugin_dir_url( __FILE__ ) ) : null;

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpdating-paypal.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpdating_paypal() {
    new Wpdating_Paypal();
}

run_wpdating_paypal();

