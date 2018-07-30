<?php
global $wpdb;
$plugin_file = WP_DSP_ABSPATH . "dsp_dating.php";
$plugin_data = get_plugin_data($plugin_file, $markup = true, $translate = true);
$version = $plugin_data['Version'];
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$check_plugin_version = $wpdb->get_var("SELECT setting_value FROM $dsp_general_settings_table WHERE setting_name = 'plugin_version'");
?>
<div id="general" class="postbox">
    <h3 class="hndle"><span><?php echo language_code('DSP_SETTINGS_HEADER_UPDATE_DATABASE'); ?></span></h3>
    <br />
    <div class="dsp_thumbnails3" >
        <div style="width:421px;">
            <?php if ($version != $check_plugin_version) { ?>
                <?php echo language_code('DSP_SETTINGS_UPDATE_DATABASE_TEXT'); ?> <a href="admin.php?page=dsp-admin-sub-page1&pid=upgrade"><input type="button" name="" value="<?php echo language_code('DSP_SETTINGS_HEADER_UPDATE_DATABASE'); ?>" /></a>
            <?php } else { ?>
                <?php echo language_code('DSP_DATABASE_UP_TO_DATE'); ?>
            <?php } ?>
        </div>
        <div>
            <div style="height:40px;"></div>
        </div>
    </div>
    <br />
</div>
