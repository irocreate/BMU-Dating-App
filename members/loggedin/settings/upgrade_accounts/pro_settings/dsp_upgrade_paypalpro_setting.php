<script type="text/javascript">
    function checkValidation()
    {
        if (document.getElementById('customer_credit_card_number').value == "")
        {
            alert('<?php echo language_code("DSP_USER_NAME_SHOULD_NO_BE_EMPTY"); ?>');
            return false;
        }
        else if (document.getElementById('cc_expiration_year').value == "")
        {
            alert('<?php echo language_code("DSP_USER_NAME_SHOULD_NO_BE_EMPTY"); ?>');
            return false;
        }
        else if (document.getElementById('cc_expiration_month').value == "")
        {
            alert('<?php echo language_code("DSP_USER_NAME_SHOULD_NO_BE_EMPTY"); ?>');
            return false;
        }
        else if (document.getElementById('cc_cvv2_number').value == "")
        {
            alert('<?php echo language_code("DSP_USER_NAME_SHOULD_NO_BE_EMPTY"); ?>');
            return false;
        }
        else if (document.getElementById('customer_first_name').value == "")
        {
            alert('<?php echo language_code("DSP_USER_NAME_SHOULD_NO_BE_EMPTY"); ?>');
            return false;
        }
        else if (document.getElementById('customer_last_name').value == "")
        {
            alert('<?php echo language_code("DSP_USER_NAME_SHOULD_NO_BE_EMPTY"); ?>');
            return false;
        }
        else if (document.getElementById('customer_address1').value == "")
        {
            alert('<?php echo language_code("DSP_USER_NAME_SHOULD_NO_BE_EMPTY"); ?>');
            return false;
        }
        else if (document.getElementById('customer_city').value == "")
        {
            alert('<?php echo language_code("DSP_USER_NAME_SHOULD_NO_BE_EMPTY"); ?>');
            return false;
        }
        else if (document.getElementById('customer_state').value == "")
        {
            alert('<?php echo language_code("DSP_USER_NAME_SHOULD_NO_BE_EMPTY"); ?>');
            return false;
        }
        else if (document.getElementById('customer_zip').value == "")
        {
            alert('<?php echo language_code("DSP_USER_NAME_SHOULD_NO_BE_EMPTY"); ?>');
            return false;
        }
        return true;

    }
</script>
<?php
    $DSP_GATEWAYS_TABLE = $wpdb->prefix . DSP_GATEWAYS_TABLE;
    $dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
    // delete the user from tmp payment table
    $wpdb->query("DELETE FROM $dsp_temp_payments_table WHERE user_id = '$user_id'");

    $apiDetailsQuery = "SELECT pro_api_username,pro_api_password,pro_api_signature FROM $DSP_GATEWAYS_TABLE where gateway_id=3";
    //	echo '<br>'.$apiDetailsQuery;
    $apiDetailsRes = $wpdb->get_row($apiDetailsQuery);

    $my_api_username = $apiDetailsRes->pro_api_username;

    $my_api_password = $apiDetailsRes->pro_api_password;

    $my_api_signature = $apiDetailsRes->pro_api_signature;

    if ($my_api_username != '' && $my_api_password != '' && $my_api_signature != '') {
        $membership_id = $id;
        $membership_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table WHERE membership_id='$membership_id'");
        $amountAfterDiscount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '';
        $membership_plan_amount = (isset($amountAfterDiscount) && !empty($amountAfterDiscount)) ? $amountAfterDiscount : $membership_plan->price;
        $currency_code = isset($_REQUEST['currency_code']) ? $_REQUEST['currency_code'] : 'USD';
        ?>
        <div class="dsp_search_result_box_out">
            <div class="dsp_search_result_box_in">
                <?php if (get('reason') != '') { ?>
                    <div align="center" style="color:#FF0000;"><b><?php echo urldecode(get('reason')); ?></b></div>
                    <?php
                    if(dsp_issetGivenEmailSetting($user_id,'payment_failed')){
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
        //$to ='mwnt.test4@gmail.com';
                        $subject = $email_subject;
                        $message = $MemberEmailMessage;
                        $admin_email = get_option('admin_email');
                        $from = $admin_email;
                        $headers = "From: $from";
                        // wp_mail($to, $subject, $message, $headers);
                        $wpdating_email  = Wpdating_email_template::get_instance();
                        $result = $wpdating_email->send_mail( $to, $subject, $message);
                    }
                }

                if (get('mode') == 'cancel'  && dsp_issetGivenEmailSetting($user_id,'payment_canceled')) {

                    $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='18'");
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
    //$to ='mwnt.test4@gmail.com';
                    $subject = $email_subject;
                    $message = $MemberEmailMessage;
                    $admin_email = get_option('admin_email');
                    $from = $admin_email;
                    $headers = "From: $from";
                    $wpdating_email  = Wpdating_email_template::get_instance();
                    if ($wpdating_email->send_mail( $to, $subject, $message)) {
                        ?>
                        <script type='text/javascript'> location.href = '<?php echo $root_link . "setting/upgrade_account/"; ?>'</script>
                        <?php
                    }
                }
                ?>
                <div class="box-page">

                    <form action="<?php echo $root_link . "setting/pro_settings_detail/"; ?>" method="post" onsubmit="return checkValidation();">
                        <div style="width: 100%;text-align: right;">
                            <span>*<?php echo language_code('DSP_FIELDS_ARE_MANDATORY'); ?></span>
                        </div>
                        <div class="card-box">
                            <div style="margin-bottom: 20px;margin-top: 12px;"><img src="<?php echo WPDATE_URL .  "/images/discover.png"; ?>" alt="Discover"/></div>
                            <div style="margin-bottom: 20px;"><img src="<?php echo WPDATE_URL .  "/images/visa.jpg"; ?>" alt="Visa"/></div>
                            <div style="margin-bottom: 20px;"><img src="<?php echo WPDATE_URL .  "/images/mastercard.jpg"; ?>" alt="Master Card" /></div>
                            <div style="margin-top: 0px;"><img src="<?php echo WPDATE_URL .  "/images/americanexpress.jpg"; ?>" alt="American Express" /></div>
                        </div>
                        <ul class="upgrade-details-page">



                            <li><span><font style="color: red">*</font><?php echo language_code('DSP_CREDIT_CARD_TYPE'); ?>:</span>
                                <input type="radio" name="customer_credit_card_type" value="Visa" checked="checked"><?php echo language_code('DSP_CARD_TYPE_VISA') ?>
                                <input type="radio" name="customer_credit_card_type" value="MasterCard"><?php echo language_code('DSP_CARD_TYPE_MASTERCARD') ?><br>
                                <input type="radio" name="customer_credit_card_type" value="Discover"><?php echo language_code('DSP_CARD_TYPE_DISCOVER') ?>
                                <input type="radio" name="customer_credit_card_type" value="Amex"><?php echo language_code('DSP_CARD_TYPE_AMEX') ?>

                            </li>
                            <li><span><font style="color: red">*</font><?php echo language_code('DSP_GATEWAYS_CREDIT_CARD_NO'); ?>:</span> <input type="text" class="text" size="15" id="customer_credit_card_number" name="customer_credit_card_number" value=""> 
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
                            <li><input name="submit" type="submit" value="<?php echo language_code('DSP_GATEWAYS_SUBMIT'); ?>" /><input name="cancel" type="button" value="<?php echo language_code('DSP_GATEWAYS_CANCEL'); ?>" onclick="CancelPayment()" style="margin-left:30px;" /></li>
                            <li><input type="hidden" class="text" size="14" name="x_membership_id" value="<?php echo $membership_plan->membership_id; ?>">
                                <input type="hidden" class="text" size="14" name="x_name" value="<?php echo $membership_plan->name; ?>">
                                <input type="hidden" name="currency_code" value="<?php echo $currency_code ?>" />
                                <input type="hidden" class="text" size="14" name="payment_amuont" value="<?php echo $membership_plan_amount; ?>">
                                <input type="hidden" class="text" size="14" name="x_days" value="<?php echo $membership_plan->no_of_days; ?>">
                                <input type="hidden" class="text" size="14" name="x_desc" value="<?php echo $membership_plan->description; ?>"></li>
                        </ul>
                        <div style="font-size:12px; float:left; width:100%;"><?php echo language_code('DSP_GATEWAYS_NOTE') ?></div>
                    </form>
                </div>
            </div></div>
    <?php } else {
        ?>
        <div class="dsp_search_result_box_out">
            <div class="dsp_search_result_box_in">
                <div align="center" style="color:#FF0000;"><b><?php echo language_code('DSP_INTERNAL_ERROR'); ?></b></div>
            </div>
        </div>

<?php }
