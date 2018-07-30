<div class="story-clm home-box  dspdp-col-sm-4 dspdp-spacer-lg  dsp-sm-4 dsp-spacer-lg">
    <div class="dsp-home-widget"><div class="dspdp-h4 dspdp-text-uppercase dspdp-spacer-md dsp-h4 dsp-text-uppercase dsp-spacer-md ">
    <span class="heading-text">&nbsp;</span><?php echo language_code("DSP_HAPPY_STORIES");?></div>
    <?php
    $dsp_stories = $wpdb->prefix . DSP_STORIES_TABLE;
    $story_result = $wpdb->get_results("select * from $dsp_stories order by date_added desc");
    if (count($story_result) > 0) {
        ?>
        
            <?php foreach ($story_result as $story_row) {
                ?>
                <div class="dspdp-bordered-item dspdp-small dsp-bordered-item dsp-small"><div class="dspdp-row dsp-row"><div class="dspdp-col-xs-4 dsp-xs-4"><img src="<?php echo get_bloginfo('url') . '/wp-content/uploads/dsp_media/story_images/thumb_' . $story_row->story_image; ?>" class="dsp-img-responsive dsp-circular" alt="<?php echo $story_row->story_image;?>" /> </div>
                    <div class="dspdp-col-xs-8 dsp-xs-8"><div class="content-text"><div class="title-txt"><?php echo stripslashes($story_row->story_title); ?></div>
                        <?php
                        $story_content = ltrim(str_replace('\\', '', $story_row->story_content));
                        ?>
                        <?php echo substr($story_content, 0, 49); ?> <!--<a href="#"><span>more..</span></a>--></div></div></div></div>
            <?php } ?>
        
        <?php if (is_user_logged_in()) { ?>

            <a class="read-more dsp-read-more" href="<?php echo $root_link . '/stories'; ?>"><?php echo language_code('DSP_READ_MORE');?></a>
        <?php } else { ?>

            <a class="read-more dsp-read-more" href="<?php echo get_bloginfo('url'); ?>/<?php echo 'members/stories' ?>"><?php echo language_code('DSP_READ_MORE');?></a>

        <?php } ?>

    <?php } ?></div>
</div>