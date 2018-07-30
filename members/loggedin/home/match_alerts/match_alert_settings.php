<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
?>
<?php
if (isset($_POST['submit'])) {
    $active = isset($_REQUEST['active']) ? $_REQUEST['active'] : '';
    $frequency = isset($_REQUEST['frequency']) ? $_REQUEST['frequency'] : '';
    $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
    $age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : '';
    $age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : '';
   
    $date = date("Y-m-d ");
    $user_id = $current_user->ID;
    $dsp_match_alert_criteria_table = $wpdb->prefix . DSP_MATCH_CRITERIA_TABLE;
    $check_user = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_match_alert_criteria_table where user_id='$user_id'");
    if ($check_user <= 0) {
        $wpdb->query("INSERT INTO $dsp_match_alert_criteria_table SET active = '$active', frequency = '$frequency',gender='$gender',age_from = '$age_from',age_to = '$age_to',date = '$date', user_id=$user_id,last_updated_date='$date'");
    } else {
        $wpdb->query("UPDATE $dsp_match_alert_criteria_table SET active = '$active', frequency = '$frequency',gender='$gender',age_from = '$age_from',age_to = '$age_to',date = '$date',last_updated_date='$date' WHERE user_id=$user_id");
    }
}
?>
<?php //---------------------------------------START ACCOUNT SETTINGS ------------------------------------//          ?>
<?php
$dsp_match_alert_criteria_table = $wpdb->prefix . DSP_MATCH_CRITERIA_TABLE;
$match_alert_criteria_query = $wpdb->get_row("SELECT * FROM $dsp_match_alert_criteria_table WHERE user_id = '$current_user->ID' ");
?>

<div class="box-border">
    <div class="box-pedding">
        <div class="heading-submenu"><strong><?php echo language_code('DSP_MATCH_ALERT_TITLE'); ?></strong></div>
        <div class="dsp-form-container">
            <div class="note dsp-block margin-btm-3" style="display:none"><?php echo language_code('DSP_MATCH_ALERT_NOTE') ?></div>
           <form name="frmuseraccount" method="post" action="" class="dspdp-form-horizontal">
                <div class="setting-page">
                    <ul>
                        <li class="dspdp-form-group dsp-form-group">
    					<span class="left-space dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label">
                            <?php echo language_code('DSP_MATCH_ALERT_ACTIVE') ?>:&nbsp;
                        </span>  
                        <span class="dspdp-col-sm-7 dsp-sm-7">
                            <select name="active" class="dspdp-form-control dsp-form-control">
                                <?php if ($match_alert_criteria_query->active == 'N') { ?>
                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES') ?> </option>
                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO') ?></option>
                                <?php } else if ($match_alert_criteria_query->active == 'Y') { ?>
                                    <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES') ?> </option>
                                    <option value="N"><?php echo language_code('DSP_OPTION_NO') ?></option>
                                <?php } else { ?>
                                    <option value="Y"><?php echo language_code('DSP_OPTION_YES') ?> </option>
                                    <option value="N" selected="selected"><?php echo language_code('DSP_OPTION_NO') ?></option>
                                <?php } ?>
                            </select>
                        </span>
                        </li>
                        <li class="dspdp-form-group dsp-form-group"><span class="left-space dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label"><?php echo language_code('DSP_MATCH_ALERT_FREQUENCY') ?>:&nbsp;</span> 
                            <span class="dspdp-col-sm-7 dsp-sm-7"><select name="frequency" class="dspdp-form-control dsp-form-control">
                                <?php if ($match_alert_criteria_query->frequency == 'W') { ?>
                                    <option value="D"><?php echo language_code('DSP_OPTION_DAILY') ?> </option>
                                    <option value="W" selected="selected"><?php echo language_code('DSP_OPTION_WEEKLY') ?></option>
                                    <option value="M"><?php echo language_code('DSP_OPTION_MONTHLY') ?></option>
                                <?php } else if ($match_alert_criteria_query->frequency == 'D') { ?>
                                    <option value="D" selected="selected"><?php echo language_code('DSP_OPTION_DAILY') ?> </option>
                                    <option value="W"><?php echo language_code('DSP_OPTION_WEEKLY') ?></option>
                                    <option value="M"><?php echo language_code('DSP_OPTION_MONTHLY') ?></option>	
                                <?php } else if ($match_alert_criteria_query->frequency == 'M') { ?>
                                    <option value="D"><?php echo language_code('DSP_OPTION_DAILY') ?> </option>
                                    <option value="W"><?php echo language_code('DSP_OPTION_WEEKLY') ?></option>
                                    <option value="M" selected="selected"><?php echo language_code('DSP_OPTION_MONTHLY') ?></option>
                                <?php } else { ?>
                                    <option value="D"><?php echo language_code('DSP_OPTION_DAILY') ?> </option>
                                    <option value="W" selected="selected"><?php echo language_code('DSP_OPTION_WEEKLY') ?></option>
                                    <option value="M"><?php echo language_code('DSP_OPTION_MONTHLY') ?></option>
                                <?php } ?>
                            </select></span>
                        </li>
                        <li class="dspdp-form-group dsp-form-group clearfix"><span class="left-space dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label"><?php echo language_code('DSP_GENDER') ?>&nbsp;</span>
                            <span class="dspdp-col-sm-7 dsp-sm-7"><select name="gender"  class="dspdp-form-control dsp-form-control">
                                <?php echo get_gender_list($match_alert_criteria_query->gender); ?>
                            </select></span>
                        </li>
                        <li class="age-row dspdp-form-group dsp-form-group clearfix">
                            <span class="left-space dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label"><?php echo language_code('DSP_AGE') ?>&nbsp;</span>
                           <span class="dspdp-col-sm-3 dsp-sm-3"> <select name="age_from" class="dspdp-form-control dsp-form-control">
                                <?php if ($match_alert_criteria_query->age_from != '') { ?>
                                    <?php for ($i = $match_alert_criteria_query->age_from; $i <= '90'; $i++) { ?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                    <?php } ?>

                                <?php } else { ?>

                                    <?php for ($i = '18'; $i <= '90'; $i++) { ?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                    <?php } ?>
                                <?php } ?>


                            </select></span>
                            <span class="left-space dspdp-col-sm-1 dsp-sm-1 dspdp-control-label dsp-control-label"><?php echo language_code('DSP_TO'); ?></span>
                            <span class="dspdp-col-sm-3 dsp-sm-3"><select  name="age_to" class="dspdp-form-control dsp-form-control">
                                    <?php if ($match_alert_criteria_query->age_to != '') { ?>

                                        <?php for ($j = $match_alert_criteria_query->age_to; $j >= '18'; $j--) { ?>
                                            <option value="<?php echo $j ?>"><?php echo $j ?></option>
                                        <?php } ?>

                                    <?php } else { ?>

                                        <?php for ($j = '90'; $j >= '18'; $j--) { ?>
                                            <option value="<?php echo $j ?>"><?php echo $j ?></option>
                                        <?php } ?>
                                    <?php } ?>

                                </select>
                            </span>
                        </li>
                        <li class="dspdp-form-group dsp-form-group clearfix">
                            <span class=" dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label">&nbsp;</span>
                            <span class="dspdp-col-sm-6 dsp-sm-6"><input type="submit" name="submit" value="<?php echo language_code('DSP_SAVE'); ?>" class="dsp_submit_button dspdp-btn dspdp-btn-default" /></span></li>
                    </ul>
                    <div class="note dsp-none"><?php echo language_code('DSP_MATCH_ALERT_NOTE') ?></div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
//------------------------------------- END ACCOUNT SETTINGS  ------------------------------------------ // ?>