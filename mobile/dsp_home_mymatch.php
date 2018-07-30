<?php
global $wpdb;
include("../../../../wp-config.php");
include( WP_DSP_ABSPATH . 'mobile/files/includes/english.php');  // include all table names file
$DSP_USER_ONLINE_TABLE = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$DSP_USER_PROFILES_TABLE = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;
$DSP_MEMBERS_PHOTOS_TABLE = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
$DSP_PROFILE_SETUP_TABLE = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
$DSP_PROFILE_QUESTIONS_DETAILS_TABLE = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;  // print session USER_ID
include_once(WP_DSP_ABSPATH . 'mobile/dsp_get_image.php');
include_once(WP_DSP_ABSPATH . '/general_settings.php');
$image_path = get_bloginfo('url') . '/wp-content/';  // image Path
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
$root_link = $_GET['root_link'];
?>
<table width="100%">
    <?php
    //--------------------------------- MY MATCHES----------------------------------------------------------->


    $active_question_id = $wpdb->get_results("SELECT profile_setup_id FROM $DSP_PROFILE_SETUP_TABLE WHERE display_status='Y'");

    foreach ($active_question_id as $question_id) {
        $active_question_ids[] = $question_id->profile_setup_id;
    }
    if ($active_question_ids != "") {
        $active_question_ids1 = implode(",", $active_question_ids);
    }
    //echo "<br>SELECT * FROM $DSP_PROFILE_QUESTIONS_DETAILS_TABLE WHERE profile_question_id IN ($active_question_ids1) and user_id='$user_id'";
    $matches_option = $wpdb->get_results("SELECT * FROM $DSP_PROFILE_QUESTIONS_DETAILS_TABLE WHERE profile_question_id IN ($active_question_ids1) and user_id='$user_id'");

    foreach ($matches_option as $match_opt_id) {
        $matches_option_id1[] = $match_opt_id->profile_question_option_id;
    }

    if ($matches_option_id1 != "") {
        $matches_option_id = implode(",", $matches_option_id1);
    }
    //echo "<br>SELECT * FROM $DSP_USER_PROFILES_TABLE WHERE user_id='$user_id'<br>";
    $member_gender = $wpdb->get_row("SELECT * FROM $DSP_USER_PROFILES_TABLE WHERE user_id='$user_id'");
    $member_gender->gender;

    //echo $check_couples_mode->setting_status .'statyus';

    if ($member_gender->gender == 'M')
        $match_query = " and gender='F' ";
    else if ($member_gender->gender == 'F')
        $match_query = " and gender='M' ";
    else if ($member_gender->gender == 'C')
        $match_query = " and gender='C' ";


    $count_my_mathces_query = "SELECT COUNT(*) FROM $DSP_PROFILE_QUESTIONS_DETAILS_TABLE A 
								INNER JOIN $DSP_USER_PROFILES_TABLE B ON(A.user_id=B.user_id) 
								WHERE profile_question_option_id IN ($matches_option_id) 
								and A.user_id<>$user_id $match_query";

    $count_my_matches = $wpdb->get_var($count_my_mathces_query);
    //echo $count_my_mathces_query;
    if ($count_my_matches > 0) {
        // ----------------------------------------------- Start Paging code New Member------------------------------------------------------ // 
        if (isset($_GET['page1']))
            $page1 = $_GET['page1'];
        else
            $page1 = 1;

        //get  pagination limit from database
        $pagination_limit = DSP_PAGINATION_LIMIT;
        $max_results1 = $pagination_limit;

        $adjacents = DSP_PAGINATION_ADJACENTS;
        $limit = $max_results1;

        $from1 = (($page1 * $max_results1) - $max_results1);

        // check if couple mode is off then exclude the couple user

        $totalQuery = "SELECT DISTINCT(A.user_id),B.make_private,B.gender FROM $DSP_PROFILE_QUESTIONS_DETAILS_TABLE A 
							INNER JOIN $DSP_USER_PROFILES_TABLE B ON(A.user_id=B.user_id)
							WHERE profile_question_option_id IN ($matches_option_id) 
							and A.user_id<>$user_id $match_query";

        //echo $totalQuery;			 
        $total_results1 = $wpdb->get_results($totalQuery);

        // Calculate total number of pages. Round up using ceil()
        $total_pages1 = count($total_results1);
        // ------------------------------------------------End Paging code------------------------------------------------------ //
        $getMatchMemLimitQuery = $totalQuery . " LIMIT " . $from1 . ", " . $max_results1;

        $matches_members_details = $wpdb->get_results($getMatchMemLimitQuery);
        $count_my_match = count($matches_members_details);
        $i = 0;

        foreach ($matches_members_details as $my_matches) {
            $match_user_id = $my_matches->user_id;
            $match_user_name = $wpdb->get_var("SELECT display_name FROM $DSP_USERS_TABLE WHERE ID=$match_user_id ");

            $favt_mem = array();

            $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$my_matches->user_id'");

            foreach ($private_mem as $private) {

                $favt_mem[] = $private->favourite_user_id;
            }

            if (($i % 2) == 0) {
                ?>
                <tr>
                <?php }  // End if(($i%3)==0) ?>
                <td align="left" <?php
                if (($i % 2) == 0) {
                    echo 'width="30%"';
                }
                ?>>
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center">

                                <?php
                                // check for private member
                                if ($check_couples_mode->setting_status == 'Y') {
                                    if ($my_matches->gender == 'C') {
                                        if ($my_matches->make_private == 'Y') {
                                            if ($current_user->ID != $match_user_id) {

                                                if (!in_array($current_user->ID, $favt_mem)) {
                                                    ?>
                                                    <a href="<?php
                                                    echo add_query_arg(array('pid' => 3,
                                                        'mem_id' => $match_user_id,
                                                        'pagetitle' => "view_profile",
                                                        'view' => "my_profile"), $root_link);
                                                    ?>" >
                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;"  class="dsp_img3" />
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 3, 'mem_id' => $match_user_id,
                                                        'pagetitle' => "view_profile",
                                                        'view' => "my_profile"), $root_link);
                                                    ?>" >				
                                                        <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $match_user_id,
                                                    'pagetitle' => "view_profile",
                                                    'view' => "my_profile"), $root_link);
                                                ?>" >				
                                                    <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                            <?php } ?>
                                        <?php } else {
                                            ?>                
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $match_user_id,
                                                'pagetitle' => "view_profile", 'view' => "my_profile"), $root_link);
                                            ?>" >				
                                                <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>
                                        <?php } ?>

                                    <?php } else {
                                        ?>

                                        <?php if ($my_matches->make_private == 'Y') { ?>
                                            <?php if ($current_user->ID != $match_user_id) { ?>

                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 3, 'mem_id' => $match_user_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >
                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;"  class="dsp_img3" />
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php
                                                    echo add_query_arg(array('pid' => 3,
                                                        'mem_id' => $match_user_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >				
                                                        <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $match_user_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>" >				
                                                    <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                            <?php } ?>
                                        <?php } else { ?> 
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $match_user_id,
                                                'pagetitle' => "view_profile"), $root_link);
                                            ?>">				
                                                <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>
                                        <?php } ?>

                                        <?php
                                    }
                                } else {
                                    ?>

                                    <?php if ($my_matches->make_private == 'Y') { ?>
                                        <?php if ($current_user->ID != $match_user_id) { ?>

                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $match_user_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>" >
                                                    <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;"  class="dsp_img3" />
                                                </a>                
                                            <?php } else {
                                                ?>
                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $match_user_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>" >				
                                                    <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $match_user_id,
                                                'pagetitle' => "view_profile"), $root_link);
                                            ?>" >				
                                                <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                        <?php } ?>
                                    <?php } else { ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $match_user_id,
                                            'pagetitle' => "view_profile"), $root_link);
                                        ?>">				
                                            <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>
                                        <?php
                                    }
                                }
                                //-------------	end of check for private member---------------------------------------
                                ?>

                                <!--<a href="<?php
                                echo add_query_arg(array('pid' => 3,
                                    'mem_id' => $match_user_id, 'pagetitle' => 'view_profile'), $root_link);
                                ?>">
                                <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>" width="55px" height="55px" class="dsp_img3" /></a>-->
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <a href="<?php
                                echo add_query_arg(array('pid' => 3, 'mem_id' => $match_user_id,
                                    'pagetitle' => 'view_profile'), $root_link);
                                ?>"><?php echo $match_user_name; ?></a>
                            </td>
                        </tr>
                    </table>
                </td>
                <?php
                if ($count_my_match == '1') {
                    ?>
                    <td   >&nbsp;</td>		

                    <?php
                }
                $i++;
                unset($favt_mem);
            }
            ?>
        </tr>
    </table>
    <br>
    <?php
    /* Setup page vars for display. */
    if ($page1 == 0)
        $page1 = 1;     //if no page var is given, default to 1.
    $prev = $page1 - 1;       //previous page is page - 1
    $next = $page1 + 1;       //next page is page + 1
    $lastpage = ceil($total_pages1 / $limit);
    //lastpage is = total pages / items per page, rounded up.
    $lpm1 = $lastpage - 1;      //last page minus 1
    // echo 'page1='.$page1.' last page='.$lastpage.'  total page='.$total_pages1.'limit='.$limit;
    /*
      Now we apply our rules and draw the pagination object.
      We're actually saving the code to a variable in case we want to draw it more than once.
     */
    $pagination = "";
    if ($lastpage > 1) {
        $pagination .= "<div class=\"dspmb_pagination\">";
        //previous button
        if ($page1 > 1)
            $pagination.= "<div><a onclick=\"getMatchMemPage('" . $prev . "','" . $root_link . "');\" >Previous</a></div>";
        else
            $pagination.= "<span class=\"disabled\">previous</span>";

        //pages	
        if ($lastpage <= 3 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page1)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<div><a onclick=\"getMatchMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
            }
        }
        elseif ($lastpage > 3 + ($adjacents * 2)) { //enough pages to hide some//5
            //close to beginning; only hide later pages
            if ($page1 <= 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= 1 + ($adjacents * 2); $counter++) {
                    if ($counter == $page1)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<div><a onclick=\"getMatchMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                }
                $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                $pagination.="<div><a onclick=\"getMatchMemPage('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

                $pagination.="<div><a onclick=\"getMatchMemPage('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
            }
            //in middle; hide some front and some back
            elseif ($lastpage - ($adjacents * 2) > $page1 && $page1 > ($adjacents * 2)) {
                $pagination.="<div><a onclick=\"getMatchMemPage('1','" . $root_link . "')\">1</a></div>";

                $pagination.= "<div><a onclick=\"getMatchMemPage('2','" . $root_link . "')\">2</a></div>";
                $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                for ($counter = $page1 - $adjacents; $counter <= $page1 + $adjacents; $counter++) {
                    if ($counter == $page1)
                        $pagination.= "<div class=\"current\">$counter</div>";
                    else
                        $pagination.= "<div><a onclick=\"getMatchMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                }
                $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                $pagination.= "<div><a onclick=\"getMatchMemPage('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

                $pagination.= "<div><a onclick=\"getMatchMemPage('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
            }
            //close to end; only hide early pages
            else {
                $pagination.= "<div><a onclick=\"getMatchMemPage('1','" . $root_link . "')\">1</a></div>";
                $pagination.= "<div><a onclick=\"getMatchMemPage('2','" . $root_link . "')\">2</a></div>";
                $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page1)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<div><a onclick=\"getMatchMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                }
            }
        }

        //next button
        if ($page1 < $lastpage)
            $pagination.="<div><a onclick=\"getMatchMemPage('" . $next . "','" . $root_link . "');\" >next</a></div>";
        else
            $pagination.= "<span class=\"disabled\">next</span>";
        $pagination.= "</div>\n";
    }
    ?>
    <div class="dspmb_main_paging">
        <?php echo $pagination ?>
    </div>

    <?php
}
else {
    ?><table width="45%">
        <tr><td colspan="2" ><strong><?php echo language_code('DSP_NO_MATCHES_FOUND_MSG') ?></strong></td></tr>
    <?php } ?>
</table>