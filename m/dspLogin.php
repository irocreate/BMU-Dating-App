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



//-------------------------DISPLAY ERROR OFF CODE ENDS--------------------------------



$dsp_mobile_app_settings = $wpdb->prefix . DSP_MOBILE_APP_SETTING_TABLE;
$img = "";

$query = "select mobile_logo from $dsp_mobile_app_settings";
$mobile_logo = $wpdb->get_var($query);

if (count($mobile_logo) > 0) {
    $img = get_bloginfo('url') . '/wp-content/uploads/dsp_media/mobile_logo/' . $mobile_logo;
}

if (!is_array(@getimagesize($img))) {  //don't exist  
    $img = get_bloginfo('url') . '/wp-content/uploads/dsp_media/mobile_logo/iphone-usericon100.jpg';
    if (!is_array(@getimagesize($img))) {  //don't exist  
        $img = get_bloginfo('url') . '/wp-content/plugins/dsp_dating/m/images/iphone-usericon100.jpg';
    }
}
?>


<div  data-role="header" class="ui-header ui-bar-a" role="banner">
    <a class="ui-btn-left ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all"  href="dsp_about.html"  data-shadow="true"  data-theme="a">
        <span class="ui-btn-inner">
            <span class="ui-btn-text"><?php echo language_code('DSP_ABOUT'); ?></span>
        </span>
    </a>
    <span class="ui-title"></span>
</div>



<div class="ui-content" data-role="content">
    <a id="show_reg" class="mam_text_dec"  style="color:black;">
        <div class="content-primary">	 

            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all ui-shadow">
                <li data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="d" class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-d">
                    <div class="ui-btn-inner ui-li">
                        <div class="ui-btn-text">
                            <img  class="mam_login_img" src="images/iphone-usericon40.jpg" width="40" height="40"/></span>
                            <strong><?php echo language_code('DSP_REGISTER'); ?></strong>
                        </div>
                        <span class="ui-icon ui-icon-arrow-r ui-icon-shadow" style="right:5px;">&nbsp;</span>
                    </div>
                </li>
            </ul>

        </div>
    </a>
</div>



<div data-role="content" class="ui-content" role="main">
    <form id="login_form" class="ui-body ui-body-d ui-corner-all">
        <div id="reg_result"></div> 
        <fieldset>
            <div data-role="fieldcontain">
                <input type="text" value="" name="loginUsername" id="loginUsername" placeholder="<?php echo language_code('DSP_USERNAME'); ?>"/>
            </div>                                  
            <div data-role="fieldcontain">                                      
                <input type="password" value="" name="password" id="password" placeholder="<?php echo language_code('DSP_LOGIN_PASSWORD'); ?>"/> 
            </div>
            <input class="mam_btn" type="button" name="submit" id="submit" style="color: blue;" value="<?php echo language_code('DSP_LOGIN'); ?>">
        </fieldset>


    </form>                              
</div>	 

<div id="siteLogo" data-role="content" class="ui-content" role="main" style="text-align: center">
    <img  width="100" height="100"  src="<?php echo $img; ?>" />	
</div>
