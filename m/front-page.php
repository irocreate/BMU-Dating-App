<?php
/**

 * The template for displaying front page pages.

 *

 */
?>

<?php get_header(); ?>  

<div class="page-content">

    <div class="grid_16 alpha">

        <div class="content-bar sample">

            <div class="register"> <a href="members/?pgurl=register">Click Here to Register for Free</a> </div>

<!--<p><img class="size-full wp-image-145 aligncenter" alt="main dating profiles" src="http://romanceontheblock.com/wp-content/uploads/2013/04/main-dating-profiles.png" width="100%" style="margin-left:0px;"></p>-->


            <?php
            global $wpdb;

            $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
            $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
            $dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;

            include_once("wp-content/plugins/dsp_dating/files/includes/table_names.php");

            include_once("wp-content/plugins/dsp_dating/dspFunction.php");

            include_once("wp-content/plugins/dsp_dating/general_settings.php");



            $check_member_list_gender_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'member_list_gender'");

            $member_list_gender = $check_member_list_gender_mode->setting_value;

            $root_link = get_bloginfo('url') . '/member/';

            $page_name = $root_link . "?pgurl=ALL";





            if ($member_list_gender == 2) {

                $member_gender = 'M';
            } else if ($member_list_gender == 3) {

                $member_gender = 'F';
            } else
                $member_gender = '';
            ?>



            <div class="profile-page">
                <ul>

                    <?php
                    if ($member_gender != '') {

                        if ($check_couples_mode->setting_status == 'Y') {
                            $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id=1  AND gender='$member_gender' AND country_id!=0 and stealth_mode='N'AND user_id >36
		AND user_id <67 Order By user_profile_id DESC LIMIT 30");
                        } else {
                            $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id=1  AND gender='$member_gender' AND country_id!=0 AND gender!='C'AND user_id >36
		AND user_id <67 and stealth_mode='N' Order By user_profile_id DESC LIMIT 30");
                        }
                    } else {
                        if ($check_couples_mode->setting_status == 'Y') {

                            $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id=1 AND country_id!=0 and stealth_mode='N'AND user_id >36
		AND user_id <67 Order By user_profile_id DESC LIMIT 30");
                        } else {
                            $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id=1 AND country_id!=0 AND gender!='C' and stealth_mode='N'AND user_id >36
		AND user_id <67 Order By user_profile_id DESC LIMIT 30");
                        }
                    }


                    $i = 0;

                    foreach ($new_members as $member) {



                        $exist_user_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$member->user_id'");



                        $user_name = $exist_user_name->display_name;



                        $exist_user_id = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$member->user_id'");



                        if (($i % 4) == 0) {
                            ?>



                        <?php } // End if(($i%4)==0)  ?>



                        <li>

                            <?php
                            if ($check_couples_mode->setting_status == 'Y') {

                                if ($member->gender == 'C') {
                                    ?>

                                    <?php if ($exist_user_id->make_private == 'Y') {
                                        ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pgurl' => 'view_member',
                                            'guest_pageurl' => 'view_mem_profile',
                                            'mem_id' => $member->user_id,
                                            'view' => 'my_profile'), $root_link);
                                        ?>">
                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:120px; height:120px;" border="0" class="dsp_img3" /></a>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pgurl' => 'view_member',
                                            'guest_pageurl' => 'view_mem_profile',
                                            'mem_id' => $member->user_id, 'view' => 'my_profile'), $root_link);
                                        ?>">
                                            <img src="<?php echo display_members_photo($member->user_id, $imagepath); ?>" style="width:120px; height:120px;"  class="dsp_img3"/></a>
                                    <?php } ?>



                                <?php } else { ?>



                                    <?php if ($exist_user_id->make_private == 'Y') { ?>

                                        <a href="<?php
                                        echo add_query_arg(array('pgurl' => 'view_member',
                                            'guest_pageurl' => 'view_mem_profile',
                                            'mem_id' => $member->user_id), $root_link);
                                        ?>">

                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:120px; height:120px;" border="0" class="dsp_img3" /></a>

                                    <?php } else { ?>

                                        <a href="<?php
                                        echo add_query_arg(array('pgurl' => 'view_member',
                                            'guest_pageurl' => 'view_mem_profile',
                                            'mem_id' => $member->user_id), $root_link);
                                        ?>">

                                            <img src="<?php echo display_members_photo($member->user_id, $imagepath); ?>"   style="width:120px; height:120px;"  class="dsp_img3"/></a>

                                    <?php } ?>



                                    <?php
                                }
                            } else {
                                ?> 



                                <?php if ($exist_user_id->make_private == 'Y') { ?>

                                    <a href="<?php
                                    echo add_query_arg(array('pgurl' => 'view_member',
                                        'guest_pageurl' => 'view_mem_profile', 'mem_id' => $member->user_id), $root_link);
                                    ?>">

                                        <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:120px; height:120px;" border="0" class="dsp_img3" /></a>

                                <?php } else { ?>

                                    <a href="<?php
                                    echo add_query_arg(array('pgurl' => 'view_member',
                                        'guest_pageurl' => 'view_mem_profile', 'mem_id' => $member->user_id), $root_link);
                                    ?>">

                                        <img src="<?php echo display_members_photo($member->user_id, $imagepath); ?>"   style="width:120px; height:120px;"  class="dsp_img3"/></a>

                                <?php } ?>





                            <?php } ?>





                        </li>


                        <?php
                        $i++;
                    } // End foreach ($new_members as $member)
                    ?>

                </ul>
            </div>


        </div>

    </div>


    <div class="grid_8 omega">
        <?php get_sidebar(); ?>
    </div>


    <?php get_footer(); ?>

</div>