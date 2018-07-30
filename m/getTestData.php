<?php

//header("Access-Control-Allow-Origin: *");
//$data="this is new project";
//echo $data;
//http://stackoverflow.com/questions/11132245/phonegap-jquery-mobile-android-app-form-submission
//include ("../wp-config.php");
/* Note Don't use $_post here .. use $_request */



include("../../../../wp-config.php");

/* to off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);

global $wpdb;

//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

include('myclass.php');

include_once 'da.php';


$sent = false;


include_once(WP_DSP_ABSPATH . 'dsp_validation_functions.php');


$username = $wpdb->escape(sanitizeData(trim($_REQUEST['sitename']), 'xss_clean'));

$msg = 'good';




$arrSections = array();
$intCounter = 0;
$arrSections[$intCounter] = new clsSections;
$arrSections[$intCounter]->section_id = $username;
$arrSections[$intCounter]->section_title = $msg;

//header("Content-Type: application/json", true);

echo $_GET['callback'] . '(' . json_encode($arrSections) . ')';
//echo json_encode($arrSections);
?>