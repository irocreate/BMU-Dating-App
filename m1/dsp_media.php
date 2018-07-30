<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_menu.php");?>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_PHOTOS'); ?></h1>
    <?php include_once("page_home.php");?>

</div>


<div class="ui-content" data-role="content">
    <div class="content-primary">	 
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all menu-list">

            
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                    <a onclick="changePhotoPage('album')">
                    <img src="images/icons/create.png" />
                    <?php echo language_code('DSP_MENU_CREATE_ALBUM'); ?>
                     </a>
                </li>
           
           
                <li data-corners="false" data-shadow="false"  data-wrapperels="div" class="ui-body ui-body-d ui-corner-all">
                     <a onclick="changePhotoPage('photo')">
                     <img src="images/icons/upload.png" />
                    <?php echo language_code('DSP_MENU_PHOTOS'); ?>
                    </a>	
                </li>
            
        </ul>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
</div>
<?php include_once("dspLeftMenu.php"); ?>


<!--</body>-->