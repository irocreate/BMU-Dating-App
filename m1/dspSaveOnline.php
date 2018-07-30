<?php

global $wpdb;

// ------------------------------------  ONLINE USER CODE ------------------------------------------------------ //
session_start();
$session_id = session_id();
$time = time();
$time_check = $time - 600; //SET TIME 10 Minute 600
$DSP_USER_ONLINE_TABLE = $wpdb->prefix . DSP_USER_ONLINE_TABLE; // Table name



$count_online_users = $wpdb->get_var("SELECT COUNT(*) FROM $DSP_USER_ONLINE_TABLE WHERE user_id='$user_id'");

if ($user_id != "" && $user_id != 0) {
    $status = 'Y';
} else {
    $status = 'N';
}
if ($count_online_users == "0") {
    $wpdb->query("INSERT INTO $DSP_USER_ONLINE_TABLE(session,user_id, status,time)VALUES('$session_id','$user_id','$status','$time')");
} else {
    $wpdb->query("UPDATE $DSP_USER_ONLINE_TABLE SET time='$time',status='$status' WHERE user_id = '$user_id'");
}
$count_user_online = $wpdb->get_var("SELECT COUNT(*) FROM $DSP_USER_ONLINE_TABLE WHERE status='Y'");
//echo "User online : $count_user_online ";
// if over 10 minute, delete session 
$wpdb->query("DELETE FROM $DSP_USER_ONLINE_TABLE WHERE time<$time_check");

// ------------------------------------  ONLINE USER CODE ------------------------------------------------------ //
?>
