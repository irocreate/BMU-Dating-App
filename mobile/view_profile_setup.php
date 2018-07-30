<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author -www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$current_user = wp_get_current_user();
$session_id = $current_user->ID;
$site = get_option('siteurl') . '/?pid=1';

$review_date = date("Y-m-d ");
$dsp_counter_hits_table = $wpdb->prefix . DSP_COUNTER_HITS_TABLE;
$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;
if (($user_id != $member_id)) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_counter_hits_table WHERE user_id=$user_id AND member_id=$member_id AND review_date='$review_date'");

    if (($count <= 0) && ($session_id != 0)) {
        $wpdb->query("INSERT INTO $dsp_counter_hits_table SET user_id=$user_id, member_id=$member_id, review_date='$review_date' ");
    }
}
$check_exist_profile_details = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE status_id=1 AND user_id = '$member_id'");
if ($check_exist_profile_details > 0) {
    $exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE status_id=1 AND user_id = '$member_id'");
    //echo "SELECT * FROM $dsp_user_profiles WHERE status_id=1 AND user_id = '$member_id'";
    // ------------------------------------START BLOCKED MEMBER -------------------------------------//


    if (isset($_POST['block_event'])) {
        $blocked_event = $_POST['block_event'];
    } else {
        $blocked_event = "";
    }
    if (($blocked_event == "blocked") && ($user_id != $member_id) && ($user_id != "")) {

        $check_block_mem_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blocked_members_table WHERE block_member_id='$member_id' AND user_id='$user_id'");

        if ($check_block_mem_exist <= 0) {

            $wpdb->query("INSERT INTO $dsp_blocked_members_table SET user_id = '$user_id',block_member_id ='$member_id'");

            $msg_blocked = language_code('DSP_MEMBER_BLOCKED_MESSAGE');
        } else {

            if ($user_id != "") {

                $msg_blocked = language_code('DSP_EXIST_IN_BLOCK_LIST_MSG');
            }
        }
    }
    if (isset($msg_blocked)) {
        ?>
        <div style="color:#FF0000;" align="left"><strong><?php echo $msg_blocked ?></strong></div>
        <?php
    }
// ------------------------------------END  BLOCKED MEMBER -------------------------------------//
// ----------------------------------Check member privacy Settings if every on esee his profile ------------------------------------
    $check_user_privacy_settings = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE view_my_profile='Y' AND user_id='$member_id'");
    $check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'");
    if (($check_user_privacy_settings > 0) && ($user_id != $member_id)) {  // check user privacy settings
//echo 'ff';
        if ($check_my_friends_list <= 0) {   // check member is not in my friend list	
            ?>

            <div class="dsp_guest_home_page_wrap">

                <div align="left"><?php echo language_code('DSP_NOT_MEMBER_FRIEND_MESSAGE'); ?></div>

            </div>

            <?php
        } else {   // -----------------------------else Check member is in my friend list ---------------------------- //
            $user_name = $wpdb->get_var("SELECT display_name FROM $DSP_USERS_TABLE WHERE ID=$member_id ");

            $dsp_album_id = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = $member_id");



            foreach ($dsp_album_id as $id) {
                $album_ids[] = $id->album_id;
            }
            if ($album_ids != "") {
                $ids1 = implode(",", $album_ids);
            }

            $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_id'");
            $favt_mem = array();
            foreach ($private_mem as $private) {
                $favt_mem[] = $private->favourite_user_id;
            }
// MEMBER TOTAL ADDED PHOTOS
            /*
              $total_member_photos = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_galleries_photos WHERE status_id=1 AND album_id IN ($ids1)");
              if($total_member_photos=="")
              {
              $total_member_photos=0;
              }
             */
// MEMBER TOTAL ADDED PHOTOS
// ----------------------------- START  GENERAL ----------------------------------------// 
            ?>
            <div class="dsp_guest_home_page_wrap">
                <div class="dsp_mb_header"><?php echo DSP_PROFILE_VIEW ?></div><br>
                <table border="0" cellpadding="3px" cellspacing="0px" width="100%" >
                    <tr><td class="dsp_mb_sm_header"><?php echo $user_name ?></td></tr>
                    <tr>
                        <td valign="top" width="10%">
                            <table  border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                    <td><?php
                                        if ($exist_profile_details->make_private == 'Y') {
                                            if ($current_user->ID != $member_id) {
                                                if (!in_array($current_user->ID, $favt_mem)) {
                                                    ?>

                                                    <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:100px;" border="0" class="dsp_img3" />
                                                    <?php
                                                } else {
                                                    ?>

                                                    <img src="<?php echo display_members_photo_mb($member_id, $image_path); ?>" style="width:100px;" border="0" class="dsp_img3" />         

                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <img src="<?php echo display_members_photo_mb($member_id, $image_path); ?>" style="width:100px;" border="0" class="dsp_img3" />               

                                            <?php } ?>
                                            <?php
                                        } else {
                                            ?>

                                            <img src="<?php echo display_members_photo_mb($member_id, $image_path); ?>" style="width:100px;" border="0" class="dsp_img3" />

                                            <?php
                                        }

                                        unset($favt_mem);
                                        ?>

                                                                                                            <!-- <img src="<?php echo display_members_photo_mb($member_id, $image_path); ?>" width="100px" height="100px" border="0" class="dsp_img3" />-->
                                    </td>
                                </tr>
                                <tr><td height="7px"></td></tr>
                            </table>
                        </td>
                        <td width="5%"></td>
                        <td valign="top">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td height="10px"></td>
                                </tr>
                                <tr>
                                    <td >
                                        <table border="0" cellspacing="0" cellpadding="0" width="100%" >
                                            <tr>
                                                <td>
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td width="40%"><?php echo DSP_I_AM ?></td>
                                                            <td width="60%">
                                                                <?php if ($exist_profile_details->gender == 'F') { ?>
                                                                    <?php echo DSP_WOMAN ?>
                                                                <?php } elseif ($exist_profile_details->gender == 'M') { ?>	
                                                                    <?php echo DSP_MAN ?>
                                                                    <?php
                                                                } else {
                                                                    echo DSP_COUPLE;
                                                                }
                                                                ?> 
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="40%"><?php echo DSP_SEEKING_A ?></td>
                                                            <td>
                                                                <?php if ($exist_profile_details->seeking == 'M') { ?>
                                                                    <?php echo DSP_MAN ?>
                                                                <?php } elseif ($exist_profile_details->seeking == 'F') {
                                                                    ?>	
                                                                    <?php echo language_code('DSP_WOMAN'); ?>
                                                                    <?php
                                                                } else {
                                                                    echo DSP_COUPLE;
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="40%"><?php echo DSP_AGE ?></td>
                                                            <td><?php echo GetAge($exist_profile_details->age); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="40%"><?php echo DSP_COUNTRY ?></td>
                                                            <td>
                                                                <?php
                                                                $country = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$exist_profile_details->country_id");
                                                                if (count($country) > 0) {
                                                                    echo $country->name;
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="40%"><?php echo DSP_TEXT_STATE ?></td>
                                                            <td>
                                                                <?php
                                                                $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$exist_profile_details->state_id");
                                                                if (count($state_name) > 0) {
                                                                    echo $state_name->name;
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo DSP_CITY ?></td>
                                                            <td>
                                                                <?php
                                                                //echo $exist_profile_details->city;
                                                                $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$exist_profile_details->city_id");
                                                                if (count($city_name) > 0) {
                                                                    echo $city_name->name;
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><?php echo language_code('DSP_ZIP'); ?></td>
                                                            <td><?php echo $exist_profile_details->zipcode ?></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" height="6px"></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><td colspan="3">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr><td height="5px" colspan="2">&nbsp;</td></tr>
                                            <?php
                                            //echo "SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE A.field_type_id =1 AND B.user_id ='$member_id' ORDER BY A.sort_order";
                                            $exist_profile_options_details1 = $wpdb->get_results("SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE A.field_type_id =1 AND B.user_id ='$member_id' ORDER BY A.sort_order");
                                            foreach ($exist_profile_options_details1 as $profile_qu1) {
                                                $question_name = $profile_qu1->question_name;
                                                $option_value = $profile_qu1->option_value;
                                                ?>
                                                <tr>
                                                    <td width="30%" style="padding-left:2px;" valign="top"><?php echo $question_name ?>:</td>
                                                    <td width="70%" style="word-wrap: break-word;"><?php echo $option_value ?></td>
                                                </tr>
                                            <?php } ?>
                                            <tr><td height="8px" colspan="2"></td></tr>	
                                            <tr>
                                                <td  width="30%" style="padding-left:2px;" valign="top"><?php echo DSP_ABOUT_ME ?>:</td>
                                                <td width="70%" style="word-wrap: break-word;"><?php echo $exist_profile_details->about_me; ?></td>
                                            </tr>
                                            <tr><td height="8px" colspan="2">&nbsp;</td></tr>	

                                            <?php
                                            //echo "SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE A.field_type_id =2 AND B.user_id ='$member_id' ORDER BY A.sort_order";
                                            $exist_profile_options_details2 = $wpdb->get_results("SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE A.field_type_id =2 AND B.user_id ='$member_id' ORDER BY A.sort_order");
                                            foreach ($exist_profile_options_details2 as $profile_qu12) {
                                                $question_name = $profile_qu12->question_name;
                                                $option_value = $profile_qu12->option_value;
                                                ?>
                                                <tr>
                                                    <td width="30%" style="padding-left:2px;" valign="top"><?php echo $question_name ?>:</td>
                                                    <td width="70%" style="word-wrap: break-word;"><?php echo $option_value ?></td>
                                                </tr>
                                            <?php } ?>	
                                            <tr>
                                                <td  width="30%" style="padding-left:2px;" valign="top"><?php echo language_code('DSP_MY_INTEREST'); ?>:</td>
                                                <td width="70%" style="word-wrap: break-word;"><?php echo $exist_profile_details->my_interest; ?></td>
                                            </tr><!--
                                            
                                            <tr>
                                              <td width="30%" style="padding-left:2px;"><?php echo language_code('DSP_MY_INTEREST'); ?>:</td>
                                              <td width="70%">swimming,reading,dancing,playing,chatting,singing,jhjhj,dsfsf</td>
                                            </tr>-->
                                            <tr><td colspan="2">&nbsp;</td></tr>

                                            <?php //********************************************START FAVOURITES ICONS **************************************** //  ?>
                                            <tr>
                                                <td width="30%" style="padding-left:2px;">
                                                    <?php
                                                    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                        if ($check_my_friends_list > 0) {
                                                            ?>
                                                            <a href="<?php
                                                            echo add_query_arg(array(
                                                                'pid' => 14, 'pagetitle' => 'my_email',
                                                                'message_template' => 'compose',
                                                                'frnd_id' => $member_id,
                                                                'Act' => 'send_msg'), $root_link);
                                                            ?>" title="<?php echo DSP_SEND_EMAIL ?>">
                                                                <img src="<?php echo $mb_image_path ?>senemail.png" border="0" /></a>&nbsp;
                                                            <a style="text-decoration: underline;color:black" href="<?php
                                                            echo add_query_arg(array(
                                                                'pid' => 14, 'pagetitle' => 'my_email',
                                                                'message_template' => 'compose',
                                                                'frnd_id' => $member_id,
                                                                'Act' => 'send_msg'), $root_link);
                                                            ?>" title="<?php echo DSP_SEND_EMAI ?>"> <?php echo DSP_SEND_EMAIL ?></a>
                                                               <?php
                                                           } else {
                                                               ?>
                                                            <a  href="<?php
                                                            echo add_query_arg(array(
                                                                'pid' => 14, 'pagetitle' => 'my_email',
                                                                'message_template' => 'compose',
                                                                'receive_id' => $member_id), $root_link);
                                                            ?>" title="<?php echo DSP_SEND_EMAIL ?>">
                                                                <img src="<?php echo $mb_image_path ?>senemail.png" border="0" /></a>&nbsp;
                                                            <a style="text-decoration: underline;color:black" href="<?php
                                                            echo add_query_arg(array(
                                                                'pid' => 14, 'pagetitle' => 'my_email',
                                                                'message_template' => 'compose',
                                                                'receive_id' => $member_id), $root_link);
                                                            ?>" title="<?php echo DSP_SEND_EMAIL ?>"> <?php echo DSP_SEND_EMAIL ?></a>
                                                               <?php
                                                           } //if($check_my_friends_list>0)  
                                                       } else {
                                                           ?>

                                                        <a href="<?php
                                                        echo add_query_arg(array(
                                                            'pgurl' => 'register'), $root_link);
                                                        ?>" title="Login">  <img src="<?php echo $mb_image_path ?>senemail.png" border="0" /></a>&nbsp;
                                                        <a href="<?php
                                                        echo add_query_arg(array(
                                                            'pgurl' => 'register'), $root_link);
                                                        ?>" title="Login"><?php echo DSP_SEND_EMAIL ?></a>
                                                       <?php } ?>
                                                </td>
                                                <td nowrap="nowrap" width="70%" style="padding-left:2px;"> 
                                                    <?php
                                                    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN                  
                                                        ?>
                                                        <a href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 7, 'user_id' => $user_id,
                                                            'fav_userid' => $member_id,
                                                            'profile' => true), $root_link);
                                                        ?>" title="<?php echo DSP_ADD_FAVORITES ?>">
                                                            <img src="<?php echo $mb_image_path ?>add_fav.png" border="0" /></a>&nbsp;
                                                        <a style="text-decoration: underline;color:black"  href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 7, 'user_id' => $user_id,
                                                            'fav_userid' => $member_id,
                                                            'profile' => true), $root_link);
                                                        ?>" title="<?php echo DSP_ADD_FAVORITES ?>">
                                                            <?php echo DSP_ADD_FAVORITES ?></a>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a href="<?php
                                                        echo add_query_arg(array(
                                                            'pgurl' => 'register'), $root_link);
                                                        ?>" title="Login">

                                                            <img src="<?php echo $mb_image_path ?>add_fav.png" border="0" /></a>&nbsp;
                                                        <a style="text-decoration: underline;color:black" href="<?php
                                                        echo add_query_arg(array(
                                                            'pgurl' => 'register'), $root_link);
                                                        ?>" title="Login"><?php echo DSP_ADD_FAVORITES ?></a>
                                                       <?php } ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td></tr>
                </table>
            </div>
            <?php
        }   // ------------------------------------------------- End if Check in my friend list --------------------------------- //
    } else {
// -------------------------------------- else  Privacy Setting for Everyone ------------------------------------------- // 
        $dsp_album_id = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = $member_id");
        $album_ids = array();
        foreach ($dsp_album_id as $id) {
            $album_ids[] = $id->album_id;
        }
        if (count($album_ids) > 0) {
            $ids1 = implode(",", $album_ids);
        }
        $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_id'");
        $favt_mem = array();
        foreach ($private_mem as $private) {
            $favt_mem[] = $private->favourite_user_id;
        }
        // ----------------------------- START  GENERAL ----------------------------------------// 
        ?>
        <div class="dsp_guest_home_page_wrap">
            <?php
            $user_name = $wpdb->get_var("SELECT display_name FROM $DSP_USERS_TABLE WHERE ID=$member_id ");
            if (isset($_GET['Action'])) {
                $Action = $_GET['Action'];
            } else {
                $Action = "";
            }
            if (isset($_GET['mid'])) {
                $mem_id = $_GET['mid'];
            } else {
                $mem_id = "";
            }

            $users_table = $wpdb->prefix . DSP_USERS_TABLE;
            $report_member_table = $wpdb->get_results("SELECT * FROM $users_table WHERE ID=$mem_id ");
            foreach ($report_member_table as $report_member) {
                $mem_id = $report_member->ID;
                $mem_login = $report_member->user_login;
                $mem_email = $report_member->user_email;
                $email = $wpdb->get_row("SELECT * FROM $users_table WHERE ID='$session_id'");
                $user_email = $email->user_email;
                $admin_email = get_option('admin_email');
                $from = $user_email;
                $headers = DSP_FROM . $from . "\r\n";
                $subject = "Report profile";
                $message = "report";
                wp_mail($admin_email, $subject, $message, $headers);
            }
            ?>
            <div class="dsp_mb_header"><?php echo DSP_PROFILE_VIEW ?></div><br>
            <table border="0" cellpadding="3px" cellspacing="0px" width="100%" >
                <tr><td class="dsp_mb_sm_header"><?php echo $user_name ?></td></tr>
                <tr>
                    <td valign="top" width="10%">
                        <table  border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <td>
                                    <?php
                                    if ($exist_profile_details->make_private == 'Y') {
                                        if ($current_user->ID != $member_id) {
                                            if (!in_array($current_user->ID, $favt_mem)) {
                                                ?>

                                                <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:100px;" border="0" class="dsp_img3" />
                                                <?php
                                            } else {
                                                ?>

                                                <img src="<?php echo display_members_photo_mb($member_id, $image_path); ?>" style="width:100px;" border="0" class="dsp_img3" />         

                                                <?php
                                            }
                                        } else {
                                            ?>

                                            <img src="<?php echo display_members_photo_mb($member_id, $image_path); ?>" style="width:100px; " border="0" class="dsp_img3" />               

                                        <?php } ?>
                                        <?php
                                    } else {
                                        ?>

                                        <img src="<?php echo display_members_photo_mb($member_id, $image_path); ?>" style="width:100px;" border="0" class="dsp_img3" />

                                        <?php
                                    }

                                    unset($favt_mem);
                                    ?>
                                                                           <!-- <img src="<?php echo display_members_photo_mb($member_id, $image_path); ?>" width="100px" height="100px" border="0" class="dsp_img3" />-->

                                </td>
                            </tr>
                            <tr><td height="7px"></td></tr>
                        </table>
                    </td>
                    <td  width="5%" ></td>
                    <td valign="top" >
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <table border="0" cellspacing="0" cellpadding="0" width="100%" >
                                        <tr>
                                            <td>
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td width="40%"><?php echo language_code('DSP_I_AM'); ?></td>
                                                        <td width="60%">
                                                            <?php if ($exist_profile_details->gender == 'F') { ?>
                                                                <?php echo language_code('DSP_WOMAN'); ?>
                                                            <?php } elseif ($exist_profile_details->gender == 'M') { ?>	
                                                                <?php echo DSP_MAN ?>
                                                                <?php
                                                            } else {
                                                                echo DSP_COUPLE;
                                                            }
                                                            ?> 
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%"><?php echo language_code('DSP_SEEKING_A'); ?></td>
                                                        <td>
                                                            <?php if ($exist_profile_details->seeking == 'M') { ?>
                                                                <?php echo language_code('DSP_MAN'); ?>
                                                            <?php } elseif ($exist_profile_details->seeking == 'F') {
                                                                ?>	
                                                                <?php echo language_code('DSP_WOMAN'); ?>
                                                                <?php
                                                            } else {
                                                                echo DSP_COUPLE;
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%"><?php echo language_code('DSP_AGE'); ?></td>
                                                        <td><?php echo GetAge($exist_profile_details->age); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%"><?php echo language_code('DSP_COUNTRY'); ?></td>
                                                        <td>
                                                            <?php
                                                            $country = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$exist_profile_details->country_id");
                                                            if (count($country) > 0) {
                                                                echo $country->name;
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%"><?php echo language_code('DSP_TEXT_STATE'); ?></td>
                                                        <td>
                                                            <?php
                                                            $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$exist_profile_details->state_id");
                                                            if (count($state_name) > 0) {
                                                                echo $state_name->name;
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="40%"><?php echo language_code('DSP_CITY'); ?></td>
                                                        <td>
                                                            <?php
                                                            // echo $exist_profile_details->city;
                                                            $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$exist_profile_details->city_id");
                                                            if (count($city_name) > 0) {
                                                                echo $city_name->name;
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php if ($check_zipcode_mode->setting_status == 'Y') { ?>
                                                        <tr>
                                                            <td width="40%"><?php echo language_code('DSP_ZIP'); ?></td>
                                                            <td><?php echo $exist_profile_details->zipcode ?></td>
                                                        </tr>
                                                    <?php } ?>	
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" height="6px"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td colspan="3">
                        <table width="100%">
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr>
                                            <td height="5px" colspan="2"></td>
                                        </tr>
                                        <?php
                                        $exist_profile_options_details1 = $wpdb->get_results("SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE A.field_type_id =1 AND B.user_id ='$member_id' ORDER BY A.sort_order");
                                        foreach ($exist_profile_options_details1 as $profile_qu1) {
                                            $question_name = $profile_qu1->question_name;
                                            $option_value = $profile_qu1->option_value;
                                            ?>
                                            <tr>
                                                <td width="30%" style="padding-left:2px;" valign="top"><?php echo $question_name ?>:</td>
                                                <td width="70%" style="word-wrap: break-word;"><?php echo $option_value ?></td>
                                            </tr>
                                        <?php } ?>
                                        <tr><td height="8px" colspan="2"></td></tr>	
                                        <tr>
                                            <td  width="30%" style="padding-left:2px;" valign="top"><?php echo language_code('DSP_ABOUT_ME'); ?>:</td>
                                            <td width="70%" style="word-wrap: break-word;"><?php echo $exist_profile_details->about_me; ?></td>
                                        </tr>
                                        <tr><td height="8px" colspan="2">&nbsp;</td></tr>	
                                        <?php
                                        $exist_profile_options_details2 = $wpdb->get_results("SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE A.field_type_id =2 AND B.user_id ='$member_id' ORDER BY A.sort_order");
                                        foreach ($exist_profile_options_details2 as $profile_qu12) {
                                            $question_name = $profile_qu12->question_name;
                                            $option_value = $profile_qu12->option_value;
                                            ?>
                                            <tr>
                                                <td width="30%" style="padding-left:2px;"  valign="top"><?php echo $question_name ?>:</td>
                                                <td width="70%" style="word-wrap: break-word;"><?php echo $option_value ?></td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td  width="30%" style="padding-left:2px; " valign="top"><?php echo language_code('DSP_MY_INTEREST'); ?>:</td>
                                            <td width="70%" style="word-wrap: break-word;"><?php echo $exist_profile_details->my_interest; ?></td>
                                        </tr>
                                        <tr><td  colspan="2">&nbsp;</td></tr>	
                                        <!-----------------Favorite Icons------------------------->
                                        <tr>
                                            <td width="30%">  
                                                <?php
                                                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                    if ($check_my_friends_list > 0) {
                                                        ?>
                                                        <a href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 14,
                                                            'pagetitle' => 'my_email',
                                                            'message_template' => 'compose',
                                                            'frnd_id' => $member_id,
                                                            'Act' => 'send_msg'), $root_link);
                                                        ?>" title="<?php echo DSP_SEND_EMAIL ?>">
                                                            <img src="<?php echo $mb_image_path ?>senemail.png" border="0" /></a>&nbsp;
                                                        <a style="text-decoration: underline;color:black" href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 14, 'pagetitle' => 'my_email',
                                                            'message_template' => 'compose',
                                                            'frnd_id' => $member_id,
                                                            'Act' => 'send_msg'), $root_link);
                                                        ?>" title="<?php echo DSP_SEND_EMAIL ?>"> <?php echo DSP_SEND_EMAIL ?></a>
                                                       <?php } else { ?>
                                                        <a href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 14, 'pagetitle' => 'my_email',
                                                            'message_template' => 'compose',
                                                            'receive_id' => $member_id), $root_link);
                                                        ?>" title="<?php echo DSP_SEND_EMAIL ?>">
                                                            <img src="<?php echo $mb_image_path ?>senemail.png" border="0" /></a>&nbsp;
                                                        <a style="text-decoration: underline;color:black" href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 14, 'pagetitle' => 'my_email',
                                                            'message_template' => 'compose',
                                                            'receive_id' => $member_id), $root_link);
                                                        ?>" title="<?php echo DSP_SEND_EMAIL ?>"> <?php echo DSP_SEND_EMAIL ?></a>
                                                       <?php } //if($check_my_friends_list>0)   ?>
                                                   <?php } else { ?>
                                                    <a href="<?php
                                                    echo add_query_arg(array(
                                                        'pgurl' => 'register'), $root_link);
                                                    ?>" title="Login">  <img src="<?php echo $mb_image_path ?>senemail.png" border="0" /></a>&nbsp;<a href="<?php
                                                       echo add_query_arg(array(
                                                           'pgurl' => 'register'), $root_link);
                                                       ?>" title="Login"><?php echo DSP_SEND_EMAIL ?></a>
                                                       <?php } ?>
                                            </td>
                                            <td width="70%" style="padding-left:2px; vertical-align:top;" nowrap="nowrap">
                                                <?php
                                                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                    ?>
                                                    <a  href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 7, 'user_id' => $user_id,
                                                        'fav_userid' => $member_id,
                                                        'profile' => true), $root_link);
                                                    ?>" title="<?php echo DSP_ADD_FAVORITES ?>">
                                                        <img src="<?php echo $mb_image_path ?>add_fav.png" border="0" /></a>&nbsp;
                                                    <a style="text-decoration: underline;color:black" href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 7,
                                                        'user_id' => $user_id,
                                                        'fav_userid' => $member_id,
                                                        'profile' => true), $root_link);
                                                    ?>" title="<?php echo DSP_ADD_FAVORITES ?>">
                                                        <?php echo DSP_ADD_FAVORITES ?></a>
                                                <?php } else { ?>
                                                    <a style="text-decoration: underline;color:black" href="<?php
                                                       echo add_query_arg(array(
                                                           'pgurl' => 'register'), $root_link);
                                                       ?>" title="Login">
                                                        <img src="<?php echo $mb_image_path ?>add_fav.png" border="0" /></a>&nbsp;<a href="<?php
                                                    echo add_query_arg(array(
                                                        'pgurl' => 'register'), $root_link);
                                                    ?>" title="Login"><?php echo DSP_ADD_FAVORITES ?></a>
                                                                                                                                 <?php } ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <?php //********************************************START FAVOURITES ICONS **************************************** //     ?>
                        </table>
                    </td></tr>
            </table>
        </div>
    <?php }   // ------------------------------------------------- End if Check Privacy Settings --------------------------------- //   ?>
    <?php //----------------------------------------------- END PROFILE QUESTIONS -----------------------------------------------     ?>
    <?php
} else {
    $profile_status = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member_id'");
    $pstatus = $profile_status->status_id;
    if (($pstatus == 2) || ($pstatus == 3)) {
        $profile_deleted = $profile_status->reason_for_status;
    }
    ?>
    <div  >

        <?php if ($member_id == $user_id) { ?>
            <div align="center"><?php echo language_code('DSP_ADMIN_DELETE_PROFILE_MESSAGE'); ?>&nbsp;<?php echo $profile_deleted ?></div>
        <?php } else { ?>
            <div align="center"><?php echo language_code('DSP_NO_PROFILE_EXISTS_MESSAGE'); ?></div>
        <?php } ?>

    </div>
<?php } ?>