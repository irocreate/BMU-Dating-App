<?php 
$profile_pageurl = get('pagetitle') != "" ? get('pagetitle') : '';
$message_template = isset($_REQUEST['message_template']) ? $_REQUEST['message_template'] : '';
$no_of_credits = $wpdb->get_var($wpdb->prepare("SELECT no_of_credits FROM $dsp_credits_usage_table WHERE user_id= '%d'",$user_id));
?>
<div class="line dsp-none">
    <?php // --------------------------------------- START WINKS MENU ----------------------------------------------------// ?>
    <?php if ($check_flirt_module->setting_status == 'Y') { ?>
        <div <?php if (($profile_pageurl == "view_winks")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <?php if ($count_wink_messages > 0) { ?>
                <a href="<?php echo $root_link . "home/view_winks/Act/R/"; ?>" style="color:#FF0000;"><?php echo language_code('DSP_MIDDLE_TAB_WINKS') ?>&nbsp;(<?php echo $count_wink_messages ?>)</a>
            <?php } else { ?>
                <a href="<?php echo $root_link . "home/view_winks/"; ?>"><?php echo language_code('DSP_MIDDLE_TAB_WINKS'); ?></a>
            <?php } ?>
        </div>
    <?php } ?>
    <?php // --------------------------------------- END WINKS MENU ----------------------------------------------------// ?>
    <?php // --------------------------------------- END FRIENDS MENU ----------------------------------------------------// ?>
    <?php if ($check_my_friend_module->setting_status == 'Y') { ?>
        <div <?php if (($profile_pageurl == "view_friends")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . "home/view_friends/"; ?>"><?php echo language_code('DSP_MIDDLE_TAB_FRIENDS'); ?></a></div>
    <?php } ?>
    <?php // --------------------------------------- END FRIENDS MENU ----------------------------------------------------// ?>
    <?php // --------------------------------------- END MY FAVOURITES MENU ----------------------------------------------// ?>
    <div <?php if (($profile_pageurl == "my_favorites")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "home/my_favorites/"; ?>"><?php echo language_code('DSP_MIDDLE_TAB_MY_FAVOURITES'); ?></a></div>
    <?php // --------------------------------------- END MY FAVOURITES MENU ------------------------------------------------// ?>
    <?php // --------------------------------------- END virtual gifts MENU ----------------------------------------------// ?>
    <?php if ($check_virtual_gifts_mode->setting_status == 'Y') { ?>
        <div <?php if (($profile_pageurl == "virtual_gifts")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <?php if ($count_friends_virtual_gifts > 0) { ?>
                <a href="<?php echo $root_link . "home/virtual_gifts/"; ?>" style="color:#FF0000;"><?php echo language_code('DSP_MIDDLE_TAB_VIRTUAL_GIFTS'); ?>&nbsp;(<?php echo $count_friends_virtual_gifts ?>)</a>
            <?php } else { ?>
                <a href="<?php echo $root_link . "home/virtual_gifts/"; ?>"><?php echo language_code('DSP_MIDDLE_TAB_VIRTUAL_GIFTS'); ?></a>
            <?php } ?>
        </div>
    <?php } ?>
    <?php // --------------------------------------- END virtual gifts MENU ------------------------------------------------// ?>
    <?php // --------------------------------------- END MY MATCHES MENU ----------------------------------------------// ?>
    <div <?php if (($profile_pageurl == "my_matches")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "home/my_matches/"; ?>"><?php echo language_code('DSP_MIDDLE_TAB_MACTHES'); ?></a></div>
    <?php // --------------------------------------- END MY MATCHES MENU ------------------------------------------------// ?>
    <?php // ----- Match Alert Menu ---- ?>
     <?php if ($check_match_alert_mode->setting_status == 'Y') { ?>
       <div <?php if (($profile_pageurl == "match_alert")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
           <a href="<?php echo $root_link . "home/match_alert/"; ?>"><?php echo language_code('DSP_SUBMENU_SETTINGS_MATCH_ALERTS'); ?></a></div>
   <?php } ?>
   <?php // ----- End Match Alert Menu ---- ?>
    <?php // ----------------------------------------- START ALERTS MENU ---------------------------------------------------// ?>
    <div <?php if ($profile_pageurl == "alerts") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?> style="color:#FF0000;">
        <?php if ($count_friends_request > 0) { ?>
            <a href="<?php echo $root_link . "home/alerts/"; ?>" style="color:#FF0000;"><?php echo language_code('DSP_MIDDLE_TAB_ALERTS'); ?>&nbsp;(<?php echo $count_friends_request ?>)</a>
        <?php } else { ?>
            <a href="<?php echo $root_link . "home/alerts/"; ?>"><?php echo language_code('DSP_MIDDLE_TAB_ALERTS'); ?></a>
        <?php } ?>
    </div>
    <?php // --------------------------------------- END ALERTS MENU ---------------------------------------------------// ?>
    <?php // --------------------------------------- END COMMENTS MENU ----------------------------------------------// ?>
    <?php if ($check_comments_mode->setting_status == 'Y') { ?>
        <div <?php if (($profile_pageurl == "comments")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <?php if ($check_approve_comments_status->setting_status == 'N') { ?>
                <?php if ($count_friends_comments > 0) { ?>
                    <a href="<?php echo $root_link . "home/comments/"; ?>" style="color:#FF0000;"><?php echo language_code('DSP_MIDDLE_TAB_COMMENTS'); ?>&nbsp;(<?php echo $count_friends_comments ?>)</a>
                <?php } else { ?>
                    <a href="<?php echo $root_link . "home/comments/"; ?>"><?php echo language_code('DSP_MIDDLE_TAB_COMMENTS'); ?></a>
                <?php } ?>
            <?php } else { ?>
                <a href="<?php echo $root_link . "home/comments/"; ?>"><?php echo language_code('DSP_MIDDLE_TAB_COMMENTS'); ?></a>
            <?php } ?></div>
    <?php } ?>
    <?php // --------------------------------------- END COMMENTS MENU ------------------------------------------------// ?>
    <?php // ----------------------------------------- START BLOCKED MENU ---------------------------------------------------// ?>
    <?php /* ?><div <?php if($profile_pageurl=="blocked") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
      <a href="<?php echo add_query_arg (array('pid' =>1,'pagetitle'=>'blocked'), $root_link);?>"><?php echo language_code('DSP_MIDDLE_TAB_BLOCKED');?></a>
      </div><?php */ ?>
    <?php // --------------------------------------- END BLOCKED MENU ---------------------------------------------------// ?>
    <?php // ----------------------------------------- START NEWS MENU ---------------------------------------------------// ?>
    <div <?php if ($profile_pageurl == "news_feed") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1 last" <?php } ?>>
        <a href="<?php echo $root_link . "home/news_feed/"; ?>"><?php echo language_code('DSP_MIDDLE_TAB_NEWS_FEED'); ?></a>
    </div>
    <?php // --------------------------------------- END NEWS MENU ---------------------------------------------------// ?>
    <div class="clr"></div>
</div>
</div>
<?php

//one to one chat pop up notification 
apply_filters('dsp_get_single_chat_popup_notification',$notification);


switch ($profile_pageurl) {
    case 'mypage':
        include_once(WP_DSP_ABSPATH . "members/loggedin/home/my_page/user_dsp_dating.php");
        break;
    
    case 'view_profile':
        include_once(WP_DSP_ABSPATH . "view_profile_setup.php");
        break;
    case 'send_wink_msg':
        include_once(WP_DSP_ABSPATH . "members/loggedin/home/my_page/user_dsp_dating.php");
        $access_feature_name = "Send Wink";
        if($_SESSION['free_member']){
                include_once(WP_DSP_ABSPATH . "send_wink_message.php");
        }else{
            if ($check_free_mode->setting_status == "N") {  // free mode is off 
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "send_wink_message.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            }
        } 
        break;
    case 'view_album':
        include_once(WP_DSP_ABSPATH . "dsp_view_user_albums.php");
        break;
    case 'view_friends':
        include_once(WP_DSP_ABSPATH . "dsp_view_member_friends.php");
        break;  
    case 'view_Pictures':
        include_once(WP_DSP_ABSPATH . "dsp_view_user_pictures.php");
        break;
        case 'view_Photos':
        include_once(WP_DSP_ABSPATH . "dsp_view_user_photos.php");
        break;
    case 'view_video':
        include_once(WP_DSP_ABSPATH . "dsp_view_member_videos.php");
        break;
    case 'view_audio':
        include_once(WP_DSP_ABSPATH . "dsp_view_member_audios.php");
        break;

    case 'view_winks':
        include_once(WP_DSP_ABSPATH . "members/loggedin/home/winks/dsp_view_winks.php");
        break;
    case 'view_friends':
        include_once(WP_DSP_ABSPATH . "dsp_view_friends.php");
        break;
    case 'my_favorites':
        include_once(WP_DSP_ABSPATH . "members/loggedin/home/favorites/dsp_my_favourites.php");
        break;
    case 'my_matches':
        include_once(WP_DSP_ABSPATH . "members/loggedin/home/matches/dsp_my_matches.php");
        break;
    case 'alerts':
        include_once(WP_DSP_ABSPATH . "members/loggedin/home/alerts/dsp_alerts_messages.php");
        break;
    case 'comments':
        if($_SESSION['free_member']){ 
            include_once(WP_DSP_ABSPATH . "members/loggedin/home/comments/dsp_view_comments.php");
        }else{
            $access_feature_name = 'Comments';
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
               include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "members/loggedin/home/comments/dsp_view_comments.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
               include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        }
        break;
    case 'virtual_gifts':
        $access_feature_name = 'Virtual Gifts';
        $check_membership_msg = check_membership($access_feature_name, $user_id);
        if ($check_virtual_gifts_mode->setting_status == 'N')
        {
            echo language_code('DSP_NO_RECORD_FOUND');
        }
        else
        {
            include_once(WP_DSP_ABSPATH . "members/loggedin/home/gifts/dsp_view_virtual_gifts.php");
        }
        /*
        else if ($check_membership_msg == "Access") 
        {
            include_once(WP_DSP_ABSPATH . "members/loggedin/home/gifts/dsp_view_virtual_gifts.php");
        } 
        else if ($check_membership_msg == "Onlypremiumaccess") 
        {
           include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
        }
         */
        break;
    case 'news_feed':
        include_once(WP_DSP_ABSPATH . "members/loggedin/home/news_feed/dsp_user_news_feed.php");
        break;

    case 'location':
        include_once(WP_DSP_ABSPATH . "members/loggedin/edit_my_location/edit_my_location.php");
        break;

    case 'match_alert':
        include_once(WP_DSP_ABSPATH . "members/loggedin/home/match_alerts/match_alert_settings.php");
        break;

    default:
        include_once(WP_DSP_ABSPATH . "members/loggedin/home/my_page/user_dsp_dating.php");
        break;
}
