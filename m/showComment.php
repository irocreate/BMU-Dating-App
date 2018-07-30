<?php
$dsp_comments_table = $wpdb->prefix . DSP_USER_COMMENTS;

$comments_id = $_REQUEST['comments_id'];

$comment_list = $wpdb->get_var("SELECT comments FROM `$dsp_comments_table` where comments_id='$comments_id' ");
?>
<div><?php echo $comment_list; ?></div>
<span> <br />
    <a  onclick="updateComment('<?php echo $comments_id ?>', 'approve', '<?php echo language_code('DSP_ARE_YOU_SURE_TO_DELETE_IT') ?>')"><?php echo language_code('DSP_MEDIA_LINK_APPROVE'); ?></a>&nbsp;|&nbsp;<a  onclick="updateComment('<?php echo $comments_id ?>', 'Del', '<?php echo language_code('DSP_ARE_YOU_SURE_TO_DELETE_IT') ?>')"><?php echo language_code('DSP_DELETE'); ?> </a>
</span>