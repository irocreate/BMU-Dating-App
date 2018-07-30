<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
?>
<script type="text/javascript">
    function mb_reply_dsp_emails(msg)
    {
        if (document.getElementById('txtMsg').value == "")
        {
            alert(msg);
            return false;
        }
        var loc = window.location.href;

        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        document.getElementById("mode").value = "sent";
        document.action = loc;
        document.frmdelmessages.submit();

    } // End delete_dsp_emails()
    function getRecMsg(pageNo, root_link, pluginpath)
    {
        //alert('ih');
        if (pageNo == "")
        {
            document.getElementById("recMsg").innerHTML = "";
            alert(pageNo);
            return;
        }
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function()
        {
            // alert(url);
            //alert('readystate'+xmlhttp.readyState+'status='+ xmlhttp.status);
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                // alert('readystate'+xmlhttp.readyState);
                // document.getElementById("corr").innerHTML='';
                document.getElementById("recMsg").innerHTML = xmlhttp.responseText;

            }
        }


        var url = "<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/mobile/dsp_rec_mes_pagi.php' ?>";
        url = url + "?page1=" + pageNo + "&root_link=" + root_link;
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
    function replyMail(senderId, msgId)
    {
        document.getElementById('reply_div').style.display = 'table-row';
        document.getElementById('reply_id').value = senderId;
        document.getElementById('msg_id').value = msgId;

    }
</script>
<?php
$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
if (isset($_POST['delmessage'])) {
    $Sender_IDs = $_POST['delmessage'];
} else {
    $Sender_IDs = "";
}
if (isset($_POST['mode'])) {
    $mode = $_POST['mode'];
} else {
    $mode = "";
}

if (($Sender_IDs != "") && ($del_mode == "delete")) {
    for ($i = 0; $i < sizeof($Sender_IDs); $i++) {
        $wpdb->query("UPDATE $dsp_user_emails_table SET delete_message=1  WHERE sender_id = '" . $Sender_IDs[$i] . "' and receiver_id='$user_id'");
    } // End for loop
} // End if 
if ($mode == "sent") {
    $getmsgid = $_REQUEST['msg_id'];
    $reply_friend_id = $_REQUEST['reply_id'];
    $mode = $_REQUEST['mode'];
    $reply_messages_subject = $wpdb->get_row("SELECT * FROM $dsp_user_emails_table where message_id='$getmsgid'");
    $reply_Subject = "Re:" . $reply_messages_subject->subject;
    $send_date = date("Y-m-d H:m:s");
    //check spam filter is ON
    $spam_words = array();

    if (trim($check_spam_filter->setting_status) === 'Y') {
        $check_spam_word = $wpdb->get_results("SELECT * FROM $dsp_spam_words_table order by spam_word");
        foreach ($check_spam_word as $spam_word) {
            if (preg_match("/\b" . $spam_word->spam_word . "\b/i", $_POST['txtmessage'])) {
                //if(stristr($_POST['txtmessage'],$spam_word->spam_word)){
                $spam_words[] = $spam_word->spam_word;
            }
        } // end foreach loop
        if (count($spam_words) > 0) {
            $spam_messageError = language_code('DSP_SPAM_FILTER_ACTIVE_MSG');
            $hasError = true;
        } else {
            $message = trim($_POST['txtmessage']);
        }
    }

    $checked_block_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blocked_members_table WHERE user_id=$reply_friend_id AND block_member_id='$user_id'");
    //checked blocked member 
    if ($checked_block_member > 0) {
        $blocked_Error = language_code('DSP_BLOCKED_MEMBER_MESSAGE');
        $hasError = true;
    }

    if (!isset($hasError)) {
        $count_threads = $wpdb->get_row("SELECT MAX(message_id) as maxid FROM $dsp_user_emails_table WHERE (sender_id = $user_id AND receiver_id=$reply_friend_id) OR (sender_id = $reply_friend_id AND receiver_id=$user_id)");
        $thread_id = $count_threads->maxid;
        $check_friend_notification = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_notification_table WHERE private_messages='N' AND user_id='$reply_friend_id'");
        if ($check_friend_notification <= 0) {
            $insertQuery = "INSERT INTO $dsp_user_emails_table SET sender_id = $user_id,receiver_id ='$reply_friend_id',subject='$reply_Subject',text_message='$message',sent_date='$send_date',message_read='N',thread_id='$thread_id'";
            $wpdb->query($insertQuery);
            //echo $insertQuery;
            //	echo "SELECT user_email FROM $DSP_USERS_TABLE WHERE ID = '$reply_friend_id'";
            $sel_email = $wpdb->get_row("SELECT user_email FROM $DSP_USERS_TABLE WHERE ID = '$reply_friend_id'");
            $email_id = $sel_email->user_email;
            $message_sent = language_code('DSP_SEND_MESSAGE_SUCCESS');
            $to = $email_id;

            $subject = language_code('DSP_SEND_MESSAGE_FROM_TEMP');

            $message = HI_YOU_HVAE_JUST_RECEIVE_MAIL_FROM_A_MEMBER_AT_DATINGSOLUTION_PLEASE_LOGIN_TO_CHECK_UR_MAIL;

            $from = DSP_CONTACT_DATINGSOLUTIONS_BIZ;

            $headers = DSP_FROM . $from;

            wp_mail($to, $subject, $message, $headers);
        } else {
            $message_sent = language_code('DSP_MEMBER_ALERT_MSG_NOTIFICATION');
        }
    }
}
?>
<div>
    <?php if (isset($spam_messageError)) {
        ?>
        <div>

            <p style="color:#FF0000; padding-left:30px;"><?php echo $spam_messageError ?></p>

        </div>

        <?php
    }

    if (isset($blocked_Error)) {
        ?>

        <div>

            <p style="color:#FF0000; padding-left:30px;"><?php echo $blocked_Error ?></p>

        </div>

        <?php
    }

    if (isset($message_sent) && $message_sent != "") {
        ?>

        <div>

            <p style="color:#FF0000; padding-left:30px;"><?php echo $message_sent ?></p>

        </div>

    <?php } ?>


    <form name="frmdelmessages" method="post">
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
            <tr style="display: none" id="reply_div" >
                <td colspan="3">
                    <div class='dsp_mb_gray_rep'  >

                        <div class="dsp_mb_rep">
                            <?php echo DSP_POST_REPLY ?>
                            <textarea class='dsp_mb_gray_txt' id="txtMsg" name="txtmessage" class="mb_test_field1" ></textarea>
                        </div> 
                        <div align="center">
                            <input type="hidden" name="mode" value="sent" id="mode"/>
                            <input type="hidden" name="msg_id" value="" id="msg_id"/>
                            <input type="hidden" name="reply_id" value="" id="reply_id" />
                            <input type="button" class="dsp_submit_button" name="reply" value="<?php echo DSP_MESSAGE_REPLY ?>" onclick="mb_reply_dsp_emails('<?php echo DSP_PLEASE_FILL_MESSAGE ?>');"/>
                        </div> 
                        </form>
                    </div>


                </td>
            </tr>
            <tr>	
                <td colspan="3">
                    <div id="recMsg">
                        <table width="100%" border="0" cellspacing="0" cellpadding="3">
                            <?php
                            $pagination_limit = DSP_PAGINATION_LIMIT;
//$pagination_limit=1;
// ----------------------------------------------- Start Paging code New Member------------------------------------------------------ // 
                            if (isset($_GET['page1']))
                                $page1 = $_GET['page1'];
                            else
                                $page1 = 1;

                            $max_results1 = $pagination_limit;
                            $adjacents = DSP_PAGINATION_ADJACENTS;
                            $limit = $max_results1;

                            $from1 = (($page1 * $max_results1) - $max_results1);
                            if ($check_couples_mode->setting_status == 'Y') {
                                $totalQuery = "SELECT * FROM $dsp_user_emails_table m,$dsp_user_profiles p WHERE m.sender_id = p.user_id AND m.receiver_id=$user_id and m.delete_message=0 group by m.sender_id Order by sent_date desc";
                            } else {

                                $totalQuery = "SELECT * FROM $dsp_user_emails_table m,$dsp_user_profiles p WHERE m.sender_id = p.user_id AND m.receiver_id=$user_id and m.delete_message=0 AND p.gender!='C' group by m.sender_id Order by sent_date desc";
                            }
//echo "SELECT * FROM $DSP_USER_PROFILES_TABLE WHERE status_id=1 AND last_update_date > DATE_SUB(now(), INTERVAL 14 DAY) ";
//	$totalQuery="SELECT * FROM $dsp_user_emails_table where receiver_id=$user_id and delete_message=0 group by sender_id Order by sent_date desc";
//	echo $totalQuery; 
                            $total_results1 = $wpdb->get_results($totalQuery);
// Calculate total number of pages. Round up using ceil()

                            $total_pages1 = count($total_results1);
                            if ($total_pages1 > 0) {
                                // ------------------------------------------------End Paging code------------------------------------------------------ //
                                $getMsgLimitQuery = $totalQuery . " LIMIT " . $from1 . ", " . $max_results1;
                                //echo $getMsgLimitQuery;
                                $my_messages = $wpdb->get_results($getMsgLimitQuery);
                                //-------------------------Pagination link----------------------------------->

                                /* Setup page vars for display. */
                                if ($page1 == 0)
                                    $page1 = 1;     //if no page var is given, default to 1.
                                $prev = $page1 - 1;       //previous page is page - 1
                                $next = $page1 + 1;       //next page is page + 1
                                $lastpage = ceil($total_pages1 / $limit);
                                //lastpage is = total pages / items per page, rounded up.
                                $lpm1 = $lastpage - 1;      //last page minus 1
                                // echo 'page1='.$page1.' last page='.$lastpage.'  total page='.$total_pages1.'limit='.$limit;
                                /*
                                  Now we apply our rules and draw the pagination object.
                                  We're actually saving the code to a variable in case we want to draw it more than once.
                                 */
                                $pagination = "";
                                if ($lastpage > 1) {
                                    $pagination .= "<div style=\"text-align:left\" class=\"dspmb_pagination\">";
                                    //previous button
                                    if ($page1 > 1)
                                        $pagination.= "<div><a onclick=\"getRecMsg('" . $prev . "','" . $root_link . "');\" >Previous</a></div>";
                                    else
                                        $pagination.= "<span class=\"disabled\">previous</span>";

                                    //pages	
                                    if ($lastpage <= 3 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
                                        for ($counter = 1; $counter <= $lastpage; $counter++) {
                                            if ($counter == $page1)
                                                $pagination.= "<span class=\"current\">$counter</span>";
                                            else
                                                $pagination.= "<div><a onclick=\"getRecMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                                        }
                                    }
                                    elseif ($lastpage > 3 + ($adjacents * 2)) { //enough pages to hide some//5
                                        //close to beginning; only hide later pages
                                        if ($page1 <= 1 + ($adjacents * 2)) {
                                            for ($counter = 1; $counter <= 1 + ($adjacents * 2); $counter++) {
                                                if ($counter == $page1)
                                                    $pagination.= "<span class=\"current\">$counter</span>";
                                                else
                                                    $pagination.= "<div><a onclick=\"getRecMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                                            }
                                            $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                                            $pagination.="<div><a onclick=\"getRecMsg('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

                                            $pagination.="<div><a onclick=\"getRecMsg('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
                                        }
                                        //in middle; hide some front and some back
                                        elseif ($lastpage - ($adjacents * 2) > $page1 && $page1 > ($adjacents * 2)) {
                                            $pagination.="<div><a onclick=\"getRecMsg('1','" . $root_link . "')\">1</a></div>";

                                            $pagination.= "<div><a onclick=\"getRecMsg('2','" . $root_link . "')\">2</a></div>";
                                            $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                                            for ($counter = $page1 - $adjacents; $counter <= $page1 + $adjacents; $counter++) {
                                                if ($counter == $page1)
                                                    $pagination.= "<div class=\"current\">$counter</div>";
                                                else
                                                    $pagination.= "<div><a onclick=\"getRecMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                                            }
                                            $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                                            $pagination.= "<div><a onclick=\"getRecMsg('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

                                            $pagination.= "<div><a onclick=\"getRecMsg('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
                                        }
                                        //close to end; only hide early pages
                                        else {
                                            $pagination.= "<div><a onclick=\"getRecMsg('1','" . $root_link . "')\">1</a></div>";
                                            $pagination.= "<div><a onclick=\"getRecMsg('2','" . $root_link . "')\">2</a></div>";
                                            $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                                            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                                                if ($counter == $page1)
                                                    $pagination.= "<span class=\"current\">$counter</span>";
                                                else
                                                    $pagination.= "<div><a onclick=\"getRecMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                                            }
                                        }
                                    }

                                    //next button
                                    if ($page1 < $lastpage)
                                        $pagination.="<div><a onclick=\"getRecMsg('" . $next . "','" . $root_link . "');\" >next</a></div>";
                                    else
                                        $pagination.= "<span class=\"disabled\">next</span>";
                                    $pagination.= "</div>\n";
                                }
                                ?>
                                <tr>
                                    <td>
                                        <div class="dspmb_main_paging">
                                            <?php echo $pagination ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                                //-------------------------End of Pagination link----------------------------------->
                                $i = 0;
                                foreach ($my_messages as $message) {
                                    $display_sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$message->sender_id'");
                                    $message_detail = $wpdb->get_row("SELECT subject,text_message,sent_date,message_id FROM $dsp_user_emails_table WHERE receiver_id=$user_id AND sender_id = '$message->sender_id' order by sent_date desc LIMIT 1");
                                    $message_id = $message_detail->message_id;
                                    $message_subject = $message_detail->subject;
                                    $message_date = date("Y d M g:i a", strtotime($message_detail->sent_date));
                                    $message_text = substr($message_detail->text_message, 0, 15);
                                    $count_member_new_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE message_read='N' AND receiver_id=$user_id AND sender_id='$message->sender_id'");
                                    $count_total_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE  delete_message=0 AND receiver_id=$user_id AND sender_id='$message->sender_id' ");
                                    // check for private pic
                                    $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$message->sender_id'");

                                    $exist_make_private->make_private;

                                    $favt_mem = array();

                                    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$message->sender_id'");

                                    foreach ($private_mem as $private) {

                                        $favt_mem[] = $private->favourite_user_id;
                                    }
                                    ?>

                                    <tr>
                                        <td>

                                            <table width="100%" border="0" cellspacing="0" cellpadding="3" <?php if ($i % 2 == 0) echo "class='dsp_mb_gray'"; ?>>
                                                <tr> 
                                                    <td width="10%">
                                                        <a onclick="replyMail(<?php echo $message->sender_id ?>,<?php echo $message_id ?>);">
                                                            <img src="<?php echo $imagepath . 'arrow.png' ?>"/>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <table>
                                                            <tr>
                                                                <td>
                                                                    <!---check for private pic-->
                                                                    <?php
                                                                    if ($check_couples_mode->setting_status == 'Y') {
                                                                        if ($message->gender == 'C') {

                                                                            if ($exist_make_private->make_private == 'Y') {

                                                                                if (!in_array($current_user->ID, $favt_mem)) {
                                                                                    ?>

                                                                                    <a href="<?php
                                                                                    echo add_query_arg(array(
                                                                                        'pid' => 3,
                                                                                        'mem_id' => $message->sender_id,
                                                                                        'pagetitle' => "view_profile"), $root_link);
                                                                                    ?>" >

                                                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:45px; height:45px;" class="img2" align="left"  />

                                                                                    </a>                

                                                                                <?php } else {
                                                                                    ?>

                                                                                    <a href="<?php
                                                                                    echo add_query_arg(array(
                                                                                        'pid' => 3,
                                                                                        'mem_id' => $message->sender_id,
                                                                                        'pagetitle' => "view_profile"), $root_link);
                                                                                    ?>" >				

                                                                                        <img src="<?php echo display_members_photo_mb($message->sender_id, $image_path); ?>"   style="width:45px; height:45px;" class="img2" align="left" /></a>                


                                                                                    <?php
                                                                                }
                                                                            } // not private end 
                                                                            else {
                                                                                ?>
                                                                                <a href="<?php
                                                                                echo add_query_arg(array(
                                                                                    'pid' => 3,
                                                                                    'mem_id' => $message->sender_id,
                                                                                    'pagetitle' => "view_profile"), $root_link);
                                                                                ?>">

                                                                                    <img src="<?php echo display_members_photo_mb($message->sender_id, $image_path); ?>" style="width:45px; height:45px;" class="img2" align="left" />

                                                                                </a>

                                                                            <?php } ?>
                                                                            <?php
                                                                        } // end of check if  sender gender is couple
                                                                        else {
                                                                            if ($exist_make_private->make_private == 'Y') {

                                                                                if (!in_array($current_user->ID, $favt_mem)) {
                                                                                    ?>

                                                                                    <a href="<?php
                                                                                    echo add_query_arg(array(
                                                                                        'pid' => 3,
                                                                                        'mem_id' => $message->sender_id,
                                                                                        'pagetitle' => "view_profile"), $root_link);
                                                                                    ?>" >

                                                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:45px; height:45px;" class="img2" align="left" />

                                                                                    </a>                

                                                                                    <?php
                                                                                } else {
                                                                                    ?>

                                                                                    <a href="<?php
                                                                                    echo add_query_arg(array(
                                                                                        'pid' => 3,
                                                                                        'mem_id' => $message->sender_id,
                                                                                        'pagetitle' => "view_profile"), $root_link);
                                                                                    ?>" >				

                                                                                        <img src="<?php echo display_members_photo_mb($message->sender_id, $image_path); ?>"    style="width:45px; height:45px;" class="img2" align="left" /></a>                

                                                                                    <?php
                                                                                }
                                                                            } else {
                                                                                ?>

                                                                                <a href="<?php
                                                                                echo add_query_arg(array(
                                                                                    'pid' => 3,
                                                                                    'mem_id' => $message->sender_id,
                                                                                    'pagetitle' => "view_profile"), $root_link);
                                                                                ?>">

                                                                                    <img src="<?php echo display_members_photo_mb($message->sender_id, $image_path); ?>" style="width:45px; height:45px;" class="img2" align="left" />

                                                                                </a>

                                                                                <?php
                                                                            }
                                                                        } // end of else gender is not a couple 
                                                                    } // end of if couple mode is on 
                                                                    else {
                                                                        if ($exist_make_private->make_private == 'Y') {

                                                                            if (!in_array($current_user->ID, $favt_mem)) {
                                                                                ?>

                                                                                <a href="<?php
                                                                                echo add_query_arg(array(
                                                                                    'pid' => 3,
                                                                                    'mem_id' => $message->sender_id,
                                                                                    'pagetitle' => "view_profile"), $root_link);
                                                                                ?>" >

                                                                                    <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:45px; height:45px;" class="img2" align="left"  />

                                                                                </a>                

                                                                            <?php } else {
                                                                                ?>

                                                                                <a href="<?php
                                                                                echo add_query_arg(array(
                                                                                    'pid' => 3,
                                                                                    'mem_id' => $message->sender_id,
                                                                                    'pagetitle' => "view_profile"), $root_link);
                                                                                ?>" >				

                                                                                    <img src="<?php echo display_members_photo_mb($message->sender_id, $image_path); ?>"    style="width:45px; height:45px;" class="img2" align="left" /></a>                

                                                                                <?php
                                                                            }
                                                                        }  // end of if pic is private
                                                                        else {
                                                                            ?>

                                                                            <a href="<?php
                                                                            echo add_query_arg(array(
                                                                                'pid' => 3,
                                                                                'mem_id' => $message->sender_id,
                                                                                'pagetitle' => "view_profile"), $root_link);
                                                                            ?>">

                                                                                <img src="<?php echo display_members_photo_mb($message->sender_id, $image_path); ?>" style="width:45px; height:45px;" class="img2" align="left" />

                                                                            </a>

                                                                        <?php } // end of else pic is not private    ?>



                                                                    <?php } // end of else    ?>

                                                                    <!----End of private pic-->
                                                                    <!--<a href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 3,
                                                                        'mem_id' => $message->sender_id,
                                                                        'pagetitle' => 'view_profile'), $root_link);
                                                                    ?>">
                                                                    <img src="<?php echo display_members_photo_mb($message->sender_id, $image_path); ?>" width="45px" height="45px" class="img2" align="left" /></a>-->
                                                                </td>
                                                                <td>
                                                                    <span class="dsp_mb_name"><a href="<?php
                                                                        echo add_query_arg(array(
                                                                            'pid' => 3,
                                                                            'mem_id' => $message->sender_id,
                                                                            'pagetitle' => 'view_profile'), $root_link);
                                                                        ?>"><?php echo $display_sender_name->display_name; ?>:</a></span>&nbsp;
                                                                    <a <?php
                                                                    if ($count_member_new_messages > 0) {
                                                                        echo 'class="dsp_md_sub"';
                                                                    } else {
                                                                        echo 'class="dsp_md_sub_read"';
                                                                    }
                                                                    ?> href="<?php
                                                                        echo add_query_arg(array(
                                                                            'pid' => 14,
                                                                            'Act' => 'R',
                                                                            'pagetitle' => 'my_email',
                                                                            'message_template' => 'view_message',
                                                                            'sender_ID' => $message->sender_id), $root_link);
                                                                        ?>">
                                                                        <?php echo $message_subject ?>&nbsp;(<?php echo $count_total_messages ?>)

                                                                    </a>
                                                                    <br /><?php echo $message_text ?>...
                                                                    <br><?php echo $message_date ?>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <!--<?php
                                                $count_member_new_messages = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE message_read='N' AND receiver_id=$user_id AND sender_id='$message->sender_id'"));
                                                if ($count_member_new_messages > 0) {
                                                    ?>
                                                                                 <td width="65%"><a href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 14, 'pagetitle' => 'my_email',
                                                        'message_template' => 'view_message',
                                                        'sender_ID' => $message->sender_id,
                                                        'Act' => 'R'), $root_link);
                                                    ?>"><?php echo $message_subject->subject; ?>&nbsp;<span style="color:#FF0000;">(<?php echo $count_member_new_messages ?>)</span></a></td>
                                                <?php } else { ?>
                                                                                 <td width="65%"><a href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 14, 'pagetitle' => 'my_email',
                                                        'message_template' => 'view_message',
                                                        'sender_ID' => $message->sender_id), $root_link);
                                                    ?>"><?php echo $message_subject->subject; ?></a></td>
                                                <?php } ?>-->
                                            </table>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                    unset($favt_mem);
                                } // for each end 
                            } // if condition end  
                            else {
                                ?>
                                <tr>
                                    <td>
                                        <div class="dsp_mb_nothing">
                                            <?php echo DSP_NO_MAIL_IN_YOUR_INBOX ?>
                                        </div>

                                    </td>
                                </tr>
                            <?php }
                            ?>


                            <?php
// --------------------------------  PRINT PAGING LINKS ------------------------------------------- //

                            /* Setup page vars for display. */
                            if ($page1 == 0)
                                $page1 = 1;     //if no page var is given, default to 1.
                            $prev = $page1 - 1;       //previous page is page - 1
                            $next = $page1 + 1;       //next page is page + 1
                            $lastpage = ceil($total_pages1 / $limit);
//lastpage is = total pages / items per page, rounded up.
                            $lpm1 = $lastpage - 1;      //last page minus 1
// echo 'page1='.$page1.' last page='.$lastpage.'  total page='.$total_pages1.'limit='.$limit;
                            /*
                              Now we apply our rules and draw the pagination object.
                              We're actually saving the code to a variable in case we want to draw it more than once.
                             */
                            $pagination = "";
                            if ($lastpage > 1) {
                                $pagination .= "<div style=\"text-align:left\" class=\"dspmb_pagination\">";
                                //previous button
                                if ($page1 > 1)
                                    $pagination.= "<div><a onclick=\"getRecMsg('" . $prev . "','" . $root_link . "');\" >Previous</a></div>";
                                else
                                    $pagination.= "<span class=\"disabled\">previous</span>";

                                //pages	
                                if ($lastpage <= 3 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
                                    for ($counter = 1; $counter <= $lastpage; $counter++) {
                                        if ($counter == $page1)
                                            $pagination.= "<span class=\"current\">$counter</span>";
                                        else
                                            $pagination.= "<div><a onclick=\"getRecMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                                    }
                                }
                                elseif ($lastpage > 3 + ($adjacents * 2)) { //enough pages to hide some//5
                                    //close to beginning; only hide later pages
                                    if ($page1 <= 1 + ($adjacents * 2)) {
                                        for ($counter = 1; $counter <= 1 + ($adjacents * 2); $counter++) {
                                            if ($counter == $page1)
                                                $pagination.= "<span class=\"current\">$counter</span>";
                                            else
                                                $pagination.= "<div><a onclick=\"getRecMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                                        }
                                        $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                                        $pagination.="<div><a onclick=\"getRecMsg('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

                                        $pagination.="<div><a onclick=\"getRecMsg('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
                                    }
                                    //in middle; hide some front and some back
                                    elseif ($lastpage - ($adjacents * 2) > $page1 && $page1 > ($adjacents * 2)) {
                                        $pagination.="<div><a onclick=\"getRecMsg('1','" . $root_link . "')\">1</a></div>";

                                        $pagination.= "<div><a onclick=\"getRecMsg('2','" . $root_link . "')\">2</a></div>";
                                        $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                                        for ($counter = $page1 - $adjacents; $counter <= $page1 + $adjacents; $counter++) {
                                            if ($counter == $page1)
                                                $pagination.= "<div class=\"current\">$counter</div>";
                                            else
                                                $pagination.= "<div><a onclick=\"getRecMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                                        }
                                        $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                                        $pagination.= "<div><a onclick=\"getRecMsg('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

                                        $pagination.= "<div><a onclick=\"getRecMsg('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
                                    }
                                    //close to end; only hide early pages
                                    else {
                                        $pagination.= "<div><a onclick=\"getRecMsg('1','" . $root_link . "')\">1</a></div>";
                                        $pagination.= "<div><a onclick=\"getRecMsg('2','" . $root_link . "')\">2</a></div>";
                                        $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                                            if ($counter == $page1)
                                                $pagination.= "<span class=\"current\">$counter</span>";
                                            else
                                                $pagination.= "<div><a onclick=\"getRecMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                                        }
                                    }
                                }

                                //next button
                                if ($page1 < $lastpage)
                                    $pagination.="<div><a onclick=\"getRecMsg('" . $next . "','" . $root_link . "');\" >next</a></div>";
                                else
                                    $pagination.= "<span class=\"disabled\">next</span>";
                                $pagination.= "</div>\n";
                            }
                            ?>
                            <tr>
                                <td>
                                    <div class="dspmb_main_paging">
                                        <?php echo $pagination ?>
                                    </div>
                                </td>
                            </tr>

                            <!---------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //-->
                        </table>


                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>