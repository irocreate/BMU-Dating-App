<script>
    jQuery(document).ready(function(e) {
        jQuery("#get-password").click(function() {
            jQuery("#loading_reset").show();
            jQuery(".notification,.error").slideUp(function() {
                jQuery(".notification,.error").remove();
            });

            var user_n_email = jQuery("#user_n_email").val(),
                ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>",
                ajaxnonce = "<?php echo wp_create_nonce( "email-verification-nonce" ) ?>";
            jQuery.ajax({
                type: "POST",
                url: ajaxurl + "?action=dsp_verify_email&_wpnonce="+ajaxnonce,
                data: {email:user_n_email},
                dataType: 'json',
                success: function(html) {
                    if (jQuery.trim(html['output']) == 1) {
                        jQuery(".lost-password-page").append('<p style="display:none" class="notification"><?php echo language_code('DSP_CHECK_EMAIL_CONFIRMATION_LINK_TEXT'); ?></p>');
                        jQuery(".lost-password-page .notification").slideDown();
                        jQuery("#user_n_email").val('');
                    }
                    else {

                        jQuery(".lost-password-page").append('<p style="display:none" class="error"><?php echo language_code('DSP_INVALID_USER_EMAIL_TEXT'); ?></p>');
                        jQuery(".lost-password-page .error").slideDown();
                    }
                    jQuery("#loading_reset").hide();
                }
            });
            return false;
        });
    });

</script>
<div class="box-border">
    <div class="box-pedding">
        <div class="lost-password-page">
            <p class="dspdp-font-2x"><strong><?php echo language_code('DSP_LOST_PASSWORD_INSTRUCTIONS'); ?></strong></p>
			<span class="dspdp-seprator"></span>
            <div class="box-email-datails">
                <strong id="usernemail"><?php echo language_code('DSP_USERNAME__EMAIL'); ?></strong><br />
                <form method="post" class="dspdp-form-inline">
                    <input type="text" id="user_n_email" class="dspdp-form-control" /><img id="loading_reset" style="display:none;width: 22px;padding-left: 2px;" src="<?php echo WPDATE_URL . '/images/loading.gif' ?>" alt="Loading" /> 
                    <input type="submit" class="dspdp-btn dspdp-btn-default" id="get-password" value="<?php echo language_code('DSP_GET_PASSWORD'); ?>" />
                </form>
            </div>


        </div>

    </div>
</div>