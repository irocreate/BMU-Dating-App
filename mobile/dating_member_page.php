<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */

// ----------------------------------------------- Start Paging code------------------------------------------------------ //  
if (isset($_GET['page1']))
    $page1 = $_GET['page1'];
else
    $page1 = 1;
$max_results1 = 20;
$from1 = (($page1 * $max_results1) - $max_results1);
//$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE status_id=1 AND user_id = '$member_id'");
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$check_member_list_gender_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'member_list_gender'");
$member_list_gender = $check_member_list_gender_mode->setting_value;
$page_name = $root_link . "?pgurl=ALL";
if ($member_list_gender == 2) {
    $member_gender = 'M';
} else if ($member_list_gender == 3) {
    $member_gender = 'F';
} else
    $member_gender = '';
if ($member_gender != '') {
    $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_profiles WHERE status_id=1 AND gender='$member_gender' order by user_profile_id");
} else {
    $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_profiles WHERE status_id=1  order by user_profile_id");
}
// Calculate total number of pages. Round up using ceil()
$total_pages1 = ceil($total_results1 / $max_results1);
// ------------------------------------------------End Paging code------------------------------------------------------ //
?>

<div class="dsp_guest_home_page_wrap">
    <?php
    if ($member_gender != '') {
        $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id=1  AND gender='$member_gender' Order By user_profile_id LIMIT $from1, $max_results1");
    } else {
        $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id=1 Order By user_profile_id LIMIT $from1, $max_results1");
    }
    $i = 0;
    ?>
    <table width="100%">
        <?php
        $countMem = count($new_members);
        foreach ($new_members as $member) {
            $exist_user_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$member->user_id'");
            $user_name = $exist_user_name->display_name;

            if (($i % 2) == 0) {
                ?>
                <tr>
                <?php } // End if(($i%4)==0)   ?>
                <td <?php
                if (($i % 2) == 0) {
                    echo 'width="30%"';
                }
                ?>   align="left"  >
                    <div class="dsp_guest_home_page_col1">

                        <div><a href="<?php
                            echo add_query_arg(array('pgurl' => 'view_member',
                                'guest_pageurl' => 'view_mem_profile', 'mem_id' => $member->user_id), $root_link);
                            ?>"><img src="<?php echo display_members_photo_mb($member->user_id, $pluginpath); ?>"   height="85px" class="dsp_img3"/></a></div>

                        <div class="dsp_clr"></div>

                        <div class="dsp_name"><a href="<?php
                            echo add_query_arg(array(
                                'pgurl' => 'view_member', 'guest_pageurl' => 'view_mem_profile',
                                'mem_id' => $member->user_id), $root_link);
                            ?>"><?php echo $user_name; ?></a></div>

                    </div>
                </td>
                <?php
                if ($count_new_mem == '1') {
                    ?>
                    <td  >&nbsp;</td>		

                    <?php
                }
                $i++;
            } // End foreach ($new_members as $member)
            ?>
        </tr>    	
    </table>
    <div class="dsp_clr"></div>
    <br /><br />
    <div class="dsp_guest_home_page_col2">
        <?php
// --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
        if ($total_results1 > $max_results1) {
//  build Previous link
            if ($page1 > 1) {
                $prev = ($page1 - 1);
                echo '<span class="dsp_paging">';
                echo "<a href=\"" . $page_name . "&page1=$prev\" class='prn'>&lt;&lt;" . DSP_PREVIOUS . "</a> ";
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
            } // End for loop
//  build Next Link
            if ($page1 < $total_pages1) {
                $next = ($page1 + 1);
                echo '<span class="dsp_paging">';
                echo "<a href=\"" . $page_name . "&page1=$next\" class='prn'>" . DSP_NEXT . "&gt;&gt;</a>";
                echo '</span>';
            }
        } // End if($total_results1 > $max_results1)
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
        ?>
    </div>
</div>