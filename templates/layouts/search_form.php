<?php 
include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path
$path = $pluginpath . 'image.php';
$check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");
if (is_user_logged_in() ) {  // CHECK MEMBER LOGIN
?>
   	<form name="frmquicksearch" id="frmquicksearch" method="GET" action="<?php echo ROOT_LINK .'search/search_result/basic_search/basic_search' ?>">
	<input type="hidden" name="pid" value="5" />
	<input type="hidden" name="pagetitle" value="search_result" />
	<?php } else { ?>
	<form name="frmquicksearch" id="frmquicksearch" method="GET" action="<?php echo ROOT_LINK . 'g_search_result/' ?>">
		<?php } ?>
		<input type="hidden" name="Pictues_only" value="P" />
		<div class="dspdp-form-horizontal dsp-form-horizontal">
			<div class="dspdp-row dsp-row">
				<?php
				$seeking = $userProfileDetailsExist ? $userProfileDetails->seeking : 'F';
				$genderList = get_gender_list($seeking);
				if (!empty($genderList)):
				?>
					<div class="dspdp-col-sm-6 dspdp-col-md-12">
						<div class="dspdp-form-group">
							<span class="dspdp-control-label dspdp-col-sm-4"><?php echo language_code('DSP_SEEKING_A'); ?></span>
							<div class="dspdp-col-sm-8">
								<select name="seeking"  class="dspdp-form-control">
									<?php echo $genderList; ?>
								</select>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<div class="dspdp-col-sm-6 dspdp-col-md-12">
					<div class="dspdp-form-group">
						<span class="dspdp-control-label dspdp-col-sm-4"><?php echo language_code('DSP_AGE'); ?></span>
						<div class="dspdp-col-sm-8">
							<div class="dspdp-row">
								<div class="dspdp-col-sm-5">
									<select name="age_from"   class="dspdp-form-control">
										<?php
										for ($fromyear = 18; $fromyear <= 99; $fromyear++) {
											if ($fromyear == 18) { 
												?>
												<option value="<?php echo $fromyear ?>" selected="selected"><?php echo $fromyear ?></option>
												<?php } else { ?>
												<option value="<?php echo $fromyear ?>"><?php echo $fromyear ?></option>
												<?php
											}
										}
										?>
									</select>
								</div>
								<div class="dspdp-col-sm-2 dspdp-control-label"><?php echo language_code('DSP_TO'); ?></div>
								<div class="dspdp-col-sm-5">
									<select name="age_to"  class="dspdp-form-control">
										<?php
										for ($toyear = 18; $toyear <= 99; $toyear++) {
											if ($toyear == 99) {
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
							</div>
						</div>
					</div>
				</div>
				<div class="dspdp-col-sm-6 dspdp-col-md-12">
					<?php if($isDistanceModeOn): ?>
						<div class="dspdp-form-group">
							<span class="dspdp-control-label dspdp-col-sm-4"><?php echo language_code('DSP_SELECT_DISTANCE'); ?></span>
							<div  class="dspdp-col-sm-8">
								<input name="distance" type="text" class="dspdp-form-control" />
							</div>
						</div>
						<div class="dspdp-form-group">
							<span class="dspdp-control-label dspdp-col-sm-4"><?php echo language_code('DSP_UNIT'); ?></span>
							<div  class="dspdp-col-sm-8">
								<select name="unit" class="dspdp-form-control">
									<option value="0"><?php echo language_code('DSP_SELECT_UNIT'); ?></option>
									<?php
									$options = array(
										3959 => language_code('DSP_MILES'),
										6371 => language_code('DSP_KM')
										);
									foreach ($options as $key=>$option) {
										?>
										<option value="<?php echo $key; ?>" ><?php echo $option; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
					<?php endif; ?>
					<div class="dspdp-form-group">
						<span class="dspdp-control-label dspdp-col-sm-4"><?php echo language_code('DSP_COUNTRY'); ?></span>
						<?php $placeholder = $isDistanceModeOn ? language_code('DSP_SEARCH_BY_PLACE_ZIPCODE_COUNTRY') : language_code('DSP_COUNTRY'); ?>  
						<div  class="dspdp-col-sm-8">
							<input id="autocomplete" name="zip_code" type="text" class="dspdp-form-control"  placeholder="<?php echo str_replace(':','', $placeholder); ?>"/>
						</div>
					</div>
					<div class="dspdp-form-group">
						<div  class="dspdp-col-sm-8">
						   <input  name="lat" id="lat"  type="hidden" value="" >
						   <input  name="lng" id="lng"  type="hidden" value="" >
						   <input  name="cmbCountry" id="country"  type="hidden" value="">
					   </div>
				   </div>
		   		</div>
				<div class="dspdp-col-sm-6 dspdp-col-md-12">
				<div class="dspdp-row dspdp-form-group">
						<div class="dspdp-col-sm-offset-4 dspdp-col-sm-8">
							<input name="submit" type="submit" class="dsp_submit_button dspdp-btn" value="<?php echo language_code('DSP_SEARCH_BUTTON'); ?>" style="background: <?php //echo $temp_color;    ?>;"/>
							<?php if (!is_user_logged_in()) { ?>
								<input class="login-btn dsp_submit_button dspdp-btn" type="button" value="<?php echo strtoupper(language_code('DSP_LOGIN')); ?>" style="  background: <?php //echo $temp_color;            ?>;  " />
								<input  class="reg_popoup dsp_submit_button dspdp-btn" id="freebox" type="button" value="Join" />
							<?php } ?>
						</div>
					</div>
				</div>
				<?php
				do_action( 'wpdating_facebook_login' );
				?>
			</div>
		</div>
	</form>
	<script type="text/javascript">
	function autoSubmitForm()
	{
		document.frmquicksearch.submit();
	}
	dsp = jQuery.noConflict();
	</script>                 
