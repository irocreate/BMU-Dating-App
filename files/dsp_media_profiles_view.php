<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");
include(WP_DSP_ABSPATH . "functions.php");
$get_profile_id = isset($_REQUEST['profile_id']) ? $_REQUEST['profile_id'] : '';
$Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';
$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
$dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
if ($Action == 'Del') {
    $id = $_GET['Id'];
    $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $id;
    $fetch_member_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos Where user_id='$id'");
    $fetch_member_pic->picture;
    $delete_picture = $directory_path . "/" . $fetch_member_picture->picture;
    unlink($delete_picture);
    $wpdb->query("DELETE from $dsp_members_photos where user_id='$id'");
}
$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
if ($Action == 'partner') {
    $exist_details = $wpdb->get_row("SELECT partner.user_id, partner.country_id, partner.state_id, partner.city_id, partner.gender, partner.seeking, partner.zipcode, partner.age, partner.about_me, partner.my_interest, partner.status_id, partner.reason_for_status, partner.last_update_date  FROM $dsp_user_profiles profile join $dsp_user_partner_profiles_table partner Where profile.user_id=partner.user_id AND profile.user_profile_id='$get_profile_id'");
} else {
    $exist_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles Where user_profile_id='$get_profile_id'");
}

$city_id = isset($_REQUEST['cmbCity']) ? $_REQUEST['cmbCity'] : '';
$state_id = isset($_REQUEST['cmbState']) ? $_REQUEST['cmbState'] : '';
$zipcode = isset($_REQUEST['zip']) ? $_REQUEST['zip'] : '';
$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
$seeking = isset($_REQUEST['seeking']) ? $_REQUEST['seeking'] : '';
$profilemode = isset($_REQUEST['profilemode']) ? $_REQUEST['profilemode'] : '';
$question_option_id = isset($_REQUEST['option_id']) ? $_REQUEST['option_id'] : '';
$question_option_id1 = isset($_REQUEST['option_id1']) ? $_REQUEST['option_id1'] : '';
$question_option_id2 = isset($_REQUEST['option_id2']) ? $_REQUEST['option_id2'] : '';
$month = isset($_REQUEST['dsp_mon']) ? $_REQUEST['dsp_mon'] : '';
$day = isset($_REQUEST['dsp_day']) ? $_REQUEST['dsp_day'] : '';
$year = isset($_REQUEST['dsp_year']) ? $_REQUEST['dsp_year'] : '';
$aboutme = isset($_REQUEST['txtaboutme']) ? $_REQUEST['txtaboutme'] : '';
$reason_for_status = isset($_REQUEST['dsp_reason']) ? $_REQUEST['dsp_reason'] : '';
$status_id = isset($_REQUEST['status_id']) ? $_REQUEST['status_id'] : '';
$age = $year . "-" . $month . "-" . $day;
$last_update_date = date('Y-m-d H:i:s');
$my_interest = isset($_REQUEST['my_interest']) ? $_REQUEST['my_interest'] : '';
$private_profile = isset( $_REQUEST['make_private_profile'] ) ? $_REQUEST['make_private_profile'] : 0;

if (isset($_POST['submit1'])) {
//Check to make sure that the Country field is not empty
    if (isset($_POST['cmbCountry']) && trim($_POST['cmbCountry']) == 0) {
        $nameError = language_code('DSP_ERROR_FORGOT_SELECT_COUNTRY_FIELD');
        $hasError = true;
    } else {
        $country_id = isset($_REQUEST['cmbCountry']) ? trim($_REQUEST['cmbCountry']) : '';
    }
//Check to make sure that the Zipcode field is not empty

    $zipcode = isset($_REQUEST['zip']) ? trim($_REQUEST['zip']) : '';

    //Check to make sure that the About Me field is not empty
    if (trim(isset($_POST['txtaboutme']) && $_POST['txtaboutme']) === '') {
        $aboutmeError = language_code('DSP_FORGOT_ABOUT_ME_MSG');
        $hasError = true;
    } else {
        $aboutme = isset($_REQUEST['txtaboutme']) ? trim($_REQUEST['txtaboutme']) : '';
    }
    //Check to make sure that the city field is not empty

    $city_id = trim($city_id);

    //Check to make sure that the city field is not empty

    $state_id = trim($state_id);

    //If there is no error, then profile updated
    function myinterest_cloud($my_interest) {
        global $wpdb;
        $dsp_interest_tags_table = $wpdb->prefix . DSP_INTEREST_TAGS_TABLE;
        $strInterest = $my_interest;
        $tag_array = explode(",", strtolower(trim($strInterest)));

        for ($intCounter = 0; $intCounter < count($tag_array); $intCounter++) {
            //echo "SELECT count(*) as ifExists FROM " . $dsp_interest_tags_table . " WHERE keyword = '" . strtolower(trim($tag_array[$intCounter])) . "' <br>";

            $interest_tags_table = $wpdb->get_var("SELECT count(*) as ifExists FROM " . $dsp_interest_tags_table . " WHERE keyword = '" . strtolower(trim($tag_array[$intCounter])) . "'");
            if ($interest_tags_table == 0) {
                $strExecuteQuery = "INSERT INTO " . $dsp_interest_tags_table . " VALUES (0,'" . strtolower(trim($tag_array[$intCounter])) . "',1,'NA')";
            } else {
                $strExecuteQuery = "UPDATE " . $dsp_interest_tags_table . " SET weight = weight+1 WHERE keyword = '" . strtolower(trim($tag_array[$intCounter])) . "'";
            }
            //print "<br>" . $strExecuteQuery . "<br>";
            $wpdb->query($strExecuteQuery);
        }
    }

// End function myinterest_cloud 
    if (!isset($hasError)) {
        if ($profilemode == "update") {
            $exist_userdetails = $wpdb->get_row("SELECT user_id FROM $dsp_user_profiles Where user_profile_id='$get_profile_id'");
            $uid = $exist_userdetails->user_id;
//$wpdb->query("UPDATE $dsp_user_partner_profiles_table SET country_id = $country_id,state_id=$state_id,city_id = $city_id ,gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id='$status_id',reason_for_status='$reason_for_status',last_update_date='$last_update_date',about_me='$aboutme' , my_interest='$my_interest' WHERE user_id  = '$uid'");
            $wpdb->query("UPDATE $dsp_user_partner_profiles_table SET status_id='$status_id',reason_for_status='$reason_for_status',last_update_date='$last_update_date' WHERE user_id  = '$uid'");
            $wpdb->query("UPDATE $dsp_user_profiles SET country_id = '$country_id',state_id= '$state_id',city_id = '$city_id' ,gender = '$gender',seeking = '$seeking',zipcode = '$zipcode',age='$age',status_id='$status_id',reason_for_status='$reason_for_status',last_update_date='$last_update_date',about_me='$aboutme' , my_interest='$my_interest' , make_private_profile = $private_profile WHERE user_profile_id  = '$get_profile_id'");
            if ($status_id == 1) {
                dsp_add_news_feed($uid, 'status');
                dsp_add_notification($uid, 0, 'status');
            }


            if ($my_interest != '') {
                myinterest_cloud($my_interest);
            }
// ************************* INSERT PROFILE QUESTION OPTION INFORMATION *****************************//
            $num_rows1 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_question_details WHERE user_id=$exist_details->user_id");
            if ($num_rows1 > 0) {
                $wpdb->query("DELETE FROM $dsp_question_details where user_id = $exist_details->user_id");
            }
            for ($i = 0; $i < sizeof($question_option_id); $i++) {
                if ($question_option_id[$i] != 0) {
                    $option_value = stripslashes($fetch_question_id->option_value);
                    $fetch_question_id = $wpdb->get_row("SELECT * FROM $dsp_question_options_table WHERE question_option_id = '" . $question_option_id[$i] . "'");
                    $wpdb->query("INSERT INTO $dsp_question_details SET user_id = $exist_details->user_id,profile_question_id = '$fetch_question_id->question_id',profile_question_option_id='" . $question_option_id[$i] . "',option_value=''");
                } // End if($question_option_id[$i]!=0)
            }  // end for loop          
            if ($question_option_id1 != "") {
                foreach ($question_option_id1 as $key => $value) {
                    if ($value != "") {
                        $value = stripslashes($value);
                        $wpdb->query("INSERT INTO $dsp_question_details SET user_id = $exist_details->user_id,profile_question_id ='$key' ,profile_question_option_id=0,option_value='" . $value . "'");
                    } // End if($value!="")
                } // End foreach($question_option_id1 as $key=>$value)
            }
            for ($i = 0; $i < sizeof($question_option_id2); $i++) {
                if ($question_option_id2[$i] != 0) {
                    $option_value = stripslashes($fetch_question_id->option_value);
                    $fetch_question_id = $wpdb->get_row("SELECT * FROM $dsp_question_options_table WHERE question_option_id = '" . $question_option_id2[$i] . "'");
                    $wpdb->query("INSERT INTO $dsp_question_details SET user_id = $exist_details->user_id,profile_question_id = '$fetch_question_id->question_id',profile_question_option_id='" . $question_option_id2[$i] . "',option_value=''");
                } // End if($question_option_id[$i]!=0)
            }  // end for loop  
// ************************************************************************************************//
        } // End if($mode=="update")
        $profile_updated = true;
        $_SESSION['message'] = language_code('DSP_UPDATE_PROFILE_MESSAGE') ;
    } // End if(!isset($hasError))
}   // End if isset(submit) condition
if (isset($profile_updated) && $profile_updated == true) {
    ?>
    <div id="message" class="updated fade"><strong><?php echo language_code('DSP_UPDATE_PROFILE_MESSAGE'); ?></strong></div>
<?php } ?>
<?php if (isset($aboutmeError) && $aboutmeError != '') { ?>
    <div id="message" class="updated fade"><strong><?php echo $aboutmeError; ?></strong></div> 
<?php } ?>
<?php if (isset($nameError) && $nameError != '') { ?>
    <div id="message" class="updated fade"><strong><?php echo $nameError; ?></strong></div> 
<?php } ?>
<?php if (isset($zipError) && $zipError != '') { ?>
    <div id="message" class="updated fade"><strong><?php echo $zipError; ?></strong></div> 
<?php } ?>
<?php if (isset($cityError) && $cityError != '') { ?>
    <div id="message" class="updated fade"><strong><?php echo $cityError; ?></strong></div> 
<?php } ?>
<?php if (isset($stateError) && $stateError != '') { ?>
    <div id="message" class="updated fade"><strong><?php echo $stateError; ?></strong></div> 
<?php } ?>
<?php
if (isset($_GET['Action']) && $_GET['Action'] == 'partner') {
    $exist_profile_details = $wpdb->get_row("SELECT partner.user_id, partner.country_id, partner.state_id, partner.city_id, partner.gender, partner.seeking, partner.zipcode, partner.age, partner.about_me, partner.my_interest, partner.status_id, partner.reason_for_status, partner.last_update_date  FROM $dsp_user_profiles profile,$dsp_user_partner_profiles_table partner Where profile.user_id=partner.user_id AND profile.user_profile_id='$get_profile_id'");
} else {
    $exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles Where user_profile_id='$get_profile_id'");
}
//$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles Where user_profile_id='$get_profile_id'");
$member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table Where ID='$exist_profile_details->user_id'");
$request_url = get_bloginfo('url') . "/wp-admin/admin.php?page=dsp-admin-sub-page2";
if (isset($_REQUEST['status_id']) && $_REQUEST['status_id']) {
    /*$pending_profile_id = $wpdb->get_var("SELECT user_profile_id FROM $dsp_user_profiles WHERE `status_id` =0 order by user_profile_id asc limit 0,1");
    if ($pending_profile_id != "") {
        $redirect_to_next_profile = $request_url . "&pid=media_profile_view&mode=edit&profile_id=" . $pending_profile_id;
    } else {
        $redirect_to_next_profile = $request_url . "&pid=media_profiles&dsp_page=not_approve";
    }*/
    $redirect_to_next_profile = $request_url . "&pid=media_profiles&dsp_page=not_approve";
    ?>
    <script>window.location.href = "<?php echo $redirect_to_next_profile; ?>"</script>
    <?php
}
?>
<script>
    function my_profile(id)
    {
        var loc = window.location.href;
        window.location.href = loc;
    }
    function partner_profile(id)
    {
        var loc = window.location.href;
        loc += "&Action=partner";
        window.location.href = loc;
    }
</script>
<style>
    .media-head{ width:80px; }
</style>
<div><a href="<?php echo $_SERVER['HTTP_REFERER'];?>"><?php echo language_code('DSP_BACK'); ?></a></div>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr><td>
            <form name="frmupdate_memprofile" action="" method="post">
                <table cellpadding="0" cellspacing="0" border="0" width="100%" >
                    <tr><td class="profile_headind"><?php echo ucfirst($member_name->display_name); ?></td></tr>
                    <tr><td>
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr><td height="20px" colspan="4">&nbsp;</td></tr>
                                <tr><td>
                                        <div id="general" class="postbox" >
                                            <h3 class="hndle"><span><?php echo language_code('DSP_GENERAL') ?></span></h3>
                                            <table cellpadding="0" cellspacing="0" width="100%" border="0" style="padding-left:20px;">
                                                <?php if (($exist_profile_details->gender == 'C') || (isset($_GET['Action']) && $_GET['Action'] == 'partner')) { ?>
                                                    <tr>
                                                        <td><div style=" float:left; margin-right:10px;  margin-bottom:10px; margin-top:5px;"><a href="<?php
                                                                echo add_query_arg(array(
                                                                    'pid' => 'media_profile_view',
                                                                    'mode' => 'edit',
                                                                    'profile_id' => $get_profile_id), $request_url);
                                                                ?>">My Profile</a></div>
                                                            <div style=" float:left; margin-right:10px;  margin-bottom:10px; margin-top:5px;" ><a href="#" onclick="partner_profile(<?php echo $get_profile_id ?>)">Partner profile</a></div></td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td>
                                                        <table border="0" cellspacing="1" cellpadding="0" style="margin:15px;" >

                                                            <tr>

                                                                <td colspan="3" height="2"></td>
                                                            </tr>

                                                            <tr>

                                                                <td class="media-head"><?php echo language_code('DSP_GENDER') ?></td>

                                                                <td>

                                                                    <select name="gender" style="width:120px;">

                                                                        <?php echo get_gender_list($exist_profile_details->gender); ?>
                                                                    </select>       </td><td><?php echo language_code('DSP_SEEKING') ?></td>

                                                                <td><select name="seeking" style="width:120px;">

                                                                        <?php echo get_gender_list($exist_profile_details->seeking); ?>
                                                                    </select></td>

                                                                <td rowspan="6" valign="top">
                                                                    <?php
                                                                    $ImagePath = get_bloginfo('url') . "/wp-content/";
                                                                    $img_path = display_members_photo($exist_profile_details->user_id, $ImagePath); 
                                                                    $alt = get_username($exist_profile_details->user_id);
                                                                        if (@dsp_fetchUrl($Mem_Image_path)) {
                                                                            $Mem_Image_path = $Mem_Image_path;
                                                                        } else {
                                                                            if (isset($_GET['Action']) && $_GET['Action'] == 'partner') {
                                                                                $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_partner_profiles_table  WHERE user_id = '$exist_profile_details->user_id'");
                                                                            } else {
                                                                                $check_gender = $wpdb->get_row("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$exist_profile_details->user_id'");
                                                                            }
                                                                            if ($check_gender->gender != 'F') {
                                                                                $Mem_Image_path = $ImagePath . "male-generic.jpg";
                                                                                $alt = 'Male Generic';
                                                                            } else {
                                                                                $Mem_Image_path = $ImagePath . "female-generic.jpg";
                                                                                $alt = 'Female Generic';
                                                                            }
                                                                        }
                                                                   
                                                                    ?>

                                                                    <img src="<?php echo $img_path; ?>" style="margin-left:60px;width:150px;height:150px;" alt="<?php echo $alt;?>"/>
                                                                    <?php echo $Mem_Image_path; ?>
                                                                    <div onclick="delete_images('<?php echo $exist_profile_details->user_id ?>')" class="span_pointer" style="font-size:12px; margin-left: 55px;text-align: center; ">delete</div>
                                                                </td>
                                                                <td rowspan="6" valign="top">
                                                                    <div class="dsp-status-update"><b><?php echo language_code('DSP_STATUS_UPDATE'); ?></b> <span><b><?php echo stripslashes($exist_profile_details->my_status); ?></b></span></div>
                                                                </td>
                                                            </tr>

                                                            <?php
                                                            if ($exist_profile_details->age != "") {

                                                                $split_age = explode("-", $exist_profile_details->age);
                                                            } // end if 
                                                            ?>

                                                            <tr>
                                                                <td>    <?php echo language_code('DSP_TEXT_DOB'); ?>    </td>
                                                                <td colspan="3">

                                                                    <?php
//array to store the months

                                                                    $mon = array(
                                                                        1 => 'January',
                                                                        'February',
                                                                        'March',
                                                                        'April',
                                                                        'May', 'June',
                                                                        'July', 'August',
                                                                        'September',
                                                                        'October',
                                                                        'November',
                                                                        'December');
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





                                                                    <?php //make the year pull-down menu    ?>

                                                                     <select name="dsp_year"  class="dspdp-form-control dsp-form-control">
                                                                    <?php
                                                                        $start_dsp_year = $check_start_year->setting_value;
                                                                        $end_dsp_year = $check_end_year->setting_value;

                                                                        for ($dsp_year = $start_dsp_year; $dsp_year <= $end_dsp_year; $dsp_year++) {
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
                                                               </td>
                                                            </tr>

                                                            <tr>

                                                                <td><?php echo language_code('DSP_COUNTRY') ?></td>

                                                                <td>

                                                                    <select name="cmbCountry" id="cmbCountry_id"  style="width:190px;  ">

<!--<select name="cmbCountry" id="cmbCountry_id1" style="width:190px;">-->



                                                                        <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY') ?></option>

                                                                        <?php
                                                                        $countries = $wpdb->get_results("SELECT * FROM $dsp_country_table Order by name");

                                                                        foreach ($countries as $country) {

                                                                            if ($exist_profile_details->country_id == $country->country_id) {
                                                                                ?>

                                                                                <option value="<?php echo $country->country_id; ?>" selected="selected"><?php echo $country->name; ?></option>

                                                                            <?php } else { ?>

                                                                                <option value="<?php echo $country->country_id; ?>" ><?php echo $country->name; ?></option>

                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>       </td>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                            </tr>

                                                            <tr>

                                                                <td><?php echo language_code('DSP_TEXT_STATE'); ?></td>

                                                                <td colspan="2">

                                                                    <div id="state_div" style="display:none"></div>
                                                                        <div id="load_img_id" style="display:none; float:right"><img src="<?php echo WPDATE_URL . '/images/loading.gif' ?>" border="0" width="20" height="20" alt="Loading"/></div>
                                                                    <div id="state_change">
                                                                        <select name="cmbState" id="cmbState_id" onChange="Show_city_e(this.value)" style="width:190px;">

                                                                            <option value="0"><?php echo language_code('DSP_SELECT_STATE'); ?></option>

                                                                            <?php
                                                                            $states = $wpdb->get_results("SELECT * FROM $dsp_state_table where country_id='$exist_profile_details->country_id' Order by name");

                                                                            foreach ($states as $state) {

                                                                                if ($exist_profile_details->state_id == $state->state_id) {
                                                                                    ?>

                                                                                    <option value="<?php echo $state->state_id; ?>" selected="selected"><?php echo $state->name; ?></option>

                                                                                <?php } else { ?>

                                                                                    <option value="<?php echo $state->state_id; ?>"><?php echo $state->name; ?></option>

                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>      </td>
                                                                <td>&nbsp;</td>
                                                            </tr>

                                                            <tr>

                                                                <td><?php echo language_code('DSP_CITY') ?></td>

                                                                <td colspan="2">
                                                                    <div id="city_div" style="display:none"></div>

                                                                        <div id="load_img_id2" style="display:none; float:right"><img src="<?php echo WPDATE_URL . '/images/loading.gif' ?>" border="0" width="20" height="20" alt="Loading"/></div>
                                                                    <div id="city_change">
                                                                        <select name="cmbCity" style="width:190px;">

                                                                            <option value="0"><?php echo language_code('DSP_SELECT_CITY'); ?></option>

                                                                            <?php
                                                                            if ($exist_profile_details->state_id == 0) {
                                                                                $cities = $wpdb->get_results("SELECT * FROM $dsp_city_table where country_id='$exist_profile_details->country_id' Order by name");
                                                                            } else {
                                                                                $cities = $wpdb->get_results("SELECT * FROM $dsp_city_table where state_id='$exist_profile_details->state_id' and country_id='$exist_profile_details->country_id' Order by name");
                                                                            }

                                                                            foreach ($cities as $city) {

                                                                                if ($exist_profile_details->city_id == $city->city_id) {
                                                                                    ?>

                                                                                    <option value="<?php echo $city->city_id; ?>" selected="selected"><?php echo $city->name; ?></option>

                                                                                <?php } else { ?>

                                                                                    <option value="<?php echo $city->city_id; ?>"><?php echo $city->name; ?></option>

                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div> 
                                                                </td>
                                                                <td>&nbsp;</td>
                                                            </tr>

                                                            <tr>

                                                                <td><?php echo language_code('DSP_ZIP') ?></td>

                                                                <td colspan="3"><input type="text" name="zip" value="<?php echo $exist_profile_details->zipcode ?>" />      </td>
                                                            </tr>
                                                        </table>

                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                <tr><td>
                                        <div id="general" class="postbox" >
                                            <h3 class="hndle"><span><?php echo language_code('DSP_PROFILE_QUESTIONS') ?></span></h3>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="3" style="padding-left:20px;">
                                                <?php
                                                $dsp_partner_profile_questions_details = $wpdb->prefix . DSP_PARTNER_PROFILE_QUESTIONS_DETAILS;
                                                if (isset($_GET['Action']) && $_GET['Action'] == 'partner') {
                                                    $exist_profile_options_details = $wpdb->get_results("SELECT * FROM $dsp_partner_profile_questions_details WHERE user_id = '$exist_profile_details->user_id'");
                                                } else {
                                                    $exist_profile_options_details = $wpdb->get_results("SELECT * FROM $dsp_question_details WHERE user_id = '$exist_profile_details->user_id'");
                                                }
                                                foreach ($exist_profile_options_details as $profile_qu) {
                                                    $update_exit_option[] = $profile_qu->profile_question_option_id;
                                                }
                                                $myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id =1 Order by sort_order");
                                                $i = 0;
                                                foreach ($myrows as $profile_questions) {
                                                    $ques_id = $profile_questions->profile_setup_id;
                                                    $profile_ques = $profile_questions->question_name;
                                                    $profile_ques_type_id = $profile_questions->field_type_id;
                                                    if (isset($i) && ($i % 2) == 0) {
                                                        ?>
                                                        <tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        <td><?php echo $profile_ques; ?></td>
                                                        <td>
                                                            <?php
                                                            if ($profile_ques_type_id == 1) {
                                                                ?>
                                                                <select name="option_id[]" style="width:150px;">
                                                                    <option value="0">Select</option>
                                                                    <?php
                                                                    $myrows_options = $wpdb->get_results("SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order");
                                                                    foreach ($myrows_options as $profile_questions_options) {
                                                                        if (in_array($profile_questions_options->question_option_id, $update_exit_option)) {
                                                                            ?>
                                                                            <option value="<?php echo $profile_questions_options->question_option_id ?>" selected="selected"><?php echo stripslashes($profile_questions_options->option_value); ?></option>
                                                                        <?php } else { ?>
                                                                            <option value="<?php echo $profile_questions_options->question_option_id ?>"><?php echo stripslashes($profile_questions_options->option_value); ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?> 
                                                                </select>
                                                            <?php } ?>
                                                        </td>
                                                        <?php
                                                        $i++;
                                                    }  //  foreach ($myrows as $profile_questions) 
                                                    ?>  
                                                </tr>
                                                <tr><td colspan="4">&nbsp;</td></tr>
                                                <tr>
                                                    <td align="left">About Me</td>
                                                    <td align="left" colspan="3"><textarea name="txtaboutme" style="width:350px; height:50px;"><?php echo $exist_profile_details->about_me ?></textarea>
                                                        <?php if (isset($aboutmeError) && $aboutmeError != '') { ?>
                                                            <span class="error"><?php echo $aboutmeError; ?></span> 
                                                        <?php } ?>
                                                    </td>
                                                </tr> 

                                                <?php
                                                $myrows2 = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id =2 Order by sort_order");
                                                $i = 0;
                                                foreach ($myrows2 as $profile_questions2) {
                                                    $ques_id = $profile_questions2->profile_setup_id;
                                                    $profile_ques = $profile_questions2->question_name;
                                                    $profile_ques_type_id = $profile_questions2->field_type_id;
                                                    if (($i % 1) == 0) {
                                                        ?>
                                                        <tr>
                                                        <?php } ?>
                                                        <td><?php echo $profile_ques; ?></td>
                                                        <td colspan="3">
                                                            <?php
                                                            if ($profile_ques_type_id == 2) {
                                                                $exist_profile_text_details = $wpdb->get_row("SELECT * FROM $dsp_question_details WHERE user_id = '$exist_profile_details->user_id' AND profile_question_id=$ques_id");
                                                                $text_value = stripslashes($exist_profile_text_details->option_value);
                                                                ?>
                                                                <textarea name="option_id1[<?php echo $ques_id ?>]" class="dsp-form-control dspdp-form-control" style="width:350px; height:50px;"><?php echo $text_value ?></textarea>

                                                            <?php } ?>
                                                        </td>
                                                        <?php
                                                        $i++;
                                                    }  //  foreach ($myrows as $profile_questions) 
                                                    ?>  
                                                </tr> 
                                                <script>jQuery(function(){ jQuery('.dsp-multiple-select').chosen(); });</script>
                                                <?php 
                                                $myrows3 = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id =3 Order by sort_order");
                                                $i = 0;
                                                foreach ($myrows3 as $profile_questions) {
                                                    $ques_id = $profile_questions->profile_setup_id;
                                                    $profile_ques = $profile_questions->question_name;
                                                    $profile_ques_type_id = $profile_questions->field_type_id;
                                                    if (isset($i) && ($i % 1) == 0) {
                                                        ?>
                                                        <tr>
                                                            <?php
                                                        }
                                                        ?>
                                                        <td><?php echo $profile_ques; ?></td>
                                                        <td>
                                                            <?php
                                                            if ($profile_ques_type_id == 3) {
                                                                ?>
                                                                <select class="dsp-multiple-select chosen chzn-done" style="width:350px;" name="option_id2[]" multiple="true">
                                                                    <?php // <option value="0">Select</option> ?>
                                                                    <?php
                                                                    $myrows_options = $wpdb->get_results("SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order");
                                                                    foreach ($myrows_options as $profile_questions_options) {
                                                                        if (in_array($profile_questions_options->question_option_id, $update_exit_option)) {
                                                                            ?>
                                                                            <option value="<?php echo $profile_questions_options->question_option_id ?>" selected="selected"><?php echo stripslashes($profile_questions_options->option_value); ?></option>
                                                                        <?php } else { ?>
                                                                            <option value="<?php echo $profile_questions_options->question_option_id ?>"><?php echo stripslashes($profile_questions_options->option_value); ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?> 
                                                                </select>
                                                            <?php } ?>
                                                        </td>
                                                        <?php
                                                        $i++;
                                                    }  //  foreach ($myrows as $profile_questions) 
                                                    ?>  
                                                </tr>

                                                <tr>
                                                    <td align="left"><?php echo language_code('DSP_MY_INTEREST'); ?></td>
                                                    <td align="left" colspan="3"><textarea name="my_interest" style="width:350px; height:50px;"><?php echo $exist_profile_details->my_interest ?></textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td align="left"><?php echo language_code('DSP_PROFILE_MAKE_PRIVATE'); ?></td>
                                                    <td align="left" colspan="3">
                                                        <input name="make_private_profile" type='checkbox' id="make_private_profile"
                                                               value="1" <?php echo ( isset( $exist_profile_details->make_private_profile ) && $exist_profile_details->make_private_profile == 1 ) ? 'checked' : '' ?>/>
                                                    </td>
                                                </tr>

                                            </table>
                                            <?php if (@$_GET['Action'] != 'partner') { ?>   
                                                <hr />
                                                <table width="100%" border="0" cellspacing="0" cellpadding="3" style="padding-left:20px;">
                                                    <tr><td>&nbsp;</td></tr>        
                                                    <tr>
                                                        <td align="center">
                                                            <input type="radio" name="status_id" value="1" <?php
                                                            if ($exist_profile_details->status_id == "1") {
                                                                echo "checked";
                                                            }
                                                            ?>>&nbsp;<?php echo language_code('DSP_MEDIA_LINK_APPROVE') ?>&nbsp;&nbsp;
                                                            <input type="radio" name="status_id" value="2" <?php
                                                            if ($exist_profile_details->status_id == "2") {
                                                                echo "checked";
                                                            }
                                                            ?>>&nbsp;<?php echo language_code('DSP_MEDIA_LINK_REJECT') ?>&nbsp;&nbsp;
                                                            <input type="radio" name="status_id" value="3" <?php
                                                            if ($exist_profile_details->status_id == "3") {
                                                                echo "checked";
                                                            }
                                                            ?>>&nbsp;<?php echo language_code('DSP_DELETE') ?>&nbsp;&nbsp;
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center"><?php echo language_code('DSP_TEXT_REASON') ?>
                                                            <INPUT type="text" name="dsp_reason" value="<?php echo $exist_profile_details->reason_for_status ?>" style="width:200px;"></td>
                                                    </tr>
                                                    <tr><td colspan="4" class="submit" align="center"><input type="hidden" name="profilemode"  value="update"/>
                                                            <input type="submit" name="submit1" value="<?php echo language_code('DSP_SUBMIT_BUTTON') ?>" />
                                                        </td></tr>
                                                </table>
                                            <?php } ?>
                                        </div>
                                    </td></tr>
                            </table>
                        </td></tr>
                </table>
            </form>
        </td></tr>
</td></tr>
</table>

<script>
  jQuery("#cmbCountry_id").change(function() {
        var country = jQuery(this).val();
        country = country.replace(/ /g, '%20');
        jQuery("#state_change").load("<?php echo WPDATE_URL . "/get_state_city.php"; ?>?country=" + country);
        jQuery("#city_change").load("<?php echo  WPDATE_URL . "/get_city.php"; ?>?state=0&country=" + country);
    });
    jQuery("#cmbState_id").live("change", function() {
            var state = jQuery(this).val();
            var country = jQuery("#cmbCountry_id").val();
            country = country.replace(/ /g, '%20');
            state = state.replace(/ /g, '%20');
            jQuery("#city_change").load("<?php echo  WPDATE_URL . "/get_city.php";?>?state=" + state + "&country=" + country);
    });
</script>