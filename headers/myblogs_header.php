<?php
$myblog_pageurl = get('subpage');
?>
<div class="line top-gap dsp-blog-tab">
    <div <?php if ($myblog_pageurl == "add_blogs") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "extras/blogs/add_blogs/"; ?>"><?php echo language_code('DSP_MENU_ADD_MY_BLOGS'); ?></a></div>
    <div <?php if ($myblog_pageurl == "my_blogs") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
       <a href="<?php echo $root_link . "extras/blogs/my_blogs/"; ?>"><?php echo language_code('DSP_MENU_MY_BLOGS'); ?></a></div>
</div>
<div class="clr" style="clear:both"></div>
<?php
//one to one chat pop up notification 
apply_filters('dsp_get_single_chat_popup_notification',$notification);

if ($myblog_pageurl == "add_blogs") {
    $access_feature_name = "Blogs";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "dsp_add_blog.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        } else { // if free trial mode is ON
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "dsp_add_blog.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            
        } // END if free trial mode is ON
    } else {
        if($_SESSION['free_member']){
            include_once(WP_DSP_ABSPATH . "dsp_add_blog.php");
        }else {
                if ($check_force_profile_mode->setting_status == "Y") {
                    $check_force_profile_msg = check_free_force_profile_feature($user_id);
                    if ($check_force_profile_msg == "Approved") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "dsp_add_blog.php");
                    } else if ($check_force_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                    $check_approved_profile_msg = check_approved_profile_feature($user_id);
                    if ($check_approved_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_approved_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "dsp_add_blog.php");
                    }
                } else {
                    $check_membership_msg = check_membership($access_feature_name, $user_id);
                    if ($check_membership_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_membership_msg == "Access") {
                       include_once(WP_DSP_ABSPATH . "dsp_add_blog.php");
                    } else if ($check_membership_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                    
                }
        }    
    }
} else if ($myblog_pageurl == "my_blogs") {
    $access_feature_name = "Blogs";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "dsp_my_blogs.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        } else { // if free trial mode is ON	
            $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "dsp_my_blogs.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        } // END if free trial mode is ON
    } else {
        if($_SESSION['free_member']){
            include_once(WP_DSP_ABSPATH . "dsp_my_blogs.php");
        }else {
            if ($check_force_profile_mode->setting_status == "Y") {
                    $check_force_profile_msg = check_free_force_profile_feature($user_id);
                    if ($check_force_profile_msg == "Approved") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_force_profile_msg == "Access") {
                        include_once(WP_DSP_ABSPATH . "dsp_my_blogs.php");
                    } else if ($check_force_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                    $check_approved_profile_msg = check_approved_profile_feature($user_id);
                    if ($check_approved_profile_msg == "NoAccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_approved_profile_msg == "Access") {
                       include_once(WP_DSP_ABSPATH . "dsp_my_blogs.php");
                    }
                } else {
                    $check_membership_msg = check_membership($access_feature_name, $user_id);
                    if ($check_membership_msg == "Expired") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    } else if ($check_membership_msg == "Access") {
                       include_once(WP_DSP_ABSPATH . "dsp_my_blogs.php");
                    } else if ($check_membership_msg == "Onlypremiumaccess") {
                        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                    }
                    
                }
        }    
    }
} else {
    include_once(WP_DSP_ABSPATH . "view_profile_setup.php");
}
