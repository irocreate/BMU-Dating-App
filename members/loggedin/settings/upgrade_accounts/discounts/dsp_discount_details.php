<?php
	$business = isset($_POST['business'])? $_POST['business'] : '';
	$currency_code = isset($_POST['currency_code'])? $_POST['currency_code'] : '';
	$item_name = isset($_POST['item_name'])? $_POST['item_name'] : get('item_name');
	$item_number = isset($_POST['item_number'])? $_POST['item_number'] : '';
	$return = isset($_POST['return'])? $_POST['return'] : '';
	$notify_url = isset($_POST['notify_url'])? $_POST['notify_url'] : '';
	$discount_code = isset($_POST['discount_code'])? $_POST['discount_code'] : '';
	$custom = isset($_POST['custom'])? $_POST['custom'] : '';
	$amount = isset($_POST['amount'])? $_POST['amount'] : dsp_get_membership_amount($id);
	$_SESSION['afterDiscountAmount'] = $amount;
	//Set Your Nonce
  	$ajax_nonce = wp_create_nonce( "discount-code-nonce" );
  	$siteUrl =  site_url() . '/members/setting/dsp_thank_you';
?>
<script>
   jQuery(document).ready(function(){
	  jQuery('tr.discount').hide();
	  jQuery('tr.amount').hide();
	  jQuery('span.dsp-check-coupan-code').click(function(){
	  		var coupanCode = jQuery('input.dsp-coupan-code').val();
	  		setCodes(coupanCode);
	  });
	  jQuery('#dsp_continue_discount').click(function(){
	      var discount_code = jQuery('#code').val();
           set_discount(discount_code);
      });

   });

   function autohideElements(time){
		   setTimeout(function() {
				jQuery('div.error').fadeOut("slow");
			}, time);
   }
   function setCodes(code) {
	   var item_name = "<?php echo $item_name; ?>";
	   var amount = "<?php echo $amount; ?>";
	   var user_id = "<?php echo get_current_user_id(); ?>";
	   var root_link = "<?php echo $root_link;?>";
	   var paymentType = "<?php echo $profile_pageurl; ?>";
	   var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
	   var ajaxnonce = "<?php echo $ajax_nonce; ?>";
	   if (jQuery.trim(code) != "") {
			jQuery('#code').val(code);
			jQuery.ajax({
					type: "POST",
					url: ajaxurl + "?action=dsp_coupan_code_calculation&_wpnonce="+ajaxnonce,
					dataType: 'json',
					data: {code:code,amount:amount,item_name:item_name,id:user_id},
					success: function(html){
							if(html['message'] != ''){
							  jQuery('div.error').fadeIn();
							  jQuery('#amount').val(amount);
							  jQuery('tr.discount').hide();
							  jQuery('tr.amount').hide();
							  var element = 'div.error';
							  jQuery('div.error span').text(html['message']);
							  autohideElements(5000);
							}else{
								if(html.hasOwnProperty('amount') && parseFloat(html['amount']) <= 0){ // amount is greater than discount
								  window.location.href = "<?php echo $siteUrl; ?>";
								}else{
								  //document.frm1.submit();
								  jQuery('div.ajaxCall').fadeIn();
								  jQuery('div.dsp-discount-info').fadeOut();
								  jQuery('#amount').val(html['amount']);
								  jQuery('tr.discount td:last-child').text(html['discount']);
								  jQuery('tr.discount').show();
								  jQuery('tr.amount td:last-child').text(html['amount']);
								  jQuery('tr.amount').show();
								}
							}
                    }
				});
			}
	  	}

   function set_discount(discount_code){

   }

</script>
<div class=" ">
  <div class="box-border">
	<div class="box-pedding">
	  <div class="setting-page">
			<ul class="dspdp-row dspdp-xs-text-center">
				<li class="dspdp-col-sm-5">
					<div class="purchase-credit-heading  dspdp-spacer"><strong><?php echo language_code('DSP_ENTER_YOUR_DISCOUNT_CODE'); ?></strong></div>
					<!-- <div class="purchase-credit-image dspdp-xs-form-group"><img class="dspdp-img-responsive dspdp-block-center" src='<?php echo WPDATE_URL . "/images/credit_purchase.png" ?>' /></div> -->
				</li>
				<li class="dspdp-col-sm-7">
					<div class="input-credits dspdp-spacer">
						<div class="dspdp-input-group dspdp-col-sm-12">
							<div class="dspdp-col-sm-8"><input class="dspdp-form-control dsp-coupan-code" name="discount_code" type="text" value=""  /></div>
							<span class="dspdp-btn dspdp-btn-primary dspdp-col-sm-4 dsp-check-coupan-code" ><?php echo language_code('DSP_SUBMIT_BUTTON'); ?></span>
						</div>
					</div>
					<div class="dsp-discount-info dspdp-col-sm-12">
							<span class="description"><?php _e(language_code('DSP_DISCOUNT_COUPON_CODE_TEXT')) ?></span>
					</div>
				</li>

			</ul>
	 	</div>


		  	<div class="ajaxCall" style="display:none">
			  <table width="100%" class="discount-codes-table">
				  <tr>
					  <td><?php echo language_code('DSP_TOTAL_AMOUNT'); ?></td>
					  <td><?php echo $amount; ?></td>
				  </tr>
				  <tr class="discount">
					  <td><?php echo language_code('DSP_DISCOUNT_PRICE'); ?></td>
					  <td></td>
				  </tr>
				  <tr class="amount">
					  <td><?php echo language_code('DSP_TOTAL_AMOUNT_AFTER_DISCOUNT'); ?></td>
					  <td ></td>
				  </tr>

			  </table>
			</div>
			<form name="frm1" action="<?php echo $root_link . "setting/".$profile_pageurl."/discountStatus/completed/id/".$id; ?>" method="post">
			  <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo $ajax_nonce; ?>" />
			  <input type="hidden" name="business" value="<?php echo $business ?>" />
			  <input type="hidden" name="id" value="<?php echo $id ?>" />
			  <input type="hidden" name="currency_code" value="<?php echo $currency_code ?>" />
			  <input type="hidden" name="item_name" value="<?php echo $item_name ?>" class="item_name"/>
			  <input type="hidden" name="item_number" value="<?php echo $item_number ?>" />
			  <input type="hidden" name="amount" id="amount" value="<?php echo $amount; ?>" />
			  <input type="hidden" name="code" id="code" value="<?php echo $discount_code;?>" />
			  <input type="Hidden" name="return" value="<?php echo $return; ?>">
			  <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>">
			  <input type="hidden" name="action" value="<?php echo 'process'; ?>">
              <input type="hidden" name="custom" id="custom" value="<?php echo $custom; ?>">
			  <input name="upgrade" title="Upgrade / PayPal" type="submit" id= "dsp_continue_discount" value="Continue<?php //echo language_code('DSP_CONTINUE') ?>"  class="dsp_span_pointer dspdp-btn dspdp-btn-default" style="text-decoration:none;" />
		  	</form>
	  	</div>
  	</div>
</div>