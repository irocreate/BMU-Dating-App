<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ----------------------------------Check member privacy Settings------------------------------------
$check_user_privacy_settings = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE view_my_pictures='Y' AND user_id='$member_id'");
if (($check_user_privacy_settings > 0) && ($user_id != $member_id)) {  // check user privacy settings
    $check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'");
    if ($check_my_friends_list <= 0) {   // check member is not in my friend list
        ?>

        <div class="box-border">
            <div class="box-pedding">
                <div align="center"><?php echo language_code('DSP_ONLY_FRIEND_VIEW_PIC_MESSAGE'); ?></div>
            </div>
        </div>
    <?php } else {   // -----------------------------else Check member is in my friend list ---------------------------- // 
        ?>
        <div class="box-border">
            <div class="box-pedding">
                <div align="left" >
                    <ul class="albums dspdp-row dsp-row">
                        <?php
                        $member_exist_albums = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = '$member_id' AND private_album='N' order by date_created DESC");
                        if(isset($member_exist_albums) && count($member_exist_albums) > 0){
                            $i = 0;
                            foreach ($member_exist_albums as $user_album) {
                                $album_id = $user_album->album_id;
                                $album_name = $user_album->album_name;
                                if (($i % 4) == 0) {
                                    ?>
                                <?php } ?>
                            <li class="dspdp-col-sm-4 dspdp-col-xs-6  dsp-text-center">
                                    <div class="image-container"><div class="name dspdp-medium" style="text-align:center;" onclick="location.href = '<?php echo $root_link . get_username($member_id) . "/Pictures/"; ?>';" style="cursor:pointer;text-decoration:underline;"><?php echo $album_name ?></div>
                                <span class="dsp_span_pointer" onclick="location.href = '<?php echo $root_link . get_username($member_id) . "/Pictures/album_id/" . $album_id . "/"; ?>';"><img src="<?php echo WPDATE_URL . '/images/album.png'; ?>" style="width:80px; height:80px;" class="img3" alt="<?php echo get_username($member_id);?>" /></span><br /></div>
                                </li>
                                <?php
                                $i++;
                            }
                        }else{ ?>
                        <li class="dspdp-col-sm-12 dsp-sm-12 dspdp-col-xs-12 dsp-xs-12">
                            <span class="dsp_span_pointer"><?php echo language_code('DSP_NO_PICTURE_UPLOADED'); ?></span><br />
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }   // ------------------------------------------------- End if Check in my friend list --------------------------------- //
} else {
// -------------------------------------- else  Privacy Setting for Everyone ------------------------------------------- // 
?>
    <div class="box-border">
        <div class="box-pedding">
            <div align="left">
                <ul class="albums albums dspdp-row dsp-row">
                    <?php
                    $member_exist_albums = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = '$member_id' AND private_album='N' order by date_created DESC");
                    if(isset($member_exist_albums) && !empty($member_exist_albums)){
                        $i = 0;
                        foreach ($member_exist_albums as $user_album) {
                            $album_id = $user_album->album_id;
                            $album_name = $user_album->album_name;
                            if (($i % 4) == 0) {
                                ?>
                            <?php } ?>
                        <li class="dspdp-col-sm-4 dsp-sm-4 dspdp-col-xs-6 dsp-xs-6 dsp-text-center">
                                <div class="image-container"><div class="name dspdp-medium" style="text-align:center;" onclick="location.href = '<?php echo $root_link . get_username($member_id) . "/Pictures/"; ?>';" style="cursor:pointer;text-decoration:underline;"><?php echo $album_name ?></div>
                            <span class="dsp_span_pointer" onclick="location.href = '<?php echo $root_link . get_username($member_id) . "/Pictures/album_id/" . $album_id . "/"; ?>';"><img src="<?php echo WPDATE_URL . '/images/album.png'; ?>" style="width:80px; height:80px;" class="img3" alt="<?php echo get_username($member_id);?>"/></span><br /></div>
                            </li>
                            <?php
                            $i++;
                        }
                    }else{
                    ?>
                    <li class="dspdp-col-sm-12 dsp-sm-12 dspdp-col-xs-12 dsp-xs-12">
                        <span class="dsp_span_pointer"><?php echo language_code('DSP_NO_PICTURE_UPLOADED'); ?></span><br />
                    </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
<?php } 