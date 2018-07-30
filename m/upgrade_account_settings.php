<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_UPGRADE'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>
</div>

<?php
global $wpdb;

$user_payment_expired = false;

$upgrade_mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

$membership_plan = isset($_REQUEST['item_name']) ? $_REQUEST['item_name'] : '';

$membership_plan_id = isset($_REQUEST['membership_id']) ? $_REQUEST['membership_id'] : '';

$membership_plan_amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '';

$payment_date = date("Y-m-d");

$dsp_gateways_table = $wpdb->prefix . dsp_gateways;
$dsp_payments_table = $wpdb->prefix . dsp_payments;
$dsp_temp_payments_table = $wpdb->prefix . dsp_temp_payments;

$getUserPaymentExpiryDate = $wpdb->get_row("SELECT expiration_date FROM $dsp_payments_table WHERE pay_user_id =$user_id");
$user_payment_expiry_date = $getUserPaymentExpiryDate->expiration_date;

if(strtotime($payment_date) > strtotime($user_payment_expiry_date)) {
    $user_payment_expired = true;
}
else {
    $user_payment_expired = false;
}
// get the subscription detail from db
$getSubDetailQuery = "SELECT count(*) FROM $dsp_payments_table WHERE pay_user_id =$user_id AND recurring_profile_status = '1'";
$recurringPaymentSatatus = $wpdb->get_var($getSubDetailQuery);

?>
 
<?php if($user_payment_expired) { ?>
<div class="PaypaliPhone"> 
      <div class="ui-content" data-role="content">
    <div class="content-primary">
       <?php  echo language_code('DSP_USE_WEB_OR_ANDROID_APP_FOR_PAYMENT') ?>
    </div>
      </div>
</div>

<div class="PaypalAndroid" style="display:none;"> 
<div class="ui-content" data-role="content">
    <div class="content-primary">	
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">


            <?php
            if (($upgrade_mode == 'update') && $membership_plan_id != "") {

                $wpdb->query("DELETE FROM $dsp_temp_payments_table WHERE user_id = '$user_id'");

                $exist_membership_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table where membership_id='$membership_plan_id'");

                $plan_days = $exist_membership_plan->no_of_days;

                $wpdb->query("INSERT INTO $dsp_temp_payments_table SET user_id = '$user_id',plan_id = '$membership_plan_id',plan_amount ='$membership_plan_amount',plan_days='$plan_days',plan_name='$membership_plan',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL $plan_days DAY),payment_status=0");
                //echo "Query."."INSERT INTO $dsp_temp_payments_table SET user_id = '$user_id',plan_id = '$membership_plan_id',plan_amount ='$membership_plan_amount',plan_days='$plan_days',plan_name='$membership_plan',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL $plan_days DAY),payment_status=0";
                $exist_gateway_address = $wpdb->get_row("SELECT * FROM $dsp_gateways_table");

                $business = $exist_gateway_address->address;
                $currency_code = $exist_gateway_address->currency;
                ?>

                <form name="frm1" action="<?php echo $root_link; ?>?pid=6&pagetitle=dsp_paypal" method="post">

                    <input type="hidden" name="business" value="<?php echo $business ?>" />
                    <input type="hidden" name="currency_code" value="<?php echo $currency_code ?>" />
                    <input type="hidden" name="item_name" value="<?php echo $membership_plan ?>" />
                    <input type="hidden" name="item_number" value="<?php echo $user_id ?>" />
                    <input type="hidden" name="amount" value="<?php echo $membership_plan_amount ?>" />
                    <input type="Hidden" name="return" value="<?php echo $root_link; ?>?pid=6&pagetitle=upgrade_account_details">
                    <input type="hidden" name="notify_url" value="<?php echo $root_link; ?>?pid=6&pagetitle=upgrade_account_details">
                </form>  

                <script type="text/javascript">

                    document.frm1.submit();
                </script>
                <?php
            }


            $exists_memberships_plan = $wpdb->get_results("SELECT * FROM $dsp_memberships_table where display_status='Y' ORDER BY date_added DESC");


            foreach ($exists_memberships_plan as $membership_plan) {

                $currency_code_table = $wpdb->get_row("SELECT currency_symbol FROM $dsp_gateways_table");
                $currency_code = $currency_code_table->currency_symbol;

                $price = $membership_plan->price;
                $no_of_days = $membership_plan->no_of_days;
                $name = $membership_plan->name;
                $membership_id = $membership_plan->membership_id;
                $desc = $membership_plan->description;
                $image = $membership_plan->image;
                ?>

                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                    <ul style="list-style: none;padding-left: 5px;">

                        <li style=" width:100%; padding:5px; text-align:center;float: left;">
                            <div style="width: 35%;float: left;text-align: left;padding-right: 5px;">
                                <div style="text-align:center;">
                                    <img src='<?php echo $imagepath ?>/uploads/dsp_media/dsp_images/<?php echo $image; ?>' title="<?php echo $name ?>" />
                                </div>
                                <div style="font-size: 13px; font-weight: bold; text-align: center;">
                                    <?php echo $currency_code ?><?php echo $price ?>
                                </div>
                            </div>
                            <div style="width: 55%;text-align: center;float: left;">

                                <?php
                                if ($check_gateways_mode->setting_status == 'Y') {


                                    $gateway_table = $wpdb->get_results("SELECT * FROM $dsp_gateways_table");

                                    foreach ($gateway_table as $gateway) {
                                        $clientID = trim($gateway->paypal_client_id);
                                        $clientSecret = $gateway->paypal_secret;
                                        $apiUsername = $gateway->pro_api_username;
                                        $apiPassword = $gateway->pro_api_password;
                                        $apiSignature = $gateway->pro_api_signature;
                                        $business = trim($gateway->address);
                                        $currency_code = $gateway->currency;


                                        if ($gateway->gateway_name == 'paypal' && $gateway->status == 1) {

                                            if ($clientID != "" && $clientSecret != "" && $apiUsername != "" && $apiPassword != "" && $apiSignature != "" && $business != "" && $currency_code != "") {
                                                ?>

                                                <div style="padding-bottom: 10px;">
                                                    <input id="paypalBtn" type="button" name="paypalbtn" onclick="upgradePaypalMembership(<?php echo $membership_id; ?>)" value="<?php echo language_code('DSP_PAYPAL'); ?>"/>
                                                    <input name="memName" id="memName<?php echo $membership_id; ?>" value="<?php echo $name; ?>"  type="hidden" />
                                                    <input name="memAmount" id="memAmount<?php echo $membership_id; ?>" value="<?php echo $price; ?>"  type="hidden"  />
                                                    <input name="memCurrency" id="memCurrency<?php echo $membership_id; ?>" value="<?php echo $gateway->currency; ?>"  type="hidden" />
                                                    <input name="memClientId" id="memClientId<?php echo $membership_id; ?>" value="<?php echo $clientID; ?>"  type="hidden"  />
                                                    <input name="memBusiness" id="memBusiness<?php echo $membership_id; ?>" value="<?php echo $business; ?>"  type="hidden"  />
                                                </div>


                                                <?php
                                            } else {
                                                ?>
                                                <div style="padding-bottom: 10px;">
                                                    <input id="paypalBtn" type="button" name="paypalbtn" onclick="gateWayDisable('<?php echo language_code('DSP_PAYPAL_NOT_SET_YET'); ?>')" value="<?php echo language_code('DSP_PAYPAL'); ?>"/>
                                                </div>
                                                <?php
                                            }
                                        } else if ($gateway->gateway_name == 'authorize' && $gateway->status == 1) {
                                            ?>
                                            <div style="padding-bottom: 10px; display: none;">
                                                <input    id="paypalBtn" onclick="callUpgrade('auth_settings', '<?php echo $membership_id; ?>')" name="upgrade" title="Credit Card" type="button" value="<?php echo language_code('DSP_CREDIT_CARD') ?>"   style="text-decoration:none; margin-top:5px;" />
                                                <br />  
                                            </div>
                                            <?php
                                        } else if ($gateway->gateway_name == 'paypal pro' && $gateway->status == 1) {
                                            ?>
                                            <div style="padding-bottom: 10px;">
                                                <input    id="paypalBtn"  name="upgrade" title="PayPal Pro" type="button" value="<?php echo language_code('DSP_PAYPAL_PRO') ?>" onclick="callUpgrade('pro_settings', '<?php echo $membership_id; ?>')" style="text-decoration:none;" />

                                            </div>

                                            <?php
                                        } else if ($gateway->gateway_name == 'paypal advance' && $gateway->status == 1) {
                                            if ($recurringPaymentSatatus) {
                                                ?>
                                                <!--<div style="width: 100%;text-align: center;padding-bottom: 12px;font-weight: bold;font-size: 13px ">
                                                <?php echo language_code('DSP_U_ARE_ALREADY_A') . $name . ' ' . language_code('DSP_PLAN_MEMBER'); ?>
                                                </div>-->
                                                <?php
                                            } else {
                                                ?>
                                                <!--<div>
                                                   <form action="<?php
                                                echo add_query_arg(array('pid' => '6',
                                                    'pagetitle' => 'paypal_advance'), $root_link);
                                                ?>"  method="post">
                                                       <input type="hidden" name="item_name" id="item_name" value="<?php echo $name; ?>" />
                                                       
                                                       <input type="hidden" name="amount" id="amount" value="<?php echo $price; ?>" /> 
                                                       
                                                       <input type="hidden" name="no_days" id="no_days" value="<?php echo $no_of_days; ?>" /> 
                                                        
                                                       
                                                       <input type="hidden" name="membership_id" id="membership_id" value="<?php echo $membership_id; ?>" /> 
                                                        
                                                       <input class="subscribe" name="btn_advance" type="submit"  value="" />
                                                   </form>
                                                   
                                                    <span style="font-size:13px; font-weight:bold;"><?php echo $currency_code ?><?php echo $price ?></span>  <br />
                                               </div>-->
                                                <?php
                                            }
                                        } else if ($gateway->gateway_name == 'iDEAL' && $gateway->status == 1) {
                                            ?>
                                            <!--<div>
                                                <input name="upgrade" title="Upgrade / iDEAL" type="button" value="<?php echo language_code('DSP_UPGRADE_IDEAL_BTN') ?>" onclick="window.location='<?php
                                            echo add_query_arg(array(
                                                'pid' => '6', 'pagetitle' => 'iDEAL',
                                                'id' => $membership_id), $root_link);
                                            ?>'" class="dsp_span_pointer" style="text-decoration:none;" />
                                                <br />  <span style="font-size:13px; font-weight:bold;"><?php echo $currency_code ?><?php echo $price ?></span>  <br />	
                                        </div> -->

                                            <?php
                                        }
                                    } // end of for each loop	
                                } //if($check_gateways_mode->setting_status == 'Y'){
                                else {
                                    $gateway_row = $wpdb->get_row("SELECT * FROM $dsp_gateways_table where status='1' and gateway_id=1");
                                    // it will display only paypal gateway
                                    $clientID = trim($gateway_row->paypal_client_id);
                                    $clientSecret = $gateway_row->paypal_secret;
                                    $apiUsername = $gateway_row->pro_api_username;
                                    $apiPassword = $gateway_row->pro_api_password;
                                    $apiSignature = $gateway_row->pro_api_signature;
                                    $business = trim($gateway_row->address);
                                    $currency_code = $gateway_row->currency;


                                    if ($gateway_row->gateway_name == 'paypal') {
                                        if ($clientID != "" && $clientSecret != "" && $apiUsername != "" && $apiPassword != "" && $apiSignature != "" && $business != "" && $currency_code != "") {
                                            ?>

                                            <div style="padding-bottom: 10px;">
                                                <input id="paypalBtn" type="button" name="paypalbtn" onclick="upgradePaypalMembership(<?php echo $membership_id; ?>)" value="<?php echo language_code('DSP_PAYPAL'); ?>"/>
                                                <input name="memName" id="memName<?php echo $membership_id; ?>" value="<?php echo $name; ?>"  type="hidden" />
                                                <input name="memAmount" id="memAmount<?php echo $membership_id; ?>" value="<?php echo $price; ?>"  type="hidden"  />
                                                <input name="memCurrency" id="memCurrency<?php echo $membership_id; ?>" value="<?php echo $currency_code; ?>"  type="hidden" />
                                                <input name="memClientId" id="memClientId<?php echo $membership_id; ?>" value="<?php echo $clientID; ?>"  type="hidden"  />
                                                <input name="memBusiness" id="memBusiness<?php echo $membership_id; ?>" value="<?php echo $business; ?>"  type="hidden"  />
                                            </div>

                                            <?php
                                        } else {
                                            ?>
                                            <div style="padding-bottom: 10px;">
                                                <input style="width: 100px;" type="button" name="paypalbtn" onclick="gateWayDisable('<?php echo language_code('DSP_PAYPAL_NOT_SET_YET'); ?>')" value="<?php echo language_code('DSP_PAYPAL'); ?>"/>
                                            </div>
                                            <?php
                                        }
                                    }
                                } // else if($check_gateways_mode->setting_status == 'N'){	
                                ?>




                            </div>
                            <div style="text-align: left; word-wrap: break-word;padding-top: 10px;float: left;width: 100%;">	
                                <?php echo $desc; ?>
                            </div>
                        </li>



                    </ul>



                </li>


                <?php
            }

            /* Credit work */

            if ($check_credit_mode->setting_status == 'Y') {
                include(WP_DSP_ABSPATH . "/m/credit_upgrade_account_settings.php");
            }
            ?>


        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up       ?>
</div>
  </div>
 
<script type="text/javascript">

    function payment(item_name, amount, id)
    {
        alert(' paymanet  ' + item_name + ' ' + amount + ' ' + id);

        document.paymentfrm.item_name.value = item_name;

        document.paymentfrm.amount.value = amount;

        document.paymentfrm.membership_id.value = id;

//document.paymentfrm.submit();

    }

</script>
<?php } else { ?>
<div style="width: 100%; text-align: center; padding:20px 10px; font-weight: bold;">
    Your account has not expired yet.
</div>
<?php } ?>
<?php
//-------------------------------END UPGRADE ACCOUNT SETTINGS ---------------------------------- ?>