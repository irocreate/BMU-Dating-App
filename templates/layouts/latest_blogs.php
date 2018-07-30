<div class="latest-blog home-box  dspdp-col-sm-4 dspdp-spacer-lg   dsp-sm-4 dsp-spacer-lg">
    <div class="dsp-home-widget"><div class="dspdp-h4 dspdp-text-uppercase dspdp-spacer-md dsp-h4 dsp-text-uppercase dsp-spacer-md">
        <span class="heading-text">&nbsp;</span><?php echo language_code('DSP_LATEST_BLOG'); ?></div>
    <?php
        $args = array('numberposts' => '5');
        $recent_posts = wp_get_recent_posts($args);
        foreach ($recent_posts as $recent) {
            echo '<div class="dspdp-bordered-item  dspdp-small dsp-bordered-item  dsp-small"><div class="title-txt color-txt">' . mysql2date('j M Y', $recent["post_date"]) . '</div><a href="' . get_permalink($recent["ID"]) . '" title="Blog ' . esc_attr($recent["post_title"]) . '" >' . $recent["post_title"] . '</a> </div> ';
        }
    ?></div>
</div>