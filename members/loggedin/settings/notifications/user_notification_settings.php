<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
$dsp_user_notification_table = $wpdb->prefix . DSP_USER_NOTIFICATION_TABLE;
$premium_member_expiration = isset($_REQUEST['premium_member_expiration']) ? $_REQUEST['premium_member_expiration'] : '';
$payment_successful = isset($_REQUEST['payment_successful']) ? $_REQUEST['payment_successful'] : '';
$payment_failed = isset($_REQUEST['payment_failed']) ? $_REQUEST['payment_failed'] : '';
$payment_canceled = isset($_REQUEST['payment_canceled']) ? $_REQUEST['payment_canceled'] : '';
$credit_balance_low = isset($_REQUEST['credit_balance_low']) ? $_REQUEST['credit_balance_low'] : '';
$credit_purchase = isset($_REQUEST['credit_purchase']) ? $_REQUEST['credit_purchase'] : '';
$meet_me = isset($_REQUEST['meet_me']) ? $_REQUEST['meet_me'] : '';
$reset_password = isset($_REQUEST['reset_password']) ? $_REQUEST['reset_password'] : '';
$wink = isset($_REQUEST['wink']) ? $_REQUEST['wink'] : '';
$update_mode = isset($_REQUEST['update_mode']) ? $_REQUEST['update_mode'] : '';
if (($update_mode == 'update') && ($user_id != "")) {
    $check_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_notification_table WHERE user_id='$user_id'");
    if ($check_user_exists > 0) {
        $wpdb->query("UPDATE $dsp_user_notification_table 
                        SET premium_member_expiration='$premium_member_expiration',
                            payment_successful='$payment_successful',
                            payment_failed='$payment_failed',
                            payment_canceled='$payment_canceled',
                            credit_purchase='$credit_purchase',
                            credit_balance_low='$credit_balance_low',
                            meet_me='$meet_me',
                            reset_password='$reset_password',
                            wink='$wink'
                            WHERE user_id = '$user_id'"
                    );
    } else {
        $wpdb->query("INSERT INTO $dsp_user_notification_table 
                            SET user_id = '$user_id',
                                premium_member_expiration='$premium_member_expiration',
                                payment_successful='$payment_successful',
                                payment_failed='$payment_failed',
                                payment_canceled='$payment_canceled',
                                credit_purchase='$credit_purchase',
                                credit_balance_low='$credit_balance_low',
                                meet_me='$meet_me',
                                reset_password='$reset_password',
                                wink='$wink'
                    ");
    }
    $settings_updated = true;
}
$member_notification_settings = $wpdb->get_row("SELECT * FROM $dsp_user_notification_table WHERE user_id = '$user_id'");
?>
<?php if (isset($settings_updated) && $settings_updated == true) { ?>
    <div class="thanks">
        <p align="center" class="error"><?php echo language_code('DSP_SETTINGS_UPDATED'); ?></p>
    </div>
<?php } ?>
<?php //---------------------------------------START NOTIFICATION SETTINGS ------------------------------------//  ?>

<div class="box-border">
    <div class="box-pedding">  
        <div class="heading-submenu"><strong><?php echo language_code('DSP_EMAIL_NOTIFICATION_TITLE'); ?></strong></div><span class="dsp-none"></br></br></span>
        <div class="dsp-form-container">
            <form name="frmpnotificationsettings" action="" method="post" class="dspdp-form-horizontal dsp-form-horizontal">
                <div class="setting-page-account">
                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_PREMIUM_MEMBERSHIP_EXPIRATION_EMAIL'); ?>
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="premium_member_expiration" class="dspdp-form-control dsp-form-control">
                                <?php
                                if ($member_notification_settings->premium_member_expiration == 'N') {
                                    ?>
                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } else { ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </div>

                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_PAYMENT_SUCCESSFULL_EMAIL'); ?>
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="payment_successful" class="dspdp-form-control dsp-form-control">
                                <?php
                                if ($member_notification_settings->payment_successful == 'N') {
                                    ?>
                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } else { ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </div>

                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_PAYMENT_FAILED_EMAIL'); ?>
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="payment_failed" class="dspdp-form-control dsp-form-control">
                                <?php
                                if ($member_notification_settings->payment_failed == 'N') {
                                    ?>
                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } else { ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </div>

                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_PAYMENT_CANCELED_EMAIL'); ?>
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="payment_canceled" class="dspdp-form-control dsp-form-control">
                                <?php
                                if ($member_notification_settings->payment_canceled == 'N') {
                                    ?>
                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } else { ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </div>

                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_CREDIT_BALANCE_LOW_EMAIL'); ?>
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="credit_balance_low" class="dspdp-form-control dsp-form-control">
                                <?php
                                if ($member_notification_settings->credit_balance_low == 'N') {
                                    ?>
                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } else { ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </div>

                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_CREDIT_PURCHASE_EMAIL'); ?>
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="credit_purchase" class="dspdp-form-control dsp-form-control">
                                <?php
                                if ($member_notification_settings->credit_purchase == 'N') {
                                    ?>
                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } else { ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </div>

                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_MEET_ME_EMAIL'); ?>
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="meet_me" class="dspdp-form-control dsp-form-control">
                                <?php
                                if ($member_notification_settings->meet_me == 'N') {
                                    ?>
                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } else { ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </div>

                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_RESET_PASSWORD_EMAIL'); ?>
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="reset_password" class="dspdp-form-control dsp-form-control">
                                <?php
                                if ($member_notification_settings->reset_password == 'N') {
                                    ?>
                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } else { ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </div>
                     <div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_OPTION_WINK'); ?>
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="wink" class="dspdp-form-control dsp-form-control">
                                <?php
                                if ($member_notification_settings->wink == 'N') {
                                    ?>
                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } else { ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </div>

                    <div class="dspdp-form-group dsp-form-group">
                        <p class=" dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <input type="hidden" name="update_mode" value="update" />
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <input type="submit"  name="submit" value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>" class="dsp_submit_button dspdp-btn dspdp-btn-default" />
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
//------------------------------------- END NOTIFICATION SETTINGS  ------------------------------------------ // ?>