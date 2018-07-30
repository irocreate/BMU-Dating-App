<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
include_once("../../../wp-config.php");
include_once("./files/includes/functions.php");
global $wpdb;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;  // print session USER_ID
$dsp_match_alert_criteria_table = $wpdb->prefix . DSP_MATCH_CRITERIA_TABLE;
$dsp_profile_setup_table = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
$dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_match_alert_criteria = $wpdb->prefix . DSP_MATCH_CRITERIA_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_email_templates_table = $wpdb->prefix . "dsp_email_templates";
$siteUrl = get_option('siteurl');

$my_query = $wpdb->get_results("SELECT * FROM $dsp_match_alert_criteria where active='Y'");
foreach ($my_query as $query) {
    $muser_id = $query->user_id;
    $alreadyMatchSendUsers = array();
    $sentMatchUsers = apply_filters('dsp_get_already_mail_sent_match_users', $muser_id);//unserialize(get_option('myMatch'.$muser_id));
    $sentMatchUsers .= isset($sentMatchUsers) && !empty($sentMatchUsers) ? ',' .$muser_id : $muser_id;
    $frequency = $query->frequency;
    $gender = $query->gender;
    $age_from = $query->age_from;
    $age_to = $query->age_to;
    $date = $query->last_updated_date;
    $musers_table = $wpdb->get_row("SELECT user_email FROM $dsp_user_table  WHERE ID='$muser_id'");
    $emailid = $musers_table->user_email;
    $email_templates_table = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id = 15 ");
    $email_subject = $email_templates_table->subject;
    $email_body = $email_templates_table->email_body;
    $active_question_id = $wpdb->get_results("SELECT profile_setup_id FROM $dsp_profile_setup_table WHERE display_status='Y'");
    foreach ($active_question_id as $question_id) {
        $active_question_ids[] = $question_id->profile_setup_id;
    }
    if ($active_question_ids != "") {
        $active_question_ids1 = implode(",", $active_question_ids);
    }
    //echo $active_question_ids1;
    //echo "SELECT * FROM $dsp_question_details WHERE profile_question_id IN ($active_question_ids1) and user_id='$muser_id'";
    $matches_option = $wpdb->get_results("SELECT * FROM $dsp_question_details WHERE profile_question_id IN ($active_question_ids1) and user_id='$muser_id'");
    foreach ($matches_option as $match_opt_id) {
        $matches_option_id1[] = $match_opt_id->profile_question_option_id;
    }
    if ($matches_option_id1 != "") {
        $matches_option_id = implode(",", $matches_option_id1);
    }
    $s = "SELECT A.user_id FROM $dsp_question_details A INNER JOIN $dsp_user_profiles B ON(A.user_id=B.user_id) WHERE profile_question_option_id IN ($matches_option_id)  ";
    $s .= !empty($sentMatchUsers) ? " AND A.user_id NOT IN($sentMatchUsers) " : " ";
    if ($age_from >= 18) {
        $s .= " and ((year(CURDATE())-year(age)) >= '" . $age_from . "') AND ((year(CURDATE())-year(age)) < '" . $age_to . "') AND ";
    }
    if ($gender == 'M') {
        $s .= " B.gender='M' ";
    } else if ($gender == 'F') {
        $s .= " B.gender='F' ";
    } else if ($gender == 'C') {
        $s .= " B.gender='C' ";
    } else {
        $s .= " B.gender IN('M','F','C') ";
    }
    $s.= "order BY B.user_id";
    $my_match_query = $wpdb->get_results($s);
    if(isset($my_match_query) && !empty($my_match_query)){
        foreach ($my_match_query as $my_match) {
           $matchUserId = $my_match->user_id;
           $userData = array(
                            'match_id' => $muser_id,
                            'user_id' => $matchUserId
                    );
           do_action('dsp_insert_match_users',$userData);
        }
   
    $message = '<div style="color: #FF0000;font-family: arial;font-size: 30px;font-weight: bold;padding: 5px;width: 600px;">' . $email_subject . '</div>
<div style="background-color: #EFEFEF;border: 1px solid #CCCCCC;padding: 5px;width: 600px;float:left;">
<div style="background-color: #FFFFFF;border: 1px solid #CCCCCC;padding: 5px;float:left;width:587px;">
<div style="display: block;padding: 1px;width: 100%;">';

    foreach ($my_match_query as $my_match) {
        $match_user_id = $my_match->user_id;
        $find_active_user = $wpdb->get_row("SELECT user_id,frequency,last_updated_date FROM $dsp_match_alert_criteria WHERE user_id = '$match_user_id' AND active='Y'");
        @$fuser_id = $find_active_user->user_id;
        @$frequency = $find_active_user->frequency;
        @$fdate = $find_active_user->last_updated_date;
        $alt = '';
        $users_table = $wpdb->get_row("SELECT ID,user_email,user_login FROM $dsp_user_table  WHERE ID='$match_user_id'");
        $id = $users_table->ID;
        $user_login = $users_table->user_login;
        if ($fuser_id != $id) {
            $dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
            $count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id='$id' AND status_id=1");
            $imagepath = WPDATE_URL . '/uploads/dsp_media/';  // image Path
            $status = $wpdb->get_var("SELECT `make_private` FROM $dsp_user_profiles WHERE user_id='$id' AND status_id=1");
            if($status  == 'Y'){
                  $Mem_Image_path = WPDATE_URL . '/images/private-photo-pic.jpg';
                  $alt = 'private photo';
            }else{
                 if ($count_member_images > 0) {
                        $member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$id' AND status_id=1");
                        if ($member_exist_picture->picture == "") {
                            $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$id'");
                            if ($check_gender->gender != 'F') {
                                $Mem_Image_path = $imagepath . "male-generic.jpg";
                                $alt = 'Male photo';
                            } else {
                                $Mem_Image_path = $imagepath . "female-generic.jpg";
                                $alt = 'Female photo';
                            }
                        } else {
                            $Mem_Image_path = $imagepath . "user_photos/user_" . $id . "/thumbs1/thumb_" . $member_exist_picture->picture;
                            $alt = $user_login;
                        }
                    } else {
                        $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$id'");
                        if ($check_gender->gender != 'F') {
                            $Mem_Image_path = $imagepath . "male-generic.jpg";
                            $alt = 'Male photo';
                        } else {
                            $Mem_Image_path = $imagepath . "female-generic.jpg";
                            $alt = 'Female photo';
                        }
                        $Mem_Image_path = $path . "images/no-image.jpg";
                        $alt = 'No Image';
                    }
            }
            ?> <?php

            $message .= '<div style="display: block;float: left;text-align: center;width: 25%;">
				<div>
					<img src="' . $Mem_Image_path . '" style="border: 1px solid #426082;margin-left: auto;margin-right: auto;padding: 3px;text-align: center;" alt="'. $alt .'" />
				</div>
				<div style="clear: both;"></div>
				<div style="color: #426082;">' . $user_login . '</div>
			</div>';
        }
    } //if($fuser_id != $id){
    echo $message .='
<div style="clear: both;"></div>
<br>
<div style="font-size:14px;font-weight:bold;" >' . $email_body . '</div>
</div>
</div>';
// subject
    $subject = $email_subject;
    $adminemail = get_option('admin_email');
// To send HTML mail, the Content-type header must be set
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// Additional headers
    $headers .= 'To:' . $emailid . "\r\n";
    $headers .= 'From:' . $adminemail . "\r\n";
    $frequency = $query->frequency;

    if ($frequency == 'W') {
        $row = $wpdb->get_row("SELECT DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 7 DAY), '%Y-%m-%d') as date  FROM  dual",ARRAY_A);
       // $query_week = mysql_query("SELECT DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 7 DAY), '%Y-%m-%d') as date  FROM  dual");
       // $row = mysql_fetch_array($query_week);
        $week_date = $row['date'];
        $today = date("Y-m-d");
        $to = $emailid;
        if ($today == ($query->date)) {
            if (wp_mail($to, $subject, $message, $headers)) {
                $date = date("Y-m-d");
                $wpdb->query("UPDATE $dsp_match_alert_criteria SET last_updated_date='$date' WHERE user_id=$muser_id");
            }
        } else if ($today == $week_date) {
// Mail it
            if (wp_mail($to, $subject, $message, $headers)) {
                 $date = date("Y-m-d");
                $wpdb->query("UPDATE $dsp_match_alert_criteria SET last_updated_date='$date' WHERE user_id=$muser_id");
            }
        }
    } else if ($frequency == 'D') {
        $today = date("Y-m-d");
        $fdate = $query->last_updated_date;
        $diff = abs(strtotime($today) - strtotime($fdate));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        if (($days == '0') || ($days == '1')) {
            $to = $emailid;
// Mail it
            if (wp_mail($to, $subject, $message, $headers)) {
                 $date = date("Y-m-d");
                $wpdb->query("UPDATE $dsp_match_alert_criteria SET last_updated_date='$date' WHERE user_id=$muser_id");
            }
        }
    } else if ($frequency == 'M') {
        $today = date("Y-m-d");
        $date = $query->last_updated_date;
        $date1 = new DateTime($date);
        $date2 = new DateTime("now");
        $interval = $date1->diff($date2);
        $months = $interval->format('%m');
        $days = $interval->format('%d');
        $to = $emailid;
        if ($months == 0) {
            if (wp_mail($to, $subject, $message, $headers)) {
                $date = date("Y-m-d");
                $wpdb->query("UPDATE $dsp_match_alert_criteria SET last_updated_date='$date' WHERE user_id=$muser_id");
            }
        } else if ($months == '1') {
            // Mail it
            if (wp_mail($to, $subject, $message, $headers)) {
                $date = date("Y-m-d");
                $wpdb->query("UPDATE $dsp_match_alert_criteria SET last_updated_date='$date' WHERE user_id=$muser_id");
            }
        }
    }
  }
}
