<?php
global $wpdb;
include("../../../../wp-config.php");
include( WP_DSP_ABSPATH . 'mobile/files/includes/english.php');  // include all table names file

$DSP_EMAILS_TABLE = $wpdb->prefix . DSP_EMAILS_TABLE;
$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$DSP_USER_PROFILES_TABLE = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
include_once(WP_DSP_ABSPATH . 'mobile/dsp_get_image.php');
include_once(WP_DSP_ABSPATH . '/general_settings.php');
$image_path = get_bloginfo('url') . '/wp-content/';  // image Path
$current_user = wp_get_current_user();
$user_id = $current_user->ID;  // print session USER_ID
$root_link = $_GET['root_link'];
?>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
    <?php
    $pagination_limit = DSP_PAGINATION_LIMIT;

    // ----------------------------------------------- Start Paging code ------------------------------------------------------ // 
    if (isset($_GET['page1']))
        $page1 = $_GET['page1'];
    else
        $page1 = 1;

    $max_results1 = $pagination_limit;

    $adjacents = DSP_PAGINATION_ADJACENTS;
    $limit = $max_results1;

    $from1 = (($page1 * $max_results1) - $max_results1);

    if ($check_couples_mode->setting_status == 'Y') {
        $totalQuery = "SELECT * FROM $DSP_EMAILS_TABLE m,$DSP_USER_PROFILES_TABLE p WHERE m.sender_id = p.user_id AND m.receiver_id=$user_id and m.delete_message=0 group by m.sender_id Order by sent_date desc";
    } else {

        $totalQuery = "SELECT * FROM $DSP_EMAILS_TABLE m,$DSP_USER_PROFILES_TABLE p WHERE m.sender_id = p.user_id AND m.receiver_id=$user_id and m.delete_message=0 AND p.gender!='C' group by m.sender_id Order by sent_date desc";
    }

    $total_results1 = $wpdb->get_results($totalQuery);

    // Calculate total number of pages. Round up using ceil()
    $total_pages1 = count($total_results1);
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

        $display_sender_name = $wpdb->get_row("SELECT * FROM $DSP_USERS_TABLE WHERE ID = '$message->sender_id'");

        $message_detail = $wpdb->get_row("SELECT subject,text_message,sent_date,message_id FROM $DSP_EMAILS_TABLE WHERE receiver_id=$user_id AND sender_id = '$message->sender_id' order by sent_date desc LIMIT 1");
        $message_date = date("Y d M g:i a", strtotime($message_detail->sent_date));
        $message_id = $message_detail->message_id;
        $message_subject = $message_detail->subject;
        $message_text = substr($message_detail->text_message, 0, 15);
        $count_member_new_messages = $wpdb->get_var("SELECT COUNT(*) FROM $DSP_EMAILS_TABLE WHERE message_read='N' AND receiver_id=$user_id AND sender_id='$message->sender_id'");
        $count_total_messages = $wpdb->get_var("SELECT COUNT(*) FROM $DSP_EMAILS_TABLE WHERE  delete_message=0 AND receiver_id=$user_id AND sender_id='$message->sender_id' ");

        // check for private pic
        $exist_make_private = $wpdb->get_row("SELECT * FROM $DSP_USER_PROFILES_TABLE WHERE user_id='$message->sender_id'");

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
                                <img src="<?php echo $image_path . 'plugins/dsp_dating/mobile/images/arrow.png' ?>"/>
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
                                                    echo add_query_arg(array('pid' => 3,
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
                                                    echo add_query_arg(array('pid' => 3,
                                                        'mem_id' => $message->sender_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >

                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:45px; height:45px;" class="img2" align="left"  />

                                                    </a>                

                                                <?php } else {
                                                    ?>

                                                    <a href="<?php
                                                    echo add_query_arg(array('pid' => 3,
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
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $message->sender_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>">

                                                    <img src="<?php echo display_members_photo_mb($message->sender_id, $image_path); ?>" style="width:45px; height:45px;" class="img2" align="left" />

                                                </a>

                                            <?php } // end of else pic is not private   ?>



                                        <?php } // end of else ?>
                                        <!--<a href="<?php
                                        echo add_query_arg(array(
                                            'pid' => 3,
                                            'mem_id' => $message->sender_id,
                                            'pagetitle' => 'view_profile'), $root_link);
                                        ?>">
                                        <img src="<?php echo display_members_photo_mb($message->sender_id, $pluginpath); ?>" width="45px" height="45px" class="img2" align="left" /></a>-->
                                    </td>
                                    <td>
                                        <span class="dsp_mb_name"><a href="<?php
                                            echo add_query_arg(array(
                                                'pid' => 3, 'mem_id' => $message->sender_id,
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
                                                'pid' => 14, 'Act' => 'R', 'pagetitle' => 'my_email',
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
                </table>
            </td>
        </tr>

        <?php
        $i++;
        unset($favt_mem);
    } // for each end  
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
</table>
