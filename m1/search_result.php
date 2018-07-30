<?php
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_user_search_criteria_table = $wpdb->prefix . DSP_USER_SEARCH_CRITERIA_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_online_user_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
// ----------------------------------------------- Start Paging code------------------------------------------------------ //  


$root_link = "";
if (isset($_GET['page1']))
    $page = $_GET['page1'];
else
    $page = 1;



// How many adjacent pages should be shown on each side?

$adjacents = 2;



$limit = 5;

//$limit = 1; 	

if ($page)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;
$search_type = isset($_REQUEST['search_type']) ? $_REQUEST['search_type'] : '';

if ($search_type == "basic") {
    $backtype = "basic_search";
} else if ($search_type == "Advanced") {
    $backtype = "advance_search";
}else if ($search_type =="show_save_search"){
    $backtype="save_searches";
}else{
   $backtype=$search_type;
}

$function1="saveHidden('".($page-1)."');";
$function2="viewSearch(0,'".$backtype."');";
?>

<div role="banner" class="ui-header ui-bar-a" data-role="header">
   <a class="ui-btn-left ui-btn-corner-all ui-icon-back ui-btn-icon-notext ui-shadow"  onclick="<?php echo ($page>1)? $function1 : $function2;?>" href="#" >
   </a> 

   <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_SEARCH'); ?></h1>
   <?php include_once("page_home.php");?>
</div>
<?php 
// ----------------------------------------------- Start Paging code------------------------------------------------------ //

$fetch_record_by_save_search = isset($_REQUEST['save_search_Id']) ? $_REQUEST['save_search_Id'] : '';




$searchbysave = isset($_REQUEST['searchbysave']) ? $_REQUEST['searchbysave'] : '';

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
    $Online_only = $search1->online_only;

    $profile_question_option_id = $search1->Profile_questions_option_ids;

    $countryName = $wpdb->get_var("SELECT name FROM $dsp_country_table WHERE country_id = '" . $cmbCountry . "'");
    $stateName = $wpdb->get_row("SELECT name FROM $dsp_state_table WHERE state_id = '" . $cmbState . "'");
    $cityName = $wpdb->get_row("SELECT name FROM $dsp_city_table WHERE city_id = '" . $cmbCity . "' and state_id=" . $cmbState);

    $search_type = $search1->search_type;
    ?>
    <input name="save_search_Id" id="save_search_Id" type="hidden" value="<?php echo $fetch_record_by_save_search; ?>" />
    <input type="hidden" name="searchbysave" value="save_search" />
    <?php
} else {

    $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
    $seeking = isset($_REQUEST['seeking']) ? $_REQUEST['seeking'] : '';
    $age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : '';
    $age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : '';
    $countryName = isset($_REQUEST['cmbCountry']) ? $_REQUEST['cmbCountry'] : '';
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';


    if (strlen($countryName) > 1) {


        $get_Country = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE name = '" . $countryName . "'");

        $cmbCountryid = $get_Country->country_id;
    } else {
        $cmbCountryid = 0;
    }

    $stateName = isset($_REQUEST['cmbState']) ? $_REQUEST['cmbState'] : '';

    if ($stateName != "Select" && strlen($stateName) > 1) {
        $get_State = $wpdb->get_row("SELECT * FROM $dsp_state_table WHERE name = '" . $stateName . "'");

        $cmbStateid = $get_State->state_id;
    } else {

        $cmbStateid = 0;
    }

    $cityName = isset($_REQUEST['cmbCity']) ? $_REQUEST['cmbCity'] : '';

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
    $cmbCountry = $cmbCountryid;

    $cmbState = $cmbStateid;
    $cmbCity = $cmbCityid;
    $Pictues_only = isset($_REQUEST['Pictues_only']) ? $_REQUEST['Pictues_only'] : '';

    if (isset($_REQUEST['Online_only'])) {
        $Online_only = $_REQUEST['Online_only'];
    } else {
        $Online_only = 'N';
    }

    $profile_question_option_id1 = isset($_REQUEST['profile_question_option_id']) ? $_REQUEST['profile_question_option_id'] : '';

    if ($profile_question_option_id1 != "") {

        $profile_question_option_id = implode(",", $profile_question_option_id1);
    } else {
        $profile_question_option_id = 0;
    }
}







$search_save = isset($_REQUEST['savesearch']) ? $_REQUEST['savesearch'] : '';

$check_save = isset($_REQUEST['check_save']) ? $_REQUEST['check_save'] : '';

$search_type = isset($_REQUEST['search_type']) ? $_REQUEST['search_type'] : '';



if ($search_type == "basic") {
    $type = "basic_search";
} else if ($search_type == "Advanced") {
    $type = "advance_search";
}
//echo "Search:".$search_save."<br /> and check save:".$check_save; die;

if ($search_save != "" && $check_save ) {


    if ($search_type == "basic") {

        $wpdb->query("INSERT INTO $dsp_user_search_criteria_table SET user_id = $user_id,user_gender='$gender',seeking_gender = '$seeking',age_from = '$age_from',age_to = '$age_to',country_id='$cmbCountry',state_id = '$cmbState',city_id ='$cmbCity',online_only='$Online_only',with_pictures='$Pictues_only',Profile_questions_option_ids='0',search_name='$search_save',search_type='$search_type'");
    } else if ($search_type == "Advanced") {
        $type = "advance_search";
        $wpdb->query("INSERT INTO $dsp_user_search_criteria_table SET user_id = $user_id,user_gender='$gender',seeking_gender='$seeking',age_from='$age_from',age_to='$age_to',country_id='$cmbCountry',state_id = '$cmbState',city_id ='$cmbCity',online_only='$Online_only',with_pictures='$Pictues_only',Profile_questions_option_ids='$profile_question_option_id',search_name='$search_save',search_type='$search_type'");
    }
}

$bolIfSearchCriteria = false;



$strQuery = "SELECT DISTINCT (fb.user_id),(year(CURDATE())-year(fb.age)) age FROM $dsp_user_profiles fb WHERE  ";

if ($Online_only == "Y") {
    $strQuery = substr($strQuery, 0, strlen($strQuery) - 7);
    $strQuery .= "INNER JOIN $dsp_user_online_table AS onl ON fb.user_id = onl.user_id WHERE  ";
    $bolIfSearchCriteria = true;
}



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

    $strQuery .= " ((year(CURDATE())-year(fb.age)) BETWEEN $age_from AND $age_to)  AND ";

    $bolIfSearchCriteria = true;
}
if (trim(strlen($gender)) > 0) {
    $strQuery .= " fb.gender='" . $seeking . "' AND ";
    $bolIfSearchCriteria = true;
}

if (trim(strlen($seeking)) > 0) {
    $strQuery .= " fb.seeking='" . $gender . "' AND ";
    $bolIfSearchCriteria = true;
}
if ($Online_only == "Y") {
    $strQuery .= " onl.status='Y' AND ";
    $bolIfSearchCriteria = true;
}

$intRecordsPerPage = 10;
$intStartLimit = isset($_REQUEST['p']) ? $_REQUEST['p'] : ''; # page selected 1,2,3,4...

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

    $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
}
// ----------------------------------------------- Start Paging code------------------------------------------------------ //

if ($search_type == "basic") {
    $page_name = $root_link . "?pid=5&pagetitle=search_result&basic_search=basic_search&gender=" . $gender . "&seeking=" . $seeking . "&age_from=" . $age_from . "&age_to=" . $age_to . "&cmbCountry=" . $countryName . "&cmbState=" . $stateName . "&cmbCity=" . $cityName . "&Online_only=" . $Online_only . "&Pictues_only=" . $Pictues_only . "&search_type=" . $search_type . "&savesearch=" . $search_save . "&submit=Submit";
} else if ($search_type == "Advanced") {

    $page_name = $root_link . "?pid=5&pagetitle=search_result&gender=" . $gender . "&seeking=" . $seeking . "&age_from=" . $age_from . "&age_to=" . $age_to . "&cmbCountry=" . $countryName . "&cmbState=" . $stateName . "&cmbCity=" . $cityName . "&Pictues_only=" . $Pictues_only . "&profile_question_option_id[]=" . $profile_question_option_id . "&search_type=" . $search_type . "&savesearch=" . $search_save . "&submit=Submit";
} else if ($searchbysave == "save_search") {

    @$page_name = $root_link . "?pid=5&pagetitle=search_result&gender=" . $gender . "&seeking=" . $seeking . "&age_from=" . $age_from . "&age_to=" . $age_to . "&cmbCountry=" . $countryName . "&cmbState=" . $stateName . "&cmbCity=" . $cityName . "&Pictues_only=" . $Pictues_only . "&profile_question_option_id[]=" . $profile_question_option_id . "&search_type=" . $search_type . "&savesearch=" . $search_save . "&submit=Submit";
} else {
    $page_name = $root_link . "?pid=5&pagetitle=search_result&gender=" . $gender . "&seeking=" . $seeking . "&age_from=" . $age_from . "&age_to=" . $age_to . "&cmbCountry=" . $countryName . "&username=" . $username . "&submit=Search";
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

    $pagination .= "<div class='button-area'>";

    //previous button

    if ($page > 1) {
        $pagination.="

        <div onclick='saveHidden(1)' class='btn-pre1' >
        <img src='images/icons/prev-1.png' />
       </div>";
   } else {
    $pagination.= "
    <div class='btn-pre1' >
    <img src='images/icons/prev-1.png' />
          
   </div>";
}

if ($page > 1) {
    $pagination.="<div  onclick='saveHidden($prev)' class='btn-pre2'>
    <img src='images/icons/prev-all.png'' />
</div>";
} else {
    $pagination.=" <div  class='btn-pre2'>
    <img src='images/icons/prev-all.png' />
    
</div>";
}


$pagination.= "<div class='main3'>
<ul class='page_ul' style='font-size: 12px;'> 
    <li > Page</li>
    <li >$page</li>
    <li >of $lastpage</li>
</ul>
</div>";

if ($page < $lastpage) {
    $pagination.= "
    <div onclick='saveHidden($next)' class='main4' >
        <img src='images/icons/next-all.png' />
    </div>";

    $pagination.= "	<div onclick='saveHidden($lastpage)' class='main5'>
    <img src='images/icons/next-1.png' />
</div>";
} else {
    $pagination.= "
    <div class='main4'>
     <img src='images/icons/next-all.png' />
 </div>";

 $pagination.= "	<div class='main5'>
 <img src='images/icons/next-1.png' />
</div>";
}

$pagination.= "</div>\n";
}
?>


<?php
// ------------------------------------------------End Paging code------------------------------------------------------ // 






if (isset($user_count))
    $intTotalRecordsEffected = $user_count;
else
    $intTotalRecordsEffected = 0;






if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {

} else {
    ?>
    <div class="ui-content" data-role="content">
        <div class="content-primary">	


            <div class="page-not-found alert-message">
                <?php echo language_code('DSP_NO_RECORD_FOUND'); ?>

                <span><a onclick="viewSearch(0, '<?php echo $type; ?>');"><?php echo language_code('DSP_START_NEW_SEARCH'); ?></a></span>
            </div>
        </div>
        <?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
    </div>

    <?php
} // if ($intTotalRecordsEffected != '0')	
//echo $strQuery ;
?>
<div class="ui-content" data-role="content">
    <div class="content-primary">

        <form id="frmsearch">
            <input type="hidden" value="<?php echo $gender; ?>" name="gender"/>
            <input type="hidden" value="<?php echo $seeking; ?>" name="seeking"/>
            <input type="hidden" value="<?php echo $age_from; ?>" name="age_from"/>
            <input type="hidden" value="<?php echo $age_to; ?>" name="age_to"/>
            <input type="hidden" value="<?php echo $countryName; ?>" name="cmbCountry"/>

            <input type="hidden" value="<?php echo $stateName; ?>" name="cmbState"/>
            <input type="hidden" value="<?php echo $cityName; ?>" name="cmbCity"/>
            <input type="hidden" value="<?php echo $Online_only; ?>" name="Online_only"/>
            <input type="hidden" value="<?php echo $Pictues_only; ?>" name="Pictues_only"/>
            <input type="hidden" value="<?php echo $profile_question_option_id; ?>" name="profile_question_option_id[]"/>

            <input type="hidden" value="<?php echo $user_id; ?>" name="user_id"/>
            <input type="hidden" value="<?php echo "search_result"; ?>" name="pagetitle"/>
            <input type="hidden" value="<?php echo $search_type; ?>" name="search_type"/>
            <input type="hidden" value="" id="page" name="page1" />
        </form>	

        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul userlist">


            <?php
            $search_members = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");


//echo $strQuery;




            foreach ($search_members as $member1) {
                ?>
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">


                    <?php
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

                    <div class="dsp_pro_full_view">
                        <div class="profile_img_view">
                            <?php
                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($s_gender == 'C') {
                                    ?>
                                    <?php
                                    if ($s_make_private == 'Y') {
                                        if ($user_id != $s_user_id) {
                                            ?>
                                            <?php if (!in_array($user_id, $favt_mem)) {
                                                ?>

                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                    <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  style="width:100px; height:100px;"  border="0" class="dsp_img3" />
                                                </a>                
                                                <?php
                                            } else {
                                                ?>

                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				

                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    style="width:100px; height:100px;"  border="0" class="dsp_img3"/></a>                

                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  border="0" class="dsp_img3" />

                                                </a>

                                                <?php } ?>

                                                <?php
                                            } else {
                                                ?>

                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  border="0" class="dsp_img3" />

                                                </a>

                                                <?php } ?>
                                                <?php } else { ?>



                                                <?php if ($s_make_private == 'Y') { ?>



                                                <?php if ($user_id != $s_user_id) { ?>



                                                <?php if (!in_array($user_id, $favt_mem)) { ?>

                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                    <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  style="width:100px; height:100px;" border="0" class="dsp_img3" />

                                                </a>                

                                                <?php } else {
                                                    ?>

                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				

                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"     style="width:100px; height:100px;" border="0" class="dsp_img3"/></a>                

                                                        <?php
                                                    }
                                                } else {
                                                    ?>

                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  border="0" class="dsp_img3" />

                                                    </a>

                                                    <?php } ?>



                                                    <?php } else { ?>



                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  border="0" class="dsp_img3" />

                                                    </a>

                                                    <?php } ?>



                                                    <?php
                                                }
                                            } else {
                                                ?> 



                                                <?php if ($s_make_private == 'Y') { ?>

                                                <?php if ($user_id != $s_user_id) { ?>



                                                <?php if (!in_array($user_id, $favt_mem)) { ?>

                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                    <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  style="width:100px; height:100px;" border="0" class="dsp_img3" />

                                                </a>                

                                                <?php } else {
                                                    ?>

                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				

                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    style="width:100px; height:100px;" border="0" class="dsp_img3"/></a>                

                                                        <?php
                                                    }
                                                } else {
                                                    ?>

                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" />

                                                    </a>

                                                    <?php } ?>





                                                    <?php } else { ?>



                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" />

                                                    </a>

                                                    <?php } ?>





                                                    <?php } ?>

                                                </div>
                                                <div class="dsp_on_lf_view">
                                                   

                                                            <span class="online">
                                                                <?php $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_online_table WHERE user_id=$s_user_id"); ?>
                                                                <img class="icon-on-off" src="<?php
                                                                if ($check_online_user > 0)
                                                                    echo $fav_icon_image_path . 'online-chat.gif';
                                                                else
                                                                    echo $fav_icon_image_path . 'off-line-chat.jpg';
                                                                ?>" title="<?php
                                                                if ($check_online_user > 0)
                                                                   echo language_code('DSP_CHAT_ONLINE');
                                                               else
                                                                   echo language_code('DSP_CHAT_OFFLINE');
                                                               ?>" border="0" />
                                                           </span>

                                                           <span class="user-name">
                                                            <strong>
                                                                <?php
                                                                if ($check_couples_mode->setting_status == 'Y') {

                                                                    if ($s_gender == 'C') {
                                                                        ?>

                                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                                            <?php echo $displayed_member_name->display_name ?>                

                                                                            <?php
                                                                        } else {
                                                                            ?>

                                                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                                                <?php echo $displayed_member_name->display_name ?>

                                                                                <?php
                                                                            }
                                                                        } else {
                                                                            ?> 
                                                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                                <?php echo $displayed_member_name->display_name ?>
                                                                                <?php } ?>

                                                                            </a>
                                                                        </strong>
                                                                    </span>
                                                               

                                                                    <div class="user-details">
                                                                        <?php echo $s_age ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?> <?php
                                                                        if ($s_gender == 'M')
                                                                            echo language_code('DSP_MAN');
                                                                        else if ($s_gender == 'F')
                                                                            echo language_code('DSP_WOMAN');
                                                                        else if ($s_gender == 'C')
                                                                            echo language_code('DSP_COUPLE');
                                                                        ?> <?php echo language_code('DSP_FROM_TEXT'); ?> <br />
                                                                        <?php
                                                                        if (count($city_name) > 0) {
                                                                            if ($city_name->name != "")
                                                                                echo $city_name->name . ',';
                                                                        }
                                                                        ?> 
                                                                        <?php
                                                                        if (count($state_name) > 0) {
                                                                            if ($state_name->name != "")
                                                                                echo $state_name->name . ',';
                                                                        }
                                                                        ?> <?php echo @$country_name->name; ?>
                                                                    </div>
                                                              
                                                                <div class="dsp_prof_view">
                                                                    <div style="width: 100%">

                                                                        <?php
                                                            if ($check_my_friend_module->setting_status == 'Y') { // Check My friend module Activated or not 
                                                                ?>
                                                                <div> <?php
                                                                    if ($check_user_profile_exist > 0) {  // check user dating profile exist or not 	 
                                                                        ?>

                                                                        <a onclick="addFriend('<?php echo $s_user_id; ?>')" title="<?php echo language_code('DSP_ADD_TO_FRIENDS'); ?>">
                                                                            <img src="<?php echo $fav_icon_image_path ?>friend.jpg" border="0" style="width:20px;height:20px"/>
                                                                        </a>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <a onclick="redirectEditProfile('<?php echo language_code('DSP_UPDATE_PROFILE_BEFORE_ADD_FRND_MSG') ?>');" title="Edit Profile">
                                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>friend.jpg" border="0" />
                                                                        </a> 
                                                                        <?php } ?>
                                                                    </div>

                                                                    <?php } // END My friends module Activation check condition   ?>



                                                                    <div>
                                                                        <a onclick="addFavourite('<?php echo $s_user_id; ?>')" title="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>">
                                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>star.jpg" border="0" />
                                                                        </a>
                                                                    </div>
                                                                    <div> 

                                                                        <?php
                                                                        if (isset($check_my_friends_list) && $check_my_friends_list > 0) {
                                                                            ?>
                                                                            <a onclick="composeMessage('<?php echo $s_user_id ?>', 0)" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                                                <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" />
                                                                            </a>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <a onclick="composeMessage('<?php echo $s_user_id ?>', 0)" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                                                <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" />
                                                                            </a>
                                                                            <?php } //if($check_my_friends_list>0)   ?>

                                                                        </div>


                                                            <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not 
                                                                ?>
                                                                <div>
                                                                    <?php
                                                                    if ($check_user_profile_exist > 0) {  // check user dating profile exist or not 		  
                                                                        ?>
                                                                        <a onclick="sendWink('<?php echo $s_user_id; ?>')" title="<?php echo language_code('DSP_SEND_WINK'); ?>">
                                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" />
                                                                        </a>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <a onclick="redirectEditProfile('<?php echo language_code('DSP_UPDATE_PROFILE_BEFORE_ADD_FRND_MSG') ?>');" title="Edit Profile">
                                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" />
                                                                        </a>

                                                                        <?php } ?>
                                                                    </div>
                                                                    <?php
                                                            } // END My friends module Activation check condition 
                                                            // check if one to one csetting is yes
                                                            if ($check_chat_one_mode->setting_status == 'Y') {
                                                                // send chat request if user is online
                                                                if ($s_user_id != $user_id) { // if this member is not user itself
                                                                    $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_online_user_table WHERE user_id=$s_user_id");

                                                                    if ($check_online_user > 0) { // show chat icon if user is online
                                                                        ?>
                                                                        <div>
                                                                            <a onclick="openChatRoom('<?php echo $s_user_id; ?>', 'send_request')">
                                                                                <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>chat.jpg" border="0" />
                                                                            </a>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                   
                                            </div>
                                        </div>





                                    </li>

                                    <?php
                                                }// foreach($search_members as $member) 
                                                ?>
                                            </ul>

                                        </div>
                                        <?php include_once('dspNotificationPopup.php'); // for notification pop up     ?>
                                    </div>


                                    <div class="ds_pagination" > 
                                        <?php echo $pagination ?>
                                    </div>