<?php
global $wpdb;
$DSP_PAYMENTS_TABLE = $wpdb->prefix . DSP_PAYMENTS_TABLE;
$DSP_GATEWAYS_TABLE = $wpdb->prefix . DSP_GATEWAYS_TABLE;
$getMemProfIDQuery = "select recurring_profile_id from $DSP_PAYMENTS_TABLE where pay_user_id=$user_id and recurring_profile_status='1'";
$recurring_profile_res = $wpdb->get_row($getMemProfIDQuery);
if (count($recurring_profile_res) > 0) {
    $recurring_profile_id = $recurring_profile_res->recurring_profile_id;
    if ($recurring_profile_id) {
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

                $test_mode = 0; // 1 = true, 0 = production
                if ($test_mode == 1) {
                    $submiturl = 'https://pilot-payflowpro.paypal.com';
                } else {
                    $submiturl = 'https://payflowpro.paypal.com';
                }
                // body
                $plist = 'USER=' . $PF_USER . '&';
                $plist .= 'VENDOR=' . $PF_USER . '&';
                $plist .= 'PARTNER=paypal' . '&';
                $plist .= 'PWD=' . $PF_PWD . '&';
                $plist .= 'TENDER=' . 'P' . '&'; // C = credit card, P = PayPal
                $plist .= 'TRXTYPE=' . 'R' . '&'; //  S = Sale transaction, A = Authorisation, C = Credit, D = Delayed Capture, V = Void                        

                $plist .= 'ACTION=C' . '&';
                $plist .= 'ORIGPROFILEID=' . $recurring_profile_id . '&';

                // verbosity
                $plist .= 'VERBOSITY=MEDIUM';




                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $submiturl);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                // Turn off the server and peer verification (TrustManager Concept).
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

                // Set the request as a POST FIELD for curl.
                curl_setopt($ch, CURLOPT_POSTFIELDS, $plist);

                $result = curl_exec($ch);

                curl_close($ch);

                $pfpro = get_curl_result($result); //result arrray

                if (isset($pfpro['RESULT']) && $pfpro['RESULT'] == 0) {
                    //   echo '<br>success'.print_r($pfpro);
                    // update payment table
                    $updatequery = "update  $DSP_PAYMENTS_TABLE set recurring_profile_status='0' where pay_user_id=$user_id";
                    $wpdb->query($updatequery);
                    ?>
                    <div class="dsp_search_result_box_out">
                        <div class="dsp_search_result_box_in">

                            <?php echo language_code('DSP_MEMBERSHIP_HAS_CANCELED');  //echo '<br>'.$pfpro['RESPMSG'] ; ?>

                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="dsp_search_result_box_out">
                        <div class="dsp_search_result_box_in">

                            <?php echo language_code('DSP_MEMBERSHIP_NOT_CANCELED_TRY_AGAIN') . '<br>' . $pfpro['RESPMSG']; ?>

                        </div>
                    </div>
                    <?php
                }
            }// end of gateway if
            else {
                ?>
                <div class="dsp_search_result_box_out">
                    <div class="dsp_search_result_box_in">
                        <?php echo language_code('DSP_MEMBERSHIP_NOT_CANCELED_TRY_AGAIN'); ?>

                    </div>
                </div>
                <?php
            }
        }// end of gatewy row if
        else {
            ?>
            <div class="dsp_search_result_box_out">
                <div class="dsp_search_result_box_in">
                    <?php echo language_code('DSP_MEMBERSHIP_NOT_CANCELED_TRY_AGAIN'); ?>

                </div>
            </div>
            <?php
        }
    } // end of if recurriong id found 
    else {
        ?>
        <div class="dsp_search_result_box_out">
            <div class="dsp_search_result_box_in">
                <?php echo language_code('DSP_YOU_DONT_HAVE_ANY_ACTIVE_MEMBERSHIP'); ?>

            </div>
        </div>
        <?php
    }
} // end of user payment does not exist
else {
    ?>
    <div class="dsp_search_result_box_out">
        <div class="dsp_search_result_box_in">
            <?php echo language_code('DSP_YOU_DONT_HAVE_ANY_ACTIVE_MEMBERSHIP'); ?>

        </div>
    </div>
    <?php
}
