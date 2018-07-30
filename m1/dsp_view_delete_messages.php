<?php
//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->

// In this file we checks Admin General Settings



$get_sender_id = isset($_REQUEST['sender_ID']) ? $_REQUEST['sender_ID'] : '';



$request_Action = isset($_REQUEST['Act']) ? $_REQUEST['Act'] : '';
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_back.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MIDDLE_TAB_DELETED');?></h1>
     <?php include_once("page_home.php");?> 
</div>
<?php
if (($request_Action == "R") && ($get_sender_id != "")) {



    $wpdb->query("UPDATE $dsp_user_emails_table  SET message_read='Y' WHERE sender_id = '$get_sender_id'");
} // End if 
?>



<div class="ui-content" data-role="content">
    <div class="content-primary">


        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul message-list">


            <?php
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++

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



            $bolIfSearchCriteria = true;

            $strQuery = "SELECT * FROM $dsp_user_emails_table where sender_id = $get_sender_id AND receiver_id=$user_id";





            $intRecordsPerPage = 10;



            $intStartLimit = isset($_REQUEST['p']) ? $_REQUEST['p'] : ''; # page selected 1,2,3,4...



            if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
                $intStartLimit = 1; //default
            }



            $intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;



            if ($bolIfSearchCriteria) {



                $strQuery = $strQuery . " AND delete_message=1 ORDER BY thread_id desc";

                $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
            }



// ----------------------------------------------- Start Paging code------------------------------------------------------ //





            $page_name = "";



            $total_results1 = $user_count;

// Calculate total number of pages. Round up using ceil()
// $total_pages1 = ceil($total_results1 / $max_results1); 
// ------------------------------------------------End Paging code------------------------------------------------------ // 



            $intTotalRecordsEffected = $user_count;



            if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {



                //print "Total records found: " . $intTotalRecordsEffected;
            }



            $my_messages = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");



            foreach ($my_messages as $message) {



                $display_sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$message->sender_id'");



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
			 
				<div onclick='getViewDeleted(1,$get_sender_id)' class='btn-pre1'>
					<img src='images/icons/prev-1.png' />
				</div>";
                    } else {
                        $pagination.= "
				<div class='btn-pre1'>
					<img src='images/icons/prev-1.png' />
				</div>";
                    }

                    if ($page > 1) {
                        $pagination.="<div  onclick='getViewDeleted($prev,$get_sender_id)' class='btn-pre2'>
							<img src='images/icons/prev-all.png' />
						</div>";
                    } else {
                        $pagination.=" <div  class='btn-pre2'>
							<img src='images/icons/prev-all.png' />
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
			<div onclick='getViewDeleted($next,$get_sender_id)' class='main4' >
				<img src='images/icons/next-all.png' />
			</div>";

                        $pagination.= "	<div onclick='getViewDeleted($lastpage,$get_sender_id)' class='main5'>
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
                ?>


                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                    <div class="image"> <?php
                        $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$message->sender_id'");

                        $exist_make_private->make_private;

                        $user_gender = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$message->sender_id'");

                        $user_gender->gender;

                        $favt_mem = array();

                        $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$message->sender_id'");

                        foreach ($private_mem as $private) {

                            $favt_mem[] = $private->favourite_user_id;
                        }

                        if ($check_couples_mode->setting_status == 'Y') {

                            if ($user_gender->gender == 'C') {
                                ?>

                                <?php if ($exist_make_private->make_private == 'Y') { ?>



                                    <?php if (!in_array($user_id, $favt_mem)) { ?>

                                        <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')"> 

                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:75px; height:75px;" class="dsp_img2" align="left" />

                                        </a>                

                                    <?php } else {
                                        ?>

                                        <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">				

                                            <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"   style="width:75px; height:75px;" class="dsp_img2" align="left"/></a>                

                                        <?php
                                    }
                                } else {
                                    ?>



                                    <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">

                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:75px; height:75px;" class="dsp_img2" align="left" />

                                    </a>

                                <?php } ?>



                            <?php } else { ?>



                                <?php if ($exist_make_private->make_private == 'Y') { ?>



                                    <?php if (!in_array($user_id, $favt_mem)) { ?>

                                        <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">

                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:75px; height:75px;" class="dsp_img2" align="left" />

                                        </a>                

                                    <?php } else {
                                        ?>

                                        <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">				

                                            <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"   style="width:75px; height:75px;" class="dsp_img2" align="left"/></a>                

                                        <?php
                                    }
                                } else {
                                    ?>



                                    <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">

                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:75px; height:75px;" class="dsp_img2" align="left" />

                                    </a>

                                <?php } ?>

                                <?php
                            }
                        } else {
                            ?>

                            <?php if ($exist_make_private->make_private == 'Y') { ?>



                                <?php if (!in_array($user_id, $favt_mem)) { ?>

                                    <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">

                                        <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:75px; height:75px;" class="dsp_img2" align="left" />

                                    </a>                

                                <?php } else {
                                    ?>

                                    <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">				

                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"   style="width:75px; height:75px;" class="dsp_img2" align="left"/></a>                

                                    <?php
                                }
                            } else {
                                ?>



                                <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')">

                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:75px; height:75px;" class="dsp_img2" align="left" />

                                </a>

                            <?php } ?>





                        <?php } ?>


                    </div>

                    <div class="msg-info">
                        <ul>
                            <li>
                            <div class="user-name">
                            <?php echo $display_sender_name->display_name; ?>
                            </div>
                            </li>
                            <li><?php echo $message_date ?></li>
                            <li><?php echo $message->text_message ?></li>
                            <?php if ($message->sender_id != $user_id) {
                                ?>
                                <li>
                                <div class="row-btn-traker spacer-top-sm">
                                    <a class="reply-button" onclick="composeMessage('<?php echo $message->sender_id ?>', '<?php echo $message->message_id; ?>')" >
                                        <?php echo language_code('DSP_MESSAGE_REPLY'); ?>
                                    </a>
                                    </div>
                                </li>
                            <?php } //end if   ?>
                        </ul>
                    </div>

                </li>



            </ul>

            <?php
            unset($favt_mem);
        } // End for loop  
        ?>

        <div class="ds_pagination" > 
            <?php echo $pagination ?>
        </div>

    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up     ?>
</div>