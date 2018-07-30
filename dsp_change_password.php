<?php

@include_once('../../../wp-config.php');


include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");

include_once(WP_DSP_ABSPATH . "/files/includes/dsp_mail_function.php");
include_once(WP_DSP_ABSPATH . "/files/includes/functions.php");

extract($_REQUEST);
global $wpdb;
global $msdb;
global $master_table_prefix;
global $site_name;
$result = array();
wp_set_password($password, $user_id);
if(dsp_issetGivenEmailSetting($user_id,'reset_password')){
	 	$email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='23'");
        $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$user_id'");
        $reciver_name = $reciver_details->display_name;
        $receiver_email_address = $reciver_details->user_email;
       // $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$userid'");
        //$sender_name = $sender_details->display_name;
        $admin_email = get_option('admin_email');
        $admin_username = dsp_get_admin_username($admin_email);
        $from = $admin_email;
       // $url = $_SERVER['HTTP_HOST'];
        $email_subject = $email_template->subject;
        $email_subject = str_replace("<#SENDER_NAME#>", $admin_username , $email_subject);
        $mem_email_subject = $email_subject;
        $email_message = $email_template->email_body;
        $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
        $email_message = str_replace("<#SENDER_NAME#>", $admin_username, $email_message);
        //$email_message = str_replace("<#URL#>", $url, $email_message);
        $MemberEmailMessage = $email_message;
        // dsp_send_email($receiver_email_address, $from, $admin_email, $mem_email_subject, $MemberEmailMessage, $message_html = "");
        $wpdating_email  = Wpdating_email_template::get_instance();
        $result_mail = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );

        $result['output'] = 1;
}else{
    $result['output'] = 0;
}
echo json_encode($result);
exit;