<?php
//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

global $wpdb;

$dsp_interest_tags_table = $wpdb->prefix . DSP_INTEREST_TAGS_TABLE;

$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

$dsp_temp_interest_tags_table = $wpdb->prefix . DSP_TEMP_INTEREST_TAGS_TABLE;

$words = array();
$words_link = array();

$userId = $user_id;
$gender = "";

if (isset($_REQUEST['gender'])) {

//	mysql_query("truncate table $dsp_temp_interest_tags_table");

    $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';

    $age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : '';

    $age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : '';
    ?>
    <div style="width: 100%;padding-bottom: 10px;float: left;" align="left">
        <form id="dsp_trending">

            <div><label class="interest-space"><?php echo language_code('DSP_GENDER') ?></label>
            <input type="hidden" value="<?php echo $user_id; ?>"   name="user_id" />
            <input type="hidden" value="<?php echo $extra_pageurl; ?>"   name="pagetitle" />

            <select name="gender">

                <option value="all" <?php if ($gender == 'all') { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> ><?php echo language_code('DSP_OPTION_ALL') ?></option>

                <option value="M" <?php if ($gender == 'M') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_MALE') ?></option>

                <option value="F" <?php if ($gender == 'F') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_FEMALE') ?></option>

                <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                    <option value="C" <?php if ($gender == 'C') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_COUPLE') ?></option>

                <?php } ?>

            </select><br>
            </div>
            <div>
            <label class="interest-space"><?php echo language_code('DSP_AGE') ?></label>

            <select name="age_from">

                <?php for ($i = '18'; $i <= '90'; $i++) { ?>

                    <option value="<?php echo $i ?>"><?php echo $i ?></option>

                <?php } ?>
            </select>
            &nbsp;&nbsp;
            <?php echo language_code('DSP_TO') ?>
            &nbsp;&nbsp;
            <select  name="age_to">

                <?php for ($j = '90'; $j >= '18'; $j--) { ?>

                    <option value="<?php echo $j ?>"><?php echo $j ?></option>

                <?php } ?>
            </select>
            </div>
            <div>
            <input name="submit" class="btn-filter" type="button" onclick="viewExtra(0, 'post')" value="<?php echo language_code('DSP_FILTER_BUTTON') ?>" />
            </div>
        </form>
    </div>
    <div style="width: 100%;padding-bottom: 10px;float: left;" align="left">


        <?php
        $strQuery = "SELECT user_id, my_interest FROM $dsp_user_profiles_table where my_interest != '' ";

        if ($age_from >= 18) {
            $strQuery .= " and ((year(CURDATE())-year(age)) > '" . $age_from . "') AND ((year(CURDATE())-year(age)) < '" . $age_to . "') AND ";
        }

        if ($gender == 'M') {
            $strQuery .= " gender='M'  ";
        } else if ($gender == 'F') {
            $strQuery .= " gender='F'  ";
        } else if ($gender == 'C') {
            $strQuery .= " gender='C'  ";
        } else if ($gender == 'all') {
            $strQuery .= " gender IN('M','F','C') ";
        }
        $strQuery .="LIMIT 20";
        //echo 'query==='. $strQuery; 

        $user_profiles_table = $wpdb->get_results($strQuery);
        ?>

        <?php
        $my_interest = "";
        $j = 0;
        foreach ($user_profiles_table as $user_profiles) {
            if ($j != 0) {
                $my_interest = $my_interest . ',' . strtolower(trim($user_profiles->my_interest));
            } else {
                $my_interest = $my_interest . strtolower(trim($user_profiles->my_interest));
            }
            $user_id = $user_profiles->user_id;
            $j++;
            //$count = count($posts);
        }


        $posts = explode(",", $my_interest);

        $temp_table = array_unique($posts);
        shuffle($temp_table);

        //print_r($temp_table);
        // foreach ($temp_table as $temp)
        for ($i = 0; $i < sizeof($temp_table); $i++) {

            $keyword = $temp_table[$i];

            $interest_tags_table = "SELECT keyword,weight,link FROM " . $dsp_interest_tags_table . " ORDER BY rand()";

            $resultset = $wpdb->get_results($interest_tags_table);

            foreach ($resultset as $row) {
                $words[$row->keyword] = $row->weight;

                $words_link[$row->keyword] = $row->link;
            }

            // Incresing this number will make the words bigger; Decreasing will do reverse

            $factor = 0.3;



// Smallest font size possible

            $starting_font_size = 12;



// Tag Separator

            $tag_separator = '&nbsp; &nbsp; &nbsp;';

            $max_count = array_sum($words);



            foreach ($words as $tag => $weight) {

                $x = round(($weight * 100) / $max_count) * $factor;

                $font_size = $starting_font_size + $x . 'px';

                $tag;





                if (strtolower(trim($keyword)) == strtolower(trim($tag))) {

                    if ($words_link[$tag] == 'NA') {
                        ?>
                        <span style='font-size:<?php echo $font_size ?>; color: #676F9D;float:left;'>
                            <form id="frm_<?php echo $tag; ?>">
                                <input type="hidden" value="myinterest_search_result" name="pagetitle" />
                                <input type="hidden" value="my_interest" name="search_type" />
                                <input type="hidden" value="<?php echo $tag; ?>" name="my_int" />
                                <input type="hidden" value="<?php echo $gender; ?>" name="gender" />
                                <input type="hidden" value="<?php echo $age_to; ?>" name="age_to" />
                                <input type="hidden" value="<?php echo $age_from; ?>" name="age_from" />
                                <input type="hidden" value="<?php echo $userId; ?>" name="user_id" />

                                <a onclick='callInterestSearch("frm_<?php echo $tag; ?>", "post_interest")'><?php echo $tag ?></a><?php echo $tag_separator; ?>
                            </form>
                        </span>

                        <?php
                    } else {
                        ?>
                        <span style='font-size:<?php echo $font_size ?>; color: #676F9D;float:left;'>
                            <form id="frm_<?php echo $tag; ?>">
                                <input type="hidden" value="myinterest_search_result" name="pagetitle" />
                                <input type="hidden" value="my_interest" name="search_type" />
                                <input type="hidden" value="<?php echo $tag; ?>" name="my_int" />
                                <input type="hidden" value="<?php echo $userId; ?>" name="user_id" />
                                <a onclick='callInterestSearch("frm_<?php echo $tag; ?>", "post_interest")' ><?php echo $tag ?></a>
                                <?php $tag_separator; ?>
                            </form>
                        </span>

                        <?php
                    }
                }
            }
        }
        ?>

        <?php //}   ?></div>
    <?php
} else {
    ?>
    <div style="width: 100%;padding-bottom: 10px;float: left;" align="left">

        <form id="dsp_div_cloud">
            <label class="interest-space"><?php echo language_code('DSP_GENDER') ?></label>
           
            <input type="hidden" value="<?php echo $user_id; ?>"   name="user_id" />
            <input type="hidden" value="interest_cloud"   name="pagetitle" />
            <select name="gender">

                <option value="all" <?php if ($gender == 'all') { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> >All</option>

                <option value="M" <?php if ($gender == 'M') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_MALE') ?></option>

                <option value="F" <?php if ($gender == 'F') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_FEMALE') ?></option>

                <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                    <option value="C" <?php if ($gender == 'C') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_COUPLE') ?></option>

                <?php } ?>

            </select>
            
            <br>
            <div><label class="interest-space"> <?php echo language_code('DSP_AGE') ?></label>
            <select name="age_from">

                <?php for ($i = '18'; $i <= '90'; $i++) { ?>

                    <option value="<?php echo $i ?>"><?php echo $i ?></option>

                <?php } ?>
            </select>
            &nbsp;&nbsp;
            to
            <select  name="age_to">

                <?php for ($j = '90'; $j >= '18'; $j--) { ?>

                    <option value="<?php echo $j ?>"><?php echo $j ?></option>

                <?php } ?>
            </select>
            </div>
            <div>
            <input name="submit" class="btn-filter" type="button" value="Filter"   onclick="ExtraLoad('div_cloud', 'true')"/>
            </div>

        </form>
    </div>
    <?php
}?>