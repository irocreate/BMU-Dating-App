<?php

class Wpdating_Paypal_Public
{
    private $wpdating_paypal_webhook_log_file;
    private $dsp_gateways_table;
    private $post_string;
    private $table_name_paypal_transaction;
    private $user_id;

    var $ipn_data = array();

    public function __construct()
    {
        global $wpdb;

        $this->wpdating_paypal_webhook_log_file = WPDATING_PAYPAL_ABSPATH . 'Paypal_ipn_handle_debug.log';
        $this->dsp_gateways_table               = $wpdb->prefix . DSP_GATEWAYS_TABLE;
        $this->table_name_paypal_transaction    = $wpdb->prefix . 'dsp_paypal_transaction';

    }

    /**
     * API request - Trigger API requests.
     */
    public function handle_api_requests()
    {
        global $wp;
        if ( ! empty($_GET['wpdating-api'])) {
            $wp->query_vars['wpdating-api'] = $_GET['wpdating-api'];
        }

        if ( ! empty($wp->query_vars['wpdating-api'])) {
            // Clean the API request.
            $api_request = strtolower($wp->query_vars['wpdating-api']);
            do_action('dsp_api_' . $api_request);
        }
    }

    public function validate_ipn()
    {
        $post_string = '';
        foreach ($_POST as $field => $value) {
            $this->ipn_data["$field"] = $value;
            $post_string              .= $field . '=' . urlencode(stripslashes($value)) . '&';
        }
        $this->post_string = $post_string;
        $this->debug_log('Post string : ' . $this->post_string, true);

// STEP 1: read POST data
// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
// Instead, read raw POST data from the input stream.
        $raw_post_data  = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost         = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2) {
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }
// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

// Step 2: POST IPN data back to PayPal to validate
        $valid_url = $this->is_test_mode() ? 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr' : 'https://ipnpb.paypal.com/cgi-bin/webscr';
        $ch        = curl_init($valid_url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// In wamp-like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set
// the directory path of the certificate as shown below:
        curl_setopt($ch, CURLOPT_CAINFO, WPDATING_PAYPAL_ABSPATH . '/pem/cacert.pem');
        if ( ! ($res = curl_exec($ch))) {
            // error_log("Got " . curl_error($ch) . " when processing IPN data");
            $this->debug_log("Got " . curl_error($ch) . " when processing IPN data", false, true);
            curl_close($ch);

            return false;
        }
        curl_close($ch);

        // inspect IPN validation result and act accordingly
        if (strcmp($res, "VERIFIED") == 0) {
            $this->debug_log('Verification Passed', true);

            return true;

        } else if (strcmp($res, "INVALID") == 0) {

            $this->debug_log('Verification Failed', false, true);

            return false;
        }
    }

    public function validate_and_dispatch_product()
    {
        $payment_status = $this->ipn_data['payment_status'];
        if ($payment_status == "Denied") {
            $this->debug_log("You denied the transaction. Most likely a cancellation of an eCheque. Nothing to do here.",
                false, true);

            return false;
        }
        if ($payment_status == "Canceled_Reversal") {
            $this->debug_log("This is a dispute closed notification in your favour. The plugin will not do anyting.",
                false, true);

            return true;
        }
        if ($payment_status != "Completed" && $payment_status != "Processed" && $payment_status != "Refunded" && $payment_status != "Reversed") {
            $this->debug_log('Payment for this transaction is in a pending state. Funds for this transaction have not cleared yet. Product(s) will be delivered when the funds clear.',
                false, true);

            return false;
        }

        $custom          = $this->ipn_data['custom'];
        $delimiter       = "&";
        $customvariables = array();

        $namevaluecombos = explode($delimiter, $custom);
        foreach ($namevaluecombos as $keyval_unparsed) {
            $equalsignposition = strpos($keyval_unparsed, '=');
            if ($equalsignposition === false) {
                $customvariables[$keyval_unparsed] = '';
                continue;
            }
            $key                   = substr($keyval_unparsed, 0, $equalsignposition);
            $value                 = substr($keyval_unparsed, $equalsignposition + 1);
            $customvariables[$key] = $value;
        }

        //Check for refund payment
        $gross_total = $this->ipn_data['mc_gross'];
        if ($gross_total < 0) {
            // This is a refund or reversal so handle the refund
            $this->debug_log('This is a refund/reversal. Refund amount: ' . $gross_total, true, true);

            return true;
        }
        //Check for duplicate notification due to server setup issue
        if ($this->is_txn_already_processed($this->ipn_data)) {
            $this->debug_log('The transaction ID and the email address already exists in the database. So this seems to be a duplicate transaction notification.',
                true, true);

            return true;
        }


        $user_id       = $customvariables['user_id'];
        $discount_code = isset($customvariables['discount_code']) ? $customvariables['discount_code'] : '';

        $this->update_database($user_id, $discount_code);
        return;

    }

    public function is_txn_already_processed($payment_data)
    {
        global $wpdb;
        $txn_id       = $payment_data['txn_id'];
        $emailaddress = $payment_data['payer_email'];
        $resultset    = $wpdb->get_results("SELECT * FROM $this->table_name_paypal_transaction WHERE txn_id = '$txn_id' and email_address = '$emailaddress'",
            OBJECT);
        if ($resultset) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check for Paypal IPN Response
     */
    public function check_paypal_response()
    {
        if ($this->validate_ipn()) {
            $this->validate_and_dispatch_product();
        }
        return;
    }

    /**
     * Create debug log
     *
     * @param $message
     * @param $success
     * @param bool $end
     */
    public function debug_log($message, $success, $end = false)
    {
        $text = '[' . date('m/d/Y g:i:s A') . '] - ' . (($success) ? 'SUCCESS :' : 'FAILURE :') . $message . "\n";

        if ($end) {
            $text .= "\n------------------------------------------------------------------\n\n";
        }
        // Write to log
        $fp = fopen($this->wpdating_paypal_webhook_log_file, 'a');
        fwrite($fp, $text);
        fclose($fp);
    }

    public function is_test_mode()
    {
        global $wpdb;
        $gateway_row = $wpdb->get_row("SELECT * FROM $this->dsp_gateways_table WHERE `gateway_name` = 'paypal'");

        if ($gateway_row->test_mode == 1) {
            return true;
        }

        return false;
    }

    public function append_values_to_custom_field($name, $value)
    {
        $custom_field_val = $_SESSION['wpdating_paypal_custom_values'];
        $new_val          = $name . '=' . $value;
        if (empty($custom_field_val)) {
            $custom_field_val = $new_val;
        } else {
            $custom_field_val = $custom_field_val . '&' . $new_val;
        }
        $_SESSION['wpdating_paypal_custom_values'] = $custom_field_val;

        return $custom_field_val;
    }

    public function get_payment_custom_var($custom)
    {
        $delimiter       = "&";
        $customvariables = array();
        $namevaluecombos = explode($delimiter, $custom);
        foreach ($namevaluecombos as $keyval_unparsed) {
            $equalsignposition = strpos($keyval_unparsed, '=');
            if ($equalsignposition === false) {
                $customvariables[$keyval_unparsed] = '';
                continue;
            }
            $key                   = substr($keyval_unparsed, 0, $equalsignposition);
            $value                 = substr($keyval_unparsed, $equalsignposition + 1);
            $customvariables[$key] = $value;
        }

        return $customvariables;
    }

    public function get_custom_field_value()
    {
        $_SESSION['wpdating_paypal_custom_values'] = '';
    }

    public function update_database($user_id, $discount_code)
    {
        global $wpdb;
        $dsp_payments_table           = $wpdb->prefix . DSP_PAYMENTS_TABLE;
        $dsp_temp_payments_table      = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
        $dsp_email_templates_table    = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
        $dsp_user_table               = $wpdb->prefix . DSP_USERS_TABLE;
        $dsp_credits_purchase_history = $wpdb->prefix . DSP_CREDITS_PURCHASE_HISTORY_TABLE;
        $dsp_credits_usage_table      = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
        $dsp_credits_table            = $wpdb->prefix . DSP_CREDITS_TABLE;

        $update_payment_details = $wpdb->get_row("SELECT * FROM $dsp_temp_payments_table where user_id='$user_id'");
        if (count($update_payment_details) > 0) {
            $check_already_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
            if ($check_already_user_exists <= 0) {
                $wpdb->query("INSERT INTO $dsp_payments_table SET pay_user_id = '$update_payment_details->user_id',pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id',recurring_profile_status='1'");
            } else {
                $wpdb->query("UPDATE $dsp_payments_table SET pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id', recurring_profile_status='1'  WHERE pay_user_id = '$update_payment_details->user_id'");
            }

            if (isset($discount_code) && ! empty($discount_code)) {
                dsp_update_discount_coupan_used($discount_code);
                add_user_meta(get_current_user_id(), 'discount_code', $discount_code);
            }

            if (dsp_issetGivenEmailSetting($user_id, 'payment_successful')) {
                $email_template         = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='16'");
                $reciver_details        = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$user_id'");
                $reciver_name           = $reciver_details->display_name;
                $receiver_email_address = $reciver_details->user_email;
                $siteurl                = get_option('siteurl');
                $email_subject          = $email_template->subject;
                $email_message          = $email_template->email_body;
                $email_message          = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
                $email_message          = str_replace("<#DOMAIN_NAME#>", $siteurl, $email_message);
                $MemberEmailMessage     = $email_message;
                $to                     = $receiver_email_address;
                $subject                = $email_subject;
                $message                = $MemberEmailMessage;
                $admin_email            = get_option('admin_email');
                $from                   = $admin_email;
                $headers                = "From: $from";
                // wp_mail($to, $subject, $message, $headers);
                $wpdating_email = Wpdating_email_template::get_instance();
                $result         = $wpdating_email->send_mail($to, $subject, $email_message);
                if ($result) {
                    $this->debug_log('Notification email sent', true);
                }
            }
        } else {
            extract($_REQUEST);
            $credit_purchase_id = $wpdb->get_var("SELECT credit_purchase_id FROM `$dsp_credits_purchase_history` where user_id ='$user_id' and status ='0' ORDER BY  `credit_purchase_id` DESC  limit 1");
            $wpdb->update($dsp_credits_purchase_history, array('status' => 1),
                array('credit_purchase_id' => $credit_purchase_id));
            $credit            = $wpdb->get_var("select credit_purchased from $dsp_credits_purchase_history where credit_purchase_id='$credit_purchase_id'");
            $chk_credit_row    = $wpdb->get_var("select count(*) from $dsp_credits_usage_table where user_id='$user_id'");
            $credit_row        = $wpdb->get_row("select * from $dsp_credits_table");
            $emails_per_credit = $credit_row->emails_per_credit;
            $new_emails        = $credit * $emails_per_credit;
            if ($chk_credit_row > 0) {
                $credit_usage_row = $wpdb->get_row("select * from $dsp_credits_usage_table where user_id='$user_id'");
                $wpdb->update($dsp_credits_usage_table, array(
                    'no_of_credits' => $credit_usage_row->no_of_credits + $credit,
                    'no_of_emails'  => $credit_usage_row->no_of_emails + $new_emails,
                    'email_sent'    => 0
                ), array(
                    'user_id' => $user_id
                ));
            } else {
                $wpdb->insert($dsp_credits_usage_table, array(
                    'no_of_credits' => $credit,
                    'no_of_emails'  => $new_emails,
                    'user_id'       => $user_id
                ));
            }
            $wpdb->query("update $dsp_credits_table set credits_purchased=credits_purchased+$credit");
            if (dsp_issetGivenEmailSetting($user_id, 'credit_purchase')) {
                $email_template         = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='20'");
                $reciver_details        = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$user_id'");
                $reciver_name           = $reciver_details->display_name;
                $receiver_email_address = $reciver_details->user_email;
                $siteurl                = get_option('siteurl');
                $email_subject          = $email_template->subject;
                $email_message          = $email_template->email_body;
                $email_message          = str_replace("<#AMOUNT-OF-CREDITS#>", $credit, $email_message);
                $MemberEmailMessage     = $email_message;
                $to                     = $receiver_email_address;
                $subject                = $email_subject;
                $message                = $MemberEmailMessage;
                $admin_email            = get_option('admin_email');
                $from                   = $admin_email;
                $headers                = "From: $from";
                $wpdating_email         = Wpdating_email_template::get_instance();
                $result                 = $wpdating_email->send_mail($to, $subject, $message);
                // wp_mail($to, $subject, $message, $headers);
            }
        }

        $fields = array();

        $fields['first_name']             = $this->ipn_data['first_name'];
        $fields['last_name']              = $this->ipn_data['last_name'];
        $fields['email_address']          = $this->ipn_data['payer_email'];
        $fields['txn_id']                 = $this->ipn_data['txn_id'];
        $fields['purchased_product_name'] = $this->ipn_data['item_name'];
        $fields['sale_amount']            = $this->ipn_data['mc_gross'];
        $fields['date']                   = (date("Y-m-d"));

        $fields = array_filter($fields);//Remove any null values.
        $result = $wpdb->insert($this->table_name_paypal_transaction, $fields);
        if ($result) {
            $this->debug_log('Paypal Payments table updated', true, true);
        } else {
            $this->debug_log('Paypal Payments table not updated', false, true);
        }

        return true;

    }

}