<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$pay_member_id = isset($_REQUEST['item_number']) ? $_REQUEST['item_number'] : '';
//$pay_member_id=$_REQUEST['pay_member_id'];
$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
$dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
$update_payment_details = $wpdb->get_row("SELECT * FROM $dsp_temp_payments_table where user_id='$pay_member_id'");
$check_already_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$pay_member_id'");
if ($check_already_user_exists > 0) {

    $wpdb->query("UPDATE $dsp_payments_table SET pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1 WHERE pay_user_id = '$update_payment_details->user_id'");
} else {


    $wpdb->query("INSERT INTO $dsp_payments_table SET pay_user_id = '$update_payment_details->user_id',pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1");
}
?>
<div class="dsp_box-out">
    <div class="dsp_box-in">
               <div align="center" style="color:#FF0000;"><b><?php echo language_code('DSP_THANKYOU_FOR_UR_PAYMENT');?></b></div>
    </div>
</div>