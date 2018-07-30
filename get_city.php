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
$state_id = $_REQUEST['state'];
$state_name = $wpdb->get_var("select name from $dsp_state_table where state_id = '$state_id' and country_id=$country_id");

if ($state_name != '0') {
    ?>
    <select name="cmbCity" id="cmbCity_id" class="dspdp-form-control dsp-form-control">
        <option value="0"><?php echo language_code('DSP_SELECT_CITY'); ?></option>
        <?php
        $check_cities = $wpdb->get_var("select count(*) from $dsp_city_table where state_id=$state_id and country_id=$country_id");
        if ($check_cities != 0) {
            $city_rows = $wpdb->get_results("select * from $dsp_city_table where state_id=$state_id and country_id=$country_id ORDER BY name");
            foreach ($city_rows as $rows) {
                ?>
                <option value="<?php echo $rows->city_id; ?>"><?php echo $rows->name; ?></option>
                <?php
            }
        }
        ?>
    </select>
<?php } else {
    ?>
    <select name="cmbCity" id="cmbCity_id" class="dspdp-form-control dsp-form-control">
        <option value="0"><?php echo language_code('DSP_SELECT_CITY'); ?></option>
        <?php
        $check_states = $wpdb->get_var("select count(*) from $dsp_state_table where country_id=$country_id");
        $check_cities = $wpdb->get_var("select count(*) from $dsp_city_table where country_id=$country_id");
        if ($check_states == 0) {
            if ($check_cities != 0) {
                $city_rows = $wpdb->get_results("select * from $dsp_city_table where  country_id=$country_id ORDER BY name");
                foreach ($city_rows as $rows) {
                    ?>
                    <option value="<?php echo $rows->city_id; ?>"><?php echo $rows->name; ?></option>
                    <?php
                }
            }
        }
    }
