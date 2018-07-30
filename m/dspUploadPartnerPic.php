<?php

include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

$dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_tmp_members_photos_table = $wpdb->prefix . DSP_TMP_MEMBERS_PHOTOS_TABLE;



$user_id = $_REQUEST['user_id'];

if (isset($_REQUEST['private']) && $_REQUEST['private'] != '') {
    $make_private = $_REQUEST['private'];
} else {
    $make_private = 'N';
}


$check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");


$check_partner_profile_exist = $wpdb->get_var("SELECT count(*) FROM $dsp_user_partner_profiles_table WHERE user_id = '$user_id'");

$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_partner_profiles_table WHERE user_id = '$user_id'");

$check_approve_photos_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'authorize_photos'");

$imageMimeTypes = array(
    'image/jpg',
    'image/jpeg',
    'image/pjpeg',
    'image/png',
    'image/gif'
);
$imageMimeTypeExts = array(
    'image/jpg' =>  'jpg',
    'image/jpeg'    =>  'jpg',
    'image/pjpeg'   =>  'jpg',
    'image/png' =>  'png',
    'image/gif' =>  'gif'
);

if ($_FILES["ReferenceName"]["error"] > 0) {

    echo "Error: " . $_FILES["ReferenceName"]["error"] . "<br>";
} else {

    if ($_FILES['ReferenceName']['name']) {

        $update_img = mysql_query("select picture from $dsp_members_partner_photos_table where user_id=$user_id");
        $my_img = mysql_fetch_array($update_img);
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

    $image_file = $_FILES['ReferenceName']['name'];

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
                $canvas, $image_data, 0, 0, $x_offset, $y_offset, $thumb_size, $thumb_size, $square_size, $square_size)) {

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


    /* -------------------------- check folder exist ------------------- */

    $dirs = wp_upload_dir();
    $upload_dir = $dirs['basedir'];

    if (!file_exists($upload_dir . '/dsp_media/user_photos/user_' . $user_id)) {
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777);
        }

        if (!file_exists($upload_dir . '/dsp_media')) {
            mkdir($upload_dir . '/dsp_media', 0777);
        }

        if (!file_exists($upload_dir . '/dsp_media/user_photos')) {

            mkdir($upload_dir . '/dsp_media/user_photos', 0777);
        }

        // it will default to 0755 regardless 

        mkdir($upload_dir . '/dsp_media/user_photos/user_' . $user_id, 0755);



        mkdir($upload_dir . '/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0755);



        mkdir($upload_dir . '/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0755);

        chmod($upload_dir . '/dsp_media/user_photos/user_' . $user_id, 0777);
        chmod($upload_dir . '/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0777);

        chmod($upload_dir . '/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0777);
    } else if (!file_exists($upload_dir . '/dsp_media/user_photos/user_' . $user_id . '/thumbs')) {
        mkdir($upload_dir . '/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0755);
        mkdir($upload_dir . '/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0755);
        chmod($upload_dir . '/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0777);
        chmod($upload_dir . '/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0777);
    }



    /* -------------------------- check folder exist ends------------------- */
    $extension = '';
    if ($image_file) {
        $filename = stripslashes($_FILES['ReferenceName']['name']);
        $fileMimeType = trim( strtolower( $_FILES['ReferenceName']['type'] ) );
//        $extension = getExtension($filename);
//        $extension = strtolower($extension);
        $extension = $imageMimeTypeExts[$fileMimeType];
        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
            echo 'Error :Unknown extension!';
            $errors = 1;
        } else {
            $size = getimagesize($_FILES['ReferenceName']['tmp_name']);
            $sizekb = filesize($_FILES['ReferenceName']['tmp_name']);

            $img_name = basename($image_file);
            $new_name = "p" . $user_id . "_" . $img_name . "." . $extension;
//            $new_name = "p" . $user_id . "_" . $img_name;
            //$image_name=$new_name.'.'.$extension;
            $newname = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/" . $new_name;

            $copied = copy($_FILES['ReferenceName']['tmp_name'], $newname);

            if (!$copied) {
                $error_uploading = language_code('DSP_ERROR_UPLOADING');
                $print_error_uploading = str_replace("<#FILE_NAME#>", $img_name, $error_uploading);
                echo $print_error_uploading;
            } else {
                $thumb_name1 = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs1/thumb_" . $new_name;
                $thumb1 = square_crop($newname, $thumb_name1, 100);
                $thumb_name = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs/thumb_" . $new_name;
                $thumb = square_crop($newname, $thumb_name, 150);

                if ($check_approve_photos_status->setting_status == 'Y') {  // if photo approve status is Y then photos Automatically Approved.
                    $count_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_partner_photos_table WHERE user_id=$user_id");
                    if ($count_rows > 0) {

                        $wpdb->query("UPDATE $dsp_members_partner_photos_table SET picture = '$new_name',status_id=1 WHERE user_id  = '$user_id'");
                    } else {
                        $wpdb->query("INSERT INTO $dsp_members_partner_photos_table SET picture = '$new_name',status_id=1,user_id='$user_id'");
                    } //  if($count_rows>0)
                    $msg = language_code('DSP_UPLOAD_SUCESS');
                    echo $msg;
                } else {
                    $count_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_partner_photos_table WHERE user_id=$user_id");
                    if ($count_rows > 0) {

                        $count_rowsin_tmp = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_tmp_members_photos_table WHERE t_user_id=$user_id");
                        if ($count_rowsin_tmp > 0) {
                            $wpdb->query("UPDATE $dsp_tmp_members_photos_table SET t_picture='$new_name',t_status_id=0 WHERE t_user_id=$user_id");
                        } else {

                            $wpdb->query("INSERT INTO $dsp_tmp_members_photos_table SET t_user_id=$user_id,t_picture='$new_name',t_status_id=0");
                        } //  if($count_rowsin_tmp>0){
                    } else {

                        $wpdb->query("INSERT INTO $dsp_members_partner_photos_table SET picture = '$new_name',status_id=0,user_id='$user_id'");

                        $wpdb->query("INSERT INTO $dsp_tmp_members_photos_table SET  t_user_id='$user_id', t_picture = '$new_name',t_status_id=0");
                    }  // if($count_rows>0){

                    $approval_message = language_code('DSP_UPLOAD_SUCESS');
                    echo $approval_message;
                } // if($check_approve_photos_status->setting_status=='Y')
                // update private status
                $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_partner_profiles_table WHERE user_id=$user_id");
                if ($num_rows == 0) {
                    $wpdb->query("INSERT INTO $dsp_user_partner_profiles_table SET user_id = $user_id,make_private='$make_private' ");
                } else {
                    $wpdb->query("UPDATE $dsp_user_partner_profiles_table SET make_private='$make_private' WHERE user_id  = '$user_id'");
                }
            }
        }// End if(move_uploaded_file)
    }  // End if ($image_file)
    $profile_updated = true;
} // End if($mode=="update")
?>