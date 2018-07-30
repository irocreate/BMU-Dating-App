<?php
/*

  Copyright (C) www.wpdating.com - All Rights Reserved!

  Author - www.wpdating.com

  WordPress Dating Plugin

  contact@wpdating.com

 */

if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

// ----------------------------------------------- Start Paging code------------------------------------------------------ //  

if (get('page'))
    $page = get('page');
else
    $page = 1;



// How many adjacent pages should be shown on each side?

$adjacents = 2;

$limit = 6;

if ($page)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;

// ----------------------------------------------- Start Paging code------------------------------------------------------ //

$audio_mode = isset($_REQUEST['txtmode']) ? $_REQUEST['txtmode'] : '';
$created_date = date("Y-m-d H:m:s");

$Action = get('Action');

$audio_file = isset($_FILES['file-upload']) ? $_FILES['file-upload']['name'] : '';

$get_audio_Id = get('audio_Id');

$count_uploaded_audios = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_audios where user_id='$user_id' AND status_id=1");

if (isset($_REQUEST['private']) && $_POST['private'] != '') {

    $private = isset($_REQUEST['private']) ? $_REQUEST['private'] : '';
} else {

    $private = 'N';
}

switch ($audio_mode) {

    case 'add':    // ADD PHOTO 

        $uploadfile1 = ABSPATH . "/wp-content/uploads/dsp_media/user_audios/user_" . $user_id . "/";

// define a constant for the maximum upload size

        define('MAX_FILE_SIZE', 51200000);

        define('UPLOAD_DIR', $uploadfile1);

// create an array of permitted MIME types

        $permitted = array('audio/mp3', 'audio/mpeg', 'audio/mpeg3', 'audio/x-mpeg-3');



        // check that file is within the permitted size
        $sizeOK = false;
        $typeOK = false;
        if ($_FILES['file-upload']['size'] > 0 || $_FILES['file-upload']['size'] <= MAX_FILE_SIZE) {

            $sizeOK = true;
        }

// check that file is of an permitted MIME type

        foreach ($permitted as $type) {

            if ($type == $_FILES['file-upload']['type']) {

                $typeOK = true;

                break;
            }
        }
        if ($sizeOK && $typeOK) {

            switch ($_FILES['file-upload']['error']) {

                case 0:

                    $wpdb->query("INSERT INTO $dsp_member_audios SET user_id='$user_id',date_added='$created_date',status_id=0, private_audio='$private'");
                    $insertid = $wpdb->insert_id; // AUTOINCREMENT ID

                    $audio_name = basename($audio_file);

                    $newName = $insertid . "_" . $audio_name;



                    if (!file_exists('wp-content/uploads/dsp_media/user_audios')) {

                        mkdir('wp-content/uploads/dsp_media/user_audios', 0755); // it will default to 0755 regardless 

                        chmod('wp-content/uploads/dsp_media/user_audios', 0777);  // Finally, chmod it to 777
                    }



                    if (!file_exists('wp-content/uploads/dsp_media/user_audios/user_' . $user_id)) {

                        mkdir('wp-content/uploads/dsp_media/user_audios/user_' . $user_id, 0755); // it will default to 0755 regardless 

                        chmod('wp-content/uploads/dsp_media/user_audios/user_' . $user_id, 0777);  // Finally, chmod it to 777
                    }





                    // check if a file of the same name has been uploaded

                    if (!file_exists(UPLOAD_DIR . $audio_file)) {

                        // move the file to the upload folder and rename it

                        $success = move_uploaded_file($_FILES['file-upload']['tmp_name'], UPLOAD_DIR . $newName);
                    }
                    if ($success) {

                        if ($count_uploaded_audios < $check_audio_count->setting_value) {  // check condition Number of Audios in A Profiles.
                            if ($check_approve_audios_status->setting_status == 'Y') {  // if Audio approve status is Y then Audio Automatically Approved.
                                $wpdb->query("UPDATE $dsp_member_audios SET file_name = '$newName',status_id=1 WHERE audio_file_id='$insertid'");
                                $result[] = " $audio_file &nbsp;" . language_code('DSP_UPLOAD_SUCESS');
                                dsp_add_news_feed($user_id, 'audio');
                                dsp_add_notification($user_id, 0, 'audio');
                            } else {

                                $wpdb->query("UPDATE $dsp_member_audios SET file_name = '$newName',status_id=0 WHERE audio_file_id='$insertid'");

                                $wpdb->query("INSERT INTO $dsp_tmp_member_audios_table (t_audio_id ,t_user_id,t_filename,  	t_date_added,t_status_id) VALUES ('$insertid','$user_id','$newName','$created_date','0')");



                                $result[] = language_code('DSP_AUDIO_UPDATE_IN_HOURS_MSG');
                            } // end if($check_approve_audios_status->setting_status=='Y')
                        } else {
                            $wpdb->query("DELETE FROM $dsp_member_audios WHERE file_name='' AND user_id='$user_id' ");
                            $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_audios/user_' . $user_id;

                            $delete_audio = $directory_path . "/" . $newName;

                            unlink($delete_audio);

                            $limit_msg = language_code('DSP_UPLOAD_AUDIO_LIMIT');

                            $print_limit_msg = str_replace("<#COUNT#>", $check_audio_count->setting_value, $limit_msg);

                            $result[] = $print_limit_msg;
                        }  // End  if($count_uploaded_audios!=$check_audio_count)  */
                    } else {

                        $error_uploading = language_code('DSP_ERROR_UPLOADING');

                        $print_error_uploading = str_replace("<#FILE_NAME#>", $audio_file, $error_uploading);

                        $result[] = $print_error_uploading;
                    }



                    break;

                case 3:

                    $error_uploading = language_code('DSP_ERROR_UPLOADING');

                    $print_error_uploading = str_replace("<#FILE_NAME#>", $audio_file, $error_uploading);

                    $result[] = $print_error_uploading;

                default:

                    $error_uploading = language_code('DSP_SYSTEM_ERROR_UPLOADING');

                    $print_error_uploading = str_replace("<#FILE_NAME#>", $audio_file, $error_uploading);

                    $result[] = $print_error_uploading;
            }
        } elseif ($_FILES['file-upload']['error'] == 4) {

            $result[] = language_code('DSP_NO_FILE_SELECTED_MSG');
        } else {

            $error_uploading = language_code('DSP_FILE_NOT_UPLOADED_MSG');

            $print_error_uploading = str_replace("<#FILE_NAME#>", $audio_file, $error_uploading);

            $result[] = $print_error_uploading;
        }





        break;
} // END SWITCH CASE
// ---------------------------------------------- DELETE AUDIO ------------------------------------------ //



if ($Action == "Del" && !empty($get_audio_Id)) {

    $audio_file_name = $wpdb->get_row("SELECT * FROM $dsp_member_audios Where audio_file_id='$get_audio_Id'");

    $audio_name = $audio_file_name->file_name;

    if ($audio_name != "") {

        $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_audios/user_' . $user_id;

        $delete_audio = $directory_path . "/" . $audio_name;

        unlink($delete_audio);

        $wpdb->query("DELETE FROM $dsp_member_audios WHERE audio_file_id = '$get_audio_Id'");
    } // END if($audio_name!="")

    $delete_audio_msg = $audio_name . " has been Deleted.";
} // END if($Action=="Del" && !empty($get_audio_Id)) */
// ---------------------------------------------- DELETE AUDIO ------------------------------------------ //
?>

<?php if (isset($delete_audio_msg) && $delete_audio_msg != "") { ?>



    <div class="thanks">

        <p align="center" class="error"><?php echo $delete_audio_msg ?></p>

    </div>

    <?php
}

if (isset($result) && $result != "") {

    $result1 = implode(" ", $result);
    ?>

    <div class="thanks">

        <p align="center" class="error"><?php echo $result1; ?></p>

    </div>

<?php }
?>


<div class="box-border">
    <div class="box-pedding dsp-form-container">
        <div class="heading-submenu"><strong><?php echo language_code('DSP_ADD_AUDIO'); ?></strong></div>
        <div align="left">

            <?php
// ----------------------------------------------- Start Paging code------------------------------------------------------ //

            $page_name = $root_link . "media/add_audio/";

            $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_audios where user_id='$user_id' AND status_id=1");
            
            // Calculate total number of pages. Round up using ceil()
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
                    $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "page/$prev/\">".language_code('DSP_PREVIOUS')."</a></div>";
                else
                    $pagination.= "<span  class='disabled'>".language_code('DSP_PREVIOUS')."</span>";



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

                        $pagination.= "<div><a href=\"" . $page_name . "page/1/\">1</a>";

                        $pagination.= "<div><a href=\"" . $page_name . "page/2/\">2</a>";

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
                    $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "page/$next/\">".language_code('DSP_NEXT')."</a></div>";
                else
                    $pagination.= "<span class='disabled'>".language_code('DSP_NEXT')."</span>";

                $pagination.= "</div>\n";
            }



// ------------------------------------------------End Paging code------------------------------------------------------ //
            $exists_audios = $wpdb->get_results("SELECT * FROM $dsp_member_audios where user_id='$user_id' AND status_id=1 ORDER BY date_added  LIMIT $start, $limit  ");
            
            ?>
            <div class="dsp-row">
                <ul class="audio-list dspdp-row">
            <?php
            foreach ($exists_audios as $user_audios) {

                $audio_file_id = $user_audios->audio_file_id;

                $status_id = $user_audios->status_id;

                $audio_file_name = $user_audios->file_name;

                $private = $user_audios->private_audio;

                $user_id1 = $current_user->ID;

                $audio_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_audios/user_" . $user_id1 . "/" . $audio_file_name;

                $player_path = $pluginpath . "flash/player_mp3.swf";

                ?>


                        <li class="dspdp-col-sm-6 dsp-sm-6 dspdp-col-xs-12 dsp-xs-12">
                            <div class=" image-container ">
                                <div class="audio-box">
                                    <p>
                                        <span class="fa fa-music"></span>
                                    </p>
                                    <audio style="width:100%" controls name="media" class="dsp-spacer"  ><source src="<?php echo $audio_path; ?>" type="audio/mp3"></audio>
                                    <!-- <object type="application/x-shockwave-flash" data="<?php echo $player_path ?>" width="150px" height="25">
                                        <param name="movie" value="<?php echo $player_path ?>" />
                                        <param name="FlashVars" value="mp3=<?php echo $audio_path ?>&amp;showstop=1&amp;showinfo=1" />
                                    </object> -->
                                </div>
                            <div style=" text-align:center;" align="center">
                                <a class="dspdp-btn dspdp-btn-sm dspdp-btn-danger dsp-btn-danger dsp-btn-sm" href="<?php echo $root_link . "media/add_audio/Action/Del/audio_Id/" . $audio_file_id . "/"; ?>" onclick="if (!confirm('<?php echo language_code('DSP_DELETE_AUDIO_MESSAGE'); ?>'))
                                        return false;"><span style="cursor:pointer;"><?php echo language_code('DSP_DELETE'); ?></span></a></div>
                            </div>
                        </li>
                <?php } ?>
                    
                        </ul>
                </div>
                <div style="float:left; width:100%;">
                <?php
// --------------------------------  PRINT PAGING LINKS ------------------------------------------- //

                echo $pagination

// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                ?>

            </div> 

        </div>
        <div class="dsp-box-container dsp-space">
            <form  class="dspdp-box-border" name="frmaddaudio" action="<?php echo $page_name ?>" method="post" enctype="multipart/form-data">
                <div >
                    <div >
                       <span><?php echo language_code('DSP_ADD_AUDIO'); ?>&nbsp;</span>
                       <div class="dspdp-form-group dsp-form-group"> <span><input class="dspdp-form-control dsp-form-control" type="file" name="file-upload" value=""></span></div>
                       <div class="dspdp-form-group"> <span classs="dspdp-checkbox ">
                            <span  classs="dspdp-vertical-middle"><?php echo language_code('DSP_MAKE_PRIVATE');?></span>
                                <input name="private" classs="dspdp-vertical-middle dsp-vertical-middle" type="checkbox" value="Y"/>
                            </span>
                        </div>
                        <span><input type="hidden" name="txtmode" id="txtmode" value="add"></span>
                    </div>
                    <div class="btn-row">
                        <input type="submit" name="submit" class="dsp_submit_button dspdp-btn dspdp-btn-default" value="<?php echo language_code('DSP_ADD_AUDIO_BUTTON') ?>">
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
