<?php
$user_id = $_REQUEST['user_id'];

$member_id = $_REQUEST['member_id'];

$dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_member_videos = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;


// ----------------------------------Check member privacy Settings------------------------------------



$check_user_privacy_settings = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE   	view_my_video='Y' AND user_id='$member_id'");



if (($check_user_privacy_settings > 0) && ($user_id != $member_id)) {  // check user privacy settings
    $check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'");



    if ($check_my_friends_list <= 0) {   // check member is not in my friend list 
        ?>
        <div align="center"><?php echo language_code('DSP_CANT_VIEW_MEM_VIDEOS'); ?></div>

        <?php
    } else {   // -----------------------------else Check member is in my friend list ---------------------------- // 
        ?>

        <div class="swipe_div" id="mainVideo" style="height: 98px">
            <ul id="swipe_ulVideo"  style="padding-left:0px;text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">
                <?php
                $member_exist_videos = $wpdb->get_results("SELECT * FROM $dsp_member_videos WHERE user_id = '$member_id' AND status_id='1' ORDER BY date_added ");

                $i = 0;

                foreach ($member_exist_videos as $member_videos) {

                    $video_file_name = $member_videos->file_name;

                    $private = $member_videos->private_video;

                    $video_ext = explode(".", $video_file_name);

                    $video_path = "http://" . $_SERVER['HTTP_HOST'] . "/wp-content/uploads/dsp_media/user_videos/user_" . $member_id . "/" . $video_file_name;
                    ?>

                    <li class="ivew-list">
                        <div style="text-align: center;width: 100%">

                            <?php
                            if ($private == 'Y') {

                                if ($user_id == $member_id) {
                                    ?>
                                    <div class="video-box">
                                        <div class="iPhonePlayer" style="display:none;"> 
                                         <video id="player" width="75" height="75" controls="controls" autostart="false">
                                            <source src="<?php echo $video_path ?>"  />
                                          </video>
                                        </div>
                                         <div class="AndroidPlayer" style="display:none;"> 
                                        <a onclick="playMyVideo('<?php echo $video_path ?>')">
                                            <img src="<?php echo get_bloginfo('url') ?>/wp-content/plugins/dsp_dating/m1/images/play.jpeg" style="width: 85px;height: 85px">
                                        </a>
                                         </div>
                                    </div>


                                    <?php
                                } else {
                                    ?>

                                    <img src="<?php echo $imagepath ?>plugins/dsp_dating/images/private-video.jpg" style="width:85px; height:85px;" class="img2" align="left" />

                                    <?php
                                }
                            } else {
                                ?>
                                <div class="video-box">
                                    <div class="iPhonePlayer" style="display:none;"> 
                                    <video id="player" width="75" height="75" controls="controls" autostart="false">
                                            <source src="<?php echo $video_path ?>"  />
                                          </video>
                                        <div>
                                            <div class="AndroidPlayer" style="display:none;"> 
                                    <a onclick="playMyVideo('<?php echo $video_path ?>')">
                                        <img src="<?php echo get_bloginfo('url') ?>/wp-content/plugins/dsp_dating/m1/images/play.jpeg" style="width: 85px;height: 85px">
                                    </a>
                                                 <div>
                                </div>
                            <?php }
                            ?>



                        </div>
                    </li>          

                    <?php
                }
                ?>

            </ul>
        </div>


        <?php
    }   // ------------------------------------------------- End if Check in my friend list --------------------------------- //
} else {



// -------------------------------------- else  Privacy Setting for Everyone ------------------------------------------- // 
    ?>



    <div class="swipe_div" id="mainVideo" style="height: 98px">
        <ul id="swipe_ulVideo"  style="padding-left:0px;text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">

            <?php
            $member_exist_videos = $wpdb->get_results("SELECT * FROM $dsp_member_videos WHERE user_id = '$member_id' AND status_id='1'  ORDER BY date_added ");

            $i = 0;

            foreach ($member_exist_videos as $member_videos) {

                $video_file_name = $member_videos->file_name;

                $private = $member_videos->private_video;

                $video_ext = explode(".", $video_file_name);

                $video_path = "http://" . $_SERVER['HTTP_HOST'] . "/wp-content/uploads/dsp_media/user_videos/user_" . $member_id . "/" . $video_file_name;
                ?>


                <li class="ivew-list">
                    <div style="text-align: center;width: 100%">
                        <?php
                        if ($private == 'Y') {

                            if ($user_id == $member_id) {
                                ?>
                                <div class="video-box">
                                    <div class="iPhonePlayer" style="display:none;">
                                    <video id="player" width="75" height="75" controls="controls" autostart="false">
                                            <source src="<?php echo $video_path ?>"  />
                                          </video>
                                    </div>
                                      <div class="AndroidPlayer" style="display:none;"> 
                                    <a onclick="playMyVideo('<?php echo $video_path ?>')">
                                        <img src="<?php echo get_bloginfo('url') ?>/wp-content/plugins/dsp_dating/m1/images/play.jpeg" style="width: 85px;height: 85px">
                                    </a>
                                      </div>
                                </div>





                            <?php } else { ?>

                                <img src="<?php echo $imagepath ?>plugins/dsp_dating/images/private-video.jpg" style="width:85px; height:85px;" class="img2" align="left" />

                                <?php
                            }
                        } else {
                            ?>
                            <div class="video-box">
                                  <div class="iPhonePlayer" style="display:none;">
                                <video id="player" width="75" height="75" controls="controls" autostart="false">
                                            <source src="<?php echo $video_path ?>"  />
                                          </video>
                                  </div>
                                  <div class="AndroidPlayer" style="display:none;"> 
                                <a onclick="playMyVideo('<?php echo $video_path ?>')">
                                    <img src="<?php echo get_bloginfo('url') ?>/wp-content/plugins/dsp_dating/m1/images/play.jpeg" style="width: 85px;height: 85px">
                                </a>
                                      </div>
                            </div>

                        <?php } ?>
                    </div>

                </li>



                <?php
                $i++;
            }
            ?>


        </ul>
    </div>





<?php } ?>