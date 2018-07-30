<?php
include("../../../../wp-config.php");
//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->
//

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));



//-------------------------DISPLAY ERROR OFF CODE ENDS--------------------------------



$dsp_mobile_app_settings = $wpdb->prefix . DSP_MOBILE_APP_SETTING_TABLE;
$img = "";

$query = "select about_text from $dsp_mobile_app_settings";
$about = $wpdb->get_var($query);
?>


<div  data-role="header" class="ui-header ui-bar-a" role="banner">
    <div class="back-image">
        <a href="index.html"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_ABOUT'); ?></h1>

</div>



<div class="ui-content" data-role="content">
    <div class="content-primary">	 

        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">
            <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">

                <?php echo $about; ?>
            </li>
        </ul>

    </div>

</div>




