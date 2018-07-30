<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
//-------------------------------START UPGRADE ACCOUNT SETTINGS ---------------------------------- 
//echo 'asdasd';
//include_once('dsp_upgrade_paypal_advance.php');
//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
global $wpdb;

extract( $_REQUEST );
if ( isset( $upgrade_credit ) ) {

	$exist_gateway_address = $wpdb->get_row( "SELECT * FROM $dsp_gateways_table" );
	$business              = $exist_gateway_address->address;
	$currency_code         = $exist_gateway_address->currency;
	$credit_purchase_data  = array(
		'user_id'          => $user_id,
		'status'           => 0,
		'credit_price'     => $credit_amount,
		'credit_purchased' => $no_of_credit_to_purchase,
		'purchase_date'    => date( 'Y-m-d H:i:s' )
	);

	$wpdb->insert( $dsp_credits_purchase_history, $credit_purchase_data );
	$inserted_id = $wpdb->insert_id;

	?>
	<form name="frm1" action="<?php echo $root_link . "setting/dsp_paypal/"; ?>" method="post">
		<input type="hidden" name="business" value="<?php echo $business ?>"/>
		<input type="hidden" name="currency_code" value="<?php echo $currency_code ?>"/>
		<input type="hidden" name="item_name" value="Credits Purchase"/>
		<input type="hidden" name="item_number" value="<?php echo $user_id ?>"/>
		<input type="hidden" name="amount" value="<?php echo $credit_amount ?>"/>
		<input type="hidden" name="return"
		       value="<?php echo $root_link . "setting/credit_upgrade_account_details/credit_purchase_id/" . $inserted_id . "/"; ?>">
		<input type="hidden" name="notify_url"
		       value="<?php echo $root_link . "setting/credit_upgrade_account_details/credit_purchase_id/" . $inserted_id . "/"; ?>">
	</form>
	<script type="text/javascript">
		document.frm1.submit();
	</script>
	<?php
}
$credit_row          = $wpdb->get_row( "select * from $dsp_credits_table" );
$currency_code_table = $wpdb->get_row( "SELECT currency_symbol FROM $dsp_gateways_table" );
$currency_code       = $currency_code_table->currency_symbol;
?>
<script>
	function change_credit(val) {
		var per_credit = <?php echo $credit_row->price_per_credit; ?>;
		var new_credits = parseFloat((val * per_credit).toFixed(3));
		jQuery(".no_of_credit_to_purchase").each(function () {
			jQuery(this).val(new_credits);
		});
		if (jQuery.trim(val) != "") {
			jQuery(".credit_price_change").each(function () {
				jQuery(this).html('$' + val);
			});
			jQuery(".credit_amount").each(function () {
				jQuery(this).val(val);
			});

			var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' )?>';

			var data = {
				action: 'stripe_ajax',
				post_var: val
			};

			// the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
			jQuery.post(ajaxurl, data, function (response) {
				jQuery('#stripe-test').html(response);
				jQuery(".no_of_credit_to_purchase").each(function () {
					jQuery(this).val(new_credits);
				});
			});


			/**
			 * For CCBILL
			 */

			var data = {
				action: 'ccbill_ajax',
				post_var: val
			};

			// the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
			jQuery.post(ajaxurl, data, function (response) {
				jQuery('.ccbill-test').html(response);
				jQuery(".no_of_credit_to_purchase").each(function () {
					jQuery(this).val(new_credits);
				});
			});

			return false;
		}
		else {
			jQuery(".credit_price_change").each(function () {
				jQuery(this).html('$' + 0);
			});
			jQuery(".credit_amount").each(function () {
				jQuery(this).val(0);
			});

			var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' )?>';

			var data = {
				action: 'stripe_ajax',
				post_var: val
			};

			jQuery.post(ajaxurl, data, function (response) {
				jQuery('#stripe-test').html(response);
			});

			/**
			 * For CCBILL
			 */

			var data = {
				action: 'ccbill_ajax',
				post_var: val
			};

			// the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
			jQuery.post(ajaxurl, data, function (response) {
				jQuery('#ccbill-test').html(response);
			});
			return false;
		}
	}
	jQuery(document).ready(function (e) {
		jQuery("#no_of_credits").keydown(function (event) {
			if (event.shiftKey) {
				event.preventDefault();
			}

			if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9) {
			}
			else {
				if (event.keyCode < 95) {
					if (event.keyCode < 48 || event.keyCode > 57) {
						event.preventDefault();
					}
				}
				else {
					if (event.keyCode < 96 || event.keyCode > 105) {
						event.preventDefault();
					}
				}
			}
		});

	});
</script>

<div class="dspdp-col-sm-12 dsp-sm-4">
	<div class="box-border dsp_upgrade-container">
		<div class="box-pedding dsp-upgrade-container dsp-upgrade-container-custom">
			<div class="setting-page__disable">
				<ul class="dspdp-row dsp-row dspdp-xs-text-center">
					<li class="dspdp-col-sm-3 dsp-sm-12">
						<div
							class="purchase-credit-heading dspdp-text-center dspdp-spacer"><?php echo language_code( 'DSP_PURCHASE_CREDITS' ); ?></div>
						<div class="purchase-credit-image dspdp-xs-form-group"><img
								class="dspdp-img-responsive dspdp-block-center"
								src='<?php echo WPDATE_URL . "/images/credit_purchase.png" ?>' alt="credit purchase"/>
						</div>
					</li>
					<li class="dspdp-col-sm-5 dsp-sm-12">
						<div class="input-credits dspdp-spacer">
							<div class="dspdp-input-group dsp-input-group"><input
									class="dspdp-form-control dsp-form-control" id="no_of_credits" type="text" value=""
									on onkeyup="change_credit(this.value);"/>
								<span
									class="dspdp-input-group-addon dsp-input-group-addon"><?php echo $currency_code ?></span>
							</div>
						</div>
                    <span class="default-credit-value dspdp-h4 dspdp-block  dsp-h4 dsp-block">
                        <?php echo $credit_row->price_per_credit; ?>
                        <?php echo $currency_code ?>
                    </span>
						<input type="hidden" id="credits_per_price"
						       value="<?php echo $credit_row->price_per_credit; ?>"/>
					</li>
					<li class="dspdp-col-sm-4 dspdp-text-center dsp-sm-12 dsp-text-center">
						<?php
						$gateway_table = $wpdb->get_results( "SELECT * FROM $dsp_gateways_table" );
						foreach ( $gateway_table as $gateway ) {
							//echo '<br>name'.$gateway->gateway_name;
							if ( $gateway->gateway_name == 'paypal' && $gateway->status == 1 ) {
								?>
								<div>
									<form name="paymentfrm"
									      action="<?php echo $root_link . "setting/upgrade_account/mode/update/"; ?>"
									      method="post">

										<input type="hidden" name="credit_amount" class="credit_amount"
										       value="<?php echo $credit_row->price_per_credit; ?>"/>

										<input type="hidden" class="no_of_credit_to_purchase"
										       name="no_of_credit_to_purchase" value="1"/>

										<input name="upgrade_credit" title="Upgrade / PayPal" type="submit"
										       value="<?php echo language_code( 'DSP_UPGRADE_PAYPAL_BTN' ) ?>"
										       class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
										       style="text-decoration:none;"/>
										<br/>
										<span style="font-size:13px; font-weight:bold;"
										      class="credit_price_change"><?php echo $currency_code ?><?php echo $credit_row->price_per_credit; ?></span>
										<br/>
									</form>
								</div>

								<?php
							} else if ( $gateway->gateway_name == 'authorize' && $gateway->status == 1 ) {
								?>
								<div>
									<form name="paymentfrm"
									      action="<?php echo $root_link . "setting/credit_auth_settings/"; ?>"
									      method="post">
										<input type="hidden" name="credit_amount" class="credit_amount"
										       value="<?php echo $credit_row->price_per_credit; ?>"/>

										<input type="hidden" class="no_of_credit_to_purchase"
										       name="no_of_credit_to_purchase" value="1"/>
										<input name="upgrade" title="Upgrade / Credit Card" type="submit"
										       value="<?php echo language_code( 'DSP_UPGRADE_CREDITCARD_BTN' ) ?>"
										       class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
										       style="text-decoration:none; margin-top:5px;"/>
									</form>
                            <span style="font-size:13px; font-weight:bold;" class="credit_price_change">
                                <?php echo $currency_code ?><?php echo $credit_row->price_per_credit; ?>
                            </span>
								</div>

								<?php
							} else if ( $gateway->gateway_name == 'paypal pro' && $gateway->status == 1 ) {
								?>
								<div>
									<form name="paymentfrm"
									      action="<?php echo $root_link . "setting/credit_pro_settings/"; ?>"
									      method="post">
										<input name="upgrade" title="Upgrade / PayPal Pro" type="submit"
										       value="<?php echo language_code( 'DSP_UPGRADE_PAYPALPRO_BTN' ) ?>"
										       class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
										       style="text-decoration:none;"/>
										<input type="hidden" name="credit_amount" class="credit_amount"
										       value="<?php echo $credit_row->price_per_credit; ?>"/>

										<input type="hidden" class="no_of_credit_to_purchase"
										       name="no_of_credit_to_purchase" value="1"/>
									</form>
									<span style="font-size:13px; font-weight:bold;"
									      class="credit_price_change"><?php echo $currency_code ?><?php echo $credit_row->price_per_credit; ?></span>
								</div>
								<?php
							} else if ( $gateway->gateway_name == 'paypal advance' && $gateway->status == 1 ) {
								if ( $recurringPaymentSatatus ) {
									?>
									<div
										style="width: 100%;text-align: center;padding-bottom: 12px;font-weight: bold;font-size: 13px ">
										<?php echo language_code( 'DSP_U_ARE_ALREADY_A' ) . $name . ' ' . language_code( 'DSP_PLAN_MEMBER' ); ?>
									</div>
									<?php
								} else {
									?>
									<div>
										<form action="<?php echo $root_link . "setting/paypal_advance/"; ?>"
										      method="post">
											<input type="hidden" name="item_name" id="item_name"
											       value="Credit Purchase"/>

											<input type="hidden" name="credit_amount" class="credit_amount"
											       value="<?php echo $credit_row->price_per_credit; ?>"/>

											<input type="hidden" class="no_of_credit_to_purchase"
											       name="no_of_credit_to_purchase" value="1"/>
											<input type="hidden" name="payment_action" value="credit"/>
											<!--<input type="submit" value="<?php echo language_code( 'DSP_UPGRADE_PAYPALADV_BTN' ) ?>" name="btn_advance" />-->
											<input class="subscribe   dspdp-btn dspdp-btn-default" name="btn_advance"
											       type="submit"
											       value="<?php echo language_code( 'DSP_UPGRADE_PAYPALADV_BTN' ) ?>"/>
										</form>

                                    <span style="font-size:13px; font-weight:bold;" class="credit_price_change">
                                        <?php echo $currency_code ?><?php echo $credit_row->price_per_credit; ?>
                                    </span>
										<br/>
									</div>
									<?php
								}
							} else if ( $gateway->gateway_name == 'iDEAL' && $gateway->status == 1 ) {
								?>
								<div>
									<form action="<?php echo $root_link . "setting/credit_iDEAL/"; ?>" method="post">
										<input name="upgrade" title="Upgrade / iDEAL" type="submit"
										       value="<?php echo language_code( 'DSP_UPGRADE_IDEAL_BTN' ) ?>"
										       class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
										       style="text-decoration:none;"/>
										<input type="hidden" name="credit_amount" class="credit_amount"
										       value="<?php echo $credit_row->price_per_credit; ?>"/>

										<input type="hidden" class="no_of_credit_to_purchase"
										       name="no_of_credit_to_purchase" value="1"/>
									</form>
									<span style="font-size:13px; font-weight:bold;"
									      class="credit_price_change"><?php echo $currency_code ?><?php echo $credit_row->price_per_credit; ?></span>
								</div>
								<?php
							}else if ($gateway->gateway_name == 'bank_wire' && $gateway->status == 1) {
                                                ?>
                                                <div>

                                                    <input name="upgrade" title="Upgrade / Bank wire" type="button"
                                                           value="<?php echo language_code('DSP_UPGRADE_BANK_WIRE') ?>"
                                                           onclick="window.location = '<?php echo $root_link . "setting/BANK_WIRE/id/" . $membership_id . "/"; ?>'"
                                                           class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
                                                           style="text-decoration:none;"/>
                                                    <br/> <span style="font-size:13px; font-weight:bold;"
									      class="credit_price_change"><?php echo $currency_code ?><?php echo $credit_row->price_per_credit; ?></span>
                                                    <br/>
                                                </div>
                                                <?php
                                            } else if ($gateway->gateway_name == 'cheque_payment' && $gateway->status == 1) {
                                                ?>
                                                <div>
                                                    <input name="upgrade" title="Upgrade / Cheque payment" type="button"
                                                           value="<?php echo language_code('DSP_UPGRADE_CHEQUE_PAYMENT') ?>"
                                                           onclick="window.location = '<?php echo $root_link . "setting/CHEQUE_PAYMENT/id/" . $membership_id . "/"; ?>'"
                                                           class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
                                                           style="text-decoration:none;"/>
                                                    <br/> <span style="font-size:13px; font-weight:bold;"
									      class="credit_price_change"><?php echo $currency_code ?><?php echo $credit_row->price_per_credit; ?></span>
                                                    <br/>
                                                </div>
                                                <?php
                                            }
						} // end of for each loop

						do_action( 'dsp_payment_addons_credit', $credit_row->price_per_credit );

						?>

					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
