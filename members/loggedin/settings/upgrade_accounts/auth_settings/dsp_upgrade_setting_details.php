<?php
$membership_id = $id;
$membership_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table WHERE membership_id='$membership_id'");
$amountAfterDiscount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '';
$membership_plan_amount = (isset($amountAfterDiscount) && !empty($amountAfterDiscount)) ? $amountAfterDiscount : $membership_plan->price;
global $current_user;
$user_id = $current_user->ID;
?>
<div class="dsp_search_result_box_out">
    <div class="dsp_search_result_box_in">
        <?php if (get('reason') != '') { ?>
            <div align="center" style="color:#FF0000;"><b><?php echo urldecode(get('reason')); ?></b></div>
        <?php
          if(dsp_issetGivenEmailSetting($user_id,'payment_failed')){
            $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='17'");
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
//$to ='mwnt.test4@gmail.com';
            $subject = $email_subject;
            $message = $MemberEmailMessage;
            $admin_email = get_option('admin_email');
            $from = $admin_email;
            $headers = "From: $from";
            // wp_mail($to, $subject, $message, $headers);
            $wpdating_email  = Wpdating_email_template::get_instance();
            $result = $wpdating_email->send_mail( $to, $subject, $message);
        }
       } 


        if (get('mode') == 'cancel' && dsp_issetGivenEmailSetting($user_id,'payment_canceled')) {

            $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='18'");
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
//$to ='mwnt.test4@gmail.com';
            $subject = $email_subject;
            $message = $MemberEmailMessage;
            $admin_email = get_option('admin_email');
            $from = $admin_email;
            $headers = "From: $from";
            $wpdating_email  = Wpdating_email_template::get_instance();
            if ($wpdating_email->send_mail( $to, $subject, $message)) {
                ?>
                <script type='text/javascript'> location.href = '<?php echo $root_link . "setting/upgrade_account/"; ?>'</script>
                <?php
            }
        }
        ?>
        <div class="box-page">

            <form action="<?php echo $root_link . "setting/auth_settings_detail/"; ?>" method="post">
                <div class="card-box">
                    <div style="margin-bottom: 20px;margin-top: 12px;"><img src="<?php echo WPDATE_URL . '/images/discover.png'; ?>" /></div>
                    <div style="margin-bottom: 20px;"><img src="<?php echo WPDATE_URL . '/images/visa.jpg"';?>" /></div>
                    <div style="margin-bottom: 20px;"><img src="<?php echo WPDATE_URL . '/images/mastercard.jpg';  ?>" /></div>
                    <div style="margin-top: 0px;"><img src="<?php echo WPDATE_URL . '/images/americanexpress.jpg';  ?>" /></div>
                </div>
                <ul class="upgrade-details-page">
                    <li><span><?php echo language_code('DSP_GATEWAYS_CREDIT_CARD_NO'); ?>:</span> <input type="text" class="text" size="15" name="x_card_num" value=""> 
                    </li>
                    <li><span><?php echo language_code('DSP_GATEWAYS_EXPIRATION_DATE'); ?>:</span> <input type="text" class="text" size="4" name="x_exp_date" value=""></li>
                    <li><span><?php echo language_code('DSP_GATEWAYS_CCV'); ?>:</span> <input type="text" class="text" size="4" name="x_card_code" value=""></li>
                    <li><span><?php echo language_code('DSP_GATEWAYS_FIRST_NAME'); ?>:</span> <input type="text" class="text" size="15" name="x_first_name" value=""> </li>
                    <li><span><?php echo language_code('DSP_GATEWAYS_LAST_NAME'); ?>:</span> <input type="text" class="text" size="15" name="x_last_name" value=""></li>
                    <li><span><?php echo language_code('DSP_GATEWAYS_ADDRESS'); ?>:</span> <input type="text" class="text" size="15" name="x_address" value=""> </li>
                    <li><span><?php echo language_code('DSP_GATEWAYS_STATE'); ?>:</span> <input type="text" class="text" size="15" name="x_state" value=""></li>
                    <li><span><?php echo language_code('DSP_GATEWAYS_ZIP'); ?>:</span> <input type="text" class="text" size="15" name="x_zip" value=""></li>
                    <li><input name="submit" type="submit" value="<?php echo language_code('DSP_GATEWAYS_SUBMIT'); ?>" /><input name="cancel" type="button" value="<?php echo language_code('DSP_GATEWAYS_CANCEL'); ?>" onclick="CancelPayment()" style="margin-left:30px;" /></li>
                    <li><input type="hidden" class="text" size="14" name="x_membership_id" value="<?php echo $membership_plan->membership_id; ?>">
                        <input type="hidden" class="text" size="14" name="x_name" value="<?php echo $membership_plan->name; ?>">
                        <input type="hidden" class="text" size="14" name="x_amount" value="<?php echo $membership_plan_amount; ?>">
                        <input type="hidden" class="text" size="14" name="x_days" value="<?php echo $membership_plan->no_of_days; ?>">
                        <input type="hidden" class="text" size="14" name="x_desc" value="<?php echo $membership_plan->description; ?>">
                        

                </ul>
                <div style="font-size:12px; float:left; width:100%;"><?php echo language_code('DSP_GATEWAYS_NOTE') ?></div>
            </form>
        </div>
    </div>
</div>
