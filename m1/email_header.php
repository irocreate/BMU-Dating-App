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


$user_id = $_REQUEST['user_id'];

$dsp_user_emails_table = $wpdb->prefix . DSP_EMAILS_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
$dsp_credits_table = $wpdb->prefix . DSP_CREDITS_TABLE;

// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');

$user_id = $_REQUEST['user_id'];

$message_template = isset($_REQUEST['message_template']) ? $_REQUEST['message_template'] : '';

$reply_Action = isset($_REQUEST['Act']) ? $_REQUEST['Act'] : '';


$Sender_ID = isset($_REQUEST['sender_ID']) ? $_REQUEST['sender_ID'] : '';

$del_mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

if (($Sender_ID != "") && ($del_mode == "delete")) {
    for ($i = 0; $i < sizeof($Sender_ID); $i++) {
        $wpdb->query("UPDATE $dsp_user_emails_table SET delete_message=1  WHERE sender_id = '" . $Sender_ID[$i] . "' and receiver_id='$user_id'");
    }
} // End if

$count_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE message_read='N' AND receiver_id=$user_id AND delete_message=0");


if ($message_template == "inbox") {
    if ($check_free_mode->setting_status == "N") {  // check condition if free mode is off 
// *********** Start Check Access Email Feature is Active ********* //
        $access_feature_name = "Access Email";

        if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is on
            $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_recieve_messages.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
            $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
            if ($check_free_email_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_recieve_messages.php");
            } else if ($check_free_email_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_free_email_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_free_email_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_force_profile_mode->setting_status == "Y") { // free email access mode is on
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_recieve_messages.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else { // if free trial mode is off
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_recieve_messages.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } // END if free trial mode is ON   
        // *********** End Check Access Email Feature is Active ********* //
    } else {
        if ($check_force_profile_mode->setting_status == "Y") { // free email access mode is on
            $access_feature_name = "Access Email";
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {

                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_recieve_messages.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            include(WP_DSP_ABSPATH . "/m1/dsp_recieve_messages.php");
        } // End // check condition if free mode is off 
    }
}
// *********** Start compose  ********* //
else if ($message_template == "compose" && ($reply_Action != "Reply")) {
    $access_feature_name = "Compose New Email Message";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is off 
            $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
            $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
            if ($check_free_email_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
            } else if ($check_free_email_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_free_email_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_free_email_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_force_profile_mode->setting_status == "Y") { // free email access mode is on
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else { // if free trial mode is ON
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                if ($check_credit_mode->setting_status == 'Y') {
                    $no_of_credits = $wpdb->get_var("select no_of_credits from $dsp_credits_usage_table where user_id='$user_id'");
                    if (count($no_of_credits) == 0)
                        $no_of_credits = count($no_of_credits);
                    if ($no_of_credits > 0) {
                        include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
                    } else {
                        include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                    }
                } else
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                if ($check_credit_mode->setting_status == 'Y') {
                    $no_of_credits = $wpdb->get_var("select no_of_credits from $dsp_credits_usage_table where user_id='$user_id'");
                    if (count($no_of_credits) == 0)
                        $no_of_credits = count($no_of_credits);
                    if ($no_of_credits > 0) {
                        include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
                    } else {
                        include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                    }
                } else
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } // END if free trial mode is ON  
    } else {
        if ($check_force_profile_mode->setting_status == "Y") { // free email access mode is on
            $access_feature_name = "Compose New Email Message";
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
        }
    }
} else if (($message_template == "compose") && ($reply_Action == "Reply")) {
    $access_feature_name = "Reply Email Message";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is off 
            $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
            $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
            if ($check_free_email_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
            } else if ($check_free_email_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_free_email_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_free_email_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else { // if free trial mode is ON
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {

                if ($check_credit_mode->setting_status == 'Y') {
                    $no_of_credits = $wpdb->get_var("select no_of_credits from $dsp_credits_usage_table where user_id='$user_id'");
                    if (count($no_of_credits) == 0)
                        $no_of_credits = count($no_of_credits);
                    if ($no_of_credits > 0) {
                        include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
                    } else {
                        include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                    }
                } else
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {

                if ($check_credit_mode->setting_status == 'Y') {
                    $no_of_credits = $wpdb->get_var("select no_of_credits from $dsp_credits_usage_table where user_id='$user_id'");
                    if (count($no_of_credits) == 0)
                        $no_of_credits = count($no_of_credits);
                    if ($no_of_credits > 0) {
                        include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
                    } else {
                        include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                    }
                } else
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } // END if free trial mode is ON   
    } else {
        include(WP_DSP_ABSPATH . "/m1/dsp_send_new_message.php");
    }
} else if ($message_template == "view_message") {
    if ($check_free_mode->setting_status == "N") {  // check condition if free mode is off 
// *********** Start Check View Email Message Feature is Active ********* //
        $access_feature_name = "View Email Message";
        if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is off 
            $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_view_message.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
// *********** End Check View Email Message Feature is Active ********* // 
        } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
            $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
            if ($check_free_email_msg == "Access") {
                 include(WP_DSP_ABSPATH . "/m1/dsp_view_message.php");
            } else if ($check_free_email_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_free_email_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_free_email_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else { // if free trial mode is ON
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_view_message.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } // END if free trial mode is ON
    } else {

        include(WP_DSP_ABSPATH . "/m1/dsp_view_message.php");
        exit;
    } // End // check condition if free mode is off 
} else if ($message_template == "sent") {
    include(WP_DSP_ABSPATH . "/m1/dsp_sent_message.php");
} else if ($message_template == "deleted") {
    include(WP_DSP_ABSPATH . "/m1/dsp_delete_messages.php");
} else if ($message_template == "delete_messages") {
    include(WP_DSP_ABSPATH . "/m1/dsp_view_delete_messages.php");
} else if ($message_template == "view_winks") {

    include(WP_DSP_ABSPATH . "/m1/dsp_view_winks.php");
} else if ($message_template == "view_friends") {

    include(WP_DSP_ABSPATH . "/m1/dsp_view_friends.php");
} else if ($message_template == "my_favourites") {

    include(WP_DSP_ABSPATH . "/m1/dsp_my_favourites.php");
} else if ($message_template == "my_matches") {

    include(WP_DSP_ABSPATH . "/m1/dsp_my_matches.php");
} else if ($message_template == "alerts") {

    include(WP_DSP_ABSPATH . "/m1/dsp_alerts_messages.php");
} else if ($message_template == "blocked") {

    include(WP_DSP_ABSPATH . "/m1/dsp_blocked_members.php");
} else {

    if ($check_free_mode->setting_status == "N") {  // check condition if free mode is off 
// *********** Start Check Access Email Feature is Active ********* //
        $access_feature_name = "Access Email";
        if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is off 
            $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_recieve_messages.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
            $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
            if ($check_free_email_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_recieve_messages.php");
            } else if ($check_free_email_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_free_email_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_free_email_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else { // if free trial mode is ON
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_recieve_messages.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } // END if free trial mode is ON
// *********** End Check Access Email Feature is Active ********* //   
    } else {

        include(WP_DSP_ABSPATH . "/m1/dsp_recieve_messages.php");
    } // End // check condition if free mode is off 
}
?>