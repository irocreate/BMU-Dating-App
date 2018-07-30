<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_language_table = $wpdb->prefix . DSP_LANGUAGE_TABLE;
$dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
$request_url = $_SERVER['REQUEST_URI'];
$code_id = isset($_REQUEST['code_id']) ? $_REQUEST['code_id'] : '';
$text_name = isset($_REQUEST['text_name']) ? $_REQUEST['text_name'] : '';
$edit_lang_id = isset($_REQUEST['edit_lang_id']) ? $_REQUEST['edit_lang_id'] : '';
?>
<style type="text/css">
    .div {
        position:relative; 
        width: 180px;
        padding: 10px;
        display: none;
    }
</style>
<div>
    <div id="general" class="postbox">
        <h3 class="hndle"><span><?php echo language_code('DSP_TOOLS_LANGUAGE_ADMIN'); ?></span></h3>
        <div style="height:30px;"></div>
        <div class="dsp_thumbnails3" >
            
            <div>
                <div style="height:20px;"></div>
                <?php
                if (isset($_REQUEST['search'])) {
                    $text_name = trim($_REQUEST['text_name']);
                    $language_table = $wpdb->get_results("SELECT * FROM $dsp_language_table WHERE text_name like '%$text_name%'");
                    ?> 
                    <form name='abc' method="post" action="">
                        <table width="100%" border="0">
                            <tr style="line-height: 10px;">
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
                                    <td style=" font-size:10px;"><?php echo $code_name; ?></td>
                                    <td style="font-size:11px;"><?php echo base64_decode($text_name) ?></td>
                                    <td style="font-size:13px;"><a href="<?php
                                        echo add_query_arg(array(
                                            'pid' => 'tools_language_edit', 'code_id' => $code_id,
                                            'text_name' => base64_encode($text_name),
                                            'mode' => 'edit'), $request_url);
                                        ?>" style="text-decoration:none;" ><span class="code_edit"><?php echo "Edit"; ?></span></a></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </form>
                <?php } ?>


                <?php
                if ((@$_GET['mode'] == 'edit') && (@$_POST['text_name'] == '') && $_GET['edit_lang'] != '') {

                    $edit_lang_id = $_GET['edit_lang'];
                    $lang_table = $_GET['edit_lang'];
                    $edit_language_table = $wpdb->prefix . $lang_table;
                    // get the language table name from language id

                    $language_detail_table = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table WHERE table_name='$lang_table'");
                    $imagePath = get_bloginfo('url') . '/wp-content/uploads/flags/' . $language_detail_table->flag_image;


                    $text_name = base64_decode($_GET['text_name']);
                    $code_id = $_GET['code_id'];
                    $wpdb->query("UPDATE $edit_language_table SET text_name='$text_name' where code_id=$code_id");
                    $language_table = $wpdb->get_results("SELECT * FROM $edit_language_table WHERE text_name = '$text_name' AND code_id=$code_id");
                    ?>
                    <form name='abc' method="post" action="">
                        <table width="995" border="0">
                            <tr style="line-height: 10px;">
                                <td valign="bottom" style="width:30px;"><img width="24"  height="24" src="<?php echo $imagePath; ?>" alt="<?php echo $language_detail_table->flag_image;?>"/></td>
                                <td style="width:70px;"><?php echo ucfirst($language_detail_table->language_name); ?></td>
                                <td class="dsp_language_txt"><?php echo language_code('DSP_TOOLS_LANGUAGE_CODE_NAME'); ?></td>
                                <td class="dsp_language_txt1"><?php echo language_code('DSP_TOOLS_LANGUAGE_TEXT_NAME'); ?></td>
                            </tr>
                            <?php
                            foreach ($language_table as $language) {
                                $code_name = $language->code_name;
                                $text_name = $language->text_name;
                                $code_id = $language->code_id;
                                ?>
                                <tr style="line-height: 10px; height:15px;">
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td style=" font-size:10px;"><?php echo $code_name; ?></td>
                                    <td style="font-size:11px;"><?php echo $text_name ?></td>

                                </tr>
                            <?php } ?>
                        </table>
                        <div style="height:30px;"></div>
                        <div id="div"><span class='dsp_language_txt1'>Edit Text:</span> 
                            <input type='text_<?php echo $_GET['code_id']; ?>' value='<?php echo base64_decode($_GET['text_name']); ?>' name='text_name' style='width:200px;'>
                            <input type='hidden' name='code_id' value=<?php echo $_GET['code_id']; ?>>&nbsp;
                            <input type='hidden' name='edit_lang' value=<?php echo $_GET['edit_lang']; ?>>&nbsp;
                            <input type='submit'  value='Save' name='edit'>
                        </div>
                    </form>
                <?php } ?>
                <?php
                if (isset($_POST['edit'])) {
                    $text_name = $_POST['text_name'];
                    $code_id = $_POST['code_id'];

                    $edit_lang = $_POST['edit_lang'];

// get the language table name from language id

                    $edit_language_table = $wpdb->prefix . $edit_lang;

                    $wpdb->query("UPDATE $edit_language_table SET text_name='$text_name' where code_id=$code_id");
                    $language_table = $wpdb->get_results("SELECT * FROM $edit_language_table WHERE text_name like '%$text_name%'");
                    ?>
                    <form name='abc' method="post" action="">
                        <table width="100%" border="0">
                            <tr style="line-height: 10px;">
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
                                    <td style=" font-size:10px;"><?php echo $code_name; ?></td>
                                    <td style="font-size:11px;"><?php echo $text_name ?></td>
                                    <td style="font-size:13px;">
                                        <a href="<?php
                                                echo add_query_arg(array(
                                                                'pid' => 'tools_language_edit',
                                                                'code_id' => $code_id,
                                                                'text_name' => base64_encode($text_name),
                                                                'mode' => 'edit',
                                                                'edit_lang_id' => $edit_lang_id),
                                                                 $request_url
                                                    );
                                        ?>" style="text-decoration:none;" >
                                            <span class="code_edit"><?php echo "Edit"; ?></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </form>
                <?php }
                ?>
            </div>
        </div>
        <div style="height:30px;"></div>
    </div>
   