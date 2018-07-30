<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
//  ########################################  UPDATE FLIRT TEXT DETAILS ##################################### //

global $wpdb;
$dsp_flirt_text_table = $wpdb->prefix . DSP_FLIRT_TEXT_TABLE;
$dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
$goback = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$dsp_action = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$dsp_flirt_text = isset($_REQUEST['dsp_flirt_text']) ? $_REQUEST['dsp_flirt_text'] : '';
$get_flirt_id = isset($_REQUEST['flirt_Id']) ? $_REQUEST['flirt_Id'] : '';
switch ($dsp_action) {
    case 'add':    // ADD FLIRT TEXT
        if (!empty($dsp_flirt_text)) {
            foreach ($dsp_flirt_text as $key1 => $value1) {
                $key1;
                $value1;

                $languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where language_id=$key1 ");
                $language_name = $languages->language_name;

                if ($language_name == 'english') {
                    $tableName = "dsp_flirt";
                } else {
                    $tableName = "dsp_flirt_" .strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                }
                //echo $tableName;
                $DSP_TABLE_NAME = $wpdb->prefix . $tableName;
                $wpdb->query($wpdb->prepare("INSERT INTO $DSP_TABLE_NAME( flirt_Text) VALUES ( %s )", array(
                        $value1)));
            }
            //$wpdb->query( $wpdb->prepare( "INSERT INTO $dsp_flirt_text_table( flirt_Text) VALUES ( %s )",array($dsp_flirt_text)));  
        }
        ?>
        <div class="updated">
            <p>
                <strong>New Flirt text added.</strong>
            </p>
        </div>
        <?php
        break;
    case 'update':    // UPDATE FLIRT TEXT
        if (!empty($dsp_flirt_text)) {
            foreach ($dsp_flirt_text as $key1 => $value1) {
                $key1;
                $value1;

                $languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where language_id=$key1 ");
                $language_name = $languages->language_name;

                if ($language_name == 'english') {
                    $tableName = "dsp_flirt";
                } else {
                    $tableName = "dsp_flirt_" .strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                }
                //echo $tableName;
                $DSP_TABLE_NAME = $wpdb->prefix . $tableName;

                $wpdb->query("UPDATE $DSP_TABLE_NAME SET flirt_Text = '$value1' WHERE Flirt_ID = '$get_flirt_id'");
            }
        }
        //header("Location:".$goback);
        $sendback = remove_query_arg(array('Action', 'Id'), $goback);
        echo "Flirt text updated! <a href='$sendback'>Click here</a> to View List";
        // wp_redirect($sendback,301);
        exit();
        break;
}
if (isset($_GET['Action']) && ($_GET['Action'] == "Del") && ($get_flirt_id != "")) {   // DELETE FLIRT TEXT
    $qrylanguages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table  ");
    foreach ($qrylanguages as $langs) {
        $language_name = $langs->language_name;

        if ($language_name == 'english') {
            $tableName = "dsp_flirt";
        } else {
            $tableName = "dsp_flirt_" .strtolower(trim(esc_sql(substr($language_name, 0, 2))));
        }
        $DSP_TABLE_NAME = $wpdb->prefix . $tableName;

        $wpdb->query("DELETE FROM $DSP_TABLE_NAME WHERE Flirt_ID = '$get_flirt_id'");
    }
}
// ############################################################################################################## //
?>
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <div id="general" class="postbox">
                <h3 class="hndle"><span><?php echo language_code('DSP_FLIRT_MESSAGE_HEAD'); ?></span></h3>
                <table cellpadding="0" cellspacing="0" class="dsp_thumbnails1">
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td>
                            <table cellpadding="6" cellspacing="0">
                                <?php
                                $myrows = $wpdb->get_results("SELECT * FROM $dsp_flirt_text_table Order by Flirt_ID");
                                foreach ($myrows as $flirts) {
                                    $Flirt_ID = $flirts->Flirt_ID;
                                    $flirt_Text = $flirts->flirt_Text;
                                    ?>
                                    <tr>
                                        <td class="form-field form-required"><?php echo $flirt_Text; ?></td>
                                        <td width="30px">&nbsp;</td>
                                        <td class="form-field form-required">
                                            <span onclick="update_flirt_text(<?php echo $Flirt_ID ?>);" class="span_pointer"><?php echo language_code('DSP_EDIT'); ?></span>/
                                            <span onclick="delete_flirt_text(<?php echo $Flirt_ID ?>);" class="span_pointer"><?php echo language_code('DSP_DELETE'); ?></span>
                                    </tr>
                                    <?php
                                }  //  foreach ($myrows as $spam) 
                                ?>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="form-field form-required" colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="form-field form-required">

                            <!-- -----------------------------   FLIRT TEXT BOX  --------------------------------- -->
                            <?php
                            if (isset($_GET['Action']) && $_GET['Action'] == 'update') {
                                $mode = 'update';
                            } else {

                                $get_flirt_id = "";

                                $mode = 'add';
                            }
                            ?>
                            <FORM  name="frmflirttext" method="post">
                                <table border="0" cellpadding="0" cellspacing="0" width="500px;">
                                    <tr>
                                        <td colspan="3" height="50"><?php echo language_code('DSP_FLIRT_TEXT'); ?></td>
                                    </tr>
                                    <?php
                                    // get all the language stored in table
                                    $all_languages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table  ");
                                    foreach ($all_languages as $lang) {

                                        $add_code_language_id = $lang->language_id;
                                        $imagePath = get_bloginfo('url') . '/wp-content/uploads/flags/' . $lang->flag_image;
                                        ?>
                                        <tr>
                                            <td width="35"><img height="24" src="<?php echo $imagePath; ?>" alt="<?php echo  $lang->flag_image;?>"/></td>
                                            <td><?php echo ucfirst($lang->language_name); ?></td>
                                            <td>

                                                <?php
                                                $alllanguages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where language_name= '$lang->language_name' ");


                                                $language_name = $alllanguages->language_name;

                                                if ($language_name == 'english') {
                                                    $tableName = "dsp_flirt";
                                                } else {
                                                    $tableName = "dsp_flirt_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                                                }
                                                $tableName;
                                                $DSP_TABLE_NAME = $wpdb->prefix . $tableName;
                                                if ($get_flirt_id != '')
                                                    $dsp_updates = $wpdb->get_row("SELECT * FROM $DSP_TABLE_NAME WHERE Flirt_ID = $get_flirt_id");
                                                ?>
                                                <input type="text" id="dsp_flirt_text_<?php echo $add_code_language_id; ?>" name="dsp_flirt_text[<?php echo $add_code_language_id; ?>]" value="<?php echo @$dsp_updates->flirt_Text; ?>" class="regular-text" />

                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr><td><input type="hidden" name="mode" value="<?php echo $mode ?>" /></td></tr>
                                    <tr>
                                        <td class="submit" align="center">
                                            <input  class="button" type="button"  value="<?php
                                                    if ($mode == 'add')
                                                        _e('Add');
                                                    else
                                                        _e('Update');
                                                    ?>" onclick="add_flirt_text();"/>
                                        </td>
                                    </tr>
                                </table>
                            </FORM>
                            <!-- -----------------------------  FLIRT TEXT BOX  --------------------------------- -->
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
<br />
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>