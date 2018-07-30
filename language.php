<?php
include_once('../../../wp-config.php');
global $wpdb;
$table = $wpdb->prefix . "dsp_language";
$filename = "language";
$wpdb->get_results("SHOW COLUMNS FROM " . $table . "");
$i = $wpdb->num_rows;
/*$result = mysql_query("SHOW COLUMNS FROM " . $table . "");
$i = mysql_num_rows($result);*/
$rows = $wpdb->get_row("SELECT * FROM " . $table . "",ARRAY_N);
//$values = mysql_query("SELECT * FROM " . $table . "");df
$csv_output = "";
//while ($rowr = mysql_fetch_row($values)) {
  foreach ($rows  as  $rowr) {
  	for ($j = 0; $j < $i; $j++) {
        $csv_output .= $rowr[$j] . ", ";
    }
    $csv_output .= "\n";
}
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . $filename . ".csv");
header("Content-disposition: filename=" . $filename . ".csv");
print $csv_output;

