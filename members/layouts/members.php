<div class="box-border home-gest-page">
    <div class="box-pedding">
        <div class="heading-text"><?php echo language_code('DSP_DZONIA_MEMBERS_HEADING_TEXT');?></div>
        <div class="dsp_guest_home_page_wrap dspdp-clearfix dsp-clearfix">
            <div class="dsp-row">
            <?php
           if ($member_gender != '') {

                if ($check_couples_mode->setting_status == 'Y') {
                    $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles profile WHERE status_id=1  AND gender='$member_gender' AND country_id!=0 and stealth_mode='N'  Order By user_profile_id DESC LIMIT $start, $limit");
                } else {

                    $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles profile WHERE status_id=1  AND gender='$member_gender' AND country_id!=0 AND gender!='C' and stealth_mode='N'  Order By user_profile_id DESC LIMIT $start, $limit");
                }
            } else {  
                if ($check_couples_mode->setting_status == 'Y') {
                    $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles profile WHERE status_id=1 AND country_id!=0 and stealth_mode='N'  Order By user_profile_id DESC LIMIT $start, $limit");
                } else {

                    $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles profile WHERE status_id=1 AND country_id!=0 AND gender!='C' and stealth_mode='N'  Order By user_profile_id DESC LIMIT $start, $limit");
                }
            }
            $i = 0;
            foreach ($new_members as $member) { 
                $exist_user_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$member->user_id'");
                $user_name = isset($exist_user_name->display_name)?$exist_user_name->display_name : '';
                $exist_user_id = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$member->user_id'");
                if (($i % 4) == 0) {
                    ?>
                <?php } // End if(($i%4)==0) ?>
                
                    <div class="dsp_guest_home_page_col1 dsp-md-3 dspdp-col-sm-3 dspdp-col-xs-6">
                       <div class="image-container"> <div class="dsp-new-member-photo circle-image">
                            <?php 
                            if ($check_couples_mode->setting_status == 'Y') {

                                if ($member->gender == 'C') {
                                    ?>
                                    <?php if ($exist_user_id->make_private == 'Y') { ?>

                                        <a href="<?php echo $root_link . get_username($member->user_id) . "/my_profile/"; ?>">
                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0"  alt="Private Photo"/></a>

                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . get_username($member->user_id) . "/my_profile/"; ?>">
                                            
                                            <img src="<?php echo display_members_photo($member->user_id, $imagepath); ?>" style="width:100px; height:100px;"  alt="<?php echo $user_name;?>" /></a>
                                    <?php } ?>

                                <?php } else { ?>

                                    <?php if ($exist_user_id->make_private == 'Y') { ?>
                                        <a href="<?php echo $root_link . get_username($member->user_id); ?>">
                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0"  alt="Private Photo" /></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . get_username($member->user_id); ?>">
                                            <img src="<?php echo display_members_photo($member->user_id, $imagepath); ?>"   style="width:100px; height:100px;"  alt="<?php echo $user_name;?>"/></a>
                                    <?php } ?>

                                    <?php
                                }
                            } else {
                                
                                ?> 
                                <?php if ($exist_user_id->make_private == 'Y') { ?>
                                    <a href="<?php echo $root_link . get_username($member->user_id); ?>">
                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0"  alt="Private Photo" /></a>
                                <?php } else {  ?>
                                    <a href="<?php echo $root_link . get_username($member->user_id); ?>">
                                        <img src="<?php echo display_members_photo($member->user_id, $imagepath); ?>"   style="width:100px; height:100px;"  alt="<?php echo $user_name;?>" /></a>
                                <?php } ?>


                            <?php } ?>
                        </div>
                        <div class="dsp_clr"></div>
                        <div class="dsp_name">
                            <?php
                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($member->gender == 'C') {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($member->user_id) . "/my_profile"; ?>">
                                        <?php echo $user_name; ?></a>

                                <?php } else { ?>
                                    <a href="<?php echo $root_link . get_username($member->user_id); ?>">
                                        <?php echo $user_name; ?></a>
                                    <?php
                                }
                            } else {
                                ?> 
                                <a href="<?php echo $root_link . get_username($member->user_id); ?>"><?php echo $user_name; ?></a>
                            <?php } ?>
                            <br /><span class="age-text"> <?php echo GetAge($member->age) ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?></span>
                        </div></div>
                    </div>
                               
                <?php
                $i++;
            } // End foreach ($new_members as $member)
            ?>
            </div> 
            <div class="dsp_clr"></div>
        </div>

        <div >
            <?php
            // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
            echo $pagination
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
            ?>
        </div>
    </div>
</div>