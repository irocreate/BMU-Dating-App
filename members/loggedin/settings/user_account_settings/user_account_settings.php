<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
$DSP_PAYMENTS_TABLE = $wpdb->prefix . DSP_PAYMENTS_TABLE;
//----- code for cancel subscription------------------------
$getMemProfIDQuery = "select recurring_profile_id from $DSP_PAYMENTS_TABLE where  pay_user_id=$user_id and recurring_profile_status='1'";
$recurring_profile_res = $wpdb->get_row($getMemProfIDQuery);
//----- code for cancel subscription------------------------
$txtusername = isset($_REQUEST['txtusername']) ? esc_sql(sanitizeData(trim($_REQUEST['txtusername']), 'xss_clean')) : '';
if (isset($_POST['change_account'])) {
//Check to make sure sure that a valid email address is submitted
    if (trim($_POST['txtemailbox']) === '') {
        $EmailError = language_code('DSP_FORGOT_ENTER_MAIL_ADDRESS');
        $hasError = true;
    }  else if (!preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i", trim($_POST['txtemailbox']))) {
        $EmailError = language_code('DSP_ENTER_INVALID_MAIL_ADDRESS');
        $hasError = true;
    } else {
        $txtemailbox = esc_sql(sanitizeData(trim($_POST['txtemailbox']), 'xss_clean'));
    }



//Check to make sure that the Password Field is not empty
    if (trim($_POST['txtpassword1']) === "") {
        $Pass1Error = language_code('DSP_ENTER_PASSWORD');
        $hasError = true;
    } else {
        $txtpassword1 = esc_sql(sanitizeData(trim($_POST['txtpassword1']), 'xss_clean'));
    }
//Check to make sure that the Email is not empty
    if (trim($_POST['txtpassword1']) != trim($_POST['txtpassword2'])) {
        $confirmError = language_code('DSP_PASSWORD_NOT_MATCH_CONFIRM');
        $hasError = true;
    } else {
        $txtpassword2 = esc_sql(sanitizeData(trim($_POST['txtpassword2']), 'xss_clean'));
    }


    //If there is no error, then profile updated

    if (!isset($hasError)) {

        if (isset($_POST['txtemailbox'])) {
            $wpdb->query($wpdb->prepare("UPDATE $wpdb->users SET user_email = '%s' WHERE ID = $user_id", $txtemailbox));
        }

        if (isset($_POST['txtpassword1']) && isset($_POST['txtpassword2']) && $_POST['txtpassword1'] == $_POST['txtpassword2']) {
            $errors = $wpdb->query("UPDATE $wpdb->users SET user_pass = '" . wp_hash_password($txtpassword1) . "' WHERE ID = $user_id");
        }
        $updated = true;
    }
}
$user_account_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$user_id'");
?>
<?php if (isset($updated) && $updated == true) { ?>
    <div class="thanks">
        <p align="center" class="error"><?php echo language_code('DSP_ACCOUNT_SETTINGS_UPDATED'); ?></p>
    </div>
<?php } ?>
<?php //---------------------------------------START ACCOUNT SETTINGS ------------------------------------//  ?>

<div class="box-border">
    <div class="box-pedding">
        <div class="heading-submenu"><strong><?php echo language_code('DSP_ACCOUNT_SETTING_TITLE'); ?></strong></div>
        <div class="dsp-form-container">
            <form name="frmuseraccount" method="post" action="" class="dspdp-form-horizontal dsp-form-horizontal">
                <div class="setting-page-account">
                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_USERNAME') ?>:
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <input type="text" name="txtusername" class="dspdp-form-control dsp-form-control" value="<?php echo $user_account_details->user_login ?>" disabled="disabled" /> 
                        </p>
                        <span class="dspdp-help-block dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_NOT_CHANGE_USERNAME') ?>
                        </span>
                    </div>                    
    				<div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_NEW_PASSWORD') ?>
                        </p>
                        <p class=" dspdp-col-sm-6 dsp-sm-6">
                            <input class="dspdp-form-control dsp-form-control" type="password" name="txtpassword1" value="" />
                            <?php if (isset($Pass1Error) && $Pass1Error != '') { ?>
                                <span class="error dspdp-text-danger dspdp-help-block dspdp-col-sm-3 dsp-sm-3"><?php echo $Pass1Error; ?></span> 
                            <?php } ?>
                        </p>
                    </div>    				
    				<div class="dspdp-form-group dsp-form-group clearfix">
    				    <p class="bold-text dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label ">
                            <?php echo language_code('DSP_CONFIRM_PASSWORD') ?>:
                        </p>
                        <p class=" dspdp-col-sm-6 dsp-sm-6">
                            <input class="dspdp-form-control dsp-form-control" type="password" name="txtpassword2" value="" />
                            <?php if (isset($confirmError) && $confirmError != '') { ?>
                                <span class="error dspdp-text-danger dspdp-help-block dspdp-col-sm-3 dsp-sm-3"><?php echo $confirmError; ?></span> 
                            <?php } ?>
                        </p>
                    </div>    				
    				
                    <div class="dspdp-form-group dsp-form-group clearfix">
    				    <p class="bold-text dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label ">
                            <?php echo language_code('DSP_TEXT_EMAIL') ?>:
                        </p>
                        <p class=" dspdp-col-sm-6 dsp-sm-6">
                            <input class="dspdp-form-control dsp-form-control" type="text" name="txtemailbox" value="<?php echo $user_account_details->user_email ?>" />
                            <?php if (isset($EmailError) && $EmailError != '') { ?>
                                <span class="error"><?php echo $EmailError; ?></span> 
                            <?php } ?>
                        </p>
    				</div>
    				
                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <div class="btn-row dspdp-col-sm-offset-3 dspdp-col-sm-6 dsp-sm-6">
                            <input type="submit" name="change_account" value="<?php echo language_code('DSP_SUBMIT_BUTTON') ?>" class="dsp_submit_button dspdp-btn dspdp-btn-default" />
                        </div>
                    </div>
                </div>
                <?php
    // show cancel membership only user has payment done and he has his recurring profile id
                if ($recurring_profile_res != null) {
                    $recurring_profile_id = $recurring_profile_res->recurring_profile_id;
                    if ($recurring_profile_id) {
                        ?>
                        <div style="padding-top: 32px; float: right; padding-right: 10px;">
                            <a onclick="return confirmAction('<?php echo language_code('DSP_R_U_SURE_TO_CANCEL_MEMBERSHIP'); ?>?');" href="<?php echo $root_link . "setting/dsp_cancel_membership/"; ?>">
                                <span style="color: red"><?php echo language_code('DSP_CANCEL_MY_MEMBERSHIP'); ?></span>	
                            </a>
                        </div>
                        <?php
                    }
                }
                ?>
            </form>
        </div>
    </div>
</div>
<?php //------------------------------------- END ACCOUNT SETTINGS  ------------------------------------------ // ?>
<script type="text/javascript">
    function confirmAction(msg)
    {
        //alert('ssd');
        var confirmed = confirm(msg);
        return confirmed;
    }
</script>