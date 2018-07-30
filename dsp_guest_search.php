<form name="frmguestsearch" method="GET" action="<?php echo $root_link . "g_search_result"; ?>">
    <input type="hidden" name="search_type" value="basic_search" />
    <?php //---------------------------------START  GENERAL SEARCH--------------------------------------- ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page guest-search">
                <?php 
                   $genderList = get_gender_list('M');
                   if(!empty($genderList)):
                ?>
                    <p><span class="dsp_left"><?php echo language_code('DSP_I_AM'); ?></span>
                        <select name="gender">
                            <?php echo $genderList; ?>
                        </select>
                    </p>
                <?php endif; ?>                
                <p>
                    <span class="dsp_left"><?php echo language_code('DSP_SEEKING_A'); ?></span>
                    <select name="seeking">
                        <?php echo get_gender_list('F'); ?>
                    </select>
                </p>	 
                <p>
                    <span class="dsp_left"><?php echo language_code('DSP_AGE'); ?></span>
                    <select name="age_from" style="width:50px;"> 
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

                    <select name="age_to" style="width:50px;">
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
                </p>
                <p>
                    <span class="dsp_left dsp-control-label"><?php echo language_code('DSP_COUNTRY'); ?></span>
                    <select name="cmbCountry" id="cmbCountry_id" class="dsp-form-control">
                        <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY'); ?></option>
                        <?php
                        $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
                        foreach ($strCountries as $rdoCountries) {

                            echo "<option value='" . $rdoCountries->name . "' >" . $rdoCountries->name . "</option>";
                        }
                        ?>
                    </select>
                </p>
                <p style="float:left;">
                    <span class="dsp_left"><?php echo language_code('DSP_TEXT_STATE'); ?></span>
                    <!--onChange="Show_state(this.value);"-->
                <div id="state_change">
                    <select name="cmbState" id="cmbState_id" style="width:110px;" class="dspdp-form-control">
                        <option value="0"><?php echo language_code('DSP_SELECT_STATE'); ?></option>
                    </select>
                </div>
                </p>
                <!-- End City combo-->
                <p  style="float:left;">
                    <span class="dsp_left"><?php echo language_code('DSP_CITY'); ?></span>
                    <!--onChange="Show_state(this.value);"-->
                <div id="city_change">
                    <select name="cmbCity" id="cmbCity_id" class="dspdp-form-control">
                        <option value="0"><?php echo language_code('DSP_SELECT_CITY'); ?></option>
                    </select>
                </div>
                </p>
                <!-- End city combo-->
                <span class="dsp_left"><?php echo language_code('DSP_SEARCH_ONLINE_ONLY'); ?>:</span>
                <select name="Online_only" class="dspdp-form-control">
                    <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                    <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                </select>
                </p>
                <p>
                    <span class="dsp_left"><?php echo language_code('DSP_SEARCH_WITH_PICTURE_ONLY'); ?>:</span>
                    <select name="Pictues_only" class="dspdp-form-control">
                        <option value="P"><?php echo language_code('DSP_OPTION_NO_PREFERENCE'); ?></option>
                        <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
                        <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
                    </select>
                </p>
                <p>
                    <span class="dsp_left">&nbsp;</span>
                <div class="dsp_right">
                    <input type="submit" name="btnsubmit" class="dsp_submit_button dspdp-btn dspdp-btn-default" value="<?php echo language_code('DSP_SEARCH_BUTTON'); ?>" onclick="dsp_guest_search();" />
                </div>
                </p>
            </div>
        </div>
    </div>
</form>