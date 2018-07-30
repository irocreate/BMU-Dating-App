<?php

include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
//-------------------------DISPLAY ERROR OFF CODE ENDS--------------------------------

include_once("dspFunction.php");

include_once("../general_settings.php");

global $wpdb;


        $exist_user_name = $wpdb->get_row("SELECT text_name FROM $DSP_TABLE_NAME WHERE code_name = '$code'",ARRAY_A);
        //$exist_user_name = mysql_fetch_array(mysql_query("SELECT text_name FROM $DSP_TABLE_NAME WHERE code_name = '$code'"));
$user_id = $_REQUEST['user_id'];

$imagepath = get_option('siteurl') . '/wp-content/';  // image Path

if (!function_exists('display_thumb2_members_photo')) {

    function display_thumb2_members_photo($photo_member_id, $path) {
        global $wpdb;
        $dsp_members_photos = $wpdb->prefix . "dsp_members_photos";
        $dsp_user_profiles = $wpdb->prefix . "dsp_user_profiles";
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

    // END FUNCTION CREATE thumb2  MEMBER PHOTO PATH
}

global $wp_query;

$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;



$dsp_chat_request = $wpdb->prefix . "dsp_chat_request";
$dsp_user_table = $wpdb->prefix . "users";

$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path
$fav_icon_image_path = $pluginpath . "images/"; // fav,chat,star,friends,mail Icon image path


$check_request = $wpdb->get_var("select count(*) from $dsp_chat_request where receiver_id='$user_id' and request_status=0");
if ($check_request > 0) {

    $request_row = $wpdb->get_row("select * from $dsp_chat_request where receiver_id='$user_id' and request_status=0");
    $photo_member_id = $request_row->sender_id;
    $displayed_member_name = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$photo_member_id'");


    echo "valid" . '#' . language_code('DSP_POPUP_CHAT_REQUEST') . ' ' . language_code('DSP_FROM_TEXT') . ' ' . $displayed_member_name . '#' . $photo_member_id;
} else {
    echo "invalid";
}
?>