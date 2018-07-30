<?php
 /**
 * This file  is overwrite version of members/loggedin/online/dsp_online_other_users.php
 * You can customize this file simply keeping on  child theme child-theme/dsp_dating/members/withoutloggedin/tabs/dsp_online_other_users.php
 */

global $wp_query;
$page_id = $wp_query->post->ID; //fetch post query string id
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$posts_table = $wpdb->prefix . POSTS;
$insertMemberPageId = "UPDATE $dsp_general_settings SET setting_value = '$page_id' WHERE setting_name ='member_page_id'";
$wpdb->query($insertMemberPageId);
$posts_table = $wpdb->prefix . POSTS;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$page_id'");

// ROOT PATH
$pieces = explode('/', $_SERVER['REQUEST_URI']);
$page_index = array_search('page', $pieces);

//if (get('page')) $page = get('page'); else $page = 1;
$page = isset($_REQUEST['page']) ? esc_sql($_REQUEST['page']) : $pieces[$page_index + 1];
if($page_index == false)
    $page=0;

// How many adjacent pages should be shown on each side?
$adjacents = 2;
$limit = !empty($check_search_result->setting_value) ? $check_search_result->setting_value : 12;
if ($page > 1)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;
// ------------------------------------------------End Paging code------------------------------------------------------ 
$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : get('gender');
$lm_gender = $gender;
$age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : get('age_from');
$age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : get('age_to');

$page_name = $root_link . "online_members/show/display/gender/";
$page_name .= ! empty($gender) ?   'gender/' . $gender . "/": '';
$page_name .= !empty($age_from) ? 'age_from/' . $age_from . "/"  : '';
$page_name .= !empty($age_to) ?   'age_to/' . $age_to . "/" : '';
$filters = '';
$dsp_user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$random_online_status = $check_online_member_mode->setting_status;
$online_member = array();
$filters = array(
            'age_from' => $age_from,
            'age_to' => $age_to,
            'gender' => $gender,
            'start' => $start,
            'last' => $limit,
        );
if($random_online_status == 'Y')
{
    $random_online_number = $check_online_member_mode->setting_value;
    $online_member = dsp_randomOnlineMembers($random_online_number,$filters);
}else
{   
   $online_member = dsp_getOnlineMembers($filters);
}
$user_count = dsp_getTotalOnlineUsers(false,$random_online_status);
$total_results1 = $user_count;
//add pagination
if( $total_results1 > $limit ) {
    $pagination = love_match_dsp_pagination( $total_results1,$limit, $page, $adjacents,$page_name); 
}
?>
</div>
<?php if($total_results1 > 0): ?>
<div class="heading-submenu dsp-block"><?php echo language_code('DSP_ONLINE_WIDGET_TEXT') ?></div>
<div class="lm-guest-online-tabs-container">
    <div class="box-pedding">
        <div class="dsp-form-container" style="">
            <div class="content-search">
                <form action="<?php echo $root_link . "online_members/" ?>" method="post" class="dspdp-form-inline">
                    <div class="dsp-form-group clearfix" align="left">
                        <div class="col-md-3 col-sm-3 col-xs-12"> 
                            <span class="dsp-control-label"><?php echo language_code('DSP_GENDER') ?></span>
                            <span class="dsp-md-2">
                                <select name="gender" class="dspdp-form-control">
                                    <option value="all" <?php if ($gender == 'all' || isset($_REQUEST['show'])) { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> >All</option>
                                    <?php
                                        echo get_gender_list($gender); 
                                    ?>
                                </select>
                            </span>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12">  
                            <span class="dsp-control-label"><?php echo language_code('DSP_AGE') ?></span>
                            <span class="dsp-md-2">
                                <select name="age_from" class="dspdp-form-control">
                                    <?php for ($i = '18'; $i <= '90'; $i++) { ?>
                                        <option value="<?php echo $i ?>" <?php echo  $i == $age_from ? 'selected="selected"': '';?>><?php echo $i ?></option>
                                    <?php } ?>
                                </select>
                            </span>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12">  
                            <span class="dsp-md-1 dsp-control-label"><?php echo language_code('DSP_TO') ?></span> 
                            <span class="dsp-md-2">
                                <select  name="age_to" class="dspdp-form-control dspdp-xs-form-group">
                                    <?php for ($j = '90'; $j >= '18'; $j--) { ?>
                                        <option value="<?php echo $j ?>" <?php  echo $j == $age_to ? 'selected="selected"': '';?>><?php echo $j ?></option>
                                    <?php } ?>


                                </select>
                            </span>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-12"> 
                            <input class="dspdp-btn dspdp-btn-default pull-right" name="submit" type="submit" value="<?php echo language_code('DSP_FILTER_BUTTON') ?>" />
                        </div>
                </form>

            </div>
            <div class="dspdp-seprator"></div>
            </div>
    </div>
    <div id="<?php if( get_theme_mod( 'dsp_pagination' ) === "single_button" ){ echo esc_attr( 'lm_member_home_on_click_online_logout_infinite_loop' ); } elseif( $total_results1 > $limit && get_theme_mod( 'dsp_pagination', 'infinite_scroll' ) == 'infinite_scroll'  ) { echo esc_attr( 'lm_member_home_online_logout_infinite_loop' ); 
            } ?>" class="dspdp-row row dsp-member-container">
        <?php
        foreach ($online_member as $member1) {
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
            $stealth_mode = isset($member->stealth_mode) ? $member->stealth_mode : '';
            //$s_user_pic = $member->user_pic;
            $displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$s_user_id'");
            $country_name = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$s_country_id");
            $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$s_state_id");
            $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$s_city_id");
            $favt_mem = array();
            $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");
            foreach ($private_mem as $private) {
                $favt_mem[] = $private->favourite_user_id;
            }
            //add sidebar on the basis of activeness on right sidebar
                if( is_active_sidebar( 'lm_dating_member_right_sidebar' ) ) {
                    $grid_layout_css = 'col-md-4 col-sm-6 col-xs-12';
                } else {
                    $grid_layout_css = 'col-md-3 col-sm-4 col-xs-12';
                }
            ?>
                
            <div class="<?php echo esc_attr( $grid_layout_css ); ?>">
                <div class="box-search-result image-container">
                    <div class="img-box dspdp-spacer circle-image">
                            <?php
                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($s_gender == 'C') {
                                    ?>

                                <?php if ($s_make_private == 'Y') { ?>

                                    <?php if ($current_user->ID != $s_user_id) { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >               
                                                <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"    border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                            <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/>
                                        </a>
                                    <?php } ?>

                                <?php } else { ?>

                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                        <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/>
                                    </a>
                                <?php } ?>

                            <?php } else { ?>

                                <?php if ($s_make_private == 'Y') { ?>

                                    <?php if ($current_user->ID != $s_user_id) { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                                <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >              
                                                <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"     border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                            <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                        </a>
                                    <?php } ?>
                                <?php } else { ?>

                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                        <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                    </a>
                                <?php } ?>
                                <?php
                            }
                        } else {
                            ?> 

                            <?php if ($s_make_private == 'Y') { ?>
                                <?php if ($current_user->ID != $s_user_id) { ?>

                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                            <img class="img-circle" src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo" />
                                        </a>                
                                    <?php } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >              
                                            <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"    border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>                
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                        <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                    </a>
                                <?php } ?>

                            <?php } else { ?>

                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                    <img class="img-circle" src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                </a>
                            <?php } ?>

                        <?php } ?>

                    </div>
                    <div class="user-status dsp_name img-name">

                        <span class="user-name"><strong>

                                <?php
                                if ($check_couples_mode->setting_status == 'Y') {
                                    if ($s_gender == 'C') {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                            <?php
                                            if (strlen($displayed_member_name->display_name) > 15)
                                                echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                            else
                                                echo $displayed_member_name->display_name;
                                            ?>                
                                        <?php } else { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                <?php
                                                if (strlen($displayed_member_name->display_name) > 15)
                                                    echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                else
                                                    echo $displayed_member_name->display_name;
                                                ?>
                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                <?php
                                                if (strlen($displayed_member_name->display_name) > 15)
                                                    echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                else
                                                    echo $displayed_member_name->display_name;
                                                ?>
                                            <?php } ?>
                                        </a>
                                    </a></strong>
                            </span>

                            <!-- online status  -->
                            <span class="online dspdp-online-status">
                                <?php
                                    $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_online_user_table WHERE user_id=$s_user_id");
                                    $check_online_user = ($stealth_mode == "Y") ? '0' : $check_online_user;
                                    ?>
                                    <?php
                                    //echo $fav_icon_image_path;
                                    if ($check_online_user > 0)
                                        echo '<span class="dspdp-status-on" '.language_code('DSP_CHAT_ONLINE').'></span>';
                                    else
                                        echo '<span class="dspdp-status-off" '.language_code('DSP_CHAT_OFFLINE').'></span>';
                                ?>
                            </span>
                    </div>
                    <div class="user-details dspdp-spacer dspdp-user-details dsp-user-details">
                        <?php echo $s_age ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?> <?php echo get_gender($s_gender); ?> <?php echo language_code('DSP_FROM_TEXT'); ?> <br /><?php if (@$city_name->name != "") echo @$city_name->name . ','; ?> <?php if (@$state_name->name != "") echo @$state_name->name . ','; ?> <?php echo @$country_name->name; ?>
                    </div>
                </div>
            </div>
        <?php }
        ?>
    </div>
</div>
</div>

<div class="row-paging"> 
    <div class="lm-online-members-lggedin" page_id="<?php echo absint( $wp_query->post->ID ); ?>" request_uri="<?php echo esc_url( $_SERVER["REQUEST_URI"] ); ?>" gender="<?php echo esc_attr( $lm_gender ); ?>" age_from="<?php echo esc_attr( $age_from ); ?>" age_to="<?php echo esc_attr( $age_to ); ?>">
        <?php
// --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
        echo $total_results1 > $limit ? $pagination : '';
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
        ?>
    </div>  
</div>
<?php else : ?>
    <div class="box-border">
            <div class="box-pedding">
                <div class="page-not-found">
                    <?php echo language_code('DSP_NO_RECORD_FOUND_EXTRAS'); ?><br /><br />
                </div>
            </div>
    </div>
<?php endif; ?>
