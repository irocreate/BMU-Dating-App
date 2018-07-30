<?php

$edit_profile_pageurl = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
//log_message('debug','mobile header title=='.$edit_profile_pageurl);

if ($edit_profile_pageurl == "my_profile") {
    include(WP_DSP_ABSPATH . "m1/edit_profile_setup.php");
} else if ($edit_profile_pageurl == "partner_profile") {
    include(WP_DSP_ABSPATH . "m1/edit_partner_profile.php");
} else if ($edit_profile_pageurl == "partner_general") {
    include(WP_DSP_ABSPATH . "m1/edit_partner_general.php");
} else if ($edit_profile_pageurl == "partner_question") {
    include(WP_DSP_ABSPATH . "m1/edit_partner_question.php");
} else if ($edit_profile_pageurl == "partner_picture") {
    include(WP_DSP_ABSPATH . "m1/edit_partner_picture.php");
} else {

    include(WP_DSP_ABSPATH . "m1/edit_profile_setup.php");
}
?>