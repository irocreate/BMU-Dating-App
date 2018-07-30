<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$pageURL = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';
?>
    <div class="wrap"><h2><?php echo __('Dating Site Admin', 'dsp_trans_domain') ?></h2></div>
    <div id="navmenu" align="left">
        <ul>
            <li <?php if (($pageURL == "Quickstats") || ($pageURL == "")) { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                <a href="admin.php?page=dsp-admin-sub-page4&pid=Quickstats"
                   title="Quickstats"><?php echo language_code('DSP_ACCOUNTING_QUICKSTAT_SETTING') ?></a><span
                    class="dsp_tab1_span">|</span></li>
            <li <?php if ($pageURL == "Thisweek") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                <a href="admin.php?page=dsp-admin-sub-page4&pid=Thisweek"
                   title="Thisweek"><?php echo language_code('DSP_ACCOUNTING_THIS_WEEK_SETTING') ?></a><span
                    class="dsp_tab1_span">|</span></li>
            <li <?php if ($pageURL == "Profiles") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                <a href="admin.php?page=dsp-admin-sub-page4&pid=Profiles"
                   title="Profiles"><?php echo language_code('DSP_ACCOUNTING_PROFILES_SETTING') ?></a><span
                    class="dsp_tab1_span">|</span></li>
            <li <?php if ($pageURL == "Memberships") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                <a href="admin.php?page=dsp-admin-sub-page4&pid=Memberships"
                   title="Memberships"><?php echo language_code('DSP_ACCOUNTING_MEMBERSHIPS_SETTING') ?></a><span
                    class="dsp_tab1_span">|</span></li>
            <!--<li <?php if ($pageURL == "Demographics") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page4&pid=Demographics" title="Demographics"><?php echo language_code('DSP_ACCOUNTING_DEMOGRAPHICS_SETTING') ?></a><span class="dsp_tab1_span">|</span></li>
        -->
            <li <?php if ($pageURL == "Accounting") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                <a href="admin.php?page=dsp-admin-sub-page4&pid=Accounting"
                   title="Accounting"><?php echo language_code('DSP_ACCOUNTING_REPORT_SETTING') ?></a><span
                    class="dsp_tab1_span">|</span></li>
            <li <?php if ($pageURL == "Userlists") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                <a href="admin.php?page=dsp-admin-sub-page4&pid=Userlists"
                   title="Userlists"><?php echo language_code('DSP_USER_LISTS_BASED_ON_JOINED_DATE') ?></a><span
                    class="dsp_tab1_span">|</span></li>
            <li <?php if ($pageURL == "Userstats") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                <a href="admin.php?page=dsp-admin-sub-page4&pid=Userstats"
                   title="Userstats"><?php echo language_code('DSP_USERS_STATISTICS') ?></a><span class="dsp_tab1_span">|</span>
            </li>
            <li <?php if ($pageURL == "AllUserstats") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                <a href="admin.php?page=dsp-admin-sub-page4&pid=AllUserstats"
                   title="AllUserstats"><?php echo language_code('DSP_ALL_USERS_STATISTICS') ?></a><span
                    class="dsp_tab1_span">|</span></li>
            <li <?php if ($pageURL == "BankChequeUsers") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                <a href="admin.php?page=dsp-admin-sub-page4&pid=BankChequeUsers"
                   title="BankChequeUsers"><?php echo language_code('DSP_BANK_CHEQUE_USERS') ?></a></li>
            <li <?php if ($pageURL == "bank_cheque_users") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>

    </div>
<?php
switch ($pageURL) {
    case 'Quickstats':
        include_once(WP_DSP_ABSPATH . 'files/reports/dsp_quickstats_reports.php');
        break;
    case 'Thisweek':
        include_once(WP_DSP_ABSPATH . 'files/reports/dsp_thisweek_reports.php');
        break;
    case 'Profiles':
        ;
    case 'Memberships':
        include_once(WP_DSP_ABSPATH . 'files/reports/dsp_profile_memberships_reports.php');
        break;
    case 'Userlists':
        include_once(WP_DSP_ABSPATH . 'files/reports/dsp_userlists_reports.php');
        break;
    case 'Accounting':
        include_once(WP_DSP_ABSPATH . 'files/reports/dsp_payment_report.php');
        break;
    case 'Userstats':
        include_once(WP_DSP_ABSPATH . 'files/reports/dsp_user_statistics.php');
        break;
    case 'AllUserstats':
        include_once(WP_DSP_ABSPATH . 'files/reports/dsp_all_users_statistics.php');
        break;
    case 'BankChequeUsers':
        include_once(WP_DSP_ABSPATH . 'files/dsp_bank_cheque_users.php');
        break;
    default:
        include_once(WP_DSP_ABSPATH . 'files/reports/dsp_quickstats_reports.php');
        break;
}

?>