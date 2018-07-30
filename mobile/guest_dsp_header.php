<?php $imagepath = $pluginpath . "mobile/images/"; ?>
<div class="dsp_mb_line">
    <div class="dsp_mb_menu" ><a href="<?php echo add_query_arg(array('pgurl' => 'register'), $root_link); ?>" title="<?php echo DSP_LOGGING ?>"><img src="<?php echo $imagepath . 'home.png' ?>"/></a></div>
    <div class="dsp_mb_menu" style="padding-left: 30px;" ><a href="<?php
        echo add_query_arg(array(
            'pgurl' => 'guest_search'), $root_link);
        ?>" title="<?php echo language_code('DSP_GUEST_HEADER_SEARCH'); ?>"><img src="<?php echo $imagepath . 'srch.png' ?>"/></a></div>
    <div class="clr"></div>
</div>
<?php
if ($pgurl == "register") {
    include("wp-content/plugins/dsp_dating/mobile/dsp_register.php");
} else if ($pgurl == "guest_search") { // guest search 
    ?>
    <div class="dsp_mb_header"><?php echo DSP_MEMBER_SEARCH ?></div><br>
    <?php
    include("wp-content/plugins/dsp_dating/mobile/dsp_user_search.php");
} else if (isset($_REQUEST['pagetitle']) && ($_REQUEST['pagetitle'] == "view_profile")) { // user profile 
    include("wp-content/plugins/dsp_dating/mobile/view_profile_setup.php");
} else if (isset($_REQUEST['pagetitle']) && ($_REQUEST['pagetitle']) == "search_result") { // guest search result
    ?>
    <div class="dsp_mb_header"><?php echo DSP_MEMBER_SEARCH ?></div><br>
    <?php
    include("wp-content/plugins/dsp_dating/mobile/search_result.php");
} else {
    include("wp-content/plugins/dsp_dating/mobile/dsp_register.php");
}
?>