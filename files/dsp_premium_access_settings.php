<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ********************************  PREMIUM ACCESS SETINGS ************************************ //
global $wpdb;
$dsp_features_table = $wpdb->prefix . dsp_features;
$dsp_premium_access_feature_table = $wpdb->prefix . dsp_premium_access_feature;
$request_url = $_SERVER['REQUEST_URI'];
$cmbfeatures_id = $_POST['cmbfeatures_id'];
$feature_mode = $_POST['feature_mode'];
$update_feature_mode = $_POST['update_feature_mode'];
$update_feature_id = $_POST['update_feature_id'];
$txtfeatures_name = $_POST['txtfeatures_name'];
$active_status = $_POST['active_status'];
$ft_ID = $_GET['ft_ID'];
$Action = $_GET['Action'];
$feature_name = $wpdb->get_row("SELECT * FROM $dsp_features_table WHERE feature_id = '$cmbfeatures_id'");
if (($feature_mode == 'add_feature') && ($cmbfeatures_id != 0)) {
    $check_already_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM  $dsp_premium_access_feature_table WHERE feature_id='$cmbfeatures_id'"));
    if ($check_already_exists <= 0) {
        $wpdb->query($wpdb->prepare("INSERT INTO $dsp_premium_access_feature_table SET feature_id ='$cmbfeatures_id',f_name= '$feature_name->feature_name',active_status ='N'"));
        $Print_message = "feature Added.";
    } else {
        $Print_message = "You have already Added this feature.";
    }
}
if (($update_feature_mode == 'update_feature_mode') && ($update_feature_id != "")) {
    if ($txtfeatures_name != "") {
        foreach ($txtfeatures_name as $key => $value) {
            $wpdb->query($wpdb->prepare("UPDATE $dsp_premium_access_feature_table SET f_name= '$value' WHERE access_feature_id ='$key'"));
        }
    }

    if ($active_status != "") {
        $wpdb->query($wpdb->prepare("UPDATE $dsp_premium_access_feature_table SET active_status ='N'"));
        foreach ($active_status as $key => $value) {
            if ($value == "Y") {
                $status = "Y";
                $wpdb->query($wpdb->prepare("UPDATE $dsp_premium_access_feature_table SET active_status ='$status' WHERE access_feature_id ='$key'"));
            }
        }
    }
    $Print_message = "features Updated.";
}
if (($ft_ID != "") && ($Action == "Del")) {  // DELETE ACCESS FEATURE FROM LIST
    $check_status = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM  $dsp_premium_access_feature_table WHERE access_feature_id='$ft_ID' AND active_status='Y'"));
    if ($check_status <= 0) {
        $wpdb->query("DELETE FROM $dsp_premium_access_feature_table WHERE access_feature_id = '$ft_ID'");
        $Print_message = "Feature Deleted.";
    } else {
        $Print_message = "Active feature, You can't Delete this feature.";
    }
    $sendback = remove_query_arg(array('Action', 'ft_ID'), $request_url);
//wp_redirect($sendback); 
    ?>
    <script>location.href = "<?php echo $sendback; ?>"</script>
    <?php
}
?>
<?php if (isset($Print_message)) { ?>
    <div id="message" class="updated fade"><strong><?php echo $Print_message ?></strong></div>
<?php } ?>
<div class="dsp_admin_headings"><?php echo "Premium Access"; ?></div>
<div style="height:30px;"></div>
<div>
    <table class="dsp_thumbnails1" border="0">
        <tr>
            <td colspan="3">
                <?php //-------------------------------------- Added features list-------------------------------------- //  ?>  
                <form name="updatefeaturesfrm" action="" method="post">
                    <table cellpadding="0" width="100%">
                        <tr>
                            <td style="color:#32669d"><strong>Name</strong></td>
                            <td style="color:#32669d"><strong>Active</strong></td>
                            <td style="color:#32669d"><strong>Action</strong></td>
                        </tr>
                        <?php
                        $myrows = $wpdb->get_results("SELECT * FROM $dsp_premium_access_feature_table Order by access_feature_id ");
                        foreach ($myrows as $premium_acess_feature) {
                            $access_feature_id = $premium_acess_feature->access_feature_id;
                            $f_name = $premium_acess_feature->f_name;
                            $active_status_value = $premium_acess_feature->active_status;
                            $feature_id = $premium_acess_feature->feature_id;
                            ?>
                            <tr>
                                <td><input type="text" name="txtfeatures_name[<?php echo $access_feature_id ?>]" value="<?php echo $f_name; ?>" /></td>
                                <td>
                                    <input type="hidden" name="update_feature_id[<?php echo $access_feature_id ?>]" value="<?php echo $access_feature_id; ?>" />
                                    <input type="checkbox" name="active_status[<?php echo $access_feature_id ?>]" value="Y" <?php if ($active_status_value == "Y") { ?> checked="checked"  <?php } ?>/>
                                </td> 
                                <td class="form-field form-required"><span onclick="delete_access_feature(<?php echo $access_feature_id ?>);" class="span_pointer"><?php echo "remove"; ?></span></td>
                            </tr>
                        <?php } ?>
                        <tr><td colspan="3"></td></tr>
                        <tr>
                            <td colspan="2"><input type="hidden" name="update_feature_mode" id="update_feature_mode" value="" /></td>
                            <td><input type="button" name="savebutton" value="Save" onclick="update_access_feature();"/></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
            <td colspan="3">
                <?php //-------------------------------------- Add New feature-------------------------------------- //  ?>  
                <form name="addfeaturefrm" action="" method="post">
                    <table cellpadding="0" width="100%">
                        <tr>
                            <td style="color:#32669d" colspan="3" align="center"><strong>Name:</strong>
                                <select name="cmbfeatures_id">
                                    <option value="0">Select Feature</option>
                                    <?php
                                    $features_list = $wpdb->get_results("SELECT * FROM $dsp_features_table Order by feature_id");
                                    foreach ($features_list as $feature) {
                                        ?>
                                        <option value="<?php echo $feature->feature_id ?>"><?php echo $feature->feature_name ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>

                        <tr><td colspan="3"></td></tr>
                        <tr>
                            <td colspan="2"><input type="hidden" name="feature_mode" id="feature_mode" value="" /></td>
                            <td align="center"><input type="button" name="addbutton" value="<?php echo language_code('DSP_ADD') ?>" onclick="add_dsp_feature();" /></td>
                        </tr>
                    </table>
                </form>
            </td>
        </tr>
    </table>
</div>
<br />
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>