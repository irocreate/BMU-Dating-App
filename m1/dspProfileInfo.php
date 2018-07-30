<?php
//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->

//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');




$user_id = $_REQUEST['user_id'];
$session_id = $user_id;
$member_id = $_REQUEST['member_id'];




$review_date = date('Y-m-d');

$dsp_counter_hits_table = $wpdb->prefix . DSP_COUNTER_HITS_TABLE;
$dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_blocked_members_table = $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
$dsp_profile_setup_table = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
$dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;


$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE status_id=1 AND user_id = '$member_id'");

$userName = $wpdb->get_var("SELECT display_name FROM $DSP_USERS_TABLE WHERE id =$member_id ");
?>
<ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all ui-shadow">
    <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
        <div class="dsp_pro_full">
            <span><?php echo language_code('DSP_I_AM'); ?></span>

            <?php if ($exist_profile_details->gender == 'F') {
                ?>

                <?php echo language_code('DSP_WOMAN'); ?>

                <?php
            } else if ($exist_profile_details->gender == 'M') {
                ?>	

                <?php echo language_code('DSP_MAN'); ?>

                <?php
            } else {
                ?>	

                <?php echo language_code('DSP_COUPLE'); ?>

            <?php } ?> 
        </div>

        <div class="dsp_pro_full">
            <span><?php echo language_code('DSP_SEEKING_A'); ?></span>


            <?php if ($exist_profile_details->seeking == 'M') {
                ?>
                <?php echo language_code('DSP_MAN'); ?>
                <?php
            } else if ($exist_profile_details->seeking == 'F') {
                ?>	

                <?php echo language_code('DSP_WOMAN'); ?>

            <?php } else {
                ?>	

                <?php echo language_code('DSP_COUPLE'); ?>

            <?php } ?>

        </div>

        <div class="dsp_pro_full">
            <span><?php echo language_code('DSP_AGE'); ?></span>
            <?php echo GetAge($exist_profile_details->age); ?>
        </div>
        <?php if ($exist_profile_details->country_id != 0) {
            ?>
            <div class="dsp_pro_full">
                <span><?php echo language_code('DSP_COUNTRY'); ?></span>
                <?php
                $country = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$exist_profile_details->country_id");
                echo $country->name;
                ?>

            </div>
        <?php } ?>

        <?php if ($exist_profile_details->state_id != 0) {
            ?>
            <div class="dsp_pro_full">
                <span><?php echo language_code('DSP_TEXT_STATE'); ?></span>
                <?php
                $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$exist_profile_details->state_id");
                echo $state_name->name;
                ?>

            </div>
        <?php } ?>
        <?php if ($exist_profile_details->city_id != 0) {
            ?>
            <div class="dsp_pro_full">
                <span><?php echo language_code('DSP_CITY'); ?></span>
                <?php
                $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$exist_profile_details->city_id");
                echo $city_name->name;
                ?>
            </div>
        <?php } ?>
        <?php if ($check_zipcode_mode->setting_status == 'Y') {
            ?>
            <div class="dsp_pro_full">
                <span><?php echo language_code('DSP_ZIP'); ?></span>
                <?php echo $exist_profile_details->zipcode ?>
            </div>
        <?php } ?>	

    </li>

</ul>
<ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all ui-shadow dsp_prof_ul ">
    <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">	
        <?php
        $exist_profile_options_details1 = $wpdb->get_results("SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE A.field_type_id =1 AND B.user_id ='$member_id' ORDER BY A.sort_order");

        foreach ($exist_profile_options_details1 as $profile_qu1) {
            $question_name = $profile_qu1->question_name;

            $option_value = $profile_qu1->option_value;
            ?>

            <span><?php echo $question_name ?>:</span>
            <div class="details"><?php echo $option_value ?></div>

        <?php } ?>

        <span style="margin-top:10px;"><?php echo language_code('DSP_ABOUT_ME'); ?>:</span>
        <div class="details" style="width:100%; margin-bottom:10px;"><?php echo $exist_profile_details->about_me; ?></div>

        <?php
        $exist_profile_options_details2 = $wpdb->get_results("SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE A.field_type_id =2 AND B.user_id ='$member_id' ORDER BY A.sort_order");

        foreach ($exist_profile_options_details2 as $profile_qu12) {
            $question_name = $profile_qu12->question_name;

            $option_value = $profile_qu12->option_value;
            ?>



            <span ><?php echo $question_name ?>:</span>
            <div class="details" style="width:100%; margin-bottom:10px;"><?php echo $option_value ?></div>

        <?php } ?>

        <span ><?php echo language_code('DSP_MY_INTEREST'); ?>:</span>
        <div class="details" style="width:100%; margin-bottom:10px;"><?php echo $exist_profile_details->my_interest; ?></div>
    </li>

</ul>
