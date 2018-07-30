<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

//error_reporting(0);

$const_image_link = WPDATE_URL . "/images/";
wp_deregister_style('jquery-ui-1.8.6.custom');
wp_register_style('jquery-ui-1.8.6.custom', WPDATE_URL . '/css/jquery-ui-1.8.6.custom.css');
wp_enqueue_style('jquery-ui-1.8.6.custom');
wp_enqueue_script('jquery-ui-1.8.6.custom',  WPDATE_URL . '/css/jquery-ui-1.8.6.custom.min.js', array(), '', true);

$cal_image_url = WPDATE_URL . '/images/dsp_calendar.gif'
?>

<script type="text/javascript">

    var $t = jQuery.noConflict();

    $t(function() {

        $t('.example-container > pre').each(function(i) {

            eval($t(this).text());

        });

    });

</script>





<script type="text/javascript">



    function checkValidPost()

    {

        //alert('check');

        if (document.forms[0].radio_payment[1].checked)// duration radio button

        {



            //check duration is not blank

            if (document.getElementById('txt_during_last_days').value == '')

            {

                alert("<?php echo language_code('DSP_PLEASE_ENTER_DAYS'); ?>");

                document.getElementById('txt_during_last_days').focus();

                return false;

            }

            else

            {

                if (isNaN(document.getElementById('txt_during_last_days').value))

                {

                    alert("<?php echo language_code('DSP_PLEASE_ENTER_A_NUMBER'); ?>");

                    document.getElementById('txt_during_last_days').focus();

                    return false;

                }

            }

        }

        if (document.forms[0].radio_payment[2].checked)// on date radio button

        {



            // check date is not blank

            if (document.getElementById('datepicker1').value == '')

            {

                alert("<?php echo language_code('DSP_PLEASE_ENTER_ON_DATE'); ?>");

                document.getElementById('datepicker1').focus();

                return false;

            }

        }

        if (document.forms[0].radio_payment[3].checked)// between dates radio button 

        {



            // check if from date is blank

            if (document.getElementById('from').value == '')

            {

                alert("<?php echo language_code('DSP_PLEASE_ENTER_FROM_DATE'); ?>");

                document.getElementById('from').focus();

                return false;

            }

            //check if to date is blank

            if (document.getElementById('to').value == '')

            {

                alert("<?php echo language_code('DSP_PLEASE_ENTER_TO_DATE'); ?>");

                document.getElementById('to').focus();

                return false;

            }





        }





    }

</script>

<?php
global $wpdb;

$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;

$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;

$dsp_gateways_table = $wpdb->prefix . DSP_GATEWAYS_TABLE;

include_once('dsp_accounting_report_function.php');



if (isset($_POST['submit'])) {

    $type = isset($_REQUEST['radio_payment']) ? $_REQUEST['radio_payment'] : '';

    switch ($type) {

        case 'total_amount':

            $total_payment = getTotalPayment();

            $total = 'checked="checked"';

            break;



        case 'last_days':
            $seldays = 'checked="checked"';

            $days = isset($_REQUEST['txt_during_last_days']) ? $_REQUEST['txt_during_last_days'] : '';

            $date = date('Y-m-d');

            if ($days == '0') {

                $total_payment = getOnDatePayment($date);
            } else {

                $total_payment = getByLastDaysPayment($days, $date);
            }

            break;



        case 'on_date':

            $selDate = 'checked="checked"';

            $onDate = isset($_REQUEST['txt_on_date']) ? $_REQUEST['txt_on_date'] : '';

            $total_payment = getOnDatePayment($onDate);

            break;



        case 'between_days':

            $selBetween = 'checked="checked"';

            $fromDate = isset($_REQUEST['txt_from_date']) ? $_REQUEST['txt_from_date'] : '';

            $toDate = isset($_REQUEST['txt_to_date']) ? $_REQUEST['txt_to_date'] : '';

            $total_payment = getPaymentBetweenDates($fromDate, $toDate);

            break;



        default:

            $total_payment = getTotalPayment();

            $total = 'checked="checked"';

            break;
    }
} else {

    $total_payment = getTotalPayment();

    $total = 'checked="checked"';
}
?>

<div class="admin_setting_div">

    <div id="general" class="postbox">

        <h3 class="hndle"><span><?php echo language_code('DSP_ACCOUNTING_REPORT_SETTING') ?></span></h3>

        <div style="margin:20px;">

            <form action="" method="post" onsubmit="return checkValidPost();" >





                <table  style="width:60% "   border="0" cellspacing="0" cellpadding="00" >



                    <tr>

                        <td width="20%" colspan="2" >

                            <input type="radio" <?php echo $total ?>  name="radio_payment" value="total_amount" />&nbsp;<?php echo language_code('DSP_TOTAL_AMOUNT_REPORT_SETTING') ?>

                        </td>



                    </tr>

                    <tr>

                        <td width="25%" ><input type="radio" <?php echo $seldays ?> name="radio_payment" value="last_days"  />&nbsp;

                            <?php echo language_code('DSP_DURING_LAST_REPORT_SETTING') ?></td>

                        <td><input type="text" id="txt_during_last_days" name="txt_during_last_days" value="<?php if (isset($days)) echo $days; ?>" />

                            <?php echo language_code('DSP_TYPE_0_FOR_TODAY_TRANSACTION_REPORT_SETTING') ?></td>

                    </tr>

                    <tr>

                        <td width="25%" ><input type="radio" <?php echo $selDate ?> name="radio_payment" value="on_date" />&nbsp;<?php echo language_code('DSP_ON_THE_DATE_REPORT_SETTING') ?></td>

                        <td><div class="example-container">

                                <input type="text" id="datepicker1" name="txt_on_date" value="<?php if (isset($onDate)) echo $onDate; ?>" autocomplete="off"/>

                                <script type="text/javascript">
                                    var date = jQuery.noConflict();
                                    date(document).ready(function() {
                                        date('#datepicker1').datepicker({
                                            showSecond: true,
                                            formattedDate: 'y-m-d'

                                        });
                                        date('#from').datepicker({
                                            showSecond: true,
                                            formattedDate: 'y-m-d'

                                        });
                                        date('#to').datepicker({
                                            showSecond: true,
                                            formattedDate: 'y-m-d'

                                        });

                                    });
                                </script>

                            </div>

                        </td>

                    </tr>

                    <tr>

                        <td width="25%" ><input type="radio" <?php echo $selBetween ?> name="radio_payment" value="between_days" />&nbsp;

                            <?php echo language_code('DSP_BETWEEN_DATES_REPORT_SETTING') ?></td>

                        <td><div class="example-container">

                                <input id="from"  type="text" name="txt_from_date" value="<?php if (isset($fromDate)) echo $fromDate; ?>"/>

                                <script type="text/javascript">



                                </script>

                            </div></td>

                    </tr>

                    <tr>

                        <td width="25%" >&nbsp;</td>

                        <td><div class="example-container">

                                <input id="to" type="text"   name="txt_to_date" value="<?php if (isset($toDate)) echo $toDate; ?>"/>

                                <script type="text/javascript">


                                </script>

                            </div></td>

                    </tr>





                    <tr><td>&nbsp;</td>

                        <td ><p class="submit"><input type="submit" class="button-primary" name="submit" value="<?php echo language_code('DSP_SUBMIT_REPORT_SETTING') ?>"/></p></td>  

                    </tr>

                    <tr>

                        <td colspan="2" align="center">

                            <table width="90%" border="0" cellspacing="0" cellpadding="0">

                                <tr style="height:20px;">

                                    <td>Payment ID:</td>

                                    <td>Gateway:</td>

                                    <td>Member:</td>

                                    <td>Membership Type:</td>

                                    <td>Amount:</td>

                                    <?php
                                    $type = isset($_REQUEST['radio_payment']) ? $_REQUEST['radio_payment'] : '';

                                    if ($type == 'last_days') {



                                        if ($days == '0') {

                                            $dsp_payments_username = $wpdb->get_results("SELECT * FROM $dsp_payments_table WHERE  payment_status = 1 AND payment_date = '$date'");
                                        } else {

                                            $dsp_payments_username = $wpdb->get_results("SELECT * FROM $dsp_payments_table WHERE  payment_status = 1 AND payment_date >= DATE_SUB('$date',INTERVAL $days DAY) AND payment_date <='$date'");
                                        }

                                        foreach ($dsp_payments_username as $username) {

                                            $user_id = $username->pay_user_id;

                                            $pay_plan_id = $username->pay_plan_id;



                                            $search_username = $wpdb->get_row("SELECT user_login FROM $dsp_user_table Where ID='$user_id'");

                                            $gateway_name = $wpdb->get_row("SELECT * FROM $dsp_gateways_table Where gateway_id='$pay_plan_id'");
                                            ?>

                                        <tr  style="height:20px;">

                                            <td><?php echo $username->payment_id; ?></td>

                                            <td><?php echo ucfirst($gateway_name->gateway_name); ?></td>

                                            <td><?php echo ucfirst($search_username->user_login); ?></td>

                                            <td><?php echo ucfirst($username->pay_plan_name); ?></td>

                                            <td><?php echo language_code('SITE_CURRENCY_SYMBOL') . ' ' . $username->pay_plan_amount; ?></td>  



                                        <tr>

                                            <?php
                                        }
                                    } else if ($type == 'on_date') {



                                        $dsp_payments_username = $wpdb->get_results("SELECT * FROM $dsp_payments_table WHERE  payment_status = 1 AND payment_date = '$onDate'");



                                        foreach ($dsp_payments_username as $username) {

                                            $user_id = $username->pay_user_id;

                                            $pay_plan_id = $username->pay_plan_id;



                                            $search_username = $wpdb->get_row("SELECT user_login FROM $dsp_user_table Where ID='$user_id'");

                                            $gateway_name = $wpdb->get_row("SELECT gateway_name  FROM $dsp_gateways_table Where gateway_id='$pay_plan_id'");
                                            ?>

                                        <tr  style="height:20px;">

                                            <td><?php echo $username->payment_id; ?></td>

                                            <td><?php echo ucfirst($gateway_name->gateway_name); ?></td>

                                            <td><?php echo ucfirst($search_username->user_login); ?></td>

                                            <td><?php echo ucfirst($username->pay_plan_name); ?></td>

                                            <td><?php echo language_code('SITE_CURRENCY_SYMBOL') . ' ' . $username->pay_plan_amount; ?></td>  



                                        <tr>







                                            <?php
                                        }
                                    } else if ($type == 'between_days') {



                                        $dsp_payments_username = $wpdb->get_results("SELECT * FROM $dsp_payments_table WHERE  payment_status = 1 AND payment_date >= '$fromDate' AND payment_date <='$toDate'");



                                        foreach ($dsp_payments_username as $username) {

                                            $user_id = $username->pay_user_id;

                                            $pay_plan_id = $username->pay_plan_id;



                                            $search_username = $wpdb->get_row("SELECT user_login FROM $dsp_user_table Where ID='$user_id'");

                                            $gateway_name = $wpdb->get_row("SELECT gateway_name  FROM $dsp_gateways_table Where gateway_id='$pay_plan_id'");
                                            ?>

                                        <tr  style="height:20px;">

                                            <td><?php echo $username->payment_id; ?></td>

                                            <td><?php echo ucfirst($gateway_name->gateway_name); ?></td>

                                            <td><?php echo ucfirst($search_username->user_login); ?></td>

                                            <td><?php echo ucfirst($username->pay_plan_name); ?></td>

                                            <td><?php echo language_code('SITE_CURRENCY_SYMBOL') . ' ' . $username->pay_plan_amount; ?></td>  



                                        <tr>







                                            <?php
                                        }
                                    } else {

                                        $dsp_payments_username = $wpdb->get_results("SELECT * FROM $dsp_payments_table where payment_status = 1");

                                        foreach ($dsp_payments_username as $username) {

                                            $user_id = $username->pay_user_id;

                                            $pay_plan_id = $username->pay_plan_id;





                                            $chk_pay_exist = $wpdb->get_var("SELECT count(*) FROM $dsp_user_table Where ID='$user_id'");

                                            if ($chk_pay_exist != 0) {

                                                $search_username = $wpdb->get_row("SELECT user_login FROM $dsp_user_table Where ID='$user_id'");

                                                $gateway_name = $wpdb->get_var("SELECT gateway_name  FROM $dsp_gateways_table Where gateway_id='$pay_plan_id'");
                                                ?>

                                            <tr  style="height:20px;">

                                                <td><?php echo $username->payment_id; ?></td>

                                                <td><?php echo ucfirst($gateway_name); ?></td>

                                                <td><?php echo ucfirst($search_username->user_login); ?></td>

                                                <td><?php echo ucfirst($username->pay_plan_name); ?></td>

                                                <td><?php
                                                    if ($username->pay_plan_amount != "") {
                                                        echo language_code('SITE_CURRENCY_SYMBOL') . ' ' . $username->pay_plan_amount;
                                                    }
                                                    ?></td>  



                                            <tr>







                                                <?php
                                            }
                                        }
                                    }
                                    ?>



                                </tr>

                                <tr>

                                    <td colspan="5">

                                        <hr />

                                    </td>

                                </tr>

                                <tr>

                                    <td colspan="3">&nbsp;</td>

                                    <td align="right" style=" padding-right:10px; font-size:15px; font-weight:bold; color:#058C14;"> <?php echo language_code('DSP_TOTAL_REPORT_SETTING') ?>:</td>

                                    <td style="font-weight:bold; color:#058C14;font-size:15px;"><?php echo language_code('SITE_CURRENCY_SYMBOL') . '  ' . $total_payment ?> 

                                    </td>

                                </tr>

                            </table>

                        </td>

                    </tr>
                </table> 

            </form>

        </div>

    </div>

</div>