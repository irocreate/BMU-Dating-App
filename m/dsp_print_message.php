<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <span class="ui-title" />
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>


</div>
<div class="ui-content" data-role="content">
    <div class="content-primary">
        <div style="width: 100%; ">

            <?php
            if (isset($check_membership_msg) && $check_membership_msg != "") {

                if ($check_membership_msg == "Expired") {
                    $message = language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                }
                ?>

                <span   style="color:#FF0000;"><?php echo $message ?></span>


                <div align="center" valign="top" class="MenuiPhone">
                    <a href="dsp_upgrade.html"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a>
                </div>



            <?php } else if (isset($check_member_trial_msg) && $check_member_trial_msg != "") { ?>

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

                        $msg = "You must create your profile before you can use '" . $access_feature_name . "' features";
                    } else {

                        $msg = "You must create your profile before you can use this features";
                    }
                } else if ($check_member_trial_msg == "Approved") {



                    $msg = "Your profile has not been Approved";
                }
                ?>


                <span   style="color:#FF0000;"><?php echo $message ?></span>

                <a  class="MenuiPhone" href="dsp_upgrade.html"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a>


            <?php } else if (isset($check_free_email_msg) && $check_free_email_msg != "") { ?>


                <?php
                if ($check_free_email_msg == "NoAccess") {

                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } if ($check_free_email_msg == "Onlypremiumaccess") {

                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                }if ($check_free_email_msg == "Expired") {

                    $message = language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                }
                ?>


                <span  style="color:#FF0000;"><?php echo $message ?></span>


                <a class="MenuiPhone" href="dsp_upgrade.html"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a>



            <?php } else if (isset($check_approved_profile_msg) && $check_approved_profile_msg != '') { ?>


                <?php
                if ($check_approved_profile_msg == "NoAccess") {

                    $message = language_code('DSP_ADMIN_DELETE_PROFILE_MESSAGE');
                } else if ($check_approved_profile_msg == "NoExist") {

                    $message = language_code('DSP_NO_PROFILE_EXISTS_MESSAGE');
                }
                ?>


                <span   style="color:#FF0000;"><?php echo $message ?></span>



            <?php } else if ($check_force_profile_mode != "") { ?>


                <?php
                if ($check_force_profile_msg == "Expired") {

                    $msg = language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_force_profile_msg == "Onlypremiumaccess") {

                    $msg = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } else

                if ($check_force_profile_msg == "NoAccess") {

                    if (isset($access_feature_name) && $access_feature_name != '') {

                        $msg = "You must create your profile before you can use '" . $access_feature_name . "' features";
                    } else {

                        $msg = "You must create your profile before you can use this features";
                    }
                } else if ($check_force_profile_msg == "Approved") {



                    $msg = "Your profile has not been Approved";
                }
                ?>


                <span  style="color:#FF0000;"><?php echo $msg ?></span>



                <a class="MenuiPhone" href="dsp_upgrade.html"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a>





            <?php } else if ($check_limit_profile_mode != "") { ?>

                <?php
                if ($check_membership_msg == "NoAccess") {

                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                } if ($check_membership_msg == "Onlypremiumaccess") {

                    $message = language_code('DSP_UPGRADE_PREMIUM_MEMBER_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                }if ($check_membership_msg == "Expired") {

                    $message = language_code('DSP_PREMIUM_MEMBER_EXPIRED_MESSAGE') . " " . language_code('DSP_TO') . "&nbsp;" . $access_feature_name . ".";
                }
                ?>



                <span style="color:#FF0000;"><?php echo $message ?></span>


                <a class="MenuiPhone" href="dsp_upgrade.html"><?php echo language_code('DSP_UPGRADE_HERE'); ?></a>

            <?php } else { ?>




                <?php
                $frnd_userid = $_REQUEST['frnd_id'];



                if ($user_id == $frnd_userid) {



                    $print_msg = language_code('DSP_CANT_SEND_MESSAGE_YOURSELF_MSG');
                } else {



                    $print_msg = language_code('DSP_SENT_MESSAGE_ONLY_FRIEND_MSG');
                }
                ?>


                <span style="color:#FF0000;"><?php echo $print_msg; ?></span>



            <?php } ?>
        </div>	
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
</div>