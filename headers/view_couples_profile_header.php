<?php
if (is_user_logged_in()) {
    include_once( WP_DSP_ABSPATH . 'headers/view_profile_tab_header.php');
    $view_profile_pageurl = get('view');
} else {
    include_once( WP_DSP_ABSPATH . 'headers/guest_view_profile_header_tab.php');
    $view_profile_pageurl = get('view');
}
?>
<div class="line top-gap">
    <div <?php if ($view_profile_pageurl == "my_profile") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>

        <a href="<?php echo $root_link . get_username($member_id) . "/my_profile/"; ?>"><?php echo language_code('DSP_MENU_EDIT_MY_PROFILE'); ?></a>

    </div>
    <div <?php if ($view_profile_pageurl == "partner_profile") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>

        <a class="last" href="<?php echo $root_link . get_username($member_id) . "/partner_profile/"; ?>"><?php echo language_code('DSP_MENU_EDIT_PARTNER_PROFILE'); ?></a>


    </div>
    <div class="clr"></div>
</div>
<?php
//one to one chat pop up notification 
apply_filters('dsp_get_single_chat_popup_notification',$notification);
if ($view_profile_pageurl == "my_profile") {
    include_once(WP_DSP_ABSPATH . "view_profile_setup.php");
} else if ($view_profile_pageurl == "partner_profile") {
    include_once(WP_DSP_ABSPATH . "view_partner_profile_setup.php");
} else {
    include_once(WP_DSP_ABSPATH . "view_profile_setup.php");
}
