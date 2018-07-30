<?php
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
$dsp_profile_setup_table = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
$dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;

$root_link = get_bloginfo('url');
?>

<!--<div role="banner" class="ui-header ui-bar-a" data-role="header">
                    <div class="back-image">
                    <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
                    </div>
                <h1 aria-level="1" role="heading" class="ui-title"><?php
echo language_code('DSP_PROFILE_QUESTIONS');
;
?></h1>
                
</div>--->


<?php
$check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");

$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$user_id'");

$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';

if ($gender == '') {
    $gender = $exist_profile_details->gender;
}


$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

$question_option_id = isset($_REQUEST['option_id']) ? $_REQUEST['option_id'] : '';

$question_option_id1 = isset($_REQUEST['option_id1']) ? $_REQUEST['option_id1'] : '';

$aboutme = isset($_REQUEST['txtaboutme']) ? $_REQUEST['txtaboutme'] : '';

$my_interest = isset($_REQUEST['my_interest']) ? $_REQUEST['my_interest'] : '';

$last_update_date = date("Y-m-d H:m:s");



if (isset($_REQUEST['mode'])) {
//Check to make sure that the Country field is not empty

    if (trim($_REQUEST['txtaboutme']) === '') {
        $aboutmeError = language_code('DSP_FORGOT_ABOUT_ME_MSG');
        $hasError = true;
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
                $ques_val = $wpdb->get_row("SELECT question_name FROM $dsp_profile_setup_table WHERE profile_setup_id='$key'");

                $required_Error1[] = $ques_val->question_name;
                $hasError = true;
            } // end if($value == '')
        }  // End if($check_required>0)
    } // End foreach($question_option_id1 as $key=>$value)
    // Checked dropdown Profile question is required or not 

    foreach ($question_option_id as $key => $value) {
        $check_required = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_profile_setup_table WHERE profile_setup_id='$key' AND required='Y'");

        if ($check_required > 0) {

            if ($value == 0) {

                $ques_val = $wpdb->get_row("SELECT question_name FROM $dsp_profile_setup_table WHERE profile_setup_id='$key'");

                $required_Error2[] = $ques_val->question_name;
                $hasError = true;
            } // end if($value == '')
        }  // End if($check_required>0)
    } // End foreach($question_option_id1 as $key=>$value)
    // start function myinterest_cloud 

    function myinterest_cloud($my_interest) {
        global $wpdb;

        $dsp_interest_tags_table = $wpdb->prefix . DSP_INTEREST_TAGS_TABLE;

        $strInterest = $my_interest;

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
    if (!isset($hasError)) {
        if ($mode == "update") {
            if ($check_approve_profile_status->setting_status == 'Y') {  // if Profile approve status is Y then Profile Automatically Approved.
                $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id");

                if ($num_rows == 0) {
                    $wpdb->query("INSERT INTO $dsp_user_profiles SET user_id = $user_id,status_id=1,last_update_date='$last_update_date',about_me='$aboutme', edited='Y', my_interest='$my_interest'");

                    if ($my_interest != '') {
                        myinterest_cloud($my_interest);
                    }

                    $user = $wpdb->get_row("SELECT user_login FROM $dsp_user_table WHERE ID= $user_id");

                    $user_name = $user->user_login;

                    $to = get_option('admin_email');

                    $from = $to;

                    $headers = language_code('DSP_FROM') . $from . "\r\nContent-type: text/html; charset=us-ascii\n";

                    $subject = " New profile create";

                    $message = "A new user $user_name has created a profile. You can view their profile by <a href='" . $root_link . "?pgurl=view_member&mem_id=$user_id&guest_pageurl=view_mem_profile'>clicking here</a>";

                    wp_mail($to, $subject, $message, $headers);
                } else {
                    $wpdb->query("UPDATE $dsp_user_profiles SET status_id=1,last_update_date='$last_update_date',about_me='$aboutme', my_interest='$my_interest'  WHERE user_id  = '$user_id'");

                    if ($my_interest != '') {
                        myinterest_cloud($my_interest);
                    }

                    $user = $wpdb->get_row("SELECT user_login FROM $dsp_user_table WHERE ID= $user_id");

                    $user_name = $user->user_login;
                    $to = get_option('admin_email');
                    $from = $to;

                    $headers = language_code('DSP_FROM') . $from . "\r\nContent-type: text/html; charset=us-ascii\n";

                    $subject = " New profile create";

                    $message = "A new user $user_name has created a profile. You can view their profile by <a href='" . $root_link . "?pgurl=view_member&mem_id=$user_id&guest_pageurl=view_mem_profile'>clicking here</a>";

                    wp_mail($to, $subject, $message, $headers);
                } // if($num_rows==0)
            } else {
                $count_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id");

                if ($count_rows > 0) {

                    $wpdb->query("UPDATE $dsp_user_profiles SET status_id=0,last_update_date='$last_update_date',about_me='$aboutme', my_interest='$my_interest' WHERE user_id  = '$user_id'");

                    if ($my_interest != '') {
                        myinterest_cloud($my_interest);
                    }
                } else {
                    $wpdb->query("INSERT INTO $dsp_user_profiles SET user_id = $user_id,age='$age',status_id=0,last_update_date='$last_update_date',about_me='$aboutme', my_interest='$my_interest'");
                    if ($my_interest != '') {
                        myinterest_cloud($my_interest);
                    }
                }  // if($count_rows>0){

                $profile_approval_message = language_code('DSP_PROFILE_UPDATE_IN_HOURS_MSG');
            } // if($check_approve_profile_status->setting_status=='Y')
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
        } // End if($mode=="update")

        $profile_updated = true;
    } // End if(!isset($hasError))
}   // End if isset(submit) condition


$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$user_id'");
$exist_profile_details->gender;
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

        if (document.frm_u_profile.txtaboutme.value == "")
        {
            alert("Please Enter About Me.");
            document.frm_u_profile.txtaboutme.focus();
            return false;
        }

        for (var i = 0; i < document.frm_u_profile.hidtextprofileqquesid.length; i++)
        {

            var q_name2 = document.frm_u_profile.hidetextqu_name[i].value;
            var q_id2 = document.frm_u_profile.hidtextprofileqquesid[i].value;

            var text_option_id = document.getElementById("text_option_id" + q_id2).value;
            if (text_option_id == "")
            {
                alert("Please Enter " + q_name2 + " value");
                return false;
            }
        }
    }



    function xyz(i)
    {
        var url = "<?php echo get_option('siteurl') ?>";
        if (i == 'C')
            url += "?pid=2&view=my_profile";
        else
            url += "?pid=2";
        //alert(url);
        window.location.href = url;
        return i;
    }
</script>

<form id="frmEditQuestion" style="padding-top:10px">

    <ul class="edit-question">


        <?php if (isset($required_Error1) && $required_Error1 != '') {
            ?>
            <li>
                <p align="center" class="error"><?php
                    $req_error = implode(", ", $required_Error1);
                    echo language_code('DSP_PLEASE_ENTER_MSG') . "&nbsp;" . $req_error . "&nbsp;" . language_code('DSP_VALUE_MSG');
                    ?></p>
            </li>

            <?php
        }

        if (isset($required_Error2) && $required_Error2 != '') {
            ?>
            <li>

                <p align="center" class="error"><?php
                    $req_error2 = implode(", ", $required_Error2);
                    echo language_code('DSP_PLEASE_SELECT_MSG') . "&nbsp;" . $req_error2 . "&nbsp;" . language_code('DSP_VALUE_MSG');
                    ?></p>
            </li>

            <?php
        }


        if ($check_approve_profile_status->setting_status == 'Y') {
            if (isset($profile_updated) && $profile_updated == true) {
                $exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$user_id'");

                $exist_profile_gender = $exist_profile_details->gender;

                $siteurl = get_option('siteurl');
                ?>

                <li>
                    <p align="center" class="error"><?php echo language_code('DSP_UPDATE_PROFILE_MESSAGE') ?></p>
                </li>
                <?php
            }
        } else {
            if (isset($profile_approval_message) && ($profile_approval_message != "")) {
                ?>
                <li >
                    <p align="center" class="error"><?php echo $profile_approval_message ?></p>
                </li>
                <?php
            }
        }

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

        $exist_profile_options_details = $wpdb->get_results("SELECT * FROM $dsp_question_details WHERE user_id = '$user_id'");

        foreach ($exist_profile_options_details as $profile_qu) {
            $update_exit_option[] = $profile_qu->profile_question_option_id;
        }
        $myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id =1 Order by sort_order");
        $i = 0;

        foreach ($myrows as $profile_questions) {
            $ques_id = $profile_questions->profile_setup_id;
            $profile_ques = $profile_questions->question_name;
            $profile_ques_type_id = $profile_questions->field_type_id;
            ?> 

            <li><span><?php echo $profile_ques; ?></span>

                <?php
                if ($profile_ques_type_id == 1) {
                    if ($profile_questions->required == "Y") {
                        ?> 

                        <input type="hidden" name="hidprofileqques[]" id="hidprofileqques[]" value="<?php echo $profile_ques; ?>" />
                        <input type="hidden" id="hidprofileqquesid[]" name="hidprofileqquesid[]" value="<?php echo $ques_id; ?>" />
                    <?php } ?>

                    <select name="option_id[<?php echo $ques_id ?>]" id="q_opt_ids<?php echo $ques_id ?>"  style="width:56%;">
                        <option value="0"><?php echo language_code('DSP_SELECT_OPTION'); ?></option>
                        <?php
                        $myrows_options = $wpdb->get_results("SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order");
                        foreach ($myrows_options as $profile_questions_options) {
                            if (@in_array($profile_questions_options->question_option_id, $update_exit_option)) {
                                ?>
                                <option value="<?php echo $profile_questions_options->question_option_id ?>" selected><?php echo $profile_questions_options->option_value ?></option>
                            <?php } else {
                                ?>

                                <option value="<?php echo $profile_questions_options->question_option_id ?>"><?php echo $profile_questions_options->option_value ?></option>
                                <?php
                            }
                        }
                        ?> 
                    </select>
                <?php } ?>

            </li>

            <?php
            $i++;
        }  //  foreach ($myrows as $profile_questions) 
        ?>	

        <li class="row"><span><?php echo language_code('DSP_ABOUT_ME') ?></span>

            <textarea id="txtaboutme" name="txtaboutme" style="width:90%; height:50px;"><?php echo $exist_profile_details->about_me; ?></textarea>

            <?php if (isset($aboutmeError) && $aboutmeError != '') {
                ?>
                <span class="error"><?php echo $aboutmeError; ?></span> 
            <?php } ?>
        </li>

        <?php
        $myrows2 = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id =2 Order by sort_order");
        $i = 0;
        foreach ($myrows2 as $profile_questions2) {
            $ques_id = $profile_questions2->profile_setup_id;
            $profile_ques = $profile_questions2->question_name;

            $profile_ques_type_id = $profile_questions2->field_type_id;

            $profile_ques_max_length = $profile_questions2->max_length;
            ?> 


            <li class="row"><span><?php echo $profile_ques; ?></span>

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


                    <?php if ($profile_questions2->required == "Y") { ?>  

                        <input type="hidden" name="hidetextqu_name[]"  value="<?php echo $profile_ques; ?>" />
                        <input type="hidden" name="hidtextprofileqquesid[]" id="hidtextprofileqquesid" value="<?php echo $ques_id; ?>" />
                    <?php } ?>

                    <textarea name="option_id1[<?php echo $ques_id ?>]" id="text_option_id<?php echo $ques_id ?>" maxlength="<?php echo $profile_ques_max_length; ?>" style="width:90%;"><?php echo $text_value ?></textarea>
                <?php } ?>

            </li>

            <?php
            $i++;
        }  //  foreach ($myrows as $profile_questions) 
        ?>	

        <li class="row"><span><?php echo language_code('DSP_MY_INTEREST'); ?></span>

            <textarea name="my_interest" style="width:90%;"><?php echo $exist_profile_details->my_interest ?></textarea>
        </li>

        <li>
            <span>
                <input type="hidden" name="mode"  value="update"/>&nbsp;
                <input type="hidden" name="user_id"  value="<?php echo $user_id; ?>"/>
                <input type="hidden" name="pagetitle"  value="edit_profile_question"/>&nbsp;
            </span>
            <input type="button" name="submit1" onclick="editProfileQuestion('true', '<?php echo language_code('DSP_USER_NAME_SHOULD_NO_BE_EMPTY'); ?>')" value="<?php _e(language_code('DSP_UPDATE_BUTTON')); ?>" />
        </li>
    </ul>

</form>