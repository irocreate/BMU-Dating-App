<?php
$dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;



$user_id = $_REQUEST['user_id'];

$member_id = $_REQUEST['member_id'];
?>
<li id="div_photos"  data-corners="false" data-shadow="true"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all dsp_inv">
    <div class="swipe_div" id="mainPhotos" >
        <ul id="swipe_ulPhotos" class="dsp_ul_pic gallery user-profile"  style="padding-left:0px; text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">

            <?php
            $exists_photos = $wpdb->get_results("SELECT * FROM $dsp_galleries_photos galleries where galleries.status_id=1 AND galleries.user_id = '$member_id'");

            $i = 0;

            foreach ($exists_photos as $user_photos) {

                $photo_id = $user_photos->gal_photo_id;

                $status_id = $user_photos->status_id;

                $private = $user_photos->private_album;

                $album_id = $user_photos->album_id;

                $file_name = $user_photos->image_name;

                if ($private == 'Y') {

                    if ($user_id == $member_id) {

                        $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_" . $album_id . "/" . $file_name;
                    } else {

                        $image_path = get_bloginfo('url') . "/wp-content/plugins/dsp_dating/images/private-photo-pic.jpg";
                    }
                } else {

                    $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_" . $album_id . "/" . $file_name;
                }
                ?>

                <li class="ivew-list">

                    <a id="galleryImg" class="<?php if ($i == 0) echo "one"; ?>" show="<?php echo $image_path ?>">
                        <img src="<?php echo $image_path ?>" style=" width:85px;height:85px;" class="img3 iviewed-img"/>
                    </a>

                </li>

                <?php
                $i++;
            }
            ?>




        </ul>


    </div>
</li>


