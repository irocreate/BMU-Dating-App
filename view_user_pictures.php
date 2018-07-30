<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
if (is_user_logged_in()) {
    if ($_GET['mem_id']) {
        $member_id = $_GET['mem_id'];
    } else {
        $member_id = $current_user->ID;
    }
} else {
    $member_id = $_GET['mem_id'];
}
// ----------------------------------------------- Start Paging code------------------------------------------------------ //  
if (isset($_GET['page1']))
    $page1 = $_GET['page1'];
else
    $page1 = 1;
$max_results1 = 12;
$from1 = (($page1 * $max_results1) - $max_results1);
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
$picture_id = $_GET['picture_Id'];
?>
<div class="box-border">
    <div class="box-pedding">
        <div align="left" style="padding-left:50px;">
            <table cellpadding="2" cellspacing="8" border="0">
                <?php
                $dsp_album_id = $wpdb->get_results("SELECT * FROM $dsp_user_albums_table WHERE user_id = $member_id");
                foreach ($dsp_album_id as $id) {
                    $album_ids[] = $id->album_id;
                }
                if ($album_ids != "") {
                    $ids1 = implode(",", $album_ids);
                }
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
                $page_name = $root_link . "&pid=3&pagetitle=view_Pictures";
                $total_results1 = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) as Num FROM $dsp_user_photos_table $dsp_user_photos_table where album_id IN ($ids1)"));
// Calculate total number of pages. Round up using ceil()
                $total_pages1 = ceil($total_results1 / $max_results1);
// ------------------------------------------------End Paging code------------------------------------------------------ //
                $exists_photos = $wpdb->get_results("SELECT * FROM $dsp_user_photos_table where album_id IN ($ids1) ORDER BY date_created LIMIT $from1, $max_results1");
                $i = 0;
                foreach ($exists_photos as $user_photos) {
                    $photo_id = $user_photos->user_photo_id;
                    $album_id1 = $user_photos->album_id;
                    $file_name = $user_photos->file_name;
                    $image_path = WPDATE_URL . "/user_photos/user_" . $member_id . "/album_" . $album_id1 . "/" . $file_name;
                    if (($i % 4) == 0) {
                        ?>
                        <tr>
                            <?php
                        }
                        ?>
                        <td><img src="<?php echo $image_path ?>" width="85px" class="img3" alt="<?php echo $file_name;?>"/></td>
                        <?php
                        $i++;
                    }
                    ?>
                </tr>
            </table>
        </div>
        <div align="right" style="padding-right:20px;">
            <?php
// -------------------------------------------  PRINT PAGING LINKS --------------------------------------------------------------- //
            if ($total_results1 > $max_results1) {
//  build Previous link
                if ($page1 > 1) {
                    $prev = ($page1 - 1);
                    echo '<span class="dsp_paging">';
                    echo "<a href=\"" . $page_name . "&page1=$prev\" class='prn'>&lt;&lt;Previous</a> ";
                    echo '</span>';
                }

                // display page numbers
                for ($i = 1; $i <= $total_pages1; $i++) {
                    if ($page1 == $i) {
                        echo '<b>' . $i . '</b>' . " ";
                    } else {
                        echo '<span class="dsp_paging">';
                        echo "<a href=\"" . $page_name . "&page1=$i\">$i</a> ";
                        echo '</span>';
                    }
                }
                //  build Next Link
                if ($page1 < $total_pages1) {
                    $next = ($page1 + 1);
                    echo '<span class="dsp_paging">';
                    echo "<a href=\"" . $page_name . "&page1=$next\" class='prn'>Next&gt;&gt;</a>";
                    echo '</span>';
                }
            }
// ------------------------------------------- END OF PRINT PAGING LINKS --------------------------------------------------------------- //
            ?>
        </div>
    </div>
</div>