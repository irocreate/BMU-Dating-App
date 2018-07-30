<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
$dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
$dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;
$dsp_user_emails_table = $wpdb->prefix . DSP_EMAILS_TABLE;
$dsp_interest_tags_table = $wpdb->prefix . DSP_INTEREST_TAGS_TABLE;
$dsp_user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$count_total_members = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_user_profiles ORDER BY user_profile_id"));
$count_total_males = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM  $dsp_user_profiles WHERE gender='M'"));
$count_total_females = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM  $dsp_user_profiles WHERE  gender='F'"));
$count_total_couples = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM  $dsp_user_profiles WHERE  gender='C'"));
$count_total_sent_winks = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM  $dsp_member_winks_table ORDER BY wink_mesage_id"));
$count_approved_photos = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_galleries_photos WHERE status_id=1 ORDER BY gal_photo_id"));
$count_waiting_approval_photos = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_galleries_photos WHERE status_id=0 ORDER BY gal_photo_id"));
$count_total_galleries = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_user_albums_table ORDER BY album_id"));
$count_total_sent_emails = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_user_emails_table ORDER BY message_id"));
$count_wait_approve_profile = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_user_profiles where status_id='0' ORDER BY user_profile_id"));
$totalpen = 0;
$totalon = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_user_online_table"));
$count_interest_tags = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_interest_tags_table"));
?>
<div id="general" class="postbox">
    <h3 class="hndle"><span><?php echo language_code('DSP_ACCOUNTING_DEMOGRAPHICS_SETTING') ?></span></h3>
    <div style="margin:20px">
        <table class="dsp_thumbnails1" border="0" width="100%">
            <tr>
                <td>
                    <table width="500" border="0" cellspacing="0" cellpadding="6" style="padding-top:20x">
                        <tr>
                            <td width="200px"><strong>Awaiting Approval</strong></td>
                            <td width="200px"><b>Quick Stats</b></td>
                            <td width="200px"><b>Demographics</b></td>
                        </tr>

                        <tr>
                            <td><font color="#0066FF"><b><a href="<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_profiles&dsp_page=not_approve"><?php print("$count_wait_approve_profile"); ?></a></b></font>&nbsp;Members</td>
                            <td><font color="#0066FF"><?php print("$count_total_sent_winks"); ?></font>&nbsp;Winks Sent</td>
                            <td><font color="#0066FF"><?php print("$count_total_members"); ?></font>&nbsp;Members</td>
                        </tr>

                        <tr>
                            <td><font color="#0066FF"><b><a href="<?php echo get_bloginfo('url'); ?>/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=Profile_photos"><?php print("$count_waiting_approval_photos"); ?></a></b></font>&nbsp;Photos</td>
                            <td><font color="#0066FF"><?php print("$totalon"); ?></font>&nbsp;Online</td>
                            <td><font color="#0066FF"><?php print("$count_total_males"); ?></font>&nbsp;Males</td>
                        </tr> 

                        <tr>
                            <td>&nbsp;</td>
                            <td><font color="#0066FF"><?php print("$count_total_galleries"); ?></font>&nbsp;Galleries</td>
                            <td><font color="#0066FF"><?php print("$count_total_females"); ?> </font>Females</td>
                        </tr> 

                        <tr>
                            <td>&nbsp;</td>
                            <td><font color="#0066FF"><?php print("$count_approved_photos"); ?></font>&nbsp;Photos</td>
                            <td>&nbsp;</td>
                        </tr>


                        <tr>
                            <td>&nbsp;</td>
                            <td><font color="#0066FF"><?php print("$count_total_sent_emails"); ?></font>&nbsp;Emails Sent</td>
                            <td>&nbsp;</td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <td><font color="#0066FF"><?php print("$count_interest_tags"); ?></font>&nbsp;Interest</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>

                <td rowspan="2">
                    <table width="100%">
                        <tr>
                            <td colspan="2">
                                <img src="<?php echo WPDATE_URL . '/files/dsp_piechart.php?data=<?php echo $count_total_males ?>*<?php echo $count_total_females ?>*<?php echo $count_total_couples ?>&label=Males*Females*couple'" alt="pie chart"/> 
                                         <!--<img src="<?php echo $const_image_link ?>dsp_pichart.gif">-->
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
<br />
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>