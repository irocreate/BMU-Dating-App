<?php  
dsp_is_user_blocked($user_id,$member_id);  // to redirect blocked users from viewing the profile
$profile_pageurl = get('pagetitle'); 
if ($profile_pageurl != 'view_profile') {
    include_once( WP_DSP_ABSPATH . 'headers/view_profile_tab_header.php');
}
$dsp_show_profile_table = $wpdb->prefix . DSP_LIMIT_PROFILE_VIEW_TABLE;
//one to one chat pop up notification 
apply_filters('dsp_get_single_chat_popup_notification',$notification);
if ($profile_pageurl == "view_profile") {
    if ((get('view') != "") && ((get('view') == "my_profile") || (get('view') == "partner_profile"))) {
        if ($check_limit_profile_mode->setting_status == 'Y') {

            if (($user_id != $member_id)) {
                //echo "SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' AND member_id='$member_id' ";
                $count = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' AND member_id='$member_id' ");
                $no_of_profile = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' ");
                $general_settings_table = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'limit_profile'");
                $value = $general_settings_table->setting_value;
                if ($value <= $no_of_profile) {
                    $exist_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' AND member_id='$member_id' and status!='0' ");

                    if ($exist_member > 0) {
                        include_once(WP_DSP_ABSPATH . "headers/view_couples_profile_header.php");
                    } else {
                        if ($payment_status != 1) {
                            ?>	
							</div>	
                            <div class="dsp_box-out">
                                <div class="dsp_box-in">
                                    <div style="text-align:center;color:#FF0000;" class="box-page">

                                        <?php echo language_code('DSP_LIMIT_PROFILE_MESSAGE'); ?> 

                                    </div>
                                </div>

                            </div>
                            <?php
                        } else {

                            include_once(WP_DSP_ABSPATH . "headers/view_couples_profile_header.php");
                        }
                    }
                } else if (($count >= 0) && ($session_id != '')) {

                    $wpdb->query("INSERT INTO $dsp_show_profile_table SET user_id='$user_id', member_id='$member_id', status='0' ");
                    include_once(WP_DSP_ABSPATH . "headers/view_couples_profile_header.php");
                } else if ($count == 1) {
                    include_once(WP_DSP_ABSPATH . "headers/view_couples_profile_header.php");
                }
            } else {

                include_once(WP_DSP_ABSPATH . "headers/view_couples_profile_header.php");
            }
        } else {
            include_once(WP_DSP_ABSPATH . "headers/view_couples_profile_header.php");
        }
    } else {
        if ($check_limit_profile_mode->setting_status == 'Y') {

            if (($user_id != $member_id)) {
                //echo "SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' AND member_id='$member_id' ";
                $count = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' AND member_id='$member_id' ");

                $no_of_profile = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' ");

                $general_settings_table = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'limit_profile'");
                $value = $general_settings_table->setting_value;
                if ($value <= $no_of_profile) {
                    $exist_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_show_profile_table WHERE user_id='$user_id' AND member_id='$member_id'  and status!='0' ");
                    if ($exist_member > 0) {
                        include_once(WP_DSP_ABSPATH . "view_profile_setup.php");
                    } else {
                        if ($payment_status != 1) {
                            ?>
							</div>		
                            <div class="dsp_box-out">
                                <div class="dsp_box-in">
                                    <div style="text-align:center;color:#FF0000;" class="box-page">

                                        <?php echo language_code('DSP_LIMIT_PROFILE_MESSAGE'); ?> 

                                    </div>
                                </div>

                            </div>
                            <?php
                        } else {

                            include_once(WP_DSP_ABSPATH . "view_profile_setup.php");
                        }
                    }
                } else if (($count >= 0) && ($session_id != '')) {

                    $wpdb->query("INSERT INTO $dsp_show_profile_table SET user_id='$user_id', member_id='$member_id', status='0' ");
                    include_once(WP_DSP_ABSPATH . "view_profile_setup.php");
                } else if ($count == 1) {
                    include_once(WP_DSP_ABSPATH . "view_profile_setup.php");
                }
            } else {

                include_once(WP_DSP_ABSPATH . "view_profile_setup.php");
            }
        } else {
            include_once(WP_DSP_ABSPATH . "view_profile_setup.php");
        }
    }
} else if ($profile_pageurl == "send_wink_msg") {
    $access_feature_name = "Send Wink";
    if($_SESSION['free_member']){ 
            include_once(WP_DSP_ABSPATH . "send_wink_message.php");
    }else{
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "send_wink_message.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        } else {
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "send_wink_message.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            }
        }
    }
} else if ($profile_pageurl == "view_album") {
    include_once(WP_DSP_ABSPATH . "dsp_view_user_albums.php");
} else if ($profile_pageurl == "view_friends") {
    include_once(WP_DSP_ABSPATH . "dsp_view_member_friends.php");
} else if ($profile_pageurl == "view_Pictures") {
    include_once(WP_DSP_ABSPATH . "dsp_view_user_pictures.php");
} else if ($profile_pageurl == "view_video") {
    include_once(WP_DSP_ABSPATH . "dsp_view_member_videos.php");
} else if ($profile_pageurl == "view_audio") {
    include_once(WP_DSP_ABSPATH . "dsp_view_member_audios.php");
} else if ($profile_pageurl == "view_blogs") {
    include_once(WP_DSP_ABSPATH . "dsp_view_member_blogs.php");
}else if($profile_pageurl == "view_photos"){
    include_once(WP_DSP_ABSPATH . "dsp_view_user_photos.php");
} else if ($profile_pageurl == "one_on_one_chat" && $check_chat_one_mode->setting_status == 'Y') {
    include_once(WP_DSP_ABSPATH . "dsp_one_on_one_chat.php");
}
