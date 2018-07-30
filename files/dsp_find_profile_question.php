<?php

/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_question_options_table = $wpdb->prefix . dsp_question_options;
$question_id = $_GET['question_id'];
if ($question_id != "" && $question_id != 'undefined') {
    $myrows = $wpdb->get_results("SELECT * FROM $dsp_question_options_table WHERE question_id = $question_id Order by sort_order");
    foreach ($myrows as $profile_questions) {
        echo $profile_questions->option_value;
    }
}
