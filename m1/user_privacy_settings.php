<div role="banner" class="ui-header ui-bar-a" data-role="header">
<a class="ui-btn-left ui-btn-corner-all ui-icon-back ui-btn-icon-notext ui-shadow"  onclick="viewSetting(0, 'setting')" href="#" >
            </a> 
    
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_SUBMENU_SETTINGS_PRIVACY'); ?></h1>
    <?php include_once('page_home.php')?>	
</div>

<?php 
$dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;


$view_my_pictures = isset($_REQUEST['view_my_pictures']) ? $_REQUEST['view_my_pictures'] : '';

$view_my_friends = isset($_REQUEST['view_my_friends']) ? $_REQUEST['view_my_friends'] : '';

$view_my_profile = isset($_REQUEST['view_my_profile']) ? $_REQUEST['view_my_profile'] : '';

$view_my_audio = isset($_REQUEST['view_my_audio']) ? $_REQUEST['view_my_audio'] : '';

$view_my_video = isset($_REQUEST['view_my_video']) ? $_REQUEST['view_my_video'] : '';

$update_mode = isset($_REQUEST['update_mode']) ? $_REQUEST['update_mode'] : '';



if (($update_mode == 'update') && ($user_id != "")) {

    $check_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE user_id='$user_id'");

    if ($check_user_exists > 0) {
        $wpdb->query("UPDATE $dsp_user_privacy_table SET view_my_pictures = '$view_my_pictures',view_my_profile='$view_my_profile',view_my_friends='$view_my_friends',view_my_audio='$view_my_audio',view_my_video='$view_my_video' WHERE user_id = '$user_id'");
    } else {
        $wpdb->query("INSERT INTO $dsp_user_privacy_table SET user_id = '$user_id',view_my_pictures = '$view_my_pictures',view_my_profile='$view_my_profile',view_my_friends='$view_my_friends',view_my_audio='$view_my_audio',view_my_video='$view_my_video'");
    }

    $settings_updated = true;
}

$member_privacy_settings = $wpdb->get_row("SELECT * FROM $dsp_user_privacy_table WHERE user_id = '$user_id'");
?>

<div class="ui-content" data-role="content">
    <div class="content-primary">	
       
                <?php
                if (isset($settings_updated) && $settings_updated == true) {
                    ?>

                    <div class="thanks success-message">
                       <?php echo language_code('DSP_SETTINGS_UPDATED'); ?>
                    </div>

                <?php } ?>

                <form name="dspAccount"   id="dspAccount">

                <fieldset>
                    <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_TEXT_CAN_VIEW_MY_PICTURES'); ?></div>

                       
                            <select name="view_my_pictures">
                                <?php
                                if ($member_privacy_settings->view_my_pictures == 'Y') {
                                    ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                    <option value="N"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="Y"><?php echo language_code('DSP_FRIENDS_OPTION'); ?></option>
                                    <option value="N"  selected="selected"><?php echo language_code('DSP_EVERYONE_OPTION'); ?></option>
                                <?php } ?>

                            </select></div>
                            </label>

                        <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_TEXT_CAN_VIEW_MY_FRIENDS'); ?></div>

                       
                            <select name="view_my_friends">
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
                        </div>
                        </label>

                        <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_TEXT_CAN_VIEW_MY_PROFILE'); ?></div>
          <select name="view_my_profile">
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
                            </div>
                            </label>

 <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_TEXT_CAN_VIEW_MY_AUDIO'); ?></div>

                        <select name="view_my_audio">

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
                            </div>
                            </label>

                         <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_TEXT_CAN_VIEW_MY_VIDEO'); ?></div>

                       <select name="view_my_video"> 
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
                            </div>
                            </label>
                        <div> <input type="hidden" name="update_mode" value="update" />
                            <input type="hidden" name="pagetitle" value="<?php echo $profile_pageurl; ?>" />
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                        </div>
                       <div class="btn-blue-wrap"><input onclick="viewSetting(0, 'post')" type="button" class="mam_btn btn-red" name="submit" value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>" /></div>
                </fieldset>
                </form>
    </div>	
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>