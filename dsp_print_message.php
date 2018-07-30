<?php if (isset($check_membership_msg) && $check_membership_msg != "") { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                if ($check_membership_msg == "Expired") {
                    $message = language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                }
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></div>
                <div align="center" valign="top"><a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"><?php echo language_code('DSP_CLICK_HERE_LINK'); ?></a></div>
            </div>
        </div>
    </div>
<?php if ($pageurl  == 15 || (isset($addDiv) && $addDiv)){ ?>
    </div>
<?php } ?>
<?php } else if (isset($check_member_trial_msg) && $check_member_trial_msg != "") { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                if ($check_member_trial_msg == "Expired") {
                    $message = language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_member_trial_msg == "NoAccess") {
                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_member_trial_msg == "Expired") {
                    $message = language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_member_trial_msg == "NotExist") {
                    if ($access_feature_name != '') {
                        $msg = language_code('DSP_PROFILE_EXISTS_TO_USE_FEATURES_MESSAGE')." ". $access_feature_name;
                    } else {

                        $msg = language_code('DSP_PROFILE_EXISTS_TO_USE_FEATURES_MESSAGE');
                    }
                    ?><script type="text/javascript">
                                var message = "<?php echo $msg ?>";
                                alert(message);
                                var loc = "<?php echo $root_link ?>";

                                loc += "?pid=2";

                                window.location.href = loc;
                    </script>
                    <?php
                } else if ($check_member_trial_msg == "Approved") {

                    $msg = language_code("DSP_PROFILE_APPROVED_MESSAGE");
                    ?><script type="text/javascript">
                                var message = "<?php echo $msg ?>";
                                alert(message);
                                var loc = "<?php echo $root_link ?>";

                                loc += "?pid=2";

                                window.location.href = loc;
                    </script>
                    <?php
                }
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></div>
                <div align="center" valign="top"><a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"><?php echo language_code('DSP_CLICK_HERE_LINK'); ?></a></div>
            </div>
        </div>
    </div>
<?php } else if (isset($check_free_email_msg) && $check_free_email_msg != "") {  ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                if ($check_free_email_msg == "NoAccess") {
                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } if ($check_free_email_msg == "Onlypremiumaccess") {
                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                }if ($check_free_email_msg == "Expired") {
                    $message = language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                }
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></div>
                <div align="center" valign="top"><a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"><?php echo language_code('DSP_CLICK_HERE_LINK'); ?></a></div>
            </div>
        </div>
    </div>
<?php } else if (isset($check_approved_profile_msg) && $check_approved_profile_msg != '') { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                if ($check_approved_profile_msg == "NoAccess") {
                    $message = language_code('DSP_ADMIN_DELETE_PROFILE_MESSAGE');
                } else if ($check_approved_profile_msg == "NoExist") {
                    $message = language_code('DSP_NO_PROFILE_EXISTS_MESSAGE');
                }
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></div>
            </div>
        </div>
    </div>
<?php } else if (isset($check_force_profile_msg) && $check_force_profile_msg != "") { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                if ($check_force_profile_msg == "Expired") {
                    $message = language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } else
                if ($check_force_profile_msg == "NoAccess") {
                    if (isset($access_feature_name) && $access_feature_name != '') {
                        $msg = language_code('DSP_PROFILE_EXISTS_TO_USE_FEATURES_MESSAGE')." ". $access_feature_name;
                    } else {
                        $msg = language_code('DSP_PROFILE_EXISTS_TO_USE_FEATURES_MESSAGE');
                    }
                    ?><script type="text/javascript">
                                var message = "<?php echo $msg ?>";
                                alert(message);
                                var loc = "<?php echo $root_link . "edit/" ?>";

                                //loc +="?pid=2";

                                window.location.href = loc;
                    </script>
                    <?php
                } else if ($check_force_profile_msg == "Approved") {

                    $msg = language_code("DSP_PROFILE_APPROVED_MESSAGE");
                    ?><script type="text/javascript">
                                var message = "<?php echo $msg ?>";
                                alert(message);
                                var loc = "<?php echo $root_link ?>";

                                loc += "?pid=2";

                                window.location.href = loc;
                    </script>
                    <?php
                }
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></div>
                <div align="center" valign="top"><a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"><?php echo language_code('DSP_CLICK_HERE_LINK'); ?></a></div>
            </div>
        </div>
    </div>
<?php if ($pageurl  == 15){ ?>
    </div>
<?php } ?>
<?php } else if ($check_limit_profile_mode->setting_status == 'Y' && isset($check_membership_msg)) { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                if ($check_membership_msg == "NoAccess") {
                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } if ($check_membership_msg == "Onlypremiumaccess") {
                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                }if ($check_membership_msg == "Expired") {
                    $message = language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                }
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></div>
                <div align="center" valign="top"><a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"><?php echo language_code('DSP_CLICK_HERE_LINK'); ?></a></div>
            </div>
        </div>
    </div>
<?php } else if (isset($no_of_credits) && $no_of_credits == 0) {
    ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <div align="center" valign="top"><span  style="color:#FF0000;"><?php echo language_code('DSP_NOT_PREMIUM_EMAIL_MESSAGE');?></span> <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"><?php echo language_code('DSP_CLICK_HERE_LINK'); ?></a></div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                    $frnd_userid = isset($_REQUEST['frnd_id']) ? $_REQUEST['frnd_id'] : '';
                    $print_msg = ($user_id == $frnd_userid) ? language_code('DSP_CANT_SEND_MESSAGE_YOURSELF_MSG') : language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE');
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $print_msg; ?></div>
                <div align="center" valign="top"><a href="<?php echo $root_link . get_username($frnd_userid) . "/"; ?>" class="dspdp-btn dspdp-btn-info" ><?php echo language_code('DSP_BACK_TO_PROFILE_LINK') ?></a></div>
            </div>
        </div>
    </div>
<?php } ?>
