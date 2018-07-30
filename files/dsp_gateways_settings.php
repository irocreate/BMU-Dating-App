<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_gateways_table = $wpdb->prefix . DSP_GATEWAYS_TABLE;
$goback = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$dsp_mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$payment_method = isset($_REQUEST['payment_method']) ? $_REQUEST['payment_method'] : '';
$dsp_gateway_address = isset($_REQUEST['gateway_address']) ? $_REQUEST['gateway_address'] : '';
$dsp_gateway_currency = isset($_REQUEST['currency']) ? $_REQUEST['currency'] : '';
$dsp_paypal_api_uname = isset($_REQUEST['paypal_api_uname']) ? $_REQUEST['paypal_api_uname'] : '';
$dsp_paypa_api_pwd = isset($_REQUEST['paypa_api_pwd']) ? $_REQUEST['paypa_api_pwd'] : '';
$dsp_paypal_signature = isset($_REQUEST['paypal_signature']) ? $_REQUEST['paypal_signature'] : '';
$dsp_paypal_clientid = isset($_REQUEST['paypal_clientid']) ? $_REQUEST['paypal_clientid'] : '';
$dsp_paypal_secret = isset($_REQUEST['paypal_secret']) ? $_REQUEST['paypal_secret'] : '';
$dsp_gateway_currency_symbol = isset($_REQUEST['currency_symbol']) ? $_REQUEST['currency_symbol'] : '';
$dsp_gateway_test_mode = isset($_REQUEST['test_mode']) ? 1 : 0;
$dsp_gateway_recurring = isset($_REQUEST['recurring']) ? 1 : 0;
$dsp_gateway_login_id = isset($_REQUEST['login_id']) ? $_REQUEST['login_id'] : '';
$dsp_gateway_transaction_id = isset($_REQUEST['transaction_id']) ? $_REQUEST['transaction_id'] : '';
$dsp_gateway_md5_hash = isset($_REQUEST['md5_hash']) ? $_REQUEST['md5_hash'] : '';
$dsp_gateway_status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
$dsp_pro_api_uname = isset($_REQUEST['api_uname']) ? $_REQUEST['api_uname'] : '';
$dsp_pro_api_password = isset($_REQUEST['api_password']) ? $_REQUEST['api_password'] : '';
$dsp_pro_api_signature = isset($_REQUEST['api_signature']) ? $_REQUEST['api_signature'] : '';
$dsp_paypal_adv_username = isset($_REQUEST['adv_username']) ? $_REQUEST['adv_username'] : '';
$dsp_paypal_adv_password = isset($_REQUEST['adv_password']) ? $_REQUEST['adv_password'] : '';
$dsp_rtlo = isset($_REQUEST['rtlo']) ? $_REQUEST['rtlo'] : '';
$dsp_bank_language = isset($_REQUEST['bank_language']) ? $_REQUEST['bank_language'] : '';
$dsp_title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
$dsp_description = isset($_REQUEST['description']) ? $_REQUEST['description'] : '';
$dsp_instruction = isset($_REQUEST['instruction']) ? $_REQUEST['instruction'] : '';
if (!$dsp_gateway_status)
    $dsp_gateway_status = 0;
if (isset($dsp_mode) && $dsp_mode == 'add') {
    $gateway_name = isset($_REQUEST['gateway_name']) ? $_REQUEST['gateway_name'] : '';
    if ($gateway_name == 'paypal') {
        $wpdb->query("UPDATE $dsp_gateways_table SET address = '$dsp_gateway_address',currency = '$dsp_gateway_currency',currency_symbol ='$dsp_gateway_currency_symbol', status = '$dsp_gateway_status'
	   	,pro_api_username='$dsp_paypal_api_uname',pro_api_password='$dsp_paypa_api_pwd',pro_api_signature='$dsp_paypal_signature',paypal_client_id='$dsp_paypal_clientid',paypal_secret='$dsp_paypal_secret',test_mode='$dsp_gateway_test_mode',recurring='$dsp_gateway_recurring'  
	   	 WHERE gateway_name  = '$gateway_name'");
    } else if ($gateway_name == 'bank_wire' || $gateway_name == 'cheque_payment' ) {
        $wpdb->query("UPDATE $dsp_gateways_table SET currency = '$dsp_gateway_currency',currency_symbol ='$dsp_gateway_currency_symbol', status = '$dsp_gateway_status',title='$dsp_title',instruction='$dsp_instruction',description='$dsp_description' WHERE gateway_name  = '$gateway_name'");
    } else if ($gateway_name == 'authorize') {
        $wpdb->query("UPDATE $dsp_gateways_table SET login_id = '$dsp_gateway_login_id',transaction_id = '$dsp_gateway_transaction_id',md5_hash ='$dsp_gateway_md5_hash',currency = '$dsp_gateway_currency',currency_symbol ='$dsp_gateway_currency_symbol', status = '$dsp_gateway_status', test_mode = '$dsp_gateway_test_mode'  WHERE gateway_name  = '$gateway_name'");
    } else if ($gateway_name == 'paypal pro') {
        $wpdb->query("UPDATE $dsp_gateways_table SET currency = '$dsp_gateway_currency',currency_symbol ='$dsp_gateway_currency_symbol', status = '$dsp_gateway_status',pro_api_username='$dsp_pro_api_uname',pro_api_password='$dsp_pro_api_password',pro_api_signature='$dsp_pro_api_signature'  WHERE gateway_name  = '$gateway_name'");
    } else if ($gateway_name == 'paypal advance') {
        $wpdb->query("UPDATE $dsp_gateways_table SET currency = '$dsp_gateway_currency',currency_symbol ='$dsp_gateway_currency_symbol', status = '$dsp_gateway_status',paypal_adv_username='$dsp_paypal_adv_username',paypal_adv_password='$dsp_paypal_adv_password'  WHERE gateway_name  = '$gateway_name'");
    } else if ($gateway_name == 'iDEAL') {
        $wpdb->query("UPDATE $dsp_gateways_table SET currency = '$dsp_gateway_currency',currency_symbol ='$dsp_gateway_currency_symbol', status = '$dsp_gateway_status',rtlo='$dsp_rtlo',bank_language='$dsp_bank_language'  WHERE gateway_name  = '$gateway_name'");
    } 
}
    //header("Location:".$goback);
    echo language_code("DSP_GATEWAYS_UPDATED_MESSAGE");
    //     exit();

?>
<div id="general" class="postbox" >
    <h3 class="hndle">
        <span><?php echo language_code('DSP_GATEWAYS'); ?></span>
    </h3>
    <table cellpadding="6" cellspacing="0" border="0" width="100%">
        <!------------------------terms page------------------------------------- -->	
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td class="form-field form-required" style="padding-left:20px;"> 
                <form name="admin.php?page=dsp-admin-sub-page1&pid=gateways_settings" method="post">
                    <?php echo language_code('DSP_SELECT_PAYMENT_GATEWAYS') ?><span style=" margin-left:105px;">
                        <select name="payment_method" onchange="this.form.submit();" style="width:150px;">
                            <?php
                            $gateway_table = $wpdb->get_results("SELECT * FROM $dsp_gateways_table");
                            foreach ($gateway_table as $gatway) {
                                ?>
                                <option value="<?php echo $gatway->gateway_name; ?>" <?php if (isset($_REQUEST['payment_method']) && $_REQUEST['payment_method'] == $gatway->gateway_name) { ?> selected="selected" <?php } ?> ><?php echo $gatway->display_name; ?></option>
                            <?php } ?>
                        </select>  
                    </span>
                </form> 
            </td>
        </tr>
        <tr>
            <td>
                <form name="" method="post">
                    <table cellpadding="0" cellspacing="0" border="0" class="dsp_thumbnails1">
                        <?php
                        if (isset($_REQUEST['payment_method']) && $_REQUEST['payment_method'] != '') {
                            $gateway_rows = $wpdb->get_row("SELECT * FROM $dsp_gateways_table where gateway_name='" . $_REQUEST['payment_method'] . "'  order by gateway_id");
                        } else {
                            $gateway_rows = $wpdb->get_row("SELECT * FROM $dsp_gateways_table where gateway_name='paypal'  order by gateway_id");
                            $_REQUEST['payment_method'] = 'paypal';
                        }
                        if (isset($_REQUEST['payment_method']) && $_REQUEST['payment_method'] == 'paypal') {
                            ?>		 
                            <tr valign="top">
                                <td width="27%" scope="row"><label for="test_mode">Sandbox Account</label></td>
                                <td><input type="checkbox" name="test_mode" <?php if($gateway_rows->test_mode==1) echo 'checked="checked"'; ?>/></td>
                            </tr>
                            <tr><td><br></td></tr>
                            
                            <tr valign="top">
                                <td width="27%" scope="row"><label for="recurring">Recurring</label></td>
                                <td><input type="checkbox" name="recurring" <?php if($gateway_rows->recurring==1) echo 'checked="checked"'; ?>/></td>
                            </tr>
                            <tr><td><br></td></tr>
                            
                            <tr valign="top">
                                <td width="27%" scope="row" class="form-field form-required"><label for="gatewayaddress"><?php _e(language_code('DSP_GATEWAYS_PAYPAL_ADDRESS')) ?></label></td>
                                <td><input type="text" name="gateway_address" value="<?php echo $gateway_rows->address; ?>" class="regular-text"  /></td>
                            </tr>


                            <tr valign="top">

                                <td width="27%" scope="row" class="form-field form-required"><label for="gatewayapiuname"><?php echo language_code('DSP_GATEWAYS_PRO_API_UNAME') . ' (' . language_code('DSP_FOR_MOBILE_APP') . ')' ?></label></td>

                                <td><input type="text" name="paypal_api_uname" value="<?php echo $gateway_rows->pro_api_username; ?>" class="regular-text"  /></td>

                            </tr>

                            <tr valign="top">

                                <td scope="row" class="form-field form-required"><label for="gatewayaddress"><?php echo language_code('DSP_GATEWAYS_PRO_API_PASSWORD') . ' (' . language_code('DSP_FOR_MOBILE_APP') . ')' ?></label></td>

                                <td><input type="text" name="paypa_api_pwd" value="<?php echo $gateway_rows->pro_api_password; ?>" class="regular-text"  /></td>

                            </tr>

                            <tr valign="top">

                                <td scope="row" class="form-field form-required"><label for="gatewayapisignature"><?php echo language_code('DSP_GATEWAYS_PRO_API_SIGNATURE') . ' (' . language_code('DSP_FOR_MOBILE_APP') . ')' ?></label></td>

                                <td><input type="text" name="paypal_signature" value="<?php echo $gateway_rows->pro_api_signature; ?>" class="regular-text"  /></td>

                            </tr>

                            <tr valign="top">

                                <td scope="row" class="form-field form-required"><label for="gatewayaddress"><?php echo language_code('DSP_CLIENT_ID') . ' (' . language_code('DSP_FOR_MOBILE_APP') . ')' ?></label></td>

                                <td><input type="text" name="paypal_clientid" value="<?php echo $gateway_rows->paypal_client_id; ?>" class="regular-text"  /></td>

                            </tr>

                            <tr valign="top">

                                <td scope="row" class="form-field form-required"><label for="gatewayapisignature"><?php echo language_code('DSP_PAYPAL_SECRET') . ' (' . language_code('DSP_FOR_MOBILE_APP') . ')' ?></label></td>

                                <td><input type="text" name="paypal_secret" value="<?php echo $gateway_rows->paypal_secret; ?>" class="regular-text"  /></td>

                            </tr>
                        <?php } else if (isset($_REQUEST['payment_method']) && $_REQUEST['payment_method'] == 'authorize') { ?>
                            <tr valign="top">
                                <td width="27%" scope="row"><label for="test_mode">Developer Account</label></td>
                                <td><input type="checkbox" name="test_mode" <?php if($gateway_rows->test_mode==1) echo 'checked="checked"'; ?>/></td>
                            </tr>
                            <tr><td><br></td></tr>
                            <tr valign="top">
                                <td width="27%" scope="row" class="form-field form-required"><label for="gatewayaddress"><?php echo language_code('DSP_GATEWAYS_AUTHORIZE_LOGIN_ID') ?></label></td>
                                <td><input type="text" name="login_id" value="<?php echo $gateway_rows->login_id; ?>" class="regular-text"  /></td>
                            </tr>
                            <tr valign="top">
                                <td scope="row" class="form-field form-required"><label for="gatewayaddress"><?php echo language_code('DSP_GATEWAYS_AUTHORIZE_TRANSACTION_ID') ?></label></td>
                                <td><input type="text" name="transaction_id" value="<?php echo $gateway_rows->transaction_id; ?>" class="regular-text"  /></td>
                            </tr>
                            <tr valign="top">
                                <td scope="row" class="form-field form-required"><label for="gatewayaddress"><?php echo language_code('DSP_GATEWAYS_AUTHORIZE_HASH') ?></label></td>
                                <td><input type="text" name="md5_hash" value="<?php echo $gateway_rows->md5_hash; ?>" class="regular-text"  /></td>
                            </tr>
                        <?php } else if (isset($_REQUEST['payment_method']) && $_REQUEST['payment_method'] == 'paypal pro') {
                            ?>
                            <tr valign="top">
                                <td width="27%" scope="row" class="form-field form-required"><label for="gatewayapiuname"><?php echo language_code('DSP_GATEWAYS_PRO_API_UNAME') ?></label></td>
                                <td><input type="text" name="api_uname" value="<?php echo $gateway_rows->pro_api_username; ?>" class="regular-text"  /></td>
                            </tr>
                            <tr valign="top">
                                <td scope="row" class="form-field form-required"><label for="gatewayaddress"><?php echo language_code('DSP_GATEWAYS_PRO_API_PASSWORD') ?></label></td>
                                <td><input type="text" name="api_password" value="<?php echo $gateway_rows->pro_api_password; ?>" class="regular-text"  /></td>
                            </tr>
                            <tr valign="top">
                                <td scope="row" class="form-field form-required"><label for="gatewayapisignature"><?php echo language_code('DSP_GATEWAYS_PRO_API_SIGNATURE') ?></label></td>
                                <td><input type="text" name="api_signature" value="<?php echo $gateway_rows->pro_api_signature; ?>" class="regular-text"  /></td>
                            </tr>
                        <?php } else if (isset($_REQUEST['payment_method']) && $_REQUEST['payment_method'] == 'paypal advance') {
                            ?>
                            <tr  valign="top">
                                <td scope="row" colspan="2" style="color: red;">
                                    <?php echo language_code('DSP_GATEWAY_PROVIDE_RECURRING_BILLING_WITH_ADV'); ?>
                                </td>
                            </tr>
                            <tr  valign="top">
                                <td scope="row" colspan="2" style="color: red;">&nbsp;

                                </td>
                            </tr>
                            <tr valign="top">
                                <td width="27%" scope="row" class="form-field form-required"><label for="gatewayapiuname"><?php echo language_code('DSP_GATEWAYS_PAYPAL_ADVANCE_UNAME') ?></label></td>
                                <td><input type="text" name="adv_username" value="<?php echo $gateway_rows->paypal_adv_username; ?>" class="regular-text"  /></td>
                            </tr>
                            <tr valign="top">
                                <td scope="row" class="form-field form-required"><label for="gatewayaddress"><?php echo language_code('DSP_GATEWAYS_PAYPAL_ADVANCE_PASSWORD') ?></label></td>
                                <td><input type="text" name="adv_password" value="<?php echo $gateway_rows->paypal_adv_password; ?>" class="regular-text"  /></td>
                            </tr>
                        <?php } else if (isset($_REQUEST['payment_method']) && $_REQUEST['payment_method'] == 'iDEAL') { ?>
                            </tr>
                            <tr valign="top">
                                <td scope="row" class="form-field form-required"><label for="gatewayaddress"><?php echo language_code('DSP_GATEWAYS_LAYOUT_NUMBER') ?></label></td>
                                <td><input type="text" name="rtlo" value="<?php echo $gateway_rows->rtlo; ?>" class="regular-text"  /></td>
                            </tr>
                            <tr valign="top">
                                <td scope="row" class="form-field form-required"><label for="gatewayaddress"><?php echo language_code('DSP_GATEWAYS_BANK_DROPDOWN_LANGUAGE') ?></label></td>
                                <td><select name="bank_language">
                                        <option value="en" <?php if ($gateway_rows->bank_language == 'en') echo 'selected="selected"'; ?>>English</option>
                                        <option value="nl" <?php if ($gateway_rows->bank_language == 'nl') echo 'selected="selected"'; ?>>Dutch</option>
                                    </select></td>
                            </tr>
                        <?php }
                            else if (
                                        (isset($_REQUEST['payment_method']) && $_REQUEST['payment_method'] == 'cheque_payment') || 
                                        (isset($_REQUEST['payment_method']) && $_REQUEST['payment_method'] == 'bank_wire') 
                                    ) 
                               {  
                        ?>
                            <tr  valign="top">
                                <td scope="row">
                                    <?php echo language_code('DSP_TEXT_TITLE'); ?>
                                </td>
                                 <td><input type="text" name="title" value="<?php echo !empty($gateway_rows) ? $gateway_rows->title : ''; ?>" class="regular-text"  /></td>
                            </tr>
                            <tr  valign="top">
                                <td scope="row">
                                    <?php echo language_code('DSP_MEMBERSHIPS_DESCRIPTION'); ?>
                                </td>
                                <td><textarea type="text" name="description"  class="regular-text"  ><?php echo !empty($gateway_rows) ? $gateway_rows->description : ''; ?></textarea></td>
                            </tr>
                            <tr  valign="top">
                                <td scope="row">
                                    <?php echo language_code('DSP_INSTRUCTION'); ?>
                                </td>
                                 <td><textarea type="text" name="instruction"  class="regular-text"  ><?php echo !empty($gateway_rows) ? $gateway_rows->instruction : ''; ?></textarea></td>
                            </tr>
                          
                        <?php } else {
                            ?>
                            <tr valign="top">
                                <td width="27%" scope="row" class="form-field form-required"><label for="gatewayaddress"><?php _e(language_code('DSP_GATEWAYS_PAYPAL_ADDRESS')) ?></label></td>
                                <td><input type="text" name="gateway_address" value="<?php echo $gateway_rows->address; ?>" class="regular-text"  /></td>
                            </tr>

                            <tr valign="top">

                                <td width="27%" scope="row" class="form-field form-required"><label for="gatewayapiuname"><?php echo language_code('DSP_GATEWAYS_PRO_API_UNAME') . ' (' . language_code('DSP_FOR_MOBILE_APP') . ')' ?></label></td>

                                <td><input type="text" name="paypal_api_uname" value="<?php echo $gateway_rows->pro_api_username; ?>" class="regular-text"  /></td>

                            </tr>

                            <tr valign="top">

                                <td scope="row" class="form-field form-required"><label for="gatewayaddress"><?php echo language_code('DSP_GATEWAYS_PRO_API_PASSWORD') . ' (' . language_code('DSP_FOR_MOBILE_APP') . ')' ?></label></td>

                                <td><input type="text" name="paypa_api_pwd" value="<?php echo $gateway_rows->pro_api_password; ?>" class="regular-text"  /></td>

                            </tr>

                            <tr valign="top">

                                <td scope="row" class="form-field form-required"><label for="gatewayapisignature"><?php echo language_code('DSP_GATEWAYS_PRO_API_SIGNATURE') . ' (' . language_code('DSP_FOR_MOBILE_APP') . ')' ?></label></td>

                                <td><input type="text" name="paypal_signature" value="<?php echo $gateway_rows->pro_api_signature; ?>" class="regular-text"  /></td>

                            </tr>

                            <tr valign="top">

                                <td scope="row" class="form-field form-required"><label for="gatewayaddress"><?php echo language_code('DSP_CLIENT_ID') . ' (' . language_code('DSP_FOR_MOBILE_APP') . ')' ?></label></td>

                                <td><input type="text" name="paypal_clientid" value="<?php echo $gateway_rows->paypal_client_id; ?>" class="regular-text"  /></td>

                            </tr>

                            <tr valign="top">

                                <td scope="row" class="form-field form-required"><label for="gatewayapisignature"><?php echo language_code('DSP_PAYPAL_SECRET') . ' (' . language_code('DSP_FOR_MOBILE_APP') . ')' ?></label></td>

                                <td><input type="text" name="paypal_secret" value="<?php echo $gateway_rows->paypal_secret; ?>" class="regular-text"  /></td>

                            </tr>

                        <?php } ?>
                        <tr valign="top">
                            <td scope="row" class="form-field form-required"><label for="gatewayaddress"><?php _e(language_code('DSP_GAYEWAYS_CURRENCY')) ?></label></td>
                            <td><input type="text" name="currency" value="<?php echo $gateway_rows->currency; ?>" class="regular-text"  /></td>
                        </tr>
                        <tr valign="top">
                            <td scope="row" class="form-field form-required"><label for="gatewayaddress"><?php _e(language_code('DSP_GAYEWAYS_CURRENCY_SYMBOL')) ?></label></td>
                            <td><input type="text" name="currency_symbol" value="<?php echo $gateway_rows->currency_symbol; ?>" maxlength="2" class="regular-text"  /></td>
                        </tr>
                        <tr valign="top">
                            <td scope="row" class="form-field form-required"><label for="gatewayaddress"><?php _e(language_code('DSP_ACTIVE')) ?></label></td>
                            <td><!--<input type="text" name="status" value="<?php //= $gateway_rows->status;         ?>" class="regular-text"  />-->
                                <input type="checkbox" name="status" value="1" <?php if ($gateway_rows->status == 1) echo 'checked="checked"'; ?>/>
                            </td>
                        </tr>
                        <tr>
                            <td class="submit" align="left">
                                <input type="hidden" name="mode" value="add" />
                                <input type="hidden" name="gateway_name" value="<?php echo $gateway_rows->gateway_name; ?>" />

                                <p>  <input type="submit"  name="submit" class="button button-primary" value="<?php _e('Save Changes') ?>" onclick="update_gateways();"/></p>

                            </td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
    </table>
</div>
<br />
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>
