<?php
@include_once('../../../wp-config.php');
include_once(WP_DSP_ABSPATH . 'files/includes/functions.php');
global $wpdb;
$current_user = wp_get_current_user();
$imagepath = get_option('siteurl') . '/wp-content/';  // image Path
$posts_table = $wpdb->prefix . POSTS;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");
$member_pageid = $member_page_title_ID->setting_value;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");
$counter = isset($_POST['counter']) ? $_POST['counter'] : 0;
$member_page_id = isset($post_page_title_ID) ? $post_page_title_ID->ID : "";
$member_page_name = isset($post_page_title_ID) ? $post_page_title_ID->post_name : ""; 
// Print Site root link
$root_link = get_bloginfo('url') . "/" . $member_page_name . "/";
$dsp_chat_request = $wpdb->prefix . DSP_CHAT_REQUEST_TABLE;
$dsp_user_table = $wpdb->prefix . "users";
$user_id = $current_user->ID;  // print session USER_ID
if ($user_id != "" && $user_id != 0)
{
    $dsp_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
    $time = time();
    $status = 'Y';
    $wpdb->query("UPDATE $dsp_online_table  SET time='$time',status='$status' WHERE `user_id` = '$user_id'");
}
$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path
$fav_icon_image_path = $pluginpath . "images/"; // fav,chat,star,friends,mail Icon image path
$request_row = $wpdb->get_row("select * from $dsp_chat_request where receiver_id='$user_id' and request_status=0");
if ($request_row != null) {
    $photo_member_id = $request_row->sender_id;
    $displayed_member_name = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$photo_member_id'");
    $audio_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/chat_popup_audio/test.mp3" ;
    ?>
    <div class="dsp-md-12 dspdp-spacer-md">
        <div class="chat-request-box">
            <?php  if( $counter < 1 ){  ?>
                <audio controls="" autoplay="autoplay" name="media"  hidden="hidden"><source src="<?php echo $audio_path; ?>" type="audio/mp3"></audio>
            <?php } ?>
            <div class="dspdp-clearfix">
                <span class="dspdp-h5 dspdp-pull-left dspdp-box-title dspdp-bold">
                    <?php echo language_code('DSP_POPUP_CHAT_REQUEST').' : '; ?>
                </span>
                <strong><?php echo $displayed_member_name; ?></strong>
                <img id="chat_request_reject" class="close  dspdp-pull-right" src="<?php echo $fav_icon_image_path ?>close-chat.jpg" border="0" alt="Close"/>
            </div>
            <div class="chat-req">
                <img class="user-image dspdp-img-responsive dspdp-block-center dspdp-spacer" src="<?php echo display_thumb2_members_photo($photo_member_id, $imagepath); ?>" border="0" alt="<?php echo $displayed_member_name;?>"/><br><br>
                <span class="btn-reuest dspdp-block dspdp-text-center">
                    <a target="_blank" id="chat_request_approve" style="text-decoration:none;" href="<?php echo $root_link . "view/one_on_one_chat/mem_id/" . $photo_member_id . "/action/accept_request/"; ?>">
                        <input class="dspdp-btn dspdp-btn-success dspdp-btn-sm" type="button" value="Accept" />
                    </a>
                    <input id="chat_request_reject" class="reject dspdp-btn dspdp-btn-danger dspdp-btn-sm " type="button" value="Reject" />
                    <input type="hidden" id="chat_request_sender_id" value="<?php echo $photo_member_id; ?>" />
                </span>
            </div>
        </div>
    </div>
</div>
<?php
}