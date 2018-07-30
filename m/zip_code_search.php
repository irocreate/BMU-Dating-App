<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_SEARCH'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>	
</div>
<form id="frmsearch">

    <div class="ui-content" data-role="content">
        <div class="content-primary">	

            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                    <input type="hidden" name="pagetitle" value="zipcode_search_result" />

                    <input type="hidden" name="zipcode_search" value="zipcode_search" />

                    <?php //---------------------------------START  GENERAL SEARCH--------------------------------------- ?>


                    <div style=" width:100%; margin-bottom: 5px; float:left; ">


                        <ul class="zip-search">
                            <li>
                                <div style="width:78px;float: left;"><?php echo language_code('DSP_GENDER') ?></div>

                                <select name="gender">

                                    <option value="all" <?php if ($gender == 'all') { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> >All</option>

                                    <option value="M" <?php if ($gender == 'M') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_MALE') ?></option>

                                    <option value="F" <?php if ($gender == 'F') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_FEMALE') ?></option>

                                    <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                                        <option value="C" <?php if ($gender == 'C') { ?> selected="selected" <?php } ?> ><?php echo language_code('DSP_COUPLE') ?></option>

                                    <?php } ?>

                                </select>
                            </li>
                            <li>
                                <div style="width:78px;float: left;">
                                    <?php echo language_code('DSP_AGE') ?>
                                </div> 
                                <div style="width:60px;float: left;">
                                    <select name="age_from">
                                        <?php for ($i = '18'; $i <= '90'; $i++) { ?>

                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>

                                        <?php } ?>
                                    </select>
                                </div>
                                <div style="width:28px;float: left;"><?php echo language_code('DSP_TO') ?></div> 			
                                <select  name="age_to">

                                    <?php for ($j = '90'; $j >= '18'; $j--) { ?>

                                        <option value="<?php echo $j ?>"><?php echo $j ?></option>

                                    <?php } ?>
                                </select>
                            </li>
                            <li >

                            </li>
                            <li></li>
                        </ul>

                    </div>

                    <div class="search-page-zip">
                        <ul>
                            <li>
                                <div style="width:78px;float: left;">
                                    <strong><?php echo language_code('DSP_MENU_SEARCH') ?>: </strong> 
                                </div>

                                <input style="width:28.5%;" type="text" name="miles"/>
                                <strong><?php echo language_code('DSP_MILES') . ' ' . language_code('DSP_FROM_TEXT') ?></strong>
                            </li>
                            <li>
                                <div style="width:78px;float: left;">
                                    <strong><?php echo language_code('DSP_ZIP_CODE'); ?>:</strong>
                                </div>

                                <input style="width:28.5%;" type="text" name="zip_code"/>
                                <input type="hidden" value="<?php echo $user_id ?>" name="user_id" />
                            </li>
                            <li>
                                <div style="width:75px;float: left;">&nbsp;
                                </div>
                                <input type="button" name="zip_submit" onclick="viewSearch(0, 'post')" value="<?php echo language_code('DSP_SUBMIT_BUTTON'); ?>" />
                            </li>
                        </ul>

                    </div>


                </li>
            </ul>
        </div>
        <?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
    </div>



</form>



<?php
//-------------------------------------END ADDITIONAL OPTIONS SEARCH -------------------------------------// ?>