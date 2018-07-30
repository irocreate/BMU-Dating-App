<?php
@include_once('../../../../wp-config.php');

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));



//-------------------------DISPLAY ERROR OFF CODE ENDS--------------------------------

global $wpdb;
?>



<script>

    $("div#chat1").scrollTop($("div#chat1")[0].scrollHeight);

</script>



<div id="chatbox_one">
    <div id="chat1" >

        <?php
        $posts_table = $wpdb->prefix . POSTS;
        $dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;


        $dsp_chat_table = $wpdb->prefix . "dsp_chat_one";
//echo "SELECT * FROM $dsp_chat_table where (sender_id=".$_REQUEST['sender_id']." or receiver_id=".$_REQUEST['sender_id'].") and (receiver_id=".$_REQUEST['receiver_id']." or sender_id=".$_REQUEST['receiver_id'].") order by chat_id limit 0, 100";
        @$chat_detail_table = $wpdb->get_results("SELECT * FROM $dsp_chat_table where (sender_id=" . $_REQUEST['sender_id'] . " or receiver_id=" . $_REQUEST['sender_id'] . ") and (receiver_id=" . $_REQUEST['receiver_id'] . " or sender_id=" . $_REQUEST['receiver_id'] . ") order by chat_id limit 0, 100");

        foreach ($chat_detail_table as $chat_detail) {

            $sender_id = $chat_detail->sender_id;

            $chat_text = $chat_detail->chat_text;

            $pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path
            $dsp_smiley = $wpdb->prefix . DSP_SMIILEY;
            $smiley_res = $wpdb->get_results("SELECT * FROM `$dsp_smiley` ORDER BY `id` ASC");

            foreach ($smiley_res as $smiley_rw) {
                $chat_text = str_replace($smiley_rw->sign, "<img src=" . $pluginpath . "images/smilies/" . $smiley_rw->image . ">", $chat_text);
            }


            $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;

            $sender_name = $wpdb->get_var("SELECT user_login FROM $dsp_users_table WHERE ID='$sender_id'");

            $user_login = $sender_name;

            $_SESSION['name'] = $user_login;
            ?>



            <div><b><a onclick="viewProfile('<?php echo $sender_id ?>', 'my_profile')"  style='text-decoration:underline;'><?php echo ucfirst($user_login); ?></a></b>: <?php echo $chat_text; ?><br></div>



        <?php }
        ?>

    </div></div>	