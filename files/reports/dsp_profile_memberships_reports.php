<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
$values = $_GET["pid"];
if ($values == "Profiles") {
    global $wpdb;
    $title = "Profiles Breakdown";
    $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
    $count_total_males = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_user_profiles WHERE gender='M'");
    $count_total_females = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_user_profiles WHERE  gender='F'");
    $count_total_couples = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_user_profiles WHERE  gender='C'");
} else if ($values = "memberships") {
    global $wpdb;
    $title = "Membership Breakdown";
    $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
    $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
    $count_total_males = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_user_profiles profile, $dsp_payments_table payment WHERE profile.user_id=payment.pay_user_id and gender='M'");
    $count_total_females = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_user_profiles profile, $dsp_payments_table payment WHERE profile.user_id=payment.pay_user_id and gender='F'");
    $count_total_couples = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_user_profiles profile, $dsp_payments_table payment WHERE profile.user_id=payment.pay_user_id and gender='C'");
}
?>
<div id="chart_div" style="width: 950px;  height:500px;" data-male="<?php echo $count_total_males; ?>"
     data-female="<?php echo $count_total_females; ?>" data-couples="<?php echo $count_total_couples; ?>"
     data-title="<?php echo $title; ?>"></div>
</div>

