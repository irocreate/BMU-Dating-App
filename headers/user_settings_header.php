<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

$profile_pageurl = get('pagetitle'); //echo $profile_pageurl;die;

$action = isset($_POST['action']) ? $_POST['action'] : '';

$discountStatus      = get('discountStatus');
$isDiscountModuleOff = dsp_check_discount_code_setting();
$id                  = isset($_POST['membership_id']) ? $_POST['membership_id'] : get('id');
$checkZeroMembership = dsp_check_zero_membership($id);
?>
    <div class="line">
        <div <?php if (($profile_pageurl == "account_settings")) { ?>class="dsp_tab1-active"
             <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . "setting/account_settings/"; ?>"><?php echo language_code('DSP_SUBMENU_SETTINGS_ACCOUNT'); ?></a>
        </div>
        <!--  <?php if ($check_match_alert_mode->setting_status == 'Y') { ?>
       <div <?php if (($profile_pageurl == "match_alert")) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
           <a href="<?php echo $root_link . "setting/match_alert/"; ?>"><?php echo language_code('DSP_SUBMENU_SETTINGS_MATCH_ALERTS'); ?></a></div>
   <?php } ?> -->
        <div <?php if ($profile_pageurl == "blocked") { ?>class="dsp_tab1-active"
             <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . "setting/blocked/"; ?>"><?php echo language_code('DSP_MIDDLE_TAB_BLOCKED'); ?></a>
        </div>
        <div <?php if ($profile_pageurl == "notification") { ?>class="dsp_tab1-active"
             <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . "setting/notification/"; ?>"><?php echo language_code('DSP_SUBMENU_SETTINGS_NOTIFICATION'); ?></a>
        </div>
        <div <?php if (($profile_pageurl == "privacy_settings")) { ?>class="dsp_tab1-active"
             <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . "setting/privacy_settings/"; ?>"><?php echo language_code('DSP_SUBMENU_SETTINGS_PRIVACY'); ?></a>
        </div>
        <?php if ($check_skype_mode->setting_status == 'Y') { // Check Skype mode Activated or not  ?>
            <div <?php if (($profile_pageurl == "skype_settings")) { ?>class="dsp_tab1-active"
                 <?php } else { ?>class="dsp_tab1" <?php } ?>>
                <a href="<?php echo $root_link . "setting/skype_settings/"; ?>"><?php echo language_code('DSP_SUBMENU_SETTINGS_SKYPE'); ?></a>
            </div>
        <?php } // END Skype mode Activation check condition  ?>
        <div <?php if (($profile_pageurl == "upgrade_account")) { ?>class="dsp_tab1-active"
             <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"><?php echo language_code('DSP_SUBMENU_SETTINGS_UPGRADE_ACCOUNT'); ?></a>
        </div>
        <div class="clr"></div>
    </div></div>

<?php
//one to one chat pop up notification 
apply_filters('dsp_get_single_chat_popup_notification', $notification);

if ($profile_pageurl == "account_settings") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/user_account_settings/user_account_settings.php");
} else if ($profile_pageurl == "notification") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/notifications/user_notification_settings.php");
} else if ($profile_pageurl == "privacy_settings") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/privacy/user_privacy_settings.php");
} else if ($profile_pageurl == "upgrade_account") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/upgrade_account_settings.php");
} else if ($profile_pageurl == "upgrade_account_details") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/upgrade_account_details/dsp_upgrade_account_details.php");
} else if ($profile_pageurl == "credit_upgrade_account_details") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/credit_upgrade_account_details/dsp_credit_upgrade_account_details.php");
} else if ($profile_pageurl == "dsp_stripe") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/discounts/stripe_discount.php");
} else if ($profile_pageurl == "dsp_paypal") {
    if ((isset($action) && ! empty($action)) || $isDiscountModuleOff) {
        $_GET['action'] = 'process';
    }
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/payments/paypal.php");
} else if ($profile_pageurl == "paypal_subscription") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/payments/paypal_subscription.php");
} else if ($profile_pageurl == "skype_settings") {
    $access_feature_name = "Skype";
    if ($check_free_mode->setting_status == "N") {  // free mode is off
        if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved" || $check_force_profile_msg == "NoAccess" || $check_force_profile_msg == "Expired" || $check_force_profile_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } else if ($check_force_profile_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "members/loggedin/settings/skype/dsp_skype_settings.php");
            }
        } else {
            if ($check_free_trail_mode->setting_status == "Y") { // free trial mode is on
                $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
                if ($check_member_trial_msg == "NotExist" || $check_member_trial_msg == "Approved" || $check_member_trial_msg == "Expired" || $check_member_trial_msg == "NoAccess" || $check_member_trial_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_member_trial_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/skype/dsp_skype_settings.php");
                }
            } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                $check_approved_profile_msg = check_approved_profile_feature($user_id);
                if ($check_approved_profile_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_approved_profile_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/skype/dsp_skype_settings.php");
                }
            } else { // if free trial mode is off
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired" || $check_membership_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/skype/dsp_skype_settings.php");
                }
            }
        }
    } else {

        if ($_SESSION['free_member']) {
            include_once(WP_DSP_ABSPATH . "members/loggedin/settings/skype/dsp_skype_settings.php");
        } else {
            if ($check_force_profile_mode->setting_status == "Y") {
                $check_force_profile_msg = check_free_force_profile_feature($user_id);
                if ($check_force_profile_msg == "Approved" || $check_force_profile_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_force_profile_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/skype/dsp_skype_settings.php");
                }
            } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                $check_approved_profile_msg = check_approved_profile_feature($user_id);
                if ($check_approved_profile_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_approved_profile_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/skype/dsp_skype_settings.php");
                }
            } else {
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired" || $check_membership_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } else if ($check_membership_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "members/loggedin/search/zipcode/zip_code_search.php");
                }
            }
        }
    }
} else if ($profile_pageurl == "auth_settings") {
    if ($checkZeroMembership) {
        wp_safe_redirect(ROOT_LINK . "setting/dsp_thank_you");
    } else if ((isset($discountStatus) && ! empty($discountStatus)) || $isDiscountModuleOff) {
        include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/auth_settings/dsp_upgrade_setting_details.php");
    } else {
        include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/discounts/dsp_discount_details.php");
    }
} else if ($profile_pageurl == "credit_auth_settings") {
    if ($checkZeroMembership) {
        wp_safe_redirect(ROOT_LINK . "setting/dsp_thank_you");
    } else if ((isset($discountStatus) && ! empty($discountStatus)) || $isDiscountModuleOff) {
        include_once(WP_DSP_ABSPATH . "/members/loggedin/settings/upgrade_accounts/credit_auth_settings/dsp_credit_upgrade_setting_details.php");
    } else {
        include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/discounts/dsp_discount_details.php");
    }
} else if ($profile_pageurl == "auth_settings_detail") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/auth_settings_detail/dsp_upgrade_check_setting.php");
} else if ($profile_pageurl == "credit_auth_settings_detail") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/credit_auth_settings_detail/dsp_credit_upgrade_check_setting.php");
} else if ($profile_pageurl == "pro_settings") {
    if ($checkZeroMembership) {
        wp_safe_redirect(ROOT_LINK . "setting/dsp_thank_you");
    } else if ((isset($discountStatus) && ! empty($discountStatus)) || $isDiscountModuleOff) {
        include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/pro_settings/dsp_upgrade_paypalpro_setting.php");
    } else {
        include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/discounts/dsp_discount_details.php");
    }
} else if ($profile_pageurl == "credit_pro_settings") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/credit_pro_settings/dsp_credit_upgrade_paypalpro_setting.php");
} else if ($profile_pageurl == "pro_settings_detail") {

    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/pro_settings_detail/dsp_upgrade_paypalpro_detail.php");
} else if ($profile_pageurl == "credit_pro_settings_detail") {

    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/credit_pro_settings_detail/dsp_credit_upgrade_paypalpro_detail.php");
} else if ($profile_pageurl == "dsp_error") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/dsp_error/dsp_error.php");
} else if ($profile_pageurl == "dsp_cancel") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/dsp_cancel/dsp_cancel.php");
} else if ($profile_pageurl == "paypal_advance") {
    if ($checkZeroMembership) {
        wp_safe_redirect(ROOT_LINK . "setting/dsp_thank_you");
    } else if ((isset($discountStatus) && ! empty($discountStatus)) || $isDiscountModuleOff) {
        include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/paypal_advance/dsp_upgrade_paypal_advance.php");
    } else {
        include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/discounts/dsp_discount_details.php");
    }
} else if ($profile_pageurl == "create_recur") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/create_recur/dsp_create_recur.php");
} else if ($profile_pageurl == "dsp_cancel_membership") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/dsp_cancel_membership/dsp_cancel_membership.php");
} else if ($profile_pageurl == "dsp_thank_you") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/dsp_thank_you/dsp_thank_you.php");
} else if ($profile_pageurl == "dsp_subscription_successful") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/dsp_thank_you/dsp_subscription_successful.php");
} else if ($profile_pageurl == "dsp_credit_thank_you") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/dsp_credit_thank_you/dsp_credit_thank_you.php");
} else if ($profile_pageurl == "dsp_iDEAL_thank_you") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/dsp_ideal_thank_you/dsp_ideal_thank_you.php");
} else if ($profile_pageurl == "iDEAL") {
    if ($checkZeroMembership) {
        wp_safe_redirect(ROOT_LINK . "setting/dsp_thank_you");
    } else if ((isset($discountStatus) && ! empty($discountStatus)) || $isDiscountModuleOff) {
        include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/ideal/dsp_upgrade_ideal_payment.php");
    } else {
        include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/discounts/dsp_discount_details.php");
    }
} else if ($profile_pageurl == "credit_iDEAL") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/credit_ideal/dsp_upgrade_credit_ideal_payment.php");
} else if ($profile_pageurl == "BANK_WIRE" || $profile_pageurl == "CHEQUE_PAYMENT") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/bank_wire/dsp_upgrade_credit_bank_wire_payment.php");
} else if ($profile_pageurl == "blocked") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/settings/blocked/dsp_blocked_members.php");
}
