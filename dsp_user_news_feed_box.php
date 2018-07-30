<?php
@include_once('../../../wp-config.php');
include_once(WP_DSP_ABSPATH . 'functions.php');
include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");
include_once(WP_DSP_ABSPATH . "/files/includes/functions.php");
global $wpdb;
$posts_table = $wpdb->prefix . POSTS;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");
$member_pageid = $member_page_title_ID->setting_value;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");
$member_page_id = $post_page_title_ID->ID;  // Print Site root link
$root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";  // Print Site root link
$user_id = $_REQUEST['user_id'];  // print session USER_ID
$imagepath = get_option('siteurl') . '/wp-content/';  // image Path
// ROOT PATH

$page = isset($_REQUEST['page']) ? esc_sql($_REQUEST['page']) : 1 ;
$limit = isset($_REQUEST['limit']) ? esc_sql($_REQUEST['limit']) : 3 ;
// How many adjacent pages should be shown on each side?
$adjacents = 2;

if ($page > 1)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;
$page_name = site_url() . "/members/home/news_feed/";
?>
<ul class="news-feed-page">
    <?php
    $dsp_news_feed_table = $wpdb->prefix . DSP_NEWS_FEED_TABLE;
    $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
    if (isset($_REQUEST['users']) && $_REQUEST['users'] == 'All') {
        $news_feed_users = $wpdb->get_results("(SELECT friend_uid as user_id FROM `$dsp_my_friends_table` WHERE user_id='$user_id') union (SELECT favourite_user_id as user_id FROM `$dsp_user_favourites_table` WHERE user_id='$user_id')");
        $news_feed_user = "";
        foreach ($news_feed_users as $users) {
            $news_feed_user.=$users->user_id . ',';
        }
        $news_feed_user = rtrim($news_feed_user, ',');
    } else {
        $news_feed_user = $_REQUEST['users'];
    }
    if(isset($news_feed_user) && !empty($news_feed_user)):
    $news_feeds = array();
    $news_feed = $wpdb->get_results("SELECT * FROM `$dsp_news_feed_table` where user_id in(" . $news_feed_user . ") ORDER BY `datetime` DESC LIMIT $start,$limit");
    foreach ($news_feed as $row) {
        $news_feeds[$row->feed_id]['feed_id'] = $row->feed_id;
        $username = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$row->user_id'");
        $gender = $wpdb->get_var("SELECT gender FROM $dsp_user_profiles WHERE user_id = '$row->user_id'");
        if ($row->feed_type == "status")
            $news_feeds[$row->feed_id]['feed_text'] = '<span>'. $username .'</span>' . ' ' . language_code('DSP_NEWS_FEED_STATUS');
        else if ($row->feed_type == "login")
            $news_feeds[$row->feed_id]['feed_text'] = '<span>'. $username .'</span>' . ' ' . language_code('DSP_NEWS_FEED_LOGIN');
        else if ($row->feed_type == "logout")
            $news_feeds[$row->feed_id]['feed_text'] = '<span>'. $username .'</span>' . ' ' . language_code('DSP_NEWS_FEED_LOGOUT');
        else if ($row->feed_type == "video")
            $news_feeds[$row->feed_id]['feed_text'] = '<span>'. $username .'</span>' . ' ' . language_code('DSP_NEWS_FEED_VIDEO');
        else if ($row->feed_type == "audio")
            $news_feeds[$row->feed_id]['feed_text'] = '<span>'. $username .'</span>' . ' ' . language_code('DSP_NEWS_FEED_AUDIO');
        else if ($row->feed_type == "gallery_photo")
            $news_feeds[$row->feed_id]['feed_text'] = '<span>'. $username .'</span>'. ' ' . language_code('DSP_NEWS_FEED_PHOTO');
        else if ($row->feed_type == "profile_photo")
            $news_feeds[$row->feed_id]['feed_text'] = '<span>'. $username .'</span>' . ' ' . language_code('DSP_NEWS_FEED_PROFILE_PHOTO');
        $news_feeds[$row->feed_id]['gender'] = $gender;
        $news_feeds[$row->feed_id]['user_id'] = $row->user_id;
        $news_feeds[$row->feed_id]['image'] = display_members_photo($row->user_id, $imagepath);
    }
    $total_results1 = $wpdb->get_var("SELECT COUNT(*) FROM `$dsp_news_feed_table` where user_id in(" . $news_feed_user . ") ");
    
    $pagination = "";
    if($total_results1 > $limit){
        ###### Pagination sections ###### 
        $pagination =  dsp_pagination($total_results1,$limit, $page, $adjacents,$page_name); 
        ###  End Paging code  ##########
    }
    foreach ($news_feeds as $news_row) {
        echo '<li><div class="dspdp-bordered-item"><a href="';
        if ($news_row['gender'] == 'C') {
            echo $root_link . get_username($news_row['user_id']) . "/my_profile/";
        } else {
            echo $root_link . get_username($news_row['user_id']) . "/";
        }
        echo '"><img style="width:50px; height:50px; vertical-align:middle; margin-right:5px;" src="' . $news_row['image'] . '"  alt="<?php echo get_username($news_row[\'user_id\']); ?>"/></a> ' . $news_row['feed_text'];
        echo '</div></li>';
        
    }
    echo '<li>' . $pagination . '</li>';
    else:
        echo '<li>'.language_code('DSP_NO_RECORD_FOUND').'</li>';
    endif; ?>
</ul>