<?php

include_once("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

$user_id = $_REQUEST['user_id'];

$member_id = $_REQUEST['member_id'];



$dsp_my_blog_table = $wpdb->prefix . DSP_MY_BLOGS_TABLE;

if (isset($_REQUEST['blog_id'])) {

    $blog_id = $_REQUEST['blog_id'];
    $blogDetail = $wpdb->get_row("SELECT * FROM $dsp_my_blog_table WHERE user_id=$member_id  and blog_id=$blog_id");
    ?>

    <?php

    if (count($blogDetail > 0)) {
        echo $blogDetail->blog_content;
        ;
    }
    ?>
    <?php

}?>