<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_interest_tags_table = $wpdb->prefix . DSP_INTEREST_TAGS_TABLE;
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_temp_interest_tags_table = $wpdb->prefix . DSP_TEMP_INTEREST_TAGS_TABLE;

$words = array();
$words_link = array();
$output = '';
    $wpdb->query("truncate table $dsp_temp_interest_tags_table");
    $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
    $age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : '';
    $age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : '';
    ?>
<div class="heading-submenu dsp-block" style="display:none"><?php echo language_code('DSP_INTEREST_CLOUD') ?></div>
            <div class="box-border">
                <div class="box-pedding">
                    <div class="content-search dsp-gutter-sm">
                        <form action="" method="post" class="dspdp-form-inline dspdp-spacer">
                            <div class="dsp-form-group">
                                <div align="center">
                                    <span class="dsp-sm-1 dsp-control-label"><?php echo language_code('DSP_GENDER') ?>&nbsp;</span>
                                    <span class="dsp-sm-2">
                                        <select class="dspdp-form-control dsp-form-control" name="gender">
                                            <option value="all" <?php if ($gender == 'all') { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> ><?php echo language_code('DSP_OPTION_ALL') ?></option>
                                            <?php 
                                                $gender = isset($gender) && !empty($gender) ? $gender : $userProfileDetails->gender;
                                                echo get_gender_list($gender); 
                                            ?>
                                        </select>
                                    </span>
                                   <!--  <span class="dsp-sm-1 dsp-control-label">
                                       &nbsp;&nbsp;&nbsp;&nbsp;<?php echo language_code('DSP_AGE') ?>
                                   </span>
                                   <span class="dsp-sm-2">
                                       <select class="dspdp-form-control dsp-form-control" name="age_from">
                                           <?php for ($i = '18'; $i <= '90'; $i++) { ?>
                                               <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                           <?php } ?>
                                       </select>
                                   </span>
                                   &nbsp;&nbsp;
                                   <span class="dsp-sm-1 dsp-control-label"><?php echo language_code('DSP_TO') ?> </span>
                                   <span class="dsp-sm-2">
                                       <select  class="dspdp-form-control dsp-form-control" name="age_to">
                                           <?php for ($j = '90'; $j >= '18'; $j--) { ?>
                                               <option value="<?php echo $j ?>"><?php echo $j ?></option>
                                           <?php } ?>
                                       </select>
                                   </span> -->
                                    <span class="dsp-sm-2">
                                        <input class="dspdp-btn dspdp-btn-default" name="submit" type="submit" value="<?php echo language_code('DSP_FILTER_BUTTON') ?>" />
                                    </span>
                            </div>
                        </form></div>
                </div>
                <div class="dspdp-text-center">
                <?php
                $interest_tags_table = "SELECT DISTINCT(keyword) FROM " . $dsp_interest_tags_table . " ORDER BY rand() LIMIT 0,3";
                $interests = $wpdb->get_results($interest_tags_table);
                $intrst = array();
                foreach ($interests as $key => $interest) {
                    $intrst[] = $interest->keyword;
                }
                $interst = "'";
                $interst .= implode("','",$intrst);
                $interst .= "'";
                $strQuery = "SELECT user_id, my_interest FROM $dsp_user_profiles_table  where my_interest IN ($interst) AND ";
                if ($age_from >= 18) {
                    $strQuery .= " AND  ((year(CURDATE())-year(age)) > '" . $age_from . "') AND ((year(CURDATE())-year(age)) < '" . $age_to . "') AND ";
                }

                if ($gender == 'M') {
                    $strQuery .= " gender='M'  ";
                } else if ($gender == 'F') {
                    $strQuery .= " gender='F'  ";
                } else if ($gender == 'C') {
                    $strQuery .= " gender='C'  ";
                } else if ($gender == 'all') {
                    $strQuery .= " gender IN('M','F','C')  ";
                }
                $strQuery .= " LIMIT 0, 10 ";
                $user_profiles_table = $wpdb->get_results($strQuery);
                $resultset = array();
                foreach ($user_profiles_table as $user_profiles) {
                    $my_interest = $user_profiles->my_interest;
                    $user_id = $user_profiles->user_id;
                    $posts = explode(",", $my_interest);
                    $count = count($posts);
                    
                    $i = 0;
                    for ($i = $i; $i < $count; $i++) {
                        $r = $posts[$i];
                        $text = strtolower(trim($r));
                        $wpdb->query("INSERT INTO $dsp_temp_interest_tags_table SET keyword = '$text', user_id=$user_id ");
                    }
                }
                $temp_table = $wpdb->get_results("select distinct  keyword  from $dsp_temp_interest_tags_table ");
                foreach ($temp_table as $temp) {
                        $keyword = $temp->keyword;
                        $interest_tags_table = 'SELECT keyword,weight,link FROM ' . $dsp_interest_tags_table . ' WHERE `keyword` LIKE "%' . $keyword . '%"';
                        $resultset[] = $wpdb->get_row($interest_tags_table,ARRAY_N);
                        if ($resultset) {
                            //while ($row = mysql_fetch_row($resultset)) {
                            foreach ($resultset as $row) {
                                $words[$row[0]] = $row[1];
                                $words_link[$row[0]] = $row[2];
                            }
                        }
                        // Incresing this number will make the words bigger; Decreasing will do reverse
                        $factor = 0.3;
                        // Smallest font size possible
                        $starting_font_size = 12;

                        // Tag Separator
                        $tag_separator = '&nbsp; &nbsp; &nbsp;';
                        $max_count = array_sum($words);
                        $max_count = empty($max_count) ? 1 : $max_count;
                        foreach ($words as $tag => $weight) {
                            $x = round(($weight * 100) / $max_count) * $factor;
                            $font_size = $starting_font_size + $x . 'px';
                            $tag;
                            if (strtolower(trim($keyword)) == strtolower(trim($tag))) {

                                if ($words_link[$tag] == 'NA')
                                    $output .= "<span style='font-size: " . $font_size . "; color: #676F9D;'>
            	                                <a href='" . $root_link . "search/myinterest_search_result/search_type/my_interest/my_int/$tag/gender/$gender/age_to/$age_to/age_from/$age_from/'>" . $tag . "</a></span>" . $tag_separator;
                                else
                                    $output .= "<span style='font-size: " . $font_size . "; color: #676F9D;'><a href='" . $root_link . "search/myinterest_search_result/search_type/my_interest/my_int/$tag/'>" . $tag . "</a></span>" . $tag_separator;
                            }
                        }
                    }
                if(!empty($output)):
                   echo $output;
                else:
                ?>
              
                        <div class="page-not-found">
                             <?php echo language_code('DSP_NO_RESULT_FOUND'); ?>
                            <br/>
                            <br/>
                        
                </div>
            <?php endif;?>
        </div>
    </div>
</div>   
