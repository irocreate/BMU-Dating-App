<?php
include("../../../../wp-config.php");
global $wpdb;

$dsp_language_details_table = $wpdb->prefix . "dsp_language_details";

$lang_arr = array(
    "'DSP_NETWORK_PROBLEM'",
    "'DSP_FILL_IN_ALL_FIELDS'",
    "'DSP_ENTER_SITE_NAME'",
    "'DSP_THANKYOU_FOR_UR_PAYMENT'",
    "'DSP_PAYMENT_FAILED'",
    "'DSP_USER_CANCELLED_PAYMENT'",
    "'DSP_UPLOAD_IMAGE_SIZE_ERROR'",
    "'DSP_SELECT_ALBUM'",
    "'DSP_UPLOAD_VIDEO_SIZE_ERROR'",
    "'DSP_APP_NOT_CONFIGURED'",
    "'DSP_COUNTRY_EMPTY_ERROR'",
    "'DSP_ABOUT_ME_EMPTY_ERROR'",
    "'DSP_TITLE_EMPTY_ERROR'",
    "'DSP_CONTENT_EMPTY_ERROR'",
    "'DSP_CONFIRM_DELETE'"
);

$imploded_codes = implode(",", $lang_arr);

$lang_table = $wpdb->get_row("SELECT table_name FROM $dsp_language_details_table WHERE display_status ='1'");
$active_table_name = $wpdb->prefix . $lang_table->table_name;

$lang_translations = $wpdb->get_results("SELECT code_name, text_name FROM $active_table_name WHERE code_name IN ($imploded_codes)");

echo json_encode($lang_translations);
?>