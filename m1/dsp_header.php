<?php
//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

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


$profile_pageurl = $_REQUEST['pagetitle'];

$user_id = $_REQUEST['user_id'];

// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');

$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_show_profile_table = $wpdb->prefix . DSP_LIMIT_PROFILE_VIEW_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

$user_id = $_REQUEST['user_id'];

$member_id = isset($_REQUEST['member_id']) ? $_REQUEST['member_id'] : '';

$check_user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id");


$fav_icon_image_path = $imagepath . "plugins/dsp_dating/m1/images/"; // fav,chat,star,friends,mail Icon image path

if ($profile_pageurl == "send_wink_msg") {
    $access_feature_name = "Send Wink";

    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
            $check_membership_msg = check_membership($access_feature_name, $user_id);

            if ($check_membership_msg == "Expired") {

                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {

                include(WP_DSP_ABSPATH . "/m1/send_wink_message.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {

                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            // if free trial mode is ON


            $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
            ;
            if ($check_member_trial_msg == "NotExist") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/send_wink_message.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        }
    } else {

        include(WP_DSP_ABSPATH . "/m1/send_wink_message.php");
    }
} else if ($profile_pageurl == 2) {
    include_once(WP_DSP_ABSPATH . '/m1/country_st_ct.php'); // to change the state and city according to country

    $exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$user_id'");
    $gender = $exist_profile_details->gender;

    $edit_profile_pageurl = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';

    if ($check_couples_mode->setting_status == 'Y') {
        if (($gender == 'C') && $edit_profile_pageurl == 'partner_profile') {
            //include(WP_DSP_ABSPATH."m1/edit_profile_header.php");
            include(WP_DSP_ABSPATH . "m1/edit_partner_profile_setup.php");
        } else if (($gender == 'M') || ($gender == 'F')) {

            include(WP_DSP_ABSPATH . "m1/edit_profile_setup.php");
        } else {
            //include(WP_DSP_ABSPATH."m1/edit_profile.php");
            include(WP_DSP_ABSPATH . "m1/edit_profile_setup.php");
        }
    } else {
        include(WP_DSP_ABSPATH . "m1/edit_profile_setup.php");
        //	include(WP_DSP_ABSPATH."m1/edit_profile.php");
    }
} else if ($profile_pageurl == 'edit_general') {
    include(WP_DSP_ABSPATH . "m1/edit_profile_general.php");
} else if ($profile_pageurl == 'edit_picture') {
    include(WP_DSP_ABSPATH . "m1/edit_profile_picture.php");
} else if ($profile_pageurl == 'edit_profile_question') {
    include(WP_DSP_ABSPATH . "m1/edit_profile_question.php");
} else if ($profile_pageurl == "view_profile") {
    $exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member_id'");
    $gender = $exist_profile_details->gender;

    if (isset($_GET['view']) && (($_GET['view'] == "my_profile") || ($_GET['view'] == "partner_profile"))) {
        $view = $_GET['view'];
        //	log_message('debug','mobile header page title=='.$_REQUEST['pagetitle'].'member '.$member_id.'title'.$view);

        if ($check_limit_profile_mode->setting_status == 'Y') {
            if (($user_id != $member_id)) {
                $count = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' AND member_id='$member_id' ");
                $no_of_profile = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' ");

                $general_settings_table = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'limit_profile'");

                $value = $general_settings_table->setting_value;

                if ($value <= $no_of_profile) {
                    $exist_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' AND member_id='$member_id' and status!='0' ");
                    if ($exist_member > 0) {
                        include(WP_DSP_ABSPATH . "m1/view_couples_profile_header.php");
                    } else {
                        if ($payment_status != 1) {
                            ?>		
                            <div role="banner" class="ui-header ui-bar-a" data-role="header">
                                <div class="back-image">
                                    <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
                                </div>
                                <span class="ui-title" />
                            </div>

                            <div class="ui-content" data-role="content">
                                <div class="content-primary">	
                                    <div style="text-align:center;color:#FF0000;" class="box-page">
                                        <?php echo language_code('DSP_LIMIT_PROFILE_MESSAGE'); ?> 
                                    </div>
                                </div>
                            </div>
                            <?php
                        } else {
                            include(WP_DSP_ABSPATH . "m1/view_couples_profile_header.php");
                        }
                    }
                } else if (($count >= 0) && ($session_id != '')) {
                    $wpdb->query("INSERT INTO $dsp_show_profile_table SET user_id='$user_id', member_id='$member_id', status='0' ");
                    include(WP_DSP_ABSPATH . "m1/view_couples_profile_header.php");
                } else if ($count == 1) {
                    include(WP_DSP_ABSPATH . "m1/view_couples_profile_header.php");
                }
            } else {
                include(WP_DSP_ABSPATH . "m1/view_couples_profile_header.php");
            }
        } else {
            include(WP_DSP_ABSPATH . "m1/view_couples_profile_header.php");
        }
    } else {
        if ($check_limit_profile_mode->setting_status == 'Y') {
            if (($user_id != $member_id)) {
                $count = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' AND member_id='$member_id' ");
                $no_of_profile = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' ");
                $general_settings_table = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'limit_profile'");
                $value = $general_settings_table->setting_value;
                if ($value <= $no_of_profile) {
                    $exist_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' AND member_id='$member_id'  and status!='0' ");
                    if ($exist_member > 0) {
                        include(WP_DSP_ABSPATH . "m1/dspViewProfile.php");
                    } else {
                        if ($payment_status != 1) {
                            ?>		
                            <div role="banner" class="ui-header ui-bar-a" data-role="header">
                                <div class="back-image">
                                    <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
                                </div>
                                <span class="ui-title" />
                            </div>

                            <div class="ui-content" data-role="content">
                                <div class="content-primary">	
                                    <div style="text-align:center;color:#FF0000;">
                                        <?php echo language_code('DSP_LIMIT_PROFILE_MESSAGE'); ?> 
                                    </div>
                                </div>
                            </div>

                            <?php
                        } else {
                            include(WP_DSP_ABSPATH . "m1/dspViewProfile.php");
                        }
                    }
                } else if (($count >= 0) && ($session_id != '')) {
                    $wpdb->query("INSERT INTO $dsp_show_profile_table SET user_id='$user_id', member_id='$member_id', status='0' ");
                    include(WP_DSP_ABSPATH . "m1/dspViewProfile.php");
                } else if ($count == 1) {
                    include(WP_DSP_ABSPATH . "m1/dspViewProfile.php");
                }
            } else {
                include(WP_DSP_ABSPATH . "m1/dspViewProfile.php");
            }
        } else {
            include(WP_DSP_ABSPATH . "m1/dspViewProfile.php");
        }
    }
} else if ($profile_pageurl == "alert") {
    $view = $_REQUEST['view'];

    if ($view == "frnd_request") {
        include(WP_DSP_ABSPATH . "m1/dsp_frnd_request.php");
    } else if ($view == "comments") {
        include(WP_DSP_ABSPATH . "m1/dsp_view_comments.php");
    } else if ($view == "viewComments") {
        include(WP_DSP_ABSPATH . "m1/showComment.php");
    } else if ($view == "virtual_gifts") {
        include(WP_DSP_ABSPATH . "m1/dsp_view_virtual_gifts.php");
    } else if ($view == "viewGift") {
        include(WP_DSP_ABSPATH . "m1/showGift.php");
    } else if ($view == "view_winks") {
        include(WP_DSP_ABSPATH . "m1/dsp_view_winks.php");
    } else if ($view == "viewWink") {
        include(WP_DSP_ABSPATH . "m1/showWink.php");
    } else {

        include(WP_DSP_ABSPATH . "m1/dspAlert.php");
    }
} else if ($profile_pageurl == "refresh_rate") {
    $check_refresh_rate = $wpdb->get_var("SELECT setting_value FROM $dsp_general_settings_table WHERE setting_name = 'refresh_rate'");
    $check_refresh_rate = $check_refresh_rate . '000'; //convert to mili second
    $check_notification_time_mode = $wpdb->get_var("SELECT setting_value FROM $dsp_general_settings_table WHERE setting_name = 'notification_time'");
    $check_notification_time_mode = $check_notification_time_mode . '000'; //convert to mili second
    echo $check_refresh_rate . '#' . $check_notification_time_mode;
} elseif ($profile_pageurl == "notification") {
   
    include(WP_DSP_ABSPATH . "m1/dsp_notification.php");
} elseif ($profile_pageurl == "check_notification_mode") {
    $check_notification_mode = $wpdb->get_var("SELECT setting_status FROM $dsp_general_settings_table WHERE setting_name = 'notification'");
    echo $check_notification_mode;
}
?>