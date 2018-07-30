<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a onclick="loadExtra()" ><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_SEARCH'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>

</div>
<?php
// http://www.dsdev.biz/wp-content/plugins/dsp_dating/m1//search_header.php?user_id=885&pagetitle=myinterest_search_result&search_type=my_interest&my_int=reading&gender=all&age_to=90&age_from=18

$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_user_search_criteria_table = $wpdb->prefix . DSP_USER_SEARCH_CRITERIA_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_online_user_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;


if (isset($_GET['page1']))
    $page = $_GET['page1'];
else
    $page = 1;



// How many adjacent pages should be shown on each side?

$adjacents = 2;

$limit = 5;  //8

if ($page)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;






$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';

$age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : '';

$age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : '';

$search_type = isset($_REQUEST['search_type']) ? $_REQUEST['search_type'] : '';

$my_int = isset($_REQUEST['my_int']) ? $_REQUEST['my_int'] : '';

$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

$user_ID = $user_id;

$strQuery1 = "SELECT user_id, my_interest FROM $dsp_user_profiles_table where my_interest like '%$my_int%' ";

if ($age_from >= 18) {
    $strQuery1 .= " and ((year(CURDATE())-year(age)) > '" . $age_from . "') AND ((year(CURDATE())-year(age)) < '" . $age_to . "') ";
}


if ($gender == 'M') {
    $strQuery1 .= " AND gender='M'";
} else if ($gender == 'F') {
    $strQuery1 .= " AND gender='F'   ";
} else if ($gender == 'C') {
    $strQuery1 .= " AND gender='C'  ";
} else if ($gender == 'all') {
    $strQuery1 .= " AND gender IN('M','F','C') ";
}
//echo $strQuery1;

$user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery1) AS total");

$intRecordsPerPage = 10;


$intStartLimit = isset($_REQUEST['p']) ? $_REQUEST['p'] : ''; # page selected 1,2,3,4...



if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
    $intStartLimit = 1; //default
}

$intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;



$total_results1 = $user_count;


// Calculate total number of pages. Round up using ceil()
// $total_pages1 = ceil($total_results1 / $max_results1); 

if ($page == 0)
    $page = 1;     //if no page var is given, default to 1.

$prev = $page - 1;

$next = $page + 1;

$lastpage = ceil($total_results1 / $limit); //lastpage is = total pages / items per page, rounded up.

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
			 
				<div onclick='saveInterestHidden(1)' class='btn-pre1'>
					<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m1/images/bb.png" . "'/>
				</div>";
    } else {
        $pagination.= "
				<div class='btn-pre1'>
					<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m1/images/b.png" . "'/>
				</div>";
    }

    if ($page > 1) {
        $pagination.="<div  onclick='saveInterestHidden($prev)' class='btn-pre2'>
							<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m1/images/aa.png" . "'/>
						</div>";
    } else {
        $pagination.=" <div  class='btn-pre2'>
							<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m1/images/a.png" . "'/>
						</div>";
    }


    $pagination.= "<div class='main3' > 
							<div class='para'> Page</div>
							<div class='main6'>
								<div class='middle'>$page</div>
							</div>
							<div class='para1'>of $lastpage</div>
						</div>";

    if ($page < $lastpage) {
        $pagination.= "
			<div onclick='saveInterestHidden($next)' class='main4' >
				<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m1/images/c.png" . "'/>
			</div>";

        $pagination.= "	<div onclick='saveInterestHidden($lastpage)' class='main5'>
								<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m1/images/d.png" . "'/>
							</div>";
    } else {
        $pagination.= "
			<div class='main4'>
			<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m1/images/cc.png" . "'/>
			</div>";

        $pagination.= "	<div class='main5'>
								<img src='" . get_bloginfo('url') . "/wp-content/plugins/dsp_dating/m1/images/dd.png" . "'/>
							</div>";
    }

    $pagination.= "</div>\n";
}



// ------------------------------------------------End Paging code------------------------------------------------------ // 
?>
<div class="ui-content" data-role="content">
    <div class="content-primary">
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
            <form id="frmsearch">

                <input type="hidden" value="<?php echo $my_int; ?>" name="my_int"/>
                <input type="hidden" value="<?php echo $gender; ?>" name="gender"/>
                <input type="hidden" value="<?php echo $age_from; ?>" name="age_from"/>
                <input type="hidden" value="<?php echo $age_to; ?>" name="age_to"/>
                <input type="hidden" value="<?php echo $user_id; ?>" name="user_id"/>
                <input type="hidden" value="<?php echo "myinterest_search_result"; ?>" name="pagetitle"/>
                <input type="hidden" value="<?php echo $search_type; ?>" name="search_type"/>
                <input type="hidden" value="" id="page" name="page1" />
            </form>	
            <?php
            $strQuery1 . " LIMIT $start, $limit  ";

            $user_profiles_table = $wpdb->get_results($strQuery1 . " LIMIT $start, $limit  ");


            foreach ($user_profiles_table as $member1) {
                ?>

                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                    <?php
                    if ($check_couples_mode->setting_status == 'Y') {
                        $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'");
                    } else {
                        $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member1->user_id'");
                    }



                    $s_user_id = $member->user_id;

                    $s_country_id = $member->country_id;

                    $s_gender = $member->gender;

                    $s_seeking = $member->seeking;

                    $s_state_id = $member->state_id;
                    $s_city_id = $member->city_id;

                    $s_age = GetAge($member->age);

                    $s_make_private = $member->make_private;

                    $displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$s_user_id'");

                    $country_name = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$s_country_id");

                    $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$s_state_id");

                    $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$s_city_id");

                    $favt_mem = array();

                    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");

                    foreach ($private_mem as $private) {

                        $favt_mem[] = $private->favourite_user_id;
                    }
                    ?>
                    <div class="dsp_pro_full_view">
                        <div class="profile_img_view">
                            <?php
                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($s_gender == 'C') {
                                    ?>
                                    <?php if ($s_make_private == 'Y') {
                                        ?>
                                        <?php
                                        if ($user_id != $s_user_id) {
                                            if (!in_array($user_id, $favt_mem)) {
                                                ?>

                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                    <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  style="width:100px; height:100px;"  class="img" />
                                                </a>                
                                                <?php
                                            } else {
                                                ?>
                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				
                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    style="width:100px; height:100px;"  class="img"/>
                                                </a>                
                                                <?php
                                            }
                                        } else {
                                            ?>

                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  >
                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  class="img" />
                                            </a>
                                        <?php } ?>

                                        <?php
                                    } else {
                                        ?>
                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  class="img" />
                                        </a>
                                    <?php } ?>
                                    <?php
                                } else {
                                    ?>
                                    <?php if ($s_make_private == 'Y') {
                                        ?>
                                        <?php if ($user_id != $s_user_id) {
                                            ?>
                                            <?php if (!in_array($user_id, $favt_mem)) {
                                                ?>

                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                    <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  style="width:100px; height:100px;"  class="img" />
                                                </a>                
                                                <?php
                                            } else {
                                                ?>
                                                <a  onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  >				
                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"     style="width:100px; height:100px;"  class="img"/>
                                                </a>                
                                                <?php
                                            }
                                        } else {
                                            ?>

                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  class="img" />
                                            </a>
                                        <?php } ?>
                                        <?php
                                    } else {
                                        ?>
                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  >
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  class="img" />
                                        </a>
                                    <?php } ?>
                                    <?php
                                }
                            } else {
                                ?> 
                                <?php if ($s_make_private == 'Y') {
                                    ?>
                                    <?php if ($user_id != $s_user_id) {
                                        ?>
                                        <?php if (!in_array($user_id, $favt_mem)) {
                                            ?>
                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  >
                                                <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  style="width:100px; height:100px;"  class="img" />
                                            </a>                
                                            <?php
                                        } else {
                                            ?>
                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  >				
                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    style="width:100px; height:100px;"  class="img"/>
                                            </a>                

                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"   >
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  class="img" />
                                        </a>
                                    <?php } ?>
                                    <?php
                                } else {
                                    ?>
                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  >
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  class="img" />
                                    </a>
                                <?php } ?>
                            <?php } ?>

                        </div>
                        <div class="dsp_on_lf_view">
                            <ul>
                                <li>
                                    <span class="online"><?php $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_online_table WHERE user_id=$s_user_id"); ?>
                                        <img class="icon-on-off" src="<?php
                                        echo $fav_icon_image_path;
                                        if ($check_online_user > 0)
                                            echo 'online-chat.gif';
                                        else
                                            echo 'off-line-chat.jpg';
                                        ?>" title="<?php
                                             if ($check_online_user > 0)
                                                 echo language_code('DSP_CHAT_ONLINE');
                                             else
                                                 echo language_code('DSP_CHAT_OFFLINE');
                                             ?>" border="0" />
                                    </span>
                                    <span class="user-name">
                                        <strong>
                                            <?php
                                            if ($check_couples_mode->setting_status == 'Y') {
                                                if ($s_gender == 'C') {
                                                    ?>

                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                        <?php echo $displayed_member_name->display_name ?>                
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')"  >
                                                            <?php echo $displayed_member_name->display_name ?>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?> 
                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                                            <?php echo $displayed_member_name->display_name ?>
                                                        <?php } ?>

                                                    </a>
                                                    </strong>
                                                    </span>
                                                    </li>
                                                    <li>
                                                        <div class="user-details">

                                                            <?php echo $s_age ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?> <?php
                                                            if ($s_gender == 'M')
                                                                echo language_code('DSP_MAN');
                                                            else if ($s_gender == 'F')
                                                                echo language_code('DSP_WOMAN');
                                                            else if ($s_gender == 'C')
                                                                echo language_code('DSP_COUPLE');
                                                            ?> <?php echo language_code('DSP_FROM_TEXT'); ?> <br />
                                                            <?php
                                                            if (count($city_name) > 0) {
                                                                if ($city_name->name != "")
                                                                    echo $city_name->name . ',';
                                                            }
                                                            ?> 
                                                            <?php
                                                            if (count($state_name) > 0) {
                                                                if ($state_name->name != "")
                                                                    echo $state_name->name . ',';
                                                            }
                                                            ?>
                                                            <?php echo @$country_name->name; ?>
                                                        </div>
                                                    </li>

                                                    <li class="dsp_prof_view">
                                                        <div style="width: 100%">
                                                            <?php if ($check_my_friend_module->setting_status == 'Y') { // Check My friend module Activated or not 
                                                                ?>
                                                                <div>
                                                                    <?php
                                                                    if ($check_user_profile_exist > 0) {  // check user dating profile exist or not 	 
                                                                        ?>
                                                                        <a onclick="addFriend('<?php echo $s_user_id; ?>')"  title="<?php echo language_code('DSP_ADD_TO_FRIENDS'); ?>">
                                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>friend.jpg" border="0" />
                                                                        </a>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <a onclick="redirectEditProfile('<?php echo language_code('DSP_UPDATE_PROFILE_BEFORE_ADD_FRND_MSG') ?>');"  title="Edit Profile">
                                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>friend.jpg" border="0" />
                                                                        </a> 
                                                                    <?php } ?>


                                                                </div>
                                                            <?php } // END My friends module Activation check condition  ?>


                                                            <div>
                                                                <a onclick="addFavourite('<?php echo $s_user_id; ?>')"  title="<?php echo language_code('DSP_ADD_TO_FAVOURITES'); ?>">
                                                                    <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>star.jpg" border="0" />
                                                                </a>
                                                            </div>

                                                            <div>
                                                                <?php
                                                                if (isset($check_my_friends_list) && $check_my_friends_list > 0) {
                                                                    ?>
                                                                    <a onclick="composeMessage('<?php echo $s_user_id ?>', 0)"  title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                                        <img src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" />
                                                                    </a>
                                                                    <?php
                                                                } else {
                                                                    ?>
                                                                    <a onclick="composeMessage('<?php echo $s_user_id ?>', 0)"  title="<?php echo language_code('DSP_SEND_MESSAGES'); ?>">
                                                                        <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" />
                                                                    </a>
                                                                <?php } //if($check_my_friends_list>0)   ?>

                                                            </div>
                                                        </div>

                                                        <div style="width: 100%">

                                                            <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not 
                                                                ?>
                                                                <div>
                                                                    <?php
                                                                    if ($check_user_profile_exist > 0) {  // check user dating profile exist or not 		  
                                                                        ?>

                                                                        <a onclick="sendWink('<?php echo $s_user_id; ?>')" title="<?php echo language_code('DSP_SEND_WINK'); ?>">
                                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" />
                                                                        </a>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <a onclick="redirectEditProfile('<?php echo language_code('DSP_UPDATE_PROFILE_BEFORE_ADD_FRND_MSG') ?>');"  title="Edit Profile">
                                                                            <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" />
                                                                        </a>
                                                                    <?php } ?>


                                                                </div>
                                                                <?php
                                                            } // END My friends module Activation check condition  
                                                            // check if one to one csetting is yes
                                                            if ($check_chat_one_mode->setting_status == 'Y') {
                                                                // send chat request if user is online
                                                                if ($s_user_id != $user_id) { // if this member is not user itself
                                                                    $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_online_user_table WHERE user_id=$s_user_id");

                                                                    if ($check_online_user > 0) { // show chat icon if user is online
                                                                        ?>
                                                                        <div>
                                                                            <a onclick="openChatRoom('<?php echo $s_user_id; ?>', 'send_request')">
                                                                                <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>chat.jpg" border="0" />
                                                                            </a>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    </ul>
                                                    </div>
                                                    </div>

                                                    </li>


                                                <?php }
                                                ?>

                                                </ul>
                                                </div>
                                                </div>

                                                <div class="ds_pagination" > 
                                                    <?php echo $pagination ?>
                                                </div>
