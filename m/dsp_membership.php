<link href="https://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
<link href="index.css" rel="stylesheet" type="text/css">

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
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_HOME_TAB_MEMBERSHIPS'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>

</div>
<div class="ui-content" data-role="content">
    <div class="content-primary">
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">
            <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">

                <?php if ($check_free_mode->setting_status == 'Y') {
                    ?>

                    <div class="tab-content hide" id="membership">

                        <ul>

                            <li class="new-title"><?php echo language_code('DSP_HOME_TAB_MEMBERSHIP'); ?></li>

                            <li class="news-info">

                                <ul>

                                    <li><?php echo language_code('DSP_HOME_MEMBERSHIP_FREE_TEXT'); ?></li>

                                    <li><img src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/oh-yes-its-free.jpg' ?>" /></li>

                                </ul>

                            </li>

                        </ul>

                    </div>

                    <?php
                } else {

                    $payment_row = $wpdb->get_row("SELECT * FROM $dsp_payments_table WHERE pay_user_id=$user_id");

                    $count_payment_row = count($payment_row);

                    if ($count_payment_row > 0) {
                        ?>

                        <div id="membership">

                            <ul>

                                <li class="new-title"><?php echo language_code('DSP_HOME_TAB_MEMBERSHIP'); ?></li>

                                <li class="news-info">

                                    <ul>

                                        <li>
                                            <div style="width:50%;float: left;"> 
                                                <img src="<?php echo get_bloginfo('url'); ?>/wp-content/uploads/dsp_media/dsp_images/<?php echo $wpdb->get_var("select image from $dsp_memberships_table where membership_id='" . $payment_row->pay_plan_id . "'"); ?>" />	
                                            </div>
                                            <div > 
                                                <?php echo $payment_row->pay_plan_name; ?>	
                                            </div>
                                        </li>

                                        <li style="float: left;width: 100%"><?php echo language_code('DSP_HOME_MEMBERSHIP_PREMIUM_TEXT_A'); ?> <?php echo $payment_row->pay_plan_name; ?> <?php echo language_code('DSP_HOME_MEMBERSHIP_PREMIUM_TEXT_B'); ?>

                                        </li>

                                        <li style="float: left;width: 100%"><?php echo language_code('DSP_HOME_MEMBERSHIP_PREMIUM_TEXT_C'); ?> <?php echo date('d-m-Y', strtotime($payment_row->expiration_date)); ?></li>

                                    </ul>

                                </li>

                            </ul>

                        </div>

                        <?php
                    } else {
                        ?>

                        <div  id="membership">

                            <ul>

                                <li class="new-title"><?php echo language_code('DSP_HOME_TAB_MEMBERSHIP'); ?></li>

                                <li class="news-info">

                                    <ul>

                                        <li><?php echo language_code('DSP_HOME_MEMBERSHIP_STANDARD_TEXT'); ?>

                                        </li>

                                        <li>
                                            <input onclick="openUpgrade()" name="" type="button"  value="<?php echo language_code('DSP_UPGRADE_NOW'); ?>!" />
                                        </li>

                                    </ul>

                                </li>

                            </ul>

                            <?php if ($check_credit_mode->setting_status == 'Y') {
                                ?>
                                <ul>

                                    <li class="new-title"><?php echo language_code('DSP_USER_CREDITS'); ?></li>

                                    <li class="news-info">

                                        <ul>
                                            <?php
                                            $no_of_credits = $wpdb->get_var("select no_of_credits from $dsp_credits_usage_table where user_id='$user_id'");
                                            if (count($no_of_credits) == 0)
                                                $no_of_credits = count($no_of_credits);
                                            ?>
                                            <li><?php echo str_replace('[m]', $no_of_credits, language_code('DSP_CREDIT_HOMEPAGE_TEXT')); ?>
                                            </li>

                                            <li>
                                                <input onclick="openUpgrade()" name="" type="button" value="<?php echo language_code('DSP_BUY_CREDITS'); ?>" />
                                            </li>

                                        </ul>

                                    </li>

                                </ul>
                            <?php } ?>
                        </div>

                        <?php
                    }
                }
                ?>
            </li>
        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up     ?>
</div>