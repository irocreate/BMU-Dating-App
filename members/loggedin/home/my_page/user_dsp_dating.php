<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
///////////////////////////start status update///////////////////////

$update_status  = isset($_REQUEST['update_status']) ? $_REQUEST['update_status'] : '';
$dateTimeFormat = dsp_get_date_timezone();
extract($dateTimeFormat);
$errors = array();
if ($update_status == 'Update') {
    $new_status = isset($_REQUEST['new_status']) ? esc_sql(sanitizeData(trim($_REQUEST['new_status']),
        'xss_clean')) : '';
    $new_status = apply_filters('dsp_spam_filters', $new_status);
    $errors[]   = $new_status == "" ? str_replace('<#name#>', 'status', language_code('DSP_SPAM_FILTER_ERROR')) : '';
    if ($new_status != "") {
        if ($check_approve_profile_status->setting_status == 'Y') {  // if Profile approve status is Y then Profile Automatically Approved.
            $wpdb->query("UPDATE $dsp_user_profiles SET my_status= '$new_status' WHERE user_id = $current_user->ID");
            $status_approval_message = language_code('DSP_UPDATE_STATUS_MESSAGE');
            dsp_add_news_feed($current_user->ID, 'status');
            dsp_add_notification($current_user->ID, 0, 'status');
        } else {
            $wpdb->query("UPDATE $dsp_user_profiles SET my_status= '$new_status' ,status_id=0 WHERE user_id = $current_user->ID");
            $status_approval_message = language_code('DSP_STATUS_UPDATE_IN_HOURS_MSG');
        }
    }
}
?>

<?php
///////////////////////////end status update///////////////////////
if (isset($_REQUEST['Action']) && $_REQUEST['Action'] == 'update') {
    $stealth_mode_id = isset($_REQUEST['smid']) ? $_REQUEST['smid'] : '';
    $smode           = isset($_REQUEST['smode']) ? $_REQUEST['smode'] : '';
    if ($smode == 'Y') {
        $wpdb->query("UPDATE $dsp_user_profiles SET stealth_mode= 'N' WHERE user_id = '$stealth_mode_id'");
    } else {
        $wpdb->query("UPDATE $dsp_user_profiles SET stealth_mode= 'Y' WHERE user_id = '$stealth_mode_id'");
    }
}

$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$current_user->ID'");
$profile_user_id       = isset($exist_profile_details) ? $exist_profile_details->user_id : '';
$count_online_member   = $wpdb->get_var("SELECT COUNT(distinct oln.user_id) FROM $dsp_online_user_table oln INNER JOIN $dsp_user_profiles usr ON ( usr.user_id = oln.user_id ) WHERE oln.status = 'Y' AND usr.country_id !=0 AND usr.stealth_mode = 'N'");
$count_online_member   = isset($count_online_member) ? $count_online_member : '';
?>
<div class="update-message" style="display: none">
    <div class="thanks">
        <p align="center" class="error"></p>
    </div>
</div>

<div class="box-border">
    <div class="dsp-row">
        <div class="box-pedding">
            <div class="dsp-md-4 dsp-block dspdp-profile">
                <div class="box-profile-link">
                    <div class="dsp-user-info-container clearfix margin-btm-3 " style="display:none;">
                        <?php
                        if ($check_couples_mode->setting_status == 'Y') {
                            if ($gender == 'C') {
                                ?>
                                <a href="<?php echo $root_link . get_username($user_id) . "/my_profile/"; ?>"><i
                                            class="fa fa-user"></i></a>
                            <?php } else { ?>
                                <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><i
                                            class="fa fa-user"></i></a>
                            <?php }
                        } else { ?>
                            <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><i
                                        class="fa fa-user"></i></a>
                        <?php } ?>
                        <a href="<?php echo $root_link . "extras/trending/"; ?>"><i class="fa fa-users"></i></a>
                        <a href="<?php echo $root_link . "extras/viewed_me/"; ?>"><i class="fa fa-eye"></i></a>
                        <a href="<?php echo $root_link . "extras/i_viewed/"; ?>"><i class="fa fa-bell-o"></i></a>
                        <a href="<?php echo $root_link . "online_members/show/all/"; ?>"><i
                                    class="fa fa-circle"></i></a>
                        <a href="<?php echo $root_link . "email/inbox/"; ?>"><i class="fa fa-envelope"></i></a>
                    </div>

                    <div class="profile-image" class="profile_image_change">
                        <a class="group1 profile_picture_link"
                           href="<?php echo display_members_original_photo($current_user->ID, $imagepath); ?>"> <img
                                    src="<?php echo display_members_photo($current_user->ID, $imagepath); ?>"
                                    class="img" id="profile_picture"
                                    alt="<?php echo get_username($current_user->ID); ?>"></a>
                        <?php echo do_action('wpdating_profile_pic_change', $current_user->ID); ?>
                    </div>

                    <div class="menus-profile">
                        <ul>
                            <li>
                                <?php
                                if ($check_couples_mode->setting_status == 'Y') {
                                if ($gender == 'C') {
                                ?>
                                <a href="<?php echo $root_link . get_username($user_id) . "/my_profile/"; ?>"
                                   title="<?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>"><i
                                            class="fa fa-user"></i>
                                    <?php } else { ?>
                                    <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"
                                       title=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>"><i
                                                class="fa fa-user"></i>
                                        <?php }
                                        } else { ?>

                                        <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"
                                           title=" <?php echo language_code('DSP_MENU_VIEW_PROFILE') ?>"><i
                                                    class="fa fa-user"></i>

                                            <?php } ?></a>
                            </li>
                            <li><a href="<?php echo $root_link . "extras/trending/"; ?>"
                                   title="<?php echo language_code('DSP_PROFILE_TRENDINGS') ?>"><i
                                            class="fa fa-users"></i></a></li>
                            <li><a href="<?php echo $root_link . "extras/viewed_me/"; ?>"
                                   title="<?php echo language_code('DSP_WHO_VIEWED_ME') ?>"><i
                                            class="fa fa-eye"></i></a></li>
                            <li><a href="<?php echo $root_link . "extras/i_viewed/"; ?>"
                                   title="<?php echo language_code('DSP_WHO_I_VIEWED') ?>"><i class="fa fa-bell-o"></i></a>
                            </li>
                            <li><a href="<?php echo $root_link . "online_members/show/all/"; ?>"
                                   title="<?php echo language_code('DSP_ONLINE_MEMBER') ?>&nbsp;(<?php echo $count_online_member ?>)"><i
                                            class="fa fa-circle"></i></a></li>
                            <li><a href="<?php echo $root_link . "email/inbox/"; ?>"
                                   title="<?php echo language_code('DSP_NEW_EMAIL'); ?>&nbsp;(<?php echo $count_inbox_messages ?>)"><i
                                            class="fa fa-envelope-o"></i></a></li>
                        </ul>
                    </div>
                    <div class="clr"></div>
                    <ul class="text-left dsp-user-spec clearfix dsp-block" style="display:none">

                        <?php if ($check_flirt_module->setting_status == 'Y') { ?>
                            <li <?php if (($profile_pageurl == "view_winks")) { ?>class="dsp_tab1-active" <?php } ?>>
                                <?php if ($count_wink_messages > 0) { ?>
                                    <a href="<?php echo $root_link . "home/view_winks/Act/R/"; ?>"><i
                                                class="fa fa-meh-o"></i><?php echo language_code('DSP_MIDDLE_TAB_WINKS') ?>
                                        &nbsp;(<?php echo $count_wink_messages ?>)</a>
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . "home/view_winks/"; ?>"><i
                                                class="fa fa-meh-o"></i><?php echo language_code('DSP_MIDDLE_TAB_WINKS'); ?>
                                    </a>
                                <?php } ?>
                            </li>
                        <?php } ?>

                        <?php if ($check_my_friend_module->setting_status == 'Y') { ?>
                            <li <?php if (($profile_pageurl == "view_friends")) { ?>class="dsp_active_link" <?php } ?>>
                                <a href="<?php echo $root_link . "home/view_friends/"; ?>"><i
                                            class="fa fa-users"></i><?php echo language_code('DSP_MIDDLE_TAB_FRIENDS'); ?>
                                </a>
                            </li>
                        <?php } ?>


                        <li <?php if (($profile_pageurl == "my_favorites")) { ?>class="dsp_active_link" <?php } ?>>
                            <a href="<?php echo $root_link . "home/my_favorites/"; ?>"><i
                                        class="fa fa-heart"></i><?php echo language_code('DSP_MIDDLE_TAB_MY_FAVOURITES'); ?>
                            </a>
                        </li>


                        <?php if ($check_virtual_gifts_mode->setting_status == 'Y') { ?>
                            <li <?php if (($profile_pageurl == "virtual_gifts")) { ?>class="dsp_active_link" <?php } ?>>
                                <?php if ($count_friends_virtual_gifts > 0) { ?>
                                    <a href="<?php echo $root_link . "home/virtual_gifts/"; ?>"><i
                                                class="fa fa-gift"></i><?php echo language_code('DSP_MIDDLE_TAB_VIRTUAL_GIFTS'); ?>
                                        &nbsp;(<?php echo $count_friends_virtual_gifts ?>) </a>
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . "home/virtual_gifts/"; ?>"><i
                                                class="fa fa-gift"></i><?php echo language_code('DSP_MIDDLE_TAB_VIRTUAL_GIFTS'); ?>
                                    </a>
                                <?php } ?>
                            </li>
                        <?php } ?>

                        <li <?php if (($profile_pageurl == "my_matches")) { ?>class="dsp_active_link" <?php } ?>>
                            <a href="<?php echo $root_link . "home/my_matches/"; ?>"><i
                                        class="fa fa-star"></i><?php echo language_code('DSP_MIDDLE_TAB_MACTHES'); ?>
                            </a>
                        </li>

                        <?php if ($check_match_alert_mode->setting_status == 'Y') { ?>
                            <li <?php if (($profile_pageurl == "match_alert")) { ?>class="dsp_active_link" <?php } ?>>
                                <a href="<?php echo $root_link . "home/match_alert/"; ?>"><i
                                            class="fa fa-bell"></i><?php echo language_code('DSP_SUBMENU_SETTINGS_MATCH_ALERTS'); ?>
                                </a>
                            </li>
                        <?php } ?>

                        <li <?php if ($profile_pageurl == "alerts") { ?>class="dsp_active_link" <?php } ?>>
                            <?php if ($count_friends_request > 0) { ?>
                                <a href="<?php echo $root_link . "home/alerts/"; ?>"><i
                                            class="fa fa-bell"></i><?php echo language_code('DSP_MIDDLE_TAB_ALERTS'); ?>
                                    &nbsp;(<?php echo $count_friends_request ?>) </a>
                            <?php } else { ?>
                                <a href="<?php echo $root_link . "home/alerts/"; ?>"><i
                                            class="fa fa-bell"></i><?php echo language_code('DSP_MIDDLE_TAB_ALERTS'); ?>
                                </a>
                            <?php } ?>
                        </li>

                        <?php if ($check_comments_mode->setting_status == 'Y') { ?>
                            <li <?php if (($profile_pageurl == "comments")) { ?>class="dsp_active_link" <?php } ?>>

                                <?php if ($check_approve_comments_status->setting_status == 'Y') { ?>
                                    <?php if ($count_friends_comments > 0) { ?>
                                        <a href="<?php echo $root_link . "home/comments/"; ?>" style="color:#FF0000;">
                                            <i class="fa fa-comments-o"></i><?php echo language_code('DSP_MIDDLE_TAB_COMMENTS'); ?>
                                            &nbsp;(<?php echo $count_friends_comments ?>)
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . "home/comments/"; ?>">
                                            <i class="fa fa-comments-o"></i><?php echo language_code('DSP_MIDDLE_TAB_COMMENTS'); ?>
                                        </a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . "home/comments/"; ?>">
                                        <i class="fa fa-comments-o"></i><?php echo language_code('DSP_MIDDLE_TAB_COMMENTS'); ?>
                                    </a>
                                <?php } ?>
                            </li>
                        <?php } ?>


                        <li <?php if ($profile_pageurl == "news_feed") { ?>class="dsp_active_link" <?php } ?>>
                            <a href="<?php echo $root_link . "home/news_feed/"; ?>"><i
                                        class="fa fa-bullhorn"></i><?php echo language_code('DSP_MIDDLE_TAB_NEWS_FEED'); ?>
                            </a>
                        </li>

                    </ul>

                </div>


            </div>

            <div class="dsp-md-8">
                <div class="dsp-welcome-text dsp-block" style="display:none">
                    <h1><?php echo language_code('DSP_WELCOME') . ' ' ?><?php echo $displayed_member_name->display_name ?></h1>
                </div>
                <div class="profle-detail">
                    <?php
                    $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$current_user->ID");
                    if ($num_rows != 0) {
                        ?>
                        <div class="Status-box-profile">
                            <?php echo apply_filters('dsp_display_errors', $errors) ?>
                            <form method="post" class="dspdp-form-inline">
                                <div class="update-row"><b
                                            class="dspdp-horiz-spacer"><?php echo language_code('DSP_STATUS_UPDATE'); ?></b><input
                                            class="dspdp-form-control"
                                            placeholder="<?php echo language_code('DSP_STATUS_UPDATE'); ?>"
                                            name="new_status" type="text" maxlength="100"/>
                                    <input type="hidden" name="update_status" value="Update"/>
                                    <input class="btn-update dspdp-btn" type="submit"
                                           value="<?php echo language_code('DSP_UPDATE_BUTTON'); ?>"/></div>
                            </form>
                            <?php
                            if ($update_status == 'Update' && isset($status_approval_message)) {
                                ?>
                                <div class="dspdp-text-success"><?php echo $status_approval_message; ?></div>
                            <?php } ?>
                            <?php if ($exist_profile_details->my_status != "") { ?>
                                <div class="my-status">
                                    <p>
                                        <span><?php echo language_code('DSP_CURRENT_STATUS'); ?></span>
                                        <span><?php echo stripslashes($exist_profile_details->my_status); ?></span>
                                    </p>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <div class="heading-row">
                        <?php if ($check_free_mode->setting_status == 'Y' && $_SESSION['free_member']) {

                            ?>
                            <div class="tab-content dspdp-alert dspdp-alert-success" id="membership">
                                <ul>
                                    <li class="new-title dspdp-light dspdp-h4"><?php echo language_code('DSP_HOME_TAB_MEMBERSHIP'); ?>
                                        <span class="dspdp-bold"><?php echo language_code('DSP_FREE_MEMBER'); ?><span
                                                    class="fa fa-rocket"></span></span></li>
                                    <li class="news-info">
                                        <ul>
                                            <li class="margin-btm-2">
                                                <div
                                                        class=" dspdp-spacer-sm"><?php echo language_code('DSP_HOME_MEMBERSHIP_FREE_TEXT'); ?></div>
                                            </li>
                                            <li><img src="<?php echo $fav_icon_image_path ?>oh-yes-its-free.jpg"
                                                     alt="Free"/></li>
                                        </ul>
                                    </li>
                                </ul>
                                <div>
                                </div>
                            </div>
                        <?php } else { ?>

                            <div class="dspdp-spacer-md">
                                <div class="heading clearfix dspdp-inline-block dspdp-font-2x">
                                    <img src="<?php echo $fav_icon_image_path ?>membership-icon.jpg" border="0"
                                         alt="Membership icon"/>
                                    <?php echo language_code('DSP_HOME_TAB_MEMBERSHIP'); ?>
                                </div>
                                <ul class="quick-star-details  dspdp-inline-block dspdp-bold dspdp-horiz-spacer  dspdp-font-2x">
                                    <?php
                                    $payment_row       = $wpdb->get_row("SELECT * FROM $dsp_payments_table WHERE pay_user_id=$user_id");
                                    if ($payment_row != null && strtotime($payment_row->expiration_date) > time()) {
                                        $now       = time(); // or your date as well
                                        $your_date = strtotime($payment_row->expiration_date);
                                        $datediff  = $your_date - $now;
                                        $days      = floor($datediff / (60 * 60 * 24));
                                        ?>
                                        <li>
                                            <span id="show_premium_div"
                                                  class="dsp-none"><?php echo language_code('DSP_PREMIUM_MEMBER'); ?></span>

                                            <div id="premium_div"
                                                 style="display:none;"><?php echo str_replace('[D]', $days,
                                                    language_code('DSP_PREMIUM_MEMBER_TEXT')); ?></div>
                                        </li>
                                    <?php } else { ?>
                                        <li><?php echo language_code('DSP_STANDARD_MEMBER'); ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php
                            $payment_row       = $wpdb->get_row("SELECT * FROM $dsp_payments_table WHERE pay_user_id=$user_id");
                            if ($payment_row != null && strtotime($payment_row->expiration_date) > time()) {
                                ?>
                                <div class="premium-area dspdp-alert dspdp-alert-success">
                                    <div class="dspdp-row">
                                        <div
                                                class="logo-premium dsp-none dspdp-font-4x dspdp-text-center dspdp-col-sm-3">
                                            <img class="dspdp-block dspdp-spacer-sm"
                                                 src="<?php echo get_bloginfo('url'); ?>/wp-content/uploads/dsp_media/dsp_images/<?php echo $wpdb->get_var("select image from $dsp_memberships_table where membership_id='" . $payment_row->pay_plan_id . "'"); ?>"
                                                 alt="<?php echo $payment_row->pay_plan_name; ?>"/><?php echo $payment_row->pay_plan_name; ?>
                                        </div>
                                        <div class="dspdp-col-sm-9">
                                            <div class="clearfix dspdp-font-2x dspdp-spacer-sm">
                                                <?php echo language_code('DSP_HOME_MEMBERSHIP_PREMIUM_TEXT_A'); ?>
                                                <?php echo $payment_row->pay_plan_name; ?><?php echo language_code('DSP_HOME_MEMBERSHIP_PREMIUM_TEXT_B'); ?>
                                            </div>
                                            <div class="seperator dsp-block" style="display:none"></div>

                                            <div class="clearfix">
                                                <?php echo language_code('DSP_HOME_MEMBERSHIP_PREMIUM_TEXT_C'); ?>
                                                <span
                                                        class="dsp-emphasis-text dsp-strong"><?php echo date(get_option('date_format'),
                                                        strtotime($payment_row->expiration_date)); ?></span>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--                                credit added later-->
                                <div class="seperator dsp-block" style="display:none"></div>
                                <?php if ($check_credit_mode->setting_status == 'Y') { ?>
                                    <div class="dspdp-alert dspdp-alert-info">
                                        <div class=" ">
                                            <div class="heading dspdp-font-2x  dspdp-spacer-sm">
                                                <img src="<?php echo $fav_icon_image_path ?>credite-icon.jpg" border="0"
                                                     alt="credit icon"/>
                                                <?php echo language_code('DSP_USER_CREDITS'); ?>
                                            </div>
                                        </div>

                                        <div class="credit-area">
                                            <?php
                                            $no_of_credits = $wpdb->get_var("select no_of_credits from $dsp_credits_usage_table where user_id='$user_id'");
                                            if ($no_of_credits == null) {
                                                $no_of_credits = 0;
                                            }
                                            ?>
                                            <span
                                                    class=" dspdp-block dspdp-spacer-sm"><?php echo str_replace('[m]',
                                                    $no_of_credits,
                                                    language_code('DSP_CREDIT_HOMEPAGE_TEXT')); ?></span>
                                            <span class="btn-credit dspdp-block"><input name="" type="button"
                                                                                        onclick='javascript:location.href = "<?php echo $root_link . "setting/upgrade_account/"; ?>"'
                                                                                        class="button  dspdp-btn dspdp-btn-default"
                                                                                        value="<?php echo language_code('DSP_BUY_CREDITS'); ?>"/></span>
                                        </div>

                                    </div>
                                <?php } ?>
                                <!--                                later added ends-->
                            <?php } else { ?>
                                <div class="standard-area dspdp-alert dspdp-alert-warning ">
                                    <div
                                            class="dspdp-spacer"><?php echo language_code('DSP_HOME_MEMBERSHIP_STANDARD_TEXT'); ?></div>
                                    <div class="btn-standard">
                                        <input name="" type="button"
                                               onclick='javascript:location.href = "<?php echo $root_link . "setting/upgrade_account/"; ?>"'
                                               class="button dspdp-btn dspdp-btn-warning "
                                               value="<?php echo language_code('DSP_UPGRADE_NOW'); ?>"/>
                                    </div>
                                </div>

                                <div class="seperator dsp-block" style="display:none"></div>
                                <?php if ($check_credit_mode->setting_status == 'Y') { ?>
                                    <div class="dspdp-alert dspdp-alert-info">
                                        <div class=" ">
                                            <div class="heading dspdp-font-2x  dspdp-spacer-sm">
                                                <img src="<?php echo $fav_icon_image_path ?>credite-icon.jpg" border="0"
                                                     alt="credit icon"/>
                                                <?php echo language_code('DSP_USER_CREDITS'); ?>
                                            </div>
                                        </div>

                                        <div class="credit-area">
                                            <?php
                                            $no_of_credits = $wpdb->get_var("select no_of_credits from $dsp_credits_usage_table where user_id='$user_id'");
                                            if ($no_of_credits == null) {
                                                $no_of_credits = 0;
                                                }
                                            ?>
                                            <span
                                                    class=" dspdp-block dspdp-spacer-sm"><?php echo str_replace('[m]',
                                                    $no_of_credits,
                                                    language_code('DSP_CREDIT_HOMEPAGE_TEXT')); ?></span>
                                            <span class="btn-credit dspdp-block"><input name="" type="button"
                                                                                        onclick='javascript:location.href = "<?php echo $root_link . "setting/upgrade_account/"; ?>"'
                                                                                        class="button  dspdp-btn dspdp-btn-default"
                                                                                        value="<?php echo language_code('DSP_BUY_CREDITS'); ?>"/></span>
                                        </div>

                                    </div>
                                <?php }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($check_happening_graph->setting_status == 'Y') { ?>
        <div class="dsp-box-container margin-btm-3">
            <div class="profile-week-details">
                <div class="right-chart">

                    <?php
                    //wp_enqueue_style('dsp_chart',plugins_url("dsp_dating/css/chart.css"));
                    //wp_enqueue_script('dsp_chart',plugins_url("dsp_dating/js/RGraph.common.core.js"),array(),'',true);
                    //wp_enqueue_script('dsp_chart2',plugins_url("dsp_dating/js/RGraph.line.js"),array(),'',true);
                    //global $is_IE;
                    //if ( $is_IE ) {
                    //wp_enqueue_script( 'excanvas', "http://explorercanvas.googlecode.com/svn/trunk/excanvas.js",array(),'',true );
                    //}
                    $startdate1 = date("Y-m-d");
                    $parts      = explode('-', $startdate1);
                    $startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));
                    $startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));
                    $startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));
                    $startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));
                    $startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));
                    $startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));
                    $dates      = array(
                        1 => $startdate7,
                        $startdate6,
                        $startdate5,
                        $startdate4,
                        $startdate3,
                        $startdate2,
                        $startdate1
                    );

                    $check_non_active_tab_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'non_active_tab_color'");
                    ?>

                    <?php /* ?>
                        <div style=" font-weight:bold; text-align:center; margin:5px;">Happening This Week</div><?php */ ?>
                    <div class="view_chart" id="profile_chart" style=" height: 200px; display:block;"></div>
                    <div class="view_chart" id="winks_chart" style="height: 200px; display:none;"></div>
                    <div class="view_chart" id="favorites_chart" style="height: 200px; display:none;"></div>
                    <div class="view_chart" id="friends_chart" style="height: 200px; display:none;"></div>

                    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                    <script type="text/javascript">
                        google.load("visualization", "1", {packages: ["corechart"]});
                        google.setOnLoadCallback(drawChart1);
                        google.setOnLoadCallback(drawChart2);
                        google.setOnLoadCallback(drawChart3);
                        google.setOnLoadCallback(drawChart4);
                        <?php
                        //$array = array(lunes => 'Mon', martes => 'Tue', miércoles => 'Wed', jueves => 'Thu', viernes => 'Fri', sábado => 'Sat', domingo => 'Sun');
                        $array = array(
                            'Mon' => addslashes(language_code('DSP_DAY_MON')),
                            'Tue' => addslashes(language_code('DSP_DAY_TUE')),
                            'Wed' => addslashes(language_code('DSP_DAY_WED')),
                            'Thu' => addslashes(language_code('DSP_DAY_THU')),
                            'Fri' => addslashes(language_code('DSP_DAY_FRI')),
                            'Sat' => addslashes(language_code('DSP_DAY_SAT')),
                            'Sun' => addslashes(language_code('DSP_DAY_SUN'))
                        );

                        ?>
                        function drawChart1() {
                            var data = new google.visualization.DataTable();
                            var day = "<?php echo addslashes(language_code('DSP_DAY'));?>";
                            var view = "<?php echo addslashes(language_code('DSP_USER_PROFILE_VIEWS')); ?>";
                            data.addColumn('string', day);
                            data.addColumn('number', view);
                            data.addRows([
                                <?php
                                $i = 1;
                                while ($i <= 7) {
                                    $todaydate               = $dates[$i];
                                    $today                   = new DateTime($todaydate);
                                    $dsp_counter_hits_table  = $wpdb->prefix . DSP_COUNTER_HITS_TABLE;
                                    $dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
                                    //$veiw_profile = mysql_num_rows(mysql_query("SELECT count(*) FROM $dsp_counter_hits_table  where member_id=$user_id AND review_date='$todaydate' GROUP BY user_id order by review_date "));
                                    //echo '["'.array_search($today->format('D'),$array).'",'.$veiw_profile.']';;
                                    $veiw_profile = $wpdb->get_var("SELECT count(*) FROM $dsp_counter_hits_table  where member_id=$user_id AND review_date='$todaydate' GROUP BY user_id order by review_date ");;
                                    $day = $array[$today->format('D')];
                                    echo '["' . $day . '",' . intval($veiw_profile) . ']';
                                    if ($i < 7) {
                                        echo ",";
                                    }
                                    $i++;
                                }
                                ?>]);

                            var chart = new google.visualization.AreaChart(document.getElementById('profile_chart'));
                            chart.draw(data, {
                                width: 'auto',
                                height: 200,
                                title: '',
                                colors: ['#<?php echo $check_non_active_tab_color->setting_value; ?>'],
                                hAxis: {title: day, titleTextStyle: {color: 'black'}}
                            });
                        }


                        ////////////////////////
                        function drawChart2() {
                            var data = new google.visualization.DataTable();
                            var day = "<?php echo addslashes(language_code('DSP_DAY'));?>";
                            var winks = "<?php echo addslashes(language_code('DSP_USER_WINKS')); ?>";
                            data.addColumn('string', day);
                            data.addColumn('number', winks);
                            data.addRows([
                                <?php
                                $i = 1;
                                while ($i <= 7) {
                                    $todaydate              = $dates[$i];
                                    $today                  = new DateTime($todaydate);
                                    $dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
                                    //$veiw_profile = mysql_num_rows(mysql_query("SELECT count(*) FROM $dsp_member_winks_table WHERE receiver_id = '$user_id' AND send_date='$todaydate' GROUP BY sender_id order by send_date "));
                                    //echo '["'.array_search($today->format('D'),$array).'",'.$veiw_profile.']';;
                                    $veiw_profile = $wpdb->get_var("SELECT count(*) FROM $dsp_member_winks_table WHERE receiver_id = '$user_id' AND send_date='$todaydate' GROUP BY sender_id order by send_date ");;
                                    $day = $array[$today->format('D')];
                                    echo '["' . $day . '",' . intval($veiw_profile) . ']';
                                    if ($i < 7) {
                                        echo ",";
                                    }
                                    $i++;
                                }
                                ?>]);

                            var chart = new google.visualization.AreaChart(document.getElementById('winks_chart'));
                            chart.draw(data, {
                                width: 'auto',
                                height: 200,
                                title: '',
                                colors: ['#<?php echo $check_non_active_tab_color->setting_value; ?>'],
                                hAxis: {title: day, titleTextStyle: {color: 'black'}}
                            });
                        }
                        ///////////////////////////////
                        function drawChart3() {
                            var data = new google.visualization.DataTable();
                            var day = "<?php echo addslashes(language_code('DSP_DAY'));?>";
                            var favorites = "<?php echo addslashes(language_code('DSP_USER_FAVOURITES')); ?>";
                            data.addColumn('string', day);
                            data.addColumn('number', favorites);
                            data.addRows([

                                <?php
                                $i = 1;
                                while ($i <= 7) {
                                    $todaydate                 = $dates[$i];
                                    $today                     = new DateTime($todaydate);
                                    $dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
                                    //$veiw_profile = mysql_num_rows(mysql_query("SELECT count(*) FROM $dsp_user_favourites_table WHERE favourite_user_id = '$user_id' AND fav_date_added='$todaydate' GROUP BY user_id order by fav_date_added "));
                                    //echo '["'.array_search($today->format('D'),$array).'",'.$veiw_profile.']';;
                                    $veiw_profile = $wpdb->get_var("SELECT count(*) FROM $dsp_user_favourites_table WHERE favourite_user_id = '$user_id' AND fav_date_added='$todaydate' GROUP BY user_id order by fav_date_added ");
                                    $day          = $array[$today->format('D')];
                                    echo '["' . $day . '",' . intval($veiw_profile) . ']';
                                    if ($i < 7) {
                                        echo ",";
                                    }
                                    $i++;
                                }
                                ?>]);

                            var chart = new google.visualization.AreaChart(document.getElementById('favorites_chart'));
                            chart.draw(data, {
                                width: 'auto',
                                height: 200,
                                title: '',
                                colors: ['#<?php echo $check_non_active_tab_color->setting_value; ?>'],
                                hAxis: {title: day, titleTextStyle: {color: 'black'}}
                            });
                        }

                        ////////////////////////
                        function drawChart4() {
                            var data = new google.visualization.DataTable();
                            var day = "<?php echo addslashes(language_code('DSP_DAY'));?>";
                            var friends = "<?php echo addslashes(language_code('DSP_USER_FRIENDS')); ?>";
                            data.addColumn('string', day);
                            data.addColumn('number', friends);
                            data.addRows([
                                <?php
                                $i = 1;
                                while ($i <= 7) {
                                    $todaydate = $dates[$i];
                                    $today     = new DateTime($todaydate);
                                    //$veiw_profile = mysql_num_rows(mysql_query("SELECT count(*) FROM $dsp_my_friends_table WHERE friend_uid = '$user_id' AND date_added='$todaydate' AND approved_status='Y' GROUP BY user_id order by date_added "));
                                    //echo '["'.array_search($today->format('D'),$array).'",'.$veiw_profile.']';;
                                    $dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
                                    $veiw_profile         = $wpdb->get_var("SELECT count(*) FROM $dsp_my_friends_table WHERE friend_uid = '$user_id' AND date_added='$todaydate' AND approved_status='Y' GROUP BY user_id order by date_added ");
                                    $day                  = $array[$today->format('D')];
                                    echo '["' . $day . '",' . intval($veiw_profile) . ']';
                                    if ($i < 7) {
                                        echo ",";
                                    }
                                    $i++;
                                }
                                ?>]);

                            var chart = new google.visualization.AreaChart(document.getElementById('friends_chart'));
                            chart.draw(data, {
                                width: 'auto',
                                height: 200,
                                title: '',
                                colors: ['#<?php echo $check_non_active_tab_color->setting_value; ?>'],
                                hAxis: {title: day, titleTextStyle: {color: 'black'}}
                            });
                        }

                    </script>

                </div>
            </div>

            <div class="bottom-link-profile">
                <div class="right">

                    <span id="profile"
                          class="activ"><?php echo language_code('DSP_USER_PROFILE_VIEWS'); ?></span><?php if ($check_flirt_module->setting_status == 'Y') { ?>
                        <span id="winks"><?php echo language_code('DSP_USER_WINKS'); ?></span><?php } ?>
                    <span id="favorites"><?php echo language_code('DSP_USER_FAVOURITES'); ?></span>
                    <?php if ($check_my_friend_module->setting_status == 'Y') { ?>
                        <span id="friends"><?php echo language_code('DSP_USER_FRIENDS'); ?></span>
                    <?php } ?>
                </div>
            </div>

            <script>
                jQuery(document).ready(function (e) {
                    jQuery(".bottom-link-profile span").click(function () {
                        var id = jQuery(this).attr('id');
                        jQuery(".right-chart .view_chart").hide();
                        jQuery(".bottom-link-profile span").removeClass('activ');
                        jQuery(this).addClass('activ');
                        jQuery("#" + id + "_chart").show();
                    });

                    var wid = '100%';
                    jQuery(".view_chart").width('wid-wid*20/100');
                });
            </script>
        </div>
    <?php } ?>

    <div class="tab-button">

        <?php /* ?><ul>

          <li><a class="active" href="home"><?php echo language_code('DSP_HOME_TAB_HOME');?></a></li>

          <li><a href="membership"><?php echo language_code('DSP_HOME_TAB_MEMBERSHIPS');?></a></li>
          </ul><?php */ ?>

        <div class="right-link">    <?php
            $stealth_mode = isset($exist_profile_details) ? $exist_profile_details->stealth_mode : '';

            if ($stealth_mode != '') {
                ?>

                <span onclick="stealth_mode('<?php echo $current_user->ID; ?>', '<?php echo $stealth_mode ?>');"
                      class="dsp_span_pointer"><?php echo language_code('DSP_STEALTH_MODE') ?>&nbsp;<?php
                    if ($stealth_mode == 'N') {
                        echo language_code("DSP_OFF");
                    } else {
                        echo language_code("DSP_ON");
                    }
                    ?></span>    <?php } ?></div>

    </div>
</div>
<?php
//---------------------------------VIEW NEW MEMBERS AND POPULAR  MEMBERS------------------------------------//
//-------------------------------------------------------------------------------------------------------------- //
if (is_user_logged_in()) {
    include_once(WP_DSP_ABSPATH . "headers/dsp_view_members_header.php");  // INCLUDE VIEW MEMBERS HEADER
}
//------------------------------------------------------------------------------------------------------------//
// --------------------------------------------------------------------------------------------------------- //
?>

