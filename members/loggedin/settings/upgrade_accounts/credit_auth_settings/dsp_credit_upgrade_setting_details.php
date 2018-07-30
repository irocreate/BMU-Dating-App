<div class="dsp_search_result_box_out">
        <div class="dsp_search_result_box_in">
            <?php
            extract($_REQUEST);
            if (get('reason') != '') {
                ?>
                <div align="center" style="color:#FF0000;"><b><?php echo urldecode(get('reason')); ?></b></div>
                <?php
            }
            if (get('mode') == 'cancel') {
                ?>
                <script type='text/javascript'> location.href = '<?php echo $root_link . "setting/upgrade_account/"; ?>'</script>
                <?php
            }
            ?>
            <div class="box-page">

                <form action="<?php echo $root_link . "setting/credit_auth_settings_detail/"; ?>" method="post">
                    <div class="card-box">
                        <div style="margin-bottom: 20px;margin-top: 12px;"><img src="<?php  echo WPDATE_URL .  "/images/discover.png"; ?>" alt="Discover" /></div>
                        <div style="margin-bottom: 20px;"><img src="<?php  echo WPDATE_URL .  "/images/visa.jpg"; ?>" alt="Visa"  /></div>
                        <div style="margin-bottom: 20px;"><img src="<?php  echo WPDATE_URL .  "/images/mastercard.jpg"; ?>" alt="Mastercard" /></div>
                        <div style="margin-top: 0px;"><img src="<?php  echo WPDATE_URL .  "/images/americanexpress.jpg"; ?>" alt="American Express" /></div>
                    </div>
                    <ul class="upgrade-details-page">
                        <li><span><?php echo language_code('DSP_GATEWAYS_CREDIT_CARD_NO'); ?>:</span> <input type="text" class="text" size="15" name="x_card_num" value=""> 
                        </li>
                        <li><span><?php echo language_code('DSP_GATEWAYS_EXPIRATION_DATE'); ?>:</span> <input type="text" class="text" size="4" name="x_exp_date" value=""></li>
                        <li><span><?php echo language_code('DSP_GATEWAYS_CCV'); ?>:</span> <input type="text" class="text" size="4" name="x_card_code" value=""></li>
                        <li><span><?php echo language_code('DSP_GATEWAYS_FIRST_NAME'); ?>:</span> <input type="text" class="text" size="15" name="x_first_name" value=""> </li>
                        <li><span><?php echo language_code('DSP_GATEWAYS_LAST_NAME'); ?>:</span> <input type="text" class="text" size="15" name="x_last_name" value=""></li>
                        <li><span><?php echo language_code('DSP_GATEWAYS_ADDRESS'); ?>:</span> <input type="text" class="text" size="15" name="x_address" value=""> </li>
                        <li><span><?php echo language_code('DSP_GATEWAYS_STATE'); ?>:</span> <input type="text" class="text" size="15" name="x_state" value=""></li>
                        <li><span><?php echo language_code('DSP_GATEWAYS_ZIP'); ?>:</span> <input type="text" class="text" size="15" name="x_zip" value=""></li>
                        <li><input name="submit" type="submit" value="<?php echo language_code('DSP_GATEWAYS_SUBMIT'); ?>" /><input name="cancel" type="button" value="<?php echo language_code('DSP_GATEWAYS_CANCEL'); ?>" onclick="CancelPayment()" style="margin-left:30px;" /></li>
                        <li>
                            <input type="hidden" name="x_amount" id="credit_amount" value="<?php echo $credit_amount; ?>" /> 
                            <input type="hidden" id="no_of_credit_to_purchase" name="no_of_credit_to_purchase" value="<?php echo $no_of_credit_to_purchase; ?>" />
                        </li>
                    </ul>
                    <div style="font-size:12px; float:left; width:100%;"><?php echo language_code('DSP_GATEWAYS_NOTE') ?></div>
                </form>
            </div>
        </div>
</div>

