<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

extract($_REQUEST);
$view_my_pictures = isset($_REQUEST['view_my_pictures']) ? $_REQUEST['view_my_pictures'] : '';
$view_my_friends = isset($_REQUEST['view_my_friends']) ? $_REQUEST['view_my_friends'] : '';
$view_my_profile = isset($_REQUEST['view_my_profile']) ? $_REQUEST['view_my_profile'] : '';
$view_my_audio = isset($_REQUEST['view_my_audio']) ? $_REQUEST['view_my_audio'] : '';
$view_my_video = isset($_REQUEST['view_my_video']) ? $_REQUEST['view_my_video'] : '';
$update_mode = isset($_REQUEST['update_mode']) ? $_REQUEST['update_mode'] : '';
if (($update_mode == 'update') && ($user_id != "")) {
    $contact_permission = array();
    $contact_permission['gender'] = implode(',', $privacy_gender);
    $contact_permission['profile_questions'] = implode(',', $profile_question_option_id);
    $check_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE user_id='$user_id'");
    if ($check_user_exists > 0) {
        $wpdb->query("UPDATE $dsp_user_privacy_table SET view_my_pictures = '$view_my_pictures',view_my_profile='$view_my_profile',view_my_friends='$view_my_friends',view_my_audio='$view_my_audio',view_my_video='$view_my_video',contact_permission='" . serialize($contact_permission) . "' WHERE user_id = '$user_id'");
    } else {
        $wpdb->query("INSERT INTO $dsp_user_privacy_table SET user_id = '$user_id',view_my_pictures = '$view_my_pictures',view_my_profile='$view_my_profile',view_my_friends='$view_my_friends',view_my_audio='$view_my_audio',view_my_video='$view_my_video',contact_permission='" . serialize($contact_permission) . "'");
    }
    $settings_updated = true;
}
$member_privacy_settings = $wpdb->get_row("SELECT * FROM $dsp_user_privacy_table WHERE user_id = $user_id");

$saved_contact_permission = unserialize($member_privacy_settings->contact_permission);
?>
<?php if (isset($settings_updated) && $settings_updated == true) { ?>
    <div class="thanks">
        <p align="center" class="error"><?php echo language_code('DSP_SETTINGS_UPDATED'); ?></p>
    </div>
<?php } ?>
<?php
//-------------------------------START PRIVACY SETTINGS ------------------------//

if (count($member_privacy_settings) == 0) {
    ?>
    <script>
        jQuery(document).ready(function(e) {
            jQuery("input[type=checkbox]").attr('checked', 'checked');
        });
    </script>
<?php } ?>

<div class="box-border">
    <div class="box-pedding">  
        <div class="heading-submenu dsp-none">
            <strong><?php echo language_code('DSP_PRIVACY_SETTING_TITLE'); ?></strong>
        </div>
        <span class="dsp-none"></br></br></span>
        <div class="dsp-form-container">
            <form name="frmprivacysettings" action="" method="post" class="dspdp-form-horizontal dsp-form-horizontal">
                <div class="setting-page-account">
                    <div class="dsp-box-container dsp-space">
                        <div class="heading margin-btm-2 dsp-block" style="display:none">
                            <h3><?php echo language_code('DSP_PRIVACY_SETTING_TITLE'); ?></h3>
                        </div>
                        <?php ?>
                            <div class="dspdp-form-group">
                                <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-4 dsp-sm-4">
                                    <?php echo language_code('DSP_TEXT_CAN_VIEW_MY_PICTURES'); ?>
                                </p>
                                <p class="dspdp-col-sm-6 dsp-sm-6">
                                    <select name="view_my_pictures" class="dspdp-form-control dsp-form-control">
                                        <?php
                                        if ($member_privacy_settings->view_my_pictures == 'Y') {
                                            ?>
                                            <option value="Y" selected="selected"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                            <option value="N"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                        <?php } else { ?>
                                            <option value="Y"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                            <option value="N"  selected="selected"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                        <?php } ?>
                                    </select>
                                </p>
                            </div>
        					
                            <div class="dspdp-form-group">
                                <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-4 dsp-sm-4">
                                    <?php echo language_code('DSP_TEXT_CAN_VIEW_MY_FRIENDS'); ?>
                                </p>
                        
        				        <p class="dspdp-col-sm-6 dsp-sm-6">
                                    <select name="view_my_friends" class="dspdp-form-control dsp-form-control">
                                        <?php
                                        if ($member_privacy_settings->view_my_friends == 'Y') {
                                            ?>
                                            <option value="Y" selected="selected"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                            <option value="N"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                        <?php } else { ?>
                                            <option value="Y"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                            <option value="N"  selected="selected"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                        <?php } ?>
                                    </select>
                                </p>
                            </div>
        					
        				    <div class="dspdp-form-group">	
                                <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-4 dsp-sm-4">
                                    <?php echo language_code('DSP_TEXT_CAN_VIEW_MY_PROFILE'); ?>
                                </p>
                                <p class="dspdp-col-sm-6 dsp-sm-6">
                                    <select name="view_my_profile" class="dspdp-form-control dsp-form-control">
                                        <?php
                                        if ($member_privacy_settings->view_my_profile == 'Y') {
                                            ?>
                                            <option value="Y" selected="selected"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                            <option value="N"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                        <?php } else { ?>
                                            <option value="Y"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                            <option value="N"  selected="selected"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                        <?php } ?>
                                    </select>
                                </p>
        					</div>
        					
            				<div class="dspdp-form-group">	
                                <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-4 dsp-sm-4"><?php echo language_code('DSP_TEXT_CAN_VIEW_MY_AUDIO'); ?></p>
                                <p class="dspdp-col-sm-6 dsp-sm-6">
                                    <select name="view_my_audio" class="dspdp-form-control dsp-form-control">
                                        <?php
                                        if ($member_privacy_settings->view_my_audio == 'Y') {
                                            ?>
                                            <option value="Y" selected="selected"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                            <option value="N"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                        <?php } else { ?>
                                            <option value="Y"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                            <option value="N"  selected="selected"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                        <?php } ?>
                                    </select>
                                </p>
            				</div>
        					
                            <div class="dspdp-form-group">
                                <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-4 dsp-sm-4">
                                    <?php echo language_code('DSP_TEXT_CAN_VIEW_MY_VIDEO'); ?>
                                </p>
                                <p class="dspdp-col-sm-6 dsp-sm-6">
                                    <select name="view_my_video" class="dspdp-form-control dsp-form-control"> 
                                        <?php
                                        if ($member_privacy_settings->view_my_video == 'Y') {
                                            ?>
                                            <option value="Y" selected="selected"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                            <option value="N"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                        <?php } else { ?>
                                            <option value="Y"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                            <option value="N"  selected="selected"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                        <?php } ?>
                                    </select>
                                </p>
                            </div>
                        
        				    <div class="select-gender dspdp-form-group">        
                                <p class="title-text dspdp-control-label dsp-control-label dspdp-col-sm-4 dsp-sm-4">
                                    Contact Permissions (Who can contact me?)
                                </p>
                                <?php /* ?><p><strong class="wid-gender-title"><?php echo language_code('DSP_GENDER')?></strong></p><?php */ ?>
                                <ul class="dspdp-col-sm-6 dsp-sm-6">
                                    <?php
                                    $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

                                    $dsp_gender_list = $wpdb->prefix . DSP_GENDER_LIST_TABLE;

                                    $check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");

                                    $query = "select * from $dsp_gender_list ";

                                    if ($check_couples_mode->setting_status == 'N') {

                                        $query.=" where enum!='C' ";
                                    }

                                    $gender_list = $wpdb->get_results($query);
                                    $selected_gender = explode(',', $saved_contact_permission['gender']);
                                    foreach ($gender_list as $gender_row) {
                                        if ($gender_row->editable == 'N') {
                                            if (@in_array($gender_row->enum, $selected_gender)) {
                                                echo '<li><input type="checkbox" name="privacy_gender[]" checked="checked" value="' . $gender_row->enum . '"> <span class="wid_dsp_gender">' . language_code($gender_row->gender) . '</span></li>';
                                            } else {
                                                echo '<li><input type="checkbox" name="privacy_gender[]" value="' . $gender_row->enum . '"> <span class="wid_dsp_gender">' . language_code($gender_row->gender) . '</span></li>';
                                            }
                                        } else {
                                            if (@in_array($gender_row->enum, $selected_gender)) {
                                                echo '<li><input type="checkbox" checked="checked" name="privacy_gender[]" value="' . $gender_row->enum . '"> <span class="wid_dsp_gender">' . $gender_row->gender . '</span</li>';
                                            } else {
                                                echo '<li><input type="checkbox" name="privacy_gender[]" value="' . $gender_row->enum . '"><span class="wid_dsp_gender">' . $gender_row->gender . '</span></li>';
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                                </div>
                        </div>
                        <div class="heading-submenu dsp-none"><?php echo language_code('DSP_PROFILE_QUESTIONS');?></div>
                        <div style="clear:both"></div>
                        <div class="dsp-border dsp-space">
                            <p class="dspdp-search-options">-->
                                <?php
                                $profiles_ques = explode(',', $saved_contact_permission['profile_questions']);
                                $dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
                                $all_languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where display_status='1' ");
                                $language_name = $all_languages->language_name;
                                if ($language_name == 'english') {
                                    $tableName1 = "dsp_profile_setup";

                                    $tableName = "dsp_question_options";
                                } else {
                                    $tableName1 = "dsp_profile_setup_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));

                                    $tableName = "dsp_question_options_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                                }
                                $dsp_question_options_table = $wpdb->prefix . $tableName;
                                $dsp_profile_setup_table = $wpdb->prefix . $tableName1;
                                $myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id=1 Order by sort_order");
                                foreach ($myrows as $profile_questions) {
                                    $ques_id = $profile_questions->profile_setup_id;
                                    $profile_ques = $profile_questions->question_name;
                                    $profile_ques_type_id = $profile_questions->field_type_id;
                                    ?>
                                <div class="text-title">
                                    <strong><?php echo $profile_ques; ?></strong>
                                </div>
                                <ul class="option-btn-adv dspdp-row">
                                    <?php
                                    $myrows_options = $wpdb->get_results("SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order");
                                    $i = 0;
                                    foreach ($myrows_options as $profile_questions_options) {
                                        if (($i % 3) == 0) {
                                            ?>
                                        <?php } ?>
                                        <li class="dspdp-col-sm-4 dsp-sm-4"><input type="checkbox" name="profile_question_option_id[]" <?php
                                            if (@in_array($profile_questions_options->question_option_id, $profiles_ques)) {
                                                echo 'checked="checked"';
                                            }
                                            ?> value="<?php echo $profile_questions_options->question_option_id ?>"/>&nbsp;<?php echo $profile_questions_options->option_value ?></li>
                                            <?php
                                            $i++;
                                        }
                                        ?>

                                </ul>
            					<span class="dspdp-seprator"></span>
                                <?php } ?>
                            </p>
                        </div> -->
                        <p><input type="hidden" name="update_mode" value="update" /></p>
                        <p><input type="submit" name="submit" value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>" class="dsp_submit_button dspdp-btn dspdp-btn-default"/></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
//------------------------------------- END PRIVACY SETTINGS  -------------------------------------// ?>