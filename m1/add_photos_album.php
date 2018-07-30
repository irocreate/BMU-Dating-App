<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_back.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_CREATE_ALBUM'); ?></h1>
    <?php include_once("page_home.php");?> 
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
        <form name="createalbum" id="frmCreate">
            <fieldset>
                <label data-role="fieldcontain" class="form-group">  
                    <div class="clearfix">                                    
                        <div class="mam_reg_lf form-label"><?php echo language_code('DSP_ADD_ALBUM') ?></div>

                        <input type="text" name="add_album" value="<?php if (isset($dsp_updates)) echo $dsp_updates->album_name; ?>" placeholder="<?php echo language_code('DSP_ALBUM_NAME') ?>">
                    </div>
                </label>

                <label data-role="fieldcontain" class="form-group">  
                    <div class="clearfix">                                    
                        <div class="mam_reg_lf form-label">
                            <input name="private" type="checkbox" <?php
                            if (isset($dsp_updates)) {
                                if ($dsp_updates->private_album == 'Y') {
                                    echo 'checked="checked"';
                                }
                            }
                            ?> class="checkbox-singleline"/> 
                            <?php echo language_code('DSP_MAKE_PRIVATE') ?></div>

                        </div>
                    </label>
                    <input type="hidden" name="album_Id"  value="<?php echo $get_album_id ?>">
                    <input type="hidden" name="albummode"  value="<?php echo $mode ?>">
                    <input type="hidden" name="user_id"  value="<?php echo $user_id ?>">
                    <input type="hidden" name="pagetitle"  value="<?php echo $photos_pageurl ?>">
                    <div class="btn-blue-wrap">
                        <input type="button" name="submit" class="mam_btn btn-blue" value="<?php echo language_code('DSP_ADD_ALBUM_BUTTON') ?>" onClick="callPhoto('album', 'post');">  </span>
                    </div>
                </fieldset>
            </form>


            <?php
            $exists_album = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = '$user_id' order by date_created DESC LIMIT 8");
            $i = 0;
            if($exists_album){ ?>
            <div class="heading-text"><strong>My Albums<?php echo language_code('DSP_MY_ALBUM'); ?></strong></div>
            <div class="col-row">
                        <?php 
                        foreach ($exists_album as $user_album) {
            
                            $album_id = $user_album->album_id;
                            $album_name = $user_album->album_name;
                            ?>
            
                            <div class="col-2  spacer-bottom-sm">
                                <div class="album-list">
                                <a onclick="callPhoto('manage_photos',<?php echo $album_id; ?>)" >
                                                        <img src="images/icons/gallery@3x.png" style="width:80px; height:80px;" class="img3" />
                                                    </a>
                                                    
                                                    <div class="user-name">
                                                        <a onclick="callPhoto('manage_photos',<?php echo $album_id; ?>)" ><?php echo $album_name ?></a>
                                                    </div>
                                                    <span class="button-edit spacer-bottom-xs" onClick="callPhoto('edit', '<?php echo $album_id ?>');" ><?php echo language_code('DSP_EDIT'); ?></span>     
                                                    <span class="button-delete spacer-bottom-xs spacer-top-xs" onClick="callPhoto('delete', '<?php echo $album_id ?>');"><?php echo language_code('DSP_DELETE'); ?></span>    
                                
                                </div>
            
                            </div>
                            <?php $i++; ?>
                            
                            <?php } ?>
            </div>
                <?php 
            }?>

        </div>
        <?php include_once('dspNotificationPopup.php'); // for notification pop up     ?>
    </div>