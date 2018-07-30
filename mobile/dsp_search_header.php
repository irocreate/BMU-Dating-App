<?php
$profile_pageurl = $_REQUEST['pagetitle'];
if (isset($_REQUEST['search_type'])) {
    $Action_search_type = $_REQUEST['search_type'];
} else {
    $Action_search_type = "";
}
?>
<div class="dsp_mb_header"><?php echo DSP_MEMBER_SEARCH ?></div><br>
<div class="dsp_mem_div" style="width: 100%;margin-left: -10px;">

    <div <?php if (($profile_pageurl == "basic_search") || ($profile_pageurl == "")) { ?>class="dsp_mail_menu_active" <?php } else { ?>class="dsp_mb_mem_menu" <?php } ?>  >
        <a href="<?php echo add_query_arg(array('pid' => 5, 'pagetitle' => 'basic_search'), $root_link); ?>" title="<?php echo DSP_NEW_SEARCH ?>"><img src="<?php echo $imagepath . 'search.png' ?>"/>
            <?php echo DSP_NEW_SEARCH ?>
        </a>
    </div>
    <div style="padding-left: 2px;" <?php if ($profile_pageurl == "save_searches") { ?>class="dsp_mail_menu_active" <?php } else { ?>class="dsp_mb_mem_menu" <?php } ?> >
        <a href="<?php echo add_query_arg(array('pid' => 5, 'pagetitle' => 'save_searches'), $root_link); ?>" title="<?php echo DSP_MY_SAVED_SEARCHES ?>"><img src="<?php echo $imagepath . 'mysearch.png' ?>"/>&nbsp;<?php echo DSP_MY_SAVED_SEARCHES ?></a></div>
    <div class="clr"></div>
</div>
<?php
if ($profile_pageurl == "basic_search") {
    $access_feature_name = "Search";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "Y") {

            // if free trial mode is ON
            $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);

            if ($check_member_trial_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_user_search.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else if ($check_force_profile_mode->setting_status == "Y") {
            // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_user_search.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_approved_profile_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_user_search.php");
            }
        } else {   //  if free trial mode is OFF
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_user_search.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        }
    } else {


        if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_user_search.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_approved_profile_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_user_search.php");
            }
        } else {
            include("wp-content/plugins/dsp_dating/mobile/dsp_user_search.php");
        }
    }
} else if ($profile_pageurl == "save_searches") {
    $access_feature_name = "Saved Search";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {

                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {

                include("wp-content/plugins/dsp_dating/mobile/save_search_results.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {

                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else { // if free trial mode is ON
            $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "Expired") {

                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/save_search_results.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } // END if free trial mode is ON 
    } else {
        include("wp-content/plugins/dsp_dating/mobile/save_search_results.php");
    }
} else if (($profile_pageurl == "search_result") && ($Action_search_type != "Advanced")) {
    $access_feature_name = "Search";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "Y") {// if free trial mode is ON
            $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/search_result.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/search_result.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_approved_profile_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/search_result.php");
            }
        } else {

            include("wp-content/plugins/dsp_dating/mobile/search_result.php");
        }
    } else {
        if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/search_result.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_approved_profile_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/search_result.php");
            }
        } else {

            include("wp-content/plugins/dsp_dating/mobile/search_result.php");
        }
    }
}
///////////////////////////////////////////////////////////////////////////////
?>