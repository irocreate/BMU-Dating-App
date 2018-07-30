<?php

include("../../../../wp-config.php");
//<!--<link href="http://code.jquery.com1/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);  
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include_once("../general_settings.php");
include_once("dspFunction.php");

$user_id = $_REQUEST['user_id'];
$extra_pageurl = $_REQUEST['pagetitle'];
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');
$user_id = $_REQUEST['user_id'];

$fav_icon_image_path = $imagepath . "plugins/dsp_dating/m1/images/"; // fav,chat,star,friends,mail Icon image path

$check_user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles_table WHERE user_id=$user_id");

if ($extra_pageurl == "viewed_me") {
    $access_feature_name = "Viewed Me";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {  // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/viewed_me.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is on
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/viewed_me.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                $check_approved_profile_msg = check_approved_profile_feature($user_id);
                if ($check_approved_profile_msg == "NoAccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_approved_profile_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/viewed_me.php");
                } else if ($check_approved_profile_msg == "NoExist") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            } else { // if free trial mode is off
                $check_membership_msg = check_membership($access_feature_name, $user_id);

                if ($check_membership_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/viewed_me.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            }
        }
    } else {
        if ($check_force_profile_mode->setting_status == "Y") {  // if force profile mode is OFF
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/viewed_me.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_approved_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/viewed_me.php");
            } else if ($check_approved_profile_msg == "NoExist") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            include(WP_DSP_ABSPATH . "/m1/viewed_me.php");
        }
    }
} else if ($extra_pageurl == "i_viewed") {
    $access_feature_name = "I Viewed";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {  // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/iviewed.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is on
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/iviewed.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                $check_approved_profile_msg = check_approved_profile_feature($user_id);
                if ($check_approved_profile_msg == "NoAccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_approved_profile_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/iviewed.php");
                } else if ($check_approved_profile_msg == "NoExist") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            } else { // if free trial mode is off
                $check_membership_msg = check_membership($access_feature_name, $user_id);

                if ($check_membership_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/iviewed.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            }
        }
    } else {
        if ($check_force_profile_mode->setting_status == "Y") {  // if force profile mode is OFF
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/iviewed.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_approved_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/iviewed.php");
            } else if ($check_approved_profile_msg == "NoExist") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            include(WP_DSP_ABSPATH . "/m1/iviewed.php");
        }
    }
} else if ($extra_pageurl == "trending") {
    $access_feature_name = "Trending";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {  // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/trending.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is on
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/trending.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                $check_approved_profile_msg = check_approved_profile_feature($user_id);
                if ($check_approved_profile_msg == "NoAccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_approved_profile_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/trending.php");
                } else if ($check_approved_profile_msg == "NoExist") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            } else { // if free trial mode is off
                $check_membership_msg = check_membership($access_feature_name, $user_id);

                if ($check_membership_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/trending.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            }
        }
    } else { // if free mode is ON
        if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/trending.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_approved_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/trending.php");
            } else if ($check_approved_profile_msg == "NoExist") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            include(WP_DSP_ABSPATH . "/m1/trending.php");
        }
    }
} else if ($extra_pageurl == "interest_cloud") {
    $access_feature_name = "Interest Cloud";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {  // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/interest_cloud.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is on
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/interest_cloud.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                $check_approved_profile_msg = check_approved_profile_feature($user_id);
                if ($check_approved_profile_msg == "NoAccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_approved_profile_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/interest_cloud.php");
                } else if ($check_approved_profile_msg == "NoExist") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            } else { // if free trial mode is off
                $check_membership_msg = check_membership($access_feature_name, $user_id);

                if ($check_membership_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/interest_cloud.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            }
        }
    } else {
        if ($check_force_profile_mode->setting_status == "Y") {  // if force profile mode is OFF
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/interest_cloud.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_approved_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/interest_cloud.php");
            } else if ($check_approved_profile_msg == "NoExist") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            include(WP_DSP_ABSPATH . "/m1/interest_cloud.php");
        }
    }
} else if ($extra_pageurl == "date_tracker") {
    include(WP_DSP_ABSPATH . "/m1/date_tracker.php");
} else if ($extra_pageurl == "edit_date_tracker") {
    include(WP_DSP_ABSPATH . "/m1/edit_date_tracker.php");
} else if ($extra_pageurl == "meet_me") {
    include(WP_DSP_ABSPATH . "/m1/dsp_meet_me.php");
} else if ($extra_pageurl == "blogs") {

    include(WP_DSP_ABSPATH . "/m1/myblogs_header.php");
} else {
    include(WP_DSP_ABSPATH . "/m1/iviewed.php");
}
?>