<?php
/**
* This file overwrites default profiler_header.php of dating plugin and is only accessible with love match themes.
* @theme @version 1.0
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
*/

//-----------------Soniya has changed the file according to mobile -----------------------------------
$dspMbDir = WP_DSP_ABSPATH . "mobile";
global $wpdb;
include_once(WP_DSP_ABSPATH . "general_settings.php");  // include general settings file

// MEMBERS PAGE POST ID 
global $wp_query;
$page_id = $wp_query->post->ID; //fetch post query string id
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
$posts_table = $wpdb->prefix . POSTS;
$insertMemberPageId = "UPDATE $dsp_general_settings SET setting_value = '$page_id' WHERE setting_name ='member_page_id'";
$wpdb->query($insertMemberPageId);
$posts_table = $wpdb->prefix . POSTS;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$page_id'");

// ROOT PATH 
$root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";  // Print Site root link

update_option('members_page_id', $post_page_title_ID->ID);
update_option('members_page_name', $post_page_title_ID->post_name);
// get the value from database
$inkthemes_altstylesheet = $wpdb->get_var("select option_value from " . $wpdb->prefix . "options where option_name='inkthemes_altstylesheet'");
$temp_color = '';
if ($inkthemes_altstylesheet == 'none')
    $temp_color = '#525252';
if ($inkthemes_altstylesheet == 'green')
    $temp_color = '#0b720b';

if ($inkthemes_altstylesheet == 'blue')
    $temp_color = '#294582';

if ($inkthemes_altstylesheet == 'black')
    $temp_color = '#525252';



if ($inkthemes_altstylesheet == 'darkcyan')
    $temp_color = '#0b7474';



if ($inkthemes_altstylesheet == 'magenta')
    $temp_color = '#b817b8';



if ($inkthemes_altstylesheet == 'orange')
    $temp_color = '#fe7200';



if ($inkthemes_altstylesheet == 'red')
    $temp_color = '#a50c03';



if ($inkthemes_altstylesheet == 'yellow')
    $temp_color = '#a08600';



$DSP_GENERAL_SETTINGS_TABLE = $wpdb->prefix . "dsp_general_settings";

$dsp_option = $wpdb->prefix . "options";




include_once(WP_DSP_ABSPATH . 'dsp_validation_functions.php');
include_once(WP_DSP_ABSPATH . 'functions.php');

/////////pagination dynamic color from admin start/////////////////

$theme_name = $wpdb->get_var("SELECT option_value FROM $dsp_option where option_name = 'stylesheet'");

if ($theme_name == 'method' || $theme_name == 'elegance') {
?>
[raw]
<?php
}
$mobileStatus = $wpdb->get_var("SELECT setting_status FROM $DSP_GENERAL_SETTINGS_TABLE where setting_name = 'mobile'");
if (file_exists($dspMbDir) && is_dir($dspMbDir) && $mobileStatus == 'Y') { // mobile folder exist 
    $wptouch_plugin_obj = new WPtouchPlugin();
    if ($wptouch_plugin_obj->applemobile || $wptouch_plugin_obj->desired_view == 'mobile') {
        include_once(WP_DSP_ABSPATH . "mobile/files/includes/english.php");
        include_once(WP_DSP_ABSPATH . "mobile/files/includes/dsp_mail_function.php");  //include email function file
        include_once(WP_DSP_ABSPATH . "js/user_section_functions.php"); // define javascript functions file path use in user section. 
        $pluginpath = esc_url(  WP_PLUGIN_URL  . '/dsp_dating/' );  // Plugin Path
        $image_path = get_option('siteurl') . '/wp-content/';  // image Path
        global $wpdb;
        include_once(WP_DSP_ABSPATH . "mobile/include_dsp_tables.php");  // include all table names file
        $mb_image_path = $pluginpath . "mobile/images/";
        include_once(WP_DSP_ABSPATH . 'mobile/dsp_get_image.php');
    } else {
        include_once(WP_DSP_ABSPATH . "files/includes/dsp_mail_function.php");  //include email function file
        include_once(WP_DSP_ABSPATH . "js/user_section_functions.php"); // define javascript functions file path use in user section.
        $pluginpath = esc_url(  WP_PLUGIN_URL  . '/dsp_dating/' );  // Plugin Path
        $imagepath = get_option('siteurl') . '/wp-content/';  // image Path
        global $wpdb;
        include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");  // include all table names file
    }
} else {
        include_once(WP_DSP_ABSPATH . "files/includes/dsp_mail_function.php");  //include email function file
        include_once(WP_DSP_ABSPATH . "js/user_section_functions.php"); // define javascript functions file path use in user section.
        $pluginpath = esc_url(  WP_PLUGIN_URL  . '/dsp_dating/' );  // Plugin Path
        $imagepath = get_option('siteurl') . '/wp-content/';  // image Path
        global $wpdb;
        include(WP_DSP_ABSPATH . "include_dsp_tables.php");  // include all table names file
}

// CURRENT SESSION USER ID
$current_user = wp_get_current_user();
$user_id = $current_user->ID;  // print session USER_ID
// ROOT PATH 
$request_url = isset($_REQUEST['REQUEST_URI']) ? $_REQUEST['REQUEST_URI'] : '';
$pageurl = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';  // request pid
$pgurl = isset($_REQUEST['pgurl']) ? $_REQUEST['pgurl'] : '';
$fav_icon_image_path = $pluginpath . "images/"; // fav,chat,star,friends,mail Icon image path
$dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
$Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';
$lang_id = isset($_REQUEST['lid']) ? $_REQUEST['lid'] : '';
$display_status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
$siteurl = get_option('siteurl') . "/";
if($Action == 'language_status') {
        if(!empty($user_id))
        {
           if(function_exists('dsp_session_language_initialize')){
             dsp_session_language_initialize(true,$current_user,$lang_id);
            }
        }
        else
        { 
          if(function_exists('dsp_session_language_initialize')){
            dsp_session_language_initialize(false,null,$lang_id);
          }
      }
?>
<script>
  var loc = window.location.href;
  if (loc.search("Action") > -1)  {
      index = loc.indexOf("Action")
      loc = loc.substring(0, index - 1);
  }
  location.href = loc;
</script>
<?php
    }  
// FUNCTION CALCULATE AGE
if (is_user_logged_in()) {

    if (get('mem_id') != "") {

        $member_id = get('mem_id') != "" ? get('mem_id') : '';
    } else {

        $member_id = $current_user->ID;
    }
} else {

    $member_id = get('mem_id') != "" ? get('mem_id') : '';
}

$chat_popup = $pluginpath . "dsp_chat_popup.php";

$chat_popup_reject = $pluginpath . "dsp_chat_popup_reject.php";

$meet_me_request = $pluginpath . "dsp_meet_me_request.php";

$notification_file = $pluginpath . "dsp_notification.php";

$news_feed_div = $pluginpath . "dsp_user_news_feed_box.php?user_id=$user_id";

$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$user_id'");

?>
<script>
    var counter = 0,
        jqueryVersion = jQuery.fn.jquery,
        //liveOrOn =  ? 'on' : 'live' ;
        //console.log(jqueryVersion);
        dsp = jQuery.noConflict();
    
    dsp(document).ready(function() {

    dsp(".comment-delete-icon").click(function() {
    if (confirm('<?php echo language_code('DSP_DELETE_COMMENTS_ALERT'); ?>')) {
    var comment_id = dsp(this).attr('id');
            dsp.ajax({
            url: "<?php echo $pluginpath . "dsp_delete_comment.php"; ?>?comment_id=" + comment_id,
                    cache: false,
                    success: function(html) {
                    if (jQuery.trim(html) == 1) {
                    //alert(html);
                    dsp("#" + comment_id).parent().slideUp('slow', function() {
                    dsp("#" + comment_id).parent().remove();
                    });
                    }
                    }
            });
    }
    });
<?php
if (!is_user_logged_in()) {
    ?>
        dsp(".dsp_chat_widget").remove();
<?php } ?>
<?php if ($theme_name == 'twentythirteen' || $theme_name == 'ward') { ?>

        dsp(".dsp_box-in,.dsp_box-in2,.box-in,.dsp_box_in_with_scroll,.dsp_search_result_box_in").css('width', '100%');
                dsp(".chat-request-box").css('height', '136px');
<?php } ?>
    dsp(".group1").colorbox({rel:'group1', maxHeight:500, maxWidth:800<?php if ($theme_name == 'infowaytheme' ) { ?>, onComplete:function() {
        dsp("#colorbox").css({"padding-right": "42px", "padding-bottom": "42px"});
                dsp("#cboxWrapper").width(dsp(".cboxPhoto").width() + 80);
                dsp("#cboxWrapper").height(dsp(".cboxPhoto").height() + 80);
        }
<?php } ?>
    });
            dsp(".inline").colorbox({inline: true, width: "100%"});
            dsp(".divinline").colorbox({inline: true, width: "100%"});
            dsp("#click").click(function() {

    dsp('#click').css({"background-color": "#f00", "color": "#fff", "cursor": "inherit"}).text("Open this window again and this message will still be here.");
            return false;
    });
            dsp("#chooseimage").colorbox({width: "60%", height: "300px", inline: true, href: "#test-content"});
            var wid = 100;
            if (wid < 575)
            dsp("#dsp_plugin").width('100%');
            else
            dsp("#dsp_plugin").width(wid);
            if (wid < 658) {
    }

    dsp("#formID").submit(function() {

    var formData = new FormData($(this)[0]);
            dsp.ajax({
            type: "POST",
                    url: dsp(this).attr("action"),
                    data: formData,
                    async: false,
                    success: function(data) {

                    dsp.fn.colorbox({
                    html: data,
                            open: true,
                            iframe: false, // NO FRAME, JUST DIV CONTAINER?

                            height: "100%",
                            width: "60%"

                    });
                    },
                    cache: false,
                    contentType: false,
                    processData: false

            });
            return false;
    });
            var stateNCityList = function() {
                        var country = dsp(this).val();
                        country = country.replace(/ /g, '%20');
                        dsp("#state_change").load("<?php echo WPDATE_URL . "/get_state_city.php"; ?>?country=" + country);
                        dsp("#city_change").load("<?php echo WPDATE_URL . "/get_city.php"; ?>?state=0&country=" + country);
                };
            var cityList =  function() {
                        var state = dsp(this).val();
                        var country = dsp("#cmbCountry_id").val();
                        country = country.replace(/ /g, '%20');
                        state = state.replace(/ /g, '%20');
                        dsp("#city_change").load("<?php echo WPDATE_URL . "/get_city.php"; ?>?state=" + state + "&country=" + country);
                }
            if(cmpVersion(jqueryVersion, 1.7) > 0){
                dsp(document).on("change",'#cmbCountry_id',stateNCityList);
                dsp(document).on("change",'#cmbState_id',cityList);
            }else{
                dsp('#cmbCountry_id').live("change",stateNCityList);
                dsp('#cmbState_id'). live("change",cityList);
            }
           
<?php
if (is_user_logged_in()) { // CHECK MEMBER LOGIN
?>
/////////////////////////////////////---------------------------////////////////////////////
        dsp('#show_premium_div').mouseover(function() {
        dsp('#premium_div').show();
        });
                dsp('#show_premium_div').mouseout(function() {

        dsp('#premium_div').hide();
        });

        var chatRequestFn = function() {
                var sender_id = dsp("#chat_request_sender_id").val();
                dsp.ajax({
                url: "<?php echo $chat_popup_reject ?>?sender_id=" + sender_id,
                        cache: false,
                        success: function(html) {

                        dsp('#chat_popup').html("");
                        }
                });
        };
        var meetMeFn = function() {
                var value = dsp(this).val();
                var user_id = dsp("#dsp_meet_me_user").val();
                var submitted = dsp("#dsp_submitted_form").val();
                if (submitted != 1) {
                     dsp("#dsp_meet_me_box").fadeOut();
                }
                dsp.ajax({
                url: "<?php echo $meet_me_request ?>?user_id=" + user_id + "&action=" + value,
                        cache: false,
                        success: function(html) {

                        //alert(html);      

                        if (submitted == 1) {

                        dsp("#dsp_change_criteria").submit();
                        }

                        else {

                        dsp("#dsp_meet_me_box").load("<?php echo $pluginpath . "dsp_meet_me_box.php?user_id=$user_id"; ?>");
                                dsp("#dsp_meet_me_box").fadeIn();
                        }

                        }
                });
        };

        var dspChangeCriteriaSubmitFn = function() {
                        var data = dsp(this).serialize();
                                dsp("#dsp_submitted_form").val('1');
                                dsp("#dsp_meet_me_box").fadeOut();
                                dsp.ajax({
                                type: 'POST',
                                        url: "<?php echo $pluginpath . "dsp_meet_me_box.php?user_id=$user_id"; ?>",
                                        data: data,
                                        dataType: 'text',
                                        success: function(html) {

                                        dsp("#dsp_meet_me_box").html(html);
                                                dsp("#dsp_meet_me_box").fadeIn();
                                        }
                                });
                        return false;
            };
         if(cmpVersion(jqueryVersion, 1.7) > 0){
                dsp(document).on('click', '#chat_request_reject', chatRequestFn );
                dsp(document).on('click', '#dsp_meet_me_click', meetMeFn);
                dsp(document).on('submit', '#dsp_change_criteria',dspChangeCriteriaSubmitFn );
                
            }else{
                dsp('#chat_request_reject').live('click', chatRequestFn );
                dsp('#dsp_meet_me_click').live('click', meetMeFn);
                dsp('#dsp_change_criteria').live('submit', dspChangeCriteriaSubmitFn );
              
            }
                
        var  dspUpdateNewsFeedBoxFn = function() {
                    var value = dsp(this).attr('href');
                    //alert(value);

                    dsp('#news_feed_box').fadeOut();
                    dsp.ajax({
                    url: "<?php echo $news_feed_div ?>&users=" + value,
                            cache: false,
                            success: function(html) {

                            dsp('#news_feed_box').html(html);
                                    dsp('#news_feed_box').fadeIn();
                            }
                    });
                    return false;
        };
               
                dsp('.tab-button ul li a').click(function() {

        dsp('.tab-button ul li a').removeClass('active');
                var page = dsp(this).attr('href');
                dsp(this).addClass('active');
                dsp('.tab-content').removeClass('hide');
                dsp('.tab-content').addClass('hide');
                dsp('div#' + page).removeClass('hide');
                //alert(page);

                return false;
        });
        if(cmpVersion(jqueryVersion, 1.7) > 0){
            dsp(document).on('click', '#update_news_feed_box', dspUpdateNewsFeedBoxFn );
        }else{
            dsp('#update_news_feed_box').live('click', dspUpdateNewsFeedBoxFn );
        }
                /////////////////////////-------------------------------/////////////////////////////////
<?php } ?>
    });
<?php
if (is_user_logged_in()) { // CHECK MEMBER LOGIN
    if ($check_notification_mode->setting_status == 'Y') {
        ?>

            function close_notifications(id) {

            dsp('#notification').html("");
                    dsp.ajax({
                    url: "<?php echo $notification_file ?>?action=hide&id=" + id,
                            cache: false,
                            success: function(html) {

                            dsp('#notification').html(html);
                            }
                    });
            }

    <?php } ?>

function check_chat_request() 
{        
        var check_chat_request_init = setInterval(function() {

        dsp.ajax({
        type: "POST",
        url: "<?php echo $chat_popup ?>",
                cache: false,
                data: {counter:counter},
                success: function(html) {
                    if(counter==0 && /\S/.test(html))
                    {    
                        dsp('#chat_popup').html(html);
                        dsp('#chat_popup').before('<div id="chat_popup_btn"></div>');
                        dsp('#chat_popup').show();
                        dsp('#chat_popup_btn').colorbox({inline:true,href:"#chat_popup",open:true,innerWidth:"250px",onClosed:hidechatrequestbox,overlayClose:false});
                        counter = 2;
                    }
                }
        });
                clearInterval(check_chat_request_init);
                check_chat_request();
        }, <?php echo $check_refresh_rate->setting_value; ?>000);
}
dsp(document).on('click','#chat_request_approve',function(){
    dsp('#cboxClose').click();
    counter = 0;
});
dsp(document).on('click','#chat_request_reject',function(){
    dsp('#cboxClose').click();
    counter = 0;
});

function hidechatrequestbox()
{
    dsp('#chat_popup').hide();
}
        

    check_chat_request();
    <?php if ($check_notification_mode->setting_status == 'Y') { ?>

            function check_notification() {

            var check_notification_init = setInterval(function() {

            dsp.ajax({
            url: "<?php echo $notification_file ?>?action=show",
                    cache: false,
                    success: function(html) {

                    dsp('#notification').html(html);
                            if (html != "") {

                    setTimeout(function() {

                    dsp.ajax({
                    url: "<?php echo $notification_file ?>?action=hide&id=" + dsp('#notification_id').val(),
                            cache: false,
                            success: function(html) {

                                dsp('#notification').html("");
                            }
                    });
                    }, <?php echo $check_notification_time_mode->setting_value ?>000);
                    }

                    }

            });
                    clearInterval(check_notification_init);
                    check_notification();
            }, <?php echo $check_refresh_rate->setting_value; ?>000);
            }

            check_notification();
        <?php
    }
}
?>
    function show_contact_message() {

    jQuery.colorbox({html: '<p class="errormsg">You\'re not allowed to contact this member</p>', width: 400, height: 120});
    }

</script>
<?php if ($theme_name == 'twentythirteen') { ?>
<style>
    #chat_popup .chat-request-box{ height:136px;}
</style>
<?php }
// In this file we checks Admin General Settings
$contact_permission_result = check_contact_permissions($member_id);
$dsp_comments_table = $wpdb->prefix . "dsp_user_comments";
$displayUsername = ($display_user_name->setting_value == 'username') ? true : false;
$displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$member_id'");
$firstname = get_user_meta($member_id,'firstname');
if(!$displayUsername && !empty($firstname)){
    $displayed_member_name->display_name = dsp_get_fullname($member_id); 
}
$count_friends_request = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid=$user_id AND approved_status='N'");

$count_friends_comments = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_comments_table WHERE member_id=$user_id AND status_id=0");

$count_inbox_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE message_read='N' AND receiver_id=$user_id AND delete_message=0");

$count_wink_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_member_winks_table WHERE wink_read='N' AND receiver_id=$user_id");

$count_user_total_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE message_read='Y' AND receiver_id=$user_id");

$count_user_total_friends = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE user_id=$user_id AND approved_status='Y'");

$check_user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id");

$payment_status = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table WHERE pay_user_id=$user_id AND payment_status='1'");


// ------------------ calculate date difrence -----------------------//
// ------------------------------------  ONLINE USER CODE ------------------------------------------------------ //
if($user_id > 0){
    $session_id = session_id();
    $time = time();
    $time_check = $time - 600; //SET TIME 10 Minute 600
    $dsp_online_user_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE; // Table name
    //$count_online_users = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_online_user_table WHERE session='$session_id'");
    $exist_users =  $wpdb->query("SELECT 'user_id' FROM $dsp_online_user_table WHERE `user_id` = '$user_id' ");
    $isEdited = dsp_is_user_profile_edited($user_id);
    $status = ($user_id != "" && $user_id != 0) ? 'Y' : 'N';
    if($isEdited){
        if ($exist_users == "0") {
            $wpdb->query("INSERT INTO $dsp_online_user_table(session,user_id, status,time)VALUES('$session_id','$user_id','$status','$time')");
        } else {
           $wpdb->query("UPDATE $dsp_online_user_table SET time='$time',user_id='$user_id',status='$status' WHERE `user_id` = '$user_id'");
        }
    }
    $count_user_online = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_online_user_table WHERE status='Y'");

    //echo "User online : $count_user_online ";
    // if over 10 minute, delete session 

    $wpdb->query("DELETE FROM $dsp_online_user_table WHERE time<$time_check");
}

// ------------------------------------  ONLINE USER CODE ------------------------------------------------------ //

if (!is_user_logged_in()) {

    if (isset($_REQUEST['pid'])) {
        ?>

        <script>

            alert("<?php echo language_code('DSP_NOT_LOGGEDIN_MESSAGE') ?>");
                    location.href = "<?php echo $root_link ?>";

        </script>

        <?php
    }
}
////////////////////////credit _ balance low email/////////////////////////////

$chk_credit_row = $wpdb->get_row("select * from $dsp_credits_usage_table where user_id='$user_id' and no_of_credits='2' and email_sent='0'");
if (($chk_credit_row != null) && dsp_issetGivenEmailSetting($user_id,'credit_balance_low')) {
    $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='21'");
    $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$user_id'");
    $reciver_name = $reciver_details->display_name;
    $receiver_email_address = $reciver_details->user_email;
    $siteurl = get_option('siteurl');
    $email_subject = $email_template->subject;
    $email_message = $email_template->email_body;
    $email_message = str_replace("<#CREDIT-BALANCE#>", $chk_credit_row->no_of_credits, $email_message);
    $email_message = str_replace("<#URL#>", $siteurl, $email_message);
    $MemberEmailMessage = $email_message;
    $to = $receiver_email_address;
    $subject = $email_subject;
    $message = $MemberEmailMessage;
    $admin_email = get_option('admin_email');
    $from = $admin_email;
    $headers = "From: $from";
    // wp_mail($to, $subject, $message, $headers);
    $wpdating_email  = Wpdating_email_template::get_instance();
    $result = $wpdating_email->send_mail( $to, $subject, $message );
    $wpdb->update($dsp_credits_usage_table, array('email_sent' => 1), array('user_id' => $user_id));
}
/////////////////////////credit _ balance low email/////////////////////////////
// --------------notification ----------------//

$notification = '<div id="notification"></div>';

//---- Check if Mobile folder exist if not redirect him to desktop folder-----------------

if (file_exists($dspMbDir) && is_dir($dspMbDir) && $mobileStatus == 'Y') { //   mobile folder exist
    if ($wptouch_plugin_obj->applemobile || $wptouch_plugin_obj->desired_view == 'mobile') { // user is acceccing site from mobile redirect him in the mobile folder
        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
            include_once(WP_DSP_ABSPATH . "mobile/member_dsp_header.php");
        } else {

            include_once(WP_DSP_ABSPATH . "mobile/guest_dsp_header.php");
        }
    } else {                                      // user accessing site form desktop version
        ?><div id="dsp_plugin" style="width:100%;" ><?php
                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN 
                    /*DISPLAY MEMBER NAME 
                    DISPLAY LOGIN USER NAME 
                    DISPLAY MEMBER NAME 
                    */

                    include lm_get_template_part( "members/loggedin/member_dsp_header.php");
                } else {
                    include lm_get_template_part( "members/withoutloggedin/guest_dsp_header.php");
                }
            ?></div><?php
        }
    } else {                              // mobile folder not exist redirect user into desktop version
        ?><div id="dsp_plugin" style="width:100%;" class="touch_to  clearfix"><?php
        if (is_user_logged_in()) {   // CHECK MEMBER LOGIN 
            /*
            DISPLAY MEMBER NAME
            DISPLAY LOGIN USER NAME
            DISPLAY MEMBER NAME 
            */
            include lm_get_template_part( "members/loggedin/member_dsp_header.php", __FILE__ );
        } else { 
            include lm_get_template_part( "members/withoutloggedin/guest_dsp_header.php");
        }
        ?>
        </div><?php
    }
    if ($theme_name == 'method' || $theme_name == 'elegance') {
        ?>
    [/raw]
    <?php
}
