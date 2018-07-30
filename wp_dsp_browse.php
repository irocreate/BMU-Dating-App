<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

global $wpdb;
$current_user = wp_get_current_user();
$options = get_option("widget_dspBrowse");
$posts_table = $wpdb->prefix . POSTS;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");
$member_pageid = $member_page_title_ID->setting_value;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");
$member_page_id = $post_page_title_ID->ID;  // Print Site root link
$root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";  // Print Site root link
?>
<script>
    dspwid = jQuery.noConflict();
    dspwid(document).ready(function() {

        dspwid(".heading-arrow").click(function() {
            if (dspwid(this).parent().next("ul.by-ul").is(":visible")) {
                dspwid(this).rotate({animateTo: 180})
            }
            else
                dspwid(this).rotate({animateTo: 0})
            dspwid(this).parent().next("ul.by-ul").slideToggle('slow');

        });

    });
</script>
<div class="dsp-browse-widget-box dspdp-spacer-hg dspdp-plugin">
    <div class="widget-gender-heading widget-heading-box"><?php echo language_code('DSP_BROWSE_BY_SEX') ?><span class="heading-arrow"></span></div>
    <?php
    $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;


    $dsp_gender_list = $wpdb->prefix . DSP_GENDER_LIST_TABLE;

    $check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings WHERE setting_name = 'couples'");
    $gender_str = $options['gender'];
    $gender_array = explode(',', $gender_str);
    echo '<ul class="by-ul dspdp-by-gender dspdp-clearfix">';
    $i = 1;
    foreach ($gender_array as $gender_row) {
        $profile_count = $wpdb->get_var("SELECT count(DISTINCT (user_id)) FROM `$dsp_user_profiles` WHERE `gender` = '" . $gender_row . "' AND `status_id` = 1 AND country_id > 0 AND age > 0 ");
        //dsp_debug($wpdb->last_query);die;
        $gender_table_row = $wpdb->get_row("select * from $dsp_gender_list where enum='$gender_row'");
        //if($profile_count>0){
        if ($gender_row == 'M')
            $icon = 'user_male.png';
        else if ($gender_row == 'F')
            $icon = 'user_female.png';
        else if ($gender_row == 'C')
            $icon = 'user_couple.png';
        else
            $icon = 'user-group-icon.png';
        if ($gender_table_row->editable == 'N') {
            if ($gender_row != 'C') {
                if (is_user_logged_in()) {
                    echo '<li><img src="' .  WPDATE_URL . '/images/' . $icon . '" alt= "' . $icon . '"> <a href="' . $root_link . "search/search_result/basic_search/basic_search/seeking/" . $gender_row . "/search_type/basic/submit/Submit/" . '">' . language_code($gender_table_row->gender) . " <span class='wid-browse-count'>(" . $profile_count . ")</span></a></li>";
                } else {
                    echo '<li><img src="' .  WPDATE_URL . '/images/' . $icon . '" alt= "' . $icon . '"> <a href="' . $root_link . "g_search_result/seeking/" . $gender_row . "/search_type/basic_search/submit/Submit/" . '">' . language_code($gender_table_row->gender) . " <span class='wid-browse-count'>(" . $profile_count . ")</span></a></li>";
                }
            } else {
                if ($check_couples_mode->setting_status == 'Y') {
                    if (is_user_logged_in()) {
                        echo '<li><img src="' .  WPDATE_URL . '/images/' . $icon . '" alt= "' . $icon . '"> <a href="' . $root_link . "search/search_result/basic_search/basic_search/seeking/" . $gender_row . "/search_type/basic/submit/Submit/" . '">' . language_code($gender_table_row->gender) . " <span class='wid-browse-count'>(" . $profile_count . ")</span></a></li>";
                    } else {
                        echo '<li><img src="' .  WPDATE_URL . '/images/' . $icon . '" alt= "' . $icon . '"> <a href="' . $root_link . "g_search_result/seeking/" . $gender_row . "/search_type/basic_search/submit/Submit/" . '">' . language_code($gender_table_row->gender) . " <span class='wid-browse-count'>(" . $profile_count . ")</span></a></li>";
                    }
                }
            }
        } else {
            if (is_user_logged_in()) {
                echo '<li><img src="' .  WPDATE_URL . '/images/' . $icon . '" alt= "' . $icon . '"> <a href="' . $root_link . "search/search_result/basic_search/basic_search/seeking/" . $gender_row . "/search_type/basic/submit/Submit/" . '">' . $gender_table_row->gender . " <span class='wid-browse-count'>(" . $profile_count . ")</span></a></li>";
            } else {
                echo '<li><img src="' .  WPDATE_URL . '/images/' . $icon . '" alt= "' . $icon . '"> <a href="' . $root_link . "g_search_result/seeking/" . $gender_row . "/search_type/basic_search/submit/Submit/" . '">' . $gender_table_row->gender . " <span class='wid-browse-count'>(" . $profile_count . ")</span></a></li>";
            }
        }
        if ($i % 2 == 0)
            echo '<div class="wid-line"></div>';

        $i++;
//	}
    }
    echo '</ul>';
    ?>
    <div class="widget-age-heading widget-heading-box"><?php echo language_code('DSP_BROWSE_BY_AGE') ?><span class="heading-arrow"></span></div>
        <?php
        echo '<ul class="by-ul dspdp-by-age dspdp-clearfix">';
        $age_str = $options['age'];
        $age_array = explode(',', $age_str);
        $i = 1;
        foreach ($age_array as $age_row) {
            $age_divide = explode('-', $age_row);
            $age_from = $age_divide[0];
            $age_to = $age_divide[1];
            $profile_count = $wpdb->get_var("SELECT count(DISTINCT (user_id))  FROM `$dsp_user_profiles` fb WHERE ((year(CURDATE())-year(age)) >= '" . $age_from . "') AND ((year(CURDATE())-year(age)) <= '" . $age_to . "') AND `status_id` = 1 AND country_id > 0 ");
            
            //if($profile_count>0){
            $icon = 'cake_icon.png';
            if (is_user_logged_in()) {
                echo '<li><img src="' .  WPDATE_URL . '/images/' . $icon . '" alt= "' . $icon . '"> <a href="' . $root_link . "search/search_result/basic_search/basic_search/age_from/" . $age_from . "/age_to/" . $age_to . "/search_type/basic/submit/Submit/" . '">' . $age_row . ' <span class="wid-browse-count">(' . $profile_count . ')</span></a></li>';
            } else {
                echo '<li><img src="' .  WPDATE_URL . '/images/' . $icon . '" alt= "' . $icon . '"> <a href="' . $root_link . "g_search_result/age_from/" . $age_from . "/age_to/" . $age_to . "/search_type/basic_search/submit/Submit/" . '">' . $age_row . ' <span class="wid-browse-count">(' . $profile_count . ')</span></a></li>';
            }
            if ($i % 2 == 0)
                echo '<div class="wid-line"></div>';

            $i++;
            //}
        }
        echo '</ul>';
        ?>
</div>