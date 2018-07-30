<?php
$name  = isset($_POST['item_name']) ? $_POST['item_name'] : '';
$price = isset($_POST['amount']) ? $_POST['amount'] : '';

$membership_id = isset($_POST['membership_id']) ? $_POST['membership_id'] : '';
$user_id       = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$desc          = isset($_POST['desc']) ? $_POST['desc'] : '';
$image         = isset($_POST['image']) ? $_POST['image'] : '';
$image_url     = isset($_POST['image_url']) ? $_POST['image_url'] : '';
$name_1        = isset($_POST['name_1']) ? $_POST['name_1'] : '';
$uploadInfo    = isset($_POST['uploadInfo']) ? $_POST['uploadInfo'] : '';


$no_of_days                          = isset($_POST['no_of_days']) ? $_POST['no_of_days'] : '';
$membership_stripe_recurring_plan_id = isset($_POST['membership_stripe_recurring_plan_id']) ? $_POST['membership_stripe_recurring_plan_id'] : '';

?>


<div class=" ">
    <div class="box-border">
        <div class="box-pedding">
            <div class="setting-page">
                <ul class="dspdp-row dspdp-xs-text-center">
                    <li class="dspdp-col-sm-5">
                        <div class="purchase-credit-heading  dspdp-spacer">
                            <strong><?php echo language_code('DSP_ENTER_YOUR_DISCOUNT_CODE'); ?></strong></div>
                        <!-- <div class="purchase-credit-image dspdp-xs-form-group"><img class="dspdp-img-responsive dspdp-block-center" src='<?php echo WPDATE_URL . "/images/credit_purchase.png" ?>' /></div> -->
                    </li>
                    <li class="dspdp-col-sm-7">
                        <div class="input-credits dspdp-spacer">
                            <div class="dspdp-input-group dspdp-col-sm-12">
                                <div class="dspdp-col-sm-8"><input class="dspdp-form-control dsp-coupan-code"
                                                                   name="discount_code" type="text" value=""/></div>
                                <span class="dspdp-btn dspdp-btn-primary dspdp-col-sm-4 dsp-check-coupan-code"><?php echo language_code('DSP_SUBMIT_BUTTON'); ?></span>
                                <input type="hidden" name="membership_id_value" class="membership_id_value"
                                       value="<?php echo $membership_id; ?>">
                            </div>
                        </div>
                        <div class="dsp-discount-info dspdp-col-sm-12">
                            <span class="description"><?php _e(language_code('DSP_DISCOUNT_COUPON_CODE_TEXT')) ?></span>
                        </div>
                    </li>

                </ul>
            </div>

            <div>
                <table width="100%" class="stripe-discount-codes-table" style="display:none">
                    <tr class="printMessage">
                        <td style="font-size: 15px; color: red;"></td>
                    </tr>
                </table>
            </div>
            <div class="ajaxCall" style="display:none">
                <table width="100%">
                    <tr class="totalAmount">
                        <td><?php echo language_code('DSP_TOTAL_AMOUNT'); ?></td>
                        <td></td>
                    </tr>
                    <tr class="discount">
                        <td><?php echo language_code('DSP_DISCOUNT_PRICE'); ?></td>
                        <td></td>
                    </tr>
                    <tr class="amount">
                        <td><?php echo language_code('DSP_TOTAL_AMOUNT_AFTER_DISCOUNT'); ?></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
        <form action="<?php echo site_url() . "/members/setting/upgrade_account/"; ?>" method="post">
            <input type="hidden" name="item_name" id="item_name"
                   value="<?php echo $name; ?>"/>
            <input type="hidden" name="name_1" id="name_1"
                   value="<?php echo $name_1; ?>"/>
            <input type="hidden" name="redirected_after_discount" value=1>
            <input type="hidden" name="amount" id="amount"
                   value="<?php echo $price; ?>"/>
            <input type="hidden" name="membership_id" id="membership_id"
                   value="<?php echo $membership_id; ?>"/>
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"/>
            <input type="hidden" name="no_of_days" value="<?php echo $no_of_days; ?>"/>
            <input type="hidden" name="desc" value="<?php echo $desc; ?>"/>
            <input type="hidden" name="image" value="<?php echo $image; ?>"/>
            <input type="hidden" name="image_url" value="<?php echo $image_url; ?>"/>
            <input type="hidden" name="uploadInfo" value="<?php echo $uploadInfo; ?>"/>
            <input type="hidden" name="membership_stripe_recurring_plan_id"
                   value="<?php echo $membership_stripe_recurring_plan_id; ?>"/>
            <input type="hidden" name="stripe_coupon" class="stripe_coupon"
                   value=""/>

            <input type="submit" name="btn_Continue" value="Continue">
        </form>

    </div>
</div>