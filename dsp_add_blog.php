<?php

/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$dsp_my_blog_table = $wpdb->prefix . DSP_MY_BLOGS_TABLE;
if (get('mode') == 'edit') {
    $blog_id = isset($_REQUEST['blog_id']) ? $_REQUEST['blog_id'] : get('blog_id');
    if (get('action') == 'update') {
        $blog_id = isset($_REQUEST['blog_id']) ? $_REQUEST['blog_id'] : get('blog_id');
        $blog_title = isset($_REQUEST['title']) ? esc_sql(sanitizeData(trim($_REQUEST['title']), 'xss_clean')) : '';
        $blog_content = isset($_REQUEST['blog_content']) ? esc_sql(sanitizeData(trim($_REQUEST['blog_content']), 'xss_clean')) : '';
        $tag = isset($_REQUEST['tag']) ? esc_sql(sanitizeData(trim($_REQUEST['tag']), 'xss_clean')) : '';
        $wpdb->query("UPDATE $dsp_my_blog_table SET blog_title = '$blog_title',blog_content = '$blog_content',tag ='$tag' WHERE blog_id  = '$blog_id'");
    }
    $my_blog_table = $wpdb->get_row("SELECT * FROM $dsp_my_blog_table WHERE blog_id= $blog_id");
?>
    <div class="box-border">
        <div class="box-pedding">
            <form name="frmuseraccount" method="post" action="<?php echo $root_link . "extras/blogs/add_blogs/blog_id/" . $my_blog_table->blog_id . "/mode/edit/action/update/"; ?>" class="dspdp-form-horizontal">
                <div class="heading-submenu"><strong><?php echo language_code('DSP_EDIT'); ?></strong></div>
                <ul class="edit-blog">
                    <li class="dspdp-form-group"><span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3"><?php echo language_code('DSP_ADD_MY_BLOGS_TITLE') ?>:</span> <span class="dspdp-col-sm-6 dsp-sm-6 "><input type="text" class="dspdp-form-control dsp-form-control " name="title" value="<?php echo stripslashes($my_blog_table->blog_title); ?>" /></span></li>
                    <li class="dspdp-form-group"><span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3"><?php echo language_code('DSP_ADD_MY_BLOG') ?>:</span> 
                        <span class="dspdp-col-sm-9 dsp-sm-9"><textarea class="dspdp-form-control dsp-form-control " name="blog_content" cols="30" rows="5"   ><?php echo stripslashes($my_blog_table->blog_content); ?></textarea></span></li>
                    <li class="dspdp-form-group"><span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3"><?php echo language_code('DSP_ADD_MY_BLOGS_TAGS') ?>:</span>
					<span class="dspdp-col-sm-6 dsp-sm-6  dspdp-xs-form-group"><input class="dspdp-form-control dsp-form-control " type="text" name="tag" value="<?php echo stripslashes($my_blog_table->tag); ?>" /></span>
                       <span class=""> <input type="submit" name="submit" class="dsp_myblog_submit_button dspdp-btn dspdp-btn-default" value="Edit"   /></span></li>
                </ul>
            </form>
        </div>
    </div>
    <?php
} else {
    if (get('mode') == 'add') {
        $blog_title = isset($_REQUEST['title']) ? esc_sql(sanitizeData(trim($_REQUEST['title']), 'xss_clean')) : '';
        $blog_content = isset($_REQUEST['blog_content']) ? esc_sql(sanitizeData(trim($_REQUEST['blog_content']), 'xss_clean')) : '';
        $tag = isset($_REQUEST['tag']) ? esc_sql(sanitizeData(trim($_REQUEST['tag']), 'xss_clean')) : '';
        $today = date('Y-m-d h:m:s');
        $insert = $wpdb->query("INSERT INTO $dsp_my_blog_table SET user_id = $user_id,blog_title='$blog_title',blog_content='$blog_content', Date='$today',tag='$tag'");
    }
    ?>
    <?php if (isset($updated) && $updated == true) { ?>
        <div class="thanks">
            <p align="center" class="error"><?php echo language_code('DSP_ACCOUNT_SETTINGS_UPDATED') ?></p>
        </div>
    <?php } ?>
    <?php //---------------------------------------START ACCOUNT SETTINGS ------------------------------------// ?>

    <div class="box-border dsp-form-container">
        <div class="box-pedding">
            <form name="frmuseraccount" method="post" action="<?php echo $root_link . "extras/blogs/add_blogs/mode/add/"; ?>" class="dspdp-form-horizontal">
                <div class="heading-submenu"><strong><?php echo language_code('DSP_MENU_ADD_MY_BLOGS') ?></strong></div>
                <ul class="edit-blog">
                    <li class="dspdp-form-group dsp-form-group clearfix">
                      <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                        <?php echo language_code('DSP_ADD_MY_BLOGS_TITLE') ?>:
                      </span>
                        <span class="dspdp-col-sm-6 dsp-sm-6 "><input class="dspdp-form-control dsp-form-control " type="text" name="title" value="" /></span>
                    </li>
                    <li class="dspdp-form-group dsp-form-group clearfix"><span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3"><?php echo language_code('DSP_ADD_MY_BLOG') ?>:</span> <span class="dspdp-col-sm-9 dsp-sm-6"><textarea name="blog_content" class="dspdp-form-control dsp-form-control " cols="30" rows="10"  ></textarea></span></li>
                    <li class="dspdp-form-group dsp-form-group clearfix"><span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3"><?php echo language_code('DSP_ADD_MY_BLOGS_TAGS') ?>:</span>
                       <span class="dspdp-col-sm-6 dsp-sm-6  dsp-sm-6 dspdp-xs-form-group dsp-xs-form-group">
                        <input class="dspdp-form-control dsp-form-control  " type="text" name="tag" value="" /></span>
                        <span class="dspdp-col-sm-3 dsp-sm-3"><input type="submit" name="submit" class="dsp_myblog_submit_button dspdp-btn dspdp-btn-default" value="<?php echo language_code('DSP_ADD_MY_BLOGS_ADD_BUTTON') ?>"   /></span>
                    </li>
                </ul>
				
            </form>
        </div>
    </div>
<?php } 
