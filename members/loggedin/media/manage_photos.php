<?php
/*

  Copyright (C) www.wpdating.com - All Rights Reserved!

  Author - www.wpdating.com

  WordPress Dating Plugin

  contact@wpdating.com

 */

$album_id = get( 'album_id' );
if ( isset( $_GET['picture_Id'] ) ) {
	$picture_id = esc_sql( $_GET['picture_Id'] );
} else {
	$picture_id = '';
}
$album_name = $wpdb->get_row( "SELECT * FROM $dsp_user_albums_table Where album_id='$album_id'" );
?>
<div class="box-border">
    <div class="box-pedding">
        <div class="box-page">
            <div class="manage-album dspdp-spacer-md"><a
                        href="<?php echo $root_link . "media/album/"; ?>"><strong><?php echo language_code( 'DSP_BACK_TO_ALBUMS' ) ?></strong></a>->>
                <a href="#"><strong><?php echo $album_name->album_name; ?></strong></a></div>
        </div>
		<?php
		do_action( 'wpdating_gallery',  $album_id);
		?>
    </div>
</div>