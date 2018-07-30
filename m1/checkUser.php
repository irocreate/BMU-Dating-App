<?php

include("../../wp-config.php");

if (is_user_logged_in()) {
    global $current_user;
    echo 'login user';
    //global $wpdb;
    //get_currentuserinfo();
    //echo $current_user->ID;


    $current_user = wp_get_current_user();
    echo 'idddddd' . $current_user->ID;
} else {
    echo 'no user login';
}
?>