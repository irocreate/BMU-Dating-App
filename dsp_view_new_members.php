<div class="box-border">
    <div class="box-pedding">
        <div class="image-row dspdp-row row">
            <?php
                if ( isset ($exist_profile_details) && !empty ($exist_profile_details)) {
                    if ($exist_profile_details->gender == "M") { 
                        $gender_check = "AND gender='F' ";
                    } else
                    if ($exist_profile_details->gender == "F") {
                        $gender_check = "AND gender='M' ";
                    } else
                    if ($exist_profile_details->gender == "C") {
                        $gender_check = "AND gender in ('M','F','C') ";
                    }
                }
                $gender_check = isset($gender_check)  ? $gender_check : '';  
                if ($check_couples_mode->setting_status == 'Y') { 
                        //echo "SELECT * FROM $dsp_user_profiles WHERE status_id=1  AND country_id!=0 AND last_update_date > DATE_SUB(now(), INTERVAL 14 DAY) $gender_check Order By Rand() LIMIT 16";die;
                    $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id=1 AND country_id!=0 $gender_check ORDER BY UNIX_TIMESTAMP(last_update_date) DESC LIMIT 8");
                } else { 

                    //$new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE gender!='C' and status_id=1  AND country_id!=0 AND last_update_date > DATE_SUB(now(), INTERVAL 14 DAY) $gender_check Order By Rand() LIMIT 16");
                    $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE gender!='C' and status_id=1  AND country_id!=0 $gender_check ORDER BY UNIX_TIMESTAMP(last_update_date) DESC LIMIT 8");
                }

            $i = 0;
            if(isset($new_members) && !empty($new_members)){
                foreach ($new_members as $member) {
                    $exist_user_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$member->user_id'");
                    $user_name = isset($exist_user_name->display_name) ? $exist_user_name->display_name  : '' ;
                    $new_member_id = $member->user_id;
                    $favt_mem = array();

                    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member->user_id'");
                    foreach ($private_mem as $private) {
                        $favt_mem[] = $private->favourite_user_id;
                    }
                    if (($i % 4) == 0) {
                        ?>
                    <?php } // End if(($i%4)==0) ?>
                    <div class="dspdp-col-sm-3 dspdp-col-xs-6 dsp-sm-3">
                        <div class="image-box image-container">
                            <div class="circle-image">
                                <?php
                                if ($check_couples_mode->setting_status == 'Y') {
                                    if ($member->gender == 'C') {
                                        ?>

                                        <?php if ($member->make_private == 'Y') { ?>

                                            <?php if ($current_user->ID != $new_member_id) { ?>


                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php echo $root_link . get_username($new_member_id) . "/my_profile/"; ?>" >
                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" alt="Private Photo" />
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($new_member_id) . "/my_profile/"; ?>" >				
                                                        <img src="<?php echo display_members_photo($new_member_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo get_username($new_member_id); ?>" /></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($new_member_id) . "/my_profile/"; ?>" >				
                                                    <img src="<?php echo display_members_photo($new_member_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;"alt="<?php echo get_username($new_member_id); ?>" /></a>                
                                            <?php } ?>
                                        <?php } else {
                                            ?>                
                                            <a href="<?php echo $root_link . get_username($new_member_id) . "/my_profile/"; ?>" >				
                                                <img src="<?php echo display_members_photo($new_member_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;"alt="<?php echo get_username($new_member_id); ?>" /></a>
                                        <?php } ?>


                                    <?php } else { ?>

                                        <?php if ($member->make_private == 'Y') { ?>
                                            <?php if ($current_user->ID != $new_member_id) { ?>

                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php echo $root_link . get_username($new_member_id) . "/"; ?>" >
                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0" class="dsp_img3"  alt="Private Photo"/>
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($new_member_id) . "/"; ?>" >				
                                                        <img src="<?php echo display_members_photo($new_member_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo get_username($new_member_id); ?>" /></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($new_member_id) . "/"; ?>" >				
                                                    <img src="<?php echo display_members_photo($new_member_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo get_username($new_member_id); ?>" /></a>                
                                            <?php } ?>
                                        <?php } else { ?> 
                                            <a href="<?php echo $root_link . get_username($new_member_id) . "/"; ?>">				
                                                <img src="<?php echo display_members_photo($new_member_id, $imagepath); ?>"   class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo get_username($new_member_id); ?>" /></a>
                                        <?php } ?>

                                        <?php
                                    }
                                } else {
                                    ?>

                                    <?php if ($member->make_private == 'Y') { ?>
                                        <?php if ($current_user->ID != $new_member_id) { ?>

                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                <a href="<?php echo $root_link . get_username($new_member_id) . "/"; ?>" >
                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" alt="Private Photo" />
                                                </a>                
                                            <?php } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($new_member_id) . "/"; ?>" >				
                                                    <img src="<?php echo display_members_photo($new_member_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo get_username($new_member_id); ?>" /></a>                
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($new_member_id) . "/"; ?>" >				
                                                <img src="<?php echo display_members_photo($new_member_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo get_username($new_member_id); ?>" /></a>                
                                        <?php } ?>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . get_username($new_member_id) . "/"; ?>">				
                                            <img src="<?php echo display_members_photo($new_member_id, $imagepath); ?>"   class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo get_username($new_member_id); ?>" /></a>
                                    <?php } ?>

                                <?php } ?>
                            </div>
                            <span class="img-name">
                                <?php
                                if ($check_couples_mode->setting_status == 'Y') {
                                        if ($member->gender == 'C') {
                                ?>
                                    <a href="<?php echo $root_link . get_username($new_member_id) . "/my_profile/"; ?>">
                                    <?php echo $user_name; ?>
                                        <?php } else { ?>
                                                <a href='<?php echo $root_link . get_username($new_member_id) . "/"; ?>'>
                                                <?php echo $user_name; ?>
                                    <?php   }   
                                } else {
                                            ?> 
                                    <a href='<?php echo $root_link . get_username($new_member_id) . "/"; ?>' >
                                    <?php echo $user_name; ?>
                                <?php } ?>
                                    </a>
                            </span>
                            <span class="age-text dspdp-block"
                            <?php echo GetAge($member->age) ?><?php echo language_code('DSP_YEARS_OLD_TEXT'); ?></span>
                        </div>
                    </div>
        <?php
                $i++;
                unset($favt_mem);
            }
        }
        ?>
        </div>
    </div>
</div>