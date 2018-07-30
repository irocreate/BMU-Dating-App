<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

@include_once('../../../wp-config.php');
include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");
extract($_REQUEST);
$delete_gift = $wpdb->query("delete from $dsp_user_virtual_gifts where gift_id='$id'");
echo 'done';
