<?php
include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path
$path = $pluginpath . 'image.php';
$check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");

if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
    ?>
    <form name="frmquicksearch" class="dspdp-col-sm-12" id="frmquicksearch" method="GET" action="<?php echo ROOT_LINK . 'search/search_result/basic_search/basic_search' ?>">
        <input type="hidden" name="pid" value="5" />
        <input type="hidden" name="pagetitle" value="search_result" />
    <?php } else { ?>
        <form name="frmquicksearch" class="dspdp-col-sm-12" id="frmquicksearch" method="GET" action="<?php echo ROOT_LINK . 'g_search_result/' ?>">
        <?php } ?>
        <input type="hidden" name="Pictues_only" value="P" />
        <div class="dspdp-form">
            <div class="dspdp-row">
                <div class="dspdp-col-sm-2 dspdp-temp-nrw">
                    <div class="dspdp-form-group">
                        <span class="dspdp-control-label "><?php echo language_code('DSP_I_AM'); ?></span>
                        <div class="">
                            <select name="gender" class="dspdp-form-control">
                                <?php
                                $gender = $userProfileDetailsExist ? $userProfileDetails->gender : '';
                                echo get_gender_list($gender);
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="dspdp-col-sm-2 dspdp-temp-nrw">
                    <div class="dspdp-form-group"><span class="dspdp-control-label"><?php echo language_code('DSP_SEEKING_A'); ?></span>
                        <div class="">
                            <select name="seeking"  class="dspdp-form-control">
                                <?php
                                $seeking = $userProfileDetailsExist ? $userProfileDetails->seeking : 'F';
                                echo get_gender_list($seeking);
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="dspdp-col-sm-2 dspdp-temp-nrw  dspdp-2col">
                    <div class="dspdp-form-group">

                        <div class="">
                            <div class="dspdp-row">
                                <div class="dspdp-col-sm-6">
                                    <span class="dspdp-control-label"><?php echo language_code('DSP_AGE'); ?></span>
                                    <select name="age_from"   class="dspdp-form-control">
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
                                </div>

                                <div class="dspdp-col-sm-6">
                                    <span class="dspdp-control-label"><?php echo language_code('DSP_TO'); ?></span>
                                    <select name="age_to"  class="dspdp-form-control">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($searchFormSettings != 'nn') : ?>
                    <div class="dspdp-col-sm-2 dspdp-temp-nrw">
                        <div class="dspdp-form-group"><span class="dspdp-control-label "><?php echo language_code('DSP_COUNTRY'); ?></span>
                            <div class="">
                                <select name="cmbCountry" class="dspdp-form-control">
                                    <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY'); ?></option>
                                    <?php
                                    $countries = $wpdb->get_results("SELECT * FROM $dsp_country_table Order by name");
                                    foreach ($countries as $country) {
                                        $selected = ($country->country_id == $check_default_country->setting_value) ? "selected = selected" : "";
                                    ?>
                                        <option value="<?php echo $country->name; ?>" <?php echo $selected; ?> ><?php echo $country->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="dspdp-col-sm-2 dspdp-temp-nrw" id="searchform_username">
                        <div class="dspdp-form-group">
                            <span class="dspdp-control-label"><?php echo language_code('DSP_USER_NAME'); ?></span>
                            <div  class="">
                                <input name="username" type="text" class="dspdp-form-control" />
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="dspdp-row">
                    <div class="dspdp-btn-group">
                            <input name="submit" type="submit" class="dsp_submit_button dspdp-btn" value="<?php echo language_code('DSP_SEARCH_BUTTON'); ?>" style="background: <?php //echo $temp_color;  ?>;"/>
                    </div>
                <?php if (!is_user_logged_in()) { ?>
                    <div class="dspdp-btn-group">
                            <input class="login-btn dsp_submit_button dspdp-btn" type="button" value="<?php echo strtoupper(language_code('DSP_LOGIN')); ?>" style="  background: <?php //echo $temp_color;          ?>;  " />
                    </div>

                    <div class="dspdp-btn-group">
                            <input  class="reg_popoup dsp_submit_button dspdp-btn" id="freebox" type="button" value="Join" />
                    </div>
                <?php } ?>
                <?php if (!is_user_logged_in()) { ?>
                        <div class="dspdp-btn-group other-template">
	                        <?php
	                        do_action( 'wpdating_facebook_login' );
	                        ?>
                        </div>
                <?php } ?>
                <?php /* ?>
                <div class="dspdp-block">
                    <div class="dspdp-col-sm-2">
                        <div class="dspdp-row">
                            <div class="">
                                <span class="dspdp-control-label dspdp-block">&nbsp;</span>
                                <input name="submit" type="submit" class="dsp_submit_button dspdp-btn" value="<?php echo language_code('DSP_SEARCH_BUTTON'); ?>" style="background: <?php //echo $temp_color;  ?>;"/>
                                <?php if (!is_user_logged_in()) { ?>
                                    <input class="login-btn dsp_submit_button dspdp-btn" type="button" value="<?php echo strtoupper(language_code('DSP_LOGIN')); ?>" style="  background: <?php //echo $temp_color;          ?>;  " />
                                    <input  class="reg_popoup dsp_submit_button dspdp-btn" id="freebox" type="button" value="Join" />
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!is_user_logged_in() && $isFacebookLoginSettingOn && $facebookCredentials) { ?>
                        <div class="dspdp-col-sm-2">
                            <div class="" style="">
                                <span class="dspdp-control-label">&nbsp;</span>
                                <a href="<?php echo $loginUrl ?>" class="dspdp-btn dspdp-btn-primary" onclick="window.open('<?php echo $loginUrl ?>', 'Authenticate', 'width=650, height=350');
                                        return false">
                                       <?php echo language_code('DSP_FACEBOOK_LOGIN'); ?>
                                </a>
                            </div>
                        </div>      
                    <?php } ?>
                </div>
                <?php */ ?>
            </div>
        </div>
    </form>
    <div class="clearfix"></div>
    <script type="text/javascript">
        function autoSubmitForm()
        {
            document.frmquicksearch.submit();
        }
        dsp = jQuery.noConflict();
    </script>   