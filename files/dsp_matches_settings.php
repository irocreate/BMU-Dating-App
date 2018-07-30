<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ********************************  ACTIVE  DEACTIVE STATUS ************************************ //
global $wpdb;
$language_code = dsp_get_current_user_language_code();
if( $language_code == 'en' || $language_code == null || empty($language_code) )
    $dsp_profile_setup_table = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
else
    $dsp_profile_setup_table = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE . '_' . $language_code;

$request_url = $_SERVER['REQUEST_URI'];

if (isset($_GET['Action'])) {
    $profile_id = $_GET['ids'];
    $status = $_GET['ST'];
    if ($status == 'Y') {
        $update_status = 'N';
    } else {
        $update_status = 'Y';
    }
//$wpdb->query($wpdb->prepare("UPDATE $dsp_spam_words_table SET spam_word = '$dsp_spamwords' WHERE spam_word_id = '$spam_word_id'"));
    $wpdb->update($dsp_profile_setup_table, array('display_status' => $update_status), array(
        'profile_setup_id' => $profile_id), array('%s'), array('%d'));
    $sendback = remove_query_arg(array('Action', 'ids'), $request_url);
//wp_redirect($sendback);
}
?>

<?php
//------------------------- 
//To update the "default_match in "DSP Admin->Settings->Matches->Default Match".
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$page_name = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';

$default_match = isset($_REQUEST['default_match']) ? $_REQUEST['default_match'] : '';

if ($mode == 'update' && $page_name == 'matches_settings') {
    $wpdb->query("UPDATE $dsp_general_settings_table SET setting_status = 'Y',setting_value='$default_match' WHERE setting_name = 'default_match'");   
} //End if
//-End: update "default_match"-------------------------
?>

<div>
    <div id="general" class="postbox">
        <h3 class="hndle"><span><?php echo language_code('DSP_MATCHES'); ?></span></h3>
        <table class="dsp_thumbnails1" border="0" cellpadding="6" >
            <tr><td colspan="2">&nbsp;</td></tr>
            
                <!-- Set Default Match a/c to gender. (ie. man, woman, couples or all) -->
                <td class="form-field form-required">
                    <!--<p>Default Match: </p>-->
                    <?php echo language_code('DSP_MATCHES'); ?>
                </td>
                <td>
                    <?php //echo "settings_root_link: ".$settings_root_link;die;
                        //get the default_match
//                            $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
//                            echo $dsp_general_settings_table;die;
                        
                            // check default_match is Activated or not.
                            $check_default_match = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'default_match'");
                    ?>
                    <form name="frmgeneralsettings" method="post" action="<?php
                        echo add_query_arg(array(
                            'pid' => 'matches_settings', 'mode' => 'update'), $settings_root_link);
                        ?>">
                        
                        
                        <select name="default_match" style="width:85px;">                            
                            <option value="man" <?php echo ($check_default_match->setting_value == 'man') ?  ' selected="selected"' : ''; ?>><?php echo language_code('DSP_MALE'); ?></option>
                            <option value="woman" <?php echo ($check_default_match->setting_value == 'woman') ?  ' selected="selected"' : ''; ?>><?php echo language_code('DSP_FEMALE'); ?></option>
                            <option value="couples" <?php echo ($check_default_match->setting_value == 'couples') ?  ' selected="selected"' : ''; ?>><?php echo language_code('DSP_COUPLE'); ?></option>
                            <option value="all" <?php echo ($check_default_match->setting_value == 'all') ?  ' selected="selected"' : ''; ?>><?php echo language_code('DSP_ALL'); ?></option>                                                        
                        </select>
                        <input type="submit" name="Submit" value="<?php _e('Save Changes', 'dsp_trans_domain') ?>" class="button button-primary" />
                    </form>     
                </td>
            
                <!-- End: Default Match -->
                
            <?php
            $myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table WHERE field_type_id=1 AND required='Y' Order by sort_order");

            foreach ($myrows as $profile_questions) {

                $profile_que_id = $profile_questions->profile_setup_id;

                $profile_ques = $profile_questions->question_name;

                $display_status = $profile_questions->display_status;
                ?>

                <tr>

                    <td class="form-field form-required"><?php echo $profile_ques; ?></td>


                    <td class="form-field form-required"><a href="<?php
                                                            echo add_query_arg(array(
                                                                'Action' => 'update',
                                                                'ST' => $display_status,
                                                                'ids' => $profile_que_id), $request_url);
                                                            ?>"><?php
                                                                if ($display_status == 'Y') {
                                                                    echo language_code('DSP_ACTIVE');
                                                                } else {
                                                                    echo language_code('DSP_INACTIVE');
                                                                }
                                                                ?></a></td>

                </tr>

            <?php } ?>

        </table>
    </div>
</div>
<br />
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>