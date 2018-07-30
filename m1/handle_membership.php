<?php

require_once(realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . "/wp-load.php");
global $wpdb;

$dsp_dating_path = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '..';
include_once $dsp_dating_path . '/files/includes/functions.php';
include_once $dsp_dating_path . '/files/includes/table_names.php';
include_once $dsp_dating_path . '/dsp_availabilty.php';

$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
$timezone_variation_tolerance = 15 * 3600 * 1000; // 15 hours; 1 hr = 3600 seconds; 1 sec = 1000 ms
$imagepath = get_option('siteurl') . '/wp-content/';  // image Path

// extra start
//if(strtotime($payment_date) > strtotime($user_payment_expiry_date))
//{
//    $user_payment_expired = true;
//}
//else
//{
//    $user_payment_expired = false;
//}
// extra end

if (isset($_POST) && $_POST['process'] == 'save') {
    $exists_memberships_plan = $wpdb->get_results("SELECT * FROM $dsp_memberships_table where display_status='Y' ORDER BY date_added DESC");
    $current_membership_plan = array();
    $payment_date = $new_payment_date = date("Y-m-d");

    $user_id = $_POST['user_id'];
    //$current_time = $_POST['current_time']; // user js time
    $current_time = time() * 1000;      // php server time * 1000 ms
    $expiryDate = $_POST['expiryDate'];

    $check_already_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
    if ($check_already_user_exists) {
        $user_details = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");
        $membership_last_expiry_date = $user_details->expiration_date;
        $last_expiryDate = strtotime($user_details->expiration_date);
        $last_expiryDate = $last_expiryDate * 1000;
    } else {
        $last_expiryDate = 0;
    }

    if ($last_expiryDate > $current_time) {
        $current_time = $last_expiryDate;
        $new_payment_date = $membership_last_expiry_date;
    }

    $membership_time = $expiryDate - $current_time;
    $time_difference = 999999999999;

    foreach ($exists_memberships_plan as $membership_plan) {
        $no_of_milliseconds = $membership_plan->no_of_days * 60 * 60 * 24 * 1000;

        if (abs($no_of_milliseconds - $membership_time) < $time_difference) {
            $time_difference = abs($no_of_milliseconds - $membership_time);
            $current_membership_plan = $membership_plan;
            $price = $membership_plan->price;
            $no_of_days = $membership_plan->no_of_days;
            $name = $membership_plan->name;
            $membership_id = $membership_plan->membership_id;
        }


        //print_r($expiryDate);echo ' - ';print_r($current_time);echo ' - ';print_r($membership_time);echo ' - ';print_r($no_of_milliseconds);echo '<br>;\r\n';

    }

    // update dsp payments table
    if ($check_already_user_exists <= 0) {
        $wpdb->query("INSERT INTO $dsp_payments_table SET "
            . "pay_user_id = '$user_id',"
            . "pay_plan_id = '$membership_id',"
            . "pay_plan_amount ='$price',"
            . "pay_plan_days='$no_of_days',"
            . "pay_plan_name='$name',"
            . "payment_date='$payment_date',"
            . "start_date='$payment_date',"
            . "expiration_date=DATE_ADD('$new_payment_date', INTERVAL $no_of_days DAY),"
            . "payment_status=1,"
            //. "recurring_profile_id='$update_payment_details->recurring_profile_id',"
            . "recurring_profile_status='1'"
        );
    } else {
        $wpdb->query("UPDATE $dsp_payments_table SET "
            . "pay_plan_id = '$user_id',"
            . "pay_plan_amount ='$price',"
            . "pay_plan_days='$no_of_days',"
            . "pay_plan_name='$name',"
            . "payment_date='$payment_date',"
            . "start_date='$payment_date',"
            . "expiration_date=DATE_ADD('$new_payment_date', INTERVAL $no_of_days DAY),"
            . "payment_status=1,"
            //. "recurring_profile_id='$update_payment_details->recurring_profile_id', "
            . "recurring_profile_status='1'  "
            . "WHERE pay_user_id = '$user_id'"
        );
    }

    if (dsp_issetGivenEmailSetting($user_id, 'payment_successful')) {
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
    }

    print_r('Success');
    exit;
} elseif (isset($_GET) && $_GET['user_id'] != null) {
    $user_id = $_GET['user_id'];
    $check_already_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");

    if ($check_already_user_exists) {
        $user_details = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");
        $expiryDate = strtotime($user_details->expiration_date);
        $expiryDate = $expiryDate * 1000;
        //$image = $imagepath.'/uploads/dsp_media/dsp_images/'.$user_details->image;
        $name = $user_details->pay_plan_name;
        $payment_date = $user_details->payment_date;
        $expiry_date = $user_details->expiration_date;
        $data = array('expiryDate' => $expiryDate, 'name' => $name, 'payment_date' => $payment_date, 'expiry_date' => $expiry_date, 'res' => 'true');
        echo json_encode($data);
        exit;
    } else {
        $data = array('res' => 'false');
        echo json_encode($data);
        exit;
    }
}
