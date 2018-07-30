<?php

! defined( 'WPDATING_GALLERY_ABSPATH' ) ? define( 'WPDATING_GALLERY_ABSPATH', plugin_dir_path( __FILE__ ) ) : null;
! defined( 'WPDATING_GALLERY_URL' ) ? define( 'WPDATING_GALLERY_URL', plugin_dir_url( __FILE__ ) ) : null;

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpdating-gallery.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpdating_gallery() {
	new Wpdating_Gallery();
}

run_wpdating_gallery();

