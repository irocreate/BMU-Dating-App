<script type="text/javascript">

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
$root_link = $_SERVER['PHP_SELF'] . '?page=dsp-admin-sub-page3&pid=tools_language&dsp_page=add_language';
// get all the language stored in table
$all_languages = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table  ");
?>
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
                    $imagePath = get_bloginfo('url') . '/wp-content/uploads/flags/' . $lang->flag_image;
                    ?>
                    <tr>
                        <td colspan="5">
                            <?php               
                                    $request_uri=$_SERVER['REQUEST_URI'];
                                    if($_SERVER['HTTP_HOST']=='localhost')
                                    {
                                        $request_uri=explode('/',$_SERVER['REQUEST_URI']);                                       
                                        $to_replace=$request_uri[1];
                                        $request_uri=implode('/',$request_uri);
                                        $request_uri=str_replace($to_replace.'/','',$request_uri);
                                    }  
                                    $current_url=get_option('siteurl').$request_uri;                                                                     
                            ?>
                            <form name="addcode_<?php echo $i; ?>" method="post" action="<?php if($_REQUEST['code_id']!=null && $add_code_language_id==$_REQUEST['add_code_language_id']) echo remove_query_arg(array('code_id', 'code_name','text_name','add_code_language_id'), $current_url); ?>" onsubmit="return validation('<?php echo $i; ?>');" >
                                <table width="100%">
                                    <tr>
                                        <td width="10%" style=" font-size:10px;">
                                            <img width="24"  height="24" src="<?php echo $imagePath; ?>" alt="<?php echo $lang->flag_image;?>"/>
                                        </td>
                                        <td width="40%"   style=" font-size:15px;word-wrap: break-word;"><?php echo ucfirst($lang->language_name); ?></td>
                                        <td width="20%" ><input name="code_name" type="text" id="code_name_<?php echo $i; ?>" value="<?php if(isset($_REQUEST['code_id']) && $_REQUEST['code_id']!=null && $add_code_language_id==$_REQUEST['add_code_language_id']) echo $_REQUEST['code_name']; ?>"/></td>
                                        <td width="20%" ><input name="text_name" type="text" id="text_name_<?php echo $i; ?>" value="<?php if(isset($_REQUEST['code_id']) && $_REQUEST['code_id']!=null && $add_code_language_id==$_REQUEST['add_code_language_id']) echo base64_decode ($_REQUEST['text_name']); ?>"/></td>
                                        <td width="10%" ><input name="addcode" type="submit" value="<?php if(isset($_REQUEST['code_id']) && $_REQUEST['code_id']!=null && $add_code_language_id==$_REQUEST['add_code_language_id']) echo 'Edit'; else echo language_code('DSP_ADD'); ?>" class="button" />
                                            <input type="hidden" value="<?php if(isset($_REQUEST['code_id']) && $_REQUEST['code_id']!=null && $add_code_language_id==$_REQUEST['add_code_language_id']) echo $_REQUEST['add_code_language_id']; else echo $add_code_language_id; ?>" name="add_code_language_id"/>

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
            $transientKey =  $code_name . "_" . $addCodeLanguageId;
            $poData = dsp_get_po_data($addCodeLanguageId);
            // get the language table name from langiage id

            $added_language_table = $wpdb->get_var("SELECT  table_name FROM $dsp_language_detail_table WHERE language_id=$addCodeLanguageId ");
            $added_language_table = $wpdb->prefix . $added_language_table;

            $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $added_language_table WHERE code_name='$code_name'");
            
            if ($num_rows > 0 && $_REQUEST['addcode']!='Edit' ) {                
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
                                            'page'=>'dsp-admin-sub-page3',
                                            'pid' => 'tools_language', 
                                            'dsp_page' => 'add_text',
                                            'code_id' => $code_id,
                                            'code_name' => $code_name,
                                            'text_name' => base64_encode(esc_html($text_name)), 
                                            'add_code_language_id' => $addCodeLanguageId));
                                        ?>" style="text-decoration:none;" ><span class="code_edit"><?php echo language_code('DSP_EDIT'); ?></span></a></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </form>
                </div>
                <?php
            } else {

                if($_REQUEST['addcode']=='Edit')
                {
                    $wpdb->query("UPDATE $added_language_table SET text_name ='$text_name' WHERE code_name = '$code_name'");
                    $id = $wpdb->get_row("SELECT * FROM $added_language_table WHERE code_name = '$code_name'");
                    $id = $id->code_id;
                    file_exists($poData['file_path']) ? Sepia\PoParserUsed::updatePo($code_name,$text_name) : '';
                    delete_transient($transientKey);
                    set_transient($transientKey,$text_name, 60*60*24*30);
                }
                else {
                    $wpdb->query("INSERT INTO $added_language_table SET code_name = '$code_name', text_name ='$text_name'");
                    $id = mysql_insert_id();
                    file_exists($poData['file_path']) ? Sepia\PoParserUsed::updatePo($code_name,$text_name) : '';
                    delete_transient( $transientKey);
                    set_transient($transientKey,$text_name, 60*60*24*30);
                }
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
                                        echo add_query_arg(array(
                                            'page'=>'dsp-admin-sub-page3',
                                            'pid' => 'tools_language', 
                                            'dsp_page' => 'add_text',
                                            'code_id' => $code_id,
                                            'code_name' => $code_name,
                                            'text_name' => base64_encode(esc_html($text_name)),
                                            'add_code_language_id' => $addCodeLanguageId));
                                        ?>" style="text-decoration:none;" ><span class="code_edit"><?php echo language_code('DSP_EDIT'); ?></span></a></td>
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