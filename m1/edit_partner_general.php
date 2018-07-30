<?php
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_partner_profile_question_details_table = $wpdb->prefix . DSP_PARTNER_PROFILE_QUESTIONS_DETAILS;
$dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;



$root_link = get_bloginfo('url') . "/members/";

include_once(WP_DSP_ABSPATH . '/m1/country_st_ct.php'); // to change the state and city according to country
?>

<!--<div role="banner" class="ui-header ui-bar-a" data-role="header">
                    <div class="back-image">
                    <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
                    </div>
                <h1 aria-level="1" role="heading" class="ui-title"><?php
echo language_code('DSP_MENU_EDIT_PARTNER_PROFILE') . ' ' . language_code('DSP_GENERAL');
;
?></h1>
                
</div>-->

<?php
$zipcode = isset($_REQUEST['zip']) ? $_REQUEST['zip'] : '';

$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';


$check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");

$check_partner_profile_exist = $wpdb->get_var("SELECT count(*) FROM $dsp_user_partner_profiles_table WHERE user_id = '$user_id'");

$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_partner_profiles_table WHERE user_id = '$user_id'");


if ($check_partner_profile_exist != 0)
    $user_partner_profile_id = $exist_profile_details->user_id;
else
    $user_partner_profile_id = "";


if ($gender == '') {
    if ($check_partner_profile_exist != 0)
        $gender = $exist_profile_details->gender;
    else
        $gender = "";
}

$seeking = isset($_REQUEST['seeking']) ? $_REQUEST['seeking'] : '';

$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

$month = isset($_REQUEST['dsp_mon']) ? $_REQUEST['dsp_mon'] : '';

$day = isset($_REQUEST['dsp_day']) ? $_REQUEST['dsp_day'] : '';

$year = isset($_REQUEST['dsp_year']) ? $_REQUEST['dsp_year'] : '';

$age = $year . "-" . $month . "-" . $day;

$last_update_date = date("Y-m-d H:m:s");




if (isset($mode) && $mode == "update") {

    $countryName = isset($_REQUEST['cmbCountry']) ? $_REQUEST['cmbCountry'] : '';

    $get_Country = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE name = '" . $countryName . "'");

    if ($countryName != "Select" && !empty($countryName))
        $countryId = $get_Country->country_id;
    else
        $countryId = "";

    $stateName = isset($_REQUEST['cmbState']) ? $_REQUEST['cmbState'] : '';

    $get_State = $wpdb->get_row("SELECT * FROM $dsp_state_table WHERE name = '" . $stateName . "'");

    if ($stateName != "Select" && !empty($stateName))
        $stateId = $get_State->state_id;
    else
        $stateId = "";

    $cityName = isset($_REQUEST['cmbCity']) ? $_REQUEST['cmbCity'] : '';
    $cityNamenew = str_replace("`", "\'", $cityName);



    if ($stateId == "") {
        $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE name = '" . $cityNamenew . "' and country_id=" . $countryId);
    } else {
        $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE name = '" . $cityNamenew . "' and state_id=" . $stateId);
    }

    if ($cityName != "Select" && !empty($cityName))
        $cityId = $get_City->city_id;
    else
        $cityId = "";


    //Check to make sure that the Country field is not empty
    if (trim($_REQUEST['cmbCountry']) == '0') {
        $nameError = language_code('DSP_ERROR_FORGOT_SELECT_COUNTRY_FIELD');
        $hasError = true;
    } else {
        $country_id = trim($_REQUEST['cmbCountry']);
    }

    $zipcode = trim($_REQUEST['zip']);
    //If there is no error, then profile updated

    if (!isset($hasError)) {

        if ($check_approve_profile_status->setting_status == 'Y') {  // if Profile approve status is Y then Profile Automatically Approved.
            $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_partner_profiles_table WHERE user_id=$user_id");

            if ($num_rows == 0) {
                $wpdb->query("INSERT INTO $dsp_user_partner_profiles_table SET user_id = $user_id,country_id = '$countryId',state_id='$stateId',city_id = '$cityId',gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id=1,last_update_date='$last_update_date',about_me='$aboutme', edited='Y', my_interest='$my_interest'");

                $user = $wpdb->get_row("SELECT user_login FROM $dsp_user_table WHERE ID= $user_id");
                $user_name = $user->user_login;
                if($check_email_admin->setting_status=='Y'){
                $to = get_option('admin_email');
                $from = $to;

                $headers = language_code('DSP_FROM') . $from . "\r\nContent-type: text/html; charset=us-ascii\n";
                $subject = " New profile create";
                $message = "A new user $user_name has created a profile. You can view their profile by <a href='" . $root_link . "?pgurl=view_member&mem_id=$user_id&guest_pageurl=view_mem_profile'>clicking here</a>";

                wp_mail($to, $subject, $message, $headers);
            }
            } else {
                $wpdb->query("UPDATE $dsp_user_partner_profiles_table SET country_id = '$countryId',state_id='$stateId',city_id = '$cityId',gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id=1,last_update_date='$last_update_date'  WHERE user_id  = '$user_id'");

                $user = $wpdb->get_row("SELECT user_login FROM $dsp_user_table WHERE ID= $user_id");
                $user_name = $user->user_login;
                 if($check_email_admin->setting_status=='Y'){
                $to = get_option('admin_email');
                $from = $to;
                $headers = language_code('DSP_FROM') . $from . "\r\nContent-type: text/html; charset=us-ascii\n";
                $subject = " New profile create";
                $message = "User '$user_name' has edited a profile. You can view their profile by <a href='" . $root_link . "?pgurl=view_member&mem_id=$user_id&guest_pageurl=view_mem_profile'>clicking here</a>";
                wp_mail($to, $subject, $message, $headers);
            }
            } // if($num_rows==0)
        } else {
            $count_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_partner_profiles_table WHERE user_id=$user_id");

            if ($count_rows > 0) {
                $wpdb->query("UPDATE $dsp_user_partner_profiles_table SET country_id = '$countryId',state_id='$stateId',city_id = '$cityId',gender = '$gender',seeking = '$seeking', zipcode = '$zipcode',age='$age',status_id=0,last_update_date='$last_update_date' WHERE user_id  = '$user_id'");
            } else {
                $wpdb->query("INSERT INTO $dsp_user_partner_profiles_table SET user_id = $user_id,country_id = '$countryId',state_id='$stateId',city_id = '$cityId', gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id=0,last_update_date='$last_update_date'");
            }  // if($count_rows>0){
            $profile_approval_message = language_code('DSP_PROFILE_UPDATE_IN_HOURS_MSG');
        } // if($check_approve_profile_status->setting_status=='Y')

        $profile_updated = true;
    } // End if(!isset($hasError))
}   // End if isset(submit) condition






$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_partner_profiles_table WHERE user_id = '$user_id'");


if ($check_partner_profile_exist != 0)
    $gender = $exist_profile_details->gender;
else
    $gender = "";
?>

<script type="text/javascript">

    function dsp_profile_validation()
    {
        if (document.frm_u_profile.cmbCountry.value == 0)
        {
            alert("Please Select Country from Country Dropdown Field..");
            document.frm_u_profile.cmbCountry.focus();
            return false;
        }

        for (var i = 0; i < document.frm_u_profile.hidprofileqquesid.length; i++)
        {
            var q_name = document.frm_u_profile.hidprofileqques[i].value;
            var q_id1 = document.frm_u_profile.hidprofileqquesid[i].value;
            var sel_option_id = document.getElementById("q_opt_ids" + q_id1).value;
            if (sel_option_id == 0)
            {

                alert("Please Select " + q_name + " value");

                return false;

            }



        }

        if (document.frm_u_profile.txtaboutme.value == "") {
            alert("Please Enter About Me.");
            document.frm_u_profile.txtaboutme.focus();
            return false;
        }
        for (var i = 0; i < document.frm_u_profile.hidtextprofileqquesid.length; i++) {

            var q_name2 = document.frm_u_profile.hidetextqu_name[i].value;
            var q_id2 = document.frm_u_profile.hidtextprofileqquesid[i].value;
            var text_option_id = document.getElementById("text_option_id" + q_id2).value;
            if (text_option_id == "") {
                alert("Please Enter " + q_name2 + " value");
                return false;
            }
        }
    }
</script>




<?php //---------------------------------START  GENERAL SEARCH---------------------------------------    ?>




<form id="frmEditGen" style="padding-top:10px">
    <div id="reg_result"></div>  
     <?php
        if (isset($_GET['msg']) && $_GET['msg'] == 'ed') {
            ?>
             <div class="success-message"><?php echo language_code('DSP_UPDATE_PROFILE_BEFORE_ADD_FRND_MSG') ?></div>
            <?php
        }
        if (isset($required_Error1) && $required_Error1 != '') {
            ?>
            <div class="success-message"><?php
                    $req_error = implode(", ", $required_Error1);
                    echo language_code('DSP_PLEASE_ENTER_MSG') . "&nbsp;" . $req_error . "&nbsp;" . language_code('DSP_VALUE_MSG');
                    ?></div>
            <?php
        }
        if (isset($required_Error2) && $required_Error2 != '') {
            ?>
            <div class="success-message">
                    <?php
                    $req_error2 = implode(", ", $required_Error2);
                    echo language_code('DSP_PLEASE_SELECT_MSG') . "&nbsp;" . $req_error2 . "&nbsp;" . language_code('DSP_VALUE_MSG');
                    ?></div>

            <?php
        }

        if ($check_approve_profile_status->setting_status == 'Y') {
            if (isset($profile_updated) && $profile_updated == true) {
                ?>
                <div class="success-message"><?php echo language_code('DSP_UPDATE_PROFILE_MESSAGE') ?></div>
                <?php
            }
        } else {
            if (isset($profile_approval_message) && ($profile_approval_message != "")) {
                ?>
                <div class="success-message"><?php echo $profile_approval_message ?></div>
                <?php
            }
        }
        ?>

        <li><span><?php echo language_code('DSP_I_AM') ?></span>



            <?php
            if ($check_partner_profile_exist != 0 && $exist_profile_details->edited == 'Y') {
                $edited = "disabled='disabled'";
            }
            ?>

            <select name="gender" <?php if ($exist_profile_details->gender != '') { ?> <?php echo $edited ?> onchange="xyz(this.value);" <?php } ?>>
                <?php if ($exist_profile_details->gender == 'F') { ?>
                    <option value="M"><?php echo language_code('DSP_MAN'); ?></option>
                    <option value="F" selected="selected"><?php echo language_code('DSP_WOMAN'); ?></option>
                    <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                        <option value="C" ><?php echo language_code('DSP_COUPLE'); ?></option>
                    <?php } ?>
                <?php } else if ($exist_profile_details->gender == 'M') {
                    ?>
                    <option value="M" selected="selected"><?php echo language_code('DSP_MAN'); ?></option>
                    <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                    <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                        <option value="C" ><?php echo language_code('DSP_COUPLE'); ?></option>
                    <?php } ?>

                    <?php
                } else if (($exist_profile_details->gender == 'C') || ($_GET['view'] == 'my_profile')) {
                    ?>
                    <option value="M"><?php echo language_code('DSP_MAN'); ?></option>
                    <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                    <?php if ($check_couples_mode->setting_status == 'Y') {
                        ?>
                        <option value="C" selected="selected"><?php echo language_code('DSP_COUPLE'); ?></option>
                    <?php } ?>
                    <?php
                } else {
                    ?>
                    <option value="M" selected="selected"><?php echo language_code('DSP_MAN'); ?></option>
                    <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                    <?php if ($check_couples_mode->setting_status == 'Y') {
                        ?>
                        <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </li>

        <li><span><?php echo language_code('DSP_SEEKING_A'); ?></span>

            <select name="seeking">
                <?php if ($exist_profile_details->seeking == 'M') { ?>
                    <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                    <option value="M" selected="selected"><?php echo language_code('DSP_MAN'); ?></option>
                    <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                        <option value="C" ><?php echo language_code('DSP_COUPLE'); ?></option>
                    <?php } ?>

                <?php } else if ($exist_profile_details->seeking == 'F') { ?>

                    <option value="M" ><?php echo language_code('DSP_MAN'); ?></option>



                    <option value="F" selected="selected"><?php echo language_code('DSP_WOMAN'); ?></option>



                    <?php if ($check_couples_mode->setting_status == 'Y') { ?>



                        <option value="C" ><?php echo language_code('DSP_COUPLE'); ?></option>



                    <?php } ?>







                <?php } else if ($exist_profile_details->seeking == 'C') { ?>







                    <option value="M" ><?php echo language_code('DSP_MAN'); ?></option>



                    <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>



                    <?php if ($check_couples_mode->setting_status == 'Y') { ?>



                        <option value="C" selected="selected"><?php echo language_code('DSP_COUPLE'); ?></option>



                    <?php } ?>







                <?php } else { ?>







                    <option value="M"><?php echo language_code('DSP_MAN'); ?></option>



                    <option value="F" selected="selected"><?php echo language_code('DSP_WOMAN'); ?></option>



                    <?php if ($check_couples_mode->setting_status == 'Y') { ?>



                        <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>



                    <?php } ?>







                <?php } ?>







            </select>

        </li>



        <?php //////////////////////////////////////  AGE FIELDS //////////////////////////////////////////    ?>







        <?php
        if ($check_partner_profile_exist != 0 && $exist_profile_details->age != "") {







            $split_age = explode("-", $exist_profile_details->age);
        }
        ?>



        <li><span><?php echo language_code('DSP_AGE') ?></span>

            <?php
            //array to store the months







            $mon = array(1 => 'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December');
            ?>







            <select name="dsp_mon" style="width:100px;">







                <?php
                foreach ($mon as $key => $value) {







                    if ($split_age[1] == $key) {
                        ?>







                        <option value="<?php echo $key ?>" selected><?php echo $value ?></option>







                    <?php } else { ?>







                        <option value="<?php echo $key ?>"><?php echo $value ?></option>







                        <?php
                    }
                }
                ?>







            </select>





            <?php //make the day pull-down menu     ?>







            <select name="dsp_day" style="width:50px;">







                <?php
                for ($dsp_day = 1; $dsp_day <= 31; $dsp_day++) {







                    if ($split_age[2] == $dsp_day) {
                        ?>







                        <option value="<?php echo $dsp_day ?>" selected><?php echo $dsp_day ?></option>







                    <?php } else { ?>







                        <option value="<?php echo $dsp_day ?>"><?php echo $dsp_day ?></option>







                        <?php
                    }
                }
                ?>







            </select>





            <?php //make the year pull-down menu     ?>







            <select name="dsp_year" style="width:60px;">







                <?php
                $start_dsp_year = $check_start_year->setting_value;
                $end_dsp_year = $check_end_year->setting_value;

                                 $start_dsp_year = !empty($start_dsp_year) ? $start_dsp_year : 1925;
                            $end_dsp_year = !empty($end_dsp_year) ? $end_dsp_year : date('Y');
                            $selected = $split_age[0];

                            for ($dsp_year = $end_dsp_year; $dsp_year >= $start_dsp_year; $dsp_year--) 
                            {






                    if ($split_age[0] == $dsp_year) {
                        ?>







                        <option value="<?php echo $dsp_year ?>" selected><?php echo $dsp_year ?></option>







                    <?php } else { ?>







                        <option value="<?php echo $dsp_year ?>"><?php echo $dsp_year ?></option>







                        <?php
                    }







                    //$dsp_year++;
                }
                ?>







            </select>

        </li>

        <?php // //////////////////////////////////// END AGE FIELDS //////////////////////////////////////////     ?>

        <li><span><?php echo language_code('DSP_COUNTRY') ?>*</span>

            <!--onChange="Show_state(this.value);"-->

            <select name="cmbCountry" id="cmbCountry_id">

                <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY'); ?></option>



                <?php
                $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");



                foreach ($strCountries as $rdoCountries) {


                    if ($exist_profile_details->country_id == $rdoCountries->country_id) {
                        echo "<option value='" . $rdoCountries->name . "' selected='selected' >" . $rdoCountries->name . "</option>";
                    } else {
                        echo "<option value='" . $rdoCountries->name . "' >" . $rdoCountries->name . "</option>";
                    }
                }
                ?>



            </select>

        </li>


        <?php if (isset($nameError) && $nameError != '') { ?>

            <li>
                <div class="error"><?php echo $nameError; ?></div> 
            </li>

        <?php } ?>

        <!--- Add StateCombo on 29-dec-2011  -->

        <li><span><?php echo language_code('DSP_TEXT_STATE') ?>*</span>


            <div id="state_change">
                <select name="cmbState" id="cmbState_id" >



                    <option value="0"><?php echo language_code('DSP_SELECT_STATE'); ?></option>

                    <?php
                    $strStates = $wpdb->get_results("SELECT * FROM $dsp_state_table where country_id='$exist_profile_details->country_id' ORDER BY name");



                    foreach ($strStates as $rdoStates) {


                        if ($exist_profile_details->state_id == $rdoStates->state_id) {
                            echo "<option value='" . $rdoStates->name . "' selected='selected' >" . $rdoStates->name . "</option>";
                        } else {
                            echo "<option value='" . $rdoStates->name . "' >" . $rdoStates->name . "</option>";
                        }
                    }
                    ?>

                </select>
            </div>


        </li>


        <!-- End City combo-->

        <li><span><?php echo language_code('DSP_CITY') ?>*</span>

            <div id="city_change">
                <select name="cmbCity" id="cmbCity_id">



                    <option value="0"><?php echo language_code('DSP_SELECT_CITY'); ?></option>
                    <?php
                    if ($exist_profile_details->state_id != 0) {
                        $strCities = $wpdb->get_results("SELECT * FROM $dsp_city_table where country_id='$exist_profile_details->country_id' and state_id='$exist_profile_details->state_id' ORDER BY name");
                    } else {
                        $strCities = $wpdb->get_results("SELECT * FROM $dsp_city_table where country_id='$exist_profile_details->country_id' ORDER BY name");
                    }


                    foreach ($strCities as $rdoCities) {


                        if ($exist_profile_details->city_id == $rdoCities->city_id) {
                            echo "<option value='" . $rdoCities->name . "' selected='selected' >" . $rdoCities->name . "</option>";
                        } else {
                            echo "<option value='" . $rdoCities->name . "' >" . $rdoCities->name . "</option>";
                        }
                    }
                    ?>


                </select>
            </div>
        </li>

        <!-- End city combo-->

        <?php if ($check_zipcode_mode->setting_status == 'Y') { ?> 

            <li><span><?php echo language_code('DSP_ZIP'); ?></span>

                <input type="text" name="zip" value="<?php if ($check_partner_profile_exist != 0) echo $exist_profile_details->zipcode ?>" style="width: 125px;"/>

            </li>

        <?php } ?>

        <?php if (isset($zipError) && $zipError != '') {
            ?>
            <li>
                <div class="error"><?php echo $zipError; ?></div> 
            </li>
        <?php } ?>






        <li><input type="hidden" name="mode"  value="update"/>
            <input type="hidden" name="user_id"  value="<?php echo $user_id; ?>"/>
            <input type="hidden" name="pagetitle" value="2" />
            <input type="hidden" name="title" value="partner_general" />
            <input type="button" onclick="editPartnerGeneral('true', '<?php echo language_code('DSP_USER_NAME_SHOULD_NO_BE_EMPTY') ?>')" name="submit1" value="<?php _e(language_code('DSP_UPDATE_BUTTON')); ?>" />


        </li>

    </ul>



    <?php // ------------------------------------------- END VERIFICATION --------------------------------------------------//     ?>
</form>