<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

$edit_profile_pageurl = get('pagetitle');
?>
	<div class="line">
	    <div <?php if ($edit_profile_pageurl == "my_profile") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
	        <a href="<?php echo $root_link . "edit/my_profile/"; ?>"><?php echo language_code('DSP_MENU_EDIT_MY_PROFILE'); ?></a>
	    </div>
	    <div <?php if ($edit_profile_pageurl == "edit_my_location") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
	        <a href="<?php echo $root_link . "edit/edit_my_location/"; ?>"   data-siteurl = "<?php echo site_url();?>" id = 'edit_location'><?php echo language_code('DSP_EDIT_MY_LOCATION') ?></a>
	    </div>
	    <?php if(($gender == 'C')):?>
	    <div <?php if ($edit_profile_pageurl == "partner_profile") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1 last" <?php } ?>>
	        <a href="<?php echo $root_link . "edit/partner_profile/"; ?>"><?php echo language_code('DSP_MENU_EDIT_PARTNER_PROFILE'); ?></a></div>
	    <?php endif; ?>
	    <div class="clr"></div>
	</div>
	
<?php
//one to one chat pop up notification 
apply_filters('dsp_get_single_chat_popup_notification',$notification);

if ($edit_profile_pageurl == "my_profile") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/edit/edit_profile_setup.php");
} else if ($edit_profile_pageurl == "partner_profile") {
    include_once(WP_DSP_ABSPATH . "edit_partner_profile_setup.php");
}else if ($edit_profile_pageurl == "edit_my_location") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/edit_my_location/edit_my_location.php");
}else { 
    include_once(WP_DSP_ABSPATH . "members/loggedin/edit/edit_profile_setup.php");
}
