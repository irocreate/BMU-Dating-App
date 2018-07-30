<?php
global $wpdb;
$dsp_gender_list = $wpdb->prefix . DSP_GENDER_LIST_TABLE;
?>
<script type="text/javascript">
    dsp = jQuery.noConflict();
    dsp(document).ready(function() {
        dsp(document).on('click', 'input[name="add_new_gender"]', function() {
            var new_gender = dsp('input[name="new_gender"]').val();
            var edit_gender_id = dsp('input[name="edit_gender_id"]').val();
            if (edit_gender_id != "") {
                var url = "<?php echo WPDATE_URL . '/files/dsp_tools_gender_list_changes.php'; ?>?new_gender=" + new_gender + "&action=update&edit_gender_id=" + edit_gender_id;
            }
            else {
                var url = "<?php echo WPDATE_URL . '/files/dsp_tools_gender_list_changes.php' ?>?new_gender=" + new_gender + "&action=add";
            }
            dsp.ajax({
                url: url,
                cache: false,
                success: function(html) {
                    //dsp('#gender_list').append(html);
                    if (edit_gender_id != "") {
                        dsp('#list_' + edit_gender_id).slideUp(500, function() {
                            dsp('#list_' + edit_gender_id + ' .title-name').html(html);
                            dsp('#list_' + edit_gender_id).slideDown(500);
                        });
                        dsp('input[name="new_gender"]').val("");
                        dsp('#add_text').html('Add:');
                        dsp('input[name="add_new_gender"]').val('Add');
                        dsp('input[name="edit_gender_id"]').val("");
                    }
                    else {
                        dsp(html).appendTo("#gender_list").hide().slideDown();
                        dsp('input[name="new_gender"]').val('');
                    }
                }
            });
            return false;
        });
        dsp(document).on('click', '#dsp_delete_gender', function() {
            var gender_id = dsp(this).attr('href');
            dsp.ajax({
                url: "<?php echo WPDATE_URL . '/files/dsp_tools_gender_list_changes.php'; ?>?gender_id=" + gender_id + "&action=delete",
                cache: false,
                success: function(html) {
                    if (html == 'done') {
                        dsp('#list_' + gender_id).animate({'height': 0}, 300, function() {
                            dsp('#list_' + gender_id).remove();

                        });

                    }

                }
            });
            return false;
        });

        dsp(document).on('click', '#dsp_edit_gender', function() {
            var gender_id = dsp(this).attr('href');
            dsp.ajax({
                url: "<?php echo WPDATE_URL . 'files/dsp_tools_gender_list_changes.php';  ?>?gender_id=" + gender_id + "&action=edit",
                cache: false,
                success: function(html) {
                    dsp('input[name="new_gender"]').val(html);
                    dsp('#add_text').html('<?php echo language_code('DSP_UPDATE'); ?>:');
                    dsp('input[name="add_new_gender"]').val('<?php echo language_code('DSP_UPDATE'); ?>');
                    dsp('input[name="edit_gender_id"]').val(gender_id);
                }
            });
            return false;
        });
    });
</script>
<div style="float:left; width:100%;" id="general" class="postbox">
    <h3 class="hndle"><span><?php echo language_code('DSP_GENDER'); ?></span></h3>
    <br />
    <div style="margin-top:0px;" class="dsp_thumbnails3" >
        <div >
            <div>
                <ul id="gender_list" class="gender_list">
                    <?php
                    $gender_list = $wpdb->get_results("select * from $dsp_gender_list");
                    foreach ($gender_list as $gender_row) {
                        if ($gender_row->editable == 'N') {
                            ?>
                            <li><span class="title-name"><?php echo language_code($gender_row->gender); ?></span></li>
                        <?php } else { ?>
                            <li id="list_<?php echo $gender_row->id; ?>"><span class="title-name"><?php echo $gender_row->gender; ?></span><span class="links-edit"><a class="edit" href="<?php echo $gender_row->id; ?>" id="dsp_edit_gender"><?php echo language_code('DSP_EDIT_GENDER'); ?></a> - <a href="<?php echo $gender_row->id; ?>" id="dsp_delete_gender"><?php echo language_code('DSP_DELETE_GENDER'); ?></a></span></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
                <ul class="gender_list">
                    <li><form>
                            <div class="dsp-row"><span class="title-name" id="add_text"><?php echo language_code('DSP_ADD'); ?>:</span>
                                <input type="text" name="new_gender" value="" /><input type="submit" name="add_new_gender" value="<?php echo language_code('DSP_ADD'); ?>" />
                                <input type="hidden" name="edit_gender_id" value="" />
                            </div>
                        </form></li>
                </ul>
            </div>
        </div>
    </div>
</div>
