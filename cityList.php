<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
?>
<select name="cmbCity" id="cmbCity_id1"  style="width:190px;">
    <?php
    global $wpdb;
    include_once("files/includes/table_names.php");
    include_once("../../../wp-config.php");
    $dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
    $dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
    $dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
    $stateId = $_REQUEST['st'];
    ?>	
    <option value="0"><?php echo language_code('DSP_SELECT_CITY') ?></option>
    <?php
    $cities = $wpdb->get_results("SELECT * FROM $dsp_city_table where state_id='" . $stateId . "'Order by name");
    foreach ($cities as $city) {
        ?>
        <option value="<?php echo $city->city_id; ?>"><?php echo $city->name; ?></option>
    <?php } ?>
</select>