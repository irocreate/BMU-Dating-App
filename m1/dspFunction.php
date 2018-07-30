<?php

//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

$imagepath = get_option('siteurl') . '/wp-content/';

if (!function_exists('GetAge')) {
    function GetAge($Birthdate) {
        $dob = strtotime($Birthdate);
        $y = date('Y', $dob);
        if (($m = (date('m') - date('m', $dob))) < 0) {
            $y++;
        } elseif ($m == 0 && date('d') - date('d', $dob) < 0) {
            $y++;
        }
        return date('Y') - $y;
    }
}

if (!function_exists('display_members_photo')) {
    function display_members_photo($photo_member_id, $path) {
        global $wpdb;
        $dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id='$photo_member_id' AND status_id=1");
        if ($count_member_images > 0) {
            $member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$photo_member_id' AND status_id=1");
            if ($member_exist_picture->picture == "") {
                $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
                if ($check_gender->gender == 'M') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                } else if ($check_gender->gender == 'F') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                } else if ($check_gender->gender == 'C') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                }
    //$Mem_Image_path=$path."images/no-image.jpg";
            } else {
                $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs1/thumb_" . $member_exist_picture->picture;
                $Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);
                if (@file_get_contents($Mem_Image_path)) {
                    $Mem_Image_path = $Mem_Image_path;
                } else {
                    $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
                    if ($check_gender->gender == 'M') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                    } else if ($check_gender->gender == 'F') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                    } else if ($check_gender->gender == 'C') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                    }
                }
            }
        } else {
            $check_gender = $wpdb->get_row("SELECT * FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
            if ($check_gender->gender == 'M') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
            } else if ($check_gender->gender == 'F') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
            } else if ($check_gender->gender == 'C') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
            }
    //$Mem_Image_path=$path."images/no-image.jpg";
        }
        return $Mem_Image_path;
    }
}    

if (!function_exists('check_free_trial_feature')) {
    function check_free_trial_feature($access_feature_name, $user_id) {

        global $wpdb;
        $dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
        $dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

        $general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_trail_gender'");
        $free_trail_gender = $general_settings->setting_value;

        $general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_email_access_gender'");
        $free_email_access_gender = $general_settings->setting_value;

        $free_trail_days_limit = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_trail_mode'");
        $free_trail_days = $free_trail_days_limit->setting_value;

        $dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
        $user_registered = $wpdb->get_row("SELECT * FROM $dsp_user_table where ID=$user_id");
        $user_registered->user_registered;
        $current_date = date('Y-m-d h:i:s', time());
        /* $diff = abs(strtotime($current_date) - strtotime($user_registered->user_registered)); 
          $years   = floor($diff / (365*60*60*24));
          $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)); */
        $days = daysDifference($current_date, ($user_registered->user_registered));

        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

        $gender_field = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles where user_id=$user_id");
        $user_gender = $gender_field->gender;

        $dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
        $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
        $pay_plan_id = $wpdb->get_var("SELECT pay_plan_id 	FROM $dsp_payments_table where pay_user_id=$user_id");

        $memberships_feature_row = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table where membership_id='" . $pay_plan_id . "'");

        if (sizeof($memberships_feature_row) > 0) {
            foreach ($memberships_feature_row as $membership_feature)
                $premium_access_feature = $membership_feature->premium_access_feature;
            if (!empty($premium_access_feature))
                $access_feature_id = explode(",", $premium_access_feature);
            else
                $access_feature_id = 0;
            for ($i = 0; $i < count($access_feature_id); ++$i) {
                $access_feature_id[$i];
                $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
                $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table where feature_id=" . $access_feature_id[$i]);
                foreach ($access_feature_row as $access_feature)
                    $feature_name = $access_feature->feature_name;
                if (isset($feature_name) && $feature_name == $access_feature_name)
                    $name = $feature_name;
            }
        }


        $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
        $dsp_premium_access_feature_table = $wpdb->prefix . DSP_PREMIUM_ACCESS_FEATURE_TABLE;
        if (isset($name)) {


            $features_list_id = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_name='$name'");



            $feature_id = $features_list_id->feature_id;
        } else {
            $feature_id = 0;
        } $premium_access_features = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_features_table where feature_id='$feature_id'");

        $check_free_email_access_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings WHERE setting_name = 'free_email_access'");
        $check_free_email_access_mode->setting_status;
        $check_force_profile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings WHERE setting_name = 'force_profile'");
        $check_force_profile_mode->setting_status;

        $user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id");

        $user_profile = $wpdb->get_row("SELECT status_id FROM $dsp_user_profiles WHERE user_id=$user_id");
        $status_id = $user_profile->status_id;

        if ($premium_access_features > 0) {
            $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
            if ($check_member_payment > 0) {
                $check_account_expire = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");
                $start_date = $check_account_expire->start_date;
                $payment_status = $check_account_expire->payment_status;
                $expiration_date = $check_account_expire->expiration_date;
                $pay_plan_days = $check_account_expire->pay_plan_days;
                $current_date = date("Y-m-d");
                $cal_days = daysDifference($current_date, $start_date);

                if ($cal_days > $pay_plan_days) {
                    if ($payment_status == '1') {
                        $wpdb->query("UPDATE $dsp_payments_table SET payment_status=2 WHERE pay_user_id = '$check_account_expire->pay_user_id'");
                    }
                    $msg = "Expired";
                } else {
                    $msg = "Access";
                } // End if($cal_expire_date>=$expiration_date)
            } else {
                $msg = "Onlypremiumaccess";
            } // End if($check_member_payment>0)
        } else if ($premium_access_features == 0) {
            $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
            if ($check_member_payment > 0) {
                $msg = "Access";
            } else {

                $memberships_feature_row = mysql_query("SELECT premium_access_feature FROM $dsp_memberships_table ");
                while ($row = mysql_fetch_array($memberships_feature_row)) {
                    $premium_access_feature = $row['premium_access_feature'];
                    if (!empty($premium_access_feature))
                        $access_feature_id = explode(",", $premium_access_feature);
                    else
                        $access_feature_id = 0;
                    for ($i = 0; $i < count($access_feature_id); ++$i) {
                        $access_feature_id[$i];

                        $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

                        $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table");
                        foreach ($access_feature_row as $access_feature) {
                            $feature_id = $access_feature->feature_id;
                            //echo $access_feature_id[$i]."-----------".$feature_id."<br>";
                            if ($access_feature_id[$i] == $feature_id)
                                $name = $access_feature_id[$i];
                        }
                        //echo $name;
                        //echo "SELECT * FROM $dsp_features_table where feature_id=$name";
                        $a = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_id=$name");
                        $feature_name = $a->feature_name;
                        if (isset($feature_name) && $feature_name == $access_feature_name)
                            $name1 = $feature_name;
                    }
                }
                //echo $name1; 
                if (@$name1 == '') {
                    $msg = "Access";
                } else if (($free_trail_gender == 1) && ($user_gender == 'M')) {
                    if ($days <= $free_trail_days) {
                        $msg = "Access";
                    } else {//Expired	
                        $msg = "Expired";
                    }
                } else if (($free_trail_gender == 2) && ($user_gender == 'F')) {
                    if ($days <= $free_trail_days) {
                        $msg = "Access";
                    } else {//Expired	
                        $msg = "Expired";
                    }
                } else if (($free_trail_gender == 3)) {
                    if ($days <= $free_trail_days) {
                        $msg = "Access";
                    } else {//Expired	
                        $msg = "Expired";
                    }
                } else if ($user_profile_exist == 0) {
                    $msg = "NotExist";
                } else if ($status_id == 0) {
                    $msg = "Approved";
                } else {
                    $msg = "Onlypremiumaccess";
                }
            }
        } else {

            $msg = "NoAccess";
        }

        return $msg;
    }
}

// ------------------End function to check free trail mode  -----------------------//

if (!function_exists('daysDifference')) {
    function daysDifference($endDate, $beginDate) {
        //explode the date by "-" and storing to array
        $date_parts1 = explode("-", $beginDate);
        $date_parts2 = explode("-", $endDate);

        //gregoriantojd() Converts a Gregorian date to Julian Day Count
        @$start_date = gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
        @$end_date = gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
        return $end_date - $start_date;
    }
}
// ------------------ calculate date difrence -----------------------//
// ------------------function to check member has a membershp plan to Access Premium feature -----------------------//
if (!function_exists('check_membership')) {
    function check_membership($access_feature_name, $user_id) {
        global $wpdb;
        $dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
        $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
        $pay_plan_id = $wpdb->get_var("SELECT pay_plan_id 	FROM $dsp_payments_table where pay_user_id=$user_id");

        $memberships_feature_row = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table where membership_id='" . $pay_plan_id . "'");

        if (sizeof($memberships_feature_row) > 0) {

            foreach ($memberships_feature_row as $membership_feature)
                $premium_access_feature = $membership_feature->premium_access_feature;

            if (!empty($premium_access_feature))
                $access_feature_id = explode(",", $premium_access_feature);
            else
                $access_feature_id = 0;

            for ($i = 0; $i < count($access_feature_id); ++$i) {
                $access_feature_id[$i];
                $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
                $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table where feature_id=" . $access_feature_id[$i]);
                foreach ($access_feature_row as $access_feature)
                    $feature_name = $access_feature->feature_name;
                if (isset($feature_name) && $feature_name == $access_feature_name)
                    $name = $feature_name;
            }
        }
        $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
        $dsp_premium_access_feature_table = $wpdb->prefix . DSP_PREMIUM_ACCESS_FEATURE_TABLE;
        if (isset($name)) {
            $features_list_id = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_name='$name'");
            $feature_id = $features_list_id->feature_id;
        } else {
            $feature_id = 0;
        }
        $premium_access_features = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_features_table where feature_id='$feature_id'");
        if ($premium_access_features > 0) {
            $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
            if ($check_member_payment > 0) {
                $check_account_expire = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");
                $start_date = $check_account_expire->start_date;
                $payment_status = $check_account_expire->payment_status;
                $expiration_date = $check_account_expire->expiration_date;
                $pay_plan_days = $check_account_expire->pay_plan_days;
                $current_date = date("Y-m-d");
                $cal_days = daysDifference($current_date, $start_date);

                if ($cal_days > $pay_plan_days) {
                    if ($payment_status == '1') {
                        $wpdb->query("UPDATE $dsp_payments_table SET payment_status=2 WHERE pay_user_id = '$check_account_expire->pay_user_id'");
                    }
                    $msg = "Expired";
                } else {
                    $msg = "Access";
                } // End if($cal_expire_date>=$expiration_date)
            } else {
                $msg = "Onlypremiumaccess";
            } // End if($check_member_payment>0)
        } else if ($premium_access_features == 0) {
            $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
            if ($check_member_payment > 0) {
                $msg = "Access";
            } else {
                $memberships_feature_row = mysql_query("SELECT premium_access_feature FROM $dsp_memberships_table ");
                while ($row = mysql_fetch_array($memberships_feature_row)) {
                    $premium_access_feature = $row['premium_access_feature'];
                    if (!empty($premium_access_feature))
                        $access_feature_id = explode(",", $premium_access_feature);
                    else
                        $access_feature_id = 0;
                    for ($i = 0; $i < count($access_feature_id); ++$i) {
                        $access_feature_id[$i];

                        $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

                        $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table");
                        foreach ($access_feature_row as $access_feature) {
                            $feature_id = $access_feature->feature_id;
                            //echo $access_feature_id[$i]."-----------".$feature_id."<br>";
                            if ($access_feature_id[$i] == $feature_id)
                                $name = $access_feature_id[$i];
                        }
                        //echo $name;
                        //echo "SELECT * FROM $dsp_features_table where feature_id=$name";
                        $a = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_id=$name");
                        $feature_name = $a->feature_name;
                        if (isset($feature_name) && $feature_name == $access_feature_name)
                            $name1 = $feature_name;
                    }
                }
                //echo $name1; 
                if (@$name1 == '') {
                    $msg = "Access";
                } else {
                    $msg = "Onlypremiumaccess";
                }
            }
        } else {
            $msg = "Access";
        } // End if($premium_access_features>0)
        return $msg;
    }
}    

// End function 
// ------------------function to check member has a membershp plan to Access Premium feature -----------------------//
// ------------------function to check free trail mode  -----------------------//

if (!function_exists('check_free_trial_email_feature')) {
    function check_free_trial_email_feature($access_feature_name, $user_id) {

        global $wpdb;
        $dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

        $general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_trail_gender'");
        $free_trail_gender = $general_settings->setting_value;

        $general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_email_access_gender'");
        $free_email_access_gender = $general_settings->setting_value;

        $free_trail_days_limit = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_trail_mode'");
        $free_trail_days = $free_trail_days_limit->setting_value;

        $dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
        $user_registered = $wpdb->get_row("SELECT * FROM $dsp_user_table where ID=$user_id");
        $user_registered->user_registered;
        $current_date = date('Y-m-d h:i:s', time());
        /* $diff = abs(strtotime($current_date) - strtotime($user_registered->user_registered)); 
          $years   = floor($diff / (365*60*60*24));
          $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)); */
        $days = daysDifference($current_date, ($user_registered->user_registered));

        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

        $gender_field = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles where user_id=$user_id");
        $user_gender = $gender_field->gender;

        $dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
        $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
        $pay_plan_id = $wpdb->get_var("SELECT pay_plan_id 	FROM $dsp_payments_table where pay_user_id=$user_id");


        $memberships_feature_row = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table where membership_id='" . $pay_plan_id . "'");

        if (sizeof($memberships_feature_row) > 0) {
            foreach ($memberships_feature_row as $membership_feature)
                $premium_access_feature = $membership_feature->premium_access_feature;


            if (!empty($premium_access_feature))
                $access_feature_id = explode(",", $premium_access_feature);
            else
                $access_feature_id = 0;


            for ($i = 0; $i < count($access_feature_id); ++$i) {
                $access_feature_id[$i];
                $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

                $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table where feature_id=" . $access_feature_id[$i]);
                foreach ($access_feature_row as $access_feature)
                    $feature_name = $access_feature->feature_name;
                if (isset($feature_name) && $feature_name == $access_feature_name)
                    $name = $feature_name;
            }
        }

        $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
        $dsp_premium_access_feature_table = $wpdb->prefix . DSP_PREMIUM_ACCESS_FEATURE_TABLE;

        if (isset($name)) {
            $features_list_id = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_name='$name'");
            $feature_id = $features_list_id->feature_id;
        } else {
            $feature_id = 0;
        }

        $premium_access_features = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_features_table where feature_id='$feature_id'");

        $check_free_email_access_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings WHERE setting_name = 'free_email_access'");
        $check_free_email_access_mode->setting_status;
        $check_force_profile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings WHERE setting_name = 'force_profile'");
        $check_force_profile_mode->setting_status;

        $user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id");

        $user_profile = $wpdb->get_row("SELECT status_id FROM $dsp_user_profiles WHERE user_id=$user_id");
        $status_id = $user_profile->status_id;

        if ($premium_access_features > 0) {
            $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
            if ($check_member_payment > 0) {
                $check_account_expire = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");
                $start_date = $check_account_expire->start_date;
                $payment_status = $check_account_expire->payment_status;
                $expiration_date = $check_account_expire->expiration_date;
                $pay_plan_days = $check_account_expire->pay_plan_days;
                $current_date = date("Y-m-d");
                $cal_days = daysDifference($current_date, $start_date);

                if ($cal_days > $pay_plan_days) {
                    if ($payment_status == '1') {
                        $wpdb->query("UPDATE $dsp_payments_table SET payment_status=2 WHERE pay_user_id = '$check_account_expire->pay_user_id'");
                    }
                    $msg = "Expired";
                } else {
                    $msg = "Access";
                } // End if($cal_expire_date>=$expiration_date)
            } else {
                $msg = "Onlypremiumaccess";
            } // End if($check_member_payment>0)
        } else if ($premium_access_features == 0) {
            $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
            if ($check_member_payment > 0) {
                $msg = "Access";
            } else {

                $memberships_feature_row = mysql_query("SELECT premium_access_feature FROM $dsp_memberships_table ");
                while ($row = mysql_fetch_array($memberships_feature_row)) {
                    $premium_access_feature = $row['premium_access_feature'];
                    if (!empty($premium_access_feature))
                        $access_feature_id = explode(",", $premium_access_feature);
                    else
                        $access_feature_id = 0;
                    for ($i = 0; $i < count($access_feature_id); ++$i) {
                        $access_feature_id[$i];

                        $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

                        $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table");
                        foreach ($access_feature_row as $access_feature) {
                            $feature_id = $access_feature->feature_id;
                            //echo $access_feature_id[$i]."-----------".$feature_id."<br>";
                            if ($access_feature_id[$i] == $feature_id)
                                $name = $access_feature_id[$i];
                        }
                        //echo $name;
                        //echo "SELECT * FROM $dsp_features_table where feature_id=$name";
                        $a = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_id=$name");
                        $feature_name = $a->feature_name;
                        if (isset($feature_name) && $feature_name == $access_feature_name)
                            $name1 = $feature_name;
                    }
                }
                //echo $name1; 
                if (@$name1 == '') {
                    $msg = "Access";
                } else if (($free_trail_gender == 1) && ($user_gender == 'M')) {
                    if ($days <= $free_trail_days) {
                        $msg = "Access";
                    } else {//Expired	
                        $msg = "Expired";
                    }
                } else if (($free_trail_gender == 2) && ($user_gender == 'F')) {
                    if ($days <= $free_trail_days) {
                        $msg = "Access";
                    } else {//Expired	
                        $msg = "Expired";
                    }
                } else if (($free_trail_gender == 3)) {
                    if ($days <= $free_trail_days) {
                        $msg = "Access";
                    } else {//Expired	
                        $msg = "Expired";
                    }
                } else if ($user_profile_exist == 0) {
                    $msg = "NotExist";
                } else if ($status_id == 0) {
                    $msg = "Approved";
                } else if ($check_free_email_access_mode->setting_status == "Y") {

                    if (($free_email_access_gender == 1) && ($user_gender == 'M')) {
                        $msg = "Access";
                    } else if (($free_email_access_gender == 2) && ($user_gender == 'F')) {
                        $msg = "Access";
                    } else {
                        $msg = "Onlypremiumaccess";
                    }
                } else {
                    $msg = "Onlypremiumaccess";
                }
            }
        } else {

            $msg = "NoAccess";
        }

        return $msg;
    }
}

$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$check_userplane_instant_messenger_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'userplane_instant_messenger'");
$check_userplane_instant_messenger_mode->setting_status;
$dsp_userplane_table = $wpdb->prefix . DSP_USERPLANE_TABLE;
$userplane_active = $wpdb->get_var("SELECT active_im FROM $dsp_userplane_table");

// START FUNCTION CREATE thumb2 MEMBER PHOTO PATH
if (!function_exists('display_thumb2_members_photo')) {
    function display_thumb2_members_photo($photo_member_id, $path) {
        global $wpdb;
        $dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id='$photo_member_id' AND status_id=1");
        if ($count_member_images > 0) {
            $member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$photo_member_id' AND status_id=1");
            if ($member_exist_picture->picture == "") {
                $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
                if ($check_gender->gender == 'M') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                } else if ($check_gender->gender == 'F') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                } else if ($check_gender->gender == 'C') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                }
    //$Mem_Image_path=$path."images/no-image.jpg";
            } else {
                $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                $Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);
                if (@file_get_contents($Mem_Image_path)) {
                    $Mem_Image_path = $Mem_Image_path;
                } else {
                    $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
                    if ($check_gender->gender == 'M') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                    } else if ($check_gender->gender == 'F') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                    } else if ($check_gender->gender == 'C') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                    }
                }
            }
        } else {
            $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
            if ($check_gender->gender == 'M') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
            } else if ($check_gender->gender == 'F') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
            } else if ($check_gender->gender == 'C') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
            }
    //$Mem_Image_path=$path."images/no-image.jpg";
        }
        return $Mem_Image_path;
    }
}

// END FUNCTION CREATE thumb2  MEMBER PHOTO PATH
// START FUNCTION CREATE MEMBER PHOTO PATH
if (!function_exists('display_members_original_photo')) {
    function display_members_original_photo($photo_member_id, $path) {
        global $wpdb;
        $dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id='$photo_member_id' AND status_id=1");
        if ($count_member_images > 0) {
            $member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$photo_member_id' AND status_id=1");
            if ($member_exist_picture->picture == "") {
                $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
                if ($check_gender->gender == 'M') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                } else if ($check_gender->gender == 'F') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                } else if ($check_gender->gender == 'C') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                }
            } else {
                $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/" . $member_exist_picture->picture;
                $Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);
                if (@file_get_contents($Mem_Image_path)) {
                    $Mem_Image_path = $Mem_Image_path;
                } else {
                    $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
                    if ($check_gender->gender == 'M') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                    } else if ($check_gender->gender == 'F') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                    } else if ($check_gender->gender == 'C') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                    }
                }
            }
        } else {
            $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
            if ($check_gender->gender == 'M') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
            } else if ($check_gender->gender == 'F') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
            } else if ($check_gender->gender == 'C') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
            }
    //$Mem_Image_path=$path."images/no-image.jpg";
        }
        return $Mem_Image_path;
    }
}

if (!function_exists('check_force_profile_feature')) {
    function check_force_profile_feature($access_feature_name, $user_id) {

        global $wpdb;


        $dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='force_profile'");
        $force_profile = $general_settings->setting_status;


        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;


        $user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id AND country_id!=0");

        if ($user_profile_exist == 0) {
            $status_id = 0;
        } else {
            $user_profile = $wpdb->get_row("SELECT status_id FROM $dsp_user_profiles WHERE user_id=$user_id");
            $status_id = $user_profile->status_id;
        }



        $dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
        $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
        $features_list_id = $wpdb->get_var("SELECT pay_plan_id 	FROM $dsp_payments_table where pay_user_id=$user_id");
        $pay_plan_id = $features_list_id;
        $memberships_feature_row = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table where membership_id='" . $pay_plan_id . "'");

        if (sizeof($memberships_feature_row) > 0) {
            foreach ($memberships_feature_row as $membership_feature)
                $premium_access_feature = $membership_feature->premium_access_feature;
            if (!empty($premium_access_feature))
                $access_feature_id = explode(",", $premium_access_feature);
            else
                $access_feature_id = 0;
            for ($i = 0; $i < count($access_feature_id); ++$i) {
                $access_feature_id[$i];
                $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
                $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table where feature_id=" . $access_feature_id[$i]);
                foreach ($access_feature_row as $access_feature)
                    $feature_name = $access_feature->feature_name;
                if (isset($feature_name) && $feature_name == $access_feature_name)
                    $name = $feature_name;
            }
        }
        $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
        $dsp_premium_access_feature_table = $wpdb->prefix . DSP_PREMIUM_ACCESS_FEATURE_TABLE;
        if (isset($name)) {


            $features_list_id = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_name='$name'");



            $feature_id = $features_list_id->feature_id;
        } else {
            $feature_id = 0;
        }
        $premium_access_features = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_features_table where feature_id='$feature_id'");

        if ($user_profile_exist == 0) {
            $msg = "NoAccess";
        } else if ($status_id == 0) {
            $msg = "Approved";
        } elseif ($premium_access_features > 0) {
            $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
            if ($check_member_payment > 0) {
                $check_account_expire = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");
                $start_date = $check_account_expire->start_date;
                $payment_status = $check_account_expire->payment_status;
                $expiration_date = $check_account_expire->expiration_date;
                $pay_plan_days = $check_account_expire->pay_plan_days;
                $current_date = date("Y-m-d");
                $cal_days = daysDifference($current_date, $start_date);

                if ($cal_days > $pay_plan_days) {
                    if ($payment_status == '1') {
                        $wpdb->query("UPDATE $dsp_payments_table SET payment_status=2 WHERE pay_user_id = '$check_account_expire->pay_user_id'");
                    }
                    $msg = "Expired";
                } else {
                    $msg = "Access";
                } // End if($cal_expire_date>=$expiration_date)
            } else {
                $msg = "Onlypremiumaccess";
            } // End if($check_member_payment>0)
        } else if ($premium_access_features == 0) {
            $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
            if ($check_member_payment > 0) {
                $msg = "Access";
            } else {

                $memberships_feature_row = mysql_query("SELECT premium_access_feature FROM $dsp_memberships_table ");
                while ($row = mysql_fetch_array($memberships_feature_row)) {
                    $premium_access_feature = $row['premium_access_feature'];
                    if (!empty($premium_access_feature))
                        $access_feature_id = explode(",", $premium_access_feature);
                    else
                        $access_feature_id = 0;
                    for ($i = 0; $i < count($access_feature_id); ++$i) {
                        $access_feature_id[$i];

                        $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

                        $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table");
                        foreach ($access_feature_row as $access_feature) {
                            $feature_id = $access_feature->feature_id;
                            //echo $access_feature_id[$i]."-----------".$feature_id."<br>";
                            if ($access_feature_id[$i] == $feature_id)
                                $name = $access_feature_id[$i];
                        }
                        //echo $name;
                        //echo "SELECT * FROM $dsp_features_table where feature_id=$name";
                        $a = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_id=$name");
                        $feature_name = $a->feature_name;
                        if (isset($feature_name) && $feature_name == $access_feature_name)
                            $name1 = $feature_name;
                    }
                }
                //echo $name1; 
                if (@$name1 == '') {
                    $msg = "Access";
                } else {
                    $msg = "Onlypremiumaccess";
                }
            }
        } else {
            $msg = "Access";
        }
        return $msg;
    }
}

if (!function_exists('check_free_email_feature')) {
    function check_free_email_feature($access_feature_name, $user_id) {
        global $wpdb;
        $dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='free_email_access_gender'");
        $free_email_access_gender = $general_settings->setting_value;

        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $gender_field = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles where user_id=$user_id");
        $user_gender = $gender_field->gender;

        $dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
        $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
        $features_list_id = $wpdb->get_var("SELECT pay_plan_id 	FROM $dsp_payments_table where pay_user_id=$user_id");
        $pay_plan_id = $features_list_id;
        $memberships_feature_row = $wpdb->get_results("SELECT premium_access_feature FROM $dsp_memberships_table where membership_id='" . $pay_plan_id . "'");
        foreach ($memberships_feature_row as $membership_feature)
            $premium_access_feature = $membership_feature->premium_access_feature;
        if (!empty($premium_access_feature))
            $access_feature_id = explode(",", $premium_access_feature);
        else
            $access_feature_id = 0;
        for ($i = 0; $i < count($access_feature_id); ++$i) {
            $access_feature_id[$i];
            $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
            $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table where feature_id=" . $access_feature_id[$i]);
            foreach ($access_feature_row as $access_feature)
                $feature_name = $access_feature->feature_name;
            if (isset($feature_name) && $feature_name == $access_feature_name)
                $name = $feature_name;
        }
        $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
        $dsp_premium_access_feature_table = $wpdb->prefix . DSP_PREMIUM_ACCESS_FEATURE_TABLE;
        if (isset($name)) {


            $features_list_id = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_name='$name'");



            $feature_id = $features_list_id->feature_id;
        } else {
            $feature_id = 0;
        } $premium_access_features = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_features_table where feature_id='$feature_id'");


        if (($free_email_access_gender == 1) && ($user_gender == 'M')) {
            $msg = "Access";
        } else if (($free_email_access_gender == 2) && ($user_gender == 'F')) {
            $msg = "Access";
        } else if ($premium_access_features > 0) {
            $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
            if ($check_member_payment > 0) {
                $check_account_expire = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");
                $start_date = $check_account_expire->start_date;
                $payment_status = $check_account_expire->payment_status;
                $expiration_date = $check_account_expire->expiration_date;
                $pay_plan_days = $check_account_expire->pay_plan_days;
                $current_date = date("Y-m-d");
                $cal_days = daysDifference($current_date, $start_date);

                if ($cal_days > $pay_plan_days) {
                    if ($payment_status == '1') {
                        $wpdb->query("UPDATE $dsp_payments_table SET payment_status=2 WHERE pay_user_id = '$check_account_expire->pay_user_id'");
                    }
                    $msg = "Expired";
                } else {
                    $msg = "Access";
                } // End if($cal_expire_date>=$expiration_date)
            } else {
                $msg = "Onlypremiumaccess";
            } // End if($check_member_payment>0)
        } else if ($premium_access_features == 0) {
            $check_member_payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
            if ($check_member_payment > 0) {
                $msg = "Access";
            } else {

                $memberships_feature_row = mysql_query("SELECT premium_access_feature FROM $dsp_memberships_table ");
                while ($row = mysql_fetch_array($memberships_feature_row)) {
                    $premium_access_feature = $row['premium_access_feature'];
                    if (!empty($premium_access_feature))
                        $access_feature_id = explode(",", $premium_access_feature);
                    else
                        $access_feature_id = 0;
                    for ($i = 0; $i < count($access_feature_id); ++$i) {
                        $access_feature_id[$i];

                        $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;

                        $access_feature_row = $wpdb->get_results("SELECT * FROM $dsp_features_table");
                        foreach ($access_feature_row as $access_feature) {
                            $feature_id = $access_feature->feature_id;
                            //echo $access_feature_id[$i]."-----------".$feature_id."<br>";
                            if ($access_feature_id[$i] == $feature_id)
                                $name = $access_feature_id[$i];
                        }
                        //echo $name;
                        //echo "SELECT * FROM $dsp_features_table where feature_id=$name";
                        $a = $wpdb->get_row("SELECT * FROM $dsp_features_table where feature_id=$name");
                        $feature_name = $a->feature_name;
                        if (isset($feature_name) && $feature_name == $access_feature_name)
                            $name1 = $feature_name;
                    }
                }
                //echo $name1; 
                if (@$name1 == '') {
                    $msg = "Access";
                } else {
                    $msg = "Onlypremiumaccess";
                }
            }
        } else {
            $msg = "NoAccess";
        }
        return $msg;
    }
}

if (!function_exists('check_free_force_profile_feature')) {
    function check_free_force_profile_feature($user_id) {
        global $wpdb;
        $dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $general_settings = $wpdb->get_row("SELECT * FROM $dsp_general_settings where setting_name='force_profile'");
        $force_profile = $general_settings->setting_status;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;


        $user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id and seeking!=''");

        $user_profile = $wpdb->get_var("SELECT status_id FROM $dsp_user_profiles WHERE user_id=$user_id");
        $status_id = $user_profile;


        if ($user_profile_exist == 0) {
            $msg = "NoAccess";
        } else if ($status_id == 0) {
            $msg = "Approved";
        } else {
            $msg = "Access";
        }
        return $msg;
    }
}

/* * *******************START FUNCTION CREATE thumb MEMBER PARTNER PHOTO PATH************************ */
if (!function_exists('display_members_partner_photo')) {
    function display_members_partner_photo($photo_member_id, $path) {
        global $wpdb;
        $dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
        $dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

    //echo "SELECT COUNT(*) FROM $dsp_members_partner_photos_table WHERE user_id='$photo_member_id' AND status_id=1";
        $count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_partner_photos_table WHERE user_id='$photo_member_id' AND status_id=1");
        if ($count_member_images > 0) {
            $member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_partner_photos_table WHERE user_id = '$photo_member_id' AND status_id=1");
            if ($member_exist_picture->picture == "") {
                //echo "<br>SELECT gender FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'";
                $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");
                if ($check_gender->gender == 'M') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                } else if ($check_gender->gender == 'F') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                } else if ($check_gender->gender == 'C') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                }
    //$Mem_Image_path=$path."images/no-image.jpg";
            } else {
                $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs1/thumb_" . $member_exist_picture->picture;
    //echo '<br>path'.$Mem_Image_path;
                $Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);
                if (@file_get_contents($Mem_Image_path)) {
                    //echo 'path exist';
                    $Mem_Image_path = $Mem_Image_path;
                } else {
                    //echo 'not';
                    $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
                    if ($check_gender->gender == 'M') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                    } else if ($check_gender->gender == 'F') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                    } else if ($check_gender->gender == 'C') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                    }
                }
            }
        } else {
            $check_gender = $wpdb->get_row("SELECT * FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");
            $count_profile_partner = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");
            if ($count_profile_partner > 0) {
                if ($check_gender->gender == 'M') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                } else if ($check_gender->gender == 'F') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                } else if ($check_gender->gender == 'C') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                }
            } else {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
            }
    //$Mem_Image_path=$path."images/no-image.jpg";
        }
        return $Mem_Image_path;
    }
}    

if (!function_exists('display_thumb2_members_partner_photo')) {
    function display_thumb2_members_partner_photo($photo_member_id, $path) {
        global $wpdb;
        $dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
        $dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
        $count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_partner_photos_table WHERE user_id='$photo_member_id' AND status_id=1");
        if ($count_member_images > 0) {
            $member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_partner_photos_table WHERE user_id = '$photo_member_id' AND status_id=1");
            if ($member_exist_picture->picture == "") {
                $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");
                if ($check_gender->gender == 'M') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                } else if ($check_gender->gender == 'F') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                } else if ($check_gender->gender == 'C') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                }
    //$Mem_Image_path=$path."images/no-image.jpg";
            } else {
                $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                $Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);
                if (@file_get_contents($Mem_Image_path)) {
                    $Mem_Image_path = $Mem_Image_path;
                } else {
                    $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
                    if ($check_gender->gender == 'M') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                    } else if ($check_gender->gender == 'F') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                    } else if ($check_gender->gender == 'C') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                    }
                }
            }
        } else {
            $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");
            if ($check_gender->gender == 'M') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
            } else if ($check_gender->gender == 'F') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
            } else if ($check_gender->gender == 'C') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
            }
    //$Mem_Image_path=$path."images/no-image.jpg";
        }
        return $Mem_Image_path;
    }
}    

if (!function_exists('display_members_partner_original_photo')) {
    function display_members_partner_original_photo($photo_member_id, $path) {
        global $wpdb;
        $dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
        $dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
        $count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_partner_photos_table WHERE user_id='$photo_member_id' AND status_id=1");
        if ($count_member_images > 0) {
            $member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_partner_photos_table WHERE user_id = '$photo_member_id' AND status_id=1");
            if ($member_exist_picture->picture == "") {
                $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");
                if ($check_gender->gender == 'M') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                } else if ($check_gender->gender == 'F') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                } else if ($check_gender->gender == 'C') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                }
            } else {
                $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/" . $member_exist_picture->picture;
                $Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);
                if (@file_get_contents($Mem_Image_path)) {
                    $Mem_Image_path = $Mem_Image_path;
                } else {
                    $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
                    if ($check_gender->gender == 'M') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                    } else if ($check_gender->gender == 'F') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                    } else if ($check_gender->gender == 'C') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                    }
                }
            }
        } else {
            $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_partner_profiles_table  WHERE user_id = '$photo_member_id'");
            if ($check_gender->gender == 'M') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
            } else if ($check_gender->gender == 'F') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
            } else if ($check_gender->gender == 'C') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
            }
    //$Mem_Image_path=$path."images/no-image.jpg";
        }
        return $Mem_Image_path;
    }
}

if (!function_exists('check_approved_profile_feature')) {
    function check_approved_profile_feature($user_id) {
        global $wpdb;

        // ----------------------------------------------- check prfile is approved or not------------------------------------------------------ // 
        $dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $profile_status = $wpdb->get_row("SELECT * FROM $dsp_user_profiles_table WHERE user_id = '$user_id'");
        $pstatus = $profile_status->status_id;

        $count_user_profile = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles_table WHERE user_id='$user_id'");
        /* if($count_user_profile==0) 
          {
          $msg= "NoExist";
          }else */ if ($pstatus == 0) {
            $msg = "NoAccess";
        } else {
            $msg = "Access";
        }
        return $msg;
    }
}    

?>