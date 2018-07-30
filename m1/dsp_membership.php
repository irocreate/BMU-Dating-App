<?php
include("../../../../wp-config.php");
/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include_once("dspGetSite.php"); // this page contains the function cleanUrl that will cleasn the url


$url = get_bloginfo('url');
$siteUrl = cleanUrl($url);

$user_id = $_REQUEST['user_id'];
// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');
include_once('../general_settings.php');


$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
$dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
?>

<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_menu.php");?>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_HOME_TAB_MEMBERSHIPS'); ?></h1>
    <?php include_once("page_home.php");?>

</div>
<div class="ui-content" data-role="content">
    <div class="content-primary">
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul userlist">
            <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">

                <?php

                if ($check_free_mode->setting_status == 'Y') {
                    ?>
                             <div class="dsp_mail_lf">
                             <img src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/oh-yes-its-free.jpg' ?>" width="100" height="100"/>
                             </div>
                  <div class="dsp_mail_rt"> 
                      <strong><?php echo language_code('DSP_HOME_TAB_MEMBERSHIP'); ?></strong>
                     <br><?php echo language_code('DSP_HOME_MEMBERSHIP_FREE_TEXT'); ?>
                    </div>
                    <?php
                } else {

                    $payment_row = $wpdb->get_row("SELECT * FROM $dsp_payments_table WHERE pay_user_id=$user_id");

                    $count_payment_row = count($payment_row);

                    if ($count_payment_row > 0) {
                        ?>

                        <div class="dsp_mail_lf">
                            <img src="<?php echo get_bloginfo('url'); ?>/wp-content/uploads/dsp_media/dsp_images/<?php echo $wpdb->get_var("select image from $dsp_memberships_table where membership_id='" . $payment_row->pay_plan_id . "'"); ?>" />  
                        </div>

                        <div class="dsp_mail_rt"> 
                            <strong><?php echo language_code('DSP_HOME_TAB_MEMBERSHIP'); ?></strong> <?php echo $payment_row->pay_plan_name; ?>

                            <span> <?php echo language_code('DSP_HOME_MEMBERSHIP_PREMIUM_TEXT_A'); ?> :<strong><?php echo $payment_row->pay_plan_name; ?> <?php echo language_code('DSP_HOME_MEMBERSHIP_PREMIUM_TEXT_B'); ?></strong></span>
                            <br>
                            <span ><?php echo language_code('DSP_HOME_MEMBERSHIP_PREMIUM_TEXT_C'); ?> :<strong><?php echo date('d-m-Y', strtotime($payment_row->expiration_date)); ?></strong></span>
                        </div>


                        <?php
                    } else {
                        ?>

                        <div  id="membership">



                            <strong><?php echo language_code('DSP_HOME_TAB_MEMBERSHIP'); ?></strong>

                            <div class="news-info">
                                <span><?php echo language_code('DSP_HOME_MEMBERSHIP_STANDARD_TEXT'); ?></span>

                                <div class="btn-blue-wrap"> <input onclick="openUpgrade()" class="mam_btn btn-blue" name="" type="button"  value="<?php echo language_code('DSP_UPGRADE_NOW'); ?>!" /></div>
                            </div>
                            <?php 
                            
                            if ($check_credit_mode->setting_status == 'Y') {
                                ?>
                                <strong><?php echo language_code('DSP_USER_CREDITS'); ?></strong>
                                <div class="news-info">
                                    <?php
                                    $no_of_credits = $wpdb->get_var("select no_of_credits from $dsp_credits_usage_table where user_id='$user_id'");
                                    if (count($no_of_credits) == 0)
                                        $no_of_credits = count($no_of_credits);
                                    ?>
                                    <span><?php echo str_replace('[m]', $no_of_credits, language_code('DSP_CREDIT_HOMEPAGE_TEXT')); ?>
                                    </span>

<!--                                    <div class="btn-blue-wrap">-->
<!--                                        <input onclick="openUpgrade()" name="" class="mam_btn btn-blue"  type="button" value="--><?php //echo language_code('DSP_BUY_CREDITS'); ?><!--" />-->
<!--                                    </div>-->
                                </div>
                                <?php } ?>
                            </div>

                            <?php }
                    }
                    ?>
                </li>
            </ul>
        </div>
        <?php include_once('dspNotificationPopup.php'); // for notification pop up     ?>
    </div>
    <?php include_once("dspLeftMenu.php"); ?>