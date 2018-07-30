<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_CREATE_ALBUM'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>
</div>
<?php
$root_link = "";

$album_mode = isset($_REQUEST['albummode']) ? $_REQUEST['albummode'] : '';
$add_album = isset($_REQUEST['add_album']) ? $_REQUEST['add_album'] : '';

$created_date = date("Y-m-d H:m:s");

$get_album_id = isset($_REQUEST['album_Id']) ? $_REQUEST['album_Id'] : '';

$dirs = wp_upload_dir();
$upload_dir = $dirs['basedir'];

if (isset($_REQUEST['private']) && $_REQUEST['private'] != '') {
    $private = isset($_REQUEST['private']) ? 'Y' : 'N';
} else {
    $private = 'N';
}



switch ($album_mode) {

    case 'add':    // ADD ALBUM 

        if ($add_album != "") {
            $already_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_albums_table WHERE user_id = $user_id AND album_name = '$add_album'");

            if ($already_exists <= 0) {
            
                $wpdb->query("INSERT INTO $dsp_user_albums_table SET user_id = $user_id,album_name = '$add_album',date_created= '$created_date',status_id =0, private_album='$private'");
                $insertid = $wpdb->insert_id;

                if (!file_exists($upload_dir . '/dsp_media/user_photos/user_' . $user_id)) {
                    // it will default to 0755 regardless 

                    mkdir($upload_dir . '/dsp_media/user_photos/user_' . $user_id, 0755);
                    // Finally, chmod it to 777
                    chmod($upload_dir . '/dsp_media/user_photos/user_' . $user_id, 0777);
                }

                if (!file_exists($upload_dir . '/dsp_media/user_photos/user_' . $user_id . '/album_' . $insertid)) {
                    // it will default to 0755 regardless 
                    mkdir($upload_dir . '/dsp_media/user_photos/user_' . $user_id . '/album_' . $insertid, 0755);
                    // Finally, chmod it to 777
                    chmod($upload_dir . '/dsp_media/user_photos/user_' . $user_id . '/album_' . $insertid, 0777);
                }
            }
        }

        // $sendback = remove_query_arg( array('Action', 'album_Id'), $goback);

        break;
    case 'update':    // UPDATE ALBUM

        if ($add_album != "") {

            $already_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_albums_table WHERE user_id = $user_id AND album_id  = '$get_album_id'");
            if ($already_exists > 0) {

                $wpdb->query("UPDATE $dsp_user_albums_table SET album_name = '$add_album',date_created = '$created_date',status_id =0,private_album='$private' WHERE album_id  = '$get_album_id'");
            }
        }
        break;
}



if (isset($_GET['Action']) && $_GET['Action'] == "Del") {   // DELETE ALBUM
    $wpdb->query("DELETE FROM $dsp_user_albums_table WHERE album_id = '$get_album_id'");
    //$sendback = remove_query_arg( array('Action', 'album_Id'), $goback);
}

if (isset($_GET['Action']) && $_GET['Action'] == 'update') {
    $mode = 'update';

    $album_Id = $_GET['album_Id'];
    $dsp_updates = $wpdb->get_row("SELECT * FROM $dsp_user_albums_table WHERE album_id = $album_Id");
} else {
    $mode = 'add';
}
?>


<div class="ui-content" data-role="content">
    <div class="content-primary">	 
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">
            <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                <form name="createalbum" id="frmCreate">
                    <ul style="list-style: none; margin-left: -15%;">

                        <li>
                            <span class="txt"><?php echo language_code('DSP_ADD_ALBUM') ?>&nbsp;</span>

                            <span class="upload-box"><input type="text" name="add_album" value="<?php if (isset($dsp_updates)) echo $dsp_updates->album_name; ?>">
                                <div class="txt"><?php echo language_code('DSP_MAKE_PRIVATE') ?>
                                <input name="private" type="checkbox" <?php
                                if (isset($dsp_updates)) {
                                    if ($dsp_updates->private_album == 'Y') {
                                        echo 'checked="checked"';
                                    }
                                }
                                ?>/> 
                                </div>
                                <input type="hidden" name="album_Id"  value="<?php echo $get_album_id ?>">
                                <input type="hidden" name="albummode"  value="<?php echo $mode ?>">
                                <input type="hidden" name="user_id"  value="<?php echo $user_id ?>">
                                <input type="hidden" name="pagetitle"  value="<?php echo $photos_pageurl ?>">
                                <input type="button" name="submit" class="dsp_submit_button" value="<?php echo language_code('DSP_ADD_ALBUM_BUTTON') ?>" onClick="callPhoto('album', 'post');">  </span>
                        </li>
                    </ul>
                </form>
            </li>


            <?php
            $exists_album = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = '$user_id' order by date_created DESC LIMIT 8");
            $i = 0;

            foreach ($exists_album as $user_album) {

                $album_id = $user_album->album_id;
                $album_name = $user_album->album_name;
                ?>

                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">

                    <div style="width: 30%;float: left;">
                        <a onclick="callPhoto('manage_photos',<?php echo $album_id; ?>)" >
                            <img src="WPDATE_URL . '/images/album.jpg' " style="width:80px; height:80px;" class="img3" />
                        </a>
                    </div>
                    <div style="width: 50%;float: left;text-align: left; margin-left:5%; padding-top: 20px;">
                        <a onclick="callPhoto('manage_photos',<?php echo $album_id; ?>)" ><?php echo $album_name ?></a>
                        &nbsp;&nbsp; 
                        <br />
                        <span onClick="callPhoto('edit', '<?php echo $album_id ?>');"   style="cursor:pointer;text-decoration:underline;"><?php echo language_code('DSP_EDIT'); ?></span> |	
                        <span onClick="callPhoto('delete', '<?php echo $album_id ?>');" style="cursor:pointer;text-decoration:underline;"><?php echo language_code('DSP_DELETE'); ?></span>	



                    </div>
                    <?php $i++; ?>
                </li> 
            <?php } ?>

        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up     ?>
</div>