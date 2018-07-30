<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
//  ########################################  UPDATE Email tempate body ##################################### //
$goback = $_SERVER['HTTP_REFERER'];
$dsp_action = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$txtmailbody = isset($_REQUEST['txtmailbody']) ? $_REQUEST['txtmailbody'] : '';
$subject = isset($_REQUEST['dsp_mail_subject']) ? $_REQUEST['dsp_mail_subject'] : '';
$get_mail_template_id = isset($_REQUEST['Mail_temp_ID']) ? $_REQUEST['Mail_temp_ID'] : '';
if ($dsp_action == "update") {
    if (!empty($txtmailbody)) {

        $wpdb->query("UPDATE $dsp_email_templates_table SET subject='$subject',email_body='$txtmailbody' WHERE mail_template_id = '$get_mail_template_id'");
        $template_updated = true;
    }
    $sendback = remove_query_arg(array('Action', 'Mail_temp_ID'), $goback);
    // echo "Flirt text updated! <a href='$sendback'>Click here</a> to View List";
}

if (isset($template_updated) && $template_updated == true) {
    ?>
    <div id="message" class="updated fade"><strong>Template Updated.</strong></div>
<?php } ?>
<div id="general" class="postbox">
    <h3 class="hndle"><span><?php echo language_code('DSP_TOOL_EMAIL_TEMPLATE') ?></span></h3>
    <table cellpadding="0" cellspacing="0" border="0">
        <tr><td>&nbsp;</td></tr>
        <tr><td>
                <table cellpadding="6" cellspacing="0" width="500px" style="padding-left:20px;">
                    <?php
                    $myrows = $wpdb->get_results("SELECT * FROM $dsp_email_templates_table Order by mail_template_id");
                    foreach ($myrows as $email_templates) {
                        $mail_template_id = $email_templates->mail_template_id;
                        $email_template_name = $email_templates->email_template_name;
                        ?>
                        <tr>
                            <td width="250px"><strong><?php echo $email_template_name; ?></strong></td>
                            <td width="20px" class="span_pointer"><div onclick="update_email_template_text(<?php echo $mail_template_id ?>);"><?php echo "Edit"; ?></div></td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php
                    }  //  foreach ($myrows as $email_templates) 
                    ?>
                </table>
            </td></tr>

        <tr><td>&nbsp;</td></tr>

        <tr><td>
                <table cellpadding="0" cellspacing="0" class="dsp_thumbnails1" style="padding-left:20px;">
                    <tr><td colspan="3">&nbsp;</td></tr>
                    <tr><td>
                            <?php
                            if (isset($_REQUEST['Action']) && $_REQUEST['Action'] == 'update') {
                                $mode = 'update';
                                $get_mail_template_id = isset($_REQUEST['Mail_temp_ID']) ? $_REQUEST['Mail_temp_ID'] : '';
                                $dsp_updates = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id = $get_mail_template_id");
                                $dsp_updates->subject;
                                $dsp_updates->email_body;
                            } else {
                                $mode = 'add';
                            }
                            ?>
                            <FORM  name="frmemailtemplates" method="post">
                                <table border="0" cellpadding="0" cellspacing="0" width="500px">
                                    <tr>
                                        <td><strong><?php echo "Subject:"; ?></strong></td>
                                        <td colspan="2">
                                            <input type="text" name="dsp_mail_subject" id="subjectid" value="<?php if (isset($_REQUEST['Action'])) echo $dsp_updates->subject; ?>" style="width:300px;" class="regular-text" />
                                        </td>
                                    </tr>

                                    <tr><td colspan="3">&nbsp;</td></tr>
                                    <tr>
                                        <td><strong><?php echo "Email Body:"; ?></strong></td>
                                        <td colspan="2"><textarea name="txtmailbody" id="mailbodyid" rows="8" style="width:300px;"/><?php if (isset($_REQUEST['Action'])) echo $dsp_updates->email_body; ?></textarea></td>
                                    </tr>
                                    <tr><td colspan="3">&nbsp;</td></tr>
                                    <tr>
                                        <td><input type="hidden" name="mode" value="<?php echo $mode ?>" /></td>
                                        <td colspan="2"><input type="button" class="button dspdp-btn dspdp-btn-sm dspdp-btn-default" name="save" value="Save"  onclick="add_email_template_text();"/>&nbsp;<input type="button" class="button dspdp-btn dspdp-btn-sm dspdp-btn-default" name="cancel" value="Cancel"  onclick="dsp_reset_fields();"/></td>
                                    </tr>

                                </table>
                            </FORM>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</div>
<br />
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>