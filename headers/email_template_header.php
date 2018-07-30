<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

$message_template = get('message_template'); 
$reply_Action = get('Act');
$Sender_IDs = get('delmessage');
$del_mode = get('mode');
if (($Sender_IDs != "") && ($del_mode == "delete")) {
    for ($i = 0; $i < sizeof($Sender_IDs); $i++) {
        $wpdb->query("UPDATE $dsp_user_emails_table SET delete_message=1  WHERE sender_id = '" . $Sender_IDs[$i] . "' and receiver_id='$user_id'");
    } // End for loop
} // End if 
$count_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE message_read='N' AND receiver_id=$user_id AND delete_message=0");
$no_of_credits = dsp_get_credit_of_current_user();
$giftSettingValue = dsp_get_credit_setting_value('emails_per_credit');
?>
<div class="line">
    <?php // --------------------------------------- START MESSAGES MENU ----------------------------------------------------//  ?>
    <div <?php if (($message_template == "inbox") || ($message_template == "")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <?php if ($count_messages > 0) { ?>
            <a href="<?php echo $root_link . "email/inbox/"; ?>" ><?php echo language_code('DSP_INBOX_MESSAGE'); ?>&nbsp;(<?php echo $count_messages ?>)</a>
        <?php } else { ?>
            <a href="<?php echo $root_link . "email/inbox/"; ?>"><?php echo language_code('DSP_INBOX_MESSAGE'); ?></a>
        <?php } ?>
    </div>
    <?php // ---------------------------------------- END MESSAGES MENU ------------------------------------------------------// ?>
    <?php // --------------------------------------- START COMPOSE MENU ----------------------------------------------------//  ?>
    <div <?php if ($message_template == "compose") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "email/compose/"; ?>"><?php echo language_code('DSP_MIDDLE_TAB_COMPOSE'); ?></a>
    </div>
    <?php // --------------------------------------- END COMPOSE MENU ----------------------------------------------------// ?>
    <?php // --------------------------------------- START SENT MENU ----------------------------------------------------//  ?>
    <div <?php if ($message_template == "sent") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "email/sent/"; ?>"><?php echo language_code('DSP_MIDDLE_TAB_SENT'); ?></a>
    </div>
    <?php // --------------------------------------- END SENT MENU ----------------------------------------------------// ?>
    <?php // --------------------------------------- START DELETED MENU ----------------------------------------------------//  ?>
    <div <?php if ($message_template == "deleted") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "email/deleted/"; ?>"><?php echo language_code('DSP_MIDDLE_TAB_DELETED'); ?></a>
    </div>
    <?php // --------------------------------------- END DELETED MENU ----------------------------------------------------//  ?>
    <div class="clr"></div>
</div>
</div>
<?php
//one to one chat pop up notification 
apply_filters('dsp_get_single_chat_popup_notification',$notification);

if ($message_template == "inbox") {
    if(
        ($no_of_credits >= $giftSettingValue && $check_credit_mode->setting_status == 'Y') ||
        $_SESSION['free_member']
    ){ 
         include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_recieve_messages.php");
    }else{

        if ($check_free_mode->setting_status == "N") {  // check condition if free mode is off 

        // *********** Start Check Access Email Feature is Active ********* //
            $access_feature_name = "Access Email";
            if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is on
                $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_recieve_messages.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
                $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
                
                if ($check_free_email_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_recieve_messages.php");
                } else if ($check_free_email_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_free_email_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_free_email_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else if ($check_force_profile_mode->setting_status == "Y") { // force profile status is on
                $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
                if ($check_force_profile_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_recieve_messages.php");
                } else if ($check_force_profile_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else {  //if free trial mode is off & email access mode is also off
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Access" || ($no_of_credits >= $giftSettingValue && $check_credit_mode->setting_status == 'Y')) {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_recieve_messages.php");
                }else{
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } 
            } // END if free trial mode is ON   
            // *********** End Check Access Email Feature is Active ********* //
        }else{
            include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_recieve_messages.php");
        } 
    } 
}
// *********** Start compose  ********* //
else if ($message_template == "compose" && ($reply_Action != "Reply")) {
    $access_feature_name = "Compose New Email Message";
    if(
        ($no_of_credits >= $giftSettingValue && $check_credit_mode->setting_status == 'Y') ||
        $_SESSION['free_member']
    ){
         include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_send_new_message.php");
    }else{
        if ($check_free_mode->setting_status == "N") {  // free mode is off 
            if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is on 
                $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_send_new_message.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
                $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
                if ($check_free_email_msg == "Access" || ($no_of_credits >= $giftSettingValue && $check_credit_mode->setting_status == 'Y')) {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_send_new_message.php");
                }else{
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } 
            } else if ($check_force_profile_mode->setting_status == "Y") { // free email access mode is on
                $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
                if ($check_force_profile_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_send_new_message.php");
                } else if ($check_force_profile_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else { // if free trial mode is ON
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_send_new_message.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } // END if free trial mode is ON  
        } else {
            include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_send_new_message.php");
        } 
        
    }
} else if (($message_template == "compose") && ($reply_Action == "Reply")) {
    $access_feature_name = "Reply Email Message";
    if(
        ($no_of_credits >= $giftSettingValue && $check_credit_mode->setting_status == 'Y') ||
        $_SESSION['free_member']
    ){
         include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_send_new_message.php");
    }else{
        if ($check_free_mode->setting_status == "N") {  // free mode is off 
            if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is off 
                $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_send_new_message.php");
                } else if ($check_member_trial_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
                $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
                if ($check_free_email_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_send_new_message.php");
                }else if ($no_of_credits >= $giftSettingValue) {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_send_new_message.php");
                }else{
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } 

            } else { // if free trial mode is off & email access mode is also off
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_send_new_message.php");
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } // END 
        }  else {
            include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_send_new_message.php");
        } 
        
    }
} else if ($message_template == "view_message") {
    if ($check_free_mode->setting_status == "N") {  // check condition if free mode is off 
// *********** Start Check View Email Message Feature is Active ********* //
        $access_feature_name = "View Email Message";

        if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is off 
            $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_view_message.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
// *********** End Check View Email Message Feature is Active ********* // 
        } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
            $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
            if ($check_free_email_msg == "Access" || $no_of_credits >= $giftSettingValue) {
                include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_view_message.php");
            }else{
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } 
        } else { // if free trial mode is ON
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_view_message.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        } // END if free trial mode is ON
    } else {
        include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_view_message.php");
    } // End // check condition if free mode is off 
} else if ($message_template == "sent") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_sent_message.php");
} else if ($message_template == "deleted") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_delete_messages.php");
} else if ($message_template == "delete_messages") {
    include_once(WP_DSP_ABSPATH . "dsp_view_delete_messages.php");
} else if ($message_template == "view_winks") {
    include_once(WP_DSP_ABSPATH . "dsp_view_winks.php");
} else if ($message_template == "view_friends") {
    include_once(WP_DSP_ABSPATH . "dsp_view_friends.php");
} else if ($message_template == "my_favourites") {
    include_once(WP_DSP_ABSPATH . "dsp_my_favourites.php");
} else if ($message_template == "my_matches") {
    include_once(WP_DSP_ABSPATH . "dsp_my_matches.php");
} else if ($message_template == "alerts") {
    include_once(WP_DSP_ABSPATH . "dsp_alerts_messages.php");
} else if ($message_template == "blocked") {
    include_once(WP_DSP_ABSPATH . "dsp_blocked_members.php");
} else {
    if ($check_free_mode->setting_status == "N") {  // check condition if free mode is off 
// *********** Start Check Access Email Feature is Active ********* //
        $access_feature_name = "Access Email";
        if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is off 
            $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_recieve_messages.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
            $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
            if ($check_free_email_msg == "Access" || $no_of_credits >= $giftSettingValue) {
                include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_recieve_messages.php");
            }else{
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } 
        } else { // if free trial mode is ON
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_recieve_messages.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        } // END if free trial mode is ON
// *********** End Check Access Email Feature is Active ********* //   
    } else {
        include_once(WP_DSP_ABSPATH . "members/loggedin/email/dsp_recieve_messages.php");
    } // End // check condition if free mode is off 
}
