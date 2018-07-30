<?php
$country_id = $_GET['country_id'];
$state_id = $_GET['state_id'];
global $wpdb;
include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");
?>
<div id="display_state">
    <div style="float:left;">
        <div id="load_img_id" style="display:none; float:right;"> 
            <img src="<?php echo WPDATE_URL . '/images/loading.gif' ?>" border="0" width="20" height="20" alt="Loading"/>	</div>	
        <select name="cmbState" id="cmbState" onChange="Show_city_e(this.value)" style="width:190px;">
            <option value="0">Select State</option>
            <?php
            $states = $wpdb->get_results("SELECT * FROM $dsp_state_table where country_id='$country_id' Order by name");
            foreach ($states as $state) {
                ?>
                <option value="<?php echo $state->state_id; ?>"><?php echo $state->name; ?></option>
            <?php } ?>
        </select>
    </div>

    <div style="float:left;">
        <div id="load_img_id2" style="display:none; float:right"> 
            <img src="<?php echo WPDATE_URL . '/images/loading.gif' ?>" border="0" width="20" height="20" alt="Loading" />	</div>					
        <select name="cmbCity" id="cmbCity" style="width:190px;">
            <option value="0">Select City</option>
            <?php
            $Cities = $wpdb->get_results("SELECT * FROM $dsp_city_table where state_id='$state_id' Order by name");
            foreach ($Cities as $city) {
                ?>
                <option value="<?php echo $city->city_id; ?>" ><?php echo $city->name; ?></option>
            <?php } ?>
        </select>	
    </div>	

</div>