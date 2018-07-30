<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$dsp_user_notification_table = $wpdb->prefix . DSP_USER_NOTIFICATION_TABLE;
$private_messages = isset($_REQUEST['private_messages']) ? $_REQUEST['private_messages'] : '';
$friend_requests = isset($_REQUEST['friend_requests']) ? $_REQUEST['friend_requests'] : '';
$update_mode = isset($_REQUEST['update_mode']) ? $_REQUEST['update_mode'] : '';
if (($update_mode == 'update') && ($user_id != "")) {
    $check_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_notification_table WHERE user_id='$user_id'");
    if ($check_user_exists > 0) {
        $wpdb->query("UPDATE $dsp_user_notification_table SET private_messages='$private_messages', friend_request='$friend_requests' WHERE user_id = '$user_id'");
    } else {
        $wpdb->query("INSERT INTO $dsp_user_notification_table SET user_id = '$user_id',private_messages = '$private_messages',friend_request='$friend_requests'");
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
        <div class="heading-submenu"><strong><?php echo language_code('DSP_NOTIFICATION_TITLE'); ?></strong></div><span class="dsp-none"></br></br></span>
        <div class="dsp-form-container">
            <form name="frmpnotificationsettings" action="" method="post" class="dspdp-form-horizontal dsp-form-horizontal">
                <div class="setting-page-account">
                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_TEXT_PRIVATE_MESSAGES'); ?>
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="private_messages" class="dspdp-form-control dsp-form-control">
                                <?php
                                if ($member_notification_settings->private_messages == 'N') {
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
                            <?php echo language_code('DSP_TEXT_FRIEND_REQUESTS'); ?>
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="friend_requests" class="dspdp-form-control dsp-form-control">
                                <?php
                                if ($member_notification_settings->friend_request == 'N') {
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