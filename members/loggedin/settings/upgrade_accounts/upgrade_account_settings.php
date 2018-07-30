<div class=" "><div class="box-border"><div class="box-pedding clearfix">

    <?php
    /*
      Copyright (C) www.wpdating.com - All Rights Reserved!
      Author - www.wpdating.com
      WordPress Dating Plugin
      contact@wpdating.com
     */
    //-------------------------------START UPGRADE ACCOUNT SETTINGS ----------------------------------
    //echo 'asdasd';
    //include_once('dsp_upgrade_paypal_advance.php');
    //error_reporting (0);
    //error_reporting(E_ALL);
    //ini_set('display_errors', 'On');
    global $wpdb;
    $bloginfo_keys = array('admin_email', 'description', 'name', 'url', 'wpurl');
    $blogInfo      = array();
    foreach ($bloginfo_keys as $bloginfo_key) {
        $blogInfo[$bloginfo_key] = get_bloginfo($bloginfo_key);
    }
    $uploadInfo = wp_upload_dir();

    $upgrade_mode           = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : get('mode');
    $membership_plan        = isset($_REQUEST['item_name']) ? $_REQUEST['item_name'] : '';
    $membership_plan_id     = isset($_REQUEST['membership_id']) ? $_REQUEST['membership_id'] : '';
    $membership_plan_amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '';
    $discount_code          = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';
    $dateTimeFormat         = dsp_get_date_timezone();
    extract($dateTimeFormat);
    $payment_date = date("Y-m-d");
    // get the subscription detail from db
    $getSubDetailQuery       = "SELECT count(*) FROM $dsp_payments_table WHERE pay_user_id =$user_id AND recurring_profile_status = '1'";
    $recurringPaymentSatatus = $wpdb->get_var($getSubDetailQuery);
    if (($upgrade_mode == 'update') && $membership_plan_id != "") {
        $wpdb->query("DELETE FROM $dsp_temp_payments_table WHERE user_id = '$user_id'");
        $exist_membership_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table where membership_id='$membership_plan_id'");
        $plan_days             = $exist_membership_plan->no_of_days;
        $wpdb->query("INSERT INTO $dsp_temp_payments_table SET user_id = '$user_id',plan_id = '$membership_plan_id',plan_amount ='$membership_plan_amount',plan_days='$plan_days',plan_name='$membership_plan',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL $plan_days DAY),payment_status=0");
        $exist_gateway_address = $wpdb->get_row("SELECT * FROM $dsp_gateways_table");
        $business              = $exist_gateway_address->address;
        $currency_code         = $exist_gateway_address->currency;
        if (class_exists('Wpdating_Paypal_Public')) {
            $wpdating_paypal_public = new Wpdating_Paypal_Public();
            $append_name            = 'user_id';
            $append_value           = $user_id;
            $wpdating_paypal_public->get_custom_field_value();
            $custom_field_val = $wpdating_paypal_public->append_values_to_custom_field($append_name, $append_value);
        }
        ?>
        <form name="frm1" action="<?php echo $root_link . "setting/dsp_paypal/"; ?>" method="post">
            <input type="hidden" name="business" value="<?php echo $business ?>"/>
            <input type="hidden" name="currency_code" value="<?php echo $currency_code ?>"/>
            <input type="hidden" name="item_name" value="<?php echo $membership_plan ?>"/>
            <input type="hidden" name="item_number" value="<?php echo $user_id ?>"/>
            <input type="hidden" name="amount" value="<?php echo $membership_plan_amount ?>"/>
            <input type="hidden" name="code" id="code" value="<?php echo $discount_code; ?>"/>
            <input type="Hidden" name="return" value="<?php echo $root_link . "setting/dsp_paypal/"; ?>">
            <input type="hidden" name="notify_url"
                   value="<?php echo site_url() . '/?wpdating-api=WC_Gateway_Paypal'; ?>">
            <input type="hidden" name="custom" value="<?php echo isset($custom_field_val) ? $custom_field_val : ''; ?>">
        </form>
        <script type="text/javascript">
            document.frm1.submit();
        </script>
        <?php
    }
    ?>
    
    <div class="heading-submenu dsp-upgrade-heading">
        <strong>
            <?php echo language_code('DSP_UPGRADE_ACCOUNT'); ?>
        </strong>
        
        <?php
        $payment_row       = $wpdb->get_row("SELECT * FROM $dsp_payments_table WHERE pay_user_id=$user_id");
        if ($payment_row != null && strtotime($payment_row->expiration_date) > time()) {
            ?>
            <div class="premium-area dspdp-alert dspdp-alert-success" style="font-size:0.7em">
                <div class="dspdp-row">
                    <div
                            class="logo-premium dsp-none dspdp-font-4x dspdp-text-center dspdp-col-sm-3">
                        <img class="dspdp-block dspdp-spacer-sm"
                             src="<?php echo get_bloginfo('url'); ?>/wp-content/uploads/dsp_media/dsp_images/<?php echo $wpdb->get_var("select image from $dsp_memberships_table where membership_id='" . $payment_row->pay_plan_id . "'"); ?>"
                             alt="<?php echo $payment_row->pay_plan_name; ?>"/><?php echo $payment_row->pay_plan_name; ?>
                    </div>
                    <div class="dspdp-col-sm-9">
                        <div class="clearfix dspdp-font-2x dspdp-spacer-sm">
                            <?php echo strip_tags(language_code('DSP_HOME_MEMBERSHIP_PREMIUM_TEXT_A')); ?>
                            <?php echo $payment_row->pay_plan_name; ?><?php echo language_code('DSP_HOME_MEMBERSHIP_PREMIUM_TEXT_B'); ?>
                        </div>
                        <div class="seperator dsp-block" style="display:none"></div>
                        <div class="clearfix">
                            <?php echo language_code('DSP_HOME_MEMBERSHIP_PREMIUM_TEXT_C'); ?>
                            <span
                                    class="dsp-emphasis-text dsp-strong"><?php echo date(get_option('date_format'),
                                    strtotime($payment_row->expiration_date)); ?></span>

                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
 
    <?php
    $exists_memberships_plan = $wpdb->get_results("SELECT * FROM $dsp_memberships_table where display_status='Y' ORDER BY date_added DESC");

    include(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/free_plan.php");

    foreach ($exists_memberships_plan as $membership_plan) {
        $price                               = $membership_plan->price;
        $no_of_days                          = $membership_plan->no_of_days;
        $name                                = $membership_plan->name;
        $membership_id                       = $membership_plan->membership_id;
        $desc                                = $membership_plan->description;
        $image                               = $membership_plan->image;
        $free_plan                           = $membership_plan->free_plan;
        $membership_stripe_recurring_plan_id = $membership_plan->stripe_recurring_plan_id;
        ?>
        <?php /* ?><table cellpadding="0" cellspacing="0" width="100%" border="0">
      <tr><td colspan="2" height="2px"></td></tr>
      <tr>
      <td>
      <span onclick="payment('<?php echo $name?>','<?php echo $price?>','<?php echo $membership_id?>');" class="dsp_span_pointer"><strong><?php echo $currency_code?><?php echo $price?> for <?php echo $name?></strong></span>
      </td></tr>
      <tr>
      <td><?php echo language_code('DSP_UPGRADE_ACCOUNT_SETTINGS_TEXT');?>&nbsp;<?php echo $name?>!</td>
      </tr>
      </table><?php */ ?>

        <?php if ($free_plan == 1) {
            $free_plan_values    = array(
                'membership_id' => $membership_id,
                'imagepath'     => $imagepath,
                'image'         => $image,
                'name'          => $name,
                'desc'          => $desc
            );
            $dsp_membership_plan = new dsp_membership_plan($free_plan_values);

            ?>

        <?php } else { ?>

            <div class="dspdp-col-sm-4 dsp-sm-4 dspdp-text-center">
                <div class="box-border dsp-upgrade-container">
                    <div class="box-pedding">
                        <div class="setting-page__disable dsp-member-upgrade-page">
                            <ul class="dspdp-row dspdp-xs-text-center">
                                <li>
                                    <div class="dspdp-spacer "><img class="dspdp-img-responsive dspdp-block-center"
                                                                    src='<?php echo $imagepath ?>/uploads/dsp_media/dsp_images/<?php echo $image; ?>'
                                                                    title="<?php echo $name ?>"
                                                                    alt="<?php echo $name ?>"/></div>
                                </li>
                                <li>
                                    <div class="dspdp-spacer dspdp-upgrade-desc"><strong><?php echo $name; ?></strong></div>
                                </li>
                                <li>
                                    <div class="dspdp-spacer dspdp-upgrade-desc"><?php echo $desc; ?></div>
                                </li>
                                <li>
                                    <?php
                                    if ($check_gateways_mode->setting_status == 'Y') {
                                        $gateway_table = $wpdb->get_results("SELECT * FROM $dsp_gateways_table");

                                        foreach ($gateway_table as $gateway) {

                                            //echo '<br>name'.$gateway->gateway_name;
                                            if ($gateway->gateway_name == 'paypal' && $gateway->status == 1) {
                                                ?>
                                                <div>
                                                    <form name="paymentfrm"
                                                          action="<?php echo $root_link . "setting/upgrade_account/mode/update/"; ?>"
                                                          method="post">
                                                        <input type="hidden" name="item_name" id="item_name"
                                                               value="<?php echo $name; ?>"/>

                                                        <input type="hidden" name="amount" id="amount"
                                                               value="<?php echo $price; ?>"/>

                                                        <input type="hidden" name="membership_id" id="membership_id"
                                                               value="<?php echo $membership_id; ?>"/>


                                                        <input name="upgrade" title="Upgrade / PayPal" type="submit"
                                                               value="<?php echo language_code('DSP_UPGRADE_PAYPAL_BTN') ?>"
                                                               class="dsp_span_pointer dspdp-btn dspdp-btn-default"
                                                               style="text-decoration:none;"/>
                                                        <br/> <span
                                                                style="font-size:130%; "><?php echo $gateway->currency_symbol; ?><?php echo $price ?></span>
                                                        <br/>
                                                    </form>
                                                </div>
                                                <?php

                                                if ($gateway->recurring == 1) {
                                                    $validity_of_membership_days = paypal_recurring_formatted_duration($no_of_days);
                                                    $already_in_use              = using_paypal_subscription_for_membership($user_id,
                                                        $membership_id, $no_of_days);
                                                    if ($validity_of_membership_days['error'] == false && $already_in_use == false) {
                                                        ?>
                                                        <form action="<?php echo $root_link . "setting/paypal_subscription"; ?>"
                                                              method="post">
                                                            <input type="hidden" name="membership_id"
                                                                   value="<?php echo $membership_id; ?>"/>
                                                            <input type="hidden" name="id"
                                                                   value="<?php echo $gateway->gateway_id; ?>"/>
                                                            <input title="<?php echo language_code('DSP_RECURRING_WARNING_USER'); ?>"
                                                                   type="image" name="submit"
                                                                   src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribe_LG.gif"
                                                                   alt="PayPal - The safer, easier way to pay online">
                                                        </form>
                                                        <?php
                                                    } elseif ($already_in_use == true) {
                                                        echo '<span class="error">You are already subscribed to this membership.</span>';
                                                    }
                                                }

                                            } else if ($gateway->gateway_name == 'authorize' && $gateway->status == 1) {
                                                ?>
                                                <div>
                                                    <input name="upgrade" title="Upgrade / Credit Card" type="button"
                                                           value="<?php echo language_code('DSP_UPGRADE_CREDITCARD_BTN') ?>"
                                                           onclick="window.location = '<?php echo $root_link . "setting/auth_settings/item_name/$name/id/" . $membership_id . "/"; ?>'"
                                                           class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
                                                           style="text-decoration:none; margin-top:5px;"/>
                                                    <br/> <span
                                                            style="font-size:130%; "><?php echo $gateway->currency_symbol; ?><?php echo $price ?></span>
                                                    <br/>
                                                </div>
                                                <?php
                                            } else if ($gateway->gateway_name == 'paypal pro' && $gateway->status == 1) {
                                                ?>
                                                <div>
                                                    <input name="upgrade" title="Upgrade / PayPal Pro" type="button"
                                                           value="<?php echo language_code('DSP_UPGRADE_PAYPALPRO_BTN') ?>"
                                                           onclick="window.location = '<?php echo $root_link . "setting/pro_settings/item_name/$name/id/" . $membership_id . "/"; ?>'"
                                                           class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
                                                           style="text-decoration:none;"/>
                                                    <br/> <span
                                                            style="font-size:130%; "><?php echo $gateway->currency_symbol; ?><?php echo $price ?></span>
                                                    <br/>
                                                </div>
                                                <?php
                                            } else if ($gateway->gateway_name == 'paypal advance' && $gateway->status == 1) {
                                                if ($recurringPaymentSatatus) {
                                                    ?>
                                                    <div style="width: 100%;text-align: center;padding-bottom: 12px;font-weight: bold;font-size: 13px ">
                                                        <?php echo language_code('DSP_U_ARE_ALREADY_A') . $name . ' ' . language_code('DSP_PLAN_MEMBER'); ?>
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div>
                                                        <form action="<?php echo $root_link . "setting/paypal_advance/"; ?>"
                                                              method="post">
                                                            <input type="hidden" name="item_name" id="item_name"
                                                                   value="<?php echo $name; ?>"/>

                                                            <input type="hidden" name="amount" id="amount"
                                                                   value="<?php echo $price; ?>"/>

                                                            <input type="hidden" name="no_days" id="no_days"
                                                                   value="<?php echo $no_of_days; ?>"/>


                                                            <input type="hidden" name="membership_id" id="membership_id"
                                                                   value="<?php echo $membership_id; ?>"/>
                                                            <!--<input type="submit" value="<?php echo language_code('DSP_UPGRADE_PAYPALADV_BTN') ?>" name="btn_advance" />-->
                                                            <input class="subscribe  dspdp-btn dspdp-btn-default"
                                                                   name="btn_advance" type="submit"
                                                                   value="<?php echo language_code('DSP_UPGRADE_PAYPALADV_BTN') ?>"/>
                                                        </form>

                                                        <span style="font-size:130%; "><?php echo $gateway->currency_symbol; ?><?php echo $price ?></span>
                                                        <br/>
                                                    </div>
                                                    <?php
                                                }
                                            } else if ($gateway->gateway_name == 'iDEAL' && $gateway->status == 1) {
                                                ?>
                                                <div>
                                                    <input name="upgrade" title="Upgrade / iDEAL" type="button"
                                                           value="<?php echo language_code('DSP_UPGRADE_IDEAL_BTN') ?>"
                                                           onclick="window.location = '<?php echo $root_link . "setting/iDEAL/item_name/$name/id/" . $membership_id . "/"; ?>'"
                                                           class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
                                                           style="text-decoration:none;"/>
                                                    <br/> <span
                                                            style="font-size:130%; "><?php echo $gateway->currency_symbol; ?><?php echo $price ?></span>
                                                    <br/>
                                                </div>
                                                <?php
                                            } else if ($gateway->gateway_name == 'bank_wire' && $gateway->status == 1) {
                                                ?>
                                                <div>

                                                    <input name="upgrade" title="Upgrade / Bank wire" type="button"
                                                           value="<?php echo language_code('DSP_UPGRADE_BANK_WIRE') ?>"
                                                           onclick="window.location = '<?php echo $root_link . "setting/BANK_WIRE/id/" . $membership_id . "/"; ?>'"
                                                           class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
                                                           style="text-decoration:none;"/>
                                                    <br/> <span
                                                            style="font-size:130%; "><?php echo $gateway->currency_symbol; ?><?php echo $price ?></span>
                                                    <br/>
                                                </div>
                                                <?php
                                            } else if ($gateway->gateway_name == 'cheque_payment' && $gateway->status == 1) {
                                                ?>
                                                <div>
                                                    <input name="upgrade" title="Upgrade / Cheque payment" type="button"
                                                           value="<?php echo language_code('DSP_UPGRADE_CHEQUE_PAYMENT') ?>"
                                                           onclick="window.location = '<?php echo $root_link . "setting/CHEQUE_PAYMENT/id/" . $membership_id . "/"; ?>'"
                                                           class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
                                                           style="text-decoration:none;"/>
                                                    <br/> <span
                                                            style="font-size:130%; "><?php echo $gateway->currency_symbol; ?><?php echo $price ?></span>
                                                    <br/>
                                                </div>
                                                <?php
                                            }
                                        } // end of for each loop

                                        do_action('dsp_payment_addons', $user_id, $membership_id, $name, $price,
                                            $no_of_days, $desc, $image, $blogInfo, $uploadInfo,
                                            $membership_stripe_recurring_plan_id);
                                    } //if($check_gateways_mode->setting_status == 'Y'){
                                    else {
                                        $gateway_row = $wpdb->get_row("SELECT * FROM $dsp_gateways_table where status='1'");
                                        if ($gateway_row->gateway_name == 'paypal') {
                                            ?>

                                            <div>
                                                <form name="paymentfrm"
                                                      action="<?php echo $root_link . "setting/upgrade_account/mode/update/"; ?>"
                                                      method="post">
                                                    <input type="hidden" name="item_name" id="item_name"
                                                           value="<?php echo $name; ?>"/>

                                                    <input type="hidden" name="amount" id="amount"
                                                           value="<?php echo $price; ?>"/>
                                                    <input type="hidden" name="membership_id" id="membership_id"
                                                           value="<?php echo $membership_id; ?>"/>

                                                    <input name="upgrade" title="Upgrade / PayPal" type="submit"
                                                           value="<?php echo language_code('DSP_UPGRADE_PAYPAL_BTN') ?>"
                                                           class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
                                                           style="text-decoration:none;"/>
                                                    <br/> <span
                                                            style="font-size:130%; "><?php $currencySymbol = isset($gateway_row->currency_symbol) ? $gateway_row->currency_symbol : '';
                                                        echo $currencySymbol; ?><?php echo $price ?></span> <br/>
                                                </form>
                                            </div>
                                            <?php
                                            if ($gateway_row->recurring == 1) {
                                                $validity_of_membership_days = paypal_recurring_formatted_duration($no_of_days);
                                                $already_in_use              = using_paypal_subscription_for_membership($user_id,
                                                    $membership_id, $no_of_days);
                                                if ($validity_of_membership_days['error'] == false && $already_in_use == false) {
                                                    ?>
                                                    <form action="<?php echo $root_link . "setting/paypal_subscription"; ?>"
                                                          method="post">
                                                        <input type="hidden" name="membership_id"
                                                               value="<?php echo $membership_id; ?>"/>
                                                        <input type="hidden" name="id"
                                                               value="<?php echo $gateway_row->gateway_id; ?>"/>
                                                        <input title="This is a recurring payment plan. You will be subscribed to this plan until you cancel it from your paypal account."
                                                               type="image" name="submit"
                                                               src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribe_LG.gif"
                                                               alt="PayPal - The safer, easier way to pay online">
                                                    </form>
                                                    <?php
                                                } elseif ($already_in_use == true) {
                                                    echo '<span class="error">You are already subscribed to this membership.</span>';
                                                }
                                            }
                                        } else if ($gateway_row->gateway_name == 'authorize') {
                                            ?>
                                            <div>
                                                <input name="upgrade" title="Upgrade / Credit Card" type="button"
                                                       value="<?php echo language_code('DSP_UPGRADE_CREDITCARD_BTN') ?>"
                                                       onclick="window.location = '<?php echo $root_link . "setting/auth_settings/id/" . $membership_id . "/"; ?>'"
                                                       class="dsp_span_pointer"
                                                       style="text-decoration:none; margin-top:5px;"/>
                                                <br/> <span
                                                        style="font-size:130%; "><?php echo $gateway_row->currency_symbol; ?><?php echo $price ?></span>
                                                <br/>
                                            </div>
                                            <?php
                                        } else if ($gateway_row->gateway_name == 'paypal pro' && $gateway_row->status == 1) {
                                            ?>
                                            <div>
                                                <input name="upgrade" title="Upgrade / PayPal Pro" type="button"
                                                       value="<?php echo language_code('DSP_UPGRADE_PAYPALPRO_BTN') ?>"
                                                       onclick="window.location = '<?php echo $root_link . "setting/pro_settings/id/" . $membership_id . "/"; ?>'"
                                                       class="dsp_span_pointer" style="text-decoration:none;"/>
                                                <br/> <span
                                                        style="font-size:130%; "><?php echo $gateway_row->currency_symbol; ?><?php echo $price ?></span>
                                                <br/>
                                            </div>
                                            <?php
                                        } else if ($gateway_row->gateway_name == 'paypal advance' && $gateway_row->status == 1) {
                                            if ($recurringPaymentSatatus) {
                                                ?>
                                                <div style="width: 100%;text-align: center;padding-bottom: 12px;font-weight: bold;font-size: 13px ">
                                                    <?php echo language_code('DSP_U_ARE_ALREADY_A') . $name . ' ' . language_code('DSP_PLAN_MEMBER'); ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div>
                                                    <form action="<?php echo $root_link . "setting/paypal_advance/"; ?>"
                                                          method="post">
                                                        <input type="hidden" name="item_name" id="item_name"
                                                               value="<?php echo $name; ?>"/>

                                                        <input type="hidden" name="amount" id="amount"
                                                               value="<?php echo $price; ?>"/>

                                                        <input type="hidden" name="no_days" id="no_days"
                                                               value="<?php echo $no_of_days; ?>"/>


                                                        <input type="hidden" name="membership_id" id="membership_id"
                                                               value="<?php echo $membership_id; ?>"/>

                                                        <input class="subscribe  dspdp-btn dspdp-btn-default"
                                                               name="btn_advance" type="submit"
                                                               value="<?php echo language_code('DSP_UPGRADE_PAYPALADV_BTN') ?>"/>
                                                    </form>

                                                    <span><?php echo $gateway_row->currency_symbol; ?><?php echo $price ?></span>
                                                    <br/>
                                                </div>
                                                <?php
                                            }
                                        }
                                        do_action('dsp_payment_addons', $user_id, $membership_id, $name, $price,
                                            $no_of_days, $desc, $image, $blogInfo, $uploadInfo,
                                            $membership_stripe_recurring_plan_id);
                                    } // else if($check_gateways_mode->setting_status == 'N'){
                                    ?>


                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if (dsp_check_discount_mode()) { ?>
        <?php //include("wp-content/plugins/dsp_dating/dsp_discount_mode.php"); ?>
    <?php } ?>
    <?php if ($check_credit_mode->setting_status == 'Y') { ?>
        <?php include_once(WP_DSP_ABSPATH . "credit_upgrade_account_settings.php"); ?>
    <?php } ?>

    <script type="text/javascript">
        function payment(item_name, amount, id) {
            // alert(' paymanet  ' + item_name + ' ' + amount + ' ' + id);
            document.paymentfrm.item_name.value = item_name;
            document.paymentfrm.amount.value = amount;
            document.paymentfrm.membership_id.value = id;
//document.paymentfrm.submit();
        }
    </script>
    <?php
    //-------------------------------END UPGRADE ACCOUNT SETTINGS ---------------------------------- ?></div></div></div>