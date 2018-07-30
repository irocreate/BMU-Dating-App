<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
// In this file we checks Admin General Settings
include(WP_DSP_ABSPATH . "mobile/general_settings.php");
$dsp_spam_words_table = $wpdb->prefix . DSP_SPAM_WORDS_TABLE;
$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;
if (isset($_GET['sender_ID'])) {
    $get_sender_id = $_GET['sender_ID'];
} else {
    $get_sender_id = "";
}
if (isset($_GET['Act'])) {
    $request_Action = $_GET['Act'];
} else {
    $request_Action = "";
}
if (isset($_GET['frnd_id'])) {
    $get_frnd_id = $_GET['frnd_id'];
    if ($request_Action == "send_msg" && $get_frnd_id != "") {
        $reply_friend_id = $get_frnd_id;
    }
} else { // if friend is not selected the we will provide the friend list in drop down
    $reply_friend_id = 0;
}
if (isset($_GET['receive_id'])) {
    $get_receiver_id = $_GET['receive_id'];
} else {
    $get_receiver_id = "";
}
if ($request_Action == "Reply" && $get_sender_id != "") {


    $getmsgid = $_GET['msgid'];
    $reply_friend_id = $get_sender_id;
    $reply_messages_subject = $wpdb->get_row("SELECT * FROM $dsp_user_emails_table where message_id='$getmsgid'");
    $reply_Subject = "Re:" . $reply_messages_subject->subject;
    $redisplay_sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$reply_messages_subject->sender_id'");
    $redisplay_reciver_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$reply_messages_subject->receiver_id'");
    $replymessage_date = date("d/m/Y h:i A", strtotime($reply_messages_subject->sent_date));
    $reply_message_content1 = strip_tags(DSP_FROM . $redisplay_sender_name->display_name);
    $reply_message_content2 = strip_tags(DSP_TO . $redisplay_reciver_name->display_name);
    $reply_message_content3 = strip_tags(DSP_DATE . $replymessage_date);
    $reply_message_contentmain = strip_tags($reply_messages_subject->text_message . "\n<br>");
}

if (isset($_POST['friend_id'])) {
    $friend_id = $_POST['friend_id'];
} else {
    $friend_id = "";
}
if (isset($_POST['mode'])) {
    $mode = $_POST['mode'];
} else {
    $mode = "";
}
$send_date = date("Y-m-d H:m:s");
if ($mode == "sent") {
    $Subject = $_POST['txtSubject'];
    $message = $_POST['txtmessage'];

    if (trim($_POST['friend_id']) == 0) {
        $to_Error = language_code('DSP_FORGOT_FRIEND_MSG');
        $hasError = true;
    } else {
        $friend_id = trim($_POST['friend_id']);
    }
    //Check to make sure that the Subject field is not empty
    if (trim($_POST['txtSubject']) === '') {
        $subjectError = language_code('DSP_FORGOT_SUBJECT_MSG');
        $hasError = true;
    } else {
        $Subject = trim($_POST['txtSubject']);
        $replykeyword = "Re:";
        $Subject = str_replace($replykeyword, "", $Subject);
    }
    //Check to make sure that the Message field is not empty
    if (trim($_POST['txtmessage']) === '') {
        $messageError = language_code('DSP_FORGOT_MESSAGE_MSG');
        $hasError = true;
    } else {
        $message = trim($_POST['txtmessage']);
    }
    //check spam filter is ON
    $spam_words = array();
    if (trim($check_spam_filter->setting_status) === 'Y') {
        $check_spam_word = $wpdb->get_results("SELECT * FROM $dsp_spam_words_table order by spam_word");
        foreach ($check_spam_word as $spam_word) {
            if (preg_match("/\b" . $spam_word->spam_word . "\b/i", $_POST['txtmessage'])) {
                //if(stristr($_POST['txtmessage'],$spam_word->spam_word)){
                $spam_words[] = $spam_word->spam_word;
            }
        } // end foreach loop
        if (count($spam_words) > 0) {
            $spam_messageError = language_code('DSP_SPAM_FILTER_ACTIVE_MSG');
            $hasError = true;
        } else {
            $message = trim($_POST['txtmessage']);
        }
    }
    // Checked member is in user blocked list
    $checked_block_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blocked_members_table WHERE user_id=$friend_id AND block_member_id='$user_id'");
    //checked blocked member 
    if ($checked_block_member > 0) {
        $blocked_Error = language_code('DSP_BLOCKED_MEMBER_MESSAGE');
        $hasError = true;
    } else {
        $friend_id = trim($_POST['friend_id']);
    }

    //If there is no error, then Message sent
    if (!isset($hasError)) {
        $count_threads = $wpdb->get_row("SELECT MAX(message_id) as maxid FROM $dsp_user_emails_table WHERE (sender_id = $user_id AND receiver_id=$friend_id) OR (sender_id = $friend_id AND receiver_id=$user_id)");
        $thread_id = $count_threads->maxid;
        $check_friend_notification = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_notification_table WHERE private_messages='N' AND user_id='$friend_id'");
        if ($check_friend_notification <= 0) {
            $wpdb->query("INSERT INTO $dsp_user_emails_table SET sender_id = $user_id,receiver_id ='$friend_id',subject='$Subject',text_message='$message',sent_date='$send_date',message_read='N',thread_id='$thread_id'");
            $sel_email = $wpdb->get_row("SELECT user_email FROM $DSP_USERS_TABLE WHERE ID = '$friend_id'");
            $email_id = $sel_email->user_email;
            $message_sent = language_code('DSP_SEND_MESSAGE_SUCCESSFULLY');
            $to = $email_id;
            $subject = language_code('DSP_SEND_MESSAGE_FROM_TEMP');
            $message = HI_YOU_HVAE_JUST_RECEIVE_MAIL_FROM_A_MEMBER_AT_DATINGSOLUTION_PLEASE_LOGIN_TO_CHECK_UR_MAIL;
            $from = DSP_CONTACT_DATINGSOLUTIONS_BIZ;
            $headers = DSP_FROM . $from;
            wp_mail($to, $subject, $message, $headers);
        } else {
            $message_sent = language_code('DSP_MEMBER_ALERT_MSG_NOTIFICATION');
        }
    }
}
?>
<div class="dsp_compose">
    <?php if (isset($spam_messageError)) { ?>
        <div>
            <p style="color:#FF0000; padding-left:30px;"><?php echo $spam_messageError ?></p>
        </div>
        <?php
    }
    if (isset($blocked_Error)) {
        ?>
        <div>
            <p style="color:#FF0000; padding-left:30px;"><?php echo $blocked_Error ?></p>
        </div>
        <?php
    }
    if (isset($message_sent) && $message_sent != "") {
        ?>
        <div>
            <p style="color:#FF0000; padding-left:30px;"><?php echo $message_sent ?></p>
        </div>
    <?php } else { ?>
        <form name="composefrm" action="" method="post">
            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                <tr>
                    <td width="2%" ><?php echo language_code('DSP_SEND_TO') ?>:</td>
                    <td>
                        <?php
                        if ($get_receiver_id != "") {

                            $display_receiver_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$get_receiver_id'");
                            ?>

                            <input type="text" class="mb_field1"  readonly name="receiver_name" value="<?php echo $display_receiver_name->display_name; ?>" />

                            <input type="hidden" class="mb_mb_field1" name="friend_id" value="<?php echo $get_receiver_id ?>" />

                            <?php
                        } else {
                            if ($reply_friend_id == '0') {// means no friend selected so will provide the list of all friend
                                $check_mem_fr_query = "SELECT COUNT(*) FROM $dsp_my_friends_table as f
			  					INNER JOIN $DSP_USERS_TABLE AS u ON u.id = f.friend_uid
								where f.user_id='$user_id' And f.approved_status='Y' Order by f.friend_id";
                                $check_mem_as_frnd = $wpdb->get_var($check_mem_fr_query);
                                $getFriendQuery = "SELECT * FROM $dsp_my_friends_table as f
			             INNER JOIN $DSP_USERS_TABLE AS u ON u.id = f.friend_uid 
			             where f.user_id='$user_id' And f.approved_status='Y' Order by f.friend_id";
                            } else {

                                //echo "SELECT COUNT(*) FROM $dsp_my_friends_table where friend_uid ='$reply_friend_id' AND user_id='$user_id' And approved_status='Y' Order by friend_id";
                                $check_mem_as_frnd = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table as f
			  			INNER JOIN $DSP_USERS_TABLE AS u ON u.id = f.friend_uid
			  			where f.friend_uid ='$reply_friend_id' AND f.user_id='$user_id' And f.approved_status='Y' Order by f.friend_id");
                                $getFriendQuery = "SELECT * FROM $dsp_my_friends_table	as f
			  		 INNER JOIN $DSP_USERS_TABLE AS u ON u.id = f.friend_uid
			  		 where f.friend_uid ='$reply_friend_id' AND f.user_id='$user_id' And f.approved_status='Y' Order by f.friend_id";
                            }
                            if ($check_mem_as_frnd > 0) {
                                ?>
                                <select name="friend_id" class="mb_field1"   >

                                    <option value="0"><?php echo language_code('DSP_SELECT_OPTION'); ?></option>

                                    <?php
                                    $myfriends_list = $wpdb->get_results($getFriendQuery);
                                    //echo $getFriendQuery;

                                    foreach ($myfriends_list as $friends) {
                                        $display_friend_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$friends->friend_uid'");
                                        if ($friends->friend_uid == $reply_friend_id) {
                                            ?>
                                            <option value="<?php echo $friends->friend_uid; ?>" selected="selected" ><?php echo $display_friend_name->display_name; ?></option>
                                        <?php } else {
                                            ?>
                                            <option value="<?php echo $friends->friend_uid; ?>" ><?php echo $display_friend_name->display_name; ?></option>
                                            <?php
                                        }
                                    }
                                    ?>

                                </select>

                                <?php
                            } else { // if sender is not as friend 
                                $display_reply_nonfrnd_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$reply_friend_id'");
                                ?>
                                <input type="text" class="mb_field1" readonly    name="receiver_name" value="<?php
                                if (count($display_reply_nonfrnd_name) > 0) {
                                    echo $display_reply_nonfrnd_name->display_name;
                                }
                                ?>
                                       " />
                                <input type="hidden" class="mb_field1"    name="friend_id" value="<?php echo $reply_friend_id ?>" />
                            <?php } ?>

                        <?php } ?> 
                    </td>
                </tr>
                <?php if (isset($to_Error)) { ?>
                    <tr><td></td><td><span class="error"><?php echo $to_Error; ?></span> </td></tr>						
                <?php } ?>
                <tr>
                    <td colspan="2"  ><?php echo language_code('DSP_SUBJECT') ?>:&nbsp;</td>
                </tr>
                <tr>

                    <td colspan="2"><input type="text" class="mb_field1"   name="txtSubject" value="<?php if (isset($reply_Subject)) echo $reply_Subject; ?>" /></td>
                </tr>
                <?php if (isset($subjectError)) { ?>
                    <tr><td></td><td><span class="error"><?php echo $subjectError; ?></span> </td></tr>						
                <?php } ?>
                <tr><td  colspan="2"><?php echo language_code('DSP_MESSAGE') ?>:</td></tr>
                <tr>
                    <td colspan="2">
                        <div >
                            <div><?php if (isset($reply_message_content1)) echo $reply_message_content1; ?></div>
                            <div><?php if (isset($reply_message_content2)) echo $reply_message_content2; ?></div>
                            <div><?php if (isset($reply_message_content3)) echo $reply_message_content3; ?></div>
                            <div><?php if (isset($reply_message_content4)) echo $reply_message_content4; ?></div>
                            <textarea  name="txtmessage" class="mb_test_field1" >
                                <?php if (isset($reply_message_contentmain)) echo $reply_message_contentmain; ?></textarea>
                        </div>
                    </td>
                </tr>
                <?php if (isset($messageError)) { ?>
                    <tr><td>&nbsp;</td><td><span class="error"><?php echo $messageError; ?></span> </td></tr>						
                <?php } ?>
                <tr>
                    <td   colspan="2" align="center">
                        <input type="hidden" name="mode" value="sent" />
                        <strong><input type="button" name="sent1" value="<?php echo DSP_SEND_BUTTON ?>" onclick="send_email_function();"></strong>&nbsp;&nbsp; 
                    </td>
                </tr>
            </table>
        </form>
    <?php } ?>
</div>