<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$users_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;
$action = get('Action');
$gift_id = get('gift_Id');
if ($action == 'Del') {
    $check_gift = $wpdb->get_var("SELECT count(*) FROM $dsp_user_virtual_gifts  WHERE gift_id = '$gift_id'");
    if ($check_gift != 0) {
        $delete = $wpdb->query("delete from $dsp_user_virtual_gifts  WHERE gift_id = '$gift_id' ");
        $delete_gift_msg = language_code("DSP_VIRTUAL_GIFT_DELETED");
    }
    ?><script>location.href = "<?php echo $root_link . "home/virtual_gifts/"; ?>"</script><?php
}
if ($action == 'approve') {
    $check_gift = $wpdb->get_var("SELECT count(*) FROM $dsp_user_virtual_gifts  WHERE gift_id = '$gift_id'");
    if ($check_gift != 0) {
        $wpdb->query("update $dsp_user_virtual_gifts set status_id=1 WHERE gift_id = '$gift_id' ");
        $result = language_code("Virtual Gift has been Approved");
    }
    ?><script>location.href = "<?php echo $root_link . "home/virtual_gifts/"; ?>"</script><?php
}
?>
<?php if (isset($delete_gift_msg) && $delete_gift_msg != "") { ?>
    <div class="thanks">
        <p align="center" class="error"><?php echo $delete_gift_msg ?></p>
    </div>
    <?php
}
if (isset($result) && $result != "") {
    ?><div class="thanks">
        <p align="center" class="error"><?php echo $result ?></p>
    </div>
<?php }
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
                                    <a href="<?php echo $root_link . get_username($user_id) . "/my_profile/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title="<?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" alt="<?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>"/>
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" alt=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" />     
                                <?php }  } else {  ?> 

                                    <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" alt=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" />       

                                <?php } ?></a>
                            </li>
                            <li><a href="<?php echo $root_link . "extras/trending/"; ?>"><img src="<?php echo $fav_icon_image_path ?>profile.jpg" title="<?php echo language_code('DSP_PROFILE_TRENDINGS') ?>"  alt="<?php echo language_code('DSP_PROFILE_TRENDINGS') ?>"/></a></li>
                            <li><a href="<?php echo $root_link . "extras/viewed_me/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_viewedme.jpg" title="<?php echo language_code('DSP_WHO_VIEWED_ME') ?>" alt="<?php echo language_code('DSP_WHO_VIEWED_ME') ?>" /></a></li>
                            <li><a href="<?php echo $root_link . "extras/i_viewed/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_iviewed.jpg" title="<?php echo language_code('DSP_WHO_I_VIEWED') ?>" alt="<?php echo language_code('DSP_WHO_I_VIEWED') ?>"/></a> </li>
                            <li><a href="<?php echo $root_link . "online_members/show/all/"; ?>"><img src="<?php echo $fav_icon_image_path ?>whos_online.jpg" title="<?php echo language_code('DSP_ONLINE_MEMBER') ?>&nbsp;(<?php echo $count_online_member ?>)" alt="<?php echo language_code('DSP_ONLINE_MEMBER') ?>"/></a></li>
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
                    <h3 class="heading-feed margin-btm-2"><?php echo language_code('DSP_MY_GIFTS'); ?></h3>
                    <div class="dspdp-row ">
                    <?php
                        //$gift_chk = $wpdb->get_var("SELECT count(*) FROM `$dsp_user_virtual_gifts` where member_id=$user_id and status_id=0 ");
                        $gift_chk = $wpdb->get_var("SELECT count(*) FROM `$dsp_user_virtual_gifts` where member_id=$user_id");
                       if ($gift_chk != 0) {     
                        //$gift_list = $wpdb->get_results("SELECT * FROM `$dsp_user_virtual_gifts` where member_id=$user_id and status_id=0  ORDER BY `date_added` DESC");
                        $gift_list = $wpdb->get_results("SELECT * FROM `$dsp_user_virtual_gifts` where member_id=$user_id ORDER BY `date_added` DESC");
                        foreach ($gift_list as $gifts) {
                            $users_details = $wpdb->get_row("SELECT ID,user_login FROM $users_table  WHERE ID='$gifts->user_id'");
                            $check_gender = $wpdb->get_var("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$gifts->user_id'");
                            ?>
                            <div class="dspdp-col-sm-4 dsp-gift-container dsp-border-container">
                                <div class="dspdp-spacer dspdp-member-col">
                                <div class="row-comment">
                                    <div class="dsp-block dsp-gift-image" style="display:none">
                                        <img class="dspdp-img-responsive dspdp-block-center" src="<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/gifts/" . $gifts->gift_image; ?>" alt="<?php echo $gifts->gift_image;?>"/>
                                    </div>
                                    <div class="dsp-friend-image-holder pull-left">
                                        <a href="<?php
                                            if ($check_gender != 'C') {
                                                echo $root_link . get_username($gifts->user_id) . "/";
                                            } else {
                                                echo $root_link . get_username($gifts->user_id) . "/my_profile/";
                                            }
                                        ?>">
                                            <div class="image-box">
                                                <img class="dspdp-img-responsive dspdp-block-center  dspdp-spacer" title="<?php echo $users_details->user_login; ?>" alt="<?php echo $users_details->user_login; ?>" src="<?php echo display_members_photo($gifts->user_id, $imagepath); ?>" />
                                            </div>
                                        </a>
                                         <a href="<?php if ($check_gender != 'C') {
                                                echo $root_link . get_username($gifts->user_id) . "/";
                                            } else {
                                                echo $root_link . get_username($gifts->user_id) . "/my_profile/";
                                            }?>"><?php echo $users_details->user_login; ?></a>
                                     </div>
                                    <div class="show-comment">
                                        <img class="dspdp-img-responsive dspdp-block-center dsp-none" src="<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/gifts/" . $gifts->gift_image; ?>" alt="<?php echo  $gifts->gift_image;?>"/>
                                        
                                        <?php if($gifts->status_id == 0): ?>
                                            <span class="dsp-none"><br />
                                                <a class="dspdp-btn-sm dspdp-btn dspdp-btn-success" href="<?php echo $root_link . "home/virtual_gifts/Action/approve/gift_Id/" . $gifts->gift_id; ?>"><?php echo language_code('DSP_MEDIA_LINK_APPROVE'); ?></a>
                                                <a class="dspdp-btn-sm dspdp-btn dspdp-btn-danger" href="<?php echo $root_link . "home/virtual_gifts/Action/Del/gift_Id/" . $gifts->gift_id; ?>" onclick="if (!confirm('<?php echo language_code('DSP_DELETE_VIRTUAL_GIFT_MESSAGE'); ?>'))
                                                                return false;"><?php echo language_code('DSP_DELETE'); ?> </a>
                                            </span>

                                             <span class="dsp-block" style="display:none">
                                                <a class="dsp-delete" title="<?php echo language_code('DSP_DELETE'); ?>" href="<?php echo $root_link . "home/virtual_gifts/Action/Del/gift_Id/" . $gifts->gift_id; ?>" onclick="if (!confirm('<?php echo language_code('DSP_DELETE_VIRTUAL_GIFT_MESSAGE'); ?>'))
                                                                return false;"><i class="fa fa-trash-o"></i></a>
                                                <a class="dsp-success" title="<?php echo language_code('DSP_ACCEPT GIFTS'); ?>" href="<?php echo $root_link . "home/virtual_gifts/Action/approve/gift_Id/" . $gifts->gift_id; ?>"><i class="fa fa-gift"></i></a>                                           
                                            </span>
                                        <?php else: ?>
                                            <h3><br></h3>
                                        <?php endif; ?>
                                        
                                    </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        } 
                    }
                    else
                    {
                    ?>
                    <div class="dspdp-col-sm-12 dsp-gift-container dsp-border-container">
                        <div class="dspdp-spacer dspdp-member-col">
                    <?php
                        echo language_code('DSP_NO_RECORD_FOUND'); 
                    ?>
                        </div>
                    </div>
                    <?php } ?>   
                </div>        
            </div>
            <div class="dsp-md-9">    
                    <h3 class="heading-feed margin-btm-2"><?php echo language_code('DSP_SENT_GIFT'); ?></h3>
                    <div class="dspdp-row ">
                    <?php
                        $gift_chk = $wpdb->get_var("SELECT count(*) FROM `$dsp_user_virtual_gifts` where user_id=$user_id");
                       if ($gift_chk != 0) {
                        $gift_list = $wpdb->get_results("SELECT * FROM `$dsp_user_virtual_gifts` where user_id=$user_id ORDER BY `date_added` DESC");
                        foreach ($gift_list as $gifts) {
                            $users_details = $wpdb->get_row("SELECT ID,user_login FROM $users_table  WHERE ID='$gifts->member_id'");
                            $check_gender = $wpdb->get_var("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$gifts->member_id'");
                            ?>
                            <div class="dspdp-col-sm-4 dsp-gift-container dsp-border-container">
                                <div class="dspdp-spacer dspdp-member-col">
                                <div class="row-comment">
                                    <div class="dsp-block dsp-gift-image" style="display:none">
                                        <img class="dspdp-img-responsive dspdp-block-center" src="<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/gifts/" . $gifts->gift_image; ?>" alt="<?php echo $gifts->gift_image;?>"/>
                                    </div>
                                    <div class="dsp-friend-image-holder pull-left">
                                        <a href="<?php
                                            if ($check_gender != 'C') {
                                                echo $root_link . get_username($gifts->member_id) . "/";
                                            } else {
                                                echo $root_link . get_username($gifts->member_id) . "/my_profile/";
                                            }
                                        ?>">
                                            <div class="image-box">
                                                <img class="dspdp-img-responsive dspdp-block-center  dspdp-spacer" title="<?php echo $users_details->user_login; ?>" alt="<?php echo $users_details->user_login; ?>" src="<?php echo display_members_photo($gifts->member_id, $imagepath); ?>" />
                                            </div>
                                        </a>
                                         <a href="<?php if ($check_gender != 'C') {
                                                echo $root_link . get_username($gifts->member_id) . "/";
                                            } else {
                                                echo $root_link . get_username($gifts->member_id) . "/my_profile/";
                                            }?>"><?php echo $users_details->user_login; ?></a>
                                     </div>
                                    <div class="show-comment">
                                        <img class="dspdp-img-responsive dspdp-block-center dsp-none" src="<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/gifts/" . $gifts->gift_image; ?>" alt="<?php echo  $gifts->gift_image;?>"/>
                                        
                                        <?php if($gifts->status_id == 1): ?>
                                            <span class="dsp-none">
                                                <br /><?php echo language_code('DSP_VIRTUAL_GIFT_APPROVED'); ?>                                             
                                            </span>
                                        <?php endif; ?>
                                        
                                    </div>
                            </div>
                        </div>
                    </div>
                    <?php                 
                        } 
                    } 
                    else
                    {
                    ?>
                    <div class="dspdp-col-sm-12 dsp-gift-container dsp-border-container">
                        <div class="dspdp-spacer dspdp-member-col">
                    <?php
                        echo language_code('DSP_NO_RECORD_FOUND'); 
                    ?>
                        </div>
                    </div>
                    <?php } ?> 
                </div>         
            </div>
        </div>
    </div>
</div>