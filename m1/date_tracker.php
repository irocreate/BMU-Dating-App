<?php
//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

include("../../../../wp-config.php");
//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include_once("../general_settings.php");
include_once("dspFunction.php");

$user_id = $_REQUEST['user_id'];

// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_menu.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_DATE_TRACKER'); ?></h1>
    <?php include_once("page_home.php");?> 

</div>
<?php
// ----------------------------------------------- Start Paging code------------------------------------------------------ //  


$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_date_tracker_table = $wpdb->prefix . DSP_DATE_TRACKER_TABLE;
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_date_tracker_message_table = $wpdb->prefix . DSP_DATE_TRACKER_MESSAGE_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;


$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

if ($mode == "save") {
    $msg = isset($_REQUEST['txtmessage']) ? $_REQUEST['txtmessage'] : '';
    $r_user_id = isset($_REQUEST['date_id']) ? $_REQUEST['date_id'] : '';
    $wpdb->query("INSERT INTO $dsp_date_tracker_message_table SET t_sender_id='$user_id',t_receiver_id='$r_user_id',	t_message='$msg' , 	t_status='1'");
}
if ($mode == "saveEdit") {
    $msg_id = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : '';
    $msg = isset($_REQUEST['txtmessage']) ? $_REQUEST['txtmessage'] : '';
    $wpdb->query("UPDATE $dsp_date_tracker_message_table SET t_message='$msg' WHERE t_message_id  = '$msg_id'");
}




if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'del_user') {
    $delUserID = $_REQUEST['delUserID'];
    $wpdb->query("DELETE FROM $dsp_date_tracker_table where member_id =" . $delUserID);
    $wpdb->query("DELETE FROM $dsp_date_tracker_message_table where t_receiver_id =" . $delUserID);
}


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
// ----------------------------------------------- Start Paging code------------------------------------------------------ //


if ($check_couples_mode->setting_status == 'Y') {
    $strQuery = "SELECT * FROM $dsp_date_tracker_table tracker, $dsp_user_profiles_table p WHERE tracker.user_id=p.user_id AND tracker.user_id=$user_id ";
} else {
    $strQuery = "SELECT * FROM $dsp_date_tracker_table tracker, $dsp_user_profiles_table p WHERE tracker.user_id=p.user_id AND tracker.user_id=$user_id AND p.gender!='C'";
}
$intRecordsPerPage = 1;

$intStartLimit = isset($_REQUEST['p']) ? $_REQUEST['p'] : ''; # page selected 1,2,3,4...

if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
    $intStartLimit = 1; //default
}
$intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;

$strQuery = $strQuery . " ORDER BY p.user_profile_id desc";

$user_count = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_date_tracker_table AS total where user_id='$user_id'");

// ----------------------------------------------- Start Paging code------------------------------------------------------ //
$page_name = "";
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

    $pagination .= "<div class='button-area'>";

    //previous button

    if ($page > 1) {
        $pagination.="

        <div onclick='myDate(\"page\",0,0,1)' class='btn-pre1'>
         <img src='images/icons/prev-1.png' />
     </div>";
 } else {
    $pagination.= "
    <div class='btn-pre1'>
     <img src='images/icons/prev-1.png' />
 </div>";
}

if ($page > 1) {
    $pagination.="<div  onclick='myDate(\"page\",0,0,$prev)' class='btn-pre2'>
    <img src='images/icons/prev-all.png'' />
</div>";
} else {
    $pagination.=" <div  class='btn-pre2'>
    <img src='images/icons/prev-all.png'' />
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
    <div onclick='myDate(\"page\",0,0,$next)' class='main4' >
        <img src='images/icons/next-all.png' />
    </div>";

    $pagination.= "	<div onclick='myDate(\"page\",0,0,$lastpage)' class='main5'>
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


// ------------------------------------------------End Paging code------------------------------------------------------ // 



$intTotalRecordsEffected = $user_count;



if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
    //print "Total records found: " . $intTotalRecordsEffected;
} else {
    ?>
    <div class="ui-content" data-role="content">
        <div class="content-primary">	 
            <div style="text-align: center;">
                <?php echo language_code('DSP_NO_RECORD_FOUND_EXTRAS'); ?>
            </div>
        </div>
    </div>

    <?php
}

$search_members = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");
?>
<div class="ui-content" data-role="content">
    <div class="content-primary">	
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul datetracker">
            <?php
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


                    <form name="frmsave" id="frmsave">
                        <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                            <div class="tracker-profile-image">

                                <?php

                                if ($check_couples_mode->setting_status == 'Y') {
                                    if ($s_gender == 'C') {
                                        if ($s_make_private == 'Y') {

                                            if ($user_id != $s_user_id) {
                                                if (!in_array($user_id, $favt_mem)) {
                                                    ?>

                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                        <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"style="width:100px; height:100px;"     border="0" class="dsp_img3" />
                                                    </a>                
                                                    <?php } else {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				

                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"    border="0" class="dsp_img3"/></a>                

                                                            <?php
                                                        }
                                                    } else {
                                                        ?>

                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="0" class="dsp_img3" />
                                                        </a>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="0" class="dsp_img3" />
                                                    </a>
                                                    <?php
                                                }
                                            } else {
                                                if ($s_make_private == 'Y') {
                                                    if ($user_id != $s_user_id) {
                                                        if (!in_array($user_id, $favt_mem)) {
                                                            ?>

                                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"style="width:100px; height:100px;"    border="0" class="dsp_img3" />
                                                            </a>                
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				
                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="0" class="dsp_img3"/>
                                                            </a>                
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>

                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="0" class="dsp_img3" />
                                                        </a>

                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="0" class="dsp_img3"/>
                                                    </a>
                                                    <?php
                                                }
                                            }
                                        } else {
                                            if ($s_make_private == 'Y') {
                                                if ($user_id != $s_user_id) {
                                                    if (!in_array($user_id, $favt_mem)) {
                                                        ?>

                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"style="width:100px; height:100px;"     border="0" class="dsp_img3" />
                                                        </a>                
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				
                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="0" class="dsp_img3"/>
                                                        </a>                
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="0" class="dsp_img3" />
                                                    </a>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="0" class="dsp_img3" />
                                                </a>

                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="tracker-detail">
                                        <strong>
                                            <?php
                                            if ($check_couples_mode->setting_status == 'Y') {
                                                if ($s_gender == 'C') {
                                                    ?>
                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                        <?php echo $displayed_member_name->display_name ?>           
                                                    </a>     
                                                    <?php } else { ?>

                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                        <?php echo $displayed_member_name->display_name ?>
                                                    </a>
                                                    <?php
                                                }
                                            } else {
                                                ?> 
                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                    <?php echo $displayed_member_name->display_name ?>
                                                </a>
                                                <?php } ?>
                                            </strong>


                                            <textarea id="txtmessage" name="txtmessage" class="textarea-box-sm"></textarea>

                                            <input type="hidden" name="date_id" value="<?php echo $s_user_id; ?>" />
                                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                            <input type="hidden" name="mode" value="save" />
                                            <input type="hidden" name="page1" value="<?php echo $page; ?>" />
                                            <div class="btn-blue-wrap">
                                                <input name="submit" onclick="myDate('true', 'Please enter the message', '<?php echo $page ?>')"  type="button" value="Save" class="mam_btn btn-blue"/>
                                            </div>
                                        </div>
                                    </li>

                                </form>

                                <?php
                            } else {

                                foreach ($tracker_message as $message) {
                                    if ((isset($_GET['mode']) && $_GET['mode'] == 'edit') && (isset($_GET['msg']) && $_GET['msg'] == $message->t_message_id)) {
                                        ?>

                                        <form name="datetrakermsgform" id="frmEditMsg">
                                         <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                                          <div class="tracker-profile-image">
                                            <?php
                                            if ($check_couples_mode->setting_status == 'Y') {
                                                if ($s_gender == 'C') {
                                                    if ($s_make_private == 'Y') {
                                                        if ($user_id != $s_user_id) {
                                                            if (!in_array($user_id, $favt_mem)) {
                                                                ?>

                                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                    <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"style="width:100px; height:100px;"    border="0" class="dsp_img3" />
                                                                </a>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				
                                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="0" class="dsp_img3"/>
                                                                </a>                
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>

                                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="0" class="dsp_img3" />
                                                            </a>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="0" class="dsp_img3" />
                                                        </a>
                                                        <?php
                                                    }
                                                } else {
                                                    if ($s_make_private == 'Y') {
                                                        if ($user_id != $s_user_id) {
                                                            if (!in_array($user_id, $favt_mem)) {
                                                                ?>
                                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                    <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:100px; height:100px;"    border="0" class="dsp_img3" />
                                                                </a>                
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				
                                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"    border="0" class="dsp_img3"/>
                                                                </a>                
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  border="0" class="dsp_img3" />
                                                            </a>
                                                            <?php } ?>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"    border="0" class="dsp_img3"/>
                                                            </a>
                                                            <?php
                                                        }
                                                    }
                                                } else {
                                                    if ($s_make_private == 'Y') {
                                                        if ($user_id != $s_user_id) {
                                                            if (!in_array($user_id, $favt_mem)) {
                                                                ?>
                                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                    <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:100px; height:100px;"    border="0" class="dsp_img3" />
                                                                </a>                
                                                                <?php } else {
                                                                    ?>
                                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				
                                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"    border="0" class="dsp_img3"/>
                                                                    </a>                
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"    border="0" class="dsp_img3" />
                                                                </a>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  border="0" class="dsp_img3"/>
                                                            </a>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <div class="tracker-detail">
                                                    <strong>
                                                        <?php
                                                        if ($check_couples_mode->setting_status == 'Y') {
                                                            if ($s_gender == 'C') {
                                                                ?>
                                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                    <?php echo $displayed_member_name->display_name ?>  
                                                                </a>              
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                    <?php echo $displayed_member_name->display_name ?>
                                                                    <?php
                                                                } ?>
                                                            </a><?php
                                                        } else {
                                                            ?> 
                                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                <?php echo $displayed_member_name->display_name ?>
                                                            </a>
                                                            <?php } ?>
                                                        </strong>


                                                        <textarea id="edittxtmessage" name="txtmessage" class="textarea-box-sm"><?php echo $message->t_message; ?></textarea>
                                                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                                        <input type="hidden" name="mode" value="saveEdit" />
                                                        <input type="hidden" value="<?php echo $_GET['msg']; ?>" name="msg"/>
                                                        <input type="hidden" name="page1" value="<?php echo $page; ?>" />
                                                       
                                                            <input name="edit" type="button" class="reply-button" onclick="myDate('editSave', '<?php echo language_code('DSP_FORGOT_MESSAGE_MSG'); ?>', 0, 1)" value="Edit" />
                                                       
                                                    </div>

                                                </li>

                                            </form>

                                            <?php
                                        } else {
                                            ?>
                                            <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                                             <div class="tracker-profile-image">
                                                <?php

                                                if ($check_couples_mode->setting_status == 'Y') {
                                                    if ($s_gender == 'C') {
                                                        if ($s_make_private == 'Y') {
                                                            if ($user_id != $s_user_id) {
                                                                if (!in_array($user_id, $favt_mem)) {
                                                                    ?>
                                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                        <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"style="width:100px; height:100px;"    border="0" class="dsp_img3" />
                                                                    </a>                
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				
                                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"    border="0" class="dsp_img3"/>
                                                                    </a>                
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"    border="0" class="dsp_img3" />
                                                                </a>
                                                                <?php } ?>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"    border="0" class="dsp_img3" />
                                                                </a>
                                                                <?php } ?>
                                                                <?php
                                                            } else {
                                                                if ($s_make_private == 'Y') {
                                                                    if ($user_id != $s_user_id) {
                                                                        if (!in_array($user_id, $favt_mem)) {
                                                                            ?>
                                                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                                <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"style="width:100px; height:100px;"     border="0" class="dsp_img3" />
                                                                            </a>                
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				
                                                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="0" class="dsp_img3"/>
                                                                            </a>                
                                                                            <?php
                                                                        }
                                                                    } else {
                                                                        ?>
                                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"     border="0" class="dsp_img3" />
                                                                        </a>
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"    border="0" class="dsp_img3" />
                                                                    </a>
                                                                    <?php
                                                                }
                                                            }
                                                        } else {
                                                            if ($s_make_private == 'Y') {
                                                                if ($user_id != $s_user_id) {
                                                                    if (!in_array($user_id, $favt_mem)) {
                                                                        ?>
                                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"style="width:100px; height:100px;"    border="0" class="dsp_img3" />
                                                                        </a>                
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				
                                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"     border="0" class="dsp_img3"/>
                                                                        </a>                
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"    border="0" class="dsp_img3" />
                                                                    </a>
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"style="width:100px; height:100px;"    border="0" class="dsp_img3"/>
                                                                </a>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="tracker-detail">
                                                    <div class="profile-username">
                                                            <?php
                                                            if ($check_couples_mode->setting_status == 'Y') {
                                                                if ($s_gender == 'C') {
                                                                    ?>
                                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                        <?php echo $displayed_member_name->display_name ?> 
                                                                    </a>               
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                        <?php echo $displayed_member_name->display_name ?>
                                                                    </a>
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?> 
                                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                                    <?php echo $displayed_member_name->display_name ?>
                                                                    </a>
                                                                    <?php } ?>

                                                                
                                                                </div>
                                                                <div class="message-description">
                                                                <?php echo $message->t_message; ?>
                                                                </div>


                                                                <div class="row-btn-traker">
                                                                    <span>
                                                                        <a class="reply-button" onclick="myDate('edit', 'text', '<?php echo $message->t_message_id ?>', '<?php echo $page ?>');" ><?php echo language_code('DSP_EDIT_ALBUM'); ?></a>
                                                                    </span>
                                                                    <span>
                                                                        <a class="delete-button" onclick="myDate('delUser', '<?php echo language_code('DSP_ARE_YOU_SURE_TO_DELETE_IT'); ?>', '<?php echo $s_user_id ?>', '<?php echo $page ?>')"><?php echo language_code('DSP_DELETE'); ?></a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <?php } ?>

                                                        <?php
                                                    }
                                                }

                                                unset($favt_mem);
                                                                                    }// foreach($search_members as $member) 
                                                                                    ?>

                                                                                </ul>


                                                                            </div>
                                                                            <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
                                                                        </div>



                                                                        <div class="ds_pagination" > 
                                                                            <?php echo $pagination ?>
                                                                        </div>
                                                                        <?php include_once("dspLeftMenu.php"); ?>