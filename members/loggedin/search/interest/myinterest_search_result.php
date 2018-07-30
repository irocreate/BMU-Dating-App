<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
if (get('page'))
    $page = get('page');
else
    $page = 1;

// How many adjacent pages should be shown on each side?
$adjacents = 2;
$limit = 8;
if ($page)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;
$gender = get('gender');
$age_from = get('age_from');
$age_to = get('age_to');
$search_type = get('search_type');
$my_int = urldecode(get('my_int'));

$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$user_ID = $current_user->ID;
$strQuery1 = "SELECT user_id, my_interest FROM $dsp_user_profiles_table where my_interest like '%$my_int%' ";

if ($age_from >= 18) {
    $strQuery1 .= " and ((year(CURDATE())-year(age)) > '" . $age_from . "') AND ((year(CURDATE())-year(age)) < '" . $age_to . "') ";
}

if ($gender == 'M') {
    $strQuery1 .= " AND gender='M'";
} else if ($gender == 'F') {
    $strQuery1 .= " AND gender='F'   ";
} else if ($gender == 'C') {
    $strQuery1 .= " AND gender='C'  ";
} else if ($gender == 'all') {
    $strQuery1 .= " AND gender IN('M','F','C') ";
}
$user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery1) AS total");
$intRecordsPerPage = 10;
$intStartLimit = get('p'); # page selected 1,2,3,4...
if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
    $intStartLimit = 1; //default
}
$intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;


$page_name = $root_link . "search/myinterest_search_result/search_type/my_interest"; 
$page_name .=  (isset($my_int) && !empty($my_int))  ? "/my_int/$my_int/" : "" ;
$page_name .=  (isset($gender) && !empty($gender))  ? "/gender/$gender/" : "" ;
$page_name .=  (isset($age_to) && !empty($age_to))  ? "/age_to/$age_to/" : "" ;
$page_name .=  (isset($age_from) && !empty($age_from))  ? "/age_from/$age_from/" : "" ;

$total_results1 = $user_count;
// Calculate total number of pages. Round up using ceil()
// $total_pages1 = ceil($total_results1 / $max_results1); 

if ($page == 0)
    $page = 1;     //if no page var is given, default to 1.
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_results1 / $limit); //lastpage is = total pages / items per page, rounded up.
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
?>
<div class="dsp_search_result_box_out box-border">
    <div class="dsp_search_result_box_in dspdp-row ">
        <?php
        $strQuery1 . " LIMIT $start, $limit  ";
        $user_profiles_table = $wpdb->get_results($strQuery1 . " LIMIT $start, $limit  ");
        foreach ($user_profiles_table as $member1) {
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
            <div class="dspdp-col-sm-4 dsp-sm-3"><div class="box-search-result image-container">
                <div class="img-box  dspdp-spacer circle-image">
                    <span class="online dspdp-online-status">
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
                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"    border="0" class="img-big" alt="Private Photo"/>
                                        </a>                
                                    <?php } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >				
                                            <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"      border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" /></a>                
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                        <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"   border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
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
                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"    border="0" class="img-big" alt="Private Photo" />
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
                <div class="user-status  dspdp-h5 dspdp-username dsp-username">

                    <span class="user-name"><strong>

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
                                        <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><span class="fa fa-users"></span></a>
                                    <?php } ?>
                                </div>
                            </li>
                        <?php } // END My friends module Activation check condition  ?>
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
                                    <?php } //if($check_my_friends_list>0)    ?>
                                <?php } else { ?>
                                    <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login">  <span class="fa fa-envelope-o"></span></a>
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
                                        <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><span class="fa fa-smile-o"></span></a>
                                    <?php } ?>
                                </div>
                            </li>
                        <?php } // END My friends module Activation check condition  ?> 
                    </ul>
                </div>
            </div></div>
            <?php
        }
        ?>
    </div></div>
<div class="row-paging"> 
    <div style="float:left; width:100%;">
        <?php
// --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
        echo $pagination;
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
        ?>
    </div>  
</div>
