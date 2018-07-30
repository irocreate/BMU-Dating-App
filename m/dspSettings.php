<?php
$user_id = $_REQUEST['user_id'];
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_SETTINGS'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>
</div>


<div class="ui-content" data-role="content">
    <div class="content-primary">	 

        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">

            <a  onclick="viewSetting(0, 'account_settings')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">

                    <?php echo language_code('DSP_SUBMENU_SETTINGS_ACCOUNT'); ?>
                </li>
            </a>
            <a onclick="viewSetting(0, 'match_alert')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <?php echo language_code('DSP_MATCH_ALERT_MODE'); ?>	
                </li>
            </a>
            <a onclick="viewSetting(0, 'blocked')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <?php echo language_code('DSP_MIDDLE_TAB_BLOCKED'); ?>
                </li>
            </a>
            <a onclick="viewSetting(0, 'notification')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <?php echo language_code('DSP_SUBMENU_SETTINGS_NOTIFICATION'); ?>
                </li>
            </a>

            <a onclick="viewSetting(0, 'privacy_settings')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <?php echo language_code('DSP_SUBMENU_SETTINGS_PRIVACY'); ?>
                </li>
            </a>

        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>	