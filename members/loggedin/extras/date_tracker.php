<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
?>
<?php
// ----------------------------------------------- Start Paging code------------------------------------------------------ //  
$dsp_date_tracker_table = $wpdb->prefix . DSP_DATE_TRACKER_TABLE;
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_date_tracker_message_table = $wpdb->prefix . DSP_DATE_TRACKER_MESSAGE_TABLE;
if (get('mode') == 'del') {
    ?>
    <script> location.href = '<?php echo $root_link . "extras/date_tracker/"; ?>'</script>
    <?php
    //$wpdb->query("DELETE FROM $dsp_date_tracker_message_table where t_message_id =".$_GET['msg']); 
}
if (get('mode') == 'del_user') {
    $wpdb->query("DELETE FROM $dsp_date_tracker_table where member_id =" . get('uid'));
    $wpdb->query("DELETE FROM $dsp_date_tracker_message_table where t_receiver_id =" . get('uid'));
}
if (get('page'))
    $page = get('page');
else
    $page = 1;

// How many adjacent pages should be shown on each side?
$adjacents = 2;
$limit = 5;
if ($page)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
if ($check_couples_mode->setting_status == 'Y') {
    $strQuery = "SELECT * FROM $dsp_date_tracker_table tracker, $dsp_user_profiles_table p WHERE tracker.user_id=p.user_id AND tracker.user_id=$user_id ";
} else {
    $strQuery = "SELECT * FROM $dsp_date_tracker_table tracker, $dsp_user_profiles_table p WHERE tracker.user_id=p.user_id AND tracker.user_id=$user_id AND p.gender!='C'";
}
$intRecordsPerPage = 1;
$intStartLimit = get('p'); # page selected 1,2,3,4...
if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
    $intStartLimit = 1; //default
}
$intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;

$strQuery = $strQuery . " ORDER BY p.user_profile_id desc";

$user_count = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_date_tracker_table AS total where user_id='$user_id'");
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
$page_name = $root_link . "extras/date_tracker/";
$total_results1 = $user_count;
// Calculate total number of pages. Round up using ceil()
//$total_pages1 = ceil($total_results1 / $max_results1); 
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
        $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "page/$prev/\">previous</a></div>";
    else
        $pagination.= "<span  class='disabled'>previous</span>";

    //pages	
    if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
        for ($counter = 1; $counter <= $lastpage; $counter++) {
            if ($counter == $page)
                $pagination.= "<span class='current'>$counter</span>";
            else
                $pagination.= "<div><a href=\"" . $page_name . "page/$counter/\">$counter</a></div>";
        }
    }
    elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
        //close to beginning; only hide later pages
        if ($page < 1 + ($adjacents * 2)) {
            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                if ($counter == $page)
                    $pagination.= "<span class='current'>$counter</span>";
                else
                    $pagination.= "<div><a href=\"" . $page_name . "page/$counter/\">$counter</a></div>";
            }
            $pagination.= "<span>...</span>";
            $pagination.= "<div><a href=\"" . $page_name . "page/$lpm1/\">$lpm1</a></div>";
            $pagination.= "<div><a href=\"" . $page_name . "page/$lastpage/\">$lastpage</a></div>";
        }
        //in middle; hide some front and some back
        elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
            $pagination.= "<div><a href=\"" . $page_name . "page/1/\">1</a></div>";
            $pagination.= "<div><a href=\"" . $page_name . "page/2/\">2</a></div>";
            $pagination.= "<span>...</span>";
            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                if ($counter == $page)
                    $pagination.= "<div class='current'>$counter</div>";
                else
                    $pagination.= "<div><a href=\"" . $page_name . "page/$counter/\">$counter</a></div>";
            }
            $pagination.= "<span>...</span>";
            $pagination.= "<div><a href=\"" . $page_name . "page/$lpm1/\">$lpm1</a></div>";
            $pagination.= "<div><a href=\"" . $page_name . "page/$lastpage/\">$lastpage</a></div>";
        }
        //close to end; only hide early pages
        else {
            $pagination.= "<div><a href=\"" . $page_name . "page/1/\">1</a></div>";
            $pagination.= "<div><a href=\"" . $page_name . "page/2/\">2</a></div>";
            $pagination.= "<span>...</span>";
            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination.= "<span class='current'>$counter</span>";
                else
                    $pagination.= "<div><a href=\"" . $page_name . "page/$counter/\">$counter</a></div>";
            }
        }
    }

    //next button
    if ($page < $counter - 1)
        $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "page/$next/\">next</a></div>";
    else
        $pagination.= "<span class='disabled'>next</span>";
    $pagination.= "</div>\n";
}
// ------------------------------------------------End Paging code------------------------------------------------------ // 
$intTotalRecordsEffected = $user_count;
if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
    //print "Total records found: " . $intTotalRecordsEffected;
} else {
    ?>

    <div class="box-border">
        <div class="box-pedding">
            <div class="page-not-found error">
                <?php echo language_code('DSP_NO_RECORD_FOUND_EXTRAS'); ?><br /><br />
            </div>
        </div>
    </div>
    <?php
} // if ($intTotalRecordsEffected != '0')	
//echo $strQuery ." LIMIT " . $from1 . "," . $max_results1;
$search_members = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");
//echo $strQuery ." LIMIT " . $from1 . "," . $max_results1; 
foreach ($search_members as $member1) {
    $member1->user_id;
    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->member_id'");
    $s_user_id = $member->user_id;
    $s_gender = $member->gender;
    $s_make_private = $member->make_private;
    $displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$s_user_id'");
    $tracker_message = $wpdb->get_results("select * from $dsp_date_tracker_message_table where t_sender_id='$user_id' AND t_receiver_id='$s_user_id'");
    $msgcount = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_date_tracker_message_table where t_sender_id='$user_id' AND t_receiver_id='$s_user_id'");
    $favt_mem = array();
    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");
    foreach ($private_mem as $private) {
        $favt_mem[] = $private->favourite_user_id;
    }

    if ($msgcount == 0) {
        ?>

        <?php
        if (isset($_POST['submit']) && $_POST['date_id'] == $s_user_id) {
            $msg = isset($_REQUEST['txtmessage']) ? esc_sql(sanitizeData(trim($_REQUEST['txtmessage']), 'xss_clean')) : '';
            $r_user_id = isset($_REQUEST['date_id']) ? $_REQUEST['date_id'] : '';
            $wpdb->query("INSERT INTO $dsp_date_tracker_message_table SET t_sender_id='$user_id',t_receiver_id='$r_user_id',	t_message='$msg' , 	t_status='1'");
            ?>
            <script> location.href = '<?php echo $root_link . "extras/date_tracker/"; ?>'</script>
        <?php } ?>
        <script>
            function dsp_profile_validation() {
                if (document.form1.txtmessage.value == '')
                {
                    alert("Please enter the message");
                    document.form1.txtmessage.focus();
                    return false;
                }

            }
        </script>
        <div class="heading-submenu dsp-block" style="display:none"><?php echo language_code('DSP_DATE_TRACKER') ?></div>
        <div class="dsp-date-tracker box-border magn-top-15 image-container clearfix">
            <div class="box-pedding" style="text-align:center;">
                <form action="" method="post" name="form1" >
                    <ul class="date-traker">
                        <li style="width:15%; margin-left: 10px;" class="circle-image"><?php
                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($s_gender == 'C') {
                                    ?>

                                    <?php if ($s_make_private == 'Y') { ?>

                                        <?php if ($current_user->ID != $s_user_id) { ?>

                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"style="width:100px; height:100px;"     border="3" class="img" alt="Private Photo"/>
                                                </a>                
                                            <?php } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >				
                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="3" class="img"  alt="<?php echo get_username($s_user_id);?>"/></a>                
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                            </a>
                                        <?php } ?>

                                    <?php } else { ?>

                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                        </a>
                                    <?php } ?>

                                <?php } else { ?>

                                    <?php if ($s_make_private == 'Y') { ?>

                                        <?php if ($current_user->ID != $s_user_id) { ?>

                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"style="width:100px; height:100px;"     border="3" class="img" alt="Private Photo"/>
                                                </a>                
                                            <?php } else {
                                                ?>
                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >				
                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" /></a>                
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                            </a>
                                        <?php } ?>
                                    <?php } else { ?>

                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>"/>
                                        </a>
                                    <?php } ?>
                                    <?php
                                }
                            } else {
                                ?> 

                                <?php if ($s_make_private == 'Y') { ?>
                                    <?php if ($current_user->ID != $s_user_id) { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"style="width:100px; height:100px;"     border="3" class="img" alt="Private Photo" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >				
                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>"/></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                        </a>
                                    <?php } ?>

                                <?php } else { ?>

                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                    </a>
                                <?php } ?>

                            <?php } ?>
                            </a>
                        </li>


                        <li class="dsp_page_link" style="text-align:left; margin-top:6px;"><strong>

                                <?php
                                if ($check_couples_mode->setting_status == 'Y') {
                                    if ($s_gender == 'C') {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                            <?php
                                            if (strlen($displayed_member_name->display_name) > 15)
                                                echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                            else
                                                echo $displayed_member_name->display_name;
                                            ?>                
                                        <?php } else { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                <?php
                                                if (strlen($displayed_member_name->display_name) > 15)
                                                    echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                else
                                                    echo $displayed_member_name->display_name;
                                                ?>
                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">

                                                <?php
                                                if (strlen($displayed_member_name->display_name) > 15)
                                                    echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                else
                                                    echo $displayed_member_name->display_name;
                                                ?>
                                            <?php } ?>
                                        </a>
                                        </strong>
                                        <br />

                                        <div class="text-area dsp-sm-6 dspdp-col-sm-9" style="margin-top:6px"><textarea name="txtmessage" class="dsp-form-control dspdp-form-control" cols="43" rows="6">&nbsp;</textarea></div>
                                    </li>
                                     <li style="margin-top:6px; float:left;" class="btn-row"> <input type="hidden" name="date_id" value="<?php echo $s_user_id; ?>" />
                                    <input name="submit" type="submit" class="dsp_submit_button dspdp-btn dspdp-btn-default btn-search" value="Save" onclick="return dsp_profile_validation();" /></li></ul>
                                </form>
                                </div>
                                </div>
                                <?php
                            }else {
                                foreach ($tracker_message as $message) {
                                    ?>

                                    <div class="dsp-date-tracker box-border magn-top-15 image-container clearfix">
                                        <div class="box-pedding" style="text-align: left; float: left; width: 100% ! important;">
                                            <?php if ((get('mode') == 'edit') && (get('msg') == $message->t_message_id)) { ?>
                                                <?php
                                                if (isset($_POST['edit'])) {
                                                    $msg_id = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : get('msg');
                                                    $msg = isset($_REQUEST['txtmessage']) ? esc_sql(sanitizeData(trim($_REQUEST['txtmessage']), 'xss_clean')) : '';
                                                    $wpdb->query("UPDATE $dsp_date_tracker_message_table SET t_message='$msg' WHERE t_message_id  = '$msg_id'");
                                                    ?>
                                                    <script> location.href = '<?php echo $root_link . "extras/date_tracker/"; ?>'</script>
                                                    <?php
                                                }
                                                ?>
                                                <form action="" method="post" name="datetrakermsgform">
                                                    <ul class="date-traker">
                                                        <li style="width:15%;margin-left: 10px;" class="circle-image">	
                                                            <?php
                                                            if ($check_couples_mode->setting_status == 'Y') {
                                                                if ($s_gender == 'C') {
                                                                    ?>

                                                                    <?php if ($s_make_private == 'Y') { ?>

                                                                        <?php if ($current_user->ID != $s_user_id) { ?>

                                                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"style="width:100px; height:100px;"   alt="Private Photo"  border="3" class="img" />
                                                                                </a>                
                                                                            <?php } else {
                                                                                ?>
                                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >				
                                                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" /></a>                
                                                                                <?php
                                                                            }
                                                                        } else {
                                                                            ?>
                                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                                                            </a>
                                                                        <?php } ?>

                                                                    <?php } else { ?>

                                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                                                        </a>
                                                                    <?php } ?>

                                                                <?php } else { ?>

                                                                    <?php if ($s_make_private == 'Y') { ?>

                                                                        <?php if ($current_user->ID != $s_user_id) { ?>

                                                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" alt="Private Photo"    border="3" class="img" />
                                                                                </a>                
                                                                            <?php } else {
                                                                                ?>
                                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >				
                                                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" /></a>                
                                                                                <?php
                                                                            }
                                                                        } else {
                                                                            ?>
                                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                                                            </a>
                                                                        <?php } ?>
                                                                    <?php } else { ?>

                                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                                                        </a>
                                                                    <?php } ?>
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?> 

                                                                <?php if ($s_make_private == 'Y') { ?>
                                                                    <?php if ($current_user->ID != $s_user_id) { ?>

                                                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;"   alt="Private Photo"  border="3" class="img" />
                                                                            </a>                
                                                                        <?php } else {
                                                                            ?>
                                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >				
                                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>"/></a>                
                                                                            <?php
                                                                        }
                                                                    } else {
                                                                        ?>
                                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                                                        </a>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  border="3" class="img" alt="<?php echo get_username($s_user_id);?>"/>
                                                                    </a>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            </a>
                                                        </li>
                                                        <li class="dsp_page_link"><strong>

                                                                <?php
                                                                if ($check_couples_mode->setting_status == 'Y') {
                                                                    if ($s_gender == 'C') {
                                                                        ?>
                                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                                                            <?php
                                                                            if (strlen($displayed_member_name->display_name) > 15)
                                                                                echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                                            else
                                                                                echo $displayed_member_name->display_name;
                                                                            ?>                
                                                                        <?php } else { ?>
                                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                                                <?php
                                                                                if (strlen($displayed_member_name->display_name) > 15)
                                                                                    echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                                                else
                                                                                    echo $displayed_member_name->display_name;
                                                                                ?>
                                                                                <?php
                                                                            }
                                                                        } else {
                                                                            ?> 
                                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">

                                                                                <?php
                                                                                if (strlen($displayed_member_name->display_name) > 15)
                                                                                    echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                                                else
                                                                                    echo $displayed_member_name->display_name;
                                                                                ?>
                                                                            <?php } ?>
                                                                        </a>
                                                                        </strong>
                                                                        <br />
                                                                        <div class="text-area dsp-sm-6 dspdp-col-sm-9" style="margin-top:6px"><textarea name="txtmessage" class="dsp-form-control dspdp-form-control" cols="43" rows="6"><?php echo str_replace("\\", "", $message->t_message); ?></textarea></div>
                                                                        </li>
                                                                        <li  style="margin-top:6px; float:left;" class="btn-row"><input name="edit" class="dsp_submit_button dspdp-btn dspdp-btn-default btn-search" type="submit" value="Edit" /></li>
                                                                        </ul>
                                                                        </form>
                                                                    <?php } else { ?>
                                                                        <ul class="date-traker">
                                                                            <li style="width:15%; margin-left: 10px;" class="circle-image">
                                                                                <?php
                                                                                if ($check_couples_mode->setting_status == 'Y') {
                                                                                    if ($s_gender == 'C') {
                                                                                        ?>

                                                                                        <?php if ($s_make_private == 'Y') { ?>

                                                                                            <?php if ($current_user->ID != $s_user_id) { ?>

                                                                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                                                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"style="width:100px; height:100px;"  alt="Private Photo"   border="3" class="img" />
                                                                                                    </a>                
                                                                                                <?php } else {
                                                                                                    ?>
                                                                                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >				
                                                                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" /></a>                
                                                                                                    <?php
                                                                                                }
                                                                                            } else {
                                                                                                ?>
                                                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                                                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                                                                                </a>
                                                                                            <?php } ?>

                                                                                        <?php } else { ?>

                                                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                                                                            </a>
                                                                                        <?php } ?>

                                                                                    <?php } else { ?>

                                                                                        <?php if ($s_make_private == 'Y') { ?>

                                                                                            <?php if ($current_user->ID != $s_user_id) { ?>

                                                                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                                                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"style="width:100px; height:100px;"   alt="Private Photo"  border="3" class="img" />
                                                                                                    </a>                
                                                                                                <?php } else {
                                                                                                    ?>
                                                                                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >				
                                                                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>"/></a>                
                                                                                                    <?php
                                                                                                }
                                                                                            } else {
                                                                                                ?>
                                                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                                                                                </a>
                                                                                            <?php } ?>
                                                                                        <?php } else { ?>

                                                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                                                                            </a>
                                                                                        <?php } ?>
                                                                                        <?php
                                                                                    }
                                                                                } else {
                                                                                    ?> 
                                                                                    <?php if ($s_make_private == 'Y') { ?>
                                                                                        <?php if ($current_user->ID != $s_user_id) { ?>

                                                                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"style="width:100px; height:100px;"  alt="Private Photo"   border="3" class="img" />
                                                                                                </a>                
                                                                                            <?php } else {
                                                                                                ?>
                                                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >				
                                                                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>"/></a>                
                                                                                                <?php
                                                                                            }
                                                                                        } else {
                                                                                            ?>
                                                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                                                                            </a>
                                                                                        <?php } ?>

                                                                                    <?php } else { ?>

                                                                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="3" class="img" alt="<?php echo get_username($s_user_id);?>" />
                                                                                        </a>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                                </a>
                                                                            </li>
                                                                            <li class="dsp_page_link"><strong>
                                                                                    <?php
                                                                                    if ($check_couples_mode->setting_status == 'Y') {
                                                                                        if ($s_gender == 'C') {
                                                                                            ?>
                                                                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                                                                <?php
                                                                                                if (strlen($displayed_member_name->display_name) > 15)
                                                                                                    echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                                                                else
                                                                                                    echo $displayed_member_name->display_name;
                                                                                                ?>                
                                                                                            <?php } else { ?>
                                                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                                                                    <?php
                                                                                                    if (strlen($displayed_member_name->display_name) > 15)
                                                                                                        echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                                                                    else
                                                                                                        echo $displayed_member_name->display_name;
                                                                                                    ?>
                                                                                                    <?php
                                                                                                }
                                                                                            } else {
                                                                                                ?> 
                                                                                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">

                                                                                                    <?php
                                                                                                    if (strlen($displayed_member_name->display_name) > 15)
                                                                                                        echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                                                                    else
                                                                                                        echo $displayed_member_name->display_name;
                                                                                                    ?>
                                                                                                <?php } ?>
                                                                                            </a>
                                                                                            </strong>
                                                                                            <br />
                                                                                            <p><?php echo str_replace("\\", "", $message->t_message); ?></p>
                                                                                        <?php } ?>
                                                                                        </li>
                                                                                        </ul>
                                                                                        <style>
                                                                                            .sample_popup{position:fixed;
                                                                                                          z-index: 100;  
                                                                                                          top:50%;  
                                                                                                          left:50%;  
                                                                                                          width:360px;  
                                                                                                          height:130px;
                                                                                                          margin-left:-100px;
                                                                                            }  
                                                                                        </style>
                                                                                        <div class="row-btn-traker">
                                                                                            <span class="dsp-none">
                                                                                                <a href="<?php echo $root_link . "extras/date_tracker/mode/edit/msg/" . $message->t_message_id . "/"; ?>"><?php echo language_code('DSP_EDIT');?></a>
                                                                                            </span>
                                                                                            <span class="dsp-none">-</span>
                                                                                            <span>
                                                                                            <span class="dsp-none" onclick="popup_show('popup', 'popup_drag', 'popup_exit', 'screen-center', 0, 0);" style="color:#365490; cursor:pointer"><?php echo language_code('DSP_DELETE');?></span>

                                                                                            <span class="dsp-block" style="display:none">
                                                                                                
                                                                                                <a class="" href="<?php echo $root_link . "extras/date_tracker/mode/edit/msg/" . $message->t_message_id . "/"; ?>"><input type="button" class="btn btn-danger custom-edit-button" value="<?php echo language_code('DSP_EDIT_ALBUM'); ?>"></a>
                                                                                                <span onclick="popup_show('popup', 'popup_drag', 'popup_exit', 'screen-center', 0, 0);"><input type="button" class="btn btn-danger custom-delete-button" value="<?php echo language_code('DSP_DELETE');?>"></span>
                                                                                            </span>
                                                                                                <div class="sample_popup"     id="popup" style="display: none;">
                                                                                                    <div class="menu_form_body">
                                                                                                        <div style=" margin-top:10px; text-align:center;"><?php echo language_code('DSP_QUESTION_DATE_TRACKER');?></div>
                                                                                                        <div style="margin-top:15px;">
                                                                                                            <div class="btn-row-date-traker">
                                                                                                                <span><input name="delete" type="button" value="Yes" style="width:85px;"  onclick="location.href = '<?php echo $root_link . "extras/date_tracker/mode/del_user/uid/" . $s_user_id . "/"; ?>';
                                                                                                                        return false;"/></span>
                                                                                                                <span style="float:right;"><input name="deletenow" type="button" value="No" style="width:85px;" onclick="location.href = '<?php echo $root_link . "extras/date_tracker/mode/del/uid/" . $message->t_message_id . "/"; ?>';
                                                                                                                        return false;"/></span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </span></div>
                                                                                        </div>
                                                                                        </div>
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                                ?>
                                                                                <?php
                                                                                unset($favt_mem);
                                                                            }// foreach($search_members as $member) 
                                                                            ?>
                                                                            <style>
                                                                                div.sample_popup { z-index: 1;
                                                                                                   background:transparent; 
                                                                                }
                                                                                div.sample_popup div.menu_form_header
                                                                                {
                                                                                    border: 1px solid black;
                                                                                    border-bottom: none;
                                                                                    width: 200px;
                                                                                    height:      20px;
                                                                                    line-height: 19px;
                                                                                    vertical-align: middle;
                                                                                    background: url('form_header.png') no-repeat;
                                                                                    text-decoration: none;
                                                                                    font-family: Times New Roman, Serif;
                                                                                    font-weight: 900;
                                                                                    font-size:  13px;
                                                                                    color:   #206040;
                                                                                    cursor:  default;
                                                                                }
                                                                                div.sample_popup div.menu_form_body
                                                                                {
                                                                                    border: 1px solid #666666;
                                                                                    height: 130px;
                                                                                    width: 360px;
                                                                                    background-color:#FFFFFF;
                                                                                }
                                                                                div.sample_popup img.menu_form_exit
                                                                                {
                                                                                    float:  right;
                                                                                    margin: 4px 5px 0px 0px;
                                                                                    cursor: pointer;
                                                                                }
                                                                                div.sample_popup table
                                                                                {
                                                                                    width: 100%;
                                                                                    border-collapse: collapse;
                                                                                }
                                                                                div.sample_popup th
                                                                                {
                                                                                    width: 1%;
                                                                                    padding: 0px 5px 1px 0px;
                                                                                    text-align: left;
                                                                                    font-family: Times New Roman, Serif;
                                                                                    font-weight: 900;
                                                                                    font-size:  13px;
                                                                                    color:   #004060;
                                                                                }
                                                                                div.sample_popup td
                                                                                {
                                                                                    width: 53%;
                                                                                    padding: 0px 0px 1px 0px;
                                                                                }
                                                                                div.sample_popup form
                                                                                {
                                                                                    margin:  0px;
                                                                                    padding: 8px 10px 10px 10px;
                                                                                }
                                                                                div.sample_popup input.field
                                                                                {
                                                                                    width: 95%;
                                                                                    border: 1px solid #808080;
                                                                                    font-family: Verdana, Sans-Serif;
                                                                                    font-size: 12px;
                                                                                }
                                                                                div.sample_popup input.btn
                                                                                {
                                                                                    margin-top: 2px;
                                                                                    background-image:url(registered_me.jpg)  ;

                                                                                    font-family: Verdana, Sans-Serif;
                                                                                    font-size: 11px;
                                                                                }
                                                                            </style>
                                                                            <script>
                                                                                // Copyright (C) 2005-2008 Ilya S. Lyubinskiy. All rights reserved.
                                                                                // Technical support: http://www.php-development.ru/
                                                                                //
                                                                                // YOU MAY NOT
                                                                                // (1) Remove or modify this copyright notice.
                                                                                // (2) Re-distribute this code or any part of it.
                                                                                //     Instead, you may link to the homepage of this code:
                                                                                //     http://www.php-development.ru/javascripts/popup-window.php
                                                                                //
                                                                                // YOU MAY
                                                                                // (1) Use this code on your website.
                                                                                // (2) Use this code as part of another product.
                                                                                //
                                                                                // NO WARRANTY
                                                                                // This code is provided "as is" without warranty of any kind.
                                                                                // You expressly acknowledge and agree that use of this code is at your own risk.
                                                                                // USAGE
                                                                                //
                                                                                // function popup_show(id, drag_id, exit_id, position, x, y, position_id)
                                                                                //
                                                                                // id          - id of a popup window;
                                                                                // drag_id     - id of an element within popup window intended for dragging it
                                                                                // exit_id     - id of an element within popup window intended for hiding it
                                                                                // position    - positioning type:
                                                                                //               "element", "element-right", "element-bottom", "mouse",
                                                                                //               "screen-top-left", "screen-center", "screen-bottom-right"
                                                                                // x, y        - offset
                                                                                // position_id - id of an element relative to which popup window will be positioned
                                                                                // ***** Variables *************************************************************
                                                                                var popup_dragging = false;
                                                                                var popup_target;
                                                                                var popup_mouseX;
                                                                                var popup_mouseY;
                                                                                var popup_mouseposX;
                                                                                var popup_mouseposY;
                                                                                var popup_oldfunction;
                                                                                // ***** popup_mousedown *******************************************************
                                                                                function popup_mousedown(e)
                                                                                {
                                                                                    var ie = navigator.appName == "Microsoft Internet Explorer";
                                                                                    popup_mouseposX = ie ? window.event.clientX : e.clientX;
                                                                                    popup_mouseposY = ie ? window.event.clientY : e.clientY;
                                                                                }
                                                                                // ***** popup_mousedown_window ************************************************
                                                                                function popup_mousedown_window(e)
                                                                                {
                                                                                    var ie = navigator.appName == "Microsoft Internet Explorer";
                                                                                    if (ie && window.event.button != 1)
                                                                                        return;
                                                                                    if (!ie && e.button != 0)
                                                                                        return;
                                                                                    popup_dragging = true;
                                                                                    popup_target = this['target'];
                                                                                    popup_mouseX = ie ? window.event.clientX : e.clientX;
                                                                                    popup_mouseY = ie ? window.event.clientY : e.clientY;
                                                                                    if (ie)
                                                                                        popup_oldfunction = document.onselectstart;
                                                                                    else
                                                                                        popup_oldfunction = document.onmousedown;
                                                                                    if (ie)
                                                                                        document.onselectstart = new Function("return false;");
                                                                                    else
                                                                                        document.onmousedown = new Function("return false;");
                                                                                }
                                                                                // ***** popup_mousemove *******************************************************
                                                                                function popup_mousemove(e)
                                                                                {
                                                                                    var ie = navigator.appName == "Microsoft Internet Explorer";
                                                                                    var element = document.getElementById(popup_target);
                                                                                    var mouseX = ie ? window.event.clientX : e.clientX;
                                                                                    var mouseY = ie ? window.event.clientY : e.clientY;
                                                                                    if (!popup_dragging)
                                                                                        return;
                                                                                    element.style.left = (element.offsetLeft + mouseX - popup_mouseX) + 'px';
                                                                                    element.style.top = (element.offsetTop + mouseY - popup_mouseY) + 'px';
                                                                                    popup_mouseX = ie ? window.event.clientX : e.clientX;
                                                                                    popup_mouseY = ie ? window.event.clientY : e.clientY;
                                                                                }
                                                                                // ***** popup_mouseup *********************************************************
                                                                                function popup_mouseup(e)
                                                                                {
                                                                                    var ie = navigator.appName == "Microsoft Internet Explorer";
                                                                                    var element = document.getElementById(popup_target);
                                                                                    if (!popup_dragging)
                                                                                        return;
                                                                                    popup_dragging = false;
                                                                                    if (ie)
                                                                                        document.onselectstart = popup_oldfunction;
                                                                                    else
                                                                                        document.onmousedown = popup_oldfunction;
                                                                                }
                                                                                // ***** popup_exit ************************************************************
                                                                                function popup_exit(e)
                                                                                {
                                                                                    var ie = navigator.appName == "Microsoft Internet Explorer";
                                                                                    var element = document.getElementById(popup_target);
                                                                                    popup_mouseup(e);
                                                                                    element.style.display = 'none';
                                                                                }
                                                                                // ***** popup_show ************************************************************
                                                                                function popup_show(id, drag_id, exit_id, position, x, y, position_id)
                                                                                {
                                                                                    var element = document.getElementById(id);
                                                                                    var drag_element = document.getElementById(drag_id);
                                                                                    var exit_element = document.getElementById(exit_id);
                                                                                    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth;
                                                                                    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight;
                                                                                    // element.style.position = "absolute";
                                                                                    element.style.display = "block";
                                                                                    if (position == "element" || position == "element-right" || position == "element-bottom")
                                                                                    {
                                                                                        var position_element = document.getElementById(position_id);
                                                                                        for (var p = position_element; p; p = p.offsetParent)
                                                                                            if (p.style.position != 'absolute')
                                                                                            {
                                                                                                x += p.offsetLeft;
                                                                                                y += p.offsetTop;
                                                                                            }
                                                                                        if (position == "element-right")
                                                                                            x += position_element.clientWidth;
                                                                                        if (position == "element-bottom")
                                                                                            y += position_element.clientHeight;
                                                                                        // element.style.left = x+'px';
                                                                                        //element.style.top  = y+'px';
                                                                                    }
                                                                                    if (position == "screen-center")
                                                                                    {
                                                                                        //element.style.left = (document.documentElement.scrollLeft+(width -element.clientWidth )/4+x)+'px';
                                                                                        // element.style.top  = (document.documentElement.scrollTop +(height-element.clientHeight)/5+y-100)+'px';
                                                                                    }
                                                                                    drag_element['target'] = id;
                                                                                    drag_element.onmousedown = popup_mousedown_window;
                                                                                    exit_element.onclick = popup_exit;
                                                                                }
                                                                            </script>
                                                                            <div class="row-paging">
                                                                                <div style="float:left; width:100%;">
                                                                                    <?php
// --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                                                                                    echo $pagination
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                                                                                    ?>
                                                                                </div>
                                                                            </div>