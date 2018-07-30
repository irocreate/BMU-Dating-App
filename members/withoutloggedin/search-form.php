<?php
/**
* This tempalte display search form for logged out condition
* @return 1.0
*/
global $wpdb;
$queried_object = get_queried_object();
$root_link = get_bloginfo('url') . '/' . $queried_object->post_name . '/';

$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : 'M';
$seeking = isset($_REQUEST['seeking']) ? $_REQUEST['seeking'] : 'F';
$age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : '';
$age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : '';
$Pictues_only = isset($_REQUEST['Pictues_only']) ? $_REQUEST['Pictues_only'] : '';
$Online_only = isset($_REQUEST['Online_only']) ? $_REQUEST['Online_only'] : '';
$countryName = isset($_REQUEST['cmbCountry']) ? $_REQUEST['cmbCountry'] : '';
$stateName = isset($_REQUEST['cmbState'] ) ? $_REQUEST['cmbState'] : '';
$cityName = isset($_REQUEST['cmbCity']) ?urldecode($_REQUEST['cmbCity']): '';

?>
<div class="lm-logout-searchform">
	<div class="lm-logout-searchform-heading col-md-12">
		<?php esc_html_e( 'Find Your Match' ); ?>		
	</div>
	<form name="frmguestsearch" class="dspdp-form-horizontal lm-logout-search-form" method="GET" action="<?php echo esc_url( $root_link . "g_search_result" ); ?>">
	    <input type="hidden" name="search_type" value="basic_search" />
	    <?php //---------------------------------START  GENERAL SEARCH---------------------------------------   ?>
	    <div class="guest-search dsp-form-container">
	    	<!-- top search field -->
	    	<div class="lm-top-search-field clearfix">
    			<div class="lm-sl-iam col-md-2">
		            <div class="lm-sl-heading">
		                <?php echo language_code('DSP_I_AM'); ?>
		            </div>
		            <div class="lm-sl-gender">
		                <select name="gender" class="dspdp-form-control dsp-form-control">
		                    <?php echo get_gender_list( sanitize_text_field( $gender  ) ); ?>
		                </select>
		            </div>
    			</div>

    			<div class="lm-sl-seeking col-md-2">
    				<?php 
			           $genderList = get_gender_list( sanitize_text_field( $seeking ) );
			           	if(!empty($genderList)): ?>
				            <div class="  clearfix">
				                <div class="lm-sl-heading">
				                    <?php echo language_code('DSP_SEEKING_A'); ?> 
				                </div>
				                <div class="lm-sl-seekinglist">
				                    <select name="seeking"  class="dspdp-form-control dsp-form-control">
				                        <?php echo $genderList; ?>
				                    </select>
				                </div>
				            </div>     
			        	<?php endif; 
		        	?>
    			</div>

    			<div class="lm-sl-age-from col-md-2">
    				<div class="lm-sl-heading">
		                <?php echo language_code('DSP_AGE'); ?>
		            </div>
		            <div class="lm-sl-agefrom-list">
		                <select name="age_from"  class="dspdp-form-control dsp-form-control"> 
		                <?php
		                for ($fromyear = 18; $fromyear <= 99; $fromyear++) {
		                    if ($fromyear == 18) { ?>
		                        <option value="<?php echo $fromyear ?>"><?php echo $fromyear ?></option>
		                    <?php } else {  ?>
		                        <option value="<?php echo $fromyear ?>" <?php selected( $age_from, $fromyear ); ?>><?php echo $fromyear ?></option>
		                        <?php
		                    }
		                }
		                ?>
		            	</select>
		            </div>
    			</div>

    			<div class="lm-sl-ageto col-md-2">
    				<div class="lm-sl-heading">
		                <?php esc_html_e( 'To', 'love-match'); ?>
		            </div>

    				<div class="lm-sl-ageto-list">
	    				<select name="age_to"  class="dspdp-form-control dsp-form-control">
			                <?php
			                for ($toyear = 18; $toyear <= 99; $toyear++) {
			                    if ($toyear == 99 && empty( $age_to ) ) { ?>
			                        <option value="<?php echo $toyear ?>" selected="selected"><?php echo $toyear ?></option>
			                    <?php } else { ?>
			                        <option value="<?php echo $toyear ?>" <?php selected( $age_to, $toyear ); ?>><?php echo $toyear ?></option>
			                        <?php
			                    }
			                }
			                ?>
			            </select>
		            </div>
    			</div>

    			<div class="lm-sl-country col-md-2">
    				<div class="lm-sl-heading">
    					<?php echo language_code('DSP_COUNTRY'); ?>
    				</div>
		            <div class="lm-sl-country-list">
		                <select name="cmbCountry" id="cmbCountry_id"  class="dspdp-form-control dsp-form-control">
			                <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY'); ?></option>
			                <?php
			                $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
			                $dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
			                $check_default_country = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'default_country'");
			                $default_country = isset($check_default_country->setting_value) && !empty($check_default_country->setting_value) ? $check_default_country->setting_value : 0
;			                $selectedCountryId = isset($countryName) && !empty($countryName) ? $countryName : $default_country ;
			                $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
			                foreach ($strCountries as $rdoCountries) {
			                    echo "<option value='" . absint( $rdoCountries->country_id ) . "' ".selected( $selectedCountryId, $rdoCountries->country_id)." >" . esc_html( $rdoCountries->name ) . "</option>";
			                }
			                ?>
			            </select>
		            </div>
    			</div>

    			<div class="lm-sl-state col-md-2">
    				<div class="lm-sl-heading">
		                <?php echo language_code('DSP_TEXT_STATE'); ?>
		            </div>
		            <!--onChange="Show_state(this.value);"-->
		            <div id="state_change" class="lm-sl-state-list">
		                <select name="cmbState" id="cmbState_id"  class="dspdp-form-control dsp-form-control">
		                    <option value="0"><?php echo language_code('DSP_SELECT_STATE'); ?></option>
		                    <?php
		                    if ($selectedCountryId != 0) {
		                        $selectedCountriesStates = apply_filters('dsp_get_all_States_Or_City',$selectedCountryId);
		                        if(isset($selectedCountriesStates) && !empty($selectedCountriesStates)):
		                            foreach ($selectedCountriesStates as $state) {
		                                echo "<option value='" . absint( $state->state_id ) . "' ".selected( $stateName, $state->state_id)." >" . esc_html( $state->name ) . "</option>";
		                            } 
		                        endif;
		                    }
		                    ?>
		                </select>
	            	</div>
    			</div>
    		</div>

	    	<!-- bottom search fielde -->
	    	<div class="lm-buttom-search-field clearfix">
	    		<div class="lm-sl-city col-md-2">
		    		<div class="lm-sl-heading"><?php echo language_code('DSP_CITY'); ?></div>
		            <!--onChange="Show_state(this.value);"-->

		            <div id="city_change" class="lm-sl-city-list">
		                <select name="cmbCity" id="cmbCity_id" class="dspdp-form-control dsp-form-control">
		                    <option value="0"><?php echo language_code('DSP_SELECT_CITY'); ?></option>
		                    <?php
		                        if ($selectedCountryId != 0) {
		                            $selectedCountriesCities = apply_filters('dsp_get_all_States_Or_City',$selectedCountryId,true);

		                            if(isset($selectedCountriesCities) && !empty($selectedCountriesCities)):
		                                foreach ($selectedCountriesCities as $city) {
		                                    echo "<option value='" . absint( $city->city_id ) . "' ".selected( $cityName, $city->city_id)." >" . esc_html( $city->name ) . "</option>";
		                                } 
		                            endif;
		                        }
		                    ?>
		                </select>
		            </div>
		        </div>

		        <div class="lm-sl-online col-md-2">
			    	<div class="lm-sl-heading">	
			    		<?php echo language_code('DSP_SEARCH_ONLINE_ONLY'); ?>
			    	</div>
			        <div class="lm-sl-online-option">
				        <select name="Online_only" class="dspdp-form-control dsp-form-control">
				            <option value="N" <?php selected( $Online_only , 'N'); ?>><?php echo language_code('DSP_OPTION_NO'); ?></option>
				            <option value="Y" <?php selected( $Online_only, 'Y'); ?>><?php echo language_code('DSP_OPTION_YES'); ?></option>
				        </select>
			        </div>
		        </div>

		        <div class="lm-sl-picture col-md-2">
			    	<div class="lm-sl-heading">
			    		<?php echo language_code('DSP_SEARCH_WITH_PICTURE_ONLY'); ?>
			    	</div>
		            <div class="lm-sl-picture-option">
			            <select name="Pictues_only" class="dspdp-form-control dsp-form-control">
			                <option value="P" <?php selected( $Pictues_only , 'P'); ?>><?php echo language_code('DSP_OPTION_NO_PREFERENCE'); ?></option>
			                <option value="N" <?php selected( $Pictues_only , 'N'); ?>><?php echo language_code('DSP_OPTION_NO'); ?></option>
			                <option value="Y" <?php selected( $Pictues_only , 'Y'); ?>><?php echo language_code('DSP_OPTION_YES'); ?></option>
			            </select>
		            </div>
			    </div>


		        <div class="lm-sl-picture col-md-2">
		        	<div class="lm-sl-heading"></div>
		            <div class="lm-sl-search-button">
		                <input type="submit" name="submit" class="dsp_submit_button dspdp-btn dspdp-btn-default" value="<?php echo language_code('DSP_SEARCH_BUTTON'); ?>" onclick="dsp_guest_search();" />
		            </div>
			    </div>
	    	</div>
	    </div>
	</form>
</div>