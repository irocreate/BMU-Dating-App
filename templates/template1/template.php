<?php ######## Template header Sections ######### ?>
<?php include_once( WP_DSP_ABSPATH . 'templates/layouts/header.php' ); ?>

<script type='text/javascript' src='<?php echo WPDATE_URL . 'extra/lavish/js/plugins.js'; ?>'></script>
<script>
    jQuery(document).ready(function () {
        jQuery(window).resize();
        jQuery(".dsp-selectbox").selectbox();
    });
</script>
<link id="lavish-date-plugin-css" media="all" type="text/css"
      href="<?php echo WPDATE_URL . 'extra/lavish/css/plugin.css'; ?>" rel="stylesheet">
<link id="lavish-date-snippet-css" media="all" type="text/css"
      href="<?php echo WPDATE_URL . 'extra/lavish/css/snippet.css'; ?>" rel="stylesheet">

<?php the_widget( 'dsp_dating_search_widget' ); ?>
<?php if ( ! is_user_logged_in() ) { ?>
<div class="lavish-seachbox quick_action_section">
    <div class="dsp-filter-container">
        <div class="dsp-join-searchbox">
            <div class="container">
                <div class="dsp-row wp_dating_search_form">
                    <div class="registration-wrap">
                        <div class="login-wrap">
                            <input class="login-btn" type="submit"
                                   value="<?php echo strtoupper( language_code( 'DSP_LOGIN' ) ); ?>" name="submit">
                        </div>

                        <div class="reg-wrap">
                            <input class="reg_popoup" type="submit"
                                   value="<?php echo strtoupper( language_code( 'DSP_DZONIA_REGISTER_JOIN' ) ); ?>"
                                   name="submit">
                        </div>
						<?php } ?>
						<?php if ( ! is_user_logged_in() ) { ?>
                            <div class="facebook-login">
								<?php
								do_action( 'wpdating_facebook_login' );
								?>
                            </div>
						<?php } ?>
						<?php if ( ! is_user_logged_in() ) { ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<?php if ( is_user_logged_in() ) { ?>
    <div class="lavish-seachbox quick_action_section">
        <div class="dsp-filter-container">
            <div class="dsp-join-searchbox">
                <div class="container">
                    <div class="dsp-row wp_dating_search_form">
                        <div class="registration-wrap">
                            <div class="login-wrap">
                                <input id="quick_action_logout" type="submit"
                                       value="<?php echo language_code( 'DSP_LOGOUT' ); ?>" name="submit">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="join-freeboxx" style="display:none;">
    <div class="join-info">
        <div class="join-box join-freebox">
            <script>
                dsp = jQuery.noConflict();
                dsp(document).ready(function () {

                    dsp('#quick_action_logout').click(function () {
                        var redirect_location = decodeURIComponent('<?php echo wp_logout_url( dsp_login_activated() ); ?>');
                        redirect_location = redirect_location.replace('&amp;', '&');
                        var logout_location = '<?php echo wp_logout_url();?>';
                        logout_location = logout_location.replace('&amp;', '&');
                        window.location = logout_location;
                        return false;
                    });

                    dsp(".login-btn").click(function () {
                        dsp("#dialog").dialog({height: 344, width: 412});
                    });

                    dsp('#register').click(function () {

                        var alert_text = "";

                        var username = dsp('#username').val();

                        if (jQuery.trim(username).length == 0) {
                            alert_text += "<?php echo language_code( 'DSP_PLEASE_ENTER_USER_NAME' ); ?>\n";
                        }
                        var email = dsp('input[name="email"]').val();
                        if (jQuery.trim(email).length == 0) {
                            alert_text += "<?php echo language_code( 'DSP_PLEASE_ENTER_EMAIL_ADDRESS' ); ?>\n";
                        }
                        var confirm_email = dsp('input[name="confirm_email"]').val();
                        if (jQuery.trim(email).length == 0) {
                            alert_text += "<?php echo language_code( 'DSP_PLEASE_ENTER_CONFIRM_EMAIL_ADDRESS' ); ?>\n";
                        }
						<?php if ($check_terms_page_mode->setting_status == 'Y') { ?>
                        if (!dsp('input[name="terms"]').is(':checked')) {
                            alert_text += "<?php echo language_code( 'DSP_CHECK_TERM_CONDITIONS' ); ?>\n";
                        }
						<?php } ?>

                        if (alert_text != "") {
                            alert(alert_text);
                            return false;
                        }
                        document.regfrm.submit();
                    });
                });
            </script>
			<?php ######### register form  section ############ ?>
			<?php include_once( WP_DSP_ABSPATH . 'templates/layouts/register_form.php' ); ?>
			<?php ######## end register form ############# ?>
        </div>
    </div>
</div>
<div class="home-page  dsp-template-1" id="wpdp-home">
    <div class="home-container">
        <div class="user-info-home">

			<?php
			// New member section
			if ( is_array( $member_elements_values ) && in_array( 'N', $member_elements_values ) ):
				include_once( WP_DSP_ABSPATH . 'templates/layouts/new_member.php' );
			endif;
			if ( is_array( $member_elements_values ) && in_array( 'F', $member_elements_values ) ):
				include_once( WP_DSP_ABSPATH . 'templates/layouts/featured_member.php' );
			endif;
			?>
            <div class="fullwidth dspdp-row">
				<?php
				// online member  section
				if ( is_array( $member_elements_values ) && in_array( 'O', $member_elements_values ) ):
					include_once( WP_DSP_ABSPATH . 'templates/layouts/online_members.php' );
				endif;
				?>
				<?php
				// Happy Stories  section/
				if ( is_array( $member_elements_values ) && in_array( 'H', $member_elements_values ) ):
					include_once( WP_DSP_ABSPATH . 'templates/layouts/happy_stories.php' );
				endif;
				?>

				<?php
				// Latest Blogs  section
				if ( is_array( $member_elements_values ) && in_array( 'L', $member_elements_values ) ):
					include_once( WP_DSP_ABSPATH . 'templates/layouts/latest_blogs.php' );
				endif;
				?>
            </div>
        </div>
    </div>
	<?php
	//Login form  section
	include_once( WP_DSP_ABSPATH . 'templates/layouts/login_form.php' );
	?>