<?php
include_once( WP_DSP_ABSPATH . "include_dsp_tables.php" );
$dsp_country_table          = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
@session_start();
$pluginpath         = str_replace( str_replace( '\\', '/', ABSPATH ), get_option( 'siteurl' ) . '/', str_replace( '\\', '/', dirname( __FILE__ ) ) ) . '/';  // Plugin Path
$path               = $pluginpath . 'image.php';
$check_couples_mode = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'" );
if ( is_user_logged_in() ) {  // CHECK MEMBER LOGIN
?>
<form name="frmquicksearch" id="frmquicksearch" class="dspdp-col-sm-12" method="GET"
      action="<?php echo ROOT_LINK . 'search/search_result/basic_search/basic_search' ?>">
    <input type="hidden" name="pid" value="5"/>
    <input type="hidden" name="pagetitle" value="search_result"/>
	<?php } else { ?>
    <form name="frmquicksearch" id="frmquicksearch" class="dspdp-col-sm-12" method="GET"
          action="<?php echo ROOT_LINK . 'g_search_result/' ?>">
		<?php } ?>
        <input type="hidden" name="Pictues_only" value="P"/>
        <div class="dspdp-form-horizontal dsp-form-horizontal">
            <div class="dspdp-form-group dsp-form-group">
                <div class="dspdp-col-sm-2 dsp-sm-2">
                    <span class="dspdp-control-label dsp-control-label"><?php echo language_code( 'DSP_I_AM' ); ?></span>
                    <select name="gender" class="dsp-form-control dspdp-form-control">
						<?php
						$gender = $userProfileDetailsExist ? $userProfileDetails->gender : '';
						echo get_gender_list( $gender );
						?>
                    </select>
                </div>
                <div class="dspdp-col-sm-2">
                    <span class="dspdp-control-label"><?php echo language_code( 'DSP_SEEKING_A' ); ?></span>
                    <select name="seeking" class="dsp-form-control dspdp-form-control">
						<?php
						$seeking = $userProfileDetailsExist ? $userProfileDetails->seeking : 'F';
						echo get_gender_list( $seeking );
						?>
                    </select>
                </div>
                <div class="dspdp-col-sm-1">
                    <span class="dspdp-control-label"><?php echo language_code( 'DSP_AGE' ); ?></span>
                    <select name="age_from" class="dspdp-form-control">
						<?php
						for ( $fromyear = 18; $fromyear <= 99; $fromyear ++ ) {
							if ( $fromyear == 18 ) {
								?>
                                <option value="<?php echo $fromyear ?>"
                                        selected="selected"><?php echo $fromyear ?></option>
							<?php } else { ?>
                                <option value="<?php echo $fromyear ?>"><?php echo $fromyear ?></option>
								<?php
							}
						}
						?>
                    </select>
                </div>
                <div class="dspdp-col-sm-1 dsp-sm-1"><span
                            class="dspdp-control-label dsp-control-label"> <?php echo language_code( 'DSP_TO' ); ?> </span>
                    <select name="age_to" class="dspdp-form-control dsp-form-control">
						<?php
						for ( $toyear = 18; $toyear <= 99; $toyear ++ ) {
							if ( $toyear == 99 ) {
								?>
                                <option value="<?php echo $toyear ?>" selected="selected"><?php echo $toyear ?></option>
							<?php } else { ?>
                                <option value="<?php echo $toyear ?>"><?php echo $toyear ?></option>
								<?php
							}
						}
						?>
                    </select>
                </div>

				<?php if ( $isDistanceModeOn ): ?>
                    <div class="dspdp-col-sm-2 dsp-sm-2">
                        <span class="dspdp-control-label dsp-control-label"><?php echo language_code( 'DSP_SELECT_DISTANCE' ); ?></span>
                        <input name="distance" type="text" class="dspdp-form-control dsp-form-control"/>
                    </div>
                    <div class="dspdp-col-sm-2 dsp-sm-2">
                        <span class="dspdp-control-label dsp-control-label"><?php echo language_code( 'DSP_UNIT' ); ?></span>
                        <select name="unit" class="dspdp-form-control dsp-form-control">
                            <option value="0"><?php echo language_code( 'DSP_SELECT_UNIT' ); ?></option>
							<?php
							$options = array(
								3959 => language_code( 'DSP_MILES' ),
								6371 => language_code( 'DSP_KM' )
							);
							foreach ( $options as $key => $option ) {
								?>
                                <option value="<?php echo $key; ?>"><?php echo $option; ?></option>
							<?php } ?>
                        </select>
                    </div>
				<?php endif; ?>
                <div class="dspdp-col-sm-2 dsp-sm-2">
                    <span class="dspdp-control-label dsp-control-label"><?php echo language_code( 'DSP_COUNTRY' ); ?></span>
					<?php $placeholder = $isDistanceModeOn ? language_code( 'DSP_SEARCH_BY_PLACE_ZIPCODE_COUNTRY' ) : language_code( 'DSP_COUNTRY' ); ?>
                    <input id="autocomplete" name="zip_code" type="text" class="dspdp-form-control dsp-form-control"
                           placeholder="<?php echo $placeholder; ?>"/>
                </div>
                <div class="dspdp-col-sm-2 dsp-col-sm-2">
                    <input name="lat" id="lat" type="hidden" value="">
                    <input name="lng" id="lng" type="hidden" value="">
                    <input name="cmbCountry" id="country" type="hidden" value="">
                </div>
                <div class=" dspdp-col-sm-12">
					<?php /* if (!is_user_logged_in()) { ?>
                            <input class="login-btn  dspdp-btn dspdp-btn-default dspdp-search-submit dsp-btn dsp-btn-default dsp-search-submit" type="button" value="<?php echo strtoupper(language_code('DSP_LOGIN')); ?>"  />
                    <?php } */ ?>
                    <input name="submit" type="submit"
                           class="dsp_submit_button  dspdp-btn dspdp-btn-default dspdp-search-submit"
                           value="<?php echo language_code( 'DSP_SEARCH_BUTTON' ); ?>"/>
					<?php if ( ! is_user_logged_in() ) { ?>
                        <input class="login-btn  dspdp-btn dspdp-btn-default dspdp-search-submit" type="button"
                               value="<?php echo strtoupper( language_code( 'DSP_LOGIN' ) ); ?>"/>
						<?php
						do_action( 'wpdating_facebook_login' );
						?>
                        <input class="reg_popoup   dspdp-btn dspdp-btn-default dspdp-search-submit dsp-btn dsp-btn-default dsp-search-submit"
                               id="freebox" type="button"
                               value="<?php echo language_code( 'DSP_DZONIA_REGISTER_HEADING_TEXT' ); ?>"/>
					<?php } ?>
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        function autoSubmitForm() {
            document.frmquicksearch.submit();
        }

        dsp = jQuery.noConflict();

    </script>                 

   