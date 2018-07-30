<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_SEARCH'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>

</div>
<?php
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;

$root_link = "";
?>
<form id="frmsearch" name="frmsearch" >

    <div class="ui-content" data-role="content">
        <div class="content-primary">	

            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">



                    <input type="hidden" name="pagetitle" value="search_result" />

                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                    <input type="hidden" name="basic_search" value="basic_search" />



                    <?php //---------------------------------START  GENERAL SEARCH---------------------------------------  ?>




                    <div class="heading-text"><strong><?php echo language_code('DSP_GENERAL'); ?></strong></div>

                    <ul class="edit-profile">

                        <li><span><?php echo language_code('DSP_I_AM'); ?></span><select name="gender">

                                <option value="M"><?php echo language_code('DSP_MAN'); ?></option>

                                <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>

                                <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                                    <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>

                                <?php } ?>



                            </select></li>
                        <li><span><?php echo language_code('DSP_SEEKING_A'); ?></span><select name="seeking">
                                <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>

                                <option value="M"><?php echo language_code('DSP_MAN'); ?></option>

                                <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                                    <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>

                                <?php } ?>

                            </select></li>
                        <li><span><?php echo language_code('DSP_AGE') ?></span> 
                            <select name="age_from" style="width:50px;"> 

                                <?php
                                for ($fromyear = 18; $fromyear <= 99; $fromyear++) {
                                    if ($fromyear == 18) {
                                        ?>
                                        <option value="<?php echo $fromyear ?>" selected="selected"><?php echo $fromyear ?></option>
                                        <?php
                                    } else {
                                        ?>
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
                        </li>
                        <li>
                            <span><?php echo language_code('DSP_COUNTRY'); ?></span>
                            <select name="cmbCountry" id="cmbCountry_id">

                                <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY'); ?></option>



                                <?php
                                $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");



                                foreach ($strCountries as $rdoCountries) {
                                    echo "<option value='" . $rdoCountries->name . "' >" . $rdoCountries->name . "</option>";
                                }
                                ?>
                            </select>
                        </li>
                        <li><span><?php echo language_code('DSP_TEXT_STATE'); ?></span>

                            <div id="state_change">
                                <select name="cmbState" id="cmbState_id">
                                    <option value="0"><?php echo language_code('DSP_SELECT_STATE'); ?></option>
                                </select>
                            </div>
                        </li>
                        <!-- End City combo-->
                        <li><span><?php echo language_code('DSP_CITY'); ?></span> 

                            <div id="city_change">
                                <select name="cmbCity" id="cmbCity_id">
                                    <option value="0"><?php echo language_code('DSP_SELECT_CITY'); ?></option>
                                </select>
                            </div>
                        </li>
                        <!-- End city combo-->
                    </ul>

                </li>


                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                    <div class="box-page search-page">

                        <div class="heading-text"><strong><?php echo language_code('DSP_ADDITIONAL_OPTIONS'); ?></strong></div>
                        <ul class="edit-profile">
                            <li>
                                <span><?php echo language_code('DSP_SEARCH_ONLINE_ONLY') ?></span>
                                <select name="Online_only">

                                    <option value="N"><?php echo language_code('DSP_OPTION_NO') ?></option>
                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES') ?></option>
                                </select>
                            </li>
                            <li>
                                <span><?php echo language_code('DSP_SEARCH_WITH_PICTURE_ONLY') ?></span> 
                                <select name="Pictues_only">

                                    <option value="P"><?php echo language_code('DSP_OPTION_NO_PREFERENCE') ?></option>

                                    <option value="N"><?php echo language_code('DSP_OPTION_NO') ?></option>

                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES') ?></option>
                                </select>
                            </li>
                            <li> <span><?php echo language_code('DSP_USER_NAME'); ?></span>
                                <input type="text" name="username" value="" /></li>

                            <li><?php echo language_code('DSP_SAVE_THIS_SEARCH'); ?>
                                <input class="search-checkbox" type="checkbox" name="check_save" value="SS" /></li>
                            <li><input type="text" name="savesearch" value=""/><input type="hidden" name="search_type" value="basic"/></li>

                            <li><input type="button" name="submit"  value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>" onclick="viewSearch(0, 'post');" /></li>
                        </ul>


                    </div>


                </li>
            </ul>
        </div>
        <?php include_once('dspNotificationPopup.php'); // for notification pop up      ?>
    </div>

</form>
<?php
//-------------------------------------END ADDITIONAL OPTIONS SEARCH -------------------------------------// ?>