<?php
include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include_once("../general_settings.php");
include_once("dspFunction.php");


//https://developer.paypal.com/webapps/developer/docs/classic/mobile/ht_mpl-itemPayment-Android/

$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
$dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
$dsp_gateways_table = $wpdb->prefix . DSP_GATEWAYS_TABLE;
$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_credits_table = $wpdb->prefix . DSP_CREDITS_TABLE;

$user_id = $_REQUEST['user_id'];
$profile_pageurl = $_REQUEST['pagetitle'];

// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');

$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$user_id'");

if ($profile_pageurl == "upgrade_account") {


    include(WP_DSP_ABSPATH . "/m1/upgrade_account_settings.php");
} else if ($profile_pageurl == "upgrade_account_details") {

    include(WP_DSP_ABSPATH . "/m1/dsp_upgrade_account_details.php");
} else if ($profile_pageurl == "dsp_paypal") {

    include(WP_DSP_ABSPATH . "/m1/payments/paypal.php");
} else if ($profile_pageurl == "skype_settings") {

    include(WP_DSP_ABSPATH . "/m1/dsp_skype_settings.php");
} else if ($profile_pageurl == "auth_settings") {

    include(WP_DSP_ABSPATH . "/m1/dsp_upgrade_setting_details.php");
} else if ($profile_pageurl == "auth_settings_detail") {

    include(WP_DSP_ABSPATH . "/m1/dsp_upgrade_check_setting.php");
} else if ($profile_pageurl == "pro_settings") {

    include(WP_DSP_ABSPATH . "/m1/dsp_upgrade_paypalpro_setting.php");
} else if ($profile_pageurl == "pro_settings_detail") {


    include(WP_DSP_ABSPATH . "/m1/dsp_upgrade_paypalpro_detail.php");
} else if ($profile_pageurl == "dsp_error") {

    include(WP_DSP_ABSPATH . "/m1/dsp_error.php");
} else if ($profile_pageurl == "dsp_cancel") {

    include(WP_DSP_ABSPATH . "/m1/dsp_cancel.php");
} else if ($profile_pageurl == "paypal_advance") {

    include(WP_DSP_ABSPATH . "/m1/dsp_upgrade_paypal_advance.php");
} else if ($profile_pageurl == "create_recur") {


    include(WP_DSP_ABSPATH . "/m1/dsp_create_recur.php");
} else if ($profile_pageurl == "dsp_cancel_membership") {
    include(WP_DSP_ABSPATH . "/m1/dsp_cancel_membership.php");
} else if ($profile_pageurl == "dsp_thank_you") {

    include(WP_DSP_ABSPATH . "/m1/dsp_thank_you.php");
} else if ($profile_pageurl == "dsp_iDEAL_thank_you") {

    include(WP_DSP_ABSPATH . "/m1/dsp_iDEAL_thank_you.php");
} else if ($profile_pageurl == "iDEAL") {

    include(WP_DSP_ABSPATH . "/m1/dsp_upgrade_iDEAL_payment.php");
} else if ($profile_pageurl == "credit_auth_settings") {
    include(WP_DSP_ABSPATH . "/m1/dsp_credit_upgrade_setting_details.php");
} else if ($profile_pageurl == "credit_auth_settings_detail") {
    include(WP_DSP_ABSPATH . "/m1/dsp_credit_upgrade_check_setting.php");
} else if ($profile_pageurl == "credit_pro_settings") {
    include(WP_DSP_ABSPATH . "/m1/dsp_credit_upgrade_paypalpro_setting.php");
} else if ($profile_pageurl == "credit_pro_settings_detail") {

    include(WP_DSP_ABSPATH . "/m1/dsp_credit_upgrade_paypalpro_detail.php");
}
?>