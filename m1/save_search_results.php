<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_back.php");?>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_SEARCH'); ?></h1>
     <?php include_once("page_home.php");?>
</div>

<?php
$dsp_user_search_criteria_table = $wpdb->prefix . DSP_USER_SEARCH_CRITERIA_TABLE;

$Search_Id = isset($_REQUEST['search_Id']) ? $_REQUEST['search_Id'] : '';



$Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';



if ($Action == "Del" && !empty($Search_Id)) {   // DELETE PICTURE
    $wpdb->query("DELETE FROM $dsp_user_search_criteria_table WHERE user_search_criteria_id = '$Search_Id'");
}
?>
<div class="ui-content" data-role="content">
    <div class="content-primary">	

        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul save-search-list">
            <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                <form name="savesearches"  id="frmsearch">

                    <input type="hidden" value="<?php echo language_code('DSP_ARE_YOU_SURE_TO_DELETE_IT'); ?>" name="del_msg" id="del_msg"/>
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                    <input type="hidden" name="pagetitle" value="search_result" />
                    <input type="hidden" name="searchbysave" value="save_search" />
                    <input type="hidden" name="search_type" value="show_save_search" />
                    <div class="box-page">
                            <input name="save_search_Id" id="save_search_Id" type="hidden" value="" />
                            <ul class="search-results">
                            <?php
                            $search_result = $wpdb->get_results("SELECT * FROM $dsp_user_search_criteria_table Where user_id='$user_id' Order by user_search_criteria_id ");

                            foreach ($search_result as $search) {
                                $save_search_id = $search->user_search_criteria_id;
                                ?>
                                
                                <li class="clearfix">
                                <div class="search-details-left">
                                    <div class="user-name" onclick="viewSearch('<?php echo $save_search_id ?>', 'show_save_search');" ><?php echo $search->search_name ?></div>
                                  <div class="date-format"> <?php echo $search->search_type ?></div>
                                   </div>
                                     <div class= "delete-button" onclick="viewSearch('<?php echo $save_search_id ?>', 'del');" ><?php echo language_code('DSP_DELETE_LINK'); ?></div>
                                   
                                </li>
                                
                                
                            <?php } ?>
                            </ul>
                       
                    </div>
                </form>
            </li>
        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>