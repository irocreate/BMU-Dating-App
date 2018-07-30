<div class="box-border">
    <div class="box-pedding">
        <?php
        global $wpdb;
        $dsp_stories = $wpdb->prefix . DSP_STORIES_TABLE;
        $story_result = $wpdb->get_results("select * from $dsp_stories order by date_added desc");
        if (count($story_result) > 0) {
            ?>
            <ul class="story-list">
                <?php foreach ($story_result as $story_row) {
                    ?>
                    <li class="">
                        <div class="dsp-border-container">
                            <div class="dspdp-bordered-item dsp-bordered-item">
                                <div class="guest-story-heading dspdp-media-heading age-text dsp-none">
                                    <?php echo stripslashes( $story_row->story_title); ?>
                                </div>
                                <div class="guest-story-box dspdp-media">
                                    <div class="dsp-row">
                                        <div class="dsp-md-2">
                                            <div class="guest-story-image dspdp-pull-left circle-image">
                                                <img src="<?php echo get_bloginfo('url') . '/wp-content/uploads/dsp_media/story_images/thumb_' . $story_row->story_image; ?>" width="100" height="100"  class="dspdp-media-object" alt="<?php echo  $story_row->story_image;?>"/>
                                            </div>
                                        </div>
                                        <div class="dsp-md-10">
                                            <div class="dsp-block" style="display:none"><h4><?php echo stripslashes( $story_row->story_title); ?></h4></div>
                                            <div class="guest-story-content dspdp-media-body"><?php echo stripslashes( $story_row->story_content ); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</div>