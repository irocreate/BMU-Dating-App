<?php
@include_once('../../../wp-config.php');

//include(WP_DSP_ABSPATH."general_settings.php");

global $wpdb;

$current_user = wp_get_current_user();

$user_id = $current_user->ID;  // print session USER_ID
?>
<script>
    jQuery(document).ready(function(){
        var $ij = jQuery.noConflict();

        $ij("div#chat1").scrollTop($ij("div#chat1")[0].scrollHeight);
    });
</script>
<div id="chatbox1"><div id="chat1" class="write-chat-box">
        <?php 
        $posts_table = $wpdb->prefix . POSTS;

        $dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

        $member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");

        $member_pageid = $member_page_title_ID->setting_value;

        $post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");

        $member_page_id = $post_page_title_ID->ID;  // Print Site root link

        $root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";  // Print Site root link

        $dsp_chat_table = $wpdb->prefix . DSP_CHAT_ONE_TABLE;
        if(isset($_REQUEST['sender_id']) && isset($_REQUEST['receiver_id'])) {
            @$chat_detail_table = $wpdb->get_results("SELECT * FROM $dsp_chat_table where (sender_id=" . $_REQUEST['sender_id'] . " or receiver_id=" . $_REQUEST['sender_id'] . ") and (receiver_id=" . $_REQUEST['receiver_id'] . " or sender_id=" . $_REQUEST['receiver_id'] . ") order by chat_id limit 0, 100");
            foreach ($chat_detail_table as $chat_detail) {
                $sender_id = $chat_detail->sender_id;
                $chat_text = $chat_detail->chat_text;
                $pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path
                $dsp_smiley = $wpdb->prefix . DSP_SMIILEY;
                $smiley_res = $wpdb->get_results("SELECT * FROM `$dsp_smiley` ORDER BY `id` ASC");
                foreach ($smiley_res as $smiley_rw) {
                $chat_text = str_replace($smiley_rw->sign, "<img src=" . $pluginpath . "images/smilies/" . $smiley_rw->image . ' alt="'. $smiley_rw->image .'">', $chat_text);
                }

                $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
                $sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_users_table WHERE ID='$sender_id'");
                $user_login = $sender_name;
                $_SESSION['name'] = $user_login;
            ?>
            <div><b><a href='<?php echo $root_link . get_username($sender_id); ?>' style='text-decoration:underline;'><?php echo ucfirst($user_login); ?></a></b>: <?php echo str_replace("\\", "", $chat_text); ?><br></div>
        <?php 
            } 
        }
    ?>
    </div>
</div>	