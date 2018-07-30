<?php
global $wp_query;
global $wpdb;
$page_id = $wp_query->post->ID; //fetch post query string id
$membership_plan_id = get('id');
$gateway_name = strtolower($profile_pageurl);
$gateway_details = apply_filters('dsp_get_gateway_details',$gateway_name);
$exist_membership_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table where membership_id='$membership_plan_id'");
$plan_days = $exist_membership_plan->no_of_days;
$membership_plan_amount = $exist_membership_plan->price;
$membership_plan = $exist_membership_plan->name;
$payment_date = date("Y-m-d");
$wpdb->query("DELETE FROM $dsp_temp_payments_table WHERE user_id = '$user_id'");
$wpdb->query("INSERT INTO $dsp_temp_payments_table SET user_id = '$user_id',plan_id = '$membership_plan_id',gateway_id='$gateway_details->gateway_id',plan_amount ='$membership_plan_amount',plan_days='$plan_days',plan_name='$membership_plan',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL $plan_days DAY),payment_status=0");
?>
<div class="dsp_box-out">
    <div class="dsp_box-in">
        <?php if(isset($gateway_details) && !empty($gateway_details)): ?>
            <div class="gateway_info">
                    <h2><?php echo $gateway_details->title;?></h2>
                    <div class="description">
                        <p><?php echo $gateway_details->description;?></p>
                    </div>
                    <div class="instruction">
                        <p><?php echo $gateway_details->instruction;?></p>
                    </div>
            </div>
        <?php endif; ?>
        <div align="center" style="color:#FF0000;"><b><?php echo language_code('DSP_THANKYOU_FOR_UR_PAYMENT'); ?></b></div>
    </div>
</div>