<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
?>

<select name="cmbState" id="cmbState_id1"  style="width:190px;" onchange="javascript : view_city(this.value);">
    <?php
    global $wpdb;
    include_once("files/includes/table_names.php");
    include_once("../../../wp-config.php");
    $dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
    $dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
    $dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
    $countryId = $_REQUEST['con'];
    ?>
    <option value="0"><?php echo language_code('DSP_SELECT_STATE'); ?></option>
    <?php
    $states = $wpdb->get_results("SELECT * FROM $dsp_state_table where country_id='" . $countryId . "'Order by name");
    foreach ($states as $state) {
        ?>
        <option value="<?php echo $state->state_id; ?>"><?php echo $state->name; ?></option>
    <?php } ?>
</select>