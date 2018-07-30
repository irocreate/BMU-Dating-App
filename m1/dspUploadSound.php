<?php

$audio_mode = isset($_REQUEST['txtmode']) ? $_REQUEST['txtmode'] : '';

$created_date = date("Y-m-d H:m:s");

$Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';

$audio_file = isset($_FILES['file-upload']) ? $_FILES['file-upload']['name'] : '';

$get_audio_Id = isset($_REQUEST['audio_Id']) ? $_REQUEST['audio_Id'] : '';

$count_uploaded_audios = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_audios where user_id='$user_id' AND status_id=1");

if (isset($_REQUEST['private']) && $_REQUEST['private'] != '') {
    $private = isset($_REQUEST['private']) ? $_REQUEST['private'] : '';
} else {
    $private = 'N';
}
switch ($audio_mode) {

    case 'add':    // ADD PHOTO 

        $uploadfile1 = ABSPATH . "/wp-content/uploads/dsp_media/user_audios/user_" . $user_id . "/";

// define a constant for the maximum upload size

        define('MAX_FILE_SIZE', 51200000);

        define('UPLOAD_DIR', $uploadfile1);

// create an array of permitted MIME types

        $permitted = array('audio/mp3', 'audio/mpeg', 'audio/mpeg3', 'audio/x-mpeg-3');



        // check that file is within the permitted size

        if ($_FILES['file-upload']['size'] > 0 || $_FILES['file-upload']['size'] <= MAX_FILE_SIZE) {

            $sizeOK = true;
        }

// check that file is of an permitted MIME type

        foreach ($permitted as $type) {

            if ($type == $_FILES['file-upload']['type']) {

                $typeOK = true;

                break;
            }
        }





        if ($sizeOK && $typeOK) {

            switch ($_FILES['file-upload']['error']) {

                case 0:

                    $wpdb->query("INSERT INTO $dsp_member_audios SET user_id='$user_id',date_added='$created_date',status_id=0, private_audio='$private'");

                    $insertid = $wpdb->insert_id; // AUTOINCREMENT ID

                    $audio_name = basename($audio_file);

                    $newName = $insertid . "_" . $audio_name;

                    if (!file_exists('wp-content/uploads/dsp_media/user_audios')) {
                        mkdir('wp-content/uploads/dsp_media/user_audios', 0755); // it will default to 0755 regardless 
                        chmod('wp-content/uploads/dsp_media/user_audios', 0777);  // Finally, chmod it to 777
                    }

                    if (!file_exists('wp-content/uploads/dsp_media/user_audios/user_' . $user_id)) {
                        mkdir('wp-content/uploads/dsp_media/user_audios/user_' . $user_id, 0755); // it will default to 0755 regardless 
                        chmod('wp-content/uploads/dsp_media/user_audios/user_' . $user_id, 0777);  // Finally, chmod it to 777
                    }


                    // check if a file of the same name has been uploaded

                    if (!file_exists(UPLOAD_DIR . $audio_file)) {

                        // move the file to the upload folder and rename it

                        $success = move_uploaded_file($_FILES['file-upload']['tmp_name'], UPLOAD_DIR . $newName);
                    }



                    if ($success) {

                        if ($count_uploaded_audios < $check_audio_count->setting_value) {  // check condition Number of Audios in A Profiles.
                            if ($check_approve_audios_status->setting_status == 'Y') {  // if Audio approve status is Y then Audio Automatically Approved.
                                $wpdb->query("UPDATE $dsp_member_audios SET file_name = '$newName',status_id=1 WHERE audio_file_id='$insertid'");

                                $result[] = " $audio_file &nbsp;" . language_code('DSP_UPLOAD_SUCESS');

                                dsp_add_news_feed($user_id, 'audio');
                                dsp_add_notification($user_id, 0, 'audio');
                            } else {

                                $wpdb->query("UPDATE $dsp_member_audios SET file_name = '$newName',status_id=0 WHERE audio_file_id='$insertid'");

                                $wpdb->query("INSERT INTO $dsp_tmp_member_audios_table (t_audio_id ,t_user_id,t_filename,  	t_date_added,t_status_id) VALUES ('$insertid','$user_id','$newName','$created_date','0')");



                                $result[] = language_code('DSP_AUDIO_UPDATE_IN_HOURS_MSG');
                            } // end if($check_approve_audios_status->setting_status=='Y')
                        } else {



                            $wpdb->query("DELETE FROM $dsp_member_audios WHERE file_name='' AND user_id='$user_id' ");

                            $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_audios/user_' . $user_id;

                            $delete_audio = $directory_path . "/" . $newName;

                            unlink($delete_audio);

                            $limit_msg = language_code('DSP_UPLOAD_AUDIO_LIMIT');

                            $print_limit_msg = str_replace("<#COUNT#>", $check_audio_count->setting_value, $limit_msg);

                            $result[] = $print_limit_msg;
                        }  // End  if($count_uploaded_audios!=$check_audio_count)  */
                    } else {

                        $error_uploading = language_code('DSP_ERROR_UPLOADING');

                        $print_error_uploading = str_replace("<#FILE_NAME#>", $audio_file, $error_uploading);

                        $result[] = $print_error_uploading;
                    }



                    break;

                case 3:

                    $error_uploading = language_code('DSP_ERROR_UPLOADING');

                    $print_error_uploading = str_replace("<#FILE_NAME#>", $audio_file, $error_uploading);

                    $result[] = $print_error_uploading;

                default:

                    $error_uploading = language_code('DSP_SYSTEM_ERROR_UPLOADING');

                    $print_error_uploading = str_replace("<#FILE_NAME#>", $audio_file, $error_uploading);

                    $result[] = $print_error_uploading;
            }
        } elseif ($_FILES['file-upload']['error'] == 4) {

            $result[] = language_code('DSP_NO_FILE_SELECTED_MSG');
        } else {

            $error_uploading = language_code('DSP_FILE_NOT_UPLOADED_MSG');

            $print_error_uploading = str_replace("<#FILE_NAME#>", $audio_file, $error_uploading);

            $result[] = $print_error_uploading;
        }





        break;
} // END SWITCH CASE
?>