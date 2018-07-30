<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
$del_mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$dsp_user_emails_table = $wpdb->prefix . DSP_EMAILS_TABLE;
$dsp_admin_emails_table = $wpdb->prefix . DSP_ADMIN_EMAILS;
$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
?>
<script type="text/javascript" language="JavaScript">
    var cX = 0;
    var cY = 0;
    var rX = 0;
    var rY = 0;
    function UpdateCursorPosition(e) {
        cX = e.pageX;
        cY = e.pageY;
    }
    function UpdateCursorPositionDocAll(e) {
        cX = event.clientX;
        cY = event.clientY;
    }
    if (document.all) {
        document.onmousemove = UpdateCursorPositionDocAll;
    }
    else {
        document.onmousemove = UpdateCursorPosition;
    }
    function AssignPosition(d) {
        if (self.pageYOffset) {
            rX = self.pageXOffset;
            rY = self.pageYOffset;
        }
        else if (document.documentElement && document.documentElement.scrollTop) {
            rX = document.documentElement.scrollLeft;
            rY = document.documentElement.scrollTop;
        }
        else if (document.body) {
            rX = document.body.scrollLeft;
            rY = document.body.scrollTop;
        }
        if (document.all) {
            cX += rX;
            cY += rY;
        }
        d.style.left = (cX + 10) + "px";
        d.style.top = (cY + 10) + "px";
    }
    function HideContent(d) {
        if (d.length < 1) {
            return;
        }
        document.getElementById(d).style.display = "none";
    }
    function ShowContent(d) {
        if (d.length < 1) {
            return;
        }
        var dd = document.getElementById(d);
        AssignPosition(dd);
        dd.style.display = "block";
    }
    function ReverseContentDisplay(d) {
        if (d.length < 1) {
            return;
        }
        var dd = document.getElementById(d);
        AssignPosition(dd);
        if (dd.style.display == "none") {
            dd.style.display = "block";
        }
        else {
            dd.style.display = "none";
        }
    }
//-->
</script>
<?php
if ($del_mode == "deletemail") {
    for ($intCounter = 0; $intCounter <= count($_POST["delete_email"]) - 1; $intCounter++) {
        $mail_ids = $_POST["delete_email"][$intCounter];
        //echo "DELETE FROM $dsp_admin_emails_table WHERE admin_mail_id = '$mail_ids'";
        $wpdb->query("DELETE FROM $dsp_user_emails_table WHERE message_id = '$mail_ids'");
    } // END loop 
    $email_deleted = "Deleted.";
} // Endif
if (isset($email_deleted) && $email_deleted != "") {
    ?>
    <div id="message" class="updated fade"><strong><?php echo $email_deleted ?></strong></div>
    <?php
}
// ---------------------------------------- PAGING CODE  ------------------------------------------------ //
$page_name = $root_link . "/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=admin_mails";
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
$total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_emails_table Order by sent_date desc");
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
<form class="dsp-form" name="adminmailsfrm" method="post">
    <table cellpadding="0" cellspacing="0" border="0" class="widefat">
        <tr>
            <th scope="col"><?php echo "Sender Name"; ?></th>
            <th scope="col"><?php echo "Receiver Name"; ?></th>
            <th scope="col"><?php echo "Email"; ?></th> 
            <th scope="col"><?php echo "Sent Date"; ?></th>
            <th scope="col"><?php echo language_code('DSP_DELETE') ?></th>
        </tr>
        <?php
        $request_url = $root_link . "/wp-admin/admin.php?page=dsp-admin-sub-page2";
        if (isset($_POST['search'])) {
            $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
            $search_username = $wpdb->get_results("SELECT * FROM $dsp_user_table WHERE user_login like '%$username%'");
            foreach ($search_username as $username) {
                $user_id = $username->ID;
                $admin_mails_list = $wpdb->get_results("SELECT * FROM $dsp_user_emails_table where sender_id='$user_id' Order by sent_date desc LIMIT $start, $limit");
                $i = 0;
                foreach ($admin_mails_list as $email) {


                    if (isset($i) && $i % 2 != 0) {
                        $class = "";
                    } else {
                        $class = "class='alternate'";
                    }
                    $i++;
                    $sender_id = $email->sender_id;
                    $receiver_id = $email->receiver_id;
                    $subject = $email->subject;
                    $text_message = $email->text_message;
                    $complete_message = "<b>Subject:</b>&nbsp;" . $subject . "<br><b>Message:</b>&nbsp;" . $text_message;
                    $message_id = $email->message_id;

                    $sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table Where ID='$sender_id'");
                    $receiver_name = $wpdb->get_row("SELECT * FROM $dsp_user_table Where ID='$receiver_id'");
                    $bgColor = (@$bgColor == '#FFFFFF') ? '#f1f1f1' : '#FFFFFF';
                    echo "<tr $class >";
                    ?>
                    <td><?php echo $sender_name->user_login ?></div></td>
                    <td><?php echo $receiver_name->user_login ?></div></td>
                    <td>
                        <a onmouseover="ShowContent('uniquename<?php echo $message_id ?>');
                                return true;" onmouseout="HideContent('uniquename<?php echo $message_id ?>');
                                        return true;"
                           href="javascript:ShowContent('uniquename<?php echo $message_id ?>')">Message</a>

                        <div 
                            id="uniquename<?php echo $message_id ?>" 
                            style="display:none; 
                            position:absolute; 
                            border-style: solid;
                            border:1px solid #CCCCCC;
                            background-color: white; 
                            padding: 5px; width:300px; text-align:left; z-index:9999;">
                            <?php
                            echo nl2br($complete_message);
                            ?>
                        </div>
                        </div></td>

                                                                                                            <!--  <a href="<?php //echo add_query_arg (array('pid' =>'view_admin_msg','mail_id' =>$admin_mail_id), $request_url);         ?>">-->
                    <td ><?php echo $email->sent_date ?></div></td> 
                    <td><input type="checkbox" name="delete_email[]" value="<?php echo $message_id ?>" /></div></td>
                    </tr>
                    <?php
                }
            } //if($_POST['search'])
        } else {
            $admin_mails_list = $wpdb->get_results("SELECT * FROM $dsp_user_emails_table Order by sent_date desc LIMIT $start, $limit");
            $i = 0;
            foreach ($admin_mails_list as $email) {


                if (isset($i) && $i % 2 != 0) {
                    $class = "";
                } else {
                    $class = "class='alternate'";
                }
                $i++;
                $sender_id = $email->sender_id;

                $chk_sender_exist = $wpdb->get_var("SELECT count(*) FROM $dsp_user_table Where ID='$sender_id'");

                $receiver_id = $email->receiver_id;

                $chk_receiver_exist = $wpdb->get_var("SELECT count(*) FROM $dsp_user_table Where ID='$receiver_id'");

                if ($chk_receiver_exist != 0 && $chk_sender_exist != 0) {
                    $subject = $email->subject;
                    $text_message = $email->text_message;
                    $complete_message = "<b>Subject:</b>&nbsp;" . $subject . "<br><b>Message:</b>&nbsp;" . $text_message;
                    $message_id = $email->message_id;

                    $sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table Where ID='$sender_id'");
                    $receiver_name = $wpdb->get_row("SELECT * FROM $dsp_user_table Where ID='$receiver_id'");
                    $bgColor = (@$bgColor == '#FFFFFF') ? '#f1f1f1' : '#FFFFFF';
                    echo "<tr $class >";
                    ?>
                    <td><?php echo $sender_name->user_login ?></div></td>
                    <td><?php echo $receiver_name->user_login ?></div></td>
                    <td>
                        <a onmouseover="ShowContent('uniquename<?php echo $message_id ?>');
                                return true;" onmouseout="HideContent('uniquename<?php echo $message_id ?>');
                                        return true;"
                           href="javascript:ShowContent('uniquename<?php echo $message_id ?>')">Message</a>

                        <div 
                            id="uniquename<?php echo $message_id ?>" 
                            style="display:none; 
                            position:absolute; 
                            border-style: solid;
                            border:1px solid #CCCCCC;
                            background-color: white; 
                            padding: 5px; width:300px; text-align:left; z-index:9999;">
                            <?php
                            echo nl2br($complete_message);
                            ?>
                        </div>
                        </div></td>

                                                                                                            <!--  <a href="<?php //echo add_query_arg (array('pid' =>'view_admin_msg','mail_id' =>$admin_mail_id), $request_url);         ?>">-->
                    <td ><?php echo $email->sent_date ?></div></td> 
                    <td><input type="checkbox" name="delete_email[]" value="<?php echo $message_id ?>" /></div></td>
                <?php } ?>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:10px;">
        <tr><td colspan="5" align="right" height="20px">
                <div class="paging-box">
                    <?php
                    // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                    echo $pagination
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                    ?>
                </div>
                <div class="btn-delete-right">
                    <input type="hidden" name="mode" value="deletemail" />
                    <input class="button" type="submit" name="submit" value="<?php echo language_code('DSP_DELETE') ?>" /></div>
            </td>
        </tr>
    </table>
</form>
<br />

<style>
    .dsp_membership_wrap{
        margin-left:2px;
        padding:15px;
        width:1040px;
        display:block;
    }
    .dsp_membership_col1 {
        width:130px;
        padding-left:6px;
        float:left;
        display:block;
        height:25px;
    }
    .dsp_membership_col2 {
        height:20px;
        display:block;
        float:left;
    }
    .dsp_membership_col3 {
        width:260px;
        height:20px;
        display:block;
        float:left;
        text-align:center;
        margin-left: 10px;
    }
</style>
<div id="general" class="postbox" >

    <h3 class="hndle"><span><?php echo "Username Search"; ?></span></h3>
    <div class="dsp_membership_wrap">
        <form name="searchfrm" action="" method="post">
            <br>
            <div class="dsp_membership_active_col"></div>
            <div class="dsp_membership_col1">Username :</div>
            <div class="dsp_membership_col2"><input name="username" type="text" /></div>
            <div class="dsp_membership_col3"><input type="submit" name="search" class="button"  value="Search"/></div>
            <div class="dsp_clr"></div>
            <?php
            $dsp_membership_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
            $myrows = $wpdb->get_results("SELECT * FROM $dsp_membership_table Order by name");
            ?>
            <div class="dsp_clr"></div>
            <br />
        </form>
    </div></div>
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>