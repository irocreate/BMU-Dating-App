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
<div id="main-container" class="tk-chaparral-pro">
    <div id="sub-container">
        <div role="banner" class="ui-header ui-bar-a top-bar" data-role="header">
               <?php include_once("page_menu.php");?> 
            <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_INBOX');?></h1>
            <?php include_once("page_home.php");?> 
        </div>
        <div class="ui-content" data-role="content">
            <div class="content-primary">	 
                <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul menu-list">

                    <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                     <a href="dsp_inbox.html" > 
                         <img src="images/icons/inbox.png"/>
                         <?php echo $msg; ?></a>
                     </li>


                     <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                        <a href="dsp_compose.html" >
                            <img src="images/icons/compose.png"/>
                            <?php echo language_code('DSP_MIDDLE_TAB_COMPOSE'); ?>
                        </a>
                    </li>


                    <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                       <a href="dsp_sent_message.html" >
                           <img src="images/icons/sent.png"/>
                           <?php echo language_code('DSP_SENT');?>
                       </a>
                   </li>


                   <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                      <a href="dsp_deleted_msg.html">
                          <img src="images/icons/deleted.png"/>
                          <?php  echo language_code('DSP_MIDDLE_TAB_DELETED'); ?>
                      </a>
                  </li>



              </ul>


          </div>
          <?php include_once('dspNotificationPopup.php'); // for notification pop up     ?>
      </div>
  </div>
</div>
<?php include_once("dspLeftMenu.php"); ?>