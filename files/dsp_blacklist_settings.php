<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author -  www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ********************************  Blacklist SETINGS ************************************ //
global $wpdb;
$current_user = wp_get_current_user();

$dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_usermeta_table = $wpdb->prefix . "usermeta";
$dsp_member_videos = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
$dsp_member_audios = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_blacklist_members_table = $wpdb->prefix . DSP_BLACKLIST_MEMBER_TABLE;
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
if ($mode == "update") {
    $ip = isset($_REQUEST['ip']) ? $_REQUEST['ip'] : '';
    $id = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
    $blacklist_id = isset($_REQUEST['blacklist_id']) ? $_REQUEST['blacklist_id'] : '';
    $ip_status = isset($_REQUEST['ip_status']) ? $_REQUEST['ip_status'] : '';
    $usermeta_table = $wpdb->get_results("SELECT * FROM $dsp_usermeta_table where meta_key='signup_ip' and meta_value='$ip'  ");
    foreach ($usermeta_table as $usermeta) { 
        $user_id = $usermeta->user_id;
        $username = $wpdb->get_row("SELECT * FROM $dsp_users_table users, $dsp_blacklist_members_table blacklist where users.user_login=blacklist.user_name AND users.ID=$user_id ");
        
        $user_login = $username->user_login;
        if (($ip_status == '0')) {

            $wpdb->query("UPDATE $dsp_blacklist_members_table SET ip_status=1 WHERE user_name='$user_login'");
            wp_delete_user($id);
            $wpdb->query("DELETE FROM $dsp_member_videos WHERE user_id=$id");
            $wpdb->query("DELETE FROM $dsp_member_audios WHERE user_id=$id");
            $wpdb->query("DELETE FROM $dsp_members_photos WHERE user_id=$id");
            $wpdb->query("DELETE FROM $dsp_user_profiles WHERE user_id=$id");
        }
    }
}else if($mode == 'unblock'){
    $blacklist_id = $_REQUEST['blacklist_id'];
    $wpdb->query("UPDATE $dsp_blacklist_members_table SET ip_status=0 WHERE blacklist_id='$blacklist_id'");
    //echo $wpdb->last_query;die;
}
?> 
<div>
    <div id="general" class="postbox" >
        <h3 class="hndle"><span><?php echo language_code('DSP_BLACKLIST_SEARCH_USERS'); ?></span></h3>
        <div style="height:20px;"></div>
        <div class="dsp_thumbnails1" >
            <div style="width:320px;">
                <div style="width:95px; font-weight:bold; letter-spacing:0.5px; float:left;"><?php echo language_code('DSP_BLACKLIST_USERNAME_OR_IP'); ?></div>
                <form name="search" method="post"  action="">
                    <div style="float:none;" >
                        <input style="float:left; margin-right:20px;" name="nameorip" type="text" />
                        &nbsp;
                        <input name="search" type="submit" value="<?php echo language_code('DSP_BLACKLIST_SEARCH_BUTTON'); ?>"  class="button"  /></div>
                </form>
            </div>
            <div>
                <div style="height:20px;"></div>
                <?php
                if (isset($_REQUEST['search'])) {
                    $nameorip = isset($_REQUEST['nameorip']) ? $_REQUEST['nameorip'] : '';
                    if ($nameorip == '') {
                        ?><script>alert("Please Enter username or IP Address");</script> <?php
                    } else {
                        $usermeta_table = $wpdb->get_results("SELECT * FROM $dsp_blacklist_members_table WHERE user_name like '%$nameorip%' OR ip_address like '%$nameorip%'");

                        foreach ($usermeta_table as $usermeta) { 
                            $user_name = $usermeta->user_name;
                            $ip_address = $usermeta->ip_address;
                            $ip_status = $usermeta->ip_status;
                            $blacklistip = $usermeta->blacklist_id;
                            $users_table = $wpdb->get_row("SELECT * FROM $dsp_users_table where user_login='$user_name' ");
                            @$user_id = $users_table->ID;
                            ?>

                            <table width="450" border="0" cellpadding="6">
                                <tr style="line-height: 10px;">
                                    <td width="30%"><?php echo $user_name; ?></td>
                                    <td width="30%"><?php echo $ip_address ?></td>
                                    <td width="40%">

                                        <?php if ($ip_status == 0) { ?>
                                            <span onclick="blacklistip('<?php echo $ip_status; ?>', '<?php echo $user_id; ?>', '<?php echo $ip_address; ?>','<?php echo $blacklistip; ?>')" class="span_pointer" style="font-size:12px;">Not Blacklisted</span>


                                            <?php
                                            /* echo "<a href='admin.php?page=dsp-admin-sub-page1&pid=check_blacklist_ipaddress&mode=update&uid=$user_id&ip=$ip_address&ip_status=$ip_status' style='text-decoration:none;'>Not Blacklisted</a>"; */
                                        } else {
                                            echo language_code('DSP_BLACKLISTED');
                                        }
                                        ?></td>
                                </tr>
                            </table>
                            <?php
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div>
        <div id="general" class="postbox">
            <h3 class="hndle"><span><?php echo language_code('DSP_BLACKLIST_ADD_IP_TO_BLACKLIST'); ?></span></h3>
            <div style="height:20px;"></div>
            <div class="dsp_thumbnails1" >
                <div style="width:320px;">
                    <div>
                        <?php
                        if (isset($_REQUEST['addip'])) {
                            $ipaddress = isset($_REQUEST['ipaddress']) ? $_REQUEST['ipaddress'] : '';
                            $row = $wpdb->get_var("select count(*) from $dsp_blacklist_members_table WHERE ip_address='$ipaddress'");
                            if ($row == 0) {
                                $wpdb->query("insert into $dsp_blacklist_members_table SET ip_status=1 , ip_address='$ipaddress'");
                            } else {
                                $wpdb->query("UPDATE $dsp_blacklist_members_table SET ip_status=1 WHERE ip_address='$ipaddress'");
                            }
                        }
                        ?>
                        <form name="ipblacklist" method="post"  action="">
                            <input style="float:left; margin-right:20px;" name="ipaddress" type="text" />&nbsp;<input  class="button"  name="addip" type="submit" value="<?php echo language_code('DSP_BLACKLIST_ADD_BUTTON'); ?>" />
                        </form>
                    </div>
                </div>
            </div>
            <div style="height:20px;"></div>
        </div>
        <div>
            <div id="general" class="postbox">
                <h3 class="hndle"><span><?php echo language_code('DSP_BLACKLIST_IP'); ?></span></h3>
                <div style="height:20px;"></div>
                <div class="dsp_thumbnails1" >
                    <div style="width:320px;">
                        <table width="450" border="0" cellpadding="6">
                            <tr>
                                <th align="left" width="30%"><?php echo language_code('DSP_BLACKLIST_USERS'); ?></th>
                                <th align="left" width="30%"><?php echo language_code('DSP_BLACKLIST_IP_ADDRESS'); ?></th>
                                <th align="left" ><?php echo language_code('DSP_BLACKLIST_USERS_STATUS'); ?></th>
                                <th align="left" ><?php echo language_code('DSP_ACTION'); ?></th>
                                
                            </tr>
                            <?php
                            $dsp_blacklist_members_table = $wpdb->prefix . DSP_BLACKLIST_MEMBER_TABLE;
                            $blacklist_members_table = $wpdb->get_results("SELECT * FROM $dsp_blacklist_members_table where ip_status=1");
                            foreach ($blacklist_members_table as $blacklist_members) {
                                $ip_address = $blacklist_members->ip_address;
                                $user_name = $blacklist_members->user_name;
                                $ip_status = $blacklist_members->ip_status;
                                ?>
                                <tr style="line-height: 10px;">
                                    <td><?php echo $user_name = $blacklist_members->user_name; ?></td>
                                    <td><?php echo $ip_address = $blacklist_members->ip_address; ?></td>
                                    <td><?php
                                        if (($ip_status = $blacklist_members->ip_status) == 1)
                                            echo language_code('DSP_BLACKLISTED');;
                                        ?></td>
                                    <td><span onclick="unblock_members(<?php echo $blacklist_members->blacklist_id; ?>);" class="span_pointer">Unblock</span></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
                <div style="height:20px;"></div>
            </div>