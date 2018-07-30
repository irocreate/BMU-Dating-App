<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ----------------------------------------------- Start Paging code------------------------------------------------------ //  
if (isset($_GET['page1']))
    $page = $_GET['page1'];
else
    $page = 1;

// How many adjacent pages should be shown on each side?
$adjacents = 2;
$limit = 5;
if ($page)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
$gender = $_REQUEST['gender'];
$age_from = $_REQUEST['age_from'];
$age_to = $_REQUEST['age_to'];
$zipcode = $_REQUEST['zip_code'];
$miles = $_REQUEST['miles'];
$bolIfSearchCriteria = true;
$dsp_zipcode_table = $wpdb->prefix . dsp_zipcodes;
$findzipcodelatlng = $wpdb->get_row("SELECT * FROM $dsp_zipcode_table WHERE zipcode = '$zipcode'");
$lat1 = $findzipcodelatlng->latitude;
$lon1 = $findzipcodelatlng->longitude;
$d = $miles;
$r = 3959;
//compute max and min latitudes / longitudes for search square
$latN = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(0))));
$latS = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(180))));
$lonE = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(90)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
$lonW = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(270)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
$findzipcodes = "SELECT zipcode FROM $dsp_zipcode_table WHERE (latitude <= $latN AND latitude >= $latS AND longitude <= $lonE AND longitude >= $lonW) AND (latitude != $lat1 AND longitude != $lon1) ";
$findallzipcodes = $wpdb->get_results($findzipcodes);
foreach ($findallzipcodes as $allzipcodes) {
    $searchzipcodes[] = $allzipcodes->zipcode;
}
if ($searchzipcodes != "") {
    $searchzipcodes1 = implode(",", $searchzipcodes);
}
$strQuery = "SELECT DISTINCT (fb.user_id) FROM $dsp_user_profiles fb WHERE stealth_mode='N' AND zipcode IN($searchzipcodes1) ";
if ($age_from >= 18) {
    $strQuery .= " and ((year(CURDATE())-year(fb.age)) > '" . $age_from . "') AND ((year(CURDATE())-year(fb.age)) < '" . $age_to . "') AND ";
}

if ($gender == 'M') {
    $strQuery .= " fb.gender='M' ";
} else if ($gender == 'F') {
    $strQuery .= " fb.gender='F' ";
} else if ($gender == 'C') {
    $strQuery .= " fb.gender='C' ";
} else if ($gender == 'all') {
    $strQuery .= " fb.gender IN('M','F','C') ";
}
$intRecordsPerPage = 10;
$intStartLimit = $_GET['p']; # page selected 1,2,3,4...
if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
    $intStartLimit = 1; //default
}
$intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;
if ($bolIfSearchCriteria) {
    if ($check_couples_mode->setting_status == 'Y') {
        $strQuery = $strQuery . "  AND fb.status_id=1 ORDER BY fb.user_profile_id desc";
    } else {

        $strQuery = $strQuery . "  AND fb.status_id=1 AND gender!='C' ORDER BY fb.user_profile_id desc";
    }
    $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
}
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
$page_name = $root_link . "?pid=5&pagetitle=zipcode_search_result&zipcode_search=zipcode_search&zip_code=" . $zipcode . "&miles=" . $miles;
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
            $pagination.= "<div><a href=\"" . $page_name . "&page1=1\">1</a>";
            $pagination.= "<div><a href=\"" . $page_name . "&page1=2\">2</a>";
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

// ------------------------------------------------End Paging code------------------------------------------------------ // 
$intTotalRecordsEffected = $user_count;
if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
    //print "Total records found: " . $intTotalRecordsEffected;
} else {
    ?>
    <div class="dsp_search_result_box_out">
        <div class="dsp_search_result_box_in">
            <div class="box-page">
                <div class="page-not-found">
                    <?php echo language_code('DSP_NO_RECORD_FOUND'); ?><br /><br /><?php echo $if_record_not_found ?>
                    <span><a href="<?php echo $root_link ?>?pid=5&pagetitle=zipcode_search"><?php echo language_code('DSP_START_NEW_SEARCH'); ?></a></span>
                </div>
            </div>
        </div>
    </div>
    <?php
} // if ($intTotalRecordsEffected != '0')	
$search_members = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");
foreach ($search_members as $member1) {
    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'");
    $s_user_id = $member->user_id;
    $s_country_id = $member->country_id;
    $s_gender = $member->gender;
    $s_seeking = $member->seeking;
    $s_state_id = $member->state_id;
    $s_city_id = $member->city_id;
    $s_city = $member->city;
    $s_age = GetAge($member->age);
    $s_make_private = $member->make_private;
//$s_user_pic = $member->user_pic;
    $displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$s_user_id'");
    $country_name = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$s_country_id");
    $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$s_state_id");
    $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$s_city_id");
    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");
    foreach ($private_mem as $private) {
        $favt_mem[] = $private->favourite_user_id;
    }
    ?>
    <div class="dsp_search_result_box_out">
        <div class="dsp_search_result_box_in">
            <div class="box-page">
                <ul class="search-result-page">
                    <li class="img-box">
                        <?php
                        if ($check_couples_mode->setting_status == 'Y') {
                            if ($s_gender == 'C') {
                                ?>

                                <?php if ($s_make_private == 'Y') { ?>

                                    <?php if ($current_user->ID != $s_user_id) { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $s_user_id,
                                                'pagetitle' => view_profile, 'view' => my_profile), $root_link);
                                            ?>" >
                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  style="width:100px; height:100px;" border="3" class="img" alt="Private Photo" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $s_user_id,
                                                'pagetitle' => view_profile, 'view' => my_profile), $root_link);
                                            ?>" >				
                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  alt="<?php echo get_username($s_user_id); ?>"  style="width:100px; height:100px;" border="3" class="img"/></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                            'pagetitle' => view_profile, 'view' => my_profile), $root_link);
                                        ?>">
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  alt="<?php echo get_username($s_user_id); ?>"  style="width:100px; height:100px;" border="3" class="img" />
                                        </a>
                                    <?php } ?>

                                <?php } else { ?>

                                    <a href="<?php
                                    echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                        'pagetitle' => view_profile, 'view' => my_profile), $root_link);
                                    ?>">
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" alt="<?php echo get_username($s_user_id); ?>"  style="width:100px; height:100px;" border="3" class="img" />
                                    </a>
                                <?php } ?>

                            <?php } else { ?>

                                <?php if ($s_make_private == 'Y') { ?>

                                    <?php if ($current_user->ID != $s_user_id) { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $s_user_id,
                                                'pagetitle' => view_profile, 'view' => my_profile), $root_link);
                                            ?>" >
                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  style="width:100px; height:100px;" border="3" class="img" alt="Private Photo" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $s_user_id,
                                                'pagetitle' => view_profile, 'view' => my_profile), $root_link);
                                            ?>" >				
                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  alt="<?php echo get_username($s_user_id); ?>"     style="width:100px; height:100px;" border="3" class="img"/></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                            'pagetitle' => view_profile), $root_link);
                                        ?>">
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" alt="<?php echo get_username($s_user_id); ?>"  style="width:100px; height:100px;" border="3" class="img" />
                                        </a>
                                    <?php } ?>
                                <?php } else { ?>

                                    <a href="<?php
                                    echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                        'pagetitle' => view_profile), $root_link);
                                    ?>">
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" alt="<?php echo get_username($s_user_id); ?>"  style="width:100px; height:100px;" border="3" class="img" />
                                    </a>
                                <?php } ?>
                                <?php
                            }
                        } else {
                            ?> 

                            <?php if ($s_make_private == 'Y') { ?>
                                <?php if ($current_user->ID != $s_user_id) { ?>

                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                            'pagetitle' => view_profile, 'view' => my_profile), $root_link);
                                        ?>" >
                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  style="width:100px; height:100px;" border="3" class="img" alt="Private Photo" />
                                        </a>                
                                    <?php } else {
                                        ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                            'pagetitle' => view_profile, 'view' => my_profile), $root_link);
                                        ?>" >				
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  alt="<?php echo get_username($s_user_id); ?>"    style="width:100px; height:100px;" border="3" class="img"/></a>                
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a href="<?php
                                    echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                        'pagetitle' => view_profile), $root_link);
                                    ?>">
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" alt="<?php echo get_username($s_user_id); ?>"  style="width:100px; height:100px;" border="3" class="img" />
                                    </a>
                                <?php } ?>

                            <?php } else { ?>

                                <a href="<?php
                                echo add_query_arg(array('pid' => 3, 'mem_id' => $s_user_id,
                                    'pagetitle' => view_profile), $root_link);
                                ?>">
                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" alt="<?php echo get_username($s_user_id); ?>"  style="width:100px; height:100px;" border="3" class="img" />
                                </a>
                            <?php } ?>

                        <?php } ?>
                        <div class="dsp_page_link user-name-div"><strong>

                                <?php
                                if ($check_couples_mode->setting_status == 'Y') {
                                    if ($s_gender == 'C') {
                                        ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3,
                                            'mem_id' => $s_user_id, 'pagetitle' => view_profile,
                                            'view' => my_profile), $root_link);
                                        ?>">
                                               <?php echo $displayed_member_name->display_name ?>                
                                           <?php } else { ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $s_user_id, 'pagetitle' => view_profile), $root_link);
                                            ?>">
                                                   <?php echo $displayed_member_name->display_name ?>
                                                   <?php
                                               }
                                           } else {
                                               ?> 
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $s_user_id, 'pagetitle' => view_profile), $root_link);
                                            ?>">
                                                   <?php echo $displayed_member_name->display_name ?>
                                               <?php } ?>
                                        </a>
                                    </a></strong></div>
                    </li>
                    <li class="user-detail-box">
                        <ul>
                            <li><span><?php echo language_code('DSP_AGE'); ?></span> <?php echo $s_age ?></li>
                            <li><span><?php echo language_code('DSP_GENDER'); ?></span> <?php echo $s_gender; ?></li>
                            <li><span><?php echo language_code('DSP_SEEKING'); ?></span> <?php echo $s_seeking; ?></li>
                            <li><span><?php echo language_code('DSP_USER_LOCATION'); ?></span><?php echo $city_name->name; ?>, <?php echo $state_name->name; ?>, <?php echo $country_name->name; ?></li>

                            <?php
                            $dsp_user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
                            $online_user = $wpdb->get_var("select count(*) from $dsp_user_online_table WHERE user_id = '$s_user_id'");
                            if ($online_user != 0) {
                                ?>
                                <li>
                                    <div class="online-stuff">
                                        <div class="show-online">
                                            <img src="<?php echo $fav_icon_image_path ?>user-online.gif" border="0" style="width:32px;" />
                                        </div>
                                        <ul>

                                            <?php if ($check_userplane_instant_messenger_mode->setting_status == 'Y') { // Check Skype mode Activated or not      ?> 
                                                <?php
                                                $check_online_user = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $tbl_name WHERE user_id=$s_user_id"));
                                                if ($check_online_user > 0) {
                                                    ?>
                                                    <li><?php include_once(WP_DSP_ABSPATH . "general_settings.php"); ?>
                                                        <script type="text/javascript">
                                                            function Onlypremiumaccess()
                                                            {
                                                                alert('Only premium member can access this feature, Please upgrade your account');
                                                            }
                                                            function expired()
                                                            {
                                                                alert('Your Premimum Account has been expired, Please upgrade your account');
                                                            }
                                                            function RecipientOnlypremiumaccess()
                                                            {
                                                                alert('Recipient member is not a premium member');
                                                            }
                                                            function Recipientexpired()
                                                            {
                                                                alert('Recipient Premimum Account has been expired');
                                                            }
                                                        </script>

                                                        <?php if ($check_free_mode->setting_status == "Y" &&  $_SESSION['free_member']) { ?>
                                                            <a onClick="up.api.startInstantMessage('<?php echo $s_user_id ?>');"><img src="<?php echo $fav_icon_image_path ?>3.jpeg" border="0" style="width:16px;" alt="3"/></a><a onClick="up.api.startInstantMessage('<?php echo $s_user_id ?>');" >Send IM Request</a>

                                                            <?php
                                                        } else if ($check_free_mode->setting_status == "N") {  // free mode is off 
                                                            $access_feature_name = "Instant Messenger";
                                                            $check_membership_msg = check_membership($access_feature_name, $user_id);
                                                            if ($check_membership_msg == "Expired") {
                                                                ?>

                                                                <a onClick="javascript:expired();"><img src="<?php echo $fav_icon_image_path ?>3.jpeg" border="0" style="width:16px;" alt="3"/></a><a onClick="javascript:expired();">Send IM Request</a>

                                                            <?php } else if ($check_membership_msg == "Onlypremiumaccess") { ?>

                                                                <a onClick="javascript:Onlypremiumaccess();"><img src="<?php echo $fav_icon_image_path ?>3.jpeg" border="0" style="width:16px;" alt="3"/></a><a onClick="javascript:Onlypremiumaccess();" >Send IM Request</a>

                                                            <?php } else if ($check_membership_msg == "Access") { ?>

                                                                <?php
                                                                if ($check_recipient_premium_member_mode->setting_status == 'Y') {


                                                                    $access_recipient_feature_name = "Instant Messenger";
                                                                    $check_recipient_membership_msg = check_membership($access_feature_name, $s_user_id);
                                                                    if ($check_recipient_membership_msg == "Expired") {
                                                                        ?>

                                                                        <a onClick="javascript:Recipientexpired();"><img src="<?php echo $fav_icon_image_path ?>3.jpeg" border="0" style="width:16px;" alt="3" /></a><a onClick="javascript:Recipientexpired();">Send IM Request</a>

                                                                    <?php } else if ($check_recipient_membership_msg == "Onlypremiumaccess") { ?>

                                                                        <a onClick="javascript:RecipientOnlypremiumaccess();"><img src="<?php echo $fav_icon_image_path ?>3.jpeg" border="0" style="width:16px;" alt="3" /></a><a onClick="javascript:RecipientOnlypremiumaccess();" >Send IM Request</a>

                                                                    <?php } else if ($check_recipient_membership_msg == "Access") { ?>

                                                                        <a onClick="up.api.startInstantMessage('<?php echo $s_user_id ?>');"><img src="<?php echo $fav_icon_image_path ?>3.jpeg" border="0" style="width:16px;" alt="3" /></a><a onClick="up.api.startInstantMessage('<?php echo $s_user_id ?>');" >Send IM Request</a>

                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>

                                                                    <a onClick="up.api.startInstantMessage('<?php echo $s_user_id ?>');"><img src="<?php echo $fav_icon_image_path ?>3.jpeg" border="0" style="width:16px;" alt="3" /></a><a onClick="up.api.startInstantMessage('<?php echo $s_user_id ?>');" >Send IM Request</a>


                                                                    <?php //}   ?>


                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </li>
                                                    <?php
                                                }unset($favt_mem);
                                            }
                                            ?> 
                                            </li>
                                            <?php if ($check_skype_mode->setting_status == 'Y') { // Check Skype mode Activated or not  ?> 
                                                <li>
                                                    <?php
                                                    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                        $check_member_skype_exist = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_skype_table WHERE user_id='$s_user_id'"));
                                                        if (($check_member_skype_exist > 0) || ($s_user_id == $user_id)) {   //  member has Skype name
                                                            $skype_name = $wpdb->get_row("SELECT * FROM $dsp_skype_table where user_id='$s_user_id'");
                                                            $check_member_skype_status = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_skype_table WHERE user_id='$s_user_id' AND skype_status='Y'"));
                                                            if ($check_member_skype_status > 0) {
                                                                $check_in_favourites = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_user_favourites_table where user_id='$s_user_id' AND favourite_user_id='$user_id'"));
                                                                if (($check_in_favourites > 0) || ($s_user_id == $user_id)) {
                                                                    ?>
                                                                    <div class="dsp_fav_link_border_user">
                                                                        <a href="skype:<?php echo $skype_name->skype_name ?>"><img src="<?php echo $fav_icon_image_path ?>skype_icon.png" border="0" alt="Skype"/></a><a href="skype:<?php echo $skype_name->skype_name ?>"> <?php echo language_code('DSP_SKYPE_ME'); ?></a></div>
                                                                    <?php
                                                                }  // End if($check_in_favourites>0) 
                                                            } else {
                                                                ?>
                                                                <div class="dsp_fav_link_border_user">
                                                                    <a href="skype:<?php echo $skype_name->skype_name ?>"><img src="<?php echo $fav_icon_image_path ?>skype_icon.png" border="0" alt="Skype" /></a><a href="skype:<?php echo $skype_name->skype_name ?>"> <?php echo language_code('DSP_SKYPE_ME'); ?></a> </div> 
                                                                <?php
                                                            } // End if( $check_member_skype_status>0)
                                                        } // End if($check_member_skype_exist>0)
                                                        ?>   
                                                    <?php } else { ?>
                                                        <div class="dsp_fav_link_border_user">
                                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><img src="<?php echo $fav_icon_image_path ?>skype_icon.png" border="0" alt="Skype" /></a><a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"> <?php echo language_code('DSP_SKYPE_ME'); ?></a></div>
                                                    <?php } ?>
                                                </li> 
                                            <?php } // END Skype mode Activation check condition     ?> 
                                        </ul>
                                    </div>

                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="user-links">
                        <ul>
                            <li class="dsp_fav_link_border">
                                <?php
                                if ($check_couples_mode->setting_status == 'Y') {
                                    if ($s_gender == 'C') {
                                        ?>
                                        <a style="text-decoration:none;" href="<?php
                                        echo add_query_arg(array(
                                            'pid' => 3, 'mem_id' => $s_user_id,
                                            'pagetitle' => view_profile,
                                            'view' => my_profile), $root_link);
                                        ?>">

                                            <img src="<?php echo $fav_icon_image_path ?>friends.jpg" border="0" alt="friends"/>

                                        <?php } else { ?>
                                            <a style="text-decoration:none;" href="<?php
                                            echo add_query_arg(array(
                                                'pid' => 3, 'mem_id' => $s_user_id,
                                                'pagetitle' => view_profile), $root_link);
                                            ?>">
                                                <img src="<?php echo $fav_icon_image_path ?>friends.jpg" border="0" alt="friends"/>
                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <a style="text-decoration:none;" href="<?php
                                            echo add_query_arg(array(
                                                'pid' => 3, 'mem_id' => $s_user_id,
                                                'pagetitle' => view_profile), $root_link);
                                            ?>">
                                                <img src="<?php echo $fav_icon_image_path ?>friends.jpg" border="0" alt="friends" />
                                            <?php } ?>
                                        </a>
                                        <span class="dsp_page_link"> 
                                            <?php
                                            if ($check_couples_mode->setting_status == 'Y') {
                                                if ($s_gender == 'C') {
                                                    ?>
                                                    <a style="text-decoration:underline;" href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 3,
                                                        'mem_id' => $s_user_id,
                                                        'pagetitle' => view_profile,
                                                        'view' => my_profile), $root_link);
                                                    ?>">

                                                        <?php echo language_code('DSP_VIEW_PROFILE_IN_SEARCH'); ?>

                                                    <?php } else { ?>
                                                        <a style="text-decoration:underline;" href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 3,
                                                            'mem_id' => $s_user_id,
                                                            'pagetitle' => view_profile), $root_link);
                                                        ?>">
                                                               <?php echo language_code('DSP_VIEW_PROFILE_IN_SEARCH'); ?>
                                                               <?php
                                                           }
                                                       } else {
                                                           ?> 
                                                        <a style="text-decoration:underline;" href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 3,
                                                            'mem_id' => $s_user_id,
                                                            'pagetitle' => view_profile), $root_link);
                                                        ?>">
                                                               <?php echo language_code('DSP_VIEW_PROFILE_IN_SEARCH'); ?>
                                                           <?php } ?>
                                                    </a>

                                                    </span>
                                                    </li>
                                                    <?php if ($check_my_friend_module->setting_status == 'Y') { // Check My friend module Activated or not   ?>
                                                        <li class="dsp_fav_link_border">
                                                            <?php
                                                            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                                if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                                                    ?>
                                                                    <a href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 8,
                                                                        'user_id' => $user_id,
                                                                        'frnd_userid' => $s_user_id), $root_link);
                                                                    ?>" title="<?php echo language_code('DSP_ADD_TO_FRIENDS'); ?>">
                                                                        <img src="<?php echo $fav_icon_image_path ?>friends.jpg" border="0" alt="friends"/></a>
                                                                    <a style="text-decoration:underline;" href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 8,
                                                                        'user_id' => $user_id,
                                                                        'frnd_userid' => $s_user_id), $root_link);
                                                                    ?>" title="<?php echo language_code('DSP_ADD_TO_FRIENDS'); ?>">
                                                                        <?php echo language_code('DSP_ADD_TO_FRIENDS'); ?></a>
                                                                <?php } else { ?>
                                                                    <a href="<?php
                                                                    echo add_query_arg(array(
                                                                        'pid' => 2,
                                                                        'msg' => 'ed'), $root_link);
                                                                    ?>" title="Edit Profile"><img src="<?php echo $fav_icon_image_path ?>friends.jpg" border="0" alt="friends"/></a><a style="text-decoration:underline;" href="<?php
                                                                       echo add_query_arg(array(
                                                                           'pid' => 2,
                                                                           'msg' => 'ed'), $root_link);
                                                                       ?>" title="Edit Profile"><?php echo language_code('DSP_ADD_TO_FRIENDS'); ?></a>  
                                                                       <?php } ?>
                                                                   <?php } else { ?>
                                                                <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><img src="<?php echo $fav_icon_image_path ?>friends.jpg" border="0" alt="friends" /></a><a style="text-decoration:underline;" href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><?php echo language_code('DSP_ADD_TO_FRIENDS'); ?></a>
                                                            <?php } ?>
                                                        </li>
                                                    <?php } // END My friends module Activation check condition      ?>
                                                    <li class="dsp_fav_link_border">
                                                        <?php
                                                        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                            ?>
                                                            <a href="<?php
                                                            echo add_query_arg(array(
                                                                'pid' => 7, 'user_id' => $user_id,
                                                                'fav_userid' => $s_user_id), $root_link);
                                                            ?>" title="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>">
                                                                <img src="<?php echo $fav_icon_image_path ?>star.jpg" border="0" alt="star"/></a><a style="text-decoration:underline;" href="<?php
                                                            echo add_query_arg(array(
                                                                'pid' => 7, 'user_id' => $user_id,
                                                                'fav_userid' => $s_user_id), $root_link);
                                                            ?>" title="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>">
                                                                <?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?></a>
                                                        <?php } else { ?>
                                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><img src="<?php echo $fav_icon_image_path ?>star.jpg" border="0" alt="star"/></a><a style="text-decoration:underline;" href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?></a>
                                                        <?php } ?>
                                                    </li>
                                                    <li class="dsp_fav_link_border" >
                                                        <?php
                                                        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                            if ($check_my_friends_list > 0) {
                                                                ?>
                                                                <a href="<?php
                                                                echo add_query_arg(array(
                                                                    'pid' => 14,
                                                                    'pagetitle' => 'my_email',
                                                                    'message_template' => 'compose',
                                                                    'frnd_id' => $s_user_id,
                                                                    'Act' => 'send_msg'), $root_link);
                                                                ?>" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                                    <img src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" /></a><a style="text-decoration:underline;" href="<?php
                                                                echo add_query_arg(array(
                                                                    'pid' => 14,
                                                                    'pagetitle' => 'my_email',
                                                                    'message_template' => 'compose',
                                                                    'frnd_id' => $s_user_id,
                                                                    'Act' => 'send_msg'), $root_link);
                                                                ?>" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>"> <?php echo language_code('DSP_SEND_MESSAGES'); ?></a>
                                                                                                                                          <?php } else { ?>
                                                                <a href="<?php
                                                                echo add_query_arg(array(
                                                                    'pid' => 14,
                                                                    'pagetitle' => 'my_email',
                                                                    'message_template' => 'compose',
                                                                    'receive_id' => $s_user_id), $root_link);
                                                                ?>" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                                    <img src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" /></a><a style="text-decoration:underline;" href="<?php
                                                                echo add_query_arg(array(
                                                                    'pid' => 14,
                                                                    'pagetitle' => 'my_email',
                                                                    'message_template' => 'compose',
                                                                    'receive_id' => $s_user_id), $root_link);
                                                                ?>" title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>"> <?php echo language_code('DSP_SEND_MESSAGES'); ?></a>
                                                                                                                                          <?php } //if($check_my_friends_list>0)     ?>
                                                                                                                                      <?php } else { ?>
                                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login">  <img src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" /></a><a style="text-decoration:underline;" href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><?php echo language_code('DSP_SEND_MESSAGES'); ?></a>
                                                        <?php } ?>
                                                    </li>
                                                    <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not      ?>
                                                        <li>
                                                            <div class="dsp_fav_link_border">
                                                                <?php
                                                                if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                                    if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                                                        ?>
                                                                        <a href='<?php
                                                                        echo add_query_arg(array(
                                                                            'pid' => 3,
                                                                            'pagetitle' => 'send_wink_msg',
                                                                            'receiver_id' => $s_user_id), $root_link);
                                                                        ?>' title="<?php echo language_code('DSP_SEND_WINK'); ?>">
                                                                            <img src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" /></a><a style="text-decoration:underline;" href='<?php
                                                                        echo add_query_arg(array(
                                                                            'pid' => 3,
                                                                            'pagetitle' => 'send_wink_msg',
                                                                            'receiver_id' => $s_user_id), $root_link);
                                                                        ?>' title="<?php echo language_code('DSP_SEND_WINK'); ?>"><?php echo language_code('DSP_SEND_WINK'); ?></a>
                                                                                                                                                  <?php } else { ?>
                                                                        <a href="<?php
                                                                        echo add_query_arg(array(
                                                                            'pid' => 2,
                                                                            'msg' => 'ed'), $root_link);
                                                                        ?>" title="Edit Profile"><img src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" /></a><a style="text-decoration:underline;" href="<?php
                                                                           echo add_query_arg(array(
                                                                               'pid' => 2,
                                                                               'msg' => 'ed'), $root_link);
                                                                           ?>" title="Edit Profile"> <?php echo language_code('DSP_SEND_WINK'); ?></a>
                                                                           <?php } ?>
                                                                       <?php } else { ?>
                                                                    <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login">  <img src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" /></a><a style="text-decoration:underline;" href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"> <?php echo language_code('DSP_SEND_WINK'); ?></a>
                                                                <?php } ?>
                                                            </div>
                                                        </li>
                                                    <?php } // END My friends module Activation check condition        ?> 
                                                    </ul>
                                                    </li>
                                                    </ul>
                                                    </div>
                                                    </div>
                                                    </div>
                                                    <?php
                                                }// foreach($search_members as $member) 
                                                ?>
                                                <div class="row-paging"> 
                                                    <div style="float:left; width:100%;">
                                                        <?php
// --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                                                        echo $pagination
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                                                        ?>
                                                    </div>  
                                                </div>
