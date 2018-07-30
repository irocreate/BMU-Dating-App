<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

global $wpdb;
$dsp_mobile_app_settings = $wpdb->prefix . DSP_MOBILE_APP_SETTING_TABLE;
$msg = "";

if (isset($_POST["btn_submit_abt"])) {
    $abt = $_POST['txt_abt_me'];
    $wpdb->query("update $dsp_mobile_app_settings set about_text='$abt' where setting_id=1");
    $msg = language_code("DSP_SETTINGS_UPDATED");
}

if (isset($_POST["btn_submit_term"])) {
    $term = $_POST['txt_term'];
    $wpdb->query("update $dsp_mobile_app_settings set term_text='$term' where setting_id=1");
    $msg = language_code("DSP_SETTINGS_UPDATED");
}

if (isset($_POST['btn_submit_logo'])) {

    // ************************************ UPLOAD_IMAGE *****************************************//


    $image_file = $_FILES['mobile_logo']['name'];


    define("WIDTH", "100");
    define("HEIGHT", "100");
    define("MAX_HEIGHT", "250");
    define("MAX_WIDTH", "250");
    define("MAX_SIZE", 2000); // IN KB

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
    if ($image_file) {

        /* -------------------------- check folder exist ------------------- */

        $dirs = wp_upload_dir();
        $upload_dir = $dirs['basedir'];

        if (!file_exists($upload_dir . '/dsp_media/mobile_logo')) {
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777);
            }

            if (!file_exists($upload_dir . '/dsp_media')) {
                mkdir($upload_dir . '/dsp_media', 0777);
            }

            if (!file_exists($upload_dir . '/dsp_media/mobile_logo')) {

                mkdir($upload_dir . '/dsp_media/mobile_logo', 0777);
            }



            // it will default to 0755 regardless 

            mkdir($upload_dir . '/dsp_media/mobile_logo', 0755);


            chmod($upload_dir . '/dsp_media/mobile_logo', 0777);
        }






        /* -------------------------- check folder exist ends------------------- */

        $filename = stripslashes($_FILES['mobile_logo']['name']);

        //log_message('debug','file name'.$filename);

        $extension = getExtension($filename);
        $extension = strtolower($extension);

        $size = getimagesize($_FILES['mobile_logo']['tmp_name']);
        $width = $size[0];
        $height = $size[1];

        $size = getimagesize($_FILES['mobile_logo']['tmp_name']);
        $sizekb = filesize($_FILES['mobile_logo']['tmp_name']);

        //log_message('debug','file ext'.$extension);
        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
            //echo '<h1>Unknown extension!</h1>';
            $msg = "Error: Unknown extension!";
            $errors = 1;
        } else if ($sizekb > MAX_SIZE * 1024) { // //1 megabyte (Mb) = 1024 kb = 1,048,576 bytes
            $msg.= language_code('DSP_USER_IS_TOO_LARGE_PLEASE_REDUCE_UR_IMAGE_BELOW') . ' ( ' . MAX_SIZE . ' KB ) ' . '<br>';
        } else if ($height > MAX_HEIGHT || $width > MAX_WIDTH) {
            $msg.=language_code('DSP_MAXIMUM') . ' ' . MAX_WIDTH . 'X' . MAX_HEIGHT . ' ' . language_code('DSP_USER_DIMENSION_IS_REQUIRED') . '<br>';
        } else if ($height < HEIGHT || $width < WIDTH) {
            $msg.=language_code('DSP_MINIMUM') . ' ' . WIDTH . 'X' . HEIGHT . ' ' . language_code('DSP_USER_DIMENSION_IS_REQUIRED') . '<br>';
        } else {

            $img_name = basename($image_file);
            $new_name = "mobilelogo_" . $img_name;
            $newname = ABSPATH . "/wp-content/uploads/dsp_media/mobile_logo" . "/" . $new_name;
            //$copied = copy($_FILES['mobile_logo']['tmp_name'], $newname);
            //$thumb_name=ABSPATH."/wp-content/uploads/dsp_media/mobile_logo/thumbs/".$new_name;
            //	$thumb=square_crop($newname,$thumb_name,100);

            if ($height > HEIGHT || $width > WIDTH) {
                // WE WILL MAKE THUMB with MAX_DISPLAY_DIMENSION 
                $copied = square_crop($_FILES['mobile_logo']['tmp_name'], $newname, 100);
            } else { // if height and width are less then max then we will copy that as it is no need to make a thumb
                $copied = copy($_FILES['mobile_logo']['tmp_name'], $newname);
            }

            if (!$copied) {
                $error_uploading = language_code('DSP_ERROR_UPLOADING');
                $print_error_uploading = str_replace("<#FILE_NAME#>", $img_name, $error_uploading);
                echo $print_error_uploading;
            } else {
                $update_img = mysql_query("select mobile_logo from $dsp_mobile_app_settings where setting_id=1");
                $my_img = mysql_fetch_array($update_img);
                $old_img = $my_img['mobile_logo'];

                $del_img_path = ABSPATH . "/wp-content/uploads/dsp_media/mobile_logo" . "/" . $old_img;


                if ($old_img != "" && ($old_img != "iphone-usericon100.jpg")) {
                    unlink($del_img_path);
                }


                $wpdb->query("UPDATE $dsp_mobile_app_settings SET mobile_logo = '$new_name' WHERE setting_id  = 1");
                $msg = language_code('DSP_SETTINGS_UPDATED');
            }
        }// End if(move_uploaded_file)
    }  // End if ($image_file)
}

$query = "select * from $dsp_mobile_app_settings";
$result = $wpdb->get_row($query);

if (count($result) > 0) {
    $img = get_bloginfo('url') . '/wp-content/uploads/dsp_media/mobile_logo/' . $result->mobile_logo;
}

if (!is_array(@getimagesize($img))) {  //don't exist  
    $img = get_bloginfo('url') . '/wp-content/uploads/dsp_media/mobile_logo/iphone-usericon100.jpg';
}
?> 







<div id="general" class="postbox">

    <h3 class="hndle"><span><?php echo language_code('DSP_MOBILE_DATING_APP_SETTINGS'); ?></span></h3>

    <div style="height:20px;"></div>

    <div class="dsp_thumbnails1" >


        <form action="" method="post" enctype="multipart/form-data">
            <table width="100%" border="0" cellpadding="6">

                <?php if (isset($msg)) {
                    ?>
                    <tr>
                        <td></td>
                        <td style="color: red;">
                            <?php echo $msg; ?>
                        <td>
                    </tr>
                <?php } ?>

                <tr>
                    <td width="20%" style="text-align: right;padding-right: 10px;"><?php echo language_code('DSP_MOBILE_LOGO_PNG'); ?>:</td>
                    <td  width="20%"><input type="file" name="mobile_logo" accept="image/*"/>


                    </td>
                    <td>
                        <img src="<?php if (isset($img)) echo $img; ?>" style="width:100px;height: 100px;" alt="Mobile Logo" />
                    </td>

                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="btn_submit_logo" value="Add" class="button"/></td>
                </tr>

            </table>
        </form>

        <form action="" method="post" >
            <table width="100%" border="0" cellpadding="6">

                <tr>

                    <td width="20%"  style="text-align: right;padding-right: 10px;"><?php echo language_code('DSP_ABOUT_ME_TEXT'); ?>:</td>

                    <td><textarea name="txt_abt_me"><?php if (isset($result)) echo $result->about_text; ?></textarea></td>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" name="btn_submit_abt" value="Save" class="button"/></td>
                </tr>

            </table>
        </form>


        <form action="" method="post">
            <table width="100%" border="0" cellpadding="6">

                <tr>

                    <td width="20%"  style="text-align: right;padding-right: 10px;"><?php echo language_code('DSP_TERM_TEXT'); ?>:</td>
                    <td><textarea name="txt_term"><?php if (isset($result)) echo $result->term_text; ?></textarea></td>

                </tr>

                <tr>
                    <td></td>
                    <td><input type="submit" name="btn_submit_term" value="Save" class="button"/></td>
                </tr>
            </table> 
        </form>

    </div>

    <div style="height:20px;"></div>

</div>