<?php
//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->

// In this file we checks Admin General Settings



$dsp_spam_words_table = $wpdb->prefix . DSP_SPAM_WORDS_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_blocked_members_table = $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE;
$dsp_user_notification_table = $wpdb->prefix . DSP_USER_NOTIFICATION_TABLE;
$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_notification = $wpdb->prefix . "dsp_notification";
$dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_credits_table = $wpdb->prefix . DSP_CREDITS_TABLE;

$get_sender_id = isset($_REQUEST['sender_ID']) ? $_REQUEST['sender_ID'] : '';

$request_Action = isset($_REQUEST['Act']) ? $_REQUEST['Act'] : '';



$get_receiver_id = isset($_REQUEST['receive_id']) ? $_REQUEST['receive_id'] : '';
?>

<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_back.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MIDDLE_TAB_COMPOSE'); ?></h1>
    <?php include_once("page_home.php");?> 
</div>
<?php
if ($request_Action == "Reply" && $get_sender_id != "") {
    $getmsgid = isset($_REQUEST['msgid']) ? $_REQUEST['msgid'] : '';
    $reply_friend_id = $get_sender_id;

    $reply_messages_subject = $wpdb->get_row("SELECT * FROM $dsp_user_emails_table where message_id='$getmsgid'");
    $reply_Subject = "Re:" . $reply_messages_subject->subject;

    $redisplay_sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$reply_messages_subject->sender_id'");

    $redisplay_reciver_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$reply_messages_subject->receiver_id'");

    $replymessage_date = date("m/d/Y h:i A", strtotime($reply_messages_subject->sent_date));

    $reply_message_content1 = strip_tags(language_code('DSP_EMAIL_FROM') . ":&nbsp;" . $redisplay_sender_name->display_name);

    $reply_message_content2 = strip_tags(language_code('DSP_EMAIL_TO') . ":&nbsp;" . $redisplay_reciver_name->display_name);

    $reply_message_content3 = strip_tags(language_code('DSP_EMAIL_DATE') . ":&nbsp;" . $replymessage_date);

    $reply_message_contentmain = strip_tags("\n<br>" . $redisplay_sender_name->display_name . " " . language_code('DSP_EMAIL_WROTE') . ":\n<br>><br>" . $reply_messages_subject->text_message . "\n<br>");
}

$friend_id = isset($_REQUEST['friend_id']) ? $_REQUEST['friend_id'] : '';

$Subject = isset($_REQUEST['txtSubject']) ? $_REQUEST['txtSubject'] : '';
$message = isset($_REQUEST['txtmessage']) ? $_REQUEST['txtmessage'] : '';

$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

$send_date = date("Y-m-d H:m:s");



if ($mode == "sent") {
    if (trim($_REQUEST['friend_id']) == 0) {

        $to_Error = language_code('DSP_FORGOT_FRIEND_MSG');

        $hasError = true;
    } else {
        $friend_id = trim($_REQUEST['friend_id']);
    }

    //Check to make sure that the Subject field is not empty

    if (trim($_REQUEST['txtSubject']) === '') {
        $subjectError = language_code('DSP_FORGOT_SUBJECT_MSG');
        $hasError = true;
    } else {
        $Subject = trim($_REQUEST['txtSubject']);
    }

    //Check to make sure that the Message field is not empty
    if (trim($_REQUEST['txtmessage']) === '') {
        $messageError = language_code('DSP_FORGOT_MESSAGE_MSG');

        $hasError = true;
    } else {
        $message = trim($_REQUEST['txtmessage']);
    }
    //check spam filter is ON
    if (trim($check_spam_filter->setting_status) === 'Y') {
        $check_spam_word = $wpdb->get_results("SELECT * FROM $dsp_spam_words_table order by spam_word");

        foreach ($check_spam_word as $spam_word) {

            if (preg_match("/\b" . $spam_word->spam_word . "\b/i", $_REQUEST['txtmessage'])) {
                $spam_words[] = $spam_word->spam_word;
            }
        } // end foreach loop

        if (isset($spam_words) && $spam_words != "") {
            $spam_messageError = language_code('DSP_SPAM_FILTER_ACTIVE_MSG');
            $hasError = true;
        } else {
            $message = trim($_REQUEST['txtmessage']);
        }
    }
    // Checked member is in user blocked list
    $checked_block_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blocked_members_table WHERE user_id=$friend_id AND block_member_id='$user_id'");
    //checked blocked member 

    if ($checked_block_member > 0) {
        $blocked_Error = language_code('DSP_BLOCKED_MEMBER_MESSAGE');
        $hasError = true;
    } else {
        $friend_id = trim($_REQUEST['friend_id']);
    }

    //If there is no error, then Message sent
    if (!isset($hasError)) {

        $count_threads = $wpdb->get_row("SELECT MAX(message_id) as maxid FROM $dsp_user_emails_table WHERE (sender_id = $user_id AND receiver_id=$friend_id) OR (sender_id = $friend_id AND receiver_id=$user_id)");
        $thread_id = $count_threads->maxid;
        $check_friend_notification = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_notification_table WHERE private_messages='N' AND user_id='$friend_id'");

        if ($check_friend_notification <= 0) {

            $wpdb->query("INSERT INTO $dsp_user_emails_table SET sender_id = $user_id,receiver_id ='$friend_id',subject='$Subject',text_message='$message',sent_date='$send_date',message_read='N',thread_id='$thread_id'");

            $check_notification_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'notification'");
            if ($check_notification_mode->setting_status == 'Y') {
                if ($user_id > 0) {

                    $wpdb->query("insert into $dsp_notification values('','$user_id',$friend_id,'send_email','" . date("Y-m-d H:i:s") . "','Y')");
                }
            }
            //	dsp_add_notification($user_id,$friend_id,'send_email');

            $sel_email = $wpdb->get_row("SELECT user_email FROM $wpdb->users WHERE ID = '$friend_id'");

            $email_id = $sel_email->user_email;

            $message_sent = language_code('DSP_SEND_MESSAGE_SUCCESS');


            $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='2'");

            if ($get_receiver_id != "") {

                @$reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$get_receiver_id'");
            } else {
                @$reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$reply_messages_subject->sender_id'");
            }

            @$reciver_name = $reciver_details->display_name;

            @$receiver_email_address = $reciver_details->user_email;


            $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$user_id'");

            $sender_name = $sender_details->display_name;

            $sender_email = $sender_details->user_email;


            $url = $_SERVER['HTTP_HOST'];

            $email_subject = $email_template->subject;

            $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);

            $mem_email_subject = $email_subject;

            $email_message = $email_template->email_body;

            $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);

            $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);

            $email_message = str_replace("<#URL#>", $url, $email_message);

            $MemberEmailMessage = $email_message;

            $to = $email_id;

            $subject = $mem_email_subject;

            $message = $MemberEmailMessage;
            $admin_email = get_option('admin_email');

            $from = $admin_email;
            $headers = "From: $from";
            wp_mail($to, $subject, $message, $headers);
            //---------------------CREDIT CODE START-------------------------//

            if ($check_free_mode->setting_status == "N") {  // check condition if free mode is off 
                if ($check_credit_mode->setting_status == 'Y') {
                    $wpdb->query("update $dsp_credits_usage_table set no_of_emails=no_of_emails-1 where user_id='$user_id'");
                    $emails_per_credit = $wpdb->get_var("select emails_per_credit from $dsp_credits_table");
                    $credit_usage_row = $wpdb->get_row("select * from $dsp_credits_usage_table where user_id='$user_id'");
                    if ($credit_usage_row->no_of_emails % $emails_per_credit == 0) {
                        $new_credit = $credit_usage_row->no_of_emails / $emails_per_credit;
                        $wpdb->query("update $dsp_credits_usage_table set no_of_credits='$new_credit' where user_id='$user_id'");
                        $wpdb->query("update $dsp_credits_table set credit_used=credit_used+1");
                    }
                }
            }
            //---------------------CREDIT CODE ENDS-------------------------//
        } else {
            $message_sent = language_code('DSP_MEMBER_ALERT_MSG_NOTIFICATION');
        }
    }
}
?>



<div data-role="content" class="ui-content" role="main">


    <?php if (isset($spam_messageError)) {
        ?>

        <div>

            <div class="success-message"><?php echo $spam_messageError ?></div>

        </div>
        <?php
    }

    if (isset($blocked_Error)) {
        ?>



        <div >



         <div class="success-message"><?php echo $blocked_Error ?></div>



     </div>



     <?php
 }



 if (isset($message_sent) && $message_sent != "") {
    ?>



    <div >



        <div class="success-message"><?php echo $message_sent ?></div>



    </div>



    <?php
} else {
    ?>



    <form name="composefrm" id="composefrm">
        <div id="reg_result"></div>  

        <fieldset>


            <?php
            $check_user_favourites = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_favourites_table where  user_id=$user_id");

            if ($get_receiver_id != "") { ?>
            <label data-role="fieldcontain" class="form-group">  
              <div class="clearfix">
              <div class="mam_reg_lf form-label"><?php echo language_code('DSP_SEND_TO'); ?></div>
                  <?php 
                  $display_receiver_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$get_receiver_id'");
                  ?>

                  <input type="text" class="field1" name="receiver_name" value="<?php echo $display_receiver_name->display_name; ?>" />

                  <input type="hidden" class="field1" name="friend_id" value="<?php echo $get_receiver_id ?>" />
              </div>
          </label>
          <?php 
      } else if (isset($reply_friend_id) && $reply_friend_id != "") { ?>
      <label data-role="fieldcontain" class="form-group">  
          <div class="clearfix">
              <div class="mam_reg_lf form-label"><?php echo language_code('DSP_SEND_TO'); ?></div>
              <?php
              $check_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table where friend_uid=$reply_friend_id and user_id=$user_id");

              $display_sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$reply_friend_id'");
              ?>

              <input type="text" class="field1" name="receiver_name" value="<?php echo $display_sender_name->display_name; ?>" />

              <input type="hidden" class="field1" name="friend_id" value="<?php echo $reply_friend_id ?>" />
          </div>
      </label>
      <?php
  } else if ($check_user_favourites > 0) {
    ?>
    <label data-role="fieldcontain" class="select-group">  
      <div class="clearfix">
          <div class="mam_reg_lf select-label"><?php echo language_code('DSP_SEND_TO'); ?></div>
          <select name="friend_id" class="field1 select-box">
            <option value="0"><?php echo language_code('DSP_SELECT_OPTION'); ?></option>

            <?php
            $myfriends_list = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table where user_id ='$user_id' ");

            foreach ($myfriends_list as $friends) {
                $chk_user_exist = $wpdb->get_var("SELECT count(*) FROM $dsp_user_table WHERE ID = '$friends->favourite_user_id'");

                $display_friend_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$friends->favourite_user_id'");

                if ($chk_user_exist != 0) {
                                            /* if(isset($reply_friend_id) && $friends->user_id==$reply_friend_id)
                                              { ?>

                                              <option value="<?php   echo $friends->favourite_user_id ;?>" selected="selected" ><?php   echo $display_friend_name->display_name;?></option>

                                              <?php }
                                              else */ {
                                                ?>

                                                <option value="<?php echo $friends->favourite_user_id; ?>" ><?php echo $display_friend_name->display_name; ?></option>

                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </label>
                        <?php
                    } else {
                        ?>
                        <label data-role="fieldcontain" class="select-group">  
                          <div class="clearfix">
                              <div class="mam_reg_lf select-label"><?php echo language_code('DSP_SEND_TO'); ?></div>

                              <select name="friend_id" class="select-box">
                                <option value="0"><?php echo language_code('DSP_SELECT_OPTION'); ?></option>
                                <?php
                                $myfriends_list = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table where friend_uid ='$user_id' And approved_status='Y' Order by friend_id");

                                foreach ($myfriends_list as $friends) {
                                    $display_friend_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$friends->user_id'");

                                        /* if($friends->user_id==$reply_friend_id)
                                          { ?>

                                          <option value="<?php   echo $friends->user_id;?>" selected="selected" ><?php   echo $display_friend_name->display_name;?></option>

                                          <?php }
                                          else */ {
                                            ?>

                                            <option value="<?php echo $friends->user_id; ?>" ><?php echo $display_friend_name->display_name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </label>

                        <?php } ?> 






                        <?php if (isset($to_Error) && $to_Error != '') {
                            ?>
                            <div><span class="error"><?php echo $to_Error; ?></span></div>					
                            <?php } ?>

                            <div data-role="fieldcontain" class="ui-hide-label">
                             <input type="text" class="text-box spacer-bottom-sm" name="txtSubject" value="<?php if (isset($reply_Subject)){ echo $reply_Subject;}else { echo "";} ?>" placeholder="<?php echo language_code('DSP_SUBJECT'); ?>"/>
                         </div>


                         <?php if (isset($subjectError) && $subjectError != '') {
                            ?>

                            <div data-role="fieldcontain"><span class="error"><?php echo $subjectError; ?></span></div>				
                            <?php } ?>

                            <?php if (isset($messageError) && $messageError != '') {
                                ?>

                                <div> <span><?php echo language_code('DSP_MESSAGE') ?>:</span></div>
                                <div><span class="error"><?php echo $messageError; ?></span></div>

                                <?php
                            } 
                            ?>
                            <div data-role="fieldcontain" >

                                <div><?php if (isset($reply_message_content1)) echo $reply_message_content1; ?></div>

                                <div><?php if (isset($reply_message_content2)) echo $reply_message_content2; ?></div>

                                <div><?php if (isset($reply_message_content3)) echo $reply_message_content3; ?></div>

                                <div><?php if (isset($reply_message_content4)) echo $reply_message_content4; ?></div>

                                <textarea  name="txtmessage"  class="textarea-box" placeholder="<?php echo language_code('DSP_MESSAGE') ?>"><?php if (isset($reply_message_contentmain)) echo "\n\n" . $reply_message_contentmain; ?></textarea>
                            </div>
                            <div data-role="fieldcontain">
                                <input type="hidden" name="mode" value="sent" />
                                <input type="hidden" name="message_template" value="compose" />
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                <input type="hidden" name="ACT" value="<?php if (isset($request_Action)) echo $request_Action; ?>" />
                                <input type="hidden" name="sender_ID" value="<?php echo $get_sender_id; ?>" />

                                <input type="hidden" name="receive_id" value="<?php echo $get_receiver_id; ?>" />
                                <div class="btn-blue-wrap">
                                    <input type="button" class="mam_btn btn-blue" onclick="getCompose('true')" id="sent1" name="sent1" value="<?php echo language_code('DSP_SEND_MSG_BUTTON'); ?>"></div>

                                </div>

                            </fieldset>
                        </form>



                        <?php } ?>


                        <?php include_once('dspNotificationPopup.php'); // for notification pop up      ?>
                    </div>
