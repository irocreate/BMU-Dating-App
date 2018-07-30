<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_menu.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_SUB_MENU_ADD_VIDEO'); ?></h1>
    <?php include_once("page_home.php");?> 

</div>
<?php
// ----------------------------------------------- Start Paging code------------------------------------------------------ //  

$dsp_member_videos = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
$root_link = "";

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


$video_mode = isset($_REQUEST['txtmode']) ? $_REQUEST['txtmode'] : '';



$created_date = date("Y-m-d H:m:s");



$Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';



$video_file = isset($_FILES['file-upload']) ? $_FILES['file-upload']['name'] : '';



$get_video_Id = isset($_REQUEST['video_Id']) ? $_REQUEST['video_Id'] : '';



$count_uploaded_video = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_videos where user_id='$user_id' AND status_id=1");




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

    $delete_video_msg = "Deleted Video.";
} // END if($Action=="Del" && !empty($get_video_Id)) */
// ---------------------------------------------- DELETE VIDEO ------------------------------------------ //
?>



<?php if (isset($delete_video_msg) && $delete_video_msg != "") { ?>



<div class="success-message "><?php echo $delete_video_msg ?></div>




<?php
}



if (isset($result) && $result != "") {

    $result1 = implode(" ", $result);
    ?>



    <div class="success-message">

     <?php echo $result1; ?>
 </div>



 <?php }
 ?>



 <div class="ui-content" data-role="content">
    <div class="content-primary">




        <div class="heading-text text-center"><?php echo language_code('DSP_ADD_VIDEO'); ?> Formats - .3GP, .MP4</div>
    </div>

    <label data-role="fieldcontain" class="form-group">  
        <div class="clearfix">                                    
            <div class="mam_reg_lf form-label">
                <input name="private" type="checkbox" class="checkbox-singleline" value="Y" onclick="savePrivate(this.value)"/>
                <?php echo language_code('DSP_MAKE_PRIVATE'); ?></div>
            </div>
        </label>

        <span><input type="hidden" name="txtmode" id="txtmode" value="add"></span>
         <div class="btn-blue-wrap"><button onclick="uploadVideo();" class="mam_btn btn-blue"><?php echo language_code('DSP_UPLOAD_BUTTON') ?></button></div>

    </div>


</div>




<div align="left" >
    <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">


        <?php
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
        $page_name = $root_link . "?pid=4&pagetitle=add_video";

        $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_videos where user_id='$user_id' AND status_id=1");
// Calculate total number of pages. Round up using ceil()
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

                    $pagination .= "<div class='button-area'>";

                    //previous button

                    if ($page > 1) {
                        $pagination.="

                        <div onclick='callVideo(\"page\",1)' class='btn-pre1'>
                           <img src='images/icons/prev-1.png' />
                       </div>";
                   } else {
                    $pagination.= "
                    <div class='btn-pre1'>
                      <img src='images/icons/prev-1.png' />
                   </div>";
               }

               if ($page > 1) {
                $pagination.="<div  onclick='callVideo(\"page\",$prev)' class='btn-pre2'>
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
    <div onclick='callVideo(\"page\",$next)' class='main4' >
        <img src='images/icons/next-all.png' />
    </div>";

    $pagination.= "	<div onclick='callVideo(\"page\",$lastpage)' class='main5'>
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

$exists_videos = $wpdb->get_results("SELECT * FROM $dsp_member_videos where user_id='$user_id' AND status_id=1 ORDER BY date_added  LIMIT $start, $limit  ");

$i = 0;
$supported_video_formats = array("mp4", "3gp");
foreach ($exists_videos as $user_videos) {

    $video_file_id = $user_videos->video_file_id;
    $status_id = $user_videos->status_id;
    $video_file_name = $user_videos->file_name;
    $video_ext = explode(".", $video_file_name);
    $user_id1 = $user_id;
    $video_path = "http://" . $_SERVER['HTTP_HOST'] . "/wp-content/uploads/dsp_media/user_videos/user_" . $user_id1 . "/" . $video_file_name;
    ?>

    <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">


        <div class="video-box">
            <div class="iPhonePlayer" style="display:none;"> 
                <video id="player" width="100" height="100" controls="controls" autostart="false">
                    <source src="<?php echo $video_path ?>"  />
                    </video>
                </div>
                <div class="AndroidPlayer" style="display:none;"> 
                    <a onclick="playMyVideo('<?php echo $video_path ?>')">
                        <img src="<?php echo get_bloginfo('url') ?>/wp-content/plugins/dsp_dating/m/images/play.jpeg" style="width: 100px;  height: 100px">
                    </a>
                </div>


            </div>
            <span onclick="callVideo('Del', '<?php echo $video_file_id ?>')" style="cursor:pointer;display: block;text-align: center; text-decoration:underline;"><?php echo language_code('DSP_DELETE'); ?></span>
            <?php if(!in_array($video_ext, $supported_video_formats)) { ?>
            <span style="text-align: center; font-weight: bold; font-size: 10px;">Note:- This video requires external video player.</span>
            <?php } ?>
        </li>



        <?php
        $i++;
    }
    ?>



</ul>




<div class="ds_pagination" > 
    <?php echo $pagination ?>
</div>



</div>



</div>
<?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>

<?php include_once("dspLeftMenu.php"); ?>