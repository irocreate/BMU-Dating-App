<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

$current_user           = wp_get_current_user();
$session_id             = $current_user->ID;
$site                   = get_option('siteurl') . '/?pid=1';
$dateTimeFormat         = dsp_get_date_timezone();
$review_date            = date("Y-m-d");
$goback                 = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$dsp_counter_hits_table = $wpdb->prefix . DSP_COUNTER_HITS_TABLE;
$tbl_name               = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
if ($check_force_profile_mode->setting_status == "Y" && ! dsp_is_user_profile_exist()) {
    if ( ! is_user_logged_in()) {
        $redirectUrl = ($check_register_page_redirect_mode->setting_status == 'Y') ?
            $check_register_page_redirect_mode->setting_value :
            $redirectUrl = ROOT_LINK . "register";
    } else {
        $redirectUrl = ROOT_LINK . "edit";
    }
    ?>
    <script>
        window.location.href = "<?php echo $redirectUrl; ?>";
    </script>
    <?php
    exit();
}
if ( ! isset($view_profile_pageurl)) {
    if (is_user_logged_in()) {
        include_once(WP_DSP_ABSPATH . 'headers/view_profile_tab_header.php');
    } else {
        include_once(WP_DSP_ABSPATH . 'headers/guest_view_profile_header_tab.php');
    }
}
if (($user_id != $member_id)) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_counter_hits_table WHERE user_id='$user_id' AND member_id='$member_id' AND review_date='$review_date'");
    if (($count <= 0) && ($session_id != '')) {
        $wpdb->query("INSERT INTO $dsp_counter_hits_table SET user_id='$user_id', member_id='$member_id', review_date='$review_date' ");
    }
    dsp_add_notification($user_id, $member_id, 'view_profile');
}
$check_exist_profile_details = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE status_id=1 and country_id!=0 AND user_id = '$member_id'");
if ($check_exist_profile_details > 0) {
    $exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE status_id=1 AND user_id = '$member_id'");
// ------------------------------------START BLOCKED MEMBER -------------------------------------//
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
        <div style="color:#FF0000;" align="center"><strong><?php echo $msg_blocked ?></strong></div>
        <?php
    }
// ------------------------------------END  BLOCKED MEMBER -------------------------------------//
    // ----------------------------------Check member privacy Settings------------------------------------
    $check_user_privacy_settings = $wpdb->get_row("SELECT * FROM $dsp_user_privacy_table WHERE user_id='$member_id'");
    $check_my_friends_list       = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'");

    if ((isset($check_user_privacy_settings->view_my_profile) &&  $check_user_privacy_settings->view_my_profile == 'Y') && ($check_my_friends_list <= 0) && ($user_id != $member_id)) {   // check member is not in my friend list
        ?>
        <div class="box-border">
            <div class="box-pedding center">
                <div align="center"><?php echo language_code('DSP_NOT_MEMBER_FRIEND_MESSAGE'); ?></div>
                <div class=" dspdp-btn dspdp-btn-primary center add_friends">
                    <a href="<?php echo $root_link . "add_friend/user_id/" . $user_id . "/frnd_userid/" . $member_id . "/"; ?>
				   title="<?php echo language_code('DSP_ADD_TO_FRIENDS') ?>">
                    <?php echo language_code('DSP_ADD_TO_FRIENDS') ?></a>
                </div>

            </div>
        </div>
        <?php
    } else if ((isset($check_user_privacy_settings->view_my_profile) && $check_user_privacy_settings->view_my_profile == 'O') && ($user_id != $member_id)) {
        ?>
        <div class="box-border">
            <div class="box-pedding">
                <div align="center"><?php echo language_code('DSP_PROFILE_ONLY_ME'); ?></div>
            </div>
        </div>
        <?php
    } else {
// -------------------------------------- else  Privacy Setting for Everyone ------------------------------------------- //
        $dsp_album_id = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = $member_id");
        foreach ($dsp_album_id as $id) {
            $album_ids[] = $id->album_id;
        }
        if (isset($album_ids) && $album_ids != "") {
            $ids1 = implode(",", $album_ids);
// MEMBER TOTAL ADDED PHOTOS
            $total_member_photos = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_galleries_photos WHERE status_id=1 AND album_id IN ($ids1)");
            if ($total_member_photos == "") {
                $total_member_photos = 0;
            }
        }
// MEMBER TOTAL ADDED PHOTOS
        ?>
        <?php // ----------------------------- START  GENERAL ----------------------------------------// ?>
        <?php if (stristr($goback, 'search')): ?>
            <div class="back">
                <a href="<?php echo $goback; ?>"><?php echo language_code('DSP_BACK_TO_SEARCH_RESULT'); ?></a>
            </div>
        <?php endif; ?>
        <?php if ( ! empty($_SESSION['msg'])): ?>
            <div class="box-border mesagebox">
                <div class="box-pedding">
                    <span align="center" style="color:#458B00"><?php echo $_SESSION['msg']; ?></span>
                </div>
            </div>
            <?php
            unset($_SESSION['msg']);
        endif;
        ?>
        <div class="mesagebox">
            <span style="color:#458B00" id="message-box"></span>
        </div>
        <div class="comment_message">

        </div>
        <div

        <div class="box-border">
            <div class="box-pedding">
                <?php
                $dsp_date_tracker_table = $wpdb->prefix . DSP_DATE_TRACKER_TABLE;
                $Action                 = get('Action');
                $mem_id                 = get('mid');
                $users_table            = $wpdb->prefix . DSP_USERS_TABLE;
                $current_date           = date("Y-m-d H:i:s", time());
                if ($Action == 'date_tracker') {
                    $session_id = $current_user->ID;
                    if ($session_id != $mem_id) {
                        $check_exist_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_date_tracker_table WHERE user_id='$session_id' AND member_id='$mem_id' ");
                        if ($check_exist_member <= 0) {
                            /* echo 'Session_ID'.$session_id;
                              echo 'member_id'.$mem_id;
                              echo 'Current_Date'.$current_date; */
                            $wpdb->query("INSERT INTO $dsp_date_tracker_table SET user_id='$session_id', member_id='$mem_id', tracked_date='$current_date'");
                        }
                    }
                } //end if($Action == 'date_tracker')
                if ($Action == 'report') {
                    $report_member_table = $wpdb->get_results("SELECT * FROM $users_table WHERE ID=$mem_id ");
                    foreach ($report_member_table as $report_member) {
                        $mem_id      = $report_member->ID;
                        $mem_login   = $report_member->user_login;
                        $mem_email   = $report_member->user_email;
                        $email       = $wpdb->get_row("SELECT * FROM $users_table WHERE ID='$session_id'");
                        $user_email  = $email->user_email;
                        $admin_email = get_option('admin_email');
                        $from        = $user_email;
                        $headers     = DSP_FROM . $from . "\r\n";
                        $headers     .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                        $headers     .= "MIME-Version: 1.0" . "\n";
                        $subject     = "Report profile";
                        $message     = language_code('DSP_REPORT_PROFILE_TEXT_MESSAGE');
                        $message     .= apply_filters('dsp_get_message_based_on_gender', $mem_id);
                        wp_mail($admin_email, $subject, $message, $headers);

                    }
                }//end if($Action == 'report')

                if (isset($_POST['btn_report'])) {
                    global $wpdb;
                    $err_msg                = 0;
                    $table_name_report_user = $wpdb->prefix . 'dsp_reported_user';
                    $report_desc            = '';
                    if (isset($_POST['re-report-description']) && ! empty($_POST['re-report-description'])) {
                        $report_desc = $_POST['re-report-description'];
                    } else {
                        $message = language_code('DSP_PLEASE_ENTER_REASON');
                        $err_msg = 1;
                    }

                    $reported_by = isset($_POST['user-id']) ? $_POST['user-id'] : '';;
                    $reported_to = isset($_POST['member-id']) ? $_POST['member-id'] : '';

                    if ($err_msg == 0) {
                        $insert_report_id = $wpdb->query($wpdb->prepare(
                            "
							INSERT INTO $table_name_report_user(
							reported_by,reported_to, reason)
							values(%d, %d, %s)
							",
                            $reported_by,
                            $reported_to,
                            $report_desc

                        ));

                        if ($insert_report_id) {
                            $message = language_code('DSP_REPORT_SENT_SUCCESSFULLY');
                            $admin_email    = get_option('admin_email');
                            $subject        = "A user has been reported.";

                            $reported_to_user = get_userdata($reported_to);
                            $reported_to_user = $reported_to_user->user_login;

                            $reported_by_user = get_userdata($reported_by);
                            $reported_by_user = $reported_by_user->user_login;

                            $email_message  = "The user with username " . "$reported_to_user" . " has been reported by " . $reported_by_user;

                            $wpdating_email = Wpdating_email_template::get_instance();
                            $result         = $wpdating_email->send_mail($admin_email, $subject, $email_message);

                        } else {
                            $message = language_code('DSP_REPORT_NOT_SENT');
                            $err_msg = 1;
                        }
                    }
                    if ($err_msg == 0) {
                        $msg_comments = '<div class="dspdp-alert dspdp-alert-success">' . $message . '</div>';
                        echo "<script>window.onload = displayMessage('" . $msg_comments . "'); </script>";
                    } else {
                        $msg_comments = '<div class="dspdp-alert dspdp-alert-danger">' . $message . '</div>';
                        echo "<script>window.onload = displayMessage('" . $msg_comments . "'); </script>";
                    }

                }

                if (isset($_POST['comment-report'])) {
                    global $wpdb;
                    $error              = 0;
                    $reason             = "";
                    $table_name_comment = $wpdb->prefix . 'dsp_reported_comments';

                    if (isset($_POST['comment-report-description']) && ! empty($_POST['comment-report-description'])) {
                        $reason = $_POST['comment-report-description'];
                    } else {
                        $message = language_code('DSP_PLEASE_ENTER_REASON');
                        $error   = 1;
                    }

                    $comments_id      = (isset($_POST['comments-id'])) ? $_POST['comments-id'] : '';
                    $reported_user_id = $user_id;

                    if ($error == 0) {
                        $inserted_report_id = $wpdb->query($wpdb->prepare(
                            "
		                  INSERT INTO $table_name_comment
		                  ( comments_id, member_id, reported_user_id, reason )
		                  VALUES ( %d, %d, %d, %s )
	                    ",
                            $comments_id,
                            $member_id,
                            $reported_user_id,
                            $reason
                        ));
                        if ($inserted_report_id) {
                            $message = language_code('DSP_REPORT_SENT_SUCCESSFULLY');
                        } else {
                            $message = language_code('DSP_REPORT_NOT_SENT');
                            $error   = 1;
                        }
                    }

                    if ($error == 0) {
                        $msg_comments = '<div class="dspdp-alert dspdp-alert-success">' . $message . '</div>';
                        echo "<script>window.onload = displayMessage('" . $msg_comments . "'); </script>";
                    } else {
                        $msg_comments = '<div class="dspdp-alert dspdp-alert-danger">' . $message . '</div>';
                        echo "<script>window.onload = displayMessage('" . $msg_comments . "'); </script>";
                    }
                }


                ?>
                <div class="dsp-row dspdp-clearfix dspdp-spacer-hg">
                    <div class="box-profile-link dsp-user-img-container dsp-sm-3 dspdp-clearfix">
                        <div class="profile-image">
                            <div class="circle-image">
                                <?php
                                $favt_mem    = array();
                                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_id'");
                                foreach ($private_mem as $private) {
                                    $favt_mem[] = $private->favourite_user_id;
                                }
                                if ($exist_profile_details->make_private == 'Y') {
                                    ?>
                                    <?php if ($user_id != $member_id) { ?>
                                        <?php if ( ! in_array($user_id, $favt_mem)) { ?>
                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"
                                                 border="0" class="img"/>
                                        <?php } else {
                                            ?>
                                            <a class="group1"
                                               href="<?php echo display_members_original_photo($member_id,
                                                   $pluginpath); ?>">
                                                <img
                                                        src="<?php echo display_thumb2_members_photo($member_id,
                                                            $imagepath); ?>"
                                                        border="0" class="img"/></a>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a class="group1"
                                           href="<?php echo display_members_original_photo($member_id, $imagepath); ?>">
                                            <img
                                                    src="<?php echo display_thumb2_members_photo($member_id,
                                                        $imagepath); ?>"
                                                    border="0" class="img"/></a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <a class="group1"
                                       href="<?php echo display_members_original_photo($member_id, $imagepath); ?>"><img
                                                src="<?php echo display_thumb2_members_photo($member_id,
                                                    $imagepath); ?>"
                                                border="0" class="img"/></a>
                                <?php }
                                unset($favt_mem); ?>
                                <?php //********************************************START FAVOURITES ICONS **************************************** //   ?>
                            </div>
                        </div>

                        <?php if ($user_id != $member_id) {
                            $isFriend = apply_filters('dsp_is_friend', $member_id);
                            ?>
                            <div class="menus-profile dsp-user-info-container">
                                <ul class="box-4 dspdp-clearfix">
                                    <?php if ($check_my_friend_module->setting_status == 'Y' && ! $isFriend) { // Check My friend module Activated or not   ?>
                                        <li>
                                            <div class="fav_icons_border">
                                                <?php
                                                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                    if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                                        ?>
                                                        <a href="<?php echo $root_link . "add_friend/user_id/" . $user_id . "/frnd_userid/" . $member_id . "/"; ?>"
                                                           title="<?php echo language_code('DSP_ADD_TO_FRIENDS') ?>">
                                                            <span class="fa fa-plus-square"></span></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $root_link . "edit"; ?>"
                                                           title="<?php echo language_code('DSP_ADD_TO_FRIENDS') ?>"><span
                                                                    class="fa fa-plus-square"></span></a>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <a onClick="javascript:not_loggedin_message();"
                                                       title="<?php echo language_code('DSP_LOGIN'); ?>"><span
                                                                class="fa fa-plus-square"></span></a>
                                                <?php } ?>
                                            </div>
                                        </li>
                                    <?php } // END My friends module Activation check condition   ?>
                                    <li>
                                        <div class="fav_icons_border">
                                            <?php
                                            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                ?>
                                                <a href="<?php echo $root_link . "add_favorites/user_id/" . $user_id . "/fav_userid/" . $member_id . "/"; ?>"
                                                   title="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>">
                                                    <span class="fa fa-heart"></span></a>
                                            <?php } else { ?>
                                                <a onClick="javascript:not_loggedin_message();"
                                                   title="<?php echo language_code('DSP_LOGIN'); ?>"><span
                                                            class="fa fa-heart"></span></a>
                                            <?php } ?>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="fav_icons_border">
                                            <?php
                                            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                            if ($check_my_friends_list > 0) {
                                                ?>
                                                <a href="<?php echo $root_link . "email/compose/frnd_id/" . $member_id . "/Act/send_msg/"; ?>"
                                                   title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                    <span class="fa fa-envelope-o"></span>
                                                </a>
                                            <?php } else { ?>
                                                <a href="<?php echo $root_link . "email/compose/receive_id/" . $member_id . "/"; ?>"
                                                   title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                    <span class="fa fa-envelope-o"></span>
                                                </a>
                                            <?php } //if($check_my_friends_list>0)     ?>
                                            <?php } else { ?>
                                                <script type="text/javascript">
                                                </script>
                                                <a onClick="javascript:not_loggedin_message();"
                                                   title="<?php echo language_code('DSP_LOGIN'); ?>"><span
                                                            class="fa fa-envelope-o"></span></a>
                                            <?php } ?>
                                        </div>
                                    </li>
                                    <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not  ?>
                                        <li>
                                            <div class="fav_icons_border">
                                                <?php
                                                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                    if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                                        ?>
                                                        <a href='<?php echo $root_link . "view/send_wink_msg/receiver_id/" . $member_id . "/"; ?>'
                                                           title="<?php echo language_code('DSP_SEND_WINK'); ?>">
                                                            <span class="fa  fa-smile-o"></span></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo $root_link . "edit"; ?>"
                                                           title="Edit Profile"><span class="fa  fa-smile-o"></span></a>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <a onClick="javascript:not_loggedin_message();"
                                                       title="<?php echo language_code('DSP_LOGIN'); ?>"><span
                                                                class="fa  fa-smile-o"></span></a>
                                                <?php } ?>
                                            </div>
                                        </li>
                                    <?php } // END My friends module Activation check condition   ?>
                                    <?php if ($check_virtual_gifts_mode->setting_status == 'Y') {

                                        ?>
                                        <li>
                                            <div class="fav_icons_border">
                                                <a href="#dsp-popup" class="divinline"
                                                   title="<?php echo language_code('DSP_SEND_GIFT'); ?>"><span
                                                            class="fa  fa-gift"></span></a>
                                            </div>
                                        </li>
                                    <?php } ?>
                                    <?php

                                    $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $tbl_name WHERE user_id='$member_id'");

                                    $exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member_id'");
                                    $stealth_mode          = $exist_profile_details->stealth_mode;
                                    $check_online_user     = ($stealth_mode == "Y") ? '0' : $check_online_user;

                                    if ($check_online_user > 0) {
                                        $check_member_skype_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_skype_table WHERE user_id='$member_id'");
                                        if ($check_skype_mode->setting_status == 'Y') { // Check Skype mode Activated or not
                                            if ($check_member_skype_exist > 0) {
                                                ?>
                                                <li>
                                                    <div class="fav_icons_border">
                                                        <?php
                                                        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                            if (($check_member_skype_exist > 0) || ($member_id == $user_id)) {   //  member has Skype name
                                                                $skype_name                = $wpdb->get_row("SELECT * FROM $dsp_skype_table where user_id='$member_id'");
                                                                $check_member_skype_status = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_skype_table WHERE user_id='$member_id' AND skype_status='Y'");
                                                                if ($check_member_skype_status > 0) {
                                                                    $check_in_favourites = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_favourites_table where user_id='$member_id' AND favourite_user_id='$user_id'");
                                                                    if (($check_in_favourites > 0) || ($member_id == $user_id)) {
                                                                        ?>
                                                                        <?php if ($check_free_mode->setting_status == "Y" && $_SESSION['gender']) { ?>
                                                                            <div class="dsp_fav_link_border_user">
                                                                                <a href="skype:<?php echo $skype_name->skype_name ?>"
                                                                                   title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                            class="fa  fa-skype"></span></a>
                                                                            </div>
                                                                            <?php
                                                                        } else if ($check_free_mode->setting_status == "N") {  // free mode is off
                                                                            if ($check_free_trail_mode->setting_status == "Y") {
                                                                                // free trial mode is off
                                                                                $access_feature_name    = "Skype";
                                                                                $check_member_trial_msg = check_free_trial_feature($access_feature_name,
                                                                                    $user_id);
                                                                                if ($check_member_trial_msg == "Expired") {
                                                                                    ?>
                                                                                    <div
                                                                                            class="dsp_fav_link_border_user">
                                                                                        <a onClick="javascript:expired();"><img
                                                                                                    src="<?php echo $fav_icon_image_path ?>send-skype.jpg"
                                                                                                    title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"
                                                                                                    border="0"
                                                                                                    style="width:16px;"/></a>
                                                                                    </div>
                                                                                <?php } else if ($check_member_trial_msg == "Onlypremiumaccess") { ?>
                                                                                    <div
                                                                                            class="dsp_fav_link_border_user">
                                                                                        <a onClick="javascript:Onlypremiumaccess();"
                                                                                           title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                    class="fa  fa-skype"></span></a>
                                                                                    </div>
                                                                                    <?php
                                                                                } else if ($check_member_trial_msg == "Access") {
                                                                                    $access_recipient_feature_name  = "Skype";
                                                                                    $check_recipient_membership_msg = check_free_trial_feature($access_feature_name,
                                                                                        $member_id);
                                                                                    if ($check_recipient_membership_msg == "Expired") {
                                                                                        ?>
                                                                                        <div
                                                                                                class="dsp_fav_link_border_user">
                                                                                            <a onClick="javascript:Recipientexpired();"
                                                                                               title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                        class="fa  fa-skype"></span></a>
                                                                                        </div>
                                                                                    <?php } else if ($check_recipient_membership_msg == "Onlypremiumaccess") { ?>
                                                                                        <div
                                                                                                class="dsp_fav_link_border_user">
                                                                                            <a onClick="javascript:RecipientOnlypremiumaccess();"
                                                                                               title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                        class="fa  fa-skype"></span></a>
                                                                                        </div>
                                                                                    <?php } else if ($check_recipient_membership_msg == "Access") { ?>
                                                                                        <div
                                                                                                class="dsp_fav_link_border_user">
                                                                                            <a href="skype:<?php echo $skype_name->skype_name ?>"
                                                                                               title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                        class="fa  fa-skype"></span></a>
                                                                                        </div>
                                                                                        <?php
                                                                                    }
                                                                                } else {
                                                                                    ?>
                                                                                    <div
                                                                                            class="dsp_fav_link_border_user">
                                                                                        <a href="skype:<?php echo $skype_name->skype_name ?>"
                                                                                           title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                    class="fa  fa-skype"></span></a>
                                                                                    </div>
                                                                                <?php } ?>
                                                                                <?php
                                                                            } else if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off
                                                                                $access_feature_name  = "Skype";
                                                                                $check_membership_msg = check_membership($access_feature_name,
                                                                                    $user_id);
                                                                                if ($check_membership_msg == "Expired") {
                                                                                    ?>
                                                                                    <div
                                                                                            class="dsp_fav_link_border_user">
                                                                                        <a onClick="javascript:expired();"><img
                                                                                                    src="<?php echo $fav_icon_image_path ?>send-skype.jpg"
                                                                                                    title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"
                                                                                                    border="0"
                                                                                                    style="width:16px;"/></a>
                                                                                    </div>
                                                                                <?php } else if ($check_membership_msg == "Onlypremiumaccess") { ?>
                                                                                    <div
                                                                                            class="dsp_fav_link_border_user">
                                                                                        <a onClick="javascript:Onlypremiumaccess();"><img
                                                                                                    src="<?php echo $fav_icon_image_path ?>send-skype.jpg"
                                                                                                    title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"
                                                                                                    border="0"
                                                                                                    style="width:16px;"/></a>
                                                                                    </div>
                                                                                    <?php
                                                                                } else if ($check_membership_msg == "Access") {
                                                                                    $access_recipient_feature_name  = "Skype";
                                                                                    $check_recipient_membership_msg = check_membership($access_feature_name,
                                                                                        $member_id);
                                                                                    if ($check_recipient_membership_msg == "Expired") {
                                                                                        ?>
                                                                                        <div
                                                                                                class="dsp_fav_link_border_user">
                                                                                            <a onClick="javascript:Recipientexpired();"
                                                                                               title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                        class="fa  fa-skype"></span></a>
                                                                                        </div>
                                                                                    <?php } else if ($check_recipient_membership_msg == "Onlypremiumaccess") { ?>
                                                                                        <div
                                                                                                class="dsp_fav_link_border_user">
                                                                                            <a onClick="javascript:RecipientOnlypremiumaccess();"
                                                                                               title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                        class="fa  fa-skype"></span></a>
                                                                                        </div>
                                                                                    <?php } else if ($check_recipient_membership_msg == "Access") { ?>
                                                                                        <div
                                                                                                class="dsp_fav_link_border_user">
                                                                                            <a href="skype:<?php echo $skype_name->skype_name ?>"
                                                                                               title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                        class="fa  fa-skype"></span></a>
                                                                                        </div>
                                                                                        <?php
                                                                                    }
                                                                                } else {
                                                                                    ?>
                                                                                    <div
                                                                                            class="dsp_fav_link_border_user">
                                                                                        <a href="skype:<?php echo $skype_name->skype_name ?>"
                                                                                           title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                    class="fa  fa-skype"></span></a>
                                                                                    </div>
                                                                                <?php } ?>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }  // End if($check_in_favourites>0)
                                                                } else {
                                                                    ?>
                                                                    <?php if ($check_free_mode->setting_status == "Y" && $_SESSION['gender']) { ?>
                                                                        <div class="dsp_fav_link_border_user">
                                                                            <a href="skype:<?php echo $skype_name->skype_name ?>"
                                                                               title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                        class="fa  fa-skype"></span></a>
                                                                        </div>
                                                                        <?php
                                                                    } else if ($check_free_mode->setting_status == "N") {  // free mode is off
                                                                        if ($check_free_trail_mode->setting_status == "Y") {
                                                                            // free trial mode is off
                                                                            $access_feature_name    = "Skype";
                                                                            $check_member_trial_msg = check_free_trial_feature($access_feature_name,
                                                                                $user_id);
                                                                            if ($check_member_trial_msg == "Expired") {
                                                                                ?>
                                                                                <div class="dsp_fav_link_border_user">
                                                                                    <a onClick="javascript:expired();"
                                                                                       title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                class="fa  fa-skype"></span></a>
                                                                                </div>
                                                                            <?php } else if ($check_member_trial_msg == "Onlypremiumaccess") { ?>
                                                                                <div class="dsp_fav_link_border_user">
                                                                                    <a onClick="javascript:Onlypremiumaccess();"
                                                                                       title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                class="fa  fa-skype"></span></a>
                                                                                </div>
                                                                                <?php
                                                                            } else if ($check_member_trial_msg == "Access") {
                                                                                $access_recipient_feature_name  = "Skype";
                                                                                $check_recipient_membership_msg = check_free_trial_feature($access_feature_name,
                                                                                    $member_id);
                                                                                if ($check_recipient_membership_msg == "Expired") {
                                                                                    ?>
                                                                                    <div
                                                                                            class="dsp_fav_link_border_user">
                                                                                        <a onClick="javascript:Recipientexpired();"
                                                                                           title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                    class="fa  fa-skype"></span></a>
                                                                                    </div>
                                                                                <?php } else if ($check_recipient_membership_msg == "Onlypremiumaccess") { ?>
                                                                                    <div
                                                                                            class="dsp_fav_link_border_user">
                                                                                        <a onClick="javascript:RecipientOnlypremiumaccess();"
                                                                                           title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                    class="fa  fa-skype"></span></a>
                                                                                    </div>
                                                                                <?php } else if ($check_recipient_membership_msg == "Access") { ?>
                                                                                    <div
                                                                                            class="dsp_fav_link_border_user">
                                                                                        <a href="skype:<?php echo $skype_name->skype_name ?>"
                                                                                           title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                    class="fa  fa-skype"></span></a>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                            } else {
                                                                                ?>
                                                                                <div class="dsp_fav_link_border_user">
                                                                                    <a href="skype:<?php echo $skype_name->skype_name ?>"
                                                                                       title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                class="fa  fa-skype"></span></a>
                                                                                </div>
                                                                            <?php } ?>
                                                                            <?php
                                                                        } else if ($check_free_trail_mode->setting_status == "N") { // free trial mode is off
                                                                            $access_feature_name  = "Skype";
                                                                            $check_membership_msg = check_membership($access_feature_name,
                                                                                $user_id);
                                                                            if ($check_membership_msg == "Expired") {
                                                                                ?>
                                                                                <div class="dsp_fav_link_border_user">
                                                                                    <a onClick="javascript:expired();"
                                                                                       title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                class="fa  fa-skype"></span></a>
                                                                                </div>
                                                                            <?php } else if ($check_membership_msg == "Onlypremiumaccess") { ?>
                                                                                <div class="dsp_fav_link_border_user">
                                                                                    <a onClick="javascript:Onlypremiumaccess();"
                                                                                       title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                class="fa  fa-skype"></span></a>
                                                                                </div>
                                                                                <?php
                                                                            } else if ($check_membership_msg == "Access") {
                                                                                $access_recipient_feature_name  = "Skype";
                                                                                $check_recipient_membership_msg = check_membership($access_feature_name,
                                                                                    $member_id);
                                                                                if ($check_recipient_membership_msg == "Expired") {
                                                                                    ?>
                                                                                    <div
                                                                                            class="dsp_fav_link_border_user">
                                                                                        <a onClick="javascript:Recipientexpired();"
                                                                                           title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                    class="fa  fa-skype"></span></a>
                                                                                    </div>
                                                                                <?php } else if ($check_recipient_membership_msg == "Onlypremiumaccess") { ?>
                                                                                    <div
                                                                                            class="dsp_fav_link_border_user">
                                                                                        <a onClick="javascript:RecipientOnlypremiumaccess();"
                                                                                           title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                    class="fa  fa-skype"></span></a>
                                                                                    </div>
                                                                                <?php } else if ($check_recipient_membership_msg == "Access") { ?>
                                                                                    <div
                                                                                            class="dsp_fav_link_border_user">
                                                                                        <a href="skype:<?php echo $skype_name->skype_name ?>"
                                                                                           title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                    class="fa  fa-skype"></span></a>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                            } else {
                                                                                ?>
                                                                                <div class="dsp_fav_link_border_user">
                                                                                    <a href="skype:<?php echo $skype_name->skype_name ?>"
                                                                                       title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"><span
                                                                                                class="fa  fa-skype"></span></a>
                                                                                </div>
                                                                            <?php } ?>
                                                                            <?php
                                                                        }
                                                                    }
                                                                } // End if( $check_member_skype_status>0)
                                                            } // End if($check_member_skype_exist>0)
                                                            ?>
                                                        <?php } else { ?>
                                                            <div class="dsp_fav_link_border_user">
                                                                <a onClick="javascript:not_loggedin_message();"
                                                                   title="<?php echo language_code('DSP_LOGIN'); ?>">
																	<span
                                                                            title="<?php echo language_code('DSP_SEND_SKYPE'); ?>"
                                                                            class="fa  fa-skype"></span>
                                                                </a></div>
                                                        <?php } ?>
                                                    </div>
                                                </li>
                                                <?php
                                            }
                                        }
                                    } // END Skype mode Activation check condition
                                    ?>
                                    <?php
                                    if ($check_chat_one_mode->setting_status == 'Y') {
                                        if (is_user_logged_in()) {
                                            if ($member_id != $user_id) {
                                                $check_online_user     = $wpdb->get_var("SELECT COUNT(*) FROM $tbl_name WHERE user_id='$member_id'");
                                                $exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member_id'");
                                                $stealth_mode          = $exist_profile_details->stealth_mode;
                                                $check_online_user     = ($stealth_mode == "Y") ? '0' : $check_online_user;
                                                if ($check_online_user > 0) {
                                                    ?>
                                                    <li>
                                                        <div class="fav_icons_border">
                                                            <a href="<?php echo $root_link . "view/one_on_one_chat/mem_id/" . $member_id . "/action/send_request/"; ?>"
                                                               title="<?php echo language_code('DSP_SEND_CHAT_REQUEST'); ?>"><span
                                                                        class="fa  fa-comment"></span></a>
                                                        </div>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    ?>

                                </ul>

                            </div>
                        <?php } ?>

                    </div>
                    <div class="profle-detail dsp-profle-detail dsp-sm-8 dsp-md-offset-1">
                        <div class="heading-row">
                            <div class="heading-profile dspdp-h3 dspdp-box-title dspdp-pull-left">
                                <?php echo $displayed_member_name->display_name ?>
                            </div>
                            <div class="linkright-view-profile-page dspdp-pull-right dspdp-spacer-md">
                                <?php
                                $ImagePath = WPDATE_URL . "/images/";
                                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                    if ($member_id != $user_id) {
                                    ?>
                                    <?php if ($check_date_tracker_mode->setting_status == 'Y') { // Check Skype mode Activated or not   ?>
                                        <span
                                                onclick="date_tracker('<?php echo $member_id ?>', '<?php echo $exist_profile_details->gender ?>', '<?php echo $displayed_member_name->display_name ?>');"
                                                class="dsp_span_pointer  dspdp-btn dspdp-btn-primary dspdp-btn-xs dspdp-sm-block dspdp-spacer-xs"><?php echo language_code('DSP_DATE_TRACKER'); ?></span>
                                    <?php } ?>

                                    <!-- Start editing -->
                                    <a href="#report_for" id="re_report" class='report_mem'
                                       data-id="<?php echo $member_id ?>" data-title="<?php echo language_code('DSP_REPORT_USER'); ?>">
										<span class="dsp_span_pointer  dspdp-btn dspdp-btn-warning dspdp-btn-xs  dspdp-sm-block dspdp-spacer-xs">

										<?php echo language_code('DSP_REPORT_USER'); ?> </span></a>

                                    <div style='display:none'>
                                        <div id='report_for' style='padding:10px; background:#fff;'>
                                            <form action="" method="POST">
                                                <input type="hidden" name="user-id"
                                                       value="<?php echo $user_id; ?>">
                                                <input type="hidden" name="member-id"
                                                       value="<?php echo $member_id; ?>">
                                                <div class="dspdp-form-group">
                                                    <h5><?php echo language_code('DSP_REASON'); ?></h5>
                                                    <textarea class="dspdp-form-control"
                                                              name="re-report-description"
                                                              id="re-report-description" rows="3"></textarea>
                                                </div>
                                                <input type="submit" class="dspdp-btn dspdp-btn-default"
                                                       name="btn_report"
                                                       value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>">
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End Editing -->
                                    <span onclick="update_blocked_member();"
                                          class="dsp_span_pointer  dspdp-btn dspdp-btn-danger dspdp-btn-xs  dspdp-sm-block dspdp-spacer-xs"><?php echo language_code('DSP_BLOCK_USER'); ?></span>
                                    <form name="block_memberfrm" method="post">
                                        <input type="hidden" name="block_event" id="block_event" value=""/>
                                    </form>
                                <?php } } else { ?>
                                    <span><?php /* ?><a href="<?php echo get_site_url(); ?>" title="Login" ><?php echo language_code('DSP_BLOCK_MEMBER');?></a><?php */ ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if ($exist_profile_details->my_status != "") { ?>
                            <div class="my-status">
                                <p>
                                    <span><?php echo language_code('DSP_CURRENT_STATUS'); ?></span>
                                    <span><?php echo stripslashes($exist_profile_details->my_status); ?></span>
                                </p>
                            </div>
                        <?php } ?>
                        <div style="clear:both;" class="dspdp-hide"></div>
                        <div class="dspdp-row dspdp-list-sign dsp-content">
                            <ul class="profile-user-info dspdp-col-sm-6 dspdp-list dsp-user-detail-info">
                                <li>
                                    <span class="dspdp-bold"><?php echo language_code('DSP_I_AM'); ?></span>
                                    <?php echo get_gender($exist_profile_details->gender); ?>
                                </li>
                                <li>
                                    <span class="dspdp-bold"><?php echo language_code('DSP_SEEKING_A'); ?></span>
                                    <?php echo get_gender($exist_profile_details->seeking); ?>
                                </li>
                                <li>
                                    <span class="dspdp-bold"><?php echo language_code('DSP_AGE'); ?></span>
                                    <?php echo GetAge($exist_profile_details->age); ?>
                                </li>
                                <?php if ($exist_profile_details->country_id != 0) { ?>
                                    <li>
                                        <span class="dspdp-bold"><?php echo language_code('DSP_COUNTRY'); ?></span>
                                        <?php
                                        $country = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$exist_profile_details->country_id");
                                        echo $country->name;
                                        ?>
                                    </li>
                                <?php } ?>
                                <?php if ($exist_profile_details->state_id != 0) { ?>
                                    <li>
                                        <span class="dspdp-bold"><?php echo language_code('DSP_TEXT_STATE'); ?></span>
                                        <?php
                                        $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$exist_profile_details->state_id");
                                        echo $state_name->name;
                                        ?>
                                    </li>
                                <?php } ?>
                                <?php if ($exist_profile_details->city_id != 0) { ?>
                                    <li>
                                        <span class="dspdp-bold"><?php echo language_code('DSP_CITY'); ?></span>
                                        <?php
                                        // echo $exist_profile_details->city;
                                        $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$exist_profile_details->city_id");
                                        echo $city_name->name;
                                        ?>
                                    </li>
                                <?php } ?>
                                <?php if ($check_zipcode_mode->setting_status == 'Y') { ?>
                                    <li>
                                        <span class="dspdp-bold"><?php echo language_code('DSP_ZIP'); ?></span>
                                        <?php echo $exist_profile_details->zipcode ?>
                                    </li>
                                <?php } ?>
                            </ul>
                            <?php

                            function GetSign($date)
                            {
                                list($year, $month, $day) = explode("-", $date);
                                if (($month == 1 && $day > 20) || ($month == 2 && $day < 20)) {
                                    return "aquarius.png";
                                } else if (($month == 2 && $day > 18) || ($month == 3 && $day < 21)) {
                                    return "pisces.png";
                                } else if (($month == 3 && $day > 20) || ($month == 4 && $day < 21)) {
                                    return "aries.png";
                                } else if (($month == 4 && $day > 20) || ($month == 5 && $day < 22)) {
                                    return "taurus.png";
                                } else if (($month == 5 && $day > 21) || ($month == 6 && $day < 22)) {
                                    return "gemini.png";
                                } else if (($month == 6 && $day > 21) || ($month == 7 && $day < 24)) {
                                    return "cancer.png";
                                } else if (($month == 7 && $day > 23) || ($month == 8 && $day < 24)) {
                                    return "leo.png";
                                } else if (($month == 8 && $day > 23) || ($month == 9 && $day < 24)) {
                                    return "vergo.png";
                                } else if (($month == 9 && $day > 23) || ($month == 10 && $day < 24)) {
                                    return "libra.png";
                                } else if (($month == 10 && $day > 23) || ($month == 11 && $day < 23)) {
                                    return "scorpio.png";
                                } else if (($month == 11 && $day > 22) || ($month == 12 && $day < 23)) {
                                    return "sagittarius.png";
                                } else if (($month == 12 && $day > 22) || ($month == 1 && $day < 21)) {
                                    return "capricorn.png";
                                }
                            }

                            if ($check_astrological_signs_mode->setting_status == 'Y') {
                                $zodiac_sign = GetSign($exist_profile_details->age);
                                ?>
                                <div class="zodic-sign dspdp-col-sm-6 dspdp-text-right"><img
                                            title="<?php echo ucfirst(language_code('DSP_' . strtoupper(substr($zodiac_sign,
                                                    0, strpos($zodiac_sign, '.'))))); ?>"
                                            src="<?php echo $pluginpath . "images/zodiac-sign/" . $zodiac_sign; ?>"/>
                                </div>
                            <?php } ?></div>
                    </div>
                </div>
                <div class="other-details margin-btm-3">
                    <div class="heading-row dsp-section-title">
                        <div class="heading-profile dspdp-h4">
                            <?php echo $displayed_member_name->display_name ?> <?php echo language_code('DSP_OTHER_DETAILS'); ?>
                        </div>
                    </div>
                    <div class="dsp-row">
                        <?php
                        if ($exist_profile_details->make_private_profile != 1 || ($user_id == $member_id)) {
                            ?>
                            <ul class="dspdp-other-details dsp-user-detail-info">
                                <?php
                                $dsp_language_detail_table  = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
                                $dsp_session_language_table = $wpdb->prefix . DSP_SESSION_LANGUAGE_TABLE;
                                $lang_id                    = null; //default case where session is not set and not loggin
                                if (isset($_SESSION['default_lang'])) {
                                    $lang_id = $_SESSION['default_lang']; //session is set
                                } else {
                                    $adminLangId = $wpdb->get_var($wpdb->prepare("SELECT `language_id` FROM $dsp_language_detail_table where display_status = '%d'",
                                        1));
                                    if (is_user_logged_in()) {
                                        $userSessionLangId = $wpdb->get_var("SELECT  `language_id` FROM $dsp_session_language_table where user_id='" . get_current_user_id() . "' ");
                                        $lang_id           = isset($userSessionLangId) && ! empty($userSessionLangId) ? $userSessionLangId : $adminLangId; //logged  in and  session is not set
                                    } else {
                                        $lang_id = $adminLangId;
                                    }
                                }

                                $all_languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where language_id='" . $lang_id . "'");
                                $language_name = ! empty($all_languages->language_name) ? $all_languages->language_name : 'english';
                                if ($language_name == 'english') {
                                    $tableName1 = "dsp_profile_setup";
                                    $tableName  = "dsp_question_options";
                                } else {
                                    $tableName1 = "dsp_profile_setup_" . strtolower(trim(esc_sql(substr($language_name,
                                            0, 2))));
                                    $tableName  = "dsp_question_options_" . strtolower(trim(esc_sql(substr($language_name,
                                            0, 2))));
                                }
                                $dsp_question_options_table = $wpdb->prefix . $tableName;
                                $dsp_profile_setup_table    = $wpdb->prefix . $tableName1;

                                $exist_profile_options_details1 = $wpdb->get_results("SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE B.user_id ='$member_id' ORDER BY A.sort_order");
                                $prev                           = '';
                                $multi_values                   = array();
                                $multi_question                 = '';
                                foreach ($exist_profile_options_details1 as $profile_qu1) {

                                    $qId = $profile_qu1->profile_question_id;
                                    if ($prev != '' && $prev != $qId) {
                                        $prev = '';
                                        ?>
                                        <li class="dsp-md-6">
                                            <span><?php echo __($multi_question,'wpdating') ?>:</span>
                                            <div
                                                    class="details"><?php echo implode(', ',
                                                    $multi_values[$multi_question]); ?></div>
                                        </li>

                                        <?php
                                    }

                                    if ($profile_qu1->field_type_id == 1) {
                                        $question_name = $profile_qu1->question_name;
                                        //$option_value = $profile_qu1->option_value;
                                        $option_value = $wpdb->get_row("SELECT `option_value` FROM $dsp_question_options_table WHERE `question_option_id`=$profile_qu1->profile_question_option_id AND `question_id`=$profile_qu1->profile_question_id");
                                        if ( ! empty($option_value)) {
                                            $option_value = $option_value->option_value;
                                        } else {
                                            $option_value = $profile_qu1->option_value;
                                        }
                                        ?>
                                        <li class="dsp-md-6">
                                            <span><?php echo __($question_name, 'wpdating'); ?>:</span>
                                            <div class="details"><?php echo __($option_value, 'wpdating'); ?></div>
                                        </li>

                                        <?php

                                    } else if ($profile_qu1->field_type_id == 2) {
                                        $question_name = $profile_qu1->question_name;
                                        $option_value  = $profile_qu1->option_value;
                                        ?>
                                        <li class="li-fullwidth dsp-md-12">
                                            <span><?php echo $question_name ?>:</span>
                                            <div
                                                    class="details"><?php echo str_replace("\\", "",
                                                    $option_value); ?></div>
                                        </li>

                                        <?php

                                    } else if ($profile_qu1->field_type_id == 3) {
                                        $question_name = $profile_qu1->question_name;
                                        $option_value  = $wpdb->get_row("SELECT `option_value` FROM $dsp_question_options_table WHERE `question_option_id`=$profile_qu1->profile_question_option_id");

                                        if ( ! empty($option_value)) {
                                            $option_value = $option_value->option_value;
                                        } else {
                                            $option_value = $profile_qu1->option_value;
                                        }

                                        if ($prev == '' || $prev == $qId) {
                                            $multi_question                 = $question_name;
                                            $multi_values[$question_name][] = __($option_value ,'wpdating');
                                        }

                                        $prev = $qId;

                                    }
                                }

                                if ($prev != '') {
                                    $prev = '';
                                    ?>
                                    <li class="dsp-md-6">
                                        <span><?php echo __($multi_question, 'wpdating') ?>:</span>
                                        <div
                                                class="details"><?php echo implode(', ',
                                                $multi_values[$multi_question]); ?></div>
                                    </li>
                                    <?php
                                }
                                ?>

                                <?php /*
                            $exist_profile_options_details1 = $wpdb->get_results("SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE A.field_type_id =1 AND B.user_id ='$member_id' ORDER BY A.sort_order");

                            foreach ($exist_profile_options_details1 as $profile_qu1) {
                                $question_name = $profile_qu1->question_name;
                                //$option_value = $profile_qu1->option_value;

                                $option_value = $wpdb->get_row("SELECT `option_value` FROM $dsp_question_options_table WHERE `question_option_id`=$profile_qu1->profile_question_option_id AND `question_id`=$profile_qu1->profile_question_id");
                                if( !empty($option_value) )
                                    $option_value = $option_value->option_value;
                                else
                                    $option_value = $profile_qu1->option_value;
                                ?>
                                <li class="dsp-md-6">
                                    <span><?php echo $question_name ?>:</span>
                                    <div class="details"><?php echo $option_value ?></div>
                                </li>
                            <?php } ?>

                            <?php
                            $exist_profile_options_details2 = $wpdb->get_results("SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE A.field_type_id =2 AND B.user_id ='$member_id' ORDER BY A.sort_order");
                            foreach ($exist_profile_options_details2 as $profile_qu12) {
                                $question_name = $profile_qu12->question_name;
                                $option_value = $profile_qu12->option_value;
                                ?>
                                <li class="li-fullwidth dsp-md-12">
                                    <span><?php echo $question_name ?>:</span>
                                    <div class="details"><?php echo str_replace("\\", "", $option_value); ?></div>
                                </li>
                            <?php } ?>
                            <?php // multiple select box
                                $exist_profile_options_details2 = $wpdb->get_results("SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE A.field_type_id =3 AND B.user_id ='$member_id' ORDER BY A.sort_order");
                                echo apply_filters('dsp_display_profile_questions',$exist_profile_options_details2);
                            */ ?>

                                <li class="li-fullwidth dsp-md-12">
                                    <span><?php echo language_code('DSP_ABOUT_ME'); ?>:</span>
                                    <div
                                            class="details"><?php echo nl2br(stripslashes($exist_profile_details->about_me)); ?></div>
                                </li>
                                <li class="li-fullwidth dsp-md-12">
                                    <span><?php echo language_code('DSP_MY_INTEREST'); ?>:</span>
                                    <div
                                            class="details"><?php echo nl2br(stripslashes($exist_profile_details->my_interest)); ?></div>
                                </li>
                            </ul>
                            <?php
                        }
                        ?>

                    </div>
                    <?php
                    $msg_comments       = '';
                    $dsp_comments_table = $wpdb->prefix . DSP_USER_COMMENTS;
                    $new_comment        = isset($_REQUEST['new_comment']) ? esc_sql(sanitizeData(trim($_REQUEST['new_comment']),
                        'xss_clean')) : '';
                    $new_comment        = apply_filters('dsp_spam_filters', $new_comment);
                    if (isset($_POST['add_comment']) && $new_comment != "") {
                        $comment_date = date('Y-m-d H:i:s');
                        $status       = 0;
                        if ($check_approve_comments_status->setting_status == 'Y') {
                            $status = 1;
                        }
                        $values     = array(
                            'user_id'    => $user_id,
                            'member_id'  => $member_id,
                            'comments'   => stripslashes($new_comment),
                            'date_added' => $comment_date,
                            'status_id'  => $status
                        );
                        $insertedId = $wpdb->insert($dsp_comments_table, $values);
                        if ($insertedId) {
                            $msg_comments = language_code('DSP_COMMENT_SUCCESSFUL_TEXT');
                            echo "<script>window.onload = displayMessage('" . $msg_comments . "'); </script>";
                        }
                        $commentedUserInfo = get_userdata($member_id);

                        $mem_login  = $commentedUserInfo->user_login;
                        $mem_email  = $commentedUserInfo->user_email;
                        $email      = $wpdb->get_row("SELECT * FROM $users_table WHERE ID='$session_id'");
                        $user_email = $email->user_email;
                        $url        = apply_filters('dsp_get_message_based_on_gender', $member_id);

                        $commentUserName = $email->user_login;
                        $admin_email     = get_option('admin_email');
                        $from            = $user_email;
                        $headers         = language_code('DSP_FROM_TEXT') . $from . "\r\n";
                        $headers         = 'MIME-Version: 1.0' . "\r\n";
                        $headers         .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                        $subject         = "Commented on your profile";
                        $message         = language_code('DSP_COMMENT_ON_PROFILE_TEXT_MESSAGE');
                        $message         .= $url;
                        wp_mail($mem_email, $subject, $message, $headers);
                    }
                    ?>
                </div>
                <div class="fullwidth ">
                    <?php if ($check_comments_mode->setting_status == 'Y') {

                        ?>

                        <div class="heading-row">
                            <div
                                    class="heading-profile dspdp-h4"><?php echo language_code('DSP_USERS_COMMENTS') ?></div>
                        </div>
                        <ul class="comments-box">
                            <?php
                            $comment_chk = $wpdb->get_var("SELECT count(*) FROM `$dsp_comments_table` where member_id=$member_id and status_id=1 ");
                            if ($comment_chk != 0) {
                                $comment_list = $wpdb->get_results("SELECT * FROM `$dsp_comments_table` where member_id=$member_id and status_id=1  ORDER BY `date_added` DESC");
                                foreach ($comment_list as $comments) {
                                    $users_details = $wpdb->get_row("SELECT ID,user_login FROM $users_table  WHERE ID='$comments->user_id'");
                                    $check_gender  = $wpdb->get_var("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$comments->user_id'");

                                    ?>
                                    <li>
                                        <a href="<?php
                                        if ($check_gender != 'C') {
                                            echo $root_link . get_username($comments->user_id) . "/";
                                        } else {
                                            echo $root_link . get_username($comments->user_id) . "/my_profile/";
                                        }
                                        ?>">
                                            <?php echo $users_details->user_login; ?>
                                        </a>
                                        <span class="horizontal_separator"></span>
                                        <span><?php echo stripslashes($comments->comments); ?></span>
                                        <?php if ($member_id == $user_id) { ?>
                                            <span class="horizontal_separator"></span>
                                            <span id="<?php echo $comments->comments_id; ?>"
                                                  class="comment-delete-icon"><?php echo language_code('DSP_DELETE'); ?></span>
                                        <?php }

                                        ?>
                                        <p>
                                            <a class='inline_report_comment'
                                               data-id="<?php echo $comments->comments_id; ?>"
                                               data-title="<?php echo language_code('DSP_REPORT_THIS_COMMENT'); ?>"
                                               href="#inline_content"><span
                                                        class="dspdp-label dspdp-label-danger"><?php echo language_code('DSP_REPORT'); ?></span></a>
                                        </p>
                                        <!-- This contains the hidden content for inline calls -->
                                        <div style='display:none'>
                                            <div id='inline_content' style='padding:10px; background:#fff;'>
                                                <form action="" method="POST">
                                                    <input type="hidden" name="comments-id" id="comments-id" value="">
                                                    <input type="hidden" name="user-id"
                                                           value="<?php echo $comments->user_id; ?>">
                                                    <input type="hidden" name="member-id"
                                                           value="<?php echo $comments->member_id; ?>">
                                                    <div class="dspdp-form-group">
                                                        <h5><?php echo language_code('DSP_REASON'); ?></h5>
                                                        <textarea class="dspdp-form-control"
                                                                  name="comment-report-description"
                                                                  id="comment-report-description" rows="3"></textarea>
                                                    </div>
                                                    <input type="submit" class="dspdp-btn dspdp-btn-default"
                                                           name="comment-report"
                                                           value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>">
                                                </form>

                                            </div>
                                        </div>

                                        <span class="dspdp-seprator"></span>
                                    </li>

                                    <?php
                                }
                            }
                            ?>
                        </ul>

                        <div class="add-comment dspdp-spacer-lg">
                            <span><?php echo language_code('DSP_USERS_ADD_COMMENT') ?>:</span>
                            <?php
                            if (is_user_logged_in()) {
                                if ($check_free_mode->setting_status == "N") {  // free mode is off
                                    $access_feature_name = "Comments";
                                    if ($check_free_trail_mode->setting_status == "N") {
                                        $check_membership_msg = check_membership($access_feature_name, $user_id);
                                        if ($check_membership_msg == "Expired") {
                                            ?>
                                            <p class="error"><?php echo language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE_A') ?>
                                                <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"
                                                   class="error dspdp-btn dspdp-btn-default"
                                                   style="text-decoration:underline;"><?php echo language_code('DSP_UPGRADE_HERE') ?></a>
                                            </p>
                                        <?php } else if ($check_membership_msg == "Onlypremiumaccess") { ?>
                                            <p class="error"><?php echo language_code('DSP_PREMIUM_MEMBER_COMMENT_MESSAGE') ?>
                                                <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"
                                                   class="error dspdp-btn dspdp-btn-default"
                                                   style="text-decoration:underline;"><?php echo language_code('DSP_UPGRADE_HERE') ?></a>
                                            </p>
                                        <?php } else if ($check_membership_msg == "Access") {
                                            ?>
                                            <form method="post" class="dspdp-form">
                                                <input name="new_comment" class="dspdp-form-control dspdp-spacer"
                                                       maxlength="100" type="text"/> <input
                                                        class="btn-add dspdp-btn dspdp-btn-default"
                                                        value="<?php echo language_code('DSP_ADD') ?>"
                                                        name="add_comment"
                                                        type="submit"/>
                                            </form>
                                            <?php
                                        }
                                    } else {
                                        $check_member_trial_msg = check_free_trial_feature($access_feature_name,
                                            $user_id);
                                        if ($check_member_trial_msg == "Expired") {
                                            ?>
                                            <p class="error"><?php echo language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE_A') ?>
                                                <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"
                                                   class="error dspdp-btn dspdp-btn-default"
                                                   style="text-decoration:underline;"><?php echo language_code('DSP_UPGRADE_HERE') ?></a>
                                            </p>
                                        <?php } else if ($check_member_trial_msg == "Onlypremiumaccess") { ?>
                                            <p class="error"><?php echo language_code('DSP_PREMIUM_MEMBER_COMMENT_MESSAGE') ?>
                                                <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"
                                                   class="error dspdp-btn dspdp-btn-default"
                                                   style="text-decoration:underline;"><?php echo language_code('DSP_UPGRADE_HERE') ?></a>
                                            </p>
                                        <?php } else if ($check_member_trial_msg == "Access") {
                                            ?>
                                            <form method="post">
                                                <input name="new_comment" class="dspdp-form-control dspdp-spacer"
                                                       maxlength="100" type="text"/> <input
                                                        class="btn-add dspdp-btn dspdp-btn-default"
                                                        value="<?php echo language_code('DSP_ADD') ?>"
                                                        name="add_comment"
                                                        type="submit"/>
                                            </form>
                                            <?php
                                        }
                                    }
                                } else {
                                    if ($_SESSION['free_member']) {
                                        ?>
                                        <form method="post">
                                            <input name="new_comment" class="dspdp-form-control dspdp-spacer"
                                                   maxlength="100" type="text"/> <input
                                                    class="btn-add dspdp-btn dspdp-btn-default"
                                                    value="<?php echo language_code('DSP_ADD') ?>" name="add_comment"
                                                    type="submit"/>
                                        </form>
                                        <?php
                                    }
                                }
                            } else {
                                ?>
                                <p class="error"><?php echo language_code('DSP_NOT_LOGGEDIN__COMMENT_MESSAGE'); ?></p>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php
                    if ($check_virtual_gifts_mode->setting_status == 'Y') {
                        $dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;

                        if (isset($_REQUEST['image'])) {
                            $date = date('Y-m-d H:i:s');
                            if ($user_id != $member_id) {
                                $access_feature_name  = 'Virtual Gifts';
                                $check_membership_msg = check_membership($access_feature_name, $user_id);

                                if ($check_free_mode->setting_status == "Y" || $check_membership_msg == 'Access') {
                                    $result = $wpdb->query("insert into $dsp_user_virtual_gifts values('',$user_id,$member_id,'" . $_REQUEST['image'] . "','$date',0)");
                                    echo '<script>jQuery(document).ready(function(){ jQuery("#message-box").text("' . language_code('DSP_SENT') . '"); });</script>';
                                } elseif (($check_credit_mode->setting_status == 'Y') && (dsp_get_credit_of_current_user() > dsp_get_credit_setting_value('gifts_per_credit'))) {
                                    $result = $wpdb->query("insert into $dsp_user_virtual_gifts values('',$user_id,$member_id,'" . $_REQUEST['image'] . "','$date',0)");
                                    ///////// credit code////////
                                    $gift_per_credit = $wpdb->get_var("select gifts_per_credit from $dsp_credits_table");
                                    $wpdb->query("update $dsp_credits_usage_table set no_of_credits=no_of_credits-$gift_per_credit where user_id='$user_id'");
                                    $wpdb->query("update $dsp_credits_table set credit_used=credit_used+$gift_per_credit");
                                    ///////// credit code////////
                                    echo '<script>jQuery(document).ready(function(){ jQuery("#message-box").text("' . language_code('DSP_SENT') . '"); });</script>';
                                } /*
                                if  (
                                        $check_free_mode->setting_status == "N" &&
                                        $check_credit_mode->setting_status == 'Y' &&
                                        dsp_get_credit_of_current_user() > dsp_get_credit_setting_value('gifts_per_credit')  &&
                                        $check_membership_msg != 'Access'
                                    )
                                {
                                    $result = $wpdb->query("insert into $dsp_user_virtual_gifts values('',$user_id,$member_id,'" . $_REQUEST['image'] . "','$date',0)");
                                    ///////// credit code////////
                                    $gift_per_credit = $wpdb->get_var("select gifts_per_credit from $dsp_credits_table");
                                    $wpdb->query("update $dsp_credits_usage_table set no_of_credits=no_of_credits-$gift_per_credit where user_id='$user_id'");
                                    $wpdb->query("update $dsp_credits_table set credit_used=credit_used+$gift_per_credit");
                                    ///////// credit code////////

                                }
                                 */
                                else {
                                    echo '<p>You don\'t have enough credit to send email<br/><a href="' . $root_link . 'setting/upgrade_account/">  ' . language_code('DSP_CLICK_HERE_LINK') . ' </a>  to buy some credit</p> ';
                                }
                            }
                        }

                        ?>
                        <div class="virtual-gifts">
                            <div class="heading-row dsp-section-title">
                                <div
                                        class="heading-profile dspdp-h4"><?php echo language_code('DSP_VIRTUAL_GIFTS_MODE'); ?></div>
                            </div>
                            <ul class="virtual-gift-list">
                                <?php
                                $user_virtual_gifts = $wpdb->get_results("SELECT * FROM `$dsp_user_virtual_gifts` where status_id=1 and member_id='$member_id' order by date_added desc limit 9");
                                foreach ($user_virtual_gifts as $row_gift) {
                                    $gift_sendername = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '" . $row_gift->user_id . "'");
                                    ?>
                                    <li><img
                                                title="<?php echo language_code('DSP_GIFT_SENT_BY'); ?> <?php echo $gift_sendername ?>"
                                                src="<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/gifts/" . $row_gift->gift_image; ?>"
                                                style="width:67px; height:67px;"/>
                                        <?php if ($user_id == $member_id) { ?>
                                            <div class="gift_delete"
                                                 id="<?php echo $row_gift->gift_id; ?>"><?php echo language_code('DSP_DELETE') ?></div><?php } ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <style>
                            .picker img {
                                width: 67px;
                                height: 67px;
                            }
                        </style>
                        <script>
                            jQuery(document).ready(function (e) {
                                jQuery(".gift_delete").click(function () {
                                    var id = jQuery(this).attr('id');
                                    jQuery.ajax({
                                        url: "<?php echo plugins_url('dsp_dating/dsp_ajax_changes.php'); ?>?id=" + id,
                                        cache: false,
                                        success: function (html) {
                                            //alert(html);
                                            if (jQuery.trim(html) == 'done') {
                                                location.reload();
                                            }
                                        }
                                    });
                                });
                            });
                        </script>
                        <div style="display:none;">
                            <div class="picker" id="dsp-popup">
                                <?php
                                if ($user_id != $member_id) {
                                    $check_max_gifts = $wpdb->get_var("SELECT count(*) FROM `$dsp_user_virtual_gifts` where status_id=1 and member_id='$member_id'");
                                    $no_of_credits   = $wpdb->get_var("select no_of_credits from $dsp_credits_usage_table where user_id='$user_id'");
                                    $no_of_credits   = ! empty($no_of_credits) ? $no_of_credits : 0;
                                    ?>
                                    <?php
                                    if (is_user_logged_in()) {
                                        $access_feature_name = "Virtual Gifts";
                                        if ($check_free_trail_mode->setting_status == "N")      // all except free trial
                                        {
                                            $check_membership_msg = check_membership($access_feature_name, $user_id);
                                            if (($check_free_mode->setting_status == "Y" && $_SESSION['free_member']) || $check_membership_msg == 'Access' || (($check_credit_mode->setting_status == 'Y') && ($no_of_credits > dsp_get_credit_setting_value('gifts_per_credit')))) {
                                                if ($check_max_gifts < $check_virtual_gifts_mode->setting_value) { ?>

                                                    <form method="post">
                                                        <select name="image" style="display:none;"
                                                                class='image-picker show-html'>
                                                            <?php
                                                            $dsp_virtual_gifts = $wpdb->prefix . DSP_VIRTUAL_GIFT_TABLE;
                                                            $virtual_gifts     = $wpdb->get_results("select * from $dsp_virtual_gifts");
                                                            foreach ($virtual_gifts as $gift_row) {
                                                                ?>
                                                                <option
                                                                        data-img-src='<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/gifts/" . $gift_row->image; ?>'
                                                                        value='<?php echo $gift_row->image; ?>'>
                                                                    Page <?php echo $gift_row->id; ?>  </option>
                                                            <?php } ?>
                                                        </select>
                                                        <input type="submit" class="dspdp-btn dspdp-btn-default"
                                                               value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>"/>
                                                    </form>

                                                <?php } else {
                                                    ?>
                                                    <p class="error"><?php echo language_code('DSP_VIRTUAL_GIFTS_MAX_MSG'); ?></p>

                                                <?php }
                                            } else if ($check_membership_msg == "Onlypremiumaccess") { ?>

                                                <p class="error"><?php echo language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') ?>
                                                    <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"
                                                       class="error dspdp-btn dspdp-btn-default"
                                                       style="text-decoration:underline;"><?php echo language_code('DSP_UPGRADE_HERE') ?></a>
                                                </p>

                                            <?php } else { ?>
                                                <p class="error"><?php echo language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE_A') ?>
                                                    <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"
                                                       class="error dspdp-btn dspdp-btn-default"
                                                       style="text-decoration:underline;"><?php echo language_code('DSP_UPGRADE_HERE') ?></a>
                                                </p>
                                            <?php }
                                        } else  // free trial
                                        {
                                            $check_member_trial_msg = check_free_trial_feature($access_feature_name,
                                                $user_id);
                                            if ($check_member_trial_msg == "Expired") {
                                                ?>
                                                <p class="error"><?php echo language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE_A') ?>
                                                    <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"
                                                       class="error dspdp-btn dspdp-btn-default"
                                                       style="text-decoration:underline;"><?php echo language_code('DSP_UPGRADE_HERE') ?></a>
                                                </p>
                                            <?php } else if ($check_member_trial_msg == "Onlypremiumaccess") { ?>
                                                <p class="error"><?php echo language_code('DSP_PREMIUM_MEMBER_COMMENT_MESSAGE') ?>
                                                    <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"
                                                       class="error dspdp-btn dspdp-btn-default"
                                                       style="text-decoration:underline;"><?php echo language_code('DSP_UPGRADE_HERE') ?></a>
                                                </p>
                                            <?php } else if ($check_member_trial_msg == "Access") {
                                                ?>
                                                <?php

                                                if ($check_max_gifts < $check_virtual_gifts_mode->setting_value) {
                                                    ?>
                                                    <form method="post">
                                                        <select name="image" style="display:none;"
                                                                class='image-picker show-html'>
                                                            <?php
                                                            $dsp_virtual_gifts = $wpdb->prefix . DSP_VIRTUAL_GIFT_TABLE;
                                                            $virtual_gifts     = $wpdb->get_results("select * from $dsp_virtual_gifts");
                                                            foreach ($virtual_gifts as $gift_row) {
                                                                ?>
                                                                <option
                                                                        data-img-src='<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/gifts/" . $gift_row->image; ?>'
                                                                        value='<?php echo $gift_row->image; ?>'><?php echo language_code('DSP_PAGE') . "\t";
                                                                    echo $gift_row->id; ?>  </option>
                                                            <?php } ?>
                                                        </select>
                                                        <input type="submit" class="dspdp-btn dspdp-btn-default"
                                                               value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>"/>
                                                    </form>
                                                <?php } else { ?>
                                                    <p class="error"><?php echo language_code('DSP_VIRTUAL_GIFTS_MAX_MSG'); ?></p>
                                                <?php } ?>
                                                <?php
                                            }
                                        }
                                    } else        // not logged in
                                    {
                                        ?>
                                        <p class="error"><?php echo language_code('DSP_VIRTUAL_GIFTS_LOGIN_MSG'); ?></p>
                                        <?php
                                    }
                                } else        // both user same
                                {
                                    ?>
                                    <p class="error"><?php echo language_code('DSP_CANT_GIFT_SEND_MSG'); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                        <script>
                            (function ($) {
                                if ($().imagepicker) {
                                    $("select.image-picker").imagepicker({
                                        hide_select: false
                                    });
                                }
                            })(jQuery);
                        </script>
                    <?php } ?>
                </div>
                <div class="rate-profile margin-btm-3">
                    <?php
                    $mem_id                        = $member_id;
                    $dsp_general_settings_table    = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
                    $check_rate_profile_mode       = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'rate_profile'");
                    $dsp_rating_user_profile_table = $wpdb->prefix . DSP_RATING_USER_PROFILE_TABLE;
                    //$mem_id=isset($_REQUEST['mem_id']) ? $_REQUEST['mem_id'] : 0;
                    $check_member_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_rating_user_profile_table WHERE user_id = $user_id AND rate_user_id ='$mem_id'");
                    if (isset($_POST['submit'])) {
                        $rateval = $_POST['rate'];
                        // echo $_SERVER['HTTP_REFERER'];f
                        $wpdb->query("INSERT INTO $dsp_rating_user_profile_table SET rateval=$rateval,user_id = $user_id,rate_user_id ='$mem_id'");
                        $_SESSION['msg'] = language_code('DSP_RATE_SUCCESS');
                        echo "<script>window.location.href='" . $_SERVER['HTTP_REFERER'] . "'</script>";
                    }
                    if ($current_user->ID != '') {
                        if ($check_rate_profile_mode->setting_status == 'Y') {
                            $session_id = $current_user->ID;
                            if (($check_member_exist > 0)) {
                                $users        = $wpdb->get_row("SELECT * FROM $dsp_rating_user_profile_table WHERE  user_id = $session_id AND rate_user_id ='$mem_id'  ");
                                $user_id      = $users->user_id;
                                $rate_user_id = $users->rate_user_id;
                                $rateval      = $users->rateval;
                                $ratings      = $wpdb->get_row("SELECT Sum( rateval ) AS value, count( * ) AS count_user,round( (Sum( rateval )) / (count( * )), 1 ) AS avg,round( (Sum( rateval )) / (count( * )) ) AS avgr, max(rateval) max_rate FROM $dsp_rating_user_profile_table WHERE rate_user_id ='$mem_id' ");
                                //$users= $wpdb->get_row("SELECT * FROM $dsp_rating_user_profile_table WHERE  user_id = $user_id AND rate_user_id ='$mem_id'  ");
                                $value      = $ratings->value;
                                $count_user = $ratings->count_user;
                                $avgr       = $ratings->avgr;
                                $avg        = $ratings->avg;
                                $max_rate   = $ratings->max_rate;
                                ?>
                                <div class="title"><strong><?php echo language_code('DSP_RATE_RESULTS') ?></strong>
                                </div>
                                <ul class="rating-row-ul">
                                    <li><?php echo language_code('DSP_YOU_RATED') ?></li>
                                    <li>
                                        <?php
                                        if (isset($avg) && $avg == '') {
                                            for ($j = 1; $j <= 5; $j++) {
                                                ?>    <img
                                                        src="<?php echo $pluginpath . "images/blankstar.png" ?>"/>  <?php
                                            }
                                        } else {
                                            $fraction = $avg - floor($avg);
                                            $avg      = floor($avg);
                                            for ($j = 0; $j < $avg; $j++) {
                                                ?>
                                                <img src="<?php echo $pluginpath . "images/star.png" ?>"/>
                                                <?php
                                            }
                                            if ($fraction >= .5) {
                                                ?>
                                                <img src="<?php echo $pluginpath . "images/halfstar.png" ?>"/>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </li>
                                    <li><?php echo language_code('DSP_RATE_HIGHEST_SCORE') ?></li>
                                    <li>
                                        <?php
                                        if ($max_rate == '') {
                                            for ($j = 1; $j <= 5; $j++) {
                                                ?>    <img
                                                        src="<?php echo $pluginpath . "images/blankstar.png" ?>"/>  <?php
                                            }
                                        } else {
                                            for ($i = 1; $i <= $max_rate; $i++) {
                                                ?>    <img
                                                        src="<?php echo $pluginpath . "images/star.png" ?>"/>    <?php
                                            }
                                        }
                                        ?>
                                    </li>
                                </ul>
                                <ul class="rating-row-ul" style="border-top:none;">
                                    <li><?php echo language_code('DSP_RATE_AVG_SCORE') ?></li>
                                    <li><?php
                                        if (isset($avg) && $avg != '') {
                                            echo $avgr;
                                        } else {
                                            echo '0';
                                        }
                                        ?></li>
                                    <li><?php echo language_code('DSP_VOTES') ?></li>
                                    <li><?php echo $count_user ?></li>
                                </ul>
                                <?php
                            } else {
// if member
                                if ($mem_id == $user_id) {
                                    $users      = $wpdb->get_row("SELECT * FROM $dsp_rating_user_profile_table WHERE rate_user_id ='$user_id'  ");
                                    $ratings    = $wpdb->get_row("SELECT Sum( rateval ) AS value, count( * ) AS count_user,round( (Sum( rateval )) / (count( * )), 1 ) AS avg, max(rateval) max_rate FROM $dsp_rating_user_profile_table WHERE rate_user_id =$session_id ");
                                    $value      = $ratings->value;
                                    $count_user = $ratings->count_user;
                                    $avgr       = $ratings->avg;
                                    $avg        = $ratings->avg;
                                    $max_rate   = $ratings->max_rate;
                                    ?>
                                    <div class="own-profile-rate">
                                        <div class="title-rating">
                                            <strong><?php echo language_code('DSP_RATE_RESULTS') ?></strong></div>
                                        <ul class="rating-box">
                                            <li><?php echo language_code('DSP_YOU_RATED') ?></li>
                                            <li>
                                                <?php
                                                if (isset($avg) && $avg == '') {
                                                    for ($j = 1; $j <= 5; $j++) {
                                                        ?>    <img
                                                                src="<?php echo $pluginpath . "images/blankstar.png" ?>"/>  <?php
                                                    }
                                                } else {
                                                    $fraction = $avg - floor($avg);
                                                    $avg      = floor($avg);
                                                    for ($j = 0; $j < $avg; $j++) {
                                                        ?>
                                                        <img src="<?php echo $pluginpath . "images/star.png" ?>"/>
                                                        <?php
                                                    }
                                                    if ($fraction >= .5) {
                                                        ?>
                                                        <img src="<?php echo $pluginpath . "images/halfstar.png" ?>"/>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </li>
                                            <li><?php echo language_code('DSP_RATE_HIGHEST_SCORE') ?></li>
                                            <li>
                                                <?php
                                                if ($max_rate == '') {
                                                    for ($j = 1; $j <= 5; $j++) {
                                                        ?>    <img
                                                                src="<?php echo $pluginpath . "images/blankstar.png" ?>"/>  <?php
                                                    }
                                                } else {
                                                    for ($i = 1; $i <= $max_rate; $i++) {
                                                        ?>    <img
                                                                src="<?php echo $pluginpath . "images/star.png" ?>"/>    <?php
                                                    }
                                                }
                                                ?>
                                            </li>
                                        </ul>
                                        <ul style="border-top:none;" class="rating-box">
                                            <li><?php echo language_code('DSP_RATE_AVG_SCORE') ?></li>
                                            <li><?php
                                                if (isset($avg) && $avg != '') {
                                                    echo $avgr;
                                                } else {
                                                    echo '0';
                                                }
                                                ?></li>
                                            <li><?php echo language_code('DSP_VOTES') ?></li>
                                            <li><?php echo $count_user ?></li>
                                        </ul>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <form action="" method="post">
                                        <div class="title-rating">
                                            <strong><?php echo language_code('DSP_USER_RATE_PROFILE') ?></strong>
                                        </div>
                                        <?php
                                        $ratings    = $wpdb->get_row("SELECT Sum( rateval ) AS value, count( * ) AS count_user,round( (Sum( rateval )) / (count( * )), 1 ) AS avg, max(rateval) max_rate FROM $dsp_rating_user_profile_table WHERE rate_user_id =$mem_id ");
                                        $value      = $ratings->value;
                                        $count_user = $ratings->count_user;
                                        $avgr       = $ratings->avg;
                                        $avg        = $ratings->avg;
                                        ?>
                                        <ul class="empty-rate" style="margin-top:10px;">
                                            <li><?php echo language_code('DSP_RATING') ?></li>
                                            <li style="margin-top:26px;">
                                                <?php
                                                if (isset($avg) && $avg == '') {
                                                    for ($j = 1; $j <= 5; $j++) {
                                                        ?>    <img
                                                                src="<?php echo $pluginpath . "images/blankstar.png" ?>"/>  <?php
                                                    }
                                                } else {
                                                    $fraction = $avg - floor($avg);
                                                    $avg      = floor($avg);
                                                    for ($j = 0; $j < $avg; $j++) {
                                                        ?>
                                                        <img src="<?php echo $pluginpath . "images/star.png" ?>"/>
                                                        <?php
                                                    }
                                                    if ($fraction >= .5) {
                                                        ?>
                                                        <img src="<?php echo $pluginpath . "images/halfstar.png" ?>"/>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </li>
                                            <li><?php echo language_code('DSP_RATE_AVG_SCORE') ?><span><?php
                                                    if (isset($avg) && $avg != '') {
                                                        echo $avgr;
                                                    } else {
                                                        echo '0';
                                                    }
                                                    ?></span></li>
                                            <li><?php echo language_code('DSP_VOTES') ?>
                                                <span><?php echo $count_user; ?></span></li>
                                        </ul>
                                        </br></br></br>
                                        <ul class="rating-box">
                                            <li style=" width:6%"><img
                                                        src="<?php echo $pluginpath . "images/sad.png" ?>"/></li>
                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                <li>
                                                    <div style=" float:left; width:10px;"><input name="rate"
                                                                                                 type="radio"
                                                                                                 value="<?php echo $i; ?>"/>
                                                    </div>
                                                    <div
                                                            style="width: 30px; float: left; text-align: right; margin-left: 10px; line-height: 13px;"><?php echo $i; ?></div>
                                                </li>
                                            <?php } ?>
                                            <li style=" width:5%"><img
                                                        src="<?php echo $pluginpath . "images/smile.png" ?>"/></li>
                                        </ul>
                                        <div class="btn-row-rating"><input name="submit" type="submit" value="Rate"
                                                                           class="dspdp-btn dspdp-btn-default"/></div>
                                    </form>
                                    <?php
                                }
                            }
                            ?>
                            <?php
                        }
                    } else {
                        if ($check_rate_profile_mode->setting_status == 'Y') {
                            $check_member_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_rating_user_profile_table WHERE rate_user_id = $mem_id");
                            if (($check_member_exist > 0)) {
                                $users   = $wpdb->get_row("SELECT * FROM $dsp_rating_user_profile_table WHERE  rate_user_id = $mem_id  ");
                                $user_id = $users->user_id;
                                $rateval = $users->rateval;
                                $ratings = $wpdb->get_row("SELECT Sum( rateval ) AS value, count( * ) AS count_user,round( (Sum( rateval )) / (count( * )), 1 ) AS avg,round( (Sum( rateval )) / (count( * )) ) AS avgr, max(rateval) max_rate FROM $dsp_rating_user_profile_table WHERE rate_user_id ='$mem_id' ");
                                //$users= $wpdb->get_row("SELECT * FROM $dsp_rating_user_profile_table WHERE  user_id = $user_id AND rate_user_id ='$mem_id'  ");
                                $value      = $ratings->value;
                                $count_user = $ratings->count_user;
                                $avgr       = $ratings->avg;
                                $avg        = $ratings->avg;
                                $max_rate   = $ratings->max_rate;
                                ?>
                                <div class="title"><strong><?php echo language_code('DSP_RATE_RESULTS') ?></strong>
                                </div>
                                <ul class="rating-row-ul">
                                    <li><?php echo language_code('DSP_YOU_RATED') ?></li>
                                    <li>
                                        <?php
                                        if (isset($avg) && $avg == '') {
                                            for ($j = 1; $j <= 5; $j++) {
                                                ?>    <img
                                                        src="<?php echo $pluginpath . "images/blankstar.png" ?>"/>  <?php
                                            }
                                        } else {
                                            $fraction = $avg - floor($avg);
                                            $avg      = floor($avg);
                                            for ($j = 0; $j < $avg; $j++) {
                                                ?>
                                                <img src="<?php echo $pluginpath . "images/star.png" ?>"/>
                                                <?php
                                            }
                                            if ($fraction >= .5) {
                                                ?>
                                                <img src="<?php echo $pluginpath . "images/halfstar.png" ?>"/>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </li>
                                    <li><?php echo language_code('DSP_RATE_HIGHEST_SCORE') ?></li>
                                    <li>
                                        <?php
                                        if ($max_rate == '') {
                                            for ($j = 1; $j <= 5; $j++) {
                                                ?>    <img
                                                        src="<?php echo $pluginpath . "images/blankstar.png" ?>"/>  <?php
                                            }
                                        } else {
                                            for ($i = 1; $i <= $max_rate; $i++) {
                                                ?>    <img
                                                        src="<?php echo $pluginpath . "images/star.png" ?>"/>    <?php
                                            }
                                        }
                                        ?>
                                    </li>
                                </ul>
                                <ul class="rating-row-ul" style="border-top:none;">
                                    <li><?php echo language_code('DSP_RATE_AVG_SCORE') ?></li>
                                    <li><?php
                                        if (isset($avg) && $avg != '') {
                                            echo $avgr;
                                        } else {
                                            echo '0';
                                        }
                                        ?></li>
                                    <li><?php echo language_code('DSP_VOTES') ?></li>
                                    <li><?php echo $count_user ?></li>
                                </ul>
                            <?php } else { ?>
                                <div class="title"><strong><?php echo language_code('DSP_RATE_RESULTS') ?></strong>
                                </div>
                                <ul class="empty-rate" style="margin-top:10px;">
                                    <li><?php echo language_code('DSP_RATING') ?></li>
                                    <li>
                                        <?php
                                        for ($j = 1; $j <= 5; $j++) {
                                            ?>    <img src="<?php echo $pluginpath . "images/blankstar.png" ?>"/>  <?php
                                        }
                                        ?>
                                    </li>
                                    <li><?php echo language_code('DSP_RATE_AVG_SCORE') ?><span><?php
                                            if (isset($avg) && $avg != '') {
                                                echo $avgr;
                                            } else {
                                                echo '0';
                                            }
                                            ?></span></li>
                                    <li><?php echo language_code('DSP_VOTES') ?>
                                        <span><?php if (isset($count_user) && $count_user == '') {
                                                echo '0';
                                            } ?></span></li>
                                </ul>
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php // ------------------------------------------------- End if Check Privacy Settings --------------------------------- //   ?>
        <?php //----------------------------------------------- END PROFILE QUESTIONS -----------------------------------------------   ?>
        <?php
    }
} else {
    $profile_status = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member_id'");
    $pstatus        = $profile_status->status_id;
    if (($pstatus == 2) || ($pstatus == 3)) {
        $profile_deleted = $profile_status->reason_for_status;
    }
    ?>
    <div class="box-border">
        <div class="box-pedding">
            <div style="text-align:center;color:#FF0000;" class="box-page">
                <?php if ($member_id == $user_id) { ?>
                    <div align="center"><?php
                        $chk_profile_created = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE status_id=1 and country_id!= 0 AND user_id = '$member_id'");
                        if ($chk_profile_created > 0) {
                            echo language_code('DSP_ADMIN_DELETE_PROFILE_MESSAGE');
                        } else {
                            echo language_code('DSP_NO_PROFILE_EXISTS_MESSAGE');
                        }
                        ?>
                        <?php
                        if (isset($profile_deleted)) {
                            echo $profile_deleted;
                        }
                        ?>
                    </div>
                <?php } else { ?>
                    <div align="center"><?php echo language_code('DSP_NO_PROFILE_EXISTS_MESSAGE'); ?></div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
} ?>