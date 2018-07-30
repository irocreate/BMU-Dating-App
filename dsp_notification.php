<?php
@include_once('../../../wp-config.php');
include_once('functions.php');
include_once("include_dsp_tables.php");
include_once("files/includes/functions.php");
global $wpdb;
global $current_user;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$check_notification_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'notification'");
// check notification_postition settings
$check_notification_postition_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'notification_postition'");
$current_user = wp_get_current_user();
if ($check_notification_mode->setting_status == 'Y') {
    $dsp_notification = $wpdb->prefix . DSP_NOTIFICATION_TABLE;
    $user_id = $current_user->ID;
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'show') {
        $imagepath = get_option('siteurl') . '/wp-content/';  // image Path
        $news_feed_users = $wpdb->get_results("(SELECT friend_uid as user_id FROM `$dsp_my_friends_table` WHERE user_id='$user_id') union (SELECT favourite_user_id as user_id FROM `$dsp_user_favourites_table` WHERE user_id='$user_id')");
        $news_feed_user = "";
        foreach ($news_feed_users as $users) {
            $news_feed_user.=$users->user_id . ',';
        }
        $news_feed_user = rtrim($news_feed_user, ',');
        if ($user_id != 0 && $news_feed_user != "") {
            $notification_row = $wpdb->get_row("SELECT * FROM `$dsp_notification` where ((member_id='$user_id') or (member_id=0 and user_id in(" . $news_feed_user . "))) and status='Y' order by datetime desc limit 1");
            if ($notification_row != null) {
                $dsp_users_table = $wpdb->prefix . 'users';
                $displayed_member_name = $wpdb->get_var("SELECT display_name FROM $dsp_users_table WHERE ID = '" . $notification_row->user_id . "'");
                if ($notification_row->type == 'send_email')
                    $notification_text = $displayed_member_name . ' ' . language_code('DSP_NEWS_SEND_MESSAGE');
                else if ($notification_row->type == 'add_favourites')
                    $notification_text = $displayed_member_name . ' ' . language_code('DSP_NEWS_ADD_FAVOURITES');
                else if ($notification_row->type == 'friend_request')
                    $notification_text = $displayed_member_name . ' ' . language_code('DSP_NEWS_ADD_FRIEND');
                else if ($notification_row->type == 'view_profile')
                    $notification_text = $displayed_member_name . ' ' . language_code('DSP_NEWS_VIEW_PROFILE');
                else if ($notification_row->type == 'send_wink')
                    $notification_text = $displayed_member_name . ' ' . language_code('DSP_NEWS_SEND_WINK');
                else if ($notification_row->type == "status")
                    $notification_text = $displayed_member_name . ' ' . language_code('DSP_NEWS_FEED_STATUS');
                else if ($notification_row->type == "login")
                    $notification_text = $displayed_member_name . ' ' . language_code('DSP_NEWS_FEED_LOGIN');
                else if ($notification_row->type == "logout")
                    $notification_text = $displayed_member_name . ' ' . language_code('DSP_NEWS_FEED_LOGOUT');
                else if ($notification_row->type == "video")
                    $notification_text = $displayed_member_name . ' ' . language_code('DSP_NEWS_FEED_VIDEO');
                else if ($notification_row->type == "audio")
                    $notification_text = $displayed_member_name . ' ' . language_code('DSP_NEWS_FEED_AUDIO');
                else if ($notification_row->type == "gallery_photo")
                    $notification_text = $displayed_member_name . ' ' . language_code('DSP_NEWS_FEED_PHOTO');
                else if ($notification_row->type == "profile_photo")
                    $notification_text = $displayed_member_name . ' ' . language_code('DSP_NEWS_FEED_PROFILE_PHOTO');
                $notification_image = display_members_photo($notification_row->user_id, $imagepath);
                echo '<div class="notification-div notification-' . $check_notification_postition_mode->setting_value . '">
<span class="close-notifiaction" onclick="close_notifications(' . $notification_row->id . ')"></span>
<img style="width:30px; height:30px;" src="' . $notification_image . '" alt="'. $displayed_member_name .'"/> <span>' . $notification_text . '</span>
<input type="hidden" id="notification_id" value="' . $notification_row->id . '">
</div>';
            }
        }
    } else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'hide') {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $notification_row = $wpdb->get_row("SELECT * FROM `$dsp_notification` where id='" . $id . "'");
        if (count($notification_row) > 0) {
            $wpdb->query("update $dsp_notification set status='N' where user_id='" . $notification_row->user_id . "' and type='" . $notification_row->type . "'");
        }
    }
}