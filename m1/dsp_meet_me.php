<div id="dsp_meet_me_box">
    <?php
    $dsp_meet_me = $wpdb->prefix . "dsp_meet_me";

    $member_id = isset($_REQUEST['member_id']) ? $_REQUEST['member_id'] : "";
    if (isset($_REQUEST['action'])) {
        $datetime = date('Y-m-d H:i:s');
        $action = $_REQUEST['action'];

        $check_row = $wpdb->get_var(" select count(*) from $dsp_meet_me where user_id='$user_id' and member_id='$member_id'");

        if ($check_row == 0) {

            $insert = $wpdb->query("insert into $dsp_meet_me set user_id=$user_id,member_id =$member_id,status ='$action',datetime ='$datetime'");
        }
    }


    $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : "";
    $age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : "";
    $age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : "";

    $pluginpath = get_bloginfo('url') . '/wp-content/plugins/dsp_dating/';
    $meet_me_content = wp_remote_get($pluginpath . "/m1/dsp_meet_me_box.php?user_id=$user_id&gender=$gender&age_from=$age_from&age_to=$age_to&pagetitle=$extra_pageurl");
    $meet_me_content = array_key_exists('body', $meet_me_content) ? $meet_me_content['body'] : '';
    echo $meet_me_content;
    ?>
</div>


<ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
    <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

        <form id="dsp_div_meetme">

            <label data-role="fieldcontain" class="select-group">
                <div class="clearfix">

                    <div class="mam_reg_lf select-label"><?php echo language_code('DSP_GENDER') ?></div>

                    <select name="gender">

                        <option value="all" <?php if ($gender == 'all') { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> ><?php echo language_code('DSP_OPTION_ALL') ?></option>

                        <option value="M" <?php if ($gender == 'M') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_MALE') ?></option>

                        <option value="F" <?php if ($gender == 'F') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_FEMALE') ?></option>

                        <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                            <option value="C" <?php if ($gender == 'C') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_COUPLE') ?></option>

                        <?php } ?>

                    </select>
                </div>
            </label>

            <div class="heading-text"><?php echo language_code('DSP_AGE') ?></div>
            <div class="col-cont clearfix">
                <div class="col-2">
                    <label class="select-group">
                        <select name="age_from">

                            <?php for ($i = '18'; $i <= '90'; $i++) { ?>

                                <option value="<?php echo $i ?>"><?php echo $i ?></option>

                            <?php } ?>
                        </select>
                    </label>

                </div>
                <div class="col-2">
                    <label class="select-group">

                        <div  class="mam_reg_lf select-label">
                            <select  name="age_to">

                                <?php for ($j = '90'; $j >= '18'; $j--) { ?>

                                    <option value="<?php echo $j ?>"><?php echo $j ?></option>

                                <?php } ?>
                            </select>
                        </div>
                    </label>
                </div>
            </div>

            <input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
            <input type="hidden" name="pagetitle" value="meet_me" />
            <div class="btn-blue-wrap">
                <input name="submit" type="button" class="mam_btn btn-blue" onclick="ExtraLoad('div_meetme', 'true')" value="<?php echo language_code('DSP_FILTER_BUTTON') ?>" />
            </div>
        </form>
    </li>
</ul>		