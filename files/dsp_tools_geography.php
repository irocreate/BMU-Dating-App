<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
include_once(WP_DSP_ABSPATH . "general_settings.php");
global $wpdb;
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$edit_country_id = isset($_REQUEST['edit_country_id']) ? $_REQUEST['edit_country_id'] : '';
$edit_state_id = isset($_REQUEST['edit_state_id']) ? $_REQUEST['edit_state_id'] : '';
$edit_city_id = isset($_REQUEST['edit_city_id']) ? $_REQUEST['edit_city_id'] : '';
$txtCountry = isset($_REQUEST['txtcounty']) ? $_REQUEST['txtcounty'] : '';
$txtState = isset($_REQUEST['txtstate']) ? $_REQUEST['txtstate'] : '';
$txtCity = isset($_REQUEST['txtcity']) ? $_REQUEST['txtcity'] : '';
$update_geo = isset($_REQUEST['update_geo']) ? $_REQUEST['update_geo'] : '';
$settings_root_link = get_bloginfo('url') . "/wp-admin/admin.php?page=dsp-admin-sub-page3";
$successMsg  = '';
if ($update_geo == "add_country") {
    //Check to make sure that the Country field is not empty
    if (trim($_POST['txtcounty']) == "") {
        $countrynameError = "You forgot to Enter Country name in Country text field.";
        $hasError = true;
    } else {
        $txtCountry = trim($_POST['txtcounty']);
    }

    //If there is no error, then profile updated
    if (!isset($hasError)) {
        $num_rows1 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_country_table WHERE country_id='$edit_country_id'");
        if ($num_rows1 > 0) {
            $wpdb->query("UPDATE $dsp_country_table SET name='$txtCountry' WHERE country_id='$edit_country_id'");
            $country_updated = true;
        } else {
            $wpdb->query("INSERT INTO $dsp_country_table SET name = '$txtCountry'");
            $Country_added = true;
        } // End if($num_rows1>0){
    } // End if(!isset($hasError))
} // if($update_geo=="add_country")

if($update_geo == 'setting_update'){
    // defaults country id as setting value
    $defaultCountry = isset($_REQUEST['default_country']) ? $_REQUEST['default_country'] : '';
     // Search form in home page display options
    $searchFormType = isset($_REQUEST['search_form_options']) ? $_REQUEST['search_form_options'] : '';

    $updateSetting = array(
                            'default_country' => $defaultCountry,
                            'search_form_options' => $searchFormType
                        );
    $result = array();
    foreach ($updateSetting as $key => $value) {
        $format = is_numeric($value) ? '%d' : '%s';
        $result[] = $wpdb->query($wpdb->prepare("UPDATE $dsp_general_settings_table SET setting_value = '$format',setting_status = 'Y' WHERE setting_name = '%s'",array($value,$key)));
    }
    
    if(in_array(0,$result)){
        $_SESSION['successMsg'] = language_code('DSP_SETTINGS_SAVED_MESSAGE');
    }
    ?>
    <script>location.href = "<?php
echo add_query_arg(array('pid' => 'tools_geography',
    'updated' => 'true'), $settings_root_link);
?>"</script>
<?php    
}

if ($update_geo == "add_state") {
    //Check to make sure that the State field is not empty
    if (trim($_POST['txtstate']) == "") {
        $statenameError = "You forgot to Enter State name in State text field.";
        $hasError = true;
    } else {
        $txtState = trim($_POST['txtstate']);
    }

    if ($edit_state_id == "") {
        //Check to make sure that the State field is not empty
        if (trim($_POST['cmbCountry']) == 0) {
            $selectcountryError = "You forgot to Select Country from Country dropdown field.";
            $hasError = true;
        } else {
            $cmbCountry_id = trim($_POST['cmbCountry']);
        }
    }

    //If there is no error, then profile updated
    if (!isset($hasError)) {
        $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_state_table WHERE state_id='$edit_state_id'");
        $scountry = $wpdb->get_row("SELECT * FROM $dsp_state_table WHERE state_id='$edit_state_id'");
        if ($num_rows > 0) {
            $wpdb->query("UPDATE $dsp_state_table SET country_id='$scountry->country_id',name = '$txtState' WHERE state_id='$edit_state_id'");
            $State_updated = true;
        } else {
            $num_rows2 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_state_table WHERE country_id='$cmbCountry_id' AND name LIKE '%" . $txtState . "%'");
            if ($num_rows <= 0) {
                $wpdb->query("INSERT INTO $dsp_state_table SET country_id='$cmbCountry_id',name = '$txtState'");
            }
            $State_added = true;
        } // if($num_rows>0){
    } // End if(!isset($hasError))
} // if($update_geo=="add_country")

if ($update_geo == "add_city") { 
    //Check to make sure that the City field is not empty
    if (trim($_POST['txtcity']) == "") {
        $statenameError = "You forgot to Enter City name in City text field.";
        $hasError = true;
    } else {
        $txtCity = trim($_POST['txtcity']);
    }


    if ($edit_city_id == "") {
        //Check to make sure that the State dropdown field is not empty
        if (trim($_POST['cmbCountry']) == 0) {
            $selectcountryError = "You forgot to Select Country from Country dropdown field.";
            $hasError = true;
        } else {
            $cmbCountry_id = trim($_POST['cmbCountry']);
        }

        //Check to make sure that the Country dropdown field is not empty
//	if(trim($_POST['cmbState']) == 0) {
//	  $selectstateError = "You forgot to Select State from State dropdown field.";
//	  $hasError = true;
//	} 
        if (trim($_POST['cmbState']) == 0) {
            $cmbState_id = trim($_POST['cmbState']);
        } else {
            $cmbState_id = trim($_POST['cmbState']);
        }
    }

    //If there is no error, then  updated
    if (!isset($hasError)) {

        $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_city_table WHERE city_id='$edit_city_id'");
        $svalues = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE city_id='$edit_city_id'");
        if ($num_rows > 0) {
            $wpdb->query("UPDATE $dsp_city_table SET country_id='$svalues->country_id',state_id='$svalues->state_id',name = '$txtCity' WHERE city_id='$edit_city_id'");
            $City_updated = true;
        } else {
            $num_rows2 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_city_table WHERE name ='" . $txtCity . "' AND state_id='$cmbState_id'");
            if ($num_rows2 <= 0) {
                $wpdb->query("INSERT INTO $dsp_city_table SET country_id='$cmbCountry_id',state_id='$cmbState_id',name = '$txtCity'");
            }
            $City_added = true;
        } // if($num_rows>0){
    } // End if(!isset($hasError))
} // if($update_geo=="add_city")
//-------------------------------------- DELETE COUNTRY , STATE AND CITY ------------------------------------------------- //
if ($update_geo == "delete_country") {

    //Check to make sure that the State field is not empty
    if (trim($_POST['cmbCountry']) == 0) {
        $delselectcountryError = "Please Select Country which you want to Delete.";
        $hasError = true;
    } else {
        $cmbCountry_id = trim($_POST['cmbCountry']);
    }

    //If there is no error, then updated
    if (!isset($hasError)) {
        $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_country_table WHERE  country_id='$cmbCountry_id'");
        if ($num_rows > 0) {
            $wpdb->query("DELETE FROM $dsp_country_table WHERE country_id='$cmbCountry_id'");
            $country_deleted = true;
        } // if($num_rows<=0){
    } // End if(!isset($hasError))
} // if($update_geo=="delete_country")


if ($update_geo == "delete_state") {


    //Check to make sure that the State dropdown field is not empty
    if (trim($_POST['cmbState']) == 0) {
        $delselectstateError = "Please Select State which you want to Delete.";
        $hasError = true;
    } else {
        $cmbState_id = trim($_POST['cmbState']);
    }

    //If there is no error, then updated
    if (!isset($hasError)) {
        $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_state_table WHERE  state_id='$cmbState_id'");
        if ($num_rows > 0) {
            $wpdb->query("DELETE FROM $dsp_state_table WHERE state_id='$cmbState_id'");
            $state_deleted = true;
        } // if($num_rows<=0){
    } // End if(!isset($hasError))
} // if($update_geo=="delete_state")

if ($update_geo == "delete_city") {


    //Check to make sure that the City field is not empty
    if (trim($_POST['cmbCity']) == 0) {
        $delcitynameError = "Please Select City which you want to Delete.";
        $hasError = true;
    } else {
        $cmbCity_id = trim($_POST['cmbCity']);
    }

    //If there is no error, then updated
    if (!isset($hasError)) {
        $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_city_table WHERE city_id='$cmbCity_id'");
        if ($num_rows > 0) {
            $wpdb->query("DELETE FROM $dsp_city_table WHERE city_id='$cmbCity_id'");
            $city_deleted = true;
        } // if($num_rows<=0){
    } // End if(!isset($hasError))
} // if($update_geo=="delete_city")
//-------------------------------------- END DELETE COUNTRY ,STATE AND CITY -------------------------------------------------- //
?>
<?php if (isset($city_deleted) && $city_deleted == true) { ?>
    <div id="message" class="updated fade"><strong>City Deleted.</strong></div>
<?php } ?>
<?php if (isset($State_updated) && $State_updated == true) { ?>
    <div id="message" class="updated fade"><strong>State Updated.</strong></div>
<?php } ?>
<?php if (isset($country_updated) && $country_updated == true) { ?>
    <div id="message" class="updated fade"><strong>Country Updated.</strong></div>
<?php } ?>
<?php if (isset($country_deleted) && $country_deleted == true) { ?>
    <div id="message" class="updated fade"><strong>Country Deleted.</strong></div>
<?php } ?>
<?php if (isset($state_deleted) && $state_deleted == true) { ?>
    <div id="message" class="updated fade"><strong>State Deleted.</strong></div>
<?php } ?>
<?php if (isset($State_added) && $State_added == true) { ?>
    <div id="message" class="updated fade"><strong>State Added.</strong></div>
<?php } ?>
<?php if (isset($Country_added) && $Country_added == true) { ?>
    <div id="message" class="updated fade"><strong>Country Added.</strong></div>
<?php } ?>
<?php if (isset($City_added) && $City_added == true) { ?>
    <div id="message" class="updated fade"><strong>City Added.</strong></div>
<?php } ?>
<?php if (isset($City_updated) && $City_updated == true) { ?>
    <div id="message" class="updated fade"><strong>City Updated.</strong></div>
<?php } ?>
<?php if (isset($countrynameError) && $countrynameError != '') { ?>
    <div class="error"><strong><?php echo $countrynameError; ?></strong></div>
<?php } ?>
<?php if (isset($statenameError) && $statenameError != '') { ?>
    <div class="error"><strong><?php echo $statenameError; ?></strong></div>
<?php } ?>	
<?php if (isset($alerady_exists) && $alerady_exists != '') { ?>
    <div class="error"><strong><?php echo $alerady_exists; ?></strong></div>
<?php } ?>
<?php if (isset($selectcountryError) && $selectcountryError != '') { ?>
    <div class="error"><strong><?php echo $selectcountryError; ?></strong></div>
<?php } ?>	
<?php if (isset($selectstateError) && $selectstateError != '') { ?>
    <div class="error"><strong><?php echo $selectstateError; ?></strong></div>
<?php } ?>

<?php if (isset($delselectcountryError) && $delselectcountryError != '') { ?>
    <div class="error"><strong><?php echo $delselectcountryError; ?></strong></div>
<?php } ?>
<?php if (isset($updateselectcountryError) && $updateselectcountryError != '') { ?>
    <div class="error"><strong><?php echo $updateselectcountryError; ?></strong></div>
<?php } ?>
<?php if (isset($delselectstateError) && $delselectstateError != '') { ?>
    <div class="error"><strong><?php echo $delselectstateError; ?></strong></div>
<?php } ?>
<?php if (isset($delcitynameError) && $delcitynameError != '') { ?>
    <div class="error"><strong><?php echo $delcitynameError; ?></strong></div>
<?php } ?>
<?php if(!isset($_SESSION['successMsg'])): ?>
<div>
    <strong style="color:green;">
        <?php
            if(isset($_SESSION['successMsg'])){
                echo $_SESSION['successMsg'];
                unset($_SESSION['successMsg']);
            }
        ?>
    </strong>
</div>
<?php endif; ?>
<form name="add_dsp_geography" action="" method="post">
    <input type="hidden" name="update_geo" value="">
    <div id="state_div" style="display:none"></div>
    <div id="city_div" style="display:none"></div>
    <div class="dsp_wrap">
        <div id="general" class="postbox">
            <h3 class="hndle"><span><?php echo language_code('DSP_TOOL_GEOGRAPHY') ?></span></h3>
            <div class="dsp_wrap dsp_thumbnails1">
                <?PHP //**************************  DISPLAY COUNTRY DROPDOWN, TEXT AND EDIT,DELETE,ADD BUTTON *************************//  ?>
                <?php
                if ($update_geo == "update_country") {
                    $cCountry_id = trim($_POST['cmbCountry']);
                    $dsp_updates1 = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE country_id = '$cCountry_id'");
                    $dsp_updates1->name;
                    $dsp_updates1->country_id;
                } // End if($update_geo=="update_country") 
                ?>
                <div class="dsp_col1"><strong><?php echo language_code('DSP_COUNTRY') ?></strong>
                    <input type="hidden" name="edit_country_id" id="edit_country_id" value="" />
                </div>
                <div class="dsp_col2">
                    <!--<select name="cmbCountry" id="cmbCountry" style="width:150px;" onChange="Show(this.value)">-->
                    <select name="cmbCountry" id="cmbCountry" style="width:150px;" onChange="document.add_dsp_geography.submit();">
                        <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY') ?></option>
                        <?php
                        $countries = $wpdb->get_results("SELECT * FROM $dsp_country_table Order by name");
                        foreach ($countries as $country) {
                            ?>	
                            <option value="<?php echo $country->country_id; ?>" <?php
                            if (isset($_POST['cmbCountry']) && $country->country_id == $_POST['cmbCountry']) {
                                echo 'selected="selected"';
                            }
                            ?>><?php echo $country->name; ?></option>
                                <?php } ?>
                    </select>
                </div>
                <div class="dsp_col3">
                    <input type="button" name="edit_button" class="button" value="<?php echo language_code('DSP_EDIT'); ?>" onclick="update_dsp_country();"/>
                    &nbsp;<input type="button" name="delete_button" class="button" value="<?php echo language_code('DSP_DELETE'); ?>" onclick="delete_dsp_country();" /></div>
                <div class="dsp_col4"><input type="text" id="txtcounty" name="txtcounty" value="" /></div>
                <div class="dsp_col5">
                    <input type="button" name="add_country_button" id="country_button" class="button" value="<?php echo language_code('DSP_ADD'); ?>" onClick="add_dsp_country();" />
                </div>
                <?PHP //**************************  DISPLAY COUNTRY DROPDOWN, TEXT AND EDIT,DELETE,ADD BUTTON *************************//  ?>
                <div class="clear"></div>
                <?PHP //**************************  DISPLAY STATE DROPDOWN, TEXT AND EDIT,DELETE,ADD BUTTON *************************//   ?>
                <div class="dsp_col1"><strong><?php echo language_code('DSP_TEXT_STATE') ?></strong>
                    <input type="hidden" name="edit_state_id" id="edit_state_id" value="" />
                </div>
                <div class="dsp_col2" id="statedropdown">
                   <!--<select name="cmbState" style="width:150px; float:left;" onChange="Show2(this.value)">-->
                    <select name="cmbState" id="cmbState" style="width:150px; float:left;" onChange="document.add_dsp_geography.submit();">
                        <option value="0" id="0" >Select State</option>
                        <?php
                        $states = $wpdb->get_results("SELECT * FROM $dsp_state_table where country_id='" . $_POST['cmbCountry'] . "' Order by name");
                        if(isset($_POST['cmbState'])){
                        foreach ($states as $state) {
                            ?>	
                            <option value="<?php echo $state->state_id; ?>" <?php
                            if ($state->state_id == $_POST['cmbState']) {
                                echo 'selected="selected"';
                            }
                            ?>><?php echo $state->name; ?></option>
                                <?php }
                            if(empty($states)):
                         ?>
                              <option value="non" id="000" <?php $cmbState = isset($_POST['cmbState']) ? $_POST['cmbState'] : '';if($cmbState == 'non'){echo 'selected="selected"';} ?>>Non State</option>
                           <?php endif; ?>
                      <?php } ?>
              <!--<option value="1"><?php echo language_code('DSP_SELECT_STATE') ?></option>-->
                    </select>
                    <div id="load_img_id" style="display:none; float:left"> 
                        <img src="<?php echo WPDATE_URL . '/images/loading.gif' ?>" border="0" width="20" height="20" alt="Loading" />	   </div>		 
                </div>
                <div class="dsp_col3">
                    <input type="button" name="edit_button" class="button" value="<?php echo language_code('DSP_EDIT'); ?>" onclick="update_dsp_state();"/>&nbsp;
                    <input type="button" name="delete_button" class="button" value="<?php echo language_code('DSP_DELETE'); ?>" onclick="delete_dsp_state();" />
                </div>
                <div class="dsp_col4"><input type="text" id="txtstate" name="txtstate" value="" /></div>
                <div class="dsp_col5">
                    <input type="button" name="add_button" id="state_button" class="button" value="<?php echo language_code('DSP_ADD'); ?>"  onClick="add_dsp_state();"/></div>
                <?PHP //**************************  DISPLAY STATE DROPDOWN, TEXT AND EDIT,DELETE,ADD BUTTON *************************//   ?>
                </FORM>
                <div class="clear"></div>  
                <?PHP //**************************  DISPLAY CITY DROPDOWN, TEXT AND EDIT,DELETE,ADD BUTTON *************************//   ?>
                <div class="dsp_col1"><strong><?php echo language_code('DSP_CITY') ?></strong>
                    <input type="hidden" id="edit_city_id" name="edit_city_id" value="" />
                </div>
                <div class="dsp_col2" id="citydropdown">
                    <select name="cmbCity" id="cmbCity" style="width:150px; float:left;">
                        <option value="0"><?php echo language_code('DSP_SELECT_CITY') ?></option>
                        <?php
                        $cities = $wpdb->get_results("SELECT * FROM $dsp_city_table where country_id='" . $_POST['cmbCountry'] . "' AND state_id = '" . $_POST['cmbState'] . "' Order by name");
                        foreach ($cities as $city) {
                            ?>	
                            <option value="<?php echo $city->city_id ?>"><?php echo $city->name ?></option>
                        <?php } ?>
                    </select>
                    <div id="load_img_id2" style="display:none; float:left"> 
                        <img src="<?php echo WPDATE_URL . '/images/loading.gif' ?>" border="0" width="20" height="20" alt="Loading" />	</div>	
                </div>
                <div class="dsp_col3"><input type="button" name="edit_button" class="button" value="<?php echo language_code('DSP_EDIT'); ?>" onclick="update_dsp_city();" />&nbsp;
                    <input type="button" name="delete_button" class="button" value="<?php echo language_code('DSP_DELETE'); ?>" onclick="delete_dsp_city();" /></div>
                <div class="dsp_col4"><input type="text" name="txtcity" id="txtcity" value="" /></div>
                <div class="dsp_col5"><input type="button" name="add_button" id="city_button" class="button" value="<?php echo language_code('DSP_ADD'); ?>" onclick="add_dsp_city();"/></div>
                <?PHP //**************************  DISPLAY CITY DROPDOWN, TEXT AND EDIT,DELETE,ADD BUTTON *************************//   ?>
                <div class="clear"></div>
            </div>
            <br><br>
            <div class="dsp_note">Note : To add a new City to a specific state,select the country, then the state,then you can add or edit cities in that state:</div>
        </div>
    </div>
</form>
<form name="frmgeneralsettings" method="post" action="<?php
    echo add_query_arg(array(
        'pid' => 'tools_geography', 'update_geo' => 'setting_update'), $settings_root_link);
    ?>">
    <div class="dsp_wrap">
        <div id="general" class="postbox">
            <h3 class="hndle"><span><?php echo language_code('DSP_SETTINGS') ?></span></h3>
            <table>
                <!-- Module to select default country -->
                <tr >
                    <td style="width:112px;" class="form-field form-required" id="head"><?php echo language_code('DSP_SELECT_DEFAULT_COUNTRY'); ?></td>
                    <td style="width:100px;" >
                        <select name="default_country" id="default_country_id"  class="dspdp-form-control dsp-form-control">
                            <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY'); ?></option>
                            <?php
                            $dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
                            $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
                            foreach ($strCountries as $rdoCountries) {
                                $selected = ($rdoCountries->country_id == $check_default_country->setting_value) ? "selected = selected" : "";
                                echo "<option value='" . $rdoCountries->country_id . "' $selected >" . $rdoCountries->name . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td align="left" ><span class="description"><?php _e(language_code('DSP_SELECT_DEFAULT_COUNTRY_TEXT')) ?></span> </td>  
                </tr>
                <!-- End  of Module to select default country -->
                
                <!--  Search form type geocode  or old way in home page  --> 
                <!-- old way - ow
                     new way - nw
                     none - none
                -->
                <tr>
                    <td class="form-field form-required display-option-head" id="head"><?php echo language_code('DSP_SEARCH_FORM_TYPE'); ?></td>
                    <td>
                       
                        <select name="search_form_options" style="width:210px;">
                            <?php 
                                if ($check_search_from_option->setting_value == 'ow') : // Old way search form ?>
                                        <option value="ow" selected="selected"><?php echo language_code('DSP_SEARCH_BY_LOCATION'); ?></option>
                                        <option value="nw"><?php echo language_code('DSP_SEARCH_BY_GEOGRAPHY'); ?></option>
                                        <option value="nn"><?php echo language_code('DSP_NONE'); ?></option>
                                       
                            <?php elseif ($check_search_from_option->setting_value == 'nw') : // new way search form by using Google geography  ?>
                                       <option value="ow" ><?php echo language_code('DSP_SEARCH_BY_LOCATION'); ?></option>
                                       <option value="nw" selected="selected"><?php echo language_code('DSP_SEARCH_BY_GEOGRAPHY'); ?></option> 
                                       <option value="nn" ><?php echo language_code('DSP_NONE'); ?></option>
                            <?php else : //No country search ?>
                                        <option value="ow"><?php echo language_code('DSP_SEARCH_BY_LOCATION'); ?></option>
                                        <option value="nw" ><?php echo language_code('DSP_SEARCH_BY_GEOGRAPHY'); ?></option>
                                        <option value="nn" selected="selected"><?php echo language_code('DSP_NONE'); ?></option>
                            <?php endif; ?>
                        </select>
                    </td>
                    <td><span class="description" style="white-space:nowrap;">&nbsp;<?php echo language_code('DSP_SELECT_LAYOUT_TO_DISPLAY_MEMBERS'); ?></span></td>
                </tr>
                <tr><td height="20px">&nbsp;</td></tr>
                <tr>
                    <td align="left" height="30px">
                        <input type="submit" name="Submit" value="<?php _e('Save Changes', 'dsp_trans_domain') ?>" class="button button-primary" />
                    </td>
                </tr>
                
        </table>
        </div>
    </div>
</form>
<br />
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>