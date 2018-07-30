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

//-------------------------DISPLAY ERROR OFF CODE ENDS--------------------------------

global $wpdb;
global $msg;

include_once("dspGetSite.php");


$url = get_bloginfo('url');
$siteUrl = cleanUrl($url);


include_once(WP_DSP_ABSPATH . "general_settings.php");
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
      <a class="ui-btn-left ui-btn-corner-all ui-icon-back ui-btn-icon-notext ui-shadow"  href="index.html"  data-shadow="true"  data-theme="a">
    </a> 
      <h1 aria-level="1" role="heading" class="ui-title"><?php echo $siteUrl; ?></h1>

</div>
<div class="ui-content" data-role="content">
    <div class="content-primary">	
        <?php
        if (get_option('users_can_register')) {

//Check whether user registration is enabled by the administrator 
            ?>


            <form id="register-user" >
                <div id="reg_result_register"></div>
                <fieldset>
                    <div data-role="fieldcontain">
                        <input type="text" value="" name="username" id="username" placeholder="Username"/>
                    </div>    
                    <div data-role="fieldcontain">
                        <input type="text" value="" name="email" id="email" placeholder="Email Adderss"/>
                    </div>                              
                    <div data-role="fieldcontain">                                      
                        <input type="text" value="" name="confirm_email" id="confirm_email" placeholder="Confirm Email Address"/> 
                    </div>

                    <label data-role="fieldcontain" class="select-group">  
                    <div class="clearfix">                                    
                        <div class="mam_reg_lf select-label"><?php echo language_code('DSP_REGISTER_GENDER'); ?></div>
                        <select name="select_gender" id="select_gender" data-mini="true" data-role="none">
                            <option value="M"><?php echo language_code('DSP_MAN'); ?></option>
                            <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>

                            <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                                <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>

                            <?php } ?>

                        </select> 
                        </div>
                    </label>

                    <div data-role="fieldcontain" >                                      
                        <div class="mam_reg_lf spacer-bottom-xs"><?php echo language_code('DSP_BIRTH_DATE'); ?></div>
                        <?php
                        $mon = array(1 => language_code('DSP_JANUARY'),
                            language_code('DSP_FABRUARY'),
                            language_code('DSP_MARCH'),
                            language_code('DSP_APRIL'),
                            language_code('DSP_MAY'),
                            language_code('DSP_JUNE'),
                            language_code('DSP_JULY'),
                            language_code('DSP_AUGUST'),
                            language_code('DSP_SEPTEMBER'),
                            language_code('DSP_OCTOBER'),
                            language_code('DSP_NOVEMBER'),
                            language_code('DSP_DECEMBER'));
                        ?>
<div class="col-cont clearfix">
<div class="col-3">
<label class="select-group">
                        <select name="dsp_mon" class="select-full"  data-mini="true" data-role="none">

                            <?php
                            foreach ($mon as $key => $value) {

                                if ($split_age[1] == $key) {
                                    ?>
                                    <option value="<?php echo $key ?>" selected><?php echo $value ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="<?php echo $key ?>"><?php echo $value ?></option>

                                    <?php
                                }
                            }
                            ?>
                        </select>
</label></div>
<div class="col-3">
<label class="select-group">
                        <select name="dsp_day" class="select-full"  data-role="none">

                            <?php
                            for ($dsp_day = 1; $dsp_day <= 31; $dsp_day++) {
                                if ($split_age[2] == $dsp_day) {
                                    ?>
                                    <option value="<?php echo $dsp_day ?>" selected><?php echo $dsp_day ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="<?php echo $dsp_day ?>"><?php echo $dsp_day ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
</label>
</div>
<div class="col-3">
<label class="select-group">
                        <select name="dsp_year" class="select-full"  data-role="none">
                            <?php                       
                                   
                            $contents = '';
                            $start_dsp_year = $check_start_year->setting_value;
                            $end_dsp_year = $check_end_year->setting_value;

                            $start_dsp_year = !empty($start_dsp_year) ? $start_dsp_year : 1925;
                            $end_dsp_year = !empty($end_dsp_year) ? $end_dsp_year : date('Y');
                            $selected = $split_age[0];
                            for ($dsp_year = $end_dsp_year; $dsp_year >= $start_dsp_year; $dsp_year--) 
                            {
                                $select = ($selected == $dsp_year) ? 'selected' : '';
                                $contents .= '<option value="' . $dsp_year . '" ' . $selected . '>' . $dsp_year . '</option>';
                            }
                            echo $contents;                               
                            ?>
                        </select>
</label>
</div>
</div>
                    </div>

                    <div data-role="fieldcontain"> 
                    <input class="check-box" data-role="none" type="checkbox" value="1" name="terms" checked>

                        <div class="mam_reg_lf"><?php echo language_code("DSP_TERMS_A") ?> </div>

                        <a href="dsp_terms.html"  data-role="none" ><?php echo language_code("DSP_TERMS_B"); ?></a>
                        
                    </div> 

                    <div class="btn-blue-wrap"><input class="mam_btn btn-blue" type="button" name="register" id="register" value="Register"></div>
                    <br> <div style="font-size: 10px;">  <?php echo language_code('DSP_NOTE_A_PASSWORD_WILL_BE_EMAIL_TO_YOU'); ?></div> 
                </fieldset>

            </form>    


            <?php
        } else {
            ?>
            <span style="float:left; width:100%; text-align:center; color:#ff0000;">
                <?php echo language_code('DSP_REGISTRATION_IS_CURRENTLY_DISABLE_PLEASE_TRY_AGAIN_LATER'); ?>


            </span>
            <?php
        }
        ?>
    </div>
</div>