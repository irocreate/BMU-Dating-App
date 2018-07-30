<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$current_user = wp_get_current_user();
$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path
$user_id = $current_user->ID;  // print session USER_ID
$online_member_div = $pluginpath . "dsp_online_members_list.php";
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$check_refresh_rate = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'refresh_rate'");
?>
<script>
    jqueryVersion = jQuery.fn.jquery,
    wpo = jQuery.noConflict();
    wpo(document).ready(function() {
<?php if (is_user_logged_in()) { // CHECK MEMBER LOGIN ?>
            var dspChangeGenderFn = function() {
                wpo("#dsp_online_gender").val(wpo("#dsp_online_gender_select").val());
                check_online();
            };

                if(cmpVersion(jqueryVersion, 1.7) > 0){
                    wpo(document).on('click', '#dsp_change_gender',dspChangeGenderFn);
                }else {
                    wpo('#dsp_change_gender').live('click', dspChangeGenderFn);
                }
            //End
            
<?php } ?>
    });
<?php
if (is_user_logged_in()) { // CHECK MEMBER LOGIN
    ?>
        function check_online() {
            var check_online_init = setInterval(function() {

                var gender = wpo("#dsp_online_gender").val();

                wpo.ajax({
                    url: "<?php echo $online_member_div ?>?gender=" + gender,
                    cache: false,
                    success: function(html) {

                        wpo('.online-person').html(html);

                    },
                });

                clearInterval(check_online_init);
                check_online();
            }, <?php echo $check_refresh_rate->setting_value; ?>000);
        }
        check_online();

<?php } ?>
</script>
<div class="online-person">
</div><br />
<div class="select-info dspdp-form-inline"><span class="title dspdp-control-label"><?php echo language_code('DSP_GENDER'); ?></span>&nbsp;<select id="dsp_online_gender_select" class="dspdp-form-control">
        <option value="All"><?php echo language_code('DSP_OPTION_ALL'); ?></option>
        <?php echo get_gender_list(); ?>
    </select> <input class="button dspdp-btn  dspdp-btn-default" id="dsp_change_gender" type="button" value="<?php echo language_code('DSP_FILTER_BUTTON'); ?>" /></div>
<input type="hidden" id="dsp_online_gender" value="All" />