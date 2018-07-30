<?php
global $wpdb;
$dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
$DSP_GATEWAYS_TABLE = $wpdb->prefix . DSP_GATEWAYS_TABLE;
if (isset($_POST['btn_advance'])) {
    extract($_REQUEST);
    $wpdb->query("DELETE FROM $dsp_temp_payments_table WHERE user_id = '$user_id'");
    if (isset($payment_action) && $payment_action == 'credit') {
        $amt = $credit_amount;
        $credit_purchase_data = array('user_id' => $user_id,
            'status' => 0,
            'credit_price' => $amt,
            'credit_purchased' => $no_of_credit_to_purchase,
            'purchase_date' => date('Y-m-d H:i:s'));

        $wpdb->insert($dsp_credits_purchase_history, $credit_purchase_data);
    } else {
        $amt = $_REQUEST['amount'];
        $membership_plan_id = $_REQUEST['membership_id'];
        $plan = $_REQUEST['item_name'];
        $plan_days = $_REQUEST['no_days'];
        $text = "Upgrade/Paypal Advance";
        //echo '$user_id'.$user_id.'  '.$amt.'  '.$plan_days.' id='.$membership_plan_id.' plan='.$plan;



        $membership_plan = $plan;
        $membership_plan_amount = $amt;
        $payment_date = date('Y-m-d');
        //echo "DELETE FROM $dsp_temp_payments_table WHERE user_id = '$user_id'";
        //echo "<br>INSERT INTO $dsp_temp_payments_table SET user_id = '$user_id',plan_id = '$membership_plan_id',plan_amount ='$amt',plan_days='$plan_days',plan_name='$membership_plan',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL $plan_days DAY),payment_status=0";
        $wpdb->query("INSERT INTO $dsp_temp_payments_table SET user_id = '$user_id',plan_id = '$membership_plan_id',plan_amount ='$amt',plan_days='$plan_days',plan_name='$membership_plan',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL $plan_days DAY),payment_status=0");


        $check_already_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_temp_payments_table where user_id='$user_id'");

        if ($check_already_user_exists <= 0) {
            $wpdb->query("INSERT INTO $dsp_temp_payments_table SET user_id = '$user_id',plan_id = '$membership_plan_id',plan_amount ='$amt',plan_days='$plan_days',plan_name='$membership_plan',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL $plan_days DAY),payment_status=0");
        }
    }

    $apiDetailsQuery = "SELECT paypal_adv_username,paypal_adv_password FROM $DSP_GATEWAYS_TABLE where gateway_id=4"; // paypal advance 
    //echo '<br>'.$apiDetailsQuery;

    $apiDetailsRes = $wpdb->get_row($apiDetailsQuery);
    if (count($apiDetailsRes) > 0) {
        $PF_USER = trim($apiDetailsRes->paypal_adv_username);
        $PF_VENDOR = trim($apiDetailsRes->paypal_adv_username);
        $PF_PARTNER = "PayPal";
        $PF_PWD = trim($apiDetailsRes->paypal_adv_password);

        //echo $apiDetailsRes->paypal_adv_username.'  pwd='.$apiDetailsRes->paypal_adv_password;
        if ($PF_USER != "" && $PF_PWD != "") {
            //echo $apiDetailsRes->paypal_adv_username.'  pwd='.$apiDetailsRes->paypal_adv_password;
            //$PF_MODE="TEST";
            //$PF_HOST_ADDR="https://pilot-payflowpro.paypal.com"; // test
            //live

            $PF_MODE = "LIVE";
            $PF_HOST_ADDR = "https://payflowpro.paypal.com";


            $SECURE_TOKEN_ID = uniqid('', true);

            $postData = "USER=" . $PF_USER .
                "&VENDOR=" . $PF_VENDOR .
                "&PARTNER=" . $PF_PARTNER .
                "&PWD=" . $PF_PWD .
                "&CREATESECURETOKEN=Y" .
                "&SECURETOKENID=" . $SECURE_TOKEN_ID .
                "&TRXTYPE=S" .
                "&AMT=" . $amt;
//echo $postData;
            //initialize and setup request

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $PF_HOST_ADDR);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            // Turn off the server and peer verification (TrustManager Concept).
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            // Set the request as a POST FIELD for curl.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

            // Get response from the server.
            $httpResponse = curl_exec($ch);

            if (!$httpResponse) {
                ?>
                <div class="dsp_search_result_box_out">
                    <div class="dsp_search_result_box_in">
                        <?php echo language_code('DSP_PAYPAL_ADV_TRYAGAIN_TEXT') ?>

                    </div>
                </div>
                <?php
            }
            //parse and assign to array
            $arr = array();
            parse_str($httpResponse, $arr);

            if ($arr['RESULT'] != 0) { // handel error server failor
                ?>
                <div class="dsp_search_result_box_out">
                    <div class="dsp_search_result_box_in">
                        <?php echo language_code('DSP_PAYPAL_ADV_TRYAGAIN_TEXT') ?>

                    </div>
                </div>
                <?php
                exit();
            }
            ?>
            <html>
                <head><title><?php echo language_code('DSP_PAYMENT_PROCESSING') ?></title></head>
                <body onLoad="document.forms['frm1_adv'].submit();" >
                <center><h2><?php echo language_code('DSP_PAYPAL_ADV_PROCESS_TEXT_A') ?></body></html> <?php echo language_code('DSP_PAYPAL_ADV_PROCESS_TEXT_B') ?> <br> <?php echo language_code('DSP_PAYPAL_ADV_PROCESS_TEXT_C') ?></h2></center>
                <form name="frm1_adv" method="post" action="https://payflowlink.paypal.com">
                    <input type="hidden" name="SECURETOKEN" value="<?php echo $arr['SECURETOKEN']; ?>" />
                    <input type="hidden" name="SECURETOKENID" value="<?php echo $SECURE_TOKEN_ID; ?>" />
                    <input type="hidden" name="MODE" value="<?php echo $PF_MODE; ?>" />
                    <center><br/><br/><?php echo language_code('DSP_PAYPAL_REDIRECT_TEXT') ?><br/><br/>

                        <input type="submit" value="Click Here" /></center>
                </form>
            </body>
            </html>
            <?php
        } else {
            ?>
            <div class="dsp_search_result_box_out">
                <div class="dsp_search_result_box_in">
                    <?php echo language_code('DSP_PAYPAL_ADV_TRYAGAIN_TEXT') ?>

                </div>
            </div>
            <?php
            exit();
        }
    } else {
        ?>
        <div class="dsp_search_result_box_out">
            <div class="dsp_search_result_box_in">
                <?php echo language_code('DSP_PAYPAL_ADV_TRYAGAIN_TEXT') ?>

            </div>
        </div>
        <?php
        exit();
    }
} else {
    ?>
    <div class="dsp_search_result_box_out">
        <div class="dsp_search_result_box_in">
            <?php echo language_code('DSP_PAYPAL_ADV_TRYAGAIN_TEXT') ?>

        </div>
    </div>

    <?php
    exit();
}


