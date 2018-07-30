<?php

extract($_REQUEST);
$src = base64_decode($src);
$image = @imagecreatefromjpeg($src) or // Read JPEG Image
    $image = @imagecreatefrompng($src) or // or PNG Image
    $image = @imagecreatefromgif($src) or // or GIF Image
    $image = false; // If image is not JPEG, PNG, or GIF
// GD variables:
list($width, $height) = @getimagesize($src);

// Image sizes:
$bigImage = array($w, $h);

$source_aspect_ratio = $width / $height;
$big_aspect_ratio = $bigImage[0] / $bigImage[1];

if ($source_aspect_ratio > $big_aspect_ratio) {
    $temp_height = $bigImage[1];
    $temp_width = (int) ( $bigImage[1] * $source_aspect_ratio );
} else {
    $temp_width = $bigImage[0];
    $temp_height = (int) ( $bigImage[0] / $source_aspect_ratio );
}
//echo "$temp_width, $temp_height";
$temp_img = imagecreatetruecolor($temp_width, $temp_height);
imagecopyresampled($temp_img, $image, 0, 0, 0, 0, $temp_width, $temp_height, $width, $height);

$bx0 = ($temp_width - $bigImage[0]) / 2;
$by0 = ($temp_height - $bigImage[1]) / 2;

$desired = imagecreatetruecolor($bigImage[0], $bigImage[1]);
imagecopy($desired, $temp_img, 0, 0, $bx0, $by0, $bigImage[0], $bigImage[1]);

// Save image:
header("Content-type: image/jpeg");
imagejpeg($desired);

// Destroy images:
imagedestroy($image);
imagedestroy($desired);
