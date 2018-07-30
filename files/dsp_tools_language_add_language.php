<script type="text/javascript">
    var validLangName = false;
    
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
    function checkValidEditLang()
    {
        if (document.getElementById('txt_language_name').value == '')
        {
            alert("<?php echo language_code('DSP_PLEASE_LANGUAGE_NAME'); ?>");
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
        if(!validLangName){

            alert("<?php echo language_code('DSP_LANGUAGE_NAME_ALREADY_EXIST'); ?>");
            return false;
        }

        return true;
    }
    
    jQuery(document).ready(function(){
        jQuery('.default_language_selector').click(function(){
            var current=jQuery(this);
            
            jQuery('<form>', {
                            "id": "js_form",
                            "method" : 'POST',
                            "html": '<input type="hidden" name="default_language_code" value="'+ current.val() +'" >',                           
                            "action": ''
                }).appendTo('.default_language_selector').submit();
        });
    });

    function checkLanguageName(langName){
    if(langName == ''){
        if(jQuery('td.errmsg').length > 0){
            jQuery('td.errmsg').remove();
        }
        var emptyMsg = "<?php echo language_code('DSP_PLEASE_LANGUAGE_NAME'); ?>";
        jQuery('td.info').before('<td class="errmsg"><span style="color:red">'+emptyMsg+'</span></td>');
        return false;
    }
    if(jQuery('td.errmsg').length > 0){
        jQuery('td.errmsg').remove();
    }
    var ajaxnonce = '<?php echo wp_create_nonce( "lang-nonce" ); ?>',
        errMsg = '<?php echo language_code("DSP_LANGUAGE_NAME_ALREADY_EXIST"); ?>';
        loaderPath = '<?php echo WPDATE_URL . '/images/ajax-loader.gif'; ?>'; 
        jQuery.ajax({
                    type: "POST",
                    url: ajaxurl + "?action=dsp_verify_lang_name&_wpnonce="+ajaxnonce,
                    dataType: 'json',
                    data: {langName:langName},
                    beforeSend: function() {
                       jQuery('td.errmsg').before('<td class="loader"><img src="'+ loaderPath +'" /></td>');
                    },
                    complete: function() {
                        jQuery('td.loader').remove();
                    },
                    success: function(html){
                        if(jQuery('td.errmsg').length > 0){
                            jQuery('td.errmsg').remove();
                        }
                        if(html['count'] > 0){
                            validLangName = false;
                            jQuery('td.info').before('<td class="errmsg"><span style="color:red">'+errMsg+'</span></td>');
                            return validLangName;
                        }
                        validLangName = true;
                        return validLangName;
                    }
                });
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
$dsp_session_language_table = $wpdb->prefix . DSP_SESSION_LANGUAGE_TABLE;
$mode = "";
$userId = get_current_user_id();

if(isset($_POST['default_language_code']) && $_POST['default_language_code']!=null) {
    $default_language_code=$_POST['default_language_code'];
    unset($_SESSION['default_lang']);
    $_SESSION['default_lang'] = $default_language_code;
    $wpdb->query("UPDATE $dsp_language_detail_table SET display_status='0'");
    $wpdb->query("UPDATE $dsp_language_detail_table SET display_status='1' WHERE language_id='$default_language_code'");
    $wpdb->query($wpdb->prepare("UPDATE $dsp_session_language_table SET language_id ='%d' WHERE user_id ='%d'", array($default_language_code,$userId)));
    echo '<script>window.location="' . $_SERVER['HTTP_REFERER'] . '";</script>';
}

if (isset($_REQUEST['mode'])) {
    $mode = $_REQUEST['mode'];
    $editLanguageId = $_REQUEST['editLangId'];
}

include_once("dsp_upload_image_with_GD.php"); // include file for image upload
$root_link = $_SERVER['PHP_SELF'] . '?page=dsp-admin-sub-page3&pid=tools_language&dsp_page=add_language';
//--------------ADD LANGUAGE AND UPDATE LANGUAGE--------------------------------------------
if (isset($_REQUEST['add_lang']) && !empty($_REQUEST['add_lang']) && $invalidEntry == 0) {   
    if ($mode == "add") {
    //echo '<br>add mode';  
        // create a table with the lang name
        $languageName = strtolower(trim(esc_sql($_POST['txt_language_name'])));
        $table_name= strtolower(trim(esc_sql(substr($_POST['txt_language_name'],0,2))));
        $tableName = "dsp_language_" .$table_name ;
        $DSP_TABLE_NAME = $wpdb->prefix . $tableName;
        $flag_image = $default_image;
        $chaSet = $_POST['txt_charset'];
       // check condition if table exists
        if ($wpdb->get_var("show tables like '$DSP_TABLE_NAME'") != $DSP_TABLE_NAME) {
           //$wpdb->query(  "CREATE TABLE $DSP_TABLE_NAME AS (SELECT * FROM $dsp_language_table )");
            $wpdb->query("CREATE TABLE $DSP_TABLE_NAME LIKE $dsp_language_table ");
            $wpdb->query("ALTER TABLE $DSP_TABLE_NAME  DEFAULT CHARACTER SET utf8  COLLATE utf8_unicode_ci");
            $wpdb->query("INSERT INTO $DSP_TABLE_NAME SELECT * FROM $dsp_language_table");

            // check condition if table exists
            $DSP_PROFILE_SETUP_TABLE = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
            $profiletable = "dsp_profile_setup_" . $table_name;
            $DSP_PROFILE_TABLE_NAME = $wpdb->prefix . $profiletable;

            $dsp_profile_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;
            $profilequestiontable = "dsp_question_options_" . $table_name;
            $DSP_PROFILE_QUES_TABLE_NAME = $wpdb->prefix . $profilequestiontable;

            $dsp_flirt_text_table = $wpdb->prefix . DSP_FLIRT_TEXT_TABLE;
            $flirttable = "dsp_flirt_" . $table_name;
            $DSP_FLIRT_TABLE_NAME = $wpdb->prefix . $flirttable;
            
            $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
            $DSP_FEATURES_TABLE_NAME = $dsp_features_table .'_'. $table_name;

            //      echo '<br>table name'; 
            if ($wpdb->get_var("show tables like '$DSP_PROFILE_TABLE_NAME'") != $DSP_PROFILE_TABLE_NAME) {//echo '<br>profile table name'.$DSP_PROFILE_TABLE_NAME;
                $wpdb->query("CREATE TABLE $DSP_PROFILE_TABLE_NAME AS (SELECT * FROM $DSP_PROFILE_SETUP_TABLE )");
                $wpdb->query("ALTER TABLE $DSP_PROFILE_TABLE_NAME  DEFAULT CHARACTER SET utf8  COLLATE utf8_unicode_ci");
                $wpdb->query("ALTER TABLE  $DSP_PROFILE_TABLE_NAME CHANGE  `profile_setup_id`  `profile_setup_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ");
                $wpdb->query("ALTER TABLE  $DSP_PROFILE_TABLE_NAME CHANGE  `question_name`  `question_name` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ");
                
            }

            if ($wpdb->get_var("show tables like '$DSP_PROFILE_QUES_TABLE_NAME'") != $DSP_PROFILE_QUES_TABLE_NAME) { //echo '<br>profile ques table name'.$DSP_PROFILE_QUES_TABLE_NAME;
                $wpdb->query("CREATE TABLE $DSP_PROFILE_QUES_TABLE_NAME AS (SELECT * FROM $dsp_profile_question_options_table )");
                $wpdb->query("ALTER TABLE $DSP_PROFILE_QUES_TABLE_NAME  DEFAULT CHARACTER SET utf8  COLLATE utf8_unicode_ci");
                $wpdb->query("ALTER TABLE  $DSP_PROFILE_QUES_TABLE_NAME CHANGE  `question_option_id`  `question_option_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ");
                $wpdb->query("ALTER TABLE  $DSP_PROFILE_QUES_TABLE_NAME  CHANGE `option_value` `option_value` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");
            }

            if ($wpdb->get_var("show tables like '$DSP_FLIRT_TABLE_NAME'") != $DSP_FLIRT_TABLE_NAME) {
                //echo '<br>profile flirt table name'.$DSP_FLIRT_TABLE_NAME;
                $wpdb->query("CREATE TABLE $DSP_FLIRT_TABLE_NAME AS (SELECT * FROM $dsp_flirt_text_table )");
                $wpdb->query("ALTER TABLE $DSP_FLIRT_TABLE_NAME  DEFAULT CHARACTER SET utf8  COLLATE utf8_unicode_ci");
                $wpdb->query("ALTER TABLE  $DSP_FLIRT_TABLE_NAME CHANGE  `Flirt_ID`  `Flirt_ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ");
                $wpdb->query("ALTER TABLE  $DSP_FLIRT_TABLE_NAME  CHANGE `flirt_Text` `flirt_Text` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");
            }
            
            if ($wpdb->get_var("show tables like '$DSP_FEATURES_TABLE_NAME'") != $DSP_FEATURES_TABLE_NAME) {
                //echo '<br>profile flirt table name'.$DSP_FLIRT_TABLE_NAME;
                $wpdb->query("CREATE TABLE $DSP_FEATURES_TABLE_NAME AS (SELECT * FROM $dsp_features_table )");
                $wpdb->query("ALTER TABLE $DSP_FEATURES_TABLE_NAME  DEFAULT CHARACTER SET utf8  COLLATE utf8_unicode_ci");
                $wpdb->query("ALTER TABLE  $DSP_FEATURES_TABLE_NAME CHANGE  `feature_id`  `feature_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ");
                $wpdb->query("ALTER TABLE  $DSP_FEATURES_TABLE_NAME  CHANGE `feature_name` `feature_name` VARCHAR( 120 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");
            }
            
            //echo '<br>tables created';
            // INSERT INTO TABLE
            //$insertDetailQuery = "INSERT INTO SET language_name='$language_name',display_status='0',flag_image='$flag_image', table_name = '$tableName',charset='$chaSet'";
            
            $wpdb->insert(  $dsp_language_detail_table,
                            array(
                                  'language_name' => $language_name,
                                  'display_status'=> 0,
                                  'flag_image'=> $flag_image,
                                  'table_name'=> $tableName,
                                  'charset'   => $chaSet
                                ),
                            array('%s','%d','%s','%s','%s')
                        );
            $langId = $wpdb->insert_id;
            dsp_generate_po_file($langId,$languageName);
            //echo $insertDetailQuery.'<br>';
        }
} else if ($mode == "update") {

        $charSet = isset($_REQUEST['txt_charset']) ? $_REQUEST['txt_charset'] : '';
        $language_name = isset($_REQUEST['txt_language_name']) ? $_REQUEST['txt_language_name'] : '';
        $updatetDetailQuery = "UPDATE  $dsp_language_detail_table SET language_name='$language_name', charset='$charSet' WHERE language_id='$editLanguageId'";
        $wpdb->query($updatetDetailQuery);
        echo '<script>location.href = "'.$root_link.'"</script>';
        exit();
        //echo $updatetDetailQuery;
        ?>
        
        <?php
    }
}
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
        $deleteRowDetail = $wpdb->get_row("SELECT table_name,flag_image,language_name,display_status FROM $dsp_language_detail_table WHERE language_id='$langId'");
        $deleteImagePath = ABSPATH . '/wp-content/uploads/flags/' . $deleteRowDetail->flag_image;
        $poData = dsp_get_po_data($langId);
        $deletePoPath = PO_PATH . $poData['language_name'];

        $tableName = $wpdb->prefix . $deleteRowDetail->table_name;
        $status = $deleteRowDetail->display_status;
        //set default language  to another if deleted language is default language
        if($status > 0){
            do_action('dsp_set_another_default_language',$langId);
           
        }
        
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
        $deleteQuery = "DELETE FROM $dsp_language_detail_table WHERE language_id='%d'";
        $wpdb->query($wpdb->prepare($deleteQuery,$langId));

        //delete session table values matches with this language id
        $deleteQuery = "DELETE FROM $dsp_session_language_table WHERE language_id='%d'";
        $wpdb->query($wpdb->prepare($deleteQuery,$langId));
        

        //echo '<br>'.$deleteQuery;
        // delete the whole table of this language
        $deleteLanguageTableQuery = "DROP TABLE IF EXISTS $tableName";
        $wpdb->query($deleteLanguageTableQuery);

        //echo '<br>'.$deleteLanguageTableQuery;
        // delete the flag image
        file_exists($deleteImagePath) ? unlink($deleteImagePath) : '';
        if(file_exists($deletePoPath)){ // removing po file & folder
            unlink($poData['file_path']);
            rmdir($deletePoPath);
           
        }
        echo '<script>location.href = "'. $root_link.'"</script>';
        exit();
    }    

    ?>
   
    <?php 
}
$all_languages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table  ");
?>
<!-- ------------------------ ADD NEW LANGUAGE ------------------------------------------- -->
<div id="general" class="postbox">
    <h3 class="hndle"><span><?php echo language_code('DSP_ADD_NEW_LANGUAGE'); ?></span></h3>
    <br />
    <div class="dsp_thumbnails3" >
        <div style="width:800px;">
                <div id="js_form_container" style="display:none;"></div>
                <div style="float:none;" >
                    <table border="0" cellspacing="5" cellpadding="5" width="45%">
                        <th>Default</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <?php
                        foreach ($all_languages as $lang) {
                            $lang_id = $lang->language_id;
                            $isNotDefaultLang = $lang->display_status != 1 ? true : false;
                            $imagePath = get_bloginfo('url') . '/wp-content/uploads/flags/' . $lang->flag_image;
                            if($lang_id == 1 && !file_exists($imagePath)){
                                $fileNames = array($lang->flag_image);
                                $locations = array(
                                                    'src' => WP_DSP_ABSPATH . '/images/',
                                                    'dest'=> ABSPATH . '/wp-content/uploads/flags/'
                                            );
                                do_action('dsp_copy_images',$fileNames,$locations);
                            }
                            ?>
                            <tr>
                                <td>
                                    <input type="radio" name="default_language_selector" class="default_language_selector" <?php if($lang->display_status==true) echo 'checked="checked"'; ?> value="<?php echo $lang->language_id; ?>" />
                                </td>
                                <td style=" font-size:10px;">
                                    <img width="24"  height="24" src="<?php echo $imagePath; ?>" alt="<?php echo $lang->flag_image;?>" />
                                </td>
                                <td width="50%" style=" font-size:15px;">
                                    <?php echo ucfirst($lang->language_name); ?>
                                </td>
                                <td style=" font-size:15px;">
                                    <a href="<?php echo $root_link . "&Action=edit&lId=" . $lang_id; ?>"><?php echo language_code('DSP_EDIT'); ?></a>
                                </td> 
                                <?php if($isNotDefaultLang): ?>
                                <td>-</td>
                                <td style=" font-size:15px;">
                                    <a href="<?php echo $root_link . "&Action=delete&lId=" . $lang_id; ?>" onclick="return checkconfirm();"><?php echo language_code('DSP_DELETE'); ?></a>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php }
                        ?>
                    </table>
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
                        <td><input id="txt_language_name" type="text" value="<?php if (isset($langName)) echo $langName; ?>" name="txt_language_name" <?php if (isset($langName)) echo 'readonly'; ?> onblur="checkLanguageName(this.value);return false;"/></td>
                        <?php if (isset($langName)) echo '<td class="info">Not Editable</td>'; else echo '<td style="color:#CC0000;" class="info">Please Be Careful : Cannot Be Edited Later</td>'; ?>
                        <td>&nbsp;</td>                     
                    </tr>

                    <tr>
                        <td><?php echo language_code('DSP_CHARSET'); ?>:</td>
                        <td><input type="text" value="<?php if (isset($charSet)) echo $charSet; ?>" name="txt_charset" /></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><?php echo language_code('DSP_FLAG_IMAGE'); ?>:</td>
                        <td><input id="default_image" type="file" name="default_image" size="18" accept="image/*" value=""/></td>
                        <td >
                            <a href="<?php echo WPDATE_URL . '/flags/flags.zip';?>" style="color: red"><?php echo language_code('DSP_DOWNLOAD_FLAG_FIRST'); ?></a>
                            <?php if (isset($flagImage)) { ?><img src="<?php echo get_bloginfo('url') . '/wp-content/uploads/flags/' . $flagImage; ?>" alt="<?php echo $flagImage;?>" /> <?php } ?>
                        </td>   </tr>
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