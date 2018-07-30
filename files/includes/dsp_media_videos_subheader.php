<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$video_list_status = isset($_REQUEST['video_status']) ? $_REQUEST['video_status'] : '';
?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <!--<tr><td><strong><?php echo language_code('DSP_MEDIA_HEADER_VIDEOS') ?></strong></td></tr>-->
                <tr><td>
                        <div id="navmenu" align="left">
                            <ul>
                                <li <?php if ($video_list_status == "0") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                                    <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_videos&video_status=0"><?php echo language_code('DSP_MEDIA_SUB_HEADER_NOT_APPROVE') ?></a><span class="dsp_tab1_span">|</span></li>
                                <li <?php if ($video_list_status == "1") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                                    <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_videos&video_status=1"><?php echo language_code('DSP_MEDIA_SUB_HEADER_APROVED') ?></a><span class="dsp_tab1_span">|</span></li>
                                <li <?php if ($video_list_status == "2") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                                    <a href="<?php echo $root_link ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_videos&video_status=2"><?php echo language_code('DSP_MEDIA_SUB_HEADER_REJECTED') ?></a></li>
                            </ul>
                        </div>
                    </td></tr>
            </table>
        </td>
    </tr>
</table>

<?php
switch ($video_list_status) {
    case 0:
        $status = 0;
        break;
    case 1:
        $status = 1;
        break;
    case 2:
        $status = 2;
        break;
    default:
        $status = 0;
        break;
}
include_once( WP_DSP_ABSPATH . 'files/dsp_media_videos.php');
?>