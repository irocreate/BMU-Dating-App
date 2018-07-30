<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author -  www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
if (get('page') != "")
    $page = get('page');
else
    $page = 1;

// How many adjacent pages should be shown on each side?
$adjacents = 2;
$limit = isset($check_search_result) && !empty($check_search_result->setting_value) ? $check_search_result->setting_value : 6;
if ($page)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;
//$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE status_id=1 AND user_id = '$member_id'");
// ------------------------------------------------End Paging code------------------------------------------------------ //

//Gets MATCH according to the OPTION SELECTED (from backend) IN "DSP Admin->Settings->Matches->Default Match:"
if( !isset ($_REQUEST['gender']) ){
    //$gender = "all"; //M-man, F-woman, C-couples, all-all.
    
    $check_default_match = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'default_match'");
//    echo " frm-db: ".$check_default_match->setting_value;//die; //ok
    
    if ( $check_default_match->setting_value == "man" )
        $gender = "M";
    else if ( $check_default_match->setting_value == "woman" )
        $gender = "F";
    else if ( $check_default_match->setting_value == "couples" )
        $gender = "C";
    else if ( $check_default_match->setting_value == "all" )
        $gender = "all";
}

if (isset($_REQUEST['submit']) || get('submit') != "") {
    $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : get('gender');
    $age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : get('age_from');
    $age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : get('age_to');
    $page_name = $root_link . "home/my_matches/gender/$gender/age_from/$age_from/age_to/$age_to/submit/Filter/";
} else {
    $age_from = '';
    $age_to = '';
}

$my_matches = isset($_REQUEST['message_template']) ? $_REQUEST['message_template'] : '';
$active_question_id = $wpdb->get_results("SELECT profile_setup_id FROM $dsp_profile_setup_table WHERE display_status='Y'");
foreach ($active_question_id as $question_id) {
    $active_question_ids[] = $question_id->profile_setup_id;
}
if ($active_question_ids != "") {
    $active_question_ids1 = implode(",", $active_question_ids);
}
$matches_option = $wpdb->get_results("SELECT profile_question_option_id FROM $dsp_question_details WHERE profile_question_id IN ($active_question_ids1) and user_id='$user_id'");
foreach ($matches_option as $match_opt_id) {
    $matches_option_id1[] = $match_opt_id->profile_question_option_id;
}
if (isset($matches_option_id1) && $matches_option_id1 != "") {
    $matches_option_id = implode(",", $matches_option_id1);
}
$member_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles WHERE user_id='$user_id'");
//$member_gender->gender;

if (isset($matches_option_id)) {
    $count_my_matches = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_question_details A INNER JOIN $dsp_user_profiles B ON(A.user_id=B.user_id) WHERE profile_question_option_id IN ($matches_option_id) AND B.gender='$member_gender->gender'");
} else {
    $count_my_matches = 0;
}
?>

<?php
if ($count_my_matches > 0) {
    ?>
    <div class="box-border">
        <div class="box-pedding  dspdp-row">
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

                                        <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>"  alt=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>" />       

                                    <?php } ?></a>
                                </li>
                                <li><a href="<?php echo $root_link . "extras/trending/"; ?>"><img src="<?php echo $fav_icon_image_path ?>profile.jpg" title="<?php echo language_code('DSP_PROFILE_TRENDINGS') ?>"  alt="<?php echo language_code('DSP_PROFILE_TRENDINGS') ?>"/></a></li>
                                <li><a href="<?php echo $root_link . "extras/viewed_me/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_viewedme.jpg" title="<?php echo language_code('DSP_WHO_VIEWED_ME') ?>" alt="<?php echo language_code('DSP_WHO_VIEWED_ME') ?>" /></a></li>
                                <li><a href="<?php echo $root_link . "extras/i_viewed/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_iviewed.jpg" title="<?php echo language_code('DSP_WHO_I_VIEWED') ?>"  alt="<?php echo language_code('DSP_WHO_I_VIEWED') ?>"/></a> </li>
                                <li><a href="<?php echo $root_link . "online_members/show/all/"; ?>"><img src="<?php echo $fav_icon_image_path ?>whos_online.jpg" title="<?php echo language_code('DSP_ONLINE_MEMBER') ?>&nbsp;(<?php echo isset($count_online_member) ? $count_online_member : ''; ?>)" alt="<?php echo language_code('DSP_ONLINE_MEMBER') ?>"/></a></li>
                                <li><a href="<?php echo $root_link . "email/inbox/"; ?>"><img src="<?php echo $fav_icon_image_path ?>message.jpg" title="<?php echo language_code('DSP_NEW_EMAIL'); ?>&nbsp;(<?php echo $count_inbox_messages ?>)"  border="0" alt="<?php echo language_code('DSP_NEW_EMAIL') ?>"/></a></li>
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
                <div class="dsp-md-9">                    
                    
                        <h3 class="heading-feed margin-btm-2"><?php echo language_code('DSP_MY_MATCHES') ?></h3>
                        
                        <div class="content-search">
                            <div class="dsp-row dspdp-clearfix">
                                <div style="width:100%; margin-bottom: 10px; text-align:center; float:left; ">
                                    <form action="" method="post"  class="dspdp-form-inline">
                                        <div class="dsp-md-4 dspdp-inline">
                                            <span class="dsp-label"><?php echo language_code('DSP_GENDER') ?></span>
                                            <span>
                                                <select name="gender" class="dspdp-form-control">
                                                    <option value="all" <?php if ($gender == 'all') { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> ><?php echo language_code('DSP_ALL') ?></option>
                                                    <?php echo get_gender_list($gender); ?>
                                                </select>
                                            </span>&nbsp;
                                        </div>
                                        <div class="dsp-md-3 dspdp-inline">
                                            <span class="dsp-label"><?php echo language_code('DSP_AGE') ?></span>&nbsp;
                                            <span>
                                                <select name="age_from" class="dspdp-form-control">
                                                    <?php for ($i = '18'; $i <= '90'; $i++) { ?>
                                                        <option value="<?php echo $i ?>" <?php echo  $i == $age_from ? 'selected="selected"': '';?>><?php echo $i ?></option>
                                                    <?php } ?>

                                                </select>
                                            </span>
                                        </div>
                                        <div class="dsp-md-3 dspdp-inline">
                                        <span style="margin:0px 5px;" class="dsp-label"><?php echo language_code('DSP_TO') ?></span>
                                        <span>
                                            <select  name="age_to" class="dspdp-form-control  dspdp-xs-form-group">
                                                <?php for ($j = '90'; $j >= '18'; $j--) { ?>
                                                    <option value="<?php echo $j ?>" <?php  echo $j == $age_to ? 'selected="selected"': '';?>><?php echo $j ?></option>
                                                <?php } ?>
                                            </select>
                                        </span>
                                        </div>
                                        <div class="dsp-md-2 dspdp-inline">
                                            <span style="width:65px; text-align:right;"><input class="dspdp-btn dspdp-btn-default" name="submit" type="submit" value="Filter" /></span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
						<div class="dspdp-seprator"></div>
                    <?php
                    $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
                    $matches_opt_id = explode(",", $matches_option_id);
                    $count = count($matches_opt_id);
                    $match_query = "SELECT B.user_id,(year(CURDATE())-year(B.age)) age,A.profile_question_option_id,B.gender FROM $dsp_question_details A INNER JOIN $dsp_user_profiles B ON(A.user_id=B.user_id) WHERE profile_question_option_id IN ($matches_option_id) and A.user_id<>$user_id";
                    if (isset($age_from) && $age_from >= 18) {
                        $match_query .= " AND ((year(CURDATE())-year(B.age)) BETWEEN $age_from AND $age_to)  AND ";
                    } else {
                        $age_to = 90;
                        $age_from = 18;
                        $match_query .= " AND ((year(CURDATE())-year(B.age)) BETWEEN $age_from AND $age_to)  AND ";
                    }
                    if (!isset($gender)) {
                        if ($member_gender->gender == 'M')
                            $match_query .= " gender='F' ";
                        else if ($member_gender->gender == 'F')
                            $match_query .= " gender='M' ";
                        else if ($member_gender->gender == 'C')
                            $match_query .= " gender='C' ";
                    }
                    else {
                        if ($gender == 'M') {
                            $match_query .= " gender='M' ";
                        } else if ($gender == 'F') {
                            $match_query .= " gender='F' ";
                        } else if ($gender == 'C') {
                            $match_query .= " gender='C' ";
                        } else {
                            if ($check_couples_mode->setting_status == 'Y') {
                                $match_query .= " gender IN('M','F','C') ";
                            } else {
                                $match_query .= " gender IN('M','F') ";
                            }
                        }
                    }
                    $match_query.= "GROUP BY A.user_id having count(*)=" . $count;
                    $wpdb->get_results($match_query);
                    $user_count = $wpdb->num_rows;
                    //$user_count = mysql_num_rows(mysql_query($match_query));

                    $total_results1 = $user_count;
                    if($total_results1 > 0){
                    // ------------------------------------------------start Paging code------------------------------------------------------ 			
                    if ($page == 0)
                        $page = 1;     //if no page var is given, default to 1.
                    $prev = $page - 1;
                    $next = $page + 1;
                    $lastpage = ceil($total_results1 / $limit);
                    
                    ;  //lastpage is = total pages / items per page, rounded up.
                    $lpm1 = $lastpage - 1;
                    /*
                      Now we apply our rules and draw the pagination object.
                      We're actually saving the code to a variable in case we want to draw it more than once.
                     */
                    $pagination = "";
                    $page_name = isset($page_name)?$page_name:'';
                    if ($lastpage > 1) {
                        $pagination .= "<div class='wpse_pagination'>";
                        //previous button
                        if ($page > 1)
                            $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "page/$prev/\">".language_code('DSP_PREVIOUS')."</a></div>";
                        else
                            $pagination.= "<span  class='disabled'>".language_code('DSP_PREVIOUS')."</span>";

                        //pages	
                        if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
                            for ($counter = 1; $counter <= $lastpage; $counter++) {
                                if ($counter == $page)
                                    $pagination.= "<span class='current'>$counter</span>";
                                else
                                    $pagination.= "<div><a href=\"" . $page_name . "page/$counter/\">$counter</a></div>";
                            }
                        }
                        elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
                            //close to beginning; only hide later pages
                            if ($page < 1 + ($adjacents * 2)) {
                                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                                    if ($counter == $page)
                                        $pagination.= "<span class='current'>$counter</span>";
                                    else
                                        $pagination.= "<div><a href=\"" . $page_name . "page/$counter/\">$counter</a></div>";
                                }
                                $pagination.= "<span>...</span>";
                                $pagination.= "<div><a href=\"" . $page_name . "page/$lpm1/\">$lpm1</a></div>";
                                $pagination.= "<div><a href=\"" . $page_name . "page/$lastpage/\">$lastpage</a></div>";
                            }
                            //in middle; hide some front and some back
                            elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                                $pagination.= "<div><a href=\"" . $page_name . "page/1/\">1</a></div>";
                                $pagination.= "<div><a href=\"" . $page_name . "page/2/\">2</a></div>";
                                $pagination.= "<span>...</span>";
                                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                                    if ($counter == $page)
                                        $pagination.= "<div class='current'>$counter</div>";
                                    else
                                        $pagination.= "<div><a href=\"" . $page_name . "page/$counter/\">$counter</a></div>";
                                }
                                $pagination.= "<span>...</span>";
                                $pagination.= "<div><a href=\"" . $page_name . "page/$lpm1/\">$lpm1</a></div>";
                                $pagination.= "<div><a href=\"" . $page_name . "page/$lastpage/\">$lastpage</a></div>";
                            }
                            //close to end; only hide early pages
                            else {
                                $pagination.= "<div><a href=\"" . $page_name . "page/1/\">1</a></div>";
                                $pagination.= "<div><a href=\"" . $page_name . "page/2/\">2</a></div>";
                                $pagination.= "<span>...</span>";
                                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                                    if ($counter == $page)
                                        $pagination.= "<span class='current'>$counter</span>";
                                    else
                                        $pagination.= "<div><a href=\"" . $page_name . "page/$counter/\">$counter</a></div>";
                                }
                            }
                        }

                        //next button
                        if ($page < $counter - 1)
                            $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "page/$next/\">".language_code('DSP_NEXT')."</a></div>";
                        else
                            $pagination.= "<span class='disabled'>".language_code('DSP_NEXT')."</span>";
                        $pagination.= "</div>\n";
                    }
                  }else{
                    $pagination = '';
                  }  
                ?>

                    <div class="search-match">
                        <?php
    // ------------------------------------------------End Paging code------------------------------------------------------ // 

                            $match_query .= " LIMIT  $start, $limit";

                            $match_member = $wpdb->get_results($match_query);

                            foreach ($match_member as $member1) {
                                if ($check_couples_mode->setting_status == 'Y') {
                                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'");
                                } else {
                                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member1->user_id'");
                                }
                                $s_user_id = $member->user_id;
                                $s_country_id = $member->country_id;
                                $s_gender = $member->gender;
                                $s_seeking = $member->seeking;
                                $s_state_id = $member->state_id;
                                $s_city_id = $member->city_id;
                                $s_age = $member1->age;
                                $s_make_private = $member->make_private;
                                $stealth_mode = isset($member->stealth_mode) ? $member->stealth_mode : '';
            //$s_user_pic = $member->user_pic;
                                $displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$s_user_id'");
                                $country_name = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$s_country_id");
                                $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$s_state_id");
                                $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$s_city_id");
                                $favt_mem = array();
                                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");
                                foreach ($private_mem as $private) {
                                    $favt_mem[] = $private->favourite_user_id;
                                }
                                ?>
                              <div class="dspdp-col-sm-4 dsp-sm-3">  <div class="box-search-result image-container">
                                    <div class="img-box  dspdp-spacer circle-image">
                                        <span class="online  dspdp-online-status">
                                                 <?php
                                                 $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_online_user_table WHERE user_id=$s_user_id");
                                                 $check_online_user = ($stealth_mode == "Y") ? '0' : $check_online_user;
                                                 ?>
                                            <?php
                            //echo $fav_icon_image_path;
                            if ($check_online_user > 0)
                                echo '<span class="dspdp-status-on" '.language_code('DSP_CHAT_ONLINE').'></span>';
                            else
                                echo '<span class="dspdp-status-off" '.language_code('DSP_CHAT_OFFLINE').'></span>';
                            ?></span>
                                            <?php
                                            if ($check_couples_mode->setting_status == 'Y') {
                                                if ($s_gender == 'C') {
                                                    ?>

                                                <?php if ($s_make_private == 'Y') { ?>

                                                    <?php if ($current_user->ID != $s_user_id) { ?>

                                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"   border="0" class="img-big" alt="Private Photo" />
                                                            </a>                
                                                        <?php } else {
                                                            ?>
                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >				
                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"     border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>                
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                                        </a>
                                                    <?php } ?>

                                                <?php } else { ?>

                                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                                    </a>
                                                <?php } ?>

                                            <?php } else { ?>

                                                <?php if ($s_make_private == 'Y') { ?>

                                                    <?php if ($current_user->ID != $s_user_id) { ?>

                                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"   border="0" class="img-big" alt="Private Photo" />
                                                            </a>                
                                                        <?php } else {
                                                            ?>
                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >				
                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"      border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>                
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                                        </a>
                                                    <?php } ?>
                                                <?php } else { ?>

                                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                                    </a>
                                                <?php } ?>
                                                <?php
                                            }
                                        } else {
                                            ?> 

                                            <?php if ($s_make_private == 'Y') { ?>
                                                <?php if ($current_user->ID != $s_user_id) { ?>

                                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"   border="0" class="img-big" alt="Private Photo" />
                                                        </a>                
                                                    <?php } else {
                                                        ?>
                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >				
                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"     border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" /></a>                
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                                    </a>
                                                <?php } ?>

                                            <?php } else { ?>

                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                                </a>
                                            <?php } ?>

                                        <?php } ?>

                                    </div>
                                    <div class="user-status  dspdp-h5 dspdp-username dsp-username">

                                        <span class="user-name">

                                            <?php
                                            if ($check_couples_mode->setting_status == 'Y') {
                                                if ($s_gender == 'C') {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                                        <?php
                                                        if (strlen($displayed_member_name->display_name) > 15)
                                                            echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                        else
                                                            echo $displayed_member_name->display_name;
                                                        ?>                
                                                    <?php } else { ?>
                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                            <?php
                                                            if (strlen($displayed_member_name->display_name) > 15)
                                                                echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                            else
                                                                echo $displayed_member_name->display_name;
                                                            ?>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?> 
                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                            <?php
                                                            if (strlen($displayed_member_name->display_name) > 15)
                                                                echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                            else
                                                                echo $displayed_member_name->display_name;
                                                            ?>
                                                        <?php } ?>
                                                    </a>
                                                </a></span>
                                    </div>
                                    <div class="user-details  dspdp-spacer dspdp-user-details dsp-user-details">
                                        <?php echo $s_age ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?> <?php echo get_gender($s_gender); ?> <?php echo language_code('DSP_FROM_TEXT'); ?> <br /><?php if (@$city_name->name != "") echo @$city_name->name . ','; ?> <?php if (@$state_name->name != "") echo @$state_name->name . ','; ?> <?php echo @$country_name->name; ?>
                                    </div>
                                    <div class="user-links">
                                        <ul class="dspdp-row">
                                            <?php if ($check_my_friend_module->setting_status == 'Y') { // Check My friend module Activated or not  ?>
                                                <li class="dspdp-col-xs-3">
                                                    <div class="dsp_fav_link_border">
                                                        <?php
                                                        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                            if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                                                ?>
                                                                <a href="<?php echo $root_link . "add_friend/user_id/" . $user_id . "/frnd_userid/" . $s_user_id . "/"; ?>" title="<?php echo language_code('DSP_ADD_TO_FRIENDS'); ?>">
                                                                    <span class="fa fa-user"></span></a>
                                                            <?php } else { ?>
                                                                <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"><span class="fa fa-user"></span></a> 
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><span class="fa fa-user"></span></a>
                                                        <?php } ?>
                                                    </div>
                                                </li>
                                            <?php } // END My friends module Activation check condition ?>
                                            <li class="dspdp-col-xs-3">
                                                <div class="dsp_fav_link_border">
                                                    <?php
                                                    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                        ?>
                                                        <a href="<?php echo $root_link . "add_favorites/user_id/" . $user_id . "/fav_userid/" . $s_user_id . "/"; ?>" title="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>"><span class="fa fa-heart"></span></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><span class="fa fa-heart"></span></a>
                                                    <?php } ?>
                                                </div>
                                            </li>
                                            <li class="dspdp-col-xs-3">
                                                <div class="dsp_fav_link_border" >
                                                    <?php
                                                    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                        if (isset($check_my_friends_list) && $check_my_friends_list > 0) {
                                                            ?>
                                                            <a <?php
                                                            $result = check_contact_permissions($s_user_id);
                                                            if (!$result) {
                                                                ?> href="javascript:void(0);" onclick="javascript:show_contact_message();" <?php } else { ?>   href="<?php echo $root_link . "email/compose/frnd_id/" . $s_user_id . "/Act/send_msg/"; ?>"  <?php } ?>  title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                                <span class="fa fa-envelope-o"></span></a>
                                                        <?php } else { ?>
                                                            <a <?php
                                                            $result = check_contact_permissions($s_user_id);
                                                            if (!$result) {
                                                                ?> href="javascript:void(0);" onclick="javascript:show_contact_message();" <?php } else { ?>   href="<?php echo $root_link . "email/compose/receive_id/" . $s_user_id . "/"; ?>"  <?php } ?> title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                                <span class="fa fa-envelope-o"></span></a>
                                                        <?php } //if($check_my_friends_list>0)    ?>
                                                    <?php } else { ?>
                                                        <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"> <span class="fa fa-envelope-o"></span></a>
                                                    <?php } ?>
                                                </div>
                                            </li>
                                            <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not  ?>
                                                <li class="dspdp-col-xs-3">
                                                    <div class="dsp_fav_link_border">
                                                        <?php
                                                        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                            if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                                                ?>
                                                                <a href='<?php echo $root_link . "view/send_wink_msg/receiver_id/" . $s_user_id . "/"; ?>' title="<?php echo language_code('DSP_SEND_WINK'); ?>">
                                                                   <span class="fa fa-smile-o"></span></a>
                                                            <?php } else { ?>
                                                                <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"><span class="fa fa-smile-o"></span></a>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login">  <span class="fa fa-smile-o"></span></a>
                                                        <?php } ?>
                                                    </div>
                                                </li>
                                            <?php } // END My friends module Activation check condition  ?> 
                                        </ul>
                                    </div>
                                </div></div>
                            <?php }
                            ?>

                        </div>
                    </div>
                 </div>
                </div>
            </div>

    <div class="row-paging"> 
        <div style="float:left; width:100%;">
            <?php
            // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
            echo $pagination;
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
            ?>
        </div>  
    </div>
<?php } else { ?>
    <div class="box-page">
        <div class="page-not-found">
            <strong><?php echo language_code('DSP_NO_MATCHES_FOUND_MSG'); ?></strong>
        </div>
    </div>
<?php } ?>
