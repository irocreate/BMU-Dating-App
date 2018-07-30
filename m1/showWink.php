<?php
$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
$dsp_flirt_table = $wpdb->prefix . DSP_FLIRT_TEXT_TABLE;

$wink_msg_id = isset($_REQUEST['wink_id']) ? $_REQUEST['wink_id'] : '';

//echo "SELECT * FROM `$dsp_member_winks_table` where wink_mesage_id=$wink_msg_id";
//echo "SELECT * FROM $dsp_flirt_table WHERE Flirt_ID = '$wink_id'";
//
$wink = $wpdb->get_row("SELECT * FROM `$dsp_member_winks_table` where wink_mesage_id=$wink_msg_id");
$wink_id = $wink->wink_id;


$exist_wink_message = $wpdb->get_row("SELECT * FROM $dsp_flirt_table WHERE Flirt_ID = '$wink_id'");

// update wink as read 
$wpdb->query("UPDATE $dsp_member_winks_table SET wink_read='Y' where wink_mesage_id ='$wink_msg_id'");
?>


<p ><?php echo $exist_wink_message->flirt_Text ?></p>
<div class="delete-button spacer-bottom-sm" onclick="updateWink(<?php echo $wink_msg_id ?>, '<?php echo language_code('DSP_ARE_YOU_SURE_TO_DELETE_IT') ?>');" ><?php echo language_code('DSP_DELETE_LINK'); ?>

