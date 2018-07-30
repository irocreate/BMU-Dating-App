<?php
// ----------------------------------Check member privacy Settings------------------------------------
$redirect_location = add_query_arg(array('pid' => 3, 'pagetitle' => 'view_friends'), $root_link);
$request_Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';
$del_friend_Id = isset($_REQUEST['friend_Id']) ? $_REQUEST['friend_Id'] : '';
// ###########################  Reject Image ########################################

if (($request_Action == "Del") && ($del_friend_Id != "")) {
    //echo "DELETE from $dsp_my_friends_table WHERE friend_uid = '$del_friend_Id' AND user_id=$user_id";
    $wpdb->query("DELETE from $dsp_my_friends_table WHERE friend_uid = '$del_friend_Id' AND user_id=$user_id");
    //wp_redirect($redirect_location, $redirect_status);
}
//*************************************************************************//
$check_user_privacy_settings = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE view_my_friends='Y' AND user_id='$member_id'");
if (($check_user_privacy_settings > 0) && ($user_id != $member_id)) {  // check user privacy settings
    $check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'");
    if ($check_my_friends_list <= 0) {   // check member is not in my friend list
        ?>
        <div class="box-border">
            <div align="center"><?php echo language_code('DSP_CANT_VIEW_MEM_FRIENDS'); ?></div>

        </div>
    <?php } else {   // -----------------------------else Check member is in my friend list ---------------------------- // 
        ?>
        <div class="box-border show-details">
            <div class="dsp-row">
                <div class="dsp-md-3 dsp-block" style="display:none">              
                    <div class="box-profile-link">                   

                        <div class="menus-profile">
                            <ul>
                                <li>
                                    <?php
                                        if ($check_couples_mode->setting_status == 'Y') {
                                            if ($gender == 'C') {
                                    ?>
                                        <a href="<?php echo $root_link . get_username($user_id) . "/my_profile/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title="<?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" />
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" />     
                                    <?php }  } else {  ?> 

                                        <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" />       

                                    <?php } ?></a>
                                </li>
                                <li><a href="<?php echo $root_link . "extras/trending/"; ?>"><img src="<?php echo $fav_icon_image_path ?>profile.jpg" title="<?php echo language_code('DSP_PROFILE_TRENDINGS') ?>"  alt="<?php echo language_code('DSP_PROFILE_TRENDINGS') ?>"/></a></li>
                                <li><a href="<?php echo $root_link . "extras/viewed_me/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_viewedme.jpg" title="<?php echo language_code('DSP_WHO_VIEWED_ME') ?>"  alt="<?php echo language_code('DSP_WHO_VIEWED_ME') ?>"/></a></li>
                                <li><a href="<?php echo $root_link . "extras/i_viewed/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_iviewed.jpg" title="<?php echo language_code('DSP_WHO_I_VIEWED') ?>"  alt="<?php echo language_code('DSP_WHO_I_VIEWED') ?>" /></a> </li>
                                <li><a href="<?php echo $root_link . "online_members/show/all/"; ?>"><img src="<?php echo $fav_icon_image_path ?>whos_online.jpg" title="<?php echo language_code('DSP_ONLINE_MEMBER') ?>&nbsp;(<?php echo $count_online_member ?>)"  alt="<?php echo language_code('DSP_ONLINE_MEMBER') ?>" /></a></li>
                                <li><a href="<?php echo $root_link . "email/inbox/"; ?>"><img src="<?php echo $fav_icon_image_path ?>message.jpg" title="<?php echo language_code('DSP_NEW_EMAIL'); ?>&nbsp;(<?php echo $count_inbox_messages ?>)"  border="0" alt="<?php echo language_code('DSP_NEW_EMAIL') ?>" /></a></li>
                            </ul>
                        </div>
                        <div class="clr"></div>
                        <ul class="text-left dsp-user-spec clearfix">
                     
                            <?php if ($check_flirt_module->setting_status == 'Y') { ?>
                                <li <?php if (($profile_pageurl == "view_winks")) { ?>class="dsp_tab1-active" <?php } ?>>
                                 <?php if ($count_wink_messages > 0) { ?>
                                    <a href="<?php echo $root_link . "home/view_winks/Act/R/"; ?>"><i class="fa fa-meh-o"></i><?php echo language_code('DSP_MIDDLE_TAB_WINKS') ?>&nbsp;<span class="dsp-alert-count">(<?php echo $count_wink_messages ?>)</span></a>
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . "home/view_winks/"; ?>"><i class="fa fa-meh-o"></i><?php echo language_code('DSP_MIDDLE_TAB_WINKS'); ?></a>
                                <?php } ?>
                                </li>
                            <?php } ?>
       
                            <?php if ($check_my_friend_module->setting_status == 'Y') { ?>
                                <li <?php if (($profile_pageurl == "view_friends")) { ?>class="dsp_active_link"  <?php } ?>>
                                    <a href="<?php echo $root_link . "home/view_friends/"; ?>"><i class="fa fa-users"></i><?php echo language_code('DSP_MIDDLE_TAB_FRIENDS'); ?></a>
                                </li>
                            <?php } ?>
                            

                            <li <?php if (($profile_pageurl == "my_favorites")) { ?>class="dsp_active_link" <?php } ?>>
                                <a href="<?php echo $root_link . "home/my_favorites/"; ?>"><i class="fa fa-heart"></i><?php echo language_code('DSP_MIDDLE_TAB_MY_FAVOURITES'); ?></a>
                            </li>
                            
                            
                            <?php if ($check_virtual_gifts_mode->setting_status == 'Y') { ?>
                            <li <?php if (($profile_pageurl == "virtual_gifts")) { ?>class="dsp_active_link" <?php } ?>>
                                <?php if ($count_friends_virtual_gifts > 0) { ?>
                                    <a href="<?php echo $root_link . "home/virtual_gifts/"; ?>"><i class="fa fa-gift"></i><?php echo language_code('DSP_MIDDLE_TAB_VIRTUAL_GIFTS'); ?>&nbsp;<span class="dsp-alert-count">(<?php echo $count_friends_virtual_gifts ?>) </span></a>
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . "home/virtual_gifts/"; ?>"><i class="fa fa-gift"></i><?php echo language_code('DSP_MIDDLE_TAB_VIRTUAL_GIFTS'); ?> </a>
                                <?php } ?>
                            </li>
                            <?php } ?>

                            <li <?php if (($profile_pageurl == "my_matches")) { ?>class="dsp_active_link" <?php } ?>>
                                <a href="<?php echo $root_link . "home/my_matches/"; ?>"><i class="fa fa-star"></i><?php echo language_code('DSP_MIDDLE_TAB_MACTHES'); ?></a>
                            </li>

                            <?php if ($check_match_alert_mode->setting_status == 'Y') { ?>
                                <li <?php if (($profile_pageurl == "match_alert")) { ?>class="dsp_active_link"  <?php } ?>>
                                    <a href="<?php echo $root_link . "home/match_alert/"; ?>"><i
                                            class="fa fa-bell"></i><?php echo language_code('DSP_SUBMENU_SETTINGS_MATCH_ALERTS'); ?>
                                    </a>
                                </li>
                            <?php } ?>

                            <li <?php if ($profile_pageurl == "alerts") { ?>class="dsp_active_link" <?php } ?>>
                                <?php if ($count_friends_request > 0) { ?>
                                    <a href="<?php echo $root_link . "home/alerts/"; ?>"><i class="fa fa-bell"></i><?php echo language_code('DSP_MIDDLE_TAB_ALERTS'); ?>&nbsp;<span class="dsp-alert-count">(<?php echo $count_friends_request ?>)</span> </a>
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . "home/alerts/"; ?>"><i class="fa fa-bell"></i><?php echo language_code('DSP_MIDDLE_TAB_ALERTS'); ?></a>
                                <?php } ?>
                            </li>
                            
                            <?php if ($check_comments_mode->setting_status == 'Y') { ?>
                                <li <?php if (($profile_pageurl == "comments")) { ?>class="dsp_active_link" <?php } ?>>

                                <?php if ($check_approve_comments_status->setting_status == 'Y') { ?>
                                    <?php if ($count_friends_comments > 0) { ?>
                                    <a href="<?php echo $root_link . "home/comments/"; ?>" style="color:#FF0000;">
                                        <i class="fa fa-comments-o"></i><?php echo language_code('DSP_MIDDLE_TAB_COMMENTS'); ?>&nbsp;<span class="dsp-alert-count">(<?php echo $count_friends_comments ?>)</span>
                                    </a>
                                    <?php } else { ?>
                                    <a href="<?php echo $root_link . "home/comments/"; ?>">
                                        <i class="fa fa-comments-o"></i><?php echo language_code('DSP_MIDDLE_TAB_COMMENTS'); ?>
                                    </a>
                                    <?php } ?>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . "home/comments/"; ?>">
                                            <i class="fa fa-comments-o"></i><?php echo language_code('DSP_MIDDLE_TAB_COMMENTS'); ?>
                                        </a>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                             

                            <li <?php if ($profile_pageurl == "news_feed") { ?>class="dsp_active_link" <?php } ?>>
                                <a href="<?php echo $root_link . "home/news_feed/"; ?>"><i class="fa fa-bullhorn"></i><?php echo language_code('DSP_MIDDLE_TAB_NEWS_FEED'); ?></a>
                            </li>

                        </ul>

                    </div>
                </div>
                <div class="friends-member dsp-md-9">
                    <?php
                    $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table WHERE user_id = '$member_id' AND approved_status='Y'");
                    $i = 0;
                    foreach ($member_exist_friends as $member_friends) {

                        if (($i % 4) == 0) {
                            ?>
                        <?php }  // End if(($i%4)==0) ?>
                        <p>
                        <div class="image-container">
                            <a href="<?php
                            echo add_query_arg(array('pid' => 3, 'mem_id' => $member_friends->friend_uid,
                                'pagetitle' => "view_profile"), $root_link);
                            ?>"> <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"  /></a>
                        </div>
                        </p>
                        <?php
                        $i++;
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php
    }   // ------------------------------------------------- End if Check in my friend list --------------------------------- //
} else {
// -------------------------------------- else  Privacy Setting for Everyone ------------------------------------------- // 
    ?>
    <div class="box-border show-details">
        <div class="friends-member">
            <div class="heading-text">My Friends</div>
            <div style="clear:both;"></div>
            <?php
            if ($check_couples_mode->setting_status == 'Y') {
                $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$member_id' AND friends.approved_status='Y'");
            } else {
                $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$member_id' AND friends.approved_status='Y' AND profile.gender!='C'");
            }
            $i = 0;
            foreach ($member_exist_friends as $member_friends) {
                $displayed_member_name = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$member_friends->friend_uid'");
                if (($i % 4) == 0) {
                    ?>
                <?php }  // End if(($i%4)==0)    ?>
                <div class="image-container">
                    <span class="delete-icon" title="<?php echo language_code('DSP_DELETE_LINK'); ?>" onclick="delete_friend_from_list('<?php echo $member_friends->friend_uid; ?>');">&times;</span> 
                    <?php if ($user_id == '') { ?>

                        <a href="<?php
                        echo add_query_arg(array('pgurl' => 'view_member', 'guest_pageurl' => 'view_mem_profile',
                            'mem_id' => $member_friends->friend_uid), $root_link);
                        ?>" title="<?php echo $displayed_member_name; ?>"> <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"  style="width:100px; height:100px;"  alt="<?php echo $displayed_member_name; ?>" /></a>

                        <?php
                    } else {
                        $favt_mem = array();
                        $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_friends->friend_uid'");
                        foreach ($private_mem as $private) {
                            $favt_mem[] = $private->favourite_user_id;
                        }
                        ?>

                        <?php
                        if ($check_couples_mode->setting_status == 'Y') {
                            if ($member_friends->gender == 'C') {
                                ?>
                                <?php if ($member_friends->make_private == 'Y') { ?>

                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $member_friends->friend_uid,
                                            'pagetitle' => "view_profile", 'view' => "my_profile"), $root_link);
                                        ?>" title="<?php echo $displayed_member_name; ?>" >
                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:100px; height:100px;"border="0"  alt="<?php echo $displayed_member_name; ?>" />
                                        </a>                
                                    <?php } else {
                                        ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $member_friends->friend_uid,
                                            'pagetitle' => "view_profile", 'view' => "my_profile"), $root_link);
                                        ?>" title="<?php echo $displayed_member_name; ?>" >				
                                            <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"     width="100" height="100" alt="<?php echo $displayed_member_name; ?>" /></a>                
                                        <?php
                                    }
                                } else {
                                    ?>

                                    <a  href="<?php
                                    echo add_query_arg(array('pid' => 3, 'mem_id' => $member_friends->friend_uid,
                                        'pagetitle' => "view_profile", 'view' => "my_profile"), $root_link);
                                    ?>" title="<?php echo $displayed_member_name; ?>"> 
                                        <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"  style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" />
                                    </a>
                                <?php } ?>

                            <?php } else { ?>

                                <?php if ($member_friends->make_private == 'Y') { ?>

                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $member_friends->friend_uid,
                                            'pagetitle' => "view_profile"), $root_link);
                                        ?>"  title="<?php echo $displayed_member_name; ?>">
                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:100px; height:100px;"border="0" alt="<?php echo $displayed_member_name; ?>"  />
                                        </a>                
                                    <?php } else {
                                        ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $member_friends->friend_uid,
                                            'pagetitle' => "view_profile"), $root_link);
                                        ?>"  title="<?php echo $displayed_member_name; ?>">				
                                            <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"     width="100" height="100" alt="<?php echo $displayed_member_name; ?>"/></a>                
                                        <?php
                                    }
                                } else {
                                    ?>

                                    <a href="<?php
                                    echo add_query_arg(array('pid' => 3, 'mem_id' => $member_friends->friend_uid,
                                        'pagetitle' => "view_profile"), $root_link);
                                    ?>" title="<?php echo $displayed_member_name; ?>"> 
                                        <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"  style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" />
                                    </a>
                                <?php } ?>

                                <?php
                            }
                        } else {
                            ?> 

                            <?php if ($member_friends->make_private == 'Y') { ?>

                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                    <a href="<?php
                                    echo add_query_arg(array('pid' => 3, 'mem_id' => $member_friends->friend_uid,
                                        'pagetitle' => "view_profile"), $root_link);
                                    ?>" title="<?php echo $displayed_member_name; ?>" >
                                        <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:100px; height:100px;"border="0"  alt="Private photo" />
                                    </a>                
                                <?php } else {
                                    ?>
                                    <a href="<?php
                                    echo add_query_arg(array('pid' => 3, 'mem_id' => $member_friends->friend_uid,
                                        'pagetitle' => "view_profile"), $root_link);
                                    ?>" title="<?php echo $displayed_member_name; ?>">				
                                        <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"     width="100" height="100" alt="<?php echo $displayed_member_name; ?>" /></a>                
                                    <?php
                                }
                            } else {
                                ?>

                                <a href="<?php
                                echo add_query_arg(array('pid' => 3, 'mem_id' => $member_friends->friend_uid,
                                    'pagetitle' => "view_profile"), $root_link);
                                ?>" title="<?php echo $displayed_member_name; ?>"> 
                                    <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"  style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" />
                                </a>
                            <?php } ?>

                        <?php } ?>

                        <?php
                        unset($favt_mem);
                    }
                    ?>
                    <?php /* ?> <a href="<?php echo add_query_arg( array('pid' =>3,'mem_id'=>$member_friends->friend_uid,'pagetitle'=>"view_profile"), $root_link); ?>"> <img src="<?php echo display_members_photo($member_friends->friend_uid,$pluginpath); ?>" height="85px"  /></a><?php */ ?>
                    <div style="clear:both"></div>
                    <span class="user-name-show"><?php if ($member_friends->gender == 'C') {
                        ?>
                            <a href="<?php
                            echo add_query_arg(array('pid' => 3, 'mem_id' => $member_friends->friend_uid,
                                'pagetitle' => "view_profile", 'view' => "my_profile"), $root_link);
                            ?>" >	
                               <?php } else { ?>
                                <a href="<?php
                                echo add_query_arg(array('pid' => 3, 'mem_id' => $member_friends->friend_uid,
                                    'pagetitle' => "view_profile"), $root_link);
                                ?>">		
                                   <?php } ?>
                                   <?php echo $displayed_member_name; ?>
                            </a>
                    </span>
                    <?php if ($_REQUEST['pid'] == 1) { ?>

                    <?php } ?>
                </div>
                <?php
                $i++;
            }
            ?>
        </div>
    </div>

<?php } ?>