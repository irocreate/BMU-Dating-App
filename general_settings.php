<?php 

/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
// check My friends module is Activated or not.
$check_my_friend_module = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'my_friends'");
// check flirt(wink) module is Activated or not.
$check_flirt_module = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'flirt_module'");
// check Free Mode is Activated or not.
$check_free_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_mode'");

// check Free Trail Mode is Activated or not.
$check_free_trail_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_trail_mode'");
// check Member List Gender Access  Mode is Activated or not.
$check_free_member_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_member'");
// check Free Trail Mode is Activated or not.
$check_free_trail_gender_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_trail_gender'");
// check Free Email Access Mode  is Activated or not.
$check_free_email_access_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_email_access'");
// check Free Email Access  Mode is Activated or not.
$check_free_email_access_gender_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_email_access_gender'");
// check Member List Gender Access  Mode is Activated or not.
$check_member_list_gender_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'member_list_gender'");
// check Force profile Access  Mode is Activated or not.
$check_force_profile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'force_profile'");
// check force_photo Access  Mode is Activated or not.
$check_force_photo_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'force_photo'");

// check rating Profile Automatically is Activated or not.
$check_rate_profile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'rate_profile'");
// check Userplane Instant Messenger Mode is Activated or not.
$check_userplane_instant_messenger_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'userplane_instant_messenger'");
// check IM Recipient must be premium member Mode is Activated or not.
$check_recipient_premium_member_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'recipient_must_be_premium_member'");
// check Blog module must be premium member Mode is Activated or not.
$check_blog_module = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'blog_module'");
// check Picture Gallery module must be premium member Mode is Activated or not.
$check_picture_gallery_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'picture_gallery_module'");
// check Video module must be premium member Mode is Activated or not.
$check_video_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'video_module'");
// check Audio module must be premium member Mode is Activated or not.
$check_audio_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'audio_module'");
// check match_alert module must be premium member Mode is Activated or not.
$check_match_alert_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'match_alert'");
// check couples module must be premium member Mode is Activated or not.
$check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");
// check male module must be premium member Mode is Activated or not.
$check_male_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'male'");
// check female module must be premium member Mode is Activated or not.
$check_female_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'female'");
// check date tracker module must be premium member Mode is Activated or not.
$check_date_tracker_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'date_tracker'");
// check limit_profile module must be premium member Mode is Activated or not.
$check_limit_profile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'limit_profile'");
// check register_page_redirect module must be premium member Mode is Activated or not.
$check_register_page_redirect_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'register_page_redirect'");
// check mobile module must be premium member Mode is Activated or not.
$check_mobile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'mobile'");
// check guest_limit_profile module must be premium member Mode is Activated or not.
$check_guest_limit_profile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'guest_limit_profile'");
// check terms page  module must be premium member Mode is Activated or not.
$check_terms_page_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'terms_page'");
// check Gateways  module must be premium member Mode is Activated or not.
$check_gateways_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'gateways'");
// check chat Mode is Activated or not.
$check_chat_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'chat_mode'");
// check chat one Mode is Activated or not.
$check_chat_one_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'chat_one_mode'");
// check comments is Activated or not.
$check_comments_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'comments'");
// check astrological_signs is Activated or not.
$check_astrological_signs_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'astrological_signs'");
// check virtual_gifts is Activated or not.
$check_virtual_gifts_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'virtual_gifts'");
// check Skype Mode is Activated or not.
$check_skype_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'skype_mode'");
// check Zipcode Mode is Activated or not.
$check_zipcode_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'zipcode_mode'");
// check near me
$check_near_me = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'near_me'");
// check Spam Filter is Activated or not.
$check_spam_filter = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'spam_filter'");
// check Aprove Profile Automatically is Activated or not.
$check_approve_profile_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'authorize_profiles'");
// check Aprove Photos Automatically is Activated or not.
$check_approve_photos_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'authorize_photos'");
// check Aprove Audios Automatically is Activated or not.
$check_approve_audios_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'authorize_audios'");
// check Aprove Videos Automatically is Activated or not.
$check_approve_videos_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'authorize_videos'");
// check Aprove Comments Automatically is Activated or not.
$check_approve_comments_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'authorize_comments'");
// COUNT Number of Images in A Profiles.
$check_image_count = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'count_image'");
// COUNT Number of Audios in A Profiles.
$check_audio_count = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'count_audios'");
// COUNT Number of Videos in A Profiles.
$check_video_count = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'count_videos'");
// check Registerd user updated your dating Profile or not.
// check notification is Activated or not.
$check_notification_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'notification'");
// check notification_postition settings
$check_notification_postition_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'notification_postition'");
// check notification_time settings
$check_notification_time_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'notification_time'");
$check_meet_me_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'meet_me'");
$check_refresh_rate = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'refresh_rate'");
$check_pagination_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'pagination_color'");
$check_credit_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'credit'");
$check_tab_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'tab_color'");
$check_non_active_tab_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'non_active_tab_color'");
$check_button_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'button_color'");
$check_title_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'title_color'");
$check_search_result = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'search_result'");
$check_front_page_result = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'front_page_result'");
$check_happening_graph = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'happening_graph'");
$check_title_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'title_color'");
// get the start year
$check_start_year = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'start_dsp_year'");
// end start year
$check_end_year = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'end_dsp_year'");

$member_elements_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'home_page_elements'");

// get the register after redirect  url
$check_register_after_redirect_url = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'after_registration_redirect'");

// get the random online members setting
$check_online_member_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'random_online_members'");
// check Email Admin is Activated or not.
$check_email_admin = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'email_admin'");

// get the distance feature status
$check_distance_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'distance_feature'");
// check Member not logged in display option 
$check_member_not_logged_display_options = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'display_options'");

// get the default country
$check_default_country = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'default_country'");

// Recaptcha status
$check_recaptcha_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'recaptcha_option'");

// Google api key
$check_google_app_id = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'google_api_key'");
$check_google_secret_key = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'google_secret_key'");

// Facebook api key
$check_facebook_app_id = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'facebook_api_key'");
$check_facebook_secret_id = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'facebook_secret_key'");

// facebook_login Setting
$check_facebook_login = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'facebook_login'");

// Make private photo setting
$check_private_photo = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'private_photo'");

// Make discount code setting
$check_discount_code = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'discount_code'");

// get trending option setting
$check_trending_option = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'trending_status'");

// get password field setting
$check_password_option = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'password_option'");

// check setting for search form in home page
$check_search_from_option = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'search_form_options'");

// firstname and lastname field in registeration form Settings
$register_form_setting =  $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'register_form_setting'");

// display name in user profile section 
$display_user_name =  $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'display_user_name'");

$after_user_register =  $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'after_user_register_option'");

//license key setting
$check_license_key = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'license_key'");

//Use po for language translation
$use_po_file =$wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'po_language'");

//Google API key
$google_api_key_zip = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'google_api_key_zip'");