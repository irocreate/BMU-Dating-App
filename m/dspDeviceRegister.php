<?php
include("../../../../wp-config.php");
global $wpdb;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$user_id = $_REQUEST['user_id'];
$deviceToken = $_REQUEST['deviceToken'];
$deviceType = $_REQUEST['deviceType'];
//select column according to device type
$column = $deviceType == 'iPhone' ? 'ios_device' : 'android_device';
//extract exist token & appen with new one
$newTokens = dsp_extract_device_token($column) . ',' . $deviceToken;
//save new token appen with exist one
dsp_include_token($column,$newTokens,$user_id);

/**
 * This function is used to extract any existed token if exist
 * @param String [Column name]
 * @return String
 */

if(!function_exists('dsp_extract_device_token')){
	function dsp_extract_device_token($column){
		global $wpdb;
		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
		
		$query = "SELECT '%s' FROM $dsp_user_profiles WHERE user_id = '%d'";
		$values = array($column,$user_id);
		$alreadyExistToken = $wpdb->get_var($wpdb->prepare($query,$values));
		return !empty($alreadyExistToken) ? $alreadyExistToken : '';
	}
}

/**
 * This function is used to update token according to provided device type
 * @param String [Column name]
 * @param String [Token]
 * @return boolean value
 */

if(!function_exists('dsp_include_token')){
	function dsp_include_token($column,$token,$userId){
		global $wpdb;
		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
		//$query = "SELECT '%s' FROM $dsp_user_profiles WHERE user_id = '%d'";
		$datas = array(
						 $column => $token
					);
		$where = array(
						'user_id' => $userId
					);
		$format = array( '%s');
		$whrFormat = array( '%d');
		return $wpdb->update($dsp_user_profiles,$datas,$where,$format,$whrFormat);
	}
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
