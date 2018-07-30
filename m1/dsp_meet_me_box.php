<?php
@include_once('../../../../wp-config.php');

/* To off  display error or warning which is set of in wp-confing file ---
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include(WP_DSP_ABSPATH . "general_settings.php");

include(WP_DSP_ABSPATH . "include_dsp_tables.php");

//include(WP_DSP_ABSPATH."/files/includes/functions.php");

global $wpdb;
$posts_table = $wpdb->prefix . POSTS;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_meet_me = $wpdb->prefix . DSP_MEET_ME_TABLE;
$member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");
$member_pageid = $member_page_title_ID->setting_value;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");
$member_page_id = $post_page_title_ID->ID;  // Print Site root link
$root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";  // Print Site root link



function GetAge($Birthdate) {
    $dob = strtotime($Birthdate);
    $y = date('Y', $dob);
    if (($m = (date('m') - date('m', $dob))) < 0) {
        $y++;
    } elseif ($m == 0 && date('d') - date('d', $dob) < 0) {
        $y++;
    }
    return date('Y') - $y;
}

$user_id = $_REQUEST['user_id'];  // print session USER_ID
$imagepath = get_option('siteurl') . '/wp-content/';  // image Path
$posts_table = $wpdb->prefix . POSTS;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");
$member_pageid = $member_page_title_ID->setting_value;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");
$member_page_id = $post_page_title_ID->ID;  // Print Site root link
$root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";  // Print Site root link

extract($_REQUEST);

//print_r($_REQUEST);

if (isset($gender) && $gender != "")
    $user_seeking = $gender;
else
    $user_seeking = $wpdb->get_var("select seeking from $dsp_user_profiles where user_id='$user_id'");


if (isset($age_from) && isset($age_to) && $age_from != "" && $age_to != "") {

    $age = "((year(CURDATE())-year(age)) > '" . $age_from . "') AND ((year(CURDATE())-year(age)) < '" . $age_to . "') and  ";
} else
    $age = "";
$check_member_ids = $wpdb->get_results(" select member_id from $dsp_meet_me where user_id='$user_id'");
$ids = "";
if (count($check_member_ids) > 0) {
    foreach ($check_member_ids as $id) {
        $ids.=$id->member_id . ",";
    }
}





if ($ids != "")
    $ids = ' user_id not in (' . rtrim($ids, ',') . ') and ';
if ($user_seeking != 'all')
    $gender_check = " gender='$user_seeking' and  ";
else
    $gender_check = "";

$profile_row = $wpdb->get_row("select * from $dsp_user_profiles where $ids $gender_check $age country_id!=0 limit 1");
@$user_image = display_members_photo($profile_row->user_id, $imagepath);

if ($user_image != "") {
    if (count($profile_row) > 0) {
        $user_login = $wpdb->get_var("SELECT user_login FROM $dsp_user_table where ID='$profile_row->user_id'");
        $user_country = $wpdb->get_var("SELECT name FROM $dsp_country_table where country_id='$profile_row->country_id'");
        if ($profile_row->state_id != 0)
            $user_state = $wpdb->get_var("SELECT name FROM $dsp_state_table where state_id='$profile_row->state_id'");
        if ($profile_row->city_id != 0)
            $user_city = $wpdb->get_var("SELECT name FROM $dsp_city_table where city_id='$profile_row->city_id'");
        ?>

        <div class="meet-to-info">
            <h1><?php echo language_code('DSP_MEET_ME_QUESTION') ?></h1>
            <div class="btn-row-meet">

                <form id="dsp_yes">
                    <input id="dsp_meet_me_user" name="member_id" type="hidden" value="<?php echo $profile_row->user_id; ?>"  />
                    <input type="hidden" name="action" value="<?php echo language_code('DSP_OPTION_YES') ?>" />
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                    <input type="hidden" name="pagetitle" value="meet_me" />
                    <input class="button yes"  onclick="ExtraLoad('div_meetme', 'yes')"   type="button" value="<?php echo language_code('DSP_OPTION_YES') ?>"  />
                </form>
                <form id="dsp_no">
                    <input id="dsp_meet_me_user" name="member_id" type="hidden" value="<?php echo $profile_row->user_id; ?>"  />
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                    <input type="hidden" name="pagetitle" value="meet_me" />
                    <input type="hidden" name="action" value="<?php echo language_code('DSP_OPTION_NO') ?>" />
                    <input class="button no"  onclick="ExtraLoad('div_meetme', 'no')" type="button" value="<?php echo language_code('DSP_OPTION_NO') ?>" />
                </form>

            </div>
            <div style="clear:both;"></div>
            <div class="meet-status"><?php $profile_row->my_status; ?></div>
            <div class="image-box">
                <a href="<?php
                if ($profile_row->gender == 'C') {
                    echo add_query_arg(array('pid' => 3, 'mem_id' => $profile_row->user_id,
                                             'pagetitle' => 'view_profile', 'view' => 'my_profile'), $root_link);
                } else {
                    echo add_query_arg(array('pid' => 3, 'mem_id' => $profile_row->user_id,
                                             'pagetitle' => 'view_profile'), $root_link);
                }
                ?>"><img style=" max-width:375px; max-height:375px;" src="<?php echo $user_image; ?>" /></a>
            </div>
            <div class="user-meetto-info">
                <?php echo $user_login; ?><br />
                <?php echo GetAge($profile_row->age); ?><br />
                <?php
                if (isset($user_city))
                    echo $user_city . ', ';
                if (isset($user_state))
                    echo $user_state . ', ';
                echo $user_country;
                ?>

            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="thanks-note"><p class="error" style="text-align: center"><?php echo language_code('DSP_MEET_ME_NO_MORE') ?></p>
        <a href="dsp_main_search.html"><?php echo language_code('DSP_START_NEW_SEARCH'); ?></a>


    </div>
<?php } ?>

