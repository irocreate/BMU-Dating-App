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
     <?php include_once("page_menu.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_EXTRAS'); ?></h1>
     <?php include_once("page_home.php");?> 

</div>


<div class="ui-content" data-role="content">
    <div class="content-primary">	 

        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all alert-list">

           
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                     <a  onclick="slide_me('div_viewed_me')">
                    <img src="images/icons/viewedme.png" />
                    <?php echo language_code('DSP_MENU_VIEWED_ME'); ?>
                    </a>
                </li>
            </ul>
             <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all accordion-item">
            <li  id="div_viewed_me" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('viewed_me.php') ?>
            </li>
           </ul>
              <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all alert-list">
          
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                     <a onclick="slide_me('div_i_viewed')">
                    <img src="images/icons/iviewed.png" />
                    <?php echo language_code('DSP_MENU_I_VIEWED'); ?>
                    </a>	
                </li>
            </ul>
               <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all accordion-item">
          
            <li  id="div_i_viewed" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('iviewed.php') ?>
            </li>
            </ul>
            <?php if($check_trending_option->setting_status == 'Y'){ ?>
              <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all alert-list">
          
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <a onclick="slide_me('div_trending')">
                    <img src="images/icons/trending.png" />
                    <?php echo language_code('DSP_MENU_TRENDING'); ?>
                     </a>
                </li>
           </ul>
              <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all teneding-search">
          
            <li  id="div_trending" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
               
                <?php include_once('trending.php') ?>
            </li>
            </ul>
<?php }?>
               <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all alert-list">
          
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <a onclick="slide_me('div_cloud')" >
                    <img src="images/icons/intrestcloud.png" />
                    <?php echo language_code('DSP_INTEREST_CLOUD'); ?>
                      </a>
                </li>
          </ul>
             <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all teneding-search">
          
            <li  id="div_cloud" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                <?php include_once('interest_cloud.php') ?>
            </li>
</ul>
            <?php if ($check_blog_module->setting_status == 'Y') { ?>
                   <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all menu-list">
          
                    <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                       <a onclick="callExtra('blogs')">
                        <img src="images/icons/blogs.png" />
                        <?php echo language_code('DSP_MENU_MY_BLOGS'); ?>
                        </a>
                    </li>
                </ul>
            <?php } ?>

            <?php if ($check_meet_me_mode->setting_status == 'Y') {
                ?>
                   <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all alert-list">
          
                    <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                       <a onclick="slide_me('div_meetme')" >
                       <img src="images/icons/meetme.png" />
                        <?php echo language_code('DSP_MEET_ME'); ?>
                        </a>
                    </li>
                    </ul>
                       <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all teneding-search">
          
                
                <li  id="div_meetme" data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all dsp_inv">
                    <?php include_once('dsp_meet_me.php') ?>
                </li>
                </ul>
            <?php } ?>

    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>	
<?php include_once("dspLeftMenu.php"); ?>