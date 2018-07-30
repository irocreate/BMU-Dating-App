<?php
/**
 * This class is used to handle all files upload & delete
 * works.
 * @package class
 * @author www.wpdating.com
 * @since 5.0
 */

class LicenceChecker 
{

	/**
	 * This property is used for default configuaration 
	 * for license api which must include SECRET_KEY,
	 * ITEM_REFERENCE,LICENSE_SERVER_URL
	 * 
	 * @var array
	 */
	static $_counter = 1;
	private $_config = array(
					'secret_key' => '',
					//'item_reference' => '',
					'license_key' => '',
					'license_server_url' => ''
				);

	public $isValidLicense = null;
	private $userId = '';
	public $errors = array();

	function __construct() {
		$this->userId = get_current_user_id();
	}

	/**
	 * This method is used to initialize and check 
	 * for validity of license
	 * @access public
	 * @param String
 	 * @since 5.0
 	 * @return Boolean
	 * @author www.wpdating.com
	 */
	
	public function initAndCheckForValidityOfLicense() {
		global $isValidLicense;
		$this->_checkConfig($this->_config);
		$emptyValueConfig = array_search('',$this->_config);
		$isValidLicense = $this->checkLicense();
	}

	/**
	 *
	 * This method is used to check for global license
	 * variable is valid or not
	 * wheather or not object is json
	 * @access public
	 * @param String
 	 * @since 5.0
 	 * @return Boolean
	 * @author www.wpdating.com
	 */
	
	public  function licenseIsValid($config) {
		global $isValidLicense;
		if(!empty($isValidLicense))
		{
			return $isValidLicense;
		}
		$this->_config = $config;
		$this->initAndCheckForValidityOfLicense();

	}

	/**
	 *
	 * This is helper method to check 
	 * wheather or not object is json
	 * @access private
	 * @param String
 	 * @since 5.0
 	 * @return Boolean
	 * @author www.wpdating.com
	 */
		

	private function _isJson($string) {
		 json_decode($string);
		 return (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}

	/**
	 *
	 * This method is used to check configuration value for license api
	 * @access private
 	 * @since 5.0
 	 * @return null
	 * @author www.wpdating.com
	 */
	
	private function _checkConfig($config) {
		if (empty($config) ){
			return false;
		}
		foreach ($config as $key => $value) {
			if(empty($value))
				$this->_config[$key] = '';
			$this->_config[$key] = $value;

		}
	}

	/**
	 *
	 * This method is used to generate notice in frontend
	 * if the license is not configured correctly
	 * @access public
 	 * @since 5.0
 	 * @return String
	 * @author www.wpdating.com
	 */

	public function licenseNotices ($content) { 
		
		$prevContent = $content;
		$content = $this->_getLicenseNotices();
		$content .= $prevContent;
    	return $content;
	}

	/**
	 *
	 * This method is used to generate notice in admin section
	 * if the license is not configured correctly
	 * @access public
 	 * @since 5.0
 	 * @return String
	 * @author www.wpdating.com
	 */

	public function adminLicenseNotices () { 
		echo $this->_getLicenseNotices();
	}

	/**
	 *
	 * This method is used  as common  for checking errors 
	 * wheather or not license is configured correctly
	 * @access private
 	 * @since 5.0
 	 * @return String
	 * @author www.wpdating.com
	 */


	private function _getLicenseNotices() {
		empty($this->errors) || count($this->errors) > 0 ? array_push($this->errors,language_code('DSP_LICENSE_KEY_TEXT')) : '';
		$content = sprintf('<div class="license-notices"><ul><li><strong>Warning !</strong></li>');
		foreach ($this->errors as $key => $err) {
			$content .=  sprintf('<li>%s</li>',$err);
		}
		$content .= sprintf('</ul></div>');

		return $content;
	}

	/**
	 *
	 * This method is used to check for license validation 
	 * @access public
 	 * @since 5.0
 	 * @param  String $str 
	 * @return String
	 * @author www.wpdating.com
	 */
	
	public function checkLicense() {
		$api_params = array(
            'slm_action' => 'slm_activate',
            'secret_key' => $this->_config['secret_key'],
            'domain' => $_SERVER['HTTP_HOST'],
            'license_key' => $this->_config['license_key']
        );

		// Send query to the license manager server
        $requestUrl = add_query_arg($api_params, $this->_config['license_server_url'], array('timeout' => 20, 'sslverify' => false));
        try {
	        $response = wp_remote_get($requestUrl);

	        // Check for error in the response
	        if (is_wp_error($response)){
	            array_push($this->errors,"Request unsuccessfull");
	        	return false;
	        }
	        $license_data =  json_decode(wp_remote_retrieve_body($response));
	        
	        if(!is_null($license_data) && $license_data->result == 'success'){//Success was returned for the license activation
	        	update_option('_license_validated', true);
	        	return true;
	        }
	        else{
	            //Show error to the user. Probably entered incorrect license key.
	            //Uncomment the followng line to see the message that returned from the license server
	            array_push($this->errors,$license_data->message);
	            return false;
	        }
	    } catch (Exception $e) {
	    	//trigger_error($e->getMessage());die;
	    	array_push($this->errors,$e->getMessage());
	    	return false;
	    }
	}


	/**
	 *
	 * This method is used to get license key set in DSP ADMIN
	 * @access public
 	 * @since 5.0
 	 * @return Boolean
	 * @author www.wpdating.com
	 */
	
	public static function generalLicenseNotices($content)	{
		$prevContent = $content;
		$content = sprintf('<div class="license-notices alert alert-warning alert-dismissible" role="alert">');
		$content .=  sprintf('<p> <strong>Warning!</strong> Please validate your license for WP Dating Plugin</p>');
		$content .= sprintf('</div>');
		$content .= $prevContent;
		return $content;
	}	

	/**
	 *
	 * This method is used to get license key set in DSP ADMIN
	 * @access public
 	 * @since 5.0
 	 * @return Boolean
	 * @author www.wpdating.com
	 */
	
	public static function frontPageLicenseNotices()	{
		$content = sprintf('<div class="notices alert alert-warning alert-dismissible" role="alert"  style="display:none">');
		$content .=  sprintf('<p> <strong>Warning!</strong> Please validate your license for WP Dating Plugin</p>');
		$content .= sprintf('</div>');
		echo $content;
	}	

	/**
	 *
	 * This method is static to display general message
	 * @access public
 	 * @since 5.0
 	 * @return Boolean
	 * @author www.wpdating.com
	 */
	
	public static function getLicenseKey()	{ 
		global $DSP_GENERAL_SETTINGS_TABLE,$wpdb;
		$licenseKey = $wpdb->get_var("SELECT `setting_value` FROM  $DSP_GENERAL_SETTINGS_TABLE WHERE setting_name = 'license_key'");
		return $licenseKey;
	}	


}

if ( empty($isValidLicense)) {
	add_action( 'wp_footer', array('LicenceChecker','frontPageLicenseNotices'));
}
