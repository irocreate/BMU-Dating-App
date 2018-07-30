<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_userplane_table = $wpdb->prefix . DSP_USERPLANE_TABLE;
@$goback = $_SERVER['HTTP_REFERER'];
$dsp_mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
//   $dsp_userplane_site_id=isset($_REQUEST['userplane_site_id']) ? $_REQUEST['userplane_site_id'] : '';
//  $dsp_userplane_api_key=isset($_REQUEST['userplane_api_key']) ? $_REQUEST['userplane_api_key'] : '';
$flashchat_script = isset($_REQUEST['flashchat_script']) ? htmlspecialchars($_REQUEST['flashchat_script']) : '';
$active_im = isset($_REQUEST['active_im']) ? $_REQUEST['active_im'] : '';
// $num_rows = $wpdb->get_row("SELECT * FROM $dsp_gateways_table order by userplane_id");
$num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_userplane_table");
//echo '<p> count is ' . $num_rows . '</p>';

if ($dsp_mode == 'add') {
    if ($num_rows == 0) {
        $wpdb->query("INSERT INTO $dsp_userplane_table( flashchat_script,active_im) VALUES ('$flashchat_script',  '$active_im')");
        //header("Location:".$goback);
        ?>
        <div id="message" class="updated fade"><p><strong>FlashChat details added.</strong></p></div>
        <?php
    } else {
        $userplane = $wpdb->get_row("SELECT * FROM $dsp_userplane_table order by userplane_id");
        $userplane_id = $userplane->userplane_id;
        $wpdb->update($dsp_userplane_table, array(/* 'userplane_site_id' => $dsp_userplane_site_id,'userplane_api_key' => $dsp_userplane_api_key, */
            'flashchat_script' => $flashchat_script,
            'active_im' => $active_im), array('userplane_id' => $userplane_id));
        //header("Location:".$goback);
        ?>
        <div id="message" class="updated fade"><p><strong>Userplane details Updated.</strong></p></div>
    <?php
    }
}
?>
<div id="general" class="postbox">
    <h3 class="hndle"><span><?php echo language_code('USERPLANE'); ?></span></h3>
    <table cellpadding="6" cellspacing="0" border="0" width="100%">
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <form name="gatewaysfrm" method="post">
                    <table cellpadding="0" cellspacing="0" border="0" class="dsp_thumbnails1">
                        <?php
                        $check_userplane = $wpdb->get_var("SELECT count(*) FROM $dsp_userplane_table order by userplane_id");
                        $userplane = $wpdb->get_row("SELECT * FROM $dsp_userplane_table order by userplane_id");
                        if ($check_userplane != 0) {
                            $userplane_site_id = $userplane->userplane_site_id;
                            $userplane_api_key = $userplane->userplane_api_key;
                            $flashchat_script = $userplane->flashchat_script;
                            $active_im = $userplane->active_im;
                        } else {
                            $userplane_site_id = "";
                            $userplane_api_key = "";
                            $flashchat_script = "";
                        }
                        ?>
                        <tr valign="top">
                            <td colspan="2" scope="row" class="form-field form-required"><label
                                    for="gatewayaddress"><?php _e(language_code('DSP_IM_SOLUTION')) ?></label>
                                <input type="hidden" name="im_select" value="flashchat"/></td>
                        </tr>
                        <tr valign="top" id="flashchat_row" style="display:<?php
                        if ($active_im == 'userplane') {
                            echo 'none';
                        } else {
                            echo 'block';
                        }
                        ?>;">
                            <td width="133" scope="row" class="form-field form-required"><label for="gatewayaddress">
                                    &nbsp;</label></td>
                            <td><textarea name="flashchat_script"
                                          style="height: 85px;width: 393px;"><?php echo $flashchat_script; ?></textarea>
                            </td>
                        </tr>
                        <tr valign="top" id="user_plane1" style="display:<?php
                        if ($active_im == 'userplane') {
                            echo 'block';
                        } else {
                            echo 'none';
                        }
                        ?>;">
                            <td width="133" scope="row" class="form-field form-required"><label
                                    for="gatewayaddress"><?php _e(language_code('DSP_SITE_ID')) ?></label></td>
                            <td><input type="text" name="userplane_site_id" value="<?php echo $userplane_site_id; ?>"
                                       class="regular-text"/></td>
                        </tr>
                        <tr valign="top" id="user_plane2" style="display:<?php
                        if ($active_im == 'userplane') {
                            echo 'block';
                        } else {
                            echo 'none';
                        }
                        ?>;">
                            <td width="133" scope="row" class="form-field form-required"><label
                                    for="gatewayaddress"><?php _e(language_code('DSP_API_KEY')) ?></label></td>
                            <td><input type="text" name="userplane_api_key" value="<?php echo $userplane_api_key; ?>"
                                       class="regular-text"/></td>
                        </tr>
                        <tr>
                            <td class="submit" align="left"><input type="hidden" name="mode" value="add"/>
                                <input id="active_im" type="hidden" name="active_im" value="flashchat"/>
                                <input type="submit" class="button  button-primary" value="<?php _e('Save') ?>"
                                       onclick="update_gateways();"/>
                            </td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
    </table>
</div>
<br/>
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>