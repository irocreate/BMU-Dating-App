<?php
get_header();
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
?>
<div id="content" class="widecolumn">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="navigation">
        <div class="alignleft">
            <?php previous_post_link("&laquo; %link")?></div>
        <div class="alignright"><?php next_post_link("%link &raquo;")?></div>
    </div>
    <div class="entry">
        <?php the_content("<p class = \"serif\">Read the rest of this entry &raquo;</p>"); ?>
        <p>This post was written by <?php the_author(); ?></p>
        <?php the_date('Y-m-d','<h2>Published On', '</h2>'); ?>
    </div>
    <?php endwhile; endif;?>
</div>
        