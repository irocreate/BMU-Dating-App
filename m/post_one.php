<?php

include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

global $wpdb;

//$user_id=$_REQUEST['user_id'];  // print session USER_ID
//if(isset($_POST)) 
{
$text = anti_injection($_REQUEST['usermsg']);

$dsp_chat_table = $wpdb->prefix . "dsp_chat_one";


$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM  $dsp_chat_table where (sender_id=" . $_REQUEST['sender_id'] . " or receiver_id=" . $_REQUEST['sender_id'] . ") and (receiver_id=" . $_REQUEST['receiver_id'] . " or sender_id=" . $_REQUEST['receiver_id'] . ")"));

if ($count < 100) {

$insert = mysql_query("INSERT INTO $dsp_chat_table SET sender_id='" . $_REQUEST['sender_id'] . "',receiver_id='" . $_REQUEST['receiver_id'] . "', chat_text='$text', time='" . date('g:i A') . "', date='" . date('Y-m-d') . "'");
} else {
$wpdb->query("DELETE FROM $dsp_chat_table order by chat_id ASC LIMIT 1");
$insert = mysql_query("INSERT INTO $dsp_chat_table SET sender_id='" . $_REQUEST['sender_id'] . "',receiver_id='" . $_REQUEST['receiver_id'] . "', chat_text='$text', time='" . date('g:i A') . "', date='" . date('Y-m-d') . "'");
}
}
?>
<?php

function anti_injection($sql) {

    // $sql = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|like|show tables|'\| |,|\|'|<|>|#|\|--|\\\\)/"), "" ,$sql);
    // we are commenting the above  like as sql_regcase is depricated in php now

    $sql = preg_replace("/(from|select|insert|delete|where|drop table|show tables|#|\*|–|\\\\)/i", "", $sql);

    $sql = trim($sql);
    $sql = strip_tags($sql);
    $sql = (get_magic_quotes_gpc()) ? $sql : addslashes($sql);
    return $sql;
}

?>