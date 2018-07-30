<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
$dsp_user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
$dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
$dsp_member_audios = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
$dsp_member_videos = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
$dsp_user_emails_table = $wpdb->prefix . DSP_EMAILS_TABLE;
$dsp_interest_tags_table = $wpdb->prefix . DSP_INTEREST_TAGS_TABLE;
$dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_date_tracker_table = $wpdb->prefix . DSP_DATE_TRACKER_TABLE;
$count_total_sent_winks = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_member_winks_table ORDER BY wink_mesage_id");
$totalon = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_online_table");
$count_approved_photos = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE status_id=1");
$count_total_galleries = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_galleries_photos WHERE status_id=1 ORDER BY gal_photo_id");
$count_total_audio = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_member_audios WHERE status_id=1 ORDER BY audio_file_id");
$count_total_video = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_member_videos WHERE status_id=1 ORDER BY video_file_id");
$count_total_sent_emails = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table ORDER BY message_id");
$count_interest_tags = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_interest_tags_table");
$count_total_registred_user = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_users_table");
$count_total_profile_created = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_users_table users, $dsp_user_profiles profiles where users.ID=profiles.user_id");
$count_total_date_tracked = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_date_tracker_table");
$imagepath = WPDATE_URL ;
?>
<div id="general" class="postbox">
    <h3 class="hndle"><span><?php echo language_code('DSP_ACCOUNTING_QUICKSTAT_SETTING') ?></span></h3>
    <div style="margin:20px">
        <table class="dsp_thumbnails1" border="0" width="100%">
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2" style="padding-top:20x" class="report-quick-stats">
                        <tr>
                            <td style="width:20px;"><img src=<?php echo $imagepath ?>/images/wink_sent.jpg /></td>
                            <td align="left" style="width:20px;color:#6D6D6D; font-weight:bold;"><?php print("$count_total_sent_winks"); ?></td>
                            <td style="font-weight:bold;">Winks Sent</td>
                        </tr>

                        <tr>
                            <td><img src=<?php echo $imagepath ?>/images/online.jpg /></td>
                            <td style="color:#6D6D6D; font-weight:bold;"><?php print("$totalon"); ?></td>
                            <td style="font-weight:bold;">Online</td>
                        </tr> 

                        <tr>
                            <td><img src=<?php echo $imagepath ?>/images/member.jpg /></td>
                            <td style="color:#6D6D6D; font-weight:bold;"><?php print("$count_approved_photos"); ?></td>
                            <td style="font-weight:bold;">Member Photos</td>
                        </tr>

                        <tr>
                            <td><img src=<?php echo $imagepath ?>/images/gallery.jpg /></td>
                            <td style="color:#6D6D6D; font-weight:bold;"><?php print("$count_total_galleries"); ?></td>
                            <td style="font-weight:bold;">Galleries</td>
                        </tr> 

                        <tr>
                            <td><img src=<?php echo $imagepath ?>/images/audio.jpg /></td>
                            <td style="color:#6D6D6D; font-weight:bold;"><?php print("$count_total_audio"); ?></td>
                            <td style="font-weight:bold;">Audio</td>
                        </tr> 

                        <tr>
                            <td><img src=<?php echo $imagepath ?>/images/video.jpg /></td>
                            <td style="color:#6D6D6D; font-weight:bold;"><?php print("$count_total_video"); ?></td>
                            <td style="font-weight:bold;">Video</td>
                        </tr> 

                        <tr>
                            <td><img src=<?php echo $imagepath ?>/images/email.jpg /></td>
                            <td style="color:#6D6D6D; font-weight:bold;"><?php print("$count_total_sent_emails"); ?></td>
                            <td style="font-weight:bold;">Emails Sent</td>
                        </tr>

                        <tr>
                            <td><img src=<?php echo $imagepath ?>/images/interst.jpg /></td>
                            <td style="color:#6D6D6D; font-weight:bold;"><?php print("$count_interest_tags"); ?></td>
                            <td style="font-weight:bold;">Total Interest</td>
                        </tr>

                        <tr>
                            <td><img src=<?php echo $imagepath ?>/images/registred_user.jpg /></td>
                            <td style="color:#6D6D6D; font-weight:bold;"><?php print("$count_total_registred_user"); ?></td>
                            <td style="font-weight:bold;">Registered Users</td>
                        </tr>

                        <tr>
                            <td><img src=<?php echo $imagepath ?>/images/profile_created.jpg /></td>
                            <td style="color:#6D6D6D; font-weight:bold;"><?php print("$count_total_profile_created"); ?></td>
                            <td style="font-weight:bold;">Profiles Created</td>
                        </tr>

                        <tr>
                            <td><img src=<?php echo $imagepath ?>/images/dste_tracked.jpg /></td>
                            <td style="color:#6D6D6D; font-weight:bold;"><?php print("$count_total_date_tracked"); ?></td>
                            <td style="font-weight:bold;">Dates Tracked</td>
                        </tr>
                    </table>
                </td>


            </tr>

            <tr>
                <td>
                  <!--<table width="100%" border="0" cellspacing="2" cellpadding="0" style="padding-top:50x">
                   <tr>
                   <td width="200px"><strong></strong></td>
                   <td width="200px"><b>Registered</b></td>
                   <td width="200px"><b>Profiles</b></td>
                   </tr>
                   
                   <tr>
                      <td>Males</td>
                      <td><?php echo $count_total_males ?></td>
                      <td><?php echo $count_total_males ?></td>
                  </tr>
                  
                   <tr>
                      <td>Females</td>
                      <td><?php echo $count_total_females ?></td>
                      <td><?php echo $count_total_females ?></td>
                  </tr>
                  
                   <tr>
                      <td>Totals</td>
                      <td><?php echo $count_total_members ?></td>
                      <td><?php echo $count_total_members ?></td>
                  </tr>
                  
                  
                  </table>-->
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
