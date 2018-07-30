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

<form id="frmsearch" name="frmadvsearch" method="POST" action="">

    <div class="ui-content" data-role="content">
        <div class="content-primary">	
           <!--<input type="hidden" name="pid" value="5" />-->
           <input type="hidden" name="pagetitle" value="search_result" />
           <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
           <?php //---------------------------------START  GENERAL SEARCH--------------------------------------- ?>

           <div class="heading-text"><?php echo language_code('DSP_GENERAL'); ?></div>

           <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_I_AM') ?></div>
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
        <div class="heading-text"><?php echo language_code('DSP_AGE') ?></div>
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
                            <?php
                        } else {
                            ?>
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

<!-- End City combo-->
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
<label data-role="fieldcontain" class="select-group">  
    <div class="clearfix">                                    
        <div class="mam_reg_lf select-label"><?php echo language_code('DSP_SEARCH_WITH_PICTURE_ONLY'); ?></div>
        <select name="Pictues_only">
            <option value="P"><?php echo language_code('DSP_OPTION_NO_PREFERENCE'); ?></option>
            <option value="N"><?php echo language_code('DSP_OPTION_NO'); ?></option>
            <option value="Y"><?php echo language_code('DSP_OPTION_YES'); ?></option>
        </select>
    </div>
</label>
</div>



<?php //-----------------------------------------END GENERAL SEARCH-------------------------------------------//   ?>


<?php //-------------------------------------START ADDITIONAL OPTIONS SEARCH -------------------------------------//   ?>
 <div class="heading-text"><?php echo language_code('DSP_ADDITIONAL_OPTIONS'); ?></div>

        <?php
        $dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;

        $all_languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where display_status='1' ");
        $language_name = $all_languages->language_name;

        if ($language_name == 'english') {
            $tableName1 = "dsp_profile_setup";

            $tableName = "dsp_question_options";
        } else {
            $tableName1 = "dsp_profile_setup_" . substr($language_name, 0, 2);

            $tableName = "dsp_question_options_" . substr($language_name, 0, 2);
        }

        $dsp_question_options_table = $wpdb->prefix . $tableName;
        $dsp_profile_setup_table = $wpdb->prefix . $tableName1;


        $myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id=1 Order by sort_order");

        foreach ($myrows as $profile_questions) {
            $ques_id = $profile_questions->profile_setup_id;
            $profile_ques = $profile_questions->question_name;
            $profile_ques_type_id = $profile_questions->field_type_id;
            ?>


            <div class="text-title"><strong><?php echo $profile_ques; ?></strong></div>
            <ul class="option-btn-adv">
                <?php
                $myrows_options = $wpdb->get_results("SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order");
                $i = 0;

                foreach ($myrows_options as $profile_questions_options) {
                    ?>
                    <li><label class="search-chkbox"><input type="checkbox" name="profile_question_option_id[]" value="<?php echo $profile_questions_options->question_option_id ?>"/></label><span class="search-text"><?php echo $profile_questions_options->option_value ?></span></li>
                    <?php
                    $i++;
                }
                ?>
            </ul>
            <?php } ?>
            <ul class="search-field-bottom">
                <li>
                 <input type="checkbox" name="check_save" value="SS" class="pull-left" />
                    <label class="spacer-bottom-sm"><?php echo language_code('DSP_SAVE_THIS_SEARCH_AS'); ?></label>
                   
                    <input type="text" name="savesearch" class="input-control" value=""/>
                    <input type="hidden" name="search_type" value="Advanced"/>
                </li>

                <li>
                 <div class="btn-blue-wrap">
                    <input type="button" name="submit" class="mam_btn btn-blue" value="<?php echo language_code('DSP_SUBMIT_BUTTON') ?>" onclick="viewSearch(0, 'post');" />
                </div>
                </li>
            </ul>
        </div>


    </form>



</div>
<?php include_once('dspNotificationPopup.php'); // for notification pop up     ?>
</div>

<?php
//-------------------------------------END ADDITIONAL OPTIONS SEARCH -------------------------------------// ?>