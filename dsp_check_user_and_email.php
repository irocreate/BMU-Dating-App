<?php 
@include_once('../../../wp-config.php');
include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");
include_once(WP_DSP_ABSPATH . "/files/includes/dsp_mail_function.php");
global $wpdb;
$user_n_email = isset($_POST['email']) ? $_POST['email'] : '';
$result = array();
$check_user_exist = $wpdb->get_row("SELECT * FROM `$dsp_user_table` where user_login like '$user_n_email' or user_email like '$user_n_email'");
if (count($check_user_exist) > 0) {    
    $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='22'");
    $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='" . $check_user_exist->ID . "'");
    $reciver_name = $reciver_details->user_login;
    $receiver_email_address = $reciver_details->user_email;
    $admin_email = get_option('admin_email');
    $from = $admin_email;
    $url = site_url();
    $email_subject = $email_template->subject;
    $mem_email_subject = $email_subject;
    $email_message = $email_template->email_body;
    $email_message = str_replace("[SITE_URL]", $url, $email_message);
    $email_message = str_replace("[USERNAME]", $reciver_name, $email_message);
    $email_message = str_replace("[RESET_URL]", get_site_url() . '/members/reset_password/?key=' . base64_encode($check_user_exist->ID . ',' . $check_user_exist->user_pass), $email_message);
    $MemberEmailMessage = $email_message;
    // dsp_send_email($receiver_email_address, $from, get_bloginfo('name'), $mem_email_subject, $MemberEmailMessage, $message_html = "");
    $wpdating_email  = Wpdating_email_template::get_instance();
    $result = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
    $result['output'] = 1;
}
 else {
    $result['output'] = 0;
}
 
echo json_encode($result);