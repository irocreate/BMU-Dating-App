<?php

function updateMembership($user_id) {
    include("../../../../wp-config.php");
    /* To off  display error or warning which is set of in wp-confing file --- 
      // use this lines after including wp-config.php file
     */
    error_reporting(0);
    @ini_set('display_errors', 0);
    error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

    /* ------------- end of show error off code------------------------------------------ */

    global $wpdb;

    $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
    $dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
    $dsp_user_table = $wpdb->prefix . users;
    $dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;

    $update_payment_details = $wpdb->get_row("SELECT * FROM $dsp_temp_payments_table where user_id='$user_id'");
    $check_already_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
    if ($check_already_user_exists <= 0) {
        //echo "<br>INSERT INTO $dsp_payments_table SET pay_user_id = '$update_payment_details->user_id',pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id',recurring_profile_status='1'";
        $wpdb->query("INSERT INTO $dsp_payments_table SET pay_user_id = '$update_payment_details->user_id',pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id',recurring_profile_status='1'");
    } else {

//echo "<br>UPDATE $dsp_payments_table SET pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id', recurring_profile_status='1'  WHERE pay_user_id = '$update_payment_details->user_id'";
        $wpdb->query("UPDATE $dsp_payments_table SET pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id', recurring_profile_status='1'  WHERE pay_user_id = '$update_payment_details->user_id'");
    }
    if(dsp_issetGivenEmailSetting($user_id,'Payment Successful Email')){
        $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='16'");
        $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$user_id'");
        $reciver_name = $reciver_details->display_name;
        $receiver_email_address = $reciver_details->user_email;

        $siteurl = get_option('siteurl');

        $email_subject = $email_template->subject;
        $email_message = $email_template->email_body;
        $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
        $email_message = str_replace("<#DOMAIN_NAME#>", $siteurl, $email_message);

        $MemberEmailMessage = $email_message;

        $to = $receiver_email_address;

        $subject = $email_subject;

        $message = $MemberEmailMessage;


        $admin_email = get_option('admin_email');

        $from = $admin_email;

        $headers = "From: $from";

        wp_mail($to, $subject, $message, $headers);
      }  
}

function getData($app_id, $key, $production) {
    if ($production == "TEST") {
        $apiUrl = 'https://svcs.sandbox.paypal.com/AdaptivePayments/PaymentDetails';
    } else {
        $apiUrl = 'https://svcs.paypal.com/AdaptivePayments/PaymentDetails';
    }

    $api_user = API_USERNAME; // "mwnt.test10-facilitator_api1.gmail.com";
    $api_pass = API_PASSWORD; //"1365419626";
    $api_sig = API_SIGNATURE; //"AQU0e5vuZCvSg-XJploSa.sGUDlpAnYFD-iDkq6xirt6wIg.wQJv-lge";
    //$app_id = "APP-80W284485P519543T";
    // $key="AP-0SP08527PJ815781M";


    $packet = array(
        "requestEnvelope" => array(
            "errorLanguage" => "en_US",
            "detailLevel" => "ReturnAll",
        ),
        "payKey" => $key,
    );

    $http_header = array(
        "X-PAYPAL-SECURITY-USERID: $api_user",
        "X-PAYPAL-SECURITY-PASSWORD: $api_pass",
        "X-PAYPAL-SECURITY-SIGNATURE: $api_sig",
        "X-PAYPAL-REQUEST-DATA-FORMAT: JSON",
        "X-PAYPAL-RESPONSE-DATA-FORMAT: JSON",
        "X-PAYPAL-APPLICATION-ID: $app_id"
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($packet));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = json_decode(curl_exec($ch), true);
    return $response;
}

function getDataCredit($payment_id, $production) {


    if ($production == "TEST") {
        $url = "https://api.sandbox.paypal.com/v1/";
    } else {
        $url = "https://api.paypal.com/v1/";
    }

    $postvals = "grant_type=client_credentials";
    $uri = $url . "oauth2/token";
    //echo $uri;
    $auth_response = getCall($uri, 'POST', $postvals, true, 0);

    //print_r($auth_response); die;

    $access_token = $auth_response['body']->access_token;
    $token_type = $auth_response['body']->token_type;

    $response = process_payment($access_token, $payment_id, $url);
    return $response;
}

function getCall($url, $method = 'GET', $postvals = null, $auth = false, $token) {
    define("CLIENT_ID", "AW1g7hC_AKsalQyCvxjgeFi0KiJRo1fYfP29JiMAElOGagOWMrmWLfzjJsOm");
    define("CLIENT_SECRET", "EApQtRAvrdUsIqgCithaH-ijR6dRVqrbHUgQMGJtcBVXFCKjhOhHouuR4n8-");

    $ch = curl_init($url);
    if ($auth) {
        $headers = array("Accept: application/json", "Accept-Language: en_US");

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, CLIENT_ID . ":" . CLIENT_SECRET);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    } else {
        $headers = array("Content-Type:application/json", "Authorization:Bearer $token");
    }

    $options = array(
        CURLOPT_HEADER => true,
        CURLINFO_HEADER_OUT => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_VERBOSE => true,
        CURLOPT_TIMEOUT => 10
    );
    if ($method == 'POST') {
        $options[CURLOPT_POSTFIELDS] = $postvals;
        $options[CURLOPT_CUSTOMREQUEST] = $method;
    }

    //echo "<br>post val".print_r($postvals);

    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
    $body = json_decode(substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE)));
    curl_close($ch);
    return array('header' => $header, 'body' => $body);
}

function process_payment($token, $payment_id, $url) {
    $uri = $url . "payments/payment/$payment_id";
    return getCall($uri, 'GET', NULL, false, $token);
}

function update_credit($credit_purchase_id, $user_id) {
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
    $dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
    $dsp_credits_table = $wpdb->prefix . DSP_CREDITS_TABLE;
    $dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
    $dsp_user_table = $wpdb->prefix . users;

    $wpdb->update($dsp_credits_purchase_history, array('status' => 1), array('credit_purchase_id' => $credit_purchase_id)); /// update credit purchase table 

    $credit = $wpdb->get_var("select credit_purchased from $dsp_credits_purchase_history where credit_purchase_id='$credit_purchase_id'");
    $chk_credit_row = $wpdb->get_var("select count(*) from $dsp_credits_usage_table where user_id='$user_id'");
    $credit_row = $wpdb->get_row("select * from $dsp_credits_table");
    $emails_per_credit = $credit_row->emails_per_credit;
    $new_emails = $credit * $emails_per_credit;
    if ($chk_credit_row > 0) {
        $credit_usage_row = $wpdb->get_row("select * from $dsp_credits_usage_table where user_id='$user_id'");
        $wpdb->update($dsp_credits_usage_table, array('no_of_credits' => $credit_usage_row->no_of_credits + $credit,
            'no_of_emails' => $credit_usage_row->no_of_emails + $new_emails, 'email_sent' => 0), array(
            'user_id' => $user_id));
    } else {
        $wpdb->insert($dsp_credits_usage_table, array('no_of_credits' => $credit,
            'no_of_emails' => $new_emails, 'user_id' => $user_id));
    }
    $wpdb->query("update $dsp_credits_table set credits_purchased=credits_purchased+$credit");

    $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='20'");
    $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$user_id'");
    $reciver_name = $reciver_details->display_name;
    $receiver_email_address = $reciver_details->user_email;
    $siteurl = get_option('siteurl');
    $email_subject = $email_template->subject;
    $email_message = $email_template->email_body;
    $email_message = str_replace("<#AMOUNT-OF-CREDITS#>", $credit, $email_message);
    $MemberEmailMessage = $email_message;
    $to = $receiver_email_address;
    $subject = $email_subject;
    $message = $MemberEmailMessage;
    $admin_email = get_option('admin_email');
    $from = $admin_email;
    $headers = "From: $from";
    wp_mail($to, $subject, $message, $headers);
}

//new code as per New Paypal Mobile SDK
function verifyPaypalPaymentNew($payId, $clientId, $clientSecret) {
    
    //get the access token using clientId and clientSecret
    if (mobile_is_test_mode()) {
        $tokenUrl = "https://api.sandbox.paypal.com/v1/oauth2/token";
    } else {
        $tokenUrl = "https://api.paypal.com/v1/oauth2/token";
    }

    $headers = array("Accept: application/json", "Accept-Language: en_US");
    $postvals = "grant_type=client_credentials";
    $method = "POST";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvals);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    $header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
    $body = json_decode(substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE)));
    curl_close($ch);
    $arr_response = array('header' => $header, 'body' => $body);
    
    $access_token = $arr_response['body']->access_token;
    $token_type = $arr_response['body']->token_type;
    
    //make call to verify payment using above access_token
    if( !empty($access_token) ) {
//        $verifyUrl = "https://api.sandbox.paypal.com/v1/payments/payment/".$payId;
        if (mobile_is_test_mode()) {
            $verifyUrl = "https://api.sandbox.paypal.com/v1/payments/payment/" . $payId;
        } else {
            $verifyUrl = "https://api.paypal.com/v1/payments/payment/" . $payId;
        }
        $verifyHeader = array("Content-Type:application/json", "Authorization:Bearer $access_token");
        
        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, $verifyUrl);
        curl_setopt($ch1, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch1, CURLOPT_HEADER, true);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, $verifyHeader);
        $result = curl_exec($ch1);
        $header = substr($result, 0, curl_getinfo($ch1, CURLINFO_HEADER_SIZE));
        $body = json_decode(substr($result, curl_getinfo($ch1, CURLINFO_HEADER_SIZE)));
        $arr_verify_response = array('header' => $header, 'body' => $body);
        
        curl_close($ch1);
        return $arr_verify_response['body']->state;
    }
    return false;
    
}


function mobile_is_test_mode()
{
    global $wpdb;

    $dsp_gateways_table               = $wpdb->prefix . DSP_GATEWAYS_TABLE;

    global $wpdb;
    $gateway_row = $wpdb->get_row("SELECT * FROM $dsp_gateways_table WHERE `gateway_name` = 'paypal'");

    if ($gateway_row->test_mode == 1) {
        return true;
    }

    return false;
}

?>