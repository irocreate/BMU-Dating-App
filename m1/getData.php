<?php

//header("Access-Control-Allow-Origin: *");
//$data="this is new project";
//echo $data;
//http://stackoverflow.com/questions/11132245/phonegap-jquery-mobile-android-app-form-submission
//include ("../wp-config.php");



include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

global $wpdb;

include (get_template_directory_uri() . "/page-templates/functions.php");

$connect = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die('Our database is currently down for updates, please check back later.');

$db = @mysql_select_db(DB_NAME, $connect) or die('Our database is currently down for updates, please check back later.');

include('myclass.php');

$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;
$getRes = $wpdb->get_results("SELECT * from $DSP_USERS_TABLE limit 5");

//echo "SELECT * from $DSP_USERS_TABLE".sizeof($getRes);


/*
  foreach($getRes as $val)
  {
  echo $val->user_login.'<br>';
  }
 */
//exit( var_dump( $wpdb->last_query ) );

$arrSections = array();
$intCounter = 0;

foreach ($getRes as $section) {

    $arrSections[$intCounter] = new clsSections;
    $arrSections[$intCounter]->section_id = $section->ID;
    $arrSections[$intCounter]->section_title = $section->user_login;
    $arrSections[$intCounter]->section_element = $section->user_email;

    $intCounter++;
}


//header("Content-Type: application/json", true);

echo $_GET['callback'] . '(' . json_encode($arrSections) . ')';
//echo json_encode($arrSections);
?>