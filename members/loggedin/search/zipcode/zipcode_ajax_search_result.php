<?php
/**
* @return This provide response to zipcode search result
*/

$request_uri = isset( $_POST[ 'request_uri'] ) ? esc_url( $_POST[ 'request_uri'] ) : '';

if (love_match_get2('page', $request_uri ))
    $page = love_match_get2('page', $request_uri );
else
    $page = 1;


global $wp_query, $wpdb;
$limit = 6;
$current_user = wp_get_current_user();
$user_id = $current_user->ID; 
$start = isset( $_POST[ 'paged' ] ) ? $_POST[ 'paged' ] : '';
$page_id = isset( $_POST[ 'paged_id'] ) ? absint( $_POST[ 'paged_id'] ) : ''; //fetch post query string id

$request_uri = isset( $_POST[ 'request_uri'] ) ? esc_url( $_POST[ 'request_uri'] ) : '';
$posts_table = $wpdb->prefix . POSTS;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$page_id'");
$root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";  // Print Site root link
$dsp_user_search_criteria_table = $wpdb->prefix . DSP_USER_SEARCH_CRITERIA_TABLE;
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_online_user_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
$check_flirt_module = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'flirt_module'");
// check My friends module is Activated or not.
$check_my_friend_module = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'my_friends'");
$check_couples_mode = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'" );
$imagepath = get_option('siteurl') . '/wp-content/';  // image Path


// ----------------------------------------------- Start Paging code------------------------------------------------------ //
$dsp_zipcode_table = $wpdb->prefix . DSP_ZIPCODES_TABLE;
$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : love_match_get2('gender', $request_uri );
$age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : love_match_get2('age_from', $request_uri );
$age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : love_match_get2('age_to', $request_uri );
$countryName = isset($_REQUEST['cmbCountry']) ? esc_sql($_REQUEST['cmbCountry']) : love_match_get2('cmbCountry', $request_uri );
$countryName = urldecode($countryName);
$seeking = isset($_REQUEST['seeking']) ? esc_sql($_REQUEST['seeking']) : love_match_get2('seeking', $request_uri );
$zipcode = isset($_REQUEST['zip_code']) ?esc_sql(absint(trim($_REQUEST['zip_code']), 'xss_clean')) : love_match_get2('zip_code', $request_uri );
$zipcode = ltrim($zipcode, '0');
$miles = isset($_REQUEST['miles']) ?esc_sql(absint(trim($_REQUEST['miles']), 'xss_clean')) : love_match_get2('miles', $request_uri );
$bolIfSearchCriteria = true;
$goback = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER']  : '';
$errors = array();
$cmbCountryid = 0;
if (strlen($countryName) > 1){
    $cmbCountryid = (int) $countryName;
    $get_Country = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE name = '" . $countryName . "'");
    $cmbCountryid = $get_Country->country_id;
}

if(!empty($zipcode) && !empty($miles)){
        $findzipcodelatlng = $wpdb->get_row("SELECT * FROM $dsp_zipcode_table WHERE zipcode = '$zipcode'");
        $errors[] = empty($findzipcodelatlng)? language_code("DSP_INVALID_ZIPCODE") : '';
        if(!empty($findzipcodelatlng)){
            ########## harvesine formula ########
            @$lat = $findzipcodelatlng->latitude;
            @$lng = $findzipcodelatlng->longitude;

            $d = $miles;
            $r = 3959;
            $findzipcodes = "SELECT zipcode,( $r * acos( cos( radians({$lat}) ) * cos( radians( `latitude` ) ) * cos( radians( `longitude` ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( `latitude`) ) ) ) AS distance  FROM  $dsp_zipcode_table  HAVING distance < $d ORDER BY distance ";
            $findallzipcodes = $wpdb->get_results($findzipcodes);
            foreach ($findallzipcodes as $allzipcodes) {
                    $searchzipcodes[] = $allzipcodes->zipcode;
                }
                if (isset($searchzipcodes) && $searchzipcodes != "") {
                    $searchzipcodes1 = "";
                    foreach ($searchzipcodes as $codes) {
                        if (is_numeric($codes)) {
                            $searchzipcodes1.=$codes . ",";
                        } else {
                            $searchzipcodes1.="'" . $codes . "',";
                        }
                    }
                    $searchzipcodes1 = rtrim($searchzipcodes1, ',');
                } else {
                    $searchzipcodes1 = '';
                }
            }  
       }
    $errors  = array_filter($errors);
    if(empty($errors)):    
        $strQuery = "SELECT DISTINCT (fb.user_id) FROM $dsp_user_profiles fb WHERE stealth_mode='N'   ";
        $strQuery .= !empty($searchzipcodes1) ? "   AND zipcode IN($searchzipcodes1)" : " ";
        if ($age_from >= 18) {
            $strQuery .= " and ((year(CURDATE())-year(fb.age)) > '" . $age_from . "') AND ((year(CURDATE())-year(fb.age)) < '" . $age_to . "')  ";
        }
        //$strQuery .=  isset($gender) ? " AND ": " ";
        if ($gender == 'M') {
            $strQuery .= " AND fb.gender='M' ";
        } else if ($gender == 'F') {
            $strQuery .= " AND fb.gender='F' ";
        } else if ($gender == 'C') {
            $strQuery .= " AND fb.gender='C' ";
        } else if ($gender == 'all') {
            $strQuery .= " AND fb.gender IN('M','F','C') ";
        }

        if (trim(strlen($seeking)) > 0) {
                $strQuery .= " AND fb.gender='" . $seeking . "'";
                $bolIfSearchCriteria = true;
        }
        
        if(!empty($cmbCountryid) && $cmbCountryid != 0){
            $strQuery .= " AND fb.country_id = '" . $cmbCountryid . "' ";
        }
        $intRecordsPerPage = 10;
        $intStartLimit = isset($_REQUEST['p']) ? $_REQUEST['p'] : ''; # page selected 1,2,3,4...
        if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
            $intStartLimit = 1; //default
        }
        $intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;
        if ($bolIfSearchCriteria) {
            if ($check_couples_mode->setting_status == 'Y') {
                $strQuery = $strQuery . "  AND fb.status_id=1 AND fb.country_id  > 0 ORDER BY fb.user_profile_id desc";
            } else {

                $strQuery = $strQuery . "  AND fb.status_id=1 AND gender != 'C' AND fb.country_id  > 0 ORDER BY fb.user_profile_id desc";
            }
            $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
        }

        // ----------------------------------------------- Start Paging code------------------------------------------------------ //
        $page_name = $root_link . "search/zipcode_search_result/zipcode_search/zipcode_search";
        if ($gender != "")
            $page_name.="/gender/" . $gender;
        if ($seeking != "")
            $page_name.="/seeking/" . $seeking;
        if ($age_from != "")
            $page_name.="/age_from/" . $age_from;
        if ($age_to != "")
            $page_name.="/age_to/" . $age_to;
        if ($countryName != "")
            $page_name.="/cmbCountry/" . $countryName;
        if ($zipcode != "")
            $page_name.="/zip_code/" . $zipcode;
        if ($zipcode != "")
            $page_name.="/miles/" . $miles ;

        $page_name .= "/";
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
        
        // ------------------------------------------------End Paging code------------------------------------------------------ // 
        $intTotalRecordsEffected = $user_count;
    endif;
    if(!empty($errors)){ 
?>
    <div class="box-border">
        <div class="box-pedding">  
            <div class="error">
                <?php foreach ($errors as $error) :?>
                    <span><?php echo $error; ?></span>
                    <span><a href="<?php echo $goback; ?>"><?php echo language_code('DSP_START_NEW_SEARCH'); ?></a></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>    
    
<?php }else if (  
            empty($intTotalRecordsEffected) || 
            $intTotalRecordsEffected == 0 
        ) {
?>
    <div class="box-border">
        <div class="box-pedding">  
            <div class="page-not-found">
                <?php echo language_code('DSP_NO_RECORD_FOUND'); ?><br /><br />
                <?php if(is_user_logged_in()):?>
                    <span><a href="<?php echo $root_link . "search/zipcode_search/"; ?>"><?php echo language_code('DSP_START_NEW_SEARCH'); ?></a></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
} else{
$search_members = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");
	if( ! empty( $search_members ) ) {
	    foreach ($search_members as $member1) {
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
	        $alt = '';
	        $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");
	        foreach ($private_mem as $private) {
	            $favt_mem[] = $private->favourite_user_id;
	        }
	        ?>

	        <div class="col-md-4 col-sm-6 col-xs-12">
	            <div  class="box-search-result image-container">

	                <div class="img-box dspdp-spacer  circle-image">
	                    
	                        <?php
	                        if ($check_couples_mode->setting_status == 'Y') {
	                            if ($s_gender == 'C') {
	                                ?>

	                            <?php if ($s_make_private == 'Y') { ?>

	                                <?php if ($current_user->ID != $s_user_id) { ?>

	                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
	                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
	                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"   border="0" class="img-big img-circle"  alt="Private Photo"/>
	                                        </a>                
	                                    <?php } else {
	                                        ?>
	                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >               
	                                            <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"     border="0" class="img-big img-circle" alt="<?php echo get_username($s_user_id); ?>"/></a>                
	                                        <?php
	                                    }
	                                } else {
	                                    ?>
	                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
	                                        <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"   border="0" class="img-big img-circle" alt="<?php echo get_username($s_user_id); ?>"/>
	                                    </a>
	                                <?php } ?>

	                            <?php } else { ?>

	                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
	                                    <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"   border="0" class="img-big img-circle" alt="<?php echo get_username($s_user_id); ?>"/>
	                                </a>
	                            <?php } ?>

	                        <?php } else { ?>

	                            <?php if ($s_make_private == 'Y') { ?>

	                                <?php if ($current_user->ID != $s_user_id) { ?>

	                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
	                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
	                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"    border="0" class="img-big img-circle" alt="Private Photo" />
	                                        </a>                
	                                    <?php } else {
	                                        ?>
	                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >              
	                                            <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"      border="0" class="img-big img-circle" alt="<?php echo get_username($s_user_id); ?>" /></a>                
	                                        <?php
	                                    }
	                                } else {
	                                    ?>
	                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
	                                        <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"   border="0" class="img-big img-circle" alt="<?php echo get_username($s_user_id); ?>"/>
	                                    </a>
	                                <?php } ?>
	                            <?php } else { ?>

	                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
	                                    <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"   border="0" class="img-big img-circle" alt="<?php echo get_username($s_user_id); ?>"/>
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
	                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"    border="0" class="img-big img-circle" alt="Private Photo" />
	                                    </a>                
	                                <?php } else {
	                                    ?>
	                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >              
	                                        <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"     border="0" class="img-big img-circle" alt="<?php echo get_username($s_user_id); ?>" /></a>                
	                                    <?php
	                                }
	                            } else {
	                                ?>
	                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
	                                    <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"   border="0" class="img-big img-circle" alt="<?php echo get_username($s_user_id); ?>" />
	                                </a>
	                            <?php } ?>

	                        <?php } else { ?>

	                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
	                                <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"   border="0" class="img-big img-circle" alt="<?php echo get_username($s_user_id); ?>" />
	                            </a>
	                        <?php } ?>

	                    <?php } ?>

	                </div>
	                <div class="user-status img-name">
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

	                        <!-- online status  -->
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
	                        ?>
	                    </span>

	                </div>
	                <div class="user-details dspdp-spacer dspdp-user-details  dsp-user-details">
	                    <?php echo $s_age ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?> <?php echo get_gender($s_gender); ?> <?php echo language_code('DSP_FROM_TEXT'); ?> <br /><?php if (@$city_name->name != "") echo @$city_name->name . ','; ?> <?php if (@$state_name->name != "") echo @$state_name->name . ','; ?> <?php echo @$country_name->name; ?>
	                </div>
	                <div class="user-links lm-user-links">
	                    <ul class="dspdp-row">
	                        <?php if ($check_my_friend_module->setting_status == 'Y') { // Check My friend module Activated or not  ?>
	                            <li class="dspdp-col-xs-3">
	                                <div class="dsp_fav_link_border">
	                                    <?php
	                                    $check_user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles_table WHERE user_id=$user_id");
	                                    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
	                                        if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
	                                            ?>
	                                            <a href="<?php echo $root_link . "add_friend/user_id/" . $user_id . "/frnd_userid/" . $s_user_id . "/"; ?>" title="<?php echo language_code('DSP_ADD_TO_FRIENDS'); ?>">
	                                                <span class="fa fa-plus-square"></span></a>
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
	                                    <?php } //if($check_my_friends_list>0)    ?>
	                                <?php } else { ?>
	                                    <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><span class="fa fa-envelope-o"></span>></a>
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
	                                               <span class="fa fa-smile-o"></span></a></a>
	                                        <?php } else { ?>
	                                            <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"><span class="fa fa-smile-o"></span></a></a>
	                                        <?php } ?>
	                                    <?php } else { ?>
	                                        <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><span class="fa fa-smile-o"></span></a></a>
	                                    <?php } ?>
	                                </div>
	                            </li>
	                        <?php } // END My friends module Activation check condition  ?> 
	                    </ul>
	                </div>
	            </div>
	        </div>
	        <?php
	    }// foreach($search_members as $member)
 	} else {
 		printf( __( '<div class="lm-members-not-found">%1$s<span class="lm-pagenot-found-pagetitle">%2$s</span>%3$s</div>', 'love-match' ), 'No More','Members', 'Available' );

 	}
} 
           