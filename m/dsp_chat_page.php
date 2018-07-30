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

include_once("dspFunction.php");

include_once("../general_settings.php");

$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;

$user_id = $_REQUEST['user_id'];
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a onclick="goBlank()" ><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_CHAT'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>

</div>
<?php


global $wp_query;
global $wpdb;

//$page_id = $wp_query->post->ID; //fetch post query string id

$posts_table = $wpdb->prefix . POSTS;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');


$user_id = $_REQUEST['user_id'];  // print session USER_ID

$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path



$a = $pluginpath . "post.php";



$b = $pluginpath . "log_tab.php";

$dsp_smiley = $wpdb->prefix . DSP_SMIILEY;

$smiley_result = $wpdb->get_results("SELECT * FROM $dsp_smiley ORDER BY `id` ASC");
$smiley = '<div class="chat_smiley">';

foreach ($smiley_result as $smiley_row) {
    $smiley.='<a id="add_smiley" onclick="callChat(\'add_smile\',\'' . $smiley_row->sign . '\')">';
    $smiley.='<img src="' . $pluginpath . 'images/smilies/' . $smiley_row->image . '" title="' . $smiley_row->sign . '" >';

    $smiley.='</a>';
}
$smiley.='</div>';
?>


<style>
    .textlink { text-decoration:underline; }
    #chat1 { 
        height: 210px;
        overflow-y: scroll;
        position:relative;
    } 

</style>

<div class="ui-content" data-role="content">
    <div class="content-primary">	 
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
            <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                <div class="chat-box1">
                    <?php
                    if ($check_free_mode->setting_status == "N") {  // free mode is off 
                        $access_feature_name = "Group Chat";

                        if ($check_free_trail_mode->setting_status == "N") {
                            $check_membership_msg = check_membership($access_feature_name, $user_id);
                            if ($check_membership_msg == "Expired") {
                                ?>
                                <p><?php echo language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE_A'); ?> 
                                    <a href="dsp_upgrade.html" class="textlink MenuiPhone"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a>
                                </p>
                                <?php
                            } else if ($check_membership_msg == "Onlypremiumaccess") {
                                ?>
                                <p><?php echo language_code('DSP_PREMIUM_MEMBER_CHAT_MESSAGE_A'); ?> 
                                    <a href="dsp_upgrade.html" class="textlink MenuiPhone"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a>
                                </p>
                                <?php
                            } else if ($check_membership_msg == "Access") {
                                ?>
                                <div class="form-chat">
                                    <?php
                                    $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
                                    $sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");
                                    $user_login = $sender_name;
                                    $_SESSION['name'] = $user_login;
                                    ?>
                                    <div id="wrapper">
                                        <div id="chatbox1">
                                            <?php include(WP_DSP_ABSPATH . '/m/log_tab.php'); ?>
                                        </div>
                                        <div>
                                            <form id="frmchat" class="submit-chat-form" name="frmchat">
                                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" id="user_id"/>
                                                <input style="width:60%" name="text" type="text" value=""   id="usermsg1" size="18" maxlength="75"  />
                                                <input onclick="callChat('post', 0)" style="margin-right:18px; margin-top:0px;" name="submitmsg" type="button"  id="submitmsg1" value="Send" />
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
                                <div class="form-chat">
                                    <?php
                                    $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
                                    $sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");
                                    $user_login = $sender_name;
                                    $_SESSION['name'] = $user_login;
                                    ?>
                                    <div id="wrapper">
                                        <div id="chatbox1">
                                            <?php include(WP_DSP_ABSPATH . '/m/log_tab.php'); ?>
                                        </div>

                                        <div>	
                                            <form id="frmchat" class="submit-chat-form" name="frmchat" >


                                                <input style="width:60%" name="text" type="text" value=""  id="usermsg1" size="18" maxlength="75"  />
                                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" id="user_id"/>


                                                <input onclick="callChat('post', 0)" style="margin-right:18px; margin-top:0px;" name="submitmsg" type="button"  id="submitmsg1" value="Send" />

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
                        <div class="form-chat">
                            <?php
                            $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;

                            $sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");
                            $user_login = $sender_name;
                            $_SESSION['name'] = $user_login;
                            ?>

                            <div id="wrapper">
                                <div id="chatbox1">
                                    <?php include(WP_DSP_ABSPATH . '/m/log_tab.php'); ?>
                                </div>
                                <div>
                                    <form id="frmchat" class="submit-chat-form" name="frmchat">
                                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" id="user_id"/>
                                        <input style="width:60%;" name="text" type="text" value="" id="usermsg1" size="18"  maxlength="75"/>
                                        <input onclick="callChat('post', 0)" style="margin-right:18px; margin-top:0px;" name="submitmsg" type="button"  id="submitmsg1" value="Send" />
                                    </form>
                                    <?php echo $smiley; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </li>
        </ul>


    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
</div>