<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a onclick="viewSetting(0, 'setting')"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_SUBMENU_SETTINGS_ACCOUNT'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>
</div>

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

    <div class="thanks">

        <p align="center" class="error"><?php echo language_code('DSP_ACCOUNT_SETTINGS_UPDATED'); ?></p>
    </div>

<?php } ?>



<?php //---------------------------------------START ACCOUNT SETTINGS ------------------------------------// ?>



<div class="ui-content" data-role="content">
    <div class="content-primary">	
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
            <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                <form id="dspAccount" >

                    <div class="setting-page-account" style="width: 100%;float: left;">

                        <div><?php echo language_code('DSP_USERNAME') ?>:</div>

                        <div><input type="text" name="txtusername" value="<?php echo $user_account_details->user_login ?>" disabled="disabled" /><br /><span style="color:red; font-size: 12px;"><?php echo language_code('DSP_NOT_CHANGE_USERNAME') ?></span></div>

                        <div><?php echo language_code('DSP_PASSWORD') ?></div>

                        <div><input type="password" name="txtpassword1" value="" />

                            <?php if (isset($Pass1Error) && $Pass1Error != '') {
                                ?>
                            <br />
                                <span class="error" style="font-size: 12px"><?php echo $Pass1Error; ?></span> 
                            <?php } ?>
                        </div>

                        <div><?php echo language_code('DSP_CONFIRM_PASSWORD') ?>:</div>

                        <div>
                            <input type="password" name="txtpassword2" value="" />

                            <?php if (isset($confirmError) && $confirmError != '') {
                                ?>
                                <br />
                                <span class="error" style="font-size: 12px"><?php echo $confirmError; ?></span> 

                            <?php } ?>

                        </div>
                        <div><?php echo language_code('DSP_TEXT_EMAIL') ?>:</div>

                        <div><input type="text" name="txtemailbox" value="<?php echo $user_account_details->user_email ?>" />


                            <?php if (isset($EmailError) && $EmailError != '') { ?>


                                <br />
                                <span class="error" style="font-size: 12px"><?php echo $EmailError; ?></span> 



                            <?php } ?>
                        </div>




                        <div class="btn-row">
                            <input type="hidden" name="pagetitle" value="<?php echo $profile_pageurl; ?>" />
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                            <input type="hidden" name="mode" value="<?php echo 'update'; ?>" />
                            <input type="button" onclick="viewSetting(0, 'post')" name="change_account" value="<?php echo language_code('DSP_SUBMIT_BUTTON') ?>" class="reply-btn" />
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


                </form>
            </li>
        </ul>

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