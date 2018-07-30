</div>
<div class="box-border">
    <div class="box-pedding">
        <div class="box-page dsp-favourite-notification">
            <?php
            $dsp_user_favourites_table;
            $session_user_id = get('user_id');
            $fav_user_id = get('fav_userid');
            $date = date("Y-m-d");
            if ($session_user_id != "" && $fav_user_id != "" && ($session_user_id != $fav_user_id)) {

                $exist_fav_user_screenname = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$fav_user_id'");
                $fav_screenname = $exist_fav_user_screenname->display_name;
                $exist_fav_user_title = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$fav_user_id'");
                $fav_title = isset($exist_fav_user_title->title) ? $exist_fav_user_title->title : '';
                $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_favourites_table WHERE user_id=$session_user_id AND favourite_user_id=$fav_user_id");

                if ($num_rows <= 0) {
                    $wpdb->query("INSERT INTO $dsp_user_favourites_table SET user_id = $user_id,favourite_user_id ='$fav_user_id' ,fav_screenname='$fav_screenname',fav_date_added='$date',fav_title='$fav_title',fav_private='N'");
                    dsp_add_notification($user_id, $fav_user_id, 'add_favourites');


                    $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='9'");
                    $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$fav_user_id'");
                    $reciver_name = $reciver_details->display_name;
                    $receiver_email_address = $reciver_details->user_email;
                    $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$user_id'");
                    $sender_name = $sender_details->display_name;


                    $email_subject = $email_template->subject;
                    $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);
                    $mem_email_subject = $email_subject;

                    $email_message = $email_template->email_body;
                    $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
                    $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);

                    $MemberEmailMessage = $email_message;
                    $admin_email = get_option('admin_email');
                    $from = $admin_email;
                    // wp_mail($receiver_email_address, $mem_email_subject, $MemberEmailMessage);
                     $wpdating_email  = Wpdating_email_template::get_instance();
                    $result = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );


                    $print_msg = language_code('DSP_FAVOURITES_ADDED_SUCESS_MSG');
                } else {
                    $print_msg = language_code('DSP_ALREADY_ADDED_FAVOURITES_MSG');
                    // $wpdb->query("UPDATE $dsp_user_favourites_table SET fav_screenname='$fav_screenname',fav_date_added='$date',fav_title='$fav_title',fav_private='N' where user_id=$session_user_id AND favourite_user_id=$fav_user_id");
                }
            } else if ($session_user_id == $fav_user_id) {
                $print_msg = language_code('DSP_CANT_ADD_YOURSELF_MSG');
            }
            ?>
            <div class="msg"><?php echo $print_msg; ?></div>
            <div style="text-align:center;"><a href="<?php echo $root_link . get_username($fav_user_id); ?>" class="dspdp-btn dspdp-btn-info"><?php echo language_code('DSP_BACK_TO_PROFILE_LINK') ?></a></div>
        </div>
    </div>
</div>

