<div role="banner" class="ui-header ui-bar-a" data-role="header">
 <a class="ui-btn-left ui-btn-corner-all ui-icon-back ui-btn-icon-notext ui-shadow"  onclick="viewSetting(0, 'setting')" href="#" >
            </a> 
    
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_SUBMENU_SETTINGS_ACCOUNT'); ?></h1>
    <?php include_once("page_home.php");?> 
</div>


    <div class="ui-content" data-role="content">
        <div class="content-primary">   

<?php
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$DSP_PAYMENTS_TABLE = $wpdb->prefix . DSP_PAYMENTS_TABLE;

//----- code for cancel subscription------------------------
$getMemProfIDQuery = "select recurring_profile_id from $DSP_PAYMENTS_TABLE where  pay_user_id=$user_id and recurring_profile_status='1'";
$recurring_profile_res = $wpdb->get_row($getMemProfIDQuery);
//----- code for cancel subscription------------------------

$txtusername = isset($_REQUEST['txtusername']) ? $_REQUEST['txtusername'] : '';


if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "update") {
    //Check to make sure sure that a valid email address is submitted
    if (trim($_REQUEST['txtemailbox']) === '') {
        $EmailError = language_code('DSP_FORGOT_ENTER_MAIL_ADDRESS');
        $hasError = true;
    }
    //else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_REQUEST['txtemailbox']))) we have replace eregi with filter_var as eregi is deprecated
    else if (!filter_var(trim($_REQUEST['txtemailbox']), FILTER_VALIDATE_EMAIL)) {
        $EmailError = language_code('DSP_ENTER_INVALID_MAIL_ADDRESS');
        $hasError = true;
    } else {
        $txtemailbox = trim($_REQUEST['txtemailbox']);
    }

    //Check to make sure that the Password Field is not empty



    if (trim($_REQUEST['txtpassword1']) === "") {
        $Pass1Error = language_code('DSP_ENTER_PASSWORD');
        $hasError = true;
    } else {
        $txtpassword1 = $_REQUEST['txtpassword1'];
    }

    //Check to make sure that the Email is not empty
    if (trim($_REQUEST['txtpassword1']) != trim($_REQUEST['txtpassword2'])) {
        $confirmError = language_code('DSP_PASSWORD_NOT_MATCH_CONFIRM');
        $hasError = true;
    } else {
        $txtpassword2 = $_REQUEST['txtpassword2'];
    }

    //If there is no error, then profile updated

    if (!isset($hasError)) {
        if (isset($_REQUEST['txtemailbox'])) {
            $wpdb->query($wpdb->prepare("UPDATE $wpdb->users SET user_email = '%s' WHERE ID = $user_id", $_REQUEST['txtemailbox']));
        }

        if (isset($_REQUEST['txtpassword1']) && isset($_REQUEST['txtpassword2']) && $_REQUEST['txtpassword1'] == $_REQUEST['txtpassword2']) {

            $errors = $wpdb->query("UPDATE $wpdb->users SET user_pass = '" . wp_hash_password($_REQUEST['txtpassword1']) . "' WHERE ID = $user_id");
        }

        $updated = true;
    }
}

$user_account_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$user_id'");



if (isset($updated) && $updated == true) {
    ?>

    <div class="thanks success-message">

       <?php echo language_code('DSP_ACCOUNT_SETTINGS_UPDATED'); ?>
    </div>

    <?php } ?>



    <?php //---------------------------------------START ACCOUNT SETTINGS ------------------------------------// ?>



            <form id="dspAccount" >
                <fieldset>
                 <label data-role="fieldcontain" class="form-group">  
                        <div class="clearfix">                                    
                            <div class="mam_reg_lf form-label"> <?php echo language_code('DSP_USERNAME').' : '.$user_account_details->user_login."  "; ?></div>
                            <span style="color:red; font-size: 12px;">&nbsp;&nbsp;&nbsp;<?php echo language_code('DSP_NOT_CHANGE_USERNAME') ?></span>
                    </div>
                </label>

                 <label data-role="fieldcontain" class="form-group">  
                        <div class="clearfix">                                    
                            <div class="mam_reg_lf form-label"> <?php echo language_code('DSP_PASSWORD') ?></div>

                        <input type="password" name="txtpassword1" value="" />
                         <?php if (isset($Pass1Error) && $Pass1Error != '') {
                        ?>
                       
                        <span class="error" style="font-size: 12px"><?php echo $Pass1Error; ?></span> 
                        <?php } ?>
                    
                        </div>
                        </label>

                   

                    <label data-role="fieldcontain" class="form-group">  
                        <div class="clearfix">                                    
                            <div class="mam_reg_lf form-label"><?php echo language_code('DSP_CONFIRM_PASSWORD') ?></div>

                    
                        <input type="password"   name="txtpassword2" value="" />
                        <?php if (isset($confirmError) && $confirmError != '') {
                            ?>
                            
                            <span class="error" style="font-size: 12px"><?php echo $confirmError; ?></span> 

                            <?php } ?>

                        </div>
                        </label>

                        
                      
                        <label data-role="fieldcontain" class="form-group">  
                        <div class="clearfix">                                    
                            <div class="mam_reg_lf form-label"> <?php echo language_code('DSP_TEXT_EMAIL') ?></div>

                        <input type="text" name="txtemailbox"   value="<?php echo $user_account_details->user_email ?>" />
                        <?php if (isset($EmailError) && $EmailError != '') { ?>
                            <span class="error" style="font-size: 12px"><?php echo $EmailError; ?></span> 
                            <?php } ?>
                      </div>
                        </label>

                        <div class="btn-row">
                            <input type="hidden" name="pagetitle" value="<?php echo $profile_pageurl; ?>" />
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                            <input type="hidden" name="mode" value="<?php echo 'update'; ?>" />
                            <div class="btn-blue-wrap">
                            <input type="button" class="mam_btn btn-red" onclick="viewSetting(0, 'post')" name="change_account" value="<?php echo language_code('DSP_SUBMIT_BUTTON') ?>" class="reply-btn" />
                            </div>
                        </div>

                    </div>
                    <?php
// show cancel membership only user has payment done and he has his recurring profile id

                    if (count($recurring_profile_res) > 0) {
                        $recurring_profile_id = $recurring_profile_res->recurring_profile_id;

                        if ($recurring_profile_id) {
                            ?>
                            <div style="padding-top: 32px; float: right; padding-right: 10px;">
                                <a onclick="return confirmAction('<?php echo language_code('DSP_R_U_SURE_TO_CANCEL_MEMBERSHIP'); ?>?');" href="<?php
                                    echo add_query_arg(array(
                                        'pid' => '6', 'pagetitle' => 'dsp_cancel_membership'), $root_link);
                                        ?>">
                                        <span style="color: red"><?php echo language_code('DSP_CANCEL_MY_MEMBERSHIP'); ?></span>	
                                    </a>
                                </div>
                                <?php
                            }
                        }
                        ?>
                        </fieldset>

                    </form>
               
        </div>
        <?php include_once('dspNotificationPopup.php'); // for notification pop up      ?>
    </div>


    <?php //------------------------------------- END ACCOUNT SETTINGS  ------------------------------------------ //    ?>
    <script type="text/javascript">

        function confirmAction(msg)
        {
        //alert('ssd');
        var confirmed = confirm(msg);
        return confirmed;
    }
</script>