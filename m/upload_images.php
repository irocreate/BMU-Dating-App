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
$dsp_tmp_galleries_photos_table = $wpdb->prefix . DSP_TMP_GALLERIES_PHOTOS_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;

$root_link = "";

if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');




// ----------------------------------------------- Start Paging code------------------------------------------------------ //  



if (isset($_GET['page1']))
    $page = $_GET['page1'];
else
    $page = 1;



// How many adjacent pages should be shown on each side?

$adjacents = 2;


//$limit = 1; 	

$limit = 5;

if ($page)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;



// ----------------------------------------------- Start Paging code------------------------------------------------------ //
// --------------------- Start Select members created Albums in Array ----------------------------------- //







$dsp_album_id = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = $user_id");







foreach ($dsp_album_id as $id) {

    $album_ids[] = $id->album_id;
}


if (isset($album_ids) && $album_ids != "") {

    $ids1 = implode(",", $album_ids);
}

if (isset($ids1)) {
    $count_uploaded_images = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_galleries_photos where album_id IN ($ids1)  AND status_id=1");
} else {
    $count_uploaded_images = 0;
}

// ---------------------  End Select members created Albums in Array ----------------------------------- //


$photo_mode = isset($_REQUEST['txtmode']) ? $_REQUEST['txtmode'] : '';

$created_date = date("Y-m-d H:m:s");

$album_id = isset($_REQUEST['album_id']) ? $_REQUEST['album_id'] : '';

$Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';

$picture_id = isset($_REQUEST['picture_Id']) ? $_REQUEST['picture_Id'] : '';




// ---------------------------------------------- DELETE PICTURE ------------------------------------------ //


if ($Action == "Del" && !empty($picture_id)) {

    $photo_name = $wpdb->get_row("SELECT * FROM $dsp_galleries_photos Where gal_photo_id='$picture_id'");
    $picture = $photo_name->image_name;

    $pic_album_id = $photo_name->album_id;

    if ($picture != "") {
        $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/album_' . $pic_album_id;
        $delete_picture = $directory_path . "/" . $picture;
        unlink($delete_picture);

        $wpdb->query("DELETE FROM $dsp_galleries_photos WHERE gal_photo_id = '$picture_id'");
    } // END if($picture!="")
} // END if($Action=="Del" && !empty($picture_id)) 
// ---------------------------------------------- DELETE PICTURE ------------------------------------------ //
?>







<?php if (isset($printErrormsg) && $printErrormsg != "") { ?>







    <div class="thanks">







        <p align="center" class="error"><?php echo $printErrormsg ?></p>







    </div>







    <?php
}







if ($check_approve_photos_status->setting_status == 'N') {

    if (isset($approval_message) && ($approval_message != "")) {
        ?>
        <div class="thanks">
            <p align="center" class="error"><?php echo $approval_message ?></p>
        </div>
        <?php
    }
}
?>


<div class="ui-content" data-role="content" id="galleryPage">
    <div class="content-primary">	 





        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">
            <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">

                <div class="paging"  style="padding-right:20px;">
                    <?php
                    if (isset($_GET['Action']) && $_GET['Action'] == 'update_img') {

                        $mode = 'update';

                        $image_Id = $_GET['image_Id'];

                        $dsp_updates = $wpdb->get_row("SELECT * FROM $dsp_galleries_photos WHERE gal_photo_id = $image_Id");
                    } else {
                        $mode = 'add';
                    } // end  if($_GET['Action']=='update_img')
                    ?>




                    <div class="upload-box">
                        <p>
                            <div><?php echo language_code('DSP_SELECT_ALBUM'); ?>:&nbsp;</div>
                            <select name="album_id" onchange="saveAlbumId(this.value)">
                                <option value="0"><?php echo language_code('DSP_SELECT_ALBUM'); ?></option>
                                <?php
                                $select_albums = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = '$user_id'");

                                foreach ($select_albums as $albums) {
                                    ?>

                                    <option value="<?php echo $albums->album_id ?>"><?php echo $albums->album_name ?></option>	
                                <?php } // foreach ($select_albums as $albums)    ?>	
                            </select>
                        </p>

                        <p>
                            <div><?php echo language_code('DSP_UPLOAD_PHOTOS') ?>&nbsp;</div>

                            <input type="hidden" name="txtmode" id="txtmode" value="<?php echo $mode ?>">
                            <input type="button"  name="submit" value="<?php echo language_code('DSP_ADD_PHOTO_BUTTON') ?>" onClick="getMediaPhoto();">

                        </p>

                    </div>



                </div>
            </li>
        </ul>
        <div class="box-page">
            <div align="left" >
                <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul gallery" >
                    <?php
// ----------------------------------------------- Start Paging code------------------------------------------------------ //



                    $page_name = $root_link . "?pid=4&pagetitle=photo";




                    if (isset($ids1)) {
                        $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_galleries_photos where album_id IN ($ids1)  AND status_id=1");
                    } else {
                        $total_results1 = 0;
                    }




// Calculate total number of pages. Round up using ceil()
//$total_pages1 = ceil($total_results1 / $max_results1); 

                    if ($page == 0)
                        $page = 1;     //if no page var is given, default to 1.

                    $prev = $page - 1;

                    $next = $page + 1;

                    $lastpage = ceil($total_results1 / $limit);
                    ;  //lastpage is = total pages / items per page, rounded up.

                    $lpm1 = $lastpage - 1;



                    /*

                      Now we apply our rules and draw the pagination object.

                      We're actually saving the code to a variable in case we want to draw it more than once.

                     */

                    $pagination = "";

                    if ($lastpage > 1) {

                        $pagination .= "<div class='button-area'>";

                        //previous button

                        if ($page > 1) {
                            $pagination.="
			 
				<div onclick='callPhoto(\"page\",1)' class='btn-pre1'>
					<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/bb.png" . "'/>
				</div>";
                        } else {
                            $pagination.= "
				<div class='btn-pre1'>
					<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/b.png" . "'/>
				</div>";
                        }

                        if ($page > 1) {
                            $pagination.="<div  onclick='callPhoto(\"page\",$prev)' class='btn-pre2'>
							<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/aa.png" . "'/>
						</div>";
                        } else {
                            $pagination.=" <div  class='btn-pre2'>
							<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/a.png" . "'/>
						</div>";
                        }


                        $pagination.= "<div class='main3' > 
							<div class='para'> Page</div>
							<div class='main6'>
								<div class='middle'>$page</div>
							</div>
							<div class='para1'>of $lastpage</div>
						</div>";

                        if ($page < $lastpage) {
                            $pagination.= "
			<div onclick='callPhoto(\"page\",$next)' class='main4' >
				<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/c.png" . "'/>
			</div>";

                            $pagination.= "	<div onclick='callPhoto(\"page\",$lastpage)' class='main5'>
								<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/d.png" . "'/>
							</div>";
                        } else {
                            $pagination.= "
			<div class='main4'>
			<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/cc.png" . "'/>
			</div>";

                            $pagination.= "	<div class='main5'>
								<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/dd.png" . "'/>
							</div>";
                        }

                        $pagination.= "</div>\n";
                    }

// ------------------------------------------------End Paging code------------------------------------------------------ //




                    if (!isset($ids1)) {
                        $ids1 = 0;
                    }

                    $exists_photos = $wpdb->get_results("SELECT * FROM $dsp_galleries_photos galleries, $dsp_user_albums_table albums WHERE galleries.album_id=albums.album_id AND galleries.status_id=1 AND galleries.album_id IN ($ids1) ORDER BY date_added  LIMIT $start, $limit");

                    $i = 0;


                    foreach ($exists_photos as $user_photos) {

                        $photo_id = $user_photos->gal_photo_id;
                        $album_id1 = $user_photos->album_id;

                        $status_id = $user_photos->status_id;

                        $file_name = $user_photos->image_name;

                        $private = $user_photos->private_album;

                        $user_id1 = $user_id;

                        if ($private == 'Y') {

                            if ($user_id == $user_id1) {
                                $image_path = "/uploads/dsp_media/user_photos/user_" . $user_id1 . "/album_" . $album_id1 . "/" . $file_name;
                            } else {
                                $image_path = "/plugins/dsp_dating/images/private-photo-pic.jpg";
                            }
                        } else {
                            $image_path = "/uploads/dsp_media/user_photos/user_" . $user_id1 . "/album_" . $album_id1 . "/" . $file_name;
                        }
                        ?>

                        <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all" style="text-align:center;">

                            <a id="galleryImg" class="<?php if ($i == 0) echo "one"; ?>" show="<?php echo $imagepath . $image_path ?>">

                                <?php if ($private == 'Y') {
                                    ?>
                                    <?php if ($user_id != $user_id1) { ?>

                                        <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:100px;height:100px" class="img2" align="left" />
                                        <?php
                                    } else {
                                        ?>
                                        <img src="<?php echo $imagepath . $image_path ?>" style="width:100px;height:100px" class="img3" />
                                    <?php } ?>

                                    <?php
                                } else {
                                    ?>
                                    <img src="<?php echo $imagepath . $image_path ?>" style=" width:100px;height:100px" class="img3" />
                                <?php } ?>

                            </a>


                            <div  style="width: 100%;text-align:center; padding-top: 15px;">
                                <span onclick="callPhoto('deletePic',<?php echo $photo_id ?>);" style="cursor:pointer;text-decoration:underline;"><?php echo language_code('DSP_DELETE'); ?></span>
                            </div>

                        </li>

                        <?php
                        $i++;
                    }
                    ?>



                    <div style="float:left; width:100%;">
                        <?php
                        // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                        echo $pagination
                        ?>
                    </div>


            </div>



        </div>
        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up     ?>
</div>

<div id="tadcontent" data-role="content" class="ui-content" role="main">
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
    <div id="imageflipimg" style="display:none; text-align: center;">
        <img id="displayImg" src=""/>
    </div>





</div>