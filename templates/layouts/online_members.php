<div class="online-members-clm home-box dspdp-col-sm-4 dspdp-spacer-lg dsp-sm-4"><div class="dsp-home-widget">
    <div class="dspdp-h4 dsp-h4 dspdp-text-uppercase dspdp-spacer-md dsp-spacer-md  dsp-text-uppercase">
        <span class="heading-text">&nbsp;</span><?php echo language_code('DSP_ONLINE_MEMBER_TEXT'); ?>
    </div>
    <div class="dspdp-clearfix dsp-row">
        <?php
        if(!function_exists('display_members_photo')){
            include_once( WP_DSP_ABSPATH . 'functions.php');
        }
        $imagepath = get_option('siteurl') . '/wp-content/';  // image Paths
        $random_online_status = dsp_check_online_setting();
        $random_online_number = $check_online_member_mode->setting_value;
        $new_members = ($random_online_status) ? dsp_randomOnlineMembers($random_online_number) : dsp_getOnlineMembers();
        foreach ($new_members as $member) {
            $new_member_id = $member->user_id;
            $username = get_userdata($new_member_id);
            $imagePath = $member->private == 'Y' ? WPDATE_URL . '/images/private-photo-pic.jpg' : display_members_photo($new_member_id, $imagepath);

        ?>
            <div class="dspdp-col-xs-4 dspdp-text-center dspdp-small dspdp-spacer  dsp-clearfix dsp-online-member">
                <a href="<?php
                if (is_user_logged_in()) {
                    if ($member->gender == 'C') {
                        echo ROOT_LINK . get_username($new_member_id) . "/my_profile/";
                    } else {
                        echo ROOT_LINK . get_username($new_member_id) . "/";
                    }
                } else {
                    if ($member->gender == 'C') {
                        echo ROOT_LINK . get_username($member->user_id) . "/my_profile/";
                    } else {
                        echo ROOT_LINK . get_username($member->user_id) . "/";
                    }
                }
                ?>" class="dsp-xs-4">
                <img src="<?php echo display_members_photo($new_member_id, $imagepath); ?>" class="dspdp-img-responsive  dspdp-block-center dsp-img-responsive  dsp-block-center dsp-circular" alt="<?php echo get_username($member->user_id);?>"/></a>
                <span class="user-details dspdp-block dsp-block dsp-xs-8">
                    <?php echo substr($username->display_name, 0, 7) . '..' ?><br />
                    <span class="color-txt age-text"><?php echo GetAge($member->age) ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?></span>
                </span>
            </div>
			<div class="dsp-xs-12"><div class="dsp-bordered-item"></div></div>
        <?php } ?>
    </div>
    <?php if (count($new_members) > 3) { ?>
    <input name="" type="button" class="button btn btn-sm btn-default dspdp-btn dspdp-btn-sm dspdp-btn-default dsp-btn dsp-btn-sm dsp-btn-default" value="SEE ALL"  onclick="location.href = '<?php echo ROOT_LINK . "online_members/show/all/"; ?>';return false;"/>
    <?php } ?>
</div></div>
