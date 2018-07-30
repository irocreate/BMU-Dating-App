<?php
include("../../../../wp-config.php");

//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->

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
//http://docs.phonegap.com/en/2.9.0/cordova_media_capture_capture.md.html#CaptureImageOptions
// play audio http://simonmacdonald.blogspot.in/2011/05/using-media-class-in-phonegap.html
//http://mobile.tutsplus.com/tutorials/phonegap/build-an-audioplayer-with-phonegap-application-tuning/
// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');

$user_id = $_REQUEST['user_id'];

$photos_pageurl = isset($_REQUEST['pagetitle']) ? $_REQUEST['pagetitle'] : '';

$dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;



if ($photos_pageurl == "media") {
    include(WP_DSP_ABSPATH . "/m1/dsp_media.php");
}
if ($photos_pageurl == "album") {
      
    $access_feature_name = "Create Album";

    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {
            // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/add_photos_album.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
                $check_membership_msg = check_membership($access_feature_name, $user_id);

                if ($check_membership_msg == "Expired") {

                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {

                    include(WP_DSP_ABSPATH . "/m1/add_photos_album.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {

                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            } else { // if free trial mode is ON
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                ;
                if ($check_member_trial_msg == "NotExist") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    echo "approved";
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include(WP_DSP_ABSPATH . "/m1/add_photos_album.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            } // END if free trial mode is ON
        }
    } else {
        if ($check_force_profile_mode->setting_status == "Y") {
             // if force profile mode is OFF

            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                if ($check_picture_gallery_mode->setting_status == 'Y') {
                    include(WP_DSP_ABSPATH . "/m1/add_photos_album.php");
                } else if ($check_audio_mode->setting_status == 'Y') {
                    include(WP_DSP_ABSPATH . "/m1/dsp_add_audios.php");
                } else if ($check_video_mode->setting_status == 'Y') {
                    include(WP_DSP_ABSPATH . "/m1/dsp_add_videos.php");
                }
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            if ($check_picture_gallery_mode->setting_status == 'Y') {
                include(WP_DSP_ABSPATH . "/m1/add_photos_album.php");
            } else if ($check_audio_mode->setting_status == 'Y') {
                include(WP_DSP_ABSPATH . "/m1/dsp_add_audios.php");
            } else if ($check_video_mode->setting_status == 'Y') {
                include(WP_DSP_ABSPATH . "/m1/dsp_add_videos.php");
            }else{
                include(WP_DSP_ABSPATH . "/m1/add_photos_album.php");
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
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/upload_images.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
                $check_membership_msg = check_membership($access_feature_name, $user_id);

                if ($check_membership_msg == "Expired") {

                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {

                    include(WP_DSP_ABSPATH . "/m1/upload_images.php");
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
                    include(WP_DSP_ABSPATH . "/m1/upload_images.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            } // END if free trial mode is ON
        }
    } else {
        if ($check_force_profile_mode->setting_status == "Y") {
            // if force profile mode is OFF
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/upload_images.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            include(WP_DSP_ABSPATH . "/m1/upload_images.php");
        }
    }
} else if ($photos_pageurl == "add_audio") {

    $access_feature_name = "Upload Audio";

    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {
            // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_add_audios.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
                $check_membership_msg = check_membership($access_feature_name, $user_id);

                if ($check_membership_msg == "Expired") {

                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {

                    include(WP_DSP_ABSPATH . "/m1/dsp_add_audios.php");
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
                    include(WP_DSP_ABSPATH . "/m1/dsp_add_audios.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            } // END if free trial mode is ON
        }
    } else {
        if ($check_force_profile_mode->setting_status == "Y") {
            // if force profile mode is OFF
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_add_audios.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            include(WP_DSP_ABSPATH . "/m1/dsp_add_audios.php");
        }
    }
} else if ($photos_pageurl == "add_video") {

    $access_feature_name = "Upload Video";

    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {
            // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_add_videos.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off 
                $check_membership_msg = check_membership($access_feature_name, $user_id);

                if ($check_membership_msg == "Expired") {

                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {

                    include(WP_DSP_ABSPATH . "/m1/dsp_add_videos.php");
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
                    include(WP_DSP_ABSPATH . "/m1/dsp_add_videos.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
                }
            } // END if free trial mode is ON   
        }
    } else {
        if ($check_force_profile_mode->setting_status == "Y") {
            // if force profile mode is OFF
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include(WP_DSP_ABSPATH . "/m1/dsp_add_videos.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include(WP_DSP_ABSPATH . "/m1/dsp_print_message.php");
            }
        } else {
            include(WP_DSP_ABSPATH . "/m1/dsp_add_videos.php");
        }
    }
}

/* else if($photos_pageurl=="manage_album")

  {

  include(WP_DSP_ABSPATH."/m1//manage_albums.php");

  }
 */
if ($photos_pageurl == "manage_photos") {
    include(WP_DSP_ABSPATH . "/m1/manage_photos.php");
}
?>