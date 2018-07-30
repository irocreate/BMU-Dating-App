<?php
@session_start();
$path = $pluginpath . 'image.php';
//require_once(ABSPATH . WPINC . '/registration.php');
global $wpdb;
$current_user = wp_get_current_user();
$user_ID = $current_user->ID;


echo 'sess==' . $_SESSION['security_number'];

//Check whether the user is already logged in
if (!$user_ID) {
    global $msg;
    $msg = '';

    $fail_challenge = false;
    $sent = false;
    if ($_POST) {
        //We shall SQL escape all inputs
        $username = esc_sql(sanitizeData(trim($_REQUEST['username']), 'xss_clean'));
        if (empty($username)) {
            $msg = language_code('DSP_USER_NAME_SHOULD_NO_BE_EMPTY');
        } else {
            $email = esc_sql(sanitizeData(trim($_REQUEST['email']), 'xss_clean'));
            $confirm_email = esc_sql(sanitizeData(trim($_REQUEST['confirm_email']), 'xss_clean'));
            if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email)) {
                $msg = language_code('DSP_PLEASE_ENTER_A_VALID_EMAIL');
            } else if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $confirm_email)) {
                $msg = language_code('DSP_PLEASE_ENTER_A_VALID_EMAIL');
            } else if ($email != $confirm_email) {
                $msg = language_code('DSP_EMAIL_FIELDS_ARE_NOT_SAME');
            } else if ($_REQUEST['terms'] == '') {
                $msg = language_code('DSP_AGREE_TERMS_AND_CONDITIONDS');
            } else if ($_POST['secure'] != $_SESSION['security_number']) {
                $fail_challenge = true;
            } else {

                $sent = true;

                $random_password = wp_generate_password(12, false);

                $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;

                $dsp_blacklist_members = $wpdb->prefix . DSP_BLACKLIST_MEMBER_TABLE;

                //Get the IP of the person registering

                $ip = $_SERVER['REMOTE_ADDR'];

                $check_blacklist_ipaddress_table = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blacklist_members where ip_address = '$ip' AND  ip_status=1 ");

                if ($check_blacklist_ipaddress_table <= 0) {

                    $status = wp_create_user($username, $random_password, $email);

                    $users_table = $wpdb->get_row("SELECT * FROM $dsp_users_table where user_login='$username' ");
                    $user_id = $users_table->ID;

                    $ip_address_status = 0;

                    //Add user metadata to the usermeta table

                    update_user_meta($user_id, 'signup_ip', $ip);
                    update_user_meta($user_id, 'ip_address_status', $ip_address_status);

                    $wpdb->query("INSERT INTO $dsp_blacklist_members SET user_name = '$username', ip_address ='$ip' ,ip_status=0 ");


                    $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
                    $gender = $_POST['gender'];
                    $month = $_POST['dsp_mon'];
                    $day = $_POST['dsp_day'];
                    $year = $_POST['dsp_year'];
                    $age = $year . "-" . $month . "-" . $day;
                    $dateTimeFormat = dsp_get_date_timezone();
                    extract($dateTimeFormat);
                    $last_update_date = date('Y-m-d H:i:s');
                    if ($check_approve_profile_status->setting_status == 'Y') // if Profile approve status is Y then Profile Automatically Approved
                    {
                        $new_user_status = 1;
                    }
                    else
                    {
                        $new_user_status = 0;
                    }
                    $wpdb->query("INSERT INTO $dsp_user_profiles SET user_id = '$user_id', gender ='$gender' ,age='$age',status_id=$new_user_status, edited='Y', last_update_date='$last_update_date' ");

                    if (( is_wp_error($status))) {





                        $msg = language_code('DSP_USER_NAME_ALREADY_EXIST_PLEASE_TRY_ANOTHER_ONE');
                    } else {



                        $from = get_option('admin_email');



                        $headers = language_code('DSP_FROM') . $from . "\r\n";



                        $subject = language_code('DSP_REGISTERATION_SUCCESSFULL');



                        $message = language_code('DSP_YOUR_LOGIN_DETAIL') . "\n" . language_code('DSP_USER_NAME') . $username . "\n" . language_code('DSP_PASSWORD') . $random_password;



                        wp_mail($email, $subject, $message, $headers);



                        $msg = language_code('DSP_PLEASE_CHECK_YOUR_EMAIL_FOR_LOGIN_DETAIL');
                    }
                } else {

                    $msg = language_code('DSP_IP_BLACKLIST_TEXT');
                }
            }
        }
        ?>
        <!-- <script src="http://code.jquery.com/jquery-1.4.4.js"></script> -->
        <!-- Remove the comments if you are not using jQuery already in your theme -->
        <div class="dsp_box-out">
            <div class="dsp_box-in">
                <div class="box-page">
                    <div style="font-weight:bold;color:<?php echo $temp_color; ?>"><?php //Check whether user registration is enabled by the administrator          ?>
                        <?php
                        if ($msg != '') {
                            echo $msg;
                        } else if ($fail_challenge) {
                            echo "Incorrect answer!";
                        }
                        ?></div>
                    <div id="result"></div> <!-- To hold validation results -->
                </div></div></div>

    <?php } else { ?>
        <div class="dsp_box-out">
            <div class="dsp_box-in">
                <div class="box-page">
                    <?php if (get_option('users_can_register')) { ?>
                        <div id="result"></div> 
                        <form action="" method="post" >
                            <div class="dsp_reg_main">
                                <ul>
                                    <li><span><?php echo language_code('DSP_USER_NAME'); ?></span> <input type="text" name="username" class="text" value="" /></li>
                                    <li><span><?php echo language_code('DSP_EMAIL_ADDRESS'); ?></span> <input type="text" name="email" class="text" value="" /></li>
                                    <li><span><?php echo language_code('DSP_CONFIRM_EMAIL_ADDRESS'); ?></span> <input type="text" name="confirm_email" class="text" value="" /></li>
                                    <li><span><?php echo language_code('DSP_REGISTER_GENDER'); ?></span>     <select name="gender" style="width:70px;">
                                            <?php echo get_gender_list(); ?>
                                        </select></li>
                                    <li><span><?php echo language_code('DSP_BIRTH_DATE'); ?></span>
                                        <?php
                                        //array to store the months
                                        $mon = array(1 => language_code('DSP_JANUARY'),
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
                                            language_code('DSP_DECEMBER'));
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
                                        <?php //make the day pull-down menu  ?>
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
                                        <?php //make the year pull-down menu   ?>
                                        <select name="dsp_year"  class="dspdp-form-control dsp-form-control">
                                            <?php
                                                $start_dsp_year = $check_start_year->setting_value;
                                                $end_dsp_year = $check_end_year->setting_value;
                                                echo dsp_get_year($start_dsp_year,$end_dsp_year,$year);
                                            ?>
                                        </select>
                                    </li>
                                    <li><span>&nbsp;</span> <?php
                                        //if($check_terms_page_mode->setting_status== 'Y')
                                        ?>

                                        <?php echo str_replace('[L]', $check_terms_page_mode->setting_value, language_code('DSP_TERMS_TEXT')); ?> <input name="terms" type="checkbox" value="1"  />
                                    </li>
                                    <li><span><?php echo language_code('DSP_CAPTCHA'); ?></span> 
                                        <img src="<?php echo $path ?>" alt="Click to reload image" name="captcha" id="captcha" title="Click to reload image" onclick="javascript:reloadCaptcha()" />
                                        <input type="text" name="secure" value="" onclick="this.value = ''" style="vertical-align:top; width:20px;" />
                                    </li>
                                    <li><span>&nbsp;</span><input type="submit" id="submitbtn" name="submit" value="<?php echo language_code('DSP_REGISTER'); ?>" />
                                        <?php if(!$isPaswordOptionEnabled){?>
                                            <span class="note-res" style=" float:none; margin-left:10px;"><?php echo language_code('DSP_NOTE_A_PASSWORD_WILL_BE_EMAIL_TO_YOU'); ?></span>
                                        <?php } ?>
                                    </li>
                                </ul>
                            </div>
                        </form>
                        <script type="text/javascript">

                            //<![CDATA[
                            /*
                             $j = jQuery.noConflict();
                             $j(document).ready(function(){
                             $j("#submitbtn").click(function() {
                             $j('#result').html('<img src="WPDATE_URL . '/images/loading.gif' " class="loader" />').fadeIn();
                             var input_data = $j('#wp_signup_form').serialize();
                             //alert(input_data);
                             $j.ajax({
                             type: "POST",
                             url: "",
                             data: input_data,
                             success: function(msg){
                             $j('.loader').remove();
                             $j('<div>').html(msg).appendTo('div#result').hide().fadeIn('slow');
                             }
                             });
                             return false;
                             });
                             });*/
                            //]]>
                        </script>
                        <script language="javascript" type="text/javascript">
                            /* this is just a simple reload; you can safely remove it; remember to remove it from the image too */
                            function reloadCaptcha()
                            {
                                document.getElementById('captcha').src = document.getElementById('captcha').src + '?' + new Date();
                            }
                        </script>
                    <?php } else {
                        ?>
                        <span style="float:left; width:100%; text-align:center; color:#ff0000;">
                            <?php echo language_code('DSP_REGISTRATION_IS_CURRENTLY_DISABLE_PLEASE_TRY_AGAIN_LATER'); ?>
                        </span>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    wp_redirect(home_url());
    exit;
}