<?php
@include_once('../../../../wp-config.php');
include(WP_DSP_ABSPATH . "general_settings.php");
include(WP_DSP_ABSPATH . "include_dsp_tables.php");
global $wpdb;
$dsp_gender_list = $wpdb->prefix . DSP_GENDER_LIST_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_user_search_criteria_table = $wpdb->prefix . DSP_USER_SEARCH_CRITERIA_TABLE;
$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
$dsp_match_alert_criteria_table = $wpdb->prefix . DSP_MATCH_CRITERIA_TABLE;
extract($_REQUEST);
if ($action == 'add') {
    if ($new_gender != "") {
        $enum_char = get_enum($new_gender, 1);
        $check_gender_exist = $wpdb->get_var("select count(*) from $dsp_gender_list where gender like '%$new_gender%'");
        if ($check_gender_exist == 0) {
            $insert = $wpdb->insert($dsp_gender_list, array('gender' => $new_gender,
                'enum' => $enum_char, 'editable' => 'Y'));
            $gender_id = $wpdb->insert_id;
            ?>
            <li id="list_<?php echo $gender_id; ?>"><span class="title-name"><?php echo $new_gender; ?></span><span class="links-edit"><a href="<?php echo $gender_id; ?>" id="dsp_edit_gender"><?php echo language_code('DSP_EDIT_GENDER'); ?></a> - <a href="<?php echo $gender_id; ?>" id="dsp_delete_gender"><?php echo language_code('DSP_DELETE_GENDER'); ?></a></span></li>
            <?php
        }
    }
}
if ($action == 'update') {
    if ($new_gender != "") {
        $enum_char = get_enum($new_gender, 1);
        $update = $wpdb->update($dsp_gender_list, array('gender' => $new_gender,
            'enum' => $enum_char, 'editable' => 'Y'), array('id' => $edit_gender_id));
        ?>
        <?php echo $new_gender; ?>
        <?php
    }
}
if ($action == 'delete') {

    $wpdb->delete($dsp_gender_list, array('id' => $gender_id));
    echo 'done';
}
if ($action == 'edit') {
    $gender = $wpdb->get_var("select gender from $dsp_gender_list where id='$gender_id'");
    echo $gender;
}
if ($action == 'update' || $action == 'add' || $action == 'delete') {
    $enum_list = $wpdb->get_results("select enum from $dsp_gender_list");
    $enum = "";
    foreach ($enum_list as $enum_row) {
        $enum.="'" . $enum_row->enum . "',";
    }
    $enum = rtrim($enum, ',');
    $wpdb->query("ALTER TABLE `$dsp_user_profiles` CHANGE `gender` `gender` ENUM(" . $enum . ") CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `seeking` `seeking` ENUM(" . $enum . ") CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL");

    $wpdb->query("ALTER TABLE `$dsp_user_search_criteria_table` CHANGE `seeking_gender` `seeking_gender` ENUM(" . $enum . ") CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `user_gender` `user_gender` ENUM(" . $enum . ") CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL");

    $wpdb->query("ALTER TABLE `$dsp_user_partner_profiles_table` CHANGE `gender` `gender` ENUM(" . $enum . ") CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL, CHANGE `seeking` `seeking` ENUM(" . $enum . ") CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL");

    $wpdb->query("ALTER TABLE `$dsp_match_alert_criteria_table` CHANGE `gender` `gender` ENUM(" . $enum . ") CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL");
}

function get_enum($gender, $i = 1) {
    global $wpdb;
    $dsp_gender_list = $wpdb->prefix . DSP_GENDER_LIST_TABLE;
    $enum = array();
    $enum_list = $wpdb->get_results("select enum from $dsp_gender_list");
    foreach ($enum_list as $enum_row) {
        $enum[] = $enum_row->enum;
    }
    $upper_str = strtoupper($gender);
    $new_enum = substr($upper_str, 0, $i);
    if (in_array($new_enum, $enum)) {
        $newenum = get_enum($gender, $i + 1);
    } else {
        $newenum = $new_enum;
    }

    return $newenum;
}
?>