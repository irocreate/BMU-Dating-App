<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$Search_Id = get('search_Id');
$Action = get('Action');
if ($Action == "Del" && !empty($Search_Id)) {   // DELETE PICTURE
    $wpdb->query("DELETE FROM $dsp_user_search_criteria_table WHERE user_search_criteria_id = '$Search_Id'");
}

?>
<form name="savesearches" method="POST" action="<?php echo $root_link . "search/save_searches/"; ?>">
    <div class="box-border">
        <div class="box-pedding">
          <?php
             $search_result = $wpdb->get_results("SELECT * FROM $dsp_user_search_criteria_table Where user_id='$current_user->ID' Order by user_search_criteria_id ");
              if(isset($search_result) && !empty($search_result)): 
           ?>         
            <div class="heading-submenu"><strong><?php echo language_code('DSP_SAVE_SEARCH_TITLE'); ?></strong></div>
            <ul class="save-search dsp-save-search clearfix">
                <li class="dspdp-row">
                    <span class="name dspdp-col-sm-6 dsp-sm-4 dspdp-col-xs-12"><strong><?php echo language_code('DSP_HEADER_SEARCH_NAME') ?></strong></span>
                    <span class="delete dspdp-col-xs-6 dsp-xs-4 dspdp-col-sm-2 dsp-sm-4 dsp-pull-right"><strong><?php echo language_code('DSP_HEADER_DELETE_SEARCH') ?></strong></span>
                    <span class="type dspdp-col-xs-6 dsp-xs-4    dspdp-col-sm-4 dsp-sm-4 dspdp-text-right"><strong><?php echo language_code('DSP_HEADER_SEARCH_TYPE') ?></strong></span>
                </li>
                <li class="dsp-none">
                    <hr> <input name="save_search_Id" id="save_search_Id" type="hidden" value="" /></li>
                <?php
                    foreach ($search_result as $search) {
                        $save_search_id = $search->user_search_criteria_id;
                        ?>
                        <li class="dspdp-row">
                            <a class="name dspdp-col-sm-6 dsp-sm-4 dspdp-col-xs-12" href="<?php
                            if ($search->search_type == 'basic')
                                echo $root_link . "search/search_result/basic_search/basic_search/searchbysave/save_search/save_search_Id/" . $save_search_id . "/";
                            else
                                echo $root_link . "search/search_result/searchbysave/save_search/save_search_Id/" . $save_search_id . "/";
                            ?>"><span class="name"><?php echo $search->search_name ?></span></a>
                            <a class="name dspdp-col-xs-6 dsp-sm-4 dspdp-col-sm-2 dspdp-text-danger dsp-pull-right dsp-delete-action" href="<?php echo $root_link . "search/save_searches/Action/Del/search_Id/" . $save_search_id . "/"; ?>" onclick="if (!confirm('<?php echo language_code('DSP_DELETE_SAVE_SEARCH_MESSAGE'); ?>'))
                                        return false;"> <span class="delete"><?php echo language_code('DSP_DELETE_LINK'); ?></span></a>
                            <span class="type  dspdp-col-xs-6 dspdp-col-sm-4 dsp-sm-4 dspdp-text-right"><?php echo $search->search_type ?></span>
                        </li>
                    <?php } ?>
            </ul>
        <?php else: ?>
            <div class="heading-submenu"><strong><?php echo language_code('DSP_EMPTY'); ?></strong></div>
        <?php endif; ?>
        </div>
    </div>
</form>
