<?php
/*

  Copyright (C) www.wpdating.com - All Rights Reserved!

  Author - www.wpdating.com

  WordPress Dating Plugin

  contact@wpdating.com

 */
include_once(WP_DSP_ABSPATH . 'log.php');
global $wpdb;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;  // print session USER_ID
global $wp_query;
$page_id = $wp_query->post->ID; //fetch post query string id

$posts_table = $wpdb->prefix . "posts";

$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

$member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");

$check_refresh_rate = $wpdb->get_row("SELECT * FROM $dsp_general_settings WHERE setting_name = 'refresh_rate'");

$member_pageid = $member_page_title_ID->setting_value;

$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");

$member_page_id = $post_page_title_ID->ID;  // Print Site root link

$root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";
$dateTimeFormat = dsp_get_date_timezone();
extract($dateTimeFormat);


$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path

$a = $pluginpath . "post.php";

$b = $pluginpath . "log.php";

if (is_user_logged_in()) { // CHECK MEMBER LOGIN
    ?>

    <script type="text/javascript">

        // jQuery Document

        ds = jQuery.noConflict();

        ds(document).ready(function() {

            //If user submits the form

            ds("#submitmsg").click(function() {

                var clientmsg = ds("#usermsg").val();

                if (jQuery.trim(clientmsg).length > 0) {

                    //ds("#usermsg").css({'border':'2px inset'});

                    ds.post("<?php echo $a ?>", {text: clientmsg});

                }

                else {

                    //ds("#usermsg").css({'border':'2px inset #ff0000'});

                }

                ds("#usermsg").attr("value", "");

                return false;

            });



            //Load the file containing the chat log

            function loadLog() {

                var oldscrollHeight = ds("#chatbox").attr("scrollHeight") - 20;

                ds.ajax({
                    url: "<?php echo $b ?>",
                    cache: false,
                    success: function(html) {

                        ds("#chatbox").html(html); //Insert chat log into the #chatbox div				

                        var newscrollHeight = ds("#chatbox").attr("scrollHeight") - 20;

                        if (newscrollHeight > oldscrollHeight) {

                            ds("#chatbox").animate({scrollTop: newscrollHeight}, 'normal'); //Autoscroll to bottom of div

                        }

                    }
                });

            }

            setInterval(loadLog, <?php echo $check_refresh_rate->setting_value; ?>000);	//Reload file every 2.5 seconds



            //If user wants to end session

            ds("#exit").click(function() {

                var exit = confirm("Are you sure you want to end the session?");

                if (exit == true) {
                    window.location = 'index.php?logout=true';
                }

            });

        });

    </script>
<?php } ?>
<style>

    .textlink { text-decoration:underline; }

    #chat { 

        height: 200px;

        overflow-y: scroll;

        position:relative;

    } 

</style>
<?php
// if member is login then this menu will be display 

if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
    include_once(WP_DSP_ABSPATH . "general_settings.php");
    ?>

    <div class="chat-box">

        <?php

        // ------------------ calculate date difrence -----------------------//

        function daysDifference1($endDate, $beginDate) {

            //explode the date by "-" and storing to array

            $date_parts1 = explode("-", $beginDate);

            $date_parts2 = explode("-", $endDate);



            //gregoriantojd() Converts a Gregorian date to Julian Day Count

            @$start_date = gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);

            @$end_date = gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);

            return $end_date - $start_date;
        }

// ------------------ calculate date difrence -----------------------//

        function check_membership1($access_feature_name, $user_id) {

            global $wpdb;

            $dsp_memberships_table = $wpdb->prefix . "dsp_memberships";

            $dsp_payments_table = $wpdb->prefix . "dsp_payments";

            $features_list_id = $wpdb->get_var("SELECT pay_plan_id 	FROM $dsp_payments_table where pay_user_id=$user_id");

            $pay_plan_id = $features_list_id;

            $memberships_feature_row = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table where membership_id='" . $pay_plan_id . "'");

            foreach ($memberships_feature_row as $membership_feature)
                $premium_access_feature = $membership_feature->premium_access_feature;

            if (!empty($premium_access_feature))
                $access_feature_id = explode(",", $premium_access_feature);
            else
                $access_feature_id = array('0');

            for ($i = 0; $i < count($access_feature_id); ++$i) {

                $access_feature_id[$i];

                $dsp_features_table = $wpdb->prefix . "dsp_features";

                $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table where feature_id=" . $access_feature_id[$i]);

                foreach ($access_feature_row as $access_feature)
                    $feature_name = $access_feature->feature_name;

                if (isset($feature_name) && $feature_name == $access_feature_name)
                    $name = $feature_name;
            }

            $dsp_features_table = $wpdb->prefix . "dsp_features";

            $dsp_premium_access_feature_table = $wpdb->prefix . "dsp_premium_access_feature";

            if (isset($name)) {

                $features_list_id = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_name='$name'");

                $feature_id = $features_list_id->feature_id;
            } else {

                $feature_id = 0;
            }

            $premium_access_features = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_features_table where feature_id='$feature_id'");

            if ($premium_access_features > 0) {

                $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");

                if ($check_member_payment > 0) {

                    $check_account_expire = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");

                    $start_date = $check_account_expire->start_date;

                    $payment_status = $check_account_expire->payment_status;

                    $expiration_date = $check_account_expire->expiration_date;

                    $pay_plan_days = $check_account_expire->pay_plan_days;

                    $current_date = date('Y-m-d');

                    $cal_days = daysDifference1($current_date, $start_date);



                    if ($cal_days > $pay_plan_days) {

                        if ($payment_status == '1') {

                            $wpdb->query("UPDATE $dsp_payments_table SET payment_status=2 WHERE pay_user_id = '$check_account_expire->pay_user_id'");
                        }

                        $msg = "Expired";
                    } else {

                        $msg = "Access";
                    } // End if($cal_expire_date>=$expiration_date)
                } else {

                    $msg = "Onlypremiumaccess";
                } // End if($check_member_payment>0)
            } else if ($premium_access_features == 0) {

                $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");

                if ($check_member_payment > 0) {

                    $msg = "Access";
                } else {



                   /* $memberships_feature_row = mysql_query("SELECT premium_access_feature FROM $dsp_memberships_table ");

                    while ($row = mysql_fetch_array($memberships_feature_row)) {*/
                     $rows = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table ",ARRAY_A);
                       foreach ($rows as $row) {
                        $premium_access_feature = $row['premium_access_feature'];
                        $access_feature_id = explode(",", $premium_access_feature);
                        for ($i = 0; $i < count($access_feature_id); ++$i) {

                            $access_feature_id[$i];

                            $dsp_features_table = $wpdb->prefix . "dsp_features";

                            $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table");

                            $name = "";

                            foreach ($access_feature_row as $access_feature) {

                                $feature_id = $access_feature->feature_id;

                                //echo $access_feature_id[$i]."-----------".$feature_id."<br>";

                                if ($access_feature_id[$i] == $feature_id)
                                    $name = $access_feature_id[$i];
                            }

                            //echo $name;
                            //echo "SELECT * FROM $dsp_features_table where feature_id=$name";

                            $a = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_id=$name");

                            $feature_name = $a->feature_name;

                            if ($feature_name == $access_feature_name)
                                $name1 = $feature_name;
                        }
                    }

                    //echo $name1; 

                    if (@$name1 == '') {

                        $msg = "Access";
                    } else {

                        $msg = "Onlypremiumaccess";
                    }
                }
            } else {

                $msg = "Access";
            } // End if($premium_access_features>0)

            return $msg;
        }

// End function 

        function check_free_trial_feature1($access_feature_name, $user_id) {



            global $wpdb;

            $dsp_general_settings = $wpdb->prefix . "dsp_general_settings";



            $general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_trail_gender'");

            $free_trail_gender = $general_settings->setting_value;



            $general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_email_access_gender'");

            $free_email_access_gender = $general_settings->setting_value;



            $free_trail_days_limit = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_trail_mode'");

            $free_trail_days = $free_trail_days_limit->setting_value;



            $dsp_user_table = $wpdb->prefix . "users";

            $user_registered = $wpdb->get_row("SELECT * FROM $dsp_user_table where ID=$user_id");

            $user_registered->user_registered;

            $current_date = date("Y-m-d H:i:s", time());

            /* $diff = abs(strtotime($current_date) - strtotime($user_registered->user_registered)); 

              $years   = floor($diff / (365*60*60*24));

              $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)); */

            $days = daysDifference1($current_date, ($user_registered->user_registered));



            $dsp_user_profiles = $wpdb->prefix . "dsp_user_profiles";



            $gender_field = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles where user_id=$user_id");

            $user_gender = $gender_field->gender;



            $dsp_memberships_table = $wpdb->prefix . "dsp_memberships";

            $dsp_payments_table = $wpdb->prefix . "dsp_payments";

            $pay_plan_id = $wpdb->get_var("SELECT pay_plan_id 	FROM $dsp_payments_table where pay_user_id=$user_id");



            $memberships_feature_row = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table where membership_id='" . $pay_plan_id . "'");

            foreach ($memberships_feature_row as $membership_feature)
                $premium_access_feature = $membership_feature->premium_access_feature;

            if (!empty($premium_access_feature))
                $access_feature_id = explode(",", $premium_access_feature);
            else
                $access_feature_id = array('0');

            for ($i = 0; $i < count($access_feature_id); ++$i) {

                $access_feature_id[$i];

                $dsp_features_table = $wpdb->prefix . "dsp_features";

                $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table where feature_id=" . $access_feature_id[$i]);

                foreach ($access_feature_row as $access_feature)
                    $feature_name = $access_feature->feature_name;

                if (isset($feature_name) && $feature_name == $access_feature_name)
                    $name = $feature_name;
            }

            $dsp_features_table = $wpdb->prefix . "dsp_features";

            $dsp_premium_access_feature_table = $wpdb->prefix . "dsp_premium_access_feature";

            if (isset($name)) {

                $features_list_id = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_name='$name'");

                $feature_id = $features_list_id->feature_id;
            } else {

                $feature_id = 0;
            } $premium_access_features = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_features_table where feature_id='$feature_id'");



            $check_free_email_access_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings WHERE setting_name = 'free_email_access'");

            $check_free_email_access_mode->setting_status;

            $check_force_profile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings WHERE setting_name = 'force_profile'");

            $check_force_profile_mode->setting_status;



            $user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id");



            $user_profile = $wpdb->get_row("SELECT status_id FROM $dsp_user_profiles WHERE user_id=$user_id");

            $status_id = $user_profile->status_id;



            if ($premium_access_features > 0) {

                $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");

                if ($check_member_payment > 0) {

                    $check_account_expire = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");

                    $start_date = $check_account_expire->start_date;

                    $payment_status = $check_account_expire->payment_status;

                    $expiration_date = $check_account_expire->expiration_date;

                    $pay_plan_days = $check_account_expire->pay_plan_days;

                    $current_date = date('Y-m-d');

                    $cal_days = daysDifference1($current_date, $start_date);



                    if ($cal_days > $pay_plan_days) {

                        if ($payment_status == '1') {

                            $wpdb->query("UPDATE $dsp_payments_table SET payment_status=2 WHERE pay_user_id = '$check_account_expire->pay_user_id'");
                        }

                        $msg = "Expired";
                    } else {

                        $msg = "Access";
                    } // End if($cal_expire_date>=$expiration_date)
                } else {

                    $msg = "Onlypremiumaccess";
                } // End if($check_member_payment>0)
            } else if ($premium_access_features == 0) {

                $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");

                if ($check_member_payment > 0) {

                    $msg = "Access";
                } else {

                    $rows = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table ",ARRAY_A);
                    foreach ($rows as $row) {
                        $premium_access_feature = $row['premium_access_feature'];
                        if (!empty($premium_access_feature))
                            $access_feature_id = explode(",", $premium_access_feature);
                        else
                            $access_feature_id = 0;

                        for ($i = 0; $i < count($access_feature_id); ++$i) {

                            $access_feature_id[$i];
                            $dsp_features_table = $wpdb->prefix . "dsp_features";
                            $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table");

                        foreach ($access_feature_row as $access_feature) {

                                $feature_id = $access_feature->feature_id;

                                //echo $access_feature_id[$i]."-----------".$feature_id."<br>";

                                if ($access_feature_id[$i] == $feature_id)
                                    $name = $access_feature_id[$i];
                            }

                            //echo $name;
                            //echo "SELECT * FROM $dsp_features_table where feature_id=$name";

                            $a = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_id=$name");

                            $feature_name = $a->feature_name;

                            if (isset($feature_name) && $feature_name == $access_feature_name)
                                $name1 = $feature_name;
                        }
                    }

                    //echo $name1; 

                    if (@$name1 == '') {

                        $msg = "Access";
                    } else if (($free_trail_gender == 1) && ($user_gender == 'M')) {

                        if ($days <= $free_trail_days) {

                            $msg = "Access";
                        } else {//Expired	
                            $msg = "Expired";
                        }
                    } else if (($free_trail_gender == 2) && ($user_gender == 'F')) {

                        if ($days <= $free_trail_days) {

                            $msg = "Access";
                        } else {//Expired	
                            $msg = "Expired";
                        }
                    } else if (($free_trail_gender == 3)) {

                        if ($days <= $free_trail_days) {

                            $msg = "Access";
                        } else {//Expired	
                            $msg = "Expired";
                        }
                    } else if ($user_profile_exist == 0) {

                        $msg = "NotExist";
                    } else if ($status_id == 0) {

                        $msg = "Approved";
                    } else {

                        $msg = "Onlypremiumaccess";
                    }
                }
            } else {
                $msg = "NoAccess";
            }
            return $msg;
        }

        if ($check_free_mode->setting_status == "N"){  // free mode is off 
            $access_feature_name = "Group Chat";
            if ($check_free_trail_mode->setting_status == "N") {
                $check_membership_msg = check_membership1($access_feature_name, $user_id);

                if ($check_membership_msg == "Expired") {
                    ?>

                    <p><?php echo language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE_A'); ?> <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>" class="textlink dspdp-btn dspdp-btn-default"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a></p>

                <?php } else if ($check_membership_msg == "Onlypremiumaccess") { ?>

                    <p><?php echo language_code('DSP_PREMIUM_MEMBER_CHAT_MESSAGE_A'); ?> <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"  class="textlink dspdp-btn dspdp-btn-default"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a></p>

                <?php } else if ($check_membership_msg == "Access") { ?>

                    <div class="form-chat dspdp-spacer-md">
                        <?php
                            $dsp_users_table = $wpdb->prefix . "users";
                            $sender_name = $wpdb->get_row("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");
                            $user_login = $sender_name->user_login;
                            $_SESSION['name'] = $user_login;
                        ?>
                        <div id="wrapper" class="dspdp-panel dspdp-panel-default">
                            <form class="submit-chat-form" name="message" action="post" action="post.php">
                            <div class="dspdp-input-group">
                                <input class="dspdp-form-control" name="usermsg" type="text" id="usermsg" size="18" maxlength="75"  />
                                <span class="dspdp-input-group-btn"> 
                                 <input class="dspdp-btn dspdp-btn-default" name="submitmsg" type="submit"  id="submitmsg" value="<?php echo language_code('DSP_SEND_BUTTON');?>" />
                                </span>
                            </div>
                            </form>
                        </div>
                    </div>

                    <?php
                }
            } else {
                $check_member_trial_msg = check_free_trial_feature1($access_feature_name, $user_id);
                if ($check_member_trial_msg == "Expired") {
                    ?>
                    <p><?php echo language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE_A'); ?> 
                        <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>" class="textlink dspdp-btn dspdp-btn-default"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a>
                    </p>
                <?php } else if ($check_member_trial_msg == "Onlypremiumaccess") { ?>
                    <p><?php echo language_code('DSP_PREMIUM_MEMBER_CHAT_MESSAGE_A'); ?> <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"  class="textlink"<?php echo language_code('DSP_UPGRADE_HERE'); ?></a></p>
                <?php } else if ($check_member_trial_msg == "Access") { ?>
                    <div class="form-chat dspdp-spacer-md">
                        <?php
                            $dsp_users_table = $wpdb->prefix . "users";
                            $sender_name = $wpdb->get_row("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");
                            $user_login = $sender_name->user_login;
                            $_SESSION['name'] = $user_login;
                        ?>
                        <div id="wrapper" class="">
                            <?php include_once(WP_DSP_ABSPATH . 'log.php');  ?>
                            <form class="submit-chat-form " name="message" action="post" action="post.php">
                                <div class="dspdp-input-group"><input class="dspdp-form-control" name="usermsg" type="text" id="usermsg" size="18" maxlength="75"  />
                                <div class="dspdp-input-group-btn"><input class="dspdp-btn dspdp-btn-default"  name="submitmsg" type="submit"  id="submitmsg" value="<?php echo language_code('DSP_SEND_BUTTON');?>" /></div></div>
                            </form>
                        </div>
                    </div>
                <?php
                }
            }
        } else{
            if($_SESSION['free_member']){
            ?>
            <div class="form-chat dspdp-spacer-md">
                <?php
                $dsp_users_table = $wpdb->prefix . "users";
                $sender_name = $wpdb->get_row("SELECT user_login FROM $dsp_users_table WHERE ID='$user_id'");
                $user_login = $sender_name->user_login;
                $_SESSION['name'] = $user_login;
                ?>
                <div id="wrapper">
                    <?php include_once(WP_DSP_ABSPATH . 'log.php'); ?>
                    <form class="submit-chat-form" name="message" action="post" action="post.php">
                        <div class="dspdp-input-group">
                            <input  class="dspdp-form-control"  name="usermsg" type="text" id="usermsg" size="18"  maxlength="75"/>
                            <span class="dspdp-input-group-btn">  
                                <input class="dspdp-btn dspdp-btn-default" name="submitmsg" type="submit"  id="submitmsg" value="<?php echo language_code('DSP_SEND_BUTTON');?>" />
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        <?php }
        } 
    ?>

    </div>

<?php } else { ?>

    <div style="height:300px; text-align:center; vertical-align:middle; line-height:300px; font-weight:bold;"><?php echo language_code('DSP_MUST_LOGGEDIN_TEXT') ?></div>

<?php }