<?php

/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
// check My friends module is Activated or not.
$check_my_friend_module = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'my_friends'");
// check flirt(wink) module is Activated or not.
$check_flirt_module = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'flirt_module'");
// check Free Mode is Activated or not.
$check_free_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_mode'");
// check Free Trail Mode is Activated or not.
$check_free_trail_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_trail_mode'");
// check Free Trail Mode is Activated or not.
$check_free_trail_gender_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_trail_gender'");
// check Free Email Access Mode  is Activated or not.
$check_free_email_access_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_email_access'");
// check Free Email Access  Mode gender wise is Activated or not.
$check_free_email_access_gender_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_email_access_gender'");
// check Member List Gender Access  Mode is Activated or not.
$check_member_list_gender_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'member_list_gender'");
// check Force profile Access  Mode is Activated or not.
$check_force_profile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'force_profile'");
// check rating Profile Automatically is Activated or not.
$check_rate_profile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'rate_profile'");
// check Skype Mode is Activated or not.
$check_skype_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'skype_mode'");
// check Zipcode Mode is Activated or not.
$check_zipcode_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'zipcode_mode'");
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
// COUNT Number of Images in A Profiles.
$check_image_count = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'count_image'");
// COUNT Number of Audios in A Profiles.
$check_audio_count = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'count_audios'");
// COUNT Number of Videos in A Profiles.
$check_video_count = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'count_videos'");
// check Registerd user updated your dating Profile or not.
$check_user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id");

