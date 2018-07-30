<?php
//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

include("../../../../wp-config.php");

//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include_once("dspFunction.php");

include_once("../general_settings.php");


$dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;

$user_id = $_REQUEST['user_id'];

$member_id = $_REQUEST['member_id'];
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php
        echo language_code('DSP_PHOTOS');
        ;
        ?></h1>

</div>
<div class="ui-content" data-role="content">
    <div class="content-primary">	 
        <?php
        // ----------------------------------Check member privacy Settings------------------------------------



        $check_user_privacy_settings = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE view_my_pictures='Y' AND user_id='$member_id'");



        if (($check_user_privacy_settings > 0) && ($user_id != $member_id)) {  // check user privacy settings
            $check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'");



            if ($check_my_friends_list <= 0) {   // check member is not in my friend list 
                ?>

                <div align="center"><?php echo language_code('DSP_ONLY_FRIEND_VIEW_PIC_MESSAGE'); ?></div>

                <?php
            } else {   // -----------------------------else Check member is in my friend list ---------------------------- // 
                ?>

                <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">

                    <?php
                    $member_exist_albums = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = '$member_id' order by date_created DESC");

                    foreach ($member_exist_albums as $user_album) {

                        $album_id = $user_album->album_id;

                        $album_name = $user_album->album_name;
                        ?>


                        <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                            <div class="dsp_pro_full">

                                <div class="dsp_left" onclick="member_pictures('<?php echo $album_id ?>');"><img src="WPDATE_URL . '/images/album.jpg' " style="width:80px; height:80px;" class="img3" /></div>

                                <div class="dsp_right" style="text-align:center;" onclick="member_pictures('<?php echo $album_id ?>');" style="cursor:pointer;text-decoration:underline;"><?php echo $album_name ?></div>
                            </div>
                        </li>

                    <?php } ?>


                </ul>





                <?php
            }   // ------------------------------------------------- End if Check in my friend list --------------------------------- //
        } else {   // -------------------------------------- else  Privacy Setting for Everyone ------------------------------------------- // 
            ?>

            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">
                <?php
                $member_exist_albums = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = '$member_id' order by date_created DESC");

                foreach ($member_exist_albums as $user_album) {

                    $album_id = $user_album->album_id;
                    $album_name = $user_album->album_name;
                    ?>


                    <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                        <div class="dsp_pro_full">

                            <div class="dsp_left" onclick="member_pictures('<?php echo $album_id ?>');">
                                <img src="WPDATE_URL . '/images/album.jpg' " style="width:80px; height:80px;" class="img3" />
                            </div>

                            <div class="dsp_right" style="text-align:center;" onclick="member_pictures('<?php echo $album_id ?>');" style="cursor:pointer;text-decoration:underline;"><?php echo $album_name ?></div>

                        </div>

                    </li>
                <?php }
                ?>


            </ul>





        <?php } ?>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up      ?>
</div>