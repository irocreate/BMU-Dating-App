<link href="https://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
<link href="index.css" rel="stylesheet" type="text/css">
<link href="mobile.css" rel="stylesheet" type="text/css">
<?php
include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include_once("dspFunction.php");

include_once("../general_settings.php");

$dsp_comments_table = $wpdb->prefix . DSP_USER_COMMENTS;
$dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;
$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;

$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$virtualGiftSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='virtual_gifts'");



$user_id = $_REQUEST['user_id'];


$count_friends_virtual_gifts = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_virtual_gifts WHERE member_id=$user_id AND status_id=0");
$count_wink_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_member_winks_table WHERE wink_read='N' AND receiver_id=$user_id");
$count_friends_request = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid=$user_id AND approved_status='N'");
$count_friends_comments = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_comments_table WHERE member_id=$user_id AND status_id=0");
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_ALERTS'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>

</div>


<div class="ui-content" data-role="content">
    <div class="content-primary">	 

        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">

            <a  onclick="slide_me('div_frnd_req')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">

                    <?php
                    if ($count_friends_request > 0) {
                        echo language_code('DSP_MEDIA_LINK_APPROVE') . ' ' . language_code('DSP_MIDDLE_TAB_FRIENDS') . ' (' . $count_friends_request . ')';
                    } else {
                        echo language_code('DSP_MEDIA_LINK_APPROVE') . ' ' . language_code('DSP_MIDDLE_TAB_FRIENDS');
                    }
                    ?>
                </li>
            </a>
            <li  id="div_frnd_req" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('dsp_frnd_request.php') ?>
            </li>
            <a onclick="slide_me('div_comment')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <?php
                    if ($count_friends_comments > 0) {
                        echo language_code('DSP_MEDIA_LINK_APPROVE') . ' ' . language_code('DSP_USERS_COMMENTS') . ' (' . $count_friends_comments . ')';
                    } else {
                        echo language_code('DSP_MEDIA_LINK_APPROVE') . ' ' . language_code('DSP_USERS_COMMENTS');
                    }
                    ?>
                </li>
            </a>
            <li  id="div_comment" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('dsp_view_comments.php') ?>
            </li>
            <li id="showComment" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv" >
                <a onclick="closeDiv('showComment', 'cDetail')">
                    <img style="position: absolute; top:3px; right:4px;width: 15px;height: 15px;" src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/close.gif' ?>" />
                </a>	
                <div id="cDetail"></div>
            </li>
            <?php if($virtualGiftSetting== 'Y'){ ?>
            <a onclick="slide_me('div_gift')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <?php
                    if ($count_friends_virtual_gifts > 0) {
                        echo language_code('DSP_MEDIA_LINK_APPROVE') . ' ' . language_code('DSP_VIRTUAL_GIFTS_MODE') . ' (' . $count_friends_virtual_gifts . ')';
                    } else {
                        echo language_code('DSP_MEDIA_LINK_APPROVE') . ' ' . language_code('DSP_VIRTUAL_GIFTS_MODE');
                    }
                    ?>


                </li>
            </a>
            
            <li  id="div_gift" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('dsp_view_virtual_gifts.php') ?>
            </li>
            
            <li id="showGift" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv" >
                <a onclick="closeDiv('showGift', 'gDetail')">
                    <img style="position: absolute; top:3px; right:4px;width: 15px;height: 15px;" src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/close.gif' ?>" />
                </a>	
                <div id="gDetail"></div>
            </li>
            <?php } ?>
            <a onclick="slide_me('div_wink')" >
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <?php
                    if ($count_wink_messages > 0) {
                        echo language_code('DSP_WINK_RECEIVED') . ' (' . $count_wink_messages . ')';
                    } else {
                        echo language_code('DSP_WINK_RECEIVED');
                    }
                    ?>
                </li>
            </a>
            <li  id="div_wink" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('dsp_view_winks.php') ?>
            </li>
            <li id="showWink" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv" >
                <a onclick="closeDiv('showWink', 'wDetail')">
                    <img style="position: absolute; top:3px; right:4px;width: 15px;height: 15px;" src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/close.gif' ?>" />
                </a>	
                <div id="wDetail"></div>
            </li>



        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>	