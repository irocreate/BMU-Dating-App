<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$zipcode = isset($_REQUEST['zip']) ? $_REQUEST['zip'] : '';
$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");
$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$current_user->ID'");
if ($gender == '') {
    $gender = $exist_profile_details->gender;
}
$seeking = isset($_REQUEST['seeking']) ? $_REQUEST['seeking'] : '';
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$question_option_id = isset($_REQUEST['option_id']) ? $_REQUEST['option_id'] : '';
$question_option_id1 = isset($_REQUEST['option_id1']) ? $_REQUEST['option_id1'] : '';
$month = isset($_REQUEST['dsp_mon']) ? $_REQUEST['dsp_mon'] : '';
$day = isset($_REQUEST['dsp_day']) ? $_REQUEST['dsp_day'] : '';
$year = isset($_REQUEST['dsp_year']) ? $_REQUEST['dsp_year'] : '';
$aboutme = isset($_REQUEST['txtaboutme']) ? $_REQUEST['txtaboutme'] : '';
$my_interest = isset($_REQUEST['my_interest']) ? $_REQUEST['my_interest'] : '';
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
    $countryId = "";
// $state_id =$_POST['cmbState'];
$stateName = isset($_REQUEST['cmbState']) ? $_REQUEST['cmbState'] : '';

$get_State = $wpdb->get_row("SELECT * FROM $dsp_state_table WHERE name = '" . $stateName . "'");
if ($stateName != "Select" && !empty($stateName))
    $stateId = $get_State->state_id;
else
    $stateId = "";
//$city_id = $_POST['cmbCity'];
$cityName = isset($_REQUEST['cmbCity']) ? $_REQUEST['cmbCity'] : '';
$cityNamenew = str_replace("`", "\'", $cityName);
if ($stateId == "") {
    $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE name = '" . $cityNamenew . "' and country_id=" . $countryId);
} else {
    $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE name = '" . $cityNamenew . "' and state_id=" . $stateId);
}
if ($cityName != "Select" && !empty($cityName))
    $cityId = $get_City->city_id;
else
    $cityId = "";
if (isset($_POST['submit1'])) {
//Check to make sure that the Country field is not empty
    if (trim($_POST['cmbCountry']) == '0') {
        $nameError = language_code('DSP_ERROR_FORGOT_SELECT_COUNTRY_FIELD');
        $hasError = true;
    } else {
        $country_id = trim($_POST['cmbCountry']);
    }
    $zipcode = trim($_POST['zip']);


    //Check to make sure that the About Me field is not empty
    if (trim($_POST['txtaboutme']) === '') {
        $aboutmeError = language_code('DSP_FORGOT_ABOUT_ME_MSG');
        $hasError = true;
    } else {
        $aboutme = trim($_POST['txtaboutme']);
    }
    //Check to make sure that the my_interest field is not empty


    // Checked textbox Profile question is required or not 
    foreach ($question_option_id1 as $key => $value) {
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
                $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$current_user->ID");
                if ($num_rows == 0) {
                    $wpdb->query("INSERT INTO $dsp_user_profiles SET user_id = $current_user->ID,country_id = '$countryId',state_id='$stateId',city_id = '$cityId',gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id=1,last_update_date='$last_update_date',about_me='$aboutme', edited='Y', my_interest='$my_interest',make_private='$make_private'");

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
                    $wpdb->query("UPDATE $dsp_user_profiles SET country_id = '$countryId',state_id='$stateId',city_id = '$cityId',gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id=1,last_update_date='$last_update_date',about_me='$aboutme', my_interest='$my_interest',make_private='$make_private' WHERE user_id  = '$current_user->ID'");
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

                $count_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$current_user->ID");
                if ($count_rows > 0) {
                    $wpdb->query("UPDATE $dsp_user_profiles SET country_id = '$countryId',state_id='$stateId',city_id = '$cityId',gender = '$gender',seeking = '$seeking', zipcode = '$zipcode',age='$age',status_id=0,last_update_date='$last_update_date',about_me='$aboutme', my_interest='$my_interest',make_private='$make_private' WHERE user_id  = '$current_user->ID'");
                    if ($my_interest != '') {
                        myinterest_cloud($my_interest);
                    }
                } else {
                    $wpdb->query("INSERT INTO $dsp_user_profiles SET user_id = $current_user->ID,country_id = '$countryId',state_id='$stateId',city_id = '$cityId', gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id=0,last_update_date='$last_update_date',about_me='$aboutme',make_private='$make_private', my_interest='$my_interest'");
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
            $num_rows1 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_question_details WHERE user_id=$current_user->ID");
            if ($num_rows1 > 0) {
                $wpdb->query("DELETE FROM $dsp_question_details where user_id = $current_user->ID");
            }
            foreach ($question_option_id as $key => $value) {
                if ($value != 0) {
                    $fetch_question_id = $wpdb->get_row("SELECT * FROM $dsp_question_options_table WHERE question_option_id = '" . $value . "'");
                    $wpdb->query("INSERT INTO $dsp_question_details SET user_id = $current_user->ID,profile_question_id = '$key',profile_question_option_id='" . $value . "',option_value='$fetch_question_id->option_value'");
                } // End  if($value!=0) {
            }  // end  foreach($question_option_id as $key=>$value) {         
            if ($question_option_id1 != "") {
                foreach ($question_option_id1 as $key => $value) {
                    if ($value != "") {
                        $wpdb->query("INSERT INTO $dsp_question_details SET user_id = $current_user->ID,profile_question_id ='$key' ,profile_question_option_id=0,option_value='" . $value . "'");
                    } // End if($value!="")
                } // End foreach($question_option_id1 as $key=>$value)
            }
// ************************************************************************************************//
// ************************************ UPLOAD_IMAGE *****************************************//
            if ($_FILES['photoUpload']['name']) {
                $my_img = $wpdb->get_row("select picture from $dsp_members_photos where user_id=$current_user->ID",ARRAY_A);
                /*$update_img = mysql_query("select picture from $dsp_members_photos where user_id=$current_user->ID");
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
                    $new_name = $user_id . "_" . $img_name;
                    //$image_name=$new_name.'.'.$extension;
                    $newname = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/" . $new_name;
                    $copied = copy($_FILES['photoUpload']['tmp_name'], $newname);
                    if (!$copied) {
                        echo '<h5>Copy unsuccessfull!</h5>';
                        $errors = 1;
                    } else {
                        $thumb_name1 = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs1/thumb_" . $new_name;
                        $thumb1 = square_crop($newname, $thumb_name1, 100);
                        //$tg = new thumbnailGenerator;
                        //$tg->generate($newname, width,height, $thumb_name1);

                        $thumb_name = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs/thumb_" . $new_name;
                        $thumb = square_crop($newname, $thumb_name, 150);
                        //$tg = new thumbnailGenerator;
                        //$tg->generate($newname, WIDTH,HEIGHT, $thumb_name);



                        /* if ($image_file)  {		
                          $img_name =basename($image_file);
                          $new_name = $user_id."_".$img_name;
                          $uploadfile = ABSPATH."/wp-content/plugins/dsp_dating/user_photos/user_".$user_id."/".$new_name; */


                        if ($check_approve_photos_status->setting_status == 'Y') {  // if photo approve status is Y then photos Automatically Approved.
                            $count_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id=$current_user->ID");
                            if ($count_rows > 0) {
                                $wpdb->query("UPDATE $dsp_members_photos SET picture = '$new_name',status_id=1 WHERE user_id  = '$current_user->ID'");
                            } else {
                                $wpdb->query("INSERT INTO $dsp_members_photos SET picture = '$new_name',status_id=1,user_id='$current_user->ID'");
                            } //  if($count_rows>0)
                        } else {

                            $count_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id=$current_user->ID");
                            if ($count_rows > 0) {
                                $count_rowsin_tmp = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_tmp_members_photos_table WHERE t_user_id=$current_user->ID");
                                if ($count_rowsin_tmp > 0) {
                                    $wpdb->query("UPDATE $dsp_tmp_members_photos_table SET t_picture='$new_name',t_status_id=0 WHERE t_user_id=$current_user->ID");
                                } else {
                                    $wpdb->query("INSERT INTO $dsp_tmp_members_photos_table SET t_user_id=$current_user->ID,t_picture='$new_name',t_status_id=0");
                                } //  if($count_rowsin_tmp>0){
                            } else {

                                $wpdb->query("INSERT INTO $dsp_members_photos SET picture = '$new_name',status_id=0,user_id='$current_user->ID'");
                                $wpdb->query("INSERT INTO $dsp_tmp_members_photos_table SET  t_user_id='$current_user->ID', t_picture = '$new_name',t_status_id=0");
                            }  // if($count_rows>0){

                            $approval_message = language_code('DSP_PICTURE_UPDATE_IN_HOURS_MSG');
                        } // if($check_approve_photos_status->setting_status=='Y')
                    }
                }// End if(move_uploaded_file)
            }  // End if ($image_file)
            //die("error: ".$_FILES['photoUpload']['error']);
            //$image_error="Please only upload jpg files!";
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
if(!isset($error) && empty($error)){
    if ($check_approve_profile_status->setting_status == 'Y') {
        if (isset($profile_updated) && $profile_updated == true) {
            ?>
            <?php
            $exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$current_user->ID'");
            $exist_profile_gender = $exist_profile_details->gender;
            $siteurl = get_option('siteurl');
            if ($exist_profile_gender == 'C') {
                ?>
                <script>
                    var url = "<?php echo $siteurl ?>";
                    url += "?pid=2&view=my_profile";
                    //alert(url);
                    //window.location.href=url;
                </script>
                <?php
            } else if (($exist_profile_gender == 'M') || ($exist_profile_gender == 'M')) {
                ?>
                <script>
                    var url = "<?php echo $siteurl ?>";
                    url += "?pid=2";
                    //alert(url);
                    //window.location.href=url;
                </script>
                <?php
            }
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
}
$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$current_user->ID'");
$exist_profile_details->gender;
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
<?php //---------------------------------START  GENERAL SEARCH---------------------------------------   ?>
<script>
    function xyz(i) {
//alert(i);
        var url = "<?php echo get_option('siteurl') ?>";
        if (i == 'C')
            url += "?pid=2&view=my_profile";
        else
            url += "?pid=2";
//alert(url);
        window.location.href = url;
        return i;
    }



</script>
</div>
<form name="frm_u_profile" id="frm_u_profile" action="" method="post" enctype="multipart/form-data" onSubmit="return dsp_profile_validation();
        fun1();">
          <?php //-----------------------------------START  GENERAL ----------------------------------------//    ?>
    <div class="dsp_box-out">
        <div class="dsp_box-in">
            <div class="box-page">
                <div class="heading-text"><strong><?php echo language_code('DSP_GENERAL') ?></strong></div>
                <ul class="edit-profile">
                    <li><span><?php echo language_code('DSP_I_AM') ?></span>
                        <?php
                        if ($exist_profile_details->edited == 'Y') {
                            $edited = "disabled='disabled'";
                        }
                        ?>
                        <select name="gender" <?php if ($exist_profile_details->gender != '') { ?> <?php echo $edited ?> onChange="xyz(this.value);" <?php } ?>>
                            <?php if ($exist_profile_details->gender == 'F') { ?>
                                <option value="M"><?php echo language_code('DSP_MAN'); ?></option>
                                <option value="F" selected="selected"><?php echo language_code('DSP_WOMAN'); ?></option>
                                <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                    <option value="C" ><?php echo language_code('DSP_COUPLE'); ?></option>
                                <?php } ?>
                            <?php } else if ($exist_profile_details->gender == 'M') { ?>
                                <option value="M" selected="selected"><?php echo language_code('DSP_MAN'); ?></option>
                                <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                                <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                    <option value="C" ><?php echo language_code('DSP_COUPLE'); ?></option>
                                <?php } ?>

                            <?php } else if (($exist_profile_details->gender == 'C') || ($_GET['view'] == 'my_profile')) { ?>
                                <option value="M"><?php echo language_code('DSP_MAN'); ?></option>
                                <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                                <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                    <option value="C" selected="selected"><?php echo language_code('DSP_COUPLE'); ?></option>
                                <?php } ?>

                            <?php } else { ?>
                                <option value="M" selected="selected"><?php echo language_code('DSP_MAN'); ?></option>
                                <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                                <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                    <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </li>
                    <li><span><?php echo language_code('DSP_SEEKING_A'); ?></span>
                        <select name="seeking">
                            <?php if ($exist_profile_details->seeking == 'M') { ?>
                                <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                                <option value="M" selected="selected"><?php echo language_code('DSP_MAN'); ?></option>
                                <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                    <option value="C" ><?php echo language_code('DSP_COUPLE'); ?></option>
                                <?php } ?>

                            <?php } else if ($exist_profile_details->seeking == 'F') { ?>
                                <option value="M" ><?php echo language_code('DSP_MAN'); ?></option>
                                <option value="F" selected="selected"><?php echo language_code('DSP_WOMAN'); ?></option>
                                <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                    <option value="C" ><?php echo language_code('DSP_COUPLE'); ?></option>
                                <?php } ?>

                            <?php } else if ($exist_profile_details->seeking == 'C') { ?>
                                <option value="M" ><?php echo language_code('DSP_MAN'); ?></option>
                                <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                                <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                    <option value="C" selected="selected"><?php echo language_code('DSP_COUPLE'); ?></option>
                                <?php } ?>

                            <?php } else { ?>
                                <option value="M"><?php echo language_code('DSP_MAN'); ?></option>
                                <option value="F" selected="selected"><?php echo language_code('DSP_WOMAN'); ?></option>
                                <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                    <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>
                                <?php } ?>

                            <?php } ?>
                        </select>
                    </li>
                    <?php //////////////////////////////////////  AGE FIELDS //////////////////////////////////////////  ?>
                    <?php
                    if ($exist_profile_details->age != "") {
                        $split_age = explode("-", $exist_profile_details->age);
                    }
                    ?>
                    <li><span><?php echo language_code('DSP_AGE') ?></span>
                        <?php
                        //array to store the months
                        $mon = array(1 => 'January', 'February', 'March', 'April',
                            'May', 'June', 'July', 'August', 'September', 'October',
                            'November', 'December');
                        ?>
                        <select name="dsp_mon" style="width:100px;">
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
                        <?php //make the day pull-down menu  ?>
                        <select name="dsp_day" style="width:50px;">
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
                        <?php //make the year pull-down menu  ?>
                        <select name="dsp_year"  class="dspdp-form-control dsp-form-control">
                            <?php
                                $start_dsp_year = $check_start_year->setting_value;
                                $end_dsp_year = $check_end_year->setting_value;
                                echo dsp_get_year($start_dsp_year,$end_dsp_year,$split_age[0]);
                            ?>
                        </select>
                    </li>
                    <?php // //////////////////////////////////// END AGE FIELDS //////////////////////////////////////////   ?>
                    <li><span><?php echo language_code('DSP_COUNTRY') ?></span>
                        <!--onChange="Show_state(this.value);"-->
                        <select name="cmbCountry" id="cmbCountry_id">
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
                        <?php if (isset($nameError) && $nameError != '') { ?>
                            <span class="error"><?php echo $nameError; ?></span> 
                        <?php } ?>
                    </li>
                    <!--- Add StateCombo on 29-dec-2011  -->
                    <li><span><?php echo language_code('DSP_TEXT_STATE') ?></span>
                        <!--onChange="Show_state(this.value);"-->
                        <div id="state_change">
                            <select name="cmbState" id="cmbState_id" style="width:110px;">
                                <option value="0"><?php echo language_code('DSP_SELECT_STATE'); ?></option>
                                <?php
                                $strStates = $wpdb->get_results("SELECT * FROM $dsp_state_table where country_id='$exist_profile_details->country_id' ORDER BY name");
                                foreach ($strStates as $rdoStates) {
                                    if ($exist_profile_details->state_id == $rdoStates->state_id) {
                                        echo "<option value='" . $rdoStates->name . "' selected='selected' >" . $rdoStates->name . "</option>";
                                    } else {
                                        echo "<option value='" . $rdoStates->name . "' >" . $rdoStates->name . "</option>";
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
                    <li><span><?php echo language_code('DSP_CITY') ?></span>
                        <!--onChange="Show_state(this.value);"-->
                        <div id="city_change">
                            <select name="cmbCity" id="cmbCity_id">
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
                        <?php }
                        ?>
                    </li>
                    <!-- End city combo-->
                    <?php if ($check_zipcode_mode->setting_status == 'Y') { ?> 
                        <li><span><?php echo language_code('DSP_ZIP'); ?></span>
                            <input type="text" name="zip" value="<?php echo $exist_profile_details->zipcode ?>"/>
                            <?php if (isset($zipError) && $zipError != '') { ?>
                                <span class="error"><?php echo $zipError; ?></span> 
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="gap-bottom"></div>
    <?php // -------------------------------------- END GENERAL ---------------------------------------------//   ?>
    <br>
    <?php // ---------------------------------- START PROFILE QUESTIONS ------------------------------------ //  ?>
    <div class="dsp_box-out">
        <div class="dsp_box-in">
            <div class="box-page profile-edit-page">
                <div class="heading-text"><strong><?php echo language_code('DSP_PROFILE_QUESTIONS') ?></strong></div>
                <?php
                $dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
                $all_languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where display_status='1' ");
                $language_name = $all_languages->language_name;
                if ($language_name == 'english') {
                    $tableName1 = "dsp_profile_setup";

                    $tableName = "dsp_question_options";
                } else {
                    $tableName1 = "dsp_profile_setup_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));

                    $tableName = "dsp_question_options_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                }
                $dsp_question_details = $wpdb->prefix . $tableName;
                $dsp_profile_setup_table = $wpdb->prefix . $tableName1;

                $exist_profile_options_details = $wpdb->get_results("SELECT * FROM $dsp_question_details WHERE user_id = '$current_user->ID'");
                foreach ($exist_profile_options_details as $profile_qu) {
                    $update_exit_option[] = $profile_qu->profile_question_option_id;
                }
                $update_exit_option = isset($question_option_id) ? $question_option_id : $update_exit_option;
                $myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id =1 Order by sort_order");
                $i = 0;
                foreach ($myrows as $profile_questions) {
                    $ques_id = $profile_questions->profile_setup_id;
                    $profile_ques = $profile_questions->question_name;
                    $profile_ques_type_id = $profile_questions->field_type_id;
                    if (($i % 2) == 0) {
                        ?>
                        <ul class="edit-profile">
                        <?php } ?>
                        <li><span><?php echo $profile_ques; ?></span>
                            <?php if ($profile_ques_type_id == 1) { ?>
                                <?php if ($profile_questions->required == "Y") { ?> 
                                    <input type="hidden" name="hidprofileqques" value="<?php echo $profile_ques; ?>" />
                                    <input type="hidden" name="hidprofileqquesid" value="<?php echo $ques_id; ?>" />
                                <?php } ?>
                                <select name="option_id[<?php echo $ques_id ?>]" id="q_opt_ids<?php echo $ques_id ?>"  style="width:56%;" multiple >
                                    <option value="0">Select</option>
                                    <?php
                                    $myrows_options = $wpdb->get_results("SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order");
                                    foreach ($myrows_options as $profile_questions_options) {
                                        if (@in_array($profile_questions_options->question_option_id, $update_exit_option)) {
                                            ?>
                                            <option value="<?php echo $profile_questions_options->question_option_id ?>" selected="selected"><?php echo $profile_questions_options->option_value ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $profile_questions_options->question_option_id ?>"><?php echo $profile_questions_options->option_value ?></option>
                                            <?php
                                        }
                                    }
                                    ?> 
                                </select>
                            <?php } ?>
                        </li>
                        <?php
                        $i++;
                    }  //  foreach ($myrows as $profile_questions) 
                    ?>	
                    <li class="dsp-row"><span><?php echo language_code('DSP_ABOUT_ME') ?></span>
                        <textarea name="txtaboutme" style="width:100%; height:50px;"><?php echo $exist_profile_details->about_me; ?></textarea>
                        <?php if (isset($aboutmeError) && $aboutmeError != '') { ?>
                            <span class="error"><?php echo $aboutmeError; ?></span> 
                        <?php } ?>
                    </li>
                    <?php
                    $myrows2 = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id =2 Order by sort_order");
                    $i = 0;
                    foreach ($myrows2 as $profile_questions2) {
                        $ques_id = $profile_questions2->profile_setup_id;
                        $profile_ques = $profile_questions2->question_name;
                        $profile_ques_type_id = $profile_questions2->field_type_id;
                        $profile_ques_max_length = $profile_questions2->max_length;
                        if (($i % 1) == 0) {
                            ?>
                        <?php } ?>
                        <li class="dsp-row"><span><?php echo $profile_ques; ?></span>
                            <?php
                            if ($profile_ques_type_id == 2) {
                                $check_exist_profile_text_details = $wpdb->get_var("SELECT count(*) FROM $dsp_question_details WHERE user_id = '$current_user->ID' AND profile_question_id=$ques_id");
                                if ($check_exist_profile_text_details > 0) {
                                    $exist_profile_text_details = $wpdb->get_row("SELECT * FROM $dsp_question_details WHERE user_id = '$current_user->ID' AND profile_question_id=$ques_id");
                                    $text_value = $exist_profile_text_details->option_value;
                                } else {
                                    $text_value = "";
                                }
                                ?>
                                <?php if ($profile_questions2->required == "Y") { ?>  
                                    <input type="hidden" name="hidetextqu_name"  value="<?php echo $profile_ques; ?>" />
                                    <input type="hidden" name="hidtextprofileqquesid" id="hidtextprofileqquesid" value="<?php echo $ques_id; ?>" />
                                <?php } ?>
                                <textarea name="option_id1[<?php echo $ques_id ?>]" id="text_option_id<?php echo $ques_id ?>" maxlength="<?php echo $profile_ques_max_length; ?>" style="width:65%;"><?php echo $text_value ?>
                                </textarea>
                            <?php } ?>
                        </li>
                        <?php
                        $i++;
                    }  //  foreach ($myrows as $profile_questions) 
                    ?>	
                    <li class="dsp-row"><span><?php echo language_code('DSP_MY_INTEREST'); ?></span>
                        <textarea name="my_interest" style="width:65%;"><?php echo $exist_profile_details->my_interest ?>
                        </textarea>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="gap-bottom"></div>
    <?php // ----------------------------------------------- END PROFILE QUESTIONS -----------------------------------------//  ?>
    <br>
    <?php // ------------------------------------------------ START VERIFICATION ---------------------------------------------//   ?>
    <div class="dsp_box-out">
        <div class="dsp_box-in"> 
            <div class="box-page">
                <?php
//Display error message if there are any
                if (isset($error) && strlen($error) > 0) {
                    echo "<ul><li><strong>Error!</strong></li><li>" . $error . "</li></ul>";
                }
                ?>
                <ul class="image-edit-profile">
                    <?php if (isset($approval_message) && $approval_message != '') { ?>
                        <li><span class="error"><?php echo $approval_message; ?></span></li>
                    <?php } ?>
                    <li> <img src="<?php echo display_members_photo($current_user->ID, $imagepath); ?>" style="width:100px; height:100px;"  class="img" alt="<?php echo get_username($current_user->ID);?>" />
                        <span><input name="private" type="checkbox" value="Y" <?php if ($exist_profile_details->make_private == 'Y') { ?> checked="checked"  <?php } ?>/><?php echo language_code('DSP_PHOTO_MAKE_PRIVATE'); ?></span>
                    </li>
                    <li><input type="file" name="photoUpload" value=""></li>
                    <li><input type="hidden" name="mode"  value="update"/>
                        <input type="submit" name="submit1" class="dsp_submit_button" value="<?php _e(language_code('DSP_UPDATE_BUTTON')); ?>" />
                    </li>
                </ul>
                <div id="req_result"></div>
            </div>
        </div>
    </div> 
    <?php // ------------------------------------------- END VERIFICATION --------------------------------------------------//     ?>
</form>