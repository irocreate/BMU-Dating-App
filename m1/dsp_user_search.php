<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_back.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_SEARCH'); ?></h1>
    <?php include_once("page_home.php");?> 

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
           <input type="hidden" name="pagetitle" value="search_result" />

           <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

           <input type="hidden" name="basic_search" value="basic_search" />

           <?php //---------------------------------START  GENERAL SEARCH---------------------------------------  ?>


           <div class="heading-text"><strong><?php echo language_code('DSP_GENERAL'); ?></strong></div>

           <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_I_AM'); ?></div>
                <select name="gender">

                    <option value="M"><?php echo language_code('DSP_MAN'); ?></option>

                    <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>

                    <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                    <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>

                    <?php } ?>



                </select>
            </div>
        </label>

        <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_SEEKING_A'); ?></div>
                <select name="seeking">
                    <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>

                    <option value="M"><?php echo language_code('DSP_MAN'); ?></option>

                    <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                    <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>

                    <?php } ?>

                </select>
            </div>
        </label>

        <div class="heading-text"><strong><?php echo language_code('DSP_AGE') ?></strong></div>
        <div class="col-cont clearfix">
        <div class="col-2">
                <label class="select-group">
                  <select name="age_from" > 

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
            </label>
        </div>
        <div class="col-2">
            <label class="select-group">

               <div  class="mam_reg_lf select-label"> 
                <select name="age_to" >
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
        </label>
    </div>
</div>
<label data-role="fieldcontain" class="select-group">  
    <div class="clearfix">                                    
        <div class="mam_reg_lf select-label"><?php echo language_code('DSP_COUNTRY'); ?></div>
        <select name="cmbCountry" id="cmbCountry_id">
            <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY'); ?></option>
            <?php
            $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
            foreach ($strCountries as $rdoCountries) {
                echo "<option value='" . $rdoCountries->name . "' >" . $rdoCountries->name . "</option>";
            }
            ?>
        </select>
    </div>
</label>
<label data-role="fieldcontain" class="select-group">  
    <div class="clearfix">                                    
        <div class="mam_reg_lf select-label"><?php echo language_code('DSP_TEXT_STATE'); ?></div>

        <div id="state_change">
            <select name="cmbState" id="cmbState_id">
                <option value="0"><?php echo language_code('DSP_SELECT_STATE'); ?></option>
            </select>
        </div>
        </div>
        </label>

        <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_CITY'); ?></div>

                <div id="city_change">
                    <select name="cmbCity" id="cmbCity_id">
                        <option value="0"><?php echo language_code('DSP_SELECT_CITY'); ?></option>
                    </select>
                </div>
            </div>
        </label>
        <!-- End city combo-->



         <div class="heading-text"><strong><?php echo language_code('DSP_ADDITIONAL_OPTIONS'); ?></strong></div>
                <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_SEARCH_ONLINE_ONLY') ?></div>
                        <select name="Online_only">

                            <option value="N"><?php echo language_code('DSP_OPTION_NO') ?></option>
                            <option value="Y"><?php echo language_code('DSP_OPTION_YES') ?></option>
                        </select>
                        </div>
                        </label>
                     <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_SEARCH_WITH_PICTURE_ONLY') ?></div> 
                        <select name="Pictues_only">

                            <option value="P"><?php echo language_code('DSP_OPTION_NO_PREFERENCE') ?></option>

                            <option value="N"><?php echo language_code('DSP_OPTION_NO') ?></option>

                            <option value="Y"><?php echo language_code('DSP_OPTION_YES') ?></option>
                        </select>
                    </div>
                    </label>

                        <label data-role="fieldcontain" class="form-group">  
                        <div class="clearfix">                                    
                            <div class="mam_reg_lf form-label"><?php echo language_code('DSP_USER_NAME'); ?></div>
                        <input type="text" name="username" value="" />
                        </div>
                        </label>
                          <label class="search-label" > <input class="checkbox-singleline" type="checkbox" name="check_save" value="SS" />
                            <?php echo language_code('DSP_SAVE_THIS_SEARCH'); ?></label>
                           <input type="text" name="savesearch" value="" class="input-control" placeholder="<?php echo language_code('SEARCH_NAME'); ?>"/>
                           
                           <input type="hidden" name="search_type" value="basic"/>
                           <div class="btn-blue-wrap">
                            <input type="button" name="submit"  class="mam_btn btn-blue" value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>" onclick="viewSearch(0, 'post');" />
                    </div>
                    </div>


        </div>
        <?php include_once('dspNotificationPopup.php'); // for notification pop up      ?>
    </div>

</form>
<?php
//-------------------------------------END ADDITIONAL OPTIONS SEARCH -------------------------------------// ?>