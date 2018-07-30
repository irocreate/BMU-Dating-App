<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_SUB_MENU_ADD_AUDIO'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>

</div>
<?php
$dsp_member_audios = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
$dsp_tmp_member_audios_table = $wpdb->prefix . DSP_TEMP_MEMBER_AUDIOS_TABLE;

if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
// ----------------------------------------------- Start Paging code------------------------------------------------------ //  

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

// ----------------------------------------------- Start Paging code------------------------------------------------------ //

$Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';



$get_audio_Id = isset($_REQUEST['audio_Id']) ? $_REQUEST['audio_Id'] : '';

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


<div class="ui-content" data-role="content">
    <div class="content-primary">

        <?php if (isset($delete_audio_msg) && $delete_audio_msg != "") {
            ?>
            <div class="thanks">
                <p align="center" class="error"><?php echo $delete_audio_msg ?></p>
            </div>
        <?php } ?>

        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">
            <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">


                <div style="width:80%; float:left; ">
                    <div>
                        <div style="width: 100%;padding-bottom: 5px;"><?php echo language_code('DSP_ADD_AUDIO'); ?>&nbsp;</div>
                        <span><button><?php echo language_code('DSP_UPLOAD_BUTTON') ?></button></span>
                        <span>Private:<input name="private" type="checkbox" value="Y"/> </span>
                        <span><input type="hidden" name="txtmode" id="txtmode" value="add"></span>
                    </div>													
                </div>


            </li>
        </ul>
        <div class="box-page">

            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">


                <?php
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
                $root_link = "";
                $page_name = $root_link . "?pid=4&pagetitle=add_audio";

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

                    $pagination .= "<div class='button-area'>";

                    //previous button

                    if ($page > 1) {
                        $pagination.="
			 
				<div onclick='callAudio(\"page\",1)' class='btn-pre1'>
					<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/bb.png" . "'/>
				</div>";
                    } else {
                        $pagination.= "
				<div class='btn-pre1'>
					<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/b.png" . "'/>
				</div>";
                    }

                    if ($page > 1) {
                        $pagination.="<div  onclick='callAudio(\"page\",$prev)' class='btn-pre2'>
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
			<div onclick='callAudio(\"page\",$next)' class='main4' >
				<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m/images/c.png" . "'/>
			</div>";

                        $pagination.= "	<div onclick='callAudio(\"page\",$lastpage)' class='main5'>
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

// ------------------------------------------------End Paging code------------------------------------------------------ //

                $exists_audios = $wpdb->get_results("SELECT * FROM $dsp_member_audios where user_id='$user_id' AND status_id=1 ORDER BY date_added  LIMIT $start, $limit  ");

                $i = 0;

                foreach ($exists_audios as $user_audios) {

                    $audio_file_id = $user_audios->audio_file_id;

                    $status_id = $user_audios->status_id;

                    $audio_file_name = $user_audios->file_name;

                    $private = $user_audios->private_audio;

                    $user_id1 = $user_id;

                    $audio_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_audios/user_" . $user_id1 . "/" . $audio_file_name;

                    //	$player_path=$pluginpath."flash/player_mp3.swf";
                    ?>

                    <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">

                        <div class="audio-box">
                            <p>
                                <img src="<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/images/music3.png'; ?>" height="50px" border="0" align="center" />
                            </p>



                        </div>

                        <div style="float:left;text-align:center;padding-top: 10px;font-size: 16px;" align="center">
                            <p>
                                <a onclick="playAudio('<?php echo $audio_path; ?>', '<?php echo $i; ?>')">
                                    <img id="play<?php echo $i; ?>" src="images/play.png" style="height: 30px; width: 30px;"/>	
                                </a>
                                <a onclick="stopAudio('<?php echo $i; ?>')">
                                    <img src="images/stop.png" style="height: 30px; width: 30px;"/>
                                </a>
                            </p>
                            <p>
                                <span onclick="callAudio('Del', '<?php echo $audio_file_id ?>')" style="text-decoration:underline;"><?php echo language_code('DSP_DELETE'); ?></span>
                            </p>
                        </div>

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