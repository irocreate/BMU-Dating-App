<!--
This sample code is designed to connect to Authorize.net using the AIM method.
For API documentation or additional sample code, please visit:
http://developer.authorize.net
-->


<?php
$membership_plan_id = isset($_REQUEST['x_membership_id']) ? $_REQUEST['x_membership_id'] : '';
$membership_plan = isset($_REQUEST['x_name']) ? $_REQUEST['x_name'] : '';
$membership_plan_amount = isset($_REQUEST['x_amount']) ? $_REQUEST['x_amount'] : '';
$plan_days = isset($_REQUEST['x_days']) ? $_REQUEST['x_days'] : '';
//echo $_REQUEST['x_desc'];
//echo $_REQUEST['membership_id'];
$payment_date = date("Y-m-d");


$dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;


$check_already_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_temp_payments_table where user_id='$user_id'");
if ($check_already_user_exists <= 0) {
    $wpdb->query("INSERT INTO $dsp_temp_payments_table SET user_id = '$user_id',plan_id = '$membership_plan_id',plan_amount ='$membership_plan_amount',plan_days='$plan_days',plan_name='$membership_plan',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL $plan_days DAY),payment_status=0");
}

// get authorize details of admin

$authDetailsQuery = "SELECT login_id,transaction_id FROM $dsp_gateways_table where gateway_id=2"; // authorize.net 
$authDetails = $wpdb->get_row($authDetailsQuery);

$adminLoginId = $authDetails->login_id;
$adminTransactionId = $authDetails->transaction_id;



// By default, this sample code is designed to post to our test server for
// developer accounts: https://test.authorize.net/gateway/transact.dll
// for real accounts (even in test mode), please make sure that you are
// posting to: https://secure.authorize.net/gateway/transact.dll
//$post_url = "https://test.authorize.net/gateway/transact.dll"; // for sand box

$post_url = "https://secure.authorize.net/gateway/transact.dll"; // for live

$post_values = array(
    // the API Login ID and Transaction Key must be replaced with valid values
    "x_login" => $adminLoginId,
    "x_tran_key" => $adminTransactionId,
    "x_version" => "3.1",
    "x_delim_data" => "TRUE",
    "x_delim_char" => "|",
    "x_relay_response" => "FALSE",
    "x_type" => "AUTH_CAPTURE",
    "x_method" => "CC",
    "x_card_num" => $_REQUEST['x_card_num'], //
    "x_exp_date" => $_REQUEST['x_exp_date'], //
    "x_amount" => $_REQUEST['x_amount'], //
    "x_description" => $_REQUEST['x_desc'], //
    "x_first_name" => $_REQUEST['x_first_name'], //
    "x_last_name" => $_REQUEST['x_last_name'], //
    "x_address" => $_REQUEST['x_address'], //
    "x_state" => $_REQUEST['x_state'], //
    "x_zip" => $_REQUEST['x_zip']//
    // Additional fields can be added here as outlined in the AIM integration
    // guide at: http://developer.authorize.net
);
//print_r($post_values);
// This section takes the input fields and converts them to the proper format
// for an http post.  For example: "x_login=username&x_tran_key=a1B2c3D4"
$post_string = "";
foreach ($post_values as $key => $value) {
    $post_string .= "$key=" . urlencode($value) . "&";
}
$post_string = rtrim($post_string, "& ");

// The following section provides an example of how to add line item details to
// the post string.  Because line items may consist of multiple values with the
// same key/name, they cannot be simply added into the above array.
//
// This section is commented out by default.
/*
  $line_items = array(
  "item1<|>golf balls<|><|>2<|>18.95<|>Y",
  "item2<|>golf bag<|>Wilson golf carry bag, red<|>1<|>39.99<|>Y",
  "item3<|>book<|>Golf for Dummies<|>1<|>21.99<|>Y");

  foreach( $line_items as $value )
  { $post_string .= "&x_line_item=" . urlencode( $value ); }
 */

// This sample code uses the CURL library for php to establish a connection,
// submit the post, and record the response.
// If you receive an error, you may want to ensure that you have the curl
// library enabled in your php configuration
$request = curl_init($post_url); // initiate curl object
curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
$post_response = curl_exec($request); // execute curl post and store results in $post_response
// additional options may be required depending upon your server configuration
// you can find documentation on curl options at http://www.php.net/curl_setopt
curl_close($request); // close curl object
//print_r  (explode($post_values["x_delim_char"],$post_response));
// This line takes the response and breaks it into an array using the specified delimiting character
$response_array = explode($post_values["x_delim_char"], $post_response);
$response_array[0];
$response_array[3];

if ($response_array[0] == 1) {
    include(WP_DSP_ABSPATH . "/m1/dsp_thank_you.php");
    ?>
                                    <!--<script type='text/javascript'> location.href='<?php
    echo add_query_arg(array(
        'pid' => '6', 'pagetitle' => 'dsp_thank_you'), $root_link);
    ?>'</script>---->

    <?php
} else {
    $reason = $response_array[3];
    //echo $reason;
    include(WP_DSP_ABSPATH . "/m1/dsp_upgrade_setting_details.php");
    ?>

                                    <!--<script type='text/javascript'> location.href='<?php
    echo add_query_arg(array(
        'pid' => '6', 'pagetitle' => 'auth_settings', 'id' => $membership_plan_id,
        'reason' => $response_array[3]), $root_link);
    ?>'</script>-->

    <?php
}

// The results are output to the screen in the form of an html numbered list.
/* echo "<OL>\n";
  foreach ($response_array as $value)
  {
  echo "<LI>" . $value . "&nbsp;</LI>\n";
  }
  echo "</OL>\n";
 */


// individual elements of the array could be accessed to read certain response
// fields.  For example, response_array[0] would return the Response Code,
// response_array[2] would return the Response Reason Code.
// for a list of response fields, please review the AIM Implementation Guide
?>