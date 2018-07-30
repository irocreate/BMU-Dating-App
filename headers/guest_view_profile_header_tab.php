<div class="dsp-tab-container">
<div class="dsp-line">
    <div <?php if (($guest_pageurl == "view_mem_profile") || ($guest_pageurl == "")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?> >
        <?php
        if ($check_couples_mode->setting_status == 'Y') {
            $member_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$get_profile_user_id'");
            $user_gender = $member_profile_details->gender;
            if ($user_gender == 'C') {
                ?>
                <a href="<?php echo $root_link . get_username($get_profile_user_id) . "/my_profile/"; ?>"><?php echo language_code('DSP_VIEW_PROFILE_SUBMENU_PROFILE'); ?></a>
            <?php } else { ?>
                <a href="<?php echo $root_link . get_username($get_profile_user_id) . "/"; ?>"><?php echo language_code('DSP_VIEW_PROFILE_SUBMENU_PROFILE'); ?></a>
                <?php
            }
        } else { 
        ?>  
            <a href="<?php echo $root_link . get_username($get_profile_user_id) . "/"; ?>"><?php echo language_code('DSP_VIEW_PROFILE_SUBMENU_PROFILE'); ?></a>    
        <?php } ?>        
    </div>
    <?php if ($check_picture_gallery_mode->setting_status == 'Y') { ?>
            <div <?php if (($guest_pageurl == "view_mem_photos")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . get_username($get_profile_user_id) . "/photos/"; ?>"><?php echo language_code('DSP_MENU_PHOTOS'); ?></a></div>
             
             <div <?php if (($guest_pageurl == "view_mem_album") || ($guest_pageurl == "")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
                <a href="<?php echo $root_link . get_username($get_profile_user_id) . "/album/"; ?>"><?php echo language_code('DSP_MEDIA_HEADER_ALBUMS'); ?></a></div>
    <?php } ?>
    <?php if ($check_audio_mode->setting_status == 'Y') { ?>
        <div <?php if (($guest_pageurl == "view_mem_audio") || ($guest_pageurl == "")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . get_username($get_profile_user_id) . "/audio/"; ?>"><?php echo language_code('DSP_VIEW_PROFILE_SUBMENU_AUDIO'); ?></a></div>
    <?php } ?>
    <?php if ($check_video_mode->setting_status == 'Y') { ?>
        <div <?php if (($guest_pageurl == "view_mem_video") || ($guest_pageurl == "")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . get_username($get_profile_user_id) . "/video/"; ?>"><?php echo language_code('DSP_VIEW_PROFILE_SUBMENU_VIDEO'); ?></a></div>
    <?php } ?>
    <?php if ($check_my_friend_module->setting_status == 'Y') { ?>
        <div <?php if (($guest_pageurl == "view_mem_friends") || ($guest_pageurl == "")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . get_username($get_profile_user_id) . "/friends/"; ?>"><?php echo language_code('DSP_VIEW_PROFILE_SUBMENU_FRIENDS'); ?></a></div>
    <?php } ?>
    <?php if ($check_blog_module->setting_status == 'Y') { ?>
        <div <?php if (($guest_pageurl == "view_mem_blog") || ($guest_pageurl == "")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . get_username($get_profile_user_id) . "/blog/"; ?>"><?php echo language_code('DSP_MENU_MY_BLOGS'); ?></a></div>
    <?php } ?>
    <div class="clr"></div>
</div>
</div>