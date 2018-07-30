<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$delfavourites = get('favourite_Id');
$Actiondel = get('Action');
if (($delfavourites != "") && ($Actiondel == "Del")) {
    $wpdb->query("DELETE FROM $dsp_user_favourites_table where favourite_id = '$delfavourites'");
}
$total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_favourites_table where user_id='$user_id'");
$redirect_location = ROOT_LINK . "home/my_favorites/";
?>

<div class="box-border">
    <div class="box-pedding">
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
                                <a href="<?php echo $root_link . get_username($user_id) . "/my_profile/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title="<?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>"  alt="<?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" />
                            <?php } else { ?>
                                <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" alt=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>"/>     
                            <?php }  } else {  ?> 

                                <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" alt=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" />       

                            <?php } ?></a>
                        </li>
                        <li><a href="<?php echo $root_link . "extras/trending/"; ?>"><img src="<?php echo $fav_icon_image_path ?>profile.jpg" title="<?php echo language_code('DSP_PROFILE_TRENDINGS') ?>" alt="<?php echo language_code('DSP_PROFILE_TRENDINGS') ?>"/></a></li>
                        <li><a href="<?php echo $root_link . "extras/viewed_me/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_viewedme.jpg" title="<?php echo language_code('DSP_WHO_VIEWED_ME') ?>" alt="<?php echo language_code('DSP_WHO_VIEWED_ME') ?>"/></a></li>
                        <li><a href="<?php echo $root_link . "extras/i_viewed/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_iviewed.jpg" title="<?php echo language_code('DSP_WHO_I_VIEWED') ?>" title="<?php echo language_code('DSP_WHO_I_VIEWED') ?>" /></a> </li>
                        <li><a href="<?php echo $root_link . "online_members/show/all/"; ?>"><img src="<?php echo $fav_icon_image_path ?>whos_online.jpg" title="<?php echo language_code('DSP_ONLINE_MEMBER') ?>&nbsp;(<?php echo  isset($count_online_member) ? $count_online_member : ''; ?>)"   alt="<?php echo language_code('DSP_ONLINE_MEMBER') ?>"/></a></li>
                        <li><a href="<?php echo $root_link . "email/inbox/"; ?>"><img src="<?php echo $fav_icon_image_path ?>message.jpg" title="<?php echo language_code('DSP_NEW_EMAIL'); ?>&nbsp;(<?php echo $count_inbox_messages ?>)"  border="0" alt="<?php echo language_code('DSP_NEW_EMAIL'); ?>"/></a></li>
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
                            <a href="<?php echo $root_link . "home/virtual_gifts/"; ?>"><i class="fa fa-gift"></i><?php echo language_code('DSP_MIDDLE_TAB_VIRTUAL_GIFTS'); ?>&nbsp;<span class="dsp-alert-count">(<?php echo $count_friends_virtual_gifts ?>)</span> </a>
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
                            <a href="<?php echo $root_link . "home/alerts/"; ?>"><i class="fa fa-bell"></i><?php echo language_code('DSP_MIDDLE_TAB_ALERTS'); ?>&nbsp;<span class="dsp-alert-count">(<?php echo $count_friends_request ?>) </span></a>
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
        <div class="dsp-md-9">
            <?php
            if ($total_results1 > 0) {
                ?>
                <form name="delfavoritesfrm" action="" method="post">

                    <h3 class="heading-feed margin-btm-2"><?php echo language_code('DSP_MY_FAVOURITES') ?></h3>
                    <div style="clear:both;"></div>
                    <div class="dspdp-row dsp-row">
                        <?php
                        if ($check_couples_mode->setting_status == 'Y') {
                            $my_favourites = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table favourites, $dsp_user_profiles profile WHERE favourites.favourite_user_id = profile.user_id
    AND favourites.user_id = '$user_id' ORDER BY favourites.fav_date_added");
                        } else {
                            $my_favourites = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table favourites, $dsp_user_profiles profile WHERE favourites.favourite_user_id = profile.user_id
    AND favourites.user_id = '$user_id' AND profile.gender!='C' ORDER BY favourites.fav_date_added");
                        }
                        $i = 0;
                        foreach ($my_favourites as $favourites) {
                            $favourite_id = $favourites->favourite_id;
                            $fav_user_id = $favourites->favourite_user_id;
                            $fav_screenname = $favourites->fav_screenname;
                            $favt_mem = array();
                            $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$fav_user_id'");
                            foreach ($private_mem as $private) {
                                $favt_mem[] = $private->favourite_user_id;
                            }
                            $displayed_member_name = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$fav_user_id'");
                            if (($i % 3) == 0) {
                                ?>
                            <?php }  // End if(($i%4)==0) ?>
                        <div class="dspdp-col-sm-3 dspdp-col-xs-6 dsp-sm-6"><div class="image-container">
                            <div class="dsp-friend-image-holder">
                                <a href="<?php echo $redirect_location . "Action/Del/favourite_Id/" . $favourite_id; ?>"  onclick="if (!confirm('<?php echo language_code('DSP_DELETE_FAVOURATE_MEMBER_MESSAGE'); ?>'))
                                            return false;" class="dspdp-del" >
                                            <span class="delete-icon dsp_span_pointer" title="<?php echo language_code('DSP_DELETE_LINK') ?>">&times;</span></a>
                                   <?php
                                   if ($check_couples_mode->setting_status == 'Y') {
                                       if ($favourites->gender == 'C') {
                                           ?>

                                        <?php if ($favourites->make_private == 'Y') { ?>

                                            <?php if ($current_user->ID != $fav_user_id) { ?>


                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php echo $root_link . get_username($fav_user_id) . "/my_profile/"; ?>" title="<?php echo $displayed_member_name; ?>" >
                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;"  border="0" class="img" alt="Private Photo" />
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($fav_user_id) . "/my_profile/"; ?>" title="<?php echo $displayed_member_name; ?>" >				
                                                        <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="img" style="width:100px; height:100px;"  alt="<?php echo $displayed_member_name; ?>"/></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($fav_user_id) . "/my_profile/"; ?>" title="<?php echo $displayed_member_name; ?>">				
                                                    <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="img" style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>"/></a>                
                                            <?php } ?>
                                        <?php } else {
                                            ?>                
                                            <a href="<?php echo $root_link . get_username($fav_user_id) . "/my_profile/"; ?>" title="<?php echo $displayed_member_name; ?>" >				
                                                <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="img" style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>"/></a>
                                        <?php } ?>
                                    <?php } else { ?>

                                        <?php if ($favourites->make_private == 'Y') { ?>
                                            <?php if ($current_user->ID != $fav_user_id) { ?>

                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php echo $root_link . get_username($fav_user_id) . "/"; ?>" title="<?php echo $displayed_member_name; ?>" >
                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0" class="img" alt="Private Photo" />
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($fav_user_id) . "/"; ?>" title="<?php echo $displayed_member_name; ?>" >				
                                                        <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="img" style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" /></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($fav_user_id) . "/"; ?>" title="<?php echo $displayed_member_name; ?>" >				
                                                    <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="img" style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>"/></a>                
                                            <?php } ?>
                                        <?php } else { ?> 
                                            <a href="<?php echo $root_link . get_username($fav_user_id) . "/"; ?>" title="<?php echo $displayed_member_name; ?>">				
                                                <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"   class="img" style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" /></a>
                                        <?php } ?>

                                        <?php
                                    }
                                } else {
                                    ?>

                                    <?php if ($favourites->make_private == 'Y') { ?>
                                        <?php if ($current_user->ID != $fav_user_id) { ?>

                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                <a href="<?php echo $root_link . get_username($fav_user_id) . "/"; ?>"  title="<?php echo $displayed_member_name; ?>">
                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0" class="img" alt="Private Photo" />
                                                </a>                
                                            <?php } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($fav_user_id) . "/"; ?>" title="<?php echo $displayed_member_name; ?>" >				
                                                    <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="img" style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>"/></a>                
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($fav_user_id) . "/"; ?>" title="<?php echo $displayed_member_name; ?>" >				
                                                <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="img" style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>"/></a>                
                                        <?php } ?>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . get_username($fav_user_id) . "/"; ?>" title="<?php echo $displayed_member_name; ?>">				
                                            <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"   class="img" style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>"/></a>
                                    <?php } ?>

                                <?php } ?>
                            </div>
                            <div class="dsp_name" align="center">
                                <span class="user-name-show"><?php if ($favourites->gender == 'C') {
                                ?>
                                        <a href="<?php echo $root_link . get_username($fav_user_id) . "/my_profile/"; ?>" >	
                                        <?php } else { ?>
                                            <a href="<?php echo $root_link . get_username($fav_user_id) . "/"; ?>">		
                                            <?php } ?>
                                            <?php echo $displayed_member_name; ?>
                                        </a>
                                </span>

                            </div>

                            <a href="<?php echo $redirect_location . "Action/Del/favourite_Id/" . $favourite_id; ?>" onclick="if (!confirm('<?php echo language_code('DSP_DELETE_FAVOURATE_MEMBER_MESSAGE'); ?>'))
                                            return false;" class="dsp-block dsp-delete" style="display:none" title="<?php echo language_code('DSP_DELETE_LINK') ?>"><i class="fa fa-trash-o"></i></a>
                        
                        </div></div>
                        <?php
                        $i++;
                        unset($favt_mem);
                    }
                    ?>
                    </p></div>
                </form>
            </div>
        </div>
        <?php } else { ?>
            <div align="center">
                <div class="error">
                    <strong><?php echo language_code('DSP_NO_FAVOURITES_MSG') ?></strong>
                </div>                
            </div>
			</div>
			</div>
        <?php } ?>
    </div>
</div>