<?php
global $wpdb;
$dsp_memberships                       = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
$dsp_user_profiles                     = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_general_settings                  = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_gateways                          = $wpdb->prefix . DSP_GATEWAYS_TABLE;
$dsp_email_templates                   = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_emails_table                      = $wpdb->prefix . DSP_EMAILS_TABLE;
$dsp_features_table                    = $wpdb->prefix . DSP_FEATURES_TABLE;
$dsp_state_table                       = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_country_table                     = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_city_table                        = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_my_friends_table                  = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_member_winks_table                = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
$dsp_user_search_criteria_table        = $wpdb->prefix . DSP_USER_SEARCH_CRITERIA_TABLE;
$dsp_emails_table                      = $wpdb->prefix . DSP_EMAILS_TABLE;
$dsp_user_Albums_table                 = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;
$dsp_member_audios_table               = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
$dsp_member_videos_table               = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
$dsp_language_table                    = $wpdb->prefix . DSP_LANGUAGE_TABLE;
$dsp_smiley                            = $wpdb->prefix . DSP_SMIILEY;
$dsp_comments_table                    = $wpdb->prefix . DSP_USER_COMMENTS;
$dsp_zipcode_table                     = $wpdb->prefix . DSP_ZIPCODES_TABLE;
$dsp_userplane_table                   = $wpdb->prefix . DSP_USERPLANE_TABLE;
$dsp_payments_table                    = $wpdb->prefix . DSP_PAYMENTS_TABLE;
$dsp_temp_payments_table               = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
$dsp_chat_request                      = $wpdb->prefix . DSP_CHAT_REQUEST_TABLE;
$dsp_chat_one_table                    = $wpdb->prefix . DSP_CHAT_ONE_TABLE;
$dsp_user_privacy_table                = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
$dsp_language_detail_table             = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
$dsp_user_notification_table           = $wpdb->prefix . DSP_USER_NOTIFICATION_TABLE;
$dsp_discount_codes_table              = $wpdb->prefix . DSP_DISCOUNT_CODES_TABLE;
$dsp_template_images_table             = $wpdb->prefix . DSP_TEMPLATE_IMAGES;
$dsp_gateways_table                    = $wpdb->prefix . DSP_GATEWAYS_TABLE;
$dsp_online_table                      = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_user_search_criteria_table        = $wpdb->prefix . DSP_USER_SEARCH_CRITERIA_TABLE;
$dsp_credits_table                     = $wpdb->prefix . DSP_CREDITS_TABLE;
$dsp_credits_usage_table               = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
$dsp_match_alert_email_sent_user_table = $wpdb->prefix . DSP_MATCH_ALERT_EMAIL_SENT_USER_TABLE;
$DSP_FIELD_TYPES_TABLE                 = $wpdb->prefix . DSP_FIELD_TYPES_TABLE;

$check_plugin_version = $wpdb->get_var( "SELECT setting_value FROM $dsp_general_settings WHERE setting_name = 'plugin_version'" );
$plugin_file          = ABSPATH . "wp-content/plugins/dsp_dating/dsp_dating.php";
$plugin_data          = get_plugin_data( $plugin_file, $markup = true, $translate = true );
$version              = $plugin_data['Version'];
if ( $version != $check_plugin_version ) {
	if ( $check_plugin_version == '4.8.1' || $check_plugin_version == '4.8' || $version == '4.8.2' || $version == '4.8.3' || $version == '4.8.3.1' || $version == '4.8.4' || $version == '4.8.4.1' || $version == '5.0' || $version == '5.1' || $version == '5.1.1' || $version == '5.1.2' || $version == '5.1.3' || $version == '5.1.3.1' || $version == '5.1.4' || $version == '5.2' || $version == '5.3' || $version == '5.4' || $version == '5.5' || $version == '5.6' || $version == '5.7' || $version == '5.8'  || $version == '5.9' || $version == '5.9.1'  || $version == '5.10'  || $version == '6.0.0' || $version == '6.1' || $version == '6.2' || $version == '6.3'|| $version == '6.3.1'|| $version == '6.4' || $version == '6.4.1' || $version == '6.4.2'|| $version == '6.4.3') {
		//add missing language if they doesn't exits in language table
		$tableNames  = $wpdb->get_results( "SELECT `table_name` FROM  $dsp_language_detail_table" );
		$setupValues = array(
			'DSP_SUBMENU_SETTINGS_MATCH_ALERTS'                      => 'Match Alerts',
			'DSP_USER_NAME_SHOULD_NOT_CONTAIN_SPACES'                => 'Username can not have spaces.',
			'DSP_PLEASE_ENTER_USER_NAME'                             => 'Please Enter Username.',
			'DSP_PLEASE_ENTER_EMAIL_ADDRESS'                         => 'Please Enter Email address.',
			'DSP_PLEASE_ENTER_CONFIRM_EMAIL_ADDRESS'                 => 'Please Enter Confirm email address.',
			'DSP_CHECK_TERM_CONDITIONS'                              => 'Please Check terms and conditions.',
			'DSP_ALERT'                                              => 'Alert',
			'DSP_MESSAGES'                                           => 'Messages',
			'DSP_EMPTY'                                              => 'Empty',
			'DSP_NO_RESULT_FOUND'                                    => 'No result found !',
			'DSP_JOIN_ITS_FREE'                                      => 'JOIN IT IS FREE!',
			'DSP_SEARCH_OUR_SINGLES'                                 => 'Search our singles',
			'DSP_HAPPY_STORIES'                                      => 'Happy Stories',
			'DSP_WELCOME_USER_NAME'                                  => 'Welcome User Name',
			'DSP_LATEST_BLOG'                                        => 'Latest Blog',
			'DSP_DAY'                                                => 'Days',
			'DSP_OFF'                                                => 'OFF',
			'DSP_ON'                                                 => 'ON',
			'DSP_FREE_MEMBER'                                        => 'Free Member',
			'DSP_QUESTION_DATE_TRACKER'                              => 'Are you sure you want to delete this user from your date tracker?',
			'DSP_DELETE_VIDEO'                                       => 'Deleted Video.',
			'DSP_NOT_PREMIUM_EMAIL_MESSAGE'                          => 'You must be a premium member to send an email',
			'DSP_STORIES_NOT_FOUND'                                  => 'No Stories Found',
			'DSP_VIRTUAL_GIFT_DELETED'                               => 'Virtual Gift has been Deleted',
			'DSP_VIRTUAL_GIFT_APPROVED'                              => 'Virtual Gift has been Approved',
			'DSP_MY_GIFTS'                                           => 'My Gifts',
			'DSP_MY_WINKS'                                           => 'My Winks',
			'DSP_ONLINE_MEMBER_TEXT'                                 => 'Online Members',
			'DSP_NO_IMAGE_UPLOADED'                                  => 'Empty Album id/No image uploaded',
			'DSP_OTHER_DETAILS'                                      => 'other details',
			'DSP_PAGE'                                               => 'Page',
			'DSP_READ_MORE'                                          => 'Read More',
			'DSP_USE_WEB_OR_ANDROID_APP_FOR_PAYMENT'                 => 'Use web or android app for payment',
			'DSP_NETWORK_PROBLEM'                                    => 'Network error has occurred please try again!',
			'DSP_FILL_IN_ALL_FIELDS'                                 => ' Fill in all the fields!',
			'DSP_ENTER_SITE_NAME'                                    => 'Enter site name',
			'DSP_PAYMENT_SUCCESS'                                    => 'Thankyou for the payment.',
			'DSP_PAYMENT_FAILED'                                     => ' Payment Failed! Try again later.',
			'DSP_USER_CANCELLED_PAYMENT'                             => 'You have cancelled the payment!',
			'DSP_UPLOAD_IMAGE_SIZE_ERROR'                            => 'Image size cannot be more than 5 MB!',
			'DSP_UPLOAD_VIDEO_SIZE_ERROR'                            => 'Video size cannot be more than 5 MB!',
			'DSP_APP_NOT_CONFIGURED'                                 => 'The app has not been configured yet!',
			'DSP_COUNTRY_EMPTY_ERROR'                                => ' Country field is required!',
			'DSP_ABOUT_ME_EMPTY_ERROR'                               => 'About me field is required!',
			'DSP_TITLE_EMPTY_ERROR'                                  => 'Title field is required!',
			'DSP_CONTENT_EMPTY_ERROR'                                => 'Content field is required!',
			'DSP_CONFIRM_DELETE'                                     => 'Are you sure you want to perform delete action ?',
			'DSP_REGISTER_AFTER_REDIRECT_MODULE'                     => 'After Register Redirect',
			'DSP_SELECT_AFTER_REGISTER_REDIRECT_TEXT'                => 'Select ON to activate the After Register Redirect url mode.',
			'DSP_ADMIN_EMAIL'                                        => 'Register Email To Admin',
			'DSP_ADMIN_EMAIL_NOTIFICATION_TEXT'                      => 'Select ON to Send Email to Admin after New User Registration',
			'DSP_DISPLAY_OPTIONS'                                    => 'Display options',
			'DSP_MEMBERS_UP'                                         => 'Member list up, Tab down',
			'DSP_TABS_UP'                                            => 'Tab up, Member list down',
			'DSP_TAB_ONLY'                                           => 'Tab only',
			'DSP_SELECT_LAYOUT_TO_DISPLAY_MEMBERS'                   => 'Select which layouts to show in members not loggedin page',
			'DSP_STORIES_NOT_FOUND'                                  => 'No Story Found',
			'DSP_ENTERED_WRONG_CAPTCHA'                              => 'Captcha did not match',
			'DSP_VIEW_GIFT'                                          => 'View Gift',
			'DSP_NO_VIRTUAL_GIFTS'                                   => 'No Virtual Gifts',
			'DSP_DAY_MON'                                            => 'Mon',
			'DSP_DAY_TUE'                                            => 'Tue',
			'DSP_DAY_WED'                                            => 'Wed',
			'DSP_DAY_THU'                                            => 'Thu',
			'DSP_DAY_FRI'                                            => 'Fri',
			'DSP_DAY_SAT'                                            => 'Sat',
			'DSP_DAY_SUN'                                            => 'Sun',
			'DSP_ACTION'                                             => 'Actions',
			'DSP_UNBLOCK'                                            => 'Unblock',
			'DSP_DISPLAY_STATUS'                                     => 'Show Into Advanced Search',
			'DSP_PREMIUM_MEMBERSHIP_EXPIRATION_EMAIL'                => 'Premium membership expiration',
			'DSP_PAYMENT_SUCCESSFULL_EMAIL'                          => 'Payment Successful',
			'DSP_PAYMENT_FAILED_EMAIL'                               => 'Payment Failed',
			'DSP_PAYMENT_CANCELED_EMAIL'                             => 'Payment Caceled',
			'DSP_CREDIT_BALANCE_LOW_EMAIL'                           => 'Credit balance low',
			'DSP_CREDIT_PURCHASE_EMAIL'                              => 'Credit Purchase',
			'DSP_MEET_ME_EMAIL'                                      => 'Meet me',
			'DSP_RESET_PASSWORD_EMAIL'                               => 'Reset password',
			'DSP_CANT_SEND_WINK_MSG'                                 => 'Alert Member Notifications-> You can&rsquo;t send wink message to this Member.',
			'DSP_CANT_SEND_MEET_ME_MSG'                              => 'Alert Member Notifications-> You can&rsquo;t send   meet me message to thiMember.',
			'DSP_CANT_SEND_RESET_PASSWORD_MSG'                       => 'Alert Email Notifications-> We can&rsquo;t send   you a reset password beacause of your notification setting for reset password is NO.',
			'DSP_ADD_NEW_DISCOUNT'                                   => 'Add New Discount',
			'DSP_NAME'                                               => 'Name',
			'DSP_DISCOUNT_DESCRIPTION'                               => 'Description',
			'DSP_DISCOUNT_CODE'                                      => 'Code',
			'DSP_DISCOUNT_TYPE'                                      => 'Type',
			'DSP_DISCOUNT_AMOUNT'                                    => 'Amount',
			'DSP_STATUS'                                             => 'status',
			'DSP_DISCOUNT_USES'                                      => 'Uses',
			'DSP_DISCOUNT_CODES'                                     => 'Discount Codes',
			'DSP_DISCOUNT_CODE_OPTIONS'                              => 'Discount Codes options',
			'DSP_ENTER_YOUR_DISCOUNT_CODE'                           => 'Enter Your Discount Code',
			'DSP_TOTAL_AMOUNT'                                       => 'Amount',
			'DSP_DISCOUNT_PRICE'                                     => 'Discount',
			'DSP_TOTAL_AMOUNT_AFTER_DISCOUNT'                        => 'Amount After Discount',
			'DSP_COUPAN_USED'                                        => 'Coupan already used',
			'DSP_WRONG_COUPAN_CODE'                                  => 'Wrong Coupan Code Entered',
			'DSP_USER_LISTS_BASED_ON_JOINED_DATE'                    => 'Profile By Registered Date',
			'DSP_DATEFIELD_EMPTY'                                    => 'Start and End Date not selected',
			'DSP_BACK_TO_SEARCH_RESULT'                              => 'Back to search results',
			'DSP_SELECT_FREE_MEMBER_GENDER_MODE_TEXT'                => 'Select which type of member got free this site',
			'DSP_NO_COMMENT'                                         => 'No Comments',
			'DSP_NO_VIRTUAL_GIFTS'                                   => 'No Virtual Gifts',
			'DSP_VIEW_GIFT'                                          => 'View Gift',
			'DSP_VIEW_WINK'                                          => 'View Wink',
			'DSP_TEMPLATE_IMAGES'                                    => 'Template Images',
			'DSP_UPLOAD_TEMPLATE_IMAGES'                             => 'Upload Template Images',
			'DSP_IMAGE_CAPTION'                                      => 'Caption',
			'DSP_SELECT_MALE_TEXT'                                   => 'Select ON to activate the male mode.',
			'DSP_SELECT_FEMALE_TEXT'                                 => 'Select ON to activate the female mode.',
			'DSP_SEND_SKYPE'                                         => 'Send Skype',
			'DSP_COMMENT_ON_PROFILE_TEXT_MESSAGE'                    => 'The following user has commented on your profile. Please check the comments',
			'DSP_ENABLE_HOME_ELEMENTS_TEXT_MESSAGE'                  => 'To enable elements of home page elements. Please check the checkbox',
			'DSP_INVALID_ZIPCODE'                                    => 'Invalid zip code',
			'DSP_PLEASE_ENTER_ZIP_CODE'                              => 'Please Enter zipcode.',
			'DSP_ENABLE_HOME_PAGE_ELEMENTS'                          => 'Enable Home page elements',
			'DSP_SELECT_ONLINE_MEMBERS'                              => 'Random Online Members',
			'DSP_SELECT_ONLINE_MEMBERS_VIEW_TEXT'                    => 'Select ON to activate the random online members mode.',
			'DSP_NO_OF_RANDOM_ONLINE_MEMBERS_VIEW'                   => 'No. of members',
			'DSP_EXCEEDS_NO_OF_ONLINE_MEMBERS'                       => 'Please enter less than 10 numbers for random online members',
			'DSP_USERS_STATISTICS'                                   => 'Users Statistics',
			'DSP_USERS_BY_COUNTRY'                                   => 'Members By Country',
			'DSP_ALL_USERS_STATISTICS'                               => 'All User Statistics',
			'DSP_START_DATE'                                         => 'Start Date',
			'DSP_END_DATE'                                           => 'End Date',
			'DSP_GIFTS_PER_CREDIT'                                   => 'Gifts Per Credit',
			'DSP_GIFTS_PER_CREDIT_TEXT'                              => 'This is the number of credits required to send per gift.',
			'DSP_CREDIT_PER_DOLLOR'                                  => 'Credit Per $',
			'DSP_CREDIT_PER_PRICE'                                   => 'Credit Per Price',
			'DSP_CREDIT_PER_PRICE_TEXT'                              => 'This is the credit per price. Do not use the currency sign. Just put the number',
			'DSP_SELECT_DISTANCE'                                    => 'Distance',
			'DSP_UNIT'                                               => 'Unit',
			'DSP_SELECT_UNIT'                                        => 'Select Unit',
			'DSP_MILES'                                              => 'Miles',
			'DSP_KM'                                                 => 'Km',
			'DSP_DISCOUNT_NAME_TEXT'                                 => 'please enter discount name.',
			'DSP_DISCOUNT_CODE_TEXT'                                 => 'please enter discount code.',
			'DSP_DISCOUNT_TYPE_TEXT'                                 => 'please enter discount type.',
			'DSP_DISCOUNT_DESCRIPTION_TEXT'                          => 'please enter description.',
			'DSP_DISCOUNT_AMOUNT_TEXT'                               => 'please choose discount amount.',
			'DSP_SEARCH_BY_PLACE_ZIPCODE_COUNTRY'                    => 'Place\Zipcode\Country',
			'DSP_PLACE'                                              => 'Place',
			'DSP_EDIT_MY_LOCATION'                                   => 'Edit Current Location',
			'DSP_DISTANCE_SEARCH'                                    => 'Distance Search',
			'DSP_SELECT_DISTANCE_FEATURES'                           => 'Distance Feature',
			'DSP_SELECT_DISTANCE_FEATURES_TEXT'                      => ' Select ON to activate Distance Modules.',
			'DSP_PROFILE_EXISTS_TO_USE_FEATURES_MESSAGE'             => 'You must create your profile before you can use this',
			'DSP_GATEWAYS_UPDATED_MESSAGE'                           => 'New Gateways Updated!!!!',
			'DSP_INSTRUCTION'                                        => 'Instruction',
			'DSP_UPGRADE_BANK_WIRE'                                  => 'Upgrade / Bank wire',
			'DSP_UPGRADE_CHEQUE_PAYMENT'                             => 'Upgrade / Cheque payment',
			'DSP_BANK_CHEQUE_USERS'                                  => 'Bank & Cheque Users',
			'DSP_BANK_CHEQUE_USERS'                                  => 'Bank & Cheque Users',
			'DSP_PLAN_AMOUNT'                                        => 'Plan Amount',
			'DSP_PLAN_DAYS'                                          => 'Plan Days',
			'DSP_PLAN_NAME'                                          => 'Plan Name',
			'DSP_PAYMENT_COMPLETED'                                  => 'Completed',
			'DSP_SECURITY_VILOATION'                                 => 'Security Viloation',
			'DSP_EDIT_THIS_ONLY'                                     => 'Edit This Only',
			'DSP_SELECT_TERM_TEXT'                                   => 'Select ON to activate the Terms of Service mode.',
			'DSP_NEW_DISCOUNT_CODE_ADDED'                            => 'New Discount code added!!!!',
			'DSP_NEW_DISCOUNT_CODE_UPDATED'                          => 'Discount code updated!!!!',
			'DSP_CHAT_WIDGET'                                        => 'A chat widget.',
			'DSP_ONLINE_MEMBER_WIDGET_DESCRIPTION'                   => 'A Online Members widget that displays dsp dating online members.',
			'DSP_SELECT_DEFAULT_COUNTRY'                             => 'Default Country',
			'DSP_TEXT_ENABLE_TRENDING_FEATURE'                       => 'Enable Trending Feature',
			'DSP_OTHER_SETTING'                                      => 'Other Settings',
			'DSP_START_DSP_YEAR'                                     => 'Start DSP year',
			'DSP_START_YEAR'                                         => 'Start Year',
			'DSP_ENABLE_RECAPCHA_MODE'                               => 'Enable Recapcha',
			'DSP_ENABLE_RECAPCHA_TEXT'                               => 'Select ON to enable Recaptcha ',
			'DSP_DISTANCE_MODE_TEXT'                                 => 'Select ON to enable distance ',
			'DSP_SELECT_DEFAULT_COUNTRY_TEXT'                        => 'Select default country',
			'DSP_HOME_PAGE_ELEMENTS'                                 => 'Home page Elements',
			'DSP_PERCENTAGE'                                         => '%',
			'DSP_DOLLOR'                                             => '$',
			'DSP_CHOOSE_CAPTION_MESSAGE'                             => 'please choose caption.',
			'DSP_CHOOSE_STATUS_MESSAGE'                              => 'please choose status',
			'DSP_CHOOSE_IMAGE_FILE_MESSAGE'                          => 'please choose image file.',
			'DSP_GOOGLE_RECAPTCHA_SETTING'                           => 'Google Recaptcha Setting',
			'DSP_GOOGLE_APP_ID_TEXT'                                 => 'Enter your Google APP Id here',
			'DSP_TOTAL_INTREST'                                      => 'Total Intrest',
			'DSP_REGISTERED_USERS'                                   => 'Registered Users',
			'DSP_DATES_TRACKED'                                      => 'Dates Tracked',
			'DSP_USER_STATISTICS'                                    => 'All User Statistics',
			'DSP_FACEBOOK_LOGIN_SETTING'                             => 'Facebook Login Setting',
			'DSP_APP_ID'                                             => 'APP Id',
			'DSP_FACEBOOK_APP_ID_TEXT'                               => 'Enter your facebook APP id here',
			'DSP_SECRET_ID'                                          => 'Secret Key',
			'DSP_FACEBOOK_SECRET_ID_TEXT'                            => 'Enter your facebook secret key here',
			'DSP_FACEBOOK_SECRET_ID_TEXT'                            => 'Enter your facebook secret key here',
			'DSP_FACEBOOK_LOGIN'                                     => 'Facebook Login',
			'DSP_ENABLE_MAKE_PRIVATE_OPTION_TEXT'                    => 'Enable Make Private Option in Edit Profile Section',
			'DSP_FACEBOOK_LOGIN_TEXT'                                => 'Enable Facebook Login module.For more info how to set up Facebook Api ',
			'DSP_PROFILE_QUESTIONS_ADDED'                            => 'New profile Question added!',
			'DSP_PROFILE_QUESTIONS_UPDATED'                          => 'Profile Question updated!',
			'DSP_TEMPLATE_IMAGE_INFO_TEXT'                           => 'Please upload template images of following sizes for template1(566px*361px),template2(950px*694px),template3(950px*413px),template4(950px*457px),template5(950px*482px),template6(525px*418px),template7(950px*457px)',
			'DSP_DISCOUNT_CODES_TEXT'                                => 'Select ON to enable Discount coupan code option',
			'DSP_CHOOSE_YOUR_BANK'                                   => 'Choose your bank:',
			'DSP_IDEAL_SUCCESSFUL_STATUS'                            => 'Status was Successful...Thank you for your order',
			'DSP_OK'                                                 => 'OK.',
			'DSP_IP_ADDRESS_INCORRECT'                               => 'IP address not correct... This call is not from Targetpay',
			'DSP_COULDNOT_FETCH_RESPONSE'                            => 'Could not fetch response',
			'DSP_NEW_MEMBERSHIP_ADDED'                               => 'New Membership added!!!!',
			'DSP_NEW_MEMBERSHIP_UPDATED'                             => 'Membership updated!!!!',
			'DSP_COPY_UNSUCCESSFUL'                                  => 'Copy unsuccessfull!',
			'DSP_DISCOUNT_COUPON_CODE_TEXT'                          => 'If you don\'t have coupon code,Please leave it blank',
			'DSP_API_KEY_LINK'                                       => 'Click Here To Get API Key',
			'DSP_COMMENT_SUCCESSFUL_TEXT'                            => 'The comment has been successful.It will be shown after approval of this user.',
			'DSP_GOOGLE_SECRET_ID_TEXT'                              => 'Enter your Google secret key here',
			'DSP_SEND_WINK_SUCCESS'                                  => 'Wink Sent Sucessfully.',
			'DSP_RATE_SUCCESS'                                       => 'You have rated this user successfully. Thank you !',
			'DSP_USER_NAME_ALREADY_EXIST'                            => 'Username already exists',
			'DSP_EMAIL_ALREADY_EXIST'                                => 'Email already exists',
			'DSP_SITE_KEY'                                           => 'Site Key',
			'DSP_GOOGLE_SITE_KEY_TEXT'                               => 'Enter your Google Site Key here',
			'DSP_CREDIT_PER_EMAILS_TEXT'                             => 'This is the number of credits required to send per email.',
			'DSP_CREDIT_PER_GIFTS_TEXT'                              => 'This is the number of credits required to send per gifts.',
			'DSP_SELECT_PASSWORD_FIELD_TEXT'                         => 'Select ON to enable password field  option  in registration page',
			'DSP_SELECT_TRENDING_TEXT'                               => 'Select ON to enable trending sub-menu in extra menu',
			'DSP_EMPTY_ALBUMS'                                       => 'No Image uploaded by this user',
			'DSP_SEARCH_FORM_TYPE'                                   => 'Search Form Type',
			'DSP_SEARCH_FORM_TYPE_TEXT'                              => 'Select search form type in home page',
			'DSP_SEARCH_BY_LOCATION'                                 => 'Search form with country list',
			'DSP_SEARCH_BY_GEOGRAPHY'                                => 'Search form with geography',
			'DSP_NONE'                                               => 'None',
			'DSP_FIRST_NAME_SHOULD_NO_BE_EMPTY'                      => 'First name field should not be empty.',
			'DSP_FIRST_NAME_SHOULD_NO_BE_EMPTY'                      => 'First name field should not be empty.',
			'DSP_ENABLE_REGISTRATION_FIRSTNAME_USERNAME_OPTION'      => 'Enable Firstname & Lastname Field',
			'DSP_ENABLE_REGISTRATION_FIRSTNAME_USERNAME_OPTION_TEXT' => 'Select ON to enable firstname & lastname field in Registration form',
			'DSP_FRIEND_REQUEST_SUCCESS_MSG_FRIEND'                  => 'You are now friend with this user',
			'DSP_FULLNAME'                                           => 'Fullname',
			'DSP_USER_PROFILE_DISPLAY_NAME'                          => 'User Profile Display Name',
			'DSP_USER_PROFILE_DISPLAY_NAME_TEXT'                     => 'Select option to display user profile display name',
			'DSP_DELETE_MESSAGE_SUCCESS'                             => 'Message deleted Sucessfully.',
			'DSP_AFTER_USER_REGISTER'                                => 'After User Register',
			'DSP_AUTO_LOGIN'                                         => 'Auto Login',
			'DSP_EMAIL_VERIFICATION'                                 => 'Email Verification',
			'DSP_AFTER_USER_REGISTER_TEXT'                           => 'Select option after user register.Note: You must have dsp_add_on',
			'DSP_NO_ONE_VIEW_YOUR_PROFILE'                           => 'Noone view your profile till now',
			'DSP_CURRENT_STATUS'                                     => 'Current Status :',
			'DSP_END_YEAR'                                           => 'End Year',
			'DSP_TOOLS_IMPORT_LANGUAGE_PACK'                         => 'Import Language Pack',
			'DSP_END_YEAR'                                           => 'End Year',
			'DSP_END_YEAR_TEXT'                                      => 'End DSP Year',
			'DSP_IMPORT_LANGUAGE_INFO'                               => 'Select language that pack needed to import',
			'DSP_LANGUAGE_PACK_INFO'                                 => 'Select language pack need to import',
			'DSP_LANGUAGE_PACK'                                      => 'Language Pack',
			'DSP_LANGUAGE_DELETE_TEXT'                               => 'You have to first delete <#LANGNAME#> language to import this language pack',
			'DSP_LICENSE_KEY'                                        => 'License Key',
			'DSP_LICENSE_KEY_TEXT'                                   => 'Enter a valid license key of wpdating product',
			'DSP_SPAM_FILTER_ERROR'                                  => 'Either you entered only spam words or no <#name#> entered',
			'DSP_LICENSE_NOTIFICATION'                               => 'Please make sure,you have configured your license key correctly to get rid of this message.',
			'DSP_SETTINGS_HEADER_LICENSE_ACTIVATE'                   => 'Activate License',
			'DSP_LICENSE_ACTIVATE_SUCCESSFULLY'                      => 'License Activated Successfully',
			'DSP_SEARCH_AND_CHAT'                                    => 'Search and Chat',
			'DSP_FEATURES'                                           => 'Features',
			'DSP_CUSTOMIZE'                                          => 'Customize',
			'DSP_ACCOUNTS_AND_PAYMENT'                               => 'Accounts and Payment',
			'DSP_ADDONS'                                             => 'Addons',
			'DSP_BASIC_SEARCH'                                       => 'Basic Search',
			'DSP_ADVANCED_SEARCH'                                    => 'Advanced Search',
			'DSP_UPGRADE_ACCOUNT'                                    => 'Upgrade Account',
			'DSP_EMPTY_CHAT'                                         => 'Please enter a message',
			'DSP_FORGOT_PASSWORD'                                    => 'Forgot Password?',
			'DSP_ALBUM_NAME'                                         => 'Album name',
			'DSP_PHOTO'                                              => 'Photos',
			'DSP_MY_ALBUM'                                           => 'My Album',
			'DSP_LOGOUT'                                             => 'Logout',
			'DSP_REPORT'                                             => 'Report',
			'DSP_REASON'                                             => 'Please write us your reason:',
			'DSP_REPORT_THIS_COMMENT'                                => 'Report this comment',
			'DSP_PLEASE_ENTER_REASON'                                => 'Please enter the reason for report.',
			'DSP_REPORT_SENT_SUCCESSFULLY'                           => 'Your report was successfully submitted.',
			'DSP_REPORT_NOT_SENT'                                    => 'Your report was not submitted.',
			'DSP_REPORTED_COMMENT'                                   => 'Reported Comment',
			'DSP_IGNORE'                                             => 'Ignore',
			'DSP_COMMENT'                                            => 'Comment',
			'DSP_REPORTED_BY'                                        => 'Reported by',
			'DSP_REASONS'                                            => 'Reason',
			'DSP_SETTINGS_HEADER_FEATURED_MEMBER'                    => 'Featured Member',
			'DSP_SEARCH_USER'                                        => 'Search User',
			'DSP_FEATURED_MEMBERS'                                   => 'Featured Members',
			'DSP_CONTACT_PERMISSIONS'                                => 'Contact Permissions (Who can contact me?)',
			'DSP_WELCOME'                                            => 'Welcome',
			'DSP_NEW_MEMBERS'                                        => 'New Members',
			'DSP_PRICE'                                              => 'Price',
			'DSP_PREMIUM_DELETE_NOTE'                                => 'Please Note: If a membership plan is being used you cannot delete that Membership Plan.',
			'DSP_ORDER'                                              => 'Order',
			'DSP_PROFILE_PICTURE'                                    => 'Profile Picture',
			'DSP_AGE'                                                => 'Age',
			'DSP_GENDER'                                             => 'Gender',
			'DSP_LOGIN_ID'                                           => 'Login ID',
			'DSP_MOVE'                                               => 'Move',
			'DSP_ALL'                                                => 'All',
			'DSP_EMAIL_UNREAD'                                       => 'Email is Unread',
			'DSP_EMAIL_READ'                                         => 'Email has been Read',
			'DSP_SAVE'                                               => 'Save',
			'DSP_PREVIOUS'                                           => 'Previous',
			'DSP_NEXT'                                               => 'Next',
			'DSP_JS_VALIDATION_DEFALT_MESSAGE'                       => 'This value seems to be invalid.',
			'DSP_JS_VALIDATION_EMAIL'                                => 'This value should be a valid email.',
			'DSP_JS_VALIDATION_URL'                                  => 'This value should be a valid url.',
			'DSP_JS_VALIDATION_VALID_NUMBER'                         => 'This value should be a valid number.',
			'DSP_JS_VALIDATION_VALID_INTEGER'                        => 'This value should be a valid integer.',
			'DSP_JS_VALIDATION_VALID_DIGITS'                         => 'This value should be digits.',
			'DSP_JS_VALIDATION_VALID_ALPHANUM'                       => 'This value should be alphanumeric.',
			'DSP_JS_VALIDATION_NOT_BLANK'                            => 'This value should not be blank.',
			'DSP_JS_VALIDATION_REQUIRED'                             => 'This value is required.',
			'DSP_JS_VALIDATION_PATTERN'                              => 'This value seems to be invalid.',
			'DSP_JS_VALIDATION_MIN'                                  => 'This value should be greater than or equal to %s.',
			'DSP_JS_VALIDATION_MAX'                                  => 'This value should be lower than or equal to %s.',
			'DSP_JS_VALIDATION_RANGE'                                => 'This value should be between %s and %s.',
			'DSP_JS_VALIDATION_MINLENGTH'                            => 'This value is too short. It should have %s characters or more.',
			'DSP_JS_VALIDATION_MAXLENGTH'                            => 'This value is too long. It should have %s characters or fewer.',
			'DSP_JS_VALIDATION_LENGTH'                               => 'This value length is invalid. It should be between %s and %s characters long.',
			'DSP_JS_VALIDATION_MINCHECK'                             => 'You must select at least %s choices.',
			'DSP_JS_VALIDATION_MAXCHECK'                             => 'You must select %s choices or fewer.',
			'DSP_JS_VALIDATION_CHECK'                                => 'You must select between %s and %s choices.',
			'DSP_JS_VALIDATION_EQUAL_TO'                             => 'This value should be the same.',
			'DSP_RECURRING_WARNING_USER'                             => 'This is a recurring payment plan. You will be subscribed to this plan until you cancel it from your paypal account.',
			'DSP_NO_OF_DAYS_RECURRING_WARNING'                       => 'Due to restrictions put on recurring plan, recurring payment gateway (such as Paypal Recurring - Subscriptions) can only be activated where the number of days satisfies the following conditions.',
			'DSP_NO_OF_DAYS_RECURRING_DAYS'                          => 'No of days is less than or equal to 90 with range (1 to 90)',
			'DSP_NO_OF_DAYS_RECURRING_WEEKS'                         => 'No of days is multiple of 7 with range (7 to 364).',
			'DSP_NO_OF_DAYS_RECURRING_MONTHS'                        => 'No of days is multiple of 30 with range (30 to 720).',
			'DSP_NO_OF_DAYS_RECURRING_YEARS'                         => 'No of days is multiple of 365 with range (365 to 1825).',
			'DSP_QUICK_ACTIONS'                                      => 'QUICK ACTIONS',
			'DSP_FREE_MEMBERSHIP_PLAN'                               => 'Make this membership plan free',
			'DSP_APPLY'                                              => 'Apply',
			'DSP_IS_ACTIVE'                                          => 'is activated',
			'DSP_PLAN'                                               => 'Plan',
			'DSP_PROFILE_MAKE_PRIVATE'                               => 'Make this profile questions private',
			'DSP_ONLY_OPTION'                                        => 'Only me',
			'DSP_PROFILE_ONLY_ME'                                    => 'You cannot view this profile',
			'DSP_UPGRADE_CCBILL'                                     => 'Upgrade/CCBill',
			'DSP_LIST_ALL'                                           => 'List All',
			'DSP_GOOGLE_API_KEY'                                     => 'Google Api Key',
			'DSP_REPORTED_TO'                                        => 'Reported To',
			'DSP_REPORT_USER'                                        => 'Report User',
			'DSP_NO_AUDIO_UPLOADED'                                  => 'The user has not uploaded any audios.',
			'DSP_NO_VIDEO_UPLOADED'                                  => 'The user has not uploaded any videos.',
            'DSP_NO_PICTURE_UPLOADED'                                => 'The user has not uploaded any photos.',
            'DSP_NEW_PASSWORD'                                       => 'New Password: ',
            'DSP_LIST_OF_ALBUM'                                      =>  'List Of Albums',
            'DSP_MENU_PHOTOS'                                        => 'Photos',
            'DSP_EMAIL_NOTIFICATION_TITLE'                           => 'Email Notification',
            'DSP_MEDIA_HEADER_ALBUMS'                                => 'Albums',
            'DSP_INBOX_MESSAGE'                                      => 'Inbox',
            'DSP_UNBLOCK_MEMBER_MESSAGE'                             => 'Are you sure you want to Unblock?',
            'DSP_MAKE_PRO_PIC_PRIVATE'                               => 'Make your pro pic Private',
            'DSP_SUBMENU_SEARCH_SAVED'                               => 'Saved Searches',
            'DSP_DATE_TRACKER_MESSAGE_UPDATED'                       => 'Do you want to save this user in your date tracking list?',
            'DSP_ACCEPT GIFTS'                                       => 'Accept This Gifts',
            'DSP_SENT_GIFT'                                          => 'Sent Gifts',
            'DSP_NEWS_SEND_MESSAGE'                                  => 'just sent you a message.',
		    'DSP_STEALTH_MODE_TITLE'                                 => 'Use the site being in the offline mode',
		    'DSP_SEND_MESSAGE_SUCCESSFULLY'                          => 'Message Sent Successfully.',
		    'DSP_MESSAGE_EMAIL_MODE'                                 => 'Enable Message',
		    'DSP_DELETE_MESSAGES'                                    => 'Are you sure you want to Delete this Message?',
            'DSP_HEADER_DASHBOARD'                                   => 'Dashboard',
            'DSP_START_NOW'                                          => 'Start now'
		);
		//'DSP_CHECK_COUPAN_CODE'=> 'Check'
		//echo "<pre>";var_dump($tableNames);die;
		foreach ( $tableNames as $k => $tableName ) {
			$table = $wpdb->prefix . $tableName->table_name;

			foreach ( $setupValues as $key => $value ) {
				$query = "SELECT `code_name` FROM $table WHERE code_name LIKE '$key'";
				if ( ! $wpdb->get_row( $query ) ) {
					$sql    = "INSERT IGNORE INTO " . $table . " (`code_name`, `text_name`) 
                                VALUES (%s, %s)";
					$values = array( $key, $value );
					$wpdb->query( $wpdb->prepare( $sql, $values ) );
				}
			}
		}

		$start_dsp_year = date( 'Y', mktime( 0, 0, 0, date( "m" ), date( "d" ), date( "Y" ) - 18 ) );
		$settingValues  = array(
			'start_dsp_year'              => array( 'Y', $start_dsp_year ),
			'end_dsp_year'                => array( 'Y', $start_dsp_year + 80 ),
			'after_registration_redirect' => array( 'N', 0 ),
			'email_admin'                 => array( 'Y', 0 ),
			'display_options'             => array( 'Y', 'tu' ),
			'default_match'               => array( 'Y', 'all' ),
			'random_online_members'       => array( 'Y', 3 ),
			'distance_feature'            => array( 'Y', 0 ),
			'default_country'             => array( 'Y', 0 ),
			'free_member'                 => array( 'Y', 3 ),
			'home_page_elements'          => array( 'Y', 'N,O,H,L' ),
			'recaptcha_option'            => array( 'Y', 0 ),
			'google_api_key'              => array( 'Y', '' ),
			'google_secret_key'           => array( 'Y', '' ),
			'facebook_api_key'            => array( 'Y', '' ),
			'facebook_secret_key'         => array( 'Y', '' ),
			'private_photo'               => array( 'Y', '' ),
			'facebook_login'              => array( 'Y', '' ),
			'discount_code'               => array( 'Y', '' ),
			'trending_status'             => array( 'Y', '' ),
			'search_form_options'         => array( 'Y', 'ow' ),
			'register_form_setting'       => array( 'Y', '' ),
			'after_user_register_option'  => array( 'Y', 'auto_login' ),
			'display_user_name'           => array( 'Y', 'username' ),
			'password_option'             => array( 'Y', '' ),
			'license_key'                 => array( 'Y', '' ),
			'po_language'                 => array( 'N', 0 ),
			'google_api_key_zip'          => array( 'Y', 0 )
		);

		foreach ( $settingValues as $key => $value ) {
			$sql    = "SELECT setting_value FROM $dsp_general_settings WHERE setting_name = %s ";
			$values = array( $key );
			if ( count( $wpdb->get_row( $wpdb->prepare( $sql, $values ) ) ) == 0 ) {
				$query = "INSERT INTO $dsp_general_settings SET setting_status='%s',";
				$query .= ( $key == 'display_options' ) ? " setting_value = %s," : " setting_value = %d,";
				$query .= " setting_name = %s ";
				$values = array( $value[0], $value[1], $key );
				$wpdb->query( $wpdb->prepare( $query, $values ) );
			}
		}


		// Email Template //

		$dsp_email_templates_table = $wpdb->prefix . 'dsp_email_templates';

		$insertTemplates = array(
			array(
				'mail_template_id'    => '23',
				'email_template_name' => 'Password Reset Successfully',
				'subject'             => 'Reset Password Confirmation',
				'email_body'          => 'Hi <#RECEIVER_NAME#>,<br>Your password has been reset successfully.',
			)
			// ,array(
			//     'mail_template_id' => '24',
			//     'email_template_name'=>'Place you template here',
			//     'subject'=>'Place you template here',
			//     'email_body'=> 'Place you template here',
			//     )
		);
		foreach ( $insertTemplates as $templates ) {
			$sql          = "SELECT COUNT(*)  FROM $dsp_email_templates_table WHERE email_template_name = %s ";
			$values       = array( $templates['email_template_name'] );
			$alreadyExist = $wpdb->get_var( $wpdb->prepare( $sql, $values ) ) == 0 ? false : true;

			$query = "INSERT INTO $dsp_email_templates_table SET ";
			foreach ( $templates as $column => $value ) {
				$query .= " `$column` = '$value',";

			}
			$query = rtrim( $query, "," );

			! $alreadyExist ? $wpdb->query( $query ) : '';


		}
		// End email template//

		//add contact permission field if that doesn't exists in databse
		$sql = "SELECT * FROM information_schema.COLUMNS 
                  WHERE TABLE_NAME='$dsp_user_privacy_table' 
                      AND TABLE_SCHEMA='" . DB_NAME . "' 
                      AND COLUMN_NAME ='contact_permission'";
		$wpdb->get_results( $sql );
		if ( $wpdb->num_rows < 1 ) {
			$wpdb->query( "ALTER TABLE $dsp_user_privacy_table ADD COLUMN contact_permission TEXT NOT NULL" );
		}

		$checkColumns = array(
			'trending_status',
		);
		foreach ( $checkColumns as $column ) {
			$result = $wpdb->query( "SHOW COLUMNS FROM `$dsp_user_privacy_table` LIKE '$column'" );
			if ( ! $result ) {
				$sql = "ALTER IGNORE TABLE `$dsp_user_privacy_table` 
                         ADD `$column` ENUM( 'Y', 'N' ) NOT NULL AFTER `view_my_video`";
				$wpdb->query( $sql );
			}
		}

		$checkColumns = array(
			'username',
		);
		foreach ( $checkColumns as $column ) {
			$result = $wpdb->query( "SHOW COLUMNS FROM `$dsp_user_search_criteria_table` LIKE '$column'" );
			if ( ! $result ) {
				$sql = "ALTER IGNORE TABLE `$dsp_user_search_criteria_table` 
                         ADD `$column` varchar(50) DEFAULT NULL AFTER `user_id`";
				$wpdb->query( $sql );
			}
		}

		$checkColumns = array(
			'gifts_per_credit',
		);
		foreach ( $checkColumns as $column ) {
			$result = $wpdb->query( "SHOW COLUMNS FROM `$dsp_credits_table` LIKE '$column'" );
			if ( ! $result ) {
				$sql = "ALTER IGNORE TABLE `$dsp_credits_table` 
                         ADD `$column` int(11) NOT NULL ";
				$wpdb->query( $sql );
				$sql = "INSERT INTO `$dsp_credits_table` (`price_per_credit`, `emails_per_credit`, `credits_purchased`, `credit_used`, `gifts_per_credit`) VALUES
                  (0,2,0,0,2)";
				$wpdb->query( $sql );
			}
		}

		$checkColumns = array(
			'no_of_gifts',
			'gift_sent'
		);
		foreach ( $checkColumns as $column ) {
			$result = $wpdb->query( "SHOW COLUMNS FROM `$dsp_credits_usage_table` LIKE '$column'" );
			if ( ! $result ) {
				$sql = "ALTER IGNORE TABLE `$dsp_credits_usage_table` 
                         ADD `$column` int(11) NOT NULL ";
				$wpdb->query( $sql );
			}
		}

		// Adding lat & lng columns in dsp_user_profiles table

		$checkColumns = array(
			'lat'                      => 'DOUBLE',
			'lng'                      => 'DOUBLE',
			'featured_member'          => 'INT',
			'featured_expiration_date' => 'DATE',
			'android_device'           => 'TEXT',
			'ios_device'               => 'TEXT'

		);
		//$prev = '';
		foreach ( $checkColumns as $k => $type ) {
			$result = $wpdb->query( "SHOW COLUMNS FROM `$dsp_user_profiles` LIKE '$k'" );

			if ( ! $result ) {
				$sql = "ALTER IGNORE TABLE `$dsp_user_profiles` 
                        ADD `" . $k . "` $type NOT NULL ";
				$wpdb->query( $sql );

			}
			$prev = $k;
		}
		$sql = "ALTER TABLE `$dsp_user_profiles` ADD `make_private_profile` int(11)";
		$wpdb->query( $sql );

		// adding columns into payment gateways

		$checkColumns = array(
			'title'       => 'VARCHAR (30)',
			'instruction' => 'TEXT',
			'description' => 'TEXT'
		);


		foreach ( $checkColumns as $k => $type ) {
			$result = $wpdb->query( "SHOW COLUMNS FROM `$dsp_gateways` LIKE '$k'" );

			if ( ! $result ) {
				$sql = "ALTER IGNORE TABLE `$dsp_gateways` 
                        ADD `" . $k . "` $type";
				$wpdb->query( $sql );
			}
		}

		// Adding gateway id columns in dsp_temp_payments table

		$checkColumns = array( 'gateway_id' );
		foreach ( $checkColumns as $column ) {
			$result = $wpdb->query( "SHOW COLUMNS FROM `$dsp_temp_payments_table` LIKE '$column'" );
			if ( ! $result ) {
				$sql = "ALTER IGNORE TABLE `$dsp_temp_payments_table` 
                        ADD `$column` INT NOT NULL AFTER `plan_id`";
				$wpdb->query( $sql );
			}
		}

		$checkColumns = array( 'is_random' );
		foreach ( $checkColumns as $column ) {
			$result = $wpdb->query( "SHOW COLUMNS FROM `$dsp_online_table` LIKE '$column'" );
			if ( ! $result ) {
				$sql = "ALTER IGNORE TABLE `$dsp_online_table` 
                         ADD `$column` TINYINT( 11 ) NOT NULL AFTER `time`";
				$wpdb->query( $sql );
			}
		}
		$checkColumns = array(
			'premium_member_expiration',
			'payment_successful',
			'payment_failed',
			'payment_canceled',
			'credit_balance_low',
			'credit_purchase',
			'meet_me',
			'reset_password',
			'wink'
		);

		foreach ( $checkColumns as $column ) {
			$result = $wpdb->query( "SHOW COLUMNS FROM `$dsp_user_notification_table` LIKE '$column'" );
			if ( ! $result ) {
				$sql = "ALTER IGNORE TABLE `$dsp_user_notification_table` 
                         ADD `$column` ENUM( 'Y', 'N' ) NOT NULL";
				$wpdb->query( $sql );
			}
		}


		######## Adding Bank wire and cheque transfer gateways ########
		$insertGataways = array(
			array(
				'gateway_name' => 'bank_wire',
				'display_name' => 'Bank Wire',
				'status'       => 0,
			),
			array(
				'gateway_name' => 'cheque_payment',
				'display_name' => 'Cheque payment',
				'status'       => 0,
			)
		);
		foreach ( $insertGataways as $gateways ) {
			$sql          = "SELECT COUNT(*)  FROM $dsp_gateways_table WHERE gateway_name = %s ";
			$values       = array( $gateways['gateway_name'] );
			$alreadyExist = $wpdb->get_var( $wpdb->prepare( $sql, $values ) ) == 0 ? false : true;
			$query        = "INSERT INTO $dsp_gateways_table SET ";
			foreach ( $gateways as $column => $value ) {
				$query .= " `$column` = '$value',";

			}
			$query = rtrim( $query, "," );
			! $alreadyExist ? $wpdb->query( $query ) : '';
		}

		//////////// create dsp_discount_codes table///////////////////////

		if ( $wpdb->get_var( "show tables like '$dsp_discount_codes_table'" ) != $dsp_discount_codes_table ) {
			$wpdb->query( "CREATE TABLE IF NOT EXISTS `$dsp_discount_codes_table` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `description` text NOT NULL,
                `code` varchar(256) NOT NULL,
                `amount` float NOT NULL,
                `type` varchar(255) NOT NULL,
                `status` varchar(255) NOT NULL,
                `uses` int(11) NOT NULL,
                PRIMARY KEY (`id`)
             ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" );
		}

		//////////// create dsp_template_images table///////////////////////

		if ( $wpdb->get_var( "show tables like '$dsp_template_images_table'" ) != $dsp_discount_codes_table ) {
			$wpdb->query( "CREATE TABLE IF NOT EXISTS `$dsp_template_images_table` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `caption` text NOT NULL,
            `template_image` varchar(100) NOT NULL,
            `url` varchar(255) NOT NULL,
            `file_type` varchar(255) NOT NULL,
            `display_status` enum('Y','N') NOT NULL,
             PRIMARY KEY (`id`)
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" );
		}

		//////////// create dsp_match_alert_email_sent_user///////////////////////

		if ( $wpdb->get_var( "show tables like '$dsp_match_alert_email_sent_user_table'" ) != $dsp_match_alert_email_sent_user_table ) {
			$wpdb->query( "CREATE TABLE IF NOT EXISTS `$dsp_match_alert_email_sent_user_table` (
                        `id` int(11) NOT NULL,
                        `match_id` int(11) NOT NULL,
                        `user_id` int(11) NOT NULL ,
                         PRIMARY KEY (`id`),
                         INDEX match_key (user_id, match_id)
                     ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" );
		}

		/* Create dsp_paypal_recurring table */
		$dsp_paypal_recurring = $wpdb->prefix . DSP_PAYPAL_RECURRING;
		if ( $wpdb->get_var( "show tables like '$dsp_paypal_recurring'" ) != $dsp_paypal_recurring ) {
			$wpdb->query( "CREATE TABLE IF NOT EXISTS $dsp_paypal_recurring (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int(11) NOT NULL,
                    `membership_id` int(11) NOT NULL,
                    `item_name` varchar(200) NOT NULL,
                    `item_number` varchar(200) NOT NULL,
                    `subscr_id` varchar(20) NOT NULL,
                    `first_name` varchar(50) NOT NULL,
                    `last_name` varchar(50) NOT NULL,
                    `residence_country` varchar(10) NOT NULL,
                    `payer_email` varchar(100) NOT NULL,
                    `payer_id` varchar(20) NOT NULL,
                    `subscr_date` varchar(50) DEFAULT NULL,
                    `payment_date` varchar(50) DEFAULT NULL,
                    `payment_status` varchar(20) DEFAULT NULL,
                    `txn_id` varchar(30) DEFAULT NULL,
                    `business` varchar(50) NOT NULL,
                    `receiver_email` varchar(100) NOT NULL,
                    `txn_type` varchar(15) NOT NULL,
                    `mc_currency` varchar(10) NOT NULL,
                    `amount3` float DEFAULT NULL,
                    `recurring` tinyint(4) DEFAULT NULL,
                    `period3` varchar(10) DEFAULT NULL,
                    `mc_gross` float DEFAULT NULL,
                    `mc_fee` float DEFAULT NULL,
                    `test_ipn` tinyint(1) DEFAULT NULL,
                    `timestamp` int(11) NOT NULL,
                    `status` varchar(10) NOT NULL,
                    PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB  DEFAULT CHARSET=latin1" );
		}


		if ( $wpdb->get_var( "SELECT count(*) FROM  $DSP_FIELD_TYPES_TABLE WHERE `field_name` LIKE 'Multiple Select'" ) < 1 ) {
			$wpdb->query( "INSERT INTO $DSP_FIELD_TYPES_TABLE SET field_name='Multiple Select' " );


		}

	}

	// Adding test_mode column in dsp_gateways table

	$checkColumns = array( 'test_mode' );
	foreach ( $checkColumns as $column ) {
		$result = $wpdb->query( "SHOW COLUMNS FROM `$dsp_gateways_table` LIKE '$column'" );
		if ( ! $result ) {
			$sql = "ALTER IGNORE TABLE `$dsp_gateways_table` 
                        ADD `$column` tinyint(4) DEFAULT NULL";
			$wpdb->query( $sql );
		}
	}

	// Adding recurring column in dsp_gateways table

	$checkColumns = array( 'recurring' );
	foreach ( $checkColumns as $column ) {
		$result = $wpdb->query( "SHOW COLUMNS FROM `$dsp_gateways_table` LIKE '$column'" );
		if ( ! $result ) {
			$sql = "ALTER IGNORE TABLE `$dsp_gateways_table`
                        ADD `$column` tinyint(1) DEFAULT NULL";
			$wpdb->query( $sql );
		}
	}

	// Add free plan column
	$DSP_MEMBERSHIPS_TABLE = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
	$wpdb->query( "ALTER TABLE $DSP_MEMBERSHIPS_TABLE ADD `free_plan` INT(11) NOT NULL AFTER `image`" );
	$wpdb->query( "ALTER TABLE $DSP_MEMBERSHIPS_TABLE ADD `stripe_recurring_plan_id` VARCHAR(50) NOT NULL" );

	//Alter table enum in privacy

	$wpdb->query( "ALTER TABLE $dsp_user_privacy_table CHANGE `view_my_profile` `view_my_profile` ENUM('Y','N','O') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL" );


}

$wpdb->query( "UPDATE $dsp_general_settings set setting_value='$version' WHERE setting_name = 'plugin_version'" );
echo '<script>location.href="' . $_SERVER['HTTP_REFERER'] . '"</script>';


