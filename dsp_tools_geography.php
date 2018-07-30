<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$edit_country_id = $_POST['edit_country_id'];
$edit_state_id = $_POST['edit_state_id'];
$edit_city_id = $_POST['edit_city_id'];
$txtCountry = $_POST['txtcounty'];
$txtState = $_POST['txtstate'];
$txtCity = $_POST['txtcity'];
$update_geo = $_POST['update_geo'];
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
            $wpdb->query($wpdb->prepare("UPDATE $dsp_country_table SET name='$txtCountry' WHERE country_id='$edit_country_id'"));
            $country_updated = true;
        } else {
            $wpdb->query($wpdb->prepare("INSERT INTO $dsp_country_table SET name = '$txtCountry'"));
            $Country_added = true;
        } // End if($num_rows1>0){
    } // End if(!isset($hasError))
} // if($update_geo=="add_country")


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
            $wpdb->query($wpdb->prepare("UPDATE $dsp_state_table SET country_id='$scountry->country_id',name = '$txtState' WHERE state_id='$edit_state_id'"));
            $State_updated = true;
        } else {
            $num_rows2 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_state_table WHERE country_id='$cmbCountry_id' AND name LIKE '%" . $txtState . "%'");
            if ($num_rows <= 0) {
                $wpdb->query($wpdb->prepare("INSERT INTO $dsp_state_table SET country_id='$cmbCountry_id',name = '$txtState'"));
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
            $wpdb->query($wpdb->prepare("UPDATE $dsp_city_table SET country_id='$svalues->country_id',state_id='$svalues->state_id',name = '$txtCity' WHERE city_id='$edit_city_id'"));
            $City_updated = true;
        } else {
            $num_rows2 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_city_table WHERE name LIKE '%" . $txtCity . "%' AND state_id='$cmbState_id'");
            if ($num_rows2 <= 0) {
                $wpdb->query($wpdb->prepare("INSERT INTO $dsp_city_table SET country_id='$cmbCountry_id',state_id='$cmbState_id',name = '$txtCity'"));
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
            $wpdb->query($wpdb->prepare("DELETE FROM $dsp_country_table WHERE country_id='$cmbCountry_id'"));
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
            $wpdb->query($wpdb->prepare("DELETE FROM $dsp_state_table WHERE state_id='$cmbState_id'"));
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
            $wpdb->query($wpdb->prepare("DELETE FROM $dsp_city_table WHERE city_id='$cmbCity_id'"));
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
<?php if ($countrynameError != '') { ?>
    <div class="error"><strong><?php echo $countrynameError; ?></strong></div>
<?php } ?>
<?php if ($statenameError != '') { ?>
    <div class="error"><strong><?php echo $statenameError; ?></strong></div>
<?php } ?>	
<?php if ($alerady_exists != '') { ?>
    <div class="error"><strong><?php echo $alerady_exists; ?></strong></div>
<?php } ?>
<?php if ($selectcountryError != '') { ?>
    <div class="error"><strong><?php echo $selectcountryError; ?></strong></div>
<?php } ?>	
<?php if ($selectstateError != '') { ?>
    <div class="error"><strong><?php echo $selectstateError; ?></strong></div>
<?php } ?>

<?php if ($delselectcountryError != '') { ?>
    <div class="error"><strong><?php echo $delselectcountryError; ?></strong></div>
<?php } ?>
<?php if ($updateselectcountryError != '') { ?>
    <div class="error"><strong><?php echo $updateselectcountryError; ?></strong></div>
<?php } ?>
<?php if ($delselectstateError != '') { ?>
    <div class="error"><strong><?php echo $delselectstateError; ?></strong></div>
<?php } ?>
<?php if ($delcitynameError != '') { ?>
    <div class="error"><strong><?php echo $delcitynameError; ?></strong></div>
<?php } ?>
<form name="add_dsp_geography" action="" method="post">
    <input type="hidden" name="update_geo" value="">
    <div id="state_div" style="display:none"></div>
    <div id="city_div" style="display:none"></div>
    <div class="dsp_wrap">
        <div><div class="dsp_admin_headings"></div></div>
        <br>

        <div class="dsp_wrap dsp_thumbnails1">
            <?PHP //**************************  DISPLAY COUNTRY DROPDOWN, TEXT AND EDIT,DELETE,ADD BUTTON *************************// ?>
            <?php
            if ($update_geo == "update_country") {
                $cCountry_id = trim($_POST['cmbCountry']);
                $dsp_updates1 = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE country_id = '$cCountry_id'");
                $dsp_updates1->name;
                $dsp_updates1->country_id;
            } // End if($update_geo=="update_country") 
            ?>
            <div class="dsp_col1"><strong><?php echo language_code('DSP_COUNTRY'); ?></strong>
                <input type="hidden" name="edit_country_id" id="edit_country_id" value="" />
            </div>
            <div class="dsp_col2">
                <select name="cmbCountry" id="cmbCountry" style="width:150px;" onChange="Show(this.value)">
                    <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY'); ?></option>
                    <?php
                    $countries = $wpdb->get_results("SELECT * FROM $dsp_country_table Order by name");
                    foreach ($countries as $country) {
                        ?>	
                        <option value="<?php echo $country->country_id; ?>" ><?php echo $country->name; ?></option>
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
            <?PHP //**************************  DISPLAY COUNTRY DROPDOWN, TEXT AND EDIT,DELETE,ADD BUTTON *************************// ?>
            <div class="clear"></div>
            <?PHP //**************************  DISPLAY STATE DROPDOWN, TEXT AND EDIT,DELETE,ADD BUTTON *************************//  ?>
            <div class="dsp_col1"><strong><?php echo language_code('DSP_TEXT_STATE') ?></strong>
                <input type="hidden" name="edit_state_id" id="edit_state_id" value="" />
            </div>
            <div class="dsp_col2" id="statedropdown">
                <select name="cmbState" style="width:150px; float:left;" onChange="Show2(this.value)">
                    <option value="0" id="0" >Select State</option>
                    <option value="000" id="000" >Non State</option>
         <!--<option value="1"><?php echo DSP_SELECT_STATE ?></option>-->
                </select>
                <div id="load_img_id" style="display:none; float:left"> 
                    <img src="<?php echo WPDATE_URL . '/images/loading.gif'; ?>" border="0" width="20" height="20" alt="Loading" />	   </div>		 
            </div>
            <div class="dsp_col3">
                <input type="button" name="edit_button" class="button" value="<?php echo language_code('DSP_EDIT'); ?>" onclick="update_dsp_state();"/>&nbsp;
                <input type="button" name="delete_button" class="button" value="<?php echo language_code('DSP_DELETE'); ?>" onclick="delete_dsp_state();" />
            </div>
            <div class="dsp_col4"><input type="text" id="txtstate" name="txtstate" value="" /></div>
            <div class="dsp_col5">
                <input type="button" name="add_button" id="state_button" class="button" value="<?php echo language_code('DSP_ADD'); ?>"  onClick="add_dsp_state();"/></div>
            <?PHP //**************************  DISPLAY STATE DROPDOWN, TEXT AND EDIT,DELETE,ADD BUTTON *************************//  ?>
            </FORM>
            <div class="clear"></div>  
            <?PHP //**************************  DISPLAY CITY DROPDOWN, TEXT AND EDIT,DELETE,ADD BUTTON *************************//  ?>
            <div class="dsp_col1"><strong><?php echo language_code('DSP_CITY') ?></strong>
                <input type="hidden" id="edit_city_id" name="edit_city_id" value="" />
            </div>
            <div class="dsp_col2" id="citydropdown">
                <select name="cmbCity" style="width:150px; float:left;">
                    <option value="0"><?php echo language_code('DSP_SELECT_CITY') ?></option>
                </select>
                <div id="load_img_id2" style="display:none; float:left"> 
                    <img src="<?php echo WPDATE_URL . '/images/loading.gif'; ?>" border="0" width="20" height="20" alt="Loading" />	</div>	
            </div>
            <div class="dsp_col3"><input type="button" name="edit_button" class="button" value="<?php echo language_code('DSP_EDIT'); ?>" onclick="update_dsp_city();" />&nbsp;
                <input type="button" name="delete_button" class="button" value="<?php echo language_code('DSP_DELETE'); ?>" onclick="delete_dsp_city();" /></div>
            <div class="dsp_col4"><input type="text" name="txtcity" id="txtcity" value="" /></div>
            <div class="dsp_col5"><input type="button" name="add_button" id="city_button" class="button" value="<?php echo language_code('DSP_ADD'); ?>" onclick="add_dsp_city();"/></div>
            <?PHP //**************************  DISPLAY CITY DROPDOWN, TEXT AND EDIT,DELETE,ADD BUTTON *************************//  ?>
            <div class="clear"></div>
        </div>
        <br><br>
        <div class="dsp_note">Note : To add a new City to a specific state,select the country, then the state,then you can add or edit cities in that state:</div>
    </div>
</form>