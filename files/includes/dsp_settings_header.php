<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$pageURL = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : ''; 
$settings_root_link = get_bloginfo('url') . "/wp-admin/admin.php?page=dsp-admin-sub-page1&pid=" . $pageURL;

wp_deregister_style('paging');
wp_register_style('paging', plugins_url('dsp_dating/css/pagination.css'));
wp_enqueue_style('paging');

$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

$check_pagination_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'pagination_color'");
$check_credit_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'credit'");
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
<div class="wrap"><h2><?php echo __(language_code('DSP_HEADER_SITE_ADMIN'), 'dsp_trans_domain') ?></h2></div>
<div id="navmenu" align="left">
    <ul>
        <li <?php if (($pageURL == "general_settings") || ($pageURL == "")) { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=general_settings" title="<?php echo language_code('DSP_SETTINGS_HEADER_GENERAL') ?>">
                <?php echo language_code('DSP_SETTINGS_HEADER_GENERAL') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "membership_settings") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=membership_settings" title="<?php echo language_code('DSP_SETTINGS_HEADER_MEMBERSHIPS') ?>">
                <?php echo language_code('DSP_SETTINGS_HEADER_MEMBERSHIPS') ?></a><span class="dsp_tab1_span">|</span></li>
        <?php if($check_credit_mode->setting_status == 'Y'): ?>
        <li <?php if ($pageURL == "credits") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=credits" title="<?php echo language_code('DSP_SETTINGS_HEADER_CREDITS') ?>">
                <?php echo language_code('DSP_SETTINGS_HEADER_CREDITS') ?></a><span class="dsp_tab1_span">|</span></li>

        <li <?php if ($pageURL == "credits_usage") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=credits_usage" title="<?php echo language_code('DSP_SETTINGS_HEADER_CREDITS_USAGE') ?>">
                <?php echo language_code('DSP_SETTINGS_HEADER_CREDITS_USAGE') ?></a><span class="dsp_tab1_span">|</span></li>
        <?php endif; ?>
        <li <?php if ($pageURL == "gateways_settings") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=gateways_settings" title="<?php echo language_code('DSP_SETTINGS_HEADER_GATEWAYS') ?>">
                <?php echo language_code('DSP_SETTINGS_HEADER_GATEWAYS') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "spam_settings") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=spam_settings" title="<?php echo language_code('DSP_SETTINGS_HEADER_SPAM') ?>">
                <?php echo language_code('DSP_SETTINGS_HEADER_SPAM') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "matches_settings") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=matches_settings" title="<?php echo language_code('DSP_SETTINGS_HEADER_MATCHES') ?>">
                <?php echo language_code('DSP_SETTINGS_HEADER_MATCHES') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "blacklist") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=blacklist" title="<?php echo language_code('DSP_TOOLS_HEADER_BLACKLISTS') ?>">
                <?php echo language_code('DSP_TOOLS_HEADER_BLACKLISTS') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "premium_member") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=premium_member" title="<?php echo language_code('DSP_SETTINGS_HEADER_PREMIUM_MEMBER') ?>">
                <?php echo language_code('DSP_SETTINGS_HEADER_PREMIUM_MEMBER') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "featured_member") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=featured_member" title="<?php echo language_code('DSP_SETTINGS_HEADER_FEATURED_MEMBER') ?>">
                <?php echo language_code('DSP_SETTINGS_HEADER_FEATURED_MEMBER') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "instant_messenger") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=instant_messenger" title="<?php echo language_code('DSP_SETTINGS_HEADER_USERPLANE') ?>">
                <?php echo language_code('DSP_SETTINGS_HEADER_USERPLANE') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "dsnews") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=dsnews" title="<?php echo language_code('DSP_SETTINGS_HEADER_DSNEWS') ?>">
                <?php echo language_code('DSP_SETTINGS_HEADER_DSNEWS') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "update_database") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=update_database" title="<?php echo language_code('DSP_SETTINGS_HEADER_UPDATE_DATABASE') ?>">
                <?php echo language_code('DSP_SETTINGS_HEADER_UPDATE_DATABASE') ?></a><span class="dsp_tab1_span">|</span>
        </li>
         <li <?php if ($pageURL == "license_activate") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=license_activate" title="<?php echo language_code('DSP_SETTINGS_HEADER_LICENSE_ACTIVATE') ?>">
                <?php echo language_code('DSP_SETTINGS_HEADER_LICENSE_ACTIVATE') ?></a>
        </li>
    </ul>
</div>
<?php
if ($pageURL == "membership_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_settings_memberships.php');
} else if ($pageURL == "gateways_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_gateways_settings.php');
} else if ($pageURL == "spam_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_spam_settings.php');
} else if ($pageURL == "matches_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_matches_settings.php');
} else if ($pageURL == "premium_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_premium_access_settings.php');
} else if ($pageURL == "blacklist") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_blacklist_settings.php');
} else if ($pageURL == "update_general_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_update_process/update_general_setings.php');
} else if ($pageURL == "update_spam_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_update_process/update_spam_settings.php');
} else if ($pageURL == "check_blacklist_ipaddress") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_update_process/update_check_blacklist_ipaddress.php');
} else if ($pageURL == "import_zipcodes") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_update_process/update_import_zipcodes.php');
} else if ($pageURL == "premium_member") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_premium_member_settings.php');
} else if ($pageURL == "featured_member") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_featured_member_settings.php');    
} else if ($pageURL == "instant_messenger") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_userplane_settings.php');
} else if ($pageURL == "dsnews") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_dsnews_settings.php');
} else if ($pageURL == "update_database") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_update_database_settings.php');
} else if ($pageURL == "credits") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_credits_settings.php');
} else if ($pageURL == "credits_usage") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_credits_usage_settings.php');
} else if ($pageURL == "upgrade") {
   include_once( WP_DSP_ABSPATH . 'upgrade.php');
} else if ($pageURL == "license_activate") {
   include_once( WP_DSP_ABSPATH . 'files/dsp_license_activate.php');
} else {
    include_once( WP_DSP_ABSPATH . 'files/dsp_general_settings.php');
}
?>