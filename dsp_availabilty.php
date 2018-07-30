<?php

/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path
global $wpdb;
// TABLE NAMES
$dsp_profile_setup_table = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
$dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;
$dsp_user_photos_table = $wpdb->prefix . DSP_USER_PHOTOS_TABLE;
$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_user_emails_table = $wpdb->prefix . DSP_EMAILS_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_search_criteria_table = $wpdb->prefix . DSP_USER_SEARCH_CRITERIA_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_spam_filter_table = $wpdb->prefix . DSP_SPAM_FILTERS_TABLE;
$dsp_spam_words_table = $wpdb->prefix . DSP_SPAM_WORDS_TABLE;
$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
$dsp_flirt_table = $wpdb->prefix . DSP_FLIRT_TEXT_TABLE;
$dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_blocked_members_table = $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE;
$dsp_tmp_members_photos_table = $wpdb->prefix . DSP_TMP_MEMBERS_PHOTOS_TABLE;
$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
$dsp_tmp_galleries_photos_table = $wpdb->prefix . DSP_TMP_GALLERIES_PHOTOS_TABLE;
$dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
$dsp_member_audios = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
$dsp_tmp_member_audios_table = $wpdb->prefix . DSP_TEMP_MEMBER_AUDIOS_TABLE;
$dsp_tmp_member_videos_table = $wpdb->prefix . DSP_TEMP_MEMBER_VIDEOS_TABLE;
$dsp_member_videos = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
$dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;
$dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
$dsp_credits_table = $wpdb->prefix . DSP_CREDITS_TABLE;
$dsp_credits_purchase_history = $wpdb->prefix . DSP_CREDITS_PURCHASE_HISTORY_TABLE;
// TABLE NAMES
