<?php

! defined( 'WPDATING_PROFILE_PICTURE_ABSPATH' ) ? define( 'WPDATING_PROFILE_PICTURE_ABSPATH', plugin_dir_path( __FILE__ ) ) : null;
! defined( 'WPDATING_PROFILE_PICTURE_URL' ) ? define( 'WPDATING_PROFILE_PICTURE_URL', plugin_dir_url( __FILE__ ) ) : null;

require WPDATING_PROFILE_PICTURE_ABSPATH . 'includes/class-wpdating-profile-picture.php';

function run_wpdating_profile_pic()
{
    new Wpdating_Profile_Picture();
}

run_wpdating_profile_pic();

