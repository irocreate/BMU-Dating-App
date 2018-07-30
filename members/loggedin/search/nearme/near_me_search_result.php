<?php //---------------------------------START  GENERAL SEARCH---------------------------------------            ?>
<script type="text/javascript" src="https://googlemaps.github.io/js-rich-marker/src/richmarker-compiled.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/js-marker-clusterer/1.0.0/markerclusterer_compiled.js"></script>

<?php
$age_from = 18;
$age_to  = 90;
$gender  = 'all';
if (isset($_REQUEST['near_me_submit'])) {
    $gender = isset($_REQUEST['gender']) ? esc_sql($_REQUEST['gender']) : get('gender');
    $age_from = isset($_REQUEST['age_from']) ? esc_sql($_REQUEST['age_from']) : get('age_from');
    $age_to = isset($_REQUEST['age_to']) ? esc_sql($_REQUEST['age_to']) : get('age_to');
}
?>
<div class="box-border">
    <div class="box-pedding">
        <?php
        //$strQuery = "SELECT um.user_id,um.meta_value FROM $dsp_usermeta_table um inner join $dsp_user_profiles p on(p.user_id=um.user_id) where meta_key='signup_ip' and p.user_id != " . $user_id . " and country_id=" . $country_id;
        $filters = array(
                        'gender' => $gender,
                        'age_from' => $age_from,
                        'age_to' => $age_to
                    );
        $usermeta_table = dsp_get_near_users($filters);
        if (isset($usermeta_table) && !empty($usermeta_table)) {
        ?>
            <div class="heading-submenu"><strong><?php echo language_code('DSP_NEAR_ME_TITLE'); ?></strong></div></br></br>
            <div class="content-search">
                <div class="row">
                    <form action="" method="GET" class="dspdp-form-horizontal dsp-form-horizontal">
                        <ul class="zip-search">
                            <li class="dspdp-form-group dsp-sm-4">
                                <div class="row">
                                    <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                                        <?php echo language_code('DSP_GENDER') ?>
                                    </span>
                                    <span class="dspdp-col-sm-5 dsp-sm-9">
                                        <select name="gender" class="dspdp-form-control dsp-form-control">
                                            <option value="all" <?php if ($gender == 'all') { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> >All</option>
                                            <?php
                                                $gender = isset($gender) && !empty($gender) ? $gender : $userProfileDetails->gender;
                                                echo get_gender_list($gender);
                                            ?>
                                        </select>
                                    </span>
                                </div>
                            </li>
                            <li class="dspdp-form-group dsp-sm-6">
                                <div class="row">
                                    <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-2">
                                        <?php echo language_code('DSP_AGE') ?>
                                    </span>
                                    <span class="dspdp-col-sm-2 dsp-sm-4">
                                        <select name="age_from" class="dspdp-form-control dsp-form-control">
                                            <?php for ($i = '18'; $i <= '90'; $i++) { ?>
                                                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                    <span class="dspdp-control-label dsp-control-label dspdp-col-sm-1 dsp-sm-2">
                                        <?php echo language_code('DSP_TO') ?>
                                    </span>
                                    <span class="dspdp-col-sm-2 dsp-sm-4">
                                        <select  name="age_to" class="dspdp-form-control dsp-form-control">
                                            <?php for ($j = '90'; $j >= '18'; $j--) { ?>
                                                <option value="<?php echo $j ?>"><?php echo $j ?></option>
                                            <?php } ?>

                                        </select>
                                    </span>
                                </div>
                            </li>
                            <li></li>
                        </ul>
                        <span class="dspdp-form-group dsp-sm-2">
                            <span class="dspdp-col-sm-offset-3 dspdp-col-sm-5 pull-right">
                                <input type="submit" name="near_me_submit" class="dsp_submit_button dspdp-btn dspdp-btn-default" value="<?php echo language_code('DSP_FILTER_BUTTON'); ?>" />
                            </span>
                        </span>
                    </form>
                </div>
            </div>
            <div style="clear:both;"></div>
            <?php if(!empty($usermeta_table)){ ?>
            <div id="map_wrapper" style="height: 500px; width:500; margin-left:-1px;">
                <div id="map_canvas" class="mapping" style="width: 100%;height: 100%;"></div>
            </div>
            <?php
                $lt_lg = array();
                foreach ($usermeta_table as $usermeta) {
                    $userid    = $usermeta->user_id;
                    $city_name = $usermeta->city;
                    $country_name = $usermeta->country;
                    $private = $usermeta->make_private;
                    $query = "https://maps.google.com/maps/api/geocode/json?address=" ;
                    if(!empty($country_name) && !empty($city_name)){
                        $query .= urlencode($country_name . ', ' . $city_name);
                    }else if(!empty($country_name) && empty($city_name)){
                        $query .= urlencode($country_name);
                    }else if(empty($country_name) && !empty($city_name)){
                        $query .= urlencode($city_name);
                    }else{
                        $query = '';
                    }

                    $latlangValues = wp_remote_get($query);
                    if ($latlangValues) {
                        $latlangValues = array_key_exists('body', $latlangValues) ? $latlangValues['body'] : '';
                        $latlangValues = json_decode($latlangValues);
                        $lat = isset($latlangValues->results[0]->geometry->location->lat) ? $latlangValues->results[0]->geometry->location->lat : '';
                        $lang = isset($latlangValues->results[0]->geometry->location->lng) ? $latlangValues->results[0]->geometry->location->lng : '';
                        $lt_lg[] = array("lat" => $lat, "long" => $lang, "userid" => $userid, 'privatepic' => $private);
                    }
                    //print_r($lt_lg);
                }
                $map_str = '';
                $count = 0;
                foreach ($lt_lg as $ltr) {
                    $count+=1;

                    $favt_mem = array();

                    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_id'");

                    foreach ($private_mem as $private) {
                        $favt_mem[] = $private->favourite_user_id;
                    }

                    if ($private == 'Y') {
                        if (!in_array($ltr['userid'], $favt_mem)) {
                            $image_path = WPDATE_URL . '/images/private-photo-pic.jpg';
                        } else {
                            $image_path = display_members_original_photo($ltr['userid'], ABSPATH . 'wp-content/');
                        }
                    } else {
                        $image_path = display_members_original_photo($ltr['userid'], ABSPATH . 'wp-content/');
                    }

                    $map_str.="['" . get_username($ltr['userid']) . "'," . $ltr['lat'] . "," . ($ltr['long'] + ($count/10000)) . ",0,'" . WPDATE_URL .  "/thumb.php" . "?src=" . base64_encode($image_path) . "&w=32&h=32" . "','" . $root_link . get_username($ltr['userid']) . "/" . "'],";
                }
                $map_str = rtrim($map_str, ','); /////////Longitude and Langitude
            }

            } else {

                echo "<div>" . language_code('DSP_NO_RECORD_FOUND') . "</div>";
            }


        
        
        ?>

    </div>
</div>

<script type="text/javascript">
        
    function initialize() {
        var locations = [<?php echo $map_str;?>];
        var map = new google.maps.Map(document.getElementById('map_canvas'), {
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();
        var bounds = new google.maps.LatLngBounds();
        var marker, i;

        for (i = 0; i < locations.length; i++) {
            var image = {
                url: locations[i][4],
                size: new google.maps.Size(32, 32)
            };

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                icon: image,
                map: map,
                animation: google.maps.Animation.DROP
            });
            bounds.extend(marker.position);
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent('<a href="' + locations[i][5] + '">' + locations[i][0] + '</a>');
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }

        map.fitBounds(bounds);
        var listener = google.maps.event.addListener(map, "idle", function() {
            map.setZoom(3);
            google.maps.event.removeListener(listener);
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>