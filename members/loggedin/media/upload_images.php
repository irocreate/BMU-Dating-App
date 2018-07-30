<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
?>
<div class="box-border">
    <div class="box-pedding">
        <div class="heading-submenu"><strong><?php echo language_code( 'DSP_MENU_PHOTOS' ); ?></strong></div>
        <div class="paging">
			<?php
			$album_id_gallery = 0;
			do_action( 'wpdating_gallery', $album_id_gallery );
			?>
        </div>
    </div>
</div>
