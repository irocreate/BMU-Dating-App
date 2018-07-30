<?php
$dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;

$gift_id = isset($_REQUEST['gift_Id']) ? $_REQUEST['gift_Id'] : '';



$gifts = $wpdb->get_row("SELECT * FROM `$dsp_user_virtual_gifts` where gift_id=$gift_id");
?>
<div class="show-comment">
    <img style="margin-left: 20px;width:67px; height:67px;" src="<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/gifts/" . $gifts->gift_image; ?>" />
    <span> <br />
        <a class="reply-btn" href="javascript:void();" onclick="updateGift('<?php echo $gifts->gift_id ?>', 'approve', '<?php echo language_code('DSP_ARE_YOU_SURE_TO_DELETE_IT') ?>')"><?php echo language_code('DSP_MEDIA_LINK_APPROVE'); ?></a> 
        &nbsp;|&nbsp;&nbsp;<a class="delete-btn" href="javascript:void();" onclick="updateGift('<?php echo $gifts->gift_id ?>', 'Del', '<?php echo language_code('DSP_ARE_YOU_SURE_TO_DELETE_IT') ?>')"><?php echo language_code('DSP_DELETE'); ?> </a>
    </span>
</div>
