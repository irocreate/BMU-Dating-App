<?php

add_action('dsp_api_wpdating_gateway_google', 'check_google_inapp_response', 10, 0);

function check_google_inapp_response()
{

    $error = '';
    if (isset($_POST['pay_user_id']) && ! empty($_POST['pay_user_id'])) {
        $user_id = $_POST['pay_user_id'];
    } else {
        $error .= 'User id not received <br/>';
    }
    if (isset($_POST['type']) && ! empty($_POST['type'])) {
        $type = $_POST['type'];
    } else {
        $error .= 'User Type not received <br/>';

    }
    if (isset($_POST['order_id']) && ! empty($_POST['order_id'])) {
        $order_id = $_POST['order_id'];
    } else {
        $error .= 'Order id not received <br/>';
    }
    if (isset($_POST['receipt']) && ! empty($_POST['receipt'])) {
        $receipt = $_POST['receipt'];
    } else {
        $error .= 'Receipt not received <br/>';
    }
    if (isset($_POST['pay_plan_id']) && ! empty($_POST['pay_plan_id'])) {
        $membership_id = $_POST['pay_plan_id'];
    } else {
        $error .= 'membership_id not received <br/>';
    }
    if (isset($_POST['signature']) && ! empty($_POST['signature'])) {
        $signature = $_POST['signature'];
    } else {
        $error .= 'Signature not received <br/>';
    }
    if (isset($_POST['public_key']) && ! empty($_POST['public_key'])) {
        $public_key = $_POST['public_key'];
    } else {
        $error .= 'Public key not received <br/>';
    }
    if (isset($_POST['purchase_token']) && ! empty($_POST['purchase_token'])) {
        $purchase_token = $_POST['purchase_token'];
    } else {
        $error .= 'Purchase token not received <br/>';
    }
    if (isset($_POST['pay_plan_amount']) && ! empty($_POST['pay_plan_amount'])) {
        $price = $_POST['pay_plan_amount'];
    } else {
        $error .= 'price not received <br/>';
    }
    if (isset($_POST['pay_plan_days']) && ! empty($_POST['pay_plan_days'])) {
        $subscriptionPeriod = $_POST['pay_plan_days'];
    } else {
        $error .= 'Subscription Period not received <br/>';
    }
    if (isset($_POST['pay_plan_name']) && ! empty($_POST['pay_plan_name'])) {
        $pay_plan_name = $_POST['pay_plan_name'];
    } else {
        $error .= 'Plan name not received <br/>';
    }
    $payment_date = date("Y-m-d");
    if ( ! empty($error)) {
        print_r($error);

        return;
    }
    $str          = str_replace('\\', '', $receipt);
    $verification = verify_market_in_app($str, $signature, $public_key);

    if ($verification == true) {
        global $wpdb;
        $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
        $wpdb->query("INSERT INTO $dsp_payments_table SET pay_user_id = $user_id,pay_plan_id = '$membership_id',pay_plan_amount = '$price',pay_plan_days= '$subscriptionPeriod',pay_plan_name= '$pay_plan_name',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL '$subscriptionPeriod' DAY),payment_status=1");
        wp_send_json("Payment Successful");
    } else {
        echo "Payment Failed ";
    }
}

function verify_market_in_app($receipt, $signature, $public_key)
{
    $key = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($public_key, 64, "\n") . '-----END PUBLIC KEY-----';
    //using PHP to create an RSA key
    $key = openssl_get_publickey($key);
    //$signature should be in binary format, but it comes as BASE64.
    //So, I'll convert it.
    $signature = base64_decode($signature);
    //using PHP's native support to verify the signature
    $result = openssl_verify($receipt, $signature, $key, OPENSSL_ALGO_SHA1);
    if (0 === $result) {
        return "false";
    } else if (1 !== $result) {
        return "false";
    } else {
        return "true";
    }
}
