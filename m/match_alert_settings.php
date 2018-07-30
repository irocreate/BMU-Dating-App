<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a  onclick="viewSetting(0, 'setting')"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MATCH_ALERT_MODE'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>

</div>

<?php
$dsp_match_alert_criteria_table = $wpdb->prefix . DSP_MATCH_CRITERIA_TABLE;

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "update") {

    $active = isset($_REQUEST['active']) ? $_REQUEST['active'] : '';

    $frequency = isset($_REQUEST['frequency']) ? $_REQUEST['frequency'] : '';

    $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';

    $age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : '';

    $age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : '';

    $date = date("Y-m-d");



    $check_user = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_match_alert_criteria_table where user_id='$user_id'");


    if ($check_user <= 0) {

        $wpdb->query("INSERT INTO $dsp_match_alert_criteria_table SET active = '$active', frequency = '$frequency',gender='$gender',age_from = '$age_from',age_to = '$age_to',date = '$date', user_id=$user_id,last_updated_date='$date'");
    } else {

        $wpdb->query("UPDATE $dsp_match_alert_criteria_table SET active = '$active', frequency = '$frequency',gender='$gender',age_from = '$age_from',age_to = '$age_to',date = '$date',last_updated_date='$date' WHERE user_id=$user_id");
    }
    $msg = language_code("DSP_SETTINGS_UPDATED");
}





$match_alert_criteria_query = $wpdb->get_row("SELECT * FROM $dsp_match_alert_criteria_table WHERE user_id = '$user_id' ");
?>

<div class="ui-content" data-role="content">
    <div class="content-primary">	
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
            <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">


                <form name="frmuseraccount" method="post" action="" id="dspAccount">



                    <div class="setting-page">
                        <ul class="dsp_match">
                            <?php if (isset($msg)) {
                                ?>
                                <li class="msg">
                                    <span>&nbsp;</span>
                                    <?php echo $msg; ?>
                                </li>
                            <?php } ?>
                            <li><span><?php echo language_code('DSP_MATCH_ALERT_ACTIVE') ?>:&nbsp;</span>  
                                <select name="active">

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
                            </li>

                            <li><span><?php echo language_code('DSP_MATCH_ALERT_FREQUENCY') ?>:&nbsp;</span> 
                                <select name="frequency">

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

                                </select>
                            </li>
                            <li><span><?php echo language_code('DSP_GENDER') ?>&nbsp;</span>
                                <select name="gender" >

                                    <?php if ($match_alert_criteria_query->gender == 'F') { ?>

                                        <option value="M"><?php echo language_code('DSP_MAN'); ?></option>

                                        <option value="F" selected="selected"><?php echo language_code('DSP_WOMAN'); ?></option>

                                        <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                                            <option value="C" ><?php echo language_code('DSP_COUPLE'); ?></option>

                                        <?php } ?>



                                    <?php } else if ($match_alert_criteria_query->gender == 'M') { ?>



                                        <option value="M" selected="selected"><?php echo language_code('DSP_MAN'); ?></option>

                                        <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>

                                        <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                                            <option value="C" ><?php echo language_code('DSP_COUPLE'); ?></option>

                                        <?php } ?>



                                    <?php } else if ($match_alert_criteria_query->gender == 'C') { ?>



                                        <option value="M"><?php echo language_code('DSP_MAN'); ?></option>

                                        <option value="F"><?php echo language_code('DSP_WOMAN'); ?></option>

                                        <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                                            <option value="C"  selected="selected"><?php echo language_code('DSP_COUPLE'); ?></option>

                                        <?php } ?>



                                    <?php } else { ?>



                                        <option value="M"><?php echo language_code('DSP_MAN'); ?></option>

                                        <option value="F" selected="selected"><?php echo language_code('DSP_WOMAN'); ?></option>

                                        <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                                            <option value="C" ><?php echo language_code('DSP_COUPLE'); ?></option>	

                                        <?php } ?>  

                                    <?php } ?>

                                </select>
                            </li>
                            <li class="age-row">
                                <span class="title-age"><?php echo language_code('DSP_AGE') ?>&nbsp;</span>

                                <select name="age_from" style="width: 60px;">

                                    <?php if ($match_alert_criteria_query->age_from != '') { ?>

                                        <?php for ($i = $match_alert_criteria_query->age_from; $i <= '90'; $i++) { ?>

                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>

                                        <?php } ?>



                                    <?php } else { ?>



                                        <?php for ($i = '18'; $i <= '90'; $i++) { ?>

                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>

                                        <?php } ?>

                                    <?php } ?>
                                </select>
                                <span class="mid-txt"><?php echo language_code('DSP_TO') ?></span>
                                <span><select  name="age_to" style="width: 60px;">

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
                            <li>
                                <span>&nbsp;</span>
                                <input type="hidden" name="pagetitle" value="<?php echo $profile_pageurl; ?>" />
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                <input type="hidden" name="mode" value="<?php echo 'update'; ?>" />
                                <input type="button" onclick="viewSetting(0, 'post')" name="submit" value="Save" /></li>
                        </ul>


                        <div class="note"><?php echo language_code('DSP_MATCH_ALERT_NOTE') ?></div>


                    </div>



                </form>
            </li>
        </ul>


    </div>


    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>



<?php
//------------------------------------- END ACCOUNT SETTINGS  ------------------------------------------ // ?>