 <?php 
    global $wp_query, $wpdb;   
    $limit = 8;
    $start = isset( $_POST[ 'paged' ] ) ? $_POST[ 'paged' ] : ''; 
    $page_id = isset( $_POST[ 'paged_id' ] ) ? $_POST[ 'paged_id' ] : '';  //fetch post query string id
    $posts_table = $wpdb->prefix . POSTS;
    $post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$page_id'");
    $dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
    $root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";  // Print Site root link
    $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
    $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
    $check_couples_mode = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'" );
    $imagepath = get_option('siteurl') . '/wp-content/';  // image Path
    $tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
    if (get('page') != "")
    $page = get('page');
    else
    $page = 1;
    // How many adjacent pages should be shown on each side?

    $adjacents = 2;
    $check_search_result = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'search_result'");
   /* $limit = (isset($check_search_result->setting_value) && $check_search_result->setting_value != 0) ? $check_search_result->setting_value : 8;*/

    $check_member_list_gender_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'member_list_gender'");

    $member_list_gender = $check_member_list_gender_mode->setting_value;

    $errors = array();

    $page_name = $root_link . "ALL/";

    if ($member_list_gender == 2) {

        $member_gender = 'M';

    } else if ($member_list_gender == 3) {

        $member_gender = 'F';

    } else

        $member_gender = '';



    if ($member_gender != '') {

        $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_profiles WHERE status_id=1 AND gender='$member_gender' AND country_id!=0 order by user_profile_id DESC");

    } else {

        if ($check_couples_mode->setting_status == 'Y') {

            $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_profiles WHERE status_id=1  AND country_id!=0  AND stealth_mode='N' order by user_profile_id DESC");

        }else {

            $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_profiles WHERE status_id=1  AND country_id!=0 AND gender!='C' and stealth_mode='N' order by user_profile_id DESC");

        }

    } 

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
        if( ! empty( $new_members ) ) :
            foreach ($new_members as $member ) { 
                $exist_user_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$member->user_id'");
                $user_name = isset($exist_user_name->display_name)?$exist_user_name->display_name : '';
                $exist_user_id = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$member->user_id'");
                if (($i % 4) == 0) {
                    ?>
                <?php } // End if(($i%4)==0) 
                    //add sidebar on the basis of activeness on right sidebar
                    if( is_active_sidebar( 'lm_dating_member_right_sidebar' ) ) {
                        $grid_layout_css = 'col-md-4 col-sm-6 col-xs-12';
                    } else {
                        $grid_layout_css = 'col-md-3 col-sm-4 col-xs-12';
                    }
                ?>
                
                    <div class="dsp_guest_home_page_col1  <?php echo esc_attr( $grid_layout_css ); ?>">
                       <div class="image-container"> <div class="dsp-new-member-photo circle-image">
                            <?php 
                            if ($check_couples_mode->setting_status == 'Y') {

                                if ($member->gender == 'C') {
                                    ?>
                                    <?php if ($exist_user_id->make_private == 'Y') { ?>

                                        <a href="<?php echo $root_link . get_username($member->user_id) . "/my_profile/"; ?>">
                                            <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0"  alt="Private Photo"/></a>

                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . get_username($member->user_id) . "/my_profile/"; ?>">
                                            
                                            <img class="img-circle" src="<?php echo display_members_photo($member->user_id, $imagepath); ?>" style="width:100px; height:100px;"  alt="<?php echo $user_name;?>" /></a>
                                    <?php } ?>

                                <?php } else { ?>

                                    <?php if ($exist_user_id->make_private == 'Y') { ?>
                                        <a href="<?php echo $root_link . get_username($member->user_id); ?>">
                                            <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0"  alt="Private Photo" /></a>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . get_username($member->user_id); ?>">
                                            <img class="img-circle" src="<?php echo display_members_photo($member->user_id, $imagepath); ?>"   style="width:100px; height:100px;"  alt="<?php echo $user_name;?>"/></a>
                                    <?php } ?>

                                    <?php
                                }
                            } else {
                                
                                ?> 
                                <?php if ($exist_user_id->make_private == 'Y') { ?>
                                    <a href="<?php echo $root_link . get_username($member->user_id); ?>">
                                        <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0"  alt="Private Photo" /></a>
                                <?php } else {  ?>
                                    <a href="<?php echo $root_link . get_username($member->user_id); ?>">
                                        <img class="img-circle" src="<?php echo display_members_photo($member->user_id, $imagepath); ?>"   style="width:100px; height:100px;"  alt="<?php echo $user_name;?>" /></a>
                                <?php } ?>


                            <?php } ?>
                        </div>
                        <div class="dsp_clr"></div>
                        <div class="dsp_name img-name">
                            <?php
                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($member->gender == 'C') {
                                    ?>
                                    <a class="ss1" href="<?php echo $root_link . get_username($member->user_id) . "/my_profile"; ?>">
                                        <?php echo $user_name; ?></a>

                                <?php } else { ?>
                                    <a class="ss2" href="<?php echo $root_link . get_username($member->user_id); ?>">
                                        <?php echo $user_name; ?></a>
                                    <?php
                                }
                            } else {
                                ?> 
                                <a class="ss3" href="<?php echo $root_link . get_username($member->user_id); ?>"><?php echo $user_name; ?></a>
                            <?php } ?>

                                <?php love_match_show_online_members_status( $member->user_id ); ?>
                        </div>
                        <span class="age-text dspdp-block">
                            <?php 
                                echo GetAge($member->age) ?>,&nbsp<?php echo love_match_fetch_country( $member->country_id );
                            ?>        
                            </span>
                    </div>
                    </div>
                               
                <?php
                $i++;
            } // End foreach ($new_members as $member)
        else :
            printf( __( '<div class="lm-members-not-found">%1$s<span class="lm-pagenot-found-pagetitle">%2$s</span>%3$s</div>', 'love-match' ), 'No More','Members', 'Available' );
        endif;
