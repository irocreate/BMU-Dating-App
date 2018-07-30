<?php

function checkEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

//require_once(ABSPATH . WPINC . '/registration.php');
global $wpdb;
include_once(WP_DSP_ABSPATH . 'dsp_validation_functions.php');
$current_user = wp_get_current_user();
$user_ID = $current_user->ID;
//Check whether the user is already logged in
if (!$user_ID) {
    global $reg_msg;
    $reg_msg = '';
    //echo $challenge = isset($_POST['in_challenge']) ? $_POST['in_challenge'] : "";
    $fail_challenge = false;
    $sent = false;
    $first_num = 10;
    // second number random value
    $second_num = 12;
    if (isset($_POST['submit'])) {// check user registration
        //We shall SQL escape all inputs
        //$username = $wpdb->escape($_REQUEST['username']);
        /*
          $challenge=$_POST['in_challenge'];

          $operation=$first_num + $second_num;
         */
        $username = $wpdb->escape(sanitizeData(trim($_REQUEST['username']), 'xss_clean'));

        if (empty($username)) {

            $reg_msg = language_code('DSP_USER_NAME_SHOULD_NO_BE_EMPTY');
        } else {

            $email = $wpdb->escape(sanitizeData(trim($_REQUEST['email']), 'xss_clean'));
            //if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email))
            /* if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email))
              {
              $reg_msg= language_code('DSP_PLEASE_ENTER_A_VALID_EMAIL');
              echo $reg_msg;
              } */

            $res = checkEmail($email);
            if (!$res) {
                $reg_msg = language_code('DSP_PLEASE_ENTER_A_VALID_EMAIL');
            }

            /* if(empty($challenge) ||  ($challenge != $operation))
              {
              $fail_challenge = true;
              } */ else {
                $sent = true;
                $random_password = wp_generate_password(12, false);
                $dsp_users_table = $wpdb->prefix . users;
                $dsp_blacklist_members = $wpdb->prefix . dsp_blacklist_members;
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
                    if (( is_wp_error($status))) {
                        $reg_msg = language_code('DSP_USER_NAME_ALREADY_EXIST_PLEASE_TRY_ANOTHER_ONE');
                    } else {
                        $from = get_option('admin_email');
                        $headers = DSP_FROM . $from . "\r\n";
                        $subject = DSP_REGISTERATION_SUCCESSFULL;
                        $message = DSP_YOUR_LOGIN_DETAIL . "\n" . DSP_USER_NAME . $username . "\n" . DSP_PASSWORD . $random_password;
                        wp_mail($email, $subject, $message, $headers);
                        $reg_msg = language_code('DSP_PLEASE_CHECK_YOUR_EMAIL_FOR_LOGIN_DETAIL');
                    }
                }
            }
        }
    }

    /* 	if($_POST['user-submit'])
      {
      global $login_errors;

      // Get redirect URL
      //$redirect_to = trim(stripslashes(get_option('sidebarlogin_login_redirect')));
      $redirect_to ="";
      if (empty($redirect_to)) :
      if (isset($_REQUEST['redirect_to']))

      //	$redirect_to = $_REQUEST['redirect_to'];
      $redirect_to="http://localhost/wordpress_dating/?page_id=7";
      else
      //echo 'adsa';
      //$redirect_to = sidebar_login_current_url('nologout');
      $redirect_to="http://localhost/wordpress_dating/?page_id=7";
      endif;
      //echo 're'.$redirect_to;
      // Check for Secure Cookie
      $secure_cookie = '';

      // If the user wants ssl but the session is not ssl, force a secure cookie.
      if ( !empty($_POST['log']) && !force_ssl_admin() ) {
      //echo 'sdsd';
      $user_name = sanitize_user($_POST['log']);
      //echo $user_name;
      if ( $user = get_userdatabylogin($user_name) ) {
      //echo '<br>user_id'.$user->ID;
      if ( get_user_option('use_ssl', $user->ID) ) {
      //	echo '<br>get_user_option';
      $secure_cookie = true;
      force_ssl_admin(true);
      }
      }
      }

      if ( force_ssl_admin() ) $secure_cookie = true;
      //echo 'cookie'.$secure_cookie;
      if ( $secure_cookie=='' && force_ssl_login() ) $secure_cookie = false;
      // Login
      $user = wp_signon('', $secure_cookie);
      if ( !is_wp_error($user) )
      {
      //echo 'hi';
      }
      if ( is_wp_error($user) )
      // echo 'msg'.$user->get_error_message();
      // Redirect filter
      wp_safe_redirect( apply_filters('login_redirect', $redirect_to, isset( $redirect_to ) ? $redirect_to : '', $user) );
      exit;
      if ( $secure_cookie && strstr($redirect_to, 'wp-admin') ) $redirect_to = str_replace('http:', 'https:', $redirect_to);

      // Check the username
      if ( !$_POST['log'] ) :
      $user = new WP_Error();
      //$user->add('empty_username', __('<strong>ERROR</strong>: Please enter a username.', 'sblogin'));
      elseif ( !$_POST['pwd'] ) :
      $user = new WP_Error();
      //$user->add('empty_username', __('<strong>ERROR</strong>: Please enter your password.', 'sblogin'));
      endif;

      // Redirect if successful
      if ( !is_wp_error($user) ) :
      //echo 'no error'.$redirect_to;
      wp_safe_redirect( apply_filters('login_redirect', $redirect_to, isset( $redirect_to ) ? $redirect_to : '', $user) );
      exit;
      endif;

      $login_errors = $user;


      } */
    ?>
    <!-- <script src="http://code.jquery.com/jquery-1.4.4.js"></script> -->
    <!-- Remove the comments if you are not using jQuery already in your theme -->

    <?php
//Check whether user registration is enabled by the administrator 
    ?>
    <div class="dsp_mb_header"><?php echo language_code('DSP_GUEST_HEADER_LOGIN') ?></div><br>
    <div class="dsp_mb_gray">


        <form method="post" action="<?php bloginfo('url') ?>/wp-login.php"  >
            <table style="margin-left: 20px">

                <?php if (isset($msg)) {
                    ?>
                    <tr>
                        <td><?php echo $msg ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td><?php _e(language_code('DSP_USER_NAME')); ?></td>
                </tr>
                <tr>
                    <td><input type="text" name="log" value="<?php if (isset($user_login)) echo esc_attr(stripslashes($user_login)); ?>" size="20" id="user_login" tabindex="11" /></td>
                </tr>
                <tr>
                    <td><?php _e(language_code('DSP_PASSWORD')); ?></td>
                </tr>
                <tr>
                    <td><input type="password" name="pwd" value="" size="20" id="user_pass" tabindex="12" /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="checkbox" name="rememberme" value="forever" checked="checked" id="rememberme" tabindex="13" /><?php echo language_code('DSP_REMEMBER_ME') ?>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><?php do_action('login_form'); ?>
                        <input type="submit" name="user-submit" value="<?php _e(language_code('DSP_LOGIN')); ?>" tabindex="14" class="user-submit" />
                        <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
                        <input type="hidden" name="user-cookie" value="1" /></td>
                </tr>
                </tr>
            </table>	
        </form>
    </div><br>

    <div id="result"></div> <!-- To hold validation results -->
    <div class="dsp_mb_header"><?php echo language_code('DSP_GUEST_HEADER_REGISTER') ?></div><br>
    <?php if (get_option('users_can_register')) { ?>
        <div class="dsp_mb_gray">

            <form action="" method="post">
                <table style="margin-left: 20px;" border="0" >

                    <?php if (isset($reg_msg)) {
                        ?>
                        <tr>
                            <td><?php echo $reg_msg ?></td>
                        </tr>
                    <?php } ?>

                    <tr>
                        <td ><?php echo language_code('DSP_USER_NAME'); ?></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="username" class="text" value="" /></td>
                    </tr>
                    <tr>
                        <td><?php echo language_code('DSP_EMAIL_ADDRESS'); ?></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="email" class="text" value="" /></td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" id="submitbtn" name="submit" value="<?php echo language_code('DSP_REGISTER'); ?>" /></td>
                    </tr>


                </table>
            </form>
        </div>
        <?php
    } else
        echo language_code('DSP_REGISTRATION_IS_CURRENTLY_DISABLE_PLEASE_TRY_AGAIN_LATER');
    ?>
    <!--<script type="text/javascript">
    //<![CDATA[
    $("#submitbtn").click(function() {
    $('#result').html('<img src="<?php bloginfo('template_url') ?>/images/loader.gif" class="loader" />').fadeIn();
    var input_data = $('#wp_signup_form').serialize();
    alert(input_data);
    $.ajax({
    type: "POST",
    url:  "",
    data: input_data,
    success: function(msg){
    $('.loader').remove();
    $('<div>').html(msg).appendTo('div#result').hide().fadeIn('slow');
    }
    });
    return false;
    });
    //]]>
    </script>-->

    <?php
}
else {
    wp_redirect(home_url());
    exit;
}
?>