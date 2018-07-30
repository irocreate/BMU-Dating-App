<?php

if ( !class_exists('LICENSE_HANDLER') ) {
	class License_Handler {

		protected $_licKey = '';
		private $_msgs = array('success'=> false);
		function __construct() {
			//add_action('admin_notices',array( $this, 'displayMsg' ));
		}

		/**
		 *  This method is used as setter for property of this class
		 *  @param String $licKey
		 *  @return Boolean
		 *  @author www.wpdating.com
		 *  @since 5.0
		 *  
		 */

		public function setLicKey($licKey) {
			if(empty($licKey))
				return false;
			$this->_licKey = $licKey;
			return true;
		}

		/**
		 *  This method is used as setter for property of this class
		 *  @access public
		 *  @return Boolean
		 *  @author www.wpdating.com
		 *  @since 5.0
		 *  
		 */

		public function getLicKey() {
			return $this->_licKey;
		}

		/**
		 *  This method is used as setter for property of this class
		 *  @param  void
		 *  @return Boolean
		 *  @author www.wpdating.com
		 *  @since 5.0
		 *  
		 */

		public function saveLicKey() {
			global $wpdb;
			$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

			$result = $wpdb->update( 
							$dsp_general_settings_table, 
							array( 
								'setting_value' => $this->_licKey,	// string
							), 
							array( 'setting_name' => 'license_key' ), 
							array( 
								'%s',	// value1
							), 
							array( '%s' ) 
						);
			if($result ||  $result >= 0 ) {
				$this->_msgs[] = 'License Key Saved Successfully';
				$this->_msgs['success'] = true;
				return true;
			} else {
				$this->_msgs[] = 'Error occured during saving';
				return false;
			}
				
		}


		/**
		 *  This method is used display message for user
		 *  @param  void
		 *  @return string
		 *  @author www.wpdating.com
		 *  @since 5.0
		 *  
		 */

		public function displayMsg() { 
			if( empty($this->_msgs) || count($this->_msgs)  < 1 )
				return false;
			$class = $this->_msgs['success'] ? 'class="updated"' : 'class="error"';
			$this->_msgs = array_diff($this->_msgs, array($this->_msgs['success']));
			$content = sprintf('<div id="message" class="%s">',$class);
			foreach ($this->_msgs as  $msg) {
				$content .=  sprintf('<p>%s</p>', $msg );
			}
			$content .= '</div>';
			echo  $content;
		}
	}
}