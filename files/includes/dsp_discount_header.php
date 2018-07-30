<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

global $wpdb;
$pageURL = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';
$settings_root_link = get_bloginfo('url') . "/wp-admin/admin.php?page=dsp-admin-sub-page5&pid=" . $pageURL;

wp_deregister_style('paging');
wp_register_style('paging', WPDATE_URL . '/css/pagination.css' );
wp_enqueue_style('paging');

$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

$check_pagination_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'pagination_color'");

/////////pagination dynamic color from admin start/////////////////
?>
<style>
	#wpbody-content .paging-box-withbtn .wpse_pagination .current{
		background:#<?php echo $check_pagination_color->setting_value; ?>; 
	}
</style>
<?php
/////////pagination dynamic color from admin start/////////////////
?>
<div class="wrap"><h2><?php echo language_code('DSP_DISCOUNT_CODES'); ?></h2></div>
<div id="navmenu" align="left">
	<ul>
		<li>
			<a href="admin.php?page=dsp-admin-sub-page5&pid=dsp_discount_codes" title="<?php echo "Discount Setting"?>">
				<?php //echo language_code('DSP_DISCOUNT_CODES_OPTIONS'); //language_code('DSP_SETTINGS_HEADER_UPDATE_DATABASE') ?>
			</a>
		</li>
		
	</ul>
</div>
<?php
if ($pageURL == "dsp_discount_code") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_discount_codes.php');
} else {
	include_once( WP_DSP_ABSPATH . 'files/dsp_discount_codes.php');
}
