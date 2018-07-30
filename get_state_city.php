<?php
define('ROOT', dirname(__FILE__));
include_once(ROOT . "/../../../wp-config.php");
include_once(ROOT . "/files/includes/functions.php");
global $wpdb;
$dsp_state_table = $wpdb->prefix . "dsp_state";
$dsp_country_table = $wpdb->prefix . "dsp_country";
$dsp_city_table = $wpdb->prefix . "dsp_city";
$country_name = $_REQUEST['country'];
$country_id = !is_numeric($country_name) ? $wpdb->get_var("select country_id from $dsp_country_table where name like '$country_name'") : $country_name;

?>
<!--onChange="Show_state(this.value);"-->
<select name="cmbState" id="cmbState_id" class="dsp-form-control dspdp-form-control">
    <option value=0><?php echo language_code('DSP_SELECT_STATE'); ?></option>
    <?php
    $check_states = $wpdb->get_var("select count(*) from $dsp_state_table where country_id=$country_id");
    if ($check_states != 0) {
        $state_rows = $wpdb->get_results("select * from $dsp_state_table where country_id=$country_id ORDER BY name");
        foreach ($state_rows as $rows) {
            ?>
            <option value="<?php echo $rows->state_id; ?>"><?php echo $rows->name; ?></option>
            <?php
        }
    }
    ?>
</select>


