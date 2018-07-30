<?php
$dsp_partner_profile_question_details_table = $wpdb->prefix . DSP_PARTNER_PROFILE_QUESTIONS_DETAILS;
$dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
?>

<!--<div role="banner" class="ui-header ui-bar-a" data-role="header">
                    <div class="back-image">
                    <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
                    </div>
                <h1 aria-level="1" role="heading" class="ui-title"><?php
echo language_code('DSP_ADD_PHOTO_BUTTON');
;
?></h1>
                
</div>-->

<?php
$check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");


$check_partner_profile_exist = $wpdb->get_var("SELECT count(*) FROM $dsp_user_partner_profiles_table WHERE user_id = '$user_id'");

$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_partner_profiles_table WHERE user_id = '$user_id'");



if ($check_partner_profile_exist != 0)
    $user_partner_profile_id = $exist_profile_details->user_id;
else
    $user_partner_profile_id = "";

if ($gender == '') {
    if ($check_partner_profile_exist != 0)
        $gender = $exist_profile_details->gender;
    else
        $gender = "";
}
?>

<?php //---------------------------------START  GENERAL SEARCH---------------------------------------    ?>


<span style="padding-right:10px;float: left;">
    <a onclick="getPartnerPhoto();">
        <img src="<?php echo display_members_partner_photo($user_id, $imagepath); ?>" style="width:100px; height:100px;" class="img" />
    </a>
</span>

<span>
    <div style="padding-bottom: 20px;">
        <input onclick="savePrivateStatus(this.value)" type="checkbox" value="Y" name="private"><?php echo language_code('DSP_PHOTO_MAKE_PRIVATE') ?>
    </div>
    <button onclick="getPartnerPhoto();"><?php echo language_code('DSP_BROWSE') ?></button>	
</span>			