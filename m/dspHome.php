<?php
//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">
//<link href="mobile.css" rel="stylesheet" type="text/css">-->

include("../../../../wp-config.php");
/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include_once("dspGetSite.php"); // this page contains the function cleanUrl that will cleasn the url


$dsp_user_emails_table = $wpdb->prefix . DSP_EMAILS_TABLE;
$dsp_comments_table = $wpdb->prefix . DSP_USER_COMMENTS;
$dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;
$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;

$url = get_bloginfo('url');
$siteUrl = cleanUrl($url);


$user_id = $_REQUEST['user_id'];


// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');

$user_id = $_REQUEST['user_id'];


$count_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE message_read='N' AND receiver_id=$user_id AND delete_message=0");
$count_friends_virtual_gifts = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_virtual_gifts WHERE member_id=$user_id AND status_id=0");
$count_wink_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_member_winks_table WHERE wink_read='N' AND receiver_id=$user_id");
$count_friends_request = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid=$user_id AND approved_status='N'");
$count_friends_comments = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_comments_table WHERE member_id=$user_id AND status_id=0");

$totalAlertCount = $count_friends_virtual_gifts + $count_wink_messages + $count_friends_request + $count_friends_comments;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
?>



<div data-role="header" class="ui-header ui-bar-a" role="banner">
    <a  href="#" id="btn_logout" class="ui-btn-left ui-btn ui-shadow ui-btn-corner-all ui-btn-up-a" data-corners="true" data-shadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_LOGOUT'); ?></span>
        </span>
    </a>
    <h1 class="ui-title" role="heading" aria-level="1"><?php echo $siteUrl; ?></h1>
    <a data-icon="check" onclick="callHomePage()" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a"><span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_RELOAD'); ?></span>
        </span>
    </a>
</div>


<div data-role="content" class="ui-content" role="main" >

    <ul class="dsp_home_full">



        <a href="dsp_view_status.html"   class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >
                <img    src="images/status.png" />
                <span><?php echo language_code('DSP_TITLE_STATUS'); ?></span>	
            </li>
        </a>

        <a href="dsp_view_message.html"    class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d">

                <img   src="images/messages.png" />
<?php if ($count_messages > 0) { ?>
                            <!--<div class="no_div"><?php echo $count_messages; ?></div>-->

                    <span>
    <?php
    echo '<i class="mesg-count">' . $count_messages . "</i>&nbsp;";

    if ($count_messages > 1) {
        echo language_code('DSP_MESSAGES');
    } else {
        echo language_code('DSP_MESSAGE');
    }
    ?>
                    </span>

<?php } else { ?>

                    <span><?php echo language_code('DSP_MESSAGE'); ?></span>
<?php } ?>
            </li>
        </a>


        <a href="dsp_alert.html" class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d">

                <img   src="images/alerts.png" />
<?php if ($totalAlertCount > 0) { ?>

                    <span>
    <?php
    echo '<i class="mesg-count">' . $totalAlertCount . "</i>&nbsp;";

    if ($totalAlertCount > 1) {
        echo language_code('DSP_ALERTS');
    } else {
        echo language_code('DSP_ALERT');
    }
    ?>
                    </span>

<?php } else { ?>

                    <span><?php echo language_code('DSP_ALERTS'); ?></span>
<?php } ?>	
            </li>
        </a>



        <a href="dsp_online.html" class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d">	 	
                <img  src="images/online.png" />

                <span><?php echo language_code('DSP_GUEST_HEADER_ONLINE'); ?></span>
            </li>
        </a>
<?php
$chatSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='chat_mode'");
if ($chatSetting == 'Y') {
    ?>
            <a href="dsp_chat.html"  class="mam_text_dec"  style="color:black;">
                <li class="dsp_home_icon ui-body-d" >
                    <img  src="images/chat.png"/>
                    <span><?php echo language_code('DSP_MENU_CHAT'); ?></span>	
                </li>
            </a>
<?php } ?>
        <a href="dsp_edit_profile.html" class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >
                <img  src="images/edit-profile.png" />
                <span><?php echo language_code('DSP_MENU_EDIT_PROFILE'); ?></span>
            </li>
        </a>



        <a onclick="viewProfile(<?php echo $user_id ?>, 'my_profile')" class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >	
                <img   src="images/profile.png" />
                <span><?php echo language_code('DSP_TOOLS_HEADER_PROFILE'); ?></span>	
            </li>	
        </a>
<?php
$trackerSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='date_tracker'");
if ($trackerSetting == 'Y') {
    ?>
        <a href="dsp_date_tracker.html"  class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >
                <img   src="images/datetracker.png" />
                <span><?php echo language_code('DSP_TRACKER'); ?></span>
            </li>
        </a>			
<?php } ?>
        <a href="dsp_my_favorite.html"   class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >
                <img   src="images/favorites.png" />
                <span><?php echo language_code('DSP_USER_FAVOURITES'); ?></span>
            </li>
        </a>


<?php
$friendSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='my_friends'");
if ($friendSetting == 'Y') {
    ?>
        <a onclick="viewFriends()" class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >
                <img  src="images/friends.png" />
                <span><?php echo language_code('DSP_USER_FRIENDS'); ?></span>
            </li>	
        </a>
<?php }?>
        <a    class="mam_text_dec"  style="color:black;" href="dsp_main_search.html">
            <li class="dsp_home_icon ui-body-d" >
                <img    src="images/search.png" />
                <span><?php echo language_code('DSP_MENU_SEARCH'); ?></span>
            </li>
        </a>

<?php
$gallerySetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='picture_gallery_module'");
if ($gallerySetting == 'Y') {
    ?>
        <a href="dsp_media.html" class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >
                <img   src="images/galleries.png" />
                <span><?php echo language_code('DSP_GALLERIES'); ?></span>
            </li>
        </a>

<?php } ?>
       
        <?php
$audioSettingSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='audio_module'");
if ($gallerySetting == 'Y') {
    ?>
<!--        <a href="dsp_sound.html"   class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >
                <img  style="width:40px;height:40px;"      src="images/sound.png" /><br>
                <span><?php echo language_code('DSP_SOUND'); ?></span>
            </li>
        </a>-->
<?php } ?>


        <a href="dsp_extras.html"  class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >
                <img  src="images/extras.png" />
                <span><?php echo language_code('DSP_MENU_EXTRAS'); ?></span>
            </li>
        </a>
 <?php
$videSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='video_module'");
if ($videSetting == 'Y') {
    ?>
        <a  href="dsp_video.html" class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >
                <img  src="images/video.png" />
                <span><?php echo language_code('DSP_MEDIA_HEADER_VIDEOS'); ?></span>
            </li>
        </a>
<?php } ?>
        <a onclick="callSetting('setting')" class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >
                <img   src="images/settings.png" />
                <span><?php echo language_code('DSP_SETTINGS'); ?></span>
            </li>
        </a>



        <a href="dsp_membership.html"  class="mam_text_dec MenuiPhone"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >	
                <img   src="images/membership.png" />
                <span><?php echo language_code('DSP_HOME_TAB_MEMBERSHIPS'); ?></span>
            </li>
        </a>


        <a href="dsp_upgrade.html"  class="mam_text_dec MenuiPhone"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >	
                <img     src="images/upgrade.png" />
                <span><?php echo language_code('DSP_UPGRADE'); ?></span>	
            </li>
        </a>

    </ul>


<?php include_once('dspNotificationPopup.php') ?>
</div>


