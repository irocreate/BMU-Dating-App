<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author -  www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_credits_table = $wpdb->prefix . DSP_CREDITS_TABLE;
extract($_REQUEST);
//var_dump($_REQUEST);die;
if (isset($save_credit)) {
    $wpdb->query("update $dsp_credits_table set price_per_credit='$price_per_credit',emails_per_credit='$emails_per_credit',gifts_per_credit='$gifts_per_credit'");
}
$credit_row = $wpdb->get_row("select * from $dsp_credits_table");
?>
<div id="general" class="postbox">
    <h3 class="hndle">
        <span>
            <?php echo language_code('DSP_CREDITS'); ?>
        </span>
    </h3>

    <div class="credit-box">

        <div class="credit-form">
            <form method="post">
                <div class="credit-row">

                    <div class="credit-form-heading">
                        <?php echo language_code('DSP_CREDIT_PER_PRICE'); ?>
                    </div>

                    <input type="text" name="price_per_credit" value="<?php echo $credit_row->price_per_credit; ?>"/>

                    <div class="credit-form-desc">
                        <?php echo language_code('DSP_CREDIT_PER_PRICE_TEXT'); ?>
                    </div>
                </div>
                <div class="credit-row">
                    <div class="credit-form-heading">
                        <?php echo language_code('DSP_EMAILS_PER_CREDIT'); ?>
                    </div>

                    <input type="text" name="emails_per_credit" value="<?php echo $credit_row->emails_per_credit; ?>"/>

                    <div class="credit-form-desc">
                        <?php echo language_code('DSP_CREDIT_PER_EMAILS_TEXT'); ?>
                    </div>
                </div>
                <div class="credit-row">
                    <div class="credit-form-heading">
                        <?php echo language_code('DSP_GIFTS_PER_CREDIT'); ?>
                    </div>

                    <input type="text" name="gifts_per_credit" value="<?php echo $credit_row->gifts_per_credit; ?>"/>

                    <div class="credit-form-desc">
                        <?php echo language_code('DSP_CREDIT_PER_GIFTS_TEXT'); ?>
                    </div>
                </div>

                <div class="credit-save">
                    <p>
                        <input name="save_credit" type="submit" value="<?php echo language_code('DSP_SAVE_CHANGES'); ?>"
                               class="button button-primary">
                    </p>
                </div>

            </form>
        </div>
        <!--  <div class="note-credit">
            <span class="credit-note-head">
                <?php echo language_code('DSP_NOTE'); ?>
            </span><div class="credit-note">
                <?php echo language_code('DSP_CREDIT_NOTE_A'); ?>
                <br />
                <?php echo language_code('DSP_CREDIT_NOTE_B'); ?>
                <br />
                <?php echo language_code('DSP_CREDIT_NOTE_C'); ?>
                </p>
            </div>
        </div> -->
    </div>