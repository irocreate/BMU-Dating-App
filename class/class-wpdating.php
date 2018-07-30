<?php
 /**
 * This is core class for wpdating
 * 
 * @package class
 * @author WRI HK LTD
 * @since 5.0
 */



 class WPDATING {

     private $_db ;
     public   $_wpdateGeneralSetting;
     protected $_mobileStatus = 'N';
     public static $touchObj;


 	function __construct() {

 		global $wpdb;
 		$this->_db  = $wpdb;

 		// include required files
 		$this->_includeFile();
 		$_wpdateGeneralSetting = $this->_db->prefix . DSP_GENERAL_SETTINGS_TABLE;

 		// call during plugin activation
 		$this->_callActivationHook();

 		// call action & hook of wordpress to rewrite rule for plugin
 		add_action('wp_loaded', array( $this , 'dspFlushRules'));
		add_action('wp_head', array( $this , 'dspCustomCss'));
		add_filter('rewrite_rules_array', array( $this , 'dspInsertRewriteRules')); // set rewrite rule for plugin
		add_filter('dspGetSettingValues',array($this,'dspGetSettingValuesFn') , 10 , 2 );
 	
 	}

 	/**
 	 *  This method is used to intialize all required file
 	 *  during starting of  wpdate plugin
 	 *
 	 * @access private
 	 * @author WRI HK LTD
 	 * @since 5.0
 	 * @param void
 	 * @return null
 	 */

 	private function _includeFile() {
 		include_once ( WP_DSP_ABSPATH . 'files/includes/table_names.php');
 		//include_once ( WP_DSP_ABSPATH . 'include_dsp_tables.php');
 		include_once ( WP_DSP_ABSPATH . 'class/class-license-checker.php');
		include_once ( WP_DSP_ABSPATH . 'files/includes/general.php');
		include_once ( WP_DSP_ABSPATH . 'external-lib/po-parser/PoParserUsed.php');
		include_once ( WP_DSP_ABSPATH . 'functions.php');
		include_once ( WP_DSP_ABSPATH . 'dsp_sc_login.php');
		include_once ( WP_DSP_ABSPATH . 'dsp_sc_register.php');
		include_once ( WP_DSP_ABSPATH . 'dsp_sc_search.php');
 	} 

 	/**
 	 *  This method is used to check for mobile setting
 	 *  to include or not file
 	 *
 	 * @access public
 	 * @author WRI HK LTD
 	 * @since 5.0
 	 * @param void
 	 * @return null
 	 */
 	
 	public function _checkForMobileStatus() {

 		$this->_mobileStatus = $this->_db->get_var("SELECT setting_status FROM $this->_wpdateGeneralSetting where setting_name = 'mobile'");
 		if ( !file_exists(MOBILE_DIR) || 
 			 is_dir(MOBILE_DIR)  || 
 			 $this->_checkForMobileStatus() == 'Y'
 		) { // mobile folder exist also check what is mobile status
			
			return false;
		}
		// if true for mobile then include it
		include_once(WP_DSP_ABSPATH  . 'mobile/dsp_check_mobile.php');
		self::$touchObj = new WPtouchPlugin();
		$_GLOBALS['wptouch_plugin_obj'] = self::$touchObj;
		
 	}

 	/**
 	 *  This method is used to check for mobile setting
 	 *  to include or not file
 	 *
 	 * @access public
 	 * @author WRI HK LTD
 	 * @since 5.0
 	 * @param void
 	 * @return null
 	 */

 	public function _callActivationHook() {

 		global  $pluginFile;
 		register_activation_hook($pluginFile, array($this ,'WPSetupDemoOnActivation'));
		register_activation_hook($pluginFile, array($this ,'pluginActivate'));
		//add_action('plugins_loaded','create_dsp_tables');
		register_activation_hook($pluginFile, array($this ,'createDspTables'));
		do_action('wpdating_activate_hook');

 	}


 	/**
 	 *  This method is called during activation
 	 *  of wpdating plugin to create member page
 	 *
 	 * @access protected
 	 * @author WRI HK LTD
 	 * @since 5.0
 	 * @param void
 	 * @return null
 	 */

 	protected function WPSetupDemoOnActivation() {
 		if (!get_page_by_title('Members')) {
			// Create post object
			$new_post = array(
				'post_title' => 'Members',
				'post_content' => '[include filepath="profile_header.php"]',
				'post_status' => 'publish',
				'post_date' => date('Y-m-d H:i:s'),
				'post_author' => 'admin',
				'post_type' => 'page',
				'post_category' => array(0)
			);
			$pageName = 'members';
			$post_id = wp_insert_post($new_post);
			delete_option($pageName . '_page_title');
			add_option($pageName . '_page_title', 'Members', '', 'yes');
			delete_option($pageName . '_page_name');
			add_option($pageName . '_page_name', 'Members', '', 'yes');
			delete_option($pageName . '_page_id');
			add_option($pageName . '_page_id', $post_id, '', 'yes');
		}
		/*if (file_exists(WP_DSP_ABSPATH . "/gifts")) {
			if (file_exists(ABSPATH . "/wp-content/uploads/dsp_media/gifts/")) {
				rcopy(WP_DSP_ABSPATH . "/gifts/", ABSPATH . "/wp-content/uploads/dsp_media/gifts/");
			} else {
				createPath(ABSPATH . "/wp-content/uploads/dsp_media/gifts/");
				rcopy(WP_DSP_ABSPATH . "/gifts/", ABSPATH . "/wp-content/uploads/dsp_media/gifts/");
			}
		}	*/
 	}

  	/**
 	 *  This method is called during activation
 	 *  of wpdating plugin to send mail at 
 	 *  activation@datingsolutions.biz
 	 *
 	 * @access protected
 	 * @author WRI HK LTD
 	 * @since 5.0
 	 * @param void
 	 * @return null
 	 */

 	protected function pluginActivate() {
 		ob_start();
 		$email = "activation@datingsolutions.biz";
		$ip = $_SERVER['REMOTE_ADDR'];
		$siteurl = get_option('siteurl');
		$adminemail = get_option('admin_email');
		$subject = "Plugin activation information";
		$body = "The following domain just activated the Dating Plugin:\n 
		Site Address (URL): $siteurl \n 
		Site Admin Email Address: $adminemail  \n
		IP Address: $ip \n ";
		$from = $adminemail;
		$headers .= "From: $from";
		wp_mail($email, $subject, $body, $headers);
		ob_clean();

 	}

 	/**
 	 *  This method is called during activation
 	 *  of wpdating plugin for create table & directories
 	 *
 	 * @access protected
 	 * @author WRI HK LTD
 	 * @since 5.0
 	 * @param void
 	 * @return null
 	 */

 	protected function createDspTables() {
 		if (function_exists('createtables')) {
			createtables();
		}
		if (function_exists('createdirectories')) {
			createdirectories(); //Create Media Directories and Set Permissions
		}
 	}

 	/**
 	 *  This method is called during activation
 	 *  of wpdating plugin for create table & directories
 	 *
 	 * @access public
 	 * @author WRI HK LTD
 	 * @since 5.0
 	 * @param void
 	 * @return null
 	 */

 	public function dspFlushRules() {
 		global $wp_rewrite;
 		$rules = get_option('rewrite_rules');
		$slug = $this->_dspGetPageName();
		//print_r($rules);die;
		if (!isset($rules['(' . $slug . ')/(.+)$'])) {
			$wp_rewrite->flush_rules();
		}
 	}

 	/**
 	 *  This method is called during activation
 	 *  of wpdating plugin for create table & directories
 	 *
 	 * @access public
 	 * @author WRI HK LTD
 	 * @since 5.0
 	 * @param void
 	 * @return null
 	 */


 	public function dspInsertRewriteRules() {

 		global $wp_rewrite;
		$slug = dspGetPageName();
		$newrules = array();
		$newrules['(' . $slug . ')/(.+)$'] = 'index.php?pagename=$matches[1]/&pid=$matches[2]';
		return $newrules + $rules;

 	}

	/**
 	 *  This method return page name
 	 *  for wpdating plugin 
 	 *
 	 * @access private
 	 * @author WRI HK LTD
 	 * @since 5.0
 	 * @param void
 	 * @return null
 	 */

 	private function _dspGetPageName() {
 		// get member page name
		$memberPage = get_option('members_page_name'); 
		if ($memberPage) {
			return $memberPage;
		} else {
			return "members";
		}
	}

	
	/**
 	 *  This method return page name
 	 *  for wpdating plugin 
 	 *
 	 * @access private
 	 * @author WRI HK LTD
 	 * @since 5.0
 	 * @param void
 	 * @return null
 	 */
 	
	public function dspGetSettingValuesFn($cols,$ins) {

		$values = $conditions = $cols = array();
		//$dsp_general_settings_table //= $this->_db->prefix . DSP_GENERAL_SETTINGS_TABLE;
		if(!is_array($col) && count($col) < 1 ) {
			return $values;
		}

		$cols = trim(implode(',',$cols));
		$ins = implode( ',', $whr );
		
		$query = "SELECT  $cols  FROM $dsp_general_settings_table WHERE setting_name IN ('%s') ";
		var_dump($query);die;
		return $this->_db->get_results($wpdb->prepare($query,$ins));
	}


	

	/**
 	 *  This method return page name
 	 *  for wpdating plugin 
 	 *
 	 * @access public
 	 * @author WRI HK LTD
 	 * @since 5.0
 	 * @param void
 	 * @return null
 	 */
 	
	public  function dspCustomCss() { 
		global $wpdb;
		$settingValues = array(
                                'buttonColor' => 'button_color',
                                'notActiveTabColor' => 'non_active_tab_color',
                                'paginationColor' => 'pagination_color',
                                'titleColor' => 'title_color',
                                'tabColor' => 'tab_color'
                            );
        $colorsettingsValues = apply_filters('dspSettingValues',$settingValues,true);
		//$wp_userID = get_current_user_id();
		/*$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
	   // $dsp_user_profiles =  $wpdb->prefix . DSP_USER_PROFILES_TABLE;
		$check_button_color = apply_filters('dsp_get_general_setting_value',$settingValues,true);//$wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'button_color'");
		$check_non_active_tab_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'non_active_tab_color'");
		$check_tab_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'tab_color'");
		$check_pagination_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'pagination_color'");
		$check_title_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'title_color'");
		?><style>
			button, input[type="submit"], input[type="button"], input[type="reset"], .btn-reply{ background:#<?php echo $check_button_color->setting_value; ?>;}
			.dsp-line{ background:#<?php echo $check_non_active_tab_color->setting_value; ?>;}
			.heading-text{ background:#<?php echo $check_non_active_tab_color->setting_value; ?>;}
			.tab-box a.activeLink{background-color:#<?php echo $check_non_active_tab_color->setting_value; ?>;}
			.wpse_pagination .disabled {background:#<?php echo $check_pagination_color->setting_value; ?>;}
			.age-text{color:#<?php echo $check_non_active_tab_color->setting_value; ?>;}
			#dsp_plugin .profle-detail ul.quick-star-details li{color:#<?php echo $check_non_active_tab_color->setting_value; ?>;}
			.right-link span{ color:#<?php echo $check_non_active_tab_color->setting_value; ?>;}
			.dsp_tab1{ border-right: 2px solid #<?php echo $check_non_active_tab_color->setting_value; ?>;}
			.line{background:#<?php echo $check_tab_color->setting_value; ?>;}
			.dsp_tab1-active{background-color:#<?php echo $check_tab_color->setting_value; ?>; border-right: 2px solid #<?php echo $check_tab_color->setting_value; ?>;}
			.btn-link{background:#<?php echo $check_button_color->setting_value; ?>;}
			.heading-submenu{color:#<?php echo $check_title_color->setting_value; ?>; border-bottom:2px solid #<?php echo $check_title_color->setting_value; ?>;}
			.linkright-view-profile-page span{color:#<?php echo $check_title_color->setting_value; ?>;}
		</style><?php*/
	}



 }


new WPDATING();