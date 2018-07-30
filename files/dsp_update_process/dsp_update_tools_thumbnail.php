<?php

/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
//error_reporting(0);
global $wpdb;
$dsp_user_profiles = $wpdb->prefix . dsp_user_profiles;
$dsp_members_photos = $wpdb->prefix . dsp_members_photos;
define("WIDTH", "150");
define("HEIGHT", "150");
define("width", "100");
define("height", "100");

/* class thumbnailGenerator {
  var $allowableTypes = array(
  IMAGETYPE_GIF,
  IMAGETYPE_JPEG,
  IMAGETYPE_PNG
  );
  public function imageCreateFromFile($filename, $imageType) {
  switch($imageType) {
  case IMAGETYPE_GIF  : return imagecreatefromgif($filename);
  case IMAGETYPE_JPEG : return imagecreatefromjpeg($filename);
  case IMAGETYPE_PNG  : return imagecreatefrompng($filename);
  default             : return false;
  }
  }
  public function generate($sourceFilename, $maxWidth, $maxHeight, $targetFormatOrFilename = 'jpg') {
  $size = getimagesize($sourceFilename); // 0 = width, 1 = height, 2 = type
  // check to make sure source image is in allowable format
  if(!in_array($size[2], $this->allowableTypes)) {
  return false;
  }
  // work out the extension, what target filename should be and output function to call
  $pathinfo = pathinfo($targetFormatOrFilename);
  if($pathinfo['basename'] == $pathinfo['filename']) {
  $extension = strtolower($targetFormatOrFilename);
  // set target to null so writes out to browser
  $targetFormatOrFilename = null;
  }
  else {
  $extension = strtolower($pathinfo['extension']);
  }
  switch($extension) {
  case 'gif' : $function = 'imagegif'; break;
  case 'png' : $function = 'imagepng'; break;
  default    : $function = 'imagejpeg'; break;
  }
  // load the image and return false if didn't work
  $source = $this->imageCreateFromFile($sourceFilename, $size[2]);
  if(!$source) {
  return false;
  }
  // write out the appropriate HTTP headers if going to browser
  if($targetFormatOrFilename == null) {
  if($extension == 'jpg') {
  header("Content-Type: image/jpeg");
  }
  else {
  header("Content-Type: image/$extension");
  }
  }
  // if the source fits within the maximum then no need to resize
  if($size[0] <= $maxWidth && $size[1] <= $maxHeight) {
  $function($source, $targetFormatOrFilename);
  }
  else {
  $target = imagecreatetruecolor($maxWidth, $maxHeight);
  $white = imagecolorallocate($target, 255, 255, 255);
  imagefill($target, 0, 0, $white);
  imagecopyresampled($target, $source, 0, 0, 0, 0, $maxWidth, $maxHeight, $size[0], $size[1]);
  $function($target, $targetFormatOrFilename);
  }
  return true;
  }
  } */

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

$profile_details_table = $wpdb->get_results("SELECT * FROM $dsp_user_profiles ");
foreach ($profile_details_table as $profile_details) {
    $user_id = $profile_details->user_id;

    $pluginpath = WP_DSP_ABSPATH;
    if (!file_exists($pluginpath . '/user_photos/user_' . $user_id)) {
        // it will default to 0755 regardless 
        mkdir($pluginpath . '/user_photos/user_' . $user_id, 0755);
        mkdir($pluginpath . '/user_photos/user_' . $user_id . '/thumbs', 0755);
        mkdir($pluginpath . '/user_photos/user_' . $user_id . '/thumbs1', 0755);
        // Finally, chmod it to 777
        chmod($pluginpath . '/user_photos/user_' . $user_id, 0777);
        chmod($pluginpath . '/user_photos/user_' . $user_id . '/thumbs', 0777);
        chmod($pluginpath . '/user_photos/user_' . $user_id . '/thumbs1', 0777);
    } else if (!file_exists(dirname(__FILE__) . '/user_photos/user_' . $user_id . '/thumbs')) {
        mkdir($pluginpath . '/user_photos/user_' . $user_id . '/thumbs', 0755);
        mkdir($pluginpath . '/user_photos/user_' . $user_id . '/thumbs1', 0755);

        chmod($pluginpath . '/user_photos/user_' . $user_id . '/thumbs', 0777);
        chmod($pluginpath . '/user_photos/user_' . $user_id . '/thumbs1', 0777);
    }

    $user_profile_image = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '" . $user_id . "'");
    $picture = $user_profile_image->picture;




    $dir = WPDATE_URL . "/user_photos/user_" . $user_id . "/" . $picture;

    $thumb_name1 = WP_DSP_ABSPATH . "user_photos/user_" . $user_id . "/thumbs1/thumb_" . $picture;
    //$tg = new thumbnailGenerator;
    //$tg->generate($dir, width,height, $thumb_name1);
    $thumb = square_crop($dir, $thumb_name1, 100);

    $thumb_name = WP_DSP_ABSPATH . "user_photos/user_" . $user_id . "/thumbs/thumb_" . $picture;
    $thumb1 = square_crop($dir, $thumb_name, 150);
    //$tg->generate($dir, WIDTH,HEIGHT, $thumb_name);
}
echo "<script type='text/javascript'> location.href='admin.php?page=dsp-admin-sub-page3&pid=tools_thumbnail'</script>";
?>