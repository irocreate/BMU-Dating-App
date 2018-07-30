<?php

include("../../../../wp-config.php");


/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));



//-------------------------DISPLAY ERROR OFF CODE ENDS--------------------------------


include_once("../general_settings.php");

global $wpdb;



$user_id = $_REQUEST['user_id'];  // print session USER_ID
$dsp_chat_request = $wpdb->prefix . "dsp_chat_request";


$wpdb->query("delete from $dsp_chat_request where sender_id=" . $_REQUEST['sender_id'] . " and receiver_id=$user_id");
?>