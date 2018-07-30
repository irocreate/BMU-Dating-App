<?php
$users_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;

$action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';
$gift_id = isset($_REQUEST['gift_Id']) ? $_REQUEST['gift_Id'] : '';

if ($action == 'Del') {
    $check_gift = $wpdb->get_var("SELECT count(*) FROM $dsp_user_virtual_gifts  WHERE gift_id = '$gift_id'");
    if ($check_gift != 0) {
        $delete = $wpdb->query("delete from $dsp_user_virtual_gifts  WHERE gift_id = '$gift_id' ");
    }
} else if ($action == 'approve') {
    $check_gift = $wpdb->get_var("SELECT count(*) FROM $dsp_user_virtual_gifts  WHERE gift_id = '$gift_id'");
    if ($check_gift != 0) {
        $wpdb->query("update $dsp_user_virtual_gifts set status_id=1 WHERE gift_id = '$gift_id' ");
    }
} else {
    $gift_chk = $wpdb->get_var("SELECT count(*) FROM `$dsp_user_virtual_gifts` where member_id=$user_id and status_id=0 ");
    if ($gift_chk != 0) {
        $gift_list = $wpdb->get_results("SELECT * FROM `$dsp_user_virtual_gifts` where member_id=$user_id and status_id=0  ORDER BY `date_added` DESC");
        ?>
        <div class="swipe_div" id="mainGift" style="height: 160px">
            <ul id="swipe_ulGift"  style="padding-left:0px;text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">
                <?php
                foreach ($gift_list as $gifts) {
                    $users_details = $wpdb->get_row("SELECT ID,user_login,display_name FROM $users_table  WHERE ID='$gifts->user_id'");
                    $check_gender = $wpdb->get_var("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$gifts->user_id'");
                    ?>
                    <li class="ivew-list">
                        <a onclick="viewProfile('<?php echo $comments->user_id; ?>', 'my_profile')" >
                            <div class="image-box" style="margin-bottom: 10px;">
                                <img title="<?php echo $users_details->user_login; ?>" src="<?php echo display_members_photo($gifts->user_id, $imagepath); ?>" class="img2 iviewed-img"/>
                            </div>
                        </a>
                        <div class="dsp_name"> 
                            <?php echo $users_details->display_name; ?>
                        </div>
                        <div class="dsp_name">
                            <a class="reply-btn" onclick="displayGift('<?php echo $gifts->gift_id; ?>')"><?php echo language_code('DSP_VIEW_GIFT') ?></a>
                        </div>
                    </li>


                <?php } ?>
            </ul>
        </div>		
    <?php }else{ ?>
         <div style="text-align:center;" class="box-page">
            <strong><?php echo language_code('DSP_NO_VIRTUAL_GIFTS') ?></strong>
        </div>
   <?php } ?>

    <?php
}?>