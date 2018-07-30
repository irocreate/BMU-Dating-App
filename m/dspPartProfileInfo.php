<?php
$user_id = $_REQUEST['user_id'];

$member_id = isset($_REQUEST['member_id']) ? $_REQUEST['member_id'] : '';

$review_date = date('Y-m-d');
$session_id = $user_id;

$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
$dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
$dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
$dsp_blocked_members_table = $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE;
$dsp_question_details = $wpdb->prefix . DSP_PARTNER_PROFILE_QUESTIONS_DETAILS;
$dsp_counter_hits_table = $wpdb->prefix . DSP_COUNTER_HITS_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;


$dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;

$all_languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where display_status='1' ");
$language_name = $all_languages->language_name;

if ($language_name == 'english') {

    $tableName1 = "dsp_profile_setup";

    $tableName = "dsp_question_options";
} else {

    $tableName1 = "dsp_profile_setup_" . substr($language_name, 0, 2);

    $tableName = "dsp_question_options_" . substr($language_name, 0, 2);
}

$dsp_question_options_table = $wpdb->prefix . $tableName;
$dsp_profile_setup_table = $wpdb->prefix . $tableName1;
?>

<?php
if (($user_id != $member_id)) {
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_counter_hits_table WHERE user_id=$user_id AND member_id=$member_id AND review_date='$review_date'");
    if (($count <= 0) && ($session_id != 0)) {
        $wpdb->query("INSERT INTO $dsp_counter_hits_table SET user_id=$user_id, member_id=$member_id, review_date='$review_date' ");
    }
}

$check_exist_profile_details = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_partner_profiles_table WHERE status_id=1 AND user_id = '$member_id'");

if ($check_exist_profile_details > 0) {
    $userName = $wpdb->get_var("SELECT display_name FROM $DSP_USERS_TABLE WHERE id =$member_id ");
    ?>


    <?php
    $exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_partner_profiles_table WHERE status_id=1 AND user_id = '$member_id'");
    // ------------------------------------START BLOCKED MEMBER -------------------------------------//
    $blocked_event = isset($_REQUEST['block_event']) ? $_REQUEST['block_event'] : '';

    if (($blocked_event == "blocked") && ($user_id != $member_id) && ($user_id != "")) {
        $check_block_mem_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blocked_members_table WHERE block_member_id='$member_id' AND user_id='$user_id'");

        if ($check_block_mem_exist <= 0) {

            $wpdb->query("INSERT INTO $dsp_blocked_members_table SET user_id = '$user_id',block_member_id ='$member_id'");
            $msg_blocked = language_code('DSP_MEMBER_BLOCKED_MESSAGE');
        } else {
            if ($user_id != "") {
                $msg_blocked = language_code('DSP_EXIST_IN_BLOCK_LIST_MSG');
            }
        }
    }

    if (isset($msg_blocked)) {
        ?>
        <div style="color:#FF0000;" align="center"><strong><?php echo $msg_blocked ?></strong></div>
        <?php
    }
    // ------------------------------------END  BLOCKED MEMBER -------------------------------------//
    // ----------------------------------Check member privacy Settings------------------------------------


    $check_user_privacy_settings = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE view_my_profile='Y' AND user_id='$member_id'");

    $check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'");


    if (($check_user_privacy_settings > 0) && ($user_id != $member_id)) {  // check user privacy settings
        if ($check_my_friends_list <= 0) {   // check member is not in my friend list
            ?>


            <div align="center"><?php echo language_code('DSP_NOT_MEMBER_FRIEND_MESSAGE'); ?></div>
            <?php
        } else {
            ?>

            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all ui-shadow">
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                    <div class="dsp_pro_full">
                        <span><?php echo language_code('DSP_I_AM') ?></span>
                        <?php if ($exist_profile_details->gender == 'F') {
                            ?>
                            <?php echo language_code('DSP_WOMAN') ?>
                            <?php
                        } else {
                            ?>	
                            <?php echo language_code('DSP_MAN') ?>
                        <?php } ?> 
                    </div>
                    <div class="dsp_pro_full">
                        <span><?php echo language_code('DSP_SEEKING_A') ?></span>
                        <?php if ($exist_profile_details->seeking == 'M') {
                            ?>
                            <?php echo language_code('DSP_MAN') ?>
                            <?php
                        } else {
                            ?>	
                            <?php echo language_code('DSP_WOMAN') ?>
                        <?php } ?>
                    </div>

                    <div class="dsp_pro_full">
                        <span><?php echo language_code('DSP_AGE') ?></span>
                        <?php echo GetAge($exist_profile_details->age); ?>
                </li>

            </ul>
            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all ui-shadow dsp_prof_ul">
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

                    <span><?php echo language_code('DSP_ABOUT_ME') ?>:</span>
                    <div class="details"><?php echo $exist_profile_details->about_me; ?></div></li>
                <?php
                $exist_profile_options_details2 = $wpdb->get_results("SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE A.field_type_id =2 AND B.user_id ='$member_id' ORDER BY A.sort_order");
                foreach ($exist_profile_options_details2 as $profile_qu12) {
                    $question_name = $profile_qu12->question_name;
                    $option_value = $profile_qu12->option_value;
                    ?>

                    <span><?php echo $question_name ?>:</span>
                    <div class="details"><?php echo $option_value ?></div>

                <?php } ?>	
            </ul>

            <?php
        }   // ------------------------------------------------- End if Check in my friend list --------------------------------- // 
    } else {
        ?>


        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all ui-shadow">
            <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                <div class="dsp_pro_full">
                    <span><?php echo language_code('DSP_I_AM') ?></span>
                    <?php if ($exist_profile_details->gender == 'F') {
                        ?>
                        <?php echo language_code('DSP_WOMAN') ?>
                        <?php
                    } else {
                        ?>	
                        <?php echo language_code('DSP_MAN') ?>
                    <?php } ?> 
                </div>
                <div class="dsp_pro_full">
                    <span><?php echo language_code('DSP_SEEKING_A') ?></span>
                    <?php if ($exist_profile_details->seeking == 'M') {
                        ?>
                        <?php echo language_code('DSP_MAN') ?>
                        <?php
                    } else {
                        ?>	
                        <?php echo language_code('DSP_WOMAN') ?>
                    <?php } ?>
                </div>

                <div class="dsp_pro_full">
                    <span><?php echo language_code('DSP_AGE') ?></span>
                    <?php echo GetAge($exist_profile_details->age); ?>
                </div>
            </li>

        </ul>
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all ui-shadow dsp_prof_ul">
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

                <span><?php echo language_code('DSP_ABOUT_ME') ?>:</span>
                <div class="details"><?php echo $exist_profile_details->about_me; ?></div>

                <?php
                $exist_profile_options_details2 = $wpdb->get_results("SELECT A . * , B . * FROM $dsp_profile_setup_table A INNER JOIN $dsp_question_details B ON ( A.profile_setup_id = B.profile_question_id ) WHERE A.field_type_id =2 AND B.user_id ='$member_id' ORDER BY A.sort_order");
                foreach ($exist_profile_options_details2 as $profile_qu12) {
                    $question_name = $profile_qu12->question_name;
                    $option_value = $profile_qu12->option_value;
                    ?>

                    <span><?php echo $question_name ?>:</span>
                    <div class="details"><?php echo $option_value ?></div>

                <?php } ?>	
        </ul>


        <?php
    }
} else {
    ?>
    <div align="center"><?php echo language_code('DSP_NO_PROFILE_EXISTS_MESSAGE'); ?></div>

    <?php
}
?>