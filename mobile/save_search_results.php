<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
$DSP_USER_SEARCH_CRITERIA_TABLE = $wpdb->prefix . DSP_USER_SEARCH_CRITERIA_TABLE;
if (isset($_GET['search_Id'])) {
    $Search_Id = $_GET['search_Id'];
} else {
    $Search_Id = "";
}
if (isset($_GET['Action'])) {
    $Action = $_GET['Action'];
} else {
    $Action = "";
}
?>
<form name="savesearches" method="GET" action="">
    <!-----------------------For local user only ---------------------------
    <input type="hidden" name="page_id" value="4" />
    <input type="hidden" name="view" value="mobile" />
    <!-----------comment above code when using live-------------------------->
    <input type="hidden" name="pid" value="5" />
    <input type="hidden" name="pagetitle" value="search_result" />
    <input type="hidden" name="searchbysave" value="save_search" />
    <div >
        <table cellpadding="0" cellspacing="0" width="100%" border="0">
            <tr><td colspan="3">&nbsp;</td></tr>
            <tr><td>
                    <div class="dsp_mb_sv">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr><td colspan="3"><input name="save_search_Id" id="save_search_Id" type="hidden" value="" /></td></tr>
                            <?php
                            $search_result = $wpdb->get_results("SELECT * FROM $DSP_USER_SEARCH_CRITERIA_TABLE Where user_id='$current_user->ID' Order by user_search_criteria_id ");

                            foreach ($search_result as $search) {
                                $save_search_id = $search->user_search_criteria_id;
                                ?>
                                <tr>
                                    <td align="left">
                                        <span onclick="save_search_record(<?php echo $save_search_id ?>);" style="cursor:pointer;text-decoration:underline;"><?php echo $search->search_name ?></span>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </td></tr>
        </table>
    </div>
</form>