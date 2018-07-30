<?php

$view_profile_pageurl = $_REQUEST['view'];



if ($view_profile_pageurl == "my_profile") {
    include(WP_DSP_ABSPATH . "m1/dspViewProfile.php");
} else if ($view_profile_pageurl == "partner_profile") {
    include(WP_DSP_ABSPATH . "m1/dspViewPartnerProfile.php");
} else if ($view_profile_pageurl == "partner_info") {
    include(WP_DSP_ABSPATH . "m1/dspPartProfileInfo.php");
} else if ($view_profile_pageurl == "partner_loc") {
    include(WP_DSP_ABSPATH . "m1/dspPartProfileLoc.php");
} else {
    include(WP_DSP_ABSPATH . "m1/dspViewProfile.php");
}
?>