<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
include_once(WP_DSP_ABSPATH . "files/includes/dsp_mail_function.php");
$users_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_comments_table = $wpdb->prefix . DSP_USER_COMMENTS;
$Action = isset($_REQUEST['action_status_id']) ? $_REQUEST['action_status_id'] : '';
if ($Action == 1) {
    for ($intCounter = 0; $intCounter <= count($_POST["comment_ids"]) - 1; $intCounter++) {
        $comments_ids = $_POST["comment_ids"][$intCounter];
        $wpdb->query("update $dsp_comments_table set status_id=1 WHERE comments_id = '$comments_ids'");
    } // END loop 
}
if ($Action == 2) {
    for ($intCounter = 0; $intCounter <= count($_POST["comment_ids"]) - 1; $intCounter++) {
        $comments_ids = $_POST["comment_ids"][$intCounter];
        $wpdb->query("DELETE FROM $dsp_comments_table WHERE comments_id = '$comments_ids'");
    } // END loop 
}
// ----------- DISPLAY HEADING  ----------- //
$page_name = $root_link . "/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_comments&comment_status=$comment_list_status";
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
if ($comment_list_status == 1) {
    $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_comments_table WHERE status_id = '$comment_list_status'");
} else {
    $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_comments_table WHERE status_id='$comment_list_status'");
}
//$total_pages1 = ceil($total_results1 / $max_results1); 
//******************************************************************************************************************************************

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
        $pagination.= "<div><a style='color:#474545' href=\"" . $page_name . "&page1=$prev\">previous</a></div>";
    else
        $pagination.= "<span  class='disabled'>previous</span>";

    //pages	
    if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
        for ($counter = 1; $counter <= $lastpage; $counter++) {
            if ($counter == $page)
                $pagination.= "<span class='current'>$counter</span>";
            else
                $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
        }
    }
    elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
        //close to beginning; only hide later pages
        if ($page < 1 + ($adjacents * 2)) {
            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                if ($counter == $page)
                    $pagination.= "<span class='current'>$counter</span>";
                else
                    $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
            }
            $pagination.= "<span>...</span>";
            $pagination.= "<div><a href=\"" . $page_name . "&page1=$lpm1\">$lpm1</a></div>";
            $pagination.= "<div><a href=\"" . $page_name . "&page1=$lastpage\">$lastpage</a></div>";
        }
        //in middle; hide some front and some back
        elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
            $pagination.= "<div><a href=\"" . $page_name . "&page1=1\">1</a></div>";
            $pagination.= "<div><a href=\"" . $page_name . "&page1=2\">2</a></div>";
            $pagination.= "<span>...</span>";
            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                if ($counter == $page)
                    $pagination.= "<div class='current'>$counter</div>";
                else
                    $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
            }
            $pagination.= "<span>...</span>";
            $pagination.= "<div><a href=\"" . $page_name . "&page1=$lpm1\">$lpm1</a></div>";
            $pagination.= "<div><a href=\"" . $page_name . "&page1=$lastpage\">$lastpage</a></div>";
        }
        //close to end; only hide early pages
        else {
            $pagination.= "<div><a href=\"" . $page_name . "&page1=1\">1</a></div>";
            $pagination.= "<div><a href=\"" . $page_name . "&page1=2\">2</a></div>";
            $pagination.= "<span>...</span>";
            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination.= "<span class='current'>$counter</span>";
                else
                    $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
            }
        }
    }

    //next button
    if ($page < $counter - 1)
        $pagination.= "<div><a style='color:#474545' href=\"" . $page_name . "&page1=$next\">next</a></div>";
    else
        $pagination.= "<span class='disabled'>next</span>";
    $pagination.= "</div>\n";
}

// ------------------------------------------------End Paging code------------------------------------------------------ // 
?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr><td colspan="5"><div id="general" class="postbox" >
                <h3 class="hndle"><span>
                        <?php
// ------------ DISPLAY HEADING  ------------ //
                        if ($comment_list_status == 0) {
                            echo "Comments Waiting to Approve";
                        } else if ($comment_list_status == 1) {
                            echo "Approved Comments ";
                        }
                        if ($comment_list_status == 1) {
                            $myrows = $wpdb->get_results("SELECT * FROM $dsp_comments_table WHERE status_id = '$comment_list_status' ORDER BY `date_added` DESC LIMIT $start, $limit");
                        } else {
                            $myrows = $wpdb->get_results("SELECT * FROM $dsp_comments_table WHERE status_id ='$comment_list_status' ORDER BY `date_added` DESC LIMIT $start, $limit");
                        }
                        ?>
                    </span></h3>
                <table cellpadding="0" cellspacing="0" border="0"  class="widefat comment">
                    <form method="post">
                        <?php
                        foreach ($myrows as $fivefiles) {
                            $users_details = $wpdb->get_row("SELECT ID,user_login FROM $users_table  WHERE ID='$fivefiles->user_id'");
                            ?>
                            <tr>
                                <td><?php echo $users_details->user_login; ?></td>
                                <td><?php echo $fivefiles->comments; ?> <span><input name="comment_ids[]" type="checkbox" value="<?php echo $fivefiles->comments_id; ?>" /></span></td>
                            </tr>
                        <?php } ?> 
                </table>
            </div>
            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:10px;margin-bottom:10px;" >
                <tr>
                    <td width="100%">
                        <div class="paging">
                            <?php
// --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                            echo $pagination
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                            ?>
                        </div>  
                    </td>
                    <td  align="right">
                        <select name="action_status_id">
                            <option value="0"><?php echo language_code('DSP_SELECT_STATUS') ?></option>
                            <option value="1"><?php echo language_code('DSP_MEDIA_LINK_APPROVE') ?></option>
                            <option value="2"><?php echo language_code('DSP_DELETE') ?></option>
                        </select>
                    </td></tr>
                <tr><td colspan="2" align="right">
                        <input class="button" type="submit" name="submit" value="<?php echo language_code('DSP_UPDATE_BUTTON') ?>" style="float:right; margin-top:7px;" /></td>
                </tr>
                </form>
            </table>
        </td></tr>
</table>