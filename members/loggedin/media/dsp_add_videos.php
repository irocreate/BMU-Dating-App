<?php
/*

  Copyright (C) www.wpdating.com - All Rights Reserved!

  Author - www.wpdating.com

  WordPress Dating Plugin

  contact@wpdating.com

 */

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

$video_mode = isset($_REQUEST['txtmode']) ? $_REQUEST['txtmode'] : '';
$created_date = date("Y-m-d H:m:s");

$Action = get('Action');

$video_file = isset($_FILES['file-upload']) ? $_FILES['file-upload']['name'] : '';

$get_video_Id = get('video_Id');

$count_uploaded_video = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_videos where user_id='$user_id' AND status_id=1");

if (isset($_REQUEST['private']) && $_POST['private'] != '') {

    $private = isset($_REQUEST['private']) ? $_REQUEST['private'] : '';
} else {

    $private = 'N';
}



switch ($video_mode) {

    case 'add':    // ADD PHOTO 

        $uploadfile1 = ABSPATH . "wp-content/uploads/dsp_media/user_videos/user_" . $user_id . "/";

// define a constant for the maximum upload size

        define('MAX_FILE_SIZE', 51200000);

        define('UPLOAD_DIR', $uploadfile1);

       
        // check that file is within the permitted size
        $sizeOK = false;
        $typeOK = false;
        if ($_FILES['file-upload']['size'] >= 0 || $_FILES['file-upload']['size'] <= MAX_FILE_SIZE) {

            $sizeOK = true;
        }

// create an array of permitted MIME types

        $permitted = array('video/quicktime', 'video/x-ms-wmv', 'video/mp4', 'video/avi',
            'application/octet-stream');

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

                    $wpdb->query("INSERT INTO $dsp_member_videos SET user_id='$user_id',date_added='$created_date',status_id=0, private_video='$private'");

                    $insertid = $wpdb->insert_id; // AUTOINCREMENT ID

                    $video_name = basename($video_file);

                    $newName = $insertid . "_" . $video_name;

                    if (!file_exists('wp-content/uploads/dsp_media/user_videos')) {

                        mkdir('wp-content/uploads/dsp_media/user_videos', 0755); // it will default to 0755 regardless 

                        chmod('wp-content/uploads/dsp_media/user_videos', 0777);  // Finally, chmod it to 777
                    }

                    if (!file_exists('wp-content/uploads/dsp_media/user_videos/user_' . $user_id)) {

                        mkdir('wp-content/uploads/dsp_media/user_videos/user_' . $user_id, 0755); // it will default to 0755 regardless 

                        chmod('wp-content/uploads/dsp_media/user_videos/user_' . $user_id, 0777);  // Finally, chmod it to 777
                    }



                    // check if a file of the same name has been uploaded

                    if (!file_exists(UPLOAD_DIR . $video_file)) {

                        // move the file to the upload folder and rename it

                        $success = move_uploaded_file($_FILES['file-upload']['tmp_name'], UPLOAD_DIR . $newName);
                    }



                    if ($success) {

                        if (isset($count_uploaded_video) && $count_uploaded_video < $check_video_count->setting_value) {  // check condition Number of Audios in A Profiles.
                            if ($check_approve_videos_status->setting_status == 'Y') {  // if Audio approve status is Y then Audio Automatically Approved.
                                $wpdb->query("UPDATE $dsp_member_videos SET file_name = '$newName',status_id=1 WHERE video_file_id='$insertid'");

                                $result[] = " $video_file &nbsp;" . language_code('DSP_UPLOAD_SUCESS');



                                dsp_add_news_feed($user_id, 'video');

                                dsp_add_notification($user_id, 0, 'video');
                            } else {

                                $wpdb->query("UPDATE $dsp_member_videos SET file_name = '$newName',status_id=0 WHERE video_file_id='$insertid'");

                                $wpdb->query("INSERT INTO $dsp_tmp_member_videos_table (t_video_id ,t_user_id,t_filename,  	t_date_added,t_status_id) VALUES ('$insertid','$user_id','$newName','$created_date','0')");



                                $result[] = language_code('DSP_VIDEO_UPDATE_IN_HOURS_MSG');
                            } // end if($check_approve_videos_status->setting_status=='Y')
                        } else {



                            $wpdb->query("DELETE FROM $dsp_member_videos WHERE file_name='' AND user_id='$user_id' ");

                            $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_videos/user_' . $user_id;

                            $delete_video = $directory_path . "/" . $newName;

                            unlink($delete_video);

                            $limit_msg = language_code('DSP_UPLOAD_VIDEO_LIMIT');

                            $print_limit_msg = str_replace("<#COUNT#>", $check_video_count->setting_value, $limit_msg);

                            $result[] = $print_limit_msg;
                        }  // End  if($count_uploaded_audios!=$check_audio_count)  */
                    } else {

                        $error_uploading = language_code('DSP_ERROR_UPLOADING');

                        $print_error_uploading = str_replace("<#FILE_NAME#>", $video_file, $error_uploading);

                        $result[] = $print_error_uploading;
                    }



                    break;

                case 3:

                    $error_uploading = language_code('DSP_ERROR_UPLOADING');

                    $print_error_uploading = str_replace("<#FILE_NAME#>", $video_file, $error_uploading);

                    $result[] = $print_error_uploading;

                default:

                    $error_uploading = language_code('DSP_SYSTEM_ERROR_UPLOADING');

                    $print_error_uploading = str_replace("<#FILE_NAME#>", $video_file, $error_uploading);

                    $result[] = $print_error_uploading;
            }
        } elseif ($_FILES['file-upload']['error'] == 4) {

            $result[] = language_code('DSP_NO_FILE_SELECTED_MSG');
        } else {

            $error_uploading = language_code('DSP_FILE_NOT_UPLOADED_MSG');

            $print_error_uploading = str_replace("<#FILE_NAME#>", $video_file, $error_uploading);

            $result[] = $print_error_uploading;
        }





        break;
} // END SWITCH CASE
// ---------------------------------------------- DELETE VIDEO ------------------------------------------ //



if ($Action == "Del" && !empty($get_video_Id)) {

    $video_file_name = $wpdb->get_row("SELECT * FROM $dsp_member_videos Where video_file_id='$get_video_Id'");

    $video_name = $video_file_name->file_name;

    if ($video_name != "") {

        $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_videos/user_' . $user_id;

        $delete_video = $directory_path . "/" . $video_name;

        unlink($delete_video);

        $wpdb->query("DELETE FROM $dsp_member_videos WHERE video_file_id = '$get_video_Id'");
    } // END if($audio_name!="")

    $delete_video_msg =  language_code('DSP_DELETE_VIDEO');
} // END if($Action=="Del" && !empty($get_video_Id)) */
// ---------------------------------------------- DELETE VIDEO ------------------------------------------ //
?>

<?php if (isset($delete_video_msg) && $delete_video_msg != "") { ?>

    <div class="thanks">

        <p align="center" class="error dspdp-alert dspdp-alert-danger"><?php echo $delete_video_msg ?></p>

    </div>

    <?php
}

if (isset($result) && $result != "") {

    $result1 = implode(" ", $result);
    ?>

    <div class="thanks">

        <p align="center" class="error dspdp-alert dspdp-alert-danger"><?php echo $result1; ?></p>

    </div>

<?php }
?>


<div class="box-border">
    <div class="box-pedding">
        <div class="heading-submenu"><strong><?php echo language_code('DSP_MENU_ADD_VIDEOS'); ?></strong></div>
        <div align="left" class="dspdp-spacer">

            <ul class="video-list dspdp-row dsp-row">

                <?php
// ----------------------------------------------- Start Paging code------------------------------------------------------ //

                $page_name = $root_link . "media/add_video/";

                $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_videos where user_id='$user_id' AND status_id=1");

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

                $exists_videos = $wpdb->get_results("SELECT * FROM $dsp_member_videos where user_id='$user_id' AND status_id=1 ORDER BY date_added  LIMIT $start, $limit  ");

                $i = 0;

                foreach ($exists_videos as $user_videos) {

                    $video_file_id = $user_videos->video_file_id;

                    $status_id = $user_videos->status_id;

                    $video_file_name = $user_videos->file_name;

                    $video_ext = explode(".", $video_file_name);

                    $user_id1 = $current_user->ID;

                    $video_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_videos/user_" . $user_id1 . "/" . $video_file_name;

                    if (($i % 3) == 0) {
                        ?>

                    <?php } ?>

                    <li class="dspdp-col-sm-6 dsp-sm-6">
                    <div class="image-container">
                        <div class="video-box">
                            <?php
                            if ($video_ext[1] == "mov" || $video_ext[1] == "mp4") {
                            ?>
                                <video id="sampleMovie" src="<?php echo $video_path ?>" controls width="200" height="200"></video>
                                <!-- <embed src="<?php echo $video_path ?>" width="200" height="200" autoplay="false" controller="true" type="video/quicktime" scale="tofit" PLUGINURL = "https://www.adobe.com/support/flashplayer/downloads.html"> </embed><br /> -->
                            <?php } else { ?>
                                <video id="sampleMovie" src="<?php echo $video_path ?>" controls width="200" height="200" ></video>
                                <!-- <embed src="<?php echo $video_path ?>" width="200" height="200" autostart="0" showcontrols="1" type="application/x-mplayer2" pluginspage="http://www.microsoft.com/windows/windowsmedia/download/" PLUGINURL = "https://www.adobe.com/support/flashplayer/downloads.html">test </embed> -->
                            <?php } ?>
                        </div>
                        <div class="row-delete"><a class="dspdp-btn dspdp-btn-danger dspdp-btn-sm dsp-btn dsp-btn-danger dsp-btn-sm" href="<?php echo $root_link . "media/add_video/Action/Del/video_Id/" . $video_file_id . "/"; ?>" onclick="if (!confirm('<?php echo language_code('DSP_DELETE_VIDEO_MESSAGE'); ?>')) return false;"><span><?php echo language_code('DSP_DELETE'); ?></span></a></div>
                    </div></li>
                    <?php
                    $i++;
                }
                ?>
            </ul>
            <div style="float:left; width:100%;">

                <?php
// --------------------------------  PRINT PAGING LINKS ------------------------------------------- //

                echo $pagination

// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                ?>

            </div> 

        </div>
        <div class="dsp-box-container dsp-space dsp-form-container">
            <form name="frmaddvideo" class="dspdp-box-border" enctype="multipart/form-data" action="<?php echo $page_name; ?>" method="post">

                <div>

                    <div>

                        <div class="dspdp-form-group dsp-form-group"><span><?php echo language_code('DSP_ADD_VIDEO'); ?>&nbsp;</span>

                        <span><input class="dspdp-form-control dsp-form-control" type="file" name="file-upload" value=""></span></div>



                        <div class="dspdp-form-group"><span><?php echo language_code('DSP_MAKE_PRIVATE'); ?><input name="private" type="checkbox" value="Y"/> </span></div>

                        <span><input type="hidden" name="txtmode" id="txtmode" value="add"></span>

                    </div>

                    <div class="btn-row"><input type="submit" name="submit" class="dsp_submit_button  dspdp-btn dspdp-btn-default" value="<?php echo language_code('DSP_UPLOAD_BUTTON') ?>"></div>

                </div>

            </form>
        </div>

    </div>
</div>
