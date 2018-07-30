<?php
//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->

$user_id = $_REQUEST['user_id'];

$member_id = $_REQUEST['member_id'];

$dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;




$request_Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';

$del_friend_Id = isset($_REQUEST['friend_Id']) ? $_REQUEST['friend_Id'] : '';

if (($request_Action == "Del") && ($del_friend_Id != "")) {
    $wpdb->query("DELETE from $dsp_my_friends_table WHERE friend_uid = '$del_friend_Id' AND user_id=$user_id");
}
?>



<?php
// ----------------------------------Check member privacy Settings------------------------------------





$request_Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';



$check_user_privacy_settings = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE view_my_friends='Y' AND user_id='$member_id'");



if (($check_user_privacy_settings > 0) && ($user_id != $member_id)) {  // check user privacy settings
//echo "SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'";
    $check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'");



    if ($check_my_friends_list <= 0) {   // check member is not in my friend list 
        ?>

        <div align="center"><?php echo language_code('DSP_CANT_VIEW_MEM_FRIENDS'); ?></div>
        <?php
    } else {   // -----------------------------else Check member is in my friend list ---------------------------- // 
        ?>


        <div class="swipe_div" id="mainfriend">
            <ul id="swipe_ulfriend"  style="padding-left:0px; text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">


                <?php
                $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table WHERE user_id = '$member_id' AND approved_status='Y'");

                $i = 0;

                foreach ($member_exist_friends as $member_friends) {

                    $displayed_member_name = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$member_friends->friend_uid'");
                    ?>

                    <li style="float:left;margin-right:16px;width:85px;">

                        <div class="">

                            <div >
                                <a href="<?php
                                echo add_query_arg(array('pid' => 3, 'mem_id' => $member_friends->friend_uid,
                                    'pagetitle' => "view_profile"), $root_link);
                                ?>"> 
                                    <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>" class="dsp_img3" style="width:85px; height:85px;" alt="<?php echo $displayed_member_name; ?>"/></a>
                            </div>

                            <div class="dsp_slide_l">
                                <?php echo $displayed_member_name; ?>
                            </div>

                        </div>

                    </li>
                    <?php
                    $i++;
                }
                ?>


            </ul>
        </div>

        <?php
    }   // ------------------------------------------------- End if Check in my friend list --------------------------------- //
} else { // -------------------------------------- else  Privacy Setting for Everyone ------------------------------------------- // 
    ?>

    <div class="swipe_div" id="mainfriend">
        <ul id="swipe_ulfriend"  style="padding-left:0px;text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">
            <?php
            if ($check_couples_mode->setting_status == 'Y') {

                $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$member_id' AND friends.approved_status='Y'");
            } else {

                $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$member_id' AND friends.approved_status='Y' AND profile.gender!='C'");
            }

            $i = 0;
            foreach ($member_exist_friends as $member_friends) {

                $displayed_member_name = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$member_friends->friend_uid'");
                ?>


                <li class="ivew-list">

                  
                        <div>

                            <?php if ($user_id == '') {
                                ?>

                                <a onclick="viewMemberProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">
                                    <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>" class="dsp_img3" style="width:85px; height:85px;"  alt="<?php echo $displayed_member_name; ?>" /></a>

                                <?php
                            } else {

                                $favt_mem = array();
                                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_friends->friend_uid'");

                                foreach ($private_mem as $private) {

                                    $favt_mem[] = $private->favourite_user_id;
                                }

                                if ($check_couples_mode->setting_status == 'Y') {

                                    if ($member_friends->gender == 'C') {

                                        if ($member_friends->make_private == 'Y') {

                                            if (!in_array($user_id, $favt_mem)) {
                                                ?>

                                                <a onclick="viewMemberProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">

                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:85px; height:85px;"border="0" class="dsp_img3" alt="Private Photo" />

                                                </a>                

                                                <?php
                                            } else {
                                                ?>

                                                <a onclick="viewMemberProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">				

                                                    <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"    class="dsp_img3" width="75" height="75" alt="<?php echo $displayed_member_name; ?>" /></a>                

                                                <?php
                                            }
                                        } else {
                                            ?>

                                            <a onclick="viewMemberProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')"> 

                                                <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>" class="dsp_img3" style="width:85px; height:85px;" alt="<?php echo $displayed_member_name; ?>" />

                                            </a>

                                            <?php
                                        }
                                    } else {

                                        if ($member_friends->make_private == 'Y') {

                                            if (!in_array($user_id, $favt_mem)) {
                                                ?>

                                                <a onclick="viewMemberProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">

                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:85px; height:85px;"border="0" class="dsp_img3" alt="Private Photo" />

                                                </a>                

                                                <?php
                                            } else {
                                                ?>

                                                <a onclick="viewMemberProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">				

                                                    <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"    class="dsp_img3 iviewed-img" alt="<?php echo $displayed_member_name; ?>" />
                                                </a>                

                                                <?php
                                            }
                                        } else {
                                            ?>

                                            <a onclick="viewMemberProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')"> 

                                                <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>" class="dsp_img3 iviewed-img" alt="<?php echo $displayed_member_name; ?>" />

                                            </a>

                                        <?php } ?>



                                        <?php
                                    }
                                } else {
                                    ?> 



                                    <?php if ($member_friends->make_private == 'Y') { ?>



                                        <?php if (!in_array($user_id, $favt_mem)) { ?>

                                            <a onclick="viewMemberProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">

                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" border="0" class="dsp_img3 iviewed-img" alt="Private Photo" />

                                            </a>                

                                        <?php } else {
                                            ?>

                                            <a onclick="viewMemberProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">				

                                                <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"    class="dsp_img3 iviewed-img" alt="<?php echo $displayed_member_name; ?>" /></a>                

                                            <?php
                                        }
                                    } else {
                                        ?>



                                        <a onclick="viewMemberProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')"> 

                                            <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>" class="dsp_img3 iviewed-img" alt="<?php echo $displayed_member_name; ?>" />

                                        </a>

                                    <?php } ?>



                                <?php } ?>



                                <?php
                                unset($favt_mem);
                            }
                            ?>

                            <?php /* ?> <a href="<?php echo add_query_arg( array('pid' =>3,'mem_id'=>$member_friends->friend_uid,'pagetitle'=>"view_profile"), $root_link); ?>"> <img src="<?php echo display_members_photo($member_friends->friend_uid,$pluginpath); ?>" height="85px" class="dsp_img3" /></a><?php */ ?>

                        </div>
                        <div >
                            <div class="dsp_slide_l" >
                                <?php echo $displayed_member_name; ?>
                            </div>
                            <div >
                                <?php if ($user_id == $member_id) { // display delete button only when member view his frnd list 
                                    ?>
                                    <span class="delete-icon dsp_slide_pointer" title="<?php echo language_code('DSP_DELETE_LINK'); ?>" onclick="deleteFriends('<?php echo $member_friends->friend_uid; ?>', '<?php echo language_code('DSP_DELETE_FRIEND_FROM_LIST_MESSAGE'); ?>', '<?php echo $profileView ?>');"></span> 
                                <?php } ?>
                            </div>

                        </div>
                  
                </li>

                <?php
                $i++;
            }
            ?>
        </ul>
    </div>



<?php } ?>