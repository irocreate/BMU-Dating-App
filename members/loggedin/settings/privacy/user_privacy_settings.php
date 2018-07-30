<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$current_user = wp_get_current_user();
extract($_REQUEST);
$private_messages = isset($_REQUEST['private_messages']) ? $_REQUEST['private_messages'] : '';
$friend_requests = isset($_REQUEST['friend_requests']) ? $_REQUEST['friend_requests'] : '';
$view_my_pictures = isset($_REQUEST['view_my_pictures']) ? $_REQUEST['view_my_pictures'] : '';
$view_my_friends = isset($_REQUEST['view_my_friends']) ? $_REQUEST['view_my_friends'] : '';
$view_my_profile = isset($_REQUEST['view_my_profile']) ? $_REQUEST['view_my_profile'] : '';
$view_my_audio = isset($_REQUEST['view_my_audio']) ? $_REQUEST['view_my_audio'] : '';
$view_my_video = isset($_REQUEST['view_my_video']) ? $_REQUEST['view_my_video'] : '';
//$trending_status = isset($_REQUEST['trending_status']) ? $_REQUEST['trending_status'] : '';
$update_mode = isset($_REQUEST['update_mode']) ? $_REQUEST['update_mode'] : '';
if (($update_mode == 'update') && ($user_id != "")) {
    $contact_permission = array();
    $contact_permission['gender'] = implode(',', $privacy_gender);
    $contact_permission['profile_questions'] = isset($profile_question_option_id) ? implode(',', $profile_question_option_id) : '';
    $check_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE user_id='$user_id'");
    if ($check_user_exists > 0) {
        $wpdb->query("UPDATE $dsp_user_privacy_table SET view_my_pictures = '$view_my_pictures',view_my_profile='$view_my_profile',view_my_friends='$view_my_friends',view_my_audio='$view_my_audio',view_my_video='$view_my_video',contact_permission='" . serialize($contact_permission) . "' WHERE user_id = '$user_id'");
    } else {
        $wpdb->query("INSERT INTO $dsp_user_privacy_table SET user_id = '$user_id',view_my_pictures = '$view_my_pictures',view_my_profile='$view_my_profile',view_my_friends='$view_my_friends',view_my_audio='$view_my_audio',view_my_video='$view_my_video',contact_permission='" . serialize($contact_permission) . "'");
    }
    $check_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_notification_table WHERE user_id='$user_id'");
    if ($check_user_exists > 0) {
        $wpdb->query("UPDATE $dsp_user_notification_table 
                        SET private_messages='$private_messages',
                            friend_request='$friend_requests'
                            WHERE user_id = '$user_id'"
                    );
    } else {
        $wpdb->query("INSERT INTO $dsp_user_notification_table 
                            SET user_id = '$user_id',
                                private_messages = '$private_messages',
                                friend_request='$friend_requests'
                    ");
    }
    $settings_updated = true;
}
$member_privacy_settings = $wpdb->get_row("SELECT * FROM $dsp_user_privacy_table WHERE user_id = $user_id");
$member_notification_settings = $wpdb->get_row("SELECT `private_messages`,`friend_request` FROM $dsp_user_notification_table WHERE user_id = '$user_id'");
$saved_contact_permission = unserialize(isset($member_privacy_settings->contact_permission) ? $member_privacy_settings->contact_permission : '');
?>
<?php if (isset($settings_updated) && $settings_updated == true) { ?>
    <div class="thanks">
        <p align="center" class="dspdp-text-success"><?php echo language_code('DSP_SETTINGS_UPDATED'); ?></p>
    </div>
<?php } ?>
<?php
//-------------------------------START PRIVACY SETTINGS ------------------------//

if ($member_privacy_settings != null) {
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
                                <?php echo language_code('DSP_TEXT_PRIVATE_MESSAGES'); ?>
                            </p>
                            <p class="dspdp-col-sm-6 dsp-sm-6">
                                <select name="private_messages" class="dspdp-form-control dsp-form-control">
                                    <?php
                                    if ($member_notification_settings->private_messages == 'N') {
                                        ?>
                                        <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                        <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                    <?php } else { ?>
                                        <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                        <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                    <?php } ?>
                                </select>
                            </p>
                        </div>                      
                        
                    <div class="dspdp-form-group ">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-4 dsp-sm-4">
                            <?php echo language_code('DSP_TEXT_FRIEND_REQUESTS'); ?>
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="friend_requests" class="dspdp-form-control dsp-form-control">
                                <?php
                                if ($member_notification_settings->friend_request == 'N') {
                                    ?>
                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } else { ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                                    <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                                <?php } ?>
                            </select>
                        </p>
                    </div>
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
                                            <option value="O"><?php echo language_code('DSP_ONLY_OPTION'); ?></option>
                                        <?php } else if ($member_privacy_settings->view_my_profile == 'N'){ ?>
                                            <option value="Y"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                            <option value="N"  selected="selected"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                            <option value="O"><?php echo language_code('DSP_ONLY_OPTION'); ?></option>
                                        <?php } else if ($member_privacy_settings->view_my_profile == 'O'){ ?>
                                            <option value="Y"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                            <option value="N"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                            <option value="O" selected="selected"><?php echo language_code('DSP_ONLY_OPTION'); ?></option>?>
                                        <?php } else { ?>
                                            <option value="Y"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                            <option value="N" selected="selected"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                            <option value="O"><?php echo language_code('DSP_ONLY_OPTION'); ?></option>?>?>
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
                            <!-- <div class="dspdp-form-group">
                                <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-4 dsp-sm-4">
                                    <?php echo language_code('DSP_TEXT_ENABLE_TRENDING_FEATURE'); ?>
                                </p>
                                <p class="dspdp-col-sm-6 dsp-sm-6">
                                    <select name="trending_status" class="dspdp-form-control dsp-form-control">
                                        <?php
                                        if ($member_privacy_settings->trending_status == 'Y') {
                                            ?>
                                            <option value="Y" selected="selected"><?php echo language_code('DSP_ON'); ?></option>
                                            <option value="N"><?php echo language_code('DSP_OFF'); ?></option>
                                        <?php } else { ?>
                                            <option value="Y"><?php echo language_code('DSP_ON'); ?></option>
                                            <option value="N"  selected="selected"><?php echo language_code('DSP_OFF'); ?></option>
                                        <?php } ?>
                                    </select>
                                </p>
                            </div> -->
                        
        				    <div class="select-gender dspdp-form-group">        
                                <p class="title-text dspdp-control-label dsp-control-label dspdp-col-sm-4 dsp-sm-4">
                                    <?php echo language_code('DSP_CONTACT_PERMISSIONS'); ?>
                                </p>
                                <?php /* ?><p><strong class="wid-gender-title"><?php echo language_code('DSP_GENDER')?></strong></p><?php */ ?>
                                <ul class="dspdp-col-sm-6 dsp-sm-6">
                                    <?php
                                    $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

                                    $dsp_gender_list = $wpdb->prefix . DSP_GENDER_LIST_TABLE;

                                    $check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");
                                    $check_male_mode  =    $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'male'");
                                    $check_female_mode  =  $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'female'");
                               //     print_r($check_male_mode);

                                    $gender = array();

                                    if($check_male_mode->setting_status == 'Y')
                                    {
                                        array_push($gender, 'M');
                                    }    
                                    if($check_female_mode->setting_status == 'Y')
                                    {
                                        array_push($gender, 'F');
                                    }

                                    if($check_couples_mode->setting_status == 'Y')
                                    {
                                        array_push($gender, 'C');
                                    }
                              
                                    $query = "select * from $dsp_gender_list ";


                                    // if ($check_couples_mode->setting_status == 'N') {

                                    //     $query.=" where enum!='C' ";
                                    // }


                                    $new_gender_list = array();

                                    $gender_list = $wpdb->get_results($query);

                                    $i = 0; 

                                    foreach ($gender_list as $key=>$value) {

                                        if(in_array($value->enum, $gender)){

                                            $new_gender_list[$i] = new stdClass();

                                            $new_gender_list[$i]->id = $value->id;
                                            $new_gender_list[$i]->gender = $value->gender;
                                            $new_gender_list[$i]->enum = $value->enum;
                                            $new_gender_list[$i]->editable = $value->editable;

                                        }
                            
                                     $i++;
                                    }
                                    $gender_list = $new_gender_list;
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
                        
                        </div> 
                        <p><input type="hidden" name="update_mode" value="update" /></p>
                        <p><input type="submit" name="submit" value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>" class="dsp_submit_button dspdp-btn dspdp-btn-default"/></p>
                </div>
            </form>
        </div>
    </div>

<?php
//------------------------------------- END PRIVACY SETTINGS  -------------------------------------// ?>