<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ----------------------------------------------- Start Paging code------------------------------------------------------ //

if (isset($_REQUEST['submit']) || get('submit') || get('searchbysave') || get('basic_search')) { 
    $pieces = $_SERVER["REQUEST_URI"];
    $pieces = explode('/', $pieces);
    $page_index = array_search('page', $pieces);
    $searchTypeIndex = array_search('basic_search',$pieces);
    $searchType = isset($_REQUEST['search_type']) ? esc_sql($_REQUEST['search_type']) : $pieces[$searchTypeIndex + 1];
    //if (get('page')) $page = get('page'); else $page = 1;
    $page = isset($_REQUEST['page']) ? esc_sql($_REQUEST['page']) : $pieces[$page_index + 1];
    if($page_index == false)
        $page=0;
    
    // How many adjacent pages should be shown on each side?
    $adjacents = 2;
    $limit = intval($check_search_result->setting_value)>0?$check_search_result->setting_value : 12;
    if ($page)
        $start = ($page - 1) * $limit;    //first item to display on this page
    else
        $start = 0;
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
    $fetch_record_by_save_search = get('save_search_Id');
    //$dsp_user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
    
    $searchbysave = get('searchbysave');
    if ($fetch_record_by_save_search != "") {
        $search1 = $wpdb->get_row("SELECT * FROM $dsp_user_search_criteria_table Where user_search_criteria_id='$fetch_record_by_save_search'");
        $gender = $search1->user_gender;
        $seeking = $search1->seeking_gender;
        $age_to = $search1->age_to;
        $age_from = $search1->age_from;
        $cmbCountry = $search1->country_id;
        $cmbState = $search1->state_id;
        $cmbCity = $search1->city_id;
        $Pictues_only = $search1->with_pictures;
        $profile_question_option_id = $search1->Profile_questions_option_ids;
    } else {
        $gender = isset($_REQUEST['gender']) ? esc_sql($_REQUEST['gender']) : get('gender');
        $seeking = isset($_REQUEST['seeking']) ? esc_sql($_REQUEST['seeking']) : get('seeking');
        $age_to = isset($_REQUEST['age_to']) ? esc_sql($_REQUEST['age_to']) : get('age_to');
        $age_from = isset($_REQUEST['age_from']) ? esc_sql($_REQUEST['age_from']) : get('age_from');
        $countryName = isset($_REQUEST['cmbCountry']) ? esc_sql($_REQUEST['cmbCountry']) : get('cmbCountry');
        $countryName = urldecode($countryName);
        $username = isset($_REQUEST['username']) ? esc_sql(sanitizeData(trim($_REQUEST['username']), 'xss_clean')) : get('username');
        $lat = isset($_REQUEST['lat']) ? esc_sql(sanitizeData(trim($_REQUEST['lat']), 'xss_clean')) : get('lat');
        $lng = isset($_REQUEST['lng']) ? esc_sql(sanitizeData(trim($_REQUEST['lng']), 'xss_clean')) : get('lng');
        //default for  constant value is in Miles
        $constantValues = isset($_REQUEST['unit']) ? esc_sql(sanitizeData(trim($_REQUEST['unit']), 'xss_clean')) : get('unit');
        $distance = isset($_REQUEST['distance']) ? esc_sql(sanitizeData(trim($_REQUEST['distance']), 'xss_clean')) : get('distance');
        $latlngSet = !empty($lat) && !empty($lng) ? true : false;
        $countryName = urldecode($countryName);
        if(empty($constantValues) || $constantValues == 0){
            $constantValues = 3959;
        }
        $types = ($constantValues == 3959) ? language_code('DSP_MILES') : language_code('DSP_KM');
        /* ------------------------ */
        $kontry = '';
        if (is_numeric($countryName)) {
            $cmbCountryid = (int) $countryName;
            $gc = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE country_id = '" . $cmbCountryid . "'");
            $kontry = isset($gc) ? $gc->name : '';
        } else {
            $kontry = urldecode($countryName);
        }
        /* ----------------------- */
        $username = isset($_REQUEST['username']) ? esc_sql(sanitizeData(trim($_REQUEST['username']), 'xss_clean')) : get('username');

        if (strlen($countryName) > 1) {
            $cmbCountryid = (int) $countryName;
            $get_Country = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE name = '" . $countryName . "'");
            $cmbCountryid = isset($get_Country) ? $get_Country->country_id : '';
        } else {
            $cmbCountryid = 0;
        }
        $stateName = isset($_REQUEST['cmbState']) ? esc_sql(urldecode($_REQUEST['cmbState'])) : get('cmbState'); 
        if ($stateName != "Select" && strlen($stateName) > 1) { 
            $get_State = $wpdb->get_row("SELECT * FROM $dsp_state_table WHERE name = '" .$stateName . "'");
            $cmbStateid = $get_State->state_id;
        } else {
            $cmbStateid = 0;
        }
        $cityName = isset($_REQUEST['cmbCity']) ? esc_sql(urldecode($_REQUEST['cmbCity'])) : get('cmbCity');
        if ($cityName != "Select" && strlen($cityName) > 1) {
            if ($cmbStateid == 0) {
                $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE name = '" . $cityName . "' and country_id=" . $cmbCountryid);
            } else {
                $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE name = '" . $cityName . "' and state_id=" . $cmbStateid);
            }
            $cmbCityid = $get_City->city_id;
        } else {
            $cmbCityid = 0;
        }
        $cmbCountry = is_numeric($countryName)?$countryName:$cmbCountryid;
        $cmbState = is_numeric($stateName)?$stateName:$cmbStateid;
        $cmbCity = is_numeric($cityName)?$cityName:$cmbCityid;

        $Pictues_only = isset($_REQUEST['Pictues_only']) ? esc_sql($_REQUEST['Pictues_only']) : get('Pictues_only');
        if (isset($_REQUEST['Online_only'])) {
            $Online_only = $_REQUEST['Online_only'];
        } else if (get('Online_only')) {
            $Online_only = get('Online_only');
        } else
            $Online_only = 'N';
        $profile_question_option_id1 = (empty($page) && array_key_exists('profile_question_option_id',$_REQUEST))   ? esc_sql($_REQUEST['profile_question_option_id']) : get('profile_question_option_id');
        $totalQuesId = '';
        if (!empty($page) || is_array($profile_question_option_id1)) {
            $profile_question_option_id = implode(",", $profile_question_option_id1);
            $totalQuesId = count($profile_question_option_id1);
        } else {
            $profile_question_option_id = $profile_question_option_id1;
            $totalQuesId = 1;
        }
        
    }
    $search_save = isset($_REQUEST['savesearch']) ? esc_sql(sanitizeData(trim($_REQUEST['savesearch']), 'xss_clean')) : '';
    $check_save = isset($_REQUEST['check_save']) ? esc_sql($_REQUEST['check_save']) : get('check_save');
    $search_type = isset($_REQUEST['search_type']) ? esc_sql($_REQUEST['search_type']) : get('search_type');
    if ($search_save != "" && $check_save == "SS") {
        if ($search_type == "basic_search") {
            $wpdb->query("INSERT INTO $dsp_user_search_criteria_table SET user_id = $current_user->ID,user_gender='$gender',seeking_gender = '$seeking',age_from = '$age_from',age_to = '$age_to',country_id='$cmbCountry',state_id = '$cmbState',city_id ='$cmbCity',username ='$username', online_only='$Online_only',with_pictures='$Pictues_only',Profile_questions_option_ids='0',search_name='$search_save',search_type='$search_type'");
        } else if ($search_type == "advance_search") {
            $wpdb->query("INSERT INTO $dsp_user_search_criteria_table SET user_id = $current_user->ID,user_gender='$gender',seeking_gender='$seeking',age_from='$age_from',age_to='$age_to',country_id='$cmbCountry',state_id = '$cmbState',city_id ='$cmbCity',online_only='$Online_only',with_pictures='$Pictues_only',Profile_questions_option_ids='$profile_question_option_id',search_name='$search_save',search_type='$search_type'");
        }
    }
    $bolIfSearchCriteria = false;
//$strQuery = "SELECT DISTINCT (fb.user_id) FROM $dsp_user_profiles fb WHERE fb.stealth_mode != 'Y' AND ";
    $strQuery = "SELECT  DISTINCT (fb.user_id),(year(CURDATE())-year(fb.age)) age ";
    $strQuery .= !empty($profile_question_option_id) ? " ,COUNT(o.profile_question_option_id) as `atcount`" : "";
    if($latlngSet){
    $strQuery .= ",( $constantValues * acos( cos( radians({$lat}) ) * cos( radians( `lat` ) ) * cos( radians( `lng` ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( `lat`) ) ) ) AS distance  ";
    }
    $strQuery .= " FROM $dsp_user_profiles fb WHERE  ";
    if ($Online_only == "Y") {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= "INNER JOIN $tbl_name AS onl ON fb.user_id = onl.user_id WHERE  ";
        $bolIfSearchCriteria = true;
    }

    // country=1 , state=2,city=3,profile_question_option=4,picture_only=5
    if (($cmbCountry > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'P' || $Pictues_only == '') && ($cmbState <= 0) && ($cmbCity <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    }  //( 1 )

    if (($cmbCountry <= 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'P') && ($cmbState <= 0) && ($cmbCity <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id WHERE o.profile_question_option_id IN($profile_question_option_id) AND ";
        $bolIfSearchCriteria = true;
    }  //( 4 )
    if (($cmbCountry <= 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'Y') && ($cmbState <= 0) && ($cmbCity <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id WHERE p.status_id=1  AND ";
        $bolIfSearchCriteria = true;
    }   //( 5 )
    if (($cmbCountry <= 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'N') && ($cmbState <= 0) && ($cmbCity <= 0)) {
        //$strQuery = substr($strQuery,0,strlen($strQuery)-7);
        $strQuery .= " fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1)  AND ";
        $bolIfSearchCriteria = true;
    }   //( 6 )
    if (($cmbCountry > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'P') && ($cmbCity > 0) && ($cmbState <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 )*/
//--------------------------------------
    if (($cmbCountry > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == '') && ($cmbCity > 0) && ($cmbState <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    }
    if (($cmbCountry > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'P') && ($cmbState > 0) && ($cmbCity <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 )*/
//--------------------------------------
    if (($cmbCountry > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == '') && ($cmbState > 0) && ($cmbCity <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 )*/
//-------------------------------------------
    if (($cmbCountry > 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'P') && ($cmbState <= 0) && ($cmbCity <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id  WHERE (fb.country_id = '" . $cmbCountry . "') AND o.profile_question_option_id IN($profile_question_option_id) AND ";
        $bolIfSearchCriteria = true;
    }  //( 1 & 4 )
    if (($cmbCountry > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'Y') && ($cmbState <= 0) && ($cmbCity <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE p.status_id=1  AND (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 5 )
    if (($cmbCountry > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'N') && ($cmbState <= 0) && ($cmbCity <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE  fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 5 )
    if (($cmbCountry <= 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'Y') && ($cmbState <= 0) && ($cmbCity <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id WHERE  p.status_id=1 AND o.profile_question_option_id IN($profile_question_option_id) AND ";
        $bolIfSearchCriteria = true;
    } //( 4 & 5 )
    if (($cmbCountry <= 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'N') && ($cmbState <= 0) && ($cmbCity <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id  WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND o.profile_question_option_id IN($profile_question_option_id) AND ";
        $bolIfSearchCriteria = true;
    } //( 4 & 5 )
    if (($cmbCountry > 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'Y') && ($cmbState <= 0) && ($cmbCity <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE  p.status_id=1 AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 4 & 5 )
    if (($cmbCountry > 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'N') && ($cmbState <= 0) && ($cmbCity <= 0)) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE  fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 4 & 5 )
    if (($cmbCountry > 0) && ($cmbCity > 0) && ($cmbState <= 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'P')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.city_id = '" . $cmbCity . "')  AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 4 )
    if (($cmbCountry > 0) && ($cmbCity > 0) && ($cmbState <= 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'Y')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE  p.status_id=1 AND (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 5 )
    if (($cmbCountry > 0) && ($cmbCity > 0) && ($cmbState <= 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'N')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 5 )
    if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity <= 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'P')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 4 )
    if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity <= 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'Y')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE  p.status_id=1 AND (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 5 )
    if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity <= 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'N')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 5 )

    if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'P')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id WHERE  (fb.state_id = '" . $cmbState . "') AND (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 3 )
    if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity <= 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'Y')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id WHERE  p.status_id=1 AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 4 & 5 )

    if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity <= 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'N')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 4 & 5 )


    if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'Y')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id WHERE  p.status_id=1 AND (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 3 & 5 )

    if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'N')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 3 & 5 )

    if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'P')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id  INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id WHERE  (fb.city_id = '" . $cmbCity . "') AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 3 & 4 )
    if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'Y')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id WHERE  p.status_id=1 AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "') AND (fb.city_id = '" . $cmbCity . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 3 &4 & 5 )

    if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'N')) {
        $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
        $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id WHERE  fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "') AND (fb.city_id = '" . $cmbCity . "') AND ";
        $bolIfSearchCriteria = true;
    } //( 1 & 2 & 3 &4 & 5 )



    if ($age_from >= 18) {
        //$strQuery .= "  ((year(CURDATE())-year(fb.age)) >= '" . $age_from . "') AND ((year(CURDATE())-year(fb.age)) < '" . $age_to . "') AND ";
        $strQuery .= " ((year(CURDATE())-year(fb.age)) BETWEEN $age_from AND $age_to)  AND ";
        $bolIfSearchCriteria = true;
    }
    /* if (trim(strlen($txtCity)) > 0) {
      $strQuery .= " city like '%" . $txtCity . "%' AND ";
      $bolIfSearchCriteria = true;
      } */
    if (trim(strlen($seeking)) > 0) {
        $strQuery .= " fb.gender='" . $seeking . "' AND ";
        $bolIfSearchCriteria = true;
    }

    if (trim(strlen($gender)) > 0) {
        $strQuery .= " fb.seeking='" . $gender . "' AND ";
        $bolIfSearchCriteria = true;
    }

    if ($Online_only == "Y") {
        $strQuery .= " onl.status='Y' AND ";
        $bolIfSearchCriteria = true;
    }



    $intRecordsPerPage = 10;
    $intStartLimit = isset($_REQUEST['p']) ? esc_sql($_REQUEST['p']) : ''; # page selected 1,2,3,4...
    if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages))
        $intStartLimit = 1; //default
    }
    $intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;
    if ($bolIfSearchCriteria) {
        if (isset($username) && $username != '') {
            $strQuery = "SELECT DISTINCT (fb.user_id) FROM $dsp_user_profiles fb, $dsp_user_table users  WHERE fb.user_id=users.ID AND users.user_login='$username'  ORDER BY fb.user_profile_id desc";
        } else {
            $strQuery = trim(substr($strQuery, 0, strlen($strQuery) - 4)) . "  AND fb.status_id=1 AND fb.country_id > 0  ";
        }
        $strQuery .= !empty($profile_question_option_id) ? " group by fb.user_id HAVING `atcount` = $totalQuesId order by fb.user_id " : "";
        $strQuery .=  (!empty($lat) && !empty($lng) && !empty($distance)) ? " HAVING distance < $distance " : ' ';
        //$strQuery .=  " ORDER BY fb.user_profile_id desc";
        $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
        
    }
    //dsp_debug($wpdb->last_query);die;  
}
   //if (($cmbCountry > 0) && ($profile_question_option_id <= 0) && ($Pictues_only =='P') && ($cmbState <= 0) && ($cmbCity <=0)) {

// ----------------------------------------------- Start Paging code------------------------------------------------------ //
    if ($search_type == "basic_search") { 
        $page_name = $root_link . "search/search_result/basic_search/basic_search/";
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
        if (isset($cmbCountry) && !empty($cmbCountry))
            $page_name.="/cmbCountry/" . $cmbCountry;
        if (isset($cmbState) && !empty($cmbState))
            $page_name.="/cmbState/" . $cmbState;
        if (isset($cmbCity) && !empty($cmbCity))
            $page_name.="/cmbCity/" . $cmbCity;
        if ($Online_only != "")
            $page_name.="/Online_only/" . $Online_only;

        if ($Pictues_only != "")
            $page_name.="/Pictues_only/" . $Pictues_only;
        if ($search_save != "")
            $page_name.="/savesearch/" . $search_save;
        if ($username != "")
            $page_name.="/username/" . $username;
        $page_name.="/submit/Submit/";
    }
    else if ($search_type == "advance_search") {
        $page_name = $root_link . "search/search_result/";
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
        if (isset($cmbCountry) && !empty($cmbCountry))
            $page_name.="/cmbCountry/" . $cmbCountry;
        if (isset($cmbState) && !empty($cmbState))
            $page_name.="/cmbState/" . $cmbState;
        if (isset($cmbCity) && !empty($cmbCity))
            $page_name.="/cmbCity/" . $cmbCity;
        if ($Pictues_only != "")
            $page_name.="/Pictues_only/" . $Pictues_only;
        if ($profile_question_option_id != "")
            $page_name.="/profile_question_option_id/" . $profile_question_option_id;
        if ($search_save != "")
            $page_name.="/savesearch/" . $search_save;
        $page_name.="/submit/Submit/";
    }
    else if ($searchbysave == "save_search") {
        $page_name = $root_link . "search/search_result/";
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
        if (isset($cmbCountry) && !empty($cmbCountry))
            $page_name.="/cmbCountry/" . $cmbCountry;
        if (isset($cmbState) && !empty($cmbState))
            $page_name.="/cmbState/" . $cmbState;
        if (isset($cmbCity) && !empty($cmbCity))
            $page_name.="/cmbCity/" . $cmbCity;
        if ($Pictues_only != "")
            $page_name.="/Pictues_only/" . $Pictues_only;
        if ($profile_question_option_id != "")
            $page_name.="/profile_question_option_id/" . $profile_question_option_id;
        if ($search_save != "")
            $page_name.="/savesearch/" . $search_save;
        $page_name.="/submit/Submit/";
    }else if ($search_type == "distance_search") { 

        $page_name = $root_link . "search/search_result/search_type/distance_search/";
        if ($search_type != "")
            $page_name.="search_type/" . $search_type;
        if ($seeking != "")
            $page_name.="/seeking/" . $seeking;
        if ($age_from != "")
            $page_name.="/age_from/" . $age_from;
        if ($age_to != "")
            $page_name.="/age_to/" . $age_to;
        if (isset($lat) && !empty($lat))
            $page_name.="/lat/" . $lat;
        if (isset($lng) && !empty($lng))
            $page_name.="/lng/" . $lng;
        if (isset($unit) && !empty($unit))
            $page_name.="/unit/" . $unit;
        if ($cmbCountry != "")
            $page_name.="/cmbCountry/" . $cmbCountry;
        $page_name.="/submit/Submit/";

    }else {
        $page_name = $root_link . "search/search_result/";
        
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
        if (isset($cmbCountry) && !empty($cmbCountry))
            $page_name.= "/cmbCountry/" . $cmbCountry;
        if ($username != "")
            $page_name.="/username/" . $username;
        $page_name.="/submit/Submit/";
        //$page_name.="/submit/";
    }
    if (isset($user_count))
        $total_results1 = $user_count;
    else
        $total_results1 = 0;
// Calculate total number of pages. Round up using ceil()
// $total_pages1 = ceil($total_results1 / $max_results1);
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
    if (isset($user_count))
        $intTotalRecordsEffected = $user_count;
    else
        $intTotalRecordsEffected = 0;

    if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
    ?>
    <div id="search_width" class="box-border">
        <div class="box-pedding dspdp-row dsp-row">
            <?php
            //echo $kontry;
            //echo $strQuery ." LIMIT $start, $limit  ";die;
            $search_members = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");
            
            if (isset($username) && $username != '' && !empty($search_members) )
            {
                $search_user_id = $search_members[0]->user_id;
                $search_members = $wpdb->get_results("SELECT *,(year(CURDATE())-year(fb.age)) age  FROM $dsp_user_profiles fb WHERE fb.user_id = '$search_user_id'");
            }
            
            foreach ($search_members as $member1) {
                if ($check_couples_mode->setting_status == 'Y') {
                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'");
                } else {
                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member1->user_id'");
                }
                $s_user_id = $member->user_id;
                $s_country_id = isset($member->country_id) ? $member->country_id : '';
                $s_gender = isset($member->gender) ? $member->gender : '';
                $s_seeking = isset($member->seeking) ? $member->seeking : '';
                $s_state_id = isset($member->state_id) ? $member->state_id : '';
                $s_city_id = isset($member->city_id) ? $member->city_id : '';
                $s_age = isset($member->age) ? GetAge($member->age) : '';
                $s_make_private = isset($member->make_private) ? $member->make_private : '';
                $stealth_mode = isset($member->stealth_mode) ? $member->stealth_mode : '';

                if(  isset($check_distance_mode->setting_status) &&
                     $check_distance_mode->setting_status == 'Y'  && 
                     $search_type == "distance_search" &&
                     $latlngSet
                ) {
                    $s_distance = isset($member1->distance) ? $member1->distance : 0;
                }
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
               <div class="dspdp-col-sm-4 dsp-sm-3"> <div class="dsp-search-result-item box-search-result image-container">
                    <div class="img-box dspdp-spacer">
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
                            ?> </span>
                            <?php
                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($s_gender == 'C') {
                                    ?>

                                <?php if ($s_make_private == 'Y') { ?>

                                    <?php if ($current_user->ID != $s_user_id) { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"   border="0" class="img-big" alt="Private Photo"/>
                                            </a>
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"     border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                            <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                        </a>
                                    <?php } ?>

                                <?php } else { ?>

                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                        <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
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
                                                <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"      border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                            <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                        </a>
                                    <?php } ?>
                                <?php } else { ?>

                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                        <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/>
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
                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"   border="0" class="img-big" alt="Private Photo"/>
                                        </a>
                                    <?php } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                            <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"     border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                        <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                    </a>
                                <?php } ?>

                            <?php } else { ?>

                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                    <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                </a>
                            <?php } ?>

                        <?php } ?>

                    </div>
                    <div class="user-status">

                        <span class="user-name dspdp-h5 dspdp-username"><strong>

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
                    <div class="user-details dspdp-spacer dspdp-user-details">
                        <?php echo $s_age ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?> <?php echo get_gender($s_gender); ?> <?php echo language_code('DSP_FROM_TEXT'); ?> <br /><?php if (@$city_name->name != "") echo @$city_name->name . ','; ?> <?php if (@$state_name->name != "") echo @$state_name->name . ','; ?> <?php echo @$country_name->name; ?>
                    </div>
                    <?php if(   isset($check_distance_mode->setting_status) && 
                                $check_distance_mode->setting_status == 'Y' &&
                                $search_type == "distance_search"  &&
                                $latlngSet
                            ):
                     ?>
                        <div class="user-details dspdp-spacer dspdp-user-details">
                           <?php echo language_code('DSP_SELECT_DISTANCE') . ':' . number_format($s_distance,2) . " {$types}"; ?> 
                        </div>
                    <?php endif; ?>
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
                                                    <span class="fa fa-plus-square"></span></a>
                                            <?php } else { ?>
                                                <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"><span class="fa fa-user"></span></a>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><span class="fa fa-user"></span></a>
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
                                            <a href="<?php echo $root_link . "email/compose/frnd_id/" . $member_id . "/Act/send_msg/"; ?>"  title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                    <span class="fa fa-envelope-o"></span>
                                            </a>
                                        <?php } else { ?>
                                            <a  href="<?php echo $root_link . "email/compose/receive_id/" . $s_user_id . "/"; ?>"  title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                <span class="fa fa-envelope-o"></span>
                                            </a>
                                        <?php } //if($check_my_friends_list>0)     ?>
                                    <?php } else { ?>
                                        <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login">  <span class="fa fa-envelope-o"></span></a>
                                    <?php } ?>
                                </div>
                            </li>
                            <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not    ?>
                                <li class="dspdp-col-xs-3">
                                    <div class="dsp_fav_link_border">
                                        <?php
                                        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                            if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                                ?>
                                                <a href='<?php echo $root_link . "view/send_wink_msg/receiver_id/" . $s_user_id . "/"; ?>' title="<?php echo language_code('DSP_SEND_WINK'); ?>">
                                                    <span class="fa fa-smile-o"></span></a>
                                            <?php } else { ?>
                                                <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"><img src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" alt="Wink"/></a>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login">  <span class="fa fa-smile-o"></span></a>
                                        <?php } ?>
                                    </div>
                                </li>
                            <?php } // END My friends module Activation check condition    ?>
                        </ul>
                    </div>
                </div></div>
                <?php
            }// foreach($search_members as $member)
            ?>
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
        <div class="box-border">
            <div class="box-pedding">
                <div class="page-not-found">
                    <?php echo language_code('DSP_NO_RECORD_FOUND'); ?><br /><br />
                    <span><a href="<?php echo $root_link . "search/$searchType/"; ?>"><?php echo language_code('DSP_START_NEW_SEARCH'); ?></a></span>
                </div>
            </div>
        </div>
    <?php } ?>
