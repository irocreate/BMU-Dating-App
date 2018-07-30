<?php

global $wpdb;
ini_set("max_execution_time", "10000");
session_cache_limiter('private, must-revalidate');
//session_start();
$dsp_zipcode_table = $wpdb->prefix . DSP_ZIPCODES_TABLE;
$file = isset($_REQUEST['file']) ? $_REQUEST['file'] : '';

$wpdb->query("ALTER TABLE  `$dsp_zipcode_table` CHANGE  `zipcode`  `zipcode` VARCHAR( 100 ) NOT NULL");

$csvpath = WP_DSP_ABSPATH . 'files/dsp_update_process/' . $file . '.csv';
//fixing forward slash thing for windows platform 
$csvpath = str_replace("\\", "/", $csvpath);

$query = "LOAD DATA LOCAL INFILE '$csvpath'";
$query.="INTO TABLE  $dsp_zipcode_table ";
$query.="FIELDS TERMINATED BY ',' ";
$query.="ENCLOSED BY '\"' ";
$query.="LINES TERMINATED BY '\\n' ";
//echo $query;
$wpdb->query($query);
echo '<script> location.href="admin.php?page=dsp-admin-sub-page1&pid=general_settings"; </script>';
?>
