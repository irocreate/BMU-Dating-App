<?php
$user_id = $_REQUEST['user_id'];
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
     <?php include_once("page_menu.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_SETTINGS'); ?></h1>
     <?php include_once("page_home.php");?> 
</div>


<div class="ui-content" data-role="content">
    <div class="content-primary">	 

        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all menu-list">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                <a  onclick="viewSetting(0, 'account_settings')">
                <img src="images/icons/account.png" />
                    <?php echo language_code('DSP_SUBMENU_SETTINGS_ACCOUNT'); ?>
                     </a>
                </li>
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                     <a onclick="viewSetting(0, 'match_alert')">
                     <img src="images/icons/match.png" />
                    <?php echo language_code('DSP_MATCH_ALERT_MODE'); ?>	
                     </a>
                </li>
               <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                     <a onclick="viewSetting(0, 'blocked')">
                     <img src="images/icons/blocked.png" />
                    <?php echo language_code('DSP_MIDDLE_TAB_BLOCKED'); ?>
                    </a>
                </li>
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                     <a onclick="viewSetting(0, 'notification')">
                      <img src="images/icons/bell.png" />
                    <?php echo language_code('DSP_SUBMENU_SETTINGS_NOTIFICATION'); ?>
                    </a>
                </li>
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                   <a onclick="viewSetting(0, 'privacy_settings')">
                   <img src="images/icons/lock.png" />
                    <?php echo language_code('DSP_SUBMENU_SETTINGS_PRIVACY'); ?>
                    </a>
                </li>
            

        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>	
<?php include_once("dspLeftMenu.php"); ?>