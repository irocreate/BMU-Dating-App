<?php

//error_reporting(0);  
//@ini_set('display_errors', 0);

include("../../../../wp-config.php");
/* to off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* Note Don't use $_post here .. use $_request */



global $wpdb;


$msg = 'Invalid username or password';

include('myclass.php');

include_once(WP_DSP_ABSPATH . 'dsp_validation_functions.php');


$username = $wpdb->escape(sanitizeData(trim($_REQUEST['loginUsername']), 'xss_clean'));
$password = $wpdb->escape(sanitizeData(trim($_REQUEST['password']), 'xss_clean'));




if (empty($username) || empty($password)) {
    $msg = language_code('DSP_USER_NAME_SHOULD_NO_BE_EMPTY');
} else {

    $user = get_user_by('login', $username);


    if ($user && wp_check_password($password, $user->data->user_pass, $user->ID)) {
        
        $userId = $user->ID;
        $msg = "valid";
    } else {
        $msg = 'Invalid username or password.';
    }
}
//		
$arrSections = array();
$intCounter = 0;
$arrSections[$intCounter] = new clsSections;
$arrSections[$intCounter]->section_title = $msg;
$arrSections[$intCounter]->section_id = $userId;
$arrSections[$intCounter]->section_element = $msg;


echo $_REQUEST['callback'] . '(' . json_encode($arrSections) . ')';
?>