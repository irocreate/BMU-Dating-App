<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$current_user = wp_get_current_user();
$user_id = $current_user->ID;  // print session USER_ID
// table name 
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
// table name 
global $wp_query;
$page_id = $wp_query->post->ID; //fetch post query string id
$posts_table = $wpdb->prefix . "posts";
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");
$member_pageid = $member_page_title_ID->setting_value;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");
$member_page_id = $post_page_title_ID->ID;  // Print Site root link
$root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");
$check_default_country = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'default_country'");
// if member is login then this menu will be display 
if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
    ?>
    <form name="frmquicksearch" id="frmquicksearch" method="POST" action="<?php echo get_bloginfo('url'); ?>/<?php echo $post_page_title_ID->post_name ?>/search/search_result/basic_search/basic_search/" class="dspdp-form-horizontal dspdp-spacer-hg dspdp-plugin">
    <?php } else { ?>
        <form name="frmquicksearch" id="frmquicksearch" method="POST" action="<?php echo get_bloginfo('url'); ?>/<?php echo $post_page_title_ID->post_name ?>/g_search_result/" class="dspdp-form-horizontal dspdp-spacer-hg dspdp-plugin">
        <?php } ?>
        <input type="hidden" name="Pictues_only" value="P" />
        <div class="sinder-bar-form dsp-widget-search-content">
            <?php 
                   $genderList = get_gender_list('M');
                   if(!empty($genderList)):
                ?>
                    <p class="dspdp-form-group dspdp-block">
                        <span class="dspdp-col-sm-12">
                                <?php echo language_code('DSP_I_AM'); ?><br />
                            <select name="gender" class="dspdp-form-control dspdp-input-sm">
                                <?php echo $genderList; ?>
                            </select>
                        </span>
                    </p>
            <?php endif; ?>

            <?php 
               $genderList = get_gender_list('F');
               if(!empty($genderList)):
            ?>
                   
                    <p class="dspdp-form-group dspdp-block">
                        <span class="dspdp-col-sm-12"><?php echo language_code('DSP_SEEKING_A'); ?><br />
                            <select name="seeking" class="dspdp-form-control dspdp-input-sm">
                                <?php echo $genderList; ?>
                            </select>
                        </span>
                    </p>
                    
            <?php endif; ?>
                <p class="dspdp-form-group dspdp-block">
                    <span class="dspdp-col-sm-5 clearfix">
                        <?php echo language_code('DSP_AGE') ?>
                        <select name="age_from"  class="dspdp-form-control dspdp-input-sm  margin-btm-1"> 
                            <?php
                            for ($fromyear = 18; $fromyear <= 99; $fromyear++) {
                                if ($fromyear == 18) {
                                    ?>
                                    <option value="<?php echo $fromyear ?>" selected="selected"><?php echo $fromyear ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $fromyear ?>"><?php echo $fromyear ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </span>
                
                    <span class="dspdp-col-sm-5">
                        <?php echo language_code('DSP_TO'); ?>
                        <select name="age_to"   class="dspdp-form-control dspdp-input-sm">
                            <?php
                            for ($toyear = 18; $toyear <= 99; $toyear++) {
                                if ($toyear == 99) {
                                    ?>
                                    <option value="<?php echo $toyear ?>" selected="selected"><?php echo $toyear ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $toyear ?>"><?php echo $toyear ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </span>
                </p>
            <p class="dspdp-form-group dspdp-block">
                <span class="dspdp-col-sm-12">
                    <?php echo language_code('DSP_COUNTRY'); ?><br />
                    <select name="cmbCountry" class="dspdp-form-control dspdp-input-sm">
                        <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY'); ?></option>
                        <?php
                        $countries = $wpdb->get_results("SELECT * FROM $dsp_country_table Order by name");
                        foreach ($countries as $country) {
                            $selected = ($country->country_id == $check_default_country->setting_value) ? "selected = selected" : "";
                        ?>
                            <option value="<?php echo $country->name; ?>" <?php echo $selected; ?> ><?php echo $country->name; ?></option>
                        <?php } ?>
                    </select>
                </span>
            </p>
            <p class="dspdp-form-group dspdp-block">
                <span class="dspdp-col-sm-12"><?php echo language_code('DSP_USERNAME'); ?>: 
                <input name="username" type="text" class="dspdp-form-control dspdp-input-sm dsp-input" /></span></p>
            <p><input name="submit" type="submit" class="dsp_submit_button dspdp-btn dspdp-btn-default btn-search" value="<?php echo language_code('DSP_SEARCH_BUTTON'); ?>" /></p>
        </div>
    </form>