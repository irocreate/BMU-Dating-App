<?php
/*

  Copyright (C) www.wpdating.com - All Rights Reserved!

  Author - www.wpdating.com

  WordPress Dating Plugin

  contact@wpdating.com

 */

// ----------------------------------------------- Start Paging code------------------------------------------------------ //  
if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

if (isset($_GET['page1']))
    $page = $_GET['page1'];
else
    $page = 1;



// How many adjacent pages should be shown on each side?

$adjacents = 2;

$limit = 6;

if ($page)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;

$page_name = $root_link . "?pid=3&pagetitle=view_audio";

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
        $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "&page1=$prev\">".language_code('DSP_PREVIOUS')."</a></div>";
    else
        $pagination.= "<span  class='disabled'>".language_code('DSP_PREVIOUS')."</span>";



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
        $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "&page1=$next\">".language_code('DSP_NEXT')."</a></div>";
    else
        $pagination.= "<span class='disabled'>".language_code('DSP_NEXT')."</span>";

    $pagination.= "</div>\n";
}



//******************************************************************************************************************************************
// ------------------------------------------------End Paging code------------------------------------------------------ //
// ----------------------------------Check member privacy Settings------------------------------------

$check_user_privacy_settings = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE   	view_my_audio='Y' AND user_id='$member_id'");

if (($check_user_privacy_settings > 0) && ($user_id != $member_id)) {  // check user privacy settings
    $check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'");

    if ($check_my_friends_list <= 0) {   // check member is not in my friend list
        ?>


        <div class="box-border">
            <div class="box-pedding">
                <div align="center"><?php echo language_code('DSP_CANT_VIEW_MEM_AUDIOS'); ?></div>

            </div>
        </div>


    <?php } else {   // -----------------------------else Check member is in my friend list ---------------------------- // 
        ?>



        <div class="box-border">
            <div class="box-pedding">
                <div align="center" >

                    <?php
                    $member_exist_audios = $wpdb->get_results("SELECT * FROM $dsp_member_audios WHERE user_id ='$member_id' AND status_id='1' ORDER BY date_added LIMIT $start, $limit ");
                    if(isset($member_exist_audios) && count($member_exist_audios) > 0){
                    $i = 0;
                    foreach ($member_exist_audios as $member_audios) {

                        $audio_file_name = $member_audios->file_name;



                        $private = $member_audios->private_audio;

                        $audio_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_audios/user_" . $member_id . "/" . $audio_file_name;

                        $player_path = $pluginpath . "flash/player_mp3.swf";

                        if (($i % 4) == 0) {
                            ?>

                            <ul>

                            <?php }  // End if(($i%4)==0)   ?>

                            <li style="float:left;padding-top:10px;width:193px;">

                                <?php
                                if ($private == 'Y') {

                                    if ($current_user->ID == $member_id) {
                                        ?>
                                        <div class="image-box"><img src="<?php echo $fav_icon_image_path ?>music3.png" style=""  border="0" align="center" alt="Music Image"/></div>

                                        <div class="path-plyer">
                                            <audio style="width:100%" controls="" name="media"  ><source src="<?php echo $audio_path; ?>" type="audio/mp3"></audio>
                                            <!-- <object type="application/x-shockwave-flash" data="<?php echo $player_path ?>" width="150px" height="25">

                                                <param name="movie" value="<?php echo $player_path ?>" />

                                                <param name="FlashVars" value="mp3=<?php echo $audio_path ?>&amp;showstop=1&amp;showinfo=1" />

                                            </object>-->

                                        </div>

                                    <?php } else { ?>

                                        <img src="<?php echo WPDATE_URL . '/images/private-audio.jpg'; ?>" style="width:85px; height:85px;" class="img2" align="left" alt="Private Audio"/>

                                        <?php
                                    }
                                } else {
                                    ?>

                                    <div class="image-box"><img src="<?php echo $fav_icon_image_path ?>music3.png" style="height:50px;" border="0" align="center" alt="Music 3"/></div>

                                    <div class="player-path">
                                        <audio style="width:100%" controls="" name="media"  ><source src="<?php echo $audio_path; ?>" type="audio/mp3"></audio>
                                        <!-- <object type="application/x-shockwave-flash" data="<?php echo $player_path ?>" width="150px" height="25">
                                            <param name="movie" value="<?php echo $player_path ?>" />
                                            <param name="FlashVars" value="mp3=<?php echo $audio_path ?>&amp;showstop=1&amp;showinfo=1" />
                                        </object>-->
                                    </div>

                                <?php } ?>



                            </li>

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
                    <?php }else{ ?>
                        <ul>
                            <li class="dspdp-col-sm-12 dsp-sm-12 dspdp-col-xs-12 dsp-xs-12">
                                <span class="dsp_span_pointer"><?php echo language_code('DSP_NO_AUDIO_UPLOADED'); ?></span><br />
                            </li>
                        </ul>
                    <?php } ?>
                </div>

            </div>

        </div>

        <?php
    }   // ------------------------------------------------- End if Check in my friend list --------------------------------- //
} else {

// -------------------------------------- else  Privacy Setting for Everyone ------------------------------------------- // 
    ?>



    <div class="box-border">
        <div class="box-pedding">
            <div align="center">

                <ul class="audio-payer dsp-row">

                    <?php
                    $member_exist_audios = $wpdb->get_results("SELECT * FROM $dsp_member_audios WHERE user_id = '$member_id' AND status_id='1' ORDER BY date_added LIMIT $start, $limit ");
                    if(count($member_exist_audios) > 0){
                    $i = 0;

                    foreach ($member_exist_audios as $member_audios) {

                        $audio_file_name = $member_audios->file_name;

                        $private = $member_audios->private_audio;

                        $audio_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_audios/user_" . $member_id . "/" . $audio_file_name;

                        $player_path = $pluginpath . "flash/player_mp3.swf";

                        if (($i % 3) == 0) {
                            ?>

                        <?php }  // End if(($i%4)==0)  ?>

                        <li class="dsp-sm-3">
<div class="dsp-border-container">
                            <div class="container-audio">

                                <?php
                                if ($private == 'Y') {
                                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_id'");
                                $favt_mem = array();
                                foreach ($private_mem as $private) {
                                    $favt_mem[] = $private->favourite_user_id;
                                }

                                    if ($current_user->ID == $member_id) {
                                        ?>

                                        <div class="imag-box"><img src="<?php echo $fav_icon_image_path ?>music3.png" style="height:50px;" border="0" align="center" alt="Music 3" /></div>

                                        <object type="application/x-shockwave-flash" data="<?php echo $player_path ?>" width="150px" height="25">

                                            <param name="movie" value="<?php echo $player_path ?>" />

                                            <param name="FlashVars" value="mp3=<?php echo $audio_path ?>&amp;showstop=1&amp;showinfo=1" />

                                        </object>

                                    </div>

                                <?php } else {
                                        if (!in_array($current_user->ID , $favt_mem)) {
                                    ?>
                                            <img src="<?php echo WPDATE_URL . '/images/private-audio.jpg'; ?>" style="width:85px; height:85px;" class="img2" align="left" alt="Private Audio"/>
                                    <?php } else { ?>
                                            <div class="imag-box"><img src="<?php echo $fav_icon_image_path ?>music3.png" style="height:50px;" border="0"
                                                                       align="center" alt="Music 3"/></div>

                                            <object type="application/x-shockwave-flash" data="<?php echo $player_path ?>" width="150px" height="25">

                                                <param name="movie" value="<?php echo $player_path ?>"/>

                                                <param name="FlashVars" value="mp3=<?php echo $audio_path ?>&amp;showstop=1&amp;showinfo=1"/>

                                            </object>
                                        </div>
                                            <?php
                                        }
                                }
                            } else {
                                ?>

                                <div class="image-box">

                                    <img src="<?php echo $fav_icon_image_path ?>music3.png" style="height:50px;" border="0" align="center" alt="Music 3"/>

                                </div>

                                <div class="player-path">

                                    <object type="application/x-shockwave-flash" data="<?php echo $player_path ?>" width="150px" height="25">

                                        <param name="movie" value="<?php echo $player_path ?>" />

                                        <param name="FlashVars" value="mp3=<?php echo $audio_path ?>&amp;showstop=1&amp;showinfo=1" />

                                    </object>

                                </div>

                            <?php } ?>
</div>
                        </li>

                        <?php
                        $i++;
                    }
                    ?>

                    <div style="float:left; width:100%;">

                        <?php
                        // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //

                        echo $pagination

// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                        ?>

                    </div>
                    <?php }else{?>
                        <li class="dspdp-col-sm-12 dsp-sm-12 dspdp-col-xs-12 dsp-xs-12">
                            <span class="dsp_span_pointer"><?php echo language_code('DSP_NO_AUDIO_UPLOADED'); ?></span><br />
                        </li>
                    <?php } ?>
                </ul>

            </div>

        </div>

    </div>
<?php }