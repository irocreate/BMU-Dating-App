<?php
include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */



include_once("dspFunction.php");

$user_id = $_REQUEST['user_id'];

$imagepath = get_option('siteurl') . '/wp-content/';  // image Path
?>

<img src="<?php echo display_members_photo($user_id, $imagepath); ?>" style="width:100px; height:100px;"  class="img" />
