<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a  onclick="viewSetting(0, 'setting')"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MIDDLE_TAB_BLOCKED'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>

</div>

<?php
$dsp_blocked_members_table = $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;

$delblock_id = isset($_REQUEST['Block_Id']) ? $_REQUEST['Block_Id'] : '';



$Actiondel = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';



if (($delblock_id != "") && ($Actiondel == "Del")) {

    $wpdb->query("DELETE FROM $dsp_blocked_members_table where blocked_id = '$delblock_id'");
}

$total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_blocked_members_table where user_id='$user_id'");
?>

<div class="ui-content" data-role="content">
    <div class="content-primary">	
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">

            <?php
            if ($total_results1 > 0) {
                ?>
                <form name="delblockedmembersfrm"   id="dspAccount">

                    <?php
                    if ($check_couples_mode->setting_status == 'Y') {
                        $blocked_members = $wpdb->get_results("SELECT * FROM $dsp_blocked_members_table blocked, $dsp_user_profiles profile WHERE blocked.block_member_id = profile.user_id
								AND blocked.user_id = '$user_id'");
                    } else {

                        $blocked_members = $wpdb->get_results("SELECT * FROM $dsp_blocked_members_table blocked, $dsp_user_profiles profile WHERE blocked.block_member_id = profile.user_id

								AND blocked.user_id = '$user_id' AND profile.gender!='C' ");
                    }

                    $i = 0;
                    foreach ($blocked_members as $Member) {
                        
                        $blocked_id = $Member->blocked_id;

                        $block_member_id = $Member->block_member_id;

                        $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$block_member_id'");

                        $exist_make_private->make_private;

                        $favt_mem = array();


                        $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$block_member_id'");

                        foreach ($private_mem as $private) {

                            $favt_mem[] = $private->favourite_user_id;
                        }
                        ?>
                        <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                            <div class="image-container">
                                
                                <?php  $userName = $wpdb->get_var("SELECT display_name FROM $DSP_USERS_TABLE WHERE id =$block_member_id "); ?>
                                <a style="float:left; width:100%;"  onclick="viewProfile('<?php echo $block_member_id ?>', 'my_profile')" class="dsp_span_pointer">
                                    <?php echo $userName; ?>
                                </a>
                                <?php
                                if ($check_couples_mode->setting_status == 'Y') {

                                    if ($Member->gender == 'C') {
                                        ?>

                                        <?php if ($exist_make_private->make_private == 'Y') { ?>



                                            <?php if (!in_array($user_id, $favt_mem)) {
                                                ?>

                                                <a  onclick="viewProfile('<?php echo $block_member_id ?>', 'my_profile')">

                                                    <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style=" width:100px; height:100px;"  border="0" class="dsp_img3" />

                                                </a>                

                                            <?php } else {
                                                ?>

                                                <a onclick="viewProfile('<?php echo $block_member_id ?>', 'my_profile')">				

                                                    <img src="<?php echo display_members_photo($block_member_id, $imagepath); ?>"    class="dsp_img3" style=" width:100px; height:100px;" /></a>                

                                                <?php
                                            }
                                        } else {
                                            ?>



                                            <a onclick="viewProfile('<?php echo $block_member_id ?>', 'my_profile')">

                                                <img src="<?php echo display_members_photo($block_member_id, $imagepath); ?>" class="dsp_img3" style=" width:100px; height:100px;"  />

                                            </a>

                                        <?php } ?>



                                        <?php
                                    } else {
                                        ?>



                                        <?php if ($exist_make_private->make_private == 'Y') { ?>



                                            <?php if (!in_array($user_id, $favt_mem)) { ?>

                                                <a onclick="viewProfile('<?php echo $block_member_id ?>', 'my_profile')">

                                                    <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style=" width:100px; height:100px;"  border="0" class="dsp_img3" />

                                                </a>                

                                            <?php } else {
                                                ?>

                                                <a onclick="viewProfile('<?php echo $block_member_id ?>', 'my_profile')">				

                                                    <img src="<?php echo display_members_photo($block_member_id, $imagepath); ?>"    class="dsp_img3" style=" width:100px; height:100px;" /></a>                

                                                <?php
                                            }
                                        } else {
                                            ?>

                                            <a onclick="viewProfile('<?php echo $block_member_id ?>', 'my_profile')">

                                                <img src="<?php echo display_members_photo($block_member_id, $imagepath); ?>" class="dsp_img3" style=" width:100px; height:100px;"  />

                                            </a>

                                        <?php } ?>



                                        <?php
                                    }
                                } else {
                                    ?> 



                                    <?php if ($exist_make_private->make_private == 'Y') { ?>





                                        <?php if (!in_array($user_id, $favt_mem)) { ?>

                                            <a onclick="viewProfile('<?php echo $block_member_id ?>', 'my_profile')">

                                                <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style=" width:100px; height:100px;"  border="0" class="dsp_img3" />

                                            </a>                

                                        <?php } else {
                                            ?>

                                            <a onclick="viewProfile('<?php echo $block_member_id ?>', 'my_profile')">				

                                                <img src="<?php echo display_members_photo($block_member_id, $imagepath); ?>"    class="dsp_img3" style=" width:100px; height:100px;" />
                                            </a>                

                                            <?php
                                        }
                                    } else {
                                        ?>

                                        <a onclick="viewProfile('<?php echo $block_member_id ?>', 'my_profile')">

                                            <img src="<?php echo display_members_photo($block_member_id, $imagepath); ?>" class="dsp_img3" style=" width:100px; height:100px;" />

                                        </a>

                                    <?php } ?>



                                <?php } ?>

                                <input type="hidden" name="pagetitle" value="<?php echo $profile_pageurl; ?>" />
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                <input type="hidden" name="Block_Id" value="<?php echo $blocked_id; ?>" />
                                <input type="hidden" name="Action" value="Del" />

                                <span style="float:left; width:100%;" onclick="viewSetting(0, 'post')" class="dsp_span_pointer">
                                    <?php echo language_code('DSP_UNBLOCK_LINK') ?>
                                </span>





                            </div>
                        </li>

                        <?php
                        $i++;
                        unset($favt_mem);
                    }
                    ?>
                </form>



            <?php } else { ?>


                <div style="text-align:center"><strong><?php echo language_code('DSP_NO_BLOCKED_MEMBER_MSG') ?></strong></div>



            <?php } ?>



        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up     ?>
</div>