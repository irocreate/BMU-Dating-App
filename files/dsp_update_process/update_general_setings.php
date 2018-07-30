
<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ***************************  ACTIVE DEACTIVE STATUS *********************************** //
global $wpdb;
$_SESSION['errors'] = array();
$dsp_general_settings_table = $wpdb->prefix . "dsp_general_settings";
$my_friend_module = isset($_REQUEST['my_friend_module']) ? $_REQUEST['my_friend_module'] : '';
$flirt_module = isset($_REQUEST['flirt_module']) ? $_REQUEST['flirt_module'] : '';
$free_mode = isset($_REQUEST['free_mode']) ? $_REQUEST['free_mode'] : '';
if($free_mode == 'Y'){
    $free_member = isset($_REQUEST['free_member']) ? $_REQUEST['free_member'] : '';
}
$skype_mode = isset($_REQUEST['skype_mode']) ? $_REQUEST['skype_mode'] : '';
$zipcode_mode = isset($_REQUEST['zipcode_mode']) ? $_REQUEST['zipcode_mode'] : '';
$nearme_mode = isset($_REQUEST['nearme']) ? $_REQUEST['nearme'] : '';
$authorize_profile = isset($_REQUEST['authorize_profile']) ? $_REQUEST['authorize_profile'] : '';
$authorize_photos = isset($_REQUEST['authorize_photos']) ? $_REQUEST['authorize_photos'] : '';
$authorize_audios = isset($_REQUEST['authorize_audios']) ? $_REQUEST['authorize_audios'] : '';
$authorize_videos = isset($_REQUEST['authorize_videos']) ? $_REQUEST['authorize_videos'] : '';
$authorize_comments = isset($_REQUEST['authorize_comments']) ? $_REQUEST['authorize_comments'] : '';
$count_image = isset($_REQUEST['count_image']) ? $_REQUEST['count_image'] : '';
$count_videos = isset($_REQUEST['count_videos']) ? $_REQUEST['count_videos'] : '';
$count_audios = isset($_REQUEST['count_audios']) ? $_REQUEST['count_audios'] : '';
$currency_sign = isset($_REQUEST['currency_sign']) ? $_REQUEST['currency_sign'] : '';
$dsp_currency = isset($_REQUEST['dsp_currency']) ? $_REQUEST['dsp_currency'] : '';
$trial_premium = isset($_REQUEST['trial_premium']) ? $_REQUEST['trial_premium'] : '';
$free_trial_gender = isset($_REQUEST['free_trial_gender']) ? $_REQUEST['free_trial_gender'] : '';
$dsp_currency = isset($_REQUEST['dsp_currency']) ? $_REQUEST['dsp_currency'] : '';
$free_trail_mode = isset($_REQUEST['free_trail_mode']) ? $_REQUEST['free_trail_mode'] : '';
$free_trail_gender = isset($_REQUEST['free_trail_gender']) ? $_REQUEST['free_trail_gender'] : '';
$free_trail_days_limit = isset($_REQUEST['free_trail_days_limit']) ? $_REQUEST['free_trail_days_limit'] : '';
$free_email_access = isset($_REQUEST['free_email_access']) ? $_REQUEST['free_email_access'] : '';
$free_email_access_gender = isset($_REQUEST['free_email_access_gender']) ? $_REQUEST['free_email_access_gender'] : '';
$member_list_gender = isset($_REQUEST['member_list_gender']) ? $_REQUEST['member_list_gender'] : '';
$force_profile = isset($_REQUEST['force_profile']) ? $_REQUEST['force_profile'] : '';
$rate_profile = isset($_REQUEST['rate_profile']) ? $_REQUEST['rate_profile'] : '';
$userplane_instant_messenger = isset($_REQUEST['userplane_instant_messenger']) ? $_REQUEST['userplane_instant_messenger'] : '';
$recipient_premium_member = isset($_REQUEST['recipient_premium_member']) ? $_REQUEST['recipient_premium_member'] : '';
$blog_module = isset($_REQUEST['blog_module']) ? $_REQUEST['blog_module'] : '';
$picture_gallery_module = isset($_REQUEST['picture_gallery_module']) ? $_REQUEST['picture_gallery_module'] : '';
$video_module = isset($_REQUEST['video_module']) ? $_REQUEST['video_module'] : '';
$audio_module = isset($_REQUEST['audio_module']) ? $_REQUEST['audio_module'] : '';
$match_alert = isset($_REQUEST['match_alert']) ? $_REQUEST['match_alert'] : '';
$male = isset($_REQUEST['male']) ? $_REQUEST['male'] : '';
$female = isset($_REQUEST['female']) ? $_REQUEST['female'] : '';
$couples = isset($_REQUEST['couples']) ? $_REQUEST['couples'] : '';
$date_tracker = isset($_REQUEST['date_tracker']) ? $_REQUEST['date_tracker'] : '';
$limit_profile = isset($_REQUEST['limit_profile']) ? $_REQUEST['limit_profile'] : '';
$no_of_profiles = isset($_REQUEST['no_of_profiles']) ? $_REQUEST['no_of_profiles'] : '';
$guest_limit_profile = isset($_REQUEST['guest_limit_profile']) ? $_REQUEST['guest_limit_profile'] : '';
$gno_of_profiles = isset($_REQUEST['gno_of_profiles']) ? $_REQUEST['gno_of_profiles'] : '';
$register_page_redirect = isset($_REQUEST['register_page_redirect']) ? $_REQUEST['register_page_redirect'] : '';
$registerurltxt = isset($_REQUEST['registerurltxt']) ? $_REQUEST['registerurltxt'] : '';
$after_register_page_redirect = isset($_REQUEST['after_register_page_redirect']) ? $_REQUEST['after_register_page_redirect'] : '';
$after_registerurltxt = isset($_REQUEST['after_registerurltxt']) ? $_REQUEST['after_registerurltxt'] : '';
$mobile_mode = isset($_REQUEST['mobile_mode']) ? $_REQUEST['mobile_mode'] : '';
$terms_page = isset($_REQUEST['terms_page']) ? $_REQUEST['terms_page'] : '';
$termspageurltxt = isset($_REQUEST['termspageurltxt']) ? $_REQUEST['termspageurltxt'] : '';
$gateways_mode = isset($_REQUEST['gateways_mode']) ? $_REQUEST['gateways_mode'] : '';
$image_crop_mode = isset($_REQUEST['image_crop_mode']) ? $_REQUEST['image_crop_mode'] : '';
$chat_mode = isset($_REQUEST['chat_mode']) ? $_REQUEST['chat_mode'] : '';
$chat_one_mode = isset($_REQUEST['chat_one_mode']) ? $_REQUEST['chat_one_mode'] : '';
$comments = isset($_REQUEST['comments']) ? $_REQUEST['comments'] : '';
$astrological_signs = isset($_REQUEST['astrological_signs']) ? $_REQUEST['astrological_signs'] : '';
$virtual_gifts = isset($_REQUEST['virtual_gifts']) ? $_REQUEST['virtual_gifts'] : '';
$virtual_gifts_max = isset($_REQUEST['virtual_gifts_max']) ? $_REQUEST['virtual_gifts_max'] : '';
$notification = isset($_REQUEST['notification']) ? $_REQUEST['notification'] : '';
$notification_postition = isset($_REQUEST['notification_postition']) ? $_REQUEST['notification_postition'] : '';
$notification_time = isset($_REQUEST['notification_time']) ? $_REQUEST['notification_time'] : '';
$meet_me = isset($_REQUEST['meet_me']) ? $_REQUEST['meet_me'] : '';
$refresh_rate = isset($_REQUEST['refresh_rate']) && !empty($_REQUEST['refresh_rate'])? $_REQUEST['refresh_rate'] : '10';
$pagination_color = isset($_REQUEST['pagination_color']) ? $_REQUEST['pagination_color'] : '';
$credit = isset($_REQUEST['credit']) ? $_REQUEST['credit'] : '';
$happening_graph = isset($_REQUEST['happening_graph']) ? $_REQUEST['happening_graph'] : '';
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$page_name = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';
$tab_color = isset($_REQUEST['tab_color']) ? $_REQUEST['tab_color'] : '';
$non_active_tab_color = isset($_REQUEST['non_active_tab_color']) ? $_REQUEST['non_active_tab_color'] : '';
$button_color = isset($_REQUEST['button_color']) ? $_REQUEST['button_color'] : '';
$title_color = isset($_REQUEST['title_color']) ? $_REQUEST['title_color'] : '';
$force_photo = isset($_REQUEST['force_photo']) ? $_REQUEST['force_photo'] : '';
$search_result = isset($_REQUEST['search_result']) && !empty($_REQUEST['search_result']) ? $_REQUEST['search_result'] : 10;
$front_page_result = isset($_REQUEST['front_page_result']) ? $_REQUEST['front_page_result'] : '';
$email_admin = isset($_REQUEST['email_admin']) ? $_REQUEST['email_admin'] : '';
$display_options = isset($_REQUEST['display_options']) ? $_REQUEST['display_options'] : '';
$eighteen_years_ago = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y") - 18));
$start_dsp_year = (isset($_REQUEST['start_dsp_year']) && $_REQUEST['start_dsp_year'] !== '') ? $_REQUEST['start_dsp_year'] : $eighteen_years_ago;
$home_elements = (isset($_REQUEST['home']) && $_REQUEST['home'] !== '') ? implode(',',$_REQUEST['home']) :'';
$online_member_status = isset($_REQUEST['random_online_members']) ? $_REQUEST['random_online_members'] : '';
$random_online_member_numbers = isset($_REQUEST['random_online_members_nos']) ? $_REQUEST['random_online_members_nos'] : '';

$distance_feature = isset($_REQUEST['distance_feature']) ? $_REQUEST['distance_feature'] : '';

//recapcha status
$recaptchStatus = isset($_REQUEST['recaptcha_option']) ? $_REQUEST['recaptcha_option'] : '';
//GOOGLE API KEY
$googleApi = isset($_REQUEST['google_api_key']) ? $_REQUEST['google_api_key'] : '';
//Facebook API Settings
$facebookApiKey = isset($_REQUEST['facebook_api_key']) ? $_REQUEST['facebook_api_key'] : '';
$facebookSecretKey = isset($_REQUEST['facebook_secret_key']) ? $_REQUEST['facebook_secret_key'] : '';

// Google API Settings
$googleApiKey = isset($_REQUEST['google_api_key']) ? $_REQUEST['google_api_key'] : '';
$googleSecretKey = isset($_REQUEST['google_secret_key']) ? $_REQUEST['google_secret_key'] : '';

// Private Photo Settings
$private_photo = isset($_REQUEST['private_photo']) ? $_REQUEST['private_photo'] : '';

// Facebook Login Settings
$facebook_login = isset($_REQUEST['facebook_login']) ? $_REQUEST['facebook_login'] : '';

// Discount Coupan Code Settings
$discount_code = isset($_REQUEST['discount_code']) ? $_REQUEST['discount_code'] : '';

// Trending Settings
$trending_option = isset($_REQUEST['trending_option']) ? $_REQUEST['trending_option'] : '';

// Password field in register Settings
$password_option = isset($_REQUEST['password_option']) ? $_REQUEST['password_option'] : '';

// firstname and lastname field in registeration form Settings
$register_form_setting = isset($_REQUEST['register_form_first_last_name_field']) ? $_REQUEST['register_form_first_last_name_field'] : '';

$display_user_name = isset($_REQUEST['display_user_name']) ? $_REQUEST['display_user_name'] : '';

$after_user_register_option = isset($_REQUEST['after_user_register_option']) ? $_REQUEST['after_user_register_option'] : '';

$po_language = isset($_REQUEST['po_language']) ? $_REQUEST['po_language'] : '';

$google_api_key_zip = isset($_REQUEST['google_api_key_zip']) ? $_REQUEST['google_api_key_zip'] : '';


// End dsp year
$end_dsp_year = (isset($_REQUEST['end_dsp_year']) && $_REQUEST['end_dsp_year'] !== '') ? $_REQUEST['end_dsp_year'] : $eighteen_years_ago + 80;

if($random_online_member_numbers > 10){
    $_SESSION['errors']['exceeds_random_online_nos'] = language_code('DSP_EXCEEDS_NO_OF_ONLINE_MEMBERS');
}else{

    if ($mode == 'update' && $page_name == 'update_general_settings') {
    $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$email_admin' WHERE setting_name = 'email_admin'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$my_friend_module' WHERE setting_name = 'my_friends'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$flirt_module' WHERE setting_name = 'flirt_module'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$free_mode' WHERE setting_name = 'free_mode'");
        if ($free_mode == 'Y' && $free_member > 0) {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = 'Y',setting_value = '$free_member' WHERE setting_name = 'free_member'");
        }
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$skype_mode' WHERE setting_name = 'skype_mode'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$zipcode_mode' WHERE setting_name = 'zipcode_mode'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$nearme_mode' WHERE setting_name = 'near_me'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$authorize_profile' WHERE setting_name = 'authorize_profiles'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$authorize_photos' WHERE setting_name = 'authorize_photos'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$authorize_audios' WHERE setting_name = 'authorize_audios'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$authorize_videos' WHERE setting_name = 'authorize_videos'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$authorize_comments' WHERE setting_name = 'authorize_comments'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value = '$count_image',setting_status = 'Y' WHERE setting_name = 'count_image'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value = '$count_audios',setting_status = 'Y' WHERE setting_name = 'count_audios'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value = '$count_videos',setting_status = 'Y' WHERE setting_name = 'count_videos'");
        //$wpdb->query("UPDATE $dsp_general_settings_table SET setting_value = '$defaultCountry',setting_status = 'Y' WHERE setting_name = 'default_country'");
        if ($free_trail_days_limit > 0) {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$free_trail_mode',setting_value='$free_trail_days_limit' WHERE setting_name = 'free_trail_mode'");
        }
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$free_trail_mode' WHERE setting_name = 'free_trail_mode'");
        if ($free_trail_gender > 0) {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = 'Y',setting_value='$free_trail_gender' WHERE setting_name = 'free_trail_gender'");
        }
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$free_email_access' WHERE setting_name = 'free_email_access'");
        if ($free_email_access_gender > 0) {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = 'Y',setting_value='$free_email_access_gender' WHERE setting_name = 'free_email_access_gender'");
        }
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value='$member_list_gender' WHERE setting_name = 'member_list_gender'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$force_profile' WHERE setting_name = 'force_profile'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$force_photo' WHERE setting_name = 'force_photo'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$rate_profile' WHERE setting_name = 'rate_profile'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$userplane_instant_messenger' WHERE setting_name = 'userplane_instant_messenger'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$recipient_premium_member' WHERE setting_name = 'recipient_must_be_premium_member'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$blog_module' WHERE setting_name = 'blog_module'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$picture_gallery_module' WHERE setting_name = 'picture_gallery_module'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$video_module' WHERE setting_name = 'video_module'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$audio_module' WHERE setting_name = 'audio_module'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$match_alert' WHERE setting_name = 'match_alert'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$male' WHERE setting_name = 'male'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$female' WHERE setting_name = 'female'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$couples' WHERE setting_name = 'couples'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$date_tracker' WHERE setting_name = 'date_tracker'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$limit_profile' WHERE setting_name = 'limit_profile'");
        if ($no_of_profiles > 0) {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$limit_profile',setting_value='$no_of_profiles' WHERE setting_name = 'limit_profile'");
        }
        //$wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$guest_limit_profile' WHERE setting_name = 'guest_limit_profile'");
        //if ($gno_of_profiles > 0) {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$guest_limit_profile',setting_value='$gno_of_profiles' WHERE setting_name = 'guest_limit_profile'");
        //}
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$register_page_redirect' WHERE setting_name = 'register_page_redirect'");
        if ($registerurltxt != '') {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$register_page_redirect',setting_value='$registerurltxt' WHERE setting_name = 'register_page_redirect'");
        }

    $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$after_register_page_redirect' WHERE setting_name = 'after_registration_redirect'");
    if ($registerurltxt != '') {
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$after_register_page_redirect',setting_value='$after_registerurltxt' WHERE setting_name = 'after_registration_redirect'");
    }    
        $wpdb->query(("UPDATE $dsp_general_settings_table SET setting_status='$mobile_mode' WHERE setting_name = 'mobile'"));
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$terms_page' WHERE setting_name = 'terms_page'");
        if ($termspageurltxt != '') {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$terms_page',setting_value='$termspageurltxt' WHERE setting_name = 'terms_page'");
        }
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$gateways_mode' WHERE setting_name = 'gateways'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$image_crop_mode' WHERE setting_name = 'image_crop'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$chat_mode' WHERE setting_name = 'chat_mode'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$chat_one_mode' WHERE setting_name = 'chat_one_mode'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$comments' WHERE setting_name = 'comments'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$astrological_signs' WHERE setting_name = 'astrological_signs'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$virtual_gifts' WHERE setting_name = 'virtual_gifts'");
        if ($virtual_gifts_max != '') {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$virtual_gifts',setting_value='$virtual_gifts_max' WHERE setting_name = 'virtual_gifts'");
        }
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$notification' WHERE setting_name = 'notification'");
        if ($notification_postition != '') {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$notification',setting_value='$notification_postition' WHERE setting_name = 'notification_postition'");
        }
        if ($notification_time != '') {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$notification',setting_value='$notification_time' WHERE setting_name = 'notification_time'");
        }
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$meet_me',setting_value='$notification_time' WHERE setting_name = 'meet_me'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = 'Y',setting_value='$refresh_rate' WHERE setting_name = 'refresh_rate'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = 'Y',setting_value='$pagination_color' WHERE setting_name = 'pagination_color'");

        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = 'Y',setting_value='$tab_color' WHERE setting_name = 'tab_color'");

        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = 'Y',setting_value='$non_active_tab_color' WHERE setting_name = 'non_active_tab_color'");

        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = 'Y',setting_value='$button_color' WHERE setting_name = 'button_color'");

        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = 'Y',setting_value='$title_color' WHERE setting_name = 'title_color'");

        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = 'Y',setting_value='$search_result' WHERE setting_name = 'search_result'");

        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = 'Y',setting_value='$front_page_result' WHERE setting_name = 'front_page_result'");

        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$happening_graph' WHERE setting_name = 'happening_graph'");

        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$credit' WHERE setting_name = 'credit'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value ='$display_options' WHERE setting_name = 'display_options'");
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value='$home_elements' WHERE setting_name = 'home_page_elements'");

        /* dsp start year */
        /*$wpdb->query("ALTER IGNORE TABLE `$dsp_general_settings_table` ADD CONSTRAINT unique_setting_name UNIQUE (`setting_name`)");
        $wpdb->query("INSERT INTO $dsp_general_settings_table (ID, setting_name, setting_status, setting_value) VALUES ('', 'start_dsp_year', 'Y', '$start_dsp_year') 
    			ON DUPLICATE KEY UPDATE  setting_value = $start_dsp_year");
         */
        $sql = "SELECT setting_value FROM $dsp_general_settings_table WHERE setting_name = 'start_dsp_year'";
       
        if($wpdb->get_row($sql)) {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value ='$start_dsp_year' WHERE setting_name = 'start_dsp_year'");
        } else {
            $wpdb->query("INSERT INTO $dsp_general_settings_table SET setting_status='Y', setting_value ='$start_dsp_year', setting_name = 'start_dsp_year'");
        }

        $sql = "SELECT setting_value FROM $dsp_general_settings_table WHERE setting_name = 'end_dsp_year'";
        if($wpdb->get_row($sql)) {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value ='$end_dsp_year' WHERE setting_name = 'end_dsp_year'");
        } else {
            $wpdb->query("INSERT INTO $dsp_general_settings_table SET setting_status='Y', setting_value ='$end_dsp_year', setting_name = 'end_dsp_year'");
        }

        //online members
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$online_member_status' WHERE setting_name = 'random_online_members'");
        if (isset($random_online_member_numbers) && $random_online_member_numbers < 10) {
            $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = '$online_member_status',setting_value='$random_online_member_numbers' WHERE setting_name = 'random_online_members'");
        }
       
        ### Distance feature module ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$distance_feature' WHERE setting_name = 'distance_feature'"); 
        
        ### Recaptcha feature module ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$recaptchStatus' WHERE setting_name = 'recaptcha_option'"); 
        
        ### Google API module ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value='$googleApi' WHERE setting_name = 'google_api_key'"); 

        ### Facebook API setting module ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value='$facebookApiKey' WHERE setting_name = 'facebook_api_key'"); 
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value='$facebookSecretKey' WHERE setting_name = 'facebook_secret_key'"); 

         ### Facebook API setting module ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value='$googleApiKey' WHERE setting_name = 'google_api_key'"); 
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value='$googleSecretKey' WHERE setting_name = 'google_secret_key'"); 

        ### facebook_login Setting ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$facebook_login' WHERE setting_name = 'facebook_login'"); 
        
        ### Make Private Photo Setting ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$private_photo' WHERE setting_name = 'private_photo'"); 

        ### Make Private Photo Setting ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$discount_code' WHERE setting_name = 'discount_code'"); 

        ### Trending Setting ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$trending_option' WHERE setting_name = 'trending_status'"); 

        ### Password Setting ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$password_option' WHERE setting_name = 'password_option'"); 
        
        ### Register form Setting ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$register_form_setting' WHERE setting_name = 'register_form_setting'"); 
        
         ### display name in user profile section ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value ='$display_user_name' WHERE setting_name = 'display_user_name'"); 
        
        ### display name in user profile section ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value ='$after_user_register_option' WHERE setting_name = 'after_user_register_option'"); 

        ## Use PO language ####
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status='$po_language' WHERE setting_name = 'po_language'");

        ## Google api key zip
        $wpdb->query("UPDATE $dsp_general_settings_table SET setting_value='$google_api_key_zip' WHERE setting_name = 'google_api_key_zip'");
   
        $_SESSION['message'] = language_code('DSP_SETTINGS_SAVED_MESSAGE');
    }
}    
?>
<script>location.href = "<?php
echo add_query_arg(array('pid' => 'general_settings',
    'updated' => 'true'), $settings_root_link);
?>"</script>
<?php
// ***************************  ACTIVE DEACTIVE STATUS *********************************** // ?>