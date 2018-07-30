<?php
$country_id = $_GET['country_id'];
$state_id = $_GET['state_id'];
global $wpdb;
?>
<div id="display_state">
    <div style="float:left;">
        <div id="load_img_id" style="display:none; float:right;"> 
            <img src="<?php echo WPDATE_URL . '/images/loading.gif' ?> " border="0" width="20" height="20" alt="Loading"/>	</div>	
        <select name="cmbState" id="cmbState" <?php if ($user_id != "") { ?> onChange="Show_city(this.value)" <?php } else { ?> onChange="Show_city2(this.value)" <?php } ?> style="width:190px;">


            <?php
            $num = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_state_table where country_id='$country_id'");
            $states = $wpdb->get_results("SELECT * FROM $dsp_state_table where country_id='$country_id' Order by name");
            if ($num == 0) {
                ?>	
                <option value="<?php echo $country_id ?>,0" id="<?php echo $country_id ?>" ><?php echo language_code('DSP_NON_STATE'); ?></option>
            <?php } else {
                ?>	
                <option value="0" id="0" ><?php echo language_code('DSP_SELECT_STATE'); ?></option>
            <?php } ?>	
            <?php
            foreach ($states as $state) {
                ?>

                <option value="<?php echo $state->state_id; ?>,1" ><?php echo $state->name; ?></option>
            <?php } ?>

        </select>
    </div>

    <div style="float:left;">
        <div id="load_img_id2" style="display:none; float:right"> 
            <img src="<?php echo WPDATE_URL . '/images/loading.gif' ?>" border="0" width="20" height="20" alt="Loading" />	</div>					
        <select name="cmbCity" id="cmbCity" style="width:190px;">
            <option value="0"><?php echo language_code('DSP_SELECT_CITY'); ?></option>	
            <?php
            $findme = '0';
            $pos = strpos($state_id, $findme);
            if ($pos == false) {
                //state
                $Cities = $wpdb->get_results("SELECT * FROM $dsp_city_table where state_id='$state_id' Order by name");
            } else {
                //country			
                $Cities = $wpdb->get_results("SELECT * FROM wp_dsp_city where country_id = '$state_id' Order by name");
            }
            foreach ($Cities as $city) {
                ?>
                <option value="<?php echo $city->city_id; ?>" ><?php echo $city->name; ?></option>
            <?php } ?>
        </select>	
    </div>	

</div>