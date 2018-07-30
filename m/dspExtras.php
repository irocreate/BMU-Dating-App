<?php
include("../../../../wp-config.php");

//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include_once("dspFunction.php");

include_once("../general_settings.php");
?>



<?php
$user_id = $_REQUEST['user_id'];
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_EXTRAS'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>

</div>


<div class="ui-content" data-role="content">
    <div class="content-primary">	 

        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">

            <a  onclick="slide_me('div_viewed_me')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">

                    <?php echo language_code('DSP_MENU_VIEWED_ME'); ?>
                </li>
            </a>
            <li  id="div_viewed_me" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('viewed_me.php') ?>
            </li>
            <a onclick="slide_me('div_i_viewed')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <?php echo language_code('DSP_MENU_I_VIEWED'); ?>	
                </li>
            </a>
            <li  id="div_i_viewed" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('iviewed.php') ?>
            </li>
            <?php if($check_trending_option->setting_status == 'Y'){ ?>
            <a onclick="slide_me('div_trending')">
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <?php echo language_code('DSP_MENU_TRENDING'); ?>
                </li>
            </a>
            <li  id="div_trending" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('trending.php') ?>
            </li>
<?php }?>
            <a onclick="slide_me('div_cloud')" >
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <?php echo language_code('DSP_INTEREST_CLOUD'); ?>
                </li>
            </a>
            <li  id="div_cloud" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('interest_cloud.php') ?>
            </li>

            <?php if ($check_blog_module->setting_status == 'Y') { ?>
                <a onclick="callExtra('blogs')">
                    <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                        <?php echo language_code('DSP_MENU_MY_BLOGS'); ?>
                    </li>
                </a>
            <?php } ?>

            <?php if ($check_meet_me_mode->setting_status == 'Y') {
                ?>
                <a onclick="slide_me('div_meetme')" >
                    <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                        <?php echo language_code('DSP_MEET_ME'); ?>
                    </li>
                </a>
                <li  id="div_meetme" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                    <?php include_once('dsp_meet_me.php') ?>
                </li>
            <?php } ?>

        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>	