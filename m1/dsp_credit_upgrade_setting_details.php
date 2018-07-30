<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_back.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_UPGRADE'); ?></h1>
    <?php include_once("page_home.php");?>
</div>
<?php
$credit_amount = $_REQUEST['credit_amount'];
$no_of_credit_to_purchase = $_REQUEST['no_of_credit_to_purchase'];
$user_id = $_REQUEST['user_id'];
?>
<div class="ui-content" data-role="content">
    <div class="content-primary">	
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
            <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                <div class="box-page">
                    <form id="frm_auth_detail">
                        <div style="width: 100%;text-align: right;">
                            <span>*<?php echo language_code('DSP_FIELDS_ARE_MANDATORY'); ?></span>
                        </div>
                        <ul class="upgrade-details-page">
                            <li><span><font style="color: red">*</font>
                                    <?php echo language_code('DSP_GATEWAYS_CREDIT_CARD_NO'); ?>:</span> <input id="customer_credit_card_number" type="text" class="text" size="15" name="x_card_num" value=""> 
                            </li>
                            <li><span><font style="color: red">*</font>
                                    <?php echo language_code('DSP_GATEWAYS_EXPIRATION_DATE'); ?>:</span> <input id="cc_expiration_year" type="text" class="text" size="4" name="x_exp_date" value=""></li>
                            <li><span><font style="color: red">*</font><?php echo language_code('DSP_GATEWAYS_CCV'); ?>:</span> <input id="cc_cvv2_number" type="text" class="text" size="4" name="x_card_code" value=""></li>
                            <li><span><font style="color: red">*</font><?php echo language_code('DSP_GATEWAYS_FIRST_NAME'); ?>:</span> <input id="customer_first_name" type="text" class="text" size="15" name="x_first_name" value=""> </li>
                            <li><span><font style="color: red">*</font><?php echo language_code('DSP_GATEWAYS_LAST_NAME'); ?>:</span> <input id="customer_last_name" type="text" class="text" size="15" name="x_last_name" value=""></li>
                            <li><span><font style="color: red">*</font><?php echo language_code('DSP_GATEWAYS_ADDRESS'); ?>:</span> <input id="customer_address1" type="text" class="text" size="15" name="x_address" value=""> </li>
                            <li><span><font style="color: red">*</font><?php echo language_code('DSP_GATEWAYS_STATE'); ?>:</span> <input id="customer_state" type="text" class="text" size="15" name="x_state" value=""></li>
                            <li><span><font style="color: red">*</font><?php echo language_code('DSP_GATEWAYS_ZIP'); ?>:</span> <input id="customer_zip" type="text" class="text" size="15" name="x_zip" value=""></li>
                            <li>
                                <span><input onclick="callUpgrade('credit_auth_settings_detail', '<?php echo language_code('DSP_USER_NAME_SHOULD_NO_BE_EMPTY'); ?>')" name="submit" type="button" value="<?php echo language_code('DSP_GATEWAYS_SUBMIT'); ?>" /></span>
                                <input name="cancel" type="button" value="<?php echo language_code('DSP_GATEWAYS_CANCEL'); ?>" onclick="callUpgrade('upgrade_account', 0)"  style="margin-left:30px;" /></li>
                            <li>
                                <input type="hidden"  name="user_id" value="<?php echo $user_id; ?>" />
                                <input type="hidden"  name="pagetitle" value="credit_auth_settings_detail" />
                                <input type="hidden" name="x_amount" id="credit_amount" value="<?php echo $credit_amount; ?>" />
                                <input type="hidden" name="x_desc" id="x_desc" value="<?php echo language_code('DSP_PURCHASE_CREDITS'); ?>" />  
                                <input type="hidden" id="no_of_credit_to_purchase" name="no_of_credit_to_purchase" value="<?php echo $no_of_credit_to_purchase; ?>" />
                            </li>
                        </ul>
                        <div class="card-box">
                            <div style="width:23%;float:left;"><img src="<?php echo $imagepath . "plugins/dsp_dating/images/discover.png"; ?>" /></div>
                            <div style="width:23%;float:left;"><img src="<?php echo $imagepath . "plugins/dsp_dating/images/visa.jpg"; ?>" /></div>
                            <div style="width:23%;float:left;"><img src="<?php echo $imagepath . "plugins/dsp_dating/images/mastercard.jpg"; ?>" /></div>
                            <div style="width:23%;float:left;"><img src="<?php echo $imagepath . "plugins/dsp_dating/images/americanexpress.jpg"; ?>" /></div>
                        </div>
                        <div style="font-size:12px; float:left; width:100%;"><?php echo language_code('DSP_GATEWAYS_NOTE') ?></div>
                    </form>
                </div>
            </li>
        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>