<?php

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once($parse_uri[0] . 'wp-load.php');
$nonce = false;

if (isset($_REQUEST['action']) && 'wpdating-profile-crop-image-action' == $_REQUEST['action']) {
    if (isset($_POST['_wpnonce_wpdating-profile-pic-change-form'])) {
        $nonce = wp_verify_nonce($_POST['_wpnonce_wpdating-profile-pic-change-form'],
            'wpdating-profile-pic-change-form');
    }
}

if (isset($_POST['nonce_field'])) {
    $nonce = wp_verify_nonce($_POST['nonce_field'], 'wpdating-profile-pic-change-form');
}
if ( ! $nonce) {
    exit('False');
}

$post = isset($_POST) ? $_POST : array();

if (isset($post['hdn-profile-id'])) {
    $userId = intval($post['hdn-profile-id']);
} else if (isset($_POST['id'])) {
    $userId = intval($post['id']);
} else {
    $userId = 0;
}

$profile_obj = new Wpdating_Profile_Picture_Change_Pic($userId);

switch ($post['action']) {
    case 'save':
        $profile_obj->saveProfilePic();
        $profile_obj->saveProfilePicTmp();
        break;
    default:
        $profile_obj->changeProfilePic();
        break;
}

class Wpdating_Profile_Picture_Change_Pic
{
    private $dsp_general_settings_table;
    private $dsp_members_photos;
    private $dsp_tmp_members_photos_table;
    private $userId;

    public function __construct($userId)
    {
        global $wpdb;
        $this->userId                       = $userId;
        $this->dsp_general_settings_table   = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $this->dsp_members_photos           = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
        $this->dsp_tmp_members_photos_table = $wpdb->prefix . DSP_TMP_MEMBERS_PHOTOS_TABLE;
    }

    public function saveProfilePic()
    {
        global $wpdb;
        $new_name                    = $_POST['image_name'];
        $check_approve_photos_status = $wpdb->get_row("SELECT * FROM $this->dsp_general_settings_table WHERE setting_name = 'authorize_photos'");
        $this->unlink_previous_pictures();
        if ($check_approve_photos_status->setting_status == 'Y') {  // if photo approve status is Y then photos Automatically Approved.
            $count_rows = $wpdb->get_var("SELECT COUNT(*) FROM $this->dsp_members_photos WHERE user_id=$this->userId");
            if ($count_rows > 0) {
                $wpdb->query("UPDATE $this->dsp_members_photos SET picture = '$new_name',status_id=1 WHERE user_id  = '$this->userId'");
            } else {
                $wpdb->query("INSERT INTO $this->dsp_members_photos SET picture = '$new_name',status_id=1,user_id='$this->userId'");
            } //  if($count_rows>0)

            dsp_add_news_feed($this->userId, 'profile_photo');
            dsp_add_notification($this->userId, 0, 'profile_photo');
        } else {

            $count_rows = $wpdb->get_var("SELECT COUNT(*) FROM $this->dsp_members_photos WHERE user_id=$this->userId");
            if ($count_rows > 0) {
                $count_rowsin_tmp = $wpdb->get_var("SELECT COUNT(*) FROM $this->dsp_tmp_members_photos_table WHERE t_user_id=$this->userId");
                if ($count_rowsin_tmp > 0) {
                    $wpdb->query("UPDATE $this->dsp_tmp_members_photos_table SET t_picture='$new_name',t_status_id=0 WHERE t_user_id=$this->userId");
                } else {
                    $wpdb->query("INSERT INTO $this->dsp_tmp_members_photos_table SET t_user_id=$this->userId,t_picture='$new_name',t_status_id=0");
                } //  if($count_rowsin_tmp>0){
            } else {

                $wpdb->query("INSERT INTO $this->dsp_members_photos SET picture = '$new_name',status_id=0,user_id='$this->userId'");
                $wpdb->query("INSERT INTO $this->dsp_tmp_members_photos_table SET  t_user_id='$this->userId', t_picture = '$new_name',t_status_id=0");
            }  // if($count_rows>0){

            $approval_message = language_code('DSP_PICTURE_UPDATE_IN_HOURS_MSG');
        }
    }

    public function saveProfilePicTmp()
    {
        $post = isset($_POST) ? $_POST : array();

        $path = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $this->userId . "/" . 'tmp-images/';

        $t_width  = 300; // Maximum thumbnail width
        $t_height = 300;    // Maximum thumbnail height
        if (isset($_POST['t']) and $_POST['t'] == "ajax") {
            $name      = '';
            $name      = $_POST['image_name'];
            $imagePath = $path . $name;
            list($txt, $ext) = explode(".", $name);
            $w1    = $_POST['w1'];
            $ratio = ($t_width / $w1);
            $h1    = $_POST['h1'];
            $x1    = $_POST['x1'];
            $y1    = $_POST['y1'];
            $nw    = ceil($w1 * $ratio);

            $nh    = ceil($h1 * $ratio);

            $nimg  = imagecreatetruecolor($nw, $nh);

            switch ($ext) {
                case 'jpg':
                case 'jpeg':
                    $im_src = imagecreatefromjpeg($imagePath);
                    break;
                case 'gif':
                    $im_src = imagecreatefromgif($imagePath);
                    break;
                case 'png':
                    $im_src = imagecreatefrompng($imagePath);
                    break;
                default:
                    $im_src = false;
                    break;
            }

            imagecopyresampled($nimg, $im_src, 0, 0, $x1, $y1, $nw, $nh, $w1, $h1);

            switch ($ext) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($nimg, $imagePath, 90);
                    break;
                case 'gif':
                    imagegif($nimg, $imagePath);
                    break;
                case 'png':
                    imagepng($nimg, $imagePath);
                    break;
                default:
                    imagejpeg($nimg, $imagePath, 90);
                    break;
            }

            chmod($imagePath, 0777);
            
            $new_path = ABSPATH . 'wp-content/uploads/dsp_media/user_photos/user_' . $this->userId . '/' . $name;
            $new_path_url = site_url()  . '/wp-content/uploads/dsp_media/user_photos/user_' . $this->userId . '/' . $name;
            $copied   = copy($imagePath, $new_path);
            if ($copied) {
                chmod($new_path, 0777);
                unlink($imagePath);
            }
            echo $new_path_url . '?' . time();

            $thumb_name1 = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $this->userId . "/thumbs1/thumb_" . $name;
            $thumb1      = $this->profile_square_crop($new_path, $thumb_name1, 250);
            //$tg = new thumbnailGenerator;
            //$tg->generate($newname, width,height, $thumb_name1);

            $thumb_name = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $this->userId . "/thumbs/thumb_" . $name;
            $thumb      = $this->profile_square_crop($new_path, $thumb_name, 350);
        }

        exit(0);
    }

    public function changeProfilePic()
    {
        $post      = isset($_POST) ? $_POST : array();
        $max_width = "500";

        $path          = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $this->userId . "/" . 'tmp-images';
        $path_url      = site_url() . "/wp-content/uploads/dsp_media/user_photos/user_" . $this->userId . "/" . 'tmp-images/';
        $valid_formats = array("jpg", "png", "gif", "jpeg");

        $name = $_FILES['profile-pic']['name'];
        $size = $_FILES['profile-pic']['size'];

        $this->profile_create_directory();

        if (strlen($name)) {
            list($txt, $ext) = explode(".", $name);
            if (in_array($ext, $valid_formats)) {
                if ($size < (5 * 1024 * 1024)) {
                    $actual_image_name = $this->userId . '_' . $name;
                    $filePath          = $path . '/' . $actual_image_name;
                    $tmp               = $_FILES['profile-pic']['tmp_name'];
                    if (move_uploaded_file($tmp, $filePath)) {
                        $width  = $this->getWidth($filePath);
                        $height = $this->getHeight($filePath);
                        //Scale the image if it is greater than the width set above
                        if ($width > $max_width) {
                            $scale    = $max_width / $width;
                            $uploaded = $this->resizeImage($filePath, $width, $height, $scale, $ext);
                        } else {
                            $scale    = 1;
                            $uploaded = $this->resizeImage($filePath, $width, $height, $scale, $ext);
                        }
                        echo "<img id='photo' file-name='" . $actual_image_name . "' class='' src='" . $path_url . $actual_image_name . '?' . time() . "' class='preview'/>";
                    } else {
                        echo "failed";
                    }
                } else {
                    echo "Image file size max 1 MB";
                }
            } else {
                echo language_code('DSP_USER_UNKNOWN_EXTENSION_FOR_IMAGE');
            }
        } else {
            echo "Please select image..!";
        }
        exit;
    }

    /* Function to get image height. */
    public function getHeight($image)
    {
        $sizes  = getimagesize($image);
        $height = $sizes[1];

        return $height;
    }

    /* Function to get image width */
    public function getWidth($image)
    {
        $sizes = getimagesize($image);
        $width = $sizes[0];

        return $width;
    }

    /* Function to resize image */
    public function resizeImage($image, $width, $height, $scale, $ext)
    {

        $newImageWidth  = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage       = imagecreatetruecolor($newImageWidth, $newImageHeight);

        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $source = imagecreatefromjpeg($image);
                break;
            case 'gif':
                $source = imagecreatefromgif($image);
                break;
            case 'png':
                $source = imagecreatefrompng($image);
                break;
            default:
                $source = false;
                break;
        }
        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($newImage, $image, 90);
                break;
            case 'gif':
                imagegif($newImage, $image);
                break;
            case 'png':
                imagepng($newImage, $image, 9);
                break;
            default:
                imagejpeg($newImage, $image, 90);
                break;
        }

        chmod($image, 0777);

        return $image;
    }

    public function profile_create_directory()
    {
        if ( ! file_exists(ABSPATH . 'wp-content/uploads/dsp_media/user_photos/user_' . $this->userId)) {
            if ( ! file_exists(ABSPATH . 'wp-content/uploads')) {
                mkdir(ABSPATH . 'wp-content/uploads', 0777);
            }
            if ( ! file_exists(ABSPATH . 'wp-content/uploads/dsp_media')) {
                mkdir(ABSPATH . 'wp-content/uploads/dsp_media', 0777);
            }
            if ( ! file_exists(ABSPATH . 'wp-content/uploads/dsp_media/user_photos')) {
                mkdir(ABSPATH . 'wp-content/uploads/dsp_media/user_photos', 0777);
            }
            // it will default to 0755 regardless
            mkdir(ABSPATH . 'wp-content/uploads/dsp_media/user_photos/user_' . $this->userId, 0755);
            mkdir(ABSPATH . 'wp-content/uploads/dsp_media/user_photos/user_' . $this->userId . '/thumbs', 0755);
            mkdir(ABSPATH . 'wp-content/uploads/dsp_media/user_photos/user_' . $this->userId . '/thumbs1', 0755);
            // Finally, chmod it to 777
            chmod(ABSPATH . 'wp-content/uploads/dsp_media/user_photos/user_' . $this->userId, 0777);
            chmod(ABSPATH . 'wp-content/uploads/dsp_media/user_photos/user_' . $this->userId . '/thumbs', 0777);
            chmod(ABSPATH . 'wp-content/uploads/dsp_media/user_photos/user_' . $this->userId . '/thumbs1', 0777);

        } else if ( ! file_exists(ABSPATH . 'wp-content/uploads/dsp_media/user_photos/user_' . $this->userId . '/thumbs')) {
            mkdir(ABSPATH . 'wp-content/uploads/dsp_media/user_photos/user_' . $this->userId . '/thumbs', 0755);
            mkdir(ABSPATH . 'wp-content/uploads/dsp_media/user_photos/user_' . $this->userId . '/thumbs1', 0755);

            chmod(ABSPATH . 'wp-content/uploads/dsp_media/user_photos/user_' . $this->userId . '/thumbs', 0777);
            chmod(ABSPATH . 'wp-content/uploads/dsp_media/user_photos/user_' . $this->userId . '/thumbs1', 0777);
        }

        if ( ! file_exists(ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $this->userId . "/" . 'tmp-images')) {
            mkdir(ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $this->userId . "/" . 'tmp-images',
                0755);
            chmod(ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $this->userId . "/" . 'tmp-images',
                0777);
        }
    }

    public function profile_square_crop($src_image, $dest_image, $thumb_size = 64, $jpg_quality = 90)
    {

        // Get dimensions of existing image
        $image = getimagesize($src_image);

        // Check for valid dimensions
        if ($image[0] <= 0 || $image[1] <= 0) {
            return false;
        }

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
        if ($image_data == false) {
            return false;
        }

        // Calculate measurements
        if ($image[0] & $image[1]) {
            // For landscape images
            $x_offset    = ($image[0] - $image[1]) / 2;
            $y_offset    = 0;
            $square_size = $image[0] - ($x_offset * 2);
        } else {
            // For portrait and square images
            $x_offset    = 0;
            $y_offset    = ($image[1] - $image[0]) / 2;
            $square_size = $image[1] - ($y_offset * 2);
        }

        // Resize and crop

        $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
        $white  = imagecolorallocate($canvas, 255, 255, 255);
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

    public function unlink_previous_pictures()
    {
        global $wpdb;

        $my_img = $wpdb->get_row("select picture from $this->dsp_members_photos where user_id=$this->userId",
            ARRAY_A);

        $old_img             = $my_img['picture'];
        $del_img_path        = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $this->userId . "/" . $old_img;
        $del_thumb_img_path  = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $this->userId . "/thumbs/thumb_" . $old_img;
        $del_thumb1_img_path = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $this->userId . "/thumbs1/thumb_" . $old_img;

        if ($old_img != "") {
            unlink($del_img_path);
            unlink($del_thumb_img_path);
            unlink($del_thumb1_img_path);
        }
    }

}

?>