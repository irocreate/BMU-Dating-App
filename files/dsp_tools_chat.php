<?php
global $wpdb;
$dsp_chat_one = $wpdb->prefix . "dsp_chat_one";
$dsp_chat_table = $wpdb->prefix . DSP_CHAT_TABLE;
$dsp_meet_me = $wpdb->prefix . DSP_MEET_ME_TABLE;
$dsp_notification = $wpdb->prefix . DSP_NOTIFICATION_TABLE;
if (isset($_REQUEST['chat_log'])) {
    $wpdb->query("Truncate $dsp_chat_table");
    echo language_code('DSP_CHAT_LOG_CLEAR_TEXT');
}
if (isset($_REQUEST['chat_one_log'])) {
    $wpdb->query("Truncate $dsp_chat_one");
    echo language_code('DSP_CHAT_ONE_LOG_CLEAR_TEXT');
}
if (isset($_REQUEST['meet_me_log'])) {
    $wpdb->query("Truncate $dsp_meet_me");
    echo language_code('DSP_MEET_ME_LOG_CLEAR_TEXT');
}
if (isset($_REQUEST['notifications'])) {
    $wpdb->query("Truncate $dsp_notification");
    echo language_code('DSP_TOOLS_NOTIFICATIONS_CLEAR_TEXT');
}
?>
<div id="general" class="postbox">
    <h3 class="hndle"><span><?php echo language_code('DSP_TOOLS_CHAT'); ?></span></h3>
    <br />
    <div class="dsp_thumbnails3" >
        <div >
            <form  method="post"  action="" >
                <div style="float:none;" >
                    <input name="chat_log" type="submit" value="<?php echo language_code('DSP_TOOLS_CHAT_LOGS') ?>" class="button">
                    <input name="chat_one_log" type="submit" value="<?php echo language_code('DSP_TOOLS_CHAT_ONE_LOGS') ?>" class="button">
                    <input name="meet_me_log" type="submit" value="<?php echo language_code('DSP_TOOLS_MEET_ME_LOGS') ?>" class="button">
                    <input name="notifications" type="submit" value="<?php echo language_code('DSP_TOOLS_NOTIFICATIONS') ?>" class="button">
                </div>
            </form>
        </div>
        <div>
            <div style="height:40px;"></div>
        </div>
    </div>
    <br />
</div>
