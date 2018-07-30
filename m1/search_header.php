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

$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

$user_id = $_REQUEST['user_id'];

$profile_pageurl = isset($_REQUEST['pagetitle']) ? $_REQUEST['pagetitle'] : '';

$Action_search_type = isset($_REQUEST['search_type']) ? $_REQUEST['search_type'] : '';

$fav_icon_image_path = $imagepath . "plugins/dsp_dating/m1/images/"; // fav,chat,star,friends,mail Icon image path

$check_user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles_table WHERE user_id=$user_id");


// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');

$user_id = $_REQUEST['user_id'];

if ($profile_pageurl == "basic_search") {
    include_once(WP_DSP_ABSPATH . '/m1/country_st_ct.php'); // to change the state and city according to country

    $access_feature_name = "Search";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {  // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_user_search.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "Y") {// if free trial mode is ON
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_user_search.php");
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
                    include(WP_DSP_ABSPATH . "/m1/dsp_user_search.php");
                }
            } else {   //  if free trial mode is OFF
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_user_search.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            }
        }
    } else {
        if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_user_search.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_approved_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_user_search.php");
            }
        } else {
            include(WP_DSP_ABSPATH . "/m1/dsp_user_search.php");
        }
    }
} else if ($profile_pageurl == "advance_search") {
    include_once(WP_DSP_ABSPATH . '/m1/country_st_ct.php'); // to change the state and city according to country

    $access_feature_name = "Advanced Search";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/user_advanced_search.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "Y") { // if free trial mode is ON
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/user_advanced_search.php");
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
                    include(WP_DSP_ABSPATH . "/m1/user_advanced_search.php");
                }
            } else { // free trial mode is off 
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/user_advanced_search.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            }
        }
    } else {
        if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {

                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/user_advanced_search.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_approved_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/user_advanced_search.php");
            }
        } else {
            include(WP_DSP_ABSPATH . "/m1/user_advanced_search.php");
        }
    }
} else if ($profile_pageurl == "save_searches") {

    $access_feature_name = "Saved Search";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {

                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {

                include(WP_DSP_ABSPATH . "/m1/save_search_results.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {

                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else { // if free trial mode is ON
            $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/save_search_results.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } // END if free trial mode is ON 
    } else {
        include(WP_DSP_ABSPATH . "/m1/save_search_results.php");
    }
} else if (($profile_pageurl == "search_result") && ($Action_search_type == "Advanced")) {

    include(WP_DSP_ABSPATH . "/m1/search_result.php");
} else if (($profile_pageurl == "search_result") && ($Action_search_type != "Advanced")) {
    $access_feature_name = "Search";

    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/search_result.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "Y") {// if free trial mode is ON
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/search_result.php");
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
                    include(WP_DSP_ABSPATH . "/m1/search_result.php");
                }
            } else {

                include(WP_DSP_ABSPATH . "/m1/search_result.php");
            }
        }
    } else {

        if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/search_result.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_approved_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/search_result.php");
            }
        } else {

            include(WP_DSP_ABSPATH . "/m1/search_result.php");
        }
    }
}
///////////////////////////////////////////////////////////////////////////////////
else if (($profile_pageurl == "zipcode_search")) {
    $access_feature_name = "Zip Code Search";

    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/zip_code_search.php");
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
                    include(WP_DSP_ABSPATH . "/m1/zip_code_search.php");
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
                    include(WP_DSP_ABSPATH . "/m1/zip_code_search.php");
                }
            } else { // if free trial mode is off
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/zip_code_search.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            }
        }
    } else {
        if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/zip_code_search.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_approved_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/zip_code_search.php");
            }
        } else {

            include(WP_DSP_ABSPATH . "/m1/zip_code_search.php");
        }
    }
} else if ($profile_pageurl == "zipcode_search_result") {
    include(WP_DSP_ABSPATH . "/m1/zipcode_search_result.php");
} else if ($profile_pageurl == "myinterest_search_result") {

    include(WP_DSP_ABSPATH . "/m1/myinterest_search_result.php");
} else if ($profile_pageurl == "main_search") {
    include(WP_DSP_ABSPATH . "/m1/dspSearch.php");
}

///////////////////////////////////////////////////////////////////////////////
?>