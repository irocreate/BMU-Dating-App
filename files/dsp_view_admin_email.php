<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$admin_mail_ID = $_GET['mail_id'];
$dsp_admin_emails_table = $wpdb->prefix . dsp_admin_emails;
$view_admin_email = $wpdb->get_row("SELECT * FROM $dsp_admin_emails_table Where admin_mail_id='$admin_mail_ID'");
$receiver_id = $view_admin_email->rec_user_id;
$rec_name = $wpdb->get_row("SELECT * FROM $dsp_user_table Where ID='$receiver_id'");
?>
<form name="frmupdate_memprofile" action="" method="post">
    <table cellpadding="0" cellspacing="0" border="0" width="700" bgcolor="#99CCFF" style="padding-left:20px;">
        <tr><td><strong><?php //$rec_name->display_name         ?></strong></td></tr>
        <tr><td>
                <table cellpadding="0" cellspacing="0" border="1" width="500" bgcolor="#99CCFF">
                    <tr><td height="20px" colspan="4">&nbsp;</td></tr>
                    <tr><td  colspan="4"><?php echo nl2br($view_admin_email->message); ?></td></tr>
                </table>
            </td></tr>
    </table>
</form>