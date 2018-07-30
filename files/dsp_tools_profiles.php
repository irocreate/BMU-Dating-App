<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ##########################   UPDATE PROFILE QUESTIONS   ################################# //
global $wpdb;
$dsp_field_types_table = $wpdb->prefix . DSP_FIELD_TYPES_TABLE;
$dsp_profile_setup_table = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
$dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
$dsp_profile_questions_details_table = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;

if (isset($_GET['table_id']))
    $current_lang_table = filter_var(strip_tags($_GET['table_id']), FILTER_SANITIZE_STRING);
else
    $current_lang_table = '';

$profile_question_tables = array(); // to store all the language profile setup tables names
$languages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table");
foreach ($languages as $key => $language) {
    $lang_name = $language->language_name;
    if ($lang_name == 'english') {
        $tblName = "dsp_profile_setup";
    } else {
        $tblName = "dsp_profile_setup_" . strtolower(trim(esc_sql(substr($lang_name, 0, 2))));
    }
    $DSP_TBL_NAME = $wpdb->prefix . $tblName;
    array_push($profile_question_tables, $DSP_TBL_NAME);
}
$profile_question_tables = implode('+', $profile_question_tables);

$max_length_of_field_query = "SELECT * from information_schema.COLUMNS"
    . " WHERE `TABLE_SCHEMA` = '$wpdb->dbname' AND `TABLE_NAME` =  '$dsp_profile_questions_details_table'"
    . " AND `COLUMN_NAME` = 'option_value'";
$max_length_of_field = $wpdb->get_row($max_length_of_field_query);
$max_length_of_field = $max_length_of_field->CHARACTER_MAXIMUM_LENGTH;
$last_order_value = $wpdb->get_row("SELECT `sort_order` FROM $dsp_profile_setup_table ORDER BY `sort_order` DESC");
$last_order_value = $last_order_value->sort_order;

$request_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : array();
$goback = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : array();
$dsp_action = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';

//print_r($_REQUEST['profile_question']);var_dump(isset($_REQUEST['profile_question']));print_r(array_filter($_REQUEST['profile_question']));die;
//$profile_question = isset($_REQUEST['profile_question'])?array_filter($_REQUEST['profile_question']):'';
//$profile_question = isset($_REQUEST['profile_question']) ? $_REQUEST['profile_question'] : '';
if (isset($_REQUEST['profile_question']) && (array_filter($_REQUEST['profile_question']))) {
    $profile_question = $_REQUEST['profile_question'];
    // fill empty language fields by a filled field in descending priority i,e, english then chinese etc...
    foreach ($profile_question as $prof_ques) {
        if ($prof_ques != null) {
            $default_question = stripslashes($prof_ques);
            break;
        }
    }

    // old version compatibilty: insert the same highest id on all the profile question language tables
    $highest_profile_setup_id = 0;
    foreach ($profile_question as $key => $value) {
        $languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where language_id=$key ");
        $language_name = $languages->language_name;
        if ($language_name == 'english') {
            $tableName = "dsp_profile_setup";
        } else {
            $tableName = "dsp_profile_setup_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
        }
        $DSP_TABLE_NAME = $wpdb->prefix . $tableName;
        $temp_highest_profile_setup_id = $wpdb->get_row("SELECT `profile_setup_id` FROM $DSP_TABLE_NAME ORDER BY `profile_setup_id` DESC");
        if ($temp_highest_profile_setup_id->profile_setup_id > $highest_profile_setup_id) {
            $highest_profile_setup_id = $temp_highest_profile_setup_id->profile_setup_id;
        }
    }
    $highest_profile_setup_id = $highest_profile_setup_id + 1;
} else {
    $profile_question = array();
}


$cmbfieldtype = isset($_REQUEST['cmbfieldtype']) ? $_REQUEST['cmbfieldtype'] : '';
$sort_order = (isset($_REQUEST['sort_order']) && $_REQUEST['sort_order'] != '') ? $_REQUEST['sort_order'] : $last_order_value + 1;

if (isset($_REQUEST['maxcharacter']) && ($_REQUEST['maxcharacter'] != '') && ($_REQUEST['maxcharacter'] <= $max_length_of_field))
    $maxcharacter = $_REQUEST['maxcharacter'];
else
    $maxcharacter = $max_length_of_field;

$cmbrequired = isset($_REQUEST['cmbrequired']) ? $_REQUEST['cmbrequired'] : '';
$display_status = isset($_REQUEST['cmbdisplay_status']) ? $_REQUEST['cmbdisplay_status'] : 'N';
$profile_question_id = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : '';
switch ($dsp_action) {
    case 'add':    // ADD PROFILE QUESTION
        if (!empty($profile_question) && $cmbfieldtype != '') {
            foreach ($profile_question as $key1 => $value1) {
                //$key1;
                $value1 = stripslashes($value1);
                if ($value1 == null) {
                    $value1 = $default_question;
                }
                $languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where language_id=$key1 ");
                $language_name = $languages->language_name;
                if ($language_name == 'english') {
                    $tableName = "dsp_profile_setup";
                } else {
                    $tableName = "dsp_profile_setup_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                }
                $DSP_TABLE_NAME = $wpdb->prefix . $tableName;

                //print_r($DSP_TABLE_NAME);echo '<br>';
                //print_r($wpdb->prepare("INSERT INTO $DSP_TABLE_NAME (question_name, field_type_id,sort_order,max_length,required,display_status) VALUES(%s,%d,%d,%d,%s,%s)",$value1,$cmbfieldtype,$sort_order,$maxcharacter,$cmbrequired,$display_status));echo '<br>';
                $wpdb->query($wpdb->prepare("INSERT INTO $DSP_TABLE_NAME (profile_setup_id,question_name, field_type_id,sort_order,max_length,required,display_status) VALUES(%d,%s,%d,%d,%d,%s,%s)", $highest_profile_setup_id, $value1, $cmbfieldtype, $sort_order, $maxcharacter, $cmbrequired, $display_status));
            }
            ?>
            <div class="updated">
                <p>
                    <strong><?php echo language_code('DSP_PROFILE_QUESTIONS_ADDED'); ?></strong>
                </p>
            </div>
        <?php

        } else {
            ?>
            <div class="error">
                <p>
                    <strong>Input Field Missing Values</strong>
                </p>
            </div>
        <?php
        }
        // header("Location:".$goback);
        break;
    case 'update':    // UPDATE PROFILE QUESTION
        if (!empty($profile_question) || !empty($sort_order) || !empty($maxcharacter)) {
            foreach ($profile_question as $key1 => $value1) {
                $key1;
                $value1 = stripslashes($value1);
                $languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where language_id=$key1 ");
                $language_name = $languages->language_name;
                if ($language_name == 'english') {
                    $tableName = "dsp_profile_setup";
                } else {
                    $tableName = "dsp_profile_setup_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                }
                $DSP_TABLE_NAME = $wpdb->prefix . $tableName;
                $wpdb->query($wpdb->prepare("UPDATE $DSP_TABLE_NAME SET question_name = %s,field_type_id = %d,sort_order = %d,max_length = %d,required = %s,display_status=%s WHERE profile_setup_id  = %d", $value1, $cmbfieldtype, $sort_order, $maxcharacter, $cmbrequired, $display_status, $profile_question_id));
            }
            // $wpdb->query("UPDATE $dsp_profile_setup_table SET question_name = '$profile_question',field_type_id = '$cmbfieldtype',sort_order = '$sort_order',max_length = '$maxcharacter',required = '$cmbrequired' WHERE profile_setup_id  = '$profile_question_id'");
        }
        // header("Location:".$goback);
        $http_referrer = remove_query_arg(array('Action', 'Id'));
        wp_safe_redirect($http_referrer);
        exit;
        ?>
        <div class="updated">
            <p>
                <strong><?php echo language_code('DSP_PROFILE_QUESTIONS_UPDATED'); ?></strong>
            </p>
        </div>
        <?php
        break;
}
if (isset($_GET['Action']) && $_GET['Action'] == "Del") {   // DELETE PROFILE QUESTION
    $qrylanguages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table  ");
    foreach ($qrylanguages as $langs) {
        $language_name = $langs->language_name;
        if ($language_name == 'english') {
            $tableName = "dsp_profile_setup";
            $tableName2 = "dsp_question_options";
        } else {
            $tableName = "dsp_profile_setup_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
            $tableName2 = "dsp_question_options_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
        }
        $DSP_TABLE_NAME = $wpdb->prefix . $tableName;
        $DSP_TABLE_NAME2 = $wpdb->prefix . $tableName2;
        $wpdb->query("DELETE FROM $DSP_TABLE_NAME WHERE profile_setup_id = '$profile_question_id'");
        $current_question_ids = $wpdb->get_results("SELECT `profile_setup_id` FROM $DSP_TABLE_NAME", ARRAY_N);
        $current_question_ids_result = array_reduce($current_question_ids, 'array_merge', array());
        $current_question_ids_result = implode(',', $current_question_ids_result);
        $wpdb->query("DELETE FROM $DSP_TABLE_NAME2 WHERE `question_id` NOT IN ($current_question_ids_result)"); // for backward compatibility
        $http_referrer = remove_query_arg(array('Action', 'Id'));
    }
    wp_safe_redirect($http_referrer);
    exit;

} // End if($_GET['Action']=="Del")
//##################################################################################################### //
?>
<!--<div class="profile_headind" ><?php echo language_code('DSP_TOOLS_PROFILE_SETUP'); ?></div>-->
<div style="height:8px;"></div>
<!-- ************************************* EXISTING PROFILE QUESTIONS LIST **************************************** -->
<div>
    <div id="general" class="postbox">
        <h3 class="hndle"><span><?php echo language_code('DSP_TOOLS_EXISTING_QUESTIONS'); ?></span></h3>
        <table cellpadding="6" cellspacing="0" border="0" style="padding-left:20px;">
            <tr>
                <select style="float: right;" id="change_profile_question_lang_table">
                    <?php
                    $languages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table");
                    foreach ($languages as $lang):
                        $lang_name = strtolower(trim(esc_sql(substr($lang->language_name, 0, 2))));
                        ?>
                        <option
                            value="<?php echo $lang_name; ?>" <?php if ($lang_name == $current_lang_table) echo 'selected="selected"';?>>
                            <?php echo $lang_name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </tr>
            <tr>
                <td><h4><?php echo language_code('DSP_TOOLS_NAME'); ?></h4></td>
                <td width="100px">&nbsp;</td>
                <td><h4><?php echo language_code('DSP_TOOLS_TYPE'); ?></h4></td>
                <td width="40px">&nbsp;</td>
                <td><h4><?php echo language_code('DSP_TOOLS_ORDER'); ?></h4></td>
                <td width="40px">&nbsp;</td>
                <td><h4><?php echo language_code('DSP_DISPLAY_STATUS'); ?></h4></td>
                <td width="40px">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <?php
            if ($current_lang_table != '' && $current_lang_table != 'en')
                $dsp_profile_setup_table_multilang = $dsp_profile_setup_table . '_' . $current_lang_table;
            else
                $dsp_profile_setup_table_multilang = $dsp_profile_setup_table;

            $myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table_multilang Order by sort_order");
            foreach ($myrows as $profile_questions) {
                $profile_que_id = $profile_questions->profile_setup_id;
                $profile_ques = stripslashes($profile_questions->question_name);
                $profile_ques_type_id = $profile_questions->field_type_id;
                $ques_sort_order = $profile_questions->sort_order;
                $show_on_advanced_search = $profile_questions->display_status;
                $profile_ques_type = $wpdb->get_row("SELECT * FROM $dsp_field_types_table WHERE field_id = $profile_ques_type_id");
                ?>
                <tr>
                    <?php /* ?><?php if($profile_ques_type->field_id==language_code('DSP_PROFILE_QUESTION_TYPE')) { ?>
                      <td><a href="<?php echo add_query_arg (array('pid' => 'tools_profile_option','ques_ids' => $profile_que_id), $request_url);?>"><?php echo $profile_ques; ?></a></td>
                      <?php } else { ?>
                      <td><?php echo $profile_ques; ?></td>
                      <?php } ?><?php */ ?>
                    <td>
                        <?php if ($profile_ques_type->field_name != "TextBox"): ?><a
                            href="<?php echo add_query_arg(array('pid' => 'tools_profile_option', 'ques_ids' => $profile_que_id), $request_url); ?>"><?php endif; ?>
                            <?php echo $profile_ques; ?>
                            <?php if ($profile_ques_type->field_name != "TextBox"): ?></a><?php endif; ?>
                    </td>
                    <td width="100px">&nbsp;</td>
                    <td><?php echo isset($profile_ques_type->field_name) ? stripslashes($profile_ques_type->field_name) : ''; ?></td>
                    <td width="100px">&nbsp;</td>
                    <td><?php echo $ques_sort_order; ?></td>
                    <td width="40px">&nbsp;</td>
                    <td><span><?php echo $show_on_advanced_search == 'Y' ? 'Yes' : 'No'; ?></span></td>
                    <td width="40px">&nbsp;</td>
                    <td>
                        <?php dsp_print_change_display_order_link('profile_setup_id', $profile_questions->profile_setup_id, 'sort_order', $profile_question_tables, 2); ?>
                        <span onclick="update_profile_question(<?php echo $profile_que_id ?>);"
                              class="span_pointer"><?php echo language_code('DSP_EDIT'); ?></span> /
                        <span onclick="delete_profile_question(<?php echo $profile_que_id ?>);"
                              class="span_pointer"><?php echo language_code('DSP_DELETE'); ?></span>
                    </td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td height="50px;">&nbsp;</td>
            </tr>
        </table>
    </div>
</div>
<!-- ************************************************************************************************************************* -->
<!-- ****************************************  ADD NEW PROFILE QUESTIONS ***************************************************** -->
<div class="profile_headind"><?php echo language_code('DSP_TOOLS_ADD_NEW_PROFILE_QUESTIONS'); ?></div>
<div style="height:8px;"></div>
<div>
    <?php
    if (isset($_GET['Action']) && $_GET['Action'] == 'update') {
        $mode = 'update';
        $profile_setup_id = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : '';
    } else {
        $profile_setup_id = 0;
        $mode = 'add';
    }

    // get all the language stored in table
    $all_languages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table  ");
    ?>
    <form name="frmaddquestions" method="post">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="widefat">
            <tr>
                <th scope="col"><?php echo language_code('DSP_TOOLS_QUESTION'); ?></th>
                <th scope="col"><?php echo language_code('DSP_TOOLS_TYPE'); ?></th>
                <th scope="col"><?php echo language_code('DSP_TOOLS_ORDER'); ?></th>
                <th scope="col"><?php echo language_code('DSP_TOOLS_MAX_CHAR'); ?></th>
                <th scope="col"><?php echo language_code('DSP_TOOLS_REQUIRED'); ?></th>
                <th scope="col"><?php echo language_code('DSP_DISPLAY_STATUS'); ?></th>
            </tr>
            <tr>
                <td class="dsp_admin_headings2">
                    <table>
                        <?php
                        foreach ($all_languages as $lang) {
                            $add_code_language_id = $lang->language_id;
                            $imagePath = get_bloginfo('url') . '/wp-content/uploads/flags/' . $lang->flag_image;
                            if ($add_code_language_id == 1 && !file_exists($imagePath)) {
                                $locations = array(
                                    'src' => WP_DSP_ABSPATH . '/images/',
                                    'dest' => ABSPATH . '/wp-content/uploads/flags/'
                                );
                                do_action('dsp_copy_images', $lang->flag_image, $locations);
                            }
                            ?>
                            <tr>
                                <td style="border-bottom:none;"><img width="24" height="24"
                                                                     src="<?php echo $imagePath; ?>"
                                                                     alt="<?php echo $lang->flag_image; ?>"/></td>
                                <td style="border-bottom:none; font-size:15px; font-weight:normal;"><?php echo ucfirst($lang->language_name); ?></td>
                                <td style="border-bottom:none;">
                                    <?php
                                    $alllanguages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where language_name= '$lang->language_name' ");


                                    $language_name = $alllanguages->language_name;

                                    if ($language_name == 'english') {
                                        $tableName = "dsp_profile_setup";
                                    } else {
                                        $tableName = "dsp_profile_setup_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                                    }
                                    $tableName;
                                    $DSP_TABLE_NAME = $wpdb->prefix . $tableName;
                                    $dsp_updates = $wpdb->get_row("SELECT * FROM $DSP_TABLE_NAME WHERE profile_setup_id = $profile_setup_id");

                                    ?>
                                    <input type="text" id="profile_question_<?php echo $add_code_language_id; ?>"
                                           name="profile_question[<?php echo $add_code_language_id; ?>]"
                                           class="regular-text" value="<?php echo @$dsp_updates->question_name; ?>"/>


                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </td>
                <td class="dsp_admin_headings2">
                    <select name="cmbfieldtype">
                        <option value="">Select Type</option>
                        <?php
                        $dsp_fields = $wpdb->get_results("SELECT * FROM $dsp_field_types_table Order by field_id");
                        foreach ($dsp_fields as $fieldtype) {
                            if ($dsp_updates->field_type_id == $fieldtype->field_id) {
                                ?>
                                <option value="<?php echo $fieldtype->field_id; ?>"
                                        selected="selected"><?php echo $fieldtype->field_name; ?></option>
                            <?php } else { ?>
                                <option
                                    value="<?php echo $fieldtype->field_id; ?>"><?php echo $fieldtype->field_name; ?></option>
                            <?php
                            }
                        }
                        ?>
                    </select>
                </td>
                <td class="dsp_admin_headings2">
                    <input type="text" name="sort_order" size="1" value="<?php echo @$dsp_updates->sort_order; ?>"/>
                </td>
                <td class="dsp_admin_headings2">
                    <input type="text" name="maxcharacter" size="1" value="<?php echo @$dsp_updates->max_length; ?>"/>
                </td>
                <td class="dsp_admin_headings2">
                    <select name="cmbrequired">
                        <?php
                        if (@$dsp_updates->required == 'Y') {
                            ?>
                            <option value="Y" selected="selected">Yes</option>
                            <option value="N">No</option>
                        <?php
                        } else {
                            ?>
                            <option value="Y">Yes</option>
                            <option value="N" selected="selected">No</option>
                        <?php } ?>
                    </select>
                </td>
                <td class="dsp_admin_headings2">
                    <select name="cmbdisplay_status">
                        <?php
                        if (@$dsp_updates->display_status == 'Y') {
                            ?>
                            <option value="Y" selected="selected">Yes</option>
                            <option value="N">No</option>
                        <?php
                        } else {
                            ?>
                            <option value="Y">Yes</option>
                            <option value="N" selected="selected">No</option>
                        <?php } ?>
                    </select>
                </td>
                <input type="hidden" name="mode" value="<?php echo $mode ?>"/>
                <td><input type="button" name="submit1" class="button"
                           value="<?php echo language_code('DSP_ADD_PROFILE_QUESTION_BUTTON'); ?>"
                           onclick="add_profile_question();"/></td>
            </tr>
        </table>
    </form>
</div>
<div style="height:20px;"></div>
<?php //***********************************************************************************************     ?>
<br/>
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>

<script>
    jQuery(document).ready(function () {
        jQuery('#change_profile_question_lang_table').change(function () {
            var current_option = jQuery(this).val();
            window.location = window.location.href + '&table_id=' + current_option;
        });
    });
</script>
    