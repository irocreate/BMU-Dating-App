<div role="banner" class="ui-header ui-bar-a" data-role="header">
     <?php include_once("page_back.php");?>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_SEARCH'); ?></h1>
     <?php include_once("page_home.php");?>
</div>
<form id="frmsearch">

    <div class="ui-content" data-role="content">
        <div class="content-primary">	


            <input type="hidden" name="pagetitle" value="zipcode_search_result" />

            <input type="hidden" name="zipcode_search" value="zipcode_search" />

            <?php //---------------------------------START  GENERAL SEARCH--------------------------------------- ?>

            <label data-role="fieldcontain" class="select-group">  
                <div class="clearfix">                                    
                    <div class="mam_reg_lf select-label"><?php echo language_code('DSP_GENDER') ?></div>

                    <select name="gender">

                        <option value="all" <?php if ($gender == 'all') { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> >All</option>

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

       <label data-role="fieldcontain" class="form-group">  
                        <div class="clearfix">                                    
                            <div class="mam_reg_lf form-label"> <?php echo language_code('DSP_MENU_SEARCH') ." ".language_code('DSP_MILES') . ' ' . language_code('DSP_FROM_TEXT') ?></div>
                    <input  type="text" name="miles"/>
                    </div>
                    </label>
                        <label data-role="fieldcontain" class="form-group">  
                        <div class="clearfix">                                    
                            <div class="mam_reg_lf form-label"><?php echo language_code('DSP_ZIP_CODE'); ?>
                    </div>

                    <input style="width:28.5%;" type="text" name="zip_code"/>
                    </div>
                    </label>
                    <input type="hidden" value="<?php echo $user_id ?>" name="user_id" />
                     <input type="hidden" name="search_type" value="zipcode_search"/>
                   <div class="btn-blue-wrap">
                    <input type="button" name="zip_submit" class="mam_btn btn-blue"  onclick="viewSearch(0, 'post')" value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>" />
                </div>
           </div>
</div>
<?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
</div>



</form>



<?php
//-------------------------------------END ADDITIONAL OPTIONS SEARCH -------------------------------------// ?>