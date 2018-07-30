<?php
/** DoDirectPayment NVP example; last modified 08MAY23.
 *
 *  Process a credit card payment. 
 */
error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 'On');
//$environment = 'sandbox';	// or 'beta-sandbox' or 'live'
$environment = 'live';

extract($_REQUEST);

$credit_purchase_data = array('user_id' => $user_id,
    'status' => 0,
    'credit_price' => $payment_amuont,
    'credit_purchased' => $no_of_credit_to_purchase,
    'purchase_date' => date('Y-m-d H:i:s'));

$wpdb->insert($dsp_credits_purchase_history, $credit_purchase_data);
$inserted_id = $wpdb->insert_id;

/**
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
function PPHttpPost($methodName_, $nvpStr_) {

    global $environment;
    global $wpdb;

    $DSP_GATEWAYS_TABLE = $wpdb->prefix . DSP_GATEWAYS_TABLE;

    $apiDetailsQuery = "SELECT pro_api_username,pro_api_password,pro_api_signature FROM $DSP_GATEWAYS_TABLE where gateway_id=3";
    //echo '<br>'.$apiDetailsQuery;
    $apiDetailsRes = $wpdb->get_row($apiDetailsQuery);

    $my_api_username = $apiDetailsRes->pro_api_username;

    $my_api_password = $apiDetailsRes->pro_api_password;

    $my_api_signature = $apiDetailsRes->pro_api_signature;

    //echo '<br>'.$my_api_username.' '.$my_api_password.' '.$my_api_signature;
    // Set up your API credentials, PayPal end point, and API version.
    $API_UserName = urlencode($my_api_username);
    $API_Password = urlencode($my_api_password);
    $API_Signature = urlencode($my_api_signature);

    $API_Endpoint = "https://api-3t.paypal.com/nvp"; // live
    //$API_Endpoint = "https://api-3t.sandbox.paypal.com/nvp"; // for sand box

    $version = urlencode('51.0');
    // Set the curl parameters.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    // Turn off the server and peer verification (TrustManager Concept).
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    // Set the API operation, version, and API signature in the request.
    $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
    // Set the request as a POST FIELD for curl.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
    // Get response from the server.
    $httpResponse = curl_exec($ch);
    if (!$httpResponse) {
        exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
    }
    // Extract the response details.
    $httpResponseAr = explode("&", $httpResponse);
    $httpParsedResponseAr = array();
    foreach ($httpResponseAr as $i => $value) {
        $tmpAr = explode("=", $value);
        if (sizeof($tmpAr) > 1) {
            $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
        }
    }
    if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
        exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
    }
    return $httpParsedResponseAr;
//	return SUCCESS;
}

// Set request-specific fields.
$paymentType = urlencode('Authorization');    // or 'Sale'
$firstName = urlencode(esc_sql(sanitizeData(trim($_POST['customer_first_name']), 'xss_clean')));
$lastName = urlencode(esc_sql(sanitizeData(trim($_POST['customer_last_name']), 'xss_clean')));
$creditCardType = urlencode($_POST['customer_credit_card_type']);
$creditCardNumber = urlencode(esc_sql(sanitizeData(trim($_POST['customer_credit_card_number']), 'xss_clean')));
$expDateMonth = esc_sql(sanitizeData(trim($_POST['cc_expiration_month']), 'xss_clean'));
// Month must be padded with leading zero
$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
$expDateYear = urlencode(esc_sql(sanitizeData(trim($_POST['cc_expiration_year']), 'xss_clean')));
$cvv2Number = urlencode(esc_sql(sanitizeData(trim($_POST['cc_cvv2_number']), 'xss_clean')));
$address1 = urlencode(esc_sql(sanitizeData(trim($_POST['customer_address1']), 'xss_clean')));
$address2 = urlencode(esc_sql(sanitizeData(trim($_POST['customer_address2']), 'xss_clean')));
$city = urlencode(esc_sql(sanitizeData(trim($_POST['customer_city']), 'xss_clean')));
$state = urlencode(esc_sql(sanitizeData(trim($_POST['customer_state']), 'xss_clean')));
$zip = urlencode(esc_sql(sanitizeData(trim($_POST['customer_zip']), 'xss_clean')));
$country = urlencode(getCountryCode($_POST['customer_country']));    // US or other valid country code
$amount = urlencode($_POST['payment_amuont']);
$currencyID = urlencode($_POST['currency_code']);       // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
//echo $paymentType.''.$firstName.' '.$creditCardType.' '.$address1.' '.$amount.'<br>';
// Add request-specific fields to the request string.
$nvpStr = "&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber" .
    "&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName" .
    "&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";

//	echo '<br>state=='.$nvpStr;
// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = PPHttpPost('DoDirectPayment', $nvpStr);
//if(1) {
if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
    ?>
    <script type='text/javascript'> location.href = '<?php echo $root_link . "setting/dsp_credit_thank_you/credit_purchase_id/" . $inserted_id . "/"; ?>'</script>

    <?php
} else {
    $msg = "Sorry! Your Transaction has failed.";
    //exit('Your Transaction has failed: ' . print_r($httpParsedResponseAr, true));
}

function getCountryCode($countryName) {
    $country_list = array(
        'US' => 'United States',
        'AF' => 'Afghanistan',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua And Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia And Herzegowina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros',
        'CG' => 'Congo',
        'CD' => 'Congo, The Democratic Republic Of The',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote D\'Ivoire',
        'HR' => 'Croatia (Local Name: Hrvatska)',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'TP' => 'East Timor',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FK' => 'Falkland Islands (Malvinas)',
        'FO' => 'Faroe Islands',
        'FJ' => 'Fiji',
        'FI' => 'Finland',
        'FR' => 'France',
        'FX' => 'France, Metropolitan',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard And Mc Donald Islands',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran (Islamic Republic Of)',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'Korea, Democratic People\'S Republic Of',
        'KR' => 'Korea, Republic Of',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyzstan',
        'LA' => 'Lao People\'S Democratic Republic',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macau',
        'MK' => 'Macedonia, Former Yugoslav Republic Of',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands, Republic of the',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia, Federated States Of',
        'MD' => 'Moldova, Republic Of',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'NL' => 'Netherlands',
        'AN' => 'Netherlands Antilles',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands, Commonwealth of the',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau, Republic of',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'KN' => 'Saint Kitts And Nevis',
        'LC' => 'Saint Lucia',
        'VC' => 'Saint Vincent And The Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome And Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia, South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SH' => 'St. Helena',
        'PM' => 'St. Pierre And Miquelon',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard And Jan Mayen Islands',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania, United Republic Of',
        'TH' => 'Thailand',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad And Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks And Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'UM' => 'United States Minor Outlying Islands',
        'UY' => 'Uruguay',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VA' => 'Vatican City, State of the',
        'VE' => 'Venezuela',
        'VN' => 'Viet Nam',
        'VG' => 'Virgin Islands (British)',
        'VI' => 'Virgin Islands (U.S.)',
        'WF' => 'Wallis And Futuna Islands',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'YU' => 'Yugoslavia',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );
    $countryCode = array_search($countryName, $country_list);
    if ($countryCode) {
        return $countryCode;
    } else {
        return 'US';
    }
}
?>
<div class="dsp_search_result_box_out">
    <div class="dsp_search_result_box_in">
        <?php if (isset($msg)) echo $msg; ?>
    </div>
</div>