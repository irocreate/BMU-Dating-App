<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ----------------------------------------------- Start Paging code------------------------------------------------------ //  
global $wp_query;
$page_id = $wp_query->post->ID; //fetch post query string id
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$posts_table = $wpdb->prefix . POSTS;
$insertMemberPageId = "UPDATE $dsp_general_settings SET setting_value = '$page_id' WHERE setting_name ='member_page_id'";
$wpdb->query($insertMemberPageId);
$posts_table = $wpdb->prefix . POSTS;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$page_id'");
// ROOT PATH 
$root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";  // Print Site root link
if (isset($_GET['page1']))
    $page = $_GET['page1'];
else
    $page = 1;

// How many adjacent pages should be shown on each side?
$adjacents = 2;
$limit = $check_search_result->setting_value;
if ($page)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;
$page_name = $root_link . "online_members/";
// ------------------------------------------------End Paging code------------------------------------------------------ //
if (isset($_REQUEST['submit'])) {
    $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
    $age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : '';
    $age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : '';
}
?>
</div>
<div style="margin-bottom: 10px; ">
    <form action="<?php echo $root_link . "online_members" ?>" method="post">
        <div align="center"><?php echo language_code('DSP_GENDER') ?>
            <select name="gender">
                <option value="all" <?php if ($gender == 'all' || isset($_REQUEST['show'])) { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> >All</option>
                <?php echo get_gender_list($gender); ?>
            </select>
            <?php echo language_code('DSP_AGE') ?>
            <select name="age_from">
                <?php for ($i = '18'; $i <= '90'; $i++) { ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php } ?>


            </select>
            <?php echo language_code('DSP_TO') ?>
            <select  name="age_to">
                <?php for ($j = '90'; $j >= '18'; $j--) { ?>
                    <option value="<?php echo $j ?>"><?php echo $j ?></option>
                <?php } ?>


            </select>
            <input name="submit" type="submit" value="<?php echo language_code('DSP_FILTER_BUTTON') ?>" />
    </form></div>
</div>


<?php
$dsp_user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$onlinequery = "SELECT * FROM $dsp_user_online_table oln INNER JOIN $dsp_user_profiles_table usr ON(usr.user_id=oln.user_id) WHERE oln.status = 'Y' AND usr.country_id !=0 AND usr.stealth_mode='N'    ";

if (isset($age_from) && $age_from >= 18) {
    $onlinequery .= " and ((year(CURDATE())-year(age)) > '" . $age_from . "') AND ((year(CURDATE())-year(age)) < '" . $age_to . "') AND ";
} else {
    $age_to = 90;
    $age_from = 18;
    $onlinequery .= " and ((year(CURDATE())-year(age)) > '" . $age_from . "') AND ((year(CURDATE())-year(age)) < '" . $age_to . "') AND ";
}

if (isset($gender) && $gender == 'M' && !isset($_REQUEST['show'])) {
    $onlinequery .= " gender='M' ";
} else if (isset($gender) && $gender == 'F' && !isset($_REQUEST['show'])) {
    $onlinequery .= " gender='F' ";
} else if (isset($gender) && $gender == 'C' && !isset($_REQUEST['show'])) {
    $onlinequery .= " gender='C' ";
} else {
    if ($check_couples_mode->setting_status == 'Y') {
        $onlinequery .= " gender IN('M','F','C') ";
    } else {
        $onlinequery .= " gender IN('M','F') ";
    }
}
$onlinequery.= "GROUP BY oln.user_id";
$wpdb->get_results($onlinequery);
$user_count = $wpdb->num_rows;
//$user_count = mysql_num_rows(mysql_query($onlinequery));
$total_results1 = $user_count;
// ------------------------------------------------start Paging code------------------------------------------------------ // 		
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
        $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "?page1=$prev\">".language_code('DSP_PREVIOUS')."</a></div>";
    else
        $pagination.= "<span  class='disabled'>".language_code('DSP_PREVIOUS')."</span>";

    //pages	
    if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
        for ($counter = 1; $counter <= $lastpage; $counter++) {
            if ($counter == $page)
                $pagination.= "<span class='current'>$counter</span>";
            else
                $pagination.= "<div><a href=\"" . $page_name . "?page1=$counter\">$counter</a></div>";
        }
    }
    elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
        //close to beginning; only hide later pages
        if ($page < 1 + ($adjacents * 2)) {
            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                if ($counter == $page)
                    $pagination.= "<span class='current'>$counter</span>";
                else
                    $pagination.= "<div><a href=\"" . $page_name . "?page1=$counter\">$counter</a></div>";
            }
            $pagination.= "<span>...</span>";
            $pagination.= "<div><a href=\"" . $page_name . "?page1=$lpm1\">$lpm1</a></div>";
            $pagination.= "<div><a href=\"" . $page_name . "?page1=$lastpage\">$lastpage</a></div>";
        }
        //in middle; hide some front and some back
        elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
            $pagination.= "<div><a href=\"" . $page_name . "?page1=1\">1</a></div>";
            $pagination.= "<div><a href=\"" . $page_name . "?page1=2\">2</a></div>";
            $pagination.= "<span>...</span>";
            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                if ($counter == $page)
                    $pagination.= "<div class='current'>$counter</div>";
                else
                    $pagination.= "<div><a href=\"" . $page_name . "?page1=$counter\">$counter</a></div>";
            }
            $pagination.= "<span>...</span>";
            $pagination.= "<div><a href=\"" . $page_name . "?page1=$lpm1\">$lpm1</a></div>";
            $pagination.= "<div><a href=\"" . $page_name . "?page1=$lastpage\">$lastpage</a></div>";
        }
        //close to end; only hide early pages
        else {
            $pagination.= "<div><a href=\"" . $page_name . "?page1=1\">1</a></div>";
            $pagination.= "<div><a href=\"" . $page_name . "?page1=2\">2</a></div>";
            $pagination.= "<span>...</span>";
            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination.= "<span class='current'>$counter</span>";
                else
                    $pagination.= "<div><a href=\"" . $page_name . "?page1=$counter\">$counter</a></div>";
            }
        }
    }

    //next button
    if ($page < $counter - 1)
        $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "?page1=$next\">".language_code('DSP_NEXT')."</a></div>";
    else
        $pagination.= "<span class='disabled'>".language_code('DSP_NEXT')."</span>";
    $pagination.= "</div>\n";
}

// ------------------------------------------------End Paging code------------------------------------------------------ // 

$onlinequery.= " LIMIT  $start, $limit";
$online_member = $wpdb->get_results($onlinequery);
?>

<div id="search_width" style="margin:auto">
    <?php
    foreach ($online_member as $member1) {
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
        $alt = '';
        $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");
        foreach ($private_mem as $private) {
            $favt_mem[] = $private->favourite_user_id;
        }
        ?>
        <div class="box-search-result">
            <div class="img-box">
                <?php
                if ($check_couples_mode->setting_status == 'Y') {
                    if ($s_gender == 'C') {
                        ?>

                        <?php if ($s_make_private == 'Y') { ?>

                            <?php if ($current_user->ID != $s_user_id) { ?>

                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  style="width:135px; height:135px;" border="3" class="img" alt="Private Photo" />
                                    </a>                
                                <?php } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >				
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    style="width:135px; height:135px;" border="3" class="img" alt="<?php echo get_username($s_user_id); ?>" /></a>                
                                    <?php
                                }
                            } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:135px; height:135px;" border="3" class="img" alt="<?php echo get_username($s_user_id); ?>" />
                                </a>
                            <?php } ?>

                        <?php } else { ?>

                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:135px; height:135px;" border="3" class="img" alt="<?php echo get_username($s_user_id); ?>" />
                            </a>
                        <?php } ?>

                    <?php } else { ?>

                        <?php if ($s_make_private == 'Y') { ?>

                            <?php if ($current_user->ID != $s_user_id) { ?>

                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  style="width:135px; height:135px;" border="3" class="img" alt="Private photo"/>
                                    </a>                
                                <?php } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >				
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"     style="width:135px; height:135px;" border="3" class="img" alt="<?php echo get_username($s_user_id); ?>" /></a>                
                                    <?php
                                }
                            } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:135px; height:135px;" border="3" class="img" alt="<?php echo get_username($s_user_id); ?>" />
                                </a>
                            <?php } ?>
                        <?php } else { ?>

                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:135px; height:135px;" border="3" class="img" alt="<?php echo get_username($s_user_id); ?>" />
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
                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  style="width:135px; height:135px;" border="3" class="img" alt="Private photo" />
                                </a>                
                            <?php } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >				
                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    style="width:135px; height:135px;" border="3" class="img" alt="<?php echo get_username($s_user_id); ?>" /></a>                
                                <?php
                            }
                        } else {
                            ?>
                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:135px; height:135px;" border="3" class="img" alt="<?php echo get_username($s_user_id); ?>" />
                            </a>
                        <?php } ?>

                    <?php } else { ?>

                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:135px; height:135px;" border="3" class="img" alt="<?php echo get_username($s_user_id); ?>" />
                        </a>
                    <?php } ?>

                <?php } ?>

            </div>
            <div class="user-status">
                <span class="online"><?php $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $tbl_name WHERE user_id=$s_user_id"); ?>
                    <img class="icon-on-off" src="<?php
                    echo $fav_icon_image_path;
                    if ($check_online_user > 0){
                        echo 'online-chat.gif';
                        $alt = 'online';
                    } 
                    else {
                        echo 'off-line-chat.jpg';
                        $alt = 'offline';
                    }
                    ?>" title="<?php
                         if ($check_online_user > 0)
                             echo language_code('DSP_CHAT_ONLINE');
                         else
                             echo language_code('DSP_CHAT_OFFLINE');
                         ?>" border="0" alt="<?php echo $alt; ?>" /></span>
                <span class="user-name"><strong>

                        <?php
                        if ($check_couples_mode->setting_status == 'Y') {
                            if ($s_gender == 'C') {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
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
            <div class="user-details">
                <?php echo $s_age ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?> <?php echo get_gender($s_gender); ?> <?php echo language_code('DSP_FROM_TEXT'); ?> <br /><?php if (@$city_name->name != "") echo @$city_name->name . ','; ?> <?php if (@$state_name->name != "") echo @$state_name->name . ','; ?> <?php echo @$country_name->name; ?>
            </div>
            <div class="user-links">
                <ul>
                    <?php if ($check_my_friend_module->setting_status == 'Y') { // Check My friend module Activated or not  ?>
                        <li>
                            <div class="dsp_fav_link_border">
                                <?php
                                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                    if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                        ?>
                                        <a href="<?php echo $root_link . "add_friend/user_id/" . $user_id . "/frnd_userid/" . $s_user_id . "/"; ?>" title="<?php echo language_code('DSP_ADD_TO_FRIENDS'); ?>" alt="<?php echo language_code('DSP_ADD_TO_FRIENDS'); ?>"/>
                                            <img src="<?php echo $fav_icon_image_path ?>friends.jpg" border="0" /></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"><img  alt="Edit Profile" src="<?php echo $fav_icon_image_path ?>friends.jpg" border="0" alt="<?php echo language_code('DSP_ADD_TO_FRIENDS'); ?>" /></a> 
                                    <?php } ?>
                                <?php } else { ?>
                                    <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><img alt="Edit Login" src="<?php echo $fav_icon_image_path ?>friends.jpg" border="0" alt="<?php echo language_code('DSP_ADD_TO_FRIENDS'); ?>" /></a>
                                <?php } ?>
                            </div>
                        </li>
                    <?php } // END My friends module Activation check condition  ?>
                    <li>
                        <div class="dsp_fav_link_border">
                            <?php
                            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                ?>
                                <a href="<?php echo $root_link . "add_favorites/user_id/" . $user_id . "/fav_userid/" . $s_user_id . "/"; ?>" title="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>"><img src="<?php echo $fav_icon_image_path ?>star.jpg" border="0"  alt="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>"/></a>
                            <?php } else { ?>
                                <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><img src="<?php echo $fav_icon_image_path ?>star.jpg" border="0" alt="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>" /></a>
                            <?php } ?>
                        </div>
                    </li>
                    <li>
                        <div class="dsp_fav_link_border" >
                            <?php
                            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                if (isset($check_my_friends_list) && $check_my_friends_list > 0) {
                                    ?>
                                    <a href="<?php echo $root_link . "email/compose/frnd_id/" . $s_user_id . "/Act/send_msg/"; ?>" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                        <img src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" alt="<?php echo language_code('DSP_SEND_MESSAGES'); ?>" /></a>
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . "email/compose/receive_id/" . $s_user_id . "/"; ?>" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                        <img src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" alt="<?php echo language_code('DSP_SEND_MESSAGES'); ?>" /></a>
                                <?php } //if($check_my_friends_list>0)   ?>
                            <?php } else { ?>
                                <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login">  <img src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0"  alt="<?php echo language_code('DSP_SEND_MESSAGES'); ?>" /></a>
                            <?php } ?>
                        </div>
                    </li>
                    <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not  ?>
                        <li>
                            <div class="dsp_fav_link_border">
                                <?php
                                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                    if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                        ?>
                                        <a href='<?php echo $root_link . "view/send_wink_msg/receiver_id/" . $s_user_id . "/"; ?>' title="<?php echo language_code('DSP_SEND_WINK'); ?>">
                                            <img src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" alt="<?php echo language_code('DSP_SEND_WINK'); ?>"/></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"><img alt="Edit Profile" src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" alt="<?php echo language_code('DSP_SEND_WINK'); ?>"/></a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login">  <img alt="Login" src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" alt="<?php echo language_code('DSP_SEND_WINK'); ?>" /></a>
                                <?php } ?>
                            </div>
                        </li>
                    <?php } // END My friends module Activation check condition   ?> 
                </ul>
            </div>
        </div>
    <?php }
    ?>

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