<script type="text/javascript">
    function member_pictures(id, str, album_id)
    {
        if (id) {
            var loc = window.location.href;
            if (loc.search("pagetitle") > -1)
            {
                index = loc.indexOf("pagetitle")
                loc = loc.substring(0, index - 1);
            }
            if (str == 'pictures') {
                loc += "&pagetitle=view_Pictures&album_id=" + album_id;
            }
            window.location.href = loc;
        }
    }
</script>
<div class="line">
        <div class="dsp_tab1 dsp_user_profile_name_head">
            <a href="<?php echo ROOT_LINK . $displayed_member_name->user_login ?>"><i class="fa fa-user"></i>&nbsp;<?php echo  $displayed_member_name->display_name// get_userdata($member_id)->user_login;?></a>
        </div>
    <div <?php if (($profile_pageurl == "view_profile") || ($profile_pageurl == "")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?> >
        <?php
        if ($check_couples_mode->setting_status == 'Y') {
            $member_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member_id'");
            $user_gender = $member_profile_details->gender;
            if ($user_gender == 'C') {
                ?>
                <a href="<?php echo $root_link . get_username($member_id) . "/my_profile/"; ?>"><?php echo language_code('DSP_VIEW_PROFILE_SUBMENU_PROFILE'); ?></a>
            <?php } else { ?>
                <a href="<?php echo $root_link . get_username($member_id) . "/"; ?>"><?php echo language_code('DSP_VIEW_PROFILE_SUBMENU_PROFILE'); ?></a>
                <?php
            }
        } else {
        ?> 
            <a href="<?php echo $root_link . get_username($member_id) . "/"; ?>"><?php echo language_code('DSP_VIEW_PROFILE_SUBMENU_PROFILE'); ?></a>    
        <?php } ?>        
    </div>
    <?php if ($check_picture_gallery_mode->setting_status == 'Y') { ?>

    <div <?php if (($profile_pageurl == "view_photos")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
                <a href="<?php echo $root_link . get_username($member_id) . "/photos/"; ?>"><?php echo language_code('DSP_MENU_PHOTOS'); ?></a>
    </div>
    <div <?php if (($profile_pageurl == "view_album")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . get_username($member_id) . "/album/"; ?>"><?php echo language_code('DSP_MEDIA_HEADER_ALBUMS'); ?></a>
    </div>
         
    <?php } ?>
    <?php if ($check_audio_mode->setting_status == 'Y') { ?>
        <div <?php if (($profile_pageurl == "view_audio")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . get_username($member_id) . "/audio/"; ?>"><?php echo language_code('DSP_VIEW_PROFILE_SUBMENU_AUDIO'); ?></a></div>

    <?php } ?>
    <?php if ($check_video_mode->setting_status == 'Y') { ?>

        <div <?php if (($profile_pageurl == "view_video")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . get_username($member_id) . "/video/"; ?>"><?php echo language_code('DSP_VIEW_PROFILE_SUBMENU_VIDEO'); ?></a></div>


    <?php } ?>
    <?php if ($check_my_friend_module->setting_status == 'Y') { ?>
        <div <?php if (($profile_pageurl == "view_friends")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . get_username($member_id) . "/friends/"; ?>"><?php echo language_code('DSP_VIEW_PROFILE_SUBMENU_FRIENDS'); ?></a></div>


    <?php } ?>
    <?php if ($check_blog_module->setting_status == 'Y') { ?>
        <?php if ($user_id != $member_id) { ?>
            <div <?php if (($profile_pageurl == "view_blogs")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
                <a href="<?php echo $root_link . get_username($member_id) . "/blogs/"; ?>"><?php echo language_code('DSP_MENU_MY_BLOGS'); ?></a></div>

        <?php } ?>
    <?php } ?>
    <div class="clr"></div>
</div>
</div>
<?php
//one to one chat pop up notification 
apply_filters('dsp_get_single_chat_popup_notification',$notification);
