<!--
This sample code is designed to connect to Authorize.net using the AIM method.
For API documentation or additional sample code, please visit:
http://developer.authorize.net
-->
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a onclick="callUpgrade('upgrade_account', 0)" ><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_UPGRADE'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>

</div>
<div class="ui-content" data-role="content">
    <div class="content-primary">	
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
            <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">


                <?php
                global $wpdb;

                $dsp_credits_purchase_history = $wpdb->prefix . DSP_CREDITS_PURCHASE_HISTORY_TABLE;
                $dsp_gateways_table = $wpdb->prefix . DSP_GATEWAYS_TABLE;

                include_once('../dsp_validation_functions.php');

                extract($_REQUEST);

                $credit_purchase_data = array('user_id' => $user_id,
                    'status' => 0,
                    'credit_price' => $x_amount,
                    'credit_purchased' => $no_of_credit_to_purchase,
                    'purchase_date' => date('Y-m-d H:i:s'));

                $wpdb->insert($dsp_credits_purchase_history, $credit_purchase_data);
                $credit_purchase_id = $wpdb->insert_id;

                $exist_gateway = $wpdb->get_row("SELECT * FROM $dsp_gateways_table where gateway_id=2");
                $adminLogin = $exist_gateway->login_id;
                $adminTransKey = $exist_gateway->transaction_id;



// By default, this sample code is designed to post to our test server for
// developer accounts: https://test.authorize.net/gateway/transact.dll
// for real accounts (even in test mode), please make sure that you are
// posting to: https://secure.authorize.net/gateway/transact.dll
//
//$post_url = "https://test.authorize.net/gateway/transact.dll"; // for sandbox
                $post_url = "https://secure.authorize.net/gateway/transact.dll"; // for live 
                $post_values = array(
                    // the API Login ID and Transaction Key must be replaced with valid values
                    "x_login" => $adminLogin,
                    "x_tran_key" => $adminTransKey,
                    "x_version" => "3.1",
                    "x_delim_data" => "TRUE",
                    "x_delim_char" => "|",
                    "x_relay_response" => "FALSE",
                    "x_type" => "AUTH_CAPTURE",
                    "x_method" => "CC",
                    "x_card_num" => $wpdb->escape(sanitizeData(trim($_REQUEST['x_card_num']), 'xss_clean')), //
                    "x_exp_date" => $wpdb->escape(sanitizeData(trim($_REQUEST['x_exp_date']), 'xss_clean')), //
                    "x_amount" => $_REQUEST['x_amount'], //
                    "x_description" => $wpdb->escape(sanitizeData(trim($_REQUEST['x_desc']), 'xss_clean')), //
                    "x_first_name" => $wpdb->escape(sanitizeData(trim($_REQUEST['x_first_name']), 'xss_clean')), //
                    "x_last_name" => $wpdb->escape(sanitizeData(trim($_REQUEST['x_last_name']), 'xss_clean')), //
                    "x_address" => $wpdb->escape(sanitizeData(trim($_REQUEST['x_address']), 'xss_clean')), //
                    "x_state" => $wpdb->escape(sanitizeData(trim($_REQUEST['x_state']), 'xss_clean')), //
                    "x_zip" => $wpdb->escape(sanitizeData(trim($_REQUEST['x_zip']), 'xss_clean'))//
                    // Additional fields can be added here as outlined in the AIM integration
                    // guide at: http://developer.authorize.net
                );
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
                $sendback = $_SERVER['HTTP_REFERER'];


                if ($response_array[0] == 1) {
                    include("dsp_paypal_function.php");
                    update_credit($credit_purchase_id, $user_id); // update credit tables
                    ?>
                    <div align="center" style="color:#FF0000;"><b><?php echo language_code('DSP_THANKYOU_FOR_UR_PAYMENT'); ?></b></div>


                    <?php
                } else {
                    ?>
                    <div align="center" style="color:#FF0000;"><b><?php echo $response_array[3]; ?></b></div>
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
            </li>
        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>