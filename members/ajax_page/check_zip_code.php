<?php

   @include_once('../../../../../wp-config.php');
   $dsp_zipcode_table = $wpdb->prefix . DSP_ZIPCODES_TABLE;

  $zipCode = isset($_POST['zipCode'])? $_POST['zipCode']: '';
  
  $findzipcodelatlng = $wpdb->get_row("SELECT state_code FROM $dsp_zipcode_table WHERE zipcode = '$zipCode'");
  $existZipCode['value'] = !empty($findzipcodelatlng) ? true : false;
  echo json_encode($existZipCode);