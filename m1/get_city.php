<?php
include("../../../../wp-config.php");


/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));



//-------------------------DISPLAY ERROR OFF CODE ENDS--------------------------------


global $wpdb;

$dsp_state_table = $wpdb->prefix . "dsp_state";
$dsp_country_table = $wpdb->prefix . "dsp_country";
$dsp_city_table = $wpdb->prefix . "dsp_city";

$country_name = $_REQUEST['country'];

$country_id = $wpdb->get_var("select country_id from $dsp_country_table where name like '%$country_name%'");
$state_name = $_REQUEST['state'];
if ($state_name != '0') {
    $state_id = $wpdb->get_var("select state_id from $dsp_state_table where name like '%$state_name%'");
    ?>
    <select name="cmbCity" id="cmbCity_id" >



        <option value="Select"><?php echo language_code('DSP_SELECT_CITY'); ?></option>

        <?php
        $check_cities = $wpdb->get_var("select count(*) from $dsp_city_table where state_id=$state_id and country_id=$country_id");
        if ($check_cities != 0) {
            $city_rows = $wpdb->get_results("select * from $dsp_city_table where state_id=$state_id and country_id=$country_id");
            foreach ($city_rows as $rows) {
                ?>
                <option value="<?php echo $rows->name; ?>"><?php echo $rows->name; ?></option>
                <?php
            }
        }
        ?>

    </select>
    <?php
} else {
    ?>
    <select name="cmbCity" id="cmbCity_id">



        <option value="Select"><?php echo language_code('DSP_SELECT_CITY'); ?></option>

        <?php
        $check_states = $wpdb->get_var("select count(*) from $dsp_state_table where country_id=$country_id");
        $check_cities = $wpdb->get_var("select count(*) from $dsp_city_table where country_id=$country_id");
        if ($check_states == 0) {
            if ($check_cities != 0) {
                $city_rows = $wpdb->get_results("select * from $dsp_city_table where  country_id=$country_id");
                foreach ($city_rows as $rows) {
                    ?>
                    <option value="<?php echo $rows->name; ?>"><?php echo $rows->name; ?></option>
                    <?php
                }
            }
        }
    }?>