<?php
//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->
//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

$user_id = $_REQUEST['user_id'];



$dsp_user_emails_table = $wpdb->prefix . DSP_EMAILS_TABLE;



$count_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE message_read='N' AND receiver_id=$user_id AND delete_message=0");

if ($count_messages > 0) {
    $msg = language_code('DSP_INBOX') . ' (' . $count_messages . ')';
} else {
    $msg = language_code('DSP_INBOX');
}
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php
        echo language_code('DSP_INBOX');
        ;
        ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>
</div>
<div class="ui-content" data-role="content">
    <div class="content-primary">	 
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul">
            <a href="dsp_inbox.html">
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                    <?php echo $msg; ?>
                </li>
            </a>
            <a href="dsp_compose.html">
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                    <?php
                    echo language_code('DSP_MIDDLE_TAB_COMPOSE');
                    ;
                    ?>
                </li>
            </a>
            <a href="dsp_sent_message.html">
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                    <?php
                    echo language_code('DSP_SENT');
                    ;
                    ?>
                </li>
            </a>
            <a href="dsp_deleted_msg.html">
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                    <?php
                    echo language_code('DSP_MIDDLE_TAB_DELETED');
                    ;
                    ?>
                </li>
            </a>


        </ul>


    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up     ?>
</div>