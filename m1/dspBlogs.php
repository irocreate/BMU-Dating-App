<?php
//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

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
    <?php include_once("page_back.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_MY_BLOGS'); ?></h1>
     <?php include_once("page_home.php");?> 
</div>


<div class="ui-content" data-role="content">
    <div class="content-primary">	 

        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all menu-list">

           
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                <a  onclick="viewExtra(0, 'add_blogs')">
                    <img src="images/icons/addblogs.png"/>
                    <?php echo language_code('DSP_MENU_ADD_MY_BLOGS'); ?>
                     </a>
                </li>
           
           
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                     <a onclick="viewExtra(0, 'my_blogs')">
                     <img src="images/icons/blogs.png"/>
                    <?php echo language_code('DSP_MENU_MY_BLOGS'); ?>	
                     </a>
                </li>
            </a>


        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>	