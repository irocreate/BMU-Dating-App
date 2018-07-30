<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_PHOTOS'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>
</div>
<?php
$dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;

$album_id = isset($_REQUEST['album_id']) ? $_REQUEST['album_id'] : '';



$picture_id = isset($_REQUEST['picture_Id']) ? $_REQUEST['picture_Id'] : '';



$Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';



$album_name = $wpdb->get_row("SELECT * FROM $dsp_user_albums_table Where album_id='$album_id'");



if ($Action == "Del" && !empty($picture_id)) {   // DELETE PICTURE
    $photo_name = $wpdb->get_row("SELECT * FROM $dsp_galleries_photos Where gal_photo_id='$picture_id'");
    $picture = $photo_name->image_name;

    if ($picture != "") {
        //$directory_path = ABSPATH."/wp-content/plugins/dsp_dating/user_photos/user_".$user_id."/album_".$album_id."/".$new_name;

        $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/album_' . $album_id;
        $delete_picture = $directory_path . "/" . $picture;
        unlink($delete_picture);

        $wpdb->query("DELETE FROM $dsp_galleries_photos WHERE gal_photo_id = '$picture_id'");
    }
}
?>



<div class="ui-content" data-role="content" id="galleryPage">
    <div class="content-primary">	 
        <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul gallery">


            <div style="padding-bottom: 20px;">
                <a onclick="callPhoto('album', 0)">
                    <strong><?php echo language_code('DSP_BACK_TO_ALBUMS') ?></strong>
                </a>->>

                <strong><?php echo $album_name->album_name; ?></strong>
            </div>


            <?php
            $exists_photos = $wpdb->get_results("SELECT * FROM $dsp_galleries_photos WHERE album_id = '$album_id' AND status_id=1");

            $i = 0;

            foreach ($exists_photos as $user_photos) {

                $photo_id = $user_photos->gal_photo_id;

                $status_id = $user_photos->status_id;

                $file_name = $user_photos->image_name;

                $image_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/album_" . $album_id . "/" . $file_name;
                ?>


                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all" style="text-align: center;">
                    <a id="galleryImg" class="<?php if ($i == 0) echo "one"; ?>" show="<?php echo $image_path ?>">
                        <img src="<?php echo $image_path ?>" style="width:100px;height: 100px" class="img3"/>
                    </a>

                    <div  style="width: 100%;padding-top: 15px;">
                        <form id="frmManagePhoto_<?php echo $i; ?>">
                            <input type="hidden" value="<?php echo $photo_id ?>" name="picture_Id"/>
                            <input type="hidden" value="<?php echo $album_id ?>" name="album_id"/>
                            <input type="hidden" value="<?php echo $photos_pageurl ?>" name="pagetitle"/>
                            <input type="hidden" value="<?php echo $user_id ?>" name="user_id"/>
                            <input type="hidden" value="Del" name="Action"/>
                            <span onclick="callPhoto('deleteAlbumPic', 'frmManagePhoto_<?php echo $i; ?>');" style="cursor:pointer;text-decoration:underline;"><?php echo language_code('DSP_DELETE'); ?></span>
                        </form>
                    </div>
                </li>


                <?php
                $i++;
            }
            ?>

        </ul>






    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
</div>

<div id="tadcontent" data-role="content" class="ui-content" role="main">
    <div id="imageflipimg" style="display:none; text-align: center; padding-left:2px;">
        <img id="displayImg" src=""/>
    </div>

    <div data-role="navbar" id="tadnavi" class="ui-navbar ui-mini" role="navigation" >
        <ul class="ui-grid-b">
            <li class="ui-block-a">
                <a id="tadclose" data-icon="delete" data-role="button" data-iconpos="top" href="" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-theme="c" title="" class="ui-btn ui-btn-inline ui-btn-icon-top ui-btn-up-c" data-inline="true"><span class="ui-btn-inner"><span class="ui-btn-text"></span><span class="ui-icon ui-icon-delete ui-icon-shadow">&nbsp;</span></span></a>
            </li>
            <li class="ui-block-b">
                <a onclick="previousPic()" id="tadbk" data-icon="arrow-l" data-role="button" data-iconpos="top" href="" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-theme="c" title="" class="ui-btn ui-btn-inline ui-btn-icon-top ui-btn-up-c" data-inline="true"><span class="ui-btn-inner"><span class="ui-btn-text"></span><span class="ui-icon ui-icon-arrow-l ui-icon-shadow">&nbsp;</span></span></a>
            </li>
            <li class="ui-block-c">
                <a onclick="nextPic()" id="tadnxt" data-icon="arrow-r" data-role="button" data-iconpos="top" href="" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-theme="c" title="" class="ui-btn ui-btn-inline ui-btn-icon-top ui-btn-active ui-btn-up-c" data-inline="true"><span class="ui-btn-inner"><span class="ui-btn-text"></span><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></span></a>
            </li>
        </ul>
    </div>



</div>