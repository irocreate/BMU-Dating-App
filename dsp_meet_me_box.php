<?php
@include_once('../../../wp-config.php');
include_once(WP_DSP_ABSPATH . 'functions.php');
include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");
include_once(WP_DSP_ABSPATH . "/files/includes/functions.php");
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
$user_id = $_REQUEST['user_id'];  // print session USER_ID
$imagepath = get_option('siteurl') . '/wp-content/';  // image Path
$conditions = array();
$age  = '';
$Mem_Image_path = WPDATE_URL . '/images/private-photo-pic.jpg'; // private image path
extract($_REQUEST);
if (isset($gender))
    $user_seeking = $gender;
else
    $user_seeking = $wpdb->get_var("select seeking from $dsp_user_profiles where user_id='$user_id'");
if (isset($age_from) && isset($age_to)) {
    $age .= " ((year(CURDATE())-year(fb.age)) BETWEEN $age_from AND $age_to)  AND ";
} else
    $age = "";
$check_member_ids = $wpdb->get_results(" select member_id from $dsp_meet_me where user_id='$user_id'");
$ids = "";
if (count($check_member_ids) > 0) {
    foreach ($check_member_ids as $id) {
        $ids.=$id->member_id . ",";
    }
}

$conditions[0] =  !empty($ids) ? ' user_id not in (' . rtrim($ids, ',') . ') ' :'';
$conditions[1] = ($user_seeking != 'all') ? " gender='$user_seeking' " : '';

if(isset($cmbCountry)){
    $get_Country = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE name = '" . $cmbCountry . "'");
    $cmbCountryid = $get_Country->country_id;
}
$conditions[2] = !empty($cmbCountryid) ? " country_id = $cmbCountryid " : '';
$conditions[3] = (!empty($age_from) && !empty($age_to)) ? " ((year(CURDATE())-year(age)) BETWEEN $age_from AND $age_to) " : '';
$conditions = array_filter($conditions);
$query = " select *,(year(CURDATE())-year(age)) age from $dsp_user_profiles ";
if(!empty($conditions)){
    $query .= " WHERE " . implode(' AND user_id <> ' . $user_id. ' AND', $conditions);
}
$query .= "  LIMIT 1 ";
$profile_row = $wpdb->get_row($query);

$status = isset($profile_row) ? $profile_row->make_private : '';
@$user_image = ($status  == 'Y') ? $Mem_Image_path : display_members_photo_no_generic($profile_row->user_id, $imagepath);
if ($profile_row != null) {
        $user_login = $wpdb->get_var("SELECT user_login FROM $dsp_user_table where ID='$profile_row->user_id'");
        $user_country = $wpdb->get_var("SELECT name FROM $dsp_country_table where country_id='$profile_row->country_id'");
        if ($profile_row->state_id != 0)
            $user_state = $wpdb->get_var("SELECT name FROM $dsp_state_table where state_id='$profile_row->state_id'");
        if ($profile_row->city_id != 0)
            $user_city = $wpdb->get_var("SELECT name FROM $dsp_city_table where city_id='$profile_row->city_id'");
        ?>
        <div class="meet-to-info dsp-meet-to-info dspdp-text-center">
            <h1><?php echo language_code('DSP_MEET_ME_QUESTION') ?></h1>
            <div class="btn-row-meet dspdp-spacer-md"><input  id="dsp_meet_me_user" name="" type="hidden" value="<?php echo $profile_row->user_id; ?>" /><input id="dsp_meet_me_click" class="button yes dspdp-btn dspdp-btn-success btn" name="" type="button" value="<?php echo language_code('DSP_OPTION_YES') ?>" /> <input class="button no  dspdp-btn dspdp-btn-danger btn" id="dsp_meet_me_click" name="" type="button" value="<?php echo language_code('DSP_OPTION_NO') ?>" /></div>
            <div style="clear:both;"></div>
            <div class="meet-status"><?php $profile_row->my_status; ?></div>
            <div class="image-box dspdp-spacer-md dsp-meetme-image">
                <a href="<?php
                if ($profile_row->gender == 'C') {
                    echo $root_link . get_username($profile_row->user_id) . "/my_profile/";
                } else {
                    echo $root_link . get_username($profile_row->user_id) . "/";
                }
                ?>"><img id = "meet_me_image" src="<?php echo $user_image; ?>" alt="<?php echo $user_login;?> "/></a>
            </div>
            <div class="user-meetto-info dspdp-font-2x  dspdp-spacer-md dsp-meet-details">
                <div class="dspdp-text-info dsp-user-meet"><span class="dspdp-medium"><?php echo $user_login; ?></span>, 
                <?php echo $profile_row->age; ?></div>
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
   else {
    ?>
    <div class="thanks-note"><p class="error" style="text-align: center"><?php echo language_code('DSP_MEET_ME_NO_MORE') ?></p>
        <input type="button" value="New Search" class="dspdp-btn dspdp-btn-default" onclick="javascript:location.href = '<?php echo $root_link . "search/basic_search/"; ?>';" />
    </div>
<?php } ?>
