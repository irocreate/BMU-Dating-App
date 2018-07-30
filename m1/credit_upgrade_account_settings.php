<?php
//-------------------------------START UPGRADE ACCOUNT SETTINGS ---------------------------------- 
//echo 'asdasd';
//include_once('dsp_upgrade_paypal_advance.php');
//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
global $wpdb;


extract($_REQUEST);



$credit_row = $wpdb->get_row("select * from $dsp_credits_table");
$currency_code = $wpdb->get_var("SELECT currency_symbol FROM $dsp_gateways_table");
?>
   
        <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
             <div class="dsp_mail_lf">
                    <img src='<?php echo WPDATE_URL . "/images/credit_purchase.png" ?>' />
               
                <div style="font-size: 13px; font-weight: bold; text-align: center;padding-top: 10px;">
                    <div style="padding-bottom: 10px;">
                        <input id="no_of_credits" type="text" value=""  onkeyup="change_credit(this.value);" />
                        <span><?php echo language_code('DSP_CREDIT_MODE'); ?></span>
                    </div>
                    <?php echo $currency_code ?><?php echo $credit_row->price_per_credit; ?>&nbsp;<?php echo language_code('DSP_PER_CREDIT'); ?>
                    <input type="hidden" id="price_per_credit" value="<?php echo $credit_row->price_per_credit; ?>" />
                </div>
              </div>
          

            <div style="width: 55%;text-align: center;float: left;" class="tracker-detail">

                <?php
                $gateway_table = $wpdb->get_results("SELECT * FROM $dsp_gateways_table");
                foreach ($gateway_table as $gateway) {

                    //echo '<br>name'.$gateway->gateway_name;
                    if ($gateway->gateway_name == 'paypal' && $gateway->status == 1) {
                        $clientID = trim($gateway->paypal_client_id);
                        $business = trim($gateway->address);
                        $currencyCode = trim($gateway->currency);
                        $clientSecret = $gateway->paypal_secret;
                        $apiUsername = $gateway->pro_api_username;
                        $apiPassword = $gateway->pro_api_password;
                        $apiSignature = $gateway->pro_api_signature;

                        if ($clientID != "" && $clientSecret != "" && $apiUsername != "" && $apiPassword != "" && $apiSignature != "" && $business != "" && $currency_code != "") {
                            ?>
                            <div class="btn-blue-wrap">
                                <form id="frm_paypal" name="frm_paypal" >

                                    <input type="hidden"  name="credit_amount" id="credit_amount" class="credit_amount" value="<?php echo $credit_row->price_per_credit; ?>" /> 

                                    <input type="hidden" class="no_of_credit_to_purchase" name="no_of_credit_to_purchase" value="1" />

                                    <input type="hidden" id="creditCurrencyCode" name="creditCurrencyCode" value="<?php echo $currencyCode ?>" />
                                    <input type="hidden" id="creditClientId" name="creditClientId" value="<?php echo $clientID ?>" />
                                    <input type="hidden" id="creditBusiness" name="creditBusiness" value="<?php echo $business ?>" />
                                    <input type="hidden" id="creditTitle" name="creditTitle" value="<?php echo language_code('DSP_PURCHASE_CREDITS'); ?>" />
                                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id ?>" />

                                    <input id="paypalBtn" onclick="getCredit()" class="mam_btn btn-blue" name="upgrade_credit" title="Upgrade / PayPal" type="button" value="<?php echo language_code('DSP_PAYPAL') ?>"  style="text-decoration:none;" />
                                    <br />  
                                    <span style="font-size:13px; font-weight:bold;" class="credit_price_change"><?php echo $currency_code ?><?php echo $credit_row->price_per_credit; ?></span>  <br />	
                                </form>
                            </div>


                            <?php
                        } else {
                            ?>
                            <div class="btn-blue-wrap">
                                <input id="paypalBtn" type="button" name="paypalbtn" class="mam_btn btn-blue" onclick="gateWayDisable('<?php echo language_code('DSP_PAYPAL_NOT_SET_YET'); ?>')" value="<?php echo language_code('DSP_PAYPAL'); ?>"/>
                            </div>
                            <?php
                        }
                    } else if ($gateway->gateway_name == 'authorize' && $gateway->status == 1) {
                        ?>
                        <div class="btn-blue-wrap">
                            <form id="frm_auth">
                                <input type="hidden" name="credit_amount" class="credit_amount" value="<?php echo $credit_row->price_per_credit; ?>" /> 
                                <input type="hidden"  name="user_id" value="<?php echo $user_id; ?>" />
                                <input type="hidden"  name="pagetitle" value="credit_auth_settings" />
                                <input type="hidden" class="no_of_credit_to_purchase" name="no_of_credit_to_purchase" value="1" />
                                <input onclick="callUpgrade('credit_auth_setting', '0')"  id="paypalBtn" class="mam_btn btn-blue" name="upgrade" title="Upgrade / Credit Card" type="button" value="<?php echo language_code('DSP_CREDIT_CARD') ?>" class="dsp_span_pointer" style="text-decoration:none; margin-top:5px;" />
                            </form>
                            <span style="font-size:13px; font-weight:bold;" class="credit_price_change">
                                <?php echo $currency_code ?><?php echo $credit_row->price_per_credit; ?>
                            </span>  
                        </div>
                        <?php
                    } else if ($gateway->gateway_name == 'paypal pro' && $gateway->status == 1) {
                        ?>
                        <div class="btn-blue-wrap">
                            <form id="frm_pro" name="frm_pro" >
                                <input onclick="callUpgrade('credit_pro_settings', '0')" id="paypalBtn" class="mam_btn btn-blue" name="upgrade" title="Upgrade / PayPal Pro" type="button" value="<?php echo language_code('DSP_PAYPAL_PRO') ?>" />
                                <input type="hidden" name="credit_amount" class="credit_amount" value="<?php echo $credit_row->price_per_credit; ?>" /> 
                                <input type="hidden"  name="user_id" value="<?php echo $user_id; ?>" />
                                <input type="hidden"  name="pagetitle" value="credit_pro_settings" />          
                                <input type="hidden" class="no_of_credit_to_purchase" name="no_of_credit_to_purchase" value="1" />
                            </form>
                            <span style="font-size:13px; font-weight:bold;" class="credit_price_change"><?php echo $currency_code ?><?php echo $credit_row->price_per_credit; ?></span>  
                        </div>
                        <?php
                    } else if ($gateway->gateway_name == 'paypal advance' && $gateway->status == 1) {
                        /* in dating app we will not show payopal recurring  payment option
                         *  if($recurringPaymentSatatus)
                          { ?>
                          <div style="width: 100%;text-align: center;padding-bottom: 12px;font-weight: bold;font-size: 13px ">
                          <?php echo language_code('DSP_U_ARE_ALREADY_A'). $name.' '.language_code('DSP_PLAN_MEMBER');?>
                          </div>
                          <?php }
                          else
                          { 				?>
                          <div>
                          <form action="<?php echo add_query_arg (array('pid' =>'6','pagetitle'=>'paypal_advance'), $root_link);?>"  method="post">
                          <input type="hidden" name="item_name" id="item_name" value="Credit Purchase" />

                          <input type="hidden" name="credit_amount" class="credit_amount" value="<?php echo $credit_row->price_per_credit;?>" />

                          <input type="hidden" class="no_of_credit_to_purchase" name="no_of_credit_to_purchase" value="" />
                          <input type="hidden" name="payment_action" value="credit" />
                          <!--<input type="submit" value="<?php echo language_code('DSP_UPGRADE_PAYPALADV_BTN')?>" name="btn_advance" />-->
                          <input id="paypalBtn" class="subscribe" name="btn_advance" type="submit"  value="" />
                          </form>

                          <span style="font-size:13px; font-weight:bold;" class="credit_price_change"><?php echo $currency_code?><?php echo $credit_row->price_per_credit;?></span>  <br />
                          </div>
                          <?php
                          } */
                    } else if ($gateway->gateway_name == 'iDEAL' && $gateway->status == 1) {
                        /* in dating app we will not show  iDEAL payment option
                          ?>
                          <div style="padding-bottom: 10px;">
                          <form action="<?php echo add_query_arg (array('pid' =>'6','pagetitle'=>'credit_iDEAL'), $root_link);?>"  method="post">
                          <input id="paypalBtn" name="upgrade" title="Upgrade / iDEAL" type="submit" value="<?php echo language_code('DSP_UPGRADE_IDEAL_BTN')?>" class="dsp_span_pointer" style="text-decoration:none;" />
                          <input type="hidden" name="credit_amount" class="credit_amount" value="<?php echo $credit_row->price_per_credit;?>" />

                          <input type="hidden" class="no_of_credit_to_purchase" name="no_of_credit_to_purchase" value="" />
                          </form>
                          <span style="font-size:13px; font-weight:bold;" class="credit_price_change"><?php echo $currency_code?><?php echo $credit_row->price_per_credit;?></span>
                          </div>
                          <?php */
                    }
                } // end of for each loop	
                ?>


            </div>
            <div style="text-align: left; word-wrap: break-word;padding-top: 10px;float: left;width: 100%;">
                <?php echo language_code('DSP_PURCHASE_CREDITS'); ?>
            </div>
       

