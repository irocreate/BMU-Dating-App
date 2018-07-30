<?php

if (!function_exists('display_members_photo_mb')) {

    function display_members_photo_mb($photo_member_id, $path) {
//	echo $path;
        global $wpdb;
        $dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id='$photo_member_id' AND status_id=1");
        if ($count_member_images > 0) {
            $member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$photo_member_id' AND status_id=1");
            if ($member_exist_picture->picture == "") {
                $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
                if ($check_gender->gender == 'M') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic_mb.jpg";
                } else if ($check_gender->gender == 'F') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic_mb.jpg";
                } else if ($check_gender->gender == 'C') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic_mb.jpg";
                }
//$Mem_Image_path=$path."images/no-image.jpg";
            } else {
                $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs1/thumb_" . $member_exist_picture->picture;
                if (@file_get_contents($Mem_Image_path)) {

                    $Mem_Image_path = $Mem_Image_path;
                } else {
                    $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
                    if ($check_gender->gender == 'M') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic_mb.jpg";
                    } else if ($check_gender->gender == 'F') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic_mb.jpg";
                    } else if ($check_gender->gender == 'C') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic_mb.jpg";
                    }
                }
            }
        } else {
            $check_gender = $wpdb->get_row("SELECT * FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
            if ($check_gender->gender == 'M') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic_mb.jpg";
            } else if ($check_gender->gender == 'F') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic_mb.jpg";
            } else if ($check_gender->gender == 'C') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic_mb.jpg";
            }
//$Mem_Image_path=$path."images/no-image.jpg";
        }
        return $Mem_Image_path;
    }

}
?>