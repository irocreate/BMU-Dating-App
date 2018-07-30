<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
?>
<script type="text/javascript">
    function checkconfirm()
    {
        var cnf = confirm('<?php echo language_code('DSP_ARE_YOU_SURE_TO_DELETE_IT'); ?>');
        if (cnf)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    function validation(row)
    {
        var CodePattern = /^[A-Z._-]{5,50}$/;
        //if( !CodePattern.test(document.addcode.code_name.value))
        if (!CodePattern.test(document.getElementById('code_name' + '_' + row).value))
        {
            alert("Enter valid Code");
            document.getElementById('code_name' + '_' + row).focus();
            return false;
        }


        if (document.getElementById('text_name' + '_' + row).value == '')
        {
            alert("Enter valid text");
            document.getElementById('text_name' + '_' + row).focus();
            return false;
        }
        return true;

    }
    function checkValidEditLang()
    {
        if (document.getElementById('txt_language_name').value == '')
        {
            alert("<?php echo language_code('DSP_PLEASE_LANGUAGE_NAME'); ?>");
            return false;
        }

        return true;
    }
    function checkvalidSearch()
    {

        if (document.getElementById('select_language').value == 0)
        {
            alert("<?php echo language_code('DSP_PLEASE_SELECT_LANGUAGE'); ?>");
            return false;
        }
        if (document.getElementById('search_text_name').value == '')
        {
            alert("<?php echo language_code('DSP_PLEASE_ENTER_CODE'); ?>");
            return false;
        }

        return true;
    }
    function checkValidLang()
    {

        if (document.getElementById('txt_language_name').value == '')
        {
            alert("<?php echo language_code('DSP_PLEASE_LANGUAGE_NAME'); ?>");
            return false;
        }
        if (document.getElementById('default_image').value == "")
        {

            alert("<?php echo language_code('DSP_PLEASE_SELECT_IMAGE'); ?>");
            return false;
        }
        if (document.getElementById('language_file').value == "")
        {

            alert("<?php echo language_code('DSP_PLEASE_SELECT_LANGUAGE_FILE'); ?>");
            return false;
        }
        return true;
    }
</script>
<style type="text/css">
    .div {
        position:relative; 
        width: 180px;
        padding: 10px;
        display: none;
    }
</style>
<?php
global $wpdb;
$dsp_language_table = $wpdb->prefix . DSP_LANGUAGE_TABLE;
$dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;

$request_url = $_SERVER['REQUEST_URI'];
$invalidEntry = 0;
$mode = "";
if (isset($_REQUEST['mode'])) {
    $mode = $_REQUEST['mode'];
    $editLanguageId = $_REQUEST['editLangId'];
}
include_once("dsp_upload_image_with_GD.php"); // include file for image upload
$root_link = $_SERVER['PHP_SELF'] . '?page=dsp-admin-sub-page3&pid=tools_language';
//--------------ADD LANGUAGE AND UPDATE LANGUAGE--------------------------------------------
if (isset($_REQUEST['add_lang']) && $_REQUEST['add_lang'] && $invalidEntry == 0) {
//echo '<br>add';
    if ($mode == "add") {
//echo '<br>add mode';	
        // create a table with the lang name

        $tableName = "dsp_language_" . substr($_POST['txt_language_name'], 0, 2);
        $DSP_TABLE_NAME = $wpdb->prefix . $tableName;
        $flag_image = $default_image;
        $chaSet = $_POST['txt_charset'];

        // check condition if table exists
        if ($wpdb->get_var("show tables like '$DSP_TABLE_NAME'") != $DSP_TABLE_NAME) {
//echo '<br>table name'.$DSP_TABLE_NAME;
            $wpdb->query("CREATE TABLE $DSP_TABLE_NAME (code_id int(11) NOT NULL AUTO_INCREMENT,code_name varchar(256) NOT NULL, text_name varchar(256) NOT NULL, PRIMARY KEY (code_id))");

            // check condition if table exists
            $DSP_PROFILE_SETUP_TABLE = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
            $profiletable = "dsp_profile_setup_" . substr($_POST['txt_language_name'], 0, 2);
            $DSP_PROFILE_TABLE_NAME = $wpdb->prefix . $profiletable;

            $dsp_profile_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;
            $profilequestiontable = "dsp_question_options_" . substr($_POST['txt_language_name'], 0, 2);
            $DSP_PROFILE_QUES_TABLE_NAME = $wpdb->prefix . $profilequestiontable;

            $dsp_flirt_text_table = $wpdb->prefix . DSP_FLIRT_TEXT_TABLE;
            $flirttable = "dsp_flirt_" . substr($_POST['txt_language_name'], 0, 2);
            $DSP_FLIRT_TABLE_NAME = $wpdb->prefix . $flirttable;

            //		echo '<br>table name'; 
            if ($wpdb->get_var("show tables like '$DSP_PROFILE_TABLE_NAME'") != $DSP_PROFILE_TABLE_NAME) {//echo '<br>profile table name'.$DSP_PROFILE_TABLE_NAME;
                $wpdb->query("CREATE TABLE $DSP_PROFILE_TABLE_NAME AS (SELECT * FROM $DSP_PROFILE_SETUP_TABLE )");
            }

            if ($wpdb->get_var("show tables like '$DSP_PROFILE_QUES_TABLE_NAME'") != $DSP_PROFILE_QUES_TABLE_NAME) { //echo '<br>profile ques table name'.$DSP_PROFILE_QUES_TABLE_NAME;
                $wpdb->query("CREATE TABLE $DSP_PROFILE_QUES_TABLE_NAME AS (SELECT * FROM $dsp_profile_question_options_table )");
            }

            if ($wpdb->get_var("show tables like '$DSP_FLIRT_TABLE_NAME'") != $DSP_FLIRT_TABLE_NAME) {
                //echo '<br>profile flirt table name'.$DSP_FLIRT_TABLE_NAME;
                $wpdb->query("CREATE TABLE $DSP_FLIRT_TABLE_NAME AS (SELECT * FROM $dsp_flirt_text_table )");
            }
            //echo '<br>tables created';
            // INSERT INTO TABLE
            $insertDetailQuery = "INSERT INTO $dsp_language_detail_table SET language_name='$language_name',display_status='0',flag_image='$flag_image', table_name = '$tableName',charset='$chaSet'";
            $wpdb->query($insertDetailQuery);
            //echo $insertDetailQuery.'<br>';


            $tmpname = $_FILES['language_file']['tmp_name'];


            //echo '<br>name='.$name.'tmp '.$tmpname;
            //$path=get_bloginfo('url').'/wp-content/plugins/dsp_dating/files/'.'lang.csv';
            //echo $path;
            if ($tmpname) {
                $fp = fopen($tmpname, 'r') or die("can't open file");
                //	print "<br><table>\n";
                while ($csv_line = fgetcsv($fp, 1024)) {
                    //    print '<tr>';
                    /* for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
                      print '<td>'.$csv_line[$i].'</td>';
                      } */
                    $insertLangQuery = "INSERT INTO $DSP_TABLE_NAME SET code_name='" . ltrim($csv_line[1], ' ') . "',text_name='" . ltrim($csv_line[2], ' ') . "'";
                    $wpdb->query($insertLangQuery);
                    //	echo $insertLangQuery;
                    //    print "</tr>\n";
                }
                //print '</table>\n';
                fclose($fp) or die("can't close file");
            }
        }
    } else if ($mode == "update") {


        $charSet = isset($_REQUEST['txt_charset']) ? $_REQUEST['txt_charset'] : '';
        $language_name = isset($_REQUEST['txt_language_name']) ? $_REQUEST['txt_language_name'] : '';

        if ($tmpname) { // user has change the csv file 
            //get the previous table
            $prevTableName = $wpdb->get_var("SELECT table_name FROM $dsp_language_detail_table WHERE  language_id='$editLanguageId'");

            $prevTableName = $wpdb->prefix . $prevTableName;

            // delete the previous table 
            $deleteLanguageTableQuery = "DROP TABLE IF EXISTS $prevTableName";
            $wpdb->query($deleteLanguageTableQuery);

            // CREATE NEW TABLE
            $newTable = "dsp_language_" . substr($_POST['txt_language_name'], 0, 2);

            $newTableName = $wpdb->prefix . $newTable;

            // check condition if table exists
            if ($wpdb->get_var("show tables like '$newTableName'") != $newTableName) {
                $wpdb->query("CREATE TABLE $newTableName (code_id int(11) NOT NULL AUTO_INCREMENT,code_name varchar(256) NOT NULL, text_name varchar(256) NOT NULL, PRIMARY KEY (code_id))");

                // INSERT INTO TABLE
                $updatetDetailQuery = "UPDATE  $dsp_language_detail_table SET language_name='$language_name', table_name = '$newTable',charset='$charSet' WHERE language_id='$editLanguageId'";
                $wpdb->query($updatetDetailQuery);
                //echo $updatetDetailQuery.'<br>';


                $fp = fopen($tmpname, 'r') or die("can't open file");
                //	print "<br><table>\n";
                while ($csv_line = fgetcsv($fp, 1024)) {

                    $insertLangQuery = "INSERT INTO $newTableName SET code_name='$csv_line[0]',text_name='$csv_line[1]'";
                    $wpdb->query($insertLangQuery);
                    //	echo $insertLangQuery;
                    //    print "</tr>\n";
                }
                //print '</table>\n';
                fclose($fp) or die("can't close file");
            }
        } else { // user don't want to change the previous table //just need to change the lang name char set or flag
            $updatetDetailQuery = "UPDATE  $dsp_language_detail_table SET language_name='$language_name', charset='$charSet' WHERE language_id='$editLanguageId'";
            $wpdb->query($updatetDetailQuery);
            //echo $updatetDetailQuery;
        }
        ?>
        <script>location.href = "<?php echo $root_link; ?>"</script>
        <?php
    }
}
//--------------END OF ADD LANGUAGE AND UPDATE LANGUAGE--------------------------------------------
//----------------------------DELETE AND EDIT LANGUAGE--------------------------------------------------
if (isset($_REQUEST['Action']) && $_REQUEST['Action'] == "edit" && isset($_REQUEST['lId']) && $_REQUEST['lId'] != '') {
    $langId = $_REQUEST['lId'];

    $getLangDetailQuery = "SELECT * FROM $dsp_language_detail_table WHERE language_id='$langId'";
    $getLangDetails = $wpdb->get_row($getLangDetailQuery);

    $langName = $getLangDetails->language_name;
    $flagImage = $getLangDetails->flag_image;
    $charSet = $getLangDetails->charset;
    $editLangId = $getLangDetails->language_id;
    $mode = "update";
} else {
    $mode = "add";
}
if (isset($_REQUEST['Action']) && $_REQUEST['Action'] == "delete" && isset($_REQUEST['lId']) && $_REQUEST['lId'] != '') {
    $langId = $_REQUEST['lId'];

    $count = $wpdb->get_var("SELECT count(*) FROM  $dsp_language_detail_table");
    if ($count == 1) {
        echo "";
    } else {
        $deleteRowDetail = $wpdb->get_row("SELECT table_name,flag_image,language_name FROM $dsp_language_detail_table WHERE language_id='$langId'");
        $deleteImagePath = get_bloginfo('url') . '/wp-content/uploads/flags/' . $deleteRowDetail->flag_image;
        $poData = dsp_get_po_data($langId);
        $deletePoPath = PO_PATH . $poData['language_name'];
        $tableName = $wpdb->prefix . $deleteRowDetail->table_name;

        $profiletable = "dsp_profile_setup_" . substr($deleteRowDetail->language_name, 0, 2);
        $DSP_PROFILE_TABLE_NAME = $wpdb->prefix . $profiletable;

        $profilequestiontable = "dsp_question_options_" . substr($deleteRowDetail->language_name, 0, 2);
        $DSP_PROFILE_QUES_TABLE_NAME = $wpdb->prefix . $profilequestiontable;

        $flirttable = "dsp_flirt_" . substr($deleteRowDetail->language_name, 0, 2);
        $DSP_FLIRT_TABLE_NAME = $wpdb->prefix . $flirttable;

        // delete the whole profile setup table of this language
        $deleteProfileTableQuery = "DROP TABLE IF EXISTS $DSP_PROFILE_TABLE_NAME";
        $wpdb->query($deleteProfileTableQuery);

        // delete the whole profile question option  table of this language
        $deleteProfileQuesTableQuery = "DROP TABLE IF EXISTS $DSP_PROFILE_QUES_TABLE_NAME";
        $wpdb->query($deleteProfileQuesTableQuery);


        // delete the whole flirt table of this language
        $deleteFlirtTableQuery = "DROP TABLE IF EXISTS $DSP_FLIRT_TABLE_NAME";
        $wpdb->query($deleteFlirtTableQuery);



        // delete from detail lang table
        $deleteQuery = "DELETE FROM $dsp_language_detail_table WHERE language_id='$langId'";
        $wpdb->query($deleteQuery);

        //echo '<br>'.$deleteQuery;
        // delete the whole table of this language
        $deleteLanguageTableQuery = "DROP TABLE IF EXISTS $tableName";
        $wpdb->query($deleteLanguageTableQuery);

        //echo '<br>'.$deleteLanguageTableQuery;
        // delete the flag image
        unlink($deleteImagePath);
        if(file_exists($deletePoPath)){ // removing po file & folder
            unlink($poData['file_path']);
            rmdir($deletePoPath);
            
        }
    }
    ?>
    <script>location.href = "<?php echo $root_link; ?>"</script>
    <?php
}
//----------------------------END OF DELETE AND EDIT LANGUAGE--------------------------------------------------
// get all the language stored in table
$all_languages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table  ");
?>
<div>
    <div id="general" class="postbox">
        <h3 class="hndle"><span><?php echo language_code('DSP_TOOLS_LANGUAGE_ADMIN'); ?></span></h3>
        <br />
        <div class="dsp_thumbnails3" >
            <div style="width:421px;">
                <form name="search" method="post"  action="" onsubmit="return checkvalidSearch();">
                    <div style="float:none;" >
                        <input style="float:left; margin-right:20px;" name="text_name" type="text" id="search_text_name" />
                        <input name="search" type="submit" value="<?php echo language_code('DSP_SEARCH_BUTTON'); ?>" class="button" />
                    </div>
                </form>
            </div>
            <div>
                <div style="height:20px;"></div>
                <?php
                if (isset($_REQUEST['search'])) {
                    $text_name = isset($_REQUEST['text_name']) ? trim($_REQUEST['text_name']) : '';
                    $selected_lang_id = isset($_REQUEST['select_language']) ? $_REQUEST['select_language'] : '';

                    // get the language table name from language id

                    $edit_language_table = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table ");
                    foreach ($edit_language_table as $language_table) {
                        $language_name = $language_table->language_name;
                        $imagePath = get_bloginfo('url') . '/uploads/flags/' . $language_table->flag_image;

                        $table_name = $language_table->table_name;
                        $DSP_TABLE_NAME = $wpdb->prefix . $language_table->table_name;

                        $language_table = $wpdb->get_results("SELECT * FROM $DSP_TABLE_NAME WHERE text_name like '%$text_name%'");
                        ?> 
                        <form name='abc' method="post" action="" >
                            <table border="0" style="width:995px;">
                                <tr style="line-height: 10px;">
                                    <td valign="bottom" style="width:30px;"><img height="24" src="<?php echo $imagePath; ?>" alt="<?php echo $language_table->flag_image;?>"/></td>
                                    <td style="width:70px;"><?php echo ucfirst($language_name); ?></td>
                                    <td class="dsp_language_txt"><?php echo language_code('DSP_TOOLS_LANGUAGE_CODE_NAME'); ?></td>
                                    <td class="dsp_language_txt1"><?php echo language_code('DSP_TOOLS_LANGUAGE_TEXT_NAME'); ?></td>
                                    <td style="width: 100px;">&nbsp;</td>
                                </tr>
                                <?php
                                foreach ($language_table as $language) {
                                    $code_name = $language->code_name;
                                    $text_name = $language->text_name;
                                    $code_id = $language->code_id;
                                    ?>
                                    <tr style="line-height: 10px; height:15px;">
                                        <td style=" font-size:10px;">&nbsp;</td>
                                        <td >&nbsp;</td>
                                        <td style=" font-size:10px;"><?php echo $code_name; ?></td>
                                        <td style="font-size:11px;"><?php echo $text_name ?></td>
                                        <td style="font-size:13px;">
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 'tools_language_edit',
                                                'code_id' => $code_id, 'text_name' => base64_encode($text_name),
                                                'mode' => 'edit', 'edit_lang' => $table_name), $request_url);
                                            ?>" style="text-decoration:none;" >
                                                <span class="code_edit"><?php echo "Edit"; ?></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </form>
                        <?php
                    }
                }
                ?>

            </div>
        </div>
        <br />
    </div>

    <div id="general" class="postbox">
        <h3 class="hndle"><span><?php echo language_code('DSP_ADD_LANGUAGE_TEXT') ?></span></h3>
        <br />
        <div class="dsp_thumbnails3" >
            <div style="width:615px;">
                <table width="100%" border="0">
                    <tr>
                        <td width="5%" style=" font-size:10px;">&nbsp;</td>
                        <td width="31%" style=" font-size:10px;">&nbsp;</td>
                        <td>Code Name</td>
                        <td>Text Name</td>
                        <td>&nbsp;</td>
                    </tr>
                    <?php
                    $i = 0;
                    foreach ($all_languages as $lang) {

                        $add_code_language_id = $lang->language_id;
                        $imagePath = get_bloginfo('url') . '/uploads/flags/' . $lang->flag_image;
                        ?>
                        <tr>
                            <td colspan="5">
                                <form name="addcode_<?php echo $i; ?>" method="post" action="" onsubmit="return validation('<?php echo $i; ?>');" >
                                    <table width="100%">
                                        <tr>
                                            <td width="10%" style=" font-size:10px;">
                                                <img width="24"  height="24" src="<?php echo $imagePath; ?>" alt="<?php echo  $lang->flag_image;?>"/>
                                            </td>
                                            <td width="40%"   style=" font-size:15px;word-wrap: break-word;"><?php echo ucfirst($lang->language_name); ?></td>
                                            <td width="20%" ><input name="code_name" type="text" id="code_name_<?php echo $i; ?>" /></td>
                                            <td width="20%" ><input name="text_name" type="text" id="text_name_<?php echo $i; ?>" /></td>
                                            <td width="10%" ><input name="addcode" type="submit" value="<?php echo language_code('DSP_ADD') ?>" class="button" />
                                                <input type="hidden" value="<?php echo $add_code_language_id ?>" name="add_code_language_id"/>

                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </td>


                        </tr>
                        <?php
                        $i++;
                    }
                    ?>

                </table>
            </div>
            <?php
            if (isset($_REQUEST['addcode'])) {
                $addCodeLanguageId = isset($_REQUEST['add_code_language_id']) ? $_REQUEST['add_code_language_id'] : '';
                $code_name = isset($_REQUEST['code_name']) ? trim($_REQUEST['code_name']) : '';
                $text_name = isset($_REQUEST['text_name']) ? trim($_REQUEST['text_name']) : '';

                // get the language table name from langiage id

                $added_language_table = $wpdb->get_var("SELECT  table_name FROM $dsp_language_detail_table WHERE language_id=$addCodeLanguageId ");
                $added_language_table = $wpdb->prefix . $added_language_table;

                $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $added_language_table WHERE code_name='$code_name'");

                if ($num_rows > 0) {
                    ?>
                    <div  style='color:#FF0000; margin-bottom: 15px;margin-top:15px; '> That text code '<?php echo $code_name ?>' is already in use. Please select another</div>
                    <?php
                    $language_table = $wpdb->get_results("SELECT * FROM $added_language_table WHERE code_name='$code_name'");
                    ?>
                    <div style="width:800px;">
                        <form name='abc' method="post" action="">
                            <table width="100%" border="0">
                                <tr style="line-height: 10px;">
                                    <td width="25%">&nbsp;</td>

                                    <td width="31%" class="dsp_language_txt" ><?php echo language_code('DSP_TOOLS_LANGUAGE_CODE_NAME'); ?></td>
                                    <td width="20%" class="dsp_language_txt1"><?php echo language_code('DSP_TOOLS_LANGUAGE_TEXT_NAME'); ?></td>
                                    <td style="width: 100px;">&nbsp;</td>
                                </tr>
                                <?php
                                foreach ($language_table as $language) {
                                    $code_name = $language->code_name;
                                    $text_name = $language->text_name;
                                    $code_id = $language->code_id;
                                    ?>
                                    <tr style="line-height: 10px; height:15px;">
                                        <td width="25%" style=" font-size:10px;">&nbsp;</td>

                                        <td width="31%" style=" font-size:10px;"><?php echo $code_name; ?></td>
                                        <td width="20%" style="font-size:11px;"><?php echo $text_name ?></td>
                                        <td style="font-size:13px; text-align: center"><a href="<?php
                                            echo add_query_arg(array(
                                                'pid' => 'tools_language_edit',
                                                'code_id' => $code_id,
                                                'text_name' => base64_encode($text_name),
                                                'mode' => 'edit',
                                                'edit_lang_id' => $addCodeLanguageId), $request_url);
                                            ?>" style="text-decoration:none;" ><span class="code_edit"><?php echo "Edit"; ?></span></a></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </form>
                    </div>
                    <?php
                } else {


                    $wpdb->query("INSERT INTO $added_language_table SET code_name = '$code_name', text_name ='$text_name'");
                    $id = mysql_insert_id();

                    $language_table = $wpdb->get_results("SELECT * FROM $added_language_table WHERE code_id=$id");
                    ?> 
                    <div style="margin-top:15px;width:800px;">
                        <form name='abc' method="post" action="">
                            <table width="100%" border="0">
                                <tr style="line-height: 10px;">

                                    <td width="25%" style=" font-size:10px;">&nbsp;</td>
                                    <td width="31%" class="dsp_language_txt"><?php echo language_code('DSP_TOOLS_LANGUAGE_CODE_NAME'); ?></td>
                                    <td width="20%" class="dsp_language_txt1"><?php echo language_code('DSP_TOOLS_LANGUAGE_TEXT_NAME'); ?></td>
                                    <td style="width: 100px;">&nbsp;</td>
                                </tr>
                                <?php
                                foreach ($language_table as $language) {
                                    $code_name = $language->code_name;
                                    $text_name = $language->text_name;
                                    $code_id = $language->code_id;
                                    ?>
                                    <tr style="line-height: 10px; height:15px;">


                                        <td width="25%" style=" font-size:10px;">&nbsp;</td>
                                        <td width="31%" style=" font-size:10px;"><?php echo $code_name; ?></td>
                                        <td width="20%" style="font-size:11px;"><?php echo $text_name ?></td>
                                        <td style="font-size:13px;text-align: center">
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 'tools_language_edit',
                                                'code_id' => $code_id, 'text_name' => base64_encode($text_name),
                                                'mode' => 'edit', 'edit_lang_id' => $addCodeLanguageId), $request_url);
                                            ?>" style="text-decoration:none;" ><span class="code_edit"><?php echo "Edit"; ?></span></a></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </form>
                        <?php
                    }
                }
                ?>
            </div>
            <div>
                <div style="height:20px;"></div>
                <div  style="color:#FF0000; font-weight:bold; margin-bottom: 7px;"> NOTE: Please consult this <a href="http://www.wpdating.com/support/forums/how-to/add-new-text-to-language/" style=" color:#FF0000;">Support Forum Thread</a> before adding new text to the language base.</div>

            </div>
        </div>
        <br />
    </div>
    <!-- ------------------------ADD NEW LANGUAGE------------------------------------------- -->
    <div id="general" class="postbox">
        <h3 class="hndle"><span><?php echo language_code('DSP_ADD_NEW_LANGUAGE'); ?></span></h3>
        <br />
        <div class="dsp_thumbnails3" >
            <div style="width:800px;">
                <form name="search" method="post"  action="">
                    <div style="float:none;" >
                        <table border="0" cellspacing="5" cellpadding="5" width="45%">
                            <?php
                            foreach ($all_languages as $lang) {
                                $lang_id = $lang->language_id;
                                $imagePath = get_bloginfo('url') . '/uploads/dsp_dating/flags/' . $lang->flag_image;
                                ?>
                                <tr>
                                    <td style=" font-size:10px;">
                                        <img width="24"  height="24" src="<?php echo $imagePath; ?>" alt="<?php echo $lang->flag_image;?>"/>
                                    </td>
                                    <td width="50%" style=" font-size:15px;">
                                        <?php echo ucfirst($lang->language_name); ?>
                                    </td>
                                    <td style=" font-size:15px;">
                                        <a href="<?php echo $root_link . "&Action=edit&lId=" . $lang_id; ?>"><?php echo language_code('DSP_EDIT'); ?></a>
                                    </td> 
                                    <td>-</td>
                                    <td style=" font-size:15px;">
                                        <a href="<?php echo $root_link . "&Action=delete&lId=" . $lang_id; ?>" onclick="return checkconfirm();"><?php echo language_code('DSP_DELETE'); ?></a>
                                    </td>
                                </tr>
                            <?php }
                            ?>
                        </table>
                </form>
                <br>
                <br>
                <span style="font-weight:bold;font-size: 14px "><?php echo language_code('DSP_ADD_NEW_LANGUAGE'); ?></span>
                <br>
                <br>
                <br>
                <form action="" method="post" enctype="multipart/form-data"  <?php if ($mode == "add") { ?>onsubmit="return checkValidLang();" <?php } else { ?> onsubmit="return checkValidEditLang();" <?php } ?>>
                    <table width="100%">
                        <tr>
                            <td colspan="3"  style="color: red">
                                <?php
                                if (isset($msg)) {
                                    echo $msg;
                                }
                                ?>
                            </td>
                        </tr>
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                            <td width="25%"><?php echo language_code('DSP_LANGUAGE_NAME'); ?>:</td>
                            <td><input id="txt_language_name" type="text" value="<?php if (isset($langName)) echo $langName; ?>" name="txt_language_name" /></td>
                            <td>&nbsp;</td>
                        </tr>

                        <tr>
                            <td><?php echo language_code('DSP_CHARSET'); ?>:</td>
                            <td><input type="text" value="<?php if (isset($charSet)) echo $charSet; ?>" name="txt_charset" /></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><?php echo language_code('DSP_IMPORT_LANGUAGE_FILE'); ?>:</td>
                            <td><input id="language_file" type="file" name="language_file" size="18"  value="" /></td>

                            <td ><a href="<?php echo WPDATE_URL . '/language.php' ?>" style="color: red"><?php echo language_code('DSP_DOWNLOAD_LANGUAGE_FILE'); ?><a></td>
                                        </tr>
                                        <tr>
                                            <td><?php echo language_code('DSP_FLAG_IMAGE'); ?>:</td>
                                            <td><input id="default_image" type="file" name="default_image" size="18" accept="image/*" value=""/></td>
                                            <td >
                                                <a href="<?php echo get_option('siteurl') . '/wp-content/uploads/flags.zip' ?>" style="color: red"><?php echo language_code('DSP_DOWNLOAD_FLAG_FIRST'); ?></a>
                                                <?php if (isset($flagImage)) { ?><img src="<?php echo get_bloginfo('url') . '/wp-content/uploads/flags/' . $flagImage; ?>" /> <?php } ?>
                                            </td> 	</tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <input type="hidden" name="mode" value="<?php echo $mode ?>" />
                                                <input type="hidden" name="editLangId" value="<?php echo $editLangId; ?>" />
                                                <input type="submit" value="<?php
                                                if ($mode == 'add')
                                                    _e('Add');
                                                else
                                                    _e('Update');
                                                ?>" name="add_lang" />
                                            </td>
                                        </tr>
                                        </table>
                                        </form>
                                        </div>
                                        </div>
                                        <br />
                                        </div>
                                        </div>
                                        <div style="height:30px;"></div>