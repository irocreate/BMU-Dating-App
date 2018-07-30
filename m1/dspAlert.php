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
     <?php include_once("page_menu.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_ALERTS'); ?></h1>
     <?php include_once("page_home.php");?> 

</div>


<div class="ui-content" data-role="content">
    <div class="content-primary">	 

        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul alert-list">

          
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                <a  onclick="slide_me('div_frnd_req')">
                 <img  src="images/icons/aproovefriends.png" />
                    <?php
                    if ($count_friends_request > 0) {
                        echo language_code('DSP_MEDIA_LINK_APPROVE') . ' ' . language_code('DSP_MIDDLE_TAB_FRIENDS') . ' (' . $count_friends_request . ')';
                    } else {
                        echo language_code('DSP_MEDIA_LINK_APPROVE') . ' ' . language_code('DSP_MIDDLE_TAB_FRIENDS');
                    }
                    ?>
                    </a>
                </li>
            </ul>
            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

            <li  id="div_frnd_req" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('dsp_frnd_request.php') ?>
            </li>
            </ul>
            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul alert-list">

                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                   <a onclick="slide_me('div_comment')">
                    <img  src="images/icons/aproovecomments.png" />
                    <?php
                    if ($count_friends_comments > 0) {
                        echo language_code('DSP_MEDIA_LINK_APPROVE') . ' ' . language_code('DSP_USERS_COMMENTS') . ' (' . $count_friends_comments . ')';
                    } else {
                        echo language_code('DSP_MEDIA_LINK_APPROVE') . ' ' . language_code('DSP_USERS_COMMENTS');
                    }
                    ?>
                     </a>
                </li>
                </ul>
           <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

            <li  id="div_comment" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('dsp_view_comments.php') ?>
            </li>
            </ul>
            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

            <li id="showComment" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv" >
                <a onclick="closeDiv('showComment', 'cDetail')">
                    <img style="position: absolute; top:3px; right:4px;width: 15px;height: 15px;" src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/close.gif' ?>" />
                </a>	
                <div id="cDetail"></div>
            </li>
            </ul>
            <?php if($virtualGiftSetting== 'Y'){ ?>
           <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul alert-list">

                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <a onclick="slide_me('div_gift')">
                     <img  src="images/icons/aproovegift.png" />
                    <?php
                    if ($count_friends_virtual_gifts > 0) {
                        echo language_code('DSP_MEDIA_LINK_APPROVE') . ' ' . language_code('DSP_VIRTUAL_GIFTS_MODE') . ' (' . $count_friends_virtual_gifts . ')';
                    } else {
                        echo language_code('DSP_MEDIA_LINK_APPROVE') . ' ' . language_code('DSP_VIRTUAL_GIFTS_MODE');
                    }
                    ?>

                    </a>
                </li>
                </ul>
          
            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

            <li  id="div_gift" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('dsp_view_virtual_gifts.php') ?>
            </li>
            </ul>

            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item winkdetail">

            <li id="showGift" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv" >
                <a onclick="closeDiv('showGift', 'gDetail')">
                 <img  src="images/icons/aproovegift.png" style="position: absolute; top:5px; left:10px;width: 15px;height: 15px;"/>
                    <img style="position: absolute; top:10px; right:10px;width: 15px;height: 15px;" src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/close.gif' ?>" />
                </a>	
                <div id="gDetail"></div>
            </li>

            </ul>
            <?php } ?>
           <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul alert-list">

                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                     <a onclick="slide_me('div_wink')" >
                      <img  src="images/icons/aproovewink.png" />
                    <?php
                    if ($count_wink_messages > 0) {
                        echo language_code('DSP_WINK_RECEIVED') . ' (' . $count_wink_messages . ')';
                    } else {
                        echo language_code('DSP_WINK_RECEIVED');
                    }
                    ?>
                     </a>
                </li>
           </ul>
           <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item">

            <li  id="div_wink" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('dsp_view_winks.php') ?>
            </li>
            </ul>
            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul accordion-item winkdetail">

            <li id="showWink" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv" >
                <a onclick="closeDiv('showWink', 'wDetail')">
                    <img style="position: absolute; top:10px; right:10px;width: 15px;height: 15px;" src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/close.gif' ?>" />
                </a>	
                <div id="wDetail"></div>
            </li>
            </ul>

    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>	
<?php include_once("dspLeftMenu.php"); ?>