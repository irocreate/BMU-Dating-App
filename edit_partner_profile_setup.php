<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

$zipcode = isset($_REQUEST['zip']) ? esc_sql(sanitizeData(trim($_REQUEST['zip']), 'xss_clean')) : '';
$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
$dsp_partner_profile_question_details_table = $wpdb->prefix . DSP_PARTNER_PROFILE_QUESTIONS_DETAILS;
$dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");
$check_partner_profile_exist = $wpdb->get_var("SELECT count(*) FROM $dsp_user_partner_profiles_table WHERE user_id = '$current_user->ID'");
$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_partner_profiles_table WHERE user_id = '$current_user->ID'");
if ($check_partner_profile_exist != 0)
    $user_partner_profile_id = $exist_profile_details->user_id;
else
    $user_partner_profile_id = "0";
if ($gender == '') {
    if ($check_partner_profile_exist != 0)
        $gender = $exist_profile_details->gender;
    else
        $gender = "";
}
$seeking = isset($_REQUEST['seeking']) ? $_REQUEST['seeking'] : '';
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$question_option_id = isset($_REQUEST['option_id']) ? $_REQUEST['option_id'] : '';
$question_option_id1 = isset($_REQUEST['option_id1']) ? $_REQUEST['option_id1'] : '';
$question_option_id2 = isset($_REQUEST['option_id2']) ? apply_filters('dsp_filter_empty_array_values',$_REQUEST['option_id2'])  : '';
$month = isset($_REQUEST['dsp_mon']) ? $_REQUEST['dsp_mon'] : '';
$day = isset($_REQUEST['dsp_day']) ? $_REQUEST['dsp_day'] : '';
$year = isset($_REQUEST['dsp_year']) ? $_REQUEST['dsp_year'] : '';
$aboutme = isset($_REQUEST['txtaboutme']) ? esc_sql(sanitizeData(trim($_REQUEST['txtaboutme']), 'xss_clean')) : '';
$my_interest = isset($_REQUEST['my_interest']) ? esc_sql(sanitizeData(trim($_REQUEST['my_interest']), 'xss_clean')) : '';
$age = $year . "-" . $month . "-" . $day;
$last_update_date = date('Y-m-d H:i:s');
if (isset($_POST['private']) && $_POST['private'] != '') {
    $make_private = $_POST['private'];
} else {
    $make_private = 'N';
}
// $country_id = $_POST['cmbCountry'];
$countryName = isset($_REQUEST['cmbCountry']) ? $_REQUEST['cmbCountry'] : '';
$get_Country = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE name = '" . $countryName . "'");
if ($countryName != "Select" && !empty($countryName))
    $countryId = $get_Country->country_id;
else
    $countryId = "0";
// $state_id =$_POST['cmbState'];
$stateId = isset($_REQUEST['cmbState']) ? $_REQUEST['cmbState'] : 0;

$get_State = $wpdb->get_row("SELECT * FROM $dsp_state_table WHERE state_id = '" . $stateId . "'");

if ($stateId != 0 && !empty($stateId))
    $stateName = $get_State->name;
else
    $stateName = "";//$city_id = $_POST['cmbCity'];

$cityId = isset($_REQUEST['cmbCity']) ? $_REQUEST['cmbCity'] : 0;

if ($stateId == 0) {
    $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE city_id = '" . $cityId . "' and country_id=" . $countryId);
} else {
    $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE city_id = '" . $cityId . "' and state_id=" . $stateId);
}
if ($cityId != 0 && !empty($cityId)) {
    if (!empty($get_City->city_id)) {
        $cityId = $get_City->city_id;
    } else {
        $cityId = "";
    }
} else
    $cityId = "";
if (isset($_POST['submit1'])) {
//Check to make sure that the Country field is not empty
    if (trim($_POST['cmbCountry']) == '0') {
        $nameError = language_code('DSP_ERROR_FORGOT_SELECT_COUNTRY_FIELD');
        $hasError = true;
    } else {
        $country_id = trim($_POST['cmbCountry']);
    }
    $zipcode = $zipcode;


    //Check to make sure that the About Me field is not empty
    if (trim($_POST['txtaboutme']) === '') {
        $aboutmeError = language_code('DSP_FORGOT_ABOUT_ME_MSG');
        $hasError = true;
    } else {
        $aboutme = $aboutme;
    }
    // Checked textbox Profile question is required or not 
    foreach ($question_option_id1 as $key => $value) {
        //echo "SELECT COUNT(*) FROM $dsp_profile_setup_table WHERE profile_setup_id='$key' AND required='Y'";
        $check_required = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_profile_setup_table WHERE profile_setup_id='$key' AND required='Y'");
        if ($check_required > 0) {
            if ($value == '') {
                $ques_val = $wpdb->get_row("SELECT question_name FROM $dsp_profile_setup_table WHERE profile_setup_id='$key'");
                $required_Error1[] = $ques_val->question_name;
                $hasError = true;
            } // end if($value == '')
        }  // End if($check_required>0)
    } // End foreach($question_option_id1 as $key=>$value)
    // Checked dropdown Profile question is required or not 
    foreach ($question_option_id as $key => $value) {
        $check_required = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_profile_setup_table WHERE profile_setup_id='$key' AND required='Y'");
        if ($check_required > 0) {
            if ($value == 0) {
                $ques_val = $wpdb->get_row("SELECT question_name FROM $dsp_profile_setup_table WHERE profile_setup_id='$key'");
                $required_Error2[] = $ques_val->question_name;
                $hasError = true;
            } // end if($value == '')
        }  // End if($check_required>0)
    } // End foreach($question_option_id1 as $key=>$value)
    // start function myinterest_cloud 

    function myinterest_cloud($my_interest) {
        global $wpdb;
        $dsp_interest_tags_table = $wpdb->prefix . DSP_INTEREST_TAGS_TABLE;
        $strInterest = $my_interest;
        $tag_array = explode(",", strtolower(trim($strInterest)));

        for ($intCounter = 0; $intCounter < count($tag_array); $intCounter++) {
            //echo "SELECT count(*) as ifExists FROM " . $dsp_interest_tags_table . " WHERE keyword = '" . strtolower(trim($tag_array[$intCounter])) . "' <br>";

            $interest_tags_table = $wpdb->get_var("SELECT count(*) as ifExists FROM " . $dsp_interest_tags_table . " WHERE keyword = '" . strtolower(trim($tag_array[$intCounter])) . "'");
            if ($interest_tags_table == 0) {
                $strExecuteQuery = "INSERT INTO " . $dsp_interest_tags_table . " VALUES (0,'" . strtolower(trim($tag_array[$intCounter])) . "',1,'NA')";
            } else {
                $strExecuteQuery = "UPDATE " . $dsp_interest_tags_table . " SET weight = weight+1 WHERE keyword = '" . strtolower(trim($tag_array[$intCounter])) . "'";
            }
            //print "<br>" . $strExecuteQuery . "<br>";
            $wpdb->query($strExecuteQuery);
        }
    }

// End function myinterest_cloud 
    //If there is no error, then profile updated
    if (!isset($hasError)) {
        if ($mode == "update") {
            if ($check_approve_profile_status->setting_status == 'Y') {  // if Profile approve status is Y then Profile Automatically Approved.
                $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_partner_profiles_table WHERE user_id=$current_user->ID");
                if ($num_rows == 0) {
                    $wpdb->query("INSERT INTO $dsp_user_partner_profiles_table SET user_id = $current_user->ID,country_id = '$countryId',state_id='$stateId',city_id = '$cityId',gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id=1,last_update_date='$last_update_date',about_me='$aboutme', edited='Y', my_interest='$my_interest',make_private='$make_private'");

                    if ($my_interest != '') {
                        myinterest_cloud($my_interest);
                    }

                    $user = $wpdb->get_row("SELECT user_login FROM $dsp_user_table WHERE ID= $current_user->ID");
                    $user_name = $user->user_login;
                    $to = get_option('admin_email');
                    $from = $to;

                    $headers = language_code('DSP_FROM') . $from . "\r\nContent-type: text/html; charset=us-ascii\n";
                    $subject = " New profile create";
                    $message = "A new user $user_name has created a profile. You can view their profile by <a href='" . $root_link . "?pgurl=view_member&mem_id=$current_user->ID&guest_pageurl=view_mem_profile'>clicking here</a>";

                    wp_mail($to, $subject, $message, $headers);
                } else {
                    $wpdb->query("UPDATE $dsp_user_partner_profiles_table SET country_id = '$countryId',state_id='$stateId',city_id = '$cityId',gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id=1,last_update_date='$last_update_date',about_me='$aboutme', my_interest='$my_interest',make_private='$make_private' WHERE user_id  = '$current_user->ID'");
                    if ($my_interest != '') {
                        myinterest_cloud($my_interest);
                    }

                    $user = $wpdb->get_row("SELECT user_login FROM $dsp_user_table WHERE ID= $current_user->ID");
                    $user_name = $user->user_login;
                    $to = get_option('admin_email');
                    $from = $to;

                    $headers = language_code('DSP_FROM') . $from . "\r\nContent-type: text/html; charset=us-ascii\n";
                    $subject = " New profile create";
                    $message = "A new user $user_name has created a profile. You can view their profile by <a href='" . $root_link . "?pgurl=view_member&mem_id=$current_user->ID&guest_pageurl=view_mem_profile'>clicking here</a>";

                    wp_mail($to, $subject, $message, $headers);
                } // if($num_rows==0)
            } else {

                $count_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_partner_profiles_table WHERE user_id=$current_user->ID");
                if ($count_rows > 0) {
                    $wpdb->query("UPDATE $dsp_user_partner_profiles_table SET country_id = '$countryId',state_id='$stateId',city_id = '$cityId',gender = '$gender',seeking = '$seeking', zipcode = '$zipcode',age='$age',status_id=0,last_update_date='$last_update_date',about_me='$aboutme', my_interest='$my_interest',make_private='$make_private' WHERE user_id  = '$current_user->ID'");
                    if ($my_interest != '') {
                        myinterest_cloud($my_interest);
                    }
                } else {
                    $wpdb->query("INSERT INTO $dsp_user_partner_profiles_table SET user_id = $current_user->ID,country_id = '$countryId',state_id='$stateId',city_id = '$cityId', gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id=0,last_update_date='$last_update_date',about_me='$aboutme', my_interest='$my_interest',make_private='$make_private'");
                    if ($my_interest != '') {
                        myinterest_cloud($my_interest);
                    }
                }  // if($count_rows>0){

                $profile_approval_message = language_code('DSP_PROFILE_UPDATE_IN_HOURS_MSG');
            } // if($check_approve_profile_status->setting_status=='Y')
            // ******************************** INSERT GENERAL INFORMATION *************************************//

            if (!file_exists('wp-content/uploads/dsp_media/user_photos/user_' . $user_id)) {

                if (!file_exists('wp-content/uploads')) {
                    mkdir('wp-content/uploads', 0777);
                }
                if (!file_exists('wp-content/uploads/dsp_media')) {
                    mkdir('wp-content/uploads/dsp_media', 0777);
                }
                if (!file_exists('wp-content/uploads/dsp_media/user_photos')) {
                    mkdir('wp-content/uploads/dsp_media/user_photos', 0777);
                }
                // it will default to 0755 regardless 
                mkdir('wp-content/uploads/dsp_media/user_photos/user_' . $user_id, 0755);
                mkdir('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0755);
                mkdir('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0755);
                // Finally, chmod it to 777
                chmod('wp-content/uploads/dsp_media/user_photos/user_' . $user_id, 0777);
                chmod('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0777);
                chmod('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0777);
            } else if (!file_exists('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs')) {
                mkdir('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0755);
                mkdir('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0755);

                chmod('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0777);
                chmod('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0777);
            }
// ***************************************************************************************************//
// ************************* INSERT PROFILE QUESTION OPTION INFORMATION *****************************//
            $num_rows1 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_partner_profile_question_details_table WHERE user_id=$current_user->ID");
            if ($num_rows1 > 0) {
                $wpdb->query("DELETE FROM $dsp_partner_profile_question_details_table where user_id = $current_user->ID");
            }
            foreach ($question_option_id as $key => $value) {
                if ($value != 0) {
                    $fetch_question_id = $wpdb->get_row("SELECT * FROM $dsp_question_options_table WHERE question_option_id = '" . $value . "'");
                    $wpdb->query("INSERT INTO $dsp_partner_profile_question_details_table SET user_id = $current_user->ID,profile_question_id = '$key',profile_question_option_id='" . $value . "',option_value='$fetch_question_id->option_value'");
                } // End  if($value!=0) {
            }  // end  foreach($question_option_id as $key=>$value) {         
            if ($question_option_id1 != "") {
                foreach ($question_option_id1 as $key => $value) {
                    if ($value != "") {
                        $wpdb->query("INSERT INTO $dsp_partner_profile_question_details_table SET user_id = $current_user->ID,profile_question_id ='$key' ,profile_question_option_id=0,option_value='" . $value . "'");
                    } // End if($value!="")
                } // End foreach($question_option_id1 as $key=>$value)
            }

             if ($question_option_id2 != "") {
                    foreach ($question_option_id2 as $k => $values) {
                        if ($values != "") {
                            foreach ($values as $key => $value) {
                              $optionValue = $wpdb->get_var("SELECT `option_value` FROM $dsp_question_options_table WHERE question_option_id = '" . $value . "'");
                              $wpdb->query("INSERT INTO $dsp_question_details SET user_id = $current_user->ID,profile_question_id ='$k' ,profile_question_option_id='$value',option_value='" . esc_sql($optionValue) . "'");
                            }
                        } // End if($value!="")
                    } // End foreach($question_option_id1 as $key=>$value)
                }
// ************************************************************************************************//
// ************************************ UPLOAD_IMAGE *****************************************//
            if ($_FILES['photoUpload']['name']) {
                $my_img = $wpdb->get_row("select picture from $dsp_members_partner_photos_table where user_id=$current_user->ID",ARRAY_A);
               /* $update_img = mysql_query("select picture from $dsp_members_partner_photos_table where user_id=$current_user->ID");
                $my_img = mysql_fetch_array($update_img);*/
                $old_img = $my_img['picture'];
                $del_img_path = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/" . $old_img;
                $del_thumb_img_path = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs/thumb_" . $old_img;
                $del_thumb1_img_path = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs1/thumb_" . $old_img;

                if ($old_img != "") {
                    unlink($del_img_path);
                    unlink($del_thumb_img_path);
                    unlink($del_thumb1_img_path);
                }
            }
            $image_file = $_FILES['photoUpload']['name'];
            define("MAX_SIZE", "100000");
            define("WIDTH", "150");
            define("HEIGHT", "150");
            define("width", "100");
            define("height", "100");
           
            function square_crop($src_image, $dest_image, $thumb_size = 64, $jpg_quality = 90) {

                // Get dimensions of existing image
                $image = getimagesize($src_image);

                // Check for valid dimensions
                if ($image[0] <= 0 || $image[1] <= 0)
                    return false;

                // Determine format from MIME-Type
                $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));

                // Import image
                switch ($image['format']) {
                    case 'jpg':
                    case 'jpeg':
                        $image_data = imagecreatefromjpeg($src_image);
                        break;
                    case 'png':
                        $image_data = imagecreatefrompng($src_image);
                        break;
                    case 'gif':
                        $image_data = imagecreatefromgif($src_image);
                        break;
                    default:
                        // Unsupported format
                        return false;
                        break;
                }

                // Verify import
                if ($image_data == false)
                    return false;

                // Calculate measurements
                if ($image[0] & $image[1]) {
                    // For landscape images
                    $x_offset = ($image[0] - $image[1]) / 2;
                    $y_offset = 0;
                    $square_size = $image[0] - ($x_offset * 2);
                } else {
                    // For portrait and square images
                    $x_offset = 0;
                    $y_offset = ($image[1] - $image[0]) / 2;
                    $square_size = $image[1] - ($y_offset * 2);
                }

                // Resize and crop

                $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
                $white = imagecolorallocate($canvas, 255, 255, 255);
                imagefill($canvas, 0, 0, $white);
                if (imagecopyresampled(
                        $canvas, $image_data, 0, 0, $x_offset, $y_offset, $thumb_size, $thumb_size, $square_size, $square_size
                    )) {

                    // Create thumbnail
                    switch (strtolower(preg_replace('/^.*\./', '', $dest_image))) {
                        case 'jpg':
                        case 'jpeg':
                            return imagejpeg($canvas, $dest_image, $jpg_quality);
                            break;
                        case 'png':
                            return imagepng($canvas, $dest_image);
                            break;
                        case 'gif':
                            return imagegif($canvas, $dest_image);
                            break;
                        default:
                            // Unsupported format
                            return false;
                            break;
                    }
                } else {
                    return false;
                }
            }

            function getExtension($str) {
                $i = strrpos($str, ".");
                if (!$i) {
                    return "";
                }
                $l = strlen($str) - $i;
                $ext = substr($str, $i + 1, $l);
                return $ext;
            }

            $errors = 0;
            //$image=$_FILES['image_file']['name'];
            if ($image_file) {
                $filename = stripslashes($_FILES['photoUpload']['name']);

                $extension = getExtension($filename);
                $extension = strtolower($extension);
                if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
                    echo '<h1>Unknown extension!</h1>';
                    $errors = 1;
                } else {
                    $size = getimagesize($_FILES['photoUpload']['tmp_name']);
                    $sizekb = filesize($_FILES['photoUpload']['tmp_name']);
                    $img_name = basename($image_file);
                    $new_name = "p" . $user_id . "_" . $img_name;
                    //$image_name=$new_name.'.'.$extension;
                    $newname = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/" . $new_name;
                    $copied = copy($_FILES['photoUpload']['tmp_name'], $newname);
                    if (!$copied) {
                        echo '<h5>Copy unsuccessfull!</h5>';
                        $errors = 1;
                    } else {
                        $thumb_name1 = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs1/thumb_" . $new_name;
                        $thumb1 = square_crop($newname, $thumb_name1, 100);
                        $thumb_name = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs/thumb_" . $new_name;
                        $thumb = square_crop($newname, $thumb_name, 150);
                        
                        if ($check_approve_photos_status->setting_status == 'Y') {  // if photo approve status is Y then photos Automatically Approved.
                            $count_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_partner_photos_table WHERE user_id=$current_user->ID");
                            if ($count_rows > 0) {
                                $wpdb->query("UPDATE $dsp_members_partner_photos_table SET picture = '$new_name',status_id=1 WHERE user_id  = '$current_user->ID'");
                            } else {
                                $wpdb->query("INSERT INTO $dsp_members_partner_photos_table SET picture = '$new_name',status_id=1,user_id='$current_user->ID'");
                            } //  if($count_rows>0)
                        } else {

                            $count_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_partner_photos_table WHERE user_id=$current_user->ID");
                            if ($count_rows > 0) {
                                $count_rowsin_tmp = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_tmp_members_photos_table WHERE t_user_id=$current_user->ID");
                                if ($count_rowsin_tmp > 0) {
                                    $wpdb->query("UPDATE $dsp_tmp_members_photos_table SET t_picture='$new_name',t_status_id=0 WHERE t_user_id=$current_user->ID");
                                } else {
                                    $wpdb->query("INSERT INTO $dsp_tmp_members_photos_table SET t_user_id=$current_user->ID,t_picture='$new_name',t_status_id=0");
                                } //  if($count_rowsin_tmp>0){
                            } else {

                                $wpdb->query("INSERT INTO $dsp_members_partner_photos_table SET picture = '$new_name',status_id=0,user_id='$current_user->ID'");
                                $wpdb->query("INSERT INTO $dsp_tmp_members_photos_table SET  t_user_id='$current_user->ID', t_picture = '$new_name',t_status_id=0");
                            }  // if($count_rows>0){

                            $approval_message = language_code('DSP_PICTURE_UPDATE_IN_HOURS_MSG');
                        } // if($check_approve_photos_status->setting_status=='Y')
                    }
                }// End if(move_uploaded_file)
            }  // End if ($image_file)
            
// ******************************************************************************************//
        } // End if($mode=="update")
        $profile_updated = true;
    } // End if(!isset($hasError))
}   // End if isset(submit) condition
if (isset($_GET['msg']) && $_GET['msg'] == 'ed') {
    ?>
    <div class="thanks">
        <p align="center" class="error"><?php echo language_code('DSP_UPDATE_PROFILE_BEFORE_ADD_FRND_MSG') ?></p>
    </div>
    <?php
}
if (isset($required_Error1) && $required_Error1 != '') {
    ?>
    <div class="thanks">
        <p align="center" class="error"><?php
            $req_error = implode(", ", $required_Error1);
            echo language_code('DSP_PLEASE_ENTER_MSG') . "&nbsp;" . $req_error . "&nbsp;" . language_code('DSP_VALUE_MSG');
            ?></p>
    </div>
    <?php
}
if (isset($required_Error2) && $required_Error2 != '') {
    ?>
    <div class="thanks">
        <p align="center" class="error"><?php
            $req_error2 = implode(", ", $required_Error2);
            echo language_code('DSP_PLEASE_SELECT_MSG') . "&nbsp;" . $req_error2 . "&nbsp;" . language_code('DSP_VALUE_MSG');
            ?></p>
    </div>
    <?php
}
if ($check_approve_profile_status->setting_status == 'Y') {
    if (isset($profile_updated) && $profile_updated == true) {
        ?>
        <div class="thanks">
            <p align="center" class="error"><?php echo language_code('DSP_UPDATE_PROFILE_MESSAGE') ?></p>
        </div>
        <?php
    }
} else {
    if (isset($profile_approval_message) && ($profile_approval_message != "")) {
        ?>
        <div class="thanks">
            <p align="center" class="error"><?php echo $profile_approval_message ?></p>
        </div>
        <?php
    }
}
$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_partner_profiles_table WHERE user_id = '$current_user->ID'");
if ($check_partner_profile_exist != 0)
    $gender = $exist_profile_details->gender;
else
    $gender = "";
?>
<script type="text/javascript">
    function dsp_profile_validation()
    {
        if (document.frm_u_profile.cmbCountry.value == 0) {
            alert("Please Select Country from Country Dropdown Field..");
            document.frm_u_profile.cmbCountry.focus();
            return false;
        }
        for (var i = 0; i < document.frm_u_profile.hidprofileqquesid.length; i++) {
            var q_name = document.frm_u_profile.hidprofileqques[i].value;
            var q_id1 = document.frm_u_profile.hidprofileqquesid[i].value;
            var sel_option_id = document.getElementById("q_opt_ids" + q_id1).value;
            if (sel_option_id == 0) {
                alert("Please Select " + q_name + " value");
                return false;
            }
        }

        if (document.frm_u_profile.txtaboutme.value == "") {
            alert("Please Enter About Me.");
            document.frm_u_profile.txtaboutme.focus();
            return false;
        }

        for (var i = 0; i < document.frm_u_profile.hidtextprofileqquesid.length; i++) {
            var q_name2 = document.frm_u_profile.hidetextqu_name[i].value;
            var q_id2 = document.frm_u_profile.hidtextprofileqquesid[i].value;
            var text_option_id = document.getElementById("text_option_id" + q_id2).value;
            if (text_option_id == "") {
                alert("Please Enter " + q_name2 + " value");
                return false;
            }
        }

    }
</script>
<?php //---------------------------------START  GENERAL SEARCH---------------------------------------  ?>
</div>
<form name="frm_u_profile" class="dspdp-form-horizontal" action="" method="post" enctype="multipart/form-data" onsubmit="return dsp_profile_validation();
        fun1();">
          <?php //-----------------------------------START  GENERAL ----------------------------------------//    ?>

    <div style="" class="box-border">
        <div class="box-pedding">
            <div class="heading-submenu dsp-none"><?php echo language_code('DSP_GENERAL') ?></div>
            <div class="dsp-box-container dsp-form-container dsp-space"> 
            
            <ul class="edit-profile">
                <li class=" dsp-form-group dspdp-form-group"><span class="dsp-sm-3 dsp-control-label dspdp-col-sm-3 dspdp-control-label"><?php echo language_code('DSP_I_AM') ?></span>
                    <?php
                    if ($check_partner_profile_exist != 0 && $exist_profile_details->edited == 'Y') {
                        $edited = "disabled='disabled'";
                    }
                    ?>
                    <div class="dsp-sm-6 dspdp-col-sm-6">
                        <select class="dspdp-form-control  dsp-form-control" name="gender" <?php if ($exist_profile_details->gender != '') { ?> <?php echo $edited ?> onchange="xyz(this.value);" <?php } ?>>
                            <?php echo get_gender_list($exist_profile_details->gender); ?>
                        </select>
                    </div>
                </li >
                <li class="dsp-control-label dsp-form-group dspdp-form-group">
                    <span class="dsp-sm-3 dsp-control-label dspdp-col-sm-3 dspdp-control-label">
                        <?php echo language_code('DSP_SEEKING_A'); ?>
                    </span>
                    <div class="dsp-sm-6 dspdp-col-sm-6">
                        <select class="dspdp-form-control  dsp-form-control" name="seeking">
                            <?php echo get_gender_list($exist_profile_details->seeking); ?>
                        </select>
                    </div>    
                </li>
                <?php //////////////////////////////////////  AGE FIELDS ////////////////////////////////////////// ?>
                <?php
                if ($check_partner_profile_exist != 0 && $exist_profile_details->age != "") {
                    $split_age = explode("-", $exist_profile_details->age);
                }
                ?>
                <li class="dsp-form-group dspdp-form-group">
                    <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                        <?php echo language_code('DSP_AGE') ?>
                    </span>
                   <?php
                        //array to store the months
                        $mon = array(1 => language_code('DSP_JANUARY'),
                            language_code('DSP_FABRUARY'),
                            language_code('DSP_MARCH'),
                            language_code('DSP_APRIL'),
                            language_code('DSP_MAY'),
                            language_code('DSP_JUNE'),
                            language_code('DSP_JULY'),
                            language_code('DSP_AUGUST'),
                            language_code('DSP_SEPTEMBER'),
                            language_code('DSP_OCTOBER'),
                            language_code('DSP_NOVEMBER'),
                            language_code('DSP_DECEMBER'));
                    ?>
                    <div class="dspdp-col-sm-2 dsp-sm-2 dspdp-xs-form-group">
                        <select name="dsp_mon" class="dspdp-form-control dsp-form-control ">
                            <?php
                            foreach ($mon as $key => $value) {
                                if ($split_age[1] == $key) {
                                    ?>
                                    <option value="<?php echo $key ?>" selected><?php echo $value ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>    
                    <?php //make the day pull-down menu   ?>
                    <div class="dspdp-col-sm-2 dsp-sm-2 dspdp-xs-form-group">
                        <select name="dsp_day" class="dspdp-form-control dsp-form-control ">
                            <?php
                            for ($dsp_day = 1; $dsp_day <= 31; $dsp_day++) {
                                if ($split_age[2] == $dsp_day) {
                                    ?>
                                    <option value="<?php echo $dsp_day ?>" selected><?php echo $dsp_day ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $dsp_day ?>"><?php echo $dsp_day ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <?php //make the year pull-down menu   ?>
                    <div class="dspdp-col-sm-2 dsp-sm-2 dspdp-xs-form-group">
                         <select name="dsp_year"  class="dspdp-form-control dsp-form-control">
                            <?php
                                $start_dsp_year = $check_start_year->setting_value;
                                $end_dsp_year = $check_end_year->setting_value;
                                echo dsp_get_year($start_dsp_year,$end_dsp_year,$split_age[0]);
                            ?>
                        </select>
                    </div>     
                </li>
                <?php // //////////////////////////////////// END AGE FIELDS //////////////////////////////////////////   ?>
                <li class="dsp-form-group dspdp-form-group">
                    <span class="dsp-sm-3 dsp-control-label dspdp-col-sm-3 dspdp-control-label">
                        <?php echo language_code('DSP_COUNTRY') ?>
                    </span>
                    <!--onChange="Show_state(this.value);"-->
                    <span class="dsp-sm-6 dspdp-col-sm-6">
                        <select class="dsp-form-control dspdp-form-control" name="cmbCountry" id="cmbCountry_id">
                            <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY'); ?></option>
                            <?php
                            $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
                            foreach ($strCountries as $rdoCountries) {
                                if ($exist_profile_details->country_id == $rdoCountries->country_id) {
                                    echo "<option value='" . $rdoCountries->name . "' selected='selected' >" . $rdoCountries->name . "</option>";
                                } else {
                                    echo "<option value='" . $rdoCountries->name . "' >" . $rdoCountries->name . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </span>    
                    <?php if (isset($nameError) && $nameError != '') { ?>
                        <span class="error"><?php echo $nameError; ?></span> 
                    <?php } ?>
                </li>
                <!--- Add StateCombo on 29-dec-2011  -->
                <li class="dsp-form-group dspdp-form-group">
                    <span class="dsp-control-label dsp-sm-3 dspdp-col-sm-3 dspdp-control-label">
                        <?php echo language_code('DSP_TEXT_STATE') ?>
                    </span>
                    <!--onChange="Show_state(this.value);"-->
                    <div id="state_change" class="dsp-sm-6 dspdp-col-sm-6">
                        <select name="cmbState" id="cmbState_id" class="dsp-form-control dspdp-form-control">
                            <option value="0"><?php echo language_code('DSP_SELECT_STATE'); ?></option>
                            <?php
                            $strStates = $wpdb->get_results("SELECT * FROM $dsp_state_table where country_id='$exist_profile_details->country_id' ORDER BY name");
                            foreach ($strStates as $rdoStates) {
                                if ($exist_profile_details->state_id == $rdoStates->state_id) {
                                    echo "<option value='" . $rdoStates->state_id . "' selected='selected' >" . $rdoStates->name . "</option>";
                                } else {
                                    echo "<option value='" . $rdoStates->state_id . "' >" . $rdoStates->name . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <?php if (isset($nameError) && $nameError != '') { ?>
                        <span class="error"><?php echo $nameError; ?></span> 
                    <?php } ?>
                </li>
                <!-- End City combo-->
                <li class="dsp-form-group dspdp-form-group">
                    <span class="dsp-control-label dsp-sm-3 dspdp-control-label dspdp-col-sm-3 ">
                        <?php echo language_code('DSP_CITY') ?>
                    </span>
                    <!--onChange="Show_state(this.value);"-->
                    <div id="city_change" class="dsp-sm-6 dspdp-col-sm-6">
                        <select name="cmbCity" id="cmbCity_id" class="dsp-form-control dspdp-form-control">
                            <option value="0"><?php echo language_code('DSP_SELECT_CITY'); ?></option>
                            <?php
                            if ($exist_profile_details->state_id != 0) {
                                $strCities = $wpdb->get_results("SELECT * FROM $dsp_city_table where country_id='$exist_profile_details->country_id' and state_id='$exist_profile_details->state_id' ORDER BY name");
                            } else {
                                $strCities = $wpdb->get_results("SELECT * FROM $dsp_city_table where country_id='$exist_profile_details->country_id' ORDER BY name");
                            }
                            foreach ($strCities as $rdoCities) {
                                if ($exist_profile_details->city_id == $rdoCities->city_id) {
                                    echo "<option value='" . $rdoCities->name . "' selected='selected' >" . $rdoCities->name . "</option>";
                                } else {
                                    echo "<option value='" . $rdoCities->name . "' >" . $rdoCities->name . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <?php if (isset($nameError) && $nameError != '') { ?>
                        <span class="error"><?php echo $nameError; ?></span> 
                    <?php } ?>
                </li>
                <!-- End city combo-->
                <?php if ($check_zipcode_mode->setting_status == 'Y') { ?> 
                    <li class="dsp-form-group dspdp-form-group">
                        <span class="dsp-control-label dsp-sm-3 dspdp-control-label dspdp-col-sm-3 ">
                            <?php echo language_code('DSP_ZIP'); ?>
                        </span>
                         <span class="dsp-sm-6 dspdp-col-sm-6">
                            <input class="dsp-form-control dspdp-form-control" type="text" name="zip" value="<?php if ($check_partner_profile_exist != 0) echo $exist_profile_details->zipcode ?>"/>
                            <?php if (isset($zipError) && $zipError != '') { ?>
                                <span class="error"><?php echo $zipError; ?></span> 
                            <?php } ?>
                         </span>
                    </li>
                <?php } ?>
            </ul>
            </div>
        </div>
    </div>
    <?php // -------------------------------------- END GENERAL ---------------------------------------------//   ?>
    <?php // ---------------------------------- START PROFILE QUESTIONS ------------------------------------ //  ?>

    <div class="box-border profile-edit-page magn-top-15">
        <div class="box-pedding dsp-form-container form-horizontal">
            <div class="heading-submenu dsp-none"><?php echo language_code('DSP_PROFILE_QUESTIONS') ?></div>
            <div class="dsp-box-container">
                <div class="heading dsp-block dsp-space" style="display:none">
                    <h3><?php echo language_code('DSP_PROFILE_QUESTIONS') ?></h3>
                </div>
                <?php


                $exist_profile_options_details = $wpdb->get_results("SELECT * FROM $dsp_question_details WHERE user_id = '$current_user->ID'");
                if(!empty($exist_profile_options_details)){
                    foreach ($exist_profile_options_details as $profile_qu) {
                        $update_exit_option[] = $profile_qu->profile_question_option_id;
                    }
                }else{
                    $update_exit_option[] = $question_option_id;
                }
                ?>
                <ul>
                    <?php
                    do_action('dsp_display_question_by_order',$update_exit_option);

                    /*$myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id =1 Order by sort_order");
                    foreach ($myrows as $profile_questions) {
                        $ques_id = $profile_questions->profile_setup_id;
                        $profile_ques = stripslashes($profile_questions->question_name);
                        $profile_queds_type_id = $profile_questions->field_type_id;
                        ?>
                            <li class="dsp-form-group dspdp-form-group ">
                                <span class="dsp-sm-3 dsp-control-label dspdp-control-label dspdp-col-sm-3"><?php echo $profile_ques; ?>:</span>
                                <?php if ($profile_ques_type_id == 1) { ?>
                                    <?php if ($profile_questions->required == "Y") { ?>
                                        <input type="hidden" name="hidprofileqques" value="<?php echo $profile_ques; ?>" />
                                        <input type="hidden" name="hidprofileqquesid" value="<?php echo $ques_id; ?>" />
                                    <?php } ?>
                                    <span class="dsp-md-6 dspdp-col-sm-6">
                                        <select class="dsp-form-control dspdp-form-control" name="option_id[<?php echo $ques_id ?>]" id="q_opt_ids<?php echo $ques_id ?>">
                                            <option value="0"><?php echo language_code('DSP_SELECT_OPTION'); ?></option>
                                            <?php
                                            $myrows_options = $wpdb->get_results("SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order");
                                            foreach ($myrows_options as $profile_questions_options) {
                                                if (@in_array($profile_questions_options->question_option_id, $update_exit_option)) {
                                                    ?>
                                                    <option value="<?php echo $profile_questions_options->question_option_id ?>" selected="selected"><?php echo stripslashes($profile_questions_options->option_value); ?></option>
                                                <?php } else { ?>
                                                    <option value="<?php echo $profile_questions_options->question_option_id ?>"><?php echo stripslashes($profile_questions_options->option_value); ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </span>
                                <?php } ?>
                            </li>
                            <?php

                        }  //  foreach ($myrows as $profile_questions) */
                    ?>
                    <li class="dsp-row dsp-form-group dspdp-form-group">
                        <span class="dsp-sm-3 dsp-control-label dspdp-control-label dspdp-col-sm-3 "><?php echo language_code('DSP_ABOUT_ME') ?>:</span>
                        <span class="dsp-sm-6 dspdp-col-sm-9"><textarea name="txtaboutme" class="dsp-form-control dspdp-form-control" rows="6" ><?php echo str_replace('\\', '', isset($exist_profile_details) ? trim($exist_profile_details->about_me) : ''); ?></textarea></span>
                        <?php if (isset($aboutmeError) && $aboutmeError != '') { ?>
                            <span class="error"><?php echo $aboutmeError; ?></span>
                        <?php } ?>
                    </li>
                    <?php
                    /*$myrows2 = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id = 2 Order by sort_order");
                    $i = 0;
                    foreach ($myrows2 as $profile_questions2) {
                        $ques_id = $profile_questions2->profile_setup_id;
                        $profile_ques = stripslashes($profile_questions2->question_name);
                        $profile_ques_type_id = $profile_questions2->field_type_id;
                        $profile_ques_max_length = $profile_questions2->max_length;
                        if (($i % 1) == 0) {
                            ?>
                        <?php } ?>
                        <li class="dsp-row dsp-form-group dspdp-form-group">
                            <span class="dsp-md-3 dsp-control-label dspdp-col-sm-3 dspdp-control-label"><?php echo $profile_ques; ?>:</span>
                            <?php
                            if ($profile_ques_type_id == 2) {
                                $check_exist_profile_text_details = $wpdb->get_var("SELECT count(*) FROM $dsp_question_details WHERE user_id = '$current_user->ID' AND profile_question_id=$ques_id");
                                if ($check_exist_profile_text_details > 0) {
                                    $exist_profile_text_details = $wpdb->get_row("SELECT * FROM $dsp_question_details WHERE user_id = '$current_user->ID' AND profile_question_id=$ques_id");
                                    $text_value = stripslashes($exist_profile_text_details->option_value);
                                } else {
                                    $text_value = isset($question_option_id1[$profile_ques_type_id]) ? $question_option_id1[$profile_ques_type_id] : '';
                                }
                                ?>
                                <?php if ($profile_questions2->required == "Y"){ ?>
                                    <input type="hidden" name="hidetextqu_name"  value="<?php echo $profile_ques; ?>" />
                                    <input type="hidden" name="hidtextprofileqquesid" id="hidtextprofileqquesid" value="<?php echo $ques_id; ?>" />
                                <?php } ?>
                                <span class="dsp-sm-6 dspdp-col-sm-9">
                                    <textarea class="dsp-form-control dspdp-form-control" name="option_id1[<?php echo $ques_id ?>]" id="text_option_id<?php echo $ques_id ?>" maxlength="<?php echo $profile_ques_max_length; ?>"  rows="6" ><?php echo trim($text_value) ?></textarea>
                                </span>
                            <?php } ?>
                        </li>
                        <?php
                        $i++;
                    }  //  foreach ($myrows as $profile_questions) */
                    // for multiple choice quiestion options
                    /* $update_exit_option = isset($update_exit_option) && !empty($update_exit_option) ? $update_exit_option : '';
                     do_action('dsp_multiple_choice_field',3,$update_exit_option);*/
                    ?>
                    <li class="dsp-row dsp-form-group dspdp-form-group">
                        <span class="dsp-sm-3 dsp-control-label  dspdp-control-label dspdp-col-sm-3 "><?php echo language_code('DSP_MY_INTEREST'); ?>:</span>
                        <span class="dsp-sm-6 dspdp-col-sm-9">
                            <textarea name="my_interest"  rows="6" class="dsp-form-control dspdp-form-control"><?php $userIntrest = (isset($exist_profile_details) && $exist_profile_details->my_interest != " ")?stripslashes($exist_profile_details->my_interest) : $my_interest; echo $userIntrest;?></textarea>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <?php // ----------------------------------------------- END PROFILE QUESTIONS -----------------------------------------//   ?>
    <?php // ------------------------------------------------ START VERIFICATION ---------------------------------------------//    ?>

    <div class="box-border magn-top-15">
        <div class="box-pedding">
            <div class="dsp-border dsp-space margin-btm-3">
                <?php
                //Display error message if there are any
                if (isset($error) && strlen($error) > 0) {
                    echo "<ul><li><strong>Error!</strong></li><li>" . $error . "</li></ul>";
                }
                ?>
                <ul class="image-edit-profile dsp-form-container dspdp-row row">
                    <?php if (isset($approval_message) && $approval_message != '') { ?>
                        <li><span class="error"><?php echo $approval_message; ?></span></li>
                    <?php } ?>
                    <li class="dspdp-col-sm-2 dsp-sm-3 dspdp-xs-form-group">
                        <img src="<?php echo display_members_partner_photo($user_partner_profile_id, $imagepath); ?>"  class="img dspdp-img-responsive" alt="<?php echo get_username($user_partner_profile_id);?>"/>
                        
                    </li>
                    <li class="dspdp-col-sm-6 dsp-sm-9 dspdp-xs-form-group">
                        <input class="dspdp-form-control dsp-form-control  dspdp-xs-form-group" type="file" name="photoUpload" value="">
                        <span>   <input name="private" type="checkbox" value="Y" <?php $makePirvateStatus = isset($exist_profile_details->my_interest) ? $exist_profile_details->my_interest : '';if ( $makePirvateStatus == 'Y') { ?> checked="checked"  <?php } ?>/><?php echo language_code('DSP_PHOTO_MAKE_PRIVATE'); ?>  </span>
                    </li>
                    <li class="dspdp-col-sm-4 dsp-none"><input type="hidden" name="mode"  value="update"/>
                        <input type="submit" name="submit1" class="dsp_submit_button dspdp-btn dspdp-btn-default" value="<?php _e(language_code('DSP_UPDATE_BUTTON')); ?>" />
                    </li>
                </ul>
            </div>    
            <div id="req_result"></div>
        </div>
        <div class="dsp-button-container dsp-block" style="display:none">
            <input type="hidden" name="mode"  value="update"/>
            <input type="submit" name="submit1" class="dsp_submit_button dspdp-btn dspdp-btn-default" value="<?php _e(language_code('DSP_UPDATE_BUTTON')); ?>" />
        </div>
    </div>

    <?php // ------------------------------------------- END VERIFICATION --------------------------------------------------//     ?>
</form>