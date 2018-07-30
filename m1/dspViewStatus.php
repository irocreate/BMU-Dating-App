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

$user_id = $_REQUEST['user_id'];

$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_notification = $wpdb->prefix . "dsp_notification";
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_news_feed_table = $wpdb->prefix . "dsp_news_feed";





$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE status_id=1 AND user_id = '$user_id'");



$update_status = isset($_REQUEST['update_status']) ? $_REQUEST['update_status'] : '';
$new_status = isset($_REQUEST['new_status']) ? $_REQUEST['new_status'] : '';


if ($update_status == 'Update' && $new_status != "") {

    if ($check_approve_profile_status->setting_status == 'Y') {  // if Profile approve status is Y then Profile Automatically Approved.
        $wpdb->query("UPDATE $dsp_user_profiles SET my_status= '$new_status' WHERE user_id = $user_id");
        $status_approval_message = language_code('DSP_UPDATE_STATUS_MESSAGE');
        // dsp_add_news_feed($user_id,'status');
        $wpdb->query("insert into $dsp_news_feed_table values('','$user_id','status','" . date("Y-m-d H:i:s") . "')");
        //dsp_add_notification($user_id,0,'status');

        $check_notification_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'notification'");
        if ($check_notification_mode->setting_status == 'Y') {
            if ($user_id > 0) {

                $wpdb->query("insert into $dsp_notification values('','$user_id','0','status','" . date("Y-m-d H:i:s") . "','Y')");
            }
        }
    } else {

        $wpdb->query("UPDATE $dsp_user_profiles SET my_status= '$new_status' ,status_id=0 WHERE user_id = $user_id");
        $status_approval_message = language_code('DSP_STATUS_UPDATE_IN_HOURS_MSG');
    }
}

$status = $wpdb->get_var("select my_status from  $dsp_user_profiles WHERE user_id = $user_id and status_id='1'");
?>

<div role="banner" class="ui-header ui-bar-a" data-role="header">
   <?php include_once("page_menu.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title">	<?php echo language_code('DSP_TITLE_STATUS'); ?></h1>
    <?php include_once("page_home.php");?> 

</div>
<div class="ui-content" data-role="content"  role="main">
                    <div >
                        <?php if (isset($status_approval_message)) {
                            ?>
                            <div class="success-message">
                                <?php echo $status_approval_message; ?>
                            </div>
                        <?php } ?>
                        </div>
       <form  id="frm_status">

                <fieldset>
                          <div data-role="fieldcontain" >
                        <textarea name="new_status" value="<?php echo $status; ?>" type="text" class="textarea-box-sm" placeholder="<?php echo language_code('DSP_MY_STATUS'); ?>"/><br>
                        </div>
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                        <input type="hidden" name="update_status" value="Update" />
                         <div class="btn-blue-wrap">
                        <input  name="update_status" type="button"  class="mam_btn btn-blue" onclick="viewStatus(1)" value="<?php echo language_code('DSP_UPDATE_BUTTON'); ?>" />
                      </div>
                    </fieldset>
                </form>


            </li>

        </ul>


    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>
<?php include_once("dspLeftMenu.php"); ?>