<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$get_sender_id = get('sender_ID');
$messageIds = isset($_REQUEST['delmessage']) ? $_REQUEST['delmessage']  : get('delmessage');
$del_mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$Sender_IDs = isset($_REQUEST['sender_id']) ? $_REQUEST['sender_id'] : '';
$request_Action = get('Act');
if (($request_Action == "R") && ($get_sender_id != "")) {
    $wpdb->query("UPDATE $dsp_user_emails_table  SET message_read='Y' WHERE sender_id = '$get_sender_id'");
} // End if
if (($Sender_IDs != "") && ($del_mode == 'delete')) {
    foreach ($messageIds as $messageId) {
        $wpdb->query("UPDATE $dsp_user_emails_table SET send_del_message = 1  WHERE `message_id` = $messageId ");
    }
}
?>

<div class="box-border">
    <div class="box-pedding">
        
            <?php
            //+++++++++++++++++++++++++++++++++++++++++++++++++++++++
            if (get('page'))
                $page = get('page');
            else
                $page = 1;

            // How many adjacent pages should be shown on each side?
            $adjacents = 2;
            $limit = 10;
            if ($page)
                $start = ($page - 1) * $limit;    //first item to display on this page
            else
                $start = 0;
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++
            $bolIfSearchCriteria = true;
            if ($check_couples_mode->setting_status == 'Y') {
                $strQuery = "SELECT * FROM $dsp_user_emails_table m, $dsp_user_profiles p WHERE m.receiver_id = p.user_id AND m.sender_id=$user_id and m.send_del_message=0";
            } else {
                $strQuery = "SELECT * FROM $dsp_user_emails_table m, $dsp_user_profiles p WHERE m.receiver_id = p.user_id AND m.sender_id=$user_id AND m.send_del_message=0 AND p.gender!='C'";
            }

            $intRecordsPerPage = 10;
            $intStartLimit = get('p'); # page selected 1,2,3,4...
            if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
                $intStartLimit = 1; //default
            }
            $intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;
            if ($bolIfSearchCriteria) {
                $strQuery = $strQuery . "  ORDER BY thread_id desc";
                $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
            }
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
            $page_name = $root_link . "email/sent/";
            $total_results1 = $user_count;
// Calculate total number of pages. Round up using ceil()
            //$total_pages1 = ceil($total_results1 / $max_results1); 
// ------------------------------------------------End Paging code------------------------------------------------------ // 
            $intTotalRecordsEffected = $user_count;
            if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
                //print "Total records found: " . $intTotalRecordsEffected;
            }
            $dateTimeFormat = dsp_get_date_timezone();
            extract($dateTimeFormat);
            $my_messages = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");
            if(!empty($my_messages)){
            ?>
                <form name="frmdelmessages" action="" method="post"  class="dsp-form">
                    <div class="dsp_back_inbox dspdp-spacer">
                        <a class="dspdp-btn dspdp-btn-xs dspdp-btn-default" href="<?php echo $root_link . "email/inbox/"; ?>"><?php echo language_code('DSP_BACK_TO_INBOX') ?></a>
                    </div>
                    <div class="clearfix"></div>    
            <?php
            foreach ($my_messages as $message) {
                $display_sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$message->receiver_id'");
                $message_date = date("$dateFormat $timeFormat", strtotime($message->sent_date));
                $value = $message->message_id;

                /* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

                if ($page == 0)
                    $page = 1;     //if no page var is given, default to 1.
                $prev = $page - 1;
                $next = $page + 1;
                $lastpage = ceil($total_results1 / $limit);
                ;  //lastpage is = total pages / items per page, rounded up.
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
                        $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "page/$prev\">".language_code('DSP_PREVIOUS')."</a></div>";
                    else
                        $pagination.= "<span  class='disabled'>".language_code('DSP_PREVIOUS')."</span>";

                    //pages 
                    if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
                        for ($counter = 1; $counter <= $lastpage; $counter++) {
                            if ($counter == $page)
                                $pagination.= "<span class='current'>$counter</span>";
                            else
                                $pagination.= "<div><a href=\"" . $page_name . "page/$counter\">$counter</a></div>";
                        }
                    }
                    elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
                        //close to beginning; only hide later pages
                        if ($page < 1 + ($adjacents * 2)) {
                            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                                if ($counter == $page)
                                    $pagination.= "<span class='current'>$counter</span>";
                                else
                                    $pagination.= "<div><a href=\"" . $page_name . "page/$counter\">$counter</a></div>";
                            }
                            $pagination.= "<span>...</span>";
                            $pagination.= "<div><a href=\"" . $page_name . "page/$lpm1\">$lpm1</a></div>";
                            $pagination.= "<div><a href=\"" . $page_name . "page/$lastpage\">$lastpage</a></div>";
                        }
                        //in middle; hide some front and some back
                        elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                            $pagination.= "<div><a href=\"" . $page_name . "page/1\">1</a></div>";
                            $pagination.= "<div><a href=\"" . $page_name . "page/2\">2</a></div>";
                            $pagination.= "<span>...</span>";
                            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                                if ($counter == $page)
                                    $pagination.= "<div class='current'>$counter</div>";
                                else
                                    $pagination.= "<div><a href=\"" . $page_name . "page/$counter\">$counter</a></div>";
                            }
                            $pagination.= "<span>...</span>";
                            $pagination.= "<div><a href=\"" . $page_name . "page/$lpm1\">$lpm1</a></div>";
                            $pagination.= "<div><a href=\"" . $page_name . "page/$lastpage\">$lastpage</a></div>";
                        }
                        //close to end; only hide early pages
                        else {
                            $pagination.= "<div><a href=\"" . $page_name . "page/1\">1</a></div>";
                            $pagination.= "<div><a href=\"" . $page_name . "page/2\">2</a></div>";
                            $pagination.= "<span>...</span>";
                            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                                if ($counter == $page)
                                    $pagination.= "<span class='current'>$counter</span>";
                                else
                                    $pagination.= "<div><a href=\"" . $page_name . "page/$counter\">$counter</a></div>";
                            }
                        }
                    }

                    //next button
                    if ($page < $counter - 1)
                        $pagination.= "<div><a class='disabled'  href=\"" . $page_name . "page/$next\">".language_code('DSP_NEXT')."</a></div>";
                    else
                        $pagination.= "<span class='disabled'>".language_code('DSP_NEXT')."</span>";
                    $pagination.= "</div>\n";
                }

                /* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
                $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$message->receiver_id'");
                $favt_mem = array();
                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$message->receiver_id'");
                foreach ($private_mem as $private) {
                    $favt_mem[] = $private->favourite_user_id;
                }
                ?>
                <ul class="sent-message-page clearfix">
                    <li class="dspdp-clearfix">
                        <span class="dspdp-check" style="float:left; margin-top:20px;"><input type="checkbox" name="delmessage[]" value="<?php echo $value ?>" /></span>
                        <span class="image">
                            <?php
                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($message->gender == 'C') {
                                    ?>

                                    <?php if ($exist_make_private->make_private == 'Y') { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($message->receiver_id) . "/my_profile/"; ?>" >
                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  class="img" align="left" alt="Private Photo"/>
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($message->receiver_id) . "/my_profile/"; ?>" >                
                                                <img src="<?php echo display_members_photo($message->receiver_id, $imagepath); ?>"    class="img" align="left" alt="<?php echo get_username($message->receiver_id);?>"/></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>

                                        <a href="<?php echo $root_link . get_username($message->receiver_id) . "/my_profile/"; ?>">
                                            <img src="<?php echo display_members_photo($message->receiver_id, $imagepath); ?>"  class="img" align="left" alt="<?php echo get_username($message->receiver_id);?>"/></a>
                                    <?php } ?>

                                <?php } else { ?>

                                    <?php if ($exist_make_private->make_private == 'Y') { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($message->receiver_id) . "/"; ?>" >
                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  class="img" align="left" alt="Private Photo" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($message->receiver_id) . "/"; ?>" >               
                                                <img src="<?php echo display_members_photo($message->receiver_id, $imagepath); ?>"   class="img" align="left" alt="<?php echo get_username($message->receiver_id);?>"/></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>

                                        <a href="<?php echo $root_link . get_username($message->receiver_id) . "/"; ?>">
                                            <img src="<?php echo display_members_photo($message->receiver_id, $imagepath); ?>"  class="img" align="left" alt="<?php echo get_username($message->receiver_id);?>" /></a>
                                    <?php } ?>
                                    <?php
                                }
                            } else {
                                ?> 
                                <?php if ($exist_make_private->make_private == 'Y') { ?>

                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                        <a href="<?php echo $root_link . get_username($message->receiver_id) . "/"; ?>" >
                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  class="img" align="left" alt="Private Photo" />
                                        </a>                
                                    <?php } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($message->receiver_id) . "/"; ?>" >               
                                            <img src="<?php echo display_members_photo($message->receiver_id, $imagepath); ?>"   class="img" align="left" alt="<?php echo get_username($message->receiver_id);?>" /></a>                
                                        <?php
                                    }
                                } else {
                                    ?>

                                    <a href="<?php echo $root_link . get_username($message->receiver_id) . "/"; ?>">
                                        <img src="<?php echo display_members_photo($message->receiver_id, $imagepath); ?>"  class="img" align="left" alt="<?php echo get_username($message->receiver_id);?>" /></a>
                                <?php } ?>

                            <?php } ?>
                        </span>
                        <div class="msg-info">
                            <ul>
                                <li class="name age-text dspdp-bold"><?php echo $display_sender_name->display_name; ?></li>                              
                                <li><?php echo language_code('DSP_SENT'). ' : '.$message_date ?></li>
                                <li><?php echo language_code('DSP_SUBJECT'). ' : '.$message->subject ?></li>
                                <li><?php
                                        $msg = str_replace("\\", "", $message->text_message);
                                        $desired_length=50;
                                        if( strlen($msg) > $desired_length )
                                        {    
                                            $cut_length=strpos($msg,' ',$desired_length);
                                            echo substr($msg, 0, $cut_length);
                                            if ( strlen($msg) > $cut_length )
                                            {    
                                                echo '<span class="show_more_text" style="cursor:pointer;color:green;"> .....more</span>';
                                                echo '<span class="long_message" style="display:none;width:200%;">'.substr($msg, $cut_length).'</span>';
                                                echo '<span class="hide_more_text" style="display:none;cursor:pointer;color:red;"> (Hide) </span>';
                                            }    
                                        }
                                        else
                                        {
                                            echo $msg;
                                        }
                                    ?>
                                </li>
                                <?php if ($message->sender_id != $user_id) { ?>

                                    <li><a href="<?php echo $root_link . "email/compose/sender_ID/" . $message->sender_id . "/Act/Reply/"; ?>"><?php echo language_code('DSP_MESSAGE_REPLY') ?></a></li>
                                <?php } //end if ?>
                            </ul>
                        </div>
                        <div class="read-message">
                            <?php
                            $read_msg = $message->message_read;
                            if ($read_msg == 'Y') {
                                ?>

                                <img src="<?php echo $pluginpath ?>/images/env_read.jpg" title="<?php echo language_code('DSP_EMAIL_READ') ?>"  alt="env_read"/>
                            <?php } else { ?>
                                <img src="<?php echo $pluginpath ?>/images/env_unread.jpg" title="<?php echo language_code('DSP_EMAIL_UNREAD') ?>" alt="env_unread"/>
                            <?php } ?>
                        </div>
                    </li>
                </ul>
                    <?php
                    unset($favt_mem);
                } // End for loop 
                ?>
                <div style="float:left; width:100%;">
                    <?php
                    // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                    if (isset($pagination))
                        echo $pagination;
                    else
                        echo '';
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                    ?>
                </div>
                <div class="btn-row">
                    <input type="hidden" name="mode" id="mode" value="" />
                    <input type="hidden" name="sender_id" id="sender_id" value="<?php echo $message->sender_id ?>" />
                    <input type="button" class="dsp_submit_button  dspdp-btn dspdp-btn-sm dspdp-btn-warning" name="delmsg" value="<?php echo language_code('DSP_DELETE_SELECTED') ?>" onclick="delete_dsp_emails();"/>
                </div>
        </form>
    <?php }else{ ?>
      <div class="heading-submenu"><strong><?php echo language_code('DSP_EMPTY'); ?></strong></div>
    <?php }?>
    </div>
</div>

<script>

jQuery(document).ready(function(){
    jQuery('.show_more_text').click(function(){
            var current_element = jQuery(this);
            current_element.hide();
            current_element.next('span').css('display','block');
            current_element.next('span').next('span').show();
    });
    jQuery('.hide_more_text').click(function(){
            var current_element = jQuery(this);
            current_element.hide();
            current_element.prev('span').hide();
            current_element.prev('span').prev('span').show();    
            var window_height=jQuery(window).height();
            var text_height=current_element.prev('span').height();

            if( text_height >= (window_height/5) )
            {
                jQuery('html, body').animate({
                    scrollTop: current_element.parent().siblings('.name').offset().top
                }, 1000);
            }
    });
});

</script>