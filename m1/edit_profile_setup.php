<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_menu.php"); ?>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_EDIT_PROFILE'); ?></h1>
    <?php include_once("page_home.php"); ?>
</div>
<?php
if ($gender == 'C') {
    ?>
    <div class="ui-content" data-role="content">
        <div class="content-primary">
            <div data-role="navbar" class="ui-navbar ui-mini" role="navigation">
                <ul class="ui-grid-duo ui-grid-a">
                    <li class="ui-block-a">
                        <a href="dsp_edit_profile.html" data-corners="false" data-shadow="false" data-iconshadow="true"
                           data-wrapperels="span" data-theme="a" data-inline="true"
                           class="ui-btn ui-btn-inline ui-btn-up-a  <?php if ($edit_profile_pageurl == "") {
                               echo "ui-btn-active";
                           } ?>">
                            <span class="ui-btn-inner"><span
                                        class="ui-btn-text"><?php echo language_code('DSP_MENU_EDIT_MY_PROFILE'); ?></span></span>
                        </a>
                    </li>
                    <li class="ui-block-b">
                        <a href="dsp_edit_partner_profile.html" data-corners="false" data-shadow="false"
                           data-iconshadow="true" data-wrapperels="span" data-theme="a" data-inline="true"
                           class="ui-btn ui-btn-inline ui-btn-up-a <?php if ($edit_profile_pageurl == "partner_profile") {
                               echo "ui-btn-active";
                           } ?>">
                            <span class="ui-btn-inner"><span
                                        class="ui-btn-text"><?php echo language_code('DSP_MENU_EDIT_PARTNER_PROFILE'); ?></span></span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
<?php } ?>

<?php
$root_link                  = get_bloginfo('url') . "/members/";
$dsp_country_table          = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table            = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table             = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_user_table             = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_question_details       = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
$dsp_profile_setup_table    = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
$dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;

$user_id = $_REQUEST['user_id'];


$zipcode = isset($_REQUEST['zip']) ? $_REQUEST['zip'] : '';
$gender  = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';


$check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");

$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$user_id'");

if ($gender == '') {
    $gender = $exist_profile_details->gender;
}

$seeking             = isset($_REQUEST['seeking']) ? $_REQUEST['seeking'] : '';
$mode                = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$question_option_id  = isset($_REQUEST['option_id']) ? $_REQUEST['option_id'] : '';
$question_option_id1 = isset($_REQUEST['option_id1']) ? $_REQUEST['option_id1'] : '';
$month               = isset($_REQUEST['dsp_mon']) ? $_REQUEST['dsp_mon'] : '';
$day                 = isset($_REQUEST['dsp_day']) ? $_REQUEST['dsp_day'] : '';
$year                = isset($_REQUEST['dsp_year']) ? $_REQUEST['dsp_year'] : '';
$aboutme             = isset($_REQUEST['txtaboutme']) ? $_REQUEST['txtaboutme'] : '';
$my_interest         = isset($_REQUEST['my_interest']) ? $_REQUEST['my_interest'] : '';
$age                 = $year . "-" . $month . "-" . $day;
$last_update_date    = date("Y-m-d H:m:s");

if (isset($_REQUEST['private']) && $_REQUEST['private'] != '') {
    $make_private = $_REQUEST['private'];
} else {
    $make_private = 'N';
}

$countryName = isset($_REQUEST['cmbCountry']) ? $_REQUEST['cmbCountry'] : 'United States';

$get_Country = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE name = '" . $countryName . "'");

if ($countryName != "Select" && ! empty($countryName)) {
    $countryId = $get_Country->country_id;
} else {
    $countryId = "";
}

$stateName = isset($_REQUEST['cmbState']) ? $_REQUEST['cmbState'] : '';
$get_State = $wpdb->get_row("SELECT * FROM $dsp_state_table WHERE name = '" . $stateName . "'");
if ($stateName != "Select" && ! empty($stateName)) {
    $stateId = $get_State->state_id;
} else {
    $stateId = "";
}

$cityName = isset($_REQUEST['cmbCity']) ? $_REQUEST['cmbCity'] : '';

$cityNamenew = str_replace("`", "\'", $cityName);


if ($stateId == "") {
    $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE name = '" . $cityNamenew . "' and country_id=" . $countryId);
} else {
    $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE name = '" . $cityNamenew . "' and state_id=" . $stateId);
}


if ($cityName != "Select" && ! empty($cityName)) {
    $cityId = $get_City->city_id;
} else {
    $cityId = "";
}

if (isset($_REQUEST['submit1'])) {
//Check to make sure that the Country field is not empty

    if (trim($_REQUEST['cmbCountry']) == '0') {
        $nameError = language_code('DSP_ERROR_FORGOT_SELECT_COUNTRY_FIELD');
        $hasError  = true;
    } else {
        $country_id = trim($_REQUEST['cmbCountry']);
    }

    $zipcode = trim($_REQUEST['zip']);
    //Check to make sure that the About Me field is not empty

    if (trim($_REQUEST['txtaboutme']) === '') {
        $aboutmeError = language_code('DSP_FORGOT_ABOUT_ME_MSG');
        $hasError     = true;
    } else {
        $aboutme = trim($_REQUEST['txtaboutme']);
    }

    //Check to make sure that the my_interest field is not empty
    //Check to make sure that the city field is not empty
    // Checked textbox Profile question is required or not

    foreach ($question_option_id1 as $key => $value) {
        $check_required = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_profile_setup_table WHERE profile_setup_id='$key' AND required='Y'");
        if ($check_required > 0) {
            if ($value == '') {

                $ques_val          = $wpdb->get_row("SELECT question_name FROM $dsp_profile_setup_table WHERE profile_setup_id='$key'");
                $required_Error1[] = $ques_val->question_name;
                $hasError          = true;
            } // end if($value == '')
        }  // End if($check_required>0)
    } // End foreach($question_option_id1 as $key=>$value)
    // Checked dropdown Profile question is required or not


    foreach ($question_option_id as $key => $value) {

        $check_required = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_profile_setup_table WHERE profile_setup_id='$key' AND required='Y'");
        if ($check_required > 0) {
            if ($value == 0) {
                $ques_val          = $wpdb->get_row("SELECT question_name FROM $dsp_profile_setup_table WHERE profile_setup_id='$key'");
                $required_Error2[] = $ques_val->question_name;
                $hasError          = true;
            } // end if($value == '')
        }  // End if($check_required>0)
    } // End foreach($question_option_id1 as $key=>$value)
    // start function myinterest_cloud

    function myinterest_cloud($my_interest)
    {

        global $wpdb;

        $dsp_interest_tags_table = $wpdb->prefix . DSP_INTEREST_TAGS_TABLE;
        $strInterest             = $my_interest;

        $tag_array = explode(",", strtolower(trim($strInterest)));

        for ($intCounter = 0; $intCounter < count($tag_array); $intCounter++) {

            $interest_tags_table = $wpdb->get_var("SELECT count(*) as ifExists FROM " . $dsp_interest_tags_table . " WHERE keyword = '" . strtolower(trim($tag_array[$intCounter])) . "'");

            if ($interest_tags_table == 0) {
                $strExecuteQuery = "INSERT INTO " . $dsp_interest_tags_table . " VALUES (0,'" . strtolower(trim($tag_array[$intCounter])) . "',1,'NA')";
            } else {
                $strExecuteQuery = "UPDATE " . $dsp_interest_tags_table . " SET weight = weight+1 WHERE keyword = '" . strtolower(trim($tag_array[$intCounter])) . "'";
            }

            $wpdb->query($strExecuteQuery);
        }
    }

// End function myinterest_cloud
    //If there is no error, then profile updated


    if ( ! isset($hasError)) {
        if ($mode == "update") {

            if ($check_approve_profile_status->setting_status == 'Y') {  // if Profile approve status is Y then Profile Automatically Approved.
                $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id");
                if ($num_rows == 0) {
                    $wpdb->query("INSERT INTO $dsp_user_profiles SET user_id = $user_id,country_id = '$countryId',state_id='$stateId',city_id = '$cityId',gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id=1,last_update_date='$last_update_date',about_me='$aboutme', edited='Y', my_interest='$my_interest',make_private='$make_private'");
                    if ($my_interest != '') {
                        myinterest_cloud($my_interest);
                    }
                    $user      = $wpdb->get_row("SELECT user_login FROM $dsp_user_table WHERE ID= $user_id");
                    $user_name = $user->user_login;

                    if ($check_email_admin->setting_status == 'Y') {
                        $to   = get_option('admin_email');
                        $from = $to;

                        $headers = language_code('DSP_FROM') . $from . "\r\nContent-type: text/html; charset=us-ascii\n";
                        $subject = " New profile create";
                        $message = "A new user $user_name has created a profile. You can view their profile by <a href='" . $root_link . "?pgurl=view_member&mem_id=$user_id&guest_pageurl=view_mem_profile'>clicking here</a>";

                        wp_mail($to, $subject, $message, $headers);
                    }
                } else {

                    $wpdb->query("UPDATE $dsp_user_profiles SET country_id = '$countryId',state_id='$stateId',city_id = '$cityId',gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id=1,last_update_date='$last_update_date',about_me='$aboutme', my_interest='$my_interest',make_private='$make_private' WHERE user_id  = '$user_id'");


                    if ($my_interest != '') {
                        myinterest_cloud($my_interest);
                    }


                    $user = $wpdb->get_row("SELECT user_login FROM $dsp_user_table WHERE ID= $user_id");


                    $user_name = $user->user_login;


                    if ($check_email_admin->setting_status == 'Y') {
                        $to = get_option('admin_email');


                        $from = $to;


                        $headers = language_code('DSP_FROM') . $from . "\r\nContent-type: text/html; charset=us-ascii\n";


                        $subject = " New profile create";


                        $message = "User '$user_name' has edited a profile. You can view their profile by <a href='" . $root_link . "?pgurl=view_member&mem_id=$user_id&guest_pageurl=view_mem_profile'>clicking here</a>";

                        wp_mail($to, $subject, $message, $headers);
                    }
                } // if($num_rows==0)
            } else {

                $count_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id");
                if ($count_rows > 0) {
                    $wpdb->query("UPDATE $dsp_user_profiles SET country_id = '$countryId',state_id='$stateId',city_id = '$cityId',gender = '$gender',seeking = '$seeking', zipcode = '$zipcode',age='$age',status_id=0,last_update_date='$last_update_date',about_me='$aboutme', my_interest='$my_interest',make_private='$make_private' WHERE user_id  = '$user_id'");
                    if ($my_interest != '') {
                        myinterest_cloud($my_interest);
                    }
                } else {

                    $wpdb->query("INSERT INTO $dsp_user_profiles SET user_id = $user_id,country_id = '$countryId',state_id='$stateId',city_id = '$cityId', gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id=0,last_update_date='$last_update_date',about_me='$aboutme',make_private='$make_private', my_interest='$my_interest'");
                    if ($my_interest != '') {
                        myinterest_cloud($my_interest);
                    }
                }  // if($count_rows>0){
                $profile_approval_message = language_code('DSP_PROFILE_UPDATE_IN_HOURS_MSG');
            } // if($check_approve_profile_status->setting_status=='Y')
// ******************************** END of INSERT GENERAL INFORMATION *************************************//
// ************************* INSERT PROFILE QUESTION OPTION INFORMATION *****************************//

            $num_rows1 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_question_details WHERE user_id=$user_id");


            if ($num_rows1 > 0) {
                $wpdb->query("DELETE FROM $dsp_question_details where user_id = $user_id");
            }

            foreach ($question_option_id as $key => $value) {
                if ($value != 0) {

                    $fetch_question_id = $wpdb->get_row("SELECT * FROM $dsp_question_options_table WHERE question_option_id = '" . $value . "'");

                    $wpdb->query("INSERT INTO $dsp_question_details SET user_id = $user_id,profile_question_id = '$key',profile_question_option_id='" . $value . "',option_value='$fetch_question_id->option_value'");
                } // End  if($value!=0) {
            }  // end  foreach($question_option_id as $key=>$value) {

            if ($question_option_id1 != "") {

                foreach ($question_option_id1 as $key => $value) {
                    if ($value != "") {
                        $wpdb->query("INSERT INTO $dsp_question_details SET user_id = $user_id,profile_question_id ='$key' ,profile_question_option_id=0,option_value='" . $value . "'");
                    } // End if($value!="")
                } // End foreach($question_option_id1 as $key=>$value)
            }

// ************************************************************************************************//
        } // End if($mode=="update")

        $profile_updated = true;
    } // End if(!isset($hasError))
}   // End if isset(submit) condition


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
    <div class="success-message"><?php
        $req_error2 = implode(", ", $required_Error2);
        echo language_code('DSP_PLEASE_SELECT_MSG') . "&nbsp;" . $req_error2 . "&nbsp;" . language_code('DSP_VALUE_MSG');
        ?>
    </div>
    <?php
}
if ($check_approve_profile_status->setting_status == 'Y') {
    if (isset($profile_updated) && $profile_updated == true) {
        ?>
        <?php
        $exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$user_id'");
        $exist_profile_gender  = $exist_profile_details->gender;
        $siteurl               = get_option('siteurl');
        ?>
        <div class="success-message"><?php echo language_code('DSP_UPDATE_PROFILE_MESSAGE') ?>
        </div>
        <?php
    }
} else {
    if (isset($profile_approval_message) && ($profile_approval_message != "")) {
        ?>
        <div class="success-message"><?php echo $profile_approval_message ?>
        </div>
        <?php
    }
}
$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$user_id'");

$exist_profile_details->gender;


//-----------------------------------START  GENERAL ----------------------------------------//
?>


<div class="ui-content" data-role="content">
    <div class="content-primary">

        <form id="frmEdit">
            <div id="reg_result"></div>
            <div class="heading-text"><strong><?php echo language_code('DSP_GENERAL') ?></strong></div>
            <label data-role="fieldcontain" class="select-group">
                <div class="clearfix">
                    <div class="mam_reg_lf select-label"><?php echo language_code('DSP_I_AM') ?></div>
                    <?php
                    if ($exist_profile_details->edited == 'Y') {
                        $edited = "disabled='disabled'";
                    }
                    ?>
                    <select name="gender" <?php if ($exist_profile_details->gender != '') { ?><?php echo $edited ?><?php } ?>>
                        <?php if ($exist_profile_details->gender == 'F') { ?>
                            <option value="M"><?php echo language_code('DSP_MAN'); ?></option>
                            <option value="F" selected="selected"><?php echo language_code('DSP_WOMAN'); ?></option>
                            <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>
                            <?php } ?>
                        <?php } else if ($exist_profile_details->gender == 'M') { ?>
                            <option value="M" selected="selected"><?php echo language_code('DSP_MAN'); ?></option>
                            <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                            <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>
                            <?php } ?>
                        <?php } else if (($exist_profile_details->gender == 'C')) { ?>
                            <option value="M"><?php echo language_code('DSP_MAN'); ?></option>
                            <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                            <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                <option value="C"
                                        selected="selected"><?php echo language_code('DSP_COUPLE'); ?></option>
                            <?php } ?>
                        <?php } else { ?>
                            <option value="M" selected="selected"><?php echo language_code('DSP_MAN'); ?></option>
                            <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                            <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </label>
            <label data-role="fieldcontain" class="select-group">
                <div class="clearfix">
                    <div class="mam_reg_lf select-label"><?php echo language_code('DSP_SEEKING_A'); ?></div>
                    <select name="seeking">
                        <?php if ($exist_profile_details->seeking == 'M') { ?>
                            <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                            <option value="M" selected="selected"><?php echo language_code('DSP_MAN'); ?></option>
                            <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>
                            <?php } ?>
                        <?php } else if ($exist_profile_details->seeking == 'F') { ?>
                            <option value="M"><?php echo language_code('DSP_MAN'); ?></option>
                            <option value="F" selected="selected"><?php echo language_code('DSP_WOMAN'); ?></option>
                            <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>
                            <?php } ?>
                        <?php } else if ($exist_profile_details->seeking == 'C') { ?>
                            <option value="M"><?php echo language_code('DSP_MAN'); ?></option>
                            <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>
                            <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                <option value="C"
                                        selected="selected"><?php echo language_code('DSP_COUPLE'); ?></option>
                            <?php } ?>
                        <?php } else { ?>
                            <option value="M"><?php echo language_code('DSP_MAN'); ?></option>
                            <option value="F" selected="selected"><?php echo language_code('DSP_WOMAN'); ?></option>
                            <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                <option value="C"><?php echo language_code('DSP_COUPLE'); ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </label>
            <?php //////////////////////////////////////  AGE FIELDS //////////////////////////////////////////   ?>
            <?php
            if ($exist_profile_details->age != "") {
                $split_age = explode("-", $exist_profile_details->age);
            }
            ?>
            <div class="heading-text"><strong><?php echo language_code('DSP_AGE') ?></strong></div>

            <?php
            //array to store the months
            $mon = array(
                1 => language_code('DSP_JANUARY'),
                language_code('DSP_FABRUARY'),
                language_code('DSP_MARCH'),
                language_code('DSP_APRIL'),
                language_code('DSP_MAY'),
                language_code('DSP_JUNE'),
                language_code('DSP_JULY'),
                language_code('DSP_AUGUST'),
                language_code('DSP_SEPTEMBER'),
                language_code('DSP_OCTOBER'),
                language_code('DSP_NOVEMBER'),
                language_code('DSP_DECEMBER')
            );
            ?>
            <div class="col-cont clearfix">
                <div class="col-3">
                    <label class="select-group">
                        <select name="dsp_mon">
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
                    </label></div>
                <div class="col-3">
                    <label class="select-group">
                        <?php //make the day pull-down menu   ?>
                        <select name="dsp_day">
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
                    </label>
                </div>
                <div class="col-3">
                    <label class="select-group">
                        <?php //make the year pull-down menu    ?>

                        <select name="dsp_year">
                            <?php
                            $start_dsp_year = $check_start_year->setting_value;
                            $end_dsp_year   = $check_end_year->setting_value;

                            $start_dsp_year = ! empty($start_dsp_year) ? $start_dsp_year : 1925;
                            $end_dsp_year   = ! empty($end_dsp_year) ? $end_dsp_year : date('Y');
                            $selected       = $split_age[0];

                            for ($dsp_year = $end_dsp_year; $dsp_year >= $start_dsp_year; $dsp_year--) {
                                // for ($dsp_year = $start_dsp_year; $dsp_year >= ($start_dsp_year - 72); $dsp_year--) {
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
                    </label>
                </div>
            </div>

            <?php // //////////////////////////////////// END AGE FIELDS //////////////////////////////////////////    ?>

            <label data-role="fieldcontain" class="select-group">
                <div class="clearfix">
                    <div class="mam_reg_lf select-label"><?php echo language_code('DSP_COUNTRY') ?></div>
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
                </div>
            </label>
            <?php if (isset($nameError) && $nameError != '') { ?>
                <span class="error"><?php echo $nameError; ?></span>
            <?php } ?>

            <!--- Add StateCombo on 29-dec-2011  -->

            <label data-role="fieldcontain" class="select-group">
                <div class="clearfix">
                    <div class="mam_reg_lf select-label"><?php echo language_code('DSP_TEXT_STATE') ?></div>

                    <!--onChange="Show_state(this.value);"-->

                    <div id="state_change">
                        <select name="cmbState" id="cmbState_id" style="width:110px;">


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
                </div>
            </label>


            <!-- End City combo-->

            <label data-role="fieldcontain" class="select-group">
                <div class="clearfix">
                    <div class="mam_reg_lf select-label"><?php echo language_code('DSP_CITY') ?></div>

                    <!--onChange="Show_state(this.value);"-->


                    <div id="city_change">
                        <select name="cmbCity" id="cmbCity_id">


                            <option value="0"><?php echo language_code('DSP_SELECT_CITY'); ?></option>

                            <?php
                            if ($exist_profile_details->state_id != 0) {
                                $strCities = $wpdb->get_results("SELECT * FROM $dsp_city_table where country_id='$exist_profile_details->country_id' and state_id='$exist_profile_details->state_id' ORDER BY name");
                            }
                            //else // don't select the city is there is no state selected...
                            //{
                            //$strCities = $wpdb->get_results("SELECT * FROM $dsp_city_table where country_id='$exist_profile_details->country_id' ORDER BY name");
                            //}


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
                </div>
            </label>


            <!-- End city combo-->

            <?php if ($check_zipcode_mode->setting_status == 'Y') {
            ?>

            <label data-role="fieldcontain" class="form-group">
                <div class="clearfix">
                    <div class="mam_reg_lf form-label"><?php echo language_code('DSP_ZIP'); ?></div>

                    <input type="text" name="zip" value="<?php echo $exist_profile_details->zipcode ?>"/>
                    <?php if (isset($zipError) && $zipError != '') {
                        ?>
                        <span class="error"><?php echo $zipError; ?></span>
                    <?php } ?>

                    <?php } ?>
                </div>
            </label>


            <div class="gap-bottom"></div>


            <?php // -------------------------------------- END GENERAL ---------------------------------------------//   ?>


            <?php // ---------------------------------- START PROFILE QUESTIONS ------------------------------------ // ?>

            <div class="heading-text"><strong><?php echo language_code('DSP_PROFILE_QUESTIONS') ?></strong></div>
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
            $dsp_profile_setup_table    = $wpdb->prefix . $tableName1;

            $exist_profile_options_details = $wpdb->get_results("SELECT * FROM $dsp_question_details WHERE user_id = '$user_id'");

            foreach ($exist_profile_options_details as $profile_qu) {

                $update_exit_option[] = $profile_qu->profile_question_option_id;
            }

            $myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id =1 Order by sort_order");
            $i      = 0;
            ?>

            <?php
            foreach ($myrows as $profile_questions) {
                $ques_id              = $profile_questions->profile_setup_id;
                $profile_ques         = $profile_questions->question_name;
                $profile_ques_type_id = $profile_questions->field_type_id;
                ?>



                <label data-role="fieldcontain" class="select-group">
                <div class="clearfix">
                <div class="mam_reg_lf select-label"><?php echo $profile_ques; ?></div>

                <?php if ($profile_ques_type_id == 1) { ?>
                    <?php if ($profile_questions->required == "Y") { ?>

                        <input type="hidden" name="hidprofileqques[]" id="hidprofileqques[]"
                               value="<?php echo $profile_ques; ?>"/>
                        <input type="hidden" id="hidprofileqquesid[]" name="hidprofileqquesid[]"
                               value="<?php echo $ques_id; ?>"/>

                    <?php } ?>

                    <select name="option_id[<?php echo $ques_id ?>]" id="q_opt_ids<?php echo $ques_id ?>"
                            style="width:56%;">
                        <option value="0"><?php echo language_code('DSP_SELECT_OPTION'); ?></option>
                        <?php
                        $myrows_options = $wpdb->get_results("SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order");
                        foreach ($myrows_options as $profile_questions_options) {
                            if (@in_array($profile_questions_options->question_option_id, $update_exit_option)) {
                                ?>
                                <option value="<?php echo $profile_questions_options->question_option_id ?>"
                                        selected="selected"><?php echo $profile_questions_options->option_value ?></option>
                                <?php
                            } else {
                                ?>
                                <option value="<?php echo $profile_questions_options->question_option_id ?>"><?php echo $profile_questions_options->option_value ?></option>
                                <?php
                            }
                        }
                        ?>

                    </select>
                    </div>
                    </label>

                <?php } ?>


                <?php
                $i++;
            }  //  foreach ($myrows as $profile_questions)
            ?>


            <div class="heading-text"><strong><?php echo language_code('DSP_ABOUT_ME') ?></strong></div>

            <textarea id="txtaboutme" name="txtaboutme"
                      class="textarea-box-sm"><?php echo $exist_profile_details->about_me; ?></textarea>
            <?php if (isset($aboutmeError) && $aboutmeError != '') { ?>
                <span class="error"><?php echo $aboutmeError; ?></span>
            <?php } ?>

            <?php
            $myrows2 = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id =2 Order by sort_order");
            $i       = 0;
            foreach ($myrows2 as $profile_questions2) {
                $ques_id                 = $profile_questions2->profile_setup_id;
                $profile_ques            = $profile_questions2->question_name;
                $profile_ques_type_id    = $profile_questions2->field_type_id;
                $profile_ques_max_length = $profile_questions2->max_length;
                ?>

                <div class="heading-text"><strong><?php echo $profile_ques; ?></strong></div>

                <?php
                if ($profile_ques_type_id == 2) {


                    $check_exist_profile_text_details = $wpdb->get_var("SELECT count(*) FROM $dsp_question_details WHERE user_id = '$user_id' AND profile_question_id=$ques_id");

                    if ($check_exist_profile_text_details > 0) {

                        $exist_profile_text_details = $wpdb->get_row("SELECT * FROM $dsp_question_details WHERE user_id = '$user_id' AND profile_question_id=$ques_id");


                        $text_value = $exist_profile_text_details->option_value;
                    } else {
                        $text_value = "";
                    }
                    ?>

                    <?php if ($profile_questions2->required == "Y") {
                        ?>
                        <input type="hidden" name="hidetextqu_name" value="<?php echo $profile_ques; ?>"/>
                        <input type="hidden" name="hidtextprofileqquesid" id="hidtextprofileqquesid"
                               value="<?php echo $ques_id; ?>"/>
                    <?php } ?>

                    <textarea name="option_id1[<?php echo $ques_id ?>]" class="textarea-box-sm"
                              id="text_option_id<?php echo $ques_id ?>"
                              maxlength="<?php echo $profile_ques_max_length; ?>"><?php echo trim($text_value); ?></textarea>
                <?php } ?>
                </li>

                <?php
                $i++;
            }  //  foreach ($myrows as $profile_questions)
            ?>

            <div class="heading-text"><strong><?php echo language_code('DSP_MY_INTEREST'); ?></strong></div>
            <textarea name="my_interest"
                      class="textarea-box-sm"><?php echo trim($exist_profile_details->my_interest); ?></textarea>


            <div style="float: left; padding-right: 5px; width: 40%;">
                &nbsp;
            </div>
            <div>
                <input type="hidden" name="mode" value="update"/>
                <input type="hidden" name="pagetitle" value="2"/>
                <input type="hidden" name="user_id" value="<?php echo $user_id ?>"/>
                <input type="hidden" name="submit1" value="true"/>
                <div class="btn-blue-wrap">
                    <input type="button" name="submit" class="mam_btn btn-blue"
                           value="<?php _e(language_code('DSP_UPDATE_BUTTON')); ?>"
                           onclick="editProfileQuestion('true', '<?php echo language_code('DSP_USER_NAME_SHOULD_NO_BE_EMPTY'); ?>')"
                           value="<?php _e(language_code('DSP_UPDATE_BUTTON')); ?>"/>
                </div>
            </div>


            <?php // ----------------------------------------------- END PROFILE QUESTIONS -----------------------------------------//  ?>





            <?php
            // ------------------------------------------------ START VERIFICATION ---------------------------------------------//
            //Display error message if there are any


            if (isset($error) && strlen($error) > 0) {


                echo "<ul><li><strong>Error!</strong></li><li>" . $error . "</li></ul>";
            }
            ?>


            <?php if (isset($approval_message) && $approval_message != '') {
                ?>
                <div class="success-message"><?php echo $approval_message; ?></div>
            <?php } ?>

            <ul id="iGallery" data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview"
                class="ui-listview ui-listview-inset ui-corner-all dsp_ul profile-image">
                <li class="dsp_img3">
                    <img src="<?php echo display_members_photo($user_id, $imagepath); ?>"
                         style="width: 30%;height: auto;display: inline-block;padding: 10px;vertical-align: middle;"
                         class="dsp_img3"/>

                    <input onclick="getCheckBoxStatus()" <?php if ($exist_profile_details->make_private == 'Y') { ?> checked="checked"  <?php } ?>
                           style="display: inline-block;width: 20px;vertical-align: middle;" type="checkbox"
                           value="<?php
                           if ($exist_profile_details->make_private == 'Y') {
                               echo 'Y';
                           } else {
                               echo 'N';
                           }
                           ?>" id="chkPrivate" name="private"><?php echo language_code('DSP_PHOTO_MAKE_PRIVATE') ?>
                </li>
            </ul>


            <div class="btn-blue-wrap">
                <button onclick="getPhoto(); return false;"
                        class="mam_btn btn-blue"><?php echo language_code('DSP_UPLOAD_PHOTOS') ?></button>
            </div>


            <div id="req_result"></div>


            <?php // ------------------------------------------- END VERIFICATION --------------------------------------------------//    ?>
        </form>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up      ?>
</div>
<?php include_once("dspLeftMenu.php"); ?>