<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
?>
<script type="text/javascript">
    function getSentMsg(pageNo, root_link)
    {
        //alert('ih');
        if (pageNo == "")
        {
            document.getElementById("sentMsg").innerHTML = "";
            // alert(pageNo);
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
                document.getElementById("sentMsg").innerHTML = xmlhttp.responseText;

            }
        }


        var url = "<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/mobile/dsp_sent_mes_pagi.php' ?>";
        url = url + "?page1=" + pageNo + "&root_link=" + root_link;
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
</script>
<?php
if (isset($_GET['sender_ID'])) {
    $get_sender_id = $_GET['sender_ID'];
} else {
    $get_sender_id = "";
}
if (isset($_GET['Act'])) {
    $request_Action = $_GET['Act'];
} else {
    $request_Action = "";
}
if (($request_Action == "R") && ($get_sender_id != "")) {
    $wpdb->query("UPDATE $dsp_user_emails_table  SET message_read='Y' WHERE sender_id = '$get_sender_id'");
} // End if 
?>
<div id="sentMsg">
    <!--<tr><td class="dsp_back_inbox"><a href="<?php
    echo add_query_arg(array(
        'pid' => 14, 'pagetitle' => 'my_email', 'message_template' => 'inbox'), $root_link);
    ?>"><?php echo language_code('DSP_BACK_TO_INBOX') ?></a></td></tr>-->
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
        $totalQuery = "SELECT * FROM $dsp_user_emails_table m,$dsp_user_profiles p WHERE m.sender_id = p.user_id AND m.sender_id=$user_id  group by m.receiver_id Order by thread_id desc";
    } else {

        $totalQuery = "SELECT * FROM $dsp_user_emails_table m,$dsp_user_profiles p WHERE m.sender_id = p.user_id AND m.sender_id=$user_id AND p.gender!='C' group by m.receiver_id Order by thread_id desc";
    }
//$totalQuery="SELECT * FROM $dsp_user_emails_table where sender_id=$user_id group by receiver_id Order by thread_id desc";
//	echo $totalQuery; 
    $total_results1 = $wpdb->get_results($totalQuery);

// Calculate total number of pages. Round up using ceil()
    $total_pages1 = count($total_results1);
// ------------------------------------------------End Paging code------------------------------------------------------ //
    $getMsgLimitQuery = $totalQuery . " LIMIT " . $from1 . ", " . $max_results1;
//echo $getMsgLimitQuery;


    $my_messages = $wpdb->get_results($getMsgLimitQuery);


    if ($total_results1 > $max_results1) {
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
                $pagination.= "<div><a onclick=\"getSentMsg('" . $prev . "','" . $root_link . "');\" >Previous</a></div>";
            else
                $pagination.= "<span class=\"disabled\">previous</span>";

            //pages	
            if ($lastpage <= 3 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page1)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<div><a onclick=\"getSentMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                }
            }
            elseif ($lastpage > 3 + ($adjacents * 2)) { //enough pages to hide some//5
                //close to beginning; only hide later pages
                if ($page1 <= 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter <= 1 + ($adjacents * 2); $counter++) {
                        if ($counter == $page1)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<div><a onclick=\"getSentMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                    }
                    $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                    $pagination.="<div><a onclick=\"getSentMsg('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

                    $pagination.="<div><a onclick=\"getSentMsg('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
                }
                //in middle; hide some front and some back
                elseif ($lastpage - ($adjacents * 2) > $page1 && $page1 > ($adjacents * 2)) {
                    $pagination.="<div><a onclick=\"getSentMsg('1','" . $root_link . "')\">1</a></div>";

                    $pagination.= "<div><a onclick=\"getSentMsg('2','" . $root_link . "')\">2</a></div>";
                    $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                    for ($counter = $page1 - $adjacents; $counter <= $page1 + $adjacents; $counter++) {
                        if ($counter == $page1)
                            $pagination.= "<div class=\"current\">$counter</div>";
                        else
                            $pagination.= "<div><a onclick=\"getSentMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                    }
                    $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                    $pagination.= "<div><a onclick=\"getSentMsg('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

                    $pagination.= "<div><a onclick=\"getSentMsg('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
                }
                //close to end; only hide early pages
                else {
                    $pagination.= "<div><a onclick=\"getSentMsg('1','" . $root_link . "')\">1</a></div>";
                    $pagination.= "<div><a onclick=\"getSentMsg('2','" . $root_link . "')\">2</a></div>";
                    $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page1)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<div><a onclick=\"getSentMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                    }
                }
            }

            //next button
            if ($page1 < $lastpage)
                $pagination.="<div><a onclick=\"getSentMsg('" . $next . "','" . $root_link . "');\" >next</a></div>";
            else
                $pagination.= "<span class=\"disabled\">next</span>";
            $pagination.= "</div>\n";
        }
        ?>

        <div class="dspmb_main_paging">
            <?php echo $pagination ?>
        </div>

        <br><br>
    <?php } // End if($total_results1 > $max_results1)
    ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <!-------------------------End of Pagination link----------------------------------->
        <?php
        if (count($my_messages) > '0') {
            $i = 0;
            foreach ($my_messages as $message) {

                $display_sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$message->receiver_id'");

                $getMessageQuery = "SELECT subject,text_message,sent_date FROM $dsp_user_emails_table WHERE sender_id=$user_id AND receiver_id = '$message->receiver_id'   order by sent_date desc LIMIT 1";
                $message_detail = $wpdb->get_row($getMessageQuery);

                $message_date = date("Y d M g:i a", strtotime($message_detail->sent_date));
                $message_text = substr(($message_detail->text_message), 0, 15);
                $subject = $message_detail->subject;

                // check for private pic
                $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$message->receiver_id'");

                $exist_make_private->make_private;

                $favt_mem = array();

                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$message->receiver_id'");

                foreach ($private_mem as $private) {

                    $favt_mem[] = $private->favourite_user_id;
                }
                ?>
                <tr >
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="3" <?php if ($i % 2 == 0) echo "class='dsp_mb_gray'"; ?>>
                            <tr>
                                <td width="50px">

                                    <?php
                                    if ($check_couples_mode->setting_status == 'Y') {
                                        if ($message->gender == 'C') {

                                            if ($exist_make_private->make_private == 'Y') {

                                                if (!in_array($current_user->ID, $favt_mem)) {
                                                    ?>

                                                    <a href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 3,
                                                        'mem_id' => $message->receiver_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >

                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:45px; height:45px;" class="img2" align="left"  />

                                                    </a>                

                                                <?php } else {
                                                    ?>

                                                    <a href="<?php
                                                    echo add_query_arg(array('pid' => 3,
                                                        'mem_id' => $message->receiver_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >				

                                                        <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>"   style="width:45px; height:45px;" class="img2" align="left" /></a>                


                                                    <?php
                                                }
                                            } // not private end 
                                            else {
                                                ?>
                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $message->receiver_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>">

                                                    <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>" style="width:45px; height:45px;" class="img2" align="left" />

                                                </a>

                                            <?php } ?>
                                            <?php
                                        } // end of check if  sender gender is couple
                                        else {
                                            if ($exist_make_private->make_private == 'Y') {

                                                if (!in_array($current_user->ID, $favt_mem)) {
                                                    ?>

                                                    <a href="<?php
                                                    echo add_query_arg(array('pid' => 3,
                                                        'mem_id' => $message->receiver_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >

                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:45px; height:45px;" class="img2" align="left" />

                                                    </a>                

                                                    <?php
                                                } else {
                                                    ?>

                                                    <a href="<?php
                                                    echo add_query_arg(array('pid' => 3,
                                                        'mem_id' => $message->receiver_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >				

                                                        <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>"    style="width:45px; height:45px;" class="img2" align="left" /></a>                

                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $message->receiver_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>">

                                                    <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>" style="width:45px; height:45px;" class="img2" align="left" />

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
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $message->receiver_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>" >

                                                    <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:45px; height:45px;" class="img2" align="left"  />

                                                </a>                

                                            <?php } else {
                                                ?>

                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $message->receiver_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>" >				

                                                    <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>"    style="width:45px; height:45px;" class="img2" align="left" /></a>                

                                                <?php
                                            }
                                        }  // end of if pic is private
                                        else {
                                            ?>

                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $message->receiver_id,
                                                'pagetitle' => "view_profile"), $root_link);
                                            ?>">

                                                <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>" style="width:45px; height:45px;" class="img2" align="left" />

                                            </a>

                                        <?php } // end of else pic is not private   ?>



                                    <?php } // end of else    ?>


                                    <!--	<a href="<?php
                                    echo add_query_arg(array('pid' => 3,
                                        'mem_id' => $message->receiver_id, 'pagetitle' => 'view_profile'), $root_link);
                                    ?>">
                                    <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>" width="45px" height="50px" class="dsp_img2" align="left" /></a>-->

                                </td>
                                <td>
                                    <span class="dsp_mb_name"><?php echo DSP_TO . $display_sender_name->display_name ?></span>&nbsp;:&nbsp;
                                    <a class="dsp_md_sub" href="<?php
                                    echo add_query_arg(array(
                                        'pid' => 14, 'pagetitle' => 'my_email', 'message_template' => 'view_sent_message',
                                        'receiver_ID' => $message->receiver_id), $root_link);
                                    ?>">
                                        <span class="dsp_md_sub"><?php echo $subject ?></span>
                                    </a>
                                    <br /><?php echo $message_text ?>
                                    <br /><?php echo $message_date ?>

                                    <!--<?php if ($message->sender_id != $user_id) { ?>
                                                                
                                                                        <tr><td><a href="<?php
                                        echo add_query_arg(array(
                                            'pid' => 14, 'pagetitle' => 'my_email',
                                            'message_template' => 'compose',
                                            'sender_ID' => $message->sender_id, 'Act' => 'Reply'), $root_link);
                                        ?>"><?php echo language_code('DSP_MESSAGE_REPLY') ?></a></td></tr>
                                                                
                                    <?php } //end if    ?>-->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <?php
                $i++;
                unset($favt_mem);
            } // End for loop 
        } // end if confidtion
        else { // there is not message in sent box
            ?>
            <tr>
                <td>
                    <div class="dsp_mb_nothing">
                        <?php echo DSP_NO_SENT_MESSAGE_HERE ?>
                    </div>
                </td>
            </tr>
        <?php }
        ?>
    </table> 
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
            $pagination.= "<div><a onclick=\"getSentMsg('" . $prev . "','" . $root_link . "');\" >Previous</a></div>";
        else
            $pagination.= "<span class=\"disabled\">previous</span>";

        //pages	
        if ($lastpage <= 3 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page1)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<div><a onclick=\"getSentMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
            }
        }
        elseif ($lastpage > 3 + ($adjacents * 2)) { //enough pages to hide some//5
            //close to beginning; only hide later pages
            if ($page1 <= 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= 1 + ($adjacents * 2); $counter++) {
                    if ($counter == $page1)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<div><a onclick=\"getSentMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                }
                $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                $pagination.="<div><a onclick=\"getSentMsg('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

                $pagination.="<div><a onclick=\"getSentMsg('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
            }
            //in middle; hide some front and some back
            elseif ($lastpage - ($adjacents * 2) > $page1 && $page1 > ($adjacents * 2)) {
                $pagination.="<div><a onclick=\"getSentMsg('1','" . $root_link . "')\">1</a></div>";

                $pagination.= "<div><a onclick=\"getSentMsg('2','" . $root_link . "')\">2</a></div>";
                $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                for ($counter = $page1 - $adjacents; $counter <= $page1 + $adjacents; $counter++) {
                    if ($counter == $page1)
                        $pagination.= "<div class=\"current\">$counter</div>";
                    else
                        $pagination.= "<div><a onclick=\"getSentMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                }
                $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                $pagination.= "<div><a onclick=\"getSentMsg('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

                $pagination.= "<div><a onclick=\"getSentMsg('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
            }
            //close to end; only hide early pages
            else {
                $pagination.= "<div><a onclick=\"getSentMsg('1','" . $root_link . "')\">1</a></div>";
                $pagination.= "<div><a onclick=\"getSentMsg('2','" . $root_link . "')\">2</a></div>";
                $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page1)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<div><a onclick=\"getSentMsg('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                }
            }
        }

        //next button
        if ($page1 < $lastpage)
            $pagination.="<div><a onclick=\"getSentMsg('" . $next . "','" . $root_link . "');\" >next</a></div>";
        else
            $pagination.= "<span class=\"disabled\">next</span>";
        $pagination.= "</div>\n";
    }
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
    ?>
    <div class="dspmb_main_paging">
        <?php echo $pagination ?>
    </div>



</div>