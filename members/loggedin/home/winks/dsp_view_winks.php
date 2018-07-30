<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$delwinks_msg_id = get('del_wink_id');
$Actiondel = get('Action');
if (($delwinks_msg_id != "") && ($Actiondel == "Del")) {
    $wpdb->query("DELETE FROM $dsp_member_winks_table where wink_mesage_id  = '$delwinks_msg_id'");
}
$request_Action = get('Act');
if (($request_Action == "R")) {
    $wpdb->query("UPDATE $dsp_member_winks_table SET wink_read='Y' where receiver_id='$user_id'");
}
if ($check_couples_mode->setting_status == 'Y') {
    $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_winks_table winks, $dsp_user_profiles profile WHERE winks.sender_id = profile.user_id AND winks.receiver_id = '$user_id'");
} else {
    $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_winks_table winks, $dsp_user_profiles profile WHERE winks.sender_id = profile.user_id AND winks.receiver_id = '$user_id' AND profile.gender!='C'");
//$total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_winks_table where receiver_id ='$user_id'");
}
?>
<?php if ($total_results1 > 0) { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="dsp_vertical_scrollbar">
                <div class="dsp-row">
                    <div class="dsp-md-4 dspdp-hidden">              
                        <div class="box-profile-link">

                            <div class="menus-profile">
                                <ul>
                                    <li>
                                        <?php
                                            if ($check_couples_mode->setting_status == 'Y') {
                                                if ($gender == 'C') {
                                        ?>
                                            <a href="<?php echo $root_link . get_username($user_id) . "/my_profile/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title="<?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" alt="<?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" />
                                        <?php } else { ?>
                                            <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>"  alt=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>"/>     
                                        <?php }  } else {  ?> 

                                            <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" alt=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" />       

                                        <?php } ?></a>
                                    </li>
                                    <li><a href="<?php echo $root_link . "extras/trending/"; ?>"><img src="<?php echo $fav_icon_image_path ?>profile.jpg" title="<?php echo language_code('DSP_PROFILE_TRENDINGS') ?>" alt="<?php echo language_code('DSP_PROFILE_TRENDINGS') ?>" /></a></li>
                                    <li><a href="<?php echo $root_link . "extras/viewed_me/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_viewedme.jpg" title="<?php echo language_code('DSP_WHO_VIEWED_ME') ?>" alt="<?php echo language_code('DSP_WHO_VIEWED_ME') ?>" /></a></li>
                                    <li><a href="<?php echo $root_link . "extras/i_viewed/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_iviewed.jpg" title="<?php echo language_code('DSP_WHO_I_VIEWED') ?>" alt="<?php echo language_code('DSP_WHO_I_VIEWED') ?>" /></a> </li>
                                    <li><a href="<?php echo $root_link . "online_members/show/all/"; ?>"><img src="<?php echo $fav_icon_image_path ?>whos_online.jpg" title="<?php echo language_code('DSP_ONLINE_MEMBER') ?>&nbsp;(<?php echo $count_online_member ?>)" alt="<?php echo language_code('DSP_ONLINE_MEMBER') ?>" /></a></li>
                                    <li><a href="<?php echo $root_link . "email/inbox/"; ?>"><img src="<?php echo $fav_icon_image_path ?>message.jpg" title="<?php echo language_code('DSP_NEW_EMAIL'); ?>&nbsp;(<?php echo $count_inbox_messages ?>)"  border="0" alt="<?php echo language_code('DSP_NEW_EMAIL'); ?>"/></a></li>
                                </ul>
                            </div>
                            <div class="clr"></div>
                            <ul class="text-left dsp-user-spec clearfix">
                         
                                <?php if ($check_flirt_module->setting_status == 'Y') { ?>
                                    <li <?php if (($profile_pageurl == "view_winks")) { ?>class="dsp_active_link" <?php } ?>>
                                     <?php if ($count_wink_messages > 0) { ?>
                                        <a href="<?php echo $root_link . "home/view_winks/Act/R/"; ?>"><i class="fa fa-meh-o"></i><?php echo language_code('DSP_MIDDLE_TAB_WINKS') ?>&nbsp;(<?php echo $count_wink_messages ?>)</a>
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
                                        <a href="<?php echo $root_link . "home/virtual_gifts/"; ?>"><i class="fa fa-gift"></i><?php echo language_code('DSP_MIDDLE_TAB_VIRTUAL_GIFTS'); ?>&nbsp;(<?php echo $count_friends_virtual_gifts ?>) </a>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . "home/virtual_gifts/"; ?>"><i class="fa fa-gift"></i><?php echo language_code('DSP_MIDDLE_TAB_VIRTUAL_GIFTS'); ?> </a>
                                    <?php } ?>
                                </li>
                                <?php } ?>

                                <li <?php if (($profile_pageurl == "my_matches")) { ?>class="dsp_active_link" <?php } ?>>
                                    <a href="<?php echo $root_link . "home/my_matches/"; ?>"><i class="fa fa-star"></i><?php echo language_code('DSP_MIDDLE_TAB_MACTHES'); ?></a>
                                </li>


                                <li <?php if ($profile_pageurl == "alerts") { ?>class="dsp_active_link" <?php } ?>>
                                    <?php if ($count_friends_request > 0) { ?>
                                        <a href="<?php echo $root_link . "home/alerts/"; ?>"><i class="fa fa-bell"></i><?php echo language_code('DSP_MIDDLE_TAB_ALERTS'); ?>&nbsp;(<?php echo $count_friends_request ?>) </a>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . "home/alerts/"; ?>"><i class="fa fa-bell"></i><?php echo language_code('DSP_MIDDLE_TAB_ALERTS'); ?></a>
                                    <?php } ?>
                                </li>
                                
                                <?php if ($check_comments_mode->setting_status == 'Y') { ?>
                                    <li <?php if (($profile_pageurl == "comments")) { ?>class="dsp_active_link" <?php } ?>>

                                    <?php if ($check_approve_comments_status->setting_status == 'Y') { ?>
                                        <?php if ($count_friends_comments > 0) { ?>
                                        <a href="<?php echo $root_link . "home/comments/"; ?>" style="color:#FF0000;">
                                            <i class="fa fa-comments-o"></i><?php echo language_code('DSP_MIDDLE_TAB_COMMENTS'); ?>&nbsp;(<?php echo $count_friends_comments ?>)
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

                    <div class="dsp-md-8">
                
                <h3 class="heading-feed margin-btm-2"><?php echo language_code('DSP_MY_WINKS');?></h3>
                <div class="box-page dspdp-row">
                    <form name="del`textfrm" action="" method="post">
                        <?php
                        if ($check_couples_mode->setting_status == 'Y') {
                            $my_winks = $wpdb->get_results("SELECT * FROM $dsp_member_winks_table winks, $dsp_user_profiles profile WHERE winks.sender_id = profile.user_id
                                        AND winks.receiver_id = '$user_id' ORDER BY winks.send_date");
                        } else {
                            $my_winks = $wpdb->get_results("SELECT * FROM $dsp_member_winks_table winks, $dsp_user_profiles profile WHERE winks.sender_id = profile.user_id
                                        AND winks.receiver_id = '$user_id' AND profile.gender!='C' ORDER BY winks.send_date");
                        }
                        foreach ($my_winks as $winks) {
                            $wink_msg_id = $winks->wink_mesage_id;
                            $wink_sender_id = $winks->sender_id;
                            $wink_id = $winks->wink_id;

                            $language_code = dsp_get_current_user_language_code();

                            if( $language_code == 'en' || $language_code == null || empty($language_code) )
                                $dsp_flirt_table = $wpdb->prefix . 'dsp_flirt';
                            else
                                $dsp_flirt_table = $wpdb->prefix . 'dsp_flirt' . '_' . $language_code;

                            $exist_wink_message = $wpdb->get_row("SELECT * FROM $dsp_flirt_table WHERE Flirt_ID = '$wink_id'");
                            $sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$wink_sender_id'");
                            $dateTimeFormat = dsp_get_date_timezone();
                            extract($dateTimeFormat);
                            $message_sent_date = date("$dateFormat $timeFormat", strtotime($winks->send_date));
                            $favt_mem = array();
                            $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$wink_sender_id'");
                            foreach ($private_mem as $private) {
                                $favt_mem[] = $private->favourite_user_id;
                            }
                            ?>  
                            <div class="dspdp-col-sm-4 dspdp-wink-sender">
                                <ul class="details-row image-container">
                                <li class="image-box">
                                    <span class="dspdp-block dspdp-spacer dsp-friend-image-holder">
                                        <?php
                                    if ($check_couples_mode->setting_status == 'Y') {
                                        if ($winks->gender == 'C') {
                                            ?>
                                            <?php if ($winks->make_private == 'Y') { ?>
                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php echo $root_link . get_username($wink_sender_id) . "/my_profile/"; ?>" >
                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:60px; height:60px;" class="img2" alt="Private Photo"/>
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($wink_sender_id) . "/my_profile/"; ?>" >              
                                                        <img src="<?php echo display_members_photo($wink_sender_id, $imagepath); ?>"  style="width:60px; height:60px;" class="img2" alt="<?php echo get_username($wink_sender_id); ?>"/></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <a href="<?php echo $root_link . get_username($wink_sender_id) . "/my_profile/"; ?>">
                                                    <img src="<?php echo display_members_photo($wink_sender_id, $imagepath); ?>" style="width:60px; height:60px;" class="img2" alt="<?php echo get_username($wink_sender_id); ?>" />
                                                </a>
                                            <?php } ?>


                                        <?php } else { ?>

                                            <?php if ($winks->make_private == 'Y') { ?>

                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php echo $root_link . get_username($wink_sender_id) . "/"; ?>" >
                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:60px; height:60px;" class="img2" alt="Private Photo"/>
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($wink_sender_id) . "/"; ?>" >             
                                                        <img src="<?php echo display_members_photo($wink_sender_id, $imagepath); ?>" style="width:60px; height:60px;" class="img2" alt="<?php echo get_username($wink_sender_id); ?>"/></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <a href="<?php echo $root_link . get_username($wink_sender_id) . "/"; ?>">
                                                    <img src="<?php echo display_members_photo($wink_sender_id, $imagepath); ?>" style="width:60px; height:60px;" class="img2" alt="<?php echo get_username($wink_sender_id); ?>" />
                                                </a>
                                            <?php } ?>

                                            <?php
                                        }
                                    } else {
                                        ?> 
                                        <?php if ($winks->make_private == 'Y') { ?>

                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                <a href="<?php echo $root_link . get_username($wink_sender_id) . "/"; ?>" >
                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:60px; height:60px;" class="img2"  alt="Private Photo"/>
                                                </a>                
                                            <?php } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($wink_sender_id) . "/"; ?>" >             
                                                    <img src="<?php echo display_members_photo($wink_sender_id, $imagepath); ?>"   style="width:60px; height:60px;" class="img2" alt="<?php echo get_username($wink_sender_id); ?>" /></a>                
                                                <?php
                                            }
                                        } else {
                                            ?>

                                            <a href="<?php echo $root_link . get_username($wink_sender_id) . "/"; ?>">
                                                <img src="<?php echo display_members_photo($wink_sender_id, $imagepath); ?>" style="width:60px; height:60px;" class="img2" alt="<?php echo get_username($wink_sender_id); ?>" />
                                            </a>
                                        <?php } ?>
                                    <?php } ?></span>
                                </li>
                                <li class="mid">
                                    <div class="user-name-show">
                                        <p class="dsp_page_link title dspdp-font-2x"><strong>
                                            <?php
                                            if ($check_couples_mode->setting_status == 'Y') {
                                                if ($winks->gender == 'C') {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($wink_sender_id) . "/my_profile/"; ?>"><?php echo $sender_name->display_name ?></a>
                                                <?php } else { ?>
                                                    <a href="<?php echo $root_link . get_username($wink_sender_id) . "/"; ?>"><?php echo $sender_name->display_name ?></a>
                                                    <?php
                                                }
                                            } else {
                                                ?> 
                                                <a href="<?php echo $root_link . get_username($wink_sender_id) . "/"; ?>"><?php echo $sender_name->display_name ?></a>
                                            <?php } ?>

                                        </strong></p>
                                    <p class="description"><span class="dspdp-glyphicon dspdp-glyphicon-comment"></span> <?php echo $exist_wink_message->flirt_Text ?></p>
                                    <div class="dspdp-spacer"><p class="date"><span class="dspdp-glyphicon dspdp-glyphicon-time"></span> <?php echo $message_sent_date ?></p></div>
                                    </div>
                                </li>
                                <li class="last">
                                    <a href="<?php echo $root_link . "home/view_winks/Action/Del/del_wink_id/" . $wink_msg_id; ?>" class="dsp_span_pointer dspdp-btn dspdp-btn-danger dspdp-btn-xs dsp-none">
                                        <?php echo language_code('DSP_DELETE_LINK'); ?>
                                    </a>
                                    <a href="<?php echo $root_link . "home/view_winks/Action/Del/del_wink_id/" . $wink_msg_id; ?>" class="dsp-block dsp-delete" style="display:none">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </li>
                            </ul></div>
                            <?php
                            unset($favt_mem);
                        } // foreach($my_winks as $winks)  
                        ?>
                    </form>
                </div>
                </div>
            </div>
           </div> 

        </div>
    </div>
<?php } else { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="dsp_vertical_scrollbar">
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
                                    <li><a href="<?php echo $root_link . "extras/trending/"; ?>"><img src="<?php echo $fav_icon_image_path ?>profile.jpg" title="<?php echo language_code('DSP_PROFILE_TRENDINGS') ?>" /></a></li>
                                    <li><a href="<?php echo $root_link . "extras/viewed_me/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_viewedme.jpg" title="<?php echo language_code('DSP_WHO_VIEWED_ME') ?>" /></a></li>
                                    <li><a href="<?php echo $root_link . "extras/i_viewed/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_iviewed.jpg" title="<?php echo language_code('DSP_WHO_I_VIEWED') ?>" /></a> </li>
                                    <li><a href="<?php echo $root_link . "online_members/show/all/"; ?>"><img src="<?php echo $fav_icon_image_path ?>whos_online.jpg" title="<?php echo language_code('DSP_ONLINE_MEMBER') ?>&nbsp;(<?php echo $count_online_member ?>)" /></a></li>
                                    <li><a href="<?php echo $root_link . "email/inbox/"; ?>"><img src="<?php echo $fav_icon_image_path ?>message.jpg" title="<?php echo language_code('DSP_NEW_EMAIL'); ?>&nbsp;(<?php echo $count_inbox_messages ?>)"  border="0"/></a></li>
                                </ul>
                            </div>
                            <div class="clr"></div>
                            <ul class="text-left dsp-user-spec clearfix">
                         
                                <?php if ($check_flirt_module->setting_status == 'Y') { ?>
                                    <li <?php if (($profile_pageurl == "view_winks")) { ?>class="dsp_active_link" <?php } ?>>
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
                                            <i class="fa fa-comments-o"></i><?php echo language_code('DSP_MIDDLE_TAB_COMMENTS'); ?>&nbsp;(<?php echo $count_friends_comments ?>)
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
                    <div style=" text-align:center;" class="box-page dsp-md-9">
                        <div class="error">
                            <strong><?php echo language_code('DSP_NO_WINK_MSG') ?></strong>
                        </div>                        
                    </div>
                </div>                
            </div>
        </div>
    </div>

<?php } ?>