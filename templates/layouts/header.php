<?php 
    include_once( WP_DSP_ABSPATH . 'functions.php');
    include_once(WP_DSP_ABSPATH . 'general_settings.php');
    include_once(WP_DSP_ABSPATH . 'external-lib/fb/fb.php');
    global $wpdb,$currentTemplatePath,$loginUrl;
    $dsp_general_settings_table = $wpdb->prefix . "dsp_general_settings";
    $dsp_general_settings_table = $wpdb->prefix . "dsp_general_settings";
    $templatePath = explode("/",$currentTemplatePath);
    $location = dsp_get_template_path($templatePath);
    $length = strlen($templatePath[1]);
    $templateNumber = substr($templatePath[1],$length-1,$length);
    $member_elements_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'home_page_elements'");
    $member_elements_values = explode(',',$member_elements_status->setting_value);

    // google api setting
    $siteKey = isset($check_google_app_id) ? $check_google_app_id->setting_value : '';//"6LeaFf8SAAAAAOvDpgAV1P5Wo0tEc2gfi53B0Sl-";
    $secret = isset($check_google_secret_key) ? $check_google_secret_key->setting_value : '';
    $isGoogleApiKeySet = (!empty($siteKey) && !empty($secret)) ? true : false;
  
    //facebook api setting
    $fbSettingStatus = get_facebook_login_setting('facebook_login','setting_status');
    $appId =  get_facebook_login_setting('facebook_api_key');
    $secretfb =  get_facebook_login_setting('facebook_secret_key');
    $isFacebookLoginSettingOn = ($fbSettingStatus == 'Y') ? true : false;
    $facebookCredentials = (!empty($appId) &&  !empty($secretfb)) ? true : false;
    $isDistanceModeOn = ($check_distance_mode->setting_status == 'Y') ? true : false;


    /**
    * search form setting  value
    *  ow = Old way Search form with list of country
    *  nw = New way Search form using google geography
    *  nn = Search form without Location 
    */
    $searchFormSettings = (isset($check_search_from_option) && !empty($check_search_from_option)) ?
                        $check_search_from_option->setting_value : 
                        '';
    
    $isPaswordOptionEnabled =  ($check_password_option->setting_status == 'Y') ? true : false;
    $isFirstNLastNameEnabled = ($register_form_setting->setting_status == 'Y') ? true : false;
    $userProfileDetailsExist = false;
    // get currently logged in user profile details
    if(is_user_logged_in()){
        $uId = get_current_user_id();
        $userProfileDetails = apply_filters('dsp_get_profile_details',$uId); 
        $userProfileDetailsExist = $userProfileDetails != false  ? true :false;
    }
/*
add_action('wp_enqueue_styles', 'dsp_enqueue_template_style');
function dsp_enqueue_template_style($location){
    $path =  isset($location) && !empty($location) ? $location .'template.css' : plugins_url('dsp_dating/templates/template1/template.css');
    wp_enqueue_style('jcarousel', $path);
}*/

?>
<link rel="stylesheet" type="text/css" href="<?php echo $location .'template.css'; ?>">
<script>
  document.body.onload = function(){
    initialize();
  }
 xfbml  : true  // parse XFBML
  
</script>
<?php
global $wpdb;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;  // print session USER_ID
$user = $user_id;
$inkthemes_altstylesheet = $wpdb->get_var("select option_value from " . $wpdb->prefix . "options where option_name='inkthemes_altstylesheet'");

if ($inkthemes_altstylesheet == 'none')
    $temp_color = '#525252';



if ($inkthemes_altstylesheet == 'green')
    $temp_color = '#0b720b';



if ($inkthemes_altstylesheet == 'blue')
    $temp_color = '#294582';



if ($inkthemes_altstylesheet == 'black')
    $temp_color = '#525252';



if ($inkthemes_altstylesheet == 'darkcyan')
    $temp_color = '#0b7474';



if ($inkthemes_altstylesheet == 'magenta')
    $temp_color = '#b817b8';



if ($inkthemes_altstylesheet == 'orange')
    $temp_color = '#fe7200';



if ($inkthemes_altstylesheet == 'red')
    $temp_color = '#a50c03';



if ($inkthemes_altstylesheet == 'yellow')
    $temp_color = '#a08600';





include_once(WP_DSP_ABSPATH . "js/user_section_functions.php");

$dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
$Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';
$lang_id = isset($_REQUEST['lid']) ? $_REQUEST['lid'] : '';
$display_status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
$siteurl = get_option('siteurl') . "/";
if($Action == 'language_status') {
        if(!empty($user_id))
        {
           if(function_exists('dsp_session_language_initialize')){
             dsp_session_language_initialize(true,$current_user,$lang_id);
            }
        }
        else
        { 
          if(function_exists('dsp_session_language_initialize')){
            dsp_session_language_initialize(false,null,$lang_id);
          }
          
          $_SESSION['default'] = $lang_id;
        }

?>

    <script>
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        location.href = loc;
    </script>

<?php } ?>

<style>
    .user-info-home .slider #slider div.navBulletsWrapper div {
        background: transparent url(<?php  echo WPDATE_URL .'/images/bullet_' . $inkthemes_altstylesheet . '.png'; ?>) no-repeat 0 0;
    }
    .user-info-home .slider #slider div.navBulletsWrapper div.active {
        background: transparent url(<?php  echo WPDATE_URL .'/images/bullet_' . $inkthemes_altstylesheet . '.png'; ?>) no-repeat 0 -11px;
    }
    .popup_username_div .user_icon {
        background: url(<?php echo plugins_url('images/username_img.png', __FILE__); ?>) no-repeat; background-color:#a1c30e;
    }
    .popup_username_div .pass_icon {
        background: url(<?php echo plugins_url('images/password_img.png', __FILE__); ?>) no-repeat; background-color:#a1c30e;
    }

    .popup_btn_div .popup_submit_btn {
        background: url(<?php  echo WPDATE_URL .'/templates/template2/images/login_btn_popup_' . $inkthemes_altstylesheet . '.png'; ?>);
    }
</style>


<script>
    jQuery(document).ready(function(e) {     
        jQuery("body").on('click','.reg_popoup',function() {
            jQuery(".join-freeboxx").dialog({height: 'auto', width: 412, title: "<?php echo language_code('DSP_DZONIA_REGISTER_HEADING_TEXT'); ?>"});
        });
        jQuery(function() {
            var demo1 = jQuery("#demo1").slippry({
                transition: 'fade',
                useCSS: true,
                speed: 1000,
                pause: 3000,
                auto: true,
                preload: 'visible'
            });

            jQuery('.stop').click(function() {
                demo1.stopAuto();
            });

            jQuery('.start').click(function() {
                demo1.startAuto();
            });

            jQuery('.prev').click(function() {
                demo1.goToPrevSlide();
                return false;
            });
            jQuery('.next').click(function() {
                demo1.goToNextSlide();
                return false;
            });
            jQuery('.reset').click(function() {
                demo1.destroySlider();
                return false;
            });
            jQuery('.reload').click(function() {
                demo1.reloadSlider();
                return false;
            });
            jQuery('.init').click(function() {
                demo1 = jQuery("#demo1").slippry();
                return false;
            });
        });

    });
</script>
<?php 

