<?php
global $wpdb;
$country_id = $_GET['country_id'];
$state_id = $_GET['state_id'];
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
?>
<div id="display_state">
    <select name="cmbState" id="cmbState" style="width:150px;" onChange="Show2(this.value)">
        <option value="0">Select State</option>
        <option value="<?php echo $country_id; ?>,0">Non State</option>
        <?php
        $states = $wpdb->get_results("SELECT * FROM $dsp_state_table where country_id='$country_id' Order by name");
        foreach ($states as $state) {
            ?>
            <option value="<?php echo $state->state_id; ?>,1"><?php echo $state->name; ?></option>
        <?php } ?>
    </select>
    <div  id="load_img_id" style="display:none; float:right"> 
        <img src="<<?php echo WPDATE_URL . '/images/loading.gif' ?>" border="0" width="20" height="20" alt="Loading"/>	</div>

    <select name="cmbCity" id="cmbCity" style="width:150px;">

        <option value="0">Select City</option>

        <?php
        $findme = '0';
        $pos = strpos($state_id, $findme);
        if ($pos == false) {
            //state
            $Cities = $wpdb->get_results("SELECT * FROM $dsp_city_table where state_id = '$state_id' Order by name");
        } else {
            //country

            $Cities = $wpdb->get_results("SELECT * FROM $dsp_city_table where country_id = '$state_id' Order by name");
        }
        foreach ($Cities as $city) {
            ?>
            <option value="<?php echo $city->city_id; ?>"><?php echo $city->name; ?></option>

                                    <!--  <option value="<?php echo state_id ?>"><?php echo $state_id ?></option>-->

        <?php } ?>
    </select>	

    <div id="load_img_id2" style="display:none; float:right"> 
        <img src="<<?php echo WPDATE_URL . '/images/loading.gif' ?>" border="0" width="20" height="20" alt="Loading"/>	
    </div>		
</div>