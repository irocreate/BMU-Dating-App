<!--
This sample code is designed to connect to Authorize.net using the AIM method.
For API documentation or additional sample code, please visit:
http://developer.authorize.net
-->
<?PHP
extract( $_REQUEST );
$credit_purchase_data = array(
	'user_id'          => $user_id,
	'status'           => 0,
	'credit_price'     => $x_amount,
	'credit_purchased' => $no_of_credit_to_purchase,
	'purchase_date'    => date( 'Y-m-d H:i:s' )
);

$wpdb->insert( $dsp_credits_purchase_history, $credit_purchase_data );
$inserted_id = $wpdb->insert_id;
// By default, this sample code is designed to post to our test server for
// developer accounts: https://test.authorize.net/gateway/transact.dll
// for real accounts (even in test mode), please make sure that you are
// posting to: https://secure.authorize.net/gateway/transact.dll

$gateway_row = $wpdb->get_row( "SELECT * FROM $dsp_gateways_table WHERE `gateway_name` = 'authorize'" );
if ( $gateway_row->test_mode == 1 ) {
	$post_url = "https://test.authorize.net/gateway/transact.dll";
} else {
	$post_url = "https://secure.authorize.net/gateway/transact.dll";
}

$post_values = array(
	// the API Login ID and Transaction Key must be replaced with valid values
	"x_login"          => "9bFSTZuT68r",
	"x_tran_key"       => "36cr3cb9BU9sAt6P",
	"x_version"        => "3.1",
	"x_delim_data"     => "TRUE",
	"x_delim_char"     => "|",
	"x_relay_response" => "FALSE",
	"x_type"           => "AUTH_CAPTURE",
	"x_method"         => "CC",
	"x_card_num"       => esc_sql( sanitizeData( trim( $_POST['x_card_num'] ), 'xss_clean' ) ), //
	"x_exp_date"       => esc_sql( sanitizeData( trim( $_POST['x_exp_date'] ), 'xss_clean' ) ), //
	"x_amount"         => $_POST['x_amount'], //
	"x_description"    => esc_sql( sanitizeData( trim( $_POST['x_desc'] ), 'xss_clean' ) ), //
	"x_first_name"     => esc_sql( sanitizeData( trim( $_POST['x_first_name'] ), 'xss_clean' ) ), //
	"x_last_name"      => esc_sql( sanitizeData( trim( $_POST['x_last_name'] ), 'xss_clean' ) ), //
	"x_address"        => esc_sql( sanitizeData( trim( $_POST['x_address'] ), 'xss_clean' ) ), //
	"x_state"          => esc_sql( sanitizeData( trim( $_POST['x_state'] ), 'xss_clean' ) ), //
	"x_zip"            => esc_sql( sanitizeData( trim( $_POST['x_zip'] ), 'xss_clean' ) )//
	// Additional fields can be added here as outlined in the AIM integration
	// guide at: http://developer.authorize.net
);
// This section takes the input fields and converts them to the proper format
// for an http post.  For example: "x_login=username&x_tran_key=a1B2c3D4"
$post_string = "";
foreach ( $post_values as $key => $value ) {
	$post_string .= "$key=" . urlencode( $value ) . "&";
}
$post_string = rtrim( $post_string, "& " );
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
$request = curl_init( $post_url ); // initiate curl object
curl_setopt( $request, CURLOPT_HEADER, 0 ); // set to 0 to eliminate header info from response
curl_setopt( $request, CURLOPT_RETURNTRANSFER, 1 ); // Returns response data instead of TRUE(1)
curl_setopt( $request, CURLOPT_POSTFIELDS, $post_string ); // use HTTP POST to send form data
curl_setopt( $request, CURLOPT_SSL_VERIFYPEER, false ); // uncomment this line if you get no gateway response.
$post_response = curl_exec( $request ); // execute curl post and store results in $post_response
// additional options may be required depending upon your server configuration
// you can find documentation on curl options at http://www.php.net/curl_setopt
curl_close( $request ); // close curl object
//print_r  (explode($post_values["x_delim_char"],$post_response));
// This line takes the response and breaks it into an array using the specified delimiting character
$response_array = explode( $post_values["x_delim_char"], $post_response );
$response_array[0];
$response_array[3];
$sendback = $_SERVER['HTTP_REFERER'];
if ( $response_array[0] == 1 ) {
	?>
	<script
		type='text/javascript'> location.href = '<?php echo $root_link . "setting/dsp_credit_thank_you/credit_purchase_id/" . $inserted_id . "/"; ?>'</script>
<?php } else { ?>
	<script
		type='text/javascript'> location.href = '<?php echo $root_link . "setting/credit_auth_settings/reason/" . $response_array[3] . "/"; ?>'</script>
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