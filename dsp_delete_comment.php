<?php

@include_once('../../../wp-config.php');
include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");
global $wpdb;
extract($_REQUEST);
$delete = $wpdb->delete($dsp_comments_table, array('comments_id' => $comment_id));
echo $delete;
