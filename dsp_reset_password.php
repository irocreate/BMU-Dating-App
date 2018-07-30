<script>
    jQuery(document).ready(function(e) {
        jQuery("#reset_password").click(function() {
            jQuery(".notification,.error").remove();
            var keypass = jQuery("#pass").val();
            if (keypass == 0) {
                var user_id = jQuery("#user_id").val();
                var password = jQuery("#password").val();
                var cpassword = jQuery("#cpassword").val();
                if (jQuery.trim(password).length < 7) {
                    jQuery("#reset_password").after('<p style="display:none" class="error"><?php echo language_code('DSP_PASSWORD_CHARACTER_TEXT'); ?></p>');
                    jQuery(".lost-password-page .error").slideDown();
                    return false;
                }
                if (password != cpassword) {
                    jQuery("#reset_password").after('<p style="display:none" class="error"><?php echo language_code('DSP_PASSWORD_NOT_MATCH_TEXT'); ?></p>');
                    jQuery(".lost-password-page .error").slideDown();
                    return false;
                }
                else {
                    jQuery.ajax({
                        url: "<?php echo WPDATE_URL . "/dsp_change_password.php"; ?>?user_id=" + user_id + "&password=" + password,
                        cache: false,
                        dataType: 'json',
                        success: function(html) {
                            if (jQuery.trim(html['output']) == 1) {
                                jQuery("#reset_password").after('<p style="display:none" class="notification"><?php echo language_code('DSP_PASSWORD_CHANGED_SUCCESSFULLY_TEXT'); ?></p>');
                                jQuery(".lost-password-page .notification").slideDown();
                                jQuery("#password").val('');
                                jQuery("#cpassword").val('');
                            }else{
                                jQuery("#reset_password").after('<p style="display:none" class="notification"><?php echo language_code('DSP_CANT_SEND_RESET_PASSWORD_MSG'); ?></p>');
                                jQuery(".lost-password-page .notification").slideDown();
                                jQuery("#password").val('');
                                jQuery("#cpassword").val('');
                            }
                        }

                    });
                }




            }
            return false;
        });
    });

</script>
<?php
extract($_REQUEST);
$data = explode(',', base64_decode($key));
$check_pass_changed = $wpdb->get_var("SELECT count(*) FROM `$dsp_user_table` where user_pass like '" . $data[1] . "'");
?>
<div class="dsp_box-out f_left">
    <div class="dsp_box-in f_left">
        <div class="lost-password-page">
            <?php if ($check_pass_changed > 0) { ?><div style="margin-left:0px;" class="box-email-datails">
                    <strong><?php echo language_code('DSP_ENTER_PASSWORD_BELOW_TEXT'); ?></strong><br />
                    <form method="post" class="dspdp-form-inline">
                        <br/>
                        <label for="password">New Password</label>
                        <input type="password" class="dspdp-form-control dsp-form-control" id="password" /><br /><br />
                        <label for="cpassword">Confirm New Password</label>
                        <input type="password" class="dspdp-form-control dsp-form-control" id="cpassword" /><br /><br />
                        <input type="submit" class="dspdp-btn dspdp-btn-default" id="reset_password" value="<?php echo language_code('DSP_RESET_PASSWORD'); ?>" />
                        <input type="hidden" id="pass" value="<?php
                        if (isset($key))
                            echo '0';
                        else
                            echo '1';
                        ?>" />
                        <input type="hidden" id="user_id" value="<?php echo $data[0]; ?>" />
                    </form>

                </div><br />
                <p class="hint"><?php echo language_code('DSP_PASSWOR_NOTE'); ?></p>
            <?php }else { ?>
                <p class="error"><?php echo language_code('DSP_RESET_LINK_EXPIRED_TEXT'); ?></p>
            <?php } ?></div>
    </div>
</div>                                    