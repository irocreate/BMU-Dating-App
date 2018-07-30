<?php 
ini_set('auto_detect_line_endings', true);

if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

function square_crop($src_image, $dest_image, $thumb_size = 64, $jpg_quality = 90) {



    // Get dimensions of existing image

    $image = getimagesize($src_image);



    // Check for valid dimensions

    if ($image[0] <= 0 || $image[1] <= 0)
        return false;



    // Determine format from MIME-Type

    $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));



    // Import image

    switch ($image['format']) {

        case 'jpg':

        case 'jpeg':

            $image_data = imagecreatefromjpeg($src_image);

            break;

        case 'png':

            $image_data = imagecreatefrompng($src_image);

            break;

        case 'gif':

            $image_data = imagecreatefromgif($src_image);

            break;

        default:

            // Unsupported format

            return false;

            break;
    }



    // Verify import

    if ($image_data == false)
        return false;



    // Calculate measurements

    if ($image[0] & $image[1]) {

        // For landscape images

        $x_offset = ($image[0] - $image[1]) / 2;

        $y_offset = 0;

        $square_size = $image[0] - ($x_offset * 2);
    } else {

        // For portrait and square images

        $x_offset = 0;

        $y_offset = ($image[1] - $image[0]) / 2;

        $square_size = $image[1] - ($y_offset * 2);
    }



    // Resize and crop



    $canvas = imagecreatetruecolor($thumb_size, $thumb_size);

    $white = imagecolorallocate($canvas, 255, 255, 255);

    imagefill($canvas, 0, 0, $white);

    if (imagecopyresampled(
            $canvas, $image_data, 0, 0, $x_offset, $y_offset, $thumb_size, $thumb_size, $square_size, $square_size
        )) {



        // Create thumbnail

        switch (strtolower(preg_replace('/^.*\./', '', $dest_image))) {

            case 'jpg':

            case 'jpeg':

                return imagejpeg($canvas, $dest_image, $jpg_quality);

                break;

            case 'png':

                return imagepng($canvas, $dest_image);

                break;

            case 'gif':

                return imagegif($canvas, $dest_image);

                break;

            default:

                // Unsupported format

                return false;

                break;
        }
    } else {

        return false;
    }
}

// it will default to 0755 regardless 



include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");
$dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
$languageName  = $wpdb->get_row("SELECT language_name FROM $dsp_language_detail_table WHERE display_status='1'");
$languageName = $languageName->language_name;
if (strtolower($languageName) == 'english') {
    $tableName = "dsp_question_options";
} else {
    $tableName = "dsp_question_options_" . strtolower(trim(esc_sql(substr($languageName, 0, 2))));
}
$dsp_question_options_table = $wpdb->prefix . $tableName;
wp_enqueue_script('countryStateCity', WPDATE_URL . '/js/countryStateCity.js', array(), '', true);
?>



<script type="text/javascript">

    function dsp_profile_validation()

    {

        if (document.frm_u_profile.cmbCountry.value == 0) {

            alert("Please Select Country from Country Dropdown Field..");

            document.frm_u_profile.cmbCountry.focus();

            return false;

        }

        if (document.frm_u_profile.interests.value == "") {

            alert("Please Enter number of interests.");

            document.frm_u_profile.interests.focus();

            return false;

        }



        for (var i = 0; i < document.frm_u_profile.hidprofileqquesid.length; i++) {

            var q_name = document.frm_u_profile.hidprofileqques[i].value;

            var q_id1 = document.frm_u_profile.hidprofileqquesid[i].value;

            if (document.getElementById("random_option_i_" + q_id1).checked) {



            } else {

                var sel_option_id = document.getElementById("q_opt_ids" + q_id1).value;

                if (sel_option_id == 0) {

                    alert("Please Select " + q_name + " value");

                    return false;

                }

            }

        }



    }

</script>

<script>

    function chkrandom(ques_id) {



        var i_d = 'hidden_' + ques_id;

        var check_id = "random_option_i_" + ques_id;

        if (document.getElementById(check_id).checked)

        {

            document.getElementById(i_d).value = "1";

        }

        else

        {

            document.getElementById(i_d).value = "0";

        }

    }

</script>

<?php //---------------------------------START  GENERAL SEARCH---------------------------------------  ?>

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

        $strStateList .= "'" . addslashes($rdoStates->name) . "',";
    } // end $rdoStates

    if (strlen($strStateList) > 0) {

        $strStateList = substr($strStateList, 0, strlen($strStateList) - 1);
        ?>

        <script  type="text/javascript" language="javascript">

            states['<?php echo $rdoCountries->name; ?>'] = new Array('Select',<?php echo $strStateList; ?>);

            cities['<?php echo $rdoCountries->name; ?>'] = new Array();

            cities['<?php echo $rdoCountries->name; ?>']['Select'] = new Array('Select');

        </script>

        <?php
    } else {
        ?>

        <script  type="text/javascript" language="javascript">

            states['<?php echo $rdoCountries->name; ?>'] = new Array('Select');

            cities['<?php echo $rdoCountries->name; ?>'] = new Array();

            cities['<?php echo $rdoCountries->name; ?>']['Select'] = new Array('Select');

        </script>

        <?php
    }

    $bolinitialiseArray = true;

    $strStates = $wpdb->get_results("SELECT * FROM $dsp_state_table WHERE country_id = " . $rdoCountries->country_id . " ORDER BY name");

    foreach ($strStates as $rdoStates) {

        if ($bolinitialiseArray == true) {
            
        }

        $strCityList = "";

        $strCities = $wpdb->get_results("SELECT * FROM $dsp_city_table WHERE country_id = " . $rdoCountries->country_id . " AND state_id = " . $rdoStates->state_id . " ORDER BY name");

        foreach ($strCities as $rdoCities) {

            $replacequotesfromcity = str_replace("'", "`", $rdoCities->name);

            $strCityList .= "'" . addslashes($replacequotesfromcity) . "',";
        } // end $rdocitie

        if (strlen($strCityList) > 0) {

            $strCityList = substr($strCityList, 0, strlen($strCityList) - 1);
            ?>

            <script  type="text/javascript" language="javascript">

                cities['<?php echo addslashes($rdoCountries->name); ?>']['<?php echo addslashes($rdoStates->name); ?>'] = new Array('Select',<?php echo $strCityList; ?>);

            </script>

            <?php
        }

        $bolinitialiseArray = false;
    } // end $rdoStates
//$chk_country=$wpdb->get_var("SELECT count(*) FROM $dsp_state_table WHERE country_id = " . $rdoCountries->country_id . "");
//if($chk_country==0)
//{

    $strCityList = "";

    $strCities = $wpdb->get_results("SELECT * FROM $dsp_city_table WHERE country_id = " . $rdoCountries->country_id . "  ORDER BY name");

    foreach ($strCities as $rdoCities) {

        $replacequotesfromcity = str_replace("'", "`", $rdoCities->name);

        $strCityList .= "'" . addslashes($replacequotesfromcity) . "',";
    } // end $rdocitie

    if (strlen($strCityList) > 0) {

        $strCityList = substr($strCityList, 0, strlen($strCityList) - 1);
        ?>

        <script  type="text/javascript" language="javascript">

            cities['<?php echo addslashes($rdoCountries->name); ?>']['Select'] = new Array('Select',<?php echo $strCityList; ?>);

        </script>

        <?php
    } else {
        ?>

        <script  type="text/javascript" language="javascript">

            cities['<?php echo addslashes($rdoCountries->name); ?>']['Select'] = new Array('Select');

        </script>

        <?php
    }

//} // country contain cities not states
} // end $rdoCountries
?>

<script  type="text/javascript" language="javascript">

    states[0] = new Array('Select');

    cities[0] = new Array();

    cities[0]['Select'] = new Array('Select');

</script>

<style>

    .profile-td {

        height:30px;

    }

</style>
<div>
    <script>
        function getting_data_change(val) {
            if (val == 'db') {
                document.getElementById("table_td").style.display = "table-row";
            }
            else {
                document.getElementById("table_td").style.display = "none";
            }
        }
    </script>

    <div id="general" class="postbox">
        <h3 class="hndle"><span><?php echo language_code('DSP_TOOLS_PROFILE_GENERATOR') ?></span></h3>
        <form name="frm_u_profile" action="" method="post" enctype="multipart/form-data" onsubmit="return dsp_profile_validation();
                fun1();">
            <table cellpadding="3" cellspacing="0" border="0" style="padding-left:20px; padding-top:10px; width:930px;">	
                <tr class="profile-td">	

                    <td style="width:120px; text-align:right;">Get Data From : </td>	

                    <td align="left">	

                        <!--onChange="Show_state(this.value);"-->



                        <select name="getting_data" id="getting_data" onchange="getting_data_change(this.value);">		

                            <option value="0">Select</option>		

                            <option value="file">CSV File </option>
                            <option value="db">Database</option>
                        </select>





                    </td>



                </tr>
                <tr class="profile-td " id="table_td" style="display:none;">	

                    <td style="width:120px; text-align:right;">Database Table Name : </td>	

                    <td align="left">	

                        <!--onChange="Show_state(this.value);"-->



                        <input name="table_name" value="" />





                    </td>



                </tr>

                <tr class="profile-td">	

                    <td style="width:120px; text-align:right;"><?php echo language_code('DSP_COUNTRY') ?></td>	

                    <td align="left">	

                        <!--onChange="Show_state(this.value);"-->



                        <select name="cmbCountry" id="cmbCountry_id1" onchange="javascript :setStates();">		

                            <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY') ?></option>		

                            <?php
                            $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");

                            foreach ($strCountries as $rdoCountries) {

                                echo "<option value='" . addslashes($rdoCountries->name) . "' >" . $rdoCountries->name . "</option>";
                            }
                            ?>		

                        </select>



                        <?php if (isset($nameError) && $nameError != '') { ?>	

                            <span class="error"><?php echo $nameError; ?></span> 	

                        <?php } ?>

                    </td>



                </tr>



                <!--- Add StateCombo on 29-dec-2011  -->
                <tr class="profile-td">	

                    <td style="text-align:right;"><?php echo language_code('DSP_TEXT_STATE') ?></td>	

                    <td align="left">	

                        <!--onChange="Show_state(this.value);"-->	

                        <select name="cmbState" id="cmbState_id1" style="width:110px;" onchange="javascript : setCities();">	

                            <option value="0"><?php echo language_code('DSP_SELECT_STATE') ?></option>



                            <?php
                            $states = $wpdb->get_results("SELECT * FROM $dsp_state_table where country_id='" . $exist_profile_details->country_id . "'Order by name");

                            foreach ($states as $state) {
                                ?>	

                                <?php if ($exist_profile_details->state_id == $state->state_id) { ?>	

                                    <option value="<?php echo $state->name; ?>" selected="selected"><?php echo $state->name; ?></option>	

                                <?php } else { ?>	

                                    <option value="<?php echo $state->name; ?>" ><?php echo $state->name; ?></option>	

                                    <?php
                                }
                            } // end for each 
                            ?>



                        </select>



                        <?php if (isset($nameError) && $nameError != '') { ?>	

                            <span class="error"><?php echo $nameError; ?></span> 	

                        <?php } ?>



                    </td>



                </tr>



                <!-- End City combo-->



                <tr>		

                    <td style="text-align:right;"><?php echo language_code('DSP_CITY') ?></td>		

                    <td align="left">		

                        <!--onChange="Show_state(this.value);"-->		

                        <select name="cmbCity" id="cmbCity_id1">		
                            <option value="0"><?php echo language_code('DSP_SELECT_CITY') ?></option>		

                        </select>
                        <?php if (isset($nameError) && $nameError != '') { ?>		

                            <span class="error"><?php echo $nameError; ?></span> 		

                        <?php } ?>

                        <input type="checkbox" name="city_random" value="1" /> Random

                    </td>		

                </tr>
                <!-- End city combo-->
                <tr>
                    <td style="text-align:right;"><?php echo language_code('DSP_GENDER_MODE') ?>:</td>
                    <?php 
                       $genderList = get_gender_list();
                       if(!empty($genderList)):
                    ?>
                        <td  align="left">
                            <div style="width:87px;float:left;">
                                
                                <select name="gender">
                                    <?php echo $genderList; ?>
                                </select>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <td style="text-align:right;"><?php echo language_code('DSP_SEEKING') ?></td>
                    <?php 
                       $genderList = get_gender_list('F');
                       if(!empty($genderList)):
                    ?>
                        <td>
                            <div align="left">
                                <select name="seeking" style="margin-left:4px;">                            
                                    <?php echo $genderList; ?>
                                </select>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <td  style="text-align:right;"><?php echo language_code('DSP_AGE_RANGE'); ?>:</td>
                    <td>

                        <div style="width:60px;float:left;">

                            <select name="to_age_range" style="width:50px;">



                                <?php for ($age_range = 19; $age_range <= 100; $age_range++) { ?>



                                    <option value="<?php echo $age_range ?>"><?php echo $age_range ?></option>



                                <?php } ?>



                            </select>

                        </div>

                        <div  style="width:705px;float:right;">	

                            <select name="from_age_range" style="width:50px;">



                                <?php for ($age_range = 99; $age_range >= 19; $age_range--) { ?>



                                    <option value="<?php echo $age_range ?>"><?php echo $age_range ?></option>



                                <?php } ?>



                            </select>

                        </div> 



                    </td>

                </tr>



                <tr>

                    <td  style="text-align:right;"><?php echo language_code('DSP_ADD_ABOUT_ME'); ?>:</td>

                    <td>

                        <table width="100%" border="0">

                            <tr>

                                <td><input name="aboutme" type="checkbox" value="checked" /></td>

                                <td>Must have profile.csv file in /wp-content/plugins/dsp_dating/generator folder</td>

                            </tr>

                        </table>

                    </td>

                </tr>



                <tr>

                    <td  style="text-align:right;"><?php echo language_code('DSP_ADD_MY_MATCH'); ?>:</td>

                    <td>

                        <table width="100%" border="0">

                            <tr>

                                <td><input name="mymatch" type="checkbox" value="checked" /></td>

                                <td>Must have profile.csv file in /wp-content/plugins/dsp_dating/generator folder</td>

                            </tr>

                        </table>

                    </td>

                </tr>



                <tr>

                    <td  style="text-align:right;"><?php echo language_code('DSP_IMAGES'); ?>:</td>

                    <td>

                        <table width="100%" border="0">

                            <tr>

                                <td><input name="images" type="checkbox" value="checked" /></td>

                                <td>Must have photos in /wp-content/plugins/dsp_dating/generator/images folder</td>

                            </tr>

                        </table>

                    </td>

                </tr>



                <tr>

                    <td  style="text-align:right;"><?php echo language_code('DSP_NUMBER_OF_INTEREST'); ?>:</td>

                    <td><input name="interests" type="text" style="width:60px;" value="" /></td>

                </tr>



                <tr>

                    <td colspan="2">

                        <table width="100%" border="0" cellspacing="0" cellpadding="3">



                            <?php
                            /*$exist_profile_options_details = $wpdb->get_results("SELECT * FROM $dsp_question_details ");



                            foreach ($exist_profile_options_details as $profile_qu) {



                                $update_exit_option[] = $profile_qu->profile_question_option_id;
                            } */



                            $myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id =1 Order by sort_order");



                            $i = 0;



                            foreach ($myrows as $profile_questions) {



                                $ques_id = $profile_questions->profile_setup_id;



                                $profile_ques = $profile_questions->question_name;



                                $profile_ques_type_id = $profile_questions->field_type_id;



                                if (($i % 2) == 0) {
                                    ?>



                                    <tr>



                                    <?php } ?>



                                    <td style="width:125px;"><?php echo $profile_ques; ?></td>



                                    <td>



                                        <?php if ($profile_ques_type_id == 1) { ?>



                                            <?php if ($profile_questions->required == "Y") { ?> 



                                                <input type="hidden" name="hidprofileqques" value="<?php echo $profile_ques; ?>" />



                                                <input type="hidden" name="hidprofileqquesid" value="<?php echo $ques_id; ?>" />



                                            <?php } ?>



                                            <div style="float:left; width:105px;"><select name="option_id[<?php echo $ques_id ?>]" id="q_opt_ids<?php echo $ques_id ?>"  style="width:100px;">



                                                    <option value="0">Select</option>



                                                    <?php
                                                    $myrows_options = $wpdb->get_results("SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order");



                                                    foreach ($myrows_options as $profile_questions_options) {
                                                        ?>

                                                        <option value="<?php echo $profile_questions_options->question_option_id ?>"><?php echo $profile_questions_options->option_value ?></option>



                                                    <?php } ?> 



                                                </select>

                                            </div>



                                            <div style="float:right; width:100px; margin-right:55px; margin-left:20px; margin-top:8px;">

                                                <input type="checkbox" id="random_option_i_<?php echo $ques_id; ?>" name="random_option_id[<?php echo $ques_id; ?>]" value="<?php echo $ques_id; ?>" style="width:35px;" onClick="chkrandom(<?php echo $ques_id ?>)" /><?php echo language_code('DSP_RANDOM'); ?>



                                                <input type="hidden" id="hidden_<?php echo $ques_id; ?>" name="hidden[<?php echo $ques_id; ?>]" value="0">

                                            </div>

                                        <?php } ?>



                                    </td>



                                    <?php
                                    $i++;
                                }  //  foreach ($myrows as $profile_questions) 
                                ?>	



                            </tr>

                        </table>

                    </td>

                </tr>

                <tr>

                    <td colspan="2" align="right"  style="padding-right:75px;"><input type="submit" name="submit" value="<?php echo language_code('DSP_GENERATOR'); ?>" /></td>

                </tr>

                <tr><td colspan="2">&nbsp;</td></tr>



            </table>

        </form>

    </div>

</div>

<?php 
if (isset($_POST['submit'])) {


    global $wpdb;
    
    if (isset($_REQUEST['getting_data']) && $_REQUEST['getting_data'] == 'file') {
        ini_set("max_execution_time", "3000");

        @session_cache_limiter('private, must-revalidate');

        @session_start();

        $profilepath = WP_DSP_ABSPATH . 'generator/profile.csv';

//chmod($profilepath, 0777); 

        $fp = fopen($profilepath, "r") or die(ERROR);
        $line_no = 0;
        $line_of_text = array();
        $row = 1;
        if($fp !== FALSE){
            while(($data = fgetcsv($fp,1000,",")) !== FALSE) {
               $num = count($data);

               // echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c=0; $c < $num; $c++) {
                    $line_of_text[$row][$c] = $data[$c];
                }
             }
         }
         
        //var_dump($line_of_text);die;
        //echo count($line_of_text);die;
        // get the rest of the rows
        foreach ($line_of_text as $key=>$text) {
            //$text = explode(",", trim($text[0]));
            $username = $text[0];

            $email = $username . "@test.com";
            //var_dump($username);die;
            $random_password = wp_generate_password(12, false);

            $dsp_users_table = $wpdb->prefix . 'users';

            $dsp_blacklist_members = $wpdb->prefix . 'dsp_blacklist_members';

//Get the IP of the person registering

            $ip = $_SERVER['REMOTE_ADDR'];

            $check_blacklist_ipaddress_table = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_blacklist_members where ip_address = %s AND  ip_status=%d ",$ip,1));
            //var_dump($check_blacklist_ipaddress_table);die;
            if ($check_blacklist_ipaddress_table <= 0) {
                //var_dump($username);die;
                $status = wp_create_user($username, $random_password, $email);
                $users_table = $wpdb->get_row("SELECT * FROM $dsp_users_table where user_login='$username' ");
                $user_id = $users_table->ID;
                $ip_address_status = 0;

                //Add user metadata to the usermeta table

                update_user_meta($user_id, 'signup_ip', $ip);

                update_user_meta($user_id, 'ip_address_status', $ip_address_status);



                $wpdb->query($wpdb->prepare("INSERT INTO $dsp_blacklist_members SET user_name = %s, ip_address =%s ,ip_status=%d ",$username,$ip,0));



                $countryName = $_POST['cmbCountry'];

                $get_Country = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE name = '" . $countryName . "'");

                $countryId = $get_Country->country_id;

                $stateName = $_POST['cmbState'];

                $get_State = $wpdb->get_row("SELECT * FROM $dsp_state_table WHERE name = '" . $stateName . "'");

                $stateId = $get_State->state_id;

                $cityName = $_POST['cmbCity'];

                if (isset($_POST['city_random']) && $_POST['city_random'] == 1) {
                    if ($stateId == "") {

                        $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE country_id=" . $countryId . " order by rand() limit 1");
                    } else {

                        $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE state_id=" . $stateId . " and country_id=" . $countryId . " order by rand() limit 1");
                    }
                } else {
                    $cityNamenew = str_replace("`", "\'", $cityName);

                    if ($stateId == "") {

                        $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE name = '" . $cityNamenew . "' and country_id=" . $countryId);
                    } else {

                        $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE name = '" . $cityNamenew . "' and state_id=" . $stateId);
                    }
                }

                $cityId = $get_City->city_id;

                $gender = $_POST['gender'];

                $seeking = $_POST['seeking'];

                $to_age_range = $_POST['to_age_range'];

                $from_age_range = $_POST['from_age_range'];

                $aboutme = $_POST['aboutme'];

                $mymatch = $_POST['mymatch'];

                $images = $_POST['images'];

                $interests = $_POST['interests'];

                $hidden = $_POST['hidden'];

                $random_age = rand($to_age_range, $from_age_range);



                $today = date('Y-m-d');

                $strDateTo = date("Y-m-d", strtotime('-' . $random_age . 'years', strtotime($today)));

//$To=date('Y-m-d', strtotime('-'.$to_age_range.'days', strtotime('2012-08-02')));echo "<br>"; 
//$From=date('Y-m-d', strtotime('-'.$from_age_range.'days', strtotime('2012-08-02')));echo "<br>";  
                $keyword = "";
                if ($interests>0) {
                    $dsp_interest_tags_table = $wpdb->prefix . DSP_INTEREST_TAGS_TABLE;
                    $keyinterest = $wpdb->get_results("SELECT keyword FROM $dsp_interest_tags_table ORDER BY RAND() limit $interests");
                    foreach ($keyinterest as $interest)
                        $keyword.=$interest->keyword . ",";

                    $keyword = substr($keyword, 0, -1);
                }
                
                $last_update_date = date('Y-m-d H:i:s');

                if ($aboutme == 'checked') {

                    $about = addslashes($text[1]);

                    $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

                    $query1 = "INSERT INTO $dsp_user_profiles SET user_id='$user_id', country_id='$countryId', state_id='$stateId', city_id='$cityId', gender='$gender', seeking='$seeking', age='$strDateTo', my_interest='$keyword', about_me='$about', status_id=1, last_update_date='$last_update_date'";
                } else {

                    $query1 = "INSERT INTO $dsp_user_profiles SET user_id='$user_id', country_id='$countryId', state_id='$stateId', city_id='$cityId', gender='$gender', seeking='$seeking', age='$strDateTo', my_interest='$keyword', status_id=1, last_update_date='$last_update_date'";
                }
                //echo $user_id;die;
                $exist_user_profile = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_user_profiles where user_id= %d",$user_id));
                //var_dump($exist_profile_details);die;
                if ($exist_user_profile <= 0) {
                    //echo "inside";die;
                    $wpdb->query($query1);
                }
                //echo "outside";die;
                if ($mymatch == 'checked') {

                    $dsp_profile_question_details_table = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;

                    $profile_questions = "INSERT INTO $dsp_profile_question_details_table SET user_id=%d ,profile_question_id=%d, option_value= %s";

                    $exist_user_profile_questions = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_profile_question_details_table where user_id=%d",$user_id));

                    if ($exist_user_profile_questions <= 0) {
                        $dsp_question_details_values = array($user_id,21,addslashes($text[2]));
                        $wpdb->query($wpdb->prepare($profile_questions, $dsp_question_details_values));
                    }
                }// End if($mymatch == 'checked')

                if ($images == 'checked') {

                    $dir = WP_DSP_ABSPATH . "generator/images";

//chmod($dir, 0777); 	 

                    $file_display = array('jpg', 'jpeg', 'png', 'gif');

                    if (file_exists($dir) && is_dir($dir) == false) { // mobile folder exist 
//echo "directory not found";
                    } else {

                        $directory_contents = scandir($dir);

                        if ($directory_contents[0] == '.' && $directory_contents[1] == '..') {

                            unset($directory_contents[0]);

                            unset($directory_contents[1]);

                            $directory_contents = array_values($directory_contents);
                        }

//foreach($directory_contents as $files){

                        $k = array_rand($directory_contents);

                        $files = $directory_contents[$k];

//$file_type=explode('.' , $files);
//print_r($file_type);

                        $file_type = strtolower(end(explode('.', $files)));

//print_r($file_type);

                        if ($files !== "." && $files !== ".." && in_array($file_type, $file_display) == true) {



                            $path = $dir . "/" . $files;

                            chmod($path, 0777);



                            $folder = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/";

                            $filename = $folder . $files;

                            if (!file_exists($folder)) {

                                mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id, 0755);

                                mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0755);

                                mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0755);

                                // Finally, chmod it to 777

                                chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id, 0777);

                                chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0777);

                                chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0777);



                                $copied = copy($path, $filename);

                                unlink($path);

                                $thumb_name1 = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs1/thumb_" . $files;

                                $thumb1 = square_crop($filename, $thumb_name1, 100);

                                $thumb_name = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs/thumb_" . $files;

                                $thumb = square_crop($filename, $thumb_name, 150);
                            }



                            $dsp_members_photos_table = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;

                            $exist_user_photo = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_members_photos_table where user_id='$user_id'"));

                            if ($exist_user_photo <= 0) {

                                $member_photos = "INSERT INTO $dsp_members_photos_table SET user_id='$user_id' ,picture='$files', status_id=1";

                                $wpdb->query($wpdb->prepare($member_photos));
                            }
                        }
                    }
                } // End if($images == 'checked')

                $question_option_id = $_REQUEST['option_id'];

//print_r($question_option_id);

                $hidden = $_POST['hidden'];

//print_r($hidden);

                $random_option_id = $_REQUEST['random_option_id'];

                if ($question_option_id != "") {

                    foreach ($question_option_id as $key => $value) {

                        if ($value != 0) {



                            $myrows_options = $wpdb->get_row("SELECT * FROM $dsp_question_options_table Where question_id=$key AND question_option_id=$value");



                            $insert = "INSERT INTO $dsp_question_details SET user_id = %d,profile_question_id = %d ,profile_question_option_id=%d,option_value=%s";
                            $option_value = addslashes($myrows_options->option_value);
                            $exist_user_question_options = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_question_details where user_id=%d and profile_question_id =%d",$user_id,$key));

                            if ($exist_user_question_options <= 0) {
                                $question_details_value = array($user_id,$key,$value,$option_value);
                                $wpdb->query($wpdb->prepare($insert,$question_details_value));
                            }
                        } // End if($value!="")
                    } // End foreach($question_option_id1 as $key=>$value)
                }

                if ($hidden != "") {

                    foreach ($hidden as $key1 => $value1) {

//echo "<br>***********".$key1.$value1."***********<br>";

                        if ($value1 == 1) {



                            $myrows_options = $wpdb->get_row("SELECT * FROM $dsp_question_options_table Where question_id=$key1 ORDER BY RAND() limit 1");



                            $insert = "INSERT INTO $dsp_question_details SET user_id = %d,profile_question_id = %d ,profile_question_option_id= %d,option_value=%s";

                            //echo $insert;
                            $option_value_details = addslashes($myrows_options->option_value);

                            $exist_user_question_options = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_question_details where user_id='%d' and profile_question_id ='%d'",array($user_id,$key1)));



                            if ($exist_user_question_options <= 0) {
                                $option_details_values = array($user_id,$key1,$myrows_options->question_option_id,$option_value_details);
                                $wpdb->query($wpdb->prepare($insert, $option_details_values));
                            }
                        } else {





                            foreach ($question_option_id as $key => $value) {

                                if ($value != 0) {







                                    $myrows_options = $wpdb->get_row("SELECT * FROM $dsp_question_options_table Where question_id=$key AND question_option_id=$value");

                                    $insert = "INSERT INTO $dsp_question_details SET user_id = %d,profile_question_id = %d ,profile_question_option_id= %d,option_value=%s";
                                    $exist_profile_details = addslashes($myrows_options->option_value);

                                    $exist_user_question_options = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_question_details where user_id= %d and profile_question_id =%d",$user_id,$key1));

                                    if ($exist_user_question_options <= 0) {

                                        $exist_user_question_details_values = array($user_id,$key1,$value, $exist_profile_details);

                                        $wpdb->query($wpdb->prepare($insert,$exist_user_question_details_values));
                                    }
                                }
                            }
                        }
                    } // End foreach($question_option_id1 as $key=>$value)
                }
            }
        }

        fclose($fp);
    } else {

        $table_name = $_REQUEST['table_name'];
        $get_data = $wpdb->get_results("select * from $table_name");

        foreach ($get_data as $text) {



            $username = $text->UserName;
            $AboutMe = $text->AboutMe;
            $MyMatch = $text->MyMatch;


            $email = $username . "@test.com";

            $random_password = wp_generate_password(12, false);

            $dsp_users_table = $wpdb->prefix . users;

            $dsp_blacklist_members = $wpdb->prefix . dsp_blacklist_members;

//Get the IP of the person registering

            $ip = $_SERVER['REMOTE_ADDR'];

            $check_blacklist_ipaddress_table = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_blacklist_members where ip_address = %s AND  ip_status= %d ",$ip,1));

            if ($check_blacklist_ipaddress_table <= 0) {

                $status = wp_create_user($username, $random_password, $email);

                $users_table = $wpdb->get_row("SELECT * FROM $dsp_users_table where user_login='$username' ");



                $user_id = $users_table->ID;



                $ip_address_status = 0;

                //Add user metadata to the usermeta table

                update_user_meta($user_id, 'signup_ip', $ip);

                update_user_meta($user_id, 'ip_address_status', $ip_address_status);


                $dsp_blacklist_values = array($username,$ip,0);
                $wpdb->query($wpdb->prepare("INSERT INTO $dsp_blacklist_members SET user_name = %s, ip_address =%s ,ip_status=%d",$dsp_blacklist_values));



                $countryName = $_POST['cmbCountry'];

                $get_Country = $wpdb->get_row("SELECT * FROM $dsp_country_table WHERE name = '" . $countryName . "'");

                $countryId = $get_Country->country_id;

                $stateName = $_POST['cmbState'];

                $get_State = $wpdb->get_row("SELECT * FROM $dsp_state_table WHERE name = '" . $stateName . "'");

                $stateId = $get_State->state_id;

                $cityName = $_POST['cmbCity'];
                if (isset($_POST['city_random']) && $_POST['city_random'] == 1) {
                    if ($stateId == "") {

                        $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE country_id=" . $countryId . " order by rand() limit 1");
                    } else {

                        $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE state_id=" . $stateId . " and country_id=" . $countryId . " order by rand() limit 1");
                    }
                } else {
                    $cityNamenew = str_replace("`", "\'", $cityName);

                    if ($stateId == "") {

                        $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE name = '" . $cityNamenew . "' and country_id=" . $countryId);
                    } else {

                        $get_City = $wpdb->get_row("SELECT * FROM $dsp_city_table WHERE name = '" . $cityNamenew . "' and state_id=" . $stateId);
                    }
                }
                $cityId = $get_City->city_id;

                $gender = $_POST['gender'];

                $seeking = $_POST['seeking'];

                $to_age_range = $_POST['to_age_range'];

                $from_age_range = $_POST['from_age_range'];

                $aboutme = $_POST['aboutme'];

                $mymatch = $_POST['mymatch'];

                $images = $_POST['images'];

                $interests = $_POST['interests'];

                $hidden = $_POST['hidden'];

                $random_age = rand($to_age_range, $from_age_range);



                $today = date('Y-m-d');

                $strDateTo = date("Y-m-d", strtotime('-' . $random_age . 'years', strtotime($today)));

//$To=date('Y-m-d', strtotime('-'.$to_age_range.'days', strtotime('2012-08-02')));echo "<br>"; 
//$From=date('Y-m-d', strtotime('-'.$from_age_range.'days', strtotime('2012-08-02')));echo "<br>";  

                $dsp_interest_tags_table = $wpdb->prefix . DSP_INTEREST_TAGS_TABLE;

                $keyinterest = $wpdb->get_results("SELECT keyword FROM $dsp_interest_tags_table ORDER BY RAND() limit $interests");

                $keyword = "";

                foreach ($keyinterest as $interest)
                    $keyword.=$interest->keyword . ",";

                $keyword = substr($keyword, 0, -1);

                $last_update_date = date('Y-m-d H:i:s');



                $about = addslashes($AboutMe);

                $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

                $query1 = "INSERT INTO $dsp_user_profiles SET user_id='$user_id', country_id='$countryId', state_id='$stateId', city_id='$cityId', gender='$gender', seeking='$seeking', age='$strDateTo', my_interest='$keyword', about_me='$about', status_id=1, last_update_date='$last_update_date'";



                $exist_user_profile = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_user_profiles where user_id= %d",$user_id));

                if ($exist_user_profile <= 0) {

                    $wpdb->query($wpdb->prepare($query1));
                }


                $MyMatch = addslashes($MyMatch);
                $dsp_profile_question_details_table = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;

                $profile_questions = "INSERT INTO $dsp_profile_question_details_table SET user_id='%d' ,profile_question_id=%d, option_value= %s";
                $match_option_value = addslashes($MyMatch);
                $exist_user_profile_questions = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_profile_question_details_table where user_id=",$user_id));

                if ($exist_user_profile_questions <= 0) {
                    $values = array($user_id,21,$MyMatch);
                    $wpdb->query($wpdb->prepare($profile_questions,$values));
                }



                if ($images == 'checked') {

                    $dir = WP_DSP_ABSPATH . "generator/images";

//chmod($dir, 0777); 	 

                    $file_display = array('jpg', 'jpeg', 'png', 'gif');

                    if (file_exists($dir) && is_dir($dir) == false) { // mobile folder exist 
//echo "directory not found";
                    } else {

                        $directory_contents = scandir($dir);

                        if ($directory_contents[0] == '.' && $directory_contents[1] == '..') {

                            unset($directory_contents[0]);

                            unset($directory_contents[1]);

                            $directory_contents = array_values($directory_contents);
                        }

//foreach($directory_contents as $files){

                        $k = array_rand($directory_contents);

                        $files = $directory_contents[$k];

//$file_type=explode('.' , $files);
//print_r($file_type);

                        $file_type = strtolower(end(explode('.', $files)));

//print_r($file_type);

                        if ($files !== "." && $files !== ".." && in_array($file_type, $file_display) == true) {



                            $path = $dir . "/" . $files;

                            chmod($path, 0777);



                            $folder = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/";

                            $filename = $folder . $files;

                            if (!file_exists($folder)) {

                                mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id, 0755);

                                mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0755);

                                mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0755);

                                // Finally, chmod it to 777

                                chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id, 0777);

                                chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0777);

                                chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0777);



                                $copied = copy($path, $filename);

                                unlink($path);

                                $thumb_name1 = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs1/thumb_" . $files;

                                $thumb1 = square_crop($filename, $thumb_name1, 100);

                                $thumb_name = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs/thumb_" . $files;

                                $thumb = square_crop($filename, $thumb_name, 150);
                            }



                            $dsp_members_photos_table = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;

                            $exist_user_photo = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_members_photos_table where user_id=%d",$user_id));

                            if ($exist_user_photo <= 0) {

                                $member_photos = "INSERT INTO $dsp_members_photos_table SET user_id= %d ,picture=%s, status_id=1";

                                $wpdb->query($wpdb->prepare($member_photos,$user_id,$files));
                            }
                        }
                    }
                } // End if($images == 'checked')

                $question_option_id = $_REQUEST['option_id'];

//print_r($question_option_id);

                $hidden = $_POST['hidden'];

//print_r($hidden);

                $random_option_id = $_REQUEST['random_option_id'];

                if ($question_option_id != "") {

                    foreach ($question_option_id as $key => $value) {

                        if ($value != 0) {
                            $myrows_options = $wpdb->get_row("SELECT * FROM $dsp_question_options_table Where question_id=$key AND question_option_id=$value");
                            $insert = "INSERT INTO $dsp_question_details SET user_id = %d,profile_question_id = %d ,profile_question_option_id= %d,option_value= %s";
                            $option_value = addslashes($myrows_options->option_value);
                            $exist_user_question_options = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_question_details where user_id= %d and profile_question_id = %d",$user_id,$key));

                            if ($exist_user_question_options <= 0) {
                                $dsp_question_details_values = array($user_id,$key,$value,$option_value);
                                $wpdb->query($wpdb->prepare($insert,$dsp_question_details_values));
                            }
                        } // End if($value!="")
                    } // End foreach($question_option_id1 as $key=>$value)
                }

                if ($hidden != "") {

                    foreach ($hidden as $key1 => $value1) {

//echo "<br>***********".$key1.$value1."***********<br>";

                        if ($value1 == 1) {



                            $myrows_options = $wpdb->get_row("SELECT * FROM $dsp_question_options_table Where question_id=$key1 ORDER BY RAND() limit 1");



                            $insert = "INSERT INTO $dsp_question_details SET user_id = %d,profile_question_id =%d ,profile_question_option_id=%d,option_value= %s";
                            $question_option_id1 = addslashes($myrows_options->question_option_id);
                            $option_value1 = addslashes($myrows_options->option_value);
                            //echo $insert;
                            $exist_user_question_options = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_question_details where user_id=%d and profile_question_id = %d",$user_id,$key1));
                            if ($exist_user_question_options <= 0) {
                                $dsp_question_options_values = array($user_id,$key1,$question_option_id1,$option_value1);
                                $wpdb->query($wpdb->prepare($insert,$dsp_question_options_values));
                            }
                        } else {





                            foreach ($question_option_id as $key => $value) {

                                if ($value != 0) {
                                   $myrows_options = $wpdb->get_row("SELECT * FROM $dsp_question_options_table Where question_id=$key AND question_option_id=$value");

                                    $insert = "INSERT INTO $dsp_question_details SET user_id = %d,profile_question_id =%d,profile_question_option_id= %d,option_value=%s";
                                    $option_value_details = addslashes($myrows_options->option_value);
                                    $exist_user_question_options = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $dsp_question_details where user_id= %d and profile_question_id =%d",$user_id,$key1));

                                    if ($exist_user_question_options <= 0) {
                                        $dsp_question_details_values  = array($user_id,$key1,$value,$option_value_details);
                                        $wpdb->query($wpdb->prepare($insert,$dsp_question_details_values));
                                    }
                                }
                            }
                        }
                    } // End foreach($question_option_id1 as $key=>$value)
                }
            }
        }
    }
}
?>

<div>

    <div style="height:20px;"></div>

    <div  style="color:#FF0000; font-weight:bold; margin-bottom: 7px;"> NOTE: Please consult this <a href="http://www.wpdating.com/support/forums/how-to/profile-generator/" style=" color:#FF0000;">Support Forum Thread</a> before using the Profile Generator.</div>