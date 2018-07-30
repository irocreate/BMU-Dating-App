<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$profiles_page_url = isset($_REQUEST['dsp_page']) ? $_REQUEST['dsp_page'] : '';
?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<!--<tr><td ><strong><?php echo language_code('DSP_MEDIA_SUB_HEADER_TITLE') ?></strong></td></tr>-->
    <tr><td>
            <div id="navmenu" align="left">
                <ul>
                    <li <?php if (($profiles_page_url == "not_approve") && ($pageURL == "media_profiles")) { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                        <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_profiles&dsp_page=not_approve"><?php echo language_code('DSP_MEDIA_SUB_HEADER_NOT_APPROVE') ?></a><span class="dsp_tab1_span">|</span></li>
                    <li <?php if ($profiles_page_url == "approved") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                        <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_profiles&dsp_page=approved"><?php echo language_code('DSP_MEDIA_SUB_HEADER_APROVED') ?></a><span class="dsp_tab1_span">|</span></li>
                    <li <?php if ($profiles_page_url == "rejected") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                        <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_profiles&dsp_page=rejected"><?php echo language_code('DSP_MEDIA_SUB_HEADER_REJECTED') ?></a><span class="dsp_tab1_span">|</span></li>

                    <li <?php if ($profiles_page_url == "deleted") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                        <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_profiles&dsp_page=deleted"><?php echo language_code('DSP_MEDIA_SUB_HEADER_DELETED') ?></a><span class="dsp_tab1_span">|</span></li>

                     <li <?php if ($profiles_page_url == "reported_user") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                        <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_profiles&dsp_page=reported_user"><?php echo 'Reported User' ?></a></li>

                </ul>
            </div>
        </td></tr>
</table>
<?php
$page = 0;
switch ($profiles_page_url) {
    case 'not_approve':
        $list_status = 0;
        $page =1;
        break;
    case 'approved':
        $list_status = 1;
        $page =1;
        break;
    case 'rejected':
        $list_status = 2;
        $page =1;
        break;
    case 'deleted':
        $list_status = 3;
        $page =1;
        break;
    case 'reported_user':
              include_once(WP_DSP_ABSPATH .'files/dsp_reported_user.php');
              break;
}
if($page == 1 ){
include_once( WP_DSP_ABSPATH . 'files/dsp_media_profiles.php');
}
?>