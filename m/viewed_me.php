<?php
$dsp_counter_hits_table = $wpdb->prefix . DSP_COUNTER_HITS_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;


$dsp_counter_hits_query = "SELECT Distinct hits.user_id FROM $dsp_counter_hits_table hits, $dsp_user_profiles_table profile WHERE hits.member_id=profile.user_id AND hits.member_id=$user_id ";

if ($check_couples_mode->setting_status == 'Y') {
    $strQuery = "SELECT * FROM $dsp_user_profiles_table p, $dsp_counter_hits_table h where p.user_id=h.user_id and p.status_id=1 and h.member_id=$user_id GROUP BY p.user_id";
} else {
    $strQuery = "SELECT * FROM $dsp_user_profiles_table p, $dsp_counter_hits_table h where p.user_id=h.user_id and p.status_id=1 and h.member_id=$user_id and p.gender!='C' GROUP BY p.user_id";
}


$strQuery = $strQuery . " ORDER BY p.user_profile_id desc";

$user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($dsp_counter_hits_query) AS total");




$search_members = $wpdb->get_results($strQuery);

if (count($search_members) == 0) {
    ?>
    <div style="text-align: center;">
        <?php echo language_code('DSP_NO_RECORD_FOUND_EXTRAS'); ?>
    </div>
    <?php
} else {
    ?>
    <div class="swipe_div" id="mainViewedMe" style="height: 106px">
        <ul id="swipe_ulViewedMe"  style="padding-left:0px;text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">
            <?php
            foreach ($search_members as $member1) {
                if ($check_couples_mode->setting_status == 'Y') {
                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'");
                } else {
                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member1->user_id'");
                }
                $s_user_id = $member->user_id;

                $s_gender = $member->gender;
                $s_seeking = $member->seeking;

                $s_make_private = $member->make_private;

                $displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$s_user_id'");

                $favt_mem = array();

                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");

                foreach ($private_mem as $private) {
                    $favt_mem[] = $private->favourite_user_id;
                }
                ?>
                <li class="ivew-list">

                    <div >
                        <?php
                        if ($check_couples_mode->setting_status == 'Y') {
                            if ($s_gender == 'C') {
                                ?>

                                <?php if ($s_make_private == 'Y') {
                                    ?>
                                    <?php if ($user_id != $s_user_id) {
                                        ?>

                                        <?php if (!in_array($user_id, $favt_mem)) {
                                            ?>

                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"    class="img iviewed-img" />

                                            </a>                
                                            <?php
                                        } else {
                                            ?>

                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				

                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    class="img iviewed-img"/></a>                

                                            <?php
                                        }
                                    } else {
                                        ?>

                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"   class="img iviewed-img" />

                                        </a>
                                    <?php } ?>
                                    <?php
                                } else {
                                    ?>
                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"   class="img iviewed-img" />

                                    </a>

                                    <?php
                                }
                            } else {
                                if ($s_make_private == 'Y') {
                                    if ($user_id != $s_user_id) {
                                        if (!in_array($user_id, $favt_mem)) {
                                            ?>

                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >

                                                <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"   class="img iviewed-img" />

                                            </a>                
                                            <?php
                                        } else {
                                            ?>
                                            <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				
                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  class="img iviewed-img"/>
                                            </a>                
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  class="img iviewed-img" />
                                        </a>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" class="img iviewed-img" />
                                    </a>
                                    <?php
                                }
                            }
                        } else {
                            if ($s_make_private == 'Y') {
                                if ($user_id != $s_user_id) {
                                    if (!in_array($user_id, $favt_mem)) {
                                        ?>
                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"   class="img iviewed-img" />
                                        </a>                
                                        <?php
                                    } else {
                                        ?>
                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >				
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"   class="img iviewed-img"/>
                                        </a>                
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"   class="img iviewed-img" />
                                    </a>

                                    <?php
                                }
                            } else {
                                ?>
                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"  class="img iviewed-img" />
                                </a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div style="width: 100%;text-align: center;font-size: 11px;word-wrap:break-word; ">
                        <?php
                        if ($check_couples_mode->setting_status == 'Y') {
                            if ($s_gender == 'C') {
                                ?>
                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                    <?php echo $displayed_member_name->display_name ?>                
                                    <?php
                                } else {
                                    ?>
                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                        <?php echo $displayed_member_name->display_name ?>
                                        <?php
                                    }
                                } else {
                                    ?> 
                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')" >
                                        <?php echo $displayed_member_name->display_name ?>
                                    <?php } ?>

                                </a>

                                </div>



                                </li>

                            <?php }// foreach($search_members as $member) ?>

                            </ul>
                            </div>

                        <?php } ?>
