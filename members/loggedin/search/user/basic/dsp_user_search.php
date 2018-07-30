<form class="dspdp-form-horizontal dsp-form-container" name="frmsearch" method="GET" action="<?php echo $root_link . "search/search_result/basic_search/basic_search/"; ?>">
    <?php //---------------------------------START  GENERAL SEARCH--------------------------------------- ?>
    <div class="heading-submenu dsp-block" style="display:none"><?php echo language_code('DSP_BASIC_SEARCH') ?></div>
    <div class="dsp-box-container">
        <div class="box-border">
            <div class="box-pedding dsp-space">
                <div class="heading-submenu dsp-none"><strong><?php echo language_code('DSP_GENERAL'); ?></strong></div>
                <div class="heading margin-btm-2 dsp-block" style="display:none">
                    <h3><?php echo language_code('DSP_GENERAL'); ?></h3>
                </div>
                <ul class="edit-profile">
                    <?php 
                       $gender = $userProfileDetailsExist ? $userProfileDetails->gender : '';
                       $genderList = get_gender_list($gender);
                       if(!empty($genderList)):
                    ?>
                        <li class="dspdp-form-group dsp-form-group">
                            <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                                <?php echo language_code('DSP_I_AM'); ?>
                            </span>
                            <span class="dspdp-col-sm-6 dsp-sm-6">
                                <select class="dspdp-form-control dsp-form-control" name="gender">
                                    <?php echo $genderList; ?>
                                </select>
                            </span>
                        </li>
                    <?php endif; ?>

                    <?php 
                       $seeking = $userProfileDetailsExist ? $userProfileDetails->seeking : 'F';
                       $genderList = get_gender_list($seeking);
                       if(!empty($genderList)):
                    ?>
                        <li class="dspdp-form-group dsp-form-group">
                            <span class="dspdp-control-label dsp-control-label dsp-sm-3 dspdp-col-sm-3">
                                <?php echo language_code('DSP_SEEKING_A'); ?>
                            </span>
                            <span class="dspdp-col-sm-6 dsp-sm-6">
                                <select name="seeking" class="dspdp-form-control dsp-form-control">
                                    <?php echo $genderList; ?>
                                </select>
                            </span>
                        </li>
                    <?php endif; ?>
                   
                    
                    <li class="dspdp-form-group dsp-form-group">
                        <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_AGE') ?>
                        </span> 
                        <span class="dspdp-col-sm-3 dsp-sm-3 dspdp-xs-form-group dsp-xs-form-group">
                            <select name="age_from" class="dspdp-form-control dsp-form-control"> 
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
                        </span>
                        <span class="dspdp-col-sm-3	dsp-sm-3">
                            <select name="age_to" class="dspdp-form-control dsp-form-control">
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
                        </span>
                    </li>
                    <li class="dspdp-form-group dsp-form-group">
                        <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_COUNTRY'); ?>
                        </span>
                        <span class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="cmbCountry" id="cmbCountry_id" class="dspdp-form-control dsp-form-control">
                                <option value="0">
                                    <?php echo language_code('DSP_SELECT_COUNTRY'); ?>
                                </option>
                                <?php
                                    $selectedCountryId = isset($check_default_country->setting_value) ? $check_default_country->setting_value : 0;
                                    $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
                                    foreach ($strCountries as $rdoCountries) {
                                        $selected = ($rdoCountries->country_id == $selectedCountryId) ? "selected = selected" : "";
                                        echo "<option value='" . $rdoCountries->country_id . "' $selected >" . $rdoCountries->name . "</option>";
                                    }
                                ?>
                            </select>
                        </span>
                    </li>
                    <li class="dspdp-form-group dsp-form-group">
                        <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code('DSP_TEXT_STATE'); ?>
                        </span>
                        <!--onChange="Show_state(this.value);"-->
                        <div id="state_change" class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="cmbState" id="cmbState_id" class="dspdp-form-control dsp-form-control">
                                <option value="0"><?php echo language_code('DSP_SELECT_STATE'); ?></option>
                                <?php 
                                    if ($selectedCountryId != 0) {
                                        $selectedCountriesStates = apply_filters('dsp_get_all_States_Or_City',$selectedCountryId);
                                        if(isset($selectedCountriesStates) && !empty($selectedCountriesStates)):
                                            foreach ($selectedCountriesStates as $state) {
                                                echo "<option value='" . $state->state_id . "' >" . $state->name . "</option>";
                                            }
                                        endif;
                                    }
                                ?>
                            </select>
                        </div>
                    </li>
                    <!-- End City combo-->
                    <li class="dspdp-form-group dsp-form-group">
                        <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3"><?php echo language_code('DSP_CITY'); ?></span> 
                        <!--onChange="Show_state(this.value);"-->
                        <div id="city_change" class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="cmbCity" id="cmbCity_id" class="dspdp-form-control dsp-form-control">
                                <option value="0"><?php echo language_code('DSP_SELECT_CITY'); ?></option>
                                <?php
                                    if ($selectedCountryId != 0) {
                                        $strStatesCheck = 0;
                                        $strStatesCheck = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_state_table where country_id='$selectedCountryId'");
                                        if ($strStatesCheck == 0){
                                            $selectedCountriesCities = apply_filters('dsp_get_all_States_Or_City', $selectedCountryId, true);
                                        }
//                                        $selectedCountriesCities = apply_filters('dsp_get_all_States_Or_City',$selectedCountryId,true);
                                        if(isset($selectedCountriesCities) && !empty($selectedCountriesCities)):
                                            foreach ($selectedCountriesCities as $city) {
                                                echo "<option value='" . $city->city_id . "' >" . $city->name . "</option>";
                                            }
                                        endif;
                                    }
                                ?>
                            </select>
                        </div>
                    </li>
                    <!-- End city combo-->
                    <li class="dspdp-form-group dsp-form-group">
                        <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3"><?php echo language_code('DSP_USER_NAME'); ?></span>
                        <div  class="dspdp-col-sm-6 dsp-sm-6"><input name="username" type="text" class="dspdp-form-control dsp-form-control" /></div>
                    </li>            
                </ul>
            </div>
        </div>
    </div>

    <?php //-----------------------------------------END GENERAL SEARCH-------------------------------------------// ?>

    <?php //-------------------------------------START ADDITIONAL OPTIONS SEARCH -------------------------------------//   ?>
    
    <div class="dsp-box-container margin-btm-3">
        <div class="box-border search-page magn-top-15">
            <div class="box-pedding dsp-space">
                <div class="heading-submenu dsp-none"><strong><?php echo language_code('DSP_ADDITIONAL_OPTIONS'); ?></strong></div>
                <div class="heading margin-btm-2 dsp-block" style="display:none">
                    <h3><?php echo language_code('DSP_ADDITIONAL_OPTIONS'); ?></h3>
                </div>
                <ul class="edit-profile">
                    <li class="dspdp-form-group dsp-form-group">
                        <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3"><?php echo language_code('DSP_SEARCH_ONLINE_ONLY') ?>:</span>
                        <span class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="Online_only"  class="dspdp-form-control dsp-form-control">
                            <option value="N"><?php echo language_code('DSP_OPTION_NO') ?></option>
                            <option value="Y"><?php echo language_code('DSP_OPTION_YES') ?></option>
                        </select></span></li>
                    <li class="dspdp-form-group dsp-form-group"><span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3"><?php echo language_code('DSP_SEARCH_WITH_PICTURE_ONLY') ?>:</span> 
                        <span class="dspdp-col-sm-6 dsp-sm-6"><select name="Pictues_only"  class="dspdp-form-control dsp-form-control">
                            <option value="P"><?php echo language_code('DSP_OPTION_NO_PREFERENCE') ?></option>
                            <option value="N"><?php echo language_code('DSP_OPTION_NO') ?></option>
                            <option value="Y"><?php echo language_code('DSP_OPTION_YES') ?></option>
                        </select></span>
    				</li>
                    <?php /* ?><li><span><?php echo language_code('DSP_SEARCH_DISPLAY_TYPE')?></span>
                      <select name="display_type"  class="dspdp-form-control">
                      <option value="detailed"><?php echo language_code('DSP_OPTION_DETAILED')?></option>
                      </select></li><?php */ ?>
                    <?php
                    // only login member can save search result 
                    if (is_user_logged_in()) {
                        ?>
                        <li class="dspdp-form-group dsp-form-group">
                            <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
    					<?php echo language_code('DSP_SAVE_THIS_SEARCH'); ?>:&nbsp;</span>
                            <span class="dspdp-col-sm-1 dsp-sm-1"><input type="checkbox" name="check_save" value="SS" /></span>
						<span class="dspdp-col-sm-5 dsp-sm-5"><input  class="dspdp-form-control dsp-form-control" type="text" name="savesearch" value=""/><input type="hidden" name="search_type" value="basic_search"/></span></li>
                    <?php } // if ( is_user_logged_in() )    ?>
                    <li class="dspdp-form-group dsp-form-group dsp-none">
                        <span class=" dspdp-col-sm-offset-3 dsp-sm-offset-3  dspdp-col-sm-6 dsp-sm-6">
                            <input type="submit" name="submit" class="dsp_submit_button dspdp-btn dspdp-btn-default" value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>" onclick="search_by_quick_widget();" />
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <input type="submit" name="submit" class="dsp_submit_button dspdp-btn dsp-block dspdp-btn-default" value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>" onclick="search_by_quick_widget();" style="display:none" />
</form>

<?php
//-------------------------------------END ADDITIONAL OPTIONS SEARCH -------------------------------------// ?>