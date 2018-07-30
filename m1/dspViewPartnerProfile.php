<?php
$session_id = $user_id;

$review_date = date('Y-m-d');

$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
$dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
$dsp_question_details = $wpdb->prefix . DSP_PARTNER_PROFILE_QUESTIONS_DETAILS;
$dsp_counter_hits_table = $wpdb->prefix . DSP_COUNTER_HITS_TABLE;
$dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_blocked_members_table = $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
$dsp_member_audios = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
$dsp_member_videos = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
$dsp_my_blog_table = $wpdb->prefix . DSP_MY_BLOGS_TABLE;
$dsp_online_user_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

$friendSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='my_friends'");
$gallerySetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='picture_gallery_module'");
$videSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='video_module'");
$audioSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='audio_module'");
$blogSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='blog_module'");


if (($user_id != $member_id)) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_counter_hits_table WHERE user_id=$user_id AND member_id=$member_id AND review_date='$review_date'");
    if (($count <= 0) && ($session_id != 0)) {
        $wpdb->query("INSERT INTO $dsp_counter_hits_table SET user_id=$user_id, member_id=$member_id, review_date='$review_date' ");
    }
}

$check_exist_profile_details = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_partner_profiles_table WHERE status_id=1 AND user_id = '$member_id'");

if ($check_exist_profile_details > 0) {
    $exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_partner_profiles_table WHERE status_id=1 AND user_id = '$member_id'");
    $userName = $wpdb->get_var("SELECT display_name FROM $DSP_USERS_TABLE WHERE id =$member_id ");

    $myStatus = $wpdb->get_var("Select my_status from $dsp_user_profiles_table WHERE user_id = $member_id");

    $check_user_privacy_settings = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE view_my_friends='Y' AND user_id='$member_id'");
    if (($check_user_privacy_settings > 0) && ($user_id != $member_id)) {  // check user privacy settings
        $totalFriends = $wpdb->get_var("SELECT count(*) FROM $dsp_my_friends_table WHERE user_id = '$member_id' AND approved_status='Y'");
    } else {


        if ($check_couples_mode->setting_status == 'Y') {

            $totalFriends = $wpdb->get_var("SELECT count(*) FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$member_id' AND friends.approved_status='Y'");
        } else {

            $totalFriends = $wpdb->get_var("SELECT count(*) FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$member_id' AND friends.approved_status='Y' AND profile.gender!='C'");
        }
    }


    $totalPhotos = $wpdb->get_var("SELECT count(*) FROM $dsp_galleries_photos where user_id='$member_id'");

    $totalSounds = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_audios where user_id='$member_id' AND status_id=1");

    $totalVideo = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_videos where user_id='$member_id' AND status_id=1");

    $totalBlogs = $wpdb->get_var("SELECT count(*) FROM $dsp_my_blog_table WHERE user_id=$member_id");
    ?>

    <div role="banner" class="ui-header ui-bar-a" data-role="header">
        <?php include_once("page_back.php");?> 
        <h1 aria-level="1" role="heading" class="ui-title"><?php echo $userName; ?></h1>
        <div  class="ui-btn-right" style="padding-top:10px;font-size: 12px; ">
            <?php if ($user_id != $member_id) { ?>
            <a  onclick="addFriend(<?php echo $member_id; ?>)">
                <img src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/friends.jpg' ?>" style="vertical-align:middle;" /> 
                <?php echo language_code('DSP_ADD_FRIEND'); ?>
            </a>
            <?php } ?>
        </div>
    </div>
    <?php
    if ($gender == 'C') {
        ?>  
        <div class="ui-content" data-role="content">
            <div class="content-primary">

                <div data-role="navbar" class="ui-navbar ui-mini" role="navigation">
                    <ul class="ui-grid-duo ui-grid-a">
                        <li class="ui-block-a">
                            <a onclick="viewMemberProfile('<?php echo $member_id ?>', 'my_profile')" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-theme="a" data-inline="true" class="ui-btn ui-btn-inline ui-btn-up-a  <?php if ($view == "my_profile") echo "ui-btn-active"; ?>">
                                <span class="ui-btn-inner"><span class="ui-btn-text"><?php echo language_code('DSP_MENU_EDIT_MY_PROFILE'); ?></span></span>
                            </a>
                        </li>
                        <li class="ui-block-b">
                            <a onclick="viewMemberProfile('<?php echo $member_id ?>', 'partner_profile')"  data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-theme="a" data-inline="true" class="ui-btn ui-btn-inline ui-btn-up-a <?php if ($view == "partner_profile") echo "ui-btn-active"; ?>">
                                <span class="ui-btn-inner"><span class="ui-btn-text"><?php echo language_code('DSP_MENU_EDIT_PARTNER_PROFILE'); ?></span></span>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        <?php } ?>
        
        <?php
// ------------------------------------START BLOCKED MEMBER -------------------------------------//

        $profileView = "partner_profile";

        $blocked_event = isset($_REQUEST['block_event']) ? $_REQUEST['block_event'] : '';

        if (($blocked_event == "blocked") && ($user_id != $member_id) && ($user_id != "")) {

            $check_block_mem_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blocked_members_table WHERE block_member_id='$member_id' AND user_id='$user_id'");
            if ($check_block_mem_exist <= 0) {
                $wpdb->query("INSERT INTO $dsp_blocked_members_table SET user_id = '$user_id',block_member_id ='$member_id'");

                $msg_blocked = language_code('DSP_MEMBER_BLOCKED_MESSAGE');
            } else {
                if ($user_id != "") {
                    $msg_blocked = language_code('DSP_EXIST_IN_BLOCK_LIST_MSG');
                }
            }
        }

        if (isset($msg_blocked)) {
            ?>

            <div class="ui-content" data-role="content">
                <div class="content-primary">	
                    <div style="color:#FF0000;" align="center"><strong><?php echo $msg_blocked ?></strong></div>
                </div>
                <?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
            </div>
            <?php
        }



// ------------------------------------END  BLOCKED MEMBER -------------------------------------//
    // ----------------------------------Check member privacy Settings------------------------------------



        $check_user_privacy_settings = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE view_my_profile='Y' AND user_id='$member_id'");



        $check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'");
        ?>
        <div class="ui-content" data-role="content">
            <?php
        if (($check_user_privacy_settings > 0) && ($user_id != $member_id)) {  // check user privacy settings 
            if ($check_my_friends_list <= 0) {   // check member is not in my friend list 
                ?>

                <div class="content-primary">	
                    <div align="center"><?php echo language_code('DSP_NOT_MEMBER_FRIEND_MESSAGE'); ?></div>

                </div>



                <?php
            } else {   // -----------------------------else Check member is in my friend list ---------------------------- //
// MEMBER TOTAL ADDED PHOTOS
                ?>



                <div class="content-primary">	 

                    <ul id="iGallery"  data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">
                        <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                            <div class="dsp_pro_full_view">
                                <div class="profile_img_view"> 
                                    <?php
                                    $favt_mem = array();

                                    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_id'");

                                    foreach ($private_mem as $private) {

                                        $favt_mem[] = $private->favourite_user_id;
                                    }

                                    if ($exist_profile_details->make_private == 'Y') {
                                        if ($user_id != $member_id) {
                                            if (!in_array($user_id, $favt_mem)) {
                                                ?>
                                                <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:100px; height:100px;"border="0" class="dsp_img3" />   
                                                <?php
                                            } else {
                                                ?>
                                                <a class="group1" >
                                                    <img src="<?php echo display_thumb2_members_partner_photo($member_id, $imagepath); ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" />
                                                </a>         
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <a class="group1" >
                                                <img src="<?php echo display_thumb2_members_partner_photo($member_id, $imagepath); ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" />
                                            </a>               
                                            <?php } ?>
                                            <?php
                                        } else {
                                            ?>
                                            <a class="group1" >
                                                <img src="<?php echo display_thumb2_members_partner_photo($member_id, $imagepath); ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" />
                                            </a>
                                            <?php
                                        }
                                        unset($favt_mem);
                                        ?>
                                    </div>
                                    <div class="dsp_on_lf_view">
                                        <ul>
                                            <li>
                                                <?php echo $userName; ?>
                                            </li>
                                            <li>
                                                <?php echo $myStatus; ?>
                                            </li>
                                            <li class="dsp_prof_view">

                                                <div>
                                                    <a onclick="addFavourite('<?php echo $member_id; ?>')" title="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>">
                                                        <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>star.jpg" border="0" />
                                                    </a>
                                                </div>
                                                <div> 

                                                    <?php
                                                    if (isset($check_my_friends_list) && $check_my_friends_list > 0) {
                                                        ?>
                                                        <a onclick="composeMessage('<?php echo $member_id ?>', 0)" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" />
                                                        </a>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a onclick="composeMessage('<?php echo $member_id ?>', 0)" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" /></a>
                                                            <?php } //if($check_my_friends_list>0)   ?>

                                                        </div>


                                            <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not 
                                                ?>
                                                <div>
                                                    <?php
                                                    if ($check_user_profile_exist > 0) {  // check user dating profile exist or not 		  
                                                        ?>
                                                        <a onclick="sendWink('<?php echo $member_id; ?>')" title="<?php echo language_code('DSP_SEND_WINK'); ?>">
                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" />
                                                        </a>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a onclick="redirectEditProfile('<?php echo language_code('DSP_UPDATE_PROFILE_BEFORE_SEND_WINK_MSG') ?>');" title="Edit Profile">
                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" />
                                                        </a>

                                                        <?php } ?>
                                                    </div>
                                                    <?php
                                            } // END My friends module Activation check condition 
                                            // check if one to one csetting is yes
                                            if ($check_chat_one_mode->setting_status == 'Y') {
                                                // send chat request if user is online
                                                if ($member_id != $user_id) { // if this member is not user itself
                                                    $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_online_user_table WHERE user_id=$member_id");

                                                    if ($check_online_user > 0) { // show chat icon if user is online
                                                        ?>
                                                        <div>
                                                            <a onclick="openChatRoom('<?php echo $member_id; ?>', 'send_request')">
                                                                <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>chat.jpg" border="0" />
                                                            </a>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </li>
                                        <li class="dsp_prof_view">
                                            <?php if ($check_date_tracker_mode->setting_status == 'Y') { // Check Skype mode Activated or not 
                                                ?> 
                                                <div>
                                                    <a onclick="viewMemberProfile(<?php echo $member_id ?>, 'date_tracker#<?php echo language_code('DSP_DATE_TRACKER_MESSAGE') ?>');">
                                                        <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>date_tracker.png" border="0" />
                                                    </a>
                                                </div>
                                                <?php } ?>
                                                <div>
                                                    <a onclick="viewMemberProfile(<?php echo $member_id ?>, 'report#<?php echo language_code('DSP_REPORT_PROFILE_MESSAGE'); ?>');">
                                                        <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>report.png" border="0" />
                                                    </a>
                                                </div>
                                                <div>
                                                    <a onclick="viewMemberProfile(<?php echo $member_id ?>, 'blocked#<?php echo language_code('DSP_ARE_U_SURE_TO_BLOCK_THIS_MEMBER'); ?>');">
                                                        <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>block_user.png" border="0" />
                                                    </a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </li>
                            <a onclick="slide_me('div_info')" >
                                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                                    <?php echo language_code('DSP_PROFILE_INFO'); ?>
                                    <div  id="div_info"  class="dsp_inv">
                                        <?php include_once('dspPartProfileInfo.php'); ?>
                                    </div>
                                </li>
                            </a>

                            <a onclick="slide_me('div_loc')">
                                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                                    <?php echo language_code('DSP_LOCATION'); ?>	
                                    <div  id="div_loc"  class="dsp_inv">
                                        <?php include_once('dspPartProfileLoc.php'); ?>
                                    </div>
                                </li>
                            </a>
                            <?php
                            if ($friendSetting == 'Y') {
                                ?>
                                <a onclick="<?php if ($totalFriends > 0) { ?>slide_me('div_friend')<?php } ?>">
                                    <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                                        <?php echo language_code('DSP_USER_FRIENDS') . " (" . $totalFriends . ")"; ?>
                                    </li>
                                </a>
                                <li id="div_friend" data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all ">
                                    <?php include_once('dspProfileMemberFriends.php'); ?>
                                </li>
                                <?php } 
                                if ($gallerySetting == 'Y') {
                                    ?>
                                    <a onclick="<?php if ($totalPhotos > 0) { ?>slide_me('div_photos')<?php } ?>"  >
                                        <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                                            <?php echo language_code('DSP_PHOTOS') . " (" . $totalPhotos . ")"; ?>
                                        </li>
                                    </a>
                                    <?php include_once('dspProfileUserPictures.php'); ?>
                                    <?php }
                                    if ($videSetting == 'Y') {
                                        ?>
                                        <a  onclick="<?php if ($totalVideo > 0) { ?>slide_me('div_video')<?php } ?>">
                                            <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                                                <?php echo language_code('DSP_MEDIA_HEADER_VIDEOS') . " (" . $totalVideo . ")"; ?>
                                            </li>
                                        </a>
                                        <li  id="div_video" data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all dsp_inv">
                                            <?php include_once('dspProfileVideos.php'); ?>
                                        </li>
                                        <?php }
                                        if ($audioSetting == 'Y') {
                                            ?>
                                            <a   onclick="<?php if ($totalSounds > 0) { ?>slide_me('div_audio')<?php } ?>"  >
                                                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                                                    <?php echo language_code('DSP_SOUND') . " (" . $totalSounds . ")"; ?>
                                                </li>
                                            </a>
                                            <li  id="div_audio" data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all dsp_inv">
                                                <?php include_once('dspProfileAudio.php'); ?>
                                            </li>
                                            <?php } 
                                            if ($blogSetting == 'Y') {
                                                ?>
                                                <a  onclick="<?php if ($totalBlogs > 0) { ?>slide_me('div_blog')<?php } ?>">
                                                    <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                                                        <?php echo language_code('DSP_MEDIA_HEADER_BLOGS') . " (" . $totalBlogs . ")"; ?>
                                                    </li>
                                                </a>
                                                <li  id="div_blog" data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all dsp_inv">
                                                    <?php include_once('dspProfileBlog.php'); ?>
                                                </li>
                                                <?php } ?>



                                            </ul>

                                        </div>



                                        <?php
            }   // ------------------------------------------------- End if Check in my friend list --------------------------------- //
        } else {
            // -------------------------------------- else  Privacy Setting for Everyone ------------------------------------------- // 



            $dsp_date_tracker_table = $wpdb->prefix . DSP_DATE_TRACKER_TABLE;



            $Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';

            $mem_id = isset($_REQUEST['mid']) ? $_REQUEST['mid'] : '';

            $users_table = $wpdb->prefix . DSP_USERS_TABLE;

            $current_date = date('Y-m-d h:i:s', time());

            if ($Action == 'date_tracker') {
                $session_id = $user_id;
                if ($session_id != $mem_id) {
                    $check_exist_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_date_tracker_table WHERE user_id='$session_id' AND member_id='$mem_id'");
                    if ($check_exist_member <= 0) {
                        $wpdb->query("INSERT INTO $dsp_date_tracker_table SET user_id='$session_id', member_id='$mem_id', tracked_date='$current_date'");
                    }
                }
            } //end if($Action == 'date_tracker')

            if ($Action == 'report') {
                $report_member_table = $wpdb->get_results("SELECT * FROM $users_table WHERE ID=$mem_id ");

                foreach ($report_member_table as $report_member) {
                    $mem_id = $report_member->ID;
                    $mem_login = $report_member->user_login;
                    $mem_email = $report_member->user_email;

                    $email = $wpdb->get_row("SELECT * FROM $users_table WHERE ID='$session_id'");
                    $user_email = $email->user_email;

                    $admin_email = get_option('admin_email');

                    $from = $user_email;

                    $headers = DSP_FROM . $from . "\r\n";

                    $subject = "Report partner profile";

                    $message = language_code('DSP_REPORT_PROFILE_TEXT_MESSAGE');

                    $message.=" <a href='" . add_query_arg(array('pid' => 3, 'mem_id' => $mem_id,
                        'pagetitle' => view_profile, 'view' => partner_profile), $root_link) . "' >  " . add_query_arg(array(
                        'pid' => 3, 'mem_id' => $mem_id, 'pagetitle' => view_profile,
                        'view' => partner_profile), $root_link) . "</a>";
                        wp_mail($admin_email, $subject, $message, $headers);
                    }
            }//end if($Action == 'report')
            ?>


            <div class="content-primary">	 
                <ul id="iGallery"  data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul user-profile">
                    <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                        <div class="dsp_pro_full_view">
                            <div class="profile_img_view">
                                <?php
                                $favt_mem = array();

                                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_id'");

                                foreach ($private_mem as $private) {
                                    $favt_mem[] = $private->favourite_user_id;
                                }

                                if ($exist_profile_details->make_private == 'Y') {
                                    if ($user_id != $member_id) {
                                        if (!in_array($user_id, $favt_mem)) {
                                            ?>
                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" />   
                                            <?php
                                        } else {
                                            ?>
                                            <a class="group1">
                                                <img src="<?php echo display_thumb2_members_partner_photo($member_id, $imagepath); ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" />
                                            </a>         

                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a class="group1" >
                                            <img src="<?php echo display_thumb2_members_partner_photo($member_id, $imagepath); ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" />
                                        </a>               
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a class="group1" >
                                        <img src="<?php echo display_thumb2_members_partner_photo($member_id, $imagepath); ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" />
                                    </a>
                                    <?php
                                }

                                unset($favt_mem);
                                ?>
                            </div>
                            <div class="dsp_on_lf_view">
                                <ul>
                                    <li>
                                        <strong>
                                            <?php echo $userName; ?>
                                        </strong>	
                                    </li>
                                    <li>
                                        <?php echo $myStatus; ?>
                                    </li>
                                    <!--
                                     NewTeam
                                     Don't show fav icon, message , send link and member profile for same logged in couple  -->
                                     <?php if ($user_id != $member_id) { ?>
                                     <li class="dsp_prof_view">
                                        <div style="width: 100%">
                                            <div>
                                                <a onclick="addFavourite('<?php echo $member_id; ?>')" title="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>">
                                                    <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>star.jpg" border="0" />
                                                </a>
                                            </div>
                                            <div> 

                                                <?php
                                                if (isset($check_my_friends_list) && $check_my_friends_list > 0) {
                                                    ?>
                                                    <a onclick="composeMessage('<?php echo $member_id ?>', 0)" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                        <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" />
                                                    </a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a onclick="composeMessage('<?php echo $member_id ?>', 0)" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                        <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" /></a>
                                                        <?php } //if($check_my_friends_list>0)    ?>

                                                    </div>


                                                <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not 
                                                    ?>
                                                    <div>
                                                        <?php
                                                        if ($check_user_profile_exist > 0) {  // check user dating profile exist or not 		  
                                                            ?>
                                                            <a onclick="sendWink('<?php echo $member_id; ?>')" title="<?php echo language_code('DSP_SEND_WINK'); ?>">
                                                                <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" />
                                                            </a>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <a onclick="redirectEditProfile('<?php echo language_code('DSP_UPDATE_PROFILE_BEFORE_SEND_WINK_MSG') ?>');" title="Edit Profile">
                                                                <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" />
                                                            </a>

                                                            <?php } ?>
                                                        </div>
                                                        <?php
                                                } // END My friends module Activation check condition 
                                                // check if one to one csetting is yes
                                                if ($check_chat_one_mode->setting_status == 'Y') {
                                                    // send chat request if user is online
                                                    if ($member_id != $user_id) { // if this member is not user itself
                                                        $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_online_user_table WHERE user_id=$member_id");

                                                        if ($check_online_user > 0) { // show chat icon if user is online
                                                            ?>
                                                            <div>
                                                                <a onclick="openChatRoom('<?php echo $member_id; ?>', 'send_request')">
                                                                    <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>chat.jpg" border="0" />
                                                                </a>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <div style="width: 100%">
                                                <?php if ($check_date_tracker_mode->setting_status == 'Y') { // Check Skype mode Activated or not 
                                                    ?> 
                                                    <div>
                                                        <a onclick="viewMemberProfile(<?php echo $member_id ?>, 'date_tracker#<?php echo language_code('DSP_DATE_TRACKER_MESSAGE') ?>');">
                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>date_tracker.png" border="0" />
                                                        </a>
                                                    </div>
                                                    <?php } ?>
                                                    <div>
                                                        <a onclick="viewMemberProfile(<?php echo $member_id ?>, 'report#<?php echo language_code('DSP_REPORT_PROFILE_MESSAGE'); ?>');">
                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>report.png" border="0" />
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a onclick="viewMemberProfile(<?php echo $member_id ?>, 'blocked#<?php echo language_code('DSP_ARE_U_SURE_TO_BLOCK_THIS_MEMBER'); ?>');">
                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>block_user.png" border="0" />
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>

                                </div>
                            </li>
                        </ul>
                        <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

                            <a onclick="slide_me('div_info')" >
                                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all accordion-item">
                                    <?php echo language_code('DSP_PROFILE_INFO'); ?>
                                    <div  id="div_info"  class="dsp_inv">
                                        <?php include_once('dspPartProfileInfo.php'); ?>
                                    </div>
                                </li>
                            </a>
                        </ul>
                        <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

                            <a onclick="slide_me('div_loc')">
                                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all accordion-item">
                                    <?php echo language_code('DSP_LOCATION'); ?>	
                                    <div  id="div_loc"  class="dsp_inv">
                                        <?php include_once('dspPartProfileLoc.php'); ?>
                                    </div>
                                </li>
                            </a>
                        </ul>
                        <?php
                        if ($friendSetting == 'Y') {
                            ?>
                            <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

                                <a  onclick="<?php if ($totalFriends > 0) { ?>slide_me('div_frnd')<?php } ?>">
                                    <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                                        <?php echo language_code('DSP_USER_FRIENDS') . " (" . $totalFriends . ")"; ?>
                                    </li>
                                </a>
                            </ul>
                            <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

                                <li  id="div_frnd" data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all dsp_inv">
                                    <?php include_once('dspProfileMemberFriends.php'); ?>
                                </li>
                            </ul>
                            <?php } 
                            if ($gallerySetting == 'Y') {
                                ?>
                                <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

                                    <a onclick="<?php if ($totalPhotos > 0) { ?>slide_me('div_photos')<?php } ?>"  >
                                        <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                                            <?php echo language_code('DSP_PHOTOS') . " (" . $totalPhotos . ")"; ?>
                                        </li>
                                    </a>
                                </ul>
                                <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

                                    <?php include_once('dspProfileUserPictures.php'); ?>
                                </ul>
                                <?php }
                                if ($videSetting == 'Y') {
                                    ?>
                                    <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

                                        <a  onclick="<?php if ($totalVideo > 0) { ?>slide_me('div_video')<?php } ?>">
                                            <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                                                <?php echo language_code('DSP_MEDIA_HEADER_VIDEOS') . " (" . $totalVideo . ")"; ?>
                                            </li>
                                        </a>
                                    </ul>
                                    <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

                                        <li  id="div_video" data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all dsp_inv">
                                            <?php include_once('dspProfileVideos.php'); ?>
                                        </li>
                                    </ul>
                                    <?php } 
                                    if ($audioSetting == 'Y') {
                                        ?>
                                        <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

                                            <a   onclick="<?php if ($totalSounds > 0) { ?>slide_me('div_audio')<?php } ?>"  >
                                                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                                                    <?php echo language_code('DSP_SOUND') . " (" . $totalSounds . ")"; ?>
                                                </li>
                                            </a>
                                        </ul>
                                        <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

                                            <li  id="div_audio" data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all dsp_inv">
                                                <?php include_once('dspProfileAudio.php'); ?>
                                            </li>
                                        </ul>
                                        <?php }
                                        if ($blogSetting == 'Y') {
                                            ?>
                                            <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

                                                <a  onclick="<?php if ($totalBlogs > 0) { ?>slide_me('div_blog')<?php } ?>">
                                                    <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                                                        <?php echo language_code('DSP_MEDIA_HEADER_BLOGS') . " (" . $totalBlogs . ")"; ?>
                                                    </li>
                                                </a>
                                            </ul>
                                            <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

                                                <li  id="div_blog" data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all dsp_inv">
                                                    <?php include_once('dspProfileBlog.php'); ?>
                                                </li>
                                            </ul>
                                            <?php } ?>
                                        </div>

                                        <?php } ?>


                                        <div id="BlogDetail" class="ui-body ui-body-d ui-corner-all" style="background-color: white;display: none;" >

                                            <a onclick="closeBlog()">
                                                <img style="position: absolute; top:3px; right:4px;width: 15px;height: 15px;" src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/close.gif' ?>" />
                                            </a>	
                                            <div id="bDetail"></div>
                                        </div>
                                        <div id="tadcontent" data-role="content" class="ui-content" role="main" >


                                            <div data-role="navbar" id="tadnavi" class="ui-navbar ui-mini" role="navigation" >
                                                <ul class="ui-grid-b">
                                                    <li class="ui-block-a">
                                                        <a id="tadclose" data-icon="delete" data-role="button" data-iconpos="top" href="" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-theme="c" title="" class="ui-btn ui-btn-inline ui-btn-icon-top ui-btn-up-c" data-inline="true"><span class="ui-btn-inner"><span class="ui-btn-text"></span><span class="ui-icon ui-icon-delete ui-icon-shadow">&nbsp;</span></span></a>
                                                    </li>
                                                    <li class="ui-block-b">
                                                        <a onclick="previousPic()" id="tadbk" data-icon="arrow-l" data-role="button" data-iconpos="top" href="" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-theme="c" title="" class="ui-btn ui-btn-inline ui-btn-icon-top ui-btn-up-c" data-inline="true"><span class="ui-btn-inner"><span class="ui-btn-text"></span><span class="ui-icon ui-icon-arrow-l ui-icon-shadow">&nbsp;</span></span></a>
                                                    </li>
                                                    <li class="ui-block-c">
                                                        <a onclick="nextPic()" id="tadnxt" data-icon="arrow-r" data-role="button" data-iconpos="top" href="" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-theme="c" title="" class="ui-btn ui-btn-inline ui-btn-icon-top ui-btn-active ui-btn-up-c" data-inline="true"><span class="ui-btn-inner"><span class="ui-btn-text"></span><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></span></a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div id="imageflipimg" style="display: none;width:260px;margin: auto;">
                                                <img style="position:relative;text-align: center" id="displayImg" src=""/>
                                            </div>



                                        </div>
                                        <?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
                                    </div>
                                    <?php
                                } else if ($check_exist_profile_details == 0) {
                                    ?>
                                    <div role="banner" class="ui-header ui-bar-a" data-role="header">
                                       <?php include_once("page_menu.php");?> 
                                       <span class="ui-title"></span>
                                   </div>
                                   <div class="ui-content" data-role="content">
                                    <div align="center"><?php echo language_code('DSP_NO_PROFILE_EXISTS_MESSAGE'); ?></div>
                                    <?php include_once('dspNotificationPopup.php'); // for notification pop up     ?>
                                </div>

                                <?php
                            } else {

                                $profile_status = $wpdb->get_row("SELECT * FROM $dsp_user_partner_profiles_table WHERE user_id = '$member_id'");
                                $pstatus = $profile_status->status_id;

                                if (($pstatus == 2) || ($pstatus == 3)) {
                                    $profile_deleted = $profile_status->reason_for_status;
                                }
                                ?>

                                <div role="banner" class="ui-header ui-bar-a" data-role="header">
                                   <?php include_once("page_menu.php");?> 
                                   <span class="ui-title"></span>
                               </div>
                               <div class="ui-content" data-role="content">
                                <div align="center">

                                    <?php if ($member_id == $user_id) {
                                        ?>
                                        <div align="center"><?php echo language_code('DSP_ADMIN_DELETE_PROFILE_MESSAGE'); ?><?php echo $profile_deleted ?></div>
                                        <?php
                                    } else {
                                        ?>
                                        <div align="center"><?php echo language_code('DSP_NO_PROFILE_EXISTS_MESSAGE'); ?></div>
                                        <?php } ?>
                                    </div>
                                    <?php include_once('dspNotificationPopup.php'); // for notification pop up      ?>
                                </div>

                                <?php } ?>
                                <?php include_once("dspLeftMenu.php"); ?>