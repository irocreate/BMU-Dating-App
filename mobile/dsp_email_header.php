<?php
// site free mode is off free email is on for male getting no message for female user
$imagepath = $pluginpath . "mobile/images/";
$message_template = $_REQUEST['message_template'];
if (isset($_REQUEST['Act'])) {
    $reply_Action = $_REQUEST['Act'];
} else {
    $reply_Action = "";
}
?>
<div class="dsp_mb_header"><?php echo DSP_MY_INBOX ?></div><br>
<div class="dsp_mem_div" style="width: 100%;margin-left: -10px;">

    <div <?php if (($message_template == "inbox") || ($message_template == "")) { ?>class="dsp_mail_menu_active" <?php } else { ?>class="dsp_mb_mem_menu" <?php } ?>  >
        <a href="<?php
        echo add_query_arg(array('pid' => 14, 'pagetitle' => 'my_email',
            'message_template' => 'inbox'), $root_link);
        ?>" title="<?php echo DSP_MAIL_INBOX ?>"><img src="<?php echo $imagepath . 'inbox.png' ?>"/>
               <?php echo DSP_MAIL_INBOX ?>
        </a>
    </div>
    <div <?php if ($message_template == "sent") { ?>class="dsp_mail_menu_active" <?php } else { ?>class="dsp_mb_mem_menu" <?php } ?> >
        <a href="<?php
        echo add_query_arg(array('pid' => 14, 'pagetitle' => 'my_email',
            'message_template' => 'sent'), $root_link);
        ?>" title="<?php echo DSP_SENT ?>"><img src="<?php echo $imagepath . 'sentarrow.png' ?>"/><?php echo DSP_SENT ?></a></div>
    <div style= "margin-left: 1px" <?php if ($message_template == "compose") { ?>class="dsp_mail_menu_active" <?php } else { ?>class="dsp_mb_mem_menu" <?php } ?>>
        <a href="<?php
        echo add_query_arg(array('pid' => 14, 'pagetitle' => 'my_email',
            'message_template' => 'compose'), $root_link);
        ?>" title="<?php echo DSP_COMPOSE ?>"><img src="<?php echo $imagepath . 'compose.png' ?>"/><?php echo DSP_COMPOSE ?></a></div>
    <div class="clr"></div>
</div>
<?php 
if ($message_template == "inbox") {
    if ($check_free_mode->setting_status == "N") {  // check condition if free mode is off 
// *********** Start Check Access Email Feature is Active ********* //
        $access_feature_name = "Access Email";

        if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is on
            $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_recieve_messages.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
            $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
            if ($check_free_email_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_recieve_messages.php");
            } else if ($check_free_email_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_free_email_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_free_email_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else if ($check_force_profile_mode->setting_status == "Y") { // free email access mode is on
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_recieve_messages.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else { // if free trial mode is off
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_recieve_messages.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } // END if free trial mode is ON   
        // *********** End Check Access Email Feature is Active ********* //
    } else {
        if ($check_force_profile_mode->setting_status == "Y") { // free email access mode is on
            $access_feature_name = "Access Email";
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {

                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_recieve_messages.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else {
            include("wp-content/plugins/dsp_dating/mobile/dsp_recieve_messages.php");
        } // End // check condition if free mode is off 
    }
}
// *********** Start compose  ********* //
else if ($message_template == "compose" && ($reply_Action != "Reply")) {
    $access_feature_name = "Compose New Email Message";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is off 
            $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_send_new_message.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
            $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
            if ($check_free_email_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_send_new_message.php");
            } else if ($check_free_email_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_free_email_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_free_email_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else if ($check_force_profile_mode->setting_status == "Y") { // free email access mode is on
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_send_new_message.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else { // if free trial mode is ON
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_send_new_message.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } // END if free trial mode is ON  
    } else {
        if ($check_force_profile_mode->setting_status == "Y") { // free email access mode is on
            $access_feature_name = "Compose New Email Message";
            $check_force_profile_msg = check_free_force_profile_feature($user_id);
            if ($check_force_profile_msg == "Approved") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_send_new_message.php");
            } else if ($check_force_profile_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else {
            include("wp-content/plugins/dsp_dating/mobile/dsp_send_new_message.php");
        }
    }
} else if (($message_template == "compose") && ($reply_Action == "Reply")) {
    $access_feature_name = "Reply Email Message";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is off 
            $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_send_new_message.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
            $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
            if ($check_free_email_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_send_new_message.php");
            } else if ($check_free_email_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_free_email_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_free_email_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else { // if free trial mode is ON
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_send_new_message.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } // END if free trial mode is ON   
    } else {
        include("wp-content/plugins/dsp_dating/mobile/dsp_send_new_message.php");
    }
} else if ($message_template == "view_message") {
    if ($check_free_mode->setting_status == "N") {  // check condition if free mode is off 
// *********** Start Check View Email Message Feature is Active ********* //
        $access_feature_name = "View Email Message";

        if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is off 
            $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_view_message.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
// *********** End Check View Email Message Feature is Active ********* // 
        } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
            $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
            if ($check_free_email_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_view_message.php");
            } else if ($check_free_email_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_free_email_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_free_email_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else { // if free trial mode is ON
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_view_message.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } // END if free trial mode is ON
    } else {
        include("wp-content/plugins/dsp_dating/mobile/dsp_view_message.php");
    } // End // check condition if free mode is off 
} else if ($message_template == "sent") {
    include("wp-content/plugins/dsp_dating/mobile/dsp_sent_message.php");
} else if ($message_template == "view_sent_message") {
    include("wp-content/plugins/dsp_dating/mobile/dsp_view_sent_message.php");
} else {
    if ($check_free_mode->setting_status == "N") {  // check condition if free mode is off 
// *********** Start Check Access Email Feature is Active ********* //
        $access_feature_name = "Access Email";
        if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is off 
            $check_member_trial_msg = check_free_trial_email_feature($access_feature_name, $user_id);
            if ($check_member_trial_msg == "NotExist") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Approved") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_recieve_messages.php");
            } else if ($check_member_trial_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else if ($check_free_email_access_mode->setting_status == "Y") { // free email access mode is on
            $check_free_email_msg = check_free_email_feature($access_feature_name, $user_id);
            if ($check_free_email_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_recieve_messages.php");
            } else if ($check_free_email_msg == "NoAccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_free_email_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_free_email_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } else { // if free trial mode is ON
            $check_membership_msg = check_membership($access_feature_name, $user_id);
            if ($check_membership_msg == "Expired") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            } else if ($check_membership_msg == "Access") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_recieve_messages.php");
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                include("wp-content/plugins/dsp_dating/mobile/dsp_print_message.php");
            }
        } // END if free trial mode is ON
// *********** End Check Access Email Feature is Active ********* //   
    } else {
        include("wp-content/plugins/dsp_dating/mobile/dsp_recieve_messages.php");
    } // End // check condition if free mode is off 
}
?>