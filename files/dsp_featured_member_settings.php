<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ********************************  Blacklist SETINGS ************************************ //
global $wpdb;
$current_user = wp_get_current_user();

$dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$settings_root_link = get_bloginfo('url') . "/wp-admin/admin.php?page=dsp-admin-sub-page1&pid=" . $pageURL;
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
?>
<style type="text/css">

#footer { position:relative; }
th {
    padding: 7px;
}    
td {
    text-align: center;
    line-height: 22px;
}
form td {
    text-align: left;
}
td a{
    text-decoration: none;
}
</style>
<?php
if ($mode == "update") {
        $days = isset($_REQUEST['days']) ? $_REQUEST['days'] : '';
        $exp = date('Y-m-d');
        $id = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
        
        $current_max_featured_order = $wpdb->get_var("SELECT MAX(featured_member) from $dsp_user_profiles_table"); 
        $current_max_featured_order = $current_max_featured_order + 1;
        $wpdb->query("UPDATE $dsp_user_profiles_table SET featured_expiration_date=DATE_ADD('$exp', INTERVAL $days DAY), featured_member = $current_max_featured_order WHERE user_id = $id");
        $sendback = remove_query_arg(array('mode', 'uid','days'), $_SERVER['HTTP_REFERER']);
        echo '<script>window.location="' . $sendback . '";</script>';
}
?> 
<div>
    <div id="general" class="postbox">
        <h3 class="hndle"><span><?php echo language_code('DSP_SEARCH_USER'); ?></span></h3>
        <div style="height:20px;"></div>
        <div class="dsp_thumbnails1" style="padding-bottom:20px;" >
            <div style="width:320px;">
                <form name="search" method="post"  action="">
                    <div style="float:none;" >
                        <input name="name" type="text" style="width:170px; float:left; margin-right:20px;" />	&nbsp;	<input name="search" type="submit" value="Search" class="button" />
                    </div>
                </form>
            </div>
            <?php if ($mode == "move") { 
                $id = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
                $direction = isset($_REQUEST['direction']) ? $_REQUEST['direction'] : '';
                $current_order = isset($_REQUEST['current_order']) ? $_REQUEST['current_order'] : '';
                
                if($direction == 'up')
                    $adjacent_order = $current_order + 1;
                elseif($direction == 'down')
                    $adjacent_order = $current_order - 1;
                
                //$current = $wpdb->get_row("SELECT * FROM $dsp_user_profiles_table WHERE user_id = $id");
                $adjacent_info = $wpdb->get_row("SELECT * FROM $dsp_user_profiles_table WHERE featured_member = $adjacent_order");
                
                if(!empty($adjacent_info))
                {                   
                    $wpdb->query("UPDATE $dsp_user_profiles_table SET featured_member = $adjacent_order WHERE user_id = $id");                   
                    $wpdb->query("UPDATE $dsp_user_profiles_table SET featured_member = $current_order WHERE user_id = $adjacent_info->user_id");
                    
                    $sendback = remove_query_arg(array('mode', 'uid', 'direction', 'current_order'), $_SERVER['HTTP_REFERER']);
                    echo '<script>window.location="' . $sendback . '";</script>';
                }
              
            
            } else if ($mode == "remove") {
                $id = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
                $wpdb->query("UPDATE $dsp_user_profiles_table SET featured_member = 0 WHERE user_id = $id");
                $sendback = remove_query_arg(array('mode', 'uid'), $_SERVER['HTTP_REFERER']);
                echo '<script>window.location="' . $sendback . '";</script>';
                ?>
            <?php } else { ?>	
                <div>
                 <div style="height:20px;"></div>   
                    <?php
                    if (isset($_REQUEST['search'])) {
                        $nameorip = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
                        $users_table = $wpdb->get_results("SELECT * FROM $dsp_users_table user INNER JOIN $dsp_user_profiles_table profile ON profile.user_id=user.ID where user.user_login like '%$nameorip%' AND profile.featured_member < 1");
                        foreach ($users_table as $users) {
                            ?>
                            <form name="extend" method="POST"  action="<?php
                            echo add_query_arg(array(
                                'mode' => 'update', 'uid' => $users->ID), $settings_root_link);
                            ?>" >
                                <table width="100%" border="0"  style="border-bottom:1px solid #EEEEEE; border-top:1px solid #EEEEEE; padding-top:5px; padding-bottom:5px;  margin-bottom:-1px;">
                                    <tr style="line-height: 10px;">
                                        <td width="35%"><label><strong><?php echo $users->user_login; ?></strong></label></td>
                                        <td width="20%"><input name="days" type="text"  style="width:100%;"  onclick="this.value" placeholder="<?php echo language_code('DSP_MEMBERSHIPS_DAYS_NO'); ?>" /></td>
                                        <td width="10%"><input name="extend" type="submit" value="Submit"  class="button" 	/></td> 	
                                    </tr>
                                </table>
                            </form>
                            <?php
                        }
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div>
        <div id="general" class="postbox" >
            <h3 class="hndle"><span><?php echo language_code('DSP_FEATURED_MEMBERS'); ?></span></h3>
            <div style="height:20px;"></div>
            <div class="dsp_thumbnails2" style="padding-bottom:20px; padding-right:15px; width:auto;" >
                <div>
                    <div style="height:20px;"></div>                                
                        <table width="100%" style="padding-left:10px;" >
                              <tr>
                                  <th><?php echo language_code('DSP_ORDER'); ?></th>
                                  <th><?php echo language_code('DSP_PROFILE_PICTURE'); ?></th>
                                  <th><?php echo language_code('DSP_NAME'); ?></th>
                                  <th><?php echo language_code('DSP_AGE'); ?></th>
                                  <th><?php echo language_code('DSP_GENDER'); ?></th>
                                  <th><?php echo language_code('DSP_LOGIN_ID'); ?></th>
                                  <th><?php echo language_code('DSP_MOVE'); ?> &updownarrow;</th>
                                  <th><?php echo language_code('DSP_GATEWAYS_EXPIRATION_DATE'); ?> &updownarrow;</th>
                                  <th><?php echo language_code('DSP_REMOVE_BUTTON'); ?> &updownarrow;</th>
                              </tr>    
                              <?php
                              $featured_users = $wpdb->get_results("SELECT * FROM $dsp_user_profiles_table profile INNER JOIN $dsp_users_table user ON profile.user_id=user.ID WHERE profile.featured_member > 0 ORDER BY profile.featured_member DESC");
                              $upload_dir = content_url().'/';
                              foreach ($featured_users as $users) {
                                  $user_id = $users->user_id;
                                  ?>                      
                              <tr>
                                  <td><?php echo $users->featured_member; ?></td> 
                                  <td>
                                    <a class="group1" href="<?php echo ROOT_LINK . get_username($user_id) . "/"; ?>" >
                                      <img src="<?php echo display_thumb2_members_photo($user_id, $upload_dir); ?>"  border="0" class="img" />
                                    </a>
                                  </td>
                                  <td><?php echo $users->display_name; ?></td>
                                  <td><?php echo GetAge($users->age); ?></td>
                                  <td><?php echo $users->gender; ?></td>
                                  <td><?php echo $users->user_login; ?></td>
                                  <td style="font-family: Lucida Console;font-size: 24px;font-weight: bold;">
                                      <a href="<?php echo add_query_arg(array('mode' => 'move', 'direction' => 'up', 'uid' => $user_id, 'current_order' => $users->featured_member), $settings_root_link); ?>">&UpArrow;</a>                                   
                                      <a href="<?php echo add_query_arg(array('mode' => 'move', 'direction' => 'down', 'uid' => $user_id, 'current_order' => $users->featured_member), $settings_root_link); ?>">&downarrow;</a>
                                  </td>
                                  <td><?php echo $users->featured_expiration_date; ?></td>     
                                  <td style="font-family: Tahoma;font-size: 20px;font-weight: bold;">
                                      <a href="<?php echo add_query_arg(array('mode' => 'remove', 'uid' => $user_id), $settings_root_link); ?>">&minus;</a>
                                  </td>
                              </tr>
                              <?php } ?>
                        </table>               
                </div>
            </div>
            </table>
        </div>
    </div>
</div>