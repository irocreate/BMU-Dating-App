<?php

$view_profile_pageurl = $_REQUEST['view'];



if ($view_profile_pageurl == "my_profile") {
    include(WP_DSP_ABSPATH . "m/dspViewProfile.php");
} else if ($view_profile_pageurl == "partner_profile") {
    include(WP_DSP_ABSPATH . "m/dspViewPartnerProfile.php");
} else if ($view_profile_pageurl == "partner_info") {
    include(WP_DSP_ABSPATH . "m/dspPartProfileInfo.php");
} else if ($view_profile_pageurl == "partner_loc") {
    include(WP_DSP_ABSPATH . "m/dspPartProfileLoc.php");
} else {
    include(WP_DSP_ABSPATH . "m/dspViewProfile.php");
}
?>