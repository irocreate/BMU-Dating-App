<?php
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


$user_id = $_REQUEST['user_id'];



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


<div role="banner" class="ui-header ui-bar-a" data-role="header">
   <?php include_once("page_menu.php");?> 
   <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_USER_FRIENDS'); ?></h1>
   <?php include_once("page_home.php");?> 

</div>
<?php
// ----------------------------------Check member privacy Settings------------------------------------





$request_Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : ''; { // -------------------------------------- else  Privacy Setting for Everyone ------------------------------------------- // 
    ?>

    <div class="ui-content" data-role="content">
        <div class="content-primary">    
            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul userlist">
                <?php
                if ($check_couples_mode->setting_status == 'Y') {

                    $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$user_id' AND friends.approved_status='Y'");
                } else {

                    $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$user_id' AND friends.approved_status='Y' AND profile.gender!='C'");
                }

                $i = 0;
                
                if( count($member_exist_friends)) {
                foreach ($member_exist_friends as $member_friends) {

                    $displayed_member_name = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$member_friends->friend_uid'");
                    ?>


                    <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                        <div class="dsp_pro_full_view">

                            <div class="profile_img_view">

                                <?php if ($user_id == '') {
                                    ?>

                                    <a onclick="viewProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">
                                        <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>" border="0" class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>"/></a>

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

                                                    <a onclick="viewProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">

                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0" class="dsp_img3"  alt="<?php echo $displayed_member_name; ?>" />

                                                    </a>                

                                                    <?php
                                                } else {
                                                    ?>

                                                    <a onclick="viewProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">               

                                                        <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"    class="dsp_img3" width="100" height="100" alt="<?php echo $displayed_member_name; ?>" /></a>                

                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <a onclick="viewProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')"> 

                                                    <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"  border="0"  class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" />

                                                </a>

                                                <?php
                                            }
                                        } else {

                                            if ($member_friends->make_private == 'Y') {

                                                if (!in_array($user_id, $favt_mem)) {
                                                    ?>

                                                    <a onclick="viewProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">

                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" alt="Private Photo" />

                                                    </a>                

                                                    <?php
                                                } else {
                                                    ?>

                                                    <a onclick="viewProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">               

                                                        <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"   border="0"   class="dsp_img3" width="100" height="100" alt="<?php echo $displayed_member_name; ?>" />
                                                    </a>                

                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <a onclick="viewProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')"> 

                                                    <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"  border="0"  class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" />

                                                </a>

                                            <?php } ?>



                                            <?php
                                        }
                                    } else {
                                        ?> 



                                        <?php if ($member_friends->make_private == 'Y') { ?>



                                            <?php if (!in_array($user_id, $favt_mem)) { ?>

                                                <a onclick="viewProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">

                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" alt="Private Photo" />

                                                </a>                

                                            <?php } else {
                                                ?>

                                                <a onclick="viewProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')">               

                                                    <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"   border="0"   class="dsp_img3" width="100" height="100" alt="<?php echo $displayed_member_name; ?>" /></a>                

                                                <?php
                                            }
                                        } else {
                                            ?>



                                            <a onclick="viewProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')"> 

                                                <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"  border="0"  class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" />

                                            </a>

                                        <?php } ?>



                                    <?php } ?>



                                    <?php
                                    unset($favt_mem);
                                }
                                ?>

                                <?php /* ?> <a href="<?php echo add_query_arg( array('pid' =>3,'mem_id'=>$member_friends->friend_uid,'pagetitle'=>"view_profile"), $root_link); ?>"> <img src="<?php echo display_members_photo($member_friends->friend_uid,$pluginpath); ?>" height="85px" class="dsp_img3" /></a><?php */ ?>

                            </div>
                            <div class="dsp_on_lf_view">
                                
                                        <a onclick="viewProfile('<?php echo $member_friends->friend_uid; ?>', 'my_profile')" >  
                                           
                                          <div class="user-name spacer-bottom-xs spacer-top-sm"> <?php echo $displayed_member_name; ?></div>
                                        </a>
                                   

                                        <span class="button-delete" title="<?php echo language_code('DSP_DELETE_LINK'); ?>" onclick="viewUserFriends('<?php echo $member_friends->friend_uid; ?>', '<?php echo language_code('DSP_DELETE_FRIEND_FROM_LIST_MESSAGE'); ?>');"><?php echo language_code('DSP_DELETE_LINK'); ?></span> 



                            </div>



                        </div>

                    </li>

                    <?php
                    $i++;
                } //end of foreach
                }
                else {
                ?>
                    <span style="text-align: center;">
                    <h3>No friends yet.</h3>
                    </span>
                <?php
                }
                ?>


            </ul>



        </div>


        <?php include_once('dspNotificationPopup.php'); // for notification pop up           ?>
    </div>

<?php } ?>
<?php include_once("dspLeftMenu.php"); ?>