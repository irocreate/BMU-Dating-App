<?php

! defined( 'WPDATING_FACEBOOK_ABSPATH' ) ? define( 'WPDATING_FACEBOOK_ABSPATH', plugin_dir_path( __FILE__ ) ) : null;
! defined( 'WPDATING_FACEBOOK_URL' ) ? define( 'WPDATING_FACEBOOK_URL', plugin_dir_url( __FILE__ ) ) : null;

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpdating-facebook.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpdating_facebook() {
	new Wpdating_Facebook();
}

run_wpdating_facebook();

