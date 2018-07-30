<div class="members-slider jcarousel-wrapper">
    <div class="dspdp-h4 dspdp-text-uppercase dspdp-spacer-md dsp-h4 dsp-text-uppercase dsp-spacer-md dsp-text-center"><span class="heading-text">&nbsp;</span><?php echo language_code('DSP_FEATURED_MEMBERS'); ?></div>
    <div class="jcarousel">
        <ul>
            <?php
            if (!function_exists('display_members_photo')) {
                include_once( WP_DSP_ABSPATH . 'functions.php');
            }
            //$root_link = get_bloginfo('url') . "/members/";
            $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
            $dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

            $imagepath = get_option('siteurl') . '/wp-content/';  // image Path
            $members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles_table profile INNER JOIN $dsp_users_table user ON profile.user_id=user.ID WHERE profile.featured_member > 0 ORDER BY profile.featured_member DESC");

            foreach ($members as $member) {
                $member_id = $member->user_id;
                $username = get_userdata($member_id);
                $imagePath = $member->make_private == 'Y' ? WPDATE_URL . '/images/private-photo-pic.jpg' : display_members_photo($member_id, $imagepath);
                ;
                ?>
                <li class="class1 dspdp-text-center  dspdp-small dsp-text-center dsp-small">
                    <a href="<?php
                    if (is_user_logged_in()) {
                        if ($member->gender == 'C') {
                            echo ROOT_LINK . get_username($member_id) . "/my_profile/";
                        } else {
                            echo ROOT_LINK . get_username($member_id) . "/";
                        }
                    } else {
                        if ($member->gender == 'C') {
                            echo ROOT_LINK . get_username($member->user_id) . "/my_profile/";
                        } else {
                            echo ROOT_LINK . get_username($member->user_id) . "/";
                        }
                    }
                    ?>"><div class="member-image dsp-spacer-sm"><img src="<?php echo display_members_photo($member_id, $imagepath); ?>" alt="<?php echo get_username($member->user_id); ?>"/></div></a>
                    </a>
                    <span class="user-details">
    <?php echo substr($username->display_name, 0, 7) . '..' ?><br />
                        <span class="age-text" <?php /* ?>style="color:<?php echo $temp_color;?>"<?php */ ?>><?php echo GetAge($member->age) ?> <?php echo language_code('DSP_YEARS_OLD_TEXT'); ?></span>
                    </span>
                </li>
<?php } ?>
        </ul>
    </div>
</div>

