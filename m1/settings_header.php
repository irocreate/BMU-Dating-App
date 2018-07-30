<?php
include("../../../../wp-config.php");

//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include_once("../general_settings.php");
include_once("dspFunction.php");

$user_id = $_REQUEST['user_id'];
$profile_pageurl = $_REQUEST['pagetitle'];

// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');

$user_id = $_REQUEST['user_id'];

$dsp_memberships_table = $wpdb->prefix . dsp_memberships;

if ($profile_pageurl == "account_settings") {

    include(WP_DSP_ABSPATH . "/m1/user_account_settings.php");
}

if ($profile_pageurl == "match_alert") {

    include(WP_DSP_ABSPATH . "/m1/match_alert_settings.php");
} else if ($profile_pageurl == "notification") {

    include(WP_DSP_ABSPATH . "/m1/user_notification_settings.php");
} else if ($profile_pageurl == "privacy_settings") {

    include(WP_DSP_ABSPATH . "/m1/user_privacy_settings.php");
} else if ($profile_pageurl == "upgrade_account") {


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
} else if ($profile_pageurl == "blocked") {

    include(WP_DSP_ABSPATH . "/m1/dsp_blocked_members.php");
} else if ($profile_pageurl == "setting") {

    include(WP_DSP_ABSPATH . "/m1/dspSettings.php");
}
?>