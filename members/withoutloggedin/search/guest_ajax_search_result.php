<?php
/**
* This files is used to provide ajax response for logged out search result
*/
?>
<script>

    dsp = jQuery.noConflict();

    dsp(document).ready(function() {
        dsp(".not_logged").click(function() {
            dsp("#alert_dialog").dialog();
        });

    });
</script>
<?php 
$request_uri = isset( $_POST[ 'request_uri'] ) ? esc_url( $_POST[ 'request_uri'] ) : '';
if ( love_match_get( 'page1',$request_uri ) != "")
    $page =  love_match_get( 'page1', $request_uri );
else
    $page = 1;

global $wp_query, $wpdb;
$limit = 6;
$current_user = wp_get_current_user();
$user_id = $current_user->ID; 
$start = isset( $_POST[ 'paged' ] ) ? $_POST[ 'paged' ] : '';
$page_id = isset( $_POST[ 'paged_id'] ) ? absint( $_POST[ 'paged_id'] ) : ''; //fetch post query string id
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

$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] :  love_match_get( 'gender', $request_uri );
$seeking = isset($_REQUEST['seeking']) ? $_REQUEST['seeking'] :  love_match_get( 'seeking', $request_uri );
$age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] :  love_match_get( 'age_to', $request_uri );
$age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] :  love_match_get( 'age_from', $request_uri );
$Pictues_only = isset($_REQUEST['Pictues_only']) ? $_REQUEST['Pictues_only'] :  love_match_get( 'Pictues_only', $request_uri );
$Online_only = isset($_REQUEST['Online_only']) ? $_REQUEST['Online_only'] :  love_match_get( 'Online_only', $request_uri );
$search_save = isset($_REQUEST['savesearch']) ? $_REQUEST['savesearch'] :  love_match_get( 'savesearch', $request_uri);
$check_save = isset($_REQUEST['check_save']) ? $_REQUEST['check_save'] :  love_match_get( 'check_save', $request_uri);
$search_type = isset($_REQUEST['search_type']) ? $_REQUEST['search_type'] :  love_match_get( 'search_type', $request_uri);
$countryName = isset($_REQUEST['cmbCountry']) ? $_REQUEST['cmbCountry'] :  love_match_get( 'cmbCountry', $request_uri);
$lat = isset($_REQUEST['lat']) ? esc_sql(sanitizeData(trim($_REQUEST['lat']), 'xss_clean')) :  love_match_get( 'lat', $request_uri );
$lng = isset($_REQUEST['lng']) ? esc_sql(sanitizeData(trim($_REQUEST['lng']), 'xss_clean')) :  love_match_get( 'lng', $request_uri);
//default for  constant value is in Miles
$constantValues = isset($_REQUEST['unit']) ? esc_sql(sanitizeData(trim($_REQUEST['unit']), 'xss_clean')) :  love_match_get( 'unit', $request_uri );
$distance = isset($_REQUEST['distance']) ? esc_sql(sanitizeData(trim($_REQUEST['distance']), 'xss_clean')) :  love_match_get( 'distance', $request_uri );
$countryName = urldecode($countryName);
if(empty($constantValues) || $constantValues == 0){
    $constantValues = 3959;
}
/* ------------------------ */
$kontry = '';

         /* ----------------------- */
        if (strlen($countryName) > 1) {
            $get_Country = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $dsp_country_table WHERE name = %s", $countryName ) );
            $cmbCountryid = isset($get_Country) ? $get_Country->country_id : 0;
        } else {
            $cmbCountryid = 0;
        }

        if (is_numeric($countryName)) {
            $cmbCountryid = (int) $countryName;
            $gc = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE country_id = '" . $cmbCountryid . "'");
            $kontry = isset($gc->name) ? $gc->name : '';
        } else {
            $kontry = urldecode($countryName);
        }    
        $stateName = isset($_REQUEST['cmbState']) ?urldecode($_REQUEST['cmbState']) : get('cmbState');
        if($stateName != "Select" && strlen($stateName) > 1) { 
            $get_State = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $dsp_state_table WHERE name = %s", $stateName ) );
            $cmbStateid = $get_State->state_id;
            
        } else{ 
            $cmbStateid = 0;
        }

        
        $cityName = isset($_REQUEST['cmbCity']) ?urldecode($_REQUEST['cmbCity']): get('cmbCity');
        if ($cityName != "Select" && strlen($cityName) > 1) {
            if ($cmbStateid == 0) {
                $get_City = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $dsp_city_table WHERE name = %s and country_id= %s", $cityName , $cmbCountryid ) );
            } else {
                $get_City = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $dsp_city_table WHERE name =%s and state_id= %s", $cityName , $cmbStateid ) );
            }
            $cmbCityid = $get_City->city_id;
        } else {
            $cmbCityid = 0;
        }

        $cmbCountry = is_numeric($countryName)?$countryName:$cmbCountryid;
        $cmbState = is_numeric($stateName)?$stateName:$cmbStateid;
        $cmbCity = is_numeric($cityName)?$cityName:$cmbCityid;
        $bolIfSearchCriteria = false;
        $boollocationpicturecriteria = false;
       //$strQuery = "SELECT  DISTINCT (fb.user_id) FROM $dsp_user_profiles fb WHERE fb.stealth_mode != 'Y' AND";
        $strQuery = "SELECT  DISTINCT (fb.user_id),(year(CURDATE())-year(fb.age)) age ";
        if(!empty($lat) && !empty($lng)){
        $strQuery .= ",( $constantValues * acos( cos( radians({$lat}) ) * cos( radians( `lat` ) ) * cos( radians( `lng` ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( `lat`) ) ) ) AS distance  ";
        }
        $strQuery .= " FROM $dsp_user_profiles fb  WHERE fb.country_id > 0 ";
       
        if (($cmbCountry > 0) && ($Pictues_only == 'P' || empty($Pictues_only)) && ($cmbState <= 0) && ($cmbCity <= 0)) { 
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 25);
            $strQuery .= " INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE (fb.country_id = '" . $cmbCountry . "') ";
            $bolIfSearchCriteria = true;

        }

        if (($cmbCountry <= 0) && ($Pictues_only == 'Y') && ($cmbState <= 0) && ($cmbCity <= 0)) {
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 25);
            $strQuery .= " INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id WHERE p.status_id=1  AND";
            $bolIfSearchCriteria = true;
            $boollocationpicturecriteria = true;
        }

        if (($cmbCountry <= 0) && ($Pictues_only == 'N') && ($cmbState <= 0) && ($cmbCity <= 0)) {
            //$strQuery = substr($strQuery,0,strlen($strQuery)-7);
            $strQuery .= "AND fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1)  AND ";
            $bolIfSearchCriteria = true;
            $boollocationpicturecriteria = true;
        }


        if (($cmbCountry > 0) && ($Pictues_only == 'Y') && ($cmbState <= 0) && ($cmbCity <= 0)) {
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 25);
            $strQuery .= " INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id  WHERE p.status_id=1 AND (fb.country_id = '" . $cmbCountry . "') AND ";
            $bolIfSearchCriteria = true;
            $boollocationpicturecriteria = true;

        }

        if (($cmbCountry > 0) && ($Pictues_only == 'N') && ($cmbState <= 0) && ($cmbCity <= 0)) {
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 25);
            $strQuery .= " INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.country_id = '" . $cmbCountry . "') AND ";
            $bolIfSearchCriteria = true;
            $boollocationpicturecriteria = true;
        }
        if (($cmbCountry > 0) && ($cmbCity > 0) && ($cmbState <= 0) && ($Pictues_only == 'P')) {
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 25);
            $strQuery .= " INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
            $bolIfSearchCriteria = true;
            $boollocationpicturecriteria = true;
        }

        if (($cmbCountry > 0) && ($cmbCity > 0) && ($cmbState <= 0) && ($Pictues_only == 'N')) {
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 25);
            $strQuery .= " INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
            $bolIfSearchCriteria = true;
            $boollocationpicturecriteria = true;
        }

        if (($cmbCountry > 0) && ($cmbCity > 0) && ($cmbState <= 0) && ($Pictues_only == 'Y')) {
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 25);
            $strQuery .= " INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE  p.status_id=1 AND (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
            $bolIfSearchCriteria = true;
            $boollocationpicturecriteria = true;
        }



        if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity <= 0) && ($Pictues_only == 'P')) {
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 25);
            $strQuery .= " INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
            $bolIfSearchCriteria = true;
            $boollocationpicturecriteria = true;
        }

        if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity <= 0) && ($Pictues_only == 'N')) {
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 25);
            $strQuery .= " INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
            $bolIfSearchCriteria = true;
            $boollocationpicturecriteria = true;
        }

        if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity <= 0) && ($Pictues_only == 'Y')) {
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 25);
            $strQuery .= " INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE  p.status_id=1 AND (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
            $bolIfSearchCriteria = true;
            $boollocationpicturecriteria = true;
        }

        if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($Pictues_only == 'P')) {
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 25);
            $strQuery .= " INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id WHERE (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND (fb.city_id = '" . $cmbCity . "') AND ";
            $bolIfSearchCriteria = true;
            $boollocationpicturecriteria = true;
        }

        if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($Pictues_only == 'Y')) {
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 25);
            $strQuery .= " INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id WHERE p.status_id=1 AND (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND (fb.city_id = '" . $cmbCity . "') AND ";
            $bolIfSearchCriteria = true;
            $boollocationpicturecriteria = true;
        }

        if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($Pictues_only == 'N')) {
            $strQuery = substr($strQuery, 0, strlen($strQuery) - 25);
            $strQuery .= " INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND (fb.city_id = '" . $cmbCity . "') AND ";
            $bolIfSearchCriteria = true;
            $boollocationpicturecriteria = true;
        }

        
        if( $boollocationpicturecriteria ) {
            $lm_connect_query = '';
        } else {
            $lm_connect_query = 'AND';
        }

        if ($age_from >= 18) {
            $strQuery .= " $lm_connect_query ((year(CURDATE())-year(fb.age)) BETWEEN $age_from AND $age_to)  AND ";
            $bolIfSearchCriteria = true;
        }
        if (trim(strlen($seeking)) > 0) {
            $strQuery .= "  gender='" . $seeking . "' AND ";
            $bolIfSearchCriteria = true;
        }


        if (trim(strlen($gender)) > 0) {
            $strQuery .= " seeking='" . $gender . "' AND ";
            $bolIfSearchCriteria = true;
        }
       
        $intRecordsPerPage = 10;
        $intStartLimit = isset($_REQUEST['p']) ? $_REQUEST['p'] : ''; # page selected 1,2,3,4...
        if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages))
            $intStartLimit = 1; //default
        }
        $intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;
        if ($bolIfSearchCriteria) {
            $strQuery = trim(substr($strQuery, 0, strlen($strQuery) - 4)) . "  AND fb.status_id=1  ";
            $strQuery .=  (!empty($lat) && !empty($lng) && !empty($distance)) ? " HAVING distance < $distance " : ' ';
            $strQuery .=  " ORDER BY fb.user_profile_id desc ";
            
           
        }
//print_r($strQuery);die;
$user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");

// ----------------------------------------------- Start Paging code------------------------------------------------------ //

if ($search_type == "basic_search") {
    $page_name = $root_link . "g_search_result/";
    if ($search_type != "")
        $page_name.="search_type/" . $search_type;
    if ($gender != "")
        $page_name.="/gender/" . $gender;
    if ($seeking != "")
        $page_name.="/seeking/" . $seeking;
    if ($age_from != "")
        $page_name.="/age_from/" . $age_from;
    if ($age_to != "")
        $page_name.="/age_to/" . $age_to;
    if ($cmbCountry != "")
        $page_name.="/cmbCountry/" . $cmbCountry;
    if ($cmbState != "")
        $page_name.="/cmbState/" . $cmbState;
    if ($cmbCity != "")
        $page_name.="/cmbCity/" . $cmbCity;
    if ($Online_only != "")
        $page_name.="/Online_only/" . $Online_only;
    if ($Pictues_only != "")
        $page_name.="/Pictues_only/" . $Pictues_only;
    $page_name.="/submit/Search";
} else {
    $page_name = $root_link . "g_search_result/";
    if ($gender != "")
        $page_name.="/gender/" . $gender;
    if ($seeking != "")
        $page_name.="/seeking/" . $seeking;
    if ($age_from != "")
        $page_name.="/age_from/" . $age_from;
    if ($age_to != "")
        $page_name.="/age_to/" . $age_to;
    if ($kontry != "")
        $page_name.="/cmbCountry/" . $kontry;
    $page_name.="/submit/Search";
}

$total_results1 = $user_count;
$search_members = $wpdb->get_results($strQuery . " LIMIT $start, $limit");
if( ! empty( $search_members ) ) :
    foreach ($search_members as $member1) {
        if ($check_couples_mode->setting_status == 'Y') {
            $member = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $dsp_user_profiles WHERE user_id = %s", $member1->user_id ) );
        } else {
            $member = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = %s", $member1->user_id ) );
        }
        $s_user_id = $member->user_id;
        $stealth_mode = $member->stealth_mode;
        $s_country_id = $member->country_id;
        $s_gender = $member->gender;
        $s_seeking = $member->seeking;
        $s_state_id = $member->state_id;
        $s_city_id = $member->city_id;
        $s_age = $member1->age;
        $s_make_private = $member->make_private;
//$s_user_pic = $member->user_pic;
        $displayed_member_name = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $dsp_user_table WHERE ID = %s" , $s_user_id ) );
        $country_name = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $dsp_country_table where country_id=%s", $s_country_id ) );
        $state_name = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $dsp_state_table where state_id=%s", $s_state_id ) );
        $city_name = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $dsp_city_table where city_id=%s", $s_city_id ) );
        $favt_mem = array();
        $private_mem = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $dsp_user_favourites_table WHERE user_id=%s", $s_user_id ) );
        foreach ($private_mem as $private) {
            $favt_mem[] = $private->favourite_user_id;
        }
        $online =  ($stealth_mode == 'N') ? " dspdp-status-on " : " dspdp-status-off "; ?>

        <div class="col-md-3 col-sm-6 col-xs-12 dsp-home-member"><div class="box-search-result image-container">
            <div class="img-box dspdp-spacer circle-image">
                
                    <?php
                    if ($check_couples_mode->setting_status == 'Y') {
                        if ($s_gender == 'C') {
                            ?>

                        <?php if ($s_make_private == 'Y') { ?>

                            <?php if ($current_user->ID != $s_user_id) { ?>

                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                        <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo" />
                                    </a>
                                <?php } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                        <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>"/></a>
                                    <?php
                                }
                            } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                    <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                                </a>
                            <?php } ?>

                        <?php } else { ?>

                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                            </a>
                        <?php } ?>

                    <?php } else { ?>

                        <?php if ($s_make_private == 'Y') { ?>

                            <?php if ($current_user->ID != $s_user_id) { ?>

                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id); ?>" >
                                        <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo"/>
                                    </a>
                                <?php } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id); ?>" >
                                        <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"     border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" /></a>
                                    <?php
                                }
                            } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id); ?>">
                                    <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                                </a>
                            <?php } ?>
                        <?php } else { ?>

                            <a href="<?php echo $root_link . get_username($s_user_id); ?>">
                                <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                            </a>
                        <?php } ?>
                        <?php
                    }
                } else {
                    ?>

                    <?php if ($s_make_private == 'Y') { ?>
                        <?php if ($current_user->ID != $s_user_id) { ?>

                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                <a href="<?php echo $root_link . get_username($s_user_id); ?>" >
                                    <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo" />
                                </a>
                            <?php } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id); ?>" >
                                    <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" /></a>
                                <?php
                            }
                        } else {
                            ?>
                            <a href="<?php echo $root_link . get_username($s_user_id); ?>">
                                <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                            </a>
                        <?php } ?>

                    <?php } else { ?>

                        <a href="<?php echo $root_link . get_username($s_user_id); ?>">
                            <img class="img-circle" src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                        </a>
                    <?php } ?>

                <?php } ?>

            </div>
            <div class="user-status dspdp-h5 dspdp-username img-name">

                <span class="user-name"><strong>

                        <?php
                        if ($check_couples_mode->setting_status == 'Y') {
                            if ($s_gender == 'C') {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" class="dsp-member-title">
                                    <?php
                                    if (strlen($displayed_member_name->display_name) > 15)
                                        echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                    else
                                        echo $displayed_member_name->display_name;
                                    ?>
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id); ?>" class="dsp-member-title">
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
                                    <a href="<?php echo $root_link . get_username($s_user_id); ?>" class="dsp-member-title">
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
            <div class="user-links lm-user-links">
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
                                    <a class="not_logged" title="Login"><span class="fa fa-user"></span></a>
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
                                <a class="not_logged" title="Login"><span class="fa fa-heart"></span></a>
                            <?php } ?>
                        </div>
                    </li>
                    <li class="dspdp-col-xs-3">
                        <div class="dsp_fav_link_border" >
                            <?php
                            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                if (isset($check_my_friends_list) && $check_my_friends_list > 0) {
                                    ?>
                                    <a href="<?php echo $root_link . "email/compose/frnd_id/" . $s_user_id . "/Act/send_msg/"; ?>" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                       <span class="fa fa-envelope-o"></span></a>
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . "email/compose/receive_id/" . $s_user_id . "/"; ?>" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                        <span class="fa fa-envelope-o"></span></a>
                                <?php } //if($check_my_friends_list>0)   ?>
                            <?php } else { ?>
                                <a class="not_logged" title="Login">  <span class="fa fa-envelope-o"></span></a>
                            <?php } ?>
                        </div>
                    </li>
                    <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not ?>
                        <li class="dspdp-col-xs-3">
                            <div class="dsp_fav_link_border">
                                <?php
                                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                    if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                        ?>
                                        <a href='<?php echo $root_link . "view/send_wink_msg/receiver_id/" . $s_user_id . "/"; ?>' title="<?php echo language_code('DSP_SEND_WINK'); ?>">
                                           <span class="fa fa-smile-o"></span></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"> <span class="fa fa-smile-o"></span></a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <a class="not_logged" title="Login">   <span class="fa fa-smile-o"></span></a>
                                <?php } ?>
                            </div>
                        </li>
                    <?php } // END My friends module Activation check condition    ?>
                </ul>
            </div>
        </div>
    </div>
    <?php }
else :
    printf( __( '<div class="lm-members-not-found">%1$s<span class="lm-pagenot-found-pagetitle">%2$s</span>%3$s</div>', 'love-match' ), 'No More','Members', 'Available' );
endif; ?>


<div style="display:none">
    <div id="alert_dialog">
        <p><?php echo language_code('DSP_NOT_LOGGEDIN_MESSAGE'); ?></p>
    </div>
</div>