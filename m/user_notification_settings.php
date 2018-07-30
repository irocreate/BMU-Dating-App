<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a  onclick="viewSetting(0, 'setting')"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_SUBMENU_SETTINGS_NOTIFICATION'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>
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
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
            <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">



                <?php if (isset($settings_updated) && $settings_updated == true) {
                    ?>
                    <div class="thanks">
                        <p align="center" class="error"><?php echo language_code('DSP_SETTINGS_UPDATED'); ?></p>
                    </div>
                <?php } ?>

                <form name="dspAccount" id="dspAccount">
                    <div class="setting-page-account">
                        <div><?php echo language_code('DSP_TEXT_PRIVATE_MESSAGES'); ?></div>
                        <div>
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
                        <div><?php echo language_code('DSP_TEXT_FRIEND_REQUESTS'); ?></div>


                        <div>
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

                        <div>	<input type="hidden" name="pagetitle" value="<?php echo $profile_pageurl; ?>" />
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                            <input type="hidden" name="update_mode" value="update" /></div>
                        <div><input onclick="viewSetting(0, 'post')" type="button" name="submit" value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>"  /></div>
                    </div>
                </form>
            </li>
        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>