<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ----------------------------------------------- Start Paging code------------------------------------------------------ //  

// ------------------------------------------------End Paging code------------------------------------------------------ // 
// ROOT PATH

//if (get('page')) $page = get('page'); else $page = 1;
if (get('page')) $page = get('page'); else $page = 1;
$limit = !empty($check_search_result->setting_value)? $check_search_result->setting_value: 3;
?>
<div class="box-border dsp-news-feed-container">
    <div class="box-pedding clearfix">
        <div class="heading-submenu dsp-none"><strong><?php echo language_code('DSP_NEWS_FEED_TITLE'); ?></strong></div>
        <div class="dsp-row">
            <div class="dsp-md-3">
                <div class="user-friends dsp-user-friends">
                    <h3 class="heading-feed"><?php echo language_code('DSP_NEWS_FEED_FRIENDS'); ?></h3>
                    <ul class="friends-list dspdp-nav dspdp-nav-tabs">
                        <li class="all"><a id="update_news_feed_box" href="All"><?php echo language_code('DSP_OPTION_ALL'); ?></a></li>
                        <?php
                        $feed_users = $wpdb->get_results("(SELECT friend_uid as userid FROM `$dsp_my_friends_table` WHERE user_id='$user_id') union (SELECT favourite_user_id as userid FROM `$dsp_user_favourites_table` WHERE user_id='$user_id')");
                        foreach ($feed_users as $users) {
                            $username = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$users->userid'");
                            ?>
                            <li><a id="update_news_feed_box" href="<?php echo $users->userid; ?>"><?php echo $username; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="box-profile-link dsp-block" style="display:none">   
                    <div class="clr"></div>
                    <ul class="text-left dsp-user-spec clearfix ">
                 
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

                        <?php if ($check_match_alert_mode->setting_status == 'Y') { ?>
                            <li <?php if (($profile_pageurl == "match_alert")) { ?>class="dsp_active_link"  <?php } ?>>
                                <a href="<?php echo $root_link . "home/match_alert/"; ?>"><i
                                        class="fa fa-bell"></i><?php echo language_code('DSP_SUBMENU_SETTINGS_MATCH_ALERTS'); ?>
                                </a>
                            </li>
                        <?php } ?>

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
            <div class="dsp-md-9">
                <div class="news-box">
                    <h3 class="heading-feed margin-btm-2"><?php echo language_code('DSP_NEWS_FEED_NEWS'); ?></h3>
                    <div id="news_feed_box"><?php
                        $news_feed_div = $pluginpath . "dsp_user_news_feed_box.php?user_id=$user_id&users=All";
                        $news_feed_div .= !empty($page) ?   '&page=' . $page : ' ';
                        $news_feed_div .= !empty($limit) ?   '&limit=' . $limit : ' ';
                        $content_news = wp_remote_get($news_feed_div);
                        if($content_news['response']['message'] == 'OK'){
                            $content_news = array_key_exists('body', $content_news) ? $content_news['body'] : '';
                            echo $content_news;
                        }
                        ?></div>
                </div>
            </div>
        </div>
    </div>
</div>