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
$DSP_GATEWAYS_TABLE = $wpdb->prefix . DSP_GATEWAYS_TABLE;
$dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;

// delete the user from tmp payment table
$wpdb->query("DELETE FROM $dsp_temp_payments_table WHERE user_id = '$user_id'");

$apiDetailsQuery = "SELECT pro_api_username,pro_api_password,pro_api_signature FROM $DSP_GATEWAYS_TABLE where gateway_id=3";
//	echo '<br>'.$apiDetailsQuery;
$apiDetailsRes = $wpdb->get_row($apiDetailsQuery);

$my_api_username = $apiDetailsRes->pro_api_username;

$my_api_password = $apiDetailsRes->pro_api_password;

$my_api_signature = $apiDetailsRes->pro_api_signature;

if ($my_api_username != '' && $my_api_password != '' && $my_api_signature != '') {

    $membership_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

    $membership_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table WHERE membership_id='$membership_id'");

    $currency_code = isset($_REQUEST['currency_code']) ? $_REQUEST['currency_code'] : 'USD';
    ?>

    <div class="ui-content" data-role="content">
        <div class="content-primary">	
            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                    <?php
                    if (isset($reason) && $reason != '') {
                        ?>
                        <div align="center" style="color:#FF0000;"><b><?php echo $reason; ?></b></div>

                        <?php
                        $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='17'");

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
                    ?>
                    <div class="box-page">

                        <form id="frmAuth">

                            <div style="width: 100%;text-align: right;color: red;padding-bottom: 10px;">
                                <span>*<?php echo language_code('DSP_FIELDS_ARE_MANDATORY'); ?></span>
                            </div>

                            <ul class="upgrade-details-page">

                                <li><span><font style="color: red">*</font><?php echo language_code('DSP_CREDIT_CARD_TYPE'); ?>:</span>
                                    <div >
                                        <input type="radio" name="customer_credit_card_type" value="Visa" checked="checked"><?php echo language_code('DSP_CARD_TYPE_VISA') ?>
                                        <input type="radio" name="customer_credit_card_type" value="MasterCard"><?php echo language_code('DSP_CARD_TYPE_MASTERCARD') ?><br>
                                        <input type="radio" name="customer_credit_card_type" value="Discover"><?php echo language_code('DSP_CARD_TYPE_DISCOVER') ?>
                                        <input type="radio" name="customer_credit_card_type" value="Amex"><?php echo language_code('DSP_CARD_TYPE_AMEX') ?>
                                    </div>
                                </li>
                                <li>
                                    <span><font style="color: red">*</font><?php echo language_code('DSP_GATEWAYS_CREDIT_CARD_NO'); ?>:</span>
                                    <input type="text" class="text" size="15" id="customer_credit_card_number" name="customer_credit_card_number" value=""> 
                                </li>
                                <li><span><font style="color: red">*</font><?php echo language_code('DSP_EXPIRATION_YEAR'); ?>:</span> <input type="text" class="text" size="4" id="cc_expiration_year" name="cc_expiration_year" value=""></li>
                                <li><span><font style="color: red">*</font><?php echo language_code('DSP_EXPIRATION_MONTH'); ?>:</span> <input type="text" class="text" size="4" id="cc_expiration_month" name="cc_expiration_month" value=""></li>
                                <li><span><font style="color: red">*</font><?php echo language_code('DSP_GATEWAYS_CCV'); ?>:</span> <input type="text" class="text" size="4" id="cc_cvv2_number" name="cc_cvv2_number" value=""></li>
                                <li><span><font style="color: red">*</font><?php echo language_code('DSP_GATEWAYS_FIRST_NAME'); ?>:</span> <input type="text" class="text" size="15" id="customer_first_name" name="customer_first_name" value=""> </li>
                                <li><span><font style="color: red">*</font><?php echo language_code('DSP_GATEWAYS_LAST_NAME'); ?>:</span> <input type="text" class="text" size="15" id="customer_last_name" name="customer_last_name" value=""></li>
                                <li><span><font style="color: red">*</font><?php echo language_code('DSP_ADDRESS_ONE'); ?>:</span> <input type="text" class="text" size="15" id="customer_address1" name="customer_address1" value=""> </li>
                                <li><span><?php echo language_code('DSP_ADDRESS_TWO'); ?>:</span> <input type="text" class="text" size="15" name="customer_address2"  value=""> </li>
                                <li><span><font style="color: red">*</font><?php echo language_code('DSP_CITY'); ?></span> <input type="text" class="text" size="15" id="customer_city" name="customer_city" value=""></li>
                                <li><span><font style="color: red">*</font><?php echo language_code('DSP_GATEWAYS_STATE'); ?>:</span> <input type="text" class="text" size="15" id="customer_state" name="customer_state" value=""></li>
                                <li><span><font style="color: red">*</font><?php echo language_code('DSP_COUNTRY'); ?></span>

                                    <select id="customer_country" name="customer_country">
                                        <?php
                                        $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");

                                        foreach ($strCountries as $rdoCountries) {

                                            if ($exist_profile_details->country_id == $rdoCountries->country_id) {
                                                echo "<option value='" . $rdoCountries->name . "' selected='selected' >" . $rdoCountries->name . "</option>";
                                            } else {
                                                echo "<option value='" . $rdoCountries->name . "' >" . $rdoCountries->name . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </li>

                                <li><span><font style="color: red">*</font><?php echo language_code('DSP_GATEWAYS_ZIP'); ?>:</span> <input type="text" class="text" size="15" id="customer_zip" name="customer_zip" value=""></li>
                                <li>
                                    <span>
                                        <input name="submit" type="button" value="<?php echo language_code('DSP_GATEWAYS_SUBMIT'); ?>"  onclick="callUpgrade('pro_settings_detail', '<?php echo language_code("DSP_USER_NAME_SHOULD_NO_BE_EMPTY"); ?>')" />	
                                    </span>

                                    <input name="cancel" type="button" value="<?php echo language_code('DSP_GATEWAYS_CANCEL'); ?>" onclick="callUpgrade('upgrade_account', 0)" style="margin-left:30px;" /></li>
                                <li>
                                    <input type="hidden" class="text" size="14" name="x_membership_id" value="<?php echo $membership_plan->membership_id; ?>">
                                    <input type="hidden" class="text" size="14" name="x_name" value="<?php echo $membership_plan->name; ?>">
                                    <input type="hidden" name="currency_code" value="<?php echo $currency_code ?>" />
                                    <input type="hidden" class="text" size="14" name="payment_amuont" value="<?php echo $membership_plan->price; ?>">
                                    <input type="hidden" class="text" size="14" name="x_days" value="<?php echo $membership_plan->no_of_days; ?>">
                                    <input type="hidden" class="text" size="14" name="x_desc" value="<?php echo $membership_plan->description; ?>">
                                    <input type="hidden" name="pagetitle" value="pro_settings_detail">
                                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                    <input type="hidden" name="id" value="<?php echo $membership_plan->membership_id; ?>">

                                </li>
                            </ul>

                            <div class="card-box">
                                <div  style="width: 23%;float: left;"><img src="<?php echo $imagepath . "plugins/dsp_dating/images/discover.png"; ?>" /></div>

                                <div  style="width: 23%;float: left;"><img src="<?php echo $imagepath . "plugins/dsp_dating/images/visa.jpg"; ?>" /></div>

                                <div  style="width: 23%;float: left;"><img src="<?php echo $imagepath . "plugins/dsp_dating/images/mastercard.jpg"; ?>" /></div>

                                <div  style="width: 23%;float: left;"><img src="<?php echo $imagepath . "plugins/dsp_dating/images/americanexpress.jpg"; ?>" /></div>
                            </div>

                            <div style="font-size:12px; float:left; width:100%;"><?php echo language_code('DSP_GATEWAYS_NOTE') ?></div>

                        </form>
                    </div>


                <?php } else {
                    ?>

                    <div align="center" style="color:#FF0000;"><b><?php echo language_code('DSP_INTERNAL_ERROR'); ?></b></div>

                <?php }
                ?>
            </li>
        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
</div>