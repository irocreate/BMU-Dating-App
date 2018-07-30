<?php

include("../../../wp-config.php");
global $wp_query;
global $wpdb;
$page_id = $_REQUEST['page']; //fetch post query string id
$dsp_general_settings = $wpdb->prefix . "dsp_general_settings";
$posts_table = $wpdb->prefix . "posts";
$insertMemberPageId = "UPDATE $dsp_general_settings SET setting_value = '$page_id' WHERE setting_name ='member_page_id'";
$wpdb->query($insertMemberPageId);
$posts_table = $wpdb->prefix . "posts";
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$page_id'");
// ROOT PATH 
$root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";  // Print Site root link
$token = $_GET["token"];
if (!$token) {
    die();
}
$url = false;
$a = explode(",", $token);
foreach ($a as $k) {
    $url .= "$k/";
}
// uitlezen van de iDeal parameters 
foreach ($_GET as $k => $v) {
    if ($k != "token") {
        $url .= "$k/$v/";
    }
}
// Stel deze URL in op uw site
$url = $root_link . "/" . $url;
//echo $url; exit(); 
echo "<script>document.location.href='".$url."';</script>";
?>