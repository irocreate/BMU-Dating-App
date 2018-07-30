<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// In this file we checks Admin General Settings
include_once(WP_DSP_ABSPATH . "general_settings.php");
// ###############################  UPDATE SPAM WORD DETAILS ################################ //
global $wpdb;
$dsp_spam_words_table = $wpdb->prefix . DSP_SPAM_WORDS_TABLE;
$dsp_action = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$dsp_spamwords = isset($_REQUEST['dsp_spamwords']) ? $_REQUEST['dsp_spamwords'] : '';
$spam_word_id = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : '';
switch ($dsp_action) {
    case 'add':    // ADD SPAM WORD 
        if (!empty($dsp_spamwords)) {
            $wpdb->query($wpdb->prepare("INSERT INTO $dsp_spam_words_table( spam_word) VALUES ( %s )", array(
                $dsp_spamwords)));
        }  // if( !empty($dsp_spamwords))
        ?>
        <script>location.href = "<?php
        echo add_query_arg(array('pid' => 'spam_settings',
            'msg' => 'a'), $settings_root_link);
        ?>"</script>
        <?php
        break;

    case 'update':  // UPDATE SPAM WORD
        if (!empty($dsp_spamwords)) {
            $wpdb->query("UPDATE $dsp_spam_words_table SET spam_word = '$dsp_spamwords' WHERE spam_word_id = '$spam_word_id'");
        } // if( !empty($dsp_spamwords)) 
        ?>
        <script>location.href = "<?php
        echo add_query_arg(array('pid' => 'spam_settings',
            'msg' => 'e'), $settings_root_link);
        ?>"</script>
        <?php
        break;
}

if (isset($_GET['Action']) && $_GET['Action'] == "Del") {  // DELETE SPAM WORD
    $spam_word_id = $_GET['Id'];
    $wpdb->query("DELETE FROM $dsp_spam_words_table WHERE spam_word_id = '$spam_word_id'");
    ?>
    <script>location.href = "<?php
    echo add_query_arg(array('pid' => 'spam_settings',
        'msg' => 'd'), $settings_root_link);
    ?>"</script>
<?php
} // if($_GET['Action']=="Del")
// ############################################################################################################## //
$spam_msg = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : '';
if ($spam_msg == 'a') {
    ?>
    <div id="message" class="updated fade"><p><strong>Spam word Added.</strong></p></div>
<?php
}
if ($spam_msg == 'e') {
    ?>
    <div id="message" class="updated fade"><p><strong>Spam word Updated.</strong></p></div>
<?php
}
if ($spam_msg == 'd') {
    ?>
    <div id="message" class="updated fade"><p><strong>Spam word Deleted.</strong></p></div>
<?php
}
$filter_msg = isset($_REQUEST['updated']) ? $_REQUEST['updated'] : '';
if ($filter_msg == 'true') {
    ?>
    <div id="message" class="updated fade"><p><strong>Settings saved.</strong></p></div>
<?php } ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td>
            <div id="general" class="postbox">
                <h3 class="hndle"><span><?php echo language_code('DSP_SPAM_FILTER'); ?></span></h3>

                <form name="frmspamfilter" method="post" action="<?php
                echo add_query_arg(array(
                    'pid' => 'update_spam_settings', 'mode' => 'update'), $settings_root_link);
                ?>">

                    <table cellpadding="6" cellspacing="0" border="0" class="dsp_thumbnails1">
                        <tr>
                            <td>&nbsp;</td>
                        </tr>

                        <tr valign="top">

                            <td scope="row" class="form-field form-required"><label
                                    for="spamfilter"><?php _e(language_code('DSP_SPAM_FILTER')) ?></label></td>

                            <td>

                                <select name="cmbspamfilter">

                                    <?php if ($check_spam_filter->setting_status == 'Y') { ?>

                                        <option value="Y" selected="selected">On</option>

                                        <option value="N">Off</option>

                                    <?php } else { ?>

                                        <option value="Y">On</option>

                                        <option value="N" selected="selected">Off</option>

                                    <?php } ?>

                                </select>

                            </td>

                            <td><span class="description"
                                      style="white-space:nowrap;"><?php _e(language_code('DSP_SPAM__FILTER_TEXT')) ?></span>
                            </td>

                        </tr>

                        <tr>
                            <td colspan="3" class="submit" align="left">

                                <input type="hidden" name="filter_Action" value="update_filter"/>

                                <input type="submit" name="submit" value="<?php _e('Save Changes') ?>"
                                       class="button button-primary"/>

                            </td>
                        </tr>

                    </table>

                </form>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div id="general" class="postbox">
                <h3 class="hndle"><span><?php echo language_code('DSP_SPAM_WORDS'); ?></span></h3>
                <table cellpadding="0" cellspacing="0" class="dsp_thumbnails1" width="100%">
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table cellpadding="6" cellspacing="0">
                                <?php
                                $myrows = $wpdb->get_results("SELECT * FROM $dsp_spam_words_table Order by spam_word");
                                foreach ($myrows as $spam) {
                                    $spam_word_id = $spam->spam_word_id;
                                    $spam_words = $spam->spam_word;
                                    ?>
                                    <tr>
                                        <td class="form-field form-required"><?php echo $spam_words; ?></td>
                                        <td width="30px">&nbsp;</td>
                                        <td class="form-field form-required">
                                            <span onclick="update_spamword(<?php echo $spam_word_id ?>);"
                                                  class="span_pointer"><?php echo language_code('DSP_EDIT'); ?></span>/
                                            <span onclick="delete_spamword(<?php echo $spam_word_id ?>);"
                                                  class="span_pointer"><?php echo language_code('DSP_DELETE'); ?></span>
                                    </tr>
                                <?php }  //  foreach ($myrows as $spam)      ?>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="form-field form-required" colspan="3">&nbsp;</td>
                    </tr>

                    <tr>
                        <td class="form-field form-required">
                            <!-- -----------------------------   SPAM WORD TEXT BOX  --------------------------------- -->
                            <?php
                            if (isset($_GET['Action']) && $_GET['Action'] == 'update') {
                                $mode = 'update';
                                $spam_word_id = $_GET['Id'];
                                $dsp_updates = $wpdb->get_row("SELECT * FROM $dsp_spam_words_table WHERE spam_word_id = $spam_word_id");
                                $spam_word = $dsp_updates->spam_word;
                            } else {

                                $spam_word = "";

                                $mode = 'add';
                            }
                            ?>
                            <form name="frmspamwords" method="post">
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td><?php echo language_code('DSP_SPAMWORD'); ?></td>
                                        <td colspan="2">
                                            <input type="text" name="dsp_spamwords" value="<?php echo $spam_word; ?>"
                                                   style="width:200px;" class="regular-text"/></td>
                                    </tr>
                                    <tr>
                                        <td><input type="hidden" name="mode" value="<?php echo $mode ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td class="submit" align="center">
                                            <input type="button" value="<?php
                                            if ($mode == 'add')
                                                _e('Add');
                                            else
                                                _e('Update');
                                            ?>" onclick="add_spam_words();"/></td>
                                    </tr>
                                </table>
                            </form>
                            <!-- -----------------------------  SPAM WORD TEXT BOX  --------------------------------- -->
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
</td>
</tr>
</table>
<br/>
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>
