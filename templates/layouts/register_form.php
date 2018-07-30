<?php
global $wpdb;
include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
@session_start();
$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path
$path = $pluginpath . 'image.php';
$check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");
// ROOT PATH 
//$root_link = get_bloginfo('url') . "/members/";  // Print Site root link
if ($check_recaptcha_mode->setting_status == 'Y'  && $isGoogleApiKeySet){
    $path = WP_DSP_ABSPATH . 'recaptchalib.php';
    require_once($path);
}
if(get_option('users_can_register')){
?>
<form name="regfrm" id="regfrm" action="<?php echo ROOT_LINK . "register/"; ?>" method="post" data-parsley-validate >
    <ul>
        <li><span><?php echo language_code('DSP_USER_NAME'); ?></span> <input type="text" name="username" id="username"  required /></li>
        <?php if($isFirstNLastNameEnabled): ?>
            <li >
                <span ><?php echo language_code('DSP_GATEWAYS_FIRST_NAME'); ?></span>
                <input type="text" name="firstname"  value="<?php echo isset($firstname)?$firstname:'';?>" required />
            </li>
            <li >
                <span ><?php echo language_code('DSP_GATEWAYS_LAST_NAME'); ?></span>
               <input type="text" name="lastname"  value="<?php echo isset($lastname)?$lastname:'';?>" required />
            </li>
        <?php endif; ?>
        <?php 
        if($isPaswordOptionEnabled){
        $values['password'] = @$password;
        $values['rePassword'] = @$rePassword;
        $password_content = '';
        $password_content = apply_filters('dsp_register_form_filter', $password_content, $values);
        echo $password_content;
        }
        ?>
        <li><span><?php echo language_code('DSP_REGISTER_GENDER'); ?></span> <select  name="gender"> 
        <?php echo get_gender_list(); ?>
        </select>
        </li>
        <li><span><?php echo language_code('DSP_EMAIL_ADDRESS'); ?></span> <input type="text" id="dsp-email" name="email" data-parsley-trigger="change" required  /></li>
        <li><span><?php echo language_code('DSP_CONFIRM_EMAIL_ADDRESS'); ?></span> <input type="text" name="confirm_email" data-parsley-equalto="#dsp-email" data-parsley-trigger="change" required /></li>
        <li style="width:100%; margin:10px 0px;">
            <div class="dspdp-row">               
                <div class="dspdp-col-sm-3 register_date_label">
            <span><?php echo language_code('DSP_BIRTH_DATE'); ?></span> 
            </div>
                <div class="dspdp-col-sm-9 register_date_pad">
                    <div class="dspdp-col-sm-4 register_date_pad">
            <?php
            //array to store the months
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
            <select class="month dspdp-form-control dsp-form-control" name="dsp_mon">
            <?php foreach ($mon as $key => $value) {
                ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                <?php } ?>
            </select>
            &nbsp;	
                    </div>
                <?php //make the day pull-down menu  ?>
                    <div class="dspdp-col-sm-2 register_date_pad">
            <select class="days dspdp-form-control dsp-form-control" name="dsp_day" >
            <?php
            for ($dsp_day = 1; $dsp_day <= 31; $dsp_day++) {
            if ($split_age[2] == $dsp_day) {
            ?>
                        <option value="<?php echo $dsp_day ?>" selected><?php echo $dsp_day ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $dsp_day ?>"><?php echo $dsp_day ?></option>
                        <?php
                    }
                }
                ?>
            </select>
            &nbsp;	
                    </div>
                    <div class="dspdp-col-sm-3 register_date_pad">
                <?php //make the year pull-down menu ?>
            <select name="dsp_year"  class="dspdp-form-control dsp-form-control">
                <?php
                    $start_dsp_year = $check_start_year->setting_value;
                    $end_dsp_year = $check_end_year->setting_value;
                    echo dsp_get_year($start_dsp_year,$end_dsp_year,$split_age[0]);
                ?>
            </select>             
            </div>
                </div>
            </div>
        </li>
        <?php if ($check_recaptcha_mode->setting_status == 'Y' && $isGoogleApiKeySet): ?>
            <li style="width:100%;">
                <span style="width:60px; margin-right:0px;" class="g-recaptcha"  data-sitekey="<?php echo $siteKey; ?>"><?php echo language_code('DSP_CAPTCHA'); ?>
                </span> <?php $error = isset($error) ? $error : '';
                    //echo recaptcha_get_html($publickey, $error); ?>
            </li>
        <?php endif; ?>
        <?php if ($check_terms_page_mode->setting_status == 'Y') { ?>
            <li style="width:100%;"> <strong><?php echo str_replace('[L]', $check_terms_page_mode->setting_value, language_code('DSP_TERMS_TEXT')); ?></strong><input name="terms" type="checkbox"  /></li>
        <?php } ?>
        <li style="width:100%;"> <?php /* ?><a id="register" onclick="" href="javascript:void(0);"> <?php echo language_code('DSP_REGISTER');?> </a><?php */ ?><input  class="join-btn" type="submit" name="join" value="<?php echo language_code('DSP_DZONIA_REGISTER_BTN_TEXT'); ?>" style=" " /> <?php /* ?><input class="login-btn" type="button" value="<?php echo strtoupper(language_code('DSP_LOGIN'));?>" style="
          background: <?php echo $temp_color;?>;
          " /><?php */ ?>
        </li>
    </ul>
</form>
<?php }else{ ?>
    <span style="float:left; width:100%; text-align:center; color:#ff0000;">
        <?php echo language_code('DSP_REGISTRATION_IS_CURRENTLY_DISABLE_PLEASE_TRY_AGAIN_LATER'); ?>
    </span>
<?php } ?>
<script>
    jQuery(document).ready(function(e) {
        jQuery("#recaptcha_area").each(function() {
            jQuery(this).css({'width': 'auto !important'});
            jQuery(this).css({'float': 'right !important'});
        });
        jQuery("#recaptcha_area span").each(function() {
            jQuery(this).css({'line-height': 'inherit !important'});
            jQuery(this).css({'width': 'auto !important'});
        });
    });
</script>
