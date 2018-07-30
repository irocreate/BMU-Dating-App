<?php
//  ############################  UPDATE MEMBERSHIPS DETAILS ############################### //
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_membership_table         = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
$dsp_payments_table           = $wpdb->prefix . DSP_PAYMENTS_TABLE;
$dsp_users_table              = $wpdb->prefix . DSP_USERS_TABLE;
$goback                       = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$dsp_action                   = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$dsp_mem_name                 = isset($_REQUEST['txtmembership_name']) ? $_REQUEST['txtmembership_name'] : '';
$dsp_mem_price                = isset($_REQUEST['txtmembership_price']) ? $_REQUEST['txtmembership_price'] : '';
$dsp_mem_free_plan            = isset($_REQUEST['membership_plan_free']) ? $_REQUEST['membership_plan_free'] : 0;
$dsp_stripe_recurring_plan_id = isset($_REQUEST['stripe_recuring_plan_id']) ? trim($_REQUEST['stripe_recuring_plan_id']) : '';
$dsp_mem_days                 = isset($_REQUEST['dsp_membership_days']) ? $_REQUEST['dsp_membership_days'] : '';
$dsp_mem_desc                 = isset($_POST['dsp_mem_desc']) ? stripslashes($_POST['dsp_mem_desc']) : '';
$dsp_mem_status               = "Y";
$dsp_mem_active_status        = "N";
$dsp_mem_added_date           = date('Y-m-d');
$update_membership_id         = isset($_REQUEST['update_membership_id']) ? $_REQUEST['update_membership_id'] : '';
$display_status               = isset($_REQUEST['display_status']) ? $_REQUEST['display_status'] : '';
$premium_access_feature_array = isset($_REQUEST['access_feature']) ? $_REQUEST['access_feature'] : '';
$source2                      = "";
if ($premium_access_feature_array) {
    foreach ($premium_access_feature_array as $premium_access_feature) {
        $source2 .= $premium_access_feature . ", ";
    }
}
if (isset($source2)) {
    $premium_access_feature_value = substr($source2, 0, -2);
} else {
    $premium_access_feature_value = '';
}
if ( ! defined('WP_CONTENT_DIR')) {
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
}
$folder = ABSPATH . "/wp-content/uploads/dsp_media/dsp_images/";
if ( ! file_exists($folder)) {
    @mkdir(ABSPATH . '/wp-content/uploads/dsp_media/dsp_images', 0755);
    @chmod(ABSPATH . '/wp-content/uploads/dsp_media/dsp_images', 0777);
} else {
    @chmod(ABSPATH . '/wp-content/uploads/dsp_media/dsp_images', 0777);
}
$dsp_mem_image  = isset($_FILES['dsp_mem_image']) ? $_FILES['dsp_mem_image']['name'] : '';
$dsp_mem_image1 = isset($_FILES['dsp_mem_image1']) ? $_FILES['dsp_mem_image1']['name'] : '';

define("MAX_SIZE", "100000");
define("WIDTH", "150");
define("HEIGHT", "150");
define("width", "100");
define("height", "100");

function square_crop($src_image, $dest_image, $thumb_size = 64, $jpg_quality = 90)
{

    // Get dimensions of existing image
    $image = getimagesize($src_image);

    // Check for valid dimensions
    if ($image[0] <= 0 || $image[1] <= 0) {
        return false;
    }

    // Determine format from MIME-Type
    $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));

    // Import image
    switch ($image['format']) {
        case 'jpg':
        case 'jpeg':
            $image_data = imagecreatefromjpeg($src_image);
            break;
        case 'png':
            $image_data = imagecreatefrompng($src_image);
            break;
        case 'gif':
            $image_data = imagecreatefromgif($src_image);
            break;
        default:
            // Unsupported format
            return false;
            break;
    }

    // Verify import
    if ($image_data == false) {
        return false;
    }

    // Calculate measurements
    if ($image[0] & $image[1]) {
        // For landscape images
        $x_offset    = ($image[0] - $image[1]) / 2;
        $y_offset    = 0;
        $square_size = $image[0] - ($x_offset * 2);
    } else {
        // For portrait and square images
        $x_offset    = 0;
        $y_offset    = ($image[1] - $image[0]) / 2;
        $square_size = $image[1] - ($y_offset * 2);
    }

    // Resize and crop

    $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
    $white  = imagecolorallocate($canvas, 255, 255, 255);
    imagefill($canvas, 0, 0, $white);
    if (imagecopyresampled(
        $canvas, $image_data, 0, 0, $x_offset, $y_offset, $thumb_size, $thumb_size, $square_size, $square_size
    )) {

        // Create thumbnail
        switch (strtolower(preg_replace('/^.*\./', '', $dest_image))) {
            case 'jpg':
            case 'jpeg':
                return imagejpeg($canvas, $dest_image, $jpg_quality);
                break;
            case 'png':
                return imagepng($canvas, $dest_image);
                break;
            case 'gif':
                return imagegif($canvas, $dest_image);
                break;
            default:
                // Unsupported format
                return false;
                break;
        }
    } else {
        return false;
    }
}

function getExtension($str)
{
    $i = strrpos($str, ".");
    if ( ! $i) {
        return "";
    }
    $l   = strlen($str) - $i;
    $ext = substr($str, $i + 1, $l);

    return $ext;
}

if (isset($_POST['submit'])) {
    $dsp_action = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
    switch ($dsp_action) {
        case 'add':
            if ( ! empty($dsp_mem_name)) {
                $errors = 0;
                if ($dsp_mem_image) {
                    $filename = stripslashes($_FILES['dsp_mem_image']['name']);

                    $extension = getExtension($filename);
                    $extension = strtolower($extension);
                    if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
                        ?>
                        <div class="error">
                            <p>
                                <strong><?php echo language_code('DSP_USER_UNKNOWN_EXTENSION_FOR_IMAGE'); ?></strong>
                            </p>
                        </div>
                        <?php
                        $errors = 1;
                    } else {
                        $size     = getimagesize($_FILES['dsp_mem_image']['tmp_name']);
                        $sizekb   = filesize($_FILES['dsp_mem_image']['tmp_name']);
                        $img_name = basename($dsp_mem_image);
                        $new_name = time() . "_" . $img_name;
                        $newname  = ABSPATH . "/wp-content/uploads/dsp_media/dsp_images/" . $new_name;
                        $copied   = copy($_FILES['dsp_mem_image']['tmp_name'], $newname);
                        if ( ! $copied) {
                            ?>
                            <div class="error">
                                <p>
                                    <strong><?php echo language_code('DSP_COPY_UNSUCCESSFUL'); ?></strong>
                                </p>
                            </div>
                            <?php
                            $errors = 1;
                        } else {
                            $thumb_name1 = ABSPATH . "/wp-content/uploads/dsp_media/dsp_images/t_" . $new_name;
                            $thumb1      = square_crop($newname, $thumb_name1, 100);
                            $wpdb->query("INSERT INTO $dsp_membership_table (`membership_id`, `name`, `price`, `no_of_days`, `display_status`, `active_status`, `date_added`, `premium_access_feature`, `description`, `image`, `free_plan`, `stripe_recurring_plan_id`) VALUES ('','$dsp_mem_name', '$dsp_mem_price', '$dsp_mem_days', '$dsp_mem_status','$dsp_mem_active_status', '$dsp_mem_added_date', '$premium_access_feature_value','$dsp_mem_desc','t_" . $new_name . "', $dsp_mem_free_plan, '$dsp_stripe_recurring_plan_id')");
                        }
                    }
                }
            } // END  if ( !empty($dsp_mem_name) && !empty($dsp_mem_price) && !empty($dsp_mem_days))
            //header("Location:".$goback);
            ?>
            <div class="updated">
                <p>
                    <strong><?php echo language_code('DSP_NEW_MEMBERSHIP_UPDATED'); ?></strong>
                </p>
            </div>
            <?php
            break;

        case 'update':
            if ( ! empty($dsp_mem_name) && isset($dsp_mem_price) && $dsp_mem_price != "" && ! empty($dsp_mem_days)) {
                $membership_id = $_GET['Id'];
                $errors        = 0;
                if ($dsp_mem_image1 != '') {

                    if ($_FILES['dsp_mem_image1']['name']) {
                        $my_img = $wpdb->get_row("select image from $dsp_membership_table where membership_id=$membership_id",
                            ARRAY_A);
                        /*$update_img = mysql_query("select image from $dsp_membership_table where membership_id=$membership_id");
                        $my_img = mysql_fetch_array($update_img);*/
                        $old_img            = $my_img['image'];
                        $old_img            = str_replace('t_', '', $old_img);
                        $del_img_path       = ABSPATH . "/wp-content/uploads/dsp_media/dsp_images/" . $old_img;
                        $del_thumb_img_path = ABSPATH . "/wp-content/uploads/dsp_media/dsp_images/t_" . $old_img;
                        if ($old_img != "") {
                            unlink($del_img_path);
                            unlink($del_thumb_img_path);
                        }
                    }


                    $filename = stripslashes($_FILES['dsp_mem_image1']['name']);

                    $extension = getExtension($filename);
                    $extension = strtolower($extension);
                    if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
                        ?>
                        <div class="error">
                            <p>
                                <strong><?php echo language_code('DSP_USER_UNKNOWN_EXTENSION_FOR_IMAGE'); ?></strong>
                            </p>
                        </div>
                        <?php
                        $errors = 1;
                    } else {
                        $size     = getimagesize($_FILES['dsp_mem_image1']['tmp_name']);
                        $sizekb   = filesize($_FILES['dsp_mem_image1']['tmp_name']);
                        $img_name = basename($dsp_mem_image1);
                        $new_name = time() . "_" . $img_name;


                        $newname = ABSPATH . "/wp-content/uploads/dsp_media/dsp_images/" . $new_name;
                        $copied  = copy($_FILES['dsp_mem_image1']['tmp_name'], $newname);
                        if ( ! $copied) {
                            ?>
                            <div class="error">
                                <p>
                                    <strong><?php echo language_code('DSP_COPY_UNSUCCESSFUL'); ?></strong>
                                </p>
                            </div>
                            <?php
                            $errors = 1;
                        } else {

                            $thumb_name1 = ABSPATH . "/wp-content/uploads/dsp_media/dsp_images/t_" . $new_name;
                            $thumb1      = square_crop($newname, $thumb_name1, 100);
                            $wpdb->update($dsp_membership_table, array('image' => 't_' . $new_name . ''), array(
                                'membership_id' => $membership_id
                            ), array('%s'), array(
                                '%d'
                            ));
                        }
                    }
                }
                $wpdb->update($dsp_membership_table, array(
                    'name'                     => $dsp_mem_name,
                    'price'                    => $dsp_mem_price,
                    'no_of_days'               => $dsp_mem_days,
                    'date_added'               => $dsp_mem_added_date,
                    'premium_access_feature'   => $premium_access_feature_value,
                    'description'              => $dsp_mem_desc,
                    'free_plan'                => $dsp_mem_free_plan,
                    'stripe_recurring_plan_id' => $dsp_stripe_recurring_plan_id,
                ), array(
                    'membership_id' => $membership_id
                ), array(
                    '%s',
                    '%s',
                    '%d',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%s'
                ), array('%d'));
            } // END if ( !empty($dsp_mem_name) && !empty($dsp_mem_price) && !empty($dsp_mem_days))
            //header("Location:".$goback);
            ?>
            <div class="updated">
                <p>
                    <?php echo language_code('DSP_NEW_MEMBERSHIP_UPDATED'); ?>
                </p>
            </div>
            <?php
            break;
    } // CLOSE SWITCH CASE \0
}
if ($update_membership_id != "") {
    $wpdb->query("UPDATE $dsp_membership_table SET display_status ='N'");
    if ($display_status != "") {
        foreach ($display_status as $key => $value) {
            if ($value == "Y") {
                $status = "Y";
                $wpdb->query("UPDATE $dsp_membership_table SET display_status ='$status' WHERE membership_id ='$key'");
            } // End if($value=="Y")
        } // End loop
    } // end if
} // End if
//------------------------start delete membership plan------------------------------------- //
if (isset($_GET['Action']) && $_GET['Action'] == "Del") {
    $membership_id = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : '';
    $wpdb->query("DELETE FROM $dsp_membership_table WHERE membership_id = '$membership_id'");
} // END if($_GET['Action']=="Del")
//------------------------ end delete membership plan------------------------------------- //
//  ########################################################################################################## //
?>
<style>
    .dsp_membership_wrap {
        margin-left: 2px;
        padding: 15px;
        width: 700px;
        display: block;
    }

    .dsp_membership_col1 {
        width: 130px;
        padding-left: 6px;
        float: left;
        display: block;
        height: 25px;
    }

    .dsp_membership_col2 {
        width: 100px;
        height: 20px;
        display: block;
        float: left;
    }

    .dsp_membership_col3 {
        width: 200px;
        height: 20px;
        display: block;
        float: left;
        text-align: center;
    }

    .dsp_membership_col4 {
        height: 20px;
        display: block;
        float: left;
    }

    .dsp_membership_col6 {
        height: 20px;
        display: block;
        float: left;
        width: 80%;
    }

    .dsp_membership_col5 {
        width: 130px;
        height: 80px;
        display: block;
        float: left;
    }

    .dsp_membership_active_col {
        width: 20px;
        height: 20px;
        text-align: right;
        float: left;
        display: block;
    }
</style>
<div id="general" class="postbox">

    <h3 class="hndle"><span><?php echo language_code('DSP_MEMBERSHIPS'); ?></span></h3>


    <form name="updatedisplay_statusfrm" action="" method="post">
        <!--<div><div class="dsp_admin_headings"><?php //echo language_code('DSP_MEMBERSHIPS');          ?></div></div>-->
        <div class="inside">
            <table class="form-table" style="width:70%">
                <tbody>
                <tr>
                    <th><?php echo language_code('DSP_NAME'); ?></th>
                    <th><?php echo language_code('DSP_PRICE'); ?></th>
                    <th><?php echo language_code('DSP_ACTION'); ?></th>
                    <th><?php echo language_code('DSP_DISPLAY_STATUS-1'); ?></th>
                </tr>

                <?php
                $myrows = $wpdb->get_results("SELECT * FROM $dsp_membership_table Order by name");
                foreach ($myrows as $memberships) { ?>
                    <tr>
                        <?php

                        $membership_name   = $memberships->name;
                        $membership_amount = $memberships->price;
                        $membership_id     = $memberships->membership_id;
                        $active_status     = $memberships->active_status;
                        $display_status    = $memberships->display_status;
                        /*$check_plan_is_active = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_plan_id='$membership_id' AND payment_status='1'");*/
                        $check_plan_is_active = $wpdb->get_results("SELECT * FROM $dsp_users_table user,$dsp_payments_table payments where  user.ID=payments.pay_user_id and payments.pay_plan_id='$membership_id' and payment_status= 1");
                        if (
                            empty($check_plan_is_active) ||
                            $check_plan_is_active == null
                        ) {
                            $check_plan_is_active = 0;
                        }
                        if ($check_plan_is_active > 0) { ?>
                            <td>
                                <strong style="color:#009900; font-size: 20px">&bull;</strong>&nbsp;
                                <?php _e($membership_name) ?>
                            </td>
                        <?php } else { ?>
                            <td>
                                <strong style="color:#990000; font-size: 20px">&bull;</strong>
                                <?php _e($membership_name) ?>
                            </td>
                        <?php } ?>

                        <td>
                            <input type="text" name="membership_amount" value="<?php echo $membership_amount; ?>"
                                   class="
                                   regular-text"/>
                        </td>

                        <td>
                            <?php if ($check_plan_is_active > 0) { ?>
                                <label><span onclick="update_memberships(<?php echo $membership_id ?>);"
                                             class="span_pointer">edit</span></label>

                                <label>Delete</label>
                            <?php } else { ?>
                                <label><span onclick="update_memberships(<?php echo $membership_id ?>);"
                                             class="span_pointer">edit</span></label>
                                <label><span onclick="delete_memberships(<?php echo $membership_id ?>);"
                                             class="span_pointer">Delete</span></label>
                            <?php } ?>
                        </td>

                        <td>
                            <input type="hidden" name="update_membership_id[<?php echo $membership_id ?>]"
                                   value="<?php echo $membership_id; ?>"/>
                            <input type="checkbox" name="display_status[<?php echo $membership_id ?>]"
                                   value="Y" <?php if ($display_status == "Y") { ?> checked="checked"  <?php } ?>/>
                        </td>
                    </tr>
                    <?php
                } // foreach ($myrows as $memberships)
                ?>

                </tbody>
            </table>
            <p>
                <input type="submit" name="submit" class="button-primary"
                       value="Update Status"/>
            </p>
            <p class="dsp_note">
                <?php echo language_code('DSP_PREMIUM_DELETE_NOTE'); ?>
            </p>
            <p class="dsp_note">
                <?php echo language_code('DSP_NO_OF_DAYS_RECURRING_WARNING'); ?>
            </p>
            <p class="dsp_note">
                <?php echo '1.' . language_code('DSP_NO_OF_DAYS_RECURRING_DAYS') . '<br>'
                           . '2.' . language_code('DSP_NO_OF_DAYS_RECURRING_WEEKS') . '<br>'
                           . '3.' . language_code('DSP_NO_OF_DAYS_RECURRING_MONTHS') . '<br>'
                           . '4.' . language_code('DSP_NO_OF_DAYS_RECURRING_YEARS');
                ?>
            </p>
        </div>

    </form>

</div>
<div class="dsp_clr"></div>
<?php
if (isset($_GET['Action']) && $_GET['Action'] == 'update') {
    $mode                                = 'update';
    $membership_id                       = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : '';
    $dsp_updates                         = $wpdb->get_row("SELECT * FROM $dsp_membership_table WHERE membership_id = $membership_id");
    $membership_name                     = $dsp_updates->name;
    $price                               = $dsp_updates->price;
    $membership_free_plan                = $dsp_updates->free_plan;
    $membership_stripe_recurring_plan_id = $dsp_updates->stripe_recurring_plan_id;
    $noofdays                            = $dsp_updates->no_of_days;
    $description                         = $dsp_updates->description;
    $premium_access_feature              = $dsp_updates->premium_access_feature;
} else {
    $premium_access_feature = "";
    $membership_name        = "";
    $description            = "";
    $price                  = "";
    $noofdays               = "";
    $mode                   = 'add';
} // if($_GET['Action']=='update')
?>
<div id="general" class="postbox">

    <h3 class="hndle"><span><?php echo language_code('DSP_CUSTOM_MEMBERSHIPS'); ?></span></h3>


    <form name="membershipfrm" method="post" enctype="multipart/form-data">
        <div class="inside">
            <table class="form-table">
                <tbody>
                <tr>
                    <th>
                        <label><?php _e(language_code('DSP_MEMBERSHIPS_NAME')); ?></label>
                    </th>
                    <td>
                        <input type="text" name="txtmembership_name" value="<?php echo $membership_name; ?>"
                               class="regular-text"/>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label><?php _e(language_code('DSP_MEMBERSHIPS_PRICE')); ?></label>
                    </th>
                    <td>
                        <input type="text" name="txtmembership_price" value="<?php echo $price; ?>"
                               class="regular-text"/>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                    </th>
                    <td>
                        <input name="membership_plan_free" type="checkbox" id="membership_plan_free" value="1"
                            <?php echo (isset($membership_free_plan) && $membership_free_plan == 1) ? "checked": '' ?>>
                        <?php _e(language_code('DSP_FREE_MEMBERSHIP_PLAN')); ?><br>
                        <br>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label><?php _e(language_code('DSP_MEMBERSHIPS_DAYS_NO')); ?></label>
                    </th>
                    <td>
                        <input type="text" name="dsp_membership_days" value="<?php echo $noofdays; ?>"
                               class="regular-text"/>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label><?php _e(language_code('DSP_MEMBERSHIPS_DESCRIPTION')); ?></label>
                    </th>
                    <td>
                          <textarea name="dsp_mem_desc" cols="41" rows="5" class="regular-text"
                          ><?php echo $description; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label><?php _e(language_code('DSP_UPLOAD_PHOTOS')); ?></label>
                    </th>
                    <td>
                        <?php
                        if (isset($_GET['Action']) && $_GET['Action'] == 'update') {

                            $my_img = $wpdb->get_row("select image from $dsp_membership_table where membership_id=$membership_id",
                                ARRAY_A);

                            ?>
                            <input name="dsp_mem_image1" type="file"/><br/><img
                                    src="<?php echo get_bloginfo('url') . '/wp-content/uploads/dsp_media/dsp_images/' . $my_img['image']; ?>"
                                    style="border:1px solid #DFDFDF; margin-top:5px;" class="test"
                                    alt="<?php echo $my_img['image']; ?>"/>

                        <?php } else { ?>
                            <input name="dsp_mem_image" type="file"/>
                        <?php } ?>
                    </td>

                </tr>
                <?php
                if (class_exists('DSP_STRIPE_PAYMENT_ADDON')) {
                    $stripe_recurring    = new DSP_STRIPE_PAYMENT_ADDON();
                    $is_stripe_recurring = $stripe_recurring->is_recurring();
                    if ($is_stripe_recurring) {
                        ?>
                        <tr>
                            <th scope="row">
                                <label><?php echo 'Stripe Subscription Plan Id'; ?></label>
                            </th>
                            <td>
                                <input name="stripe_recuring_plan_id" type="text" id="stripe_recuring_plan_id"
                                       class="regular-text" value="<?php echo isset($membership_stripe_recurring_plan_id) ? $membership_stripe_recurring_plan_id : '' ?>">
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
            <table style="width:50%">

                <tbody>
                <tr style="float: left">
                    <th>
                        <label style="font-weight: 600;font-size: 14px;">Feature Access</label>
                    </th>
                </tr>
                <br>
                <?php

                $language_code = dsp_get_current_user_language_code();

                if ($language_code == 'en' || $language_code == null || empty($language_code)) {
                    $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE;
                } else {
                    $dsp_features_table = $wpdb->prefix . DSP_FEATURES_TABLE . '_' . $language_code;
                }

                $request_url = $_SERVER['REQUEST_URI'];
                $myrows      = $wpdb->get_results("SELECT * FROM $dsp_features_table Order by feature_id ");
                $i           = 0;
                foreach ($myrows as $access_feature) {
                $f_name     = $access_feature->feature_name;
                $feature_id = $access_feature->feature_id;

                if ($i % 2 == 0) { ?>
                <tr>
                    <?php
                    }
                    ?>
                    <td>
                    </td>

                    <td>
                        <input type="checkbox" name="access_feature[<?php echo $feature_id ?>]"
                               value="<?php echo $feature_id ?>" <?php
                        $premium_access_feature;
                        $editaccessfeatures = explode(",", $premium_access_feature);
                        $count              = count($editaccessfeatures);
                        $k                  = 0;
                        for ($k = 0; $k < $count; $k++) {
                            $editaccessfeatures[$k];
                            if ($feature_id == $editaccessfeatures[$k]) {
                                ?> checked="checked" <?php
                            }
                        }
                        ?> />
                    </td>

                    <td>
                        <?php echo __($f_name, 'wpdating'); ?>
                    </td>

                    <?php
                    $i++;
                    }
                    ?>
                </tr>
                </tbody>
            </table>
            <div><input type="hidden" name="mode" value="<?php echo $mode ?>"/></div>
            <input style="float:none;" type="submit" class="button button-primary" name="submit"
                   value="<?php _e('Save Changes') ?>" onclick=" return Checkform();"/>
        </div>
    </form>
</div>

<script>
    function Checkform() {
        if (document.membershipfrm.txtmembership_name.value == "") {
            alert('please choose membership name.');
            document.membershipfrm.txtmembership_name.focus();
            return false;
        }
        if (document.membershipfrm.txtmembership_price.value == "") {
            alert('please enter price.');
            document.membershipfrm.txtmembership_price.focus();
            return false;
        }
        if (document.membershipfrm.dsp_membership_days.value == "") {
            alert('please enter days.');
            document.membershipfrm.dsp_membership_days.focus();
            return false;
        }
        if (document.membershipfrm.dsp_mem_desc.value == "") {
            alert('please enter description.');
            document.membershipfrm.dsp_mem_desc.focus();
            return false;
        }
        if (document.membershipfrm.dsp_mem_image.value == "") {
            alert('please choose image file.');
            document.membershipfrm.dsp_mem_image.focus();
            return false;
        }
        return true;
    }
</script>
