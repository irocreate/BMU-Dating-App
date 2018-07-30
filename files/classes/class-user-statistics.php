<?php
  //echo dsp_find_wp_config_path() .'/wp-config.php';die;
include_once(dsp_find_wp_config_path() .'/wp-config.php');
include_once(dirname(__FILE__) . '../../includes/table_names.php');

if(!class_exists('UserStats_class')){
	class UserStats_class
	{
		private $_db;
		private $_dsp_user_profile_table;
		private $_dsp_payments_table;
		private $_users_table ;
		private $_country_table ;

		public function __construct()
		{
			global $wpdb;
			$this->_db = $wpdb;
			$this->_dsp_user_profile_table = $this->_db->prefix . DSP_USER_PROFILES_TABLE ;
			$this->_dsp_payments_table = $this->_db->prefix . DSP_PAYMENTS_TABLE ;
			$this->_users_table = $this->_db->prefix . DSP_USERS_TABLE;
			$this->_country_table = $this->_db->prefix . DSP_COUNTRY_TABLE;

		}

	  	/**
	  	* This method is used to count & list all users  by  
	    * gender using start and end  registered date filter
	  	* @param Array $filters 
	    * @return Object 
	    * 
	  	*/

	  	public function countAllUsersByGender($filters = null){
	  		$query = "SELECT wp.gender as  `gender`,COUNT(*) AS `count` FROM $this->_dsp_user_profile_table AS wp ";
	  		$query .= " INNER JOIN $this->_users_table  AS wu ";
	  		$query .=  " on wu.ID = wp.user_id ";
	  		if(!empty($filters)){
	  			extract($filters);
	  			$query .=  " WHERE wu.user_registered between '$startDate' and '$endDate' ";
	  		}
	  		$query .= " GROUP BY wp.gender";
	      //echo $query;die;
	  		$AllUsersListedByGender = $this->_db->get_results($query);
	  		return $AllUsersListedByGender;
	  	}

	    /**
	    * 
	    * This method is used to get all users  by  
	    * country using start and end  registered date filter
	    * @param Array $filters 
	    * @return Object 
	    */

	    public function getAllUsersByCountry($filters = null){

	    	$query = "SELECT wc.name AS country, count(wp.country_id) AS total FROM  ";
	    	$query .= " $this->_users_table  AS wu inner join $this->_dsp_user_profile_table wp";
	    	$query .=  " on wu.ID = wp.user_id ";
	    	$query .=  " inner join $this->_country_table wc on wp.country_id = wc.country_id ";
	    	if(!empty($filters)){
	    		extract($filters);
	    		$query .=  " WHERE wu.user_registered between '$startDate' and '$endDate' ";
	    	}
	    	$query .= " GROUP BY wp.country_id";
	    	$AllUsersListedByCountry = $this->_db->get_results($query);
	    	return $AllUsersListedByCountry;
	    }

	  	/**
	    * 
	    *  This method is used to count 
	  	*  all premium users by country
	  	*  @param Array $filters is used for filter out results using gender,country,seeking options 
	    *  @return Object 
	    */ 
	  	

	  	public function countAllPremiumUsers($filters = null){
	  		$query = "SELECT COUNT(*) AS `total` FROM $this->_dsp_user_profile_table AS wp ";
	  		$query .= " INNER JOIN  $this->_dsp_payments_table AS payment ";
	  		$query .= " ON wp.user_id=payment.pay_user_id ";
	  		$query .=  " INNER JOIN $this->_users_table wu on wp.user_id = wu.ID ";
	  		if(!empty($filters)){
	  			extract($filters);
	  			$query .=  " WHERE wu.user_registered between '$startDate' and '$endDate' ";
	  		}
	  		$totalPremiumUsers = $this->_db->get_results($query);
	  		return $totalPremiumUsers;
	  	}


	  	/**
	    * 
	    *  This method is used to count 
	    *  all users by registration date
	  	*  @param $month is filter out results for last month,two month etc..
	  	*  $month = 1 not $month = "January"
	    *  @return Object 
	  	*/

	  	public function countAllUsersByMonth($filters = null){
	  		$query = "SELECT COUNT(*) AS `count` FROM $this->_users_table AS users";
	  		if(!empty($filters)){
	  			extract($filters);
	  			$query .= " WHERE DATE(user_registered) BETWEEN $start_date  AND $end_date";
	  		}
	  		$totalUsersByMonth = $this->_db->get_results($query);
	  		return $totalUsersByMonth;
	  	}
	  }
	}
