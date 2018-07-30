<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
$DSP_USER_ONLINE_TABLE = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
// ----------------------------------------------- Start Paging code------------------------------------------------------ //  
if (isset($_GET['page1']))
    $page1 = $_GET['page1'];
else
    $page1 = 1;
$max_results1 = DSP_PAGINATION_LIMIT;
$adjacents = DSP_PAGINATION_ADJACENTS;
$limit = $max_results1;
$from1 = (($page1 * $max_results1) - $max_results1);
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
if (isset($_REQUEST['save_search_Id'])) {
    $fetch_record_by_save_search = $_REQUEST['save_search_Id'];
} else {
    $fetch_record_by_save_search = "";
}
if (isset($_REQUEST['searchbysave'])) {
    $searchbysave = $_REQUEST['searchbysave'];
} else {
    $searchbysave = "";
}
if ($fetch_record_by_save_search != "") {
    $getSavedSearchQuery = "SELECT * FROM $dsp_user_search_criteria_table Where user_search_criteria_id='$fetch_record_by_save_search'";
    $search1 = $wpdb->get_row($getSavedSearchQuery);
//echo $getSavedSearchQuery;
    $gender = $search1->user_gender;
    $seeking = $search1->seeking_gender;
    $age_to = $search1->age_to;
    $age_from = $search1->age_from;
    $cmbCountry = $search1->country_id;
//echo '<br>com'.$cmbCountry;
    $cmbState = $search1->state_id;
    $cmbCity = $search1->city_id;
    $Pictues_only = $search1->with_pictures;
    if ($Pictues_only == '') {
        $Pictues_only = "P";
    }
    $profile_question_option_id = $search1->Profile_questions_option_ids;
    $Online_only = $search1->online_only;
} else {
    $gender = $_REQUEST['gender'];
    $seeking = $_REQUEST['seeking'];
    $age_to = $_REQUEST['age_to'];
    $age_from = $_REQUEST['age_from'];
    $countryName = $_REQUEST['cmbCountry'];
    if (strlen($countryName) > 1) {
        $get_Country = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE name = '" . $countryName . "'");
        $cmbCountryid = $get_Country->country_id;
    } else {
        $cmbCountryid = 0;
    }
    if (isset($_REQUEST['cmbState'])) {
        $stateName = $_REQUEST['cmbState'];
        if ($stateName != "Select" && strlen($stateName) > 1) {

            $get_State = $wpdb->get_row("SELECT * FROM $dsp_state_table WHERE name = '" . $stateName . "'");

            $cmbStateid = $get_State->state_id;
        } else {

            $cmbStateid = 0;
        }
    } else {
        $stateName = "";
        $cmbStateid = 0;
    }
    if (isset($_REQUEST['cmbState'])) {
        if ($_REQUEST['cmbState'] != "Select") {
            if (isset($_REQUEST['cmbCity'])) {
                $cityName = $_REQUEST['cmbCity'];

                if ($cityName != "Select" && strlen($cityName) > 1) {

                    $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE name = '" . $cityName . "'");

                    $cmbCityid = $get_City->city_id;
                } else {

                    $cmbCityid = 0;
                }
            } else {
                $cmbCityid = 0;
            }
        } else {
            $cmbCityid = 0;
            $cityName = "";
            $cmbStateid = 0;
            $stateName = "";
        }
    } else {
        $cmbCityid = 0;
        $cityName = "";
        $cmbStateid = 0;
        $stateName = "";
    }
    $cmbCountry = $cmbCountryid;
    $cmbState = $cmbStateid;
    $cmbCity = $cmbCityid;
    if (isset($_REQUEST['Pictues_only'])) {
        $Pictues_only = $_REQUEST['Pictues_only'];
    } else {
        $Pictues_only = 'P';
    }
    if (isset($_REQUEST['Online_only'])) {
        $Online_only = $_REQUEST['Online_only'];
    } else {
        $Online_only = 'N';
    }
    if (isset($_REQUEST['profile_question_option_id'])) {
        $profile_question_option_id1 = $_REQUEST['profile_question_option_id'];
    } else {
        $profile_question_option_id1 = "";
    }
    if ($profile_question_option_id1 != "") {
        $profile_question_option_id = implode(",", $profile_question_option_id1);
    } else {
        $profile_question_option_id = 0;
    }
}
if (isset($_REQUEST['savesearch'])) {
    $search_save = $_REQUEST['savesearch'];
} else {
    $search_save = "";
}
if (isset($_REQUEST['check_save'])) {
    $check_save = $_REQUEST['check_save'];
} else {
    $check_save = "";
}
if (isset($_REQUEST['search_type'])) {
    $search_type = $_REQUEST['search_type'];
} else {
    $search_type = "";
}
if (isset($_REQUEST['display_type'])) {
    $display_type = $_REQUEST['display_type'];
} else {
    $display_type = "";
}
if ($search_save != "" && $check_save == "SS") {
    if ($search_type == "basic") {
        $wpdb->query("INSERT INTO $dsp_user_search_criteria_table SET user_id = $current_user->ID,user_gender='$gender',seeking_gender = '$seeking',age_from = '$age_from',age_to = '$age_to',country_id='$cmbCountry',state_id = '$cmbState',city_id ='$cmbCity',online_only='',with_pictures='$Pictues_only',Profile_questions_option_ids='0',search_name='$search_save',search_type='$search_type'");
    } else if ($search_type == "Advanced") {
        $wpdb->query("INSERT INTO $dsp_user_search_criteria_table SET user_id = $current_user->ID,user_gender='$gender',seeking_gender='$seeking',age_from='$age_from',age_to='$age_to',country_id='$cmbCountry',state_id = '$cmbState',city_id ='$cmbCity',online_only='',with_pictures='$Pictues_only',Profile_questions_option_ids='$profile_question_option_id',search_name='$search_save',search_type='$search_type'");
    }
}
$bolIfSearchCriteria = false;
$strQuery = "SELECT DISTINCT (fb.user_id) FROM $dsp_user_profiles fb WHERE ";


// country=1 , state=2,city=3,profile_question_option=4,picture_only=5
if (($cmbCountry > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'P') && ($cmbState <= 0) && ($cmbCity <= 0)) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id  ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE (fb.country_id = '" . $cmbCountry . "') AND  oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE (fb.country_id = '" . $cmbCountry . "') AND ";
    }
    $bolIfSearchCriteria = true;
}  //( 1 )

if (($cmbCountry <= 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'P') && ($cmbState <= 0) && ($cmbCity <= 0)) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id WHERE o.profile_question_option_id IN($profile_question_option_id) AND ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
						WHERE o.profile_question_option_id IN($profile_question_option_id) AND   oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE o.profile_question_option_id IN($profile_question_option_id) AND  ";
    }
    $bolIfSearchCriteria = true;
}  //( 4 )
if (($cmbCountry <= 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'Y') && ($cmbState <= 0) && ($cmbCity <= 0)) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id	";

    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
						WHERE p.status_id=1 AND   oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE p.status_id=1 AND  ";
    }
    $bolIfSearchCriteria = true;
}   //( 5 )
if (($cmbCountry <= 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'N') && ($cmbState <= 0) && ($cmbCity <= 0)) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    if ($Online_only == 'Y') {

        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
						WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1)  AND    oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1)  AND   ";
    }
    //$strQuery .= " fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1)  AND ";

    $bolIfSearchCriteria = true;
}   //( 6 )
if (($cmbCountry <= 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'P') && ($cmbState <= 0) && ($cmbCity <= 0)) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    if ($Online_only == 'Y') {

        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
						WHERE  oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE ";
    }
    //$strQuery .= " fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1)  AND ";

    $bolIfSearchCriteria = true;
}   //( 6 )
if (($cmbCountry > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'P') && ($cmbState > 0) && ($cmbCity <= 0)) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
						WHERE (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND   ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 2 )*/	
if (($cmbCountry > 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'P') && ($cmbState <= 0) && ($cmbCity <= 0)) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id 
    			   INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id  ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
						WHERE (fb.country_id = '" . $cmbCountry . "') AND o.profile_question_option_id IN($profile_question_option_id) AND 
						oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE (fb.country_id = '" . $cmbCountry . "') AND o.profile_question_option_id IN($profile_question_option_id) AND   ";
    }
    $bolIfSearchCriteria = true;
}  //( 1 & 4 )
if (($cmbCountry > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'Y') && ($cmbState <= 0) && ($cmbCity <= 0)) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
						WHERE p.status_id=1  AND (fb.country_id = '" . $cmbCountry . "')  AND oln.status = 'Y' AND ";
    } else {
        $strQuery .=" p.status_id=1  AND (fb.country_id = '" . $cmbCountry . "')  AND  ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 5 )
if (($cmbCountry > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'N') && ($cmbState <= 0) && ($cmbCity <= 0)) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id	";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
						WHERE  fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.country_id = '" . $cmbCountry . "') AND  oln.status = 'Y' AND  ";
    } else {
        $strQuery .=" WHERE  fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.country_id = '" . $cmbCountry . "') AND  ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 5 )
if (($cmbCountry <= 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'Y') && ($cmbState <= 0) && ($cmbCity <= 0)) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id 
    			   INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id ";

    if ($Online_only == 'Y') {
        $strQuery .=" INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
    			   WHERE  p.status_id=1 AND o.profile_question_option_id IN($profile_question_option_id) AND  oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE  p.status_id=1 AND o.profile_question_option_id IN($profile_question_option_id) AND ";
    }
    $bolIfSearchCriteria = true;
} //( 4 & 5 )
if (($cmbCountry <= 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'N') && ($cmbState <= 0) && ($cmbCity <= 0)) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id  ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE p.status_id=1) AND o.profile_question_option_id IN($profile_question_option_id) AND  oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE p.status_id=1) AND o.profile_question_option_id IN($profile_question_option_id) AND ";
    }

    $bolIfSearchCriteria = true;
} //( 4 & 5 )
if (($cmbCountry > 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'Y') && ($cmbState <= 0) && ($cmbCity <= 0)) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id 
    				INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id  
    				INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id ";

    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE  p.status_id=1 AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND   oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE  p.status_id=1 AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND  ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 4 & 5 )
if (($cmbCountry > 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'N') && ($cmbState <= 0) && ($cmbCity <= 0)) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id 
    			  INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE  fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND   oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE  fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND   ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 4 & 5 )
if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity <= 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'P')) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id  
    			   INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  AND    oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  AND ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 2 & 4 )
if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity <= 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'Y')) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id 
    			   INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id 
    			   INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE  p.status_id=1 AND (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE  p.status_id=1 AND (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND  ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 2 & 5 )
if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity <= 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'N')) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id 
    			   INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') 
					AND  oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.state_id = '" . $cmbState . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 2 & 5 )

if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'P')) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id 
    			   INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id 
    			   INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id  ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE  (fb.state_id = '" . $cmbState . "') AND (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "')  
					AND  oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE  (fb.state_id = '" . $cmbState . "') AND (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 2 & 3 )
if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity <= 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'Y')) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id 
                   INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id  
                   INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id 
                   INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE  p.status_id=1 AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')   
					AND  oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE  p.status_id=1 AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  AND  ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 2 & 4 & 5 )

if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity <= 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'N')) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id 
                   INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id
                   INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')    
					AND  oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  AND   ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 2 & 4 & 5 )


if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'Y')) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id 
                   INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id 
                   INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id 
                   INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE  p.status_id=1 AND (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')      
					AND  oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE  p.status_id=1 AND (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  AND ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 2 & 3 & 5 )

if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($profile_question_option_id <= 0) && ($Pictues_only == 'N')) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id 
                   INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id 
                   INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  
					AND  oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND (fb.city_id = '" . $cmbCity . "') AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  AND ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 2 & 3 & 5 )

if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'P')) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id  
    	           INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id 
    	           INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id 
    	           INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE  (fb.city_id = '" . $cmbCity . "') AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "') 
					AND   oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE  (fb.city_id = '" . $cmbCity . "') AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "')  AND ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 2 & 3 & 4 )
if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'Y')) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id
                   INNER JOIN $dsp_members_photos p ON fb.user_id = p.user_id 
                   INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id
                   INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id 
                   INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					WHERE  p.status_id=1 AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "') AND (fb.city_id = '" . $cmbCity . "') 
					AND    oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE  p.status_id=1 AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "') AND (fb.city_id = '" . $cmbCity . "') AND ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 2 & 3 &4 & 5 )

if (($cmbCountry > 0) && ($cmbState > 0) && ($cmbCity > 0) && ($profile_question_option_id > 0) && ($Pictues_only == 'N')) {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= " INNER JOIN $dsp_question_details o ON fb.user_id = o.user_id 
                   INNER JOIN $dsp_country_table c ON fb.country_id = c.country_id 
                   INNER JOIN $dsp_state_table s ON fb.state_id = s.state_id
                   INNER JOIN $dsp_city_table ct ON fb.city_id = ct.city_id ";
    if ($Online_only == 'Y') {
        $strQuery .= "  INNER JOIN $DSP_USER_ONLINE_TABLE as oln ON(fb.user_id=oln.user_id) 
					   WHERE  fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "') AND (fb.city_id = '" . $cmbCity . "')
					  AND     oln.status = 'Y' AND ";
    } else {
        $strQuery .=" WHERE  fb.user_id NOT IN (SELECT p.user_id FROM $dsp_members_photos p WHERE p.status_id=1) AND o.profile_question_option_id IN($profile_question_option_id) AND (fb.country_id = '" . $cmbCountry . "') AND (fb.state_id = '" . $cmbState . "') AND (fb.city_id = '" . $cmbCity . "') AND ";
    }
    $bolIfSearchCriteria = true;
} //( 1 & 2 & 3 &4 & 5 )	



if ($age_from >= 18) {
    $strQuery .= "  ((year(CURDATE())-year(age)) > '" . $age_from . "') AND ((year(CURDATE())-year(age)) < '" . $age_to . "') AND ";
    $bolIfSearchCriteria = true;
}
/* if (trim(strlen($txtCity)) > 0) {
  $strQuery .= " city like '%" . $txtCity . "%' AND ";
  $bolIfSearchCriteria = true;
  } */
if (trim(strlen($gender)) > 0) {
    $strQuery .= " gender='" . $seeking . "' AND ";
    $bolIfSearchCriteria = true;
}
if (trim(strlen($seeking)) > 0) {
    $strQuery .= " seeking='" . $gender . "' AND ";
    $bolIfSearchCriteria = true;
}
//----------------------code for zip code (Location Search )--------------------------------------
if (isset($_REQUEST['zip_code'])) {
    $zipcodeSet = $_REQUEST['zip_code'];
} else {
    $zipcodeSet = "";
}

if (isset($_REQUEST['miles'])) {
    $milesSet = $_REQUEST['miles'];
} else {
    $milesSet = "";
}

if ($zipcodeSet != "" && $milesSet != "") {
    $zipcode = $_REQUEST['zip_code'];

    $miles = $_REQUEST['miles'];

    $bolIfSearchCriteria = true;
    $dsp_zipcode_table = $wpdb->prefix . DSP_ZIPCODES_TABLE;
    //echo "SELECT * FROM $dsp_zipcode_table WHERE zipcode = '$zipcode'<br>";
    $findzipcodelatlng = $wpdb->get_row("SELECT * FROM $dsp_zipcode_table WHERE zipcode = '$zipcode'");
    $lat1 = $findzipcodelatlng->latitude;
    $lon1 = $findzipcodelatlng->longitude;
    $d = $miles;
    $r = 3959;
    //compute max and min latitudes / longitudes for search square
    $latN = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(0))));
    $latS = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(180))));
    $lonE = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(90)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
    $lonW = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(270)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));

    $findzipcodes = "SELECT zipcode FROM $dsp_zipcode_table WHERE (latitude <= $latN AND latitude >= $latS AND longitude <= $lonE AND longitude >= $lonW) AND (latitude != $lat1 AND longitude != $lon1) ";
    //echo $findzipcodes;
    $findallzipcodes = $wpdb->get_results($findzipcodes);

    foreach ($findallzipcodes as $allzipcodes) {
        $searchzipcodes[] = $allzipcodes->zipcode;
    }

    if ($searchzipcodes != "") {
        $searchzipcodes1 = implode(",", $searchzipcodes);
    }

    $strQuery .= "  fb.zipcode IN($searchzipcodes1)  AND ";
    $bolIfSearchCriteria = true;
}
//---------------------------------------- End of zip search -----------------------------------------------------------------

$intRecordsPerPage = 10;

if (isset($_GET['p'])) {
    $intStartLimit = $_GET['p']; # page selected 1,2,3,4...
} else {
    $intStartLimit = ""; # page selected 1,2,3,4...
}

if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
    $intStartLimit = 1; //default
}
$intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;
if ($bolIfSearchCriteria) {
    $strQuery = trim(substr($strQuery, 0, strlen($strQuery) - 4)) . "  AND fb.status_id=1 AND fb.stealth_mode='N' ORDER BY fb.user_profile_id desc";
    $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
}
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
if ($search_type == "basic") {
//$page_name=$root_link."&pid=5&pagetitle=search_result&basic_search=basic_search&gender=".$gender."&seeking=".$seeking."&age_from=".$age_from."&age_to=".$age_to."&cmbCountry=".$cmbCountry."&cmbState=".$cmbState."&cmbCity=".$cmbCity."&Online_only=".$Online_only."&Pictues_only=".$Pictues_only."&search_type=".$search_type."&display_type=".$display_type."&savesearch=".$search_save."&submit=Submit";
    $page_name = $root_link . "?pid=5&pagetitle=search_result&basic_search=basic_search&gender=" . $gender . "&seeking=" . $seeking . "&age_from=" . $age_from . "&age_to=" . $age_to . "&cmbCountry=" . $cmbCountry . "&cmbState=" . $cmbState . "&cmbCity=" . $cmbCity . "&Online_only=" . $Online_only . "&Pictues_only=" . $Pictues_only . "&search_type=" . $search_type . "&display_type=" . $display_type . "&savesearch=" . $search_save . "&submit=Submit";
} else if ($searchbysave == "save_search") {
    $page_name = $root_link . "?pid=5&pagetitle=search_result&gender=" . $gender . "&seeking=" . $seeking . "&age_from=" . $age_from . "&age_to=" . $age_to . "&cmbCountry=" . $cmbCountry . "&cmbState=" . $cmbState . "&cmbCity=" . $cmbCity . "&Pictues_only=" . $Pictues_only . "&profile_question_option_id[]=" . $profile_question_option_id . "&search_type=" . $search_type . "&savesearch=" . $search_save . "&submit=Submit";
} else {
//$page_name=$root_link."&pid=5&pagetitle=search_result&gender=".$gender."&seeking=".$seeking."&age_from=".$age_from."&age_to=".$age_to."&cmbCountry=".$cmbCountry."&submit=Search";
    $page_name = $root_link . "?pid=5&pagetitle=search_result&gender=" . $gender . "&seeking=" . $seeking . "&age_from=" . $age_from . "&age_to=" . $age_to . "&cmbCountry=" . $cmbCountry . "&submit=Search";
}
$total_results1 = $user_count;
$total_pages1 = $user_count;
// ------------------------------------------------End Paging code------------------------------------------------------ // 
$intTotalRecordsEffected = $user_count;
if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
    //print "Total records found: " . $intTotalRecordsEffected;
} else {
    ?>
    <div>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr align="center">
                <td colspan="5">&nbsp;</td>
            </tr>
            <tr align="center">
                <td colspan="9"><?php echo language_code('DSP_NO_RECORD_FOUND'); ?><br><br><?php if (isset($if_record_not_found)) echo $if_record_not_found; ?></td>
            </tr>
            <tr><td></td></tr>
            <tr><td align="center">
                    <?php
                    if (is_user_logged_in()) {
                        ?>
                        <a href="<?php echo $root_link ?>?pid=5&pagetitle=basic_search"><?php echo language_code('DSP_START_NEW_SEARCH'); ?></a></td></tr>
                <?php
            } else {
                ?>
                <a href="<?php echo $root_link ?>?pgurl=guest_search"><?php echo language_code('DSP_START_NEW_SEARCH'); ?></a></td></tr>
                <?php
            }
            ?>
        </table>

    </div>
    <?php
} // if ($intTotalRecordsEffected != '0')	
//echo $strQuery.'<br>';
$search_members = $wpdb->get_results($strQuery . " LIMIT " . $from1 . "," . $max_results1);
//echo $strQuery ." LIMIT " . $from1 . "," . $max_results1;
foreach ($search_members as $member1) {
    //echo "SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'";
    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'");
    $s_user_id = $member->user_id;
    $s_country_id = $member->country_id;
    $s_gender = $member->gender;
    $s_seeking = $member->seeking;
    $s_state_id = $member->state_id;
    $s_city_id = $member->city_id;
    $s_make_private = $member->make_private;
    $s_age = GetAge($member->age);
//$s_user_pic = $member->user_pic;
    $displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$s_user_id'");
    $country_name = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$s_country_id");
    $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$s_state_id");
    $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$s_city_id");
    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");
    $favt_mem = array();
    foreach ($private_mem as $private) {
        $favt_mem[] = $private->favourite_user_id;
    }
//echo "SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'";
//print_r($favt_mem);
    ?>
    <div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="search_table">
            <tr>
                <td width="35%">

                    <?php
                    if ($check_couples_mode->setting_status == 'Y') {
                        if ($s_gender == 'C') {
                            if ($s_make_private == 'Y') {
                                if ($current_user->ID != $s_user_id) {
                                    if (!in_array($current_user->ID, $favt_mem)) {
                                        ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                            'pagetitle' => 'view_profile'), $root_link);
                                        ?>" >

                                            <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg"  style="width:100px; height:100px;" border="3" class="img" />

                                        </a>       	<?php
                                    } else {
                                        ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                            'pagetitle' => 'view_profile'), $root_link);
                                        ?>" >				

                                            <img src="<?php echo display_members_photo_mb($s_user_id, $image_path); ?>"    style="width:100px; height:100px;" border="3" class="img"/></a>                
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a href="<?php
                                    echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                        'pagetitle' => 'view_profile'), $root_link);
                                    ?>">

                                        <img src="<?php echo display_members_photo_mb($s_user_id, $image_path); ?>" style="width:100px; height:100px;" border="3" class="img" />

                                    </a>
                                    <?php
                                }
                            } else {
                                ?>
                                <a href="<?php
                                echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                    'pagetitle' => 'view_profile'), $root_link);
                                ?>">
                                    <img src="<?php echo display_members_photo_mb($s_user_id, $image_path); ?>" style="width:100px; height:100px;" border="3" class="img" />
                                </a>
                                <?php
                            }
                        } else {
                            if ($s_make_private == 'Y') {
                                if ($current_user->ID != $s_user_id) {


                                    if (!in_array($current_user->ID, $favt_mem)) {
                                        ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                            'pagetitle' => 'view_profile'), $root_link);
                                        ?>" >

                                            <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg"  style="width:100px; height:100px;" border="3" class="img" />

                                        </a>                
                                        <?php
                                    } else {
                                        ?>

                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                            'pagetitle' => 'view_profile'), $root_link);
                                        ?>" >				

                                            <img src="<?php echo display_members_photo_mb($s_user_id, $image_path); ?>"     style="width:100px; height:100px;" border="3" class="img"/></a>                

                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a href="<?php
                                    echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                        'pagetitle' => 'view_profile'), $root_link);
                                    ?>">

                                        <img src="<?php echo display_members_photo_mb($s_user_id, $image_path); ?>" style="width:100px; height:100px;" border="3" class="img" />

                                    </a>
                                    <?php
                                }
                            } else {
                                ?>
                                <a href="<?php
                                echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                    'pagetitle' => 'view_profile'), $root_link);
                                ?>">

                                    <img src="<?php echo display_members_photo_mb($s_user_id, $image_path); ?>" style="width:100px; height:100px;" border="3" class="img" />

                                </a>
                                <?php
                            }
                        }
                    } else {
                        if ($s_make_private == 'Y') {
                            if ($current_user->ID != $s_user_id) {
                                if (!in_array($current_user->ID, $favt_mem)) {
                                    ?>
                                    <a href="<?php
                                    echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                        'pagetitle' => 'view_profile'), $root_link);
                                    ?>" >

                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg"  style="width:100px; height:100px;" border="3" class="img" />

                                    </a>                
                                    <?php
                                } else {
                                    ?>
                                    <a href="<?php
                                    echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                        'pagetitle' => 'view_profile'), $root_link);
                                    ?>" >				

                                        <img src="<?php echo display_members_photo_mb($s_user_id, $image_path); ?>"    style="width:100px; height:100px;" border="3" class="img"/></a>                
                                    <?php
                                }
                            } else {
                                ?>
                                <a href="<?php
                                echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                    'pagetitle' => 'view_profile'), $root_link);
                                ?>">

                                    <img src="<?php echo display_members_photo_mb($s_user_id, $image_path); ?>" style="width:100px; height:100px;" border="3" class="img" />

                                </a>
                            <?php } ?>
                            <?php
                        } else {
                            ?>
                            <a href="<?php
                            echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                'pagetitle' => 'view_profile'), $root_link);
                            ?>">

                                <img src="<?php echo display_members_photo_mb($s_user_id, $image_path); ?>" style="width:100px; height:100px;" border="3" class="img" />

                            </a>
                        <?php } ?>
                    <?php } ?>
                    <!--<a href="<?php
                    echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                        'pagetitle' => 'view_profile'), $root_link);
                    ?>">
                    <img src="<?php echo display_members_photo_mb($s_user_id, $image_path); ?>" width="100px" height="100px" border="3" class="img" /></a>-->

                </td>
                <td style="padding-left:5px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="30%"><?php echo language_code('DSP_MEMBERSHIPS_NAME') ?></td>
                            <td  class="dsp_page_link" style="word-wrap: break-word;">
                                <a href="<?php
                                echo add_query_arg(array('pid' => 3,
                                    'mem_id' => $s_user_id, 'pagetitle' => 'view_profile'), $root_link);
                                ?>"><?php echo $displayed_member_name->display_name ?></a>
                            </td>
                        </tr>
                        <tr>
                            <td ><?php echo language_code('DSP_AGE'); ?></td>
                            <td><?php echo $s_age ?></td>
                        </tr>
                        <tr>
                            <td valign="top"><?php echo language_code('DSP_USER_LOCATION'); ?></td>
                            <td  style="word-wrap: break-word;"><?php
                                if (count($city_name) > 0) {
                                    echo $city_name->name;
                                }
                                ?><?php
                                if (count($city_name) > 0) {
                                    if ($city_name->name != "")
                                        echo ",";
                                }
                                ?>&nbsp;
                                <?php
                                if (count($state_name) > 0) {
                                    echo $state_name->name;
                                }
                                ?><?php
                                if (count($state_name) > 0) {
                                    if ($state_name->name != "")
                                        echo ",";
                                }
                                ?>&nbsp;<?php echo $country_name->name; ?></td>
                        </tr>
                        <!----------- ADD Mail LINK----------------------------------------------------->
                        <tr>
                            <td>
                                <?php
                                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                    if (isset($check_my_friends_list)) {
                                        if ($check_my_friends_list > 0) {
                                            ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 14,
                                                'message_template' => 'compose',
                                                'frnd_id' => $s_user_id, 'Act' => 'send_msg'), $root_link);
                                            ?>" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                <img src="<?php echo $mb_image_path ?>senemail.png" border="0" /></a>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 14,
                                                'message_template' => 'compose',
                                                'receive_id' => $s_user_id), $root_link);
                                            ?>" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                <img src="<?php echo $mb_image_path ?>senemail.png" border="0" />
                                            </a>
                                        <?php } //if($check_my_friends_list>0)    ?>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 14,
                                            'message_template' => 'compose', 'receive_id' => $s_user_id), $root_link);
                                        ?>" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                            <img src="<?php echo $mb_image_path ?>senemail.png" border="0" />
                                        </a>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a href="<?php echo add_query_arg(array('pgurl' => 'register'), $root_link); ?>" title="Login">  <img src="<?php echo $mb_image_path ?>senemail.png" border="0" /></a>
                                <?php } ?>
                            </td>
                            <td>
                                <?php
                                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                    if (isset($check_my_friends_list)) {
                                        if ($check_my_friends_list > 0) {
                                            ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 14,
                                                'message_template' => 'compose',
                                                'frnd_id' => $s_user_id, 'Act' => 'send_msg'), $root_link);
                                            ?>" title="<?php echo DSP_SEND_EMAIL ?>"> <?php echo DSP_SEND_EMAIL ?></a>

                                            <?php
                                        } else {
                                            ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 14,
                                                'message_template' => 'compose',
                                                'receive_id' => $s_user_id), $root_link);
                                            ?>" title="<?php echo DSP_SEND_EMAIL ?>"> <?php echo DSP_SEND_EMAIL ?></a>
                                           <?php } //if($check_my_friends_list>0)      ?>
                                           <?php
                                       } else {
                                           ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 14,
                                            'message_template' => 'compose', 'receive_id' => $s_user_id), $root_link);
                                        ?>" title="<?php echo DSP_SEND_EMAIL ?>"> <?php echo DSP_SEND_EMAIL ?></a>
                                           <?php
                                       }
                                   } else {
                                       ?>
                                    <a href="<?php echo add_query_arg(array('pgurl' => 'register'), $root_link); ?>" title="Login"><?php echo DSP_SEND_EMAIL ?></a>
                                <?php } ?>
                            </td>
                        </tr>
                        <!--------------------------------ADD FAVOURIT LINK----------------------------------------------------------------->
                        <tr>
                            <td>
                                <?php if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                    ?>
                                    <a href="<?php
                                    echo add_query_arg(array('pid' => 7,
                                        'user_id' => $user_id, 'fav_userid' => $s_user_id), $root_link);
                                    ?>" title="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>">
                                        <img src="<?php echo $mb_image_path ?>add_fav.png" border="0" />
                                    </a>
                                <?php } else { ?>
                                    <a href="<?php echo add_query_arg(array('pgurl' => 'register'), $root_link); ?>" title="Login"><img src="<?php echo $mb_image_path ?>add_fav.png" border="0" /></a>
                                    </a>
                                <?php } ?>
                            </td>
                            <td >
                                <?php if (is_user_logged_in()) {  // CHECK MEMBER LOGIN   
                                    ?>
                                    <a  href="<?php
                                    echo add_query_arg(array('pid' => 7,
                                        'user_id' => $user_id, 'fav_userid' => $s_user_id), $root_link);
                                    ?>" title="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>">
                                        <?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?></a>
                                <?php } else {
                                    ?>
                                    <a href="<?php echo add_query_arg(array('pgurl' => 'register'), $root_link); ?>" title="Login"><?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?></a>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </td>

            </tr>
        </table>

    </div>
    <?php
    unset($favt_mem);
}// foreach($search_members as $member) 
?>
<table width="100%" border="0">
    <tr><td height="3px;">&nbsp;</td></tr>
    <tr><td align="right" style="padding-right:20px;"> 

            <?php
            //---------------------Print Pagination-------------------------------------------
            /* Setup page vars for display. */
            if ($page1 == 0)
                $page1 = 1;     //if no page var is given, default to 1.
            $prev = $page1 - 1;       //previous page is page - 1
            $next = $page1 + 1;       //next page is page + 1
            $lastpage = ceil($total_pages1 / $limit);
            //lastpage is = total pages / items per page, rounded up.
            $lpm1 = $lastpage - 1;      //last page minus 1
            /// echo 'page1='.$page1.' last page='.$lastpage.'  total page='.$total_pages1.'limit='.$limit.'adgcent'.DSP_PAGINATION_ADJACENTS;
            /*
              Now we apply our rules and draw the pagination object.
              We're actually saving the code to a variable in case we want to draw it more than once.
             */
            $pagination = "";
            if ($lastpage > 1) {
                $pagination .= "<div class=\"dspmb_pagination\">";
                //previous button
                if ($page1 > 1)
                    $pagination.= "<div><a href=\"" . $page_name . "&page1=$prev\">Previous</a></div>";
                else
                    $pagination.= "<span class=\"disabled\">previous</span>";

                //pages	
                if ($lastpage <= 3 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
                    for ($counter = 1; $counter <= $lastpage; $counter++) {
                        if ($counter == $page1)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\" >" . $counter . "</a></div>";
                    }
                }
                elseif ($lastpage > 3 + ($adjacents * 2)) { //enough pages to hide some//5
                    //close to beginning; only hide later pages
                    if ($page1 <= 1 + ($adjacents * 2)) {
                        for ($counter = 1; $counter <= 1 + ($adjacents * 2); $counter++) {
                            if ($counter == $page1)
                                $pagination.= "<span class=\"current\">$counter</span>";
                            else
                                $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\" >" . $counter . "</a></div>";
                        }
                        $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                        $pagination.="<div><a href=\"" . $page_name . "&page1=$lpm1\" >" . $lpm1 . "</a></div>";

                        $pagination.="<div><a href=\"" . $page_name . "&page1=$lastpage\" >" . $lastpage . "</a></div>";
                    }
                    //in middle; hide some front and some back
                    elseif ($lastpage - ($adjacents * 2) > $page1 && $page1 > ($adjacents * 2)) {
                        $pagination.="<div><a href=\"" . $page_name . "&page1=1\" >1</a></div>";

                        $pagination.= "<div><a href=\"" . $page_name . "&page1=2\" >2</a></div>";
                        $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                        for ($counter = $page1 - $adjacents; $counter <= $page1 + $adjacents; $counter++) {
                            if ($counter == $page1)
                                $pagination.= "<div class=\"current\">$counter</div>";
                            else
                                $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\" >" . $counter . "</a></div>";
                        }
                        $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                        $pagination.= "<div><a href=\"" . $page_name . "&page1=$lpm1\" >" . $lpm1 . "</a></div>";

                        $pagination.= "<div><a href=\"" . $page_name . "&page1=$lastpage\" >" . $lastpage . "</a></div>";
                    }
                    //close to end; only hide early pages
                    else {
                        $pagination.= "<div><a href=\"" . $page_name . "&page1=1\" >1</a></div>";
                        $pagination.= "<div><a href=\"" . $page_name . "&page1=2\" >2</a></div>";
                        $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                            if ($counter == $page1)
                                $pagination.= "<span class=\"current\">$counter</span>";
                            else
                                $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\" >" . $counter . "</a></div>";
                        }
                    }
                }

                //next button
                if ($page1 < $lastpage)
                    $pagination.="<div><a href=\"" . $page_name . "&page1=$next\" >next</a></div>";
                else
                    $pagination.= "<span class=\"disabled\">next</span>";
                $pagination.= "</div>\n";
            }
            ?>
            <div class="dspmb_main_paging">
                <?php echo $pagination ?>
            </div>
            <!-----------Pagination ends--------------------------------------------------------------------->
        </td></tr></table>
