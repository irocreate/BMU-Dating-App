<?php
$users_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_comments_table = $wpdb->prefix . DSP_USER_COMMENTS;

$action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';
$comment_id = isset($_REQUEST['comments_id']) ? $_REQUEST['comments_id'] : ''; // here frnd_request_Id contains comment id
if ($action == 'Del') {
    $check_comment = $wpdb->get_var("SELECT count(*) FROM $dsp_comments_table  WHERE comments_id = '$comment_id'");
    if ($check_comment != 0) {
        $delete = $wpdb->query("delete from $dsp_comments_table  WHERE comments_id = '$comment_id' ");
        $delete_comment_msg = "Comment has been Deleted";
    }
} else if ($action == 'approve') {
    $check_comment = $wpdb->get_var("SELECT count(*) FROM $dsp_comments_table  WHERE comments_id = '$comment_id'");
    if ($check_comment != 0) {
        $wpdb->query("update $dsp_comments_table set status_id=1 WHERE comments_id = '$comment_id' ");
        $result = "Comment has been Approved";
    }
} else {

    if ($check_approve_comments_status->setting_status == 'Y') {
        $comment_chk = $wpdb->get_var("SELECT count(*) FROM `$dsp_comments_table` where member_id=$user_id and status_id=0 ");
        if ($comment_chk != 0) {
            $comment_list = $wpdb->get_results("SELECT * FROM `$dsp_comments_table` where member_id=$user_id and status_id=0  ORDER BY `date_added` DESC");
            ?>
            <div class="swipe_div" id="mainComment" style="height: 122px">
                <ul id="swipe_ulComment"  style="padding-left:0px;text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">
                    <?php
                    foreach ($comment_list as $comments) {
                        $users_details = $wpdb->get_row("SELECT ID,user_login FROM $users_table  WHERE ID='$comments->user_id'");
                        $check_gender = $wpdb->get_var("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$comments->user_id'");
                        ?>
                        <li style="float:left;margin-right:16px;width:85px;">
                            <a onclick="viewProfile('<?php echo $comments->user_id; ?>', 'my_profile')" >
                                <div class="image-box" style="margin-bottom: 10px;">
                                    <img title="<?php echo $users_details->user_login; ?>" src="<?php echo display_members_photo($comments->user_id, $imagepath); ?>" style="height: 85px;width: 85px;"/>
                                </div>
                            </a>
                            <div class="dsp_name">
                                <a onclick="displayComment('<?php echo $comments->comments_id; ?>')">View Comment</a>
                            </div>

                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php }else{ ?>
            <div style="text-align:center;" class="box-page">
            <strong><?php echo language_code('DSP_NO_COMMENT') ?></strong>
        </div>
       <?php } ?>
        <?php
    }else{ ?>
        <div style="text-align:center;" class="box-page">
            <strong><?php echo language_code('DSP_NO_COMMENT') ?></strong>
        </div>
  <?php  }
}
?>