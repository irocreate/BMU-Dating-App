<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$pay_member_id = $user_id;
//$pay_member_id=$_REQUEST['pay_member_id'];
extract($_REQUEST);

$credit_purchase_id = get('credit_purchase_id');
$wpdb->update($dsp_credits_purchase_history, array('status' => 1), array('credit_purchase_id' => $credit_purchase_id));
$credit = $wpdb->get_var("select credit_purchased from $dsp_credits_purchase_history where credit_purchase_id='$credit_purchase_id'");
$chk_credit_row = $wpdb->get_var("select count(*) from $dsp_credits_usage_table where user_id='$pay_member_id'");
$credit_row = $wpdb->get_row("select * from $dsp_credits_table");
$emails_per_credit = $credit_row->emails_per_credit;
$gift_per_credit = $credit_row->gifts_per_credit;
$new_emails = $credit * $emails_per_credit;
$new_gifts = $credit * $gift_per_credit;

if ($chk_credit_row > 0) {
    $credit_usage_row = $wpdb->get_row("select * from $dsp_credits_usage_table where user_id='$pay_member_id'");
    $wpdb->update($dsp_credits_usage_table, array('no_of_credits' => $credit_usage_row->no_of_credits + $credit,
        'no_of_emails' => $credit_usage_row->no_of_emails + $new_emails, 'no_of_gifts' => $credit_usage_row->no_of_gifts + $new_gifts), array(
        'user_id' => $pay_member_id));
} else {
    $wpdb->insert($dsp_credits_usage_table, array('no_of_credits' => $credit, 'no_of_emails' => $new_emails,'no_of_gifts' => $new_gifts,
        'user_id' => $pay_member_id));
}
$wpdb->query("update $dsp_credits_table set credits_purchased=credits_purchased+$credit");


$email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='20'");
$reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$user_id'");
$reciver_name = $reciver_details->display_name;
$receiver_email_address = $reciver_details->user_email;
$siteurl = get_option('siteurl');
$email_subject = $email_template->subject;
$email_message = $email_template->email_body;
$email_message = str_replace("<#AMOUNT-OF-CREDITS#>", $credit, $email_message);
$MemberEmailMessage = $email_message;
$to = $receiver_email_address;
$subject = $email_subject;
$message = $MemberEmailMessage;
$admin_email = get_option('admin_email');
$from = $admin_email;
$headers = "From: $from";
// wp_mail($to, $subject, $message, $headers);
$wpdating_email  = Wpdating_email_template::get_instance();
$result = $wpdating_email->send_mail( $to, $subject, $message );
?>
<div class="box-border">
    <div class="box-pedding">
        <div align="center" style="color:#FF0000;"><b><?php echo language_code('DSP_THANKYOU_FOR_UR_PAYMENT'); ?></b></div>
    </div>
</div>