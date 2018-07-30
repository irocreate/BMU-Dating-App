<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
$txtskypename = isset( $_REQUEST['txtskypename'] ) ? $_REQUEST['txtskypename'] : '';
$cmb_skype_setting = isset( $_REQUEST['cmb_skype_setting'] ) ? esc_sql( sanitizeData( trim( $_REQUEST['cmb_skype_setting'] ), 'xss_clean' ) ) : '';
$update_mode = isset( $_REQUEST['update_mode'] ) ? $_REQUEST['update_mode'] : '';

$skype_added = date( "Y-m-d H:i:s" );
if ( ( $update_mode == 'skype_update' ) && ( $user_id != "" ) ) {
    $check_skype_user_exists = $wpdb->get_var( "SELECT COUNT(*) FROM $dsp_skype_table WHERE user_id='$user_id'" );
    if ( $check_skype_user_exists > 0 ) {
        $wpdb->query( "UPDATE $dsp_skype_table SET skype_name = '$txtskypename',skype_status='$cmb_skype_setting',skype_added='$skype_added' WHERE user_id = '$user_id'" );
    } else {
        $wpdb->query( "INSERT INTO $dsp_skype_table SET user_id = '$user_id',skype_name = '$txtskypename',skype_status='$cmb_skype_setting',skype_added='$skype_added'" );
    }
    $settings_updated = true;
}
$member_skype_settings = $wpdb->get_row( "SELECT * FROM $dsp_skype_table WHERE user_id = '$user_id'" );
?>
<?php if ( isset( $settings_updated ) && $settings_updated == true ) { ?>
    <div class="thanks">
        <p align="center" class="error"><?php echo language_code( 'DSP_SETTINGS_UPDATED' ) ?></p>
    </div>
<?php } ?>
<?php //-------------------------------START SKYPE SETTINGS ------------------------//  ?>

<div class="box-border">
    <div class="box-pedding">
        <div class="heading-submenu"><strong><?php echo language_code( 'DSP_SKYPE_TITLE' ); ?></strong></div> <span class="dsp-none"></br></br></span>
        <div class="dsp-form-container">
            <form name="frmskypesettings" action="" method="post" class="dspdp-form-horizontal">
                <div class="setting-page">
                    <p class="dspdp-form-group dsp-form-group clearfix">
                        <span class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code( 'DSP_SKYPE_NAME' ); ?>
                        </span>
                        <span class="dspdp-col-sm-6 dsp-sm-6">
                            <input class="dspdp-form-control dsp-form-control" type="text" name="txtskypename" value="<?php echo @$member_skype_settings->skype_name; ?>">
                        </span>
                    </p>
                    <p class="dspdp-form-group dsp-form-group clearfix">
                        <span class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo language_code( 'DSP_SKYPE_SETTING' ); ?>
                        </span>
                        <span class="dspdp-col-sm-6 dsp-sm-6">
                            <select class="dspdp-form-control dsp-form-control" name="cmb_skype_setting">
                                <?php
                                    if ( $member_skype_settings->skype_status == 'Y' ) {
                                    ?>
                                    <option value="Y" selected="selected"><?php echo language_code( 'DSP_SHOW_TO_FAVOURITES_OPTION' ) ?></option>
                                    <option value="N"><?php echo language_code( 'DSP_SHOW_TO_EVERYONE_OPTION' ); ?></option>
                                <?php } else { ?>
                                    <option value="Y"><?php echo language_code( 'DSP_SHOW_TO_FAVOURITES_OPTION' ) ?></option>
                                    <option value="N"  selected="selected"><?php echo language_code( 'DSP_SHOW_TO_EVERYONE_OPTION' ); ?></option>
                                <?php } ?>
                            </select>
                        </span>
                    </p>
                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="hidden-row dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <span><input type="hidden" name="update_mode" value="skype_update" /></span>
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <input type="submit" name="submit" value="<?php echo language_code( 'DSP_SUBMIT_BUTTON' ) ?>" class="dsp_submit_button dspdp-btn dspdp-btn-default"/>
                        </p>
                    </div>
                </div>
            </form>
        </div> 
    </div>
</div>

<?php
//------------------------------------- END PRIVACY SETTINGS  -------------------------------------// ?>
