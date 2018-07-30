<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - Michael Allen
  WordPress Dating Software Plugin
  Los Angeles, California
  (213) 222-6504
  contact@wpdating.com
 */
// In this file we checks Admin General Settings
$dsp_spam_words_table = $wpdb->prefix . DSP_SPAM_WORDS_TABLE;
$get_sender_id        = get( 'sender_ID' );
$request_Action       = get( 'Act' );
$get_frnd_id          = get( 'frnd_id' );
$get_receiver_id      = get( 'receive_id' );
$dateTimeFormat       = dsp_get_date_timezone();
extract( $dateTimeFormat );
if ( $request_Action == "send_msg" && $get_frnd_id != "" ) {
	$reply_friend_id = $get_frnd_id;
}
if ( $request_Action == "Reply" && $get_sender_id != "" ) {
	$getmsgid               = get( 'msgid' );
	$reply_friend_id        = $get_sender_id;
	$reply_messages_subject = $wpdb->get_row( "SELECT * FROM $dsp_user_emails_table where message_id='$getmsgid'" );
	if ( strpos( $reply_messages_subject->subject, 'Re:' ) === false ) {
		$re = "Re:";
	} else {
		$re = '';
	}
	$reply_Subject          = stripslashes($re . $reply_messages_subject->subject);
	$redisplay_sender_name  = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID = '$reply_messages_subject->sender_id'" );
	$redisplay_reciver_name = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID = '$reply_messages_subject->receiver_id'" );

	$replymessage_date         = date( "$dateFormat $timeFormat", strtotime( $reply_messages_subject->sent_date ) );
	$reply_message_content1    = strip_tags( language_code( 'DSP_EMAIL_FROM' ) . ":&nbsp;" . $redisplay_sender_name->display_name );
	$reply_message_content2    = strip_tags( language_code( 'DSP_EMAIL_TO' ) . ":&nbsp;" . $redisplay_reciver_name->display_name );
	$reply_message_content3    = strip_tags( language_code( 'DSP_EMAIL_DATE' ) . ":&nbsp;" . $replymessage_date );
	$reply_message_contentmain = stripslashes(strip_tags( "\n<br>" . $redisplay_sender_name->display_name . " " . language_code( 'DSP_EMAIL_WROTE' ) . ":\n<br>>" . $reply_messages_subject->text_message . "\n<br>" ));
}

$friend_id    = isset( $_REQUEST['friend_id'] ) ? $_REQUEST['friend_id'] : '';
$subject      = isset( $_REQUEST['txtSubject'] ) ? esc_sql( sanitizeData( trim( $_REQUEST['txtSubject'] ), 'xss_clean' ) ) : '';
$message      = isset( $_REQUEST['txtmessage'] ) ? esc_sql( sanitizeData( trim( $_REQUEST['txtmessage'] ), 'xss_clean' ) ) : '';
$mode         = isset( $_REQUEST['mode'] ) ? $_REQUEST['mode'] : '';
$send_date    = date( 'Y-m-d H:i:s', strtotime( current_time( "$dateFormat $timeFormat" ) ) );
$messageError = '';

if ( $mode == "sent" ) {

	if ( trim( $_POST['friend_id'] ) == 0 ) {
		$messageError .= language_code( 'DSP_FORGOT_FRIEND_MSG' ) . '</br>';
		$hasError = true;
	} else {
		$friend_id = trim( $_POST['friend_id'] );
	}

	//Check to make sure that the Subject field is not empty
	if ( trim( $_POST['txtSubject'] ) === '' ) {
		//$subjectError = language_code('DSP_FORGOT_SUBJECT_MSG');
		//$hasError = true;
		$subject = "[ No Subject ]";
	} else {
		$subject = esc_sql( sanitizeData( trim( $_POST['txtSubject'] ), 'xss_clean' ) );
	}


	//Check to make sure that the Message field is not empty
	if ( trim( $_POST['txtmessage'] ) === '' ) {
		$messageError .= language_code( 'DSP_FORGOT_MESSAGE_MSG' ) . '</br>';
		$hasError = true;
	} else {
		$message = esc_sql( sanitizeData( trim( $_POST['txtmessage'] ), 'xss_clean' ) );
	}

	//check spam filter is ON
	if ( trim( $check_spam_filter->setting_status ) === 'Y' ) {
		$check_spam_word = $wpdb->get_results( "SELECT * FROM $dsp_spam_words_table order by spam_word" );
		foreach ( $check_spam_word as $spam_word ) {
			if ( preg_match( "/\b" . $spam_word->spam_word . "\b/i", $_POST['txtmessage'] ) ) {
				//if(stristr($_POST['txtmessage'],$spam_word->spam_word)){
				$spam_words[] = $spam_word->spam_word;
			}
		} // end foreach loop

		if ( isset( $spam_words ) && $spam_words != "" ) {
			//$split=implode(",",$spam_words);
			$messageError .= language_code( 'DSP_SPAM_FILTER_ACTIVE_MSG' ) . '</br>';
			$hasError = true;
		} else {
			$message = esc_sql( sanitizeData( trim( $_POST['txtmessage'] ), 'xss_clean' ) );
			$message = str_replace( "\n", '<br />', $message );
		}
	}

	// Checked member is in user blocked list
	$checked_block_member = $wpdb->get_var( "SELECT COUNT(*) FROM $dsp_blocked_members_table WHERE user_id=$friend_id AND block_member_id='$user_id'" );

	//checked blocked member
	if ( $checked_block_member > 0 ) {
		$messageError .= language_code( 'DSP_BLOCKED_MEMBER_MESSAGE' ) . '</br>';
		$hasError = true;
	} else {
		$friend_id = trim( $_POST['friend_id'] );
	}

	//If there is no error, then Message sent
	$access_feature_name  = 'Access Email';
	$check_membership_msg = check_membership( $access_feature_name, $user_id );

	$access = false;

	if ( $check_free_mode->setting_status == "Y" || $check_membership_msg == 'Access' || $check_credit_mode->setting_status == 'Y' && ( dsp_get_credit_of_current_user() >= dsp_get_credit_setting_value( 'emails_per_credit' ) ) ) {
		$access = true;
	} else {
		$access = false;
		$messageError .= 'You don\'t have enough credit to send email or your membership has expired.<br/><a href="' . $root_link . 'setting/upgrade_account/">  ' . language_code( 'DSP_CLICK_HERE_LINK' ) . ' </a>  to buy some credit or upgrade you membership ';
	}

	?>
	<?php if ( isset( $messageError ) && $messageError != '' ) { ?>
		<div class="thanks">
			<p align="center" class="error">
				<?php echo $messageError ?>
			</p>
		</div>
	<?php } ?>
	<?php

	//If there is no error, then Message sent
	if ( ! isset( $hasError ) && isset( $access ) ) {
		$count_threads = $wpdb->get_row( "SELECT MAX(message_id) as maxid FROM $dsp_user_emails_table WHERE (sender_id = $user_id AND receiver_id=$friend_id) OR (sender_id = $friend_id AND receiver_id=$user_id)" );

		$thread_id                 = $count_threads->maxid;
		$check_friend_notification = $wpdb->get_var( "SELECT COUNT(*) FROM $dsp_user_notification_table WHERE private_messages='N' AND user_id='$friend_id'" );
		if ( $check_friend_notification <= 0 ) {
			$wpdb->query( "INSERT INTO $dsp_user_emails_table SET sender_id = $user_id,receiver_id ='$friend_id',subject='$subject',text_message='$message',sent_date='$send_date',message_read='N',thread_id='$thread_id'" );
			dsp_add_notification( $user_id, $friend_id, 'send_email' );
			$sel_email = $wpdb->get_row( "SELECT user_email FROM $wpdb->users WHERE ID = '$friend_id'" );
			$email_id  = $sel_email->user_email;

			//mail('asdf@gmail.com', 'admin@asdf.com',);
			$email_template = $wpdb->get_row( "SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='2'" );

			if ( $get_receiver_id != "" ) {
				@$reciver_details = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID = '$get_receiver_id'" );
			} else {
				@$reciver_details = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID = '$reply_messages_subject->sender_id'" );
			}

			@$reciver_name = $reciver_details->display_name;
			@$receiver_email_address = $reciver_details->user_email;

			$sender_details = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID='$user_id'" );
			$sender_name    = $sender_details->display_name;
			$sender_email   = $sender_details->user_email;


			$url               = '<a href= "' . ROOT_LINK . $sender_details->user_login . '">' . $sender_name . '</a>';
			$email_subject     = $email_template->subject;
			$email_subject     = str_replace( "<#SENDER_NAME#>", $sender_name, $email_subject );
			$mem_email_subject = $email_subject;


			$email_message = $email_template->email_body;
			$email_message = str_replace( "<#RECEIVER_NAME#>", $reciver_name, $email_message );
			$email_message = str_replace( "<#SENDER_NAME#>", $sender_name, $email_message );
			$email_message = str_replace( "<#URL#>", $url, $email_message );

			$MemberEmailMessage = $email_message;


			$to      = $email_id;
			$subject = $mem_email_subject;
			$message = $MemberEmailMessage;

			$admin_email = get_option( 'admin_email' );

			$from    = $admin_email;
			$headers = "From: $from";
			//echo $to;die;
			$wpdating_email = Wpdating_email_template::get_instance();
			$wpdating_email->send_mail( $to, $subject, $message );

//			wp_mail( $to, $subject, $message );
			$check_membership_msg = check_membership( $access_feature_name, $user_id );
			///////// credit code////////
			if (
				$check_free_mode->setting_status == "N" &&
				$check_credit_mode->setting_status == 'Y' &&
				dsp_get_credit_of_current_user() > 0
				&& $check_membership_msg != 'Access'
			) {  // check condition if free mode is off
				// check condition if free mode is off
				$emails_per_credit = $wpdb->get_var( "select emails_per_credit from $dsp_credits_table" );
				$wpdb->query( "update $dsp_credits_usage_table set no_of_credits=no_of_credits-$emails_per_credit where user_id='$user_id'" );
				$wpdb->query( "update $dsp_credits_table set credit_used=credit_used+$emails_per_credit" );
			}
			///////// credit code////////
			$message_sent = language_code( 'DSP_SEND_MESSAGE_SUCCESSFULLY' );
		} else {
			$message_sent = language_code( 'DSP_MEMBER_ALERT_MSG_NOTIFICATION' );
		}
	}
}

if ( isset( $message_sent ) && $message_sent != "" ) {
	?>
	<div class="box-border">
		<div class="box-pedding">
			<p class="dspdp-text-success" style="text-align:center;"><?php echo $message_sent ?></p>
			<div style="text-align:center;"><a href="<?php echo $root_link . get_username( $friend_id ) . "/"; ?>"
			                                   class="dspdp-btn dspdp-btn-info"><?php echo language_code( 'DSP_BACK_TO_PROFILE_LINK' ) ?></a>
			</div>
		</div>
	</div>
<?php } else { ?>
	<div class="heading-submenu"><strong><?php echo language_code( 'DSP_COMPOSE' ); ?></strong></div>
	<form name="composefrm" action="" method="post">
		<div class="box-border">
			<div class="box-pedding dspdp-clearfix">
				<ul class="send-msg-page form-horizontal dsp-form-container dspdp-form-horizontal">
					<li class="dsp-form-group dspdp-form-group">
                        <span class="dsp-xs-3 dspdp-col-sm-3 dspdp-control-label">
                            <?php echo language_code( 'DSP_SEND_TO' ); ?>:</span>
						<div class="dsp-xs-8 dspdp-col-sm-8">
							<?php
							$check_user_favourites = $wpdb->get_var( "SELECT COUNT(*) FROM $dsp_user_favourites_table where  user_id=$user_id" );
							if ( $get_receiver_id != "" ) {
								$display_receiver_name = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID = '$get_receiver_id'" );
								?>
								<input type="text" class="field1 dsp-xs-8 dsp-form-control" name="receiver_name"
								       value="<?php echo $display_receiver_name->display_name; ?>"/>
								<input type="hidden" class="field1 dsp-xs-8 form-control" name="friend_id"
								       value="<?php echo $get_receiver_id ?>"/>
								<?php
							} else if ( isset( $reply_friend_id ) && $reply_friend_id != "" ) {
								$check_user          = $wpdb->get_var( "SELECT COUNT(*) FROM $dsp_my_friends_table where friend_uid=$reply_friend_id and user_id=$user_id" );
								$display_sender_name = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID = '$reply_friend_id'" );
								?>
								<input type="text" class="field1 dsp-xs-8 dsp-form-control" name="receiver_name"
								       value="<?php echo $display_sender_name->display_name; ?>"/>
								<input type="hidden" class="field1 dsp-xs-8 dsp-form-control" name="friend_id"
								       value="<?php echo $reply_friend_id ?>"/>
							<?php } else if ( $check_user_favourites > 0 ) {
								?>
								<select name="friend_id" class="field1 dsp-xs-8 dsp-form-control dspdp-form-control">
									<option value="0"><?php echo language_code( 'DSP_SELECT_OPTION' ); ?></option>
									<?php

									//$myfriends_list = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table  fav INNER JOIN $dsp_my_friends_table fren ON  fav.user_id = fren.user_id where user_id ='$user_id' ");
									$myfriends_list = $wpdb->get_results( "Select * FROM (SELECT  favourite_user_id   FROM $dsp_user_favourites_table   where user_id ='$user_id'  UNION  SELECT  friend_uid FROM $dsp_my_friends_table fren where user_id ='$user_id') as tjoin " );
									foreach ( $myfriends_list as $friends ) {
										$chk_user_exist      = $wpdb->get_var( "SELECT count(*) FROM $dsp_user_table WHERE ID = '$friends->favourite_user_id'" );
										$display_friend_name = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID = '$friends->favourite_user_id'" );

										if ( $chk_user_exist != 0 ) {
											if ( isset( $reply_friend_id ) && $friends->user_id == $reply_friend_id && $request_Action == "Reply" ) {
												?>
												<option value="<?php echo $friends->favourite_user_id; ?>"
												        selected="selected"><?php echo $display_friend_name->display_name; ?></option>
											<?php } else if ( $friends->user_id == $friend_id ) { ?>
												<option value="<?php echo $friends->favourite_user_id; ?>"
												        selected="selected"><?php echo $display_friend_name->display_name; ?></option>
											<?php } else { ?>
												<option
													value="<?php echo $friends->favourite_user_id; ?>"><?php echo $display_friend_name->display_name; ?></option>
												<?php
											}
										}
									}
									?>
								</select>
							<?php } else { ?>
								<select name="friend_id" class="field1  dspdp-form-control">
									<option value="0"><?php echo language_code( 'DSP_SELECT_OPTION' ); ?></option>
									<?php
									$myfriends_list = $wpdb->get_results( "SELECT * FROM $dsp_my_friends_table where friend_uid ='$user_id' And approved_status='Y' Order by friend_id" );
									foreach ( $myfriends_list as $friends ) {
										$display_friend_name = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID = '$friends->user_id'" );

										if ( isset( $reply_friend_id ) && $friends->user_id == $reply_friend_id && $request_Action == "Reply" ) {
											?>
											<option value="<?php echo $friends->user_id; ?>"
											        selected="selected"><?php echo $display_friend_name->display_name; ?></option>
										<?php } else if ( $friends->user_id == $friend_id ) { ?>
											<option value="<?php echo $friends->user_id; ?>"
											        selected="selected"><?php echo $display_friend_name->display_name; ?></option>
										<?php } else { ?>
											<option
												value="<?php echo $friends->user_id; ?>"><?php echo $display_friend_name->display_name; ?></option>
											<?php
										}
									}
									?>
								</select>
							<?php } ?>
						</div>

					</li>

					<li class="dsp-form-group dspdp-form-group ">
                        <span
	                        class="dsp-xs-3  dspdp-col-sm-3 dspdp-control-label"><?php echo language_code( 'DSP_SUBJECT' ) ?>
	                        :</span>
						<div class="dsp-xs-8  dspdp-col-sm-8">
							<input type="text" class="field1 dsp-form-control dspdp-form-control" name="txtSubject"
							       value="<?php if ( isset( $reply_Subject ) && $request_Action == "Reply" ) {
								       echo $reply_Subject;
							       } else {
								       echo $subject;
							       } ?>"
							       required/>
						</div>

					</li>
					<li class="dsp-form-group dspdp-form-group">
                        <span
	                        class="dsp-xs-3 dspdp-col-xs-3 dspdp-control-label"><?php echo language_code( 'DSP_MESSAGE' ) ?>:</span>
						<div class="dsp-xs-8 dsp-message  dspdp-col-sm-8">
							<div style="/*float:left; width:80%;*/">
								<div class="dsp-from-info">
									<div><?php if ( isset( $reply_message_content1 ) ) {
											echo $reply_message_content1;
										} ?></div>
									<div><?php if ( isset( $reply_message_content2 ) ) {
											echo $reply_message_content2;
										} ?></div>
									<div><?php if ( isset( $reply_message_content3 ) ) {
											echo $reply_message_content3;
										} ?></div>
									<div><?php if ( isset( $reply_message_content4 ) ) {
											echo $reply_message_content4;
										} ?></div>
								</div>
                                <textarea class="dsp-form-control dsp-textarea dspdp-form-control" name="txtmessage"
                                          rows="10" style="/*height:100px; width:60%*/"
                                          required><?php if ( isset( $reply_message_contentmain ) && $request_Action == "Reply" ) {
		                                echo "\n\n" . $reply_message_contentmain;
	                                } else {
		                                echo $message;
	                                } ?></textarea>
							</div>
						</div>
					</li>

					<li class="dsp-form-group dspdp-form-group" style="/*float:left; width:100%;*/">
						<span class="dsp-xs-3  dspdp-col-sm-3  dspdp-control-label"></span>
						<div class="dsp-xs-8  dspdp-col-sm-8">
							<input type="hidden" name="mode" value="sent"/>
							<strong><input type="button" class=" dspdp-btn dspdp-btn dspdp-btn-default" name="sent1"
							               value="<?php echo language_code( 'DSP_SEND_MSG_BUTTON' ); ?>"
							               onclick="send_email_function();"></strong>&nbsp;&nbsp;
						</div>
					</li>
				</ul>
			</div>
		</div>
	</form>
<?php } ?>
