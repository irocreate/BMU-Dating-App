<?php
 /*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ----------------------------------------- Display top menu header Menus ------------------------------ // 
$pageurl = get('pid');
$get_sender_id = get('sender_ID');
$request_Action = get('Act');
if (($request_Action == "R") && ($get_sender_id != "")) {
    $wpdb->query("UPDATE $dsp_user_emails_table  SET message_read='Y' WHERE sender_id = '$get_sender_id'");
} // End if 
$count_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE message_read='N' AND receiver_id=$user_id AND delete_message=0");
$count_friends_virtual_gifts = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_virtual_gifts WHERE member_id=$user_id AND status_id=0");
$uId = get_current_user_id();
$trendingStatusOn = apply_filters('dsp_check_trending_status',$uId);
$userProfileDetails = apply_filters('dsp_get_profile_details',$uId); 
$userProfileDetailsExist = $userProfileDetails != false  ? true : false;
?>
<nav class="uk-navbar-container" uk-navbar> 
  <div class="uk-navbar-left">  <ul class="uk-navbar-nav">
        <li <?php if (($pageurl == "1") || ($pageurl == "")) { ?>class="uk-active " <?php } else { ?>class="uk-parent" <?php } ?>>
            <?php
                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
            ?>
                <a href="<?php echo $root_link . "home/mypage/"; ?>" title="<?php echo language_code('DSP_MENU_MY_PAGE') ?>"><?php echo language_code('DSP_MENU_MY_PAGE') ?></a></li>
            <?php } else { ?>
                <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><?php echo language_code('DSP_MENU_MY_PAGE') ?></a></ul></div>
 <?php } ?>
        <?php if ($check_free_email_access_mode->setting_status == 'Y'):?>    
            <div <?php if ($pageurl == 14) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
                <?php
                    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                ?>
                <?php if ($count_messages == '0') { ?>
                    <a href="<?php echo $root_link . "email/inbox/"; ?>" title="<?php echo language_code('DSP_MIDDLE_TAB_MESSAGE') ?>"><?php echo language_code('DSP_MIDDLE_TAB_MESSAGE') ?></a>
                <?php } else { ?>
                    <a href="<?php echo $root_link . "email/inbox/"; ?>" title="<?php echo language_code('DSP_MIDDLE_TAB_MESSAGE') ?>"><?php echo language_code('DSP_MIDDLE_TAB_MESSAGE') ?>&nbsp;(<?php echo $count_messages ?>)</a>
                <?php } ?>
            </div>
            <?php } else { ?>
                <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><?php echo language_code('DSP_MIDDLE_TAB_MESSAGE') ?></a></nav>
            <?php } ?>
         <?php endif; ?>
        <div <?php if ($pageurl == 2) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <?php
            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN 
                ?>
                <a href="<?php echo $root_link . "edit/"; ?>" title="<?php echo language_code('DSP_MENU_EDIT_PROFILE') ?>"><?php echo language_code('DSP_MENU_EDIT_PROFILE') ?></a></div>
            <?php } else { ?>
                <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><?php echo language_code('DSP_MENU_EDIT_PROFILE') ?></a></div>
            <?php } ?>
        <?php if (($check_picture_gallery_mode->setting_status == 'Y') || ($check_audio_mode->setting_status == 'Y') || ($check_video_mode->setting_status == 'Y')) { ?>
        <div <?php if ($pageurl == 4) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <?php
                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
            ?>
            <a href="<?php echo $root_link . "media/photo/"; ?>" title="<?php echo language_code('DSP_MENU_ADD_MEDIA') ?>"><?php echo language_code('DSP_MENU_ADD_MEDIA') ?></a></div>
            <?php } else { ?>
                <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><?php echo language_code('DSP_MENU_ADD_MEDIA') ?></a></div>
            <?php } ?>
            <?php } ?>
        <?php if ($check_chat_mode->setting_status == 'Y') { ?>
        <div <?php if ($pageurl == 15) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <?php
                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
            ?>
                <a href="<?php echo $root_link . "chat/"; ?>" title="<?php echo language_code('DSP_MENU_CHAT') ?>"><?php echo language_code('DSP_MENU_CHAT') ?></a></div>
            <?php } else { ?>
                <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><?php echo language_code('DSP_MENU_CHAT') ?></a></div>
            <?php } ?>
        <?php } ?>
        <?php
            // if member is login then this menu will be display 
            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
        ?>
        <div <?php if ($pageurl == 5) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . "search/basic_search/"; ?>" title="<?php echo language_code('DSP_MENU_SEARCH') ?>"><?php echo language_code('DSP_MENU_SEARCH') ?></a></div>
        <?php } ?>
        <?php
            // if member is login then this menu will be display 
            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
        ?>
        <div <?php if ($pageurl == 6) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . "setting/notification/"; ?>" title="<?php echo language_code('DSP_MENU_SETTINGS') ?>"><?php echo language_code('DSP_MENU_SETTINGS') ?></a></div>
        <?php } ?>
        <?php
            // if member is login then this menu will be display 
            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
        ?>
        <div <?php if ($pageurl == 13) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
                <?php if (is_user_logged_in()) {  // CHECK MEMBER LOGIN  ?>
                    <a href="<?php echo $root_link . "extras/viewed_me/"; ?>" title="<?php echo language_code('DSP_MENU_EXTRAS') ?>"><?php echo language_code('DSP_MENU_EXTRAS') ?></a>
                <?php } else { ?>
                    <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><?php echo language_code('DSP_MENU_EXTRAS') ?></a>
                <?php } ?>
        </div>
        <div <?php if ($pageurl == 10) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . "online_members/"; ?>" title="<?php echo language_code('DSP_GUEST_HEADER_ONLINE') ?>"><?php echo language_code('DSP_GUEST_HEADER_ONLINE') ?></a></div>
        <?php } ?>
        <div <?php if ($pageurl == 16) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1 last" <?php } ?>>
            <a href="<?php echo $root_link . "help/"; ?>" title="<?php echo language_code('DSP_GUEST_HEADER_HELP') ?>"><?php echo language_code('DSP_GUEST_HEADER_HELP') ?></a>
        </div>
    <div class="clr"></div>
</div>


<?php if (is_user_logged_in() && ($pageurl == 17 )) { echo '</div>';  } //closed a div on stories ?>
<?php

$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$current_user->ID'");
$gender = isset($exist_profile_details) ? $exist_profile_details->gender : ' ';
if ($pageurl == 1) {
    include_once(WP_DSP_ABSPATH . "headers/mypage_header.php");
} else if ($pageurl == 2) { 
    include_once(WP_DSP_ABSPATH . "headers/edit_profile_header.php");
} else if ($pageurl == 3 ) {
    include_once(WP_DSP_ABSPATH . "headers/view_profile_header.php");
} else if ($pageurl == 4) {
    include_once(WP_DSP_ABSPATH . "headers/add_photos_header.php");
} else if ($pageurl == 5) {
    include_once(WP_DSP_ABSPATH . "headers/user_search_header.php");
} else if ($pageurl == 6) {
    include_once(WP_DSP_ABSPATH . "headers/user_settings_header.php");
} else if ($pageurl == 7) {
    include_once(WP_DSP_ABSPATH . "add_to_favourites.php");
} else if ($pageurl == 8) {
    include_once(WP_DSP_ABSPATH . "add_as_friend.php");
} else if ($pageurl == 9) {
    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
} else if ($pageurl == 10) {
    if ($check_force_profile_mode->setting_status == "Y") {
        // if force profile mode is OFF
        $check_force_profile_msg = check_free_force_profile_feature($user_id);
        if ($check_force_profile_msg == "Approved") {
            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
        } else if ($check_force_profile_msg == "Access") {
            include_once(WP_DSP_ABSPATH . "members/loggedin/online/dsp_online_other_users.php");
        } else if ($check_force_profile_msg == "NoAccess") {
            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
        } else if ($check_force_profile_msg == "Expired") {
            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
        } else if ($check_force_profile_msg == "Onlypremiumaccess") {
            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
        }
    } else { 
        include_once(WP_DSP_ABSPATH . "members/loggedin/online/dsp_online_other_users.php");
    }
} else if ($pageurl == 11) {
    include_once(WP_DSP_ABSPATH . "dsp_fetch_geography.php");
}else if ($pageurl == 13 ) {
    include_once(WP_DSP_ABSPATH . "headers/extras_header.php");
} else if ($pageurl == 14) {
    $access_feature_name = "Access Email";
    $check_membership_msg = check_membership($access_feature_name, $user_id);
    $addDiv = $check_free_email_access_mode->setting_status == 'N' ? true : false;
    $check_free_email_access_mode->setting_status == 'Y' ? 
        include_once(WP_DSP_ABSPATH . "user_dsp_my_email.php") : 
        include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
} else if ($pageurl == 16) {
    include_once(WP_DSP_ABSPATH . "dsp_help.php");
} else if ($pageurl == 17) {
    include_once(WP_DSP_ABSPATH . "dsp_guest_stories.php");
} else if ($pageurl == 15) {
    $access_feature_name = "Group Chat";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {
            // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "user_dsp_chat.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        } else {
            include_once(WP_DSP_ABSPATH . "user_dsp_chat.php");
        }
    } else {
                if($_SESSION['free_member']){
                    include_once(WP_DSP_ABSPATH . "user_dsp_chat.php");
                }else{
                    if ($check_force_profile_mode->setting_status == "Y") {
                        // if force profile mode is OFF
                        $check_force_profile_msg = check_free_force_profile_feature($user_id);
                        if ($check_force_profile_msg == "Approved") {
                            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                        } else if ($check_force_profile_msg == "Access") { 
                            include_once(WP_DSP_ABSPATH . "user_dsp_chat.php");
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
                            include_once(WP_DSP_ABSPATH . "user_dsp_chat.php");
                        } else if ($check_membership_msg == "Onlypremiumaccess") {
                            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                        }
                        
                    }
                }
            }
} else {  
    include_once(WP_DSP_ABSPATH . "headers/mypage_header.php");
}
?>
