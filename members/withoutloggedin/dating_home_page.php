<?php  
/*

  Copyright (C) www.wpdating.com - All Rights Reserved!

  Author - www.wpdating.com

  WordPress Dating Plugin

  contact@wpdating.com

 */

// ----------------------------------------------- Start Paging code------------------------------------------------------ //  

if (get('page') != "")

    $page = get('page');

else

    $page = 1;




// How many adjacent pages should be shown on each side?

$adjacents = 2;

$limit = (isset($check_search_result->setting_value) && $check_search_result->setting_value != 0) ? $check_search_result->setting_value : 8;



if ($page)

    $start = ($page - 1) * $limit;    //first item to display on this page

else

    $start = 0;



// ----------------------------------------------- End Paging code------------------------------------------------------ //

//$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE status_id=1 AND user_id = '$member_id'");

$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;

$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

$check_member_list_gender_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'member_list_gender'");

$member_list_gender = $check_member_list_gender_mode->setting_value;

$errors = array();

$page_name = $root_link . "ALL/";

if ($member_list_gender == 2) {

    $member_gender = 'M';

} else if ($member_list_gender == 3) {

    $member_gender = 'F';

} else

    $member_gender = '';



if ($member_gender != '') {

    $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_profiles WHERE status_id=1 AND gender='$member_gender' AND country_id!=0 order by user_profile_id DESC");

} else {

    if ($check_couples_mode->setting_status == 'Y') {

        $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_profiles WHERE status_id=1  AND country_id!=0  AND stealth_mode='N' order by user_profile_id DESC");

    }else {

        $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_profiles WHERE status_id=1  AND country_id!=0 AND gender!='C' and stealth_mode='N' order by user_profile_id DESC");

    }

}


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

 ##### Pagination sections ###### 
 
 $pagination =  dsp_pagination($total_results1, $limit, $page, $adjacents,$page_name);

//Careful with $root_link //find out where from where the value is assigned and what is the value.

$display_option = $check_member_not_logged_display_options->setting_value;

//if ( $display_option == 'mu' ): //for member up option
if ( $display_option == 'tu' ) : //for tab up option
    include_once(WP_DSP_ABSPATH . "members/layouts/tabs.php");
    include_once(WP_DSP_ABSPATH . "members/layouts/members.php");
elseif ( $display_option == 'to' ) : //for tab only 
    include_once(WP_DSP_ABSPATH . "members/layouts/tabs.php");
else :
    include_once(WP_DSP_ABSPATH . "members/layouts/members.php");
    include_once(WP_DSP_ABSPATH . "members/layouts/tabs.php");

endif;





