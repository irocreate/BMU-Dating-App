<?php
if (isset($_GET['error_msg'])) {
    $error_msg = filter_var(strip_tags($_GET['error_msg']), FILTER_SANITIZE_STRING);
    if ($error_msg != null) {
        if ($error_msg == 'invalid_username') {
            $error_msg = language_code('DSP_INVALID_USER_EMAIL_TEXT');
        } elseif ($error_msg == 'incorrect_password') {
            $error_msg = 'Password is Incorrect';
        }
    }
}
?>
<div style="display:none;">
    <div id="dialog" title="Login">
        <div class="dsp_login_main popup_login_frm">
            <?php if (isset($error_msg) && $error_msg != null): ?>
            <p class="dspdp-alert-warning">
                <?php echo $error_msg; ?>
            </p>
            <script>
                jQuery(document).ready(function(){                   
                    jQuery('.login-btn.dsp_submit_button.dspdp-btn').click();                   
                });
                jQuery(window).load(function(){                    
                    jQuery('.quick_action_section').find('.login-btn').click(); 
                });
            </script>
            <?php endif; ?>
            <form method="post" action="<?php bloginfo('url') ?>/wp-login.php"  >
                <div class="dsp_login_right popup_username_div"><span class="user_icon"></span><input type="text" name="log" placeholder="<?php _e(strtoupper(language_code('DSP_USERNAME'))); ?>" value=""  id="user_login" /></div>
                <div class="dsp_login_right popup_username_div"><span class="pass_icon"></span><input type="password" placeholder="<?php _e(strtoupper(substr(language_code('DSP_PASSWORD'), 0, -2))); ?>" name="pwd" value=""  id="user_pass"  /></div>
                <div class="dsp_login_left">&nbsp;</div>
                <a href="<?php echo get_bloginfo('wpurl') ?>/members/lost_password" rel="nofollow"><?php echo language_code('DSP_RESET_PASSWORD'); ?></a>
                <div class="dsp_login_right popup_btn_div"><?php do_action('login_form'); ?>
                    <input type="submit" name="user-submit" value="<?php _e(strtoupper(language_code('DSP_SUBMIT_BUTTON'))); ?>" tabindex="14" class="login-btn" />
                    <input type="hidden" name="redirect_to" value="<?php echo dsp_login_activated(); ?>" />
                   <input type="hidden" name="user-cookie" value="1" /></div>		 

            </form>
        </div>
    </div>
</div>

</div>
<?php
if (is_user_logged_in()) {
    $exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$user_id'");
    ?>
    <script>
        dsp = jQuery.noConflict();
        dsp(document).ready(function () {
            <?php if ($exist_profile_details->stealth_mode == 'Y') { ?>
            dsp("#menu-item-716").hide();
            <?php } ?>
        });
    </script>
<?php } ?>