<?php
$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
$dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
$discount_code = isset($_SESSION['code'])? $_SESSION['code']: '';
$update_payment_details = $wpdb->get_row("SELECT * FROM $dsp_temp_payments_table where user_id='$user_id'");
if (count($update_payment_details) > 0) {
    $check_already_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
    if ($check_already_user_exists <= 0) {
        $wpdb->query("INSERT INTO $dsp_payments_table SET pay_user_id = '$update_payment_details->user_id',pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id',recurring_profile_status='1'");
    } else {
        $wpdb->query("UPDATE $dsp_payments_table SET pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id', recurring_profile_status='1'  WHERE pay_user_id = '$update_payment_details->user_id'");
    }

    if( isset($discount_code) && !empty($discount_code)){
        dsp_update_discount_coupan_used($discount_code);
        add_user_meta(get_current_user_id(),'discount_code',$discount_code);
    }
    if(dsp_issetGivenEmailSetting($user_id,'payment_successful')){
        $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='16'");
        $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$user_id'");
        $reciver_name = $reciver_details->display_name;
        $receiver_email_address = $reciver_details->user_email;
        $siteurl = get_option('siteurl');
        $email_subject = $email_template->subject;
        $email_message = $email_template->email_body;
        $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
        $email_message = str_replace("<#DOMAIN_NAME#>", $siteurl, $email_message);
        $MemberEmailMessage = $email_message;
        $to = $receiver_email_address;
        $subject = $email_subject;
        $message = $MemberEmailMessage;
        $admin_email = get_option('admin_email');
        $from = $admin_email;
        $headers = "From: $from";
        // wp_mail($to, $subject, $message, $headers);
        $wpdating_email  = Wpdating_email_template::get_instance();
        $result = $wpdating_email->send_mail( $to, $subject, $message);
    }
} else {
    extract($_REQUEST);
    $credit_purchase_id = $wpdb->get_var("SELECT credit_purchase_id FROM `$dsp_credits_purchase_history` where user_id ='$user_id' and status ='0' ORDER BY  `credit_purchase_id` DESC  limit 1");
    $wpdb->update($dsp_credits_purchase_history, array('status' => 1), array('credit_purchase_id' => $credit_purchase_id));
    $credit = $wpdb->get_var("select credit_purchased from $dsp_credits_purchase_history where credit_purchase_id='$credit_purchase_id'");
    $chk_credit_row = $wpdb->get_var("select count(*) from $dsp_credits_usage_table where user_id='$user_id'");
    $credit_row = $wpdb->get_row("select * from $dsp_credits_table");
    $emails_per_credit = $credit_row->emails_per_credit;
    $new_emails = $credit * $emails_per_credit;
    if ($chk_credit_row > 0) {
        $credit_usage_row = $wpdb->get_row("select * from $dsp_credits_usage_table where user_id='$user_id'");
        $wpdb->update($dsp_credits_usage_table, array('no_of_credits' => $credit_usage_row->no_of_credits + $credit,
            'no_of_emails' => $credit_usage_row->no_of_emails + $new_emails, 'email_sent' => 0), array(
            'user_id' => $user_id));
    } else {
        $wpdb->insert($dsp_credits_usage_table, array('no_of_credits' => $credit,
            'no_of_emails' => $new_emails, 'user_id' => $user_id));
    }
    $wpdb->query("update $dsp_credits_table set credits_purchased=credits_purchased+$credit");
    if(dsp_issetGivenEmailSetting($user_id,'credit_purchase')){
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
        $wpdating_email  = Wpdating_email_template::get_instance();
        $result = $wpdating_email->send_mail( $to, $subject, $message);
        // wp_mail($to, $subject, $message, $headers);
    }    
}
if(isset($_SESSION['code'])){
unset($_SESSION['code']);
}
?>
<div class="dsp_box-out">
    <div class="dsp_box-in">
        <div align="center" style="color:#FF0000;"><b><?php echo language_code('DSP_THANKYOU_FOR_UR_PAYMENT'); ?></b></div>
    </div>
</div>