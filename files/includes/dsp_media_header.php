<?php 
/*

  Copyright (C) www.wpdating.com - All Rights Reserved!

  Author - www.wpdating.com

  WordPress Dating Plugin

  contact@wpdating.com

 */

global $wpdb;
include_once(WP_DSP_ABSPATH . "general_settings.php");
$current_user = wp_get_current_user();
$user_id = $current_user->ID;

$root_link = get_bloginfo('url');

$pageURL = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';

// TABLE NAMES

$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;

$dsp_user_photos_table = $wpdb->prefix . DSP_USER_PHOTOS_TABLE;

$dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;

$dsp_status_table = $wpdb->prefix . DSP_STATUS_TABLE;

$dsp_profile_setup_table = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;

$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;

$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;

$dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;

$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

$dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;

$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

$check_pagination_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'pagination_color'");

/////////pagination dynamic color from admin start/////////////////
?>
<style>
    #wpbody-content .paging-box-withbtn .wpse_pagination .current{
        background:#<?php echo $check_pagination_color->setting_value; ?>; 
    }
</style>
<?php
/////////pagination dynamic color from admin start/////////////////
// TABLE NAMES

if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

// to include colorbox script in plugin.
wp_enqueue_script('colorbox',  WPDATE_URL . '/colorbox/jquery.colorbox.js', array(), '', true);

//if(get('pagetitle')!="" && get('pagetitle')=='template-one'){
// to include image-picker script on view profile page.
//}
// to include colorbox stylesheet in plugin.
wp_deregister_style('colorbox');
wp_register_style('colorbox', WPDATE_URL . '/css/colorbox.css');
wp_enqueue_style('colorbox');
// to include pagination stylesheet in plugin.
wp_deregister_style('paging');
wp_register_style('paging',  WPDATE_URL . '/css/pagination.css');
wp_enqueue_style('paging');
?>


<script>

    var $k = jQuery.noConflict();

    $k(document).ready(function() {

        $k(".group1").colorbox({rel: 'group1'});



        $k("#click").click(function() {

            $k('#click').css({"background-color": "#f00", "color": "#fff", "cursor": "inherit"}).text("Open this window again and this message will still be here.");

            return false;

        });

    });

</script>

<div class="wrap"><h2><?php echo __(language_code('DSP_HEADER_SITE_ADMIN'), 'dsp_trans_domain') ?></h2></div>

<div id="navmenu" align="left">

    <ul>

        <li <?php if (($pageURL == "media_profiles") || ($pageURL == "")) { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>

            <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_profiles" title="<?php echo language_code('DSP_MEDIA_HEADER_PROFILES') ?>">

                <?php echo language_code('DSP_MEDIA_HEADER_PROFILES') ?></a><span class="dsp_tab1_span">|</span></li>

        <li <?php if ($pageURL == "gallery_photos") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>

            <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=gallery_photos&status=0" title="<?php echo language_code('DSP_MEDIA_HEADER_ALBUM_PHOTOS') ?>">

                <?php echo language_code('DSP_MEDIA_HEADER_ALBUM_PHOTOS') ?></a><span class="dsp_tab1_span">|</span></li>

        <li <?php if ($pageURL == "Profile_photos") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>

            <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=Profile_photos&status=0" title="<?php echo language_code('DSP_MEDIA_HEADER_PROFILE_PHOTOS') ?>"><?php echo language_code('DSP_MEDIA_HEADER_PROFILE_PHOTOS') ?></a><span class="dsp_tab1_span">|</span></li>

        <li <?php if ($pageURL == "media_audios") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>

            <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_audios" title="<?php echo language_code('DSP_MEDIA_HEADER_AUDIOS') ?>">

                <?php echo language_code('DSP_MEDIA_HEADER_AUDIOS') ?></a><span class="dsp_tab1_span">|</span></li>

        <li <?php if ($pageURL == "media_comments") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>

            <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_comments" title="<?php echo language_code('DSP_MEDIA_HEADER_COMMENTS') ?>">

                <?php echo language_code('DSP_MEDIA_HEADER_COMMENTS') ?></a><span class="dsp_tab1_span">|</span></li>

        <li <?php if ($pageURL == "media_videos") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>

            <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_videos" title="<?php echo language_code('DSP_MEDIA_HEADER_VIDEOS') ?>">

                <?php echo language_code('DSP_MEDIA_HEADER_VIDEOS') ?></a><span class="dsp_tab1_span">|</span></li>

        <li  <?php if ($pageURL == "admin_mails") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>

            <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=admin_mails" title="<?php echo language_code('DSP_MEDIA_HEADER_EMAILS') ?>">

                <?php echo language_code('DSP_MEDIA_HEADER_EMAILS') ?></a><span class="dsp_tab1_span">|</span></li>

        <li  <?php if ($pageURL == "admin_blogs") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=admin_blogs" title="<?php echo language_code('DSP_MEDIA_HEADER_BLOGS') ?>">
                <?php echo language_code('DSP_MEDIA_HEADER_BLOGS') ?>
            </a>
            <span class="dsp_tab1_span">|</span>
        </li>
        <li  <?php if ($pageURL == "template_images") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=template_images" title="<?php echo language_code('DSP_TEMPLATE_IMAGES') ?>">
                <?php echo language_code('DSP_TEMPLATE_IMAGES') ?>
            </a>
        </li>

    </ul>

</div>

<?php
if ($pageURL == "media_profiles") {

    include_once( WP_DSP_ABSPATH . 'files/includes/dsp_media_profile_subheader.php');
} else if ($pageURL == "gallery_photos") {
    include_once( WP_DSP_ABSPATH . 'files/includes/dsp_media_gallery_subheader.php');
} else if ($pageURL == "media_comments") {

    include_once( WP_DSP_ABSPATH . 'files/includes/dsp_media_comments_subheader.php');
} else if ($pageURL == "media_videos") {

    include_once( WP_DSP_ABSPATH . 'files/includes/dsp_media_videos_subheader.php');
} else if ($pageURL == "media_audios") {

    include_once( WP_DSP_ABSPATH . 'files/includes/dsp_media_audios_subheader.php');
} else if ($pageURL == "admin_mails") {

    include_once( WP_DSP_ABSPATH . 'files/dsp_admin_emails.php');
} else if ($pageURL == "admin_blogs") {

    include_once( WP_DSP_ABSPATH . 'files/dsp_admin_blogs.php');
} else if ($pageURL == "Profile_photos") {

    include_once( WP_DSP_ABSPATH . 'files/includes/dsp_media_member_subheader.php');
} else if ($pageURL == "media_profile_view") {

    include_once( WP_DSP_ABSPATH . 'files/dsp_media_profiles_view.php');
} else if ($pageURL == "media_profile_view_geo") {

    include_once( WP_DSP_ABSPATH . 'files/dsp_media_profiles_view_geo.php');
} else if ($pageURL == "view_admin_msg") {

    include_once( WP_DSP_ABSPATH . 'files/dsp_view_admin_email.php');
}else if ($pageURL == "template_images") {

    include_once( WP_DSP_ABSPATH . 'files/dsp_upload_template_images.php');
}
 else {

    include_once( WP_DSP_ABSPATH . 'files/includes/dsp_media_profile_subheader.php');
}
?>