<?php
 
 if(!function_exists('dsp_searchByDate')){
 	function dsp_searchByDate($startdate,$enddate,$start,$end){
 		global $wpdb;
 		$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
 		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
 		$wp_user_query = new WP_User_Query($args);
 		$sql = $wpdb->prepare("SELECT u.* FROM $dsp_user_table AS u INNER JOIN $dsp_user_profiles AS p ON u.ID = p.user_id WHERE 1=1 AND CAST(user_registered AS DATE) BETWEEN %s AND %s ORDER BY user_registered DESC LIMIT %d,%d",$startdate,$enddate,$start,$end);
 	   	$users = $wpdb->get_results($sql);
 	   	//var_dump($users);die;
 	    return $users;
 	}
 }

  if(!function_exists('dsp_get_total_users')){
 	function dsp_get_total_users($startdate,$enddate){
 		global $wpdb;
 		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
 		$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
 		$wp_user_query = new WP_User_Query($args);
 		$total_users = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_user_table AS u INNER JOIN $dsp_user_profiles AS p ON u.ID = p.user_id WHERE 1=1 AND CAST(user_registered AS DATE) BETWEEN %s AND %s",$startdate,$enddate));
 	    return $total_users;
 	}
 }

 if(!function_exists('dsp_get_profile_id_by_userId')){
 	function dsp_get_profile_id_by_userId($id){
 		global $wpdb;
 		$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
 		$profiles = $wpdb->get_results($wpdb->prepare("SELECT user_profile_id FROM $dsp_user_profiles WHERE user_id= %d",$id));
 	    $profile_id = $profiles[0]->user_profile_id;
 	    return $profile_id;
 	}
 }


