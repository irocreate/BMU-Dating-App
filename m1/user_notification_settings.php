<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <a class="ui-btn-left ui-btn-corner-all ui-icon-back ui-btn-icon-notext ui-shadow"  onclick="viewSetting(0, 'setting')" href="#" >
            </a>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_SUBMENU_SETTINGS_NOTIFICATION'); ?></h1>
   <?php include_once('page_home.php')?>    
</div>

<?php
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

<div class="ui-content" data-role="content">
    <div class="content-primary">	
                <?php if (isset($settings_updated) && $settings_updated == true) {
                    ?>
                    <div class="success-message">
                       <?php echo language_code('DSP_SETTINGS_UPDATED'); ?>
                    </div>
                <?php } ?>

                <form name="dspAccount" id="dspAccount">
                    <fieldset>
                    <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_TEXT_PRIVATE_MESSAGES'); ?></div>
                       
                            <select name="private_messages">
                                <?php
                                if ($member_notification_settings->private_messages == 'N') {
                                    ?>

                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>

                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO'); ?></option>

                                    <?php
                                } else {
                                    ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        </label>

                       <fieldset>
                    <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_TEXT_FRIEND_REQUESTS'); ?></div>
                            <select name="friend_requests">
                                <?php
                                if ($member_notification_settings->friend_request == 'N') {
                                    ?>

                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        </label>

                        <div>
                        	<input type="hidden" name="pagetitle" value="<?php echo $profile_pageurl; ?>" />
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                            <input type="hidden" name="update_mode" value="update" /></div>
                            <div class="btn-blue-wrap">
                        <input onclick="viewSetting(0, 'post')" type="button" class="mam_btn btn-red" name="submit" value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>"  /></div>
                    </div>
                    </fieldset>
                </form>
            
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>