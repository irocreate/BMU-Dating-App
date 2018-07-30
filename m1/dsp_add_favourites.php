<?php

include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include_once("../general_settings.php");

include_once(WP_DSP_ABSPATH . "/files/includes/dsp_mail_function.php");

global $wpdb;

$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;


$dsp_user_favourites_table;

$session_user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';

$fav_user_id = isset($_REQUEST['fav_userid']) ? $_REQUEST['fav_userid'] : '';

//echo 'user'.$session_user_id.' fav '.$fav_user_id;



$date = date("Y-m-d");

if ($session_user_id != "" && $fav_user_id != "" && ($session_user_id != $fav_user_id)) {
    $exist_fav_user_screenname = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$fav_user_id'");

    $fav_screenname = $exist_fav_user_screenname->display_name;

    $exist_fav_user_title = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$fav_user_id'");

    $fav_title = $exist_fav_user_title->title;

    $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_favourites_table WHERE user_id=$session_user_id AND favourite_user_id=$fav_user_id");

    if ($num_rows <= 0) {
        //echo "INSERT INTO $dsp_user_favourites_table SET user_id = $session_user_id,favourite_user_id ='$fav_user_id' ,fav_screenname='$fav_screenname',fav_date_added='$date',fav_title='$fav_title',fav_private='N'";
        $wpdb->query("INSERT INTO $dsp_user_favourites_table SET user_id = $session_user_id,favourite_user_id ='$fav_user_id' ,fav_screenname='$fav_screenname',fav_date_added='$date',fav_title='$fav_title',fav_private='N'");

        $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='9'");

        $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$fav_user_id'");

        $reciver_name = $reciver_details->display_name;

        $receiver_email_address = $reciver_details->user_email;

        $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$session_user_id'");

        $sender_name = $sender_details->display_name;

        $email_subject = $email_template->subject;

        $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);

        $mem_email_subject = $email_subject;

        $email_message = $email_template->email_body;

        $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);

        $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);

        $MemberEmailMessage = $email_message;
        $admin_email = get_option('admin_email');
        $from = $admin_email;
        send_email($receiver_email_address, $from, $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");

        $print_msg = language_code('DSP_FAVOURITES_ADDED_SUCESS_MSG');
        echo $print_msg;
    } else {
        $print_msg = language_code('DSP_ALREADY_ADDED_FAVOURITES_MSG');
        echo $print_msg;
    }
} else if ($session_user_id == $fav_user_id) {
    $print_msg = language_code('DSP_CANT_ADD_YOURSELF_MSG');
    echo $print_msg;
}
?>