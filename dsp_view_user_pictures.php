<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
$album_id = get('album_id');
$album_name = $wpdb->get_row("SELECT * FROM $dsp_user_albums_table Where album_id='$album_id'");
?>
<div class="box-border">
    <div class="box-pedding">
        <div class="box-page">
            <div>
				<div class="heading-line dspdp-userpic_header dsp-xs-12 dsp-spacer"><span onclick="location.href = '<?php echo $root_link . get_username($member_id) . "/album/"; ?>';" style="cursor:pointer;text-decoration:underline;"><strong><?php echo language_code('DSP_BACK_TO_ALBUMS') ?></strong></span>->>
                        <a href="#"><strong><?php echo $album_name->album_name; ?></strong></a>
                    </div>
                <ul style="" class="albums dspdp-row  dsp-row">
                    
                    <?php
                    $exists_photos = $wpdb->get_results("SELECT * FROM $dsp_galleries_photos galleries,$dsp_user_albums_table album WHERE galleries.album_id=album.album_id AND galleries.status_id=1 AND galleries.album_id = '$album_id'");
                    $i = 0;
                    foreach ($exists_photos as $user_photos) {
                        $photo_id = $user_photos->gal_photo_id;
                        $status_id = $user_photos->status_id;
                        $private = $user_photos->private_album;
                        $file_name = $user_photos->image_name;
                        if ($private == 'Y') {

                            $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_id'");
                            foreach ($private_mem as $private) {
                                $favt_mem[] = $private->favourite_user_id;
                            }

                            if ($current_user->ID == $member_id) {
                                $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_" . $album_id . "/" . $file_name;
                                $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_" . $album_id . "/" . $file_name;
                            } else {
                                if (!in_array($current_user->ID , $favt_mem)) {
                                    $image_path = WPDATE_URL . "/images/private-photo-pic.jpg";
                                 } else {
                                    $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_" . $album_id . "/" . $file_name;
                                    $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_" . $album_id . "/" . $file_name;
                                }
                            }
                        } else {
                            $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_" . $album_id . "/" . $file_name;
                        }
                        if (($i % 4) == 0) {
                            ?>
                            <?php
                        }
                        ?>
                        <li class="img-box dspdp-col-sm-4 dspdp-col-xs-6  dsp-sm-3 dsp-xs-6">
                            <div class="image-container dsp-img-fit"><a class="group1  dspdp-media-images-cont dsp-image-fill" href="<?php echo $image_path ?>"><img src="<?php echo $image_path ?>" style=" width:85px;height:85px;" class="img3"   alt="<?php echo $file_name;?>"/></a></div>
                        </li>
                        <?php
                        $i++;
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>