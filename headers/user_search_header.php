<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

$profile_pageurl = get('pagetitle');
$Action_search_type = get('search_type');
?>
<div class="line">
    <div <?php if (($profile_pageurl == "basic_search")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "search/basic_search/"; ?>"><?php echo language_code('DSP_SUBMENU_SEARCH_SEARCH'); ?></a></div>
    <div <?php if (($profile_pageurl == "advance_search")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "search/advance_search/"; ?>"><?php echo language_code('DSP_SUBMENU_SEARCH_ADVANCED'); ?></a></div> 
    <?php //////////////////////////////////////////Zip Code////////////////////////////////////////////////////////?>
    <?php if ($check_zipcode_mode->setting_status == 'Y') { ?>
        <div <?php if (($profile_pageurl == "zipcode_search")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>

            <a href="<?php echo $root_link . "search/zipcode_search/"; ?>"><?php echo language_code('DSP_ZIP_CODE'); ?></a></div>
    <?php } ?>
    <?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

     <?php //////////////////////////////////////////Zip Code////////////////////////////////////////////////////////?>
    <?php if ($check_distance_mode->setting_status == 'Y') { ?>
        <div <?php if (($profile_pageurl == "distance_search")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>

            <a href="<?php echo $root_link . "search/distance_search/"; ?>"><?php echo language_code('DSP_DISTANCE_SEARCH'); ?></a></div>
    <?php } ?>
    <?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>


    <?php //////////////////////////////////////////Near Me////////////////////////////////////////////////////////?>
    <?php if ($check_near_me->setting_status == 'Y') { ?>
        <div <?php if (($profile_pageurl == "near_me")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>

            <a href="<?php echo $root_link . "search/near_me/"; ?>"><?php echo language_code('DSP_NEAR_ME'); ?></a></div>
    <?php } ?>
    <?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

    <div <?php if (($profile_pageurl == "save_searches")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "search/save_searches/"; ?>"><?php echo language_code('DSP_SUBMENU_SEARCH_SAVED'); ?></a></div>
    <!-- <div <?php if (($profile_pageurl == "search_result")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "search/search_result/"; ?>"><?php echo language_code('DSP_SUBMENU_SEARCH_RESULT'); ?></a></div> -->
    <div class="clr"></div>
</div>
</div>

<?php 
//one to one chat pop up notification 
apply_filters('dsp_get_single_chat_popup_notification',$notification);
switch ($profile_pageurl) {
    case ($profile_pageurl == 'basic_search'):
       $access_feature_name = "Search";
        if ($check_free_mode->setting_status == "N") {  // free mode is off 
            if ($check_free_trail_mode->setting_status == "Y") {// if free trial mode is ON
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") { 
                    include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/basic/dsp_user_search.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else {
                if ($check_force_profile_mode->setting_status == "Y") {  // if force profile mode is OFF
                    $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
                    if ($check_force_profile_msg == "Approved") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/basic/dsp_user_search.php");
                    } else if ($check_force_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                    $check_approved_profile_msg = check_approved_profile_feature($user_id);
                    if ($check_approved_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_approved_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/basic/dsp_user_search.php");
                    }
                } else {   //  if free trial mode is OFF
                    $check_membership_msg = check_membership($access_feature_name, $user_id);
                    if ($check_membership_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_membership_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/basic/dsp_user_search.php");
                    } else if ($check_membership_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                }
            }
        } else { 
            if($_SESSION['free_member']){
                include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/basic/dsp_user_search.php");
            }else{
                if ($check_force_profile_mode->setting_status == "Y") {
                    $check_force_profile_msg = check_free_force_profile_feature($user_id);
                    if ($check_force_profile_msg == "Approved") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/basic/dsp_user_search.php");
                    } else if ($check_force_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                    $check_approved_profile_msg = check_approved_profile_feature($user_id);

                    if ($check_approved_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_approved_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/basic/dsp_user_search.php");
                    }
                } else { 
                    $check_membership_msg = check_membership($access_feature_name, $user_id);
                    if ($check_membership_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_membership_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/basic/dsp_user_search.php");
                    } else if ($check_membership_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                }
            }
        }
        break;
    
    case ($profile_pageurl =='advance_search'):
        $access_feature_name = "Advanced Search";
        if ($check_free_mode->setting_status == "N") {  // free mode is off 
            if ($check_free_trail_mode->setting_status == "Y") { // if free trial mode is ON
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/advanced/user_advanced_search.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else {
                if ($check_force_profile_mode->setting_status == "Y") {
                    $access_feature_name = isset($access_feature_name) ? $access_feature_name : '';
                    $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
                    if ($check_force_profile_msg == "Approved") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/advanced/user_advanced_search.php");
                    } else if ($check_force_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                    $check_approved_profile_msg = check_approved_profile_feature($user_id);
                    if ($check_approved_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_approved_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/advanced/user_advanced_search.php");
                    }
                } else { // free trial mode is off 
                    $check_membership_msg = check_membership($access_feature_name, $user_id);
                    if ($check_membership_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_membership_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/advanced/user_advanced_search.php");
                    } else if ($check_membership_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                }
            }
        } else {
            if($_SESSION['free_member']){
                include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/advanced/user_advanced_search.php");
            }else{
                if ($check_force_profile_mode->setting_status == "Y") {
                    $check_force_profile_msg = check_free_force_profile_feature($user_id);
                    if ($check_force_profile_msg == "Approved") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/advanced/user_advanced_search.php");
                    } else if ($check_force_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                    $check_approved_profile_msg = check_approved_profile_feature($user_id);
                    if ($check_approved_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_approved_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/advanced/user_advanced_search.php");
                    }
                } else {
                    $check_membership_msg = check_membership($access_feature_name, $user_id);
                    if ($check_membership_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_membership_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/user/advanced/user_advanced_search.php");
                    } else if ($check_membership_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                    
                }
                
            }
            
        }
        break;

    case ($profile_pageurl =='distance_search'):
        if ($check_distance_mode->setting_status == 'Y') {
            include_once(WP_DSP_ABSPATH . "members/loggedin/search/distance/distance_search.php");
        }
        break;
    case ($profile_pageurl =='save_searches'):
         $access_feature_name = "Saved Search";
        if ($check_free_mode->setting_status == "N") {  // free mode is off 
            if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired") {

                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {

                    include_once(WP_DSP_ABSPATH . "members/loggedin/search/save/save_search_results.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {

                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else { // if free trial mode is ON
                if($_SESSION['free_member']){
                    include_once(WP_DSP_ABSPATH . "members/loggedin/search/save/save_search_results.php");
                }else{
                    $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                    if ($check_member_trial_msg == "NotExist") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_member_trial_msg == "Approved") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_member_trial_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_member_trial_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/save/save_search_results.php");
                    } else if ($check_member_trial_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                }
            } 
        }else {
            if($_SESSION['free_member']){
                include_once(WP_DSP_ABSPATH . "members/loggedin/search/save/save_search_results.php");
            }else {
                if ($check_force_profile_mode->setting_status == "Y") {
                    $check_force_profile_msg = check_free_force_profile_feature($user_id);
                    if ($check_force_profile_msg == "Approved") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/save/save_search_results.php");
                    } else if ($check_force_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                    $check_approved_profile_msg = check_approved_profile_feature($user_id);
                    if ($check_approved_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_approved_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/save/save_search_results.php");
                    }
                } else {
                    $check_membership_msg = check_membership($access_feature_name, $user_id);
                    if ($check_membership_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_membership_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/save/save_search_results.php");
                    } else if ($check_membership_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                }
            }
        }
        break;

    case ($profile_pageurl =='search_result' && $Action_search_type == 'Advanced'):
        include_once(WP_DSP_ABSPATH . "members/loggedin/search/search_result.php");
        break;

    case ($profile_pageurl == 'search_result' && $Action_search_type != 'Advanced'):
        $access_feature_name = "Search";
        if ($check_free_mode->setting_status == "N") {  // free mode is off 
            if ($check_free_trail_mode->setting_status == "Y") {// if free trial mode is ON
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/search/search_result.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else {
                if ($check_force_profile_mode->setting_status == "Y") {
                    $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
                    if ($check_force_profile_msg == "Approved") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/search_result.php");
                    } else if ($check_force_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                    $check_approved_profile_msg = check_approved_profile_feature($user_id);
                    if ($check_approved_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_approved_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/search_result.php");
                    }
                } else {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/search/search_result.php");
                }
            }
        } else {
           if($_SESSION['free_member']){ 
                include_once(WP_DSP_ABSPATH . "members/loggedin/search/search_result.php");
            }else{ 
                    if ($check_force_profile_mode->setting_status == "Y") {
                        $check_force_profile_msg = check_free_force_profile_feature($user_id);
                        if ($check_force_profile_msg == "Approved") {
                            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                        } else if ($check_force_profile_msg == "Access") {
                            include_once(WP_DSP_ABSPATH . "members/loggedin/search/search_result.php");
                        } else if ($check_force_profile_msg == "NoAccess") {
                            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                        }
                    } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                        $check_approved_profile_msg = check_approved_profile_feature($user_id);
                        if ($check_approved_profile_msg == "NoAccess") {
                            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                        } else if ($check_approved_profile_msg == "Access") {
                            include_once(WP_DSP_ABSPATH . "members/loggedin/search/search_result.php");
                        }
                    } else {
                        $check_membership_msg = check_membership($access_feature_name, $user_id);
                        if ($check_membership_msg == "Expired") {
                            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                        } else if ($check_membership_msg == "Access") {
                            include_once(WP_DSP_ABSPATH . "members/loggedin/search/search_result.php");
                        } else if ($check_membership_msg == "Onlypremiumaccess") {
                            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                        }
                    }
            }
            
        }
        break;

    case ($profile_pageurl == 'zipcode_search'):
        $access_feature_name = "Zip Code Search";
        if ($check_free_mode->setting_status == "N") {  // free mode is off 
            if ($check_force_profile_mode->setting_status == "Y") {
                $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
                if ($check_force_profile_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/search/zipcode/zip_code_search.php");
                } else if ($check_force_profile_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else {
                if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is on
                    $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                    if ($check_member_trial_msg == "NotExist") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_member_trial_msg == "Approved") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_member_trial_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_member_trial_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/zipcode/zip_code_search.php");
                    } else if ($check_member_trial_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                    $check_approved_profile_msg = check_approved_profile_feature($user_id);
                    if ($check_approved_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_approved_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/zipcode/zip_code_search.php");
                    }
                } else { // if free trial mode is off
                    $check_membership_msg = check_membership($access_feature_name, $user_id);
                    if ($check_membership_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_membership_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/zipcode/zip_code_search.php");
                    } else if ($check_membership_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                }
            }
        } else {
            if($_SESSION['free_member']){ 
                include_once(WP_DSP_ABSPATH . "members/loggedin/search/zipcode/zip_code_search.php");
            }else{
                if ($check_force_profile_mode->setting_status == "Y") {
                    $check_force_profile_msg = check_free_force_profile_feature($user_id);
                    if ($check_force_profile_msg == "Approved") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/zipcode/zip_code_search.php");
                    } else if ($check_force_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                    $check_approved_profile_msg = check_approved_profile_feature($user_id);
                    if ($check_approved_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_approved_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/zipcode/zip_code_search.php");
                    }
                } else {
                    $check_membership_msg = check_membership($access_feature_name, $user_id);
                    if ($check_membership_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_membership_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/search/zipcode/zip_code_search.php");
                    } else if ($check_membership_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                    
                }
            }
        }
        break;

    case ($profile_pageurl =='zipcode_search_result'):
        include_once(WP_DSP_ABSPATH . "members/loggedin/search/zipcode/zipcode_search_result.php");
        break;

    case ($profile_pageurl =='near_me'):
        include_once(WP_DSP_ABSPATH . "members/loggedin/search/nearme/near_me_search_result.php");
        break;

    case ($profile_pageurl =='myinterest_search_result'):
        include_once(WP_DSP_ABSPATH . "members/loggedin/search/interest/myinterest_search_result.php");
        break;
    default:
        # code...
        break;
}