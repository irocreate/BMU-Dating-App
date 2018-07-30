<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
?>
<?php
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
$dsp_messages_table = $wpdb->prefix . DSP_EMAILS_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_favourites_list_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$profile_filter = isset($_REQUEST['profile_filter']) ? $_REQUEST['profile_filter'] : get('profile_filter');
$gender_filter = isset($_REQUEST['gender_filter']) ? $_REQUEST['gender_filter'] : get('gender_filter');
$profile_filter = empty($profile_filter) ? 'all' : $profile_filter ;
?>

<div class="box-border">
    <div class="box-pedding">
        <div class="content-search">
            <form action="<?php echo $root_link . "extras/trending/"; ?>" method="post" class="dspdp-form-inline dspdp-text-center">
                <div class="dsp-row" align="center">
                    <div class="dsp-md-2 dsp-control-label">
                        <strong  class="dspdp-control-label"><?php echo language_code('DSP_PROFILE_TRENDING'); ?></strong><span class="dsp-none"> &nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </div>
                    <div class="dsp-sm-5">
                        <select class="dspdp-form-control" name="profile_filter" onchange="this.form.submit();">
                            <?php if (!isset($profile_filter)) { ?>
                                <option>&nbsp;</option>
                            <?php } ?>
                            <option value="all" <?php if (isset($profile_filter) && $profile_filter == 'all') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_OPTION_ALL'); ?></option>
                            <option value="Wink" <?php if (isset($profile_filter) && $profile_filter == 'Wink') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_OPTION_WINK'); ?></option>    
                            <option value="emails" <?php if (isset($profile_filter) && $profile_filter == 'emails') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_OPTION_EMAILS'); ?></option>
                            <option value="friend" <?php if (isset($profile_filter) && $profile_filter == 'friend') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_OPTION_FRIEND'); ?></option>
                            <option value="favorited" <?php if (isset($profile_filter) && $profile_filter == 'favorited') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_OPTION_FAVORITED'); ?></option>
                        </select>
                    </div>
                    <div class="dsp-sm-5">
                        <?php
                        if (isset($gender_filter) && $gender_filter == '') {
                            $user_ID = $current_user->ID;
                            $user_profiles_table = $wpdb->get_var("SELECT gender FROM $dsp_user_profiles_table where user_id='$user_ID' ");
                            $gender = $user_profiles_table;
                            ?>
                            <select class="dspdp-form-control" name="gender_filter" onchange="this.form.submit();">
                                <?php echo get_gender_list($gender); ?>

                            </select>
                            <?php
                        } else {
                            $gender = isset($gender_filter) ? $gender_filter : '';
                            ?>
                            <select  class="dspdp-form-control" name="gender_filter" onchange="this.form.submit();">
                                <?php echo get_gender_list($gender); ?>    
                            </select>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
if (isset($profile_filter)) {
    if (get('page'))
        $page = get('page');
    else
        $page = 1;

    // How many adjacent pages should be shown on each side?
    $adjacents = 2;
    $limit = $check_search_result->setting_value;
    if ($page)
        $start = ($page - 1) * $limit;    //first item to display on this page
    else
        $start = 0;
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
//$dsp_counter_hits_table = $wpdb->prefix .dsp_counter_hits;
    //$_REQUEST['profile_filter'];
    $gender = isset($_REQUEST['gender_filter']) ? $_REQUEST['gender_filter'] : get('gender_filter');
    if (isset($profile_filter) && $profile_filter == 'all') {
//$query=mysql_query("SELECT `receiver_id`,count(`receiver_id`) as count FROM wp_dsp_messages GROUP BY receiver_id ");
//while($row=mysql_fetch_array($query)) {
//echo $row['count'];
//echo $row['receiver_id'];
//}
        $strQuery = "SELECT p.user_id, p.country_id, p.state_id, p.city_id, p.gender, p.seeking, p.zipcode, p.age,  p.pic_status,p.about_me,p.status_id,p.reason_for_status,p.edited,p.last_update_date as count from $dsp_user_profiles_table p where p.gender='$gender'";

        /* $strQuery = "SELECT p.user_id, p.country_id, p.state_id, p.city_id, p.gender, p.seeking, p.zipcode, p.age,p.title, p.user_pic,  p.pic_status,p.about_me,p.status_id,p.reason_for_status,p.edited,p.last_update_date as count from $dsp_user_profiles_table p where p.gender='$gender'"; */
    } elseif (isset($profile_filter) && $profile_filter == 'Wink') {

        $strQuery = "SELECT winks.receiver_id ,count(winks.receiver_id) as count, p.user_id, p.country_id, p.state_id, p.city_id, p.gender, p.seeking, p.zipcode, p.age, p.pic_status,p.about_me,p.status_id,p.reason_for_status,p.edited,p.last_update_date FROM $dsp_member_winks_table winks, $dsp_user_profiles_table p where winks.receiver_id=p.user_id and p.gender='$gender' GROUP BY winks.receiver_id  ";

        /* $strQuery = "SELECT winks.receiver_id ,count(winks.receiver_id) as count, p.user_id, p.country_id, p.state_id, p.city_id, p.gender, p.seeking, p.zipcode, p.age,p.title, p.user_pic,  p.pic_status,p.about_me,p.status_id,p.reason_for_status,p.edited,p.last_update_date FROM $dsp_member_winks_table winks, $dsp_user_profiles_table p where winks.receiver_id=p.user_id and p.gender='$gender' GROUP BY winks.receiver_id  "; */
    } elseif (isset($profile_filter) && $profile_filter == 'emails') {
        $strQuery = "SELECT msg.receiver_id,count(msg.receiver_id) as count, p.user_id, p.country_id, p.state_id, p.city_id, p.gender, p.seeking, p.zipcode, p.age,  p.pic_status,p.about_me,p.status_id,p.reason_for_status,p.edited,p.last_update_date FROM $dsp_messages_table msg, $dsp_user_profiles_table p where msg.receiver_id=p.user_id and p.gender='$gender' GROUP BY msg.receiver_id ";
    } elseif (isset($profile_filter) && $profile_filter == 'friend') {
        $strQuery = "SELECT friend.friend_uid,count(friend.friend_uid) as count,  p.user_id, p.country_id, p.state_id, p.city_id, p.gender, p.seeking, p.zipcode, p.age, p.pic_status,p.about_me,p.status_id,p.reason_for_status,p.edited,p.last_update_date FROM $dsp_my_friends_table friend, $dsp_user_profiles_table p where friend.friend_uid=p.user_id and friend.approved_status= 'Y' and p.gender='$gender' GROUP BY friend.friend_uid ";
    } elseif (isset($profile_filter) && $profile_filter == 'favorited') {
        $strQuery = "SELECT favourites.favourite_user_id ,count(favourites.favourite_user_id) as count, p.user_id, p.country_id, p.state_id, p.city_id, p.gender, p.seeking, p.zipcode, p.age, p.pic_status,p.about_me,p.status_id,p.reason_for_status,p.edited,p.last_update_date FROM $dsp_favourites_list_table  favourites, $dsp_user_profiles_table p where favourites.favourite_user_id=p.user_id and p.gender='$gender' GROUP BY favourites.favourite_user_id";
    }
    $intRecordsPerPage = 1;
    $intStartLimit = get('p'); # page selected 1,2,3,4...
    if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
        $intStartLimit = 1; //default
    }
    $intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;
    @$strQuery = $strQuery . " ORDER BY count desc";
    $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
    //echo $wpdb->last_query;die;
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
    if (isset($_REQUEST['profile_filter'])) {
        $page_name = $root_link . "extras/trending/gender_filter/$gender/profile_filter/$profile_filter/";
    }
    $total_results1 = $user_count;
// Calculate total number of pages. Round up using ceil()
    //$total_pages1 = ceil($total_results1 / $max_results1); 
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

// ------------------------------------------------End Paging code------------------------------------------------------ // 
    $intTotalRecordsEffected = $user_count;

    if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
        //print "Total records found: " . $intTotalRecordsEffected;
    }
// if ($intTotalRecordsEffected != '0')	
    //echo $strQuery ." LIMIT " . $from1 . "," . $max_results1;
    $search_members = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");
//echo $strQuery ." LIMIT " . $from1 . "," . $max_results1; 
    ?>

    <div class="box-border dsp-member-container">
        <div class="box-pedding dspdp-row row">
            <?php 
            foreach ($search_members as $member1) {
            
                if ($member1->user_id != 0 && dsp_checkProfileCompleted($member1->user_id)) {
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
                            $s_age = GetAge($member->age);
                            $s_make_private = $member->make_private;
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
                            <div class="dspdp-col-sm-4 dsp-sm-3 dsp-user-block">
                                <div class="box-search-result  image-container">
                                    <div class="dsp-user-info-container halfforth clearfix dsp-block" style="display:none">

                                        <?php if ($check_my_friend_module->setting_status == 'Y') { ?>
                                            <?php
                                            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                                    ?>
                                                    <a href="<?php echo $root_link . "add_friend/user_id/" . $user_id . "/frnd_userid/" . $s_user_id . "/"; ?>" title="<?php echo language_code('DSP_ADD_TO_FRIENDS'); ?>"><i class="fa fa-user"></i></a>
                                                <?php } else { ?>
                                                    <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"><i class="fa fa-user"></i></a>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><i class="fa fa-user"></i></a>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php if (is_user_logged_in()) { ?>
                                            <a href="<?php echo $root_link . "add_favorites/user_id/" . $user_id . "/fav_userid/" . $s_user_id . "/"; ?>" title="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>"><i class="fa fa-heart"></i></a>
                                        <?php } else { ?>
                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><i class="fa fa-heart"></i></a>
                                        <?php } ?>

                                        <?php
                                        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                            if (isset($check_my_friends_list) && $check_my_friends_list > 0) {
                                                ?>
                                                <a <?php
                                                     $result = check_contact_permissions($s_user_id);
                                                    if (!$result) {
                                                    ?> href="javascript:void(0);" onclick="javascript:show_contact_message();" <?php } else { ?> href="<?php echo $root_link . "email/compose/frnd_id/" . $s_user_id . "/Act/send_msg/"; ?>"  <?php } ?> title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>"><i class="fa fa-envelope-o"></i></a>
                                            <?php } else { ?>
                                                <a <?php
                                                $result = check_contact_permissions($s_user_id);
                                                if (!$result) {
                                                    ?> href="javascript:void(0);" onclick="javascript:show_contact_message();" <?php } else { ?> href="<?php echo $root_link . "email/compose/receive_id/" . $s_user_id . "/"; ?>"  <?php } ?> title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>"><i class="fa fa-envelope-o"></i></a>
                                            <?php } //if($check_my_friends_list>0)     ?>
                                        <?php } else { ?>
                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><i class="fa fa-envelope-o"></i></a>
                                        <?php } ?>

                                        <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not   ?>
                                            <?php
                                            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                                    ?>
                                                    <a href='<?php echo $root_link . "view/send_wink_msg/receiver_id/" . $s_user_id . "/"; ?>' title="<?php echo language_code('DSP_SEND_WINK'); ?>"><i class="fa fa-smile-o"></i></a>
                                                <?php } else { ?>
                                                    <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"><i class="fa fa-smile-o"></i></a>
                                                <?php } ?>
                                            <?php } else { ?>

                                                <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"> <i class="fa fa-smile-o"></i></a>
                                            <?php } ?>  

                                        </div>
                                        <span class="online dspdp-online-status dsp-block dsp-selected" style="display:none"><?php $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $tbl_name WHERE user_id=$s_user_id"); ?>
                                            <?php
                                            //echo $fav_icon_image_path;
                                            if ($check_online_user > 0)
                                                echo '<span class="dspdp-status-on" ' . language_code('DSP_CHAT_ONLINE') . '></span>';
                                            else
                                                echo '<span class="dspdp-status-off" ' . language_code('DSP_CHAT_OFFLINE') . '></span>';
                                            ?>
                                        </span>
                                        <div class="img-box circle-image">
                                            <span class="online dspdp-online-status dsp-none"><?php $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $tbl_name WHERE user_id=$s_user_id"); ?>
                                                <?php
                                                //echo $fav_icon_image_path;
                                                if ($check_online_user > 0)
                                                    echo '<span class="dspdp-status-on" ' . language_code('DSP_CHAT_ONLINE') . '></span>';
                                                else
                                                    echo '<span class="dspdp-status-off" ' . language_code('DSP_CHAT_OFFLINE') . '></span>';
                                                ?>
                                            </span>
                                            <?php
                                            if ($check_couples_mode->setting_status == 'Y') {
                                                if ($s_gender == 'C') {
                                                    ?>

                                                    <?php if ($s_make_private == 'Y') { ?>

                                                        <?php if ($current_user->ID != $s_user_id) { ?>

                                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"    border="0" class="img-big" alt="Private Photo"/>
                                                                </a>                
                                                            <?php } else {
                                                                ?>
                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >				
                                                                    <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"      border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>                
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                                                <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"   border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/>
                                                            </a>
                                                        <?php } ?>

                                                    <?php } else { ?>

                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                                            <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"   border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                                        </a>
                                                    <?php } ?>

                                                <?php } else { ?>

                                                    <?php if ($s_make_private == 'Y') { ?>

                                                        <?php if ($current_user->ID != $s_user_id) { ?>

                                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"    border="0" class="img-big" alt="Private Photo" />
                                                                </a>                
                                                            <?php } else {
                                                                ?>
                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >				
                                                                    <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"       border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" /></a>                
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                                <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"   border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                                            </a>
                                                        <?php } ?>
                                                    <?php } else { ?>

                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                            <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"   border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
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
                                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"    border="0" class="img-big" alt="Private Photo"/>
                                                            </a>                
                                                        <?php } else {
                                                            ?>
                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >				
                                                                <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"      border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" /></a>                
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                            <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"   border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                                        </a>
                                                    <?php } ?>

                                                <?php } else { ?>

                                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                        <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"   border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                                    </a>
                                                <?php } ?>

                                            <?php } ?>

                                        </div>
                                        <div class="user-status dspdp-h5 dspdp-username">

                                            <span class="user-name dsp-username"><strong>

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
                                                        </a></strong></span>
                                        </div>
                                        <div class="user-details dspdp-spacer dspdp-user-details dsp-user-details">
                                            <?php echo $s_age ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?> <?php echo get_gender($s_gender); ?> <?php echo language_code('DSP_FROM_TEXT'); ?> <br /><?php if (@$city_name->name != "") echo @$city_name->name . ','; ?> <?php if (@$state_name->name != "") echo @$state_name->name . ','; ?> <?php echo @$country_name->name; ?>
                                        </div>

                                    
                                        <div class="user-links dsp-none">
                                            <ul class="dspdp-row">
                                                <?php if ($check_my_friend_module->setting_status == 'Y') { // Check My friend module Activated or not ?>
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
                                                                            ?> href="javascript:void(0);" onclick="javascript:show_contact_message();" <?php } else { ?> href="<?php echo $root_link . "email/compose/frnd_id/" . $s_user_id . "/Act/send_msg/"; ?>"  <?php } ?> title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                                            <span class="fa fa-envelope-o"></span></a>
                                                <?php } else { ?>
                                                    <a <?php
                                                $result = check_contact_permissions($s_user_id);
                                                if (!$result) {
                                                                            ?> href="javascript:void(0);" onclick="javascript:show_contact_message();" <?php } else { ?> href="<?php echo $root_link . "email/compose/receive_id/" . $s_user_id . "/"; ?>"  <?php } ?> title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                                            <span class="fa fa-envelope-o"></span></a>
                                                        <?php } //if($check_my_friends_list>0)     ?>
                                                    <?php } else { ?>
                                                        <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login">  <span class="fa fa-envelope-o"></span></a>
                                                    <?php } ?>
                                                </div>
                                                </li>
                                                <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not   ?>
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
            <?php }  } } ?>
                </div>
                <div class="row-paging"> 
                    <div style="float:left; width:100%;">
                        <?php
                        // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                        echo $pagination
        // -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                        ?>
                    </div>  
                </div>
            <?php } ?>
        </div>
