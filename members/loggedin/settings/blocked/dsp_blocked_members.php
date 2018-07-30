<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
$delblock_id = get('Block_Id');
$Actiondel = get('Action');
if (($delblock_id != "") && ($Actiondel == "Del")) {
    $wpdb->query("DELETE FROM $dsp_blocked_members_table where blocked_id = '$delblock_id'");
}
$total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_blocked_members_table where user_id='$user_id'");
?>

<div class="box-border">
    <div class="box-pedding">
        <div class="heading-submenu"><strong><?php echo language_code('DSP_BLOCKED_TITLE'); ?></strong></div></br></br>
        <div class="dsp-row">
            <?php
            if ($total_results1 > 0) {
                ?>
                <form name="delblockedmembersfrm" action="" method="post">
                    <div class="title-text dsp-sm-12 margin-btm-1 dsp-h4"><strong><?php echo language_code('DSP_BLOCKED_MEMBERS') ?></strong></div>
                    <?php
                    if ($check_couples_mode->setting_status == 'Y') {
                        $blocked_members = $wpdb->get_results("SELECT * FROM $dsp_blocked_members_table blocked, $dsp_user_profiles profile WHERE blocked.block_member_id = profile.user_id
AND blocked.user_id = '$user_id'");
                    } else {
                        $blocked_members = $wpdb->get_results("SELECT * FROM $dsp_blocked_members_table blocked, $dsp_user_profiles profile WHERE blocked.block_member_id = profile.user_id
AND blocked.user_id = '$user_id' AND profile.gender!='C' ");
                    }
//$blocked_members = $wpdb->get_results("SELECT * FROM $dsp_blocked_members_table WHERE user_id = '$user_id' ORDER BY blocked_id");
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
                        if (($i % 3) == 0) {
                            ?>
                        <?php }  // End if(($i%4)==0) ?>
                        <div class="dsp-sm-3 dsp-text-center"><div class="image-container dsp-block-member-item">
                            <?php
                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($Member->gender == 'C') {
                                    ?>
                                    <?php if ($exist_make_private->make_private == 'Y') { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($block_member_id) . "/my_profile/"; ?>" >
                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo"/>
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($block_member_id) . "/my_profile/"; ?>" >				
                                                <img src="<?php echo display_members_photo_thumb($block_member_id, $imagepath); ?>"    border="0" class="img-big" alt="<?php echo  get_username($block_member_id) ;?>"/></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>

                                        <a href="<?php echo $root_link . get_username($block_member_id) . "/my_profile/"; ?>">
                                            <img src="<?php echo display_members_photo_thumb($block_member_id, $imagepath); ?>" class="dsp_img3" style=" width:100px; height:100px;"  alt="<?php echo  get_username($block_member_id) ;?>"/>
                                        </a>
                                    <?php } ?>

                                <?php } else { ?>

                                    <?php if ($exist_make_private->make_private == 'Y') { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($block_member_id) . "/"; ?>" >
                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($block_member_id) . "/"; ?>" >				
                                                <img src="<?php echo display_members_photo_thumb($block_member_id, $imagepath); ?>"    border="0" class="img-big" alt="<?php echo  get_username($block_member_id) ;?>"/></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($block_member_id) . "/"; ?>">
                                            <img src="<?php echo display_members_photo_thumb($block_member_id, $imagepath); ?>" class="dsp_img3" style=" width:100px; height:100px;"  alt="<?php echo  get_username($block_member_id) ;?>" />
                                        </a>
                                    <?php } ?>

                                    <?php
                                }
                            } else {
                                ?> 

                                <?php if ($exist_make_private->make_private == 'Y') { ?>


                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                        <a href="<?php echo $root_link . get_username($block_member_id) . "/"; ?>" >
                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo" />
                                        </a>                
                                    <?php } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($block_member_id) . "/"; ?>" >				
                                            <img src="<?php echo display_members_photo_thumb($block_member_id, $imagepath); ?>"    border="0" class="img-big" alt="<?php echo  get_username($block_member_id) ;?>"/></a>                
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($block_member_id) . "/"; ?>">
                                        <img src="<?php echo display_members_photo_thumb($block_member_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo  get_username($block_member_id) ;?>"/>
                                    </a>
                                <?php } ?>
                            <?php } ?>
                            <span style="float:left; width:100%;" onclick="delete_blocked_member(<?php echo $blocked_id ?>);" class="dsp-btn-default dsp-delete_blocked_member"><?php echo language_code('DSP_UNBLOCK_LINK') ?></span> 
                        </div></div>
                        <?php
                        $i++;
                        unset($favt_mem);
                    }
                    ?>
                </form>
            <?php } else { ?>
                <div style="text-align:center"><strong><?php echo language_code('DSP_NO_BLOCKED_MEMBER_MSG') ?></strong></div>
                    <?php } ?>
        </div>
    </div>
</div>