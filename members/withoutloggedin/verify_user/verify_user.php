<?php
 $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
 $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
 $userDetails =  get_user_by('email',$email);
 if($userDetails != false){
 	$userDetails = $userDetails->data;
 	$userId = $userDetails->ID;
 	$activated = get_user_meta($userId,'_dsp_confirm');
 	if(!empty($activated)){
 		delete_user_meta($userId,'_dsp_confirm');
 		echo language_code('DSP_USER_ACCOUNT_ACTIVATED_SUCCESSFULLY');
 	}
 }else{
 	echo language_code('DSP_USER_NOT_EXIST');
 }