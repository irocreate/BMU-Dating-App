<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$profiles_page_url = isset($_REQUEST['dsp_page']) ? $_REQUEST['dsp_page'] : '';
?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr><td>
            <div id="navmenu" align="left">
                <ul>
                    <li <?php if (($profiles_page_url == "search") && ($pageURL == "tools_language")) { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                        <a href="admin.php?page=dsp-admin-sub-page3&pid=tools_language&dsp_page=search"><?php echo language_code('DSP_TOOLS_LANGUAGE_TAB_SEARCH'); ?></a><span class="dsp_tab1_span">|</span></li>
                    <li <?php if (($profiles_page_url == "add_text")) { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                        <a href="admin.php?page=dsp-admin-sub-page3&pid=tools_language&dsp_page=add_text"><?php echo language_code('DSP_TOOLS_LANGUAGE_TAB_ADD_TEXT'); ?></a><span class="dsp_tab1_span">|</span></li>
                    <li <?php if (($profiles_page_url == "add_language")) { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                        <a href="admin.php?page=dsp-admin-sub-page3&pid=tools_language&dsp_page=add_language"><?php echo language_code('DSP_TOOLS_LANGUAGE_TAB_ADD_LANGUAGE'); ?></a> |</li>
                    <li <?php if (($profiles_page_url == "import_language")) { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
                    <a href="admin.php?page=dsp-admin-sub-page3&pid=tools_language&dsp_page=import_language_file"><?php echo language_code('DSP_TOOLS_IMPORT_LANGUAGE_PACK'); ?></a></li>
                </ul>
            </div>
        </td></tr>
</table>
<?php
switch ($profiles_page_url) {
    case 'search':
        include_once( WP_DSP_ABSPATH . 'files/dsp_tools_language_search.php');
        break;
    case 'add_text':
        include_once( WP_DSP_ABSPATH . 'files/dsp_tools_language_add_text.php');
        break;
    case 'add_language':
        include_once( WP_DSP_ABSPATH . 'files/dsp_tools_language_add_language.php');
        break;
    case 'import_language_file':
        include_once( WP_DSP_ABSPATH . 'files/dsp_import_new_language.php');
        break;
    default:
        break;
}
