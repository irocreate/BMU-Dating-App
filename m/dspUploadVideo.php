<?php

include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

$dsp_member_videos = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

$user_id = $_REQUEST['user_id'];

$video_mode = isset($_REQUEST['txtmode']) ? $_REQUEST['txtmode'] : '';

$created_date = date("Y-m-d H:m:s");

$video_file = isset($_FILES['file-upload']) ? $_FILES['file-upload']['name'] : '';

include_once("logs.php");
log_init();



log_message('debug', 'video upload user_id=' . $user_id);


$count_uploaded_video = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_videos where user_id='$user_id' AND status_id=1");

$check_video_count = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'count_videos'");
$check_approve_videos_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'authorize_videos'");


if (isset($_REQUEST['private']) && $_REQUEST['private'] != '') {
    $private = isset($_REQUEST['private']) ? $_REQUEST['private'] : '';
} else {
    $private = 'N';
}


switch ($video_mode) {

    case 'add':    // ADD PHOTO 

        $typeOK = false;

        $uploadfile1 = ABSPATH . "wp-content/uploads/dsp_media/user_videos/user_" . $user_id . "/";
        // define a constant for the maximum upload size

        define('MAX_FILE_SIZE', 51200000);

        define('UPLOAD_DIR', $uploadfile1);

        // check that file is within the permitted size
        if ($_FILES['file-upload']['size'] >= 0 || $_FILES['file-upload']['size'] <= MAX_FILE_SIZE) {
            $sizeOK = true;
        }

        // create an array of permitted MIME types
        $permitted = array('video/quicktime', 'video/x-ms-wmv', 'video/mp4', 'video/avi',
            'application/octet-stream');
        

        // check that file is of an permitted MIME type

        foreach ($permitted as $type) {
            if ($type == $_FILES['file-upload']['type']) {
                $typeOK = true;
                break;
            }
        }
      

        log_message('debug', 'video upload size and type =' . $sizeOK . ' ' . $typeOK);
        if ($sizeOK && $typeOK) {
            log_message('debug', 'video upload size and type ok=');
            switch ($_FILES['file-upload']['error']) {

                case 0:

                    $dirs = wp_upload_dir();
                    $upload_dir = $dirs['basedir'];

                    $wpdb->query("INSERT INTO $dsp_member_videos SET user_id='$user_id',date_added='$created_date',status_id=0, private_video='$private'");

                    $insertid = $wpdb->insert_id; // AUTOINCREMENT ID

                    $video_name = basename($video_file);

                    $newName = $insertid . "_" . $video_name;
                    log_message('debug', 'video upload new file ' . $newName);

                    if (!file_exists($upload_dir . '/dsp_media/user_videos')) {
                        mkdir($upload_dir . '/dsp_media/user_videos', 0755); // it will default to 0755 regardless 

                        chmod($upload_dir . '/dsp_media/user_videos', 0777);  // Finally, chmod it to 777
                    }

                    if (!file_exists($upload_dir . '/dsp_media/user_videos/user_' . $user_id)) {

                        mkdir($upload_dir . '/dsp_media/user_videos/user_' . $user_id, 0755); // it will default to 0755 regardless 

                        chmod($upload_dir . '/dsp_media/user_videos/user_' . $user_id, 0777);  // Finally, chmod it to 777
                    }

                    // check if a file of the same name has been uploaded
                    if (!file_exists(UPLOAD_DIR . $video_file)) {
                        // move the file to the upload folder and rename it
                        $success = move_uploaded_file($_FILES['file-upload']['tmp_name'], UPLOAD_DIR . $newName);
                    }

                    if ($success) {
                        if (isset($count_uploaded_video) && $count_uploaded_video < $check_video_count->setting_value) {  // check condition Number of Audios in A Profiles.
                            if ($check_approve_videos_status->setting_status == 'Y') {  // if Audio approve status is Y then Audio Automatically Approved.
                                log_message('debug', 'video upload success ');
                                $wpdb->query("UPDATE $dsp_member_videos SET file_name = '$newName',status_id=1 WHERE video_file_id='$insertid'");

                                $msg = " $video_file &nbsp;" . language_code('DSP_UPLOAD_SUCESS');

                                dsp_add_news_feed($user_id, 'video');
                                dsp_add_notification($user_id, 0, 'video');
                                echo $msg;
                            } else {
                                log_message('debug', 'video upload success ');
                                $wpdb->query("UPDATE $dsp_member_videos SET file_name = '$newName',status_id=0 WHERE video_file_id='$insertid'");
                                $wpdb->query("INSERT INTO $dsp_tmp_member_videos_table (t_video_id ,t_user_id,t_filename,  	t_date_added,t_status_id) VALUES ('$insertid','$user_id','$newName','$created_date','0')");

                                $msg = language_code('DSP_VIDEO_UPDATE_IN_HOURS_MSG');
                                echo $msg;
                            } // end if($check_approve_videos_status->setting_status=='Y')
                        } else {
                            $wpdb->query("DELETE FROM $dsp_member_videos WHERE file_name='' AND user_id='$user_id' ");
                            $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_videos/user_' . $user_id;

                            $delete_video = $directory_path . "/" . $newName;

                            unlink($delete_video);

                            $limit_msg = language_code('DSP_UPLOAD_VIDEO_LIMIT');

                            $print_limit_msg = str_replace("<#COUNT#>", $check_video_count->setting_value, $limit_msg);

                            log_message('debug', 'video upload limit msg ' . $limit_msg);

                            echo $print_limit_msg;
                        }  // End  if($count_uploaded_audios!=$check_audio_count)  */
                    } else {
                        $error_uploading = language_code('DSP_ERROR_UPLOADING');
                        $print_error_uploading = str_replace("<#FILE_NAME#>", $video_file, $error_uploading);
                        log_message('debug', 'video upload error ' . $print_error_uploading);
                        echo $print_error_uploading;
                    }
                    break;

                case 3:

                    $error_uploading = language_code('DSP_ERROR_UPLOADING');

                    $print_error_uploading = str_replace("<#FILE_NAME#>", $video_file, $error_uploading);
                    log_message('debug', 'video upload error ' . $print_error_uploading);
                    echo $print_error_uploading;

                default:

                    $error_uploading = language_code('DSP_SYSTEM_ERROR_UPLOADING');
                    $print_error_uploading = str_replace("<#FILE_NAME#>", $video_file, $error_uploading);
                    log_message('debug', 'video upload error ' . $print_error_uploading);
                    echo $print_error_uploading;
            }
        } elseif ($_FILES['file-upload']['error'] == 4) {
            $msg = language_code('DSP_NO_FILE_SELECTED_MSG');
            log_message('debug', 'video upload error ' . $msg);
            echo $msg;
        } else {
            $error_uploading = language_code('DSP_FILE_NOT_UPLOADED_MSG');
            $print_error_uploading = str_replace("<#FILE_NAME#>", $video_file, $error_uploading);
            log_message('debug', 'video upload error ' . $print_error_uploading);
            echo $print_error_uploading;
        }

        break;
} // END SWITCH CASE
?>