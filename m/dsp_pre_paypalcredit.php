<?php

include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

$dsp_credits_purchase_history = $wpdb->prefix . DSP_CREDITS_PURCHASE_HISTORY_TABLE;



global $wpdb;


$user_id = $_REQUEST['user_id'];

$credit_amount = $_REQUEST['credit_amount'];
$no_of_credit_to_purchase = $_REQUEST['no_of_credit_to_purchase'];
$user_id = $_REQUEST['user_id'];



$credit_purchase_data = array('user_id' => $user_id,
    'status' => 0,
    'credit_price' => $credit_amount,
    'credit_purchased' => $no_of_credit_to_purchase,
    'purchase_date' => date('Y-m-d H:i:s'));

$wpdb->insert($dsp_credits_purchase_history, $credit_purchase_data);

$inserted_id = $wpdb->insert_id;
echo $inserted_id;
?>