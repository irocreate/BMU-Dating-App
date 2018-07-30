<?php

@include_once('../../../wp-config.php');
include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");
include_once(WP_DSP_ABSPATH . "/files/includes/functions.php");
include_once(WP_DSP_ABSPATH . "/files/includes/dsp_mail_function.php");
extract($_REQUEST);
global $wpdb;
$dsp_meet_me = $wpdb->prefix . DSP_MEET_ME_TABLE;
$current_user = wp_get_current_user();
$userid = $current_user->ID;  // print session USER_ID
$datetime = date('Y-m-d H:i:s');
$check_row = $wpdb->get_var(" select count(*) from $dsp_meet_me where user_id='$userid' and member_id='$user_id'");

if ($check_row == 0 && dsp_issetGivenEmailSetting($userid,'meet_me')) {
    $insert = $wpdb->insert($dsp_meet_me, array('user_id' => $userid, 'member_id' => $user_id,
        'status' => $action, 'datetime' => $datetime));
    if ($action == language_code('DSP_OPTION_YES')) {

        $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='19'");
        $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$user_id'");
        $reciver_name = $reciver_details->display_name;
        $receiver_email_address = $reciver_details->user_email;
        $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$userid'");
        $sender_name = $sender_details->display_name;
        $admin_email = get_option('admin_email');
        $from = $admin_email;
        $url = '<a href= "'.ROOT_LINK . $sender_details->user_login. '">'.$sender_name.'</a>';
        $email_subject = $email_template->subject;
        $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);
        $mem_email_subject = $email_subject;
        $email_message = $email_template->email_body;
        $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
        $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
        $email_message = str_replace("<#URL#>", $url, $email_message);
        $MemberEmailMessage = $email_message;
        // dsp_send_email($receiver_email_address, $from, $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");
        $wpdating_email  = Wpdating_email_template::get_instance();
        $result = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
//        wp_mail($receiver_email_address, $mem_email_subject, $MemberEmailMessage);
    }
}else{ ?>
     <div class="dsp_box-out">
            <div class="dsp_box-in">
                <p style="color:#FF0000; padding-left:30px;">
                     <?php    
                         $print_msg = language_code('DSP_CANT_SEND_MEET_ME_MSG');
                         echo $print_msg;
                     ?>     
                </p>
            </div>
        </div>
<?php }