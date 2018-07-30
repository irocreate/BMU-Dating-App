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
    dsp = jQuery.noConflict();
    dsp(document).ready(function() {
        dsp(document).on('click', '.tabLink', function() {
            var div_id = dsp(this).attr('id');
            dsp('.tabLink').each(function() {
                var id = dsp(this).attr('id');
                dsp(this).removeClass('activeLink');

                if (!dsp('#div_' + id).hasClass('hide')) {
                    dsp('#div_' + id).addClass('hide');
                }

            });
            dsp('#div_' + div_id).removeClass('hide');
            dsp(this).addClass('activeLink');
        });
        dsp(document).on('click', '#sort_lang', function() {
            var sorttype = dsp('input[name="sort_type"]').val();
            if (sorttype == 'none') {
                dsp('input[name="sort_type"]').val('asc');
            }
            else if (sorttype == 'asc') {
                dsp('input[name="sort_type"]').val('desc');
            }
            else if (sorttype == 'desc') {
                dsp('input[name="sort_type"]').val('asc');
            }
            dsp('input[name="lang"]').val(dsp('.activeLink').attr('id'));
            dsp('input[name="search"]').click();
        });


<?php
if (isset($_REQUEST['search'])) {
    if ($_REQUEST['lang'] != "") {
        ?>
                dsp('.tabLink').each(function() {
                    var id = dsp(this).attr('id');
                    dsp(this).removeClass('activeLink');

                    if (!dsp('#div_' + id).hasClass('hide')) {
                        dsp('#div_' + id).addClass('hide');
                    }

                });
                dsp('#div_<?php echo $_REQUEST['lang']; ?>').removeClass('hide');
                dsp("#<?php echo $_REQUEST['lang']; ?>").addClass('activeLink');

        <?php
    }
}
?>
    });
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
?>
<div id="general" class="postbox">
    <h3 class="hndle"><span><?php echo language_code('DSP_TOOLS_LANGUAGE_ADMIN'); ?></span></h3>
    <br />
    <div class="dsp_thumbnails3" >
        <div style="width:421px;">
            <form name="searchfrm" method="get"  action="" onsubmit="return checkvalidSearch();">
                <div style="float:none;" >
                    <input type="hidden" name="page" value="dsp-admin-sub-page3" />
                    <input type="hidden" name="pid" value="tools_language" />
                    <input type="hidden" name="dsp_page" value="search" />
                    <input style="float:left; margin-right:20px;" name="text_name" type="text" id="search_text_name" value="<?php
                    $text_name = isset($_REQUEST['text_name']) ? trim($_REQUEST['text_name']) : '';
                    echo $text_name;
                    ?>" />
                    <input name="search" type="submit" value="<?php echo language_code('DSP_SEARCH_BUTTON'); ?>" class="button" />
                    <input type="hidden" name="sort_type" value="<?php
                    if (isset($_REQUEST['sort_type']))
                        echo $_REQUEST['sort_type'];
                    else
                        echo 'none';
                    ?>" />
                    <input type="hidden" name="lang" value="<?php
                    if (isset($_REQUEST['lang']))
                        echo $_REQUEST['lang'];
                    else
                        echo '';
                    ?>" />
                </div>
            </form>
        </div>
        <div>
            <div style="height:20px;"></div>
            <pre>
                <?php
                extract($_REQUEST);
                if (isset($update_lang_text)) {
                    foreach ($lang_text as $language_change) {
                        $table_name = $language_change['table'];
                        $tableNameWithoutPrefix = str_replace($wpdb->prefix,'',$table_name);
                        $languageId = $wpdb->get_row($wpdb->prepare("SELECT `language_id` FROM $dsp_language_detail_table WHERE  `table_name`= %s",$tableNameWithoutPrefix));
                        $languageId = isset($languageId) ? $languageId->language_id : 1;
                        $poData = dsp_get_po_data($languageId);
                        unset($language_change['table']);
                        foreach ($language_change as $key => $value) {
                            if(isset($value['text_name']) && !empty($value['text_name'])){
                                $update = $wpdb->query($wpdb->prepare("update $table_name set text_name=%s where code_id=%s",$value['text_name'],$key));
                                file_exists($poData['file_path']) ? Sepia\PoParserUsed::updatePo($value['code_name'],$value['text_name']) : '';
                                $transientKey =  $value['code_name'] . "_" . $languageId;
                                delete_transient( $transientKey);
                                set_transient($transientKey,$value['text_name'], 60*60*24*30);
                            }
                        }
                    }
                }
                if (isset($_REQUEST['search'])) {
                    $text_name = isset($_REQUEST['text_name']) ? trim($_REQUEST['text_name']) : '';
                    $selected_lang_id = isset($_REQUEST['select_language']) ? $_REQUEST['select_language'] : '';

                    // get the language table name from language id

                    $edit_language_table = $wpdb->get_results("SELECT * FROM $dsp_language_detail_table ");
                    ?>
                                     
                                    </div>
                                    </div>
                                    <br />
                                    </div>
                                    
    <div class="tab-container">
                                     
        <div class="tab-box"> 
            <?php
            $i = 0;
            foreach ($edit_language_table as $language_table) {
                $language_name = $language_table->language_name;
                $imagePath = get_bloginfo('url') . '/wp-content/uploads/flags/' . $language_table->flag_image;
            ?>
                <a href="javascript:void(0);" class="tabLink <?php if ($i == 0) echo 'activeLink'; ?>" id="<?php echo strtolower($language_name); ?>"><img height="24" src="<?php echo $imagePath; ?>" alt="<?php echo $language_table->flag_image;?>" />  <?php echo ucfirst($language_name); ?></a> 
            <?php
                $i++;
            }
            ?>
        </div>
        <?php
        $i = 0;
        $sort = 'asc';
        foreach ($edit_language_table as $language_table) {
            $language_detail_table=$language_table;
            if (isset($_REQUEST['sort_type']) && $_REQUEST['sort_type'] != 'none')
                $sort = $_REQUEST['sort_type'];
            else
                $sort = 'asc';
            $language_name = $language_table->language_name;
            $table_name = $language_table->table_name;
            $DSP_TABLE_NAME = $wpdb->prefix . $language_table->table_name;
            $language_table_query = "SELECT * FROM $DSP_TABLE_NAME WHERE text_name like '%$text_name%'";
            if (isset($_REQUEST['sort_type']) && $_REQUEST['sort_type'] != 'none' && $_REQUEST['lang'] == strtolower($language_name)) {
                $language_table_query.=" order by text_name " . $_REQUEST['sort_type'];
            }
            $language_table = $wpdb->get_results($language_table_query);
            ?> 
            <form method="post">
                <input type="hidden" value="<?php echo $DSP_TABLE_NAME; ?>" name="lang_text[<?php echo $language_name; ?>][table]" />
                <div class=" <?php if ($i != 0) echo 'hide'; ?>" id="div_<?php echo strtolower($language_name); ?>">
                    <ul>
                        <li class="heading">
                           <span class="code-name">
                               <?php echo language_code('DSP_TOOLS_LANGUAGE_CODE_NAME'); ?>
                           </span> 
                           <span class="txt-name">
                               <?php echo language_code('DSP_TOOLS_LANGUAGE_TEXT_NAME'); ?>
                           </span> 
                           <img alt="Arrow" id="sort_lang" class="short-arrow" src="<?php echo WPDATE_URL .  '/images/arrow-short-' . $sort . '.png'; ?>" />
                        </li>
                        <?php
                        foreach ($language_table as $language) 
                        {
                           $code_name = $language->code_name;
                           $textname = $language->text_name;
                           $code_id = $language->code_id;
                        ?>                                                                                                                                                                   
                        <li>
                           <span class="code-name" style='float:left;width:30%;'>
                               <input name="lang_text[<?php echo $language_name; ?>][<?php echo $code_id; ?>][code_name]" type="text" value="<?php echo $code_name; ?>"  />
                           </span> 
                           <span class="txt-name" style='float:left;width:57%;'>
                               <input name="lang_text[<?php echo $language_name; ?>][<?php echo $code_id; ?>][text_name]" type="text" value="<?php echo html_entity_decode($textname); ?>" style='background:#fff;'/>
                           </span>
                           <span style='float:left;width:10%;'>
                               <a href="<?php
                                        echo add_query_arg(array(
                                            'page'=>'dsp-admin-sub-page3',
                                            'pid' => 'tools_language', 
                                            'dsp_page' => 'add_text',
                                            'code_id' => $code_id,
                                            'code_name' => $code_name,
                                            'text_name' => base64_encode(esc_html($textname)), 
                                            'add_code_language_id' => $language_detail_table->language_id));
                                        ?>"><?php echo language_code('DSP_EDIT_THIS_ONLY'); ?></a>
                            </span>
                        </li>
                        <?php } ?>
                     </ul>
                    <div class="btn-save">
                        <input class="button" name="update_lang_text" type="submit" value="<?php echo language_code('DSP_TOOLS_LANGUAGE_SAVE_LANGUAGE'); ?>" />
                    </div>                                                                      
                </div>
            </form>                                                                     
        <?php
        $i++;
    }
    ?>                                                                         
    </div>
    <?php
}