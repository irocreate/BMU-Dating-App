<?php
/**
* This files is used to send response on scroll/click event on mymatches page
*/

global $wp_query, $wpdb;
$start = isset( $_POST[ 'paged' ] ) ? $_POST[ 'paged' ] : '';
$limit = 6;
$page_id = isset( $_POST[ 'paged_id'] ) ? $_POST[ 'paged_id'] : ''; //fetch post query string id

$request_uri = isset( $_POST[ 'request_uri'] ) ? esc_url( $_POST[ 'request_uri'] ) : '';
$current_user = wp_get_current_user();
$posts_table = $wpdb->prefix . POSTS;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$page_id'");
$root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";  // Print Site root link
$user_id = $current_user->ID;

$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_online_user_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE; 
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_profile_setup_table = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;

//only for mymatches
$dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;

$check_online_member_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'random_online_members'");
$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$posts_table = $wpdb->prefix . POSTS;
$insertMemberPageId = "UPDATE $dsp_general_settings SET setting_value = '$page_id' WHERE setting_name ='member_page_id'";
$wpdb->query($insertMemberPageId);
$posts_table = $wpdb->prefix . POSTS;


$check_flirt_module = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'flirt_module'");
$check_user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles_table WHERE user_id=$user_id");

$check_couples_mode = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'" );
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$check_my_friend_module = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'my_friends'");
$imagepath = get_option('siteurl') . '/wp-content/';  // image Path


$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
$dsp_messages_table = $wpdb->prefix . DSP_EMAILS_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_favourites_list_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;

if ( love_match_get('page', $request_uri ) != "")
    $page =  love_match_get('page', $request_uri );
else
    $page = 1;

if( ! isset ($_REQUEST['gender']) ){
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

if (isset($_REQUEST['submit']) ||  love_match_get('submit', $request_uri ) != "") {
    $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] :  love_match_get('gender', $request_uri );
    $age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] :  love_match_get('age_from', $request_uri );
    $age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] :  love_match_get('age_to', $request_uri );
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
$page_name = isset($page_name) ? $page_name:'';

?>

<div class="search-match">
<?php

    $match_query .= " LIMIT  $start, $limit";

    $match_member = $wpdb->get_results( $match_query );

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
      <div class="col-md-4 col-sm-6 col-xs-12">  <div class="box-search-result image-container">
            <div class="img-box  dspdp-spacer circle-image">
               
                   <?php
                    if ($check_couples_mode->setting_status == 'Y') {
                        if ($s_gender == 'C') {
                            ?>

                        <?php if ($s_make_private == 'Y') { ?>

                            <?php if ($current_user->ID != $s_user_id) { ?>

                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                        <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"   border="0" class="img-big" alt="Private Photo" />
                                    </a>                
                                <?php } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >				
                                        <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"     border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>                
                                    <?php
                                }
                            } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                    <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                </a>
                            <?php } ?>

                        <?php } else { ?>

                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                            </a>
                        <?php } ?>

                    <?php } else { ?>

                        <?php if ($s_make_private == 'Y') { ?>

                            <?php if ($current_user->ID != $s_user_id) { ?>

                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                        <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"   border="0" class="img-big" alt="Private Photo" />
                                    </a>                
                                <?php } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >				
                                        <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"      border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>                
                                    <?php
                                }
                            } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                    <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                </a>
                            <?php } ?>
                        <?php } else { ?>

                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
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
                                    <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"   border="0" class="img-big" alt="Private Photo" />
                                </a>                
                            <?php } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >				
                                    <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"     border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" /></a>                
                                <?php
                            }
                        } else {
                            ?>
                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                            </a>
                        <?php } ?>

                    <?php } else { ?>

                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                            <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                        </a>
                    <?php } ?>

                <?php } ?>

            </div>
            <div class="user-status dsp-username img-name">

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
                        </a>
                    </span>
                    <!-- online status  -->
		            <span class="online dspdp-online-status">
		                <?php
		                    $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_online_user_table WHERE user_id=$s_user_id");
		                    $check_online_user = ($stealth_mode == "Y") ? '0' : $check_online_user;
		                    //echo $fav_icon_image_path;
		                    if ($check_online_user > 0)
		                        echo '<span class="dspdp-status-on" '.language_code('DSP_CHAT_ONLINE').'></span>';
		                    else
		                        echo '<span class="dspdp-status-off" '.language_code('DSP_CHAT_OFFLINE').'></span>';
		                ?>
		            </span>
            </div>
            <div class="user-details  dspdp-spacer dspdp-user-details dsp-user-details">
                <?php echo $s_age ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?> <?php echo get_gender($s_gender); ?> <?php echo language_code('DSP_FROM_TEXT'); ?> <br /><?php if (@$city_name->name != "") echo @$city_name->name . ','; ?> <?php if (@$state_name->name != "") echo @$state_name->name . ','; ?> <?php echo @$country_name->name; ?>
            </div>
            <div class="user-links lm-user-links">
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
} else {
    printf( __( '<div class="lm-members-not-found">%1$s<span class="lm-pagenot-found-pagetitle">%2$s</span>%3$s</div>', 'love-match' ), 'No More','Matched Members', 'Available' );
}
