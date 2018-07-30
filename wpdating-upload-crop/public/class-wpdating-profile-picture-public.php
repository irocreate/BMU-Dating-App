<?php

class Wpdating_Profile_Picture_Public
{
    public function enqueue_styles()
    {
        wp_enqueue_style('wpdating-imgareaselect', WPDATING_PROFILE_PICTURE_URL . 'css/imgareaselect-default.css',
            array(), '', 'all');
        wp_enqueue_style('wpdating-profile-picture', WPDATING_PROFILE_PICTURE_URL . 'css/profile_picture.css',
            array(), '', 'all');
        wp_enqueue_style('wpdating-font-awesome', WPDATING_PROFILE_PICTURE_URL . 'css/font-awesome.min.css',
            array(), '', 'all');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('wp-jquery-form', WPDATING_PROFILE_PICTURE_URL . 'js/jquery.form.min.js');
        wp_enqueue_script('wp-image-area-select', WPDATING_PROFILE_PICTURE_URL . 'js/jquery.imgareaselect.js');
        wp_enqueue_script('wp-image-functions', WPDATING_PROFILE_PICTURE_URL . 'js/functions.js');
        wp_localize_script( 'wp-image-functions', 'wp_image_area_select_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'path_change_pic' => WPDATING_PROFILE_PICTURE_URL.'change-pic.php') );

    }
}