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
$dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;

$membership_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

// delete the user from tmp payment table
$wpdb->query("DELETE FROM $dsp_temp_payments_table WHERE user_id = '$user_id'");



$membership_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table WHERE membership_id='$membership_id'");
?>



<div class="ui-content" data-role="content">
    <div class="content-primary">	
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
            <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">



                <?php if (isset($reason) && $reason != '') {
                    ?>
                    <div align="center" style="color:#FF0000;padding-bottom: 10px;"><b><?php echo $reason; ?></b></div>
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

                    <form  id="frmAuth">

                        <ul class="upgrade-details-page">
                            <li><span><font style="color: red">*</font>
                                    <?php echo language_code('DSP_GATEWAYS_CREDIT_CARD_NO'); ?>:</span> <input id="customer_credit_card_number" type="text" class="text" size="15" name="x_card_num" value=""> 
                            </li>
                            <li><span><font style="color: red">*</font>
                                    <?php echo language_code('DSP_GATEWAYS_EXPIRATION_DATE'); ?>:</span> <input id="cc_expiration_year" type="text" class="text" size="4" name="x_exp_date" value=""></li>
                            <li><span><font style="color: red">*</font>
                                    <?php echo language_code('DSP_GATEWAYS_CCV'); ?>:</span> <input id="cc_cvv2_number" type="text" class="text" size="4" name="x_card_code" value=""></li>
                            <li><span><font style="color: red">*</font>
                                    <?php echo language_code('DSP_GATEWAYS_FIRST_NAME'); ?>:</span> <input id="customer_first_name" type="text" class="text" size="15" name="x_first_name" value=""> </li>
                            <li><span>
                                    <?php echo language_code('DSP_GATEWAYS_LAST_NAME'); ?>:</span> <input type="text" class="text" size="15" name="x_last_name" value=""></li>
                            <li><span>
                                    <?php echo language_code('DSP_GATEWAYS_ADDRESS'); ?>:</span> <input type="text" class="text" size="15" name="x_address" value=""> </li>
                            <li><span>
                                    <?php echo language_code('DSP_GATEWAYS_STATE'); ?>:</span> <input type="text" class="text" size="15" name="x_state" value=""></li>
                            <li><span>
                                    <?php echo language_code('DSP_GATEWAYS_ZIP'); ?>:</span> <input type="text" class="text" size="15" name="x_zip" value=""></li>
                            <li>
                                <span>
                                    <input onclick="callUpgrade('auth_settings_detail', '<?php echo language_code("DSP_USER_NAME_SHOULD_NO_BE_EMPTY"); ?>')" name="submit" type="button" value="<?php echo language_code('DSP_GATEWAYS_SUBMIT'); ?>" />	
                                </span>

                                <input name="cancel" type="button" value="<?php echo language_code('DSP_GATEWAYS_CANCEL'); ?>" onclick="callUpgrade('upgrade_account', 0)" style="margin-left:30px;" /></li>
                            <li>
                                <input type="hidden" name="pagetitle" value="auth_settings_detail">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                <input type="hidden" class="text" size="14" name="x_membership_id" value="<?php echo $membership_plan->membership_id; ?>">

                                <input type="hidden" class="text" size="14" name="x_name" value="<?php echo $membership_plan->name; ?>">

                                <input type="hidden" class="text" size="14" name="x_amount" value="<?php echo $membership_plan->price; ?>">

                                <input type="hidden" class="text" size="14" name="x_days" value="<?php echo $membership_plan->no_of_days; ?>">

                                <input type="hidden" class="text" size="14" name="x_desc" value="<?php echo $membership_plan->description; ?>">
                                <input type="hidden"  name="id" value="<?php echo $membership_plan->membership_id; ?>">
                            </li>
                        </ul>

                        <div class="card-box">
                            <div style="width: 23%;float: left;"><img src="<?php echo $imagepath . "plugins/dsp_dating/images/discover.png"; ?>" /></div>

                            <div style="width: 23%;float: left;"><img src="<?php echo $imagepath . "plugins/dsp_dating/images/visa.jpg"; ?>" /></div>

                            <div style="width: 23%;float: left;"><img src="<?php echo $imagepath . "plugins/dsp_dating/images/mastercard.jpg"; ?>" /></div>

                            <div style="width: 23%;float: left;"><img src="<?php echo $imagepath . "plugins/dsp_dating/images/americanexpress.jpg"; ?>" /></div>
                        </div>

                        <div style="font-size:12px; float:left; width:100%;"><?php echo language_code('DSP_GATEWAYS_NOTE') ?></div>

                    </form>
                </div>

            </li>
        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>