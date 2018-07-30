<div class="box-border">
    <div class="box-pedding">
        <div class="heading-submenu"><strong><?php echo language_code('DSP_GUEST_HEADER_STORIES');?></strong></div></br></br>
        <?php
        global $wpdb;
        $dsp_stories = $wpdb->prefix . DSP_STORIES_TABLE;
        $story_result = $wpdb->get_results("select * from $dsp_stories order by date_added desc");
        if (count($story_result) > 0) {
            ?>
            <ul class="story-list">
                <?php foreach ($story_result as $story_row) {
                    ?>
                    <li>
                        <div class="guest-story-heading"><?php echo $story_row->story_title; ?></div>
                        <div class="guest-story-box">
                            <div class="guest-story-image"><img src="<?php echo get_bloginfo('url') . '/wp-content/uploads/dsp_media/story_images/thumb_' . $story_row->story_image; ?>" width="100" height="100" alt="<?php echo $story_row->story_title;; ?>"/></div>
                            <div class="guest-story-content"><?php echo $story_row->story_content; ?></div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>

            <div><?php echo language_code('DSP_STORIES_NOT_FOUND');?></div>
        <?php } ?>
    </div>
</div>