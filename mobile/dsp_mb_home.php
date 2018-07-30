<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
?>

<script type="text/javascript">
    function getNewMemPage(pageNo, root_link)
    {
        //alert('ih');
        if (pageNo == "")
        {
            document.getElementById("newMem").innerHTML = "";
            //alert(pageNo);
            return;
        }
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function()
        {
            //alert(url);
            //alert('readystate'+xmlhttp.readyState+'status='+ xmlhttp.status);
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                // alert('readystate'+xmlhttp.readyState);
                // document.getElementById("corr").innerHTML='';
                document.getElementById("newMem").innerHTML = xmlhttp.responseText;

            }
        }


        var url = "<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/mobile/dsp_home_newmem.php' ?>";
        url = url + "?page1=" + pageNo + "&root_link=" + root_link;
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
    function getOnMemPage(pageNo, root_link)
    {
        //alert('ih');
        if (pageNo == "")
        {
            document.getElementById("online").innerHTML = "";
            //alert(pageNo);
            return;
        }
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function()
        {
            //alert(url);
            //alert('readystate'+xmlhttp.readyState+'status='+ xmlhttp.status);
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                // alert('readystate'+xmlhttp.readyState);
                // document.getElementById("corr").innerHTML='';
                document.getElementById("online").innerHTML = xmlhttp.responseText;

            }
        }


        var url = "<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/mobile/dsp_home_online.php' ?>";
        url = url + "?page1=" + pageNo + "&root_link=" + root_link;
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
    function getMatchMemPage(pageNo, root_link)
    {
        //alert('ih');
        if (pageNo == "")
        {
            document.getElementById("myMatch").innerHTML = "";
            //  alert(pageNo);
            return;
        }
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function()
        {
            //alert(url);
            //alert('readystate'+xmlhttp.readyState+'status='+ xmlhttp.status);
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                // alert('readystate'+xmlhttp.readyState);
                // document.getElementById("corr").innerHTML='';
                document.getElementById("myMatch").innerHTML = xmlhttp.responseText;

            }
        }


        var url = "<?php echo get_bloginfo('url') . '/wp-content/plugins/dsp_dating/mobile/dsp_home_mymatch.php' ?>";
        url = url + "?page1=" + pageNo + "&root_link=" + root_link;
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
</script>
<?php
$DSP_USER_ONLINE_TABLE = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$DSP_USER_PROFILES_TABLE = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;
//get  pagination limit from database
$pagination_limit = DSP_PAGINATION_LIMIT;
$adjacents = DSP_PAGINATION_ADJACENTS;

// ----------------------------------------------- Start Paging code New Member------------------------------------------------------ // 
if (isset($_GET['page1']))
    $page1 = $_GET['page1'];
else
    $page1 = 1;
$max_results1 = $pagination_limit;
$limit = $max_results1;
$from1 = (($page1 * $max_results1) - $max_results1);

//echo "SELECT * FROM $DSP_USER_PROFILES_TABLE WHERE status_id=1 AND last_update_date > DATE_SUB(now(), INTERVAL 14 DAY) ";
if ($exist_profile_details->gender == "M") {
    $gender_check = "and gender='F' ";
} else
if ($exist_profile_details->gender == "F") {
    $gender_check = "and gender='M' ";
} else
if ($exist_profile_details->gender == "C") {
    $gender_check = "and gender in ('M','F','C') ";
}

if ($check_couples_mode->setting_status == 'Y') {
    $totalQueryNewQuery = "SELECT * FROM $DSP_USER_PROFILES_TABLE WHERE status_id=1  AND country_id!=0 AND last_update_date > DATE_SUB(now(), INTERVAL 14 DAY) $gender_check Order By last_update_date ";
    $totalQueryNew = $wpdb->get_results($totalQueryNewQuery);
} else {
    $totalQueryNewQuery = "SELECT * FROM $DSP_USER_PROFILES_TABLE WHERE gender!='C' and status_id=1  AND country_id!=0 AND last_update_date > DATE_SUB(now(), INTERVAL 14 DAY) $gender_check Order By last_update_date ";
    $totalQueryNew = $wpdb->get_results($totalQueryNewQuery);
}

$total_results1 = $wpdb->get_results($totalQueryNewQuery);
//echo $totalQueryNewQuery;
// Calculate total number of pages. Round up using ceil()
$total_pages1 = count($total_results1);


//$total_pages1 = ceil($total_results1 / $max_results1); 
// ------------------------------------------------End Paging code------------------------------------------------------ //
$current_user = wp_get_current_user();
$user_id = $current_user->ID;  // print session USER_ID
$displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$user_id'");
?>
<div >
    <div class="dsp_mb_sm_header"><?php echo DSP_WELCOMEBACK . ' ' . $displayed_member_name->display_name ?></div> <!--DISPLAY LOGIN USER NAME --><br>
    <table width="100%">
        <tr>

            <td colspan="2"><img src="<?php echo $mb_image_path ?>message.jpg"  border="0"/>&nbsp;<?php echo $count_inbox_messages ?>&nbsp;<a style="color: black" href="<?php
                echo add_query_arg(array(
                    'pid' => 14, 'pagetitle' => 'my_email', 'message_template' => 'inbox'), $root_link);
                ?>"><?php echo DSP_UNREADEMAIL_IN_YOUR_INBOX ?></a> </td>
        </tr>
        <tr>
            <td>&nbsp;

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="dsp_mb_sm_header"><?php echo DSP_NEWEST_MEMBERS ?></div>
            </td>
        </tr>
        <tr>
            <td>&nbsp;

            </td>
        </tr>
        <tr>
            <td colspan="2" align="left">
                <div id="newMem">
                    <table width="100%">
                        <?php
                        $totalQueryNew = "SELECT * FROM $DSP_USER_PROFILES_TABLE WHERE status_id=1 AND last_update_date > DATE_SUB(now(), INTERVAL 14 DAY)  
				ORDER BY last_update_date DESC";
//	echo $totalQueryNew;
                        $getNewMemLimitQuery = $totalQueryNewQuery . " LIMIT " . $from1 . ", " . $max_results1;

//echo '<br>limit'.$getNewMemLimitQuery;

                        $new_members = $wpdb->get_results($getNewMemLimitQuery);

                        $count_new_mem = count($new_members);

                        $i = 0;

                        foreach ($new_members as $member) {
                            $exist_user_name = $wpdb->get_row("SELECT * FROM $DSP_USERS_TABLE WHERE ID='$member->user_id'");
                            $user_name = $exist_user_name->display_name;
                            $new_member_id = $member->user_id;

                            $favt_mem = array();

                            $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member->user_id'");

                            foreach ($private_mem as $private) {

                                $favt_mem[] = $private->favourite_user_id;
                            }
                            if (($i % 2) == 0) {
                                ?>
                                <tr>
                                <?php } // End if(($i%4)==0)    ?>
                                <td <?php
                                if (($i % 2) == 0) {
                                    echo 'width="30%"';
                                }
                                ?>  align="left"  >
                                    <table cellpadding="0" cellspacing="0" border="0" align="left">
                                        <tr>

                                            <td align="center">

                                                <?php
                                                if ($check_couples_mode->setting_status == 'Y') {
                                                    if ($member->gender == 'C') {
                                                        if ($member->make_private == 'Y') {

                                                            if ($current_user->ID != $new_member_id) {

                                                                if (!in_array($current_user->ID, $favt_mem)) {
                                                                    ?>

                                                                    <a href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 3,
                                                                        'mem_id' => $new_member_id,
                                                                        'pagetitle' => "view_profile",
                                                                        'view' => "my_profile"), $root_link);
                                                                    ?>" >

                                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;" border="0" class="dsp_img3" />

                                                                    </a>                

                                                                <?php } else {
                                                                    ?>

                                                                    <a href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 3,
                                                                        'mem_id' => $new_member_id,
                                                                        'pagetitle' => "view_profile",
                                                                        'view' => "my_profile"), $root_link);
                                                                    ?>" >				

                                                                        <img src="<?php echo display_members_photo($new_member_id, $image_path); ?>"    class="dsp_img3" style="width:55px; height:55px;"/></a>                

                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>

                                                                <a href="<?php
                                                                echo add_query_arg(array(
                                                                    'pid' => 3, 'mem_id' => $new_member_id,
                                                                    'pagetitle' => "view_profile",
                                                                    'view' => "my_profile"), $root_link);
                                                                ?>" >				

                                                                    <img src="<?php echo display_members_photo($new_member_id, $image_path); ?>"    class="dsp_img3" style="width:55px; height:55px;"/></a>                

                                                                <?php
                                                            }
                                                        } else {
                                                            ?>                

                                                            <a href="<?php
                                                            echo add_query_arg(array(
                                                                'pid' => 3, 'mem_id' => $new_member_id,
                                                                'pagetitle' => "view_profile",
                                                                'view' => "my_profile"), $root_link);
                                                            ?>" >				

                                                                <img src="<?php echo display_members_photo($new_member_id, $image_path); ?>"    class="dsp_img3" style="width:55px; height:55px;"/></a>

                                                        <?php } ?>

                                                        <?php
                                                    } else {
                                                        if ($member->make_private == 'Y') {

                                                            if ($current_user->ID != $new_member_id) {
                                                                ?>



                                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>

                                                                    <a href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 3,
                                                                        'mem_id' => $new_member_id,
                                                                        'pagetitle' => "view_profile"), $root_link);
                                                                    ?>" >

                                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;" border="0" class="dsp_img3" />

                                                                    </a>                

                                                                <?php } else {
                                                                    ?>

                                                                    <a href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 3,
                                                                        'mem_id' => $new_member_id,
                                                                        'pagetitle' => "view_profile"), $root_link);
                                                                    ?>" >				

                                                                        <img src="<?php echo display_members_photo($new_member_id, $image_path); ?>"    class="dsp_img3" style="width:55px; height:55px;"/></a>                

                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>

                                                                <a href="<?php
                                                                echo add_query_arg(array(
                                                                    'pid' => 3,
                                                                    'mem_id' => $new_member_id,
                                                                    'pagetitle' => "view_profile"), $root_link);
                                                                ?>" >				

                                                                    <img src="<?php echo display_members_photo($new_member_id, $image_path); ?>"    class="dsp_img3" style="width:55px; height:55px;"/></a>                

                                                                <?php
                                                            }
                                                        } else {
                                                            ?> 

                                                            <a href="<?php
                                                            echo add_query_arg(array(
                                                                'pid' => 3, 'mem_id' => $new_member_id,
                                                                'pagetitle' => "view_profile"), $root_link);
                                                            ?>">				

                                                                <img src="<?php echo display_members_photo($new_member_id, $image_path); ?>"   class="dsp_img3" style="width:55px; height:55px;"/></a>

                                                        <?php } ?>

                                                        <?php
                                                    }
                                                } else {
                                                    if ($member->make_private == 'Y') {
                                                        ?>

                                                        <?php if ($current_user->ID != $new_member_id) { ?>



                                                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>

                                                                <a href="<?php
                                                                echo add_query_arg(array(
                                                                    'pid' => 3,
                                                                    'mem_id' => $new_member_id,
                                                                    'pagetitle' => "view_profile"), $root_link);
                                                                ?>" >

                                                                    <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;" border="0" class="dsp_img3" />

                                                                </a>                

                                                            <?php } else {
                                                                ?>

                                                                <a href="<?php
                                                                echo add_query_arg(array(
                                                                    'pid' => 3, 'mem_id' => $new_member_id,
                                                                    'pagetitle' => "view_profile"), $root_link);
                                                                ?>" >				

                                                                    <img src="<?php echo display_members_photo($new_member_id, $image_path); ?>"    class="dsp_img3" style="width:55px; height:55px;"/></a>                

                                                                <?php
                                                            }
                                                        } else {
                                                            ?>

                                                            <a href="<?php
                                                            echo add_query_arg(array(
                                                                'pid' => 3, 'mem_id' => $new_member_id,
                                                                'pagetitle' => "view_profile"), $root_link);
                                                            ?>" >				

                                                                <img src="<?php echo display_members_photo($new_member_id, $image_path); ?>"    class="dsp_img3" style="width:55px; height:55px;"/></a>                

                                                        <?php } ?>



                                                        <?php
                                                    } else {
                                                        ?>

                                                        <a href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 3, 'mem_id' => $new_member_id,
                                                            'pagetitle' => "view_profile"), $root_link);
                                                        ?>">				

                                                            <img src="<?php echo display_members_photo($new_member_id, $image_path); ?>"   class="dsp_img3" style="width:55px; height:55px;"/></a>

                                                    <?php } ?>


                                                <?php } ?>

                                                <!--<a href="<?php
                                                echo add_query_arg(array(
                                                    'pid' => 3,
                                                    'mem_id' => $new_member_id, 'pagetitle' => 'view_profile'), $root_link);
                                                ?>">
                
                                                <img src="<?php echo display_members_photo_mb($new_member_id, $image_path); ?>"  width="55px" height="55px" class="dsp_img3"/></a>-->

                                            </td>

                                        </tr>

                                        <tr>

                                            <td class="dsp_name" align="center">

                                                <a href="<?php
                                                echo add_query_arg(array(
                                                    'pid' => 3, 'mem_id' => $new_member_id,
                                                    'pagetitle' => 'view_profile'), $root_link);
                                                ?>"><?php echo $user_name; ?></a>

                                            </td>

                                        </tr>
                                    </table>
                                </td>
                                <?php
                                if ($count_new_mem == '1') {
                                    ?>
                                    <td >&nbsp;</td>		
                                    <?php
                                }
                                $i++;
                                unset($favt_mem);
                            }
                            ?></tr>

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
                            $pagination.= "<div><a onclick=\"getNewMemPage('" . $prev . "','" . $root_link . "');\" >Previous</a></div>";
                        else
                            $pagination.= "<span class=\"disabled\">previous</span>";

                        //pages	
                        if ($lastpage <= 3 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
                            for ($counter = 1; $counter <= $lastpage; $counter++) {
                                if ($counter == $page1)
                                    $pagination.= "<span class=\"current\">$counter</span>";
                                else
                                    $pagination.= "<div><a onclick=\"getNewMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                            }
                        }
                        elseif ($lastpage > 3 + ($adjacents * 2)) { //enough pages to hide some//5
                            //close to beginning; only hide later pages
                            if ($page1 <= 1 + ($adjacents * 2)) {
                                for ($counter = 1; $counter <= 1 + ($adjacents * 2); $counter++) {
                                    if ($counter == $page1)
                                        $pagination.= "<span class=\"current\">$counter</span>";
                                    else
                                        $pagination.= "<div><a onclick=\"getNewMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                                }
                                $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                                $pagination.="<div><a onclick=\"getNewMemPage('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

                                $pagination.="<div><a onclick=\"getNewMemPage('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
                            }
                            //in middle; hide some front and some back
                            elseif ($lastpage - ($adjacents * 2) > $page1 && $page1 > ($adjacents * 2)) {
                                $pagination.="<div><a onclick=\"getNewMemPage('1','" . $root_link . "')\">1</a></div>";

                                $pagination.= "<div><a onclick=\"getNewMemPage('2','" . $root_link . "')\">2</a></div>";
                                $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                                for ($counter = $page1 - $adjacents; $counter <= $page1 + $adjacents; $counter++) {
                                    if ($counter == $page1)
                                        $pagination.= "<div class=\"current\">$counter</div>";
                                    else
                                        $pagination.= "<div><a onclick=\"getNewMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                                }
                                $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
                                $pagination.= "<div><a onclick=\"getNewMemPage('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

                                $pagination.= "<div><a onclick=\"getNewMemPage('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
                            }
                            //close to end; only hide early pages
                            else {
                                $pagination.= "<div><a onclick=\"getNewMemPage('1','" . $root_link . "')\">1</a></div>";
                                $pagination.= "<div><a onclick=\"getNewMemPage('2','" . $root_link . "')\">2</a></div>";
                                $pagination.="<div class='dspmb_pagination_dot'>...</div>";
                                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                                    if ($counter == $page1)
                                        $pagination.= "<span class=\"current\">$counter</span>";
                                    else
                                        $pagination.= "<div><a onclick=\"getNewMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
                                }
                            }
                        }

                        //next button
                        if ($page1 < $lastpage)
                            $pagination.="<div><a onclick=\"getNewMemPage('" . $next . "','" . $root_link . "');\" >next</a></div>";
                        else
                            $pagination.= "<span class=\"disabled\">next</span>";
                        $pagination.= "</div>\n";
                    }
                    ?>
                    <div class="dspmb_main_paging">
                        <?php echo $pagination ?>
                    </div>
                </div>

            </td>
        </tr>
        <tr>
            <td>&nbsp;

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="dsp_mb_sm_header"><?php echo DSP_ONLINE_MEMBERS ?></div>
            </td>
        </tr>
        <tr>
            <td>&nbsp;

            </td>
        </tr>
        <tr>
            <td colspan="2" align="left">
                <div id="online">
                    <table width="100%">
                        <?php
                        // ----------------------------------------------- Start Paging code on line Member------------------------------------------------------ // 
                        if (isset($_GET['page1']))
                            $page1 = $_GET['page1'];
                        else
                            $page1 = 1;

                        $max_results1 = $pagination_limit;
                        $limit = $max_results1;
                        $from1 = (($page1 * $max_results1) - $max_results1);


                        if ($check_couples_mode->setting_status == 'Y') {
                            $totalQuery = "SELECT * FROM $DSP_USER_ONLINE_TABLE oln 
 						INNER JOIN $DSP_USER_PROFILES_TABLE usr 
 						ON(usr.user_id=oln.user_id) WHERE oln.status = 'Y' 
 						AND usr.stealth_mode='N'  GROUP BY oln.user_id ";
                        } else {
                            $totalQuery = "SELECT * FROM $DSP_USER_ONLINE_TABLE oln 
 						INNER JOIN $DSP_USER_PROFILES_TABLE usr 
 						ON(usr.user_id=oln.user_id) WHERE usr.gender!='C' and oln.status = 'Y' 
 						AND usr.stealth_mode='N'  GROUP BY oln.user_id ";
                        }


                        $total_results1 = $wpdb->get_results($totalQuery);
                        // Calculate total number of pages. Round up using ceil()
                        $total_pages1 = count($total_results1);
                        // ------------------------------------------------End Paging code------------------------------------------------------ //
                        $onlineMemberQuery = $totalQuery . " LIMIT " . $from1 . ", " . $max_results1;
                        //echo $onlineMemberQuery;
                        $online_member = $wpdb->get_results($onlineMemberQuery);
                        $count_online_mem = count($online_member);
                        $i = 0;
                        //echo $pluginpath;
                        foreach ($online_member as $member) {
                            $euser_id = $member->user_id;
                            $user_name = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID=$euser_id ");

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
                                                                        echo add_query_arg(array(
                                                                            'pid' => 3,
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
                                                                            'pid' => 3,
                                                                            'mem_id' => $euser_id,
                                                                            'pagetitle' => "view_profile",
                                                                            'view' => "my_profile"), $root_link);
                                                                        ?>" >				
                                                                            <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <a href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 3,
                                                                        'mem_id' => $euser_id,
                                                                        'pagetitle' => "view_profile",
                                                                        'view' => "my_profile"), $root_link);
                                                                    ?>" >				
                                                                        <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                                <?php } ?>
                                                            <?php } else {
                                                                ?>                
                                                                <a href="<?php
                                                                echo add_query_arg(array(
                                                                    'pid' => 3, 'mem_id' => $euser_id,
                                                                    'pagetitle' => "view_profile",
                                                                    'view' => "my_profile"), $root_link);
                                                                ?>" >				
                                                                    <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>
                                                            <?php } ?>


                                                        <?php } else { ?>

                                                            <?php if ($member->make_private == 'Y') { ?>
                                                                <?php if ($current_user->ID != $euser_id) { ?>

                                                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                                        <a href="<?php
                                                                        echo add_query_arg(array(
                                                                            'pid' => 3,
                                                                            'mem_id' => $euser_id,
                                                                            'pagetitle' => "view_profile"), $root_link);
                                                                        ?>" >
                                                                            <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;"  class="dsp_img3" />
                                                                        </a>                
                                                                    <?php } else {
                                                                        ?>
                                                                        <a href="<?php
                                                                        echo add_query_arg(array(
                                                                            'pid' => 3,
                                                                            'mem_id' => $euser_id,
                                                                            'pagetitle' => "view_profile"), $root_link);
                                                                        ?>" >				
                                                                            <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <a href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 3,
                                                                        'mem_id' => $euser_id,
                                                                        'pagetitle' => "view_profile"), $root_link);
                                                                    ?>" >				
                                                                        <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                                <?php } ?>
                                                            <?php } else { ?> 
                                                                <a href="<?php
                                                                echo add_query_arg(array(
                                                                    'pid' => 3, 'mem_id' => $euser_id,
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
                                                                    echo add_query_arg(array(
                                                                        'pid' => 3,
                                                                        'mem_id' => $euser_id,
                                                                        'pagetitle' => "view_profile"), $root_link);
                                                                    ?>" >
                                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;"  class="dsp_img3" />
                                                                    </a>                
                                                                <?php } else {
                                                                    ?>
                                                                    <a href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 3,
                                                                        'mem_id' => $euser_id,
                                                                        'pagetitle' => "view_profile"), $root_link);
                                                                    ?>" >				
                                                                        <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <a href="<?php
                                                                echo add_query_arg(array(
                                                                    'pid' => 3, 'mem_id' => $euser_id,
                                                                    'pagetitle' => "view_profile"), $root_link);
                                                                ?>" >				
                                                                    <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <a href="<?php
                                                            echo add_query_arg(array(
                                                                'pid' => 3, 'mem_id' => $euser_id,
                                                                'pagetitle' => "view_profile"), $root_link);
                                                            ?>">				
                                                                <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>
                                                        <?php } ?>

                                                    <?php } ?>
                                                    <!--<a href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => '3',
                                                        'mem_id' => $euser_id, 'pagetitle' => 'view_profile'), $root_link);
                                                    ?>">
                                                    <img src="<?php echo display_members_photo_mb($euser_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>-->
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dsp_name" align="center">
                                                    <a href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => '3', 'mem_id' => $euser_id,
                                                        'pagetitle' => "view_profile"), $root_link);
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

                </div>
            </td>
        </tr>
        <tr>
            <td>&nbsp;

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="dsp_mb_sm_header"><?php echo DSP_MY_MATCHES ?></div>
            </td>
        </tr>
        <tr>
            <td>&nbsp;

            </td>
        </tr>
        <tr>
            <td colspan="2" align="left">
                <div id="myMatch">
                    <table width="100%">
                        <!--------------------------------- MY MATCHES----------------------------------------------------------->
                        <?php
                        //	$my_matches=$_GET['message_template'];
                        $active_question_id = $wpdb->get_results("SELECT profile_setup_id FROM $dsp_profile_setup_table WHERE display_status='Y'");

                        foreach ($active_question_id as $question_id) {
                            $active_question_ids[] = $question_id->profile_setup_id;
                        }
                        if ($active_question_ids != "") {
                            $active_question_ids1 = implode(",", $active_question_ids);
                        }
                        //echo "SELECT * FROM $dsp_question_details WHERE profile_question_id IN ($active_question_ids1) and user_id='$user_id'";
                        $matches_option = $wpdb->get_results("SELECT * FROM $dsp_question_details WHERE profile_question_id IN ($active_question_ids1) and user_id='$user_id'");

                        $matches_option_id1 = array();
                        foreach ($matches_option as $match_opt_id) {
                            $matches_option_id1[] = $match_opt_id->profile_question_option_id;
                        }

                        if ($matches_option_id1 != "") {
                            $matches_option_id = implode(",", $matches_option_id1);
                        }


                        //echo "SELECT * FROM $dsp_user_profiles WHERE user_id='$user_id'";
                        $member_gender = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$user_id'");
                        if (count($member_gender) > 0) {
                            $member_gender->gender;

                            if ($member_gender->gender == 'M')
                                $match_query = " and gender='F' ";
                            else if ($member_gender->gender == 'F')
                                $match_query = " and gender='M' ";
                            else if ($member_gender->gender == 'C')
                                $match_query = " and gender='C' ";


                            $count_my_mathces_query = "SELECT COUNT(*) FROM $dsp_question_details A INNER JOIN $dsp_user_profiles B ON(A.user_id=B.user_id) WHERE profile_question_option_id IN ($matches_option_id) and A.user_id<>$user_id $match_query";


                            $count_my_matches = $wpdb->get_var($count_my_mathces_query);
                            //echo $count_my_mathces_query;
                            if ($count_my_matches > 0) {
                                // ----------------------------------------------- Start Paging code New Member------------------------------------------------------ // 
                                if (isset($_GET['page1']))
                                    $page1 = $_GET['page1'];
                                else
                                    $page1 = 1;


                                $max_results1 = $pagination_limit;
                                $adjacents = DSP_PAGINATION_ADJACENTS;
                                $limit = $max_results1;


                                $from1 = (($page1 * $max_results1) - $max_results1);

                                $totalQuery = "SELECT DISTINCT(A.user_id),B.make_private,B.gender FROM $dsp_question_details A INNER JOIN $dsp_user_profiles B ON(A.user_id=B.user_id) WHERE profile_question_option_id IN ($matches_option_id) and A.user_id<>$user_id $match_query";


                                $total_results1 = $wpdb->get_results($totalQuery);
                                //echo $totalQuery;
                                // Calculate total number of pages. Round up using ceil()
                                $total_pages1 = count($total_results1);
                                // ------------------------------------------------End Paging code------------------------------------------------------ //
                                $getMatchMemLimitQuery = $totalQuery . " LIMIT " . $from1 . ", " . $max_results1;

                                $matches_members_details = $wpdb->get_results($getMatchMemLimitQuery);
                                $count_my_match = count($matches_members_details);
                                $i = 0;

                                foreach ($matches_members_details as $my_matches) {
                                    $match_user_id = $my_matches->user_id;
                                    $match_user_name = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID=$match_user_id ");
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
                                        ?> >
                                            <table cellpadding="0" cellspacing="0" border="0" ali>
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
                                                                            echo add_query_arg(array(
                                                                                'pid' => 3,
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
                                                                                'pid' => 3,
                                                                                'mem_id' => $match_user_id,
                                                                                'pagetitle' => "view_profile",
                                                                                'view' => "my_profile"), $root_link);
                                                                            ?>" >				
                                                                                <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                                            <?php
                                                                        }
                                                                    } else {
                                                                        ?>
                                                                        <a href="<?php
                                                                        echo add_query_arg(array(
                                                                            'pid' => 3,
                                                                            'mem_id' => $match_user_id,
                                                                            'pagetitle' => "view_profile",
                                                                            'view' => "my_profile"), $root_link);
                                                                        ?>" >				
                                                                            <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                                    <?php } ?>
                                                                <?php } else {
                                                                    ?>                
                                                                    <a href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 3,
                                                                        'mem_id' => $match_user_id,
                                                                        'pagetitle' => "view_profile",
                                                                        'view' => "my_profile"), $root_link);
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
                                                                                'pid' => 3,
                                                                                'mem_id' => $match_user_id,
                                                                                'pagetitle' => "view_profile"), $root_link);
                                                                            ?>" >
                                                                                <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;"  class="dsp_img3" />
                                                                            </a>                
                                                                        <?php } else {
                                                                            ?>
                                                                            <a href="<?php
                                                                            echo add_query_arg(array(
                                                                                'pid' => 3,
                                                                                'mem_id' => $match_user_id,
                                                                                'pagetitle' => "view_profile"), $root_link);
                                                                            ?>" >				
                                                                                <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                                            <?php
                                                                        }
                                                                    } else {
                                                                        ?>
                                                                        <a href="<?php
                                                                        echo add_query_arg(array(
                                                                            'pid' => 3,
                                                                            'mem_id' => $match_user_id,
                                                                            'pagetitle' => "view_profile"), $root_link);
                                                                        ?>" >				
                                                                            <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                                    <?php } ?>
                                                                <?php } else { ?> 
                                                                    <a href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 3,
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
                                                                        echo add_query_arg(array(
                                                                            'pid' => 3,
                                                                            'mem_id' => $match_user_id,
                                                                            'pagetitle' => "view_profile"), $root_link);
                                                                        ?>" >
                                                                            <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;"  class="dsp_img3" />
                                                                        </a>                
                                                                    <?php } else {
                                                                        ?>
                                                                        <a href="<?php
                                                                        echo add_query_arg(array(
                                                                            'pid' => 3,
                                                                            'mem_id' => $match_user_id,
                                                                            'pagetitle' => "view_profile"), $root_link);
                                                                        ?>" >				
                                                                            <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <a href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 3,
                                                                        'mem_id' => $match_user_id,
                                                                        'pagetitle' => "view_profile"), $root_link);
                                                                    ?>" >				
                                                                        <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>                
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <a href="<?php
                                                                echo add_query_arg(array(
                                                                    'pid' => 3, 'mem_id' => $match_user_id,
                                                                    'pagetitle' => "view_profile"), $root_link);
                                                                ?>">				
                                                                    <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>"   width="55px" height="55px" class="dsp_img3"/></a>
                                                                <?php
                                                            }
                                                        }
                                                        //-------------	end of check for private member---------------------------------------
                                                        ?>
                                                        <!-- <a href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 3, 'mem_id' => $match_user_id,
                                                            'pagetitle' => 'view_profile'), $root_link);
                                                        ?>">
                                                         <img src="<?php echo display_members_photo_mb($match_user_id, $image_path); ?>" width="55px" height="55px" class="dsp_img3" /></a>-->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center">
                                                        <a href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => '3', 'mem_id' => $match_user_id,
                                                            'pagetitle' => "view_profile"), $root_link);
                                                        ?>"><?php echo $match_user_name; ?></a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <?php
                                        if ($count_my_match == '1') {
                                            ?>
                                            <td>&nbsp;</td>		

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

                                <?php
                            }
                        } // end of if no profile exist
                        else {
                            ?><table width="45%">
                                <tr><td colspan="2" ><strong><?php echo language_code('DSP_NO_MATCHES_FOUND_MSG') ?></strong></td></tr>
                            <?php } ?>
                        </table>
                </div>

            </td>
        </tr>

    </table>

    <div>
    </div>

</div>