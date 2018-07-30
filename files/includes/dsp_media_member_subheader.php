<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$list_status = $_REQUEST['status'];
?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <!--<tr><td class="profile_headind"><?php echo "Members Photos"; ?></td></tr>-->
                <tr><td>
                        <div id="navmenu" align="left">
                            <ul>
                                <li <?php if ($list_status == "0") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                                    <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=Profile_photos&&status=0"><?php echo language_code('DSP_MEDIA_SUB_HEADER_NOT_APPROVE') ?></a><span class="dsp_tab1_span">|</span></li>
                                <li <?php if ($list_status == "1") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                                    <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=Profile_photos&&status=1"><?php echo language_code('DSP_MEDIA_SUB_HEADER_APROVED') ?></a><span class="dsp_tab1_span">|</span></li>
                                <li <?php if ($list_status == "2") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                                    <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=Profile_photos&&status=2"><?php echo language_code('DSP_MEDIA_SUB_HEADER_REJECTED') ?></a></li>
                            </ul>
                        </div>
                    </td></tr>
            </table>
        </td>
    </tr>
</table>
<?php
switch ($list_status) {
    case 0:
        $list_status1 = 0;
        break;
    case 1:
        $list_status1 = 1;
        break;
    case 2:
        $list_status1 = 2;
        break;
}
include_once( WP_DSP_ABSPATH . 'files/dsp_media_profile_photos.php');
?>