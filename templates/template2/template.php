<?php ######## Template header Sections ######### ?>
<?php include_once(WP_DSP_ABSPATH . 'templates/layouts/header.php'); ?>

<div class="home-page dsp-template-2">
    <div class="home-container">
    <div class="user-info-home">
        <div class="top-slider-info dspdp-featured">
            <div class="join-info search_box dspdp-search-box">
                <?php /* ?><div class="heading-bar"><b class="changebox" id="searchbox"><?php echo language_code('DSP_QUICK_SEARCH');?></b> <?php if(!is_user_logged_in()){?><b class="reg_popoup" id="freebox" title=""><?php echo language_code('DSP_DZONIA_REGISTER_HEADING_TEXT');?></b><?php }?></div><?php */ ?>

                <div class="join-box  join-searchbox dspdp-row">
                    <div class="dspdp-col-sm-2 dsp-sm-2 dspdp-h2 dsp-h2 dspdp-bold dsp-bold"><?php echo language_code('DSP_JOIN_ITS_FREE'); ?><!-- <img class="dspdp-search-title-img dspdp-img-responsive" src="<?php echo plugins_url('dsp_dating/templates/template2/images/join-free.jpg'); ?>"  /> --></div>
                        <?php  include_once(WP_DSP_ABSPATH . 'templates/layouts/search_form_based_on_settings.php'); ?>
                    </div>
                    <div class="join-freeboxx"  style="display:none;"><div class="join-info"><div class="join-box join-freebox">
                                <script>
                                    dsp = jQuery.noConflict();
                                    dsp(document).ready(function() {
                                        dsp(".login-btn").click(function() {
                                            dsp("#dialog").dialog({height: 344, width: 412});
                                        });

                                       /* dsp('#register').click(function() {
                                            var alert_text = "";
                                            var username = dsp('#username').val();
                                            if (jQuery.trim(username).length == 0) {
                                                alert_text += "<?php echo language_code('DSP_PLEASE_ENTER_USER_NAME'); ?>\n";
                                             }
                                            var email = dsp('input[name="email"]').val();
                                            if (jQuery.trim(email).length == 0) {
                                                    alert_text += "<?php echo language_code('DSP_PLEASE_ENTER_EMAIL_ADDRESS'); ?>\n";
                                            }
                                            var confirm_email = dsp('input[name="confirm_email"]').val();
                                            if (jQuery.trim(email).length == 0) {
                                                    alert_text += "<?php echo language_code('DSP_PLEASE_ENTER_CONFIRM_EMAIL_ADDRESS'); ?>\n";
                                            }
                                            <?php if ($check_terms_page_mode->setting_status == 'Y') { ?>
                                                    if (!dsp('input[name="terms"]').is(':checked')) {
                                                        alert_text += "<?php echo language_code('DSP_CHECK_TERM_CONDITIONS'); ?>\n";
                                                   }
                                            <?php } ?>
                                            if (alert_text != "") {
                                                alert(alert_text);
                                                return false;
                                            }
                                            document.regfrm.submit();*/
                                        });
                                   // });
                                </script>
                            <?php ######### register form  section ############ ?>
                                <?php include_once(WP_DSP_ABSPATH . 'templates/layouts/register_form.php');?>
                            <?php ######## end register form ############# ?>



                            </div></div></div>


                </div>
                  <?php
                    $sliderClass = "slider";
                    $template_image = '';
                    $numImages = '';
                    $status = 'Y';
                    $template_image = dsp_get_all_template_image('', $status);
                    $numImages = count($template_image);
                    if ($numImages > 0) {
                        $sliderClass = " ";
                        if ($numImages > 1) {
                            $sliderClass = "slider";
                        }
                    }
                   ?>
            <div class="<?php echo $sliderClass; ?> dspdp-slider">  
                <ul id="demo1" class="dspdp-reset-strict">
                    <?php  if (empty($template_image)): ?> 
                            <li class="dspdp-reset-strict"> <img class="dspdp-slider-img" src="<?php echo WPDATE_URL . '/templates/common_images/dating3.jpg' ?>"  alt="Dating Image 1"/></li>    
                            <li class="dspdp-reset-strict"><img class="dspdp-slider-img" src="<?php echo WPDATE_URL . '/templates/common_images/dating4.jpg' ?>" alt="Dating Image 2" /></li>
                            <li class="dspdp-reset-strict"><img class="dspdp-slider-img" src="<?php echo WPDATE_URL . '/templates/common_images/dating5.jpg' ?>" alt="Dating Image 3"/></li>
                   <?php else:?>
                         <?php 
                            foreach ($template_image as $img) :
                               $id = $img->id;
                               $imagePath = $img->url;
                               $caption = $img->caption;
                         ?>
                            <li class="dspdp-reset-strict"> <img src="<?php echo $imagePath; ?>"  alt="<?php echo $caption; ?>" /> </li>
                                
                   <?php    endforeach;
                        endif; 
                   ?> 
                </ul>
            </div>               
        </div>
        <?php 
        // New member section 
        if (is_array($member_elements_values) && in_array('N',$member_elements_values)):
            include_once(WP_DSP_ABSPATH . 'templates/layouts/new_member.php');
        endif;
        if (is_array($member_elements_values) && in_array('F',$member_elements_values)):
            include_once(WP_DSP_ABSPATH . 'templates/layouts/featured_member.php');
        endif;
        ?>
        <div class="fullwidth dspdp-row">
            <?php  
                // online member  section 
                if (is_array($member_elements_values) && in_array('O',$member_elements_values)):
                    include_once(WP_DSP_ABSPATH . 'templates/layouts/online_members.php');
                endif; 
            ?>
            <?php
                // Happy Stories  section/
                 if (is_array($member_elements_values) && in_array('H',$member_elements_values)):
                    include_once(WP_DSP_ABSPATH . 'templates/layouts/happy_stories.php');
                 endif;
            ?>

            <?php 
                // Latest Blogs  section 
                if (is_array($member_elements_values) && in_array('L',$member_elements_values)):
                    include_once(WP_DSP_ABSPATH . 'templates/layouts/latest_blogs.php');
                endif; 
            ?>
        </div>
    </div>
</div>
<?php
 //Login form  section
 include_once(WP_DSP_ABSPATH . 'templates/layouts/login_form.php');
 ?> 
