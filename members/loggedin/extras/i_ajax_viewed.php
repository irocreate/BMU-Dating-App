<?php
/**
* This files is used to send ajax response for io viewed members
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

if (get('page'))
    $page = get('page');
else
    $page = 1;

// How many adjacent pages should be shown on each side?

// ----------------------------------------------- Start Paging code------------------------------------------------------ //
$dsp_counter_hits_table = $wpdb->prefix . DSP_COUNTER_HITS_TABLE;
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_counter_hits_query = "SELECT Distinct hits.member_id FROM $dsp_counter_hits_table hits, $dsp_user_profiles_table profile WHERE hits.member_id=profile.user_id AND hits.user_id=$user_id ";
if ($check_couples_mode->setting_status == 'Y') {
    $strQuery = "SELECT p.user_id FROM $dsp_user_profiles_table p, $dsp_counter_hits_table h where h.member_id=p.user_id and p.status_id=1 and h.user_id=$user_id and h.member_id<>0  GROUP BY h.member_id";
} else {
    $strQuery = "SELECT p.user_id FROM $dsp_user_profiles_table p, $dsp_counter_hits_table h where h.member_id=p.user_id and p.status_id=1 AND p.gender!='C' AND h.user_id=$user_id and h.member_id<>0  GROUP BY h.member_id";
}

$intRecordsPerPage = 1;
$intStartLimit = get('p'); # page selected 1,2,3,4...
if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
    $intStartLimit = 1; //default
}
$intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;
$strQuery = $strQuery . " ORDER BY p.user_profile_id desc";
$user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($dsp_counter_hits_query) AS total");


// ----------------------------------------------- Start Paging code------------------------------------------------------ //
$page_name = $root_link . "extras/i_viewed/";
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


$intTotalRecordsEffected = $user_count;

if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
    //print "Total records found: " . $intTotalRecordsEffected;
} else {
    ?>

    <div class="box-border">
        <div class="box-pedding">
            <div class="page-not-found">
                <?php echo language_code('DSP_NO_RECORD_FOUND_EXTRAS'); ?><br /><br />
            </div>
        </div>
    </div>

    <?php
} // if ($intTotalRecordsEffected != '0')	
$search_members = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");
if( ! empty( $search_members ) ) :
    //echo $strQuery ." LIMIT " . $from1 . "," . $max_results1; 
    foreach ($search_members as $member1) {
        if ($check_couples_mode->setting_status == 'Y') {
            $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'");
        } else {
            $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member1->user_id'");
        }
        $s_user_id = $member->user_id;
        $s_country_id = $member->country_id;
        $stealth_mode = $member->stealth_mode;
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
        $online =  ($stealth_mode == 'N') ? " dspdp-status-on " : " dspdp-status-off "; 
        ?>
        <div class="col-md-4 col-sm-6 col-xs-12">
    	<div class="box-search-result image-container">
            <div class="img-box dspdp-spacer circle-image">
                
                    <?php
                    if ($check_couples_mode->setting_status == 'Y') {
                        if ($s_gender == 'C') {
                            ?>

                        <?php if ($s_make_private == 'Y') { ?>

                            <?php if ($current_user->ID != $s_user_id) { ?>

                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                        <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"   border="0" class="img-big" alt="Private Photo"/>
                                    </a>                
                                <?php } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >				
                                        <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"     border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" /></a>                
                                    <?php
                                }
                            } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                    <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                                </a>
                            <?php } ?>

                        <?php } else { ?>

                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
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
                                        <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"      border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" /></a>                
                                    <?php
                                }
                            } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                    <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                                </a>
                            <?php } ?>
                        <?php } else { ?>

                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
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
                                    <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"   border="0" class="img-big" alt="Private Photo"/>
                                </a>                
                            <?php } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >				
                                    <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"     border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" /></a>                
                                <?php
                            }
                        } else {
                            ?>
                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                            </a>
                        <?php } ?>

                    <?php } else { ?>

                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                            <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                        </a>
                    <?php } ?>

                <?php } ?>

            </div>
            <div class="user-status dspdp-h5 dspdp-username dsp-username img-name">

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
                        </a></strong>
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
            <div class="user-details dspdp-spacer dspdp-user-details dsp-user-details">
                <?php echo $s_age ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?> <?php echo get_gender($s_gender); ?> <?php echo language_code('DSP_FROM_TEXT'); ?> <br /><?php if (@$city_name->name != "") echo @$city_name->name . ','; ?> <?php if (@$state_name->name != "") echo @$state_name->name . ','; ?> <?php echo @$country_name->name; ?>
            </div>
            <div class="user-links dsp-none lm-user-links">
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
                                        ?> href="javascript:void(0);" onclick="javascript:show_contact_message();" <?php } else { ?> href="<?php echo $root_link . "email/compose/frnd_id/" . $s_user_id . "/Act/send_msg/"; ?>" <?php } ?>  title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                        <span class="fa fa-smile-o"></span></a>
                                <?php } else { ?>
                                    <a <?php
                                    $result = check_contact_permissions($s_user_id);
                                    if (!$result) {
                                        ?> href="javascript:void(0);" onclick="javascript:show_contact_message();" <?php } else { ?> href="<?php echo $root_link . "email/compose/receive_id/" . $s_user_id . "/"; ?>" <?php } ?>  title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
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
                                        <a href='<?php echo $root_link . "view/send_wink_msg/receiver_id/" . $s_user_id . "/"; ?>' title="<?php echo language_code('DSP_SEND_WINK'); ?>"> <span class="fa fa-smile-o"></span></a>
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
        </div>
    </div>  <!-- dspdp-col-sm-4.dsp-sm-3 -->
        <?php
    }// foreach($search_members as $member)
else :
    printf( __( '<div class="lm-members-not-found">%1$s<span class="lm-pagenot-found-pagetitle">%2$s</span>%3$s</div>', 'love-match' ), 'No More','I Viewed Members', 'Available' );
endif; 
       

