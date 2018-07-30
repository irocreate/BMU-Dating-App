<?php
$imagepath = $pluginpath . "mobile/images/";
$DSP_USER_PROFILES_TABLE = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$exist_profile_details = $wpdb->get_row("SELECT * FROM $DSP_USER_PROFILES_TABLE WHERE user_id = '$current_user->ID'");
$gender = $exist_profile_details->gender;
?>
<div class="dsp_mb_header"><?php echo DSP_MEMBERS ?></div><br>
<div class="dsp_mem_div" style="width: 100%;margin-left: -10px;">

    <div <?php if (($pgurl == "online") || ($pgurl == "")) { ?> class="dsp_mb_mem_menu_active"<?php } else { ?> class="dsp_mb_mem_menu" <?php } ?>  ><a href="<?php
        echo add_query_arg(array(
            'pgurl' => 'online', 'pid' => '2'), $root_link);
        ?>" title="<?php echo DSP_GUEST_HEADER_ONLINE ?>"><img src="<?php echo $imagepath . 'online.png' ?>"/>

            <?php echo DSP_GUEST_HEADER_ONLINE ?></a></div>
    <div <?php if (($pgurl == "new")) { ?> class="dsp_mb_mem_menu_active"<?php } else { ?> class="dsp_mb_mem_menu" <?php } ?> ><a href="<?php
        echo add_query_arg(array(
            'pgurl' => 'new',
            'pid' => '2'), $root_link);
        ?>" title="<?php echo DSP_NEW ?>"><img src="<?php echo $imagepath . 'new.png' ?>"/><?php echo DSP_NEW ?></a></div>
    <div <?php if (($pgurl == "my-matches")) { ?> class="dsp_mb_mem_menu_active"<?php } else { ?> class="dsp_mb_mem_menu" <?php } ?>><a href="<?php
        echo add_query_arg(array(
            'pgurl' => 'my-matches',
            'pid' => '2'), $root_link);
        ?>" title="<?php echo DSP_MY_MATCHES ?>"><img src="<?php echo $imagepath . 'mymatches.png' ?>"/><?php echo DSP_MY_MATCHES ?></a></div>
    <div <?php if (($pgurl == "most-popular")) { ?> class="dsp_mb_mem_menu_active"<?php } else { ?> class="dsp_mb_mem_menu" <?php } ?>><a href="<?php
        echo add_query_arg(array(
            'pgurl' => 'most-popular',
            'pid' => '2'), $root_link);
        ?>" title="<?php echo DSP_MY_MATCHES ?>"><img src="<?php echo $imagepath . 'popular.png' ?>"/><?php echo DSP_MOST_POPULAR ?></a></div>
    <div class="clr"></div>
</div>
<?php
if ($pgurl == "online") {
    include("wp-content/plugins/dsp_dating/mobile/dsp_online_member.php");
} else if ($pgurl == "new") {
    include("wp-content/plugins/dsp_dating/mobile/dsp_new_member.php");
} else if ($pgurl == "my-matches") {
    include("wp-content/plugins/dsp_dating/mobile/dsp_my_match_member.php");
} else if ($pgurl == "most-popular") {
    include("wp-content/plugins/dsp_dating/mobile/dsp_popular_member.php");
} else {
    include("wp-content/plugins/dsp_dating/mobile/dsp_online_member.php");
}
?>