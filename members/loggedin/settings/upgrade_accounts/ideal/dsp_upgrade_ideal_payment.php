<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
//-------------------------------START UPGRADE ACCOUNT SETTINGS ---------------------------------- 
?>
<?php
global $wp_query;
global $wpdb;
$page_id = $wp_query->post->ID; //fetch post query string id
$membership_plan_id = get('id');
$amountAfterDiscount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '';
if (isset($_REQUEST['bank'])) {
    $exist_membership_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table where membership_id='$membership_plan_id'");
    $plan_days = $exist_membership_plan->no_of_days;
    $membership_plan_amount = (isset($amountAfterDiscount) && !empty($amountAfterDiscount)) ? $amountAfterDiscount : $exist_membership_plan->price;
    $payment_date = date("Y-m-d");
    $wpdb->query("DELETE FROM $dsp_temp_payments_table WHERE user_id = '$user_id'");
    $wpdb->query("INSERT INTO $dsp_temp_payments_table SET user_id = '$user_id',plan_id = '$membership_plan_id',plan_amount ='$membership_plan_amount',plan_days='$plan_days',plan_name='$membership_plan',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL $plan_days DAY),payment_status=0");
}
$DSP_GATEWAYS_TABLE = $wpdb->prefix . DSP_GATEWAYS_TABLE;
$apiDetailsQuery = "SELECT rtlo,bank_language,currency FROM $DSP_GATEWAYS_TABLE where gateway_id=5";
//	echo '<br>'.$apiDetailsQuery;
$apiDetailsRes = $wpdb->get_row($apiDetailsQuery);
// Stel eerst deze 5 parameters in.
$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
$membership_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table WHERE membership_id='$membership_plan_id'");
$currency_code = $apiDetailsRes->currency;
$rtlo = $apiDetailsRes->rtlo;
$description = "Targetpay iDeal";
//convert amount to cents
$amount = $membership_plan->price * 100;
$amount = (isset($amountAfterDiscount) && !empty($amountAfterDiscount)) ? $amountAfterDiscount * 100 : $amount;

$returnurl = WPDATE_URL .  '/preparse.php?token=setting,iDEAL&page=' . $page_id;
$reporturl = WPDATE_URL .  '/preparse.php?token=setting,iDEAL&page=' . $page_id;
//$reporturl=$root_link."?pid=6&pagetitle=dsp_iDEAL_thank_you";
// De bank is geselecteerd. Nu starten we de transactie.
if (isset($_REQUEST['bank'])) {
    
    $url = StartTransaction($rtlo, $_REQUEST['bank'], $description, $amount, $returnurl, $reporturl);
//header( "Location: ". $url );
    echo '<script>location.href="' . $url . '"</script>';
}
// De consument komt vanaf de bank terug op de returnurl. 
// Hier controleren we de transactiestatus
if (get('ec') && get('trxid')) {
// 000000 OK betekent succesvol. We kunnen het product leveren
    if (($status = CheckReturnurl($rtlo, get('trxid'))) == "000000 OK") {
        //if payment status is correct
        //now update all the status
        $pay_member_id = $user_id;
        //$pay_member_id=$_REQUEST['pay_member_id'];
        $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
        $dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
        $update_payment_details = $wpdb->get_row("SELECT * FROM $dsp_temp_payments_table where user_id='$pay_member_id'");
        $check_already_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$pay_member_id'");
        if ($check_already_user_exists > 0) {
            $wpdb->query("UPDATE $dsp_payments_table SET pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1 WHERE pay_user_id = '$update_payment_details->user_id'");
        } else {
            $wpdb->query("INSERT INTO $dsp_payments_table SET pay_user_id = '$update_payment_details->user_id',pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1");
        }
        $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='16'");
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
        // wp_mail($to, $subject, $message, $headers);
        $wpdating_email  = Wpdating_email_template::get_instance();
        $result = $wpdating_email->send_mail( $to, $subject, $message);
        ?>
        <script type='text/javascript'> location.href = '<?php echo $root_link . "setting/dsp_iDEAL_thank_you/"; ?>'</script>
        <?php
        $msg = language_code('DSP_IDEAL_SUCCESSFUL_STATUS');
        die($msg);
    }
// Bij alle andere statussen producten niet leveren
// Voeg hier zelf programmacode toe om de status bij te werken
    else
        die($status);
}
// De reporturl wordt vanaf de Targetpay server aangeroepen
if (isset($_POST['rtlo']) && isset($_POST['trxid']) && isset($_POST['status'])) {
    HandleReporturl($_POST['rtlo'], $_POST['trxid'], $_POST['status']);
}
// Hier begint het proces met het selecteren van de bank
SelectBank();

// Paragraaf 2: Selecteer de bank
function SelectBank() {
    global $wpdb;
    $DSP_GATEWAYS_TABLE = $wpdb->prefix . DSP_GATEWAYS_TABLE;
    $url = "https://www.targetpay.com/ideal/getissuers?ver=3&format=html";
    $apiDetailsQuery = "SELECT bank_language FROM $DSP_GATEWAYS_TABLE where gateway_id=5";
//	echo '<br>'.$apiDetailsQuery;
    $apiDetailsRes = $wpdb->get_row($apiDetailsQuery);
    $strResponse = httpGetRequest($url);
    
    echo "<html>";
    echo '<div class="dsp_search_result_box_out">
<div class="dsp_search_result_box_in">';
    echo "<form method=\"post\" name=\"idealform\">";
        echo language_code('DSP_CHOOSE_YOUR_BANK');
    ?>
    <select name="bank" onChange="document.idealform.submit();">
       <?php echo $strResponse; ?>
    </select>
    <?php
    echo "</form></div>
</div>";
    echo "</html>";
}

// Paragraaf 3. Start de transactie door een redirect url 
// naar de bank op te vragen
function StartTransaction($rtlo, $bank, $description, $amount, $returnurl, $reporturl) {
    /*$url = "http://www.targetpay.com/ideal/start?" .
        "rtlo=" . $rtlo .
        "&bank=" . $bank .
        "&description=" . urlencode($description) .
        "&amount=" . $amount .
        "&returnurl=" . urlencode($returnurl) .
        "&reporturl=" . urlencode($reporturl);
    $strResponse = httpGetRequest($url);*/
    $test=0; // Set to 1 for testing as described in paragraph 1.3
    $url= "https://www.targetpay.com/ideal/start?".
    "rtlo=".$rtlo.
    "&bank=".$bank.
    "&description=".urlencode($description).
    "&amount=".$amount.
    "&returnurl=".urlencode($returnurl).
    "&reporturl=".urlencode($reporturl).
    "&test=".$test.
    "&ver=3";
    $strResponse = httpGetRequest($url);
    $aResponse = explode('|', $strResponse);
# Bad response
    if (!isset($aResponse[1]))
        die('Error' . $aResponse[0]);
    $responsetype = explode(' ', $aResponse[0]);
    $trxid = $responsetype[1];
// Hier kunt u het transactie id aan uw order toevoegen.
    if ($responsetype[0] == "000000")
        return $aResponse[1];
    else
        die($aResponse[0]);
}

// Paragraaf 5. Vraag de status op vanuit de returnurl
function CheckReturnurl($rtlo, $trxid) {
    $once = 1;
    $test = 0; // Set to 1 for testing as described in paragraph 1.3 
    $url = "https://www.targetpay.com/ideal/check?" .
        "rtlo=" . $rtlo .
        "&trxid=" . $trxid .
        "&once=" . $once .
        "&test=" . $test;
    return httpGetRequest($url);
}

// reporturl handler
// Update uw orderstatus en lever het product indien $status="000000 OK"
function HandleReporturl($rtlo, $trxid, $status) {
    if (substr($_SERVER['REMOTE_ADDR'], 0, 10) == "89.184.168") {
// Update uw orderstatus hier
// ........
// De reporturl hoort OK terug te geven aan Targetpay.
        $msg = language_code('DSP_OK');
        die($msg);
    } else {
        $msg = language_code('DSP_IP_ADDRESS_INCORRECT');
        die($msg);
    }
}

function httpGetRequest($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $strResponse = curl_exec($ch);
    curl_close($ch);
    if ($strResponse === false){
        $msg = language_code('DSP_COULDNOT_FETCH_RESPONSE');
        die($msg . $url);
    }
    return $strResponse;
}
?>