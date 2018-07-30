<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
include_once(WP_DSP_ABSPATH . "files/includes/dsp_mail_function.php");
if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
$dsp_admin_emails_table = $wpdb->prefix . DSP_ADMIN_EMAILS;
$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
$dsp_member_videos_table = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
$dsp_member_audios_table = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
$dsp_profile_question_details_table = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
$dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
$dsp_partner_profile_question_details_table = $wpdb->prefix . DSP_PARTNER_PROFILE_QUESTIONS_DETAILS;
$dsp_show_profile_table = $wpdb->prefix . DSP_LIMIT_PROFILE_VIEW_TABLE;
$update_profile_status = isset($_REQUEST['update_profile_status']) ? $_REQUEST['update_profile_status'] : '';
$Delete_profile = isset($_REQUEST['Delete_profile']) ? $_REQUEST['Delete_profile'] : '';
$status_id = isset($_REQUEST['status_id']) ? $_REQUEST['status_id'] : '';

$messae_send_date = date('Y-m-d H:i:s');
$request_url = get_bloginfo('url') . "/wp-admin/admin.php?page=dsp-admin-sub-page2";
$edit_image = WPDATE_URL . "/images/icon_edit.gif";

function remove_image($image)
{
    $image_files = scandir($image);
    chdir($image);
    foreach ($image_files as $image_file) {
        if ($image_file != "." and $image_file != "..") {
            if (is_dir($image_file)) {
                remove_image($image_file);
            } else {
                unlink($image_file);
            }//"""""""end of if
        }//""""""-end of if
    }//"""""-end of foreach
    chdir("..");
    rmdir($image);
}

//"""""end of function

function remove_video($name)
{
    $ars = scandir($name);
    chdir($name);
    foreach ($ars as $ar) {
        if ($ar != "." and $ar != "..") {
            if (is_dir($ar)) {
                //echo "found directory $ar <br />";
                remove_dir($ar);
            } else {
                //echo "found a file $ar <br />";
                unlink($ar);
            }//"""""""end of if
        }//""""""-end of if
    }//"""""-end of foreach
    chdir("..");
    rmdir($name);
}

//"""""end of function

function remove_audio($audio)
{
    $audio_files = scandir($audio);
    chdir($audio);
    foreach ($audio_files as $audio_file) {
        if ($audio_file != "." and $audio_file != "..") {
            if (is_dir($audio_file)) {
                //echo "found directory $ar <br />";
                remove_dir($audio_file);
            } else {
                //echo "found a file $ar <br />";
                unlink($audio_file);
            }//"""""""end of if
        }//""""""-end of if
    }//"""""-end of foreach
    chdir("..");
    rmdir($audio);
}

//"""""end of function
// $status_id ==2 (Reject), $status_id ==3 (Delete), $status_id ==5 (Delete Permanently)
if (isset($_POST['submit'])) {
    if (($update_profile_status != "") && ($status_id != 0)) {
        for ($intCounter = 0; $intCounter <= count($_POST["update_profile_status"]) - 1; $intCounter++) {
            $profileid = $_POST["update_profile_status"][$intCounter];
            $profile_user_id = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_profile_id='$profileid'");
            $userId = $profile_user_id->user_id;
            $userRole = array_keys(get_user_meta($userId, 'wp_capabilities', true));

            if (($status_id == 1) || ($status_id == 2) || ($status_id == 3)) {

                if ($status_id == 1) {
                    $mail_template_id = 3;
                } else if ($status_id == 2) {
                    $mail_template_id = 4;
                } else if ($status_id == 3) {
                    $mail_template_id = 4;
                }
                $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='$mail_template_id'");

                $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$profile_user_id->user_id'");
                $reciver_name = $reciver_details->display_name;
                $receiver_email_address = $reciver_details->user_email;
                $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='1'");
                $sender_name = $sender_details->display_name;
                //$site_url=$wpdb->get_row("SELECT * FROM wp_options WHERE option_name='siteurl'");
                // $sender_email_address=$site_url->option_value;
                // $sender_email_address = str_replace ("http://", '', $sender_email_address);
                $root_url = get_bloginfo('url');
                //$url=add_query_arg( array('pid' =>3), $root_url);
                $url = site_url();
                $email_subject = $email_template->subject;
                $mem_email_subject = $email_subject;

                $email_message = $email_template->email_body;
                $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
                $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
                $email_message = str_replace("<#URL#>", $url, $email_message);

                $MemberEmailMessage = $email_message;
                $admin_email = get_option('admin_email');
                $from = $admin_email;
                // wp_mail($receiver_email_address, $mem_email_subject, $mem_email_subject );
                $wpdating_email  = Wpdating_email_template::get_instance();
                $result = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
                $wpdb->query("INSERT INTO $dsp_admin_emails_table SET rec_user_id='$profile_user_id->user_id',email_template_id='$mail_template_id', message='$MemberEmailMessage',mail_sent_date='$messae_send_date'");

                $wpdb->query("UPDATE $dsp_user_profiles SET status_id='$status_id' WHERE user_profile_id = '$profileid'");
            } // if(($status_id==1) || ($status_id==2))
            if ($status_id == 5) {

                $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='4'");

                $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$profile_user_id->user_id'");
                $reciver_name = $reciver_details->display_name;
                $receiver_email_address = $reciver_details->user_email;
                $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='1'");
                $sender_name = $sender_details->display_name;
                //$site_url=$wpdb->get_row("SELECT * FROM wp_options WHERE option_name='siteurl'");
                // $sender_email_address=$site_url->option_value;
                // $sender_email_address = str_replace ("http://", '', $sender_email_address);
                $root_url = get_bloginfo('url');
                //$url=add_query_arg( array('pid' =>3), $root_url);
                $url = site_url();
                $email_subject = $email_template->subject;
                $mem_email_subject = $email_subject;

                $email_message = $email_template->email_body;
                $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
                $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
                $email_message = str_replace("<#URL#>", $url, $email_message);

                $MemberEmailMessage = $email_message;

                // Add User Data to Deleted Users Table //             
                $infoFromUsersTable = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID=$profile_user_id->user_id");
                $infoFromProfilesTable = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id=$profile_user_id->user_id");
                $infoFromMembersPhotoTable = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id=$profile_user_id->user_id");
                $current_datetime = date('Y-m-d H:i:s');
                $wpdb->query("INSERT INTO " . $wpdb->prefix . DSP_DELETED_USERS_TABLE . " SET
                                            user_id='$infoFromProfilesTable->user_id',
                                            user_profile_id='$infoFromProfilesTable->user_profile_id', 
                                            country_id='$infoFromProfilesTable->country_id',
                                            city_id='$infoFromProfilesTable->city_id',
                                            gender='$infoFromProfilesTable->gender',
                                            user_login='$infoFromUsersTable->user_login', 
                                            user_pass='$infoFromUsersTable->user_pass',
                                            user_email='$infoFromUsersTable->user_email',
                                            display_name='$infoFromUsersTable->display_name',
                                            user_registered='$infoFromUsersTable->user_registered', 
                                            deleted_on='$current_datetime',
                                            picture='$infoFromMembersPhotoTable->picture'
                           ");

                // Copy User Profile Picture to Deleted Users Profile Picture //
                $src = WP_CONTENT_DIR . '/uploads/dsp_media' . '/user_photos/user_' . $profile_user_id->user_id . '/';
                $dst = WP_CONTENT_DIR . '/uploads/dsp_media/deleted_users/';
                if (!is_dir($dst)) {
                    mkdir($dst, 0777, true);
                }
                $files = glob($src . "*.*");
                if (!empty($files) && $files != false) {
                    foreach ($files as $file) {
                        $filename = 'user_' . str_replace($src, '', $file);
                        $file_to_go = str_replace($src, $dst, $file);
                        copy($file, $file_to_go);
                    }
                }
                // End Copy              
                // End Add User Data

                //dsp_send_mail($receiver_email_address, $sender_name, $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = ""); 
                //$wpdb->query("INSERT INTO $dsp_admin_emails_table SET rec_user_id='$profile_user_id->user_id',email_template_id='4', message='$MemberEmailMessage',mail_sent_date='$messae_send_date'");
//***************************** Start Delete Video folder ***************************************
                $pluginpath = WP_CONTENT_DIR . '/uploads/dsp_media';
                $name = $pluginpath . '/user_videos/user_' . $profile_user_id->user_id;
                if (file_exists($name)) {
                    remove_video($name);
                    $wpdb->query("DELETE FROM $dsp_member_videos_table WHERE user_id = '$profile_user_id->user_id'");
                }
//***************************** End Delete Video folder ***************************************
//***************************** Start Delete Audio folder ***************************************
                $audio = $pluginpath . '/user_audios/user_' . $profile_user_id->user_id;
                if (file_exists($audio)) {
                    remove_audio($audio);
                    $wpdb->query("DELETE FROM $dsp_member_audios_table WHERE user_id = '$profile_user_id->user_id'");
                }
//***************************** End Delete Audio folder  *************************************** 
//***************************** Start Delete image folder  *************************************** 
                $image = $pluginpath . '/user_photos/user_' . $profile_user_id->user_id;
                if (file_exists($image)) {
                    remove_image($image);
                    $wpdb->query("DELETE FROM $dsp_members_photos WHERE user_id = '$profile_user_id->user_id'");
                }
//***************************** End Delete image folder  *************************************** 
                $wpdb->query("DELETE FROM $dsp_user_profiles WHERE user_profile_id = '$profileid'");
                $wpdb->query("DELETE FROM $dsp_user_albums_table WHERE user_id = '$profile_user_id->user_id'");
                $wpdb->query("DELETE FROM $dsp_galleries_photos WHERE user_id = '$profile_user_id->user_id'");
                if ($userRole[0] != 'administrator')
                    $wpdb->query("DELETE FROM $dsp_user_table WHERE ID = '$profile_user_id->user_id'");
                $wpdb->query("DELETE FROM $dsp_profile_question_details_table WHERE user_id = '$profile_user_id->user_id'");
                $wpdb->query("DELETE FROM $dsp_user_partner_profiles_table WHERE user_id  = '$profile_user_id->user_id'");
                $wpdb->query("DELETE FROM $dsp_members_partner_photos_table WHERE user_id = '$profile_user_id->user_id'");
                $wpdb->query("DELETE FROM $dsp_partner_profile_question_details_table WHERE user_id = '$profile_user_id->user_id'");
                $wpdb->query("DELETE FROM $dsp_partner_profile_question_details_table WHERE user_id = '$profile_user_id->user_id'");
                $wpdb->query("DELETE FROM $dsp_show_profile_table WHERE user_id = '$profile_user_id->user_id'");

                // delete from other tables - start
                $toDeleteTables = array('usermeta',
                    DSP_USER_VIRTUAL_GIFT_TABLE,
                    DSP_MY_BLOGS_TABLE,
                    DSP_MATCH_CRITERIA_TABLE,
                    DSP_MEET_ME_TABLE,
                    DSP_USER_NOTIFICATION_TABLE,
                    DSP_USER_ONLINE_TABLE,
                    DSP_USER_PRIVACY_TABLE,
                    DSP_USER_SEARCH_CRITERIA_TABLE,
                    DSP_NEWS_FEED_TABLE,
                    //DSP_CREDITS_USAGE_TABLE,
                    DSP_DATE_TRACKER_TABLE,
                    DSP_RATING_USER_PROFILE_TABLE,
                    DSP_SKYPE_TABLE,
                    DSP_TEMP_INTEREST_TAGS_TABLE
                );
                foreach ($toDeleteTables as $toDeleteTable) {
                    $wpdb->query("DELETE FROM " . $wpdb->prefix . $toDeleteTable . " WHERE user_id = '$profile_user_id->user_id'");
                }

                $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_MY_FRIENDS_TABLE . " WHERE user_id=$profile_user_id->user_id OR friend_uid=$profile_user_id->user_id");
                $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE . " WHERE user_id=$profile_user_id->user_id OR favourite_user_id=$profile_user_id->user_id");
                $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_COUNTER_HITS_TABLE . " WHERE user_id=$profile_user_id->user_id OR member_id=$profile_user_id->user_id");
                $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_USER_COMMENTS . " WHERE user_id=$profile_user_id->user_id OR member_id=$profile_user_id->user_id");
                $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_NOTIFICATION_TABLE . " WHERE user_id=$profile_user_id->user_id OR member_id=$profile_user_id->user_id");
                $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE . " WHERE user_id=$profile_user_id->user_id OR block_member_id=$profile_user_id->user_id");

                $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_CHAT_ONE_TABLE . " WHERE sender_id=$profile_user_id->user_id OR receiver_id=$profile_user_id->user_id");
                $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_CHAT_REQUEST_TABLE . " WHERE sender_id=$profile_user_id->user_id OR receiver_id=$profile_user_id->user_id");
                $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_MEMBER_WINKS_TABLE . " WHERE sender_id=$profile_user_id->user_id OR receiver_id=$profile_user_id->user_id");
                $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_EMAILS_TABLE . " WHERE sender_id=$profile_user_id->user_id OR receiver_id=$profile_user_id->user_id");
                $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_CHAT_TABLE . " WHERE sender_id=$profile_user_id->user_id");
                //  delete from other tables - end

            } // End if($status_id==5) { 
        } // END loop 
    } // Endif
} // Endif submit 

?>
<?php
//if (isset($_SESSION['message'])){
//    ?>
<!--    <div class="updated">-->
<!--        <p>--><?php //echo  $_SESSION['message'];?><!--</p>-->
<!--    </div>-->
<!--    --><?php
//
//}
//unset($_SESSION['message']);
//}
?>
    <div id="message"><strong><?php if (isset($_SESSION['message'])) echo $_SESSION['message'];
            unset($_SESSION['message']); ?></strong></div>
    <form name="approvalfrm" method="post">
        <table cellpadding="0" cellspacing="0" border="0" class="widefat">
            <tr>
                <th scope="col" class="manage-column"><?php echo language_code('DSP_TITLE_USERNAME') ?></th>
                <th scope="col" class="manage-column"><?php echo language_code('DSP_TITLE_EMAIL_ADDESS') ?></th>
                <th scope="col" class="manage-column"><?php echo language_code('DSP_TITLE_REGISTRATION_DATE') ?></th>
                <th scope="col" class="manage-column"><?php echo language_code('DSP_EDIT') ?></th>
                <th scope="col" class="manage-column"><?php echo language_code('DSP_TITLE_STATUS') ?></th>
            </tr>
            <?php
            $page_name = $root_link . "/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_profiles&dsp_page=$profiles_page_url";
            if (isset($_GET['page1']))
                $page = $_GET['page1'];
            else
                $page = 1;

            // How many adjacent pages should be shown on each side?
            $adjacents = 2;
            $limit = 20;
            if ($page)
                $start = ($page - 1) * $limit;    //first item to display on this page
            else
                $start = 0;
            // -----------------------------------------------Paging code------------------------------------------------------ //
            if (isset($list_status) && $list_status == 1) {
                if (isset($_POST['search'])) {
                    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
                    if ($username != '') {
                        $search_username = $wpdb->get_results("SELECT * FROM $dsp_user_table WHERE user_login like '%$username%'");
                        foreach ($search_username as $username) {
                            $user_id = $username->ID;

                            $total_results1 = $wpdb->get_var("SELECT COUNT(distinct (user_id)) as Num FROM $dsp_user_profiles WHERE status_id='$list_status' AND user_id='$user_id' and country_id!=0");
                        }
                    } else {
                        $total_results1 = $wpdb->get_var("SELECT COUNT(distinct (user_id)) as Num FROM $dsp_user_profiles WHERE status_id='$list_status' and country_id!=0");
                    }
                }   //if(isset($_POST['search']))
                else
                    $total_results1 = $wpdb->get_var("SELECT COUNT(distinct (user_id)) as Num FROM $dsp_user_profiles WHERE status_id='$list_status' and country_id!=0");
            } else {
                $total_results1 = $wpdb->get_var("SELECT COUNT(distinct (user_id)) as Num FROM $dsp_user_profiles  p INNER JOIN $dsp_user_table u ON p.user_id = u.ID  WHERE status_id=0 and country_id!=0");
            }
            //******************************************************************************************************************************************

            if ($page == 0)
                $page = 1;     //if no page var is given, default to 1.
            $prev = $page - 1;
            $next = $page + 1;
            $lastpage = ceil(@$total_results1 / $limit);  //lastpage is = total pages / items per page, rounded up.
            $lpm1 = $lastpage - 1;

            /*
              Now we apply our rules and draw the pagination object.
              We're actually saving the code to a variable in case we want to draw it more than once.
             */
            $pagination = "";
            if ($lastpage > 1) {
                $pagination .= "<div class='wpse_pagination'>";
                //previous button
                if ($page > 1)
                    $pagination .= "<div><a style='color:#474545' href=\"" . $page_name . "&page1=$prev\">previous</a></div>";
                else
                    $pagination .= "<span  class='disabled'>previous</span>";

                //pages
                if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
                    for ($counter = 1; $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<span class='current'>$counter</span>";
                        else
                            $pagination .= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
                    }
                } elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
                    //close to beginning; only hide later pages
                    if ($page < 1 + ($adjacents * 2)) {
                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                            if ($counter == $page)
                                $pagination .= "<span class='current'>$counter</span>";
                            else
                                $pagination .= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
                        }
                        $pagination .= "<span>...</span>";
                        $pagination .= "<div><a href=\"" . $page_name . "&page1=$lpm1\">$lpm1</a></div>";
                        $pagination .= "<div><a href=\"" . $page_name . "&page1=$lastpage\">$lastpage</a></div>";
                    } //in middle; hide some front and some back
                    elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                        $pagination .= "<div><a href=\"" . $page_name . "&page1=1\">1</a></div>";
                        $pagination .= "<div><a href=\"" . $page_name . "&page1=2\">2</a></div>";
                        $pagination .= "<span>...</span>";
                        for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                            if ($counter == $page)
                                $pagination .= "<div class='current'>$counter</div>";
                            else
                                $pagination .= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
                        }
                        $pagination .= "<span>...</span>";
                        $pagination .= "<div><a href=\"" . $page_name . "&page1=$lpm1\">$lpm1</a></div>";
                        $pagination .= "<div><a href=\"" . $page_name . "&page1=$lastpage\">$lastpage</a></div>";
                    } //close to end; only hide early pages
                    else {
                        $pagination .= "<div><a href=\"" . $page_name . "&page1=1\">1</a></div>";
                        $pagination .= "<div><a href=\"" . $page_name . "&page1=2\">2</a></div>";
                        $pagination .= "<span>...</span>";
                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                            if ($counter == $page)
                                $pagination .= "<span class='current'>$counter</span>";
                            else
                                $pagination .= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
                        }
                    }
                }

                //next button
                if ($page < $counter - 1)
                    $pagination .= "<div><a style='color:#474545' href=\"" . $page_name . "&page1=$next\">next</a></div>";
                else
                    $pagination .= "<span class='disabled'>next</span>";
                $pagination .= "</div>\n";
            }

            if (isset($_POST['search'])) {
                $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
                $search_username = $wpdb->get_results("SELECT * FROM $dsp_user_table WHERE user_login like '%$username%'");
                foreach ($search_username as $username) {
                    $user_id = $username->ID;
                    $users_profile_list = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id='$list_status' AND user_id='$user_id' and country_id!=0  group by user_id order by last_update_date desc  LIMIT $start, $limit");
                    $i = 0;
                    foreach ($users_profile_list as $user_profile) {

                        if (isset($i) && $i % 2 != 0) {
                            $class = "";
                        } else {
                            $class = "class='alternate'";
                        }
                        $i++;
                        $profile_id = $user_profile->user_profile_id;
                        if ($profile_id != 1) {
                            $member = $wpdb->get_row("SELECT * FROM $dsp_user_table Where ID='$user_profile->user_id'");
                            $bgColor = (@$bgColor == '#FFFFFF') ? '#f1f1f1' : '#FFFFFF';

                            echo "<tr $class >";
                            ?>
                        <td><a href="<?php
                            echo add_query_arg(array('pid' => 'media_profile_view',
                                'mode' => 'edit', 'profile_id' => $profile_id), $request_url);
                            ?>"><?php echo $member->user_login ?></a></div></td>
                        <td><?php echo $member->user_email ?></div></td>
                        <td><?php echo $user_profile->last_update_date ?></div></td> 
                        <td><a href="<?php
                            echo add_query_arg(array('pid' => 'media_profile_view',
                                'mode' => 'edit', 'profile_id' => $profile_id), $request_url);
                            ?>"><img src="<?php echo $edit_image ?>" alt="<?php echo $member->user_login ?>"/></a></div></td>
                        <td colspan="2"><input type="checkbox" name="update_profile_status[]" value="<?php echo $profile_id ?>" /></div></td>
                        </tr>
                        <?php
                        }
                    }
                } //if($_POST['search'])
            } else {
                $list_status = isset($list_status) ? $list_status : 0;
                $users_profile_list = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id='$list_status' and country_id!=0  group by user_id order by last_update_date desc  LIMIT $start, $limit");
                $i = 0;
                foreach ($users_profile_list as $user_profile) {

                    if (isset($i) && $i % 2 != 0) {
                        $class = "";
                    } else {
                        $class = "class='alternate'";
                    }
                    $i++;
                    $profile_id = $user_profile->user_profile_id;
                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_table Where ID='$user_profile->user_id'");
                    $chk_user_exist = $wpdb->get_var("SELECT count(*) FROM $dsp_user_table Where ID='$user_profile->user_id'");
                    $bgColor = (@$bgColor == '#FFFFFF') ? '#f1f1f1' : '#FFFFFF';
                    if ($chk_user_exist != 0) {
                        echo "<tr $class >";
                        ?>
                    <td><a href="<?php
                        echo add_query_arg(array('pid' => 'media_profile_view',
                            'mode' => 'edit', 'profile_id' => $profile_id), $request_url);
                        ?>"><?php echo $member->user_login ?></a></div></td>
                    <td><?php echo $member->user_email ?></div></td>
                    <td><?php echo $user_profile->last_update_date ?></div></td> 
                    <td><a href="<?php
                        echo add_query_arg(array('pid' => 'media_profile_view',
                            'mode' => 'edit', 'profile_id' => $profile_id), $request_url);
                        ?>"><img src="<?php echo $edit_image ?>" alt="<?php echo $member->user_login ?>"/></a></div></td>
                    <td colspan="2"><input type="checkbox" name="update_profile_status[]" value="<?php echo $profile_id ?>" /></div></td>
                    </tr>
                    <?php
                    }
                }
            }   //if( isset($_POST['submit']))
            ?>
        </table>
        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:10px;margin-bottom:10px;">
            <tr>
                <td width="100%">
                    <div class="paging-box-withbtn">
                        <?php
                        // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                        echo $pagination
                        // -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                        ?>
                    </div>
                </td>
                <td align="right">
                    <select name="status_id">
                        <option value="0"><?php echo language_code('DSP_SELECT_STATUS') ?></option>
                        <?php
                        $fetch_status = $wpdb->get_results("SELECT * FROM $dsp_status_table where status_id!=$list_status Order by name");
                        foreach ($fetch_status as $status) {
                            ?>
                            <option value="<?php echo $status->status_id; ?>"><?php echo $status->name; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <input class="button" type="submit" name="submit"
                           value="<?php echo language_code('DSP_UPDATE_BUTTON') ?>"
                           style="float:right; margin-top:10px;"/></td>
            </tr>
        </table>
    </form>
<?php if (isset($_GET['dsp_page']) && $_GET['dsp_page'] == 'approved') { ?>
    <style>
        .dsp_membership_wrap {
            margin-left: 2px;
            padding: 15px;
            width: 1040px;
            display: block;
        }

        .dsp_membership_col1 {
            width: 130px;
            padding-left: 6px;
            float: left;
            display: block;
            height: 25px;
        }

        .dsp_membership_col2 {
            height: 20px;
            display: block;
            float: left;
        }

        .dsp_membership_col3 {
            width: 260px;
            height: 20px;
            display: block;
            float: left;
            text-align: center;
            margin-left: 10px;
        }
    </style>
    <div id="general" class="postbox">

        <h3 class="hndle"><span><?php echo language_code('DSP_USERNAME_SEARCH') ?></span></h3>

        <div class="dsp_membership_wrap">
            <form name="searchfrm" action="" method="post">
                <br>

                <div class="dsp_membership_active_col"></div>
                <div class="dsp_membership_col1"><?php echo language_code('DSP_USER_NAME') ?></div>
                <div class="dsp_membership_col2"><input name="username" type="text"/></div>
                <div class="dsp_membership_col3"><input type="submit" name="search" class="button" value="Search"/>
                </div>
                <div class="dsp_clr"></div>
                <?php
                $dsp_membership_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
                $myrows = $wpdb->get_results("SELECT * FROM $dsp_membership_table Order by name");
                ?>
                <div class="dsp_clr"></div>
                <br/>
            </form>
        </div>
    </div>
<?php } 
