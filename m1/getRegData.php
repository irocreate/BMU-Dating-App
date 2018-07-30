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

//include (get_template_directory_uri() . "/page-templates/functions.php");

//$connect = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die('Our database is currently down for updates, please check back later.');
$connect = @new mysqli(DB_HOST, DB_USER, DB_PASSWORD);

if ($connect->connect_error) {
    echo "Error: " . $connect->connect_error;
	exit();
}

$db = @mysqli_select_db($connect,DB_NAME)or die('Our database is currently down for updates, please check back later.');

//$db = @mysqli_select_db(DB_NAME) or die('Our database is currently down for updates, please check back later.');

include('myclass.php');


$sent = false;



include_once(WP_DSP_ABSPATH . 'dsp_validation_functions.php');


$username = $wpdb->escape(sanitizeData(trim($_REQUEST['username']), 'xss_clean'));
$email = $wpdb->escape(sanitizeData(trim($_REQUEST['email']), 'xss_clean'));
$email = strtolower($email);
$msg = '';

if (empty($username)) {

$msg = language_code('DSP_USER_NAME_SHOULD_NO_BE_EMPTY');
} else {

//$confirm_email = $_REQUEST['confirm_email'];

$confirm_email = $wpdb->escape(sanitizeData(trim($_REQUEST['confirm_email']), 'xss_clean'));
$confirm_email = strtolower($confirm_email);

if (!is_email($email)) {

$msg = language_code('DSP_PLEASE_ENTER_A_VALID_EMAIL');
} else if (!is_email($confirm_email)) {

$msg = language_code('DSP_PLEASE_ENTER_A_VALID_EMAIL');
} else if ($email != $confirm_email) {

$msg = language_code('DSP_EMAIL_FIELDS_ARE_NOT_SAME');
} else if ($_REQUEST['terms'] == '') {

$msg = language_code('DSP_AGREE_TERMS_AND_CONDITIONDS');
} else {
$msg = "valid" . $username;

$gender = $_REQUEST['select_gender'];

$month = $_REQUEST['dsp_mon'];

$day = $_REQUEST['dsp_day'];

$year = $_REQUEST['dsp_year'];

$age = $year . "-" . $month . "-" . $day;

$msg = $msg . ' ' . $gender . ' age=' . $age;


$sent = true;



$random_password = wp_generate_password(12, false);



$dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;



$dsp_blacklist_members = $wpdb->prefix . DSP_BLACKLIST_MEMBER_TABLE;



//Get the IP of the person registering
// $ip = $_SERVER['REMOTE_ADDR'];
//$check_blacklist_ipaddress_table = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blacklist_members where ip_address = '$ip' AND  ip_status=1 ");
//if($check_blacklist_ipaddress_table<=0) 
{



$status = wp_create_user($username, $random_password, $email);



$users_table = $wpdb->get_row("SELECT * FROM $dsp_users_table where user_login='$username' ");

$user_id = $users_table->ID;
$msg = "user id is:" . $user_id;


//	update_user_meta($user_id, 'signup_ip', $ip);
//	update_user_meta($user_id, 'ip_address_status', $ip_address_status);	
//	$wpdb->query("INSERT INTO $dsp_blacklist_members SET user_name = '$username', ip_address ='$ip' ,ip_status=0 ");





$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;


$last_update_date = date("Y-m-d H:m:s");






if (( is_wp_error($status))) {

$msg = language_code('DSP_USER_NAME_ALREADY_EXIST_PLEASE_TRY_ANOTHER_ONE');
} else {
$wpdb->query("INSERT INTO $dsp_user_profiles SET user_id = '$user_id', gender ='$gender' ,age='$age',status_id=1, edited='Y', last_update_date='$last_update_date'");

$from = get_option('admin_email');

$headers = language_code('DSP_FROM') . $from . "\r\n";

$subject = language_code('DSP_REGISTERATION_SUCCESSFULL');

$message = language_code('DSP_YOUR_LOGIN_DETAIL') . "\n" . language_code('DSP_USER_NAME') . $username . "\n" . language_code('DSP_PASSWORD') . $random_password;

wp_mail($email, $subject, $message, $headers);


$msg = language_code('DSP_PLEASE_CHECK_YOUR_EMAIL_FOR_LOGIN_DETAIL');
}
}
}
}
$arrSections = array();
$intCounter = 0;
$arrSections[$intCounter] = new clsSections;
$arrSections[$intCounter]->section_id = $email;
$arrSections[$intCounter]->section_title = $msg;

//header("Content-Type: application/json", true);

echo $_GET['callback'] . '(' . json_encode($arrSections) . ')';
//echo json_encode($arrSections);
?>