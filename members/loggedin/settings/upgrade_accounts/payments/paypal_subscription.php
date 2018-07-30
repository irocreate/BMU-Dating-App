<?php

if(isset($_POST['membership_id']))
{
    global $wpdb;
    $membership_id = intval($_POST['membership_id']);
    $gateway_id = intval($_POST['id']);  
    $dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
    $dsp_gateways_table = $wpdb->prefix . DSP_GATEWAYS_TABLE;
    $memberships_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table where membership_id=$membership_id"); 
    $gateway = $wpdb->get_row("SELECT * FROM $dsp_gateways_table where gateway_id=$gateway_id"); 
    
    $site_url = add_query_arg('recurring','true',get_site_url().'/');
    $no_of_days = $memberships_plan->no_of_days;
    $name = $memberships_plan->name;
    $price = $memberships_plan->price;
    
    $paypal_duration_array = paypal_recurring_formatted_duration($no_of_days);

    if($paypal_duration_array['error']==false)    
    {    
        if ($gateway->test_mode == '1') 
        $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
        else        
        $paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
        
        ?>
        <div>
            <form id="paypal_subscription_form" action="<?php echo $paypal_url; ?>" method="post">
                <!-- Identify your business so that you can collect the payments. -->
                <input type="hidden" name="business" value="<?php echo $gateway->address; ?>">

                <!-- Specify a Subscribe button. -->
                <input type="hidden" name="cmd" value="_xclick-subscriptions">
                <!-- Identify the subscription. -->
                <input type="hidden" name="item_name" value="<?php echo $name; ?>">
                <input type="hidden" name="item_number" value="<?php echo $user_id . '-' . $membership_id; ?>">

                <!-- Set the terms of the regular subscription. -->
                <input type="hidden" name="currency_code" value="<?php echo $gateway->currency; ?>">
                <input type="hidden" name="a3" value="<?php echo round($price, 2); ?>">
                <input type="hidden" name="p3" value="<?php echo $paypal_duration_array['period']; ?>">
                <input type="hidden" name="t3" value="<?php echo $paypal_duration_array['unit']; ?>">

                <!-- Set recurring payments until canceled. -->
                <input type="hidden" name="src" value="1">
                <input type="hidden" name="return" value="<?php echo $root_link . "setting/dsp_subscription_successful/"; ?>">
                <input type="hidden" name="rm" value="0">
                <input type="hidden" name="notify_url" value="<?php echo $site_url; ?>">
                <!-- Display the payment button. -->

            </form>              
        </div>
        <script>
            jQuery(document).ready(function(){
               jQuery('#paypal_subscription_form').submit(); 
            });
        </script>
        <?php 
    }    
}
?>

 