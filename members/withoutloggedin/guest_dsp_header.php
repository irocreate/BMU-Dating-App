<?php
$pgurl = get('pgurl');

if ($pgurl == "login") { 
    include_once( WP_DSP_ABSPATH . "dsp_login_new.php");
}else if ($pgurl == "stories") {
    include_once( WP_DSP_ABSPATH . "dsp_guest_stories.php");
}else if ($pgurl == "view_member") {
    include_once( WP_DSP_ABSPATH . "headers/guest_view_profile_header.php");
} else if ($pgurl == "privacy_msg") {
    include_once( WP_DSP_ABSPATH . "dsp_user_privacy_messages.php");
} else if ($pgurl == "lost_password") {
    include_once( WP_DSP_ABSPATH . "dsp_lost_password.php");
} else if ($pgurl == "reset_password") {
    include_once( WP_DSP_ABSPATH . "dsp_reset_password.php");
} else if ($pgurl == "g_search_result") { 
    include_once( WP_DSP_ABSPATH . "members/withoutloggedin/search/guest_search_result.php");
}else if($pgurl == "search"){
   include_once( WP_DSP_ABSPATH . "members/loggedin/search/zipcode/zipcode_search_result.php");
}else if($pgurl == "verify_user"){
   include_once( WP_DSP_ABSPATH . "members/withoutloggedin/verify_user/verify_user.php");
}else if ($pgurl == 11) {
    include_once( WP_DSP_ABSPATH . "dsp_fetch_geography.php");
} else { 
    include_once( WP_DSP_ABSPATH . "members/withoutloggedin/dating_home_page.php");
}
