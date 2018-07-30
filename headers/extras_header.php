<?php
global $wp_query;
$extra_pageurl = get('pagetitle');
$uId = get_current_user_id();
$trendingStatusOn = ($check_trending_option->setting_status == 'Y') ? true : false;
?>
    <div class="line">
    <div <?php if ($extra_pageurl == "viewed_me") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "extras/viewed_me/"; ?>"><?php echo language_code('DSP_MENU_VIEWED_ME'); ?></a></div>
    <div <?php if ($extra_pageurl == "i_viewed") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "extras/i_viewed/"; ?>"><?php echo language_code('DSP_MENU_I_VIEWED'); ?></a></div>
<?php if( $trendingStatusOn ): ?>
    <div <?php if ($extra_pageurl == "trending") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "extras/trending/"; ?>"><?php echo language_code('DSP_MENU_TRENDING'); ?></a></div>
<?php endif; ?>
    <div <?php if ($extra_pageurl == "interest_cloud") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "extras/interest_cloud/"; ?>"><?php echo language_code('DSP_INTEREST_CLOUD'); ?></a></div>
<?php if ($check_date_tracker_mode->setting_status == 'Y') { ?>
    <div <?php if ($extra_pageurl == "date_tracker") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "extras/date_tracker/"; ?>"><?php echo language_code('DSP_DATE_TRACKER'); ?></a></div>
<?php } ?>
<?php if ($check_blog_module->setting_status == 'Y') { ?>
<div <?php if ($extra_pageurl == "blogs") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
    <?php
    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
        ?>
        <a href="<?php echo $root_link . "extras/blogs/add_blogs/"; ?>" title="<?php echo language_code('DSP_MENU_MY_BLOGS') ?>"><?php echo language_code('DSP_MENU_MY_BLOGS') ?></a></div>
    <?php } else { ?>
        <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><?php echo language_code('DSP_MENU_MY_BLOGS') ?></a></div>
    <?php } ?>
<?php } ?>
<?php if ($check_meet_me_mode->setting_status == 'Y') { ?>
    <div <?php if ($extra_pageurl == "meet_me") { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "extras/meet_me/"; ?>"><?php echo language_code('DSP_MEET_ME'); ?></a></div>
<?php } ?>
    <div class="clr"></div>
    </div>
    </div>
<?php
//one to one chat pop up notification 
apply_filters('dsp_get_single_chat_popup_notification',$notification);

if($extra_pageurl == 'blogs') {

    include_once(WP_DSP_ABSPATH . "headers/myblogs_header.php");

} else {
    if( !$trendingStatusOn &&  $extra_pageurl == 'trending') {
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( 404 );
        exit();
    }
    // menus that are not premium
    $accessMenus = array('date_tracker','edit_date_tracker','meet_me','blogs');
    // get the menu name from url
    $access_feature_name = ucwords(str_replace('_',' ', $extra_pageurl));
    // file path to include
    $messagePath = WP_DSP_ABSPATH  .  "dsp_print_message.php";
    $filePath = WP_DSP_ABSPATH  . 'members/loggedin/extras/' . $extra_pageurl .'.php';
    // filter to check for the menu to  wheather or not access

    $menuPageAccessNStatus = apply_filters('dsp_allow_menu_page',$access_feature_name , $extra_pageurl, $accessMenus);

    $status = $menuPageAccessNStatus['status'];

    $fileToInclude = $status ? $filePath : $messagePath;
    if(isset($menuPageAccessNStatus['check_membership_msg'])){
        $check_membership_msg  = $menuPageAccessNStatus['check_membership_msg'];
    }

    if(file_exists($fileToInclude))
        include_once($fileToInclude);
    else
        include_once(WP_DSP_ABSPATH  . "members/loggedin/extras/viewed_me.php"); // default file


}

