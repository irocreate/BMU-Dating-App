<form name="frmsearch" method="GET" action="">
    <input type="hidden" name="pid" value="5" />
    <input type="hidden" name="pagetitle" value="search_result" />
    <input type="hidden" name="search_type" value="basic_search" />
    <?php //---------------------------------START  GENERAL SEARCH--------------------------------------- ?>
    <script  type="text/javascript" language="javascript">
        // State lists
        var states = new Array();
        // City lists
        var cities = new Array();
    </script>
    <?php
# get state list
    $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
    foreach ($strCountries as $rdoCountries) {
        $strStateList = "";
        $strStates = $wpdb->get_results("SELECT * FROM $dsp_state_table WHERE country_id = " . $rdoCountries->country_id . " ORDER BY name");
        foreach ($strStates as $rdoStates) {
            $strStateList .= "'" . $rdoStates->name . "',";
        } // end $rdoStates
        if (strlen($strStateList) > 0) {
            $strStateList = substr($strStateList, 0, strlen($strStateList) - 1);
        }
        ?>
        <script  type="text/javascript" language="javascript">
            states['<?php echo $rdoCountries->name; ?>'] = new Array('Select',<?php echo $strStateList; ?>);
        </script>
        <?php
        $bolinitialiseArray = true;
        $strStates = $wpdb->get_results("SELECT * FROM $dsp_state_table WHERE country_id = " . $rdoCountries->country_id . " ORDER BY name");
        foreach ($strStates as $rdoStates) {
            if ($bolinitialiseArray == true) {
                ?>
                <script  type="text/javascript" language="javascript">
                    cities['<?php echo $rdoCountries->name; ?>'] = new Array();
                </script>
                <?php
            }
            $strCityList = "";
            $strCities = $wpdb->get_results("SELECT * FROM $dsp_city_table WHERE country_id = " . $rdoCountries->country_id . " AND state_id = " . $rdoStates->state_id . " ORDER BY name");
            foreach ($strCities as $rdoCities) {
                $replacequotesfromcity = str_replace("'", "`", $rdoCities->name);
                $strCityList .= "'" . $replacequotesfromcity . "',";
            } // end $rdocitie
            if (strlen($strCityList) > 0) {
                $strCityList = substr($strCityList, 0, strlen($strCityList) - 1);
            }
            ?>
            <script  type="text/javascript" language="javascript">
                cities['<?php echo $rdoCountries->name; ?>']['<?php echo $rdoStates->name; ?>'] = new Array('Select',<?php echo $strCityList; ?>);
            </script>
            <?php
            $bolinitialiseArray = false;
        } // end $rdoStates
    } // end $rdoCountries
    ?>
    <div>
        <table cellpadding="0" cellspacing="0" width="100%" border="0">
            <tr>
                <td>
                    <table border="0" cellspacing="1" cellpadding="0">
                        <tr>
                            <td colspan="2" height="2px"></td>
                        </tr>
                        <tr>
                            <td><?php echo language_code('DSP_I_AM'); ?></td>
                            <td align="left"><select name="gender">
                                    <option value="M"><?php echo language_code('DSP_MAN'); ?></option>
                                    <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>

                                    <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                        <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>
                                    <?php } ?>


                                </select></td>
                        </tr>
                        <tr>
                            <td><?php echo language_code('DSP_SEEKING_A'); ?></td>
                            <td align="left"><select name="seeking">
                                    <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                                    <option value="M"><?php echo language_code('DSP_MAN'); ?></option>

                                    <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                        <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>
                                    <?php } ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td><?php echo language_code('DSP_AGE') ?></td>
                            <td align="left"><select name="age_from" style="width:50px;"> 
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

                            </td>
                        </tr>

                        <tr>
                            <td><?php echo language_code('DSP_COUNTRY'); ?></td>
                            <td align="left">
                                <select name="cmbCountry" id="cmbCountry_id1" onchange="javascript :setStates();">
                                    <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY'); ?></option>
                                    <?php
                                    $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
                                    foreach ($strCountries as $rdoCountries) {

                                        echo "<option value='" . $rdoCountries->name . "' >" . $rdoCountries->name . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><?php echo language_code('DSP_TEXT_STATE'); ?></td>
                            <td align="left">
                                <!--onChange="Show_state(this.value);"-->
                                <select name="cmbState" id="cmbState_id1" style="width:110px;" onchange="javascript : setCities();">
                                    <option value="0"><?php echo language_code('DSP_SELECT_STATE'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <!-- End City combo-->
                        <tr>
                            <td><?php echo language_code('DSP_CITY'); ?></td>
                            <td align="left">
                                <!--onChange="Show_state(this.value);"-->
                                <select name="cmbCity" id="cmbCity_id1">
                                    <option value="0"><?php echo language_code('DSP_SELECT_CITY'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <!-- End city combo-->

                    </table>
                </td>
            </tr>
        </table>

    </div>
    <?php //-----------------------------------------END GENERAL SEARCH-------------------------------------------// ?>
    <br>
    <?php //-------------------------------------START ADDITIONAL OPTIONS SEARCH -------------------------------------//   ?>

    <br>
    <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <?php if ($check_zipcode_mode->setting_status == 'Y') {
            ?>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                        <tr>
                            <td colspan="4" align="left" class="dsp_mb_header"><?php echo language_code('DSP_USER_LOCATION'); ?></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <table border="0" cellspacing="1" cellpadding="0">
                                    <tr>
                                        <td colspan="2" height="2px"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name ='miles' value="" style="width: 55px" /><?php echo DSP_MILES_FROM ?>
                                            <input type="text" name ='zip_code' value="" style="width: 55px" /><?php echo DSP_SEARCH_ZIP ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" height="2px"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" name="Online_only" value="Y">
                                            <?php echo language_code('DSP_SEARCH_ONLINE_ONLY') ?>
                                            <br>
                                            <input type="checkbox" name="Pictues_only" value="Y">
                                            <?php echo DSP_WITH_PHOTO_ONLY ?>
                                        </td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="submit" name="submit" class="dsp_submit_button" value="<?php echo language_code('DSP_GUEST_HEADER_SEARCH'); ?>" onclick="search_by_quick_widget();" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</form>
<?php //-------------------------------------END ADDITIONAL OPTIONS SEARCH -------------------------------------//   ?>
