<?php

include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

$dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
$dsp_tmp_galleries_photos_table = $wpdb->prefix . DSP_TMP_GALLERIES_PHOTOS_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;

$user_id = $_REQUEST['user_id'];

include_once("logs.php");
log_init();

log_message('debug', 'callupload user_id=' . $user_id);

$created_date = date("Y-m-d H:m:s");

$album_id = isset($_REQUEST['album_id']) ? $_REQUEST['album_id'] : '';

$check_approve_photos_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'authorize_photos'");
$check_image_count = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'count_image'");


$dsp_album_id = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = $user_id");



foreach ($dsp_album_id as $id) {

    $album_ids[] = $id->album_id;
}


if (isset($album_ids) && $album_ids != "") {

    $ids1 = implode(",", $album_ids);
}

if (isset($ids1)) {
    $count_uploaded_images = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_galleries_photos where album_id IN ($ids1)  AND status_id=1");
} else {
    $count_uploaded_images = 0;
}

log_message('debug', 'count uploaded image=' . $count_uploaded_images . 'id==' . $album_id . ' image=' . $_FILES['ReferenceName']['name']);


if (($album_id != "") && ($album_id != 0) && ($_FILES['ReferenceName']['name'] != "")) {
    log_message('debug', 'count uploaded image=' . $count_uploaded_images);

    if ($count_uploaded_images < $check_image_count->setting_value) {  // check condition Number of Images in A Profiles.
        log_message('debug', 'image name=' . $_FILES['ReferenceName']['name']);
        if ($check_approve_photos_status->setting_status == 'Y') {  // if photo approve status is Y then photos Automatically Approved.
            log_message('debug', 'query name=' . "INSERT INTO $dsp_galleries_photos SET album_id = $album_id,user_id='$user_id',date_added= '$created_date',status_id=1");

            $wpdb->query("INSERT INTO $dsp_galleries_photos SET album_id = $album_id,user_id='$user_id',date_added= '$created_date',status_id=1");
            $insertid = $wpdb->insert_id; // AUTOINCREMENT ID
            $image_file = $_FILES['ReferenceName']['name'];

            if ($image_file) {
                $img_name = basename($image_file);
                $new_name = $insertid . "_" . $img_name;

                $uploadfile = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/album_" . $album_id . "/" . $new_name;
                log_message('debug', 'image path' . $uploadfile);
                if (move_uploaded_file($_FILES['ReferenceName']['tmp_name'], $uploadfile)) {
                    log_message('debug', 'image uploaded');
                    $wpdb->query("UPDATE $dsp_galleries_photos SET image_name = '$new_name' WHERE gal_photo_id  = '$insertid'");

                    $msg = language_code('DSP_UPLOAD_SUCESS');
                    echo $msg;
                } // END if(move_uploaded_file)
                else {
                    $error_uploading = language_code('DSP_ERROR_UPLOADING');
                    $print_error_uploading = str_replace("<#FILE_NAME#>", $img_name, $error_uploading);
                    echo $print_error_uploading;
                }
            } // END if ($image_file)


            dsp_add_news_feed($user_id, 'gallery_photo');
            dsp_add_notification($user_id, 0, 'gallery_photo');
        } else {
            log_message('debug', 'image name=' . $_FILES['ReferenceName']['name']);
            $wpdb->query("INSERT INTO $dsp_galleries_photos SET album_id = $album_id,user_id='$user_id',date_added= '$created_date',status_id=0");
            $insertid1 = $wpdb->insert_id; // AUTOINCREMENT ID

            $image_file = $_FILES['ReferenceName']['name'];

            if ($image_file) {
                $img_name = basename($image_file);
                $new_name = $insertid1 . "_" . $img_name;

                $uploadfile = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/album_" . $album_id . "/" . $new_name;
                log_message('debug', 'image path=' . $uploadfile);
                if (move_uploaded_file($_FILES['ReferenceName']['tmp_name'], $uploadfile)) {
                    log_message('debug', 'image uploaded');
                    $wpdb->query("UPDATE $dsp_galleries_photos SET image_name = '$new_name' WHERE gal_photo_id  = '$insertid1'");
                } // END if(move_uploaded_file)
                else {
                    $error_uploading = language_code('DSP_ERROR_UPLOADING');
                    $print_error_uploading = str_replace("<#FILE_NAME#>", $img_name, $error_uploading);
                    echo $print_error_uploading;
                }
            } // END if ($image_file)

            $wpdb->query("INSERT INTO $dsp_tmp_galleries_photos_table (gal_image_id ,gal_user_id ,gal_status_id) VALUES ('$insertid1', '$user_id', '0')");

            $approval_message = language_code('DSP_PICTURE_UPDATE_IN_HOURS_MSG');
            echo $approval_message;
        } // end if($check_approve_photos_status->setting_status=='Y')
    } else {
        $limit_msg = language_code('DSP_UPLOAD_PICTURE_LIMIT');
        $print_limit_msg = str_replace("<#COUNT#>", $check_image_count->setting_value, $limit_msg);
        $printErrormsg = $print_limit_msg;
        echo $printErrormsg;
    }  // End  if($count_uploaded_images!=$check_image_count) 
} //END  if($album_id!="" && $_FILES['ReferenceName']['name']!="")
else {
    log_message('debug', 'image not uploaded');
    $msg = "Error: Image upload failed.";
    echo $msg;
}
?>