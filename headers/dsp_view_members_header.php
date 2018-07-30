<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$page_members = get('members_page');
?>
<div class="tab-content-members dsp-member-tab">
    <div <?php if (($page_members == "") || ($page_members == "new_mem")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "home/mypage/members_page/new_mem/"; ?>"><?php echo language_code('DSP_NEW_MEMBERS'); ?></a></div>
    <div <?php if (($page_members == "popular_mem")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "home/mypage/members_page/popular_mem/"; ?>"><?php echo language_code('DSP_POPULAR_MEMBERS'); ?></a></div>
    <div class="clr"></div>
</div>
<div class="dsp-member-container">

<?php
//one to one chat pop up notification 
apply_filters('dsp_get_single_chat_popup_notification',$notification);

if ($page_members == "new_mem") {
   include_once(WP_DSP_ABSPATH . "dsp_view_new_members.php");
} else if ($page_members == "popular_mem") {
   include_once(WP_DSP_ABSPATH . "dsp_view_pop_members.php");
} else {
    include_once(WP_DSP_ABSPATH . "dsp_view_new_members.php");
}
?>
</div>