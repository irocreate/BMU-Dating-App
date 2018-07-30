<div role="banner" class="ui-header ui-bar-a" data-role="header">
     <?php include_once("page_back.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_UPGRADE'); ?></h1>
     <?php include_once("page_home.php");?> 
</div>
<?php
$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
$dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
$update_payment_details = $wpdb->get_row("SELECT * FROM $dsp_temp_payments_table where user_id='$user_id'");
$check_already_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
if ($check_already_user_exists <= 0) {
    //echo "<br>INSERT INTO $dsp_payments_table SET pay_user_id = '$update_payment_details->user_id',pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id',recurring_profile_status='1'";
    $wpdb->query("INSERT INTO $dsp_payments_table SET pay_user_id = '$update_payment_details->user_id',pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id',recurring_profile_status='1'");
} else {

//echo "<br>UPDATE $dsp_payments_table SET pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id', recurring_profile_status='1'  WHERE pay_user_id = '$update_payment_details->user_id'";
    $wpdb->query("UPDATE $dsp_payments_table SET pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id', recurring_profile_status='1'  WHERE pay_user_id = '$update_payment_details->user_id'");
}

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

wp_mail($to, $subject, $message, $headers);
?>
<div class="ui-content" data-role="content">
    <div class="content-primary">	
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
            <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                <div align="center" style="color:#FF0000;">
                    <b><?php echo language_code('DSP_THANKYOU_FOR_UR_PAYMENT'); ?></b>
                </div>
            </li>
        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>