
<!--<div role="banner" class="ui-header ui-bar-a" data-role="header">
                    <div class="back-image">
                    <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
                    </div>
                <h1 aria-level="1" role="heading" class="ui-title"><?php
echo language_code('DSP_ADD_PHOTO_BUTTON');
;
?></h1>
                
</div>-->




<span style="padding-right:10px;float: left;">
    <a onclick="getPhoto();">
        <img src="<?php echo display_members_photo($user_id, $imagepath); ?>" style="width:100px; height:100px;" class="img" />
    </a>
</span>

<span>
    <div style="padding-bottom: 20px;">
        <input onclick="savePrivateStatus(this.value)" type="checkbox" value="Y" name="private"><?php echo language_code('DSP_PHOTO_MAKE_PRIVATE') ?>
    </div>
    <button onclick="getPhoto();"><?php echo language_code('DSP_BROWSE') ?></button>	
</span>