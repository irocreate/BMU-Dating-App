<script type="text/javascript">
    function member_details(id, str)
    {
//alert(id);
        if (id) {
            var loc = window.location.href;
            if (loc.search("guest_pageurl") > -1)
            {
                index = loc.indexOf("guest_pageurl")
                loc = loc.substring(0, index - 1);
            }
            if (str == 'profile') {
                loc += "?guest_pageurl=view_mem_profile&mem_id=" + id;
            }
            else if (str == 'my_profile') {
                loc += "?guest_pageurl=view_mem_profile&view=my_profile&mem_id=" + id;
            }
            else if (str == 'album') {
                loc += "?guest_pageurl=view_mem_album&mem_id=" + id;
            }
            else if (str == 'photos') {
                loc += "?guest_pageurl=view_mem_photos&mem_id=" + id;
            }
            else if (str == 'audio') {
                loc += "?guest_pageurl=view_mem_audio&mem_id=" + id;
            }
            else if (str == 'video') {
                loc += "?guest_pageurl=view_mem_video&mem_id=" + id;
            }
            else if (str == 'friends') {
                loc += "?guest_pageurl=view_mem_friends&mem_id=" + id;
            }
            else if (str == 'blogs') {
                loc += "?guest_pageurl=view_mem_blog&mem_id=" + id;
            }
            window.location.href = loc;
        }
    }
    function member_pictures(id, str, album_id)
    {
        if (id) {
            var loc = window.location.href;
            if (loc.search("guest_pageurl") > -1)
            {
                index = loc.indexOf("guest_pageurl")
                loc = loc.substring(0, index - 1);
            }
            if (str == 'pictures') {
                loc += "&guest_pageurl=view_Pictures&album_id=" + album_id + "&mem_id=" + id;
            }
            window.location.href = loc;
        }
    }
</script>
<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Software Plugin
  Los Angeles, California
  (213) 222-6504
  contact@wpdating.com
 */
$guest_pageurl = get('guest_pageurl') != "" ? get('guest_pageurl') : ''; 
$posts_table = $wpdb->prefix . POSTS;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");
$member_pageid = $member_page_title_ID->setting_value;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");
$member_page_id = $post_page_title_ID->ID;  // Print Site root link
$root_url = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";
$get_profile_user_id = get('mem_id') != "" ? get('mem_id') : '';

if ($guest_pageurl != 'view_mem_profile') {
    include_once( WP_DSP_ABSPATH . 'headers/guest_view_profile_header_tab.php');
}
?>
<?php
//$dsp_show_profile_table = $wpdb->prefix .DSP_LIMIT_PROFILE_VIEW_TABLE;
$dsp_guest_profile_table = $wpdb->prefix . DSP_GUEST_LIMIT_PROFILE_VIEW_TABLE;

if ($guest_pageurl == "view_mem_profile") {
    if ((get('view') != "") && ((get('view') == "my_profile") || (get('view') == "partner_profile"))) {
        if ($check_guest_limit_profile_mode->setting_status == 'Y') {

            $ip_adress = $_SERVER['REMOTE_ADDR'];
            if ($check_register_page_redirect_mode->setting_status == 'Y') {
                $registerpage = $check_register_page_redirect_mode->setting_value;
            } else {
                $registerpage = $root_url . "/register/";
            }

            $count = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_guest_profile_table WHERE ip_adress='$ip_adress' AND member_id='$get_profile_user_id' ");

            $no_of_profile = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_guest_profile_table WHERE ip_adress='$ip_adress' ");

            $general_settings_table = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'guest_limit_profile'");
            $value = $general_settings_table->setting_value;
            if ($value <= $no_of_profile) {
                $exist_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_guest_profile_table WHERE ip_adress='$ip_adress' AND member_id='$get_profile_user_id' and status!='0' ");
                if ($exist_member > 0) {
                    include_once( WP_DSP_ABSPATH . "headers/view_couples_profile_header.php" );
                } else {
                    ?>		
                    <script>location.href = "<?php echo $registerpage; ?>"</script>
                    <?php
                }
            } else if (($count >= 0)) {

                $wpdb->query("INSERT INTO $dsp_guest_profile_table SET ip_adress='$ip_adress', member_id='$get_profile_user_id', status='0' ");
                include_once( WP_DSP_ABSPATH . "headers/view_couples_profile_header.php" );
            } else if ($count == 1) {
                include_once( WP_DSP_ABSPATH .  "headers/view_couples_profile_header.php");
            }
        } else {
            include_once( WP_DSP_ABSPATH . "headers/view_couples_profile_header.php");
        }
    } else {

        if ($check_guest_limit_profile_mode->setting_status == 'Y') {

            $ip_adress = $_SERVER['REMOTE_ADDR'];
            if ($check_register_page_redirect_mode->setting_status == 'Y') {
                $registerpage = $check_register_page_redirect_mode->setting_value;
            } else {
                $registerpage = $root_url . "/register/";
            }

            $count = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_guest_profile_table WHERE ip_adress='$ip_adress' AND member_id='$get_profile_user_id' ");

            $no_of_profile = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_guest_profile_table WHERE ip_adress='$ip_adress' ");

            $general_settings_table = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'guest_limit_profile'");
            $value = $general_settings_table->setting_value;
            
            if ($value <= $no_of_profile) {
                $exist_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_guest_profile_table WHERE ip_adress='$ip_adress' AND member_id='$get_profile_user_id'  and status!='0' ");
                
                if ($exist_member > 0) {
                    include_once( WP_DSP_ABSPATH .  "view_profile_setup.php");
                } else {
                    ?>		
                    <script>location.href = "<?php echo $registerpage; ?>"</script>
                    <?php
                }
            } else if (($count >= 0)) {

                $wpdb->query("INSERT INTO $dsp_guest_profile_table SET ip_adress='$ip_adress', member_id='$get_profile_user_id', status='0' ");
                include_once(  WP_DSP_ABSPATH . "view_profile_setup.php");
            } else if ($count == 1) {
                include_once( WP_DSP_ABSPATH . "view_profile_setup.php");
            }
        } else {
            include_once( WP_DSP_ABSPATH . "view_profile_setup.php");
        }
    }
} else if ($guest_pageurl == "view_mem_album") {
    include_once( WP_DSP_ABSPATH . "dsp_view_user_albums.php");
} else if ($guest_pageurl == "view_mem_photos") {
    include_once( WP_DSP_ABSPATH . "dsp_view_user_photos.php");
}else if ($guest_pageurl == "view_mem_audio") {
    include_once( WP_DSP_ABSPATH . "dsp_view_member_audios.php");
} else if ($guest_pageurl == "view_mem_video") {
    include_once( WP_DSP_ABSPATH . "dsp_view_member_videos.php");
} else if ($guest_pageurl == "view_mem_friends") {
    include_once( WP_DSP_ABSPATH . "dsp_view_member_friends.php");
} else if ($guest_pageurl == "view_mem_blog") {
    include_once( WP_DSP_ABSPATH . "dsp_view_member_blogs.php");
} else if ($guest_pageurl == "view_mem_Pictures") {
    include_once( WP_DSP_ABSPATH . "dsp_view_user_pictures.php");
}

