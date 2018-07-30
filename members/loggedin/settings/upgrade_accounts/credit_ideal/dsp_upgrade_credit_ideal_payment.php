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
extract($_REQUEST);
if (isset($_REQUEST['bank'])) {



    $credit_purchase_data = array('user_id' => $user_id,
        'status' => 0,
        'credit_price' => $credit_amount,
        'credit_purchased' => $no_of_credit_to_purchase,
        'purchase_date' => date('Y-m-d H:i:s'));

    $wpdb->insert($dsp_credits_purchase_history, $credit_purchase_data);
    $inserted_id = $wpdb->insert_id;
}
$DSP_GATEWAYS_TABLE = $wpdb->prefix . DSP_GATEWAYS_TABLE;
$apiDetailsQuery = "SELECT rtlo,bank_language,currency FROM $DSP_GATEWAYS_TABLE where gateway_id=5";
//	echo '<br>'.$apiDetailsQuery;
$apiDetailsRes = $wpdb->get_row($apiDetailsQuery);
// Stel eerst deze 5 parameters in.

$currency_code = $apiDetailsRes->currency;
$rtlo = $apiDetailsRes->rtlo;
$description = "Targetpay iDeal";
$amount = $credit_amount * 74.778;
$returnurl = WPDATE_URL .  '/preparse.php?token=setting,credit_iDEAL,inserted_id,' . $inserted_id . '&page=' . $page_id;
$returnurl = WPDATE_URL .  '/preparse.php?token=setting,credit_iDEAL,inserted_id,' . $inserted_id . '&page=' . $page_id;
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
// Voeg hier programmacode toe om de orderstatus bij te werken.
        ?>
        <script type='text/javascript'> location.href = '<?php echo $root_link . "setting/dsp_credit_thank_you/credit_purchase_id/" . get('inserted_id') . "/"; ?>'</script>
        <?php
        die("Status was Successful...<br>Thank you for your order");
    }
// Bij alle andere statussen producten niet leveren
// Voeg hier zelf programmacode toe om de status bij te werken
    else
        die($status);
}
// De reporturl wordt vanaf de Targetpay server aangeroepen
if (isset($_REQUEST['rtlo']) && isset($_REQUEST['trxid']) && isset($_REQUEST['status'])) {
    HandleReporturl($_REQUEST['rtlo'], $_REQUEST['trxid'], $_REQUEST['status']);
}
// Hier begint het proces met het selecteren van de bank
SelectBank();

// Paragraaf 2: Selecteer de bank
function SelectBank() {
    global $wpdb;
    extract($_REQUEST);
    $DSP_GATEWAYS_TABLE = $wpdb->prefix . DSP_GATEWAYS_TABLE;
    $url = "https://www.targetpay.com/ideal/getissuers.php";
    $apiDetailsQuery = "SELECT bank_language FROM $DSP_GATEWAYS_TABLE where gateway_id=5";
//	echo '<br>'.$apiDetailsQuery;
    $apiDetailsRes = $wpdb->get_row($apiDetailsQuery);
    $strResponse = httpGetRequest($url);
    echo "<html>";
    echo '<div class="dsp_search_result_box_out">
<div class="dsp_search_result_box_in">';
    echo "<form method=\"post\" name=\"idealform\">";
    ?>

    <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>" />
    <input type="hidden" name="credit_amount" class="credit_amount" value="<?php echo $credit_amount; ?>" /> 
    <input type="hidden" class="no_of_credit_to_purchase" name="no_of_credit_to_purchase" value="<?php echo $no_of_credit_to_purchase; ?>" />
    Choose your bank:
    <select name=bank onChange="document.idealform.submit();">
        <script src="https://www.targetpay.com/ideal/issuers-<?php echo $apiDetailsRes->bank_language; ?>.js"></script>
    </select>
    <?php
    echo "</form></div>
</div>";
    echo "</html>";
}

// Paragraaf 3. Start de transactie door een redirect url 
// naar de bank op te vragen
function StartTransaction($rtlo, $bank, $description, $amount, $returnurl, $reporturl) {
    $url = "https://www.targetpay.com/ideal/start?" .
        "rtlo=" . $rtlo .
        "&bank=" . $bank .
        "&description=" . urlencode($description) .
        "&amount=" . $amount .
        "&returnurl=" . urlencode($returnurl) .
        "&reporturl=" . urlencode($reporturl);
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
        die("OK");
    } else {
        die("IP address not correct... This call is not from Targetpay");
    }
}

function httpGetRequest($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $strResponse = curl_exec($ch);
    curl_close($ch);
    if ($strResponse === false)
        die("Could not fetch response " . $url);
    return $strResponse;
}
