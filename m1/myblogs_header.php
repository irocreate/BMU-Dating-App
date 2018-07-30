<?php

$myblog_pageurl = $_REQUEST['subpage'];
?>


<?php

if ($myblog_pageurl == "add_blogs") {
    $access_feature_name = "Blogs";

    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_add_blog.php");
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
                include(WP_DSP_ABSPATH . "/m1/dsp_add_blog.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } // END if free trial mode is ON
    } else {
        include(WP_DSP_ABSPATH . "/m1/dsp_add_blog.php");
    }
} else if ($myblog_pageurl == "my_blogs") {
    $access_feature_name = "Blogs";

    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_my_blogs.php");
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
                include(WP_DSP_ABSPATH . "/m1/dsp_my_blogs.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } // END if free trial mode is ON
    } else {
        include(WP_DSP_ABSPATH . "/m1/dsp_my_blogs.php");
    }
} else {

    include(WP_DSP_ABSPATH . "/m1/dspBlogs.php");
}
?>