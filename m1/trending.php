<?php
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
$dsp_messages_table = $wpdb->prefix . DSP_EMAILS_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_favourites_list_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_user_online = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
?>
<form id="dsp_div_trending">
 <fieldset>
    <div class="heading-text"><strong><?php echo language_code('DSP_PROFILE_TRENDING'); ?></strong></div>
    <label data-role="fieldcontain" class="select-group">  
        <div class="clearfix">                                    
        <div class="mam_reg_lf select-label"><?php echo language_code('DSP_SELECT_CAEGORY'); ?></div>
            <select id="profile_filter" name="profile_filter" onchange="ExtraLoad('div_trending', 'true')">
                <option><?php echo language_code('DSP_SELECT_OPTION') ?></option>
                <option value="all" <?php if (isset($_REQUEST['profile_filter']) && $_REQUEST['profile_filter'] == "all") { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_OPTION_ALL'); ?></option>
                <option value="Wink" <?php if (isset($_REQUEST['profile_filter']) && $_REQUEST['profile_filter'] == "Wink") { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_OPTION_WINK'); ?></option>    
                <option value="emails" <?php if (isset($_REQUEST['profile_filter']) && $_REQUEST['profile_filter'] == "emails") { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_OPTION_EMAILS'); ?></option>
                <option value="friend" <?php if (isset($_REQUEST['profile_filter']) && $_REQUEST['profile_filter'] == "friend") { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_OPTION_FRIEND'); ?></option>
                <option value="favorited" <?php if (isset($_REQUEST['profile_filter']) && $_REQUEST['profile_filter'] == "favorited") { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_OPTION_FAVORITED'); ?></option>
            </select>
        </div>
    </label>
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
    <input type="hidden" name="pagetitle" value="trending" />
    <?php
    if (isset($_REQUEST['gender_filter']) && $_REQUEST['gender_filter'] == '') {

        $user_profiles_table = $wpdb->get_var("SELECT gender FROM $dsp_user_profiles_table where user_id='$user_id' ");
        $gender = $user_profiles_table;
        ?>
        <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo language_code('DSP_REGISTER_GENDER'); ?></div>
                <select id="gender_filter" name="gender_filter" onchange="ExtraLoad('div_trending', 'true')">
                    <option value="M" <?php if ($gender == 'F') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_MALE'); ?></option>
                    <option value="F" <?php if ($gender == 'M') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_FEMALE'); ?></option>
                    <?php if ($check_couples_mode->setting_status == 'Y') {
                        ?>
                        <option value="C" <?php if ($gender == 'C') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_COUPLE'); ?></option> 
                        <?php } ?>    
                    </select>
                </div>
            </label>
            <?php
        } else {
            $gender = isset($_REQUEST['gender_filter']) ? $_REQUEST['gender_filter'] : '';
            ?>
            <label data-role="fieldcontain" class="select-group">  
                <div class="clearfix">                                    
                    <div class="mam_reg_lf select-label"><?php echo language_code('DSP_REGISTER_GENDER'); ?></div>
                    <select id="gender_filter" name="gender_filter" onchange="ExtraLoad('div_trending', 'true')">
                        <option value="M" <?php if ($gender == 'M') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_MALE'); ?></option>
                        <option value="F" <?php if ($gender == 'F') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_FEMALE'); ?></option>
                        <?php if ($check_couples_mode->setting_status == 'Y') {
                            ?> 
                            <option value="C" <?php if ($gender == 'C') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_COUPLE'); ?></option> <?php }
                            ?>       
                        </select> 
                    </div>
                </label>
                <?php }
                ?>

            </fieldset>
        </form>


        <?php
        $gender = isset($_REQUEST['gender_filter']) ? $_REQUEST['gender_filter'] : '';
        $strQuery = "";

        if (isset($_REQUEST['profile_filter']) && $_REQUEST['profile_filter'] == 'all') {
            $strQuery = "SELECT p.user_id, p.country_id, p.state_id, p.city_id, p.gender, p.seeking, p.zipcode, p.age,   p.pic_status,p.about_me,p.status_id,p.reason_for_status,p.edited,p.last_update_date as count from $dsp_user_profiles_table p where p.gender='$gender'";
        } elseif (isset($_REQUEST['profile_filter']) && $_REQUEST['profile_filter'] == "Wink") {
            $strQuery = "SELECT winks.receiver_id ,count(winks.receiver_id) as count, p.user_id, p.country_id, p.state_id, p.city_id, p.gender, p.seeking, p.zipcode, p.age,   p.pic_status,p.about_me,p.status_id,p.reason_for_status,p.edited,p.last_update_date FROM $dsp_member_winks_table winks, $dsp_user_profiles_table p where winks.receiver_id=p.user_id and p.gender='$gender' GROUP BY winks.receiver_id  ";
        } elseif (isset($_REQUEST['profile_filter']) && $_REQUEST['profile_filter'] == "emails") {
            $strQuery = "SELECT msg.receiver_id,count(msg.receiver_id) as count, p.user_id, p.country_id, p.state_id, p.city_id, p.gender, p.seeking, p.zipcode, p.age,   p.pic_status,p.about_me,p.status_id,p.reason_for_status,p.edited,p.last_update_date FROM $dsp_messages_table msg, $dsp_user_profiles_table p where msg.receiver_id=p.user_id and p.gender='$gender' GROUP BY msg.receiver_id ";
        } elseif (isset($_REQUEST['profile_filter']) && $_REQUEST['profile_filter'] == "friend") {
            $strQuery = "SELECT friend.friend_uid,count(friend.friend_uid) as count,  p.user_id, p.country_id, p.state_id, p.city_id, p.gender, p.seeking, p.zipcode, p.age,   p.pic_status,p.about_me,p.status_id,p.reason_for_status,p.edited,p.last_update_date FROM $dsp_my_friends_table friend, $dsp_user_profiles_table p where friend.friend_uid=p.user_id and friend.approved_status= 'Y' and p.gender='$gender' GROUP BY friend.friend_uid ";
        } elseif (isset($_REQUEST['profile_filter']) && $_REQUEST['profile_filter'] == "favorited") {
            $strQuery = "SELECT favourites.favourite_user_id ,count(favourites.favourite_user_id) as count, p.user_id, p.country_id, p.state_id, p.city_id, p.gender, p.seeking, p.zipcode, p.age,   p.pic_status,p.about_me,p.status_id,p.reason_for_status,p.edited,p.last_update_date FROM $dsp_favourites_list_table  favourites, $dsp_user_profiles_table p where favourites.favourite_user_id=p.user_id and p.gender='$gender' GROUP BY favourites.favourite_user_id";
        }

        if ($strQuery != "") {

            @$strQuery = $strQuery . " ORDER BY count desc LIMIT 15";

    //echo $strQuery; 



            $search_members = $wpdb->get_results($strQuery);
            ?>
            <div class="swipe_div" id="mainTrending">
                <ul id="swipe_ulTrending"  style="padding-left:0px; text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">
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
                            <div>
                                <?php
                                if ($check_couples_mode->setting_status == 'Y') {
                                    if ($s_gender == 'C') {
                                        if ($s_make_private == 'Y') {
                                            if ($user_id != $s_user_id) {
                                                if (!in_array($user_id, $favt_mem)) {
                                                    ?>
                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">
                                                        <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  class=" img iviewed-img"   />
                                                    </a>                
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">				
                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"   class=" img iviewed-img"  /></a>                
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">
                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" class=" img iviewed-img"  />
                                                    </a>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">
                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" class=" img iviewed-img"   />
                                                </a>
                                                <?php
                                            }
                                        } else {
                                            if ($s_make_private == 'Y') {
                                                if ($user_id != $s_user_id) {
                                                    if (!in_array($user_id, $favt_mem)) {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">
                                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  class=" img iviewed-img"   />
                                                        </a>                
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">				
                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"     class=" img iviewed-img" /></a>                
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>

                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">
                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" class=" img iviewed-img"   />
                                                        </a>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">
                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" class=" img iviewed-img"  />
                                                    </a>
                                                    <?php
                                                }
                                            }
                                        } else {
                                            if ($s_make_private == 'Y') {
                                                if ($user_id != $s_user_id) {
                                                    if (!in_array($user_id, $favt_mem)) {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">
                                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" class=" img iviewed-img"   />
                                                        </a>                
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">				
                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"   class=" img iviewed-img"  /></a>                
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">
                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" class=" img iviewed-img"   />
                                                        </a>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">
                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" class=" img iviewed-img"   />
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
                                                    <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">
                                                        <?php echo $displayed_member_name->display_name ?>                
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">
                                                            <?php echo $displayed_member_name->display_name ?>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?> 
                                                        <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">
                                                            <?php echo $displayed_member_name->display_name ?>
                                                            <?php } ?>
                                                        </a>
                                                    </div>

                                                </li>
                                                <?php
                            }// foreach($search_members as $member) 
                            ?>

                        </ul>

                    </div>

                    <?php
                }?>