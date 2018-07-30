<?php
@include_once('../../../wp-config.php');
include_once(WP_DSP_ABSPATH . "files/includes/table_names.php");
global $wpdb;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;  // print session USER_ID
$dsp_chat_request = $wpdb->prefix . DSP_CHAT_REQUEST_TABLE;
$wpdb->query("delete from $dsp_chat_request where sender_id=" . $_REQUEST['sender_id'] . " and receiver_id=$user_id");
