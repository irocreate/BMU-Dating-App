<?php

function dsp_add_news_feed($user_id, $type) {
    global $wpdb;
    $dsp_news_feed_table = $wpdb->prefix . DSP_NEWS_FEED_TABLE;
    $wpdb->query("insert into $dsp_news_feed_table values('','$user_id','$type','" . date("Y-m-d H:i:s") . "')");
}

function dsp_add_notification($user_id, $member_id, $type) {
    global $wpdb;
    $dsp_notification = $wpdb->prefix . DSP_NOTIFICATION_TABLE;
    $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
    $check_notification_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'notification'");
    if ($check_notification_mode->setting_status == 'Y') {
        if ($user_id > 0) {
            $wpdb->query("insert into $dsp_notification values('','$user_id','$member_id','$type','" . date("Y-m-d H:i:s") . "','Y')");
        }
    }
}

?>