<?php
$user_id = $_REQUEST['user_id'];

$member_id = $_REQUEST['member_id'];

$dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_member_audios = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;



$total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_audios where user_id='$member_id' AND status_id=1");


// ----------------------------------Check member privacy Settings------------------------------------



$check_user_privacy_settings = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE   	view_my_audio='Y' AND user_id='$member_id'");



if (($check_user_privacy_settings > 0) && ($user_id != $member_id)) {  // check user privacy settings
    $check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'");



    if ($check_my_friends_list <= 0) {   // check member is not in my friend list 
        ?>

        <div align="center"><?php echo language_code('DSP_CANT_VIEW_MEM_VIDEOS'); ?></div>
        <?php
    } else {   // -----------------------------else Check member is in my friend list ---------------------------- // 
        ?>


        <div class="swipe_div" id="mainAudio" >
            <ul id="swipe_ulAudio" class="dsp_ul_pic gallery"  style="padding-left:0px; text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">

                <?php
                $member_exist_audios = $wpdb->get_results("SELECT * FROM $dsp_member_audios WHERE user_id ='$member_id' AND status_id='1' ORDER BY date_added ");

                foreach ($member_exist_audios as $member_audios) {
                    $audio_file_name = $member_audios->file_name;

                    $private = $member_audios->private_audio;

                    $audio_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_audios/user_" . $member_id . "/" . $audio_file_name;

                    $player_path = $pluginpath . "flash/player_mp3.swf";
                    ?>

                    <li style="float:left;margin-right:16px;width:85px;">
                        <div style="text-align: center;width: 100%">

                            <?php
                            if ($private == 'Y') {
                                if ($user_id == $member_id) {
                                    ?>

                                    <div class="audio-box">
                                        <img src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/music3.png'; ?>" height="50px" border="0" align="center" />
                                        <?php echo $audio_file_name; ?> 

                                    </div>
                                    <div style="float:left;text-align:center;padding-top: 10px;font-size: 16px;" align="center">
                                        <a onclick="playAudio('<?php echo $audio_path; ?>', '<?php echo $i; ?>')">
                                            <img id="play<?php echo $i; ?>" src="images/play.png" style="height: 30px; width: 30px;"/>	
                                        </a>
                                        <a onclick="stopAudio('<?php echo $i; ?>')">
                                            <img src="images/stop.png" style="height: 30px; width: 30px;"/>
                                        </a>
                                    </div>

                                <?php } else {
                                    ?>
                                    <div class="audio-box">
                                        <img src="<?php echo $imagepath ?>plugins/dsp_dating/images/private-audio.jpg" style="width:85px; height:85px;" class="img2" align="left" />
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="audio-box">
                                    <img src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/music3.png'; ?>" height="50px" border="0" align="center" />
                                    <?php echo $audio_file_name;
                                    ?>
                                </div>
                                <div style="float:left;text-align:center;padding-top: 10px;font-size: 16px;" align="center">
                                    <a onclick="playAudio('<?php echo $audio_path; ?>', '<?php echo $i; ?>')">
                                        <img id="play<?php echo $i; ?>" src="images/play.png" style="height: 30px; width: 30px;"/>	
                                    </a>
                                    <a onclick="stopAudio('<?php echo $i; ?>')">
                                        <img src="images/stop.png" style="height: 30px; width: 30px;"/>
                                    </a>
                                </div>

                            <?php } ?>


                        </div>
                    </li>          


                <?php } ?>

            </ul>

        </div>




        <?php
    }   // ------------------------------------------------- End if Check in my friend list --------------------------------- //
} else {



// -------------------------------------- else  Privacy Setting for Everyone ------------------------------------------- // 
    ?>





    <div class="swipe_div" id="mainAudio" >
        <ul id="swipe_ulAudio" class="dsp_ul_pic gallery"  style="padding-left:0px; text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">

            <?php
            $member_exist_audios = $wpdb->get_results("SELECT * FROM $dsp_member_audios WHERE user_id = '$member_id' AND status_id='1' ORDER BY date_added ");
            $i = 0;
            foreach ($member_exist_audios as $member_audios) {

                $audio_file_name = $member_audios->file_name;

                $private = $member_audios->private_audio;

                $audio_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_audios/user_" . $member_id . "/" . $audio_file_name;

                $player_path = $pluginpath . "flash/player_mp3.swf";
                ?>

                <li style="float:left;margin-right:16px;width:85px;">	 

                    <?php
                    if ($private == 'Y') {

                        if ($user_id == $member_id) {
                            ?>

                            <div class="audio-box">
                                <img src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/music3.png'; ?>" height="50px" border="0" align="center" />
                                <?php echo $audio_file_name; ?>
                            </div>

                            <div style="float:left;text-align:center;padding-top: 10px;font-size: 16px;" align="center">

                                <a onclick="playAudio('<?php echo $audio_path; ?>', '<?php echo $i; ?>')">
                                    <img id="play<?php echo $i; ?>" src="images/play.png" style="height: 30px; width: 30px;"/>	
                                </a>
                                <a onclick="stopAudio('<?php echo $i; ?>')">
                                    <img src="images/stop.png" style="height: 30px; width: 30px;"/>
                                </a>

                            </div>


                            <?php
                        } else {
                            ?>
                            <div class="audio-box">
                                <img src="<?php echo $imagepath ?>plugins/dsp_dating/images/private-audio.jpg" style="width:85px; height:85px;" class="img2" align="left" />
                            </div>
                            <?php
                        }
                    } else {
                        ?>

                        <div class="audio-box">
                            <img src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/music3.png'; ?>" height="50px" border="0" align="center" />
                        </div>

                        <div style="float:left;text-align:center;padding-top: 10px;font-size: 16px;" align="center">
                            <a onclick="playAudio('<?php echo $audio_path; ?>', '<?php echo $i; ?>')">
                                <img id="play<?php echo $i; ?>" src="images/play.png" style="height: 30px; width: 30px;"/>	
                            </a>
                            <a onclick="stopAudio('<?php echo $i; ?>')">
                                <img src="images/stop.png" style="height: 30px; width: 30px;"/>
                            </a>


                        </div>


                    <?php } ?>


                </li>



                <?php
                $i++;
            } // foreach end    
            ?>

        </ul>
    </div>

<?php } ?>