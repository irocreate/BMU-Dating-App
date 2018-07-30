<?php
/*
Copyright (C) www.wpdating.com - All Rights Reserved!
Author - www.wpdating.com
WordPress Dating Plugin
contact@wpdating.com
*/
global $wpdb;
global $current_user;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_chat_request = $wpdb->prefix . DSP_CHAT_REQUEST_TABLE;
$user_id = $current_user->ID;
$member_id = get('mem_id');
$check_user_blocked = $wpdb->get_var("select count(*) from $dsp_blocked_members_table where user_id='$member_id' and block_member_id='$user_id'");
$displayed_member_name = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$member_id'");
if ($check_user_blocked == 0) {
if (get('action')) {
if (get('action') == 'send_request') {
$check_request = $wpdb->get_var("select count(*) from $dsp_chat_request where sender_id='$user_id' and receiver_id='$member_id' and request_status=0");
if ($check_request == 0) {
$insert = $wpdb->query("INSERT INTO $dsp_chat_request SET sender_id='$user_id',receiver_id='$member_id', request_status=0, time='" . date('g:i A') . "', date='" . date('Y-m-d') . "'");
} else {

$update = $wpdb->query("update $dsp_chat_request SET request_status=0, time='" . date('g:i A') . "', date='" . date('Y-m-d') . "' where sender_id='$user_id' and receiver_id='$member_id'");
}
}
if (get('action') == 'accept_request') {
$update = $wpdb->query("update $dsp_chat_request SET request_status=1, time='" . date('g:i A') . "', date='" . date('Y-m-d') . "' where sender_id='$member_id' and receiver_id='$user_id'");
}
}
}
$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path
$a = $pluginpath . "post_one.php?sender_id=" . $user_id . "&receiver_id=" . get('mem_id');
$b = $pluginpath . "log_tab_one.php?sender_id=" . $user_id . "&receiver_id=" . get('mem_id');
$dsp_smiley = $wpdb->prefix . DSP_SMIILEY;
$smiley_result = $wpdb->get_results("SELECT * FROM `$dsp_smiley` ORDER BY `id` ASC");
$smiley = '<div class="chat_smiley">';
foreach ($smiley_result as $smiley_row) {
$smiley.='<a id="add_smiley">';
$smiley.='<img src="' . $pluginpath . 'images/smilies/' . $smiley_row->image . '" title="' . $smiley_row->sign . '" alt="'. $smiley_row->sign .'">';
$smiley.='</a>';
}
$smiley.='</div>';
?>
<script type="text/javascript">
// jQuery Document
dss = jQuery.noConflict();
dss(document).ready(function() {
//If user submits the form
dss("#add_smiley img").click(function() {

var sign = dss(this).attr('title');
//alert(sign);
var clientmsg = dss("#usermsg1").val();
dss("#usermsg1").val(clientmsg + sign);
return false;

});
dss("#submitmsg1").click(function() {
var clientmsg = dss("#usermsg1").val();
if (jQuery.trim(clientmsg).length > 0) {
//dss("#usermsg").css({'border':'2px inset'});
dss.post("<?php echo $a ?>", {text: clientmsg});
}

dss("#usermsg1").attr("value", "");
return false;
});

//Load the file containing the chat log
function loadLog() { 
var oldscrollHeight = dss("#chatbox1").attr("scrollHeight") - 20;
dss.ajax({
url: "<?php echo $b ?>",
cache: false,
success: function(html) {
	dss("#chatbox1").html(html); //Insert chat log into the #chatbox div				
	var newscrollHeight = dss("#chatbox1").attr("scrollHeight") - 20;
	if (newscrollHeight > oldscrollHeight) {
		dss("#chatbox1").animate({scrollTop: newscrollHeight}, 'normal'); //Autoscroll to bottom of div
	}
}
});
}
setInterval(loadLog, <?php echo $check_refresh_rate->setting_value; ?>000);	//Reload file every 2.5 seconds
//If user wants to end session
	dss("#exit").click(function() {
	var exit = confirm("Are you sure you want to end the session?");
	if (exit == true) {
	window.location = 'index.php?logout=true';
	}
});
});
</script>
<style>
	.textlink { text-decoration:underline; }
	#chat1 { 
		height: 200px;
		overflow-y: scroll;
		position:relative;
	} 
</style>
<div class="dsp_box-out">
<div class="dsp_box-in">
<?php
// ------------------ calculate date difrence -----------------------//
if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $tbl_name WHERE user_id=$member_id");
if ($check_couples_mode->setting_status == 'Y') {
$member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member_id'");
} else {
$member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member_id'");
}
if ($check_user_blocked > 0) {
?>
<div style="height:300px; text-align:center; vertical-align:middle; line-height:300px; font-weight:bold;"><?php echo language_code('DSP_CHAT_USER_BLOCKED_TEXT') ?></div>
<?php } else { ?>
<div class="left-online-info">
<img style="display:block;" src="<?php echo display_thumb2_members_photo($member_id, $imagepath); ?>" width="135" height="130" border="0"  alt="<?php echo $displayed_member_name; ?>" />
<img class="icon-on-off" alt="<?php echo $displayed_member_name; ?>"  src="<?php
echo $fav_icon_image_path;
if ($check_online_user > 0)
	echo 'online';
else
	echo 'off-line';
?>-chat.jpg" title="<?php
	 if ($check_online_user > 0)
			 echo language_code('DSP_CHAT_ONLINE');
	 else
			 echo language_code('DSP_CHAT_OFFLINE');
	 ?>" border="0" /><span class="user-name">
	 <?php
	 if ($check_couples_mode->setting_status == 'Y') {
			 if ($member->gender == 'C') {
					 ?>
					<a href="<?php echo $root_link . get_username($member_id) . "/my_profile/"; ?>">
							<?php echo $displayed_member_name ?>                
					<?php } else { ?>
							<a href="<?php echo $root_link . get_username($member_id) . "/"; ?>">
									<?php echo $displayed_member_name ?>
									<?php
							}
					} else {
							?> 
							<a href="<?php echo $root_link . get_username($member_id) . "/"; ?>">
									<?php echo $displayed_member_name ?>
							<?php } ?></a>
					</span>
					</div>
					<div class="chat-box1 chat-box-page">
							<?php
							if ($check_free_mode->setting_status == "N"){  // free mode is off 
									$access_feature_name = "One to One Chat";
									if ($check_free_trail_mode->setting_status == "N") {
											$check_membership_msg = check_membership($access_feature_name, $user_id);
											if ($check_membership_msg == "Expired") {
													?>
													<p><?php echo language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE_A'); ?> <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>" class="textlink dspdp-btn dspdp-btn-default"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a></p>
											<?php } else if ($check_membership_msg == "Onlypremiumaccess") { ?>
													<p><?php echo language_code('DSP_PREMIUM_MEMBER_CHAT_MESSAGE_A'); ?> <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"  class="textlink dspdp-btn dspdp-btn-default"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a></p>
											<?php } else if ($check_membership_msg == "Access") { ?>
													<div class="form-chat">
															<?php
															$dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
															$sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");
															$user_login = $sender_name;
															$_SESSION['name'] = $user_login;
															?>
															<div id="wrapper">
																	<?php include_once(WP_DSP_ABSPATH . 'log_tab_one.php'); ?>
																	<div>	<form class="submit-chat-form" name="message" action="post" action="post_one.php" >
																					<div class="dspdp-input-group"><input class="dspdp-form-control" name="usermsg" type="text" id="usermsg1" size="18" maxlength="75"  />
																					<span class="dspdp-input-group-btn"> <input class="dspdp-btn dspdp-btn-default" name="submitmsg" type="submit"  id="submitmsg1" value="<?php echo language_code('DSP_SEND_BUTTON');?>" /></span></div>
																			</form>
																			<?php echo $smiley; ?></div></div>
														 
																	</div>
													<?php
											}
									} else {
											$check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
											if ($check_member_trial_msg == "Expired") {
													?>
													<p><?php echo language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE_A'); ?> <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>" class="textlink dspdp-btn dspdp-btn-default"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a></p>
											<?php } else if ($check_member_trial_msg == "Onlypremiumaccess") { ?>
													<p><?php echo language_code('DSP_PREMIUM_MEMBER_CHAT_MESSAGE_A'); ?> <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"  class="textlink dspdp-btn dspdp-btn-default"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a></p>
											<?php } else if ($check_member_trial_msg == "Access") { ?>
													<div class="form-chat">
															<?php
															$dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
															$sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");
															$user_login = $sender_name;
															$_SESSION['name'] = $user_login;
															?>
															<div id="wrapper">
																	<?php include_once(WP_DSP_ABSPATH . 'log_tab_one.php'); ?>
																	<div>	<form class="submit-chat-form" name="message" action="post" action="post_one.php" >
																					<div class="dspdp-input-group"><input class="dspdp-form-control" name="usermsg" type="text" id="usermsg1" size="18" maxlength="75"  />
																					 <span class="dspdp-input-group-btn"><input class="dspdp-btn dspdp-btn-default" name="submitmsg" type="submit"  id="submitmsg1" value="<?php echo language_code('DSP_SEND_BUTTON');?>" /></span></div>
																			</form>
																			<?php echo $smiley; ?></div></div>
														</div>
													<?php
											}
									}
							} else {
								 if($_SESSION['free_member']){
									?>
									<div class="form-chat">
											<?php
											$dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
											$sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");
											$user_login = $sender_name;
											$_SESSION['name'] = $user_login;
											?>
											<div id="wrapper">
													<?php include_once(WP_DSP_ABSPATH . 'log_tab_one.php'); ?>
													<div>
															<form class="submit-chat-form" name="message" action="post" action="post_one.php" >
																 <div class="dspdp-input-group"> <input class="dspdp-form-control" name="usermsg" type="text" id="usermsg1" size="18"  maxlength="75"/>
																 <span class="dspdp-input-group-btn">  <input class="dspdp-btn dspdp-btn-default" name="submitmsg" type="submit"  id="submitmsg1" value="<?php echo language_code('DSP_SEND_BUTTON');?>" /></span></div>
															</form>
															<?php echo $smiley; ?>
													</div>
											</div>
									</div>
								<?php }
							 } ?>
					</div>
			<?php } ?>
	<?php } else { ?>
			<div style="height:300px; text-align:center; vertical-align:middle; line-height:300px; font-weight:bold;"><?php echo language_code('DSP_MUST_LOGGEDIN_TEXT') ?></div>
	<?php } ?>
	</div>
</div>
