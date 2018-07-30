<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$get_album_id = $_GET['album_id'];
$Action = $_GET['Action'];
if ($Action == "delete" && $get_album_id != "") {
    $num_rows = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_user_photos_table WHERE album_id='$get_album_id'"));
    if ($num_rows == 0) {
        $wpdb->query("DELETE FROM $dsp_user_albums_table WHERE album_id = '$get_album_id'");
// Path to directory you want to delete
        $directory = dirname(__FILE__) . '/user_photos/user_' . $user_id . '/album_' . $get_album_id;
// Delete Album Directory.
        rmdir($directory);
        if ($get_album_id != "") {
            $Errormessge = language_code('DSP_ALBUM_DELETED_MSG');
        }
    } else {
        if ($get_album_id != "") {
            $Errormessge = language_code('DSP_ALBUM_NOT_BLANK_MSG');
        }
    }
}
// IF Error then print //
if (isset($Errormessge)) {
    ?>
    <p class="error" align="center"><?php echo $Errormessge; ?><p>
    <?php } ?>
<div class="box-border">
    <div class="box-pedding">
        <div>  
            <form name="albumfrm" action="" method="post">
                <table cellpadding="0" cellspacing="0" border="0" width="98%">
                    <tr>
                        <th width="50%" align="left" style="padding-left:30px;" colspan="2"><?php echo language_code('DSP_ALBUM') ?></th>
                        <th width="20%"><?php echo language_code('DSP_EDIT_ALBUM') ?></th>
                        <th width="20%"><?php echo language_code('DSP_DELETE_ALBUM?') ?></th>
                    </tr>
                    <tr><td colspan="4" style="border-bottom:1px #000000 solid;"></td></tr>
                    <?php
                    $exists_album = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = '$user_id'");
                    foreach ($exists_album as $user_album) {
                        $album_id = $user_album->album_id;
                        $album_name = $user_album->album_name;
                        ?>
                        <tr> 
                            <td width="10%" align="center"><a href="<?php echo $root_link . "media/manage_photos/album_id/" . $album_id; ?>"><img src="<?php echo WPDATE_URL . '/images/album.png'; ?>" width="40px" height="30px" alt="<?php echo $album_name ?>"/></a></td>
                            <td width="50%" align="left" style="padding-left:15px;"><a href="<?php echo $root_link . "media/manage_photos/album_id/" . $album_id; ?>"><u><?php echo $album_name ?></u></a></td>
                            <td width="20%" align="center"><a href="<?php echo $root_link . "media/album/Action/update/album_id/" . $album_id; ?>"><img src="<?php echo WPDATE_URL . '/images/edit.png'; ?>" width="16" height="16" alt="Edit" border="0" alt="edit"></a></td>
                            <td width="20%" align="center"><a href="<?php echo $root_link . "media/manage_album/Action/delete/album_id/" . $album_id; ?>"><img src="<?php echo WPDATE_URL . '/images/b_drop.png'; ?>" width="16" height="16" alt="Edit" border="0" alt="Drop"></a></td>
                        </tr>
                    <?php } ?>
                </table>
            </form>
        </div>
    </div>
</div>