<?php
//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->

// In this file we checks Admin General Settings


$get_sender_id = isset($_REQUEST['sender_ID']) ? $_REQUEST['sender_ID'] : '';

$Sender_IDs = isset($_REQUEST['delmessage']) ? $_REQUEST['delmessage'] : '';

$del_mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_back.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_SENT');?></h1>
    <?php include_once("page_home.php");?> 
</div>
<?php
$request_Action = isset($_REQUEST['Act']) ? $_REQUEST['Act'] : '';



if (($request_Action == "R") && ($get_sender_id != "")) {

    $wpdb->query("UPDATE $dsp_user_emails_table  SET message_read='Y' WHERE sender_id = '$get_sender_id'");
} // End if





if (($Sender_IDs != "") && ($del_mode == 'delete')) {
    for ($i = 0; $i < sizeof($Sender_IDs); $i++) {

        $wpdb->query("UPDATE $dsp_user_emails_table SET send_del_message=1  WHERE message_id = '" . $Sender_IDs[$i] . "'");
    }
}
?>



<div class="ui-content" data-role="content">
    <div class="content-primary">

        <div class="box-page">
            <form id="frmdelmessages" name="frmdelmessages" >


                <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul message-list">



                    <?php
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++

                    if (isset($_GET['page1']))
                        $page = $_GET['page1'];
                    else
                        $page = 1;



// How many adjacent pages should be shown on each side?

                    $adjacents = 2;

                    $limit = 5;
//$limit = 1; 	

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

                    if ($bolIfSearchCriteria) {



                        $strQuery = $strQuery . "  ORDER BY thread_id desc";

                        $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
                    }



// ----------------------------------------------- Start Paging code------------------------------------------------------ //





                    $page_name = "";



                    $total_results1 = $user_count;

// Calculate total number of pages. Round up using ceil()
//$total_pages1 = ceil($total_results1 / $max_results1); 
// ------------------------------------------------End Paging code------------------------------------------------------ // 





                    $my_messages = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");



//$my_messages= $wpdb->get_results("SELECT * FROM $dsp_user_emails_table where sender_id=$user_id Order by thread_id desc");



                    foreach ($my_messages as $message) {



                        $display_sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$message->receiver_id'");



                        $message_date = date("m/d/Y h:i", strtotime($message->sent_date));



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
			 
				<div onclick='getSent(\"false\",1)' class='btn-pre1'>
					<img src='images/icons/prev-1.png' />
				</div>";
                            } else {
                                $pagination.= "
				<div class='btn-pre1'>
					<img src='images/icons/prev-1.png' />
				</div>";
                            }

                            if ($page > 1) {
                                $pagination.="<div  onclick='getSent(\"false\",$prev)' class='btn-pre2'>
							<img src='images/icons/prev-all.png'' />
						</div>";
                            } else {
                                $pagination.=" <div  class='btn-pre2'>
							<img src='images/icons/prev-all.png'' />
						</div>";
                            }


                            $pagination.= "<div class='main3' > 
							<div class='main6'>
								<div class='middle'>$page</div>
							</div>
							<div class='para1'>of $lastpage</div>
						</div>";

                            if ($page < $lastpage) {
                                $pagination.= "
			<div onclick='getSent(\"false\",$next)' class='main4' >
				<img src='images/icons/next-all.png' />
			</div>";

                                $pagination.= "	<div onclick='getSent(\"false\",$lastpage)' class='main5'>
								 <img src='images/icons/next-1.png' />
							</div>";
                            } else {
                                $pagination.= "
			<div class='main4'>
			<img src='images/icons/next-all.png' />
			</div>";

                                $pagination.= "	<div class='main5'>
								<img src='images/icons/next-1.png' />
							</div>";
                            }

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



                        <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                            <div class="checkbox-left"><input type="checkbox" name="delmessage[]" value="<?php echo $message->message_id ?>" /></div>
                            <div class="image">
                                <div class="image">

                                    <?php
                                    if ($check_couples_mode->setting_status == 'Y') {

                                        if ($message->gender == 'C') {
                                            ?>



                                            <?php if ($exist_make_private->make_private == 'Y') { ?>



                                                <?php if (!in_array($user_id, $favt_mem)) { ?>

                                                    <a onclick ="viewProfile('<?php echo $message->receiver_id ?>', 'my_profile')">

                                                        <img src="<?php echo $imagepath ?>/plugins/dsp_dating/images/private-photo-pic.jpg" style="width:75px; height:75px;" class="dsp_img3" border="0" align="left" />

                                                    </a>                

                                                <?php } else {
                                                    ?>

                                                    <a onclick ="viewProfile('<?php echo $message->receiver_id ?>', 'my_profile')">				

                                                        <img src="<?php echo display_members_photo($message->receiver_id, $imagepath); ?>"   style="width:75px; height:75px;" class="dsp_img3" border="0" align="left"/></a>                

                                                    <?php
                                                }
                                            } else {
                                                ?>



                                                <a onclick ="viewProfile('<?php echo $message->receiver_id ?>', 'my_profile')">

                                                    <img src="<?php echo display_members_photo($message->receiver_id, $imagepath); ?>" style="width:75px; height:75px;" class="dsp_img3" border="0" align="left" /></a>

                                            <?php } ?>



                                        <?php } else { ?>



                                            <?php if ($exist_make_private->make_private == 'Y') { ?>



                                                <?php if (!in_array($user_id, $favt_mem)) { ?>

                                                    <a onclick ="viewProfile('<?php echo $message->receiver_id ?>', 'my_profile')">

                                                        <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:75px; height:75px;" class="dsp_img3" border="0" align="left" />

                                                    </a>                

                                                <?php } else {
                                                    ?>

                                                    <a onclick ="viewProfile('<?php echo $message->receiver_id ?>', 'my_profile')">				

                                                        <img src="<?php echo display_members_photo($message->receiver_id, $imagepath); ?>"  style="width:75px; height:75px;" class="dsp_img3" border="0" align="left"/></a>                

                                                    <?php
                                                }
                                            } else {
                                                ?>



                                                <a onclick ="viewProfile('<?php echo $message->receiver_id ?>', 'my_profile')">

                                                    <img src="<?php echo display_members_photo($message->receiver_id, $imagepath); ?>" style="width:75px; height:75px;" class="dsp_img3" border="0" align="left" /></a>

                                            <?php } ?>

                                            <?php
                                        }
                                    } else {
                                        ?> 

                                        <?php if ($exist_make_private->make_private == 'Y') { ?>



                                            <?php if (!in_array($user_id, $favt_mem)) { ?>

                                                <a onclick ="viewProfile('<?php echo $message->receiver_id ?>', 'my_profile')">

                                                    <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:75px; height:75px;" class="dsp_img3" border="0" align="left" />

                                                </a>                

                                            <?php } else {
                                                ?>

                                                <a onclick ="viewProfile('<?php echo $message->receiver_id ?>', 'my_profile')">				

                                                    <img src="<?php echo display_members_photo($message->receiver_id, $imagepath); ?>"  style="width:75px; height:75px;" class="dsp_img3" border="0" align="left"/></a>                

                                                <?php
                                            }
                                        } else {
                                            ?>



                                            <a onclick ="viewProfile('<?php echo $message->receiver_id ?>', 'my_profile')">

                                                <img src="<?php echo display_members_photo($message->receiver_id, $imagepath); ?>" style="width:75px; height:75px;" class="dsp_img3" border="0" align="left" />
                                            </a>

                                        <?php } ?>



                                    <?php } ?>

                                </div>
                                <!--<div class="read-message">
                                <?php
                                $read_msg = $message->message_read;

                                if ($read_msg == 'Y') {
                                    ?>
                                                                    
                                                                         
                                                                    
                                                                         <img src="<?php echo $imagepath ?>/plugins/dsp_dating/images/env_read.jpg" title="Email has been Read" />
                                                                    
                                <?php } else { ?>
                                                                    
                                                                         <img src="<?php echo $imagepath ?>/plugins/dsp_dating/images/env_unread.jpg" title="Email is Unread" />
                                                                    
                                <?php } ?>
                                
                                </div>-->
                            </div>
                            <div class="sent-msg-info">
                                <ul>
                                    <li class="user-name"><?php echo $display_sender_name->display_name; ?></li>
                                    <li class="date-format"><?php echo $message_date ?></li>
                                    <li>	<?php
                                        $msg = $message->text_message;
                                        echo $msg;
                                        ?>
                                    </li>
                                    <?php if ($message->sender_id != $user_id) {
                                        ?>
                                        <li><a href="<?php
                                            echo add_query_arg(array('pid' => 14,
                                                'pagetitle' => 'my_email', 'message_template' => 'compose',
                                                'sender_ID' => $message->sender_id,
                                                'Act' => 'Reply'), $root_link);
                                            ?>"><?php echo language_code('DSP_MESSAGE_REPLY') ?></a></li>
                                        <?php } //end if      ?>
                                </ul>
                            </div>

                        </li>



                        <?php
                        unset($favt_mem);
                    } // End for loop  
                    ?>
</ul>
                   

                    <div>

                        <input type="hidden" name="mode" id="mode" value="delete" />

                        <input type="hidden" value="<?php echo $user_id ?>"  name="user_id"/>
                        <input type="hidden" value="<?php echo $get_sender_id ?>"  name="sender_ID"/>
                        <input type="hidden" value="sent"  name="message_template"/>
                        <?php if ($my_messages) { ?>
                         <div class="btn-blue-wrap">
                        <input onclick="getSent('true', 0)" type="button" class="mam_btn btn-blue" name="delmsg" value="<?php echo language_code('DSP_DELETE_SELECTED') ?>" />
                         </div>
                    <?php
                            } else {
                                 echo '<div class="alert-message">';
                                echo language_code('DSP_EMPTY');
                                echo '</div>';
                            }
                            ?>
                   </div>

                   
                        <?php
// --------------------------------  PRINT PAGING LINKS ------------------------------------------- //

                        if (isset($pagination))
                            echo $pagination;
                        else
                            echo '';

// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- // 
                        ?>

                  

            </form>
        </div>

    </div>

    <?php include_once('dspNotificationPopup.php'); // for notification pop up        ?>

</div>