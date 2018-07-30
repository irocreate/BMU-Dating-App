<?php
/*

  Copyright (C) www.wpdating.com - All Rights Reserved!

  Author - MyAllenMedia, LLC

  WordPress Dating Plugin

  contact@wpdating.com

 */

global $wpdb;

$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;

$dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;

$dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;

$dsp_user_emails_table = $wpdb->prefix . DSP_EMAILS_TABLE;

$dsp_interest_tags_table = $wpdb->prefix . DSP_INTEREST_TAGS_TABLE;

$dsp_user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dateTimeFormat = dsp_get_date_timezone();
extract($dateTimeFormat);
$count_total_members = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles ORDER BY user_profile_id");
$count_total_males = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_user_profiles WHERE gender='M'");
$count_total_females = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_user_profiles WHERE  gender='F'");

$count_total_couples = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_user_profiles WHERE  gender='C'");

$count_total_sent_winks = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_member_winks_table ORDER BY wink_mesage_id");

$count_approved_photos = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_galleries_photos WHERE status_id=1 ORDER BY gal_photo_id");

$count_waiting_approval_photos = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_galleries_photos WHERE status_id=0 ORDER BY gal_photo_id");

$count_total_galleries = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_albums_table ORDER BY album_id");

$count_total_sent_emails = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table ORDER BY message_id");

$count_wait_approve_profile = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles where status_id='0' ORDER BY user_profile_id");

$totalpen = 0;

$totalon = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_online_table");

$count_interest_tags = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_interest_tags_table");
?>

<div id="general" class="postbox">

    <h3 class="hndle"><span><?php echo language_code('DSP_ACCOUNTING_HAPPENING_THIS_WEEK_SETTING') ?></span></h3>

    <div style="margin:20px">

        <table class="dsp_thumbnails1" border="0" width="100%">

            <tr>

                <td valign="top" style="width:122px;">

                    <table border="0" style="width:130px; padding-top:15px;">

                        <tr>

                            <td style=" width:75px; text-align:right;"><span onclick="profile_registered_chart();" style="text-decoration:none; cursor:pointer;font-weight:bold;">Profile Registered</span></td>

                        </tr>

                        <tr>

                            <td style=" width:50px; text-align:right;"><span onclick="profile_created_chart();" style="text-decoration:none; cursor:pointer;font-weight:bold;">Profile Created</span></td>

                        </tr>

                        <tr>

                            <td style=" width:50px; text-align:right;"><span onclick="payment_chart();" style="text-decoration:none; cursor:pointer;font-weight:bold;">Payments</span></td>

                        </tr>

                        <tr>

                            <td style=" width:50px; text-align:right;"><span onclick="winks_chart();" style="text-decoration:none; cursor:pointer;font-weight:bold;">Winks Sent</span></td>

                        </tr>

                        <tr>

                            <td style=" width:50px; text-align:right;"><span onclick="emails_sent_chart();" style="text-decoration:none; cursor:pointer;font-weight:bold;">Emails Sent</span></td>

                        </tr>

                        <tr>

                            <td style=" width:50px; text-align:right;"><span onclick="friends_made_chart();" style="text-decoration:none; cursor:pointer;font-weight:bold;">Friends Made</span></td>

                        </tr>

                        <tr>

                            <td style=" width:50px; text-align:right;"><span onclick="date_tracker_chart();" style="text-decoration:none; cursor:pointer;font-weight:bold;">Dates Tracked</span></td>

                        </tr>

                    </table> 

                </td>

                <td>

                    <?php
                    wp_enqueue_style('dsp_chart', WPDATE_URL . "/css/chart.css");
                    wp_enqueue_script('dsp_chart', WPDATE_URL . "/js/RGraph.common.core.js", array(), '', true);
                    wp_enqueue_script('dsp_chart2', WPDATE_URL . "/js/RGraph.line.js", array(), '', true);
                    ?>

        <!--[if IE]><script type="text/javascript" src="http://explorercanvas.googlecode.com/svn/trunk/excanvas.js"></script><![endif]-->



                    <div id="c" style="display:block;"><canvas id="cvs" width="420" height="150">[No canvas support]</canvas></div>

                    <div  id="c1" style="display:none;"><canvas id="cvs1" width="420" height="150">[No canvas support]</canvas></div>

                    <div  id="c2" style="display:none;"><canvas id="cvs2" width="420" height="150">[No canvas support]</canvas></div>

                    <div  id="c3" style="display:none;"><canvas id="cvs3" width="420" height="150">[No canvas support]</canvas></div>

                    <div  id="c4" style="display:none;"><canvas id="cvs4" width="420" height="150">[No canvas support]</canvas></div>

                    <div  id="c5" style="display:none;"><canvas id="cvs5" width="420" height="150">[No canvas support]</canvas></div>

                    <div  id="c6" style="display:none;"><canvas id="cvs6" width="420" height="150">[No canvas support]</canvas></div>

                    <script>

                        window.onload = function()

                        {

<?php
$array = array(language_code('DSP_DAY_MON') => 'Mon', language_code('DSP_DAY_TUE') => 'Tue',
    language_code('DSP_DAY_WED') => 'Wed', language_code('DSP_DAY_THU') => 'Thu',
    language_code('DSP_DAY_FRI') => 'Fri', language_code('DSP_DAY_SAT') => 'Sat',
    language_code('DSP_DAY_SUN') => 'Sun');
?>

                            var el = document.getElementById('c');

                            el.style.display = "block";

                            var el1 = document.getElementById('c1');

                            var el2 = document.getElementById('c2');

                            var el3 = document.getElementById('c3');

                            var el4 = document.getElementById('c4');

                            var el5 = document.getElementById('c5');

                            var el6 = document.getElementById('c6');

                            el1.style.display = "none";

                            el2.style.display = "none";

                            el3.style.display = "none";

                            el4.style.display = "none";

                            el5.style.display = "none";

                            el6.style.display = "none";



                            var line = new RGraph.Line('cvs', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {



    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);

    $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;



    $profile_registered = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_users_table where DATE(user_registered)='$todaydate'");

    echo $a = $profile_registered;

    if ($i < 7) {

        echo ",";
    }

    $i++;
}
?>]);

                            line.Set('chart.labels', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {

    //echo $dates[$i];
    // Create a new instance

    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);





    // Display full day name

    $today->format('l') . PHP_EOL; // lowercase L
    // Display three-letter day name

    echo "'" . array_search($today->format('D'), $array) . "'";

    echo ",";

    $i++;
}
?>]);

                            line.Draw();







                        }



                        function profile_registered_chart()

                        {

                            var el = document.getElementById('c');

                            el.style.display = "block";

                            var el1 = document.getElementById('c1');

                            var el2 = document.getElementById('c2');

                            var el3 = document.getElementById('c3');

                            var el4 = document.getElementById('c4');

                            var el5 = document.getElementById('c5');

                            var el6 = document.getElementById('c6');

                            el1.style.display = "none";

                            el2.style.display = "none";

                            el3.style.display = "none";

                            el4.style.display = "none";

                            el5.style.display = "none";

                            el6.style.display = "none";







                            var line = new RGraph.Line('cvs', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {



    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);

    $dsp_counter_hits_table = $wpdb->prefix . DSP_COUNTER_HITS_TABLE;

    $dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;





    $profile_registered = $profile_registered = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_users_table where DATE(user_registered)='$todaydate'");

    echo $a = $profile_registered;

    if ($i < 7) {

        echo ",";
    }

    $i++;
}
?>]);

                            line.Set('chart.labels', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {

    //echo $dates[$i];
    // Create a new instance

    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);





    // Display full day name

    $today->format('l') . PHP_EOL; // lowercase L
    // Display three-letter day name

    echo "'" . array_search($today->format('D'), $array) . "'";

    echo ",";

    $i++;
}
?>]);

                            line.Draw();





                        }



                        function profile_created_chart()

                        {

                            var el1 = document.getElementById('c1');

                            el1.style.display = "block";

                            var el = document.getElementById('c');

                            var el2 = document.getElementById('c2');

                            var el3 = document.getElementById('c3');

                            var el4 = document.getElementById('c4');

                            var el5 = document.getElementById('c5');

                            var el6 = document.getElementById('c6');

                            el.style.display = "none";

                            el2.style.display = "none";

                            el3.style.display = "none";

                            el4.style.display = "none";

                            el5.style.display = "none";

                            el6.style.display = "none";



                            var line = new RGraph.Line('cvs1', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {



    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);



    $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

    $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;

    $profile_created = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_users_table users, $dsp_user_profiles profiles WHERE users.ID=profiles.user_id AND DATE(last_update_date)='$todaydate'");

    echo $a = $profile_created;

    if ($i < 7) {

        echo ",";
    }

    $i++;
}
?>]);

                            line.Set('chart.labels', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {

    //echo $dates[$i];
    // Create a new instance

    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);





    // Display full day name

    $today->format('l') . PHP_EOL; // lowercase L
    // Display three-letter day name

    echo "'" . array_search($today->format('D'), $array) . "'";

    echo ",";

    $i++;
}
?>]);

                            line.Draw();



                        }



                        function payment_chart()

                        {

                            var el2 = document.getElementById('c2');

                            el2.style.display = "block";

                            var el = document.getElementById('c');

                            var el1 = document.getElementById('c1');

                            var el3 = document.getElementById('c3');

                            var el4 = document.getElementById('c4');

                            var el5 = document.getElementById('c5');

                            var el6 = document.getElementById('c6');

                            el.style.display = "none";

                            el1.style.display = "none";

                            el3.style.display = "none";

                            el4.style.display = "none";

                            el5.style.display = "none";

                            el6.style.display = "none";



                            var line = new RGraph.Line('cvs2', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {



    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);





    $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;



    $payment = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table WHERE  start_date ='$todaydate'");



    echo $a = $payment;

    if ($i < 7) {

        echo ",";
    }

    $i++;
}
?>]);

                            line.Set('chart.labels', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {

    //echo $dates[$i];
    // Create a new instance

    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);





    // Display full day name

    $today->format('l') . PHP_EOL; // lowercase L
    // Display three-letter day name

    echo "'" . array_search($today->format('D'), $array) . "'";

    echo ",";

    $i++;
}
?>]);

                            line.Draw();



                        }



                        function winks_chart()

                        {

                            var el3 = document.getElementById('c3');

                            el3.style.display = "block";

                            var el = document.getElementById('c');

                            var el1 = document.getElementById('c1');

                            var el2 = document.getElementById('c2');

                            var el4 = document.getElementById('c4');

                            var el5 = document.getElementById('c5');

                            var el6 = document.getElementById('c6');

                            el.style.display = "none";

                            el1.style.display = "none";

                            el2.style.display = "none";

                            el4.style.display = "none";

                            el5.style.display = "none";

                            el6.style.display = "none";



                            var line = new RGraph.Line('cvs3', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {



    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);



    $dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;



    $winks_sent = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_member_winks_table WHERE send_date='$todaydate'");

    echo $a = $winks_sent;

    if ($i < 7) {

        echo ",";
    }

    $i++;
}
?>]);

                            line.Set('chart.labels', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {

    //echo $dates[$i];
    // Create a new instance

    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);





    // Display full day name

    $today->format('l') . PHP_EOL; // lowercase L
    // Display three-letter day name

    echo "'" . array_search($today->format('D'), $array) . "'";

    echo ",";

    $i++;
}
?>]);

                            line.Draw();



                        }



                        function emails_sent_chart()

                        {

                            var el4 = document.getElementById('c4');

                            el4.style.display = "block";

                            var el = document.getElementById('c');

                            var el1 = document.getElementById('c1');

                            var el2 = document.getElementById('c2');

                            var el3 = document.getElementById('c3');

                            var el5 = document.getElementById('c5');

                            var el6 = document.getElementById('c6');

                            el.style.display = "none";

                            el1.style.display = "none";

                            el2.style.display = "none";

                            el3.style.display = "none";

                            el5.style.display = "none";

                            el6.style.display = "none";



                            var line = new RGraph.Line('cvs4', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {



    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);



    $dsp_user_emails_table = $wpdb->prefix . DSP_EMAILS_TABLE;



    $emails_sent = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE DATE(sent_date)='$todaydate'");

    echo $a = $emails_sent;

    if ($i < 7) {

        echo ",";
    }

    $i++;
}
?>]);

                            line.Set('chart.labels', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {

    //echo $dates[$i];
    // Create a new instance

    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);





    // Display full day name

    $today->format('l') . PHP_EOL; // lowercase L
    // Display three-letter day name

    echo "'" . array_search($today->format('D'), $array) . "'";

    echo ",";

    $i++;
}
?>]);

                            line.Draw();



                        }



                        function friends_made_chart()

                        {

                            var el5 = document.getElementById('c5');

                            el5.style.display = "block";

                            var el = document.getElementById('c');

                            var el1 = document.getElementById('c1');

                            var el2 = document.getElementById('c2');

                            var el3 = document.getElementById('c3');

                            var el4 = document.getElementById('c4');

                            var el6 = document.getElementById('c6');

                            el.style.display = "none";

                            el1.style.display = "none";

                            el2.style.display = "none";

                            el3.style.display = "none";

                            el4.style.display = "none";

                            el6.style.display = "none";



                            var line = new RGraph.Line('cvs5', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {



    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);



    $dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;



    $friends_made = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE date_added='$todaydate' AND approved_status ='Y'");

    echo $a = $friends_made;

    if ($i < 7) {

        echo ",";
    }

    $i++;
}
?>]);

                            line.Set('chart.labels', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {

    //echo $dates[$i];
    // Create a new instance

    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);





    // Display full day name

    $today->format('l') . PHP_EOL; // lowercase L
    // Display three-letter day name

    echo "'" . array_search($today->format('D'), $array) . "'";

    echo ",";

    $i++;
}
?>]);

                            line.Draw();



                        }



                        function date_tracker_chart()

                        {

                            var el6 = document.getElementById('c6');

                            el6.style.display = "block";

                            var el = document.getElementById('c');

                            var el1 = document.getElementById('c1');

                            var el2 = document.getElementById('c2');

                            var el3 = document.getElementById('c3');

                            var el4 = document.getElementById('c4');

                            var el5 = document.getElementById('c5');

                            el.style.display = "none";

                            el1.style.display = "none";

                            el2.style.display = "none";

                            el3.style.display = "none";

                            el4.style.display = "none";

                            el5.style.display = "none";







                            var line = new RGraph.Line('cvs6', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {



    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);



    $dsp_date_tracker_table = $wpdb->prefix . DSP_DATE_TRACKER_TABLE;



    $tracked_date = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_date_tracker_table WHERE DATE(tracked_date)='$todaydate'");

    echo $a = $tracked_date;

    if ($i < 7) {

        echo ",";
    }

    $i++;
}
?>]);

                            line.Set('chart.labels', [<?php
$startdate1 = date("Y-m-d");

$parts = explode('-', $startdate1);





$startdate2 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 1), $parts[0]));

$startdate3 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 2), $parts[0]));

$startdate4 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 3), $parts[0]));

$startdate5 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 4), $parts[0]));

$startdate6 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 5), $parts[0]));

$startdate7 = date("Y-m-d", mktime(0, 0, 0, $parts[1], ($parts[2] - 6), $parts[0]));







$dates = array(1 => $startdate7, $startdate6, $startdate5, $startdate4, $startdate3,
    $startdate2, $startdate1);

$i = 1;

while ($i <= 7) {

    //echo $dates[$i];
    // Create a new instance

    $todaydate = $dates[$i];

    $today = new DateTime($todaydate);





    // Display full day name

    $today->format('l') . PHP_EOL; // lowercase L
    // Display three-letter day name

    echo "'" . array_search($today->format('D'), $array) . "'";

    echo ",";

    $i++;
}
?>]);

                            line.Draw();



                        }

                    </script>







                </td>

            </tr>

        </table>

    </div>

</div>

<br />

<table width="490" border="0" cellpadding="0" cellspacing="0">

    <!--DWLayoutTable-->

    <tr>

        <td width="490" height="61" valign="top">&nbsp;</td>

    </tr>

</table>