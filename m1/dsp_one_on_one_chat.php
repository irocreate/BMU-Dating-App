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



//-------------------------DISPLAY ERROR OFF CODE ENDS--------------------------------
include_once("dspFunction.php");

include_once("../general_settings.php");



$user_id = $_REQUEST['user_id'];
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_back.php");?>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_CHAT'); ?></h1>
    <?php include_once("page_home.php");?>
</div>
<?php
global $wp_query;
global $wpdb;




$posts_table = $wpdb->prefix . POSTS;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_chat_request = $wpdb->prefix . "dsp_chat_request";
$dsp_blocked_members_table = $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_smiley = $wpdb->prefix . DSP_SMIILEY;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_online_user_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;

// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');





$fav_icon_image_path = $imagepath . "plugins/dsp_dating/m1/images/"; // fav,chat,star,friends,mail Icon image path

$member_id = $_REQUEST['mem_id'];

$check_user_blocked = $wpdb->get_var("select count(*) from $dsp_blocked_members_table where user_id='$member_id' and block_member_id='$user_id'");

$displayed_member_name = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$member_id'");

if ($check_user_blocked == 0) {
    if (isset($_REQUEST['action'])) {
        if ($_REQUEST['action'] == 'send_request') {
            $check_request = $wpdb->get_var("select count(*) from $dsp_chat_request where sender_id='$user_id' and receiver_id='$member_id' and request_status=0");

            if ($check_request == 0) {
                $insert = mysql_query("INSERT INTO $dsp_chat_request SET sender_id='$user_id',receiver_id='$member_id', request_status=0, time='" . date('g:i A') . "', date='" . date('Y-m-d') . "'");
            } else {
                $update = mysql_query("update $dsp_chat_request SET request_status=0, time='" . date('g:i A') . "', date='" . date('Y-m-d') . "' where sender_id='$user_id' and receiver_id='$member_id'");
            }
        }


        if ($_REQUEST['action'] == 'accept_request') {
            $update = mysql_query("update $dsp_chat_request SET request_status=1, time='" . date('g:i A') . "', date='" . date('Y-m-d') . "' where sender_id='$member_id' and receiver_id='$user_id'");
        }
    }
}
$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path

$a = $pluginpath . "/m1/post_one.php?sender_id=" . $user_id . "&receiver_id=" . $_REQUEST['mem_id'];

$b = $pluginpath . "/m1/log_tab_one.php?sender_id=" . $user_id . "&receiver_id=" . $_REQUEST['mem_id'];

$smiley_result = $wpdb->get_results("SELECT * FROM `$dsp_smiley` ORDER BY `id` ASC");
$smiley = '<div class="chat_smiley">	
	';

foreach ($smiley_result as $smiley_row) {
    $smiley.='<a id="add_smiley" onclick="callOneChat(\'' . $_REQUEST['receiver_id'] . '\',\'add_smile\',\'' . $smiley_row->sign . '\')">';
    $smiley.='<img src="' . $pluginpath . 'images/smilies/' . $smiley_row->image . '" title="' . $smiley_row->sign . '" >';

    $smiley.='</a>';
}
$smiley.='</div>';
?>



<div class="ui-content" data-role="content">
    <div class="content-primary">	 
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul userlist">
            <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                <div class="chat-box1">

                    <?php
// ------------------ calculate date difrence -----------------------//
                    $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_online_user_table WHERE user_id=$member_id");

                    if ($check_couples_mode->setting_status == 'Y') {
                        $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member_id'");
                    } else {
                        $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member_id'");
                    }

                    if ($check_user_blocked > 0) {
                        ?>
                        <div style="height:300px; text-align:center; vertical-align:middle; line-height:300px; font-weight:bold;"><?php echo language_code('DSP_CHAT_USER_BLOCKED_TEXT') ?></div>

                        <?php
                    } else {
                        ?>
                        <div style="width: 100%;">
                            <div style="width:100%; ">
                                <img style="display:block;" src="<?php echo display_thumb2_members_photo($member_id, $imagepath); ?>" width="100" height="100" border="0" />	
                            </div>
                            <div style="padding-top:10px">
                                <img class="icon-on-off" src="<?php
                                //echo $fav_icon_image_path;
                                if ($check_online_user > 0)
                                    echo $fav_icon_image_path . 'online';
                                else
                                    echo $fav_icon_image_path . 'off-line';
                                ?>-chat.jpg" title="<?php
                                     if ($check_online_user > 0)
                                         echo language_code('DSP_CHAT_ONLINE');
                                     else
                                         echo language_code('DSP_CHAT_OFFLINE');
                                     ?>" border="0" />
                                <span class="user-name">
                                    <?php
                                    if ($check_couples_mode->setting_status == 'Y') {
                                        if ($member->gender == 'C') {
                                            ?>
                                            <a onclick="viewProfile('<?php echo $member_id; ?>', 'my_profile')" >
                                                <?php echo $displayed_member_name ?>                
                                                <?php
                                            } else {
                                                ?>
                                                <a onclick="viewProfile('<?php echo $member_id; ?>', 'my_profile')" >
                                                    <?php echo $displayed_member_name ?>
                                                    <?php
                                                }
                                            } else {
                                                ?> 
                                                <a onclick="viewProfile('<?php echo $member_id; ?>', 'my_profile')" >
                                                    <?php echo $displayed_member_name ?>
                                                <?php } ?></a>
                                            </span>
                                            </div>
                                            </div>
                                            <div style="width: 100%;float: left;padding-top: 10px;">
                                                <?php
                                                if ($check_free_mode->setting_status == "N") {  // free mode is off 
                                                    $access_feature_name = "One to One Chat";

                                                    if ($check_free_trail_mode->setting_status == "N") {
                                                        $check_membership_msg = check_membership($access_feature_name, $user_id);

                                                        if ($check_membership_msg == "Expired") {
                                                            ?>
                                                            <p><?php echo language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE_A'); ?> 
                                                                <a href="dsp_upgrade.html"  class="textlink MenuiPhone"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a>
                                                            </p>
                                                            <?php
                                                        } else if ($check_membership_msg == "Onlypremiumaccess") {
                                                            ?>
                                                            <p><?php echo language_code('DSP_PREMIUM_MEMBER_CHAT_MESSAGE_A'); ?> 
                                                                <a href="dsp_upgrade.html"  class="textlink MenuiPhone"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a>
                                                            </p>
                                                            <?php
                                                        } else if ($check_membership_msg == "Access") {
                                                            ?>
                                                            <div>
                                                                <?php
                                                                $sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_user_table WHERE ID='$user_id'");
                                                                $user_login = $sender_name;
                                                                $_SESSION['name'] = $user_login;
                                                                ?>

                                                                <div >
                                                                    <div id="onechatbox1">
                                                                        <?php include(WP_DSP_ABSPATH . '/m1/log_tab_one.php'); ?>
                                                                    </div>

                                                                    <div>

                                                                        <form id="frmchat" class="submit-chat-form">
                                                                            <input type="hidden" name="sender_id" value="<?php echo $_REQUEST['sender_id'] ?>" />
                                                                            <input type="hidden" name="receiver_id" value="<?php echo $_REQUEST['receiver_id'] ?>" />
                                                                            <input type="text" class="input-control" placeholder="Message"  value="" name="usermsg" id="usermsg1" maxlength="75"/>
                                                                            <!--<input style="width:60%" name="usermsg" type="text" id="usermsg1"  maxlength="75" value=""  />-->
                                                                           <span> <input name="submitmsg" type="button" class="btn-comment" value="...." onclick="callOneChat('<?php echo $_REQUEST['receiver_id'] ?>', 'post', 'val')"/></span>
                                                                        </form>




                                                                        <?php echo $smiley; ?>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <?php
                                                        }
                                                    } else {
                                                        $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                                                        if ($check_member_trial_msg == "Expired") {
                                                            ?>
                                                            <p><?php echo language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE_A'); ?> 
                                                                <a href="dsp_upgrade.html" class="textlink MenuiPhone"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a>
                                                            </p>
                                                            <?php
                                                        } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                                                            ?>
                                                            <p><?php echo language_code('DSP_PREMIUM_MEMBER_CHAT_MESSAGE_A'); ?> 
                                                                <a href="dsp_upgrade.html"  class="textlink MenuiPhone"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a>
                                                            </p>
                                                            <?php
                                                        } else if ($check_member_trial_msg == "Access") {
                                                            ?>
                                                            <div>
                                                                <?php
                                                                $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;

                                                                $sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");

                                                                $user_login = $sender_name;
                                                                $_SESSION['name'] = $user_login;
                                                                ?>
                                                                <div >
                                                                    <div id="onechatbox1">
                                                                        <?php include(WP_DSP_ABSPATH . '/m1/log_tab_one.php'); ?>
                                                                    </div>

                                                                    <div>
                                                                        <form id="frmchat" class="submit-chat-form">
                                                                            <input type="hidden" name="sender_id" value="<?php echo $_REQUEST['sender_id'] ?>" />
                                                                            <input type="hidden" name="receiver_id" value="<?php echo $_REQUEST['receiver_id'] ?>" />
                                                                            <input type="text" class="input-control" placeholder="Message" value="" name="usermsg" id="usermsg1" maxlength="75"/>
                                                                            <!--<input style="width:60%" name="usermsg" type="text" id="usermsg1"  maxlength="75" value=""  />-->
                                                                           <span><input name="submitmsg" type="button" class="btn-comment" value="...." onclick="callOneChat('<?php echo $_REQUEST['receiver_id'] ?>', 'post', 'val')"/></span>
                                                                        </form>

                                                                        <?php echo $smiley; ?>
                                                                    </div>
                                                                </div>

                                                            </div>


                                                            <?php
                                                        }
                                                    }
                                                } else {
                                                    ?>

                                                    <div>
                                                        <?php
                                                        $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;

                                                        $sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");
                                                        $user_login = $sender_name;
                                                        $_SESSION['name'] = $user_login;
                                                        ?>

                                                        <div >
                                                            <div id="onechatbox1">
                                                                <?php include(WP_DSP_ABSPATH . '/m1/log_tab_one.php'); ?>
                                                            </div>

                                                            <div>
                                                                <form id="frmchat" class="submit-chat-form">
                                                                    <input type="hidden" name="sender_id" value="<?php echo $_REQUEST['sender_id'] ?>" />
                                                                    <input type="hidden" name="receiver_id" value="<?php echo $_REQUEST['receiver_id'] ?>" />
                                                                    <input type="text" class="input-control" placeholder="Message" value="" name="usermsg" id="usermsg1" maxlength="75"/>
                                                                    <!--<input style="width:60%" name="usermsg" type="text" id="usermsg1"  maxlength="75" value=""  />-->
                                                                    <span><input name="submitmsg" type="button" class="btn-comment" value="...." onclick="callOneChat('<?php echo $_REQUEST['receiver_id'] ?>', 'post', 'val')"/></span>
                                                                </form>
                                                                <?php echo $smiley; ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php }
                                                ?>
                                            </div>
                                        <?php } ?>


                                        </div>
                                        </li>
                                        </ul>
                                        </div>
                                        <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
                                        </div>