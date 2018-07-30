<?php

/*
  Copyright (C) MyAllenMedia, LLC - All Rights Reserved!
  Author - WPAuctionSoftware.com
  WordPress Auction Plugin
  Portland, Oregon, 97227
  (503) 893-2807
  contact@wpauctionsoftware.com
 */
?>
<?php

//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On'); 
global $wpdb;

//Default language packs
$avialLanguagePacks = array(
                            'ru'=>'Russian' , 
                            'ch'=>'chinese' ,
                            'fr'=>'french' , 
                            'sp'=>'spanish' , 
                            'po'=>'portugese', 
                            'du'=>'dutch' , 
                            'ge'=>'german' 
                    );
$upload_path = WP_DSP_ABSPATH . '../../uploads/flags/';
$invalidEntry = '';

// echo '<br>'.$upload_path;

$file_upload_size_in_KB = 2000;
// To change the user image
//define a maxim size for the uploaded images
define("MAX_SIZE", $file_upload_size_in_KB);
// define the width and height for the thumbnail
// note that theese dimmensions are considered the maximum dimmension and are not fixed,
// because we have to keep the image ratio intact or it will be deformed
define("WIDTH", "24");
define("HEIGHT", "24");
define("DIMENSION", "24");
define("MIN_WIDTH", "24");
define("MIN_HEIGHT", "24");
define("MAX_WIDTH", "50"); // we have removed max width and max height option client don't want this'
define("MAX_HEIGHT", "50");

// this is the function that will create the thumbnail image from the uploaded image
// the resize will be done considering the width and height defined, but without deforming the image
function make_thumb($src_image, $dest_image, $thumb_size = DIMENSION, $jpg_quality = 90) {

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
    if ($image[0] > $image[1]) {
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

// This function reads the extension of the file.
// It is used to determine if the file is an image by checking the extension.
function getExtension($str) {
    $i = strrpos($str, ".");
    if (!$i) {
        return "";
    }
    $l = strlen($str) - $i;
    $ext = substr($str, $i + 1, $l);
    return $ext;
}

// This variable is used as a flag. The value is initialized with 0 (meaning no error found)
//and it will be changed to 1 if an errro occures. If the error occures the file will not be uploaded.
$errors = 0;
// checks if the form has been submitted
/*$GLOBALS['default'] = '';*/
global $default;

if (isset($_POST['add_lang']) || isset($_POST['import_lang'])) {

    if ($mode == "add") {
        // check if language name already exist
        $language_name = $_POST['txt_language_name'];
        $checkexist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_language_detail_table WHERE language_name ='$language_name'");
    } else {
        // check if language name already exist
        $language_name = $_POST['txt_language_name'];
        $checkexist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_language_detail_table WHERE language_name ='$language_name' and language_id!='$editLanguageId'");
    }
    if ($checkexist == 0) {
        //echo '<br>submit';
        $default_image = $_FILES['default_image']['name'];

//reads the name of the file the user submitted for uploading
        // if it is not empty
        if ($default_image) {
            //  echo '<br>def imae';
            // To delete the old  user image file
            //unlink('wp-content/plugins/mam_auction/auction_images/'.$get_AuctionId->defualt_image );
            // get the original name of the file from the clients machine
            $filename = stripslashes($default_image);
            //echo '<br>file name='.$filename;
            // get the extension of the file in a lower case format
            $extension = getExtension($filename);
            $extension = strtolower($extension);
            // if it is not a known extension, we will suppose it is an error, print an error message
            //and will not upload the file, otherwise we continue
            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
                $msg.=language_code('DSP_USER_UNKNOWN_EXTENSION_FOR_IMAGE') . '<br>';
                $invalidEntry = 1;
                $errors = 1;
                $default_image = "";
            } else {
                //echo '<br>valid';
                // get the size of the image in bytes

                $size = getimagesize($_FILES['default_image']['tmp_name']);
                $width = $size[0];
                $height = $size[1];

                $sizekb = filesize($_FILES['default_image']['tmp_name']);

                //compare the size with the maxim size we defined and print error if bigger
                if ($sizekb > MAX_SIZE * 1024) {
                    $msg.=language_code('DSP_USER_IS_TOO_LARGE_PLEASE_REDUCE_UR_IMAGE_BELOW') . MAX_SIZE . DSP_USER_KB . '<br>';
                    $errors = 1;
                    $default_image = "";
                    $invalidEntry = 1;
                }

                //compare the size with the minimum  we defined and print error if bigger
                if ($height < MIN_HEIGHT || $width < MIN_WIDTH) {
                    $msg.=language_code('DSP_MINIMUM') . MIN_HEIGHT . 'x' . MIN_WIDTH . language_code('DSP_USER_DIMENSION_IS_REQUIRED') . '<br>';
                    $errors = 1;
                    $default_image = "";
                    $invalidEntry = 1;
                }

                //we will give an unique name, for example the time in unix time format
                $image_name = $_FILES['default_image']['name'];

                $newname = $upload_path . $image_name;
                //$newname = "/wp-content/uploads/flags/".$image_name;
                //echo '<br>new'.$newname;;die;
                $imageName = $image_name;
                if ($errors != 1) {
                    //echo '<br>msg='.$msg.'<br>no error====tmp name='.$_FILES['default_image']['tmp_name'];die;
                    $copied = move_uploaded_file($_FILES['default_image']['tmp_name'], $newname);
                    //var_dump($copied);die;
                    //we verify if the image has been uploaded, and print error instead
                }
                if (!$copied) {
                    //  echo '<br>can not copy';
                    $invalidEntry = 1;
                    $errors = 1;
                    $default_image = "";
                } else {
                    //  echo 'copy';
                    // the new thumbnail image will be placed in images/thumbs/ folder
                    $thumb_name = $upload_path . DIRECTORY_SEPARATOR . $image_name;
                    // call the function that will create the thumbnail. The function will get as parameters
                    //the image name, the thumbnail name and the width and height desired for the thumbnail
                    //$thumb=make_thumb($newname,$thumb_name,WIDTH,HEIGHT);
                    $thumb = make_thumb($newname, $thumb_name);
                    //unlink($image_name);

                    if ($editLanguageId != '') {
                        //get the previous image name
                        $prevImageName = $wpdb->get_var("SELECT flag_image FROM $dsp_language_detail_table WHERE language_id='$editLanguageId'");

                        // update the language table with new image
                        $updateQuery = "UPDATE $dsp_language_detail_table SET flag_image='$default_image' WHERE language_id='$editLanguageId'";
                        $wpdb->query($updateQuery);
                        //unlink the previous image
                        $prevImagePath = $upload_path . $prevImageName;
                        @unlink($prevImagePath);
                    }
                } // end of else
                //  echo 'msg'.$msg;
            }
        } // end default image upload
    } else {
        $msg = language_code('DSP_LANGUAGE_NAME_ALREADY_EXIST');
        $invalidEntry = 1;
    }
}
?>