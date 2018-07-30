<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<script>

    dsp = jQuery.noConflict();

    dsp(document).ready(function() {
        dsp(".not_logged").click(function() {
            dsp("#alert_dialog").dialog();
        });

    });
</script>
    <div class="dsp-page-title">
        <h1><?php echo language_code('DSP_SEARCH_RESULT'); ?></h1>
    </div>
<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */


// ----------------------------------------------- Start Paging code------------------------------------------------------ //
        if (get('page1') != "")
            $page = get('page1');
        else
            $page = 1;

        /*$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $force_profile= $wpdb->get_results("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'force_profile' ");
        if ($force_profile != null)
        {
            $force_profile=$force_profile[0]->setting_status;
            
            if( !is_user_logged_in() && $force_profile=='Y' )
            {
               $redirectUrl = ($check_register_page_redirect_mode->setting_status == 'Y') ? 
                               $check_register_page_redirect_mode->setting_value :
                               get_home_url();
               echo '<script>window.location="'. $redirectUrl .'";</script>';
               die;
            }
        }*/


        // How many adjacent pages should be shown on each side?
        $adjacents = 2;
        $limit = intval($check_search_result->setting_value)>0?$check_search_result->setting_value : 12;
        if ($page)
            $start = ($page - 1) * $limit;    //first item to display on this page
        else
            $start = 0;
        // ----------------------------------------------- End Paging code------------------------------------------------------ //
        $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : get('gender');
        $seeking = isset($_REQUEST['seeking']) ? $_REQUEST['seeking'] : get('seeking');
        $age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : get('age_to');
        $age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : get('age_from');
        $Pictues_only = isset($_REQUEST['Pictues_only']) ? $_REQUEST['Pictues_only'] : get('Pictues_only');
        $Online_only = isset($_REQUEST['Online_only']) ? $_REQUEST['Online_only'] : get('Online_only');
        $search_save = isset($_REQUEST['savesearch']) ? $_REQUEST['savesearch'] : get('savesearch');
        $check_save = isset($_REQUEST['check_save']) ? $_REQUEST['check_save'] : get('check_save');
        $search_type = isset($_REQUEST['search_type']) ? $_REQUEST['search_type'] : get('search_type');
        $countryName = isset($_REQUEST['cmbCountry']) ? $_REQUEST['cmbCountry'] : get('cmbCountry');
        $lat = isset($_REQUEST['lat']) ? esc_sql(sanitizeData(trim($_REQUEST['lat']), 'xss_clean')) : get('lat');
        $lng = isset($_REQUEST['lng']) ? esc_sql(sanitizeData(trim($_REQUEST['lng']), 'xss_clean')) : get('lng');
        //default for  constant value is in Miles
        $constantValues = isset($_REQUEST['unit']) ? esc_sql(sanitizeData(trim($_REQUEST['unit']), 'xss_clean')) : get('unit');
        $distance = isset($_REQUEST['distance']) ? esc_sql(sanitizeData(trim($_REQUEST['distance']), 'xss_clean')) : get('distance');
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
            $cmbStateid = isset($get_State->state_id) ? $get_State->state_id: '';
            
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
            $cmbCityid = isset($get_City->city_id)? $get_City->city_id : '';
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
        // Calculate total number of pages. Round up using ceil()
        //$total_pages1 = ceil($total_results1 / $max_results1);
        //******************************************************************************************************************************************

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
                $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "/page1/$prev\">".language_code('DSP_PREVIOUS')."</a></div>";
            else
                $pagination.= "<span  class='disabled'>".language_code('DSP_PREVIOUS')."</span>";

            //pages
            if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class='current'>$counter</span>";
                    else
                        $pagination.= "<div><a href=\"" . $page_name . "/page1/$counter\">$counter</a></div>";
                }
            }
            elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
                //close to beginning; only hide later pages
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<div><a href=\"" . $page_name . "/page1/$counter\">$counter</a></div>";
                    }
                    $pagination.= "<span>...</span>";
                    $pagination.= "<div><a href=\"" . $page_name . "/page1/$lpm1\">$lpm1</a></div>";
                    $pagination.= "<div><a href=\"" . $page_name . "/page1/$lastpage\">$lastpage</a></div>";
                }
                //in middle; hide some front and some back
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination.= "<div><a href=\"" . $page_name . "/page1/1\">1</a></div>";
                    $pagination.= "<div><a href=\"" . $page_name . "/page1/2\">2</a></div>";
                    $pagination.= "<span>...</span>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination.= "<div class='current'>$counter</div>";
                        else
                            $pagination.= "<div><a href=\"" . $page_name . "/page1/$counter\">$counter</a></div>";
                    }
                    $pagination.= "<span>...</span>";
                    $pagination.= "<div><a href=\"" . $page_name . "/page1/$lpm1\">$lpm1</a></div>";
                    $pagination.= "<div><a href=\"" . $page_name . "/page1/$lastpage\">$lastpage</a></div>";
                }
                //close to end; only hide early pages
                else {
                    $pagination.= "<div><a href=\"" . $page_name . "/page1/1\">1</a></div>";
                    $pagination.= "<div><a href=\"" . $page_name . "/page1/2\">2</a></div>";
                    $pagination.= "<span>...</span>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<div><a href=\"" . $page_name . "/page1/$counter\">$counter</a></div>";
                    }
                }
            }

            //next button
            if ($page < $counter - 1)
                $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "/page1/$next\">".language_code('DSP_NEXT')."</a></div>";
            else
                $pagination.= "<span class='disabled'>".language_code('DSP_NEXT')."</span>";
            $pagination.= "</div>\n";
        }

//******************************************************************************************************************************************
// ------------------------------------------------End Paging code------------------------------------------------------ //

        $intTotalRecordsEffected = $user_count;
        if ($intTotalRecordsEffected != '0') {
            //print "Total records found: " . $intTotalRecordsEffected;
        } else {
            ?>
    <div class="dsp_search_result_box_out">
        <div class="dsp_search_result_box_in">
            <div class="box-page">
                <div class="page-not-found">
                    <?php echo language_code('DSP_NO_RECORD_FOUND'); ?><br /><br />
                    <span><a href="<?php echo $root_link . "guest_search" ?>"><?php echo language_code('DSP_START_NEW_SEARCH'); ?></a></span>
                </div>
            </div>
        </div>
    </div>
    <?php
} // if ($intTotalRecordsEffected != '0')
//echo $strQuery ." LIMIT $start, $limit";die;
$search_members = $wpdb->get_results($strQuery . " LIMIT $start, $limit");
?>
<div class="dsp_search_result_box_out">
    <div class="dsp_search_result_box_in">
        <div class="dsp-row">
        <?php
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
            $online =  ($stealth_mode == 'N') ? " dspdp-status-on " : " dspdp-status-off "; 
            ?>
            <div class="dspdp-col-sm-4 dsp-sm-3 dsp-home-member"><div class="box-search-result image-container">
                <div class="img-box dspdp-spacer circle-image">
                    <span class="online dspdp-online-status">
                        <!-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
                        <?php
                            $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_online_user_table WHERE user_id=$s_user_id");
                            //echo $fav_icon_image_path;
                            if ($check_online_user > 0)
                                echo '<span class=" '. $online .' " '.language_code('DSP_CHAT_ONLINE').'></span>';
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
                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo" />
                                        </a>
                                    <?php } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>"/></a>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                                    </a>
                                <?php } ?>

                            <?php } else { ?>

                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                                </a>
                            <?php } ?>

                        <?php } else { ?>

                            <?php if ($s_make_private == 'Y') { ?>

                                <?php if ($current_user->ID != $s_user_id) { ?>

                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id); ?>" >
                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo"/>
                                        </a>
                                    <?php } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id); ?>" >
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"     border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" /></a>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id); ?>">
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                                    </a>
                                <?php } ?>
                            <?php } else { ?>

                                <a href="<?php echo $root_link . get_username($s_user_id); ?>">
                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
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
                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo" />
                                    </a>
                                <?php } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id); ?>" >
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" /></a>
                                    <?php
                                }
                            } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($s_user_id); ?>">
                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                                </a>
                            <?php } ?>

                        <?php } else { ?>

                            <a href="<?php echo $root_link . get_username($s_user_id); ?>">
                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id);?>" />
                            </a>
                        <?php } ?>

                    <?php } ?>

                </div>
                <div class="user-status dspdp-h5 dspdp-username">

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
        <?php } // foreach($search_members as $member) 
        ?>
        </div>
    </div></div>
    <div class="row-paging">
        <div style="float:left; width:100%;">
            <?php
    // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
            echo $pagination
    // -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
            ?>
        </div>
    </div>
<div style="display:none">
    <div id="alert_dialog">
        <p><?php echo language_code('DSP_NOT_LOGGEDIN_MESSAGE'); ?></p>
    </div>
</div>