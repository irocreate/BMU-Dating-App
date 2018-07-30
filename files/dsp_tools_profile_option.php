<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ############################################   UPDATE PROFILE QUESTIONS   ################################################# //
global $wpdb;

$dsp_profile_setup_table = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
$dsp_profile_question_option_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;
$dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;

if (isset($_GET['table_id']))
    $current_lang_table = filter_var(strip_tags($_GET['table_id']), FILTER_SANITIZE_STRING);
else
    $current_lang_table = '';

if (isset($_SERVER['HTTP_REFERER']))
    $goback = $_SERVER['HTTP_REFERER'];

$dsp_action = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$profile_question = isset($_REQUEST['profle_question_id']) ? $_REQUEST['profle_question_id'] : '';

//$option_value = isset($_REQUEST['option_value']) ? $_REQUEST['option_value'] : '';

$profile_question_option_tables = array(); // to store all the language profile setup tables names
$languages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table");
foreach ($languages as $key => $language) {
    $lang_name = $language->language_name;
    if ($lang_name == 'english') {
        $tblName = "dsp_question_options";
    } else {
        $tblName = "dsp_question_options_" . strtolower(trim(esc_sql(substr($lang_name, 0, 2))));
    }
    $DSP_TBL_NAME = $wpdb->prefix . $tblName;
    array_push($profile_question_option_tables, $DSP_TBL_NAME);
}
$profile_question_option_tables = implode('+', $profile_question_option_tables);

$last_order_value = 0;  // highest last order in all the tables // backward compatibility
$highest_question_option_id_value = 0;  // highest question_option_id_value in all the tables // backward compatibility
if( isset($_REQUEST['option_value']) && (array_filter($_REQUEST['option_value'])) )
{
    $option_value = $_REQUEST['option_value'];
    // fill empty option fields by a filled field in descending priority i,e, english then chinese etc...
    foreach ($option_value as $opt_val) {
        if ($opt_val != null) {
            $default_option = stripslashes($opt_val);
            break;
        }
    }
    
    $languages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table");
    foreach ($languages as $key => $lang) 
    {
        $language_name = $lang->language_name;
        if ($language_name == 'english') {
            $tableName = "dsp_question_options";
            $profile_question_id = isset($_REQUEST['profle_question_id']) ? $_REQUEST['profle_question_id'] : '';
        } else {
            $index = "profle_question_id_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
            $tableName = "dsp_question_options_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
            $profile_question_id = isset($_REQUEST[$index]) ? $_REQUEST[$index] : '';
        }

        $DSP_TABLE_NAME = $wpdb->prefix . $tableName;

        $temp_order_value = $wpdb->get_row("SELECT `sort_order` FROM $DSP_TABLE_NAME WHERE `question_id`=$profile_question_id ORDER BY `sort_order` DESC");
        if($temp_order_value->sort_order > $last_order_value)
        {
            $last_order_value = $temp_order_value->sort_order;
        }
        
        $temp_question_option_id_value = $wpdb->get_row("SELECT `question_option_id` FROM $DSP_TABLE_NAME ORDER BY `question_option_id` DESC");
        if($temp_question_option_id_value->question_option_id > $highest_question_option_id_value)
        {
            $highest_question_option_id_value = $temp_question_option_id_value->question_option_id;
        }
    }
    $last_order_value = $last_order_value + 1;
    $highest_question_option_id_value = $highest_question_option_id_value + 1;
}
else
{
    $option_value = array();
}

$option_sort_order = (isset($_REQUEST['option_sort_order']) && $_REQUEST['option_sort_order']!='') ? $_REQUEST['option_sort_order'] : $last_order_value;

$cmbrequired = isset($_REQUEST['cmbrequired']) ? $_REQUEST['cmbrequired'] : '';

$update_option_id = isset($_REQUEST['opt_Id']) ? $_REQUEST['opt_Id'] : '';

switch ($dsp_action) {

    case 'add':    // ADD QUESTION OPTION
        if(!empty($option_value))
        {    
            foreach ($option_value as $key1 => $value1) 
            { 
                $val = stripslashes($value1);
                if( empty($val) )
                {
                    $val = $default_option;
                }
                $languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where language_id=$key1 ");
                $language_name = $languages->language_name;

                if ($language_name == 'english') {
                   $tableName = "dsp_question_options";
                   $profile_question = isset($_REQUEST['profle_question_id']) ? $_REQUEST['profle_question_id'] : '';
                } else {
                    $index = "profle_question_id_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                    $tableName = "dsp_question_options_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                    $profile_question = isset($_REQUEST[$index]) ? $_REQUEST[$index] : '';
                }

                $tableName;
                $DSP_TABLE_NAME = $wpdb->prefix . $tableName;
                //print_r($wpdb->prepare("INSERT INTO $DSP_TABLE_NAME (question_option_id,question_id, option_value, sort_order,required) VALUES(%d,%d,%s,%d,%s)",$highest_question_option_id_value,$profile_question,$val,$option_sort_order,$cmbrequired));echo '<br>';
                $wpdb->query($wpdb->prepare("INSERT INTO $DSP_TABLE_NAME (question_option_id,question_id, option_value, sort_order,required) VALUES(%d,%d,%s,%d,%s)",$highest_question_option_id_value,$profile_question,$val,$option_sort_order,$cmbrequired));
            }
            //$wpdb->query("INSERT INTO $dsp_profile_question_option_table (question_id, option_value, sort_order,required) VALUES('".$profile_question."','".$option_value."','".$option_sort_order."','".$cmbrequired."')"); 
            // header("Location:".$goback);
            echo "Question Option added! <a href='$goback'>Click here</a> to View List";
            exit();
            break;
        }
        else
        {
            echo '<span class="error_msg">Input Field Missing Values</span>';
        }
    case 'update':    // UPDATE PROFILE OPTION
        
        if (!empty($option_value) || !empty($option_sort_order)) {
            foreach ($option_value as $key1 => $value1) {
                $key1;
                $val = stripslashes($value1);

                $languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where language_id=$key1 ");
                $language_name = $languages->language_name;

                if ($language_name == 'english') {
                   $tableName = "dsp_question_options";
                   $profile_question = isset($_REQUEST['profle_question_id']) ? $_REQUEST['profle_question_id'] : '';
                } else {
                    $index = "profle_question_id_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                    $tableName = "dsp_question_options_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                    $profile_question = isset($_REQUEST["$index"]) ? $_REQUEST["$index"] : '';
                }
                $tableName;
                $DSP_TABLE_NAME = $wpdb->prefix . $tableName;
                $wpdb->query($wpdb->prepare("UPDATE $DSP_TABLE_NAME SET option_value = %s,sort_order = %d,required = %s  WHERE question_option_id  = %d",$val,$option_sort_order,$cmbrequired,$update_option_id));
            }

            //echo $wpdb->update( $dsp_spam_words_table, array( 'spam_word' => $dsp_spamwords), array( 'spam_word_id' => $spam_word_id ), array( '%s'), array( '%d' ) ); 
        }
        $sendback = remove_query_arg(array('Action', 'opt_Id'), $goback);
        echo "Option updated! <a href='$sendback'>Click here</a> to View List";
        exit();
        break;
}



if (isset($_GET['Action']) && $_GET['Action'] == "Del") {   // DELETE PROFILE OPTION
    $qrylanguages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table  ");
    foreach ($qrylanguages as $langs) {
        $language_name = $langs->language_name;

        if ($language_name == 'english') {
            $tableName = "dsp_question_options";
        } else {
            $tableName = "dsp_question_options_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
        }
        $DSP_TABLE_NAME = $wpdb->prefix . $tableName;
        $wpdb->query("DELETE FROM $DSP_TABLE_NAME WHERE question_option_id = '$update_option_id'");
    }
    $http_referrer = remove_query_arg( array('Action', 'opt_Id') );wp_safe_redirect($http_referrer);exit;
}
// ########################################################################################################################### //
?>
<div class="profile_headind"><?php echo language_code('DSP_TOOLS_PROFILE_SETUP'); ?></div>
<div style="height:8px;"></div>
<!-- **************************************** EXISTING PROFILE QUESTIONS LIST***************************************************** -->
<!-- **************************************** EXISTING PROFILE QUESTIONS LIST***************************************************** -->
<div>
    <div id="general" class="postbox">
        <h3 class="hndle"><span><?php echo "Existing Options:"; ?></span></h3>
        <table cellpadding="6" cellspacing="0" border="0" style="padding-left:20px;">
            <tr>
                <select style="float: right;" id="change_profile_question_lang_table">
                    <?php
                    $languages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table");
                    foreach ($languages as $lang):
                        $lang_name = strtolower(trim(esc_sql(substr($lang->language_name, 0, 2))));
                        ?>
                        <option value="<?php echo $lang_name; ?>" <?php if ($lang_name == $current_lang_table) echo 'selected="selected"'; ?>>
                            <?php echo $lang_name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </tr>  
            <tr>
                <td><h4><?php echo language_code('DSP_TOOLS_NAME'); ?></h4></td>
                <td width="100px">&nbsp;</td>
                <td align="left"><h4><?php echo language_code('DSP_TOOLS_ORDER'); ?></h4></td>
                <td width="40px">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <?php
            
            if($current_lang_table != '' && $current_lang_table!='en')
                $dsp_profile_options_table_multilang = $dsp_profile_question_option_table . '_' . $current_lang_table;
            else
                $dsp_profile_options_table_multilang = $dsp_profile_question_option_table;
            
            $get_question_id = isset($_REQUEST['ques_ids']) ? $_REQUEST['ques_ids'] : '';
            $myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_options_table_multilang WHERE question_id=$get_question_id Order by sort_order");
            foreach ($myrows as $profile_questions_options) {
                $profile_option_id = $profile_questions_options->question_option_id;
                $ques_option_value = stripslashes($profile_questions_options->option_value);
                $option_sort_order = $profile_questions_options->sort_order;
                ?>
                <tr>
                    <td><?php echo $ques_option_value; ?></td>
                    <td width="100px">&nbsp;</td>
                    <td align="left"><?php echo $option_sort_order; ?></td>
                    <td width="40px" >&nbsp;</td>
                    <td>
                        <?php dsp_print_change_display_order_link('question_option_id',$profile_option_id,'sort_order',$profile_question_option_tables,2,'question_id',$profile_questions_options->question_id); ?>
                        <span onclick="update_profile_question_option(<?php echo $profile_option_id ?>);" class="span_pointer"><?php echo language_code('DSP_EDIT'); ?></span> /
                        <span onclick="delete_profile_question_option(<?php echo $profile_option_id ?>);" class="span_pointer"><?php echo language_code('DSP_DELETE'); ?></span>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr><td height="10px;">&nbsp;</td></tr>
        </table>
    </div>
</div>
<!-- ************************************************************************************************************************* -->
<!-- ****************************************  ADD NEW PROFILE QUESTIONS OPTION ***************************************************** -->
<div class="profile_headind"><?php echo language_code('DSP_TOOLS_ADD_NEW_PROFILE_QUESTIONS_OPTION'); ?></div>
<div style="height:8px;"></div>
<div>
    <?php
    if (isset($_GET['Action']) && $_GET['Action'] == 'update') {
        $mode = 'update';
        $profile_option_id = array_key_exists('opt_Id',$_GET)?$_GET['opt_Id']: '';
    } else {
        $profile_option_id = 0;
        $mode = 'add';
    }

    ?>
    <FORM  name="frmaddoptions" method="post">
        <table cellpadding="0" cellspacing="0" border="0"  class="widefat">
            <tr>
                <th scope="col">&nbsp;</th>
                <th scope="col"><?php echo language_code('DSP_TOOLS_QUESTION'); ?></th>
                <th scope="col"><?php echo language_code('DSP_TOOLS_OPTION_VALUE'); ?></th>
                <th scope="col"><?php echo language_code('DSP_TOOLS_ORDER'); ?></th>
                <th scope="col"><?php echo language_code('DSP_TOOLS_REQUIRED'); ?></th>
            </tr>
            <tr>
                <td colspan="3">
                    <table width="100%" border="0">
                        <?php
                        // get all the language stored in table
                        $all_languages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table  ");
                        foreach ($all_languages as $lang) {

                            $add_code_language_id = $lang->language_id;
                            $imagePath = get_bloginfo('url') . '/wp-content/uploads/flags/' . $lang->flag_image;
                            ?>
                            <tr>
                                <td width="35"><img height="24" src="<?php echo $imagePath; ?>" alt="<?php echo $lang->flag_image;?>"/></td>
                                <td class="dsp_admin_headings2">
                                    <?php
                                    $alllanguages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where language_name= '$lang->language_name' ");


                                    $language_name = $alllanguages->language_name;
                                    if ($language_name == 'english') {
                                        $tableName = "dsp_profile_setup";
                                        $profle_question_id = "profle_question_id";
                                    } else {
                                        $tableName = "dsp_profile_setup_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                                        $profle_question_id = "profle_question_id_".strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                                    }
                                    $DSP_TABLE_NAME = $wpdb->prefix . $tableName;
                                    
                                    ?>
                                    <select name="<?php echo $profle_question_id; ?>">
                                        <option value="">Select Profile Question</option>
                                        <?php
                                        $myrows = $wpdb->get_results("SELECT * FROM $DSP_TABLE_NAME WHERE profile_setup_id=$get_question_id Order by sort_order");
                                        foreach ($myrows as $profile_questions) {
                                            $profile_que_id = $profile_questions->profile_setup_id;
                                            $profile_ques = stripslashes($profile_questions->question_name);
                                            ?>
                                            <option value="<?php echo $profile_que_id; ?>" selected="selected"><?php echo $profile_ques; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="dsp_admin_headings2"> 
                                    <?php
                                    if ($language_name == 'english') {
                                        $tableName = "dsp_question_options";
                                    } else {
                                        $tableName = "dsp_question_options_" . strtolower(trim(esc_sql(substr($language_name, 0, 2))));
                                    }
                                    $tableName;
                                    $DSP_TABLE_NAME = $wpdb->prefix . $tableName;
                                    if(isset($profile_option_id) && !empty($profile_option_id)){
                                        $dsp_updates = $wpdb->get_row("SELECT * FROM $DSP_TABLE_NAME WHERE question_option_id = $profile_option_id");
                                    } 
                                    ?>
                                    <input type="text" name="option_value[<?php echo $add_code_language_id; ?>]" id="option_value_<?php echo $add_code_language_id; ?>" value="<?php if (isset($_REQUEST['Action']) && $_REQUEST['Action'] == 'update') { if(!empty($dsp_updates)) echo stripslashes($dsp_updates->option_value);}?>"/>   
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </td>
                <td class="dsp_admin_headings2">
                    <input type="text" name="option_sort_order" size="5" value="<?php if (isset($_REQUEST['Action']) && $_REQUEST['Action'] == 'update') {if(isset($dsp_updates) && !empty($dsp_updates)) echo $dsp_updates->sort_order;}?>"/>
                </td>
                <td class="dsp_admin_headings2"> 
                    <select name="cmbrequired">
                        <?php
                        if ($dsp_updates->required == 'Y') {
                            ?>
                            <option value="Y" selected="selected">Yes</option>
                            <option value="N">No</option>
                            <?php
                        } else {
                            ?>
                            <option value="Y">Yes</option>
                            <option value="N"  selected="selected">No</option>
                        <?php } ?>
                    </select>
                </td>
                <td width="40px"><input type="hidden" name="mode" value="<?php echo $mode ?>" /></td>
                <td><input type="button" name="submit1" class="button" value="<?php echo language_code('DSP_ADD_PROFILE_QUESTION_OPTION_BUTTON'); ?>" onclick="add_profile_question_option();"/></td>
            </tr>
        </table>
    </FORM>
</div>
<!-- *************************************************************************************************************************** -->
<div style="height:20px;"></div>
<div>
    <table cellpadding="0" cellspacing="0">
        <tr><td>
                <div id='location_div'>
                </div>
            </td></tr>
    </table>
</div>

<script>
    jQuery(document).ready(function(){
       jQuery('#change_profile_question_lang_table').change(function(){
           var current_option = jQuery(this).val();
           window.location = window.location.href + '&table_id=' + current_option;
       }); 
    });
</script>