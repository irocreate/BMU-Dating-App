<?php

include_once(WP_DSP_ABSPATH . "/files/includes/dsp_mail_function.php");

global $wpdb;


$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_flirt_table = $wpdb->prefix . DSP_FLIRT_TEXT_TABLE;
$dsp_blocked_members_table = $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE;
$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;

$user_id = $_REQUEST['user_id'];


$sender_ID = $user_id;


$get_receiver_id = isset($_REQUEST['receiver_id']) ? $_REQUEST['receiver_id'] : '';



// GET post values

$cmbwinktext = isset($_REQUEST['cmbwinktext_id']) ? $_REQUEST['cmbwinktext_id'] : '';

$sender_id = isset($_REQUEST['sender_id']) ? $_REQUEST['sender_id'] : '';

$receiver_id = isset($_REQUEST['receiver_id']) ? $_REQUEST['receiver_id'] : '';

$send_date = date("Y-m-d H:m:s");

$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';


if ($mode == "sent") {


    if (trim($cmbwinktext) == 0) {
        $message_sent = language_code('DSP_FORGET_SELECT_WINK_TEXT');

        $hasError = true;
    } else {
        $friend_id = trim($cmbwinktext);
    }

    // Checked member is in user blocked list

    $checked_block_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blocked_members_table WHERE user_id=$receiver_id AND block_member_id='$user_id'");


    //checked blocked member 
    if ($checked_block_member > 0) {

        $message_sent = "blocked";
          $hasError = true;
    } else {
        $receiver_id = trim($receiver_id);
    }

  

    //If there is no error, then Message sent
    if (!isset($hasError)) {

        $wpdb->query("INSERT INTO $dsp_member_winks_table SET sender_id='$sender_id',receiver_id='$receiver_id',wink_id='$cmbwinktext',send_date='$send_date',wink_read='N'");

        $wink_message = $wpdb->get_row("SELECT * FROM $dsp_flirt_table WHERE Flirt_ID='$cmbwinktext'");

        $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='1'");

        $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$receiver_id'");

        $reciver_name = $reciver_details->display_name;

        $receiver_email_address = $reciver_details->user_email;

        $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$sender_id'");

        $sender_name = $sender_details->display_name;

        $url = $_SERVER['HTTP_HOST'];

        $email_subject = $email_template->subject;

        $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);

        $mem_email_subject = $email_subject;

        $email_message = $email_template->email_body;

        $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);

        $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);

        $email_message = str_replace("<#WINK_MESSAGE#>", $wink_message->flirt_Text, $email_message);

        $email_message = str_replace("<#URL#>", $url, $email_message);

        $MemberEmailMessage = $email_message;

        wp_mail($receiver_email_address,$mem_email_subject,$MemberEmailMessage);

        $message_sent = language_code('DSP_SEND_MESSAGE_SUCCESS');
       
    }
    ?>
    <div role="banner" class="ui-header ui-bar-a" data-role="header">
     <?php include_once("page_back.php");?>
     <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_SEND_WINK'); ?></h1>
     <?php include_once("page_home.php");?>

 </div>
 <div class="ui-content" data-role="content">
    <div class="content-primary">
        <div class="success-message">

<?php echo $message_sent; ?>
        </div>
    </div>
</div>

<?php } else {
    ?>

    <div role="banner" class="ui-header ui-bar-a" data-role="header">
     <?php include_once("page_back.php");?>
     <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_SEND_WINK'); ?></h1>
     <?php include_once("page_home.php");?>

 </div>

 <div class="ui-content" data-role="content">
    <div class="content-primary">

     <form name="sendwinkfrm" id="sendwinkfrm">
  <fieldset>
        <input type="hidden" name="sender_id" value="<?php echo $sender_ID ?>" />

        <input type="hidden" name="pagetitle" value="send_wink_msg" />

        <input type="hidden" name="user_id" value="<?php echo $sender_ID ?>" />

        <input type="hidden" name="receiver_id" value="<?php echo $get_receiver_id ?>" />
        <label class="select-group">  

            <select name="cmbwinktext_id" data-role="none">
                <?php
                $wink_text = $wpdb->get_results("SELECT * FROM $dsp_flirt_table Order by Flirt_ID");
                foreach ($wink_text as $wink) {
                    ?>
                    <option value="<?php echo $wink->Flirt_ID; ?>" ><?php echo $wink->flirt_Text; ?></option>
                    <?php } ?>
                </select>
            </label>
            <input type="hidden" name="mode" value="sent" />

            <div data-role="fieldcontain">
                <div class="btn-blue-wrap">
                    <input type="button" id="send_flirt" class="mam_btn btn-blue" name="send_flirt" value="<?php echo language_code('DSP_SEND_WINK_BUTTON'); ?>" >
                </div>
            </div>
  </fieldset>
        </form>

    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>
<?php } ?>