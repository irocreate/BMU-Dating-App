<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_menu.php"); ?>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_UPGRADE'); ?></h1>
    <?php include_once("page_home.php"); ?>
</div>

<?php

global $wpdb;

$dsp_gateways_table = $wpdb->prefix . dsp_gateways;
$dsp_payments_table = $wpdb->prefix . dsp_payments;
$dsp_temp_payments_table = $wpdb->prefix . dsp_temp_payments;
$dsp_memberships_table = $wpdb->prefix . dsp_memberships;
$exist_membership_plan = $wpdb->get_results("SELECT * FROM {$dsp_memberships_table}");
//$getUserPaymentExpiryDate = $wpdb->get_row("SELECT expiration_date FROM $dsp_payments_table WHERE pay_user_id =$user_id");
$user_payment_expiry_date = $getUserPaymentExpiryDate->expiration_date;


foreach ($exist_membership_plan as $exist_memberships) {
    $currency_code_table = $wpdb->get_row("SELECT currency_symbol FROM $dsp_gateways_table");
    $currency_code = $currency_code_table->currency_symbol;
    $price = $exist_memberships->price;
    $no_of_days = $exist_memberships->no_of_days;
    $name = $exist_memberships->name;
    $membership_id = $exist_memberships->membership_id;
    $desc = $exist_memberships->description;
    $image = $exist_memberships->image;

    $getUserPaymentExpiryDate = $wpdb->get_var("SELECT expiration_date FROM $dsp_payments_table WHERE pay_plan_id ='$membership_id' AND pay_user_id= '$user_id'");
    $current_date = date("Y-m-d");
    if ($getUserPaymentExpiryDate == "") {
        $user_can_buy_item = true;
    } else {
        if (strtotime($current_date) > strtotime($getUserPaymentExpiryDate)) {
            $user_can_buy_item = true;
        } else {
            $user_can_buy_item = false;
        }
    }

    ?>

    <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
        <div class="dsp_mail_lf">
            <img src='<?php echo $imagepath ?>/uploads/dsp_media/dsp_images/<?php echo $image; ?>'
                 title="<?php echo $name ?>" width="100" height="100"/>
            <div style="font-size: 13px; font-weight: bold; text-align: center;">
                <?php echo $currency_code ?><?php echo $price ?>
            </div>
        </div>
        <div class="dsp_mail_rt">
            <div style="text-align: left; word-wrap: break-word;padding-top: 10px;float: left;width: 100%;">
                <?php echo $desc; ?><br/>
                <?php echo $no_of_days . 'days plan'; ?>
            </div>
        </div>
    </li>
    <div class="btn-blue-wrap" style="padding-bottom: 10px;">
        <input type="button" class="mam_btn btn-blue"
               onclick="upgradeMembership('<?php echo $user_id; ?>','<?php echo $exist_memberships->membership_id; ?>','<?php echo $exist_memberships->price ?>','<?php echo $exist_memberships->name; ?>','<?php echo $exist_memberships->no_of_days; ?>','<?php echo $user_can_buy_item; ?>')"
               value="Buy">
    </div>
<?php } ?>
<?php include_once("dspLeftMenu.php"); ?>

