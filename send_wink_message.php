<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$sender_ID = $user_id;
$get_receiver_id = get('receiver_id');
// GET post values
$cmbwinktext = isset($_REQUEST['cmbwinktext_id']) ? $_REQUEST['cmbwinktext_id'] : '';
$sender_id = isset($_REQUEST['sender_id']) ? $_REQUEST['sender_id'] : '';
$receiver_id = isset($_REQUEST['receiver_id']) ? $_REQUEST['receiver_id'] : '';
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$dateTimeFormat = dsp_get_date_timezone();
$send_date = date("Y-m-d H:m:s");
// 
if(dsp_issetGivenEmailSetting($get_receiver_id,'wink')){
    if ($mode == "sent") {
        if (trim($cmbwinktext) == 0) {
            $winktext_Error = language_code('DSP_FORGET_SELECT_WINK_TEXT');
            $hasError = true;
        } else {
            $friend_id = trim($cmbwinktext);
        }


        // Checked member is in user blocked list
        $checked_block_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blocked_members_table WHERE user_id=$receiver_id AND block_member_id='$user_id'");

        //checked blocked member 
        if ($checked_block_member > 0) {
            $blocked_Error = "blocked";
            $hasError = true;
        } else {
            $receiver_id = trim($receiver_id);
        }

        //If there is no error, then Message sent
        if (!isset($hasError) ) {
            $wpdb->query("INSERT INTO $dsp_member_winks_table SET sender_id='$sender_id',receiver_id='$receiver_id',wink_id='$cmbwinktext',send_date='$send_date',wink_read='N'");
            dsp_add_notification($sender_id, $receiver_id, 'send_wink');

            $wink_message = $wpdb->get_row("SELECT * FROM $dsp_flirt_table WHERE Flirt_ID='$cmbwinktext'");
            $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='1'");
            $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$receiver_id'");
            $reciver_name = $reciver_details->display_name;
            $receiver_email_address = $reciver_details->user_email;
            $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$sender_id'");
            $sender_name = $sender_details->display_name;
            
            $url = '<a href= "'.ROOT_LINK . $sender_details->user_login. '">'.$sender_name.'</a>';
            $email_subject = $email_template->subject;
            $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);
            $mem_email_subject = $email_subject;

            $email_message = $email_template->email_body;
            $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
            $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
            $email_message = str_replace("<#WINK_MESSAGE#>", $wink_message->flirt_Text, $email_message);
            $email_message = str_replace("<#URL#>", $url, $email_message);
            $MemberEmailMessage = $email_message;
            // dsp_send_email($receiver_email_address, get_option('admin_email'), $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");
            
            $wpdating_email  = Wpdating_email_template::get_instance();
            $result = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
            $message_sent = true;
        }
    }
    ?>
    <div class="dsp_box-out">
        <div class="dsp_box-in">
            <?php if (isset($winktext_Error)) { ?>
                <div>
                    <p style="color:#FF0000; padding-left:30px;"><?php echo $winktext_Error ?></p>
                    <p style="text-align:center;">
                        <a href="<?php echo $root_link . get_username($get_receiver_id) . "/"; ?>" class="dspdp-btn dspdp-btn-info"><?php echo language_code('DSP_BACK_TO_PROFILE_LINK') ?>
                        </a>
                    </p>  
                </div>
                <?php
            }
            if (isset($message_sent) && $message_sent == true) {
                ?>
                <div>
                    <p class="dspdp-text-success" style="padding-left:30px;"><?php echo language_code('DSP_SEND_WINK_SUCCESS'); ?></p>
                    <p style="text-align:center;">
                        <a href="<?php echo $root_link . get_username($get_receiver_id) . "/"; ?>" class="dspdp-btn dspdp-btn-info"><?php echo language_code('DSP_BACK_TO_PROFILE_LINK') ?>
                        </a>
                    </p>  
                </div>
            <?php } else { ?>
                <form name="sendwinkfrm" action="" method="post" class=" dspdp-form-inline">
                    <input type="hidden" name="sender_id" value="<?php echo $sender_ID ?>" />
                    <input type="hidden" name="receiver_id" value="<?php echo $get_receiver_id ?>" />
                    <div class="box-page box-border">
                        <div class="dspdp-spacer-sm"><strong><?php echo language_code('DSP_SEND_WINK'); ?></strong></div>
                        <div class="msg"><select  name="cmbwinktext_id" class="dspdp-form-control">
                                <?php
                                $dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
                                $dsp_session_language_table = $wpdb->prefix . DSP_SESSION_LANGUAGE_TABLE;
                                $lang_id = null; //default case where session is not set and not loggin
                                if (isset($_SESSION['default_lang'])) {
                                    $lang_id = $_SESSION['default_lang']; //session is set
                                } else {
                                    $all_languages = $wpdb->get_row("SELECT * FROM $dsp_session_language_table where user_id='" . get_current_user_id() . "' ");
                                    $lang_id = $all_languages->language_id; //logged  in and  session is not set

                                }
                                $all_languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where language_id='" . $lang_id . "'");
                                $language_name = !empty($all_languages->language_name) ? $all_languages->language_name : 'english';
                                $table_name = strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                                if ($language_name == 'english') {
                                    $tableName = "dsp_flirt";
                                } else {
                                   $tableName = "dsp_flirt_" . strtolower(substr($table_name, 0, 2));
                                }

                                $tableName =  $wpdb->prefix .$tableName;

                                $wink_text = $wpdb->get_results("SELECT * FROM $tableName Order by Flirt_ID");

                                foreach ($wink_text as $wink) {
                                    ?>
                                    <option value="<?php echo $wink->Flirt_ID; ?>" ><?php echo $wink->flirt_Text; ?></option>
                                <?php } ?>
                            </select>
                            &nbsp;
                            <input type="hidden" name="mode" value="sent" />
                            <input type="button"  class="dspdp-btn dspdp-btn-default" name="send_flirt" value="<?php echo language_code('DSP_SEND_WINK_BUTTON'); ?>" onclick="send_wink_message();">
                        </div>
                    </div>
                </form>
            <?php } ?>
        </div>
    </div>
<?php }else{ ?>
        <div class="dsp_box-out">
            <div class="dsp_box-in">
                <p style="color:#FF0000; padding-left:30px;">
                     <?php    
                         $print_msg = language_code('DSP_CANT_SEND_WINK_MSG');
                         echo $print_msg;
                     ?>     
                </p>
                <p style="text-align:center;">
                    <a href="<?php echo $root_link . get_username($get_receiver_id) . "/"; ?>" class="dspdp-btn dspdp-btn-info"><?php echo language_code('DSP_BACK_TO_PROFILE_LINK') ?></a>
                </p>    
            </div>
        </div>
<?php } ?>



