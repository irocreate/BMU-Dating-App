<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_EDIT_PROFILE'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>
    <?php
    if ($gender == 'C') {
        ?>
        <div data-role="navbar" class="ui-navbar ui-mini" role="navigation">
            <ul class="ui-grid-duo ui-grid-a">
                <li class="ui-block-a">
                    <a href="dsp_edit_profile.html" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-theme="a" data-inline="true" class="ui-btn ui-btn-inline ui-btn-up-a  <?php if ($edit_profile_pageurl == "") echo "ui-btn-active"; ?>">
                        <span class="ui-btn-inner"><span class="ui-btn-text"><?php echo language_code('DSP_MENU_EDIT_MY_PROFILE'); ?></span></span>
                    </a>
                </li>
                <li class="ui-block-b">
                    <a href="dsp_edit_partner_profile.html" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-theme="a" data-inline="true" class="ui-btn ui-btn-inline ui-btn-up-a <?php if ($edit_profile_pageurl == "partner_profile") echo "ui-btn-active"; ?>">
                        <span class="ui-btn-inner"><span class="ui-btn-text"><?php echo language_code('DSP_MENU_EDIT_PARTNER_PROFILE'); ?></span></span>
                    </a>
                </li>

            </ul>
        </div>
    <?php } ?>

</div>


<?php
?>


<div class="ui-content" data-role="content">
    <div class="content-primary">	
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">

            <a onclick="slide_me('div_gen')" >
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                    <?php echo language_code('DSP_GENERAL'); ?>
                </li>
            </a>
            <li  id="div_gen" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <div >
                    <?php include_once('edit_partner_general.php'); ?>
                </div>
            </li>

            <a onclick="slide_me('div_edit_question')" >
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                    <?php echo language_code('DSP_PROFILE_QUESTIONS'); ?>
                </li>
            </a>
            <li  id="div_edit_question" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <div >
                    <?php include_once('edit_partner_question.php'); ?>
                </div>
            </li>

            <a onclick="slide_me('div_edit_pic')" >
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                    <?php echo language_code('DSP_ADD_PHOTO_BUTTON'); ?>
                </li>
            </a>
            <li  id="div_edit_pic" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <div >
                    <?php include_once('edit_partner_picture.php'); ?>
                </div>
            </li>

        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>
