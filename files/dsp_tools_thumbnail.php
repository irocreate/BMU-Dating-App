<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_language_table = $wpdb->prefix . DSP_LANGUAGE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$request_url = $_SERVER['REQUEST_URI'];
?>
<div>
    <div id="general" class="postbox">
        <h3 class="hndle"><span><?php echo language_code('DSP_TOOL_THUMBNAILS'); ?></span></h3>
        <div class="dsp_thumbnails3" >
            <?php
            $dsp_user_profiles = $wpdb->prefix . dsp_user_profiles;
            $dsp_members_photos = $wpdb->prefix . dsp_members_photos;
            $profile_details_table = $wpdb->get_results("SELECT * FROM $dsp_user_profiles ");
            foreach ($profile_details_table as $profile_details) {
                $user_id = $profile_details->user_id;
                $user_profile_image = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '" . $user_id . "'");
                $picture = $user_profile_image->picture;

                if ($picture != '') {
                    $dir = WP_DSP_ABSPATH . "user_photos/user_" . $user_id . "/" . $picture;
                    $thumb_name1 = WP_DSP_ABSPATH . "user_photos/user_" . $user_id . "/thumbs1/thumb_" . $picture;
                    $thumb_name = WP_DSP_ABSPATH . "user_photos/user_" . $user_id . "/thumbs/thumb_" . $picture;
                    if ((!file_exists($dir)) || (!file_exists($thumb_name1)) || (!file_exists($thumb_name))) {
                        $msg = "noexist";
                        $_SESSION['msg'] = $msg;
                    } else {
                        $msg = "exist";
                        $_SESSION['msg'] = $msg;
                    }
                }
                if ($_SESSION['msg'] == "noexist")
                    break;
            }
            ?><?php if ($_SESSION['msg'] == "noexist") { ?>
                <div style="width:320px;">
                    <form name="search" method="post"  action="">
                        <div class="thumb_text" >
                            Create Profile Thumbs <a href="admin.php?page=dsp-admin-sub-page3&pid=update_thumbnail" style="text-decoration:none;"><input class="button" name="thumb" type="button" value="Convert" /></a>

                    </form>
                </div>
                <div>
                    <div class="thumb_text_height"></div>
                </div>
            </div>
        <?php } else { ?>
            <div  class="thumb_text">All thumbnails have been created.</div>
            <div class="thumb_text_height"></div>
        <?php } ?>
        <div style="height:30px;"></div>
        <div style="float:left; width:100%"><span  style="font-weight:bold;">NOTE:</span> This tool resize all your photos to proper thumbnails. Only run it one time.</div>
        <br />
    </div>
    <div style="height:30px;"></div>