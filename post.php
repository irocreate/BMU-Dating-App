<?php

session_start();
@include_once('../../../wp-config.php');
include_once(WP_DSP_ABSPATH . 'dsp_validation_functions.php');
global $wpdb;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;  // print session USER_ID
if (isset($_POST)) {
    $text = esc_sql(sanitizeData(trim($_POST['text']), 'xss_clean'));
    $text = apply_filters('dsp_spam_filters', $text);
    $dsp_chat_table = $wpdb->prefix . "dsp_chat";
    $count = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_chat_table ");
   if ($count < 100) {
        $insert = $wpdb->query("INSERT INTO $dsp_chat_table SET sender_id='" . $user_id . "', chat_text='$text', time='" . date('g:i A') . "', date='" . date("Y-m-d ") . "'");
    } else {
        $wpdb->query("DELETE FROM $dsp_chat_table order by chat_id ASC LIMIT 1");
        $insert = $wpdb->query("INSERT INTO $dsp_chat_table SET sender_id='" . $user_id . "', chat_text='$text', time='" .  date('g:i A') . "', date='" . date("Y-m-d ") . "'");
    }
}
?>
