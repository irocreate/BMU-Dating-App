<?php
include_once('../../../wp-config.php');
global $wpdb;
$dsp_payments_table = $wpdb->prefix . dsp_payments;
$dsp_users_table = $wpdb->prefix . users;
$dsp_email_templates_table = $wpdb->prefix . dsp_email_templates;
$i = 1;
for ($i = $i; $i <= 5; $i++) {
    $members = $wpdb->get_results("SELECT * FROM $dsp_payments_table WHERE DATE_FORMAT(expiration_date, '%m%d') = DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL $i DAY),'%m%d')");

    foreach ($members as $member) {

        $expiration_date = $member->expiration_date;
        $pay_user_id = $member->pay_user_id;
        if(dsp_issetGivenEmailSetting($pay_user_id,'premium_member_expiration')){
            $user = $wpdb->get_row("SELECT user_email FROM $dsp_users_table WHERE ID=$pay_user_id");
            $user_email = $user->user_email;
            $email_templates = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table where mail_template_id= 14");
            $subject = $email_templates->subject;
            $email_template_name = $email_templates->email_template_name;
            $email_body = $email_templates->email_body;
            $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_users_table WHERE ID = $pay_user_id");
            $reciver_name = $reciver_details->display_name;
            $message = $email_templates->email_body;
            $message = $email_templates->email_body;
            $message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $message);
            $to = $user_email;
            $subject = $subject;
            $from = 'contact@domain.com';
            $headers = 'From: ' . $from . "\r\n" .
                'Reply-To: ' . $from . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            $wpdating_email  = Wpdating_email_template::get_instance();
            $result = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
            // wp_mail($to, $subject, $message, $headers);
        }  
    }
}
