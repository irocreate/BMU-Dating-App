<?php
$dsp_userplane_table = $wpdb->prefix . DSP_USERPLANE_TABLE;
$check_userplane = $wpdb->get_var("SELECT count(*) FROM $dsp_userplane_table order by userplane_id");
$userplane = $wpdb->get_row("SELECT * FROM $dsp_userplane_table order by userplane_id");
if ($check_userplane != 0) {
    $SITE_ID = $userplane->userplane_site_id;
    $API_KEY = $userplane->userplane_api_key;
} else {
    $SITE_ID = "";
    $API_KEY = "";
}
