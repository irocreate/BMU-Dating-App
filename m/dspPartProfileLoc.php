<?php
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;

$user_id = $_REQUEST['user_id'];

$member_id = isset($_REQUEST['member_id']) ? $_REQUEST['member_id'] : '';

$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_partner_profiles_table WHERE status_id=1 AND user_id = '$member_id'");

$userName = $wpdb->get_var("SELECT display_name FROM $DSP_USERS_TABLE WHERE id =$member_id ");
?>

<ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">
    <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

        <?php if ($exist_profile_details->country_id != 0) {
            ?>
            <div class="dsp_pro_full">
                <span><?php echo language_code('DSP_COUNTRY'); ?></span>
                <?php
                $country = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$exist_profile_details->country_id");
                echo $country->name;
                ?>

            </div>
        <?php } ?>

        <?php if ($exist_profile_details->state_id != 0) {
            ?>
            <div class="dsp_pro_full">
                <span><?php echo language_code('DSP_TEXT_STATE'); ?></span>
                <?php
                $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$exist_profile_details->state_id");
                echo $state_name->name;
                ?>

            </div>
        <?php } ?>
        <?php if ($exist_profile_details->city_id != 0) {
            ?>
            <div class="dsp_pro_full">
                <span><?php echo language_code('DSP_CITY'); ?></span>
                <?php
                $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$exist_profile_details->city_id");
                echo $city_name->name;
                ?>
            </div>
        <?php } ?>
        <?php if ($check_zipcode_mode->setting_status == 'Y') {
            ?>
            <div class="dsp_pro_full">
                <span><?php echo language_code('DSP_ZIP'); ?></span>
                <?php echo $exist_profile_details->zipcode ?>
            </div>
        <?php } ?>	
    </li>

</ul>