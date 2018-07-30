<?php

class Wpdating_Profile_Picture
{
    private $user_id;

    public function __construct()
    {
        $this->load_dependencies();
        $this->define_public_hooks();
    }

    public function load_dependencies()
    {
        require_once WPDATING_PROFILE_PICTURE_ABSPATH . 'public/class-wpdating-profile-picture-public.php';
    }

    public function define_public_hooks()
    {
        $plugin_public = new Wpdating_Profile_Picture_Public();
        add_action('wp_enqueue_scripts', array(&$plugin_public, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array(&$plugin_public, 'enqueue_scripts'));
        add_action('wp_ajax_wp_change_pic', array($this, 'wpdating_change_profile_pic'));
        add_action('wpdating_profile_pic_change', array($this, 'wpdating_profile_pic_change_func'), 10, 1);
    }

    public function wpdating_change_profile_pic()
    {

    }

    public function wpdating_profile_pic_change_func($user_id)
    {
        $this->user_id = $user_id;
        ?>

        <div class="update_profile_text_div">
            <a id="change-profile-pic" class="">
                <i class="fa fa-camera" aria-hidden="true"></i><p class="update_profile_text">Update profile Picture</p>
            </a>
        </div>
        <!--        <img class="edit-image"-->
        <!--             src="--><?php //echo WPDATE_URL . 'images/edit-button.png'
        ?><!--">-->


        <?php
        include_once WP_DSP_ABSPATH . 'wpdating-upload-crop/modal.php';
    }

}