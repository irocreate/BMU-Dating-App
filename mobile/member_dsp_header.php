<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ----------------------------------------- Display top menu header Menus ------------------------------ // 


$DSP_USER_PROFILES_TABLE = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$exist_profile_details = $wpdb->get_row("SELECT * FROM $DSP_USER_PROFILES_TABLE WHERE user_id = '$current_user->ID'");
$gender = $exist_profile_details->gender;
$imagepath = $pluginpath . "mobile/images/";
?>
<div class="dsp_mb_line">
    <div class="dsp_mb_menu" >
        <?php
        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
            ?>
            <a href="<?php echo add_query_arg(array('pid' => 1, 'pagetitle' => 'mypage'), $root_link); ?>" title="<?php echo language_code('DSP_MENU_MY_PAGE') ?>"><img src="<?php echo $imagepath . 'home.png' ?>"/></a></div>
    <?php } else { ?>
        <a href="<?php echo add_query_arg(array('pgurl' => 'register'), $root_link); ?>" title="Login"><img src="<?php echo $imagepath . 'peop.png' ?>"/></a></div>
<?php } ?>
<div class="dsp_mb_menu">
    <?php
    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
        ?>
        <a href="<?php
        echo add_query_arg(array('pid' => 14, 'pagetitle' => 'my_email',
            'message_template' => 'inbox'), $root_link);
        ?>" title="<?php echo language_code('DSP_MIDDLE_TAB_MESSAGE') ?>"><img src="<?php echo $imagepath . 'invelop.png' ?>"/></a></div>
    <?php } else { ?>
    <a href="<?php echo add_query_arg(array('pgurl' => 'register'), $root_link); ?>" title="Login"><img src="<?php echo $imagepath . 'peop.png' ?>"/></a></div>
<?php } ?>
<div class="dsp_mb_menu">
    <?php
    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
        ?>
        <a href="<?php echo add_query_arg(array('pid' => 2), $root_link); ?>" title="<?php echo DSP_MEMBERS ?>"><img src="<?php echo $imagepath . 'peop.png' ?>"/></a></div>
<?php } else { ?>
    <a href="<?php echo add_query_arg(array('pgurl' => 'register'), $root_link); ?>" title="Login"><img src="<?php echo $imagepath . 'peop.png' ?>"/></a></div>
<?php } ?>
<div class="dsp_mb_menu">
    <?php
    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
        ?>
        <a href="<?php echo add_query_arg(array('pid' => 5, 'pagetitle' => 'basic_search'), $root_link); ?>" title="<?php echo language_code('DSP_GUEST_HEADER_SEARCH') ?>"><img src="<?php echo $imagepath . 'srch.png' ?>"/></a></div>
<?php } else { ?>
    <a href="<?php echo add_query_arg(array('pgurl' => 'register'), $root_link); ?>" title="Login"><img src="<?php echo $imagepath . 'peop.png' ?>"/></a></div>
<?php } ?>
<?php
// if member is login then this menu will be display 
if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
    ?>
    <div class="dsp_mb_menu">
        <?php
        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
            ?>
            <a href="<?php echo wp_logout_url($root_link) ?>" title="<?php echo DSP_LOGOUT ?>"><img src="<?php echo $imagepath . 'back.png' ?>"/></a></div>
    <?php } else { ?>
        <a href="<?php echo add_query_arg(array('pgurl' => 'register'), $root_link); ?>" title="Login"><img src="<?php echo $imagepath . 'back.png' ?>"/></a></div>
        <?php
    }
}
?>
<div class="clr"></div>
</div>
<?php
if ($pageurl == "1") {// home page
    include("wp-content/plugins/dsp_dating/mobile/dsp_mb_home.php");
} else if ($pageurl == 2) { // member page
    include("wp-content/plugins/dsp_dating/mobile/dsp_member_header.php");
} else if ($pageurl == 3) {
    if (isset($_REQUEST['pagetitle']) && ($_REQUEST['pagetitle'] == 'view_profile')) {
        include("wp-content/plugins/dsp_dating/mobile/view_profile_setup.php");
    }
} else if ($pageurl == 5) {
    include("wp-content/plugins/dsp_dating/mobile/dsp_search_header.php");
} else if ($pageurl == 7) {
    include("wp-content/plugins/dsp_dating/mobile/add_to_favourites.php");
} else if ($pageurl == 8) {
    include("wp-content/plugins/dsp_dating/add_as_friend.php");
} else if ($pageurl == 14) {
    include("wp-content/plugins/dsp_dating/mobile/dsp_email_header.php");
} else {
    include("wp-content/plugins/dsp_dating/mobile/dsp_mb_home.php");
}
