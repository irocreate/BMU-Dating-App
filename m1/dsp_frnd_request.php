 
<?php
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;




$request_Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';
$frnd_request_Id = isset($_REQUEST['frnd_request_Id']) ? $_REQUEST['frnd_request_Id'] : '';
$date = date("Y-m-d");
// ###########################  Approve Friend request ########################################

if (($request_Action == "approve") && ($frnd_request_Id != "")) {
    $wpdb->query("UPDATE $dsp_my_friends_table  SET approved_status='Y' WHERE friend_id = '$frnd_request_Id' AND friend_uid=$user_id");
    $request_user_id = $wpdb->get_row("SELECT * FROM $dsp_my_friends_table WHERE friend_id = '$frnd_request_Id' AND friend_uid=$user_id");
    $check_friend_in_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE user_id='$user_id' AND friend_uid='$request_user_id->user_id'");

    if ($check_friend_in_list <= 0) {
        $wpdb->query("INSERT INTO $dsp_my_friends_table SET user_id ='$user_id',friend_uid='$request_user_id->user_id',  approved_status='Y' , date_added='$date'");
    }


    $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='8'");
    $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$request_user_id->user_id'");
    $reciver_name = $reciver_details->display_name;
    $receiver_email_address = $reciver_details->user_email;
    $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$user_id'");
    $sender_name = $sender_details->display_name;
    //$site_url=$wpdb->get_row("SELECT * FROM wp_options WHERE option_name='siteurl'");
    // $sender_email_address=$site_url->option_value;
    // $sender_email_address = str_replace ("http://", '', $sender_email_address);
    // $url=add_query_arg( array('pid' =>1,'pagetitle'=>'view_friends'), $root_link);
    $url = $_SERVER['HTTP_HOST'];
    $email_subject = $email_template->subject;
    $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);
    $mem_email_subject = $email_subject;

    $email_message = $email_template->email_body;
    $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
    $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
    $email_message = str_replace("<#URL#>", $url, $email_message);

    $MemberEmailMessage = $email_message;

    dsp_send_email($receiver_email_address, $sender_name, $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");

    //wp_redirect($redirect_location, $redirect_status);
}

// ###########################  Reject Friend request  ########################################
else if (($request_Action == "reject") && ($frnd_request_Id != "")) {
    $wpdb->query("DELETE from $dsp_my_friends_table WHERE friend_id = '$frnd_request_Id' AND friend_uid=$user_id");
} else {
    $count_friends_request = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid=$user_id AND approved_status='N'");

    if ($count_friends_request > 0) {
        ?>


        <div class="swipe_div" id="mainFriendReq" style="height: 150px">
            <ul id="swipe_ulFriendReq"  style="padding-left:0px;text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">

                <?php
                if ($check_couples_mode->setting_status == 'Y') {
                    $frnd_request_members = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.user_id = profile.user_id
AND friends.friend_uid = '$user_id' AND friends.approved_status='N' LIMIT 20");
                } else {
                    $frnd_request_members = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.user_id = profile.user_id
AND friends.friend_uid = '$user_id' AND profile.gender!='C' AND friends.approved_status='N' LIMIT 20 ");
                }
//$frnd_request_members=$wpdb->get_results("SELECT * FROM $dsp_my_friends_table  where friend_uid='$user_id' AND approved_status='N' LIMIT 20");
                $i = 0;
                foreach ($frnd_request_members as $request_mem) {
                    ?>
                    <li class="ivew-list">
                        <?php
                        $exist_frnd_name = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$request_mem->user_id'");

                        $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$exist_frnd_name->user_id'");
                        $exist_make_private->make_private;
                        $s_user_id = $exist_frnd_name->user_id;
                        $favt_mem = array();

                        $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$exist_frnd_name->user_id'");
                        foreach ($private_mem as $private) {
                            $favt_mem[] = $private->favourite_user_id;
                        }
                        if (($i % 4) == 0) {
                            ?>
                        <?php }  // End if(($i%4)==0) ?>
                        <?php
                        if ($check_couples_mode->setting_status == 'Y') {
                            if ($request_mem->gender == 'C') {
                                ?>
                                <?php if ($exist_make_private->make_private == 'Y') { ?>

                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  border="0" class="dsp_img3 iviewed-img" />
                                        </a>                
                                    <?php } else {
                                        ?>
                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  >				
                                            <img src="<?php echo display_members_photo($exist_frnd_name->user_id, $imagepath); ?>"    class="dsp_img3 iviewed-img" /></a>                
                                        <?php
                                    }
                                } else {
                                    ?>

                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" > 
                                        <img src="<?php echo display_members_photo($exist_frnd_name->user_id, $imagepath); ?>" class="dsp_img3 iviewed-img"/>
                                    </a>
                                <?php } ?>

                            <?php } else { ?>

                                <?php if ($exist_make_private->make_private == 'Y') { ?>
                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  >
                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" class="dsp_img3 iviewed-img" />
                                        </a>                
                                    <?php } else {
                                        ?>
                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  >				
                                            <img src="<?php echo display_members_photo($exist_frnd_name->user_id, $imagepath); ?>"    class="dsp_img3 iviewed-img"/></a>                
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  > 
                                        <img src="<?php echo display_members_photo($exist_frnd_name->user_id, $imagepath); ?>" class="dsp_img3 iviewed-img"/>
                                    </a>
                                <?php } ?>

                                <?php
                            }
                        } else {
                            ?> 

                            <?php if ($exist_make_private->make_private == 'Y') { ?>
                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  >
                                        <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" class="dsp_img3 iviewed-img" />
                                    </a>                
                                <?php } else {
                                    ?>
                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  >				
                                        <img src="<?php echo display_members_photo($exist_frnd_name->user_id, $imagepath); ?>"    class="dsp_img3 iviewed-img"/></a>                
                                    <?php
                                }
                            } else {
                                ?>
                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  > 
                                    <img src="<?php echo display_members_photo($exist_frnd_name->user_id, $imagepath); ?>" class="dsp_img3 iviewed-img" />
                                </a>
                            <?php } ?>
                        <?php } ?>
                            <span onclick="showAlertPage('alert', 'approve', '<?php echo $request_mem->friend_id ?>')" class="button-edit spacer-bottom-xs approve-friend" ><?php echo language_code('DSP_MEDIA_LINK_APPROVE') ?></span>
                            <span onclick="showAlertPage('alert', 'reject', '<?php echo $request_mem->friend_id ?>')" class="button-delete spacer-bottom-xs approve-friend" ><?php echo language_code('DSP_MEDIA_LINK_REJECT') ?></span>
                    </li>
                    <?php
                    $i++;
                }
                ?>
            </ul>
        </div>


        <?php
    } else {
        ?>
        <div style="text-align:center;" class="box-page">
            <strong><?php echo language_code('DSP_NO_FRIEND_REQUEST_MSG') ?></strong>
        </div>
        <?php
    }
}
?>
