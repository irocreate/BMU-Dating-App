<?php
/**
 * This class is used to handle routing for dsp_dating url
 * @package class
 * @author www.wpdating.com
 * @since 5.0
 */

class WP_Router {

	protected static  $_wpMenus = array();
	protected static  $_return = array();
	protected static  $_dirAccessMenus = array();
	protected static  $_feature = '';
	protected static  $_menu = '';
	protected static  $_submenu = '';
	protected $_db;

	function __construct() {
		global $wpdb;
		$this->_defineUrlArray();
		$this->_db = $wpdb;
		add_filter('dsp_allow_menu_page', array( __CLASS__ , 'dsp_allow_menu_page_func' ),10,3);
		add_filter('dsp_get_general_setting_value', array( $this , 'dsp_get_general_setting_value'),10,2);
		
	}

	
	/**
	 * This method is used to set the menu
	 *
	 * @author WRI HK LTD
	 * @access public
	 * @since 5.0
	 * @return  Array
	 */
	
	private function _defineUrlArray() {
		WP_Router::$_wpMenus = array(
								'home' => array(
												'mypage' => 'my_page',
												'view_profile' => 'profile',
												'send_wink_msg' => 'my_page',
												'view_album' => 'album',
												'view_friends' => 'friend',
												'view_Pictures' => 'pictures',
												'view_video' => 'video',
												'view_audio' => 'audio',
												'view_winks' => 'winks',
												'my_favorites' => 'favorites',
												'my_matches' => 'matches',
												'alerts' => 'alerts',
												'comments' => 'comments',
												'virtual_gifts' => 'gifts',
												'news_feed' => 'news_feed',
												'match_alert' => 'match_alerts',
												'location' => 'edit_my_location'
											),
								'edit' => array(
												'my_profile' => 'my_page',
												'partner_profile' => 'partner-profile',
												'edit_my_location' => 'edit_my_location'
												
											),
								'email' => array(
												'inbox' => array('inbox',4),
												'compose' => array('compose',7),
												'view_message' => array('compose',7),
												'view_album' => 'album',
												'view_friends' => 'friend',
												'view_Pictures' => 'pictures',
												'view_photos' => 'photos',
												'view_video' => 'video',
												'view_audio' => 'audio',
												'view_winks' => 'winks',
												'my_favorites' => 'favorites',
												'my_matches' => 'matches',
												'alerts' => 'alerts',
												'comments' => 'comments',
												'virtual_gifts' => 'gifts',
												'news_feed' => 'news-feed',
												'match_alert' => 'match-alerts',
												'location' => 'edit_my_location'
											),
								'media' => array(
												'album' => 'add_photos_album',
												'photo' => 'photo',
												'add_audio' => 'dsp_add_audio',
												'add_video' => 'dsp_add_video',
												'manage_photos' => 'manage_photos'
											),
								'edit' => array(
												'my_profile' => 'edit_profile_setup',
												'partner_profile' => 'edit_partner_profile_setup',
												'edit_my_location' => 'dsp_add_audio'
											),
								'chat' => array(
												15 => 'user_dsp_chat',
											),
								'search' => array(
												'basic_search' => 'dsp_user_search',
												'advance_search' => 'user_advanced_search',
												'zipcode_search' => 'zip_code_search',
												'zipcode_search_result' => 'zipcode_search_result',
												'myinterest_search_result' => 'myinterest_search_result',
												'distance_search' => 'distance_search',
												'near_me' => 'near_me_search_result',
												'save_searches' => 'save_search_results',
											),
								'extras' => array(
												'viewed_me' => 'viewed_me',
												'i_viewed' => 'iviewed',
												'trending' => 'trending',
												'interest_cloud' => 'interest_cloud',
												'date_tracker' => 'date_tracker',
												'edit_date_tracker' => 'edit_date_tracker',
												'blogs' => 'myblogs_header',
												'meet_me' => 'dsp_meet_me'.
												'edit_date_tracker'=>'edit_date_tracker'
											),
								'online_members' => array(
												'online_members' => 'dsp_online_other_users',
											),
								'help' => array(
												'basic_search' => 'edit_profile_setup',
												'advance_search' => 'edit_partner_profile_setup',
												'zipcode_search' => 'dsp_add_audio',
												'advance_search' => 'edit_partner_profile_setup',
											),
								);
	}


	/**
	 * This method is used to get an  file_name
	 *
	 * @author WRI HK LTD
	 * @param null
	 * @since 5.0
	 * @return  [Object]
	 */
	
	function dsp_get_filename() {
		$filename = self::$_wpMenus[$_menu][$_submenu];
		return  file_exists(WP_DSP_ABSPATH  . 'members/loggedin/extras/' . $filename .'.php') ? 
				WP_DSP_ABSPATH  . 'members/loggedin/extras/' . $filename .'.php' : 
				WP_DSP_ABSPATH  . $filename .'.php';
	}


	/**
	 * This method is  used get general setting value
	 * 
	 * @param   String or Array setting name
	 * @param [String] Column name
	 * @author WRI HK LTD
	 * @since 5.0
	 * @return  [Object]
	 */

	
	function dsp_get_general_setting_value( $settingNames = '', $settingStatus = false ) { 
	        global $wpdb;
	        $dsp_general_settings_table = $this->_db->prefix . DSP_GENERAL_SETTINGS_TABLE;
	        if( $settingStatus )
	        {
	            if(is_array($settingNames)) 
	            {  
	                $returnSettingsStatus = array();
	                foreach ($settingNames as $key => $name) {
	                    $query = "SELECT  `setting_status` FROM $dsp_general_settings_table WHERE setting_name = '%s' ";
	                    $status = $wpdb->get_var($this->_db->prepare( $query, $settingNames ));
	                    $returnSettingsStatus[$key] = $status == 'Y' ? true : false;
	                }
	                return $returnSettingsStatus;
	            }

	            $query = "SELECT  `setting_status` FROM $dsp_general_settings_table WHERE setting_name = '%s' ";
	            $status = $this->_db->get_var($wpdb->prepare( $query, $settingNames ));
	            return $status == 'Y' ? true : false;
	        }
	        $settingValues =  $this->_db->get_results($wpdb->prepare("SELECT * FROM $dsp_general_settings_table WHERE setting_name = '%s' " , $settingNames));
	        return count($settingValues) > 0 ? array_pop($settingValues) : $settingValues;
	    }

	/**
	 * This method is  used check permission before
	 * access menu page
	 * 
	 * @param String feature name
	 * @param String page name from url for eg blogs,trending etc.
	 * @param Array  page name or menu name direct access with any check
	 * @since 5.0
	 * @return  [Object]
	 */

	function static dsp_allow_menu_page_func() {
        if(in_array(self::$_submenu,self::$_dirAccessMenus ) || $_SESSION['free_member'] )  {
                self::$return['status'] = true;
                return true;
        }
        $user_id = get_current_user_id();
        $settingValues = array(
                                'forceProfileStatus' => 'force_profile',
                                'freeTrialStatus' => 'free_trial_mode',
                                'approveProfileStatus' => 'authorize_profiles'
                            );
        $settingsStatus = apply_filters('dsp_get_general_setting_value',$settingValues,true);
        extract($settingsStatus);
        if ($forceProfileStatus) {
            $check_force_profile_msg = check_force_profile_feature(self::$feature, $user_id);
            $allowMenuPageStatus = $check_force_profile_msg == "Access" ? true : false ;
            self::$return['check_force_profile_msg'] =  $check_force_profile_msg;
        } else {
            if($freeTrialStatus) {
                $check_member_trial_msg = check_free_trial_feature(self::$feature, $user_id);
                $allowMenuPageStatus = $check_member_trial_msg == "Access" ? true : false ; 
                //array_push('check_member_trial_msg' => $check_member_trial_msg);
                self::$return['check_member_trial_msg'] =  $check_member_trial_msg;

            } else if (!$approveProfileStatus) {
                $check_approved_profile_msg = check_approved_profile_feature(self::$feature,$user_id);
                $allowMenuPageStatus = $check_approved_profile_msg == "Access" ? true : false ; 
                //array_push('check_approved_profile_msg' => $check_approved_profile_msg);
                self::$return['check_approved_profile_msg'] =  $check_approved_profile_msg;
            } else  { // if free trial mode is off
                $check_membership_msg = check_membership(self::$feature, $user_id);
                $allowMenuPageStatus = $check_membership_msg == "Access" ? true : false ; 
                //array_push('check_membership_msg' => $check_membership_msg);
                self::$return['check_membership_msg'] =  $check_membership_msg;
            }
        }
        $return['status'] =  $allowMenuPageStatus;
        return true;
    }


}