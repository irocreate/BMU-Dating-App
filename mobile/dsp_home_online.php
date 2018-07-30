<?php
global $wpdb;
include("../../../../wp-config.php");
include( WP_DSP_ABSPATH . 'mobile/files/includes/english.php');  // include all table names file
$DSP_USER_ONLINE_TABLE = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$DSP_USER_PROFILES_TABLE = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
include_once(WP_DSP_ABSPATH . 'mobile/dsp_get_image.php');
include_once(WP_DSP_ABSPATH . '/general_settings.php');

$image_path = get_bloginfo('url') . '/wp-content/';  // image Path
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
$root_link = $_GET['root_link'];
if (isset($_REQUEST['page1']))
    $page1 = $_REQUEST['page1'];
else
    $page1 = 1;

//get  pagination limit from database
$pagination_limit = DSP_PAGINATION_LIMIT;
$max_results1 = $pagination_limit;
$adjacents = DSP_PAGINATION_ADJACENTS;
$limit = $max_results1;
$from1 = (($page1 * $max_results1) - $max_results1);

if ($check_couples_mode->setting_status == 'Y') {
    $totalQuery = "SELECT * FROM $DSP_USER_ONLINE_TABLE oln 
 						INNER JOIN $DSP_USER_PROFILES_TABLE usr 
 						ON(usr.user_id=oln.user_id) WHERE oln.status = 'Y' 
 						AND usr.stealth_mode='N'  GROUP BY oln.user_id";
} else {
    $totalQuery = "SELECT * FROM $DSP_USER_ONLINE_TABLE oln 
 						INNER JOIN $DSP_USER_PROFILES_TABLE usr 
 						ON(usr.user_id=oln.user_id) WHERE usr.gender!='C' and oln.status = 'Y' 
 						AND usr.stealth_mode='N'  GROUP BY oln.user_id";
}
//echo $totalQuery;


$total_results1 = $wpdb->get_results($totalQuery);
// Calculate total number of pages. Round up using ceil()
$total_pages1 = count($total_results1);

$onlineMemberQuery = $totalQuery . " LIMIT " . $from1 . ", " . $max_results1;
// echo $onlineMemberQuery;
// ------------------------------------------------End Paging code------------------------------------------------------ //
?>
<table width="100%">

    <?php
    $online_member = $wpdb->get_results($onlineMemberQuery);
    $count_online_mem = count($online_member);
    $i = 0;
    foreach ($online_member as $member) {
        $euser_id = $member->user_id;
        $user_name = $wpdb->get_var("SELECT display_name FROM $DSP_USERS_TABLE WHERE ID=$euser_id ");

        $favt_mem = array();

        $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member->user_id'");

        foreach ($private_mem as $private) {

            $favt_mem[] = $private->favourite_user_id;
        }

        if (($i % 2) == 0) {
            ?>
            <tr>
            <?php } // End if(($i%4)==0) ?>
            <td <?php
            if (($i % 2) == 0) {
                echo 'width="30%"';
            }
            ?> >
                    <?php if ($euser_id != '') { ?>
                    <table cellpadding="0" cellspacing="0" border="0" align="left">
                        <tr>
                            <td align="center">

                                <?php
                                // check for private member
                                if ($check_couples_mode->setting_status == 'Y') {
                                    if ($member->gender == 'C') {
                                        if ($member->make_private == 'Y') {
                                            if ($current_user->ID != $euser_id) {

                                                if (!in_array($current_user->ID, $favt_mem)) {
                                                    ?>
                                                    <a href="<?php
                                                    echo add_query_arg(array('pid' => 3,
                                                        'mem_id' => $euser_id,
                                                        'pagetitle' => "view_profile",
                                                        'view' => "my_profile"), $root_link);
                                                    ?>" >
                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;"  class="dsp_img3" />
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 3, 'mem_id' => $euser_id,
                                                        'pagetitle' => "view_profile",
                                                        'view' => "my_profile"), $root_link);
                                                    ?>" >				
                                                        <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $euser_id,
                                                    'pagetitle' => "view_profile",
                                                    'view' => "my_profile"), $root_link);
                                                ?>" >				
                                                    <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                            <?php } ?>
                                        <?php } else {
                                            ?>                
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $euser_id,
                                                'pagetitle' => "view_profile", 'view' => "my_profile"), $root_link);
                                            ?>" >				
                                                <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>
                                        <?php } ?>


                                    <?php } else { ?>

                                        <?php if ($member->make_private == 'Y') { ?>
                                            <?php if ($current_user->ID != $euser_id) { ?>

                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 3, 'mem_id' => $euser_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >
                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;"  class="dsp_img3" />
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php
                                                    echo add_query_arg(array('pid' => 3,
                                                        'mem_id' => $euser_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >				
                                                        <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $euser_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>" >				
                                                    <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                            <?php } ?>
                                        <?php } else { ?> 
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $euser_id,
                                                'pagetitle' => "view_profile"), $root_link);
                                            ?>">				
                                                <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>
                                        <?php } ?>

                                        <?php
                                    }
                                } else {
                                    ?>

                                    <?php if ($member->make_private == 'Y') { ?>
                                        <?php if ($current_user->ID != $euser_id) { ?>

                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $euser_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>" >
                                                    <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;"  class="dsp_img3" />
                                                </a>                
                                            <?php } else {
                                                ?>
                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $euser_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>" >				
                                                    <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $euser_id,
                                                'pagetitle' => "view_profile"), $root_link);
                                            ?>" >				
                                                <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                        <?php } ?>
                                    <?php } else { ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $euser_id,
                                            'pagetitle' => "view_profile"), $root_link);
                                        ?>">				
                                            <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>
                                        <?php
                                    }
                                }
                                //-------------	end of check for private member---------------------------------------
                                ?>

                                <!--<a href="<?php
                                echo add_query_arg(array('pid' => '3',
                                    'mem_id' => $euser_id, 'pagetitle' => 'view_profile'), $root_link);
                                ?>">
                                <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>-->
                            </td>
                        </tr>
                        <tr>
                            <td class="dsp_name" align="center">
                                <a href="<?php
                                echo add_query_arg(array('pid' => '3', 'mem_id' => $euser_id,
                                    'pagetitle' => 'view_profile'), $root_link);
                                ?>"><?php echo $user_name; ?></a>
                            </td>
                        </tr>
                    </table>
                <?php } ?>
            </td>
            <?php
            if ($count_online_mem == '1') {
                ?>
                <td>&nbsp;</td>		

                <?php
            }
            $i++;
            unset($favt_mem);
        } // End foreach ($new_members as $member)
        ?>
    </tr>    	
</table><br>
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
        $pagination.= "<div><a onclick=\"getOnMemPage('" . $prev . "','" . $root_link . "');\" >Previous</a></div>";
    else
        $pagination.= "<span class=\"disabled\">previous</span>";

    //pages	
    if ($lastpage <= 3 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
        for ($counter = 1; $counter <= $lastpage; $counter++) {
            if ($counter == $page1)
                $pagination.= "<span class=\"current\">$counter</span>";
            else
                $pagination.= "<div><a onclick=\"getOnMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
        }
    }
    elseif ($lastpage > 3 + ($adjacents * 2)) { //enough pages to hide some//5
        //close to beginning; only hide later pages
        if ($page1 <= 1 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= 1 + ($adjacents * 2); $counter++) {
                if ($counter == $page1)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<div><a onclick=\"getOnMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
            }
            $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
            $pagination.="<div><a onclick=\"getOnMemPage('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

            $pagination.="<div><a onclick=\"getOnMemPage('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
        }
        //in middle; hide some front and some back
        elseif ($lastpage - ($adjacents * 2) > $page1 && $page1 > ($adjacents * 2)) {
            $pagination.="<div><a onclick=\"getOnMemPage('1','" . $root_link . "')\">1</a></div>";

            $pagination.= "<div><a onclick=\"getOnMemPage('2','" . $root_link . "')\">2</a></div>";
            $pagination.="<div class='dspmb_pagination_dot'>...</div>";
            for ($counter = $page1 - $adjacents; $counter <= $page1 + $adjacents; $counter++) {
                if ($counter == $page1)
                    $pagination.= "<div class=\"current\">$counter</div>";
                else
                    $pagination.= "<div><a onclick=\"getOnMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
            }
            $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
            $pagination.= "<div><a onclick=\"getOnMemPage('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

            $pagination.= "<div><a onclick=\"getOnMemPage('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
        }
        //close to end; only hide early pages
        else {
            $pagination.= "<div><a onclick=\"getOnMemPage('1','" . $root_link . "')\">1</a></div>";
            $pagination.= "<div><a onclick=\"getOnMemPage('2','" . $root_link . "')\">2</a></div>";
            $pagination.="<div class='dspmb_pagination_dot'>...</div>";
            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                if ($counter == $page1)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<div><a onclick=\"getOnMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
            }
        }
    }

    //next button
    if ($page1 < $lastpage)
        $pagination.="<div><a onclick=\"getOnMemPage('" . $next . "','" . $root_link . "');\" >next</a></div>";
    else
        $pagination.= "<span class=\"disabled\">next</span>";
    $pagination.= "</div>\n";
}
?>
<div class="dspmb_main_paging">
    <?php echo $pagination ?>
</div>
