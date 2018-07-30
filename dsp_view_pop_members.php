<div class="box-border">
    <div class="box-pedding">
        <div class="image-row dspdp-row">
            <?php
            if ( isset ($exist_profile_details) && !empty ($exist_profile_details )) {
                if ($exist_profile_details->gender == "M") {
                    $gender_check = "and gender='F' ";
                } else
                if ($exist_profile_details->gender == "F") {
                    $gender_check = "and gender='M' ";
                } else
                if ($exist_profile_details->gender == "C") {
                    $gender_check = "and gender in ('M','F','C') ";
                }
            }
            $gender_check = isset($gender_check)  ? $gender_check : '';
            $pop_members = $wpdb->get_results("SELECT receiver_id,count(wink_id)as wink FROM $dsp_member_winks_table join $dsp_user_profiles where receiver_id=user_id $gender_check Group by receiver_id order by wink desc limit 16");
            $i = 0;
             if ( isset ($pop_members) && !empty ($pop_members )) {
                    foreach ($pop_members as $popmember) {

                        if ($check_couples_mode->setting_status == 'Y') {

                            $exist_pop_members = $wpdb->get_row("SELECT * FROM $dsp_user_profiles where user_id='$popmember->receiver_id' AND country_id!=0");
                        } else {

                            $exist_pop_members = $wpdb->get_row("SELECT * FROM $dsp_user_profiles where user_id='$popmember->receiver_id' AND gender!='C' AND country_id!=0");
                        }
                        if (count($exist_pop_members) > 0) {
                            $exist_user_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$popmember->receiver_id'");
                            $user_name = $exist_user_name->display_name;
                            $pop_member_id = $exist_pop_members->user_id;
                            $favt_mem = array();
                            $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$pop_member_id'");
                            foreach ($private_mem as $private) {
                                $favt_mem[] = $private->favourite_user_id;
                            }
                            if ($pop_member_id != '') {
                                if (($i % 4) == 0) {
                                    ?>

                                <?php } // End if(($i%4)==0) ?>
                                <div class="dspdp-col-sm-3 dspdp-col-xs-6"><div class="image-box image-container">
                                    <?php
                                    if ($check_couples_mode->setting_status == 'Y') {
                                        if ($exist_pop_members->gender == 'C') {
                                            ?>
                                            <?php if ($exist_pop_members->make_private == 'Y') { ?>

                                                <?php if ($current_user->ID != $pop_member_id) { ?>

                                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                        <a href="<?php echo $root_link . get_username($pop_member_id) . "/my_profile/"; ?>" >
                                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0"  alt="Private Photo" />
                                                        </a>                
                                                    <?php } else {
                                                        ?>
                                                        <a href="<?php echo $root_link . get_username($pop_member_id) . "/my_profile/"; ?>" >				
                                                            <img src="<?php echo display_members_photo($pop_member_id, $imagepath); ?>"     style="width:100px; height:100px;" alt="<?php echo get_username($pop_member_id); ?>" /></a>                
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($pop_member_id) . "/my_profile/"; ?>" >				
                                                        <img src="<?php echo display_members_photo($pop_member_id, $imagepath); ?>"     style="width:100px; height:100px;" alt="<?php echo get_username($pop_member_id); ?>"/></a>                
                                                <?php } ?>
                                            <?php } else { ?>   
                                                <a href="<?php echo $root_link . get_username($pop_member_id) . "/my_profile/"; ?>">    
                                                    <img src="<?php echo display_members_photo($pop_member_id, $imagepath); ?>"    style="width:100px; height:100px;" alt="<?php echo get_username($pop_member_id); ?>"/></a>
                                            <?php } ?>

                                        <?php } else { ?>

                                            <?php if ($exist_pop_members->make_private == 'Y') { ?>

                                                <?php if ($current_user->ID != $pop_member_id) { ?>
                                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                        <a href="<?php echo $root_link . get_username($pop_member_id) . "/"; ?>" >
                                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0"  alt="Private Photo"/>
                                                        </a>                
                                                    <?php } else {
                                                        ?>
                                                        <a href="<?php echo $root_link . get_username($pop_member_id) . "/"; ?>" >				
                                                            <img src="<?php echo display_members_photo($pop_member_id, $imagepath); ?>"     style="width:100px; height:100px;" alt="<?php echo get_username($pop_member_id); ?>" /></a>                
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($pop_member_id) . "/"; ?>" >				
                                                        <img src="<?php echo display_members_photo($pop_member_id, $imagepath); ?>"     style="width:100px; height:100px;" alt="<?php echo get_username($pop_member_id); ?>" /></a>                
                                                <?php } ?>
                                            <?php } else { ?> 
                                                <a href="<?php echo $root_link . get_username($pop_member_id) . "/"; ?>">
                                                    <img src="<?php echo display_members_photo($pop_member_id, $imagepath); ?>"    style="width:100px; height:100px;" alt="<?php echo get_username($pop_member_id); ?>" /></a>
                                            <?php } ?>

                                            <?php
                                        }
                                    } else {
                                        ?> 

                                        <?php if ($exist_pop_members->make_private == 'Y') { ?>

                                            <?php if ($current_user->ID != $pop_member_id) { ?>
                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php echo $root_link . get_username($pop_member_id) . "/"; ?>" >
                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0" alt="Private Photo" />
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($pop_member_id) . "/"; ?>" >				
                                                        <img src="<?php echo display_members_photo($pop_member_id, $imagepath); ?>"     style="width:100px; height:100px;" alt="<?php echo get_username($pop_member_id); ?>" /></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($pop_member_id) . "/"; ?>" >				
                                                    <img src="<?php echo display_members_photo($pop_member_id, $imagepath); ?>"     style="width:100px; height:100px;" alt="<?php echo get_username($pop_member_id); ?>" /></a>                
                                            <?php } ?>
                                        <?php } else { ?>   
                                            <a href="<?php echo $root_link . get_username($pop_member_id) . "/"; ?>">
                                                <img src="<?php echo display_members_photo($pop_member_id, $imagepath); ?>"    style="width:100px; height:100px;" alt="<?php echo get_username($pop_member_id); ?>" /></a>
                                        <?php } ?>

                                    <?php } ?>
                                    </a>
                                    <span class="img-name">
                                        <?php
                                        if ($check_couples_mode->setting_status == 'Y') {
                                            if ($exist_pop_members->gender == 'C') {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($pop_member_id) . "/my_profile/"; ?>">

                                                    <?php echo $user_name; ?>

                                                <?php } else { ?>
                                                    <a href="<?php echo $root_link . get_username($pop_member_id) . "/"; ?>">
                                                        <?php echo $user_name; ?>
                                                        <?php
                                                    }
                                                } else {
                                                    ?> 
                                                    <a href="<?php echo $root_link . get_username($pop_member_id) . "/"; ?>">
                                                        <?php echo $user_name; ?>
                                                    <?php } ?>
                                                </a>
                                                </span>
                                                <span class="age-text dspdp-block" <?php /* ?>style="color:<?php echo $temp_color;?>"<?php */ ?>><?php echo GetAge($exist_pop_members->age) ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?></span>
                                                </div></div>
                                                <?php
                                                $i++;
                                                unset($favt_mem);
                                            }
                                        }
                                    }
                                }
                        ?>
        </div>
    </div>
</div>