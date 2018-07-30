<?php
global $wpdb;
$dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
$DSP_GATEWAYS_TABLE = $wpdb->prefix . DSP_GATEWAYS_TABLE;
$payPeriod = "MONTH";
if (isset($_REQUEST['RESULT'])) {
    $result = $_REQUEST['RESULT'];
    if ($result == 0) {

        //get the transaction type=
        $tender = $_REQUEST['TENDER'];

        if ($tender == "P") { // using paypal we will fetch PPREF
            $transactionId = $_REQUEST['PPREF'];
        } else if ($tender == "CC") { // using credit card  we will fetch PNREF
            $transactionId = $_REQUEST['PNREF'];
        } else {
            $transactionId = $_REQUEST['PPREF'];
        }

        $amt = $_REQUEST['AMT'];
        $dateTimeFormat = dsp_get_date_timezone();
        extract($dateTimeFormat);
        $date = date("$dateFormat"); // current date
        $date = strtotime(date("$dateFormat", strtotime($date)) . " +30 days"); //we will give date after one month

        $startDate = date("$dateFormat", $date);

        function get_curl_result($result) {
            if (empty($result))
                return;

            $pfpro = array();
            $result = strstr($result, 'RESULT');
            $valArray = explode('&', $result);
            foreach ($valArray as $val) {
                $valArray2 = explode('=', $val);
                $pfpro[$valArray2[0]] = $valArray2[1];
            }
            return $pfpro;
        }

        $getPlanDetail = $wpdb->get_row("SELECT plan_days FROM $dsp_temp_payments_table where user_id='$user_id'");

        if (count($getPlanDetail) > 0) {
            $plan_day = $getPlanDetail->plan_days;
            if (!$plan_day) { // if there is no plan day
                $plan_day = 30;
            }
        } else {
            $plan_day = 30;
        }

        if ($plan_day < 15) {
            $payPeriod = "WEEK"; // EVERY WEEK 
        } else if ($plan_day >= 15 && $plan_day < 30) {
            $payPeriod = "SMMO"; // TWISE IN A MONTH
        } else if ($plan_day >= 30 && $plan_day < 60) {
            $payPeriod = "MONTH"; // EVERY MONTH
        } else if ($plan_day >= 60 && $plan_day < 100) {
            $payPeriod = "QTER"; // WVERY 3 MONTH 
        } else if ($plan_day > 100) {
            $payPeriod = "YEAR"; // EVERY YEAR
        }


        //$PF_HOST_ADDR="https://pilot-payflowpro.paypal.com"; // test
        //live

        $PF_HOST_ADDR = "https://payflowpro.paypal.com";
        // get api details		

        $apiDetailsQuery = "SELECT paypal_adv_username,paypal_adv_password FROM $DSP_GATEWAYS_TABLE where gateway_id=4"; // paypal advance 
        //echo '<br>'.$apiDetailsQuery;
        $apiDetailsRes = $wpdb->get_row($apiDetailsQuery);

        if (count($apiDetailsRes) > 0) {
            $PF_USER = trim($apiDetailsRes->paypal_adv_username);
            $PF_VENDOR = trim($apiDetailsRes->paypal_adv_username);
            $PF_PARTNER = "PayPal";
            $PF_PWD = trim($apiDetailsRes->paypal_adv_password);

            if ($PF_USER != "" && $PF_PWD != "") {
                //initialize and setup request

                $postData = "TRXTYPE=R&TENDER=" . $tender .
                    "&PARTNER=PayPal" .
                    "&VENDOR=" . $PF_USER .
                    "&USER=" . $PF_USER .
                    "&PWD=" . $PF_PWD .
                    "&ACTION=A&PROFILENAME=Member-Subscription" .
                    "&ORIGID=" . $transactionId .
                    "&START=" . $startDate .
                    "&PAYPERIOD=" . $payPeriod .
                    "&TERM=0&COMMENT1=Dating-site-customer" .
                    "&AMT=" . $amt;
                //echo $postData.'<br><br>';

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
                $result = curl_exec($ch);



                $pfpro = get_curl_result($result); //result arrray
                // print_r($result);

                if (isset($pfpro['RESULT']) && $pfpro['RESULT'] == 0) {
                    $recur_profile_id = $pfpro['PROFILEID'];
                    $updateQuery = "Update $dsp_temp_payments_table set recurring_profile_id ='$recur_profile_id' where user_id=$user_id";

                    $wpdb->query($updateQuery);
                    //print_r($pfpro);
                    ?>
                    <script type='text/javascript'> location.href = '<?php echo $root_link . "setting/dsp_thank_you"; ?>'</script>
                <?php } else { ?>
                    <div class="dsp_search_result_box_in">
                        <div class="dsp_search_result_box_in">


                            <?php echo language_code('DSP_UR_SUBSCRIPTION_PROCEDURE_HAS_FAILED') . '<br>' . $pfpro['RESPMSG']; ?>
                        </div>
                    </div>	
                <?php
                }
            } // end of api credential check
            else {
                ?>
                <div class="dsp_search_result_box_in">
                    <div class="dsp_search_result_box_in">
                        <?php echo language_code('DSP_UR_SUBSCRIPTION_PROCEDURE_HAS_FAILED'); ?>
                    </div>
                </div>
                <?php
            }
        }// end of api credential check if
        else {
            ?>
            <div class="dsp_search_result_box_in">
                <div class="dsp_search_result_box_in">
                    <?php echo language_code('DSP_UR_SUBSCRIPTION_PROCEDURE_HAS_FAILED'); ?>
                </div>
            </div>
            <?php
        }
    } // transaction failed
    else {
        ?>

        <div class="dsp_search_result_box_out">
            <div class="dsp_search_result_box_in">

                <?php echo language_code('DSP_TRANSACTION_NOT_COMPLETED'); ?>
            </div>
        </div>
        <?php
    }
}// no request found check
else {
    ?>

    <div class="dsp_search_result_box_out">
        <div class="dsp_search_result_box_in">
            <?php echo language_code('DSP_TRANSACTION_NOT_COMPLETED'); ?>

        </div>
    </div>
    <?php
}
