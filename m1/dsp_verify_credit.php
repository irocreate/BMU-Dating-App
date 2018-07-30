<?php

include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */


global $wpdb;

$dsp_credits_purchase_history = $wpdb->prefix . DSP_CREDITS_PURCHASE_HISTORY_TABLE;
$dsp_gateways_table = $wpdb->prefix . DSP_GATEWAYS_TABLE;

include_once("logs.php");
log_init();



define("PRODUCTION", "TEST");


log_message('debug', '---------------Credit verify page-------------------');

$exist_gateway = $wpdb->get_row("SELECT * FROM $dsp_gateways_table where gateway_id=1");

$clientID = $exist_gateway->paypal_client_id;
$clientSecret = $exist_gateway->paypal_secret;
$apiUsername = $exist_gateway->pro_api_username;
$apiPassword = $exist_gateway->pro_api_password;
$apiSignature = $exist_gateway->pro_api_signature;
$business = $exist_gateway->address;
$currency_code = $exist_gateway->currency;

if (count($exist_gateway) > 0 && $clientID != "" && $clientSecret != "" && $apiUsername != "" && $apiPassword != "" && $apiSignature != "" && $business != "" && $currency_code != "") {

    define("CLIENT_ID", $clientID);
    define("CLIENT_SECRET", $clientSecret);
    define('API_USERNAME', $apiUsername);
    define('API_PASSWORD', $apiPassword);
    define('API_SIGNATURE', $apiSignature);

    log_message('debug', 'CLIENTID=' . $clientID . ' SECRET=' . $clientSecret . ' APIUNAME=' . API_USERNAME . ' APIPWD=' . API_PASSWORD . ' APISIGNATURE=' . API_SIGNATURE);


    include_once('dsp_paypal_function.php'); // paypal functions 



    $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : "";
    $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : "";
    $credit_purchase_id = isset($_REQUEST['creditPurchaseId']) ? trim($_REQUEST['creditPurchaseId']) : "";

    log_message('debug', '  credit_purchase_id==' . $credit_purchase_id);

    if ($type != "" && $user_id != "" && $credit_purchase_id != "") {
        // check if tmp payment table has entries with related user
        $payment_details = $wpdb->get_row("SELECT credit_price FROM $dsp_credits_purchase_history where credit_purchase_id='$credit_purchase_id'");

        if (count($payment_details) > 0) {
            $oldAmt = $payment_details->credit_price;


            $newReceiverEmail = "";
            $newAmt = "";
            $newCurrency = "";
            $status = "";

            log_message('debug', 'type===' . $type . ' ' . $user_id);

            $amt = isset($_REQUEST['amt']) ? $_REQUEST['amt'] : "";

            if ($type == "adaptive_payment") {// for paypal login payment
                $appId = isset($_REQUEST['appId']) ? $_REQUEST['appId'] : "";
                $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : "";

                log_message('debug', 'appId==' . $appId . ' key==' . $key . '  credit_purchase_id==' . $credit_purchase_id);

                $result = array();

                $result = getData($appId, $key, PRODUCTION);

                //$val='{"responseEnvelope":{"timestamp":"2014-01-19T22:22:28.225-08:00","ack":"Success","correlationId":"ed5be92ac3aa5","build":"7935900"},"cancelUrl":"http:\/\/www.paypal.com","currencyCode":"USD","paymentInfoList":{"paymentInfo":[{"transactionId":"0K319892F9601291M","transactionStatus":"COMPLETED","receiver":{"amount":"1.99","email":"mwnt.test10-facilitator@gmail.com","primary":"false","paymentType":"SERVICE","accountId":"YAUKF97U5SFYN"},"refundedAmount":"0.00","pendingRefund":"false","senderTransactionId":"56Y723972P2654018","senderTransactionStatus":"COMPLETED"}]},"returnUrl":"http:\/\/www.paypal.com","status":"COMPLETED","payKey":"AP-0SP08527PJ815781M","actionType":"PAY","feesPayer":"EACHRECEIVER","sender":{"accountId":"FBBNTH566UHFE","useCredentials":"true"}}';

                $paymentArray = $result['paymentInfoList']['paymentInfo'];

                $arr = $paymentArray[0];

                foreach ($arr as $key => $val) {
                    if (is_array($val)) {
                        if ($key == "receiver") {
                            $newReceiverEmail = $val['email'];
                            $newAmt = $val['amount'];
                        }
                    }
                }



                $newCurrency = $result['currencyCode'];
                $status = $result['status'];
                log_message('debug', 'currency======' . $newCurrency . ' status=' . $status . '  amt=>' . $newAmt . ' receiveremail=' . $newReceiverEmail . ' creditid=' . $credit_purchase_id);

                // check is status is completed 
                //and membership currecny is same as response currency 
                // and business email same aas  response receiver emails   
                if ($status == 'COMPLETED' && $business == $newReceiverEmail && $currency_code == $newCurrency && $oldAmt == $newAmt) {
                    log_message('debug', '-----payment success--------');
                    update_credit($credit_purchase_id, $user_id); // update credit tables
                    echo 'success';
                } else {
                    log_message('debug', '-----payment response does not match with old data---------old details are==business==' . $business . ' currency==' . $currency_code . ' amt=' . $oldAmt . ' credit id=' . $credit_purchase_id);
                    echo "fail";
                }
            } else if ($type == "rest_api") { // for credit card payment 
                //$payment_id="PAY-4GA58784920542700KLORERY";
                $payment_id = isset($_REQUEST['appId']) ? $_REQUEST['appId'] : "";

                log_message('debug', 'Credit payment=========payment_id==' . $payment_id);

                $response = getDataCredit($payment_id, PRODUCTION);

                //echo '<br>state=='.$response['body']->state;

                $status = $response['body']->state;
                $transactionArray = $response['body']->transactions;

                /// $transactionArray contains one array with key name 0 . and that key value  contains one object 
                // now we will fetch the value and get the object from that 

                foreach ($transactionArray as $key => $val) {
                    $ar = (array) $val;   // convert the object into array
                    foreach ($ar as $key => $val) {
                        if ($key == "amount") {//print_r($val);
                            $amtArray = (array) $val; // convert the object into array
                            $newAmt = $amtArray['total'];
                            $newCurrency = $amtArray['currency'];
                            //print_r($amtArray);
                        }
                    }
                }

                //echo '<br>'.$total.' '.$currency;
                log_message('debug', 'currency======' . $newCurrency . ' status=' . $status . '  amt=>' . $newAmt);

                if ($status == 'approved' && $currency_code == $newCurrency && $oldAmt == $newAmt) {
                    log_message('debug', '-----Credit payment success--------');
                    update_credit($credit_purchase_id, $user_id);
                    echo 'success';
                } else {
                    log_message('debug', '-----credit payment response does not match with old data---------old details are== currency==' . $currency_code . ' amt=' . $oldAmt);
                    echo "fail";
                }
            }
        } else {
            echo "failed";
            log_message('debug', '-----payment fail tmp table do not have any enrtry with this user---------');
        }
    } else {
        log_message('debug', '-----payment fail user id and paymnet type not found---------');
        echo "failed";
    }
} else {
    log_message('debug', '-----payment fail admin paypal detail not found not found---------');
    echo "failed";
}
?>