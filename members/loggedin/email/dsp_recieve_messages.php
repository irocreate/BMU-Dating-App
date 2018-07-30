<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

$count_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE  receiver_id=$user_id AND delete_message=0");
 ?>
<div class="box-border">
    <div class="box-pedding">
       <?php if($count_messages > 0):?> 
        <div class="heading-submenu"><strong><?php echo language_code('DSP_MY_MESSAGES'); ?></strong></div>
        <form name="frmdelmessages" action="" method="post" class="dsp-form">
            <div class="gray-title-head">
                <div class="heading-top"><strong><?php echo language_code('DSP_SENDER') ?></strong></div>
                <strong><?php echo language_code('DSP_SUBJECT') ?></strong>
            </div>
            
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
                $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

                if ($check_couples_mode->setting_status == 'Y') {
                    $strQuery = "SELECT * FROM $dsp_user_emails_table m,$dsp_user_profiles p WHERE m.sender_id = p.user_id AND m.receiver_id=$user_id and m.delete_message=0 group by m.sender_id";
                } else {
                    $strQuery = "SELECT * FROM $dsp_user_emails_table m,$dsp_user_profiles p WHERE m.sender_id = p.user_id AND m.receiver_id=$user_id and m.delete_message=0 AND p.gender!='C' group by m.sender_id";
                }
                $intRecordsPerPage = 10;
                $intStartLimit = get('p'); # page selected 1,2,3,4...
                if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
                    $intStartLimit = 1; //default
                }
                $intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;
                if ($bolIfSearchCriteria) {
                    $strQuery = $strQuery . " ORDER BY sent_date desc ";
                    $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
                }
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
                $page_name = $root_link . "email/inbox/";
                $total_results1 = $user_count;
// Calculate total number of pages. Round up using ceil()
                //$total_pages1 = ceil($total_results1 / $max_results1); 
// ------------------------------------------------End Paging code------------------------------------------------------ // 
                $intTotalRecordsEffected = $user_count;
                if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
                    //print "Total records found: " . $intTotalRecordsEffected;
                }

                $my_messages = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");
                $dateTimeFormat = dsp_get_date_timezone();
                extract($dateTimeFormat);
                foreach ($my_messages as $message) {
                    $display_sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$message->sender_id'");
                    $message_date = date("$dateFormat $timeFormat", strtotime($message->sent_date));
                    $message_subject = $wpdb->get_row("SELECT subject,message_read FROM $dsp_user_emails_table WHERE receiver_id=$user_id AND sender_id = '$message->sender_id' order by sent_date desc LIMIT 1");

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
                    $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$message->sender_id'");
                    $exist_make_private->make_private;
                    $favt_mem = array();
                    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$message->sender_id'");
                    foreach ($private_mem as $private) {
                        $favt_mem[] = $private->favourite_user_id;
                    }
                    $count_member_new_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE message_read='N' AND receiver_id=$user_id AND sender_id='$message->sender_id'  AND delete_message=0");
                        
                    
                    $unreadClass = ($count_member_new_messages > 0)?'dspdp-bg-info':'';

                   ?>
                    <div class="dsp_vertical_scrollbar">
                    <?php if ($count_member_new_messages > 0) {
                                        ?>
                        <ul class="email-page <?php echo $unreadClass;?>" style="margin:0px; padding:0px; background: rgba(43, 36, 36, 0.2);">
                                <li style="width:5%; text-align:center;"><input type="checkbox" name="delmessage[]" value="<?php echo $message->sender_id ?>" /></li>
                                <li style="width:33%; margin-right:10px;"><?php
                                    if ($check_couples_mode->setting_status == 'Y') {
                                        if ($message->gender == 'C') {
                                            ?>
                                            <?php if ($exist_make_private->make_private == 'Y') { ?>

                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/my_profile/"; ?>"  class="dsp-email-photo">
                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:45px; height:45px;" class="img2" align="left"  alt="Private Photo"/>
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/my_profile/"; ?>"  class="dsp-email-photo">				
                                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"   style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo get_username($message->sender_id);?>"/></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/my_profile/"; ?>" class="dsp-email-photo">
                                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo get_username($message->sender_id);?>" />
                                                </a>
                                            <?php } ?>

                                        <?php } else { ?>

                                            <?php if ($exist_make_private->make_private == 'Y') { ?>

                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>"  class="dsp-email-photo">
                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:45px; height:45px;" class="img2" align="left" alt="Private Photo" />
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>"  class="dsp-email-photo">				
                                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"    style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo get_username($message->sender_id);?>" /></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>"  class="dsp-email-photo">
                                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo get_username($message->sender_id);?>" />
                                                </a>
                                            <?php } ?>

                                            <?php
                                        }
                                    } else {
                                        ?> 

                                        <?php if ($exist_make_private->make_private == 'Y') { ?>

                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>"  class="dsp-email-photo">
                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:45px; height:45px;" class="img2" align="left"  alt="private photo"/>
                                                </a>                
                                            <?php } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>"  class="dsp-email-photo">				
                                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"    style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo get_username($message->sender_id);?>" /></a>                
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>"  class="dsp-email-photo">
                                                <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo get_username($message->sender_id);?>" />
                                            </a>
                                        <?php } ?>

                                    <?php } ?>
                                    <span class="name">
                                        <?php
                                        if ($check_couples_mode->setting_status == 'Y') {
                                            if ($message->gender == 'C') {
                                                ?>
                                                <?php if ($exist_make_private->make_private == 'Y') { ?>
                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/my_profile/"; ?>">
                                                        <?php echo $display_sender_name->display_name; ?>
                                                    </a>
                                                <?php } else { ?>

                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>">
                                                        <?php echo $display_sender_name->display_name; ?>
                                                    </a>
                                                <?php } ?>


                                            <?php } else { ?>

                                                <?php if ($exist_make_private->make_private == 'Y') { ?>
                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>">
                                                        <?php echo $display_sender_name->display_name; ?>
                                                    </a>
                                                <?php } else { ?>

                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>">
                                                        <?php echo $display_sender_name->display_name; ?>
                                                    </a>
                                                <?php } ?>


                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <?php if ($exist_make_private->make_private == 'Y') { ?>
                                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>">
                                                    <?php echo $display_sender_name->display_name; ?>
                                                </a>
                                            <?php } else { ?>

                                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>">
                                                    <?php echo $display_sender_name->display_name; ?>
                                                </a>
                                            <?php } ?>
                                        <?php } ?>
                                        </a>

                                    </span>
                                    <br /><?php echo $message_date ?> </li>
                                    <?php if ($count_member_new_messages > 0) {
                                        ?>
                                        <li style="width:55%;"><a href="<?php echo $root_link . "email/view_message/sender_ID/" . $message->sender_id . "/Act/R/"; ?>
                                                                  "><?php echo str_replace('\\', '', $message_subject->subject); ?>&nbsp;<span >(<?php echo $count_member_new_messages ?>)</span></a></li>
                                        <?php } else { ?>
                                        <li  style="width:55%;"><a href="<?php echo $root_link . "email/view_message/sender_ID/" . $message->sender_id . "/"; ?>"><?php echo str_replace('\\', '', $message_subject->subject); ?></a></li>

                                    <?php } ?>
                        </ul>
                        <?php }else{ ?>
                        <ul class="email-page <?php echo $unreadClass;?>" style="margin:0px; padding:0px;">
                                <li style="width:5%; text-align:center;"><input type="checkbox" name="delmessage[]" value="<?php echo $message->sender_id ?>" /></li>
                                <li style="width:33%; margin-right:10px;"><?php
                                    if ($check_couples_mode->setting_status == 'Y') {
                                        if ($message->gender == 'C') {
                                            ?>
                                            <?php if ($exist_make_private->make_private == 'Y') { ?>

                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/my_profile/"; ?>"  class="dsp-email-photo">
                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:45px; height:45px;" class="img2" align="left"  alt="Private Photo"/>
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/my_profile/"; ?>"  class="dsp-email-photo">              
                                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"   style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo get_username($message->sender_id);?>"/></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/my_profile/"; ?>" class="dsp-email-photo">
                                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo get_username($message->sender_id);?>" />
                                                </a>
                                            <?php } ?>

                                        <?php } else { ?>

                                            <?php if ($exist_make_private->make_private == 'Y') { ?>

                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>"  class="dsp-email-photo">
                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:45px; height:45px;" class="img2" align="left" alt="Private Photo" />
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>"  class="dsp-email-photo">             
                                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"    style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo get_username($message->sender_id);?>" /></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>"  class="dsp-email-photo">
                                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo get_username($message->sender_id);?>" />
                                                </a>
                                            <?php } ?>

                                            <?php
                                        }
                                    } else {
                                        ?> 

                                        <?php if ($exist_make_private->make_private == 'Y') { ?>

                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>"  class="dsp-email-photo">
                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:45px; height:45px;" class="img2" align="left"  alt="private photo"/>
                                                </a>                
                                            <?php } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>"  class="dsp-email-photo">             
                                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"    style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo get_username($message->sender_id);?>" /></a>                
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>"  class="dsp-email-photo">
                                                <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo get_username($message->sender_id);?>" />
                                            </a>
                                        <?php } ?>

                                    <?php } ?>
                                    <span class="name">
                                        <?php
                                        if ($check_couples_mode->setting_status == 'Y') {
                                            if ($message->gender == 'C') {
                                                ?>
                                                <?php if ($exist_make_private->make_private == 'Y') { ?>
                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/my_profile/"; ?>">
                                                        <?php echo $display_sender_name->display_name; ?>
                                                    </a>
                                                <?php } else { ?>

                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>">
                                                        <?php echo $display_sender_name->display_name; ?>
                                                    </a>
                                                <?php } ?>


                                            <?php } else { ?>

                                                <?php if ($exist_make_private->make_private == 'Y') { ?>
                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>">
                                                        <?php echo $display_sender_name->display_name; ?>
                                                    </a>
                                                <?php } else { ?>

                                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>">
                                                        <?php echo $display_sender_name->display_name; ?>
                                                    </a>
                                                <?php } ?>


                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <?php if ($exist_make_private->make_private == 'Y') { ?>
                                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>">
                                                    <?php echo $display_sender_name->display_name; ?>
                                                </a>
                                            <?php } else { ?>

                                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>">
                                                    <?php echo $display_sender_name->display_name; ?>
                                                </a>
                                            <?php } ?>
                                        <?php } ?>
                                        </a>

                                    </span>
                                    <br /><?php echo $message_date ?> </li>
                                    <?php if ($count_member_new_messages > 0) {
                                        ?>
                                        <li style="width:55%;"><a href="<?php echo $root_link . "email/view_message/sender_ID/" . $message->sender_id . "/Act/R/"; ?>
                                                                  "><?php echo str_replace('\\', '', $message_subject->subject); ?>&nbsp;<span >(<?php echo $count_member_new_messages ?>)</span></a></li>
                                        <?php } else { ?>
                                        <li  style="width:55%;"><a href="<?php echo $root_link . "email/view_message/sender_ID/" . $message->sender_id . "/"; ?>"><?php echo str_replace('\\', '', $message_subject->subject); ?></a></li>

                                    <?php } ?>
                        </ul>
                        <?php } ?>
                    </div>
                    <?php
                    unset($favt_mem);
                }
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
            <div class="btn-delete">
                <input type="hidden" name="mode" id="mode" value="" />
                <input type="button" class="dsp_submit_button  dspdp-btn dspdp-btn-sm dspdp-btn-warning" name="delmsg" value="<?php echo language_code('DSP_DELETE_SELECTED') ?>" onclick="delete_dsp_emails();"/>
            </div>
        </form>
       <?php else: ?>
          <div class="heading-submenu"><strong><?php echo language_code('DSP_EMPTY'); ?></strong></div>
       <?php endif; ?> 
    </div>
</div>