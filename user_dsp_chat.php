<?php 
/*
Copyright (C) www.wpdating.com - All Rights Reserved!
Author - www.wpdating.com
WordPress Dating Plugin
contact@wpdating.com
*/
global $wpdb,$wp_query;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;  // print session USER_ID
$page_id = $wp_query->post->ID; //fetch post query string id
$posts_table = $wpdb->prefix . POSTS;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");
$member_pageid = $member_page_title_ID->setting_value;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");
$member_page_id = $post_page_title_ID->ID;  // Print Site root link
$root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";
$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path
$a = $pluginpath . "post.php";
$b = $pluginpath . "log_tab.php";
$dsp_smiley = $wpdb->prefix . DSP_SMIILEY;
$smiley_result = $wpdb->get_results("SELECT * FROM `$dsp_smiley` ORDER BY `id` ASC");
$smiley = '<div class="chat_smiley">';
foreach ($smiley_result as $smiley_row) {
	$smiley.='<a id="add_smiley">';
	$smiley.='<img src="' . $pluginpath . 'images/smilies/' . $smiley_row->image . '" title="' . $smiley_row->sign . '" alt="' . $smiley_row->sign . '">';
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
					else {
							//dss("#usermsg").css({'border':'2px inset #ff0000'});
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
			//setInterval (loadLog, 2500);	//Reload file every 2.5 seconds
			setInterval(loadLog, <?php echo $check_refresh_rate->setting_value; ?>000);	//Reload file every 10 seconds

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
</div>
<?php
// ------------------ calculate date difrence -----------------------//
if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
?>
	<div class="box-border">
			<div class="box-pedding">  
					<div class="chat-box1">
							<?php
							if ($check_free_mode->setting_status == "N") {  // free mode is off 
									$access_feature_name = "Group Chat";
									if ($check_free_trail_mode->setting_status == "N") {
											$check_membership_msg = check_membership($access_feature_name, $user_id);
											if ($check_membership_msg == "Access") { ?>
													<div class="form-chat">
															<?php
															$dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
															$sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");
															$user_login = $sender_name;
															$_SESSION['name'] = $user_login;
															?>
															<div id="wrapper">
																	<?php include_once(WP_DSP_ABSPATH . 'log_tab.php'); ?>
																	<div>	
																		<form class="submit-chat-form" name="message" action="post" action="post.php" >
																			<div class="dspdp-input-group dspdp-spacer"><input class="dspdp-form-control" name="usermsg" type="text" id="usermsg1" size="18" maxlength="75"  />
																			<span class="dspdp-input-group-btn"><input class="dspdp-btn dspdp-btn-default" name="submitmsg" type="submit"  id="submitmsg1" value="<?php echo language_code('DSP_SEND_BUTTON');?>" /></span></div>
																		</form>
																		<?php echo $smiley; ?>
																	</div>
															</div>
													</div>
												<?php
											}else{
												include_once( WP_DSP_ABSPATH .  "dsp_print_message.php" );
											}
									} else {
											$check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
											if ($check_member_trial_msg == "Access") { ?>
												<div class="form-chat">
														<?php
														$dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
														$sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");
														$user_login = $sender_name;
														$_SESSION['name'] = $user_login;
														?>
														<div id="wrapper">
																<?php include_once(WP_DSP_ABSPATH . 'log_tab.php'); ?>
																<div>	
																	<form class="submit-chat-form" name="message" action="post" action="post.php" >
																				<div class="dspdp-input-group dspdp-spacer"><input class="dspdp-form-control" name="usermsg" type="text" id="usermsg1" size="18" maxlength="75"  />
																				<span class="dspdp-input-group-btn"><input class="dspdp-btn dspdp-btn-default"  name="submitmsg" type="submit"  id="submitmsg1" value="<?php echo language_code('DSP_SEND_BUTTON');?>" /></span></div>
																	</form>
																	<?php echo $smiley; ?>
																</div>
														</div>
													</div>
												<?php
											}else{
												include_once( WP_DSP_ABSPATH .  "dsp_print_message.php" );
											}
									
									}
							}else {
									?>
									<div class="form-chat">
											<?php
											$dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;

											$sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");
											$user_login = $sender_name;
											$_SESSION['name'] = $user_login;
											?>
											<div id="wrapper">
													<?php include_once(WP_DSP_ABSPATH . 'log_tab.php'); ?>
													<div>
															<form class="submit-chat-form" name="message" action="post" action="post.php" >
																	<div class="dspdp-input-group dspdp-spacer"><input class="dspdp-form-control" name="usermsg" type="text" id="usermsg1" size="18"  maxlength="75"/>
																	<span class="dspdp-input-group-btn"><input class="dspdp-btn dspdp-btn-default" name="submitmsg" type="submit"  id="submitmsg1" value="<?php echo language_code('DSP_SEND_BUTTON');?>" /></span></div>
															</form>
															<?php echo $smiley; ?>
													</div>
											</div>
									</div>
							<?php } ?>
					</div>
			<?php } else { ?>
					<div style="height:300px; text-align:center; vertical-align:middle; line-height:300px; font-weight:bold;"><?php echo language_code('DSP_MUST_LOGGEDIN_TEXT') ?></div>
			<?php } ?>
		</div>
	</div>
