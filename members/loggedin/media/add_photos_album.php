<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

// ----------------------------------------------- Folder Delete Function Start ---------------------------------------------- //

function deleteDir($dirPath) 
{
    if (is_dir($dirPath)) 
    {
      if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') 
      {
          $dirPath .= '/';
      }
      $files = glob($dirPath . '*', GLOB_MARK);
      foreach ($files as $file) 
      {
        if (is_dir($file)) {
            deleteDir($file);
        } else {
            unlink($file);
        }
      }
      rmdir($dirPath);
    }   
}

// ----------------------------------------------- Folder Delete Function End ---------------------------------------------- //

$album_mode = isset($_REQUEST['albummode']) ? $_REQUEST['albummode'] : '';
$add_album = isset($_REQUEST['add_album']) ? esc_sql(sanitizeData(trim($_REQUEST['add_album']), 'xss_clean')) : '';

$created_date = date('Y-m-d H:i:s');
$get_album_id = get('album_Id');
$goback = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$button_text=language_code('DSP_ADD_ALBUM_BUTTON');
if (isset($_REQUEST['private']) && $_REQUEST['private'] != '') {
    $private = isset($_REQUEST['private']) ? $_REQUEST['private'] : '';
} else {
    $private = 'N';
}
switch ($album_mode) {
    case 'add':    // ADD ALBUM 
        if ($add_album != "") {
            $already_exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $dsp_user_albums_table WHERE user_id = %s AND album_name = %s", $current_user->ID, $add_album ) );

            if ($already_exists <= 0) {
                $wpdb->query( $wpdb->prepare( "INSERT INTO $dsp_user_albums_table SET user_id = %s,album_name = %s,date_created= %s,status_id =0, private_album=%s", $current_user->ID, $add_album, $created_date, $private ) );

                $insertid = $wpdb->insert_id;
                if (!file_exists('wp-content/uploads/dsp_media/user_photos/user_' . $user_id)) {
                    // it will default to 0755 regardless 
                    mkdir('wp-content/uploads/dsp_media/user_photos/user_' . $user_id, 0755);
                    // Finally, chmod it to 777
                    chmod('wp-content/uploads/dsp_media/user_photos/user_' . $user_id, 0777);
                }
                if (!file_exists('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/album_' . $insertid)) {
                    // it will default to 0755 regardless 
                    mkdir('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/album_' . $insertid, 0755);
                    // Finally, chmod it to 777
                    chmod('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/album_' . $insertid, 0777);
                }
            }
        }
        $sendback = remove_query_arg(array('Action', 'album_Id'), $goback);
        //header("Location:".$sendback);
        break;
    case 'update':    // UPDATE ALBUM

        if ($add_album != "") {
            $get_album_id = $_GET['album_Id'];
            $already_exists = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $dsp_user_albums_table WHERE user_id = %s AND album_name = %s", $current_user->ID, $add_album ) );
            if ($already_exists != null) {
                $wpdb->query( $wpdb->prepare( "UPDATE $dsp_user_albums_table SET album_name = %s,date_created = %s,status_id =0, private_album = %s WHERE album_id  = %s", $add_album, $created_date,  $private ,$get_album_id) );
            }
        }
        $sendback = remove_query_arg(array('Action', 'album_Id'), $goback);
        echo '<script>window.location="' . $sendback . '";</script>';
        // header("Location:".$sendback);
        break;
}
if (isset($_GET['Action']) && $_GET['Action'] == "Del") {   // DELETE ALBUM
    $get_album_id = $_GET['album_Id'];
    $current_user=get_current_user_id();
    $check_album_owner=$wpdb->get_results( $wpdb->prepare( "SELECT * FROM $dsp_user_albums_table WHERE album_id = %s", $get_album_id ) );
    if (!empty($check_album_owner))
    {
        if($check_album_owner[0]->user_id == $current_user)
        {                       
            $photos_in_album=$wpdb->get_results("SELECT * FROM $dsp_galleries_photos WHERE album_id=$get_album_id AND user_id= $current_user ");
            if(!empty($photos_in_album))
            {    
                foreach($photos_in_album as $photo)
                {
                    $picture = $photo->image_name;
                    $pic_album_id = $photo->album_id;
                    //$directory_path = ABSPATH."/wp-content/plugins/dsp_dating/user_photos/user_".$user_id."/album_".$album_id."/".$new_name;
                    $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $current_user . '/album_' . $pic_album_id;
                    $delete_picture = $directory_path . "/" . $picture;
                    unlink($delete_picture);
                    $wpdb->query("DELETE FROM $dsp_galleries_photos WHERE gal_photo_id = $photo->gal_photo_id");
                }
            }               
            $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $current_user . '/album_' . $get_album_id .'/';
            deleteDir($directory_path);
            $wpdb->query( $wpdb->prepare("DELETE FROM $dsp_user_albums_table WHERE album_id = %s", $get_album_id) );
            $sendback = remove_query_arg(array('Action', 'album_Id'), $goback);
            //wp_redirect($sendback);header("Location:".$sendback);exit();           
            echo '<script>window.location="' . $sendback . '";</script>';
        }
        else
        {
            echo 'Unauthorized Access';
            echo '<script>window.location="' . get_bloginfo('url').'/members' . '";</script>';
        }
    }
    else
    {
        echo '<script>window.location="' . get_bloginfo('url').'/members' . '";</script>';
    }
}
?>
 <?php
        if (isset($_GET['Action']) && $_GET['Action'] == 'update') {
            $mode = 'update';
            $button_text=language_code('DSP_EDIT');
            $album_Id = $_GET['album_Id'];
            $dsp_updates = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $dsp_user_albums_table WHERE album_id = %s", $album_Id ) );
        } else {
            $mode = 'add';
        }

?>
<form name="createalbum"  class="dspdp-box-border" action="" method="post">
    <ul class="album-upload">
        <li>
<!--                    <span class="txt"><?php echo language_code('DSP_ADD_ALBUM') ?>&nbsp;</span>        -->
            <span class="upload-box dspdp-form">

                <span class="dspdp-form-group dspdp-block"><input type="text" placeholder="<?php echo language_code('DSP_ALBUM_NAME') ?>" class="dspdp-form-control" name="add_album" value="<?php if (isset($dsp_updates)) echo $dsp_updates->album_name; ?>"></span>

                <span class="dspdp-form-group dspdp-block"><label class="dspdp-horiz-spacer"><?php echo language_code('DSP_MAKE_PRIVATE') ?> <input name="private" type="checkbox" value="Y" <?php $make_private = isset($dsp_updates->private_album) ? $dsp_updates->private_album: ''; if ($make_private == 'Y') { ?> checked="checked"  <?php } ?>/> </label></span>

                <input type="hidden" name="albummode"  value="<?php echo $mode ?>">
                <input type="submit" name="submit" class="dsp_submit_button dspdp-btn dspdp-btn-default" value="<?php echo $button_text ?>" onClick="add_photos_album();">  
            </span>
        </li>
    </ul>
</form>
<div class="box-border">
    <div class="box-pedding">
        <div class="heading-submenu"><strong><?php echo language_code('DSP_LIST_OF_ALBUM');?></strong></div>
        <div class="box-upload-image clearfix  dspdp-spacer">
            <div class="dsp-row">
                <ul id="upload-img" class="albums dspdp-row">
                    <?php
                    // $exists_album = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $dsp_user_albums_table WHERE user_id = %s order by date_created DESC LIMIT 8", $current_user->ID ) );
                     $dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
                    $exists_album = $wpdb->get_results( $wpdb->prepare( "SELECT a.*, i.image_name FROM $dsp_user_albums_table a LEFT JOIN $dsp_galleries_photos i USING (album_id) WHERE a.user_id = %s GROUP BY a.album_id order by date_created DESC LIMIT 8", $current_user->ID ) );


                    // $album_image = $wpdb->get_results("SELECT a.*, i.image_name FROM $dsp_galleries_photos a JOIN wp_dsp_galleries_photos i JOIN USING (album_id) WHERE a.album_id=$get_album_id AND a.user_id = $current_user");
                    $i = 0;
                    $uploads = wp_upload_dir();
                    $upload_path = $uploads['baseurl'];

                    foreach ($exists_album as $user_album) {
                        $album_id = $user_album->album_id;
                        $album_name = $user_album->album_name;
                        $default_image = ($user_album->image_name == '') ? WPDATE_URL . '/images/album.png' : $upload_path .'/dsp_media/user_photos/user_' . $current_user->ID . '/album_' . $album_id . '/' . $user_album->image_name;

                        if (($i % 4) == 0) {
                            ?>
                        <?php } ?>
                        <li class="col-md-6 col-sm-6 col-xs-12 col-lg-4 ">
                            <div class="image-container"><div class="name dspdp-medium" style="text-align:center;"><a href="<?php echo $root_link . "media/manage_photos/album_id/" . $album_id; ?>"><?php echo $album_name ?></div>
                            
    						
    						<div class="album-icon"><img src="<?php echo $default_image; ?>" style="width:80px; height:80px;" class="img3" alt="Album"/></div></a>
    						
                            <a class="dspdp-btn dspdp-btn-sm dspdp-btn-success dsp-btn-default dsp-btn-sm" href="<?php echo $root_link . "media/album/Action/update/album_Id/" . $album_id . "/"; ?>" onclick="update_photos_album(<?php echo $album_id; ?>);return false;">
                                <span><?php echo language_code('DSP_EDIT'); ?></span>
                            </a>
                            <a class="dspdp-btn dspdp-btn-sm dspdp-btn-danger dsp-btn-danger dsp-btn-sm" href="<?php echo $root_link . "media/album/Action/Del/album_Id/" . $album_id . "/"; ?>" onclick="delete_photos_album(<?php echo $album_id; ?>);return false;">
                                <span><?php echo language_code('DSP_DELETE'); ?></span>
                            </a>
							</div>
                               
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