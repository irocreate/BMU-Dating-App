<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$txtusername = $_POST['txtusername'];
if (isset($_POST['change_account'])) {
//Check to make sure sure that a valid email address is submitted
    if (trim($_POST['txtemailbox']) === '') {
        $EmailError = language_code('DSP_FORGOT_ENTER_MAIL_ADDRESS');
        $hasError = true;
    } else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['txtemailbox']))) {
        $EmailError = language_code('DSP_ENTER_INVALID_MAIL_ADDRESS');
        $hasError = true;
    } else {
        $txtemailbox = trim($_POST['txtemailbox']);
    }



//Check to make sure that the Password Field is not empty
    if (trim($_POST['txtpassword1']) === "") {
        $Pass1Error = language_code('DSP_ENTER_PASSWORD');
        $hasError = true;
    } else {
        $txtpassword1 = $_POST['txtpassword1'];
    }
//Check to make sure that the Email is not empty
    if (trim($_POST['txtpassword1']) != trim($_POST['txtpassword2'])) {
        $confirmError = language_code('DSP_PASSWORD_NOT_MATCH_CONFIRM');
        $hasError = true;
    } else {
        $txtpassword2 = $_POST['txtpassword2'];
    }


    //If there is no error, then profile updated

    if (!isset($hasError)) {

        if (isset($_POST['txtemailbox'])) {
            $wpdb->query($wpdb->prepare("UPDATE $wpdb->users SET user_email = '%s' WHERE ID = $user_id", $_POST['txtemailbox']));
        }

        if (isset($_POST['txtpassword1']) && isset($_POST['txtpassword2']) && $_POST['txtpassword1'] == $_POST['txtpassword2']) {
            $errors = $wpdb->query("UPDATE $wpdb->users SET user_pass = '" . wp_hash_password($_POST['txtpassword1']) . "' WHERE ID = $user_id");
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
<div class="dsp_box-out">
    <div class="dsp_box-in">
        <div class="box-page">
            <form name="frmuseraccount" method="post" action="">
                <div class="setting-page-account">
                    <p><?php echo language_code('DSP_USERNAME') ?>:</p>
                    <p><input type="text" name="txtusername" value="<?php echo $user_account_details->user_login ?>" disabled="disabled" />&nbsp;<?php echo language_code('DSP_NOT_CHANGE_USERNAME') ?></p>
                    <p><?php echo language_code('DSP_PASSWORD') ?></p>
                    <p><input type="password" name="txtpassword1" value="" />
                        <?php if ($Pass1Error != '') { ?>
                            <span class="error"><?php echo $Pass1Error; ?></span> 
                        <?php } ?></p>
                    <p><?php echo language_code('DSP_CONFIRM_PASSWORD') ?>:</p>
                    <p><input type="password" name="txtpassword2" value="" />
                        <?php if ($confirmError != '') { ?>
                            <span class="error"><?php echo $confirmError; ?></span> 
                        <?php } ?>
                    </p>
                    <p><?php echo language_code('DSP_TEXT_EMAIL') ?>:</p>
                    <p><input type="text" name="txtemailbox" value="<?php echo $user_account_details->user_email ?>" />
                        <?php if ($EmailError != '') { ?>
                            <span class="error"><?php echo $EmailError; ?></span> 
                        <?php } ?>
                    </p>
                    <div class="btn-row"><input type="submit" name="change_account" value="<?php echo language_code('DSP_SUBMIT_BUTTON') ?>" class="dsp_submit_button" /></div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
//------------------------------------- END ACCOUNT SETTINGS  ------------------------------------------ // ?>