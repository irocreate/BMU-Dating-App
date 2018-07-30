<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

$photos_pageurl = get('pagetitle');
?>
    <div class="line">
        <?php if ($check_picture_gallery_mode->setting_status == 'Y') { ?>
            <div <?php if (($photos_pageurl == "photo")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
                <a href="<?php echo $root_link . "media/photo/"; ?>"><?php echo language_code('DSP_MENU_PHOTOS'); ?></a></div>
            <div <?php if ($photos_pageurl == "album") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
                <a href="<?php echo $root_link . "media/album/"; ?>"><?php echo language_code('DSP_MEDIA_HEADER_ALBUMS'); ?></a></div>
            
        <?php } ?>
        <?php if ($check_audio_mode->setting_status == 'Y') { ?>
            <div <?php if (($photos_pageurl == "add_audio")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
                <a href="<?php echo $root_link . "media/add_audio/"; ?>"><?php echo language_code('DSP_MEDIA_HEADER_AUDIOS'); ?></a></div>
        <?php } ?>
        <?php if ($check_video_mode->setting_status == 'Y') { ?>
            <div <?php if (($photos_pageurl == "add_video")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
                <a href="<?php echo $root_link . "media/add_video/"; ?>"><?php echo language_code('DSP_MEDIA_HEADER_VIDEOS'); ?></a></div>
        <?php } ?>
        <div class="clr"></div>
    </div>
</div>
<?php
//one to one chat pop up notification 
apply_filters('dsp_get_single_chat_popup_notification',$notification);

if ($photos_pageurl == "album") {
    $access_feature_name = "Create Album";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {
            // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "members/loggedin/media/add_photos_album.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                
                if ($check_membership_msg == "Expired") {

                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {

                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/add_photos_album.php");
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
                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/add_photos_album.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } // END if free trial mode is ON
        }
    } else {
        if($_SESSION['free_member']){ 
            if ($check_picture_gallery_mode->setting_status == 'Y') {
                include_once(WP_DSP_ABSPATH . "members/loggedin/media/add_photos_album.php");
            } else if ($check_audio_mode->setting_status == 'Y') {
                include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_audios.php");
            } else if ($check_video_mode->setting_status == 'Y') {
                include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_videos.php");
            }

        }else{
            if ($check_force_profile_mode->setting_status == "Y") {
                // if force profile mode is OFF
                $check_force_profile_msg = check_free_force_profile_feature($user_id);
                if ($check_force_profile_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Access") {
                    if ($check_picture_gallery_mode->setting_status == 'Y') {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/media/add_photos_album.php");
                    } else if ($check_audio_mode->setting_status == 'Y') {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_audios.php");
                    } else if ($check_video_mode->setting_status == 'Y') {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_videos.php");
                    }
                } else if ($check_force_profile_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else { 
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    if ($check_picture_gallery_mode->setting_status == 'Y') {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/media/add_photos_album.php");
                    } else if ($check_audio_mode->setting_status == 'Y') {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_audios.php");
                    } else if ($check_video_mode->setting_status == 'Y') {
                        include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_videos.php");
                    }
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
                
            }
       }
       
    }
} else if ($photos_pageurl == "photo") {
    $access_feature_name = "Upload Photos"; 
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {
            // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "members/loggedin/media/upload_images.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
                $check_membership_msg = check_membership($access_feature_name, $user_id);

                if ($check_membership_msg == "Expired") {

                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {

                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/upload_images.php");
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
                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/upload_images.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } // END if free trial mode is ON
        }
    } else {
        if($_SESSION['free_member']){
            include_once(WP_DSP_ABSPATH . "members/loggedin/media/upload_images.php");
        }else{
            if ($check_force_profile_mode->setting_status == "Y") {
                // if force profile mode is OFF
                $check_force_profile_msg = check_free_force_profile_feature($user_id);
                if ($check_force_profile_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/upload_images.php");
                } else if ($check_force_profile_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else {
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/upload_images.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
                
            }
        }
    }
} else if ($photos_pageurl == "add_audio") {
    $access_feature_name = "Upload Audio";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {
            // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_audios.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
                $check_membership_msg = check_membership($access_feature_name, $user_id);

                if ($check_membership_msg == "Expired") {

                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {

                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_audios.php");
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
                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_audios.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } // END if free trial mode is ON
        }
    } else {
       if($_SESSION['free_member']){ 
            include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_audios.php");
        }else{
            if ($check_force_profile_mode->setting_status == "Y") {
                // if force profile mode is OFF
                $check_force_profile_msg = check_free_force_profile_feature($user_id);
                if ($check_force_profile_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_audios.php");
                } else if ($check_force_profile_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else {
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_audios.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            }
        }
    }
} else if ($photos_pageurl == "add_video") {
    $access_feature_name = "Upload Video";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {
            // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_videos.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
                $check_membership_msg = check_membership($access_feature_name, $user_id);

                if ($check_membership_msg == "Expired") {

                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {

                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_videos.php");
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
                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_videos.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } // END if free trial mode is ON   
        }
    } else { 
        if($_SESSION['free_member']){
            include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_videos.php");
        }else{
            if ($check_force_profile_mode->setting_status == "Y") {
                // if force profile mode is OFF
                $check_force_profile_msg = check_free_force_profile_feature($user_id);
                if ($check_force_profile_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Access") { 
                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_videos.php");
                } else if ($check_force_profile_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else {
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/media/dsp_add_videos.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
                
            }
        }
    }
}
if ($photos_pageurl == "manage_photos") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/media/manage_photos.php");
}
