<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ***************************  ACTIVE DEACTIVE STATUS *********************************** //
global $wpdb;
$dsp_general_settings_table = $wpdb->prefix . "dsp_general_settings";
$filter_Action = isset($_REQUEST['filter_Action']) ? $_REQUEST['filter_Action'] : '';
$cmbspamfilter = isset($_REQUEST['cmbspamfilter']) ? $_REQUEST['cmbspamfilter'] : '';
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$page_name = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';
if (($mode == 'update') && $page_name == 'update_spam_settings') {
    if ($filter_Action == "update_filter") {
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$cmbspamfilter' WHERE setting_name = 'spam_filter'");
    } // if($filter_Action=="update_filter") 
}  // if(($mode=='update') && $page_name=='update_spam_settings')
?>
<script>location.href = "<?php
echo add_query_arg(array('pid' => 'spam_settings',
    'updated' => 'true'), $settings_root_link);
?>"</script>
<?php
// ***************************  ACTIVE DEACTIVE STATUS *********************************** // ?>