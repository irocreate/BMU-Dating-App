<?php

$view_profile_pageurl = $_REQUEST['view'];



if ($view_profile_pageurl == "view_info") {
    include(WP_DSP_ABSPATH . "m/dspViewProfile.php");
} else if ($view_profile_pageurl == "view_loc") {
    include(WP_DSP_ABSPATH . "m/dspViewPartnerProfile.php");
} else if ($view_profile_pageurl == "view_friends") {
    include(WP_DSP_ABSPATH . "m/dspPartProfileInfo.php");
} else if ($view_profile_pageurl == "view_blog") {
    include(WP_DSP_ABSPATH . "m/dspPartProfileLoc.php");
} else {
    include(WP_DSP_ABSPATH . "m/dspViewProfile.php");
}
?>