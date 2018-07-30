
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_SEARCH'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>

</div>


<div class="ui-content" data-role="content">
    <div class="content-primary">	 

        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">

            <a  onclick="callSearch('basic_search')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">

                    <?php echo language_code('DSP_SUBMENU_SEARCH_SEARCH'); ?>
                </li>
            </a>
            <a onclick="callSearch('advance_search')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <?php echo language_code('DSP_SUBMENU_SEARCH_ADVANCED'); ?>	
                </li>
            </a>
            <?php if ($check_zipcode_mode->setting_status == 'Y') { ?>
                <a onclick="callSearch('zipcode_search')">
                    <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                        <?php echo language_code('DSP_ZIP_CODE'); ?>
                    </li>
                </a>
            <?php } ?>
            <a onclick="callSearch('save_searches')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <?php echo language_code('DSP_SUBMENU_SEARCH_SAVE'); ?>
                </li>
            </a>



        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
</div>	