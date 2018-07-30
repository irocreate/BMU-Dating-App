<?php

include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
$dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;

global $wpdb;

$membership_plan_id = $_REQUEST['membership_plan_id'];
$user_id = $_REQUEST['user_id'];


$payment_date = date("Y-m-d");

$wpdb->query("DELETE FROM $dsp_temp_payments_table WHERE user_id = '$user_id'");
$exist_membership_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table where membership_id='$membership_plan_id'");
$plan_days = $exist_membership_plan->no_of_days;
$membership_plan_amount = $exist_membership_plan->price;
$membership_plan = $exist_membership_plan->name;

$wpdb->query("INSERT INTO $dsp_temp_payments_table SET user_id = '$user_id',plan_id = '$membership_plan_id',plan_amount ='$membership_plan_amount',plan_days='$plan_days',plan_name='$membership_plan',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL $plan_days DAY),payment_status=0");
//$exist_gateway_address=$wpdb->get_row("SELECT * FROM $dsp_gateways_table"); 
//$business=$exist_gateway_address->address;
//$currency_code=$exist_gateway_address->currency;
?>