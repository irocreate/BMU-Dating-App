<?php

include_once("../../../wp-config.php");
include_once(WP_DSP_ABSPATH . "/files/includes/functions.php");
global $wpdb;
$dsp_language_table = $wpdb->prefix . DSP_LANGUAGE_TABLE;
$wpdb->query("INSERT INTO `$dsp_language_table` (`code_id`, `code_name`, `text_name`) VALUES
(808, 'DSP_TOOLS_VIRTUAL_FLIRTS', 'Virtual Gifts'),
(811, 'DSP_TOOLS_LANGUAGE_TAB_ADD_LANGUAGE', 'Add Language'),
(810, 'DSP_TOOLS_LANGUAGE_TAB_ADD_TEXT', 'Add Text'),
(809, 'DSP_TOOLS_LANGUAGE_TAB_SEARCH', 'Search'),
(812, 'DSP_TOOLS_LANGUAGE_SAVE_LANGUAGE', 'Save')");
if (file_exists(WP_DSP_ABSPATH . "/gifts")) {
    if (file_exists(ABSPATH . "/wp-content/uploads/dsp_media/gifts/")) {
        rcopy(WP_DSP_ABSPATH . "/gifts/", ABSPATH . "/wp-content/uploads/dsp_media/gifts/");
    } else {
        createPath(ABSPATH . "/wp-content/uploads/dsp_media/gifts/");
        rcopy(WP_DSP_ABSPATH . "/gifts/", ABSPATH . "/wp-content/uploads/dsp_media/gifts/");
    }
}
echo "Database Upgrade Complete.<a href= '../../../index.php' style='cursor:pointer; text-decoration:underline;'>Continue</a>";
