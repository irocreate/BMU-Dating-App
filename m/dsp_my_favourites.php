<?php
include("../../../../wp-config.php");
//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->

/* To off  display error or warning which is set of in wp-confing file ---
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */
include_once("dspFunction.php");

include_once("../general_settings.php");

$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;

$user_id = $_REQUEST['user_id'];

// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a href="#"  data-rel="back"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MY_FAVOURITES'); ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>
</div>
<?php
$delfavourites = isset($_REQUEST['favourite_Id']) ? $_REQUEST['favourite_Id'] : '';



$Actiondel = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';



if (($delfavourites != "") && ($Actiondel == "Del")) {



    $wpdb->query("DELETE FROM $dsp_user_favourites_table where favourite_id = '$delfavourites'");
}



$total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_favourites_table where user_id='$user_id'");



if ($total_results1 > 0) {
    ?>
    <div class="ui-content" data-role="content">
        <div class="content-primary">	 
            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">

                <form name="delfavoritesfrm" action="" method="post">
                    <?php
                    if ($check_couples_mode->setting_status == 'Y') {

                        $my_favourites = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table favourites, $dsp_user_profiles profile WHERE favourites.favourite_user_id = profile.user_id
				
					AND favourites.user_id = '$user_id' ORDER BY favourites.fav_date_added");
                    } else {

                        $my_favourites = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table favourites, $dsp_user_profiles profile WHERE favourites.favourite_user_id = profile.user_id
					
					AND favourites.user_id = '$user_id' AND profile.gender!='C' ORDER BY favourites.fav_date_added");
                    }



                    $i = 0;



                    foreach ($my_favourites as $favourites) {
                        $favourite_id = $favourites->favourite_id;
                        $fav_user_id = $favourites->favourite_user_id;
                        $fav_screenname = $favourites->fav_screenname;
                        $favt_mem = array();

                        $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$fav_user_id'");

                        foreach ($private_mem as $private) {
                            $favt_mem[] = $private->favourite_user_id;
                        }
                        $displayed_member_name = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$fav_user_id'");
                        ?>

                        <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                            <div class="dsp_pro_full_view">
                                <div class="profile_img_view">
                                    <?php
                                    if ($check_couples_mode->setting_status == 'Y') {
                                        if ($favourites->gender == 'C') {
                                            if ($favourites->make_private == 'Y') {
                                                if ($user_id != $fav_user_id) {
                                                    if (!in_array($user_id, $favt_mem)) {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $fav_user_id; ?>', 'my_profile')" >
                                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:100px; height:100px;"  border="0" class="dsp_img3" />
                                                        </a>                
                                                    <?php } else {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $fav_user_id; ?>', 'my_profile')" >				
                                                            <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;"/></a>                
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a onclick="viewProfile('<?php echo $fav_user_id; ?>', 'my_profile')" >				
                                                        <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;"/></a>                
                                                <?php } ?>
                                                <?php
                                            } else {
                                                ?>                
                                                <a onclick="viewProfile('<?php echo $fav_user_id; ?>', 'my_profile')" >				
                                                    <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;"/>
                                                </a>
                                            <?php } ?>
                                            <?php
                                        } else {
                                            if ($favourites->make_private == 'Y') {
                                                if ($user_id != $fav_user_id) {
                                                    if (!in_array($user_id, $favt_mem)) {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $fav_user_id; ?>', 'my_profile')" >
                                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" />
                                                        </a>                
                                                    <?php } else {
                                                        ?>
                                                        <a onclick="viewProfile('<?php echo $fav_user_id; ?>', 'my_profile')" >				
                                                            <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;"/></a>                
                                                        <?php
                                                    }
                                                } else {
                                                    ?>

                                                    <a onclick="viewProfile('<?php echo $fav_user_id; ?>', 'my_profile')" >				
                                                        <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;"/></a>                
                                                <?php } ?>

                                                <?php
                                            } else {
                                                ?> 
                                                <a onclick="viewProfile('<?php echo $fav_user_id; ?>', 'my_profile')" >				
                                                    <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"   class="dsp_img3" style="width:100px; height:100px;"/>
                                                </a>
                                                <?php
                                            }
                                        }
                                    } else {
                                        if ($favourites->make_private == 'Y') {
                                            if ($user_id != $fav_user_id) {
                                                if (!in_array($user_id, $favt_mem)) {
                                                    ?>
                                                    <a onclick="viewProfile('<?php echo $fav_user_id; ?>', 'my_profile')" >
                                                        <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" />
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a onclick="viewProfile('<?php echo $fav_user_id; ?>', 'my_profile')" >				
                                                        <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;"/></a>                

                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <a onclick="viewProfile('<?php echo $fav_user_id; ?>', 'my_profile')" >				

                                                    <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"    class="dsp_img3" style="width:100px; height:100px;"/>
                                                </a>                

                                            <?php } ?>



                                        <?php } else { ?>

                                            <a onclick="viewProfile('<?php echo $fav_user_id; ?>', 'my_profile')" >				

                                                <img src="<?php echo display_members_photo($fav_user_id, $imagepath); ?>"   class="dsp_img3" style="width:100px; height:100px;"/></a>

                                        <?php } ?>



                                    <?php } ?>
                                </div>

                                <div class="dsp_on_lf_view">
                                    <ul>
                                        <li>
                                            <?php echo $displayed_member_name; ?>
                                        </li>
                                        <li>
                                            <span class="delete-icon dsp_span_pointer" onclick="myFavorite('<?php echo $favourite_id ?>', '<?php echo language_code('DSP_DELETE_EMAIL_MESSAGE') ?>');" title="<?php echo language_code('DSP_DELETE_LINK') ?>"></span>
                                        </li>
                                    </ul> 
                                </div>


                            </div>
                        </li>

                        <?php
                        $i++;
                        unset($favt_mem);
                    }
                    ?>
            </ul>
            </form>
        </div>
        <?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
    </div>


    <?php
} else {
    ?>
    <div class="ui-content" data-role="content">
        <div class="content-primary">	
            <div align="center">
                <strong><?php echo language_code('DSP_NO_FAVOURITES_MSG') ?></strong>
            </div>
        </div>
        <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
    </div>
<?php } ?>