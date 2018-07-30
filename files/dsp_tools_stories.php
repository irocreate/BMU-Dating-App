<?php
global $wpdb;
$dsp_stories = $wpdb->prefix . DSP_STORIES_TABLE;
extract($_REQUEST);

if (isset($delete_id)) {
    $story_image = $wpdb->get_var("select story_image from $dsp_stories where story_id='$delete_id'");
    @unlink(ABSPATH . 'wp-content/uploads/dsp_media/story_images/' . $story_image);
    @unlink(ABSPATH . 'wp-content/uploads/dsp_media/story_images/thumb_' . $story_image);
    $wpdb->query("delete from $dsp_stories where story_id='$delete_id'");
    echo '<script>window.location.href="admin.php?page=dsp-admin-sub-page3&pid=stories"</script>';
}

function square_crop($src_image, $dest_image, $thumb_size = 64, $jpg_quality = 90) {

    // Get dimensions of existing image
    $image = getimagesize($src_image);

    // Check for valid dimensions
    if ($image[0] <= 0 || $image[1] <= 0)
        return false;

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
    if ($image_data == false)
        return false;

    // Calculate measurements
    if ($image[0] & $image[1]) {
        // For landscape images
        $x_offset = ($image[0] - $image[1]) / 2;
        $y_offset = 0;
        $square_size = $image[0] - ($x_offset * 2);
    } else {
        // For portrait and square images
        $x_offset = 0;
        $y_offset = ($image[1] - $image[0]) / 2;
        $square_size = $image[1] - ($y_offset * 2);
    }

    // Resize and crop

    $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
    $white = imagecolorallocate($canvas, 255, 255, 255);
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

function getExtension($str) {
    $i = strrpos($str, ".");
    if (!$i) {
        return "";
    }
    $l = strlen($str) - $i;
    $ext = substr($str, $i + 1, $l);
    return $ext;
}

if (isset($add_story)) {
    if ($update_id == '') {
        $wpdb->insert($dsp_stories, array('story_title' => $story_title, 'story_content' => $story_content,
            'date_added' => date('Y-m-d H:i:s')));
        $inserted_id = $wpdb->insert_id;
    } else if (isset($update_id)) {
        $inserted_id = $update_id;
        $wpdb->update($dsp_stories, array('story_title' => $story_title, 'story_content' => $story_content,
            'date_added' => date('Y-m-d H:i:s')), array('story_id' => $update_id));
    }
    if (isset($_FILES['story_pic']['name'])) {
        if (!file_exists(ABSPATH . 'wp-content/uploads/dsp_media/story_images/')) {

            if (!file_exists(ABSPATH . 'wp-content/uploads')) {
                mkdir(ABSPATH . 'wp-content/uploads', 0777);
            }
            if (!file_exists(ABSPATH . 'wp-content/uploads/dsp_media')) {
                mkdir(ABSPATH . 'wp-content/uploads/dsp_media', 0777);
            }
            if (!file_exists(ABSPATH . 'wp-content/uploads/dsp_media/story_images')) {
                chmod(ABSPATH . 'wp-content/uploads', 0777);
                chmod(ABSPATH . 'wp-content/uploads/dsp_media', 0777);
                mkdir(ABSPATH . 'wp-content/uploads/dsp_media/story_images', 0777);
                chmod(ABSPATH . 'wp-content/uploads/dsp_media/story_images', 0777);
            }
        }

        $filename = stripslashes($_FILES['story_pic']['name']);
        if ($add_story == 'Update' && $filename != "") {
            $story_image = $wpdb->get_var("select story_image from $dsp_stories where story_id='$update_id'");
            if ($story_image != $inserted_id . '_' . $filename) {
                @unlink(ABSPATH . 'wp-content/uploads/dsp_media/story_images/' . $story_image);
                @unlink(ABSPATH . 'wp-content/uploads/dsp_media/story_images/thumb_' . $story_image);
            }
        }
        if ($filename != "") {
            $extension = getExtension($filename);
            $extension = strtolower($extension);
            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
                echo '<h1>Unknown extension!</h1>';
                $errors = 1;
            } else {
                $size = getimagesize($_FILES['story_pic']['tmp_name']);
                $sizekb = filesize($_FILES['story_pic']['tmp_name']);

                $newname = ABSPATH . "wp-content/uploads/dsp_media/story_images/" . $inserted_id . '_' . $filename;
                $copied = copy($_FILES['story_pic']['tmp_name'], $newname);
                if (!$copied) {
                    echo '<h5>Copy unsuccessfull!</h5>';
                    $errors = 1;
                } else {
                    $thumb_name1 = ABSPATH . "/wp-content/uploads/dsp_media/story_images/thumb_" . $inserted_id . '_' . $filename;
                    $thumb1 = square_crop($newname, $thumb_name1, 100);
                    $wpdb->update($dsp_stories, array('story_image' => $inserted_id . '_' . $filename), array(
                        'story_id' => $inserted_id));
                }
            }
        }
    }
}
?>
<script>
    jQuery(document).ready(function(e) {
        jQuery("input[name=add_story]").click(function() {
            var html = "";
            var story_title = jQuery("input[name=story_title]").val();
            if (jQuery.trim(story_title) == "") {
                html += "<?php echo language_code('DSP_STORY_TITLE_ERROR'); ?>\n";
            }
            var story_content = jQuery("[name=story_content]").val();
            if (jQuery.trim(story_content) == "") {
                html += "<?php echo language_code('DSP_STORY_CONTENT_ERROR'); ?>";
            }
            if (jQuery.trim(html) != "") {
                alert(html);
                return false;
            }

        });
        jQuery(".view-story-edit").click(function() {
            var id = jQuery(this).attr('id');
            var image = jQuery("#img_" + id).attr('src');
            var title = jQuery("#title_" + id).html();
            var content = jQuery("#content_" + id).html();
            jQuery("input[name=story_title]").val(title);
            jQuery("input[name=update_id]").val(id);
            jQuery("textarea[name=story_content]").val(content);
            jQuery("input[name=add_story]").val("Update");
            jQuery(".image-box img").attr('src', image);
            jQuery(".image-box").show();
        });
    });
</script>
<div style="float:left; width:100%;" id="general" class="postbox">
    <h3 class="hndle"><span><?php echo language_code('DSP_STORIES_MODE'); ?></span></h3>
    <br />

    <div class="stories-box">
        <h3 class="stories-form-heading"><?php echo language_code('DSP_ADD_NEW_STORY'); ?></h3>
        <form method="post" enctype="multipart/form-data">
            <div class="story-form"><div class="story-form-row"><span class="story-form-heading"><?php echo language_code('DSP_TEXT_TITLE'); ?></span><input type="text" name="story_title" value="" /></div>
                <div class="story-form-row"><span class="story-form-heading"><?php echo language_code('DSP_TEXT_STORY'); ?></span><textarea name="story_content"></textarea>
<div class="story-pic-add-box"><input type="file" name="story_pic" /><input type="submit" name="add_story" value="<?php echo language_code('DSP_ADD_MY_BLOGS_ADD_BUTTON'); ?>" />
<span class="story-add-photo-heading"><?php echo language_code('DSP_ADD_PHOTO_BUTTON'); ?></span></div><div class="image-box" style="display:none;"><input type="hidden" name="update_id" value="" /><img src="" width="100" height="100" alt="Story"/></div>
</div></div>
</form>
</div>

    <?php
    $story_result = $wpdb->get_results("select * from $dsp_stories order by date_added desc");
    if (count($story_result) > 0) {
        ?>
                                    <div class="story-view-box">
                                    <ul>
                                    <li><span class="story-title-view-heading"><?php echo language_code('DSP_TEXT_TITLE'); ?></span><span class="story-content-view-heading"><?php echo language_code('DSP_TEXT_STORY'); ?></span></li>
                <?php
                foreach ($story_result as $story_row) {
                    ?>
                                    <li><span class="view-story-image"><img id="img_<?php echo $story_row->story_id; ?>" src="<?php echo get_bloginfo('url') . '/wp-content/uploads/dsp_media/story_images/thumb_' . $story_row->story_image; ?>" alt="<?php echo $story_row->story_image;?>" width="60" height="60" /></span><span  id="title_<?php echo $story_row->story_id; ?>" class="view-story-title"><?php echo stripslashes($story_row->story_title); ?></span><span class="view-story-content" id="content_<?php echo $story_row->story_id; ?>"><?php echo stripslashes($story_row->story_content); ?> </span><span class="view-story-edit" id="<?php echo $story_row->story_id; ?>">Edit</span><span class="view-story-delete"><a href="admin.php?page=dsp-admin-sub-page3&pid=stories&delete_id=<?php echo $story_row->story_id; ?>">Delete</a></span></li>

                <?php } ?>
                                    </ul>

                                    </div>
    <?php } ?>

</div>

