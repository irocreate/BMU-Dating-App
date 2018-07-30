<?php 
@include_once('../../../wp-config.php');
include_once(WP_DSP_ABSPATH . 'functions.php');
include_once(WP_DSP_ABSPATH . "/files/includes/functions.php");
global $wpdb;
$current_user = wp_get_current_user();
$imagepath = get_option('siteurl') . '/wp-content/';  // image Path
$user_id = $current_user->ID;  // print session USER_ID
$posts_table = $wpdb->prefix . POSTS;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");
$member_pageid = $member_page_title_ID->setting_value;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");
$member_page_id = $post_page_title_ID->ID;  // Print Site root link
$root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";  // Print Site root link
$dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$gender = (isset($_REQUEST['gender']) && $_REQUEST['gender'] !='All') ? $_REQUEST['gender'] : 'all';

if ($gender == 'M')
    $gender_check = " and profile.gender='M'";
else if ($gender == 'F')
    $gender_check = " and profile.gender='F'";
else if ($gender == 'C')
    $gender_check = " and profile.gender='C'";
else
    $gender_check = " ";
// get the random online members setting
$check_online_member_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'random_online_members'");
$random_online_status = $check_online_member_mode->setting_status;
$online_users = array();
$filters = array(
            'gender' => $gender,
            'start' =>0,
            'last' => 9,
        );
if($random_online_status == 'Y')
{
    $random_online_number = $check_online_member_mode->setting_value;
    $online_users = dsp_randomOnlineMembers($random_online_number,$filters);
}else
{   
   $online_users = dsp_getOnlineMembers($filters);
}
//$online_users = $wpdb->get_results("SELECT distinct online.user_id,gender FROM `$dsp_online_table` online inner join $dsp_user_profiles profile on(online.user_id=profile.user_id) where  online.user_id<>'$user_id' $gender_check");
if (count($online_users) > 0) {
    foreach ($online_users as $online_row) {
        $displayed_member_name = $wpdb->get_var("SELECT display_name FROM $dsp_users_table WHERE ID = '" . $online_row->user_id . "'");
        $imagePath = $online_row->private == 'Y' ? WPDATE_URL . '/images/private-photo-pic.jpg'  : display_members_photo($online_row->user_id, $imagepath);
        ?>
        <a href="<?php
        if ($online_row->gender == 'C') {
            echo $root_link . get_username($online_row->user_id) . "/my_profile/";
        } else {
            echo $root_link . get_username($online_row->user_id) . "/";
        }
        ?>"><img src="<?php echo display_members_photo($online_row->user_id, $imagepath); ?>" title="<?php echo $displayed_member_name; ?>" alt="<?php echo $displayed_member_name; ?>" /></a>
        <?php
    }
} else {
    echo '<b>' . language_code('DSP_NO_USER_ONLINE') . '</b>';
}
?>
