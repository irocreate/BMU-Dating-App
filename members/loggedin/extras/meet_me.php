<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ----------------------------------------------- Start Paging code------------------------------------------------------ //  
$age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : get('age_from');
$age_from = !empty( $age_from ) ? $age_from : '18';
$age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : get('age_to');
$age_to = !empty( $age_to ) ? $age_to : '90';
?>
<div class="heading-submenu dsp-block" style="display:none"><?php echo language_code('DSP_MEET_ME') ?></div>
<div class="box-border">
    <div class="box-pedding">
    <div class="dsp_meet-_me_container">
        <div id="dsp_meet_me_box">
            <?php
            $meet_me_content = wp_remote_get($pluginpath . "dsp_meet_me_box.php?user_id=$user_id");
            $meet_me_content = array_key_exists('body', $meet_me_content) ? $meet_me_content['body'] : '';
            echo $meet_me_content;
            ?></div>
			<div class="dspdp-seprator"></div>
        <div class="content-search" style="margin-bottom: 10px; ">
            <form id="dsp_change_criteria" action="" method="post" class="dspdp-form-inline dsp-gutter-sm">
                <div class="dsp-form-group" align="center">

                    <span class="dsp-md-1 dsp-control-label"><?php echo language_code('DSP_GENDER') ?>&nbsp;</span> 
                    <span class="dsp-md-2">  
                        <select name="gender" class="dspdp-form-control dsp-form-control">
                            <option value="all" <?php if ($gender == 'all') { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> ><?php echo language_code('DSP_OPTION_ALL') ?></option>
                            <?php 
                                $gender = isset($gender) && !empty($gender) ? $gender : $userProfileDetails->gender;
                                echo get_gender_list($gender); 
                            ?>
                        </select>
                    </span> 

                    <span class="dsp-md-1 dsp-control-label">  &nbsp;&nbsp;&nbsp;&nbsp;<?php echo language_code('DSP_AGE') ?> </span> 
                    <span class="dsp-md-1">  
                        <select name="age_from" class="dspdp-form-control">
                            <?php for ($i = '18'; $i <= '90'; $i++) { ?>
                                <option value="<?php echo $i ?>" <?php echo  $i == $age_from ? 'selected="selected"': '';?>><?php echo $i ?></option>
                            <?php } ?>


                        </select>
                    </span> 
                     
                                        
                    <span class="dsp-md-1 dsp-control-label">  
                        &nbsp;&nbsp;<?php echo language_code('DSP_TO') ?>
                    </span> 

                    <span class="dsp-md-1">  
                        <select  name="age_to" class="dspdp-form-control">
                            <?php for ($j = '90'; $j >= '18'; $j--) { ?>
                                <option value="<?php echo $j ?>" <?php  echo $j == $age_to ? 'selected="selected"': '';?>><?php echo $j ?></option>
                            <?php } ?>
                        </select>
                    </span> 

                    <span class="dsp-md-1 dsp-control-label">
                        <?php echo language_code('DSP_COUNTRY'); ?>
                    </span>
                    <span class="dsp-md-2">  
                        <select name="cmbCountry" class="dspdp-form-control">
                            <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY'); ?></option>
                            <?php
                            $countries = $wpdb->get_results("SELECT * FROM $dsp_country_table Order by name");
                            foreach ($countries as $country) {
                                ?>
                                <option value="<?php echo $country->name; ?>" ><?php echo $country->name; ?></option>
                            <?php } ?>
                        </select>
                    </span>  
                    
                    <span class="dsp-md-2">  
                        <input type="hidden" id="dsp_submitted_form" value="" />
                        <input class="dspdp-btn dspdp-btn-default" name="submit" type="submit" value="<?php echo language_code('DSP_FILTER_BUTTON') ?>" />
                     </span> 
            </form>
        </div>
    </div>
    </div>
</div>

</div>