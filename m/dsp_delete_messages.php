<?php
// In this file we checks Admin General Settings
//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->
$Sender_IDs = isset($_REQUEST['delmessage']) ? $_REQUEST['delmessage'] : '';

$del_mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php
        echo language_code('DSP_MIDDLE_TAB_DELETED');
        ;
        ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>
</div>
<?php
if (($Sender_IDs != "") && ($del_mode == "delete")) {



    for ($i = 0; $i < sizeof($Sender_IDs); $i++) {
        //echo "DELETE FROM $dsp_user_emails_table WHERE sender_id = '".$Sender_IDs[$i]."' and receiver_id='$user_id'";
        $wpdb->query("DELETE FROM $dsp_user_emails_table WHERE sender_id = '" . $Sender_IDs[$i] . "' and receiver_id='$user_id'");
    } // End for loop
} // End if 
?>


<div class="ui-content" data-role="content">
    <div class="content-primary">
        <form id="frmdelmessages" name="frmdelmessages" >
            <div class="box-page">
                <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">
                    <?php
                    if (isset($_GET['page1']))
                        $page = $_GET['page1'];
                    else
                        $page = 1;



// How many adjacent pages should be shown on each side?

                    $adjacents = 2;

//$limit = 1; 	
                    $limit = 5;

                    if ($page)
                        $start = ($page - 1) * $limit;    //first item to display on this page
                    else
                        $start = 0;

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++
// if (isset($_GET['page1'])) $page1 = $_GET['page1']; else $page1 = 1;
//$max_results1 = 10;
//$from1 = (($page1 * $max_results1) - $max_results1);

                    $bolIfSearchCriteria = true;



                    if ($check_couples_mode->setting_status == 'Y') {



                        $strQuery = "SELECT * FROM $dsp_user_emails_table m, $dsp_user_profiles p WHERE m.sender_id = p.user_id AND m.receiver_id=$user_id AND m.delete_message=1 group by m.sender_id ";
                    } else {



                        $strQuery = "SELECT * FROM $dsp_user_emails_table m, $dsp_user_profiles p WHERE m.sender_id = p.user_id AND m.receiver_id=$user_id AND m.delete_message=1 AND p.gender!='C' group by m.sender_id ";
                    }

//echo $strQuery = "SELECT * FROM $dsp_user_emails_table where receiver_id=$user_id and delete_message=1 group by sender_id ";





                    $intRecordsPerPage = 10;



                    $intStartLimit = isset($_REQUEST['p']) ? $_REQUEST['p'] : ''; # page selected 1,2,3,4...



                    if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
                        $intStartLimit = 1; //default
                    }



                    $intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;



                    if ($bolIfSearchCriteria) {



                        $strQuery = $strQuery . "  ORDER BY sent_date desc";

                        $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
                    }



// ----------------------------------------------- Start Paging code------------------------------------------------------ //





                    $page_name = "";



                    $total_results1 = $user_count;

// Calculate total number of pages. Round up using ceil()
//$total_pages1 = ceil($total_results1 / $max_results1); 
// ------------------------------------------------End Paging code------------------------------------------------------ // 



                    $intTotalRecordsEffected = $user_count;



                    if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {



                        //print "Total records found: " . $intTotalRecordsEffected;
                    }



                    $my_messages = $wpdb->get_results($strQuery . "  LIMIT $start, $limit  ");





                    foreach ($my_messages as $message) {
                        $display_sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$message->sender_id'");

                        $message_date = date("m/d/Y h:i", strtotime($message->sent_date));

                        $message_subject = $wpdb->get_row("SELECT subject FROM $dsp_user_emails_table WHERE receiver_id=$user_id AND sender_id = '$message->sender_id' order by sent_date desc LIMIT 1");

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

                            $pagination .= "<div class='button-area'>";

                            //previous button

                            if ($page > 1) {
                                $pagination.="
			 
				<div onclick='getDeleted(\"false\",1)' class='btn-pre1'>
					<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/bb.png" . "'/>
				</div>";
                            } else {
                                $pagination.= "
				<div class='btn-pre1'>
					<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/b.png" . "'/>
				</div>";
                            }

                            if ($page > 1) {
                                $pagination.="<div  onclick='getDeleted(\"false\",$prev)' class='btn-pre2'>
							<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/aa.png" . "'/>
						</div>";
                            } else {
                                $pagination.=" <div  class='btn-pre2'>
							<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/a.png" . "'/>
						</div>";
                            }


                            $pagination.= "<div class='main3'>
							<ul class='page_ul'> 
								<li class='para'> Page</li>
								<li class='page_middle'>$page</li>
								<li class='para1'>of $lastpage</li>
							</ul>
						</div>";

                            if ($page < $lastpage) {
                                $pagination.= "
			<div onclick='getDeleted(\"false\",$next)' class='main4' >
				<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/c.png" . "'/>
			</div>";

                                $pagination.= "	<div onclick='getDeleted(\"false\",$lastpage)' class='main5'>
								<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/d.png" . "'/>
							</div>";
                            } else {
                                $pagination.= "
			<div class='main4'>
			<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/cc.png" . "'/>
			</div>";

                                $pagination.= "	<div class='main5'>
								<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/dd.png" . "'/>
							</div>";
                            }

                            $pagination.= "</div>\n";
                        }



                        /* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

                        $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$message->sender_id'");

                        $favt_mem = array();

                        $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$message->sender_id'");

                        foreach ($private_mem as $private) {

                            $favt_mem[] = $private->favourite_user_id;
                        }
                        ?>

                        <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                            <div style="float:left; margin-top:20px;"> <input type="checkbox" name="delmessage[]" value="<?php echo $message->sender_id ?>" /></div>

                            <div class="image">
                                <div style="width: 100%">
                                    <?php
                                    if ($check_couples_mode->setting_status == 'Y') {
                                        if ($message->gender == 'C') {
                                            if ($exist_make_private->make_private == 'Y') {

                                                if (!in_array($user_id, $favt_mem)) {
                                                    ?>

                                                    <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')"> 

                                                        <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:75px; height:75px;" class="img2" align="left" />

                                                    </a>                

                                                    <?php
                                                } else {
                                                    ?>

                                                    <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">				

                                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"    style="width:75px; height:75px;" class="img2" align="left"/>
                                                    </a>                

                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">

                                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:75px; height:75px;" class="img2" align="left" />

                                                </a>

                                                <?php
                                            }
                                        } else {
                                            if ($exist_make_private->make_private == 'Y') {
                                                if (!in_array($user_id, $favt_mem)) {
                                                    ?>

                                                    <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">

                                                        <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:75px; height:75px;" class="img2" align="left" />

                                                    </a>
                                                <?php } else {
                                                    ?>

                                                    <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">				

                                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"   style="width:75px; height:75px;" class="img2" align="left"/></a>                

                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">

                                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:75px; height:75px;" class="img2" align="left" />

                                                </a>
                                                <?php
                                            }
                                        }
                                    } else {
                                        if ($exist_make_private->make_private == 'Y') {
                                            if (!in_array($user_id, $favt_mem)) {
                                                ?>

                                                <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">

                                                    <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:75px; height:75px;" class="img2" align="left" />

                                                </a>                

                                                <?php
                                            } else {
                                                ?>

                                                <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">				

                                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"  style="width:75px; height:75px;" class="img2" align="left"/></a>                

                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">

                                                <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:75px; height:75px;" class="img2" align="left" />

                                            </a>

                                            <?php
                                        }
                                    }
                                    ?>


                                </div>




                            </div>

                            <div class="msg-info">
                                <ul>
                                    <li>
                                        <?php
                                        if ($check_couples_mode->setting_status == 'Y') {
                                            if ($message->gender == 'C') {
                                                ?>

                                                <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">

                                                    <?php echo $display_sender_name->display_name; ?></a> 
                                                <?php
                                            } else {
                                                ?>
                                                <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">

                                                    <?php echo $display_sender_name->display_name; ?>

                                                </a>
                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">

                                                <?php echo $display_sender_name->display_name; ?></a>

                                        <?php } ?>

                                    </li>
                                    <li>
                                        <?php echo $message_date ?> 
                                    </li>
                                    <li>
                                        <?php
                                        $count_member_new_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE message_read='N' AND receiver_id=$user_id AND sender_id='$message->sender_id'");

                                        if ($count_member_new_messages > 0) {
                                            ?>
                                            <div style="width:55%;">
                                                <a onclick="getViewDeleted(0,<?php echo $message->sender_id; ?>)" >
                                                    <?php echo $message_subject->subject; ?>&nbsp;<span style="color:#FF0000;">(<?php echo $count_member_new_messages ?>)
                                                    </span>
                                                </a>
                                            </div>

                                            <?php
                                        } else {
                                            ?>
                                            <div style="width:55%;">
                                                <a onclick="getViewDeleted(0,<?php echo $message->sender_id; ?>)" >
                                                    <?php echo $message_subject->subject; ?>
                                                </a>
                                            </div>
                                        <?php } ?>

                                        <?php unset($favt_mem); ?>
                                    </li>
                                </ul>
                        </li>
                    <?php } ?>

                </ul>

            </div>

    </div>

    <div class="ds_pagination" > 
        <?php echo $pagination ?>
    </div>


    <div class="btn-delete">



        <input type="hidden" name="mode" id="mode" value="delete" />
        <input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
        <input type="hidden" name="message_template" value="deleted" />

        <?php if ($my_messages) { ?>

            <input type="button" class="dsp_submit_button" name="delmsg" value="<?php echo language_code('DSP_DELETE_SELECTED') ?>" onclick="getDeleted('true', 0);"/></td>
        <?php
        } else {
            echo language_code('DSP_EMPTY');
        }
        ?>
    </div>



</form>

</div>
<?php include_once('dspNotificationPopup.php'); // for notification pop up      ?>
</div>

