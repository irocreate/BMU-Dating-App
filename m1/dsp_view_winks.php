<?php
$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
$dsp_flirt_table = $wpdb->prefix . DSP_FLIRT_TEXT_TABLE;
$users_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;

$delwinks_msg_id = isset($_REQUEST['wink_id']) ? $_REQUEST['wink_id'] : '';
$Actiondel = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';
$request_Action = isset($_REQUEST['Act']) ? $_REQUEST['Act'] : '';

if (($delwinks_msg_id != "") && ($Actiondel == "Del")) {
    $wpdb->query("DELETE FROM $dsp_member_winks_table where wink_mesage_id  = '$delwinks_msg_id'");
} else {

    //echo "SELECT COUNT(*) as Num FROM $dsp_member_winks_table winks, $dsp_user_profiles profile WHERE winks.sender_id = profile.user_id AND winks.receiver_id = '$user_id'";

    if ($check_couples_mode->setting_status == 'Y') {
        $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_winks_table winks, $dsp_user_profiles profile WHERE winks.sender_id = profile.user_id AND winks.receiver_id = '$user_id'");
    } else {
        $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_winks_table winks, $dsp_user_profiles profile WHERE winks.sender_id = profile.user_id AND winks.receiver_id = '$user_id' AND profile.gender!='C'");
    }



    if ($total_results1 > 0) {
        ?>
        <?php
        if ($check_couples_mode->setting_status == 'Y') {
            $my_winks = $wpdb->get_results("SELECT * FROM $dsp_member_winks_table winks, $dsp_user_profiles profile WHERE winks.sender_id = profile.user_id
AND winks.receiver_id = '$user_id' ORDER BY winks.send_date");
        } else {
            $my_winks = $wpdb->get_results("SELECT * FROM $dsp_member_winks_table winks, $dsp_user_profiles profile WHERE winks.sender_id = profile.user_id
AND winks.receiver_id = '$user_id' AND profile.gender!='C' ORDER BY winks.send_date");
        }
        ?>
        <div class="swipe_div" id="mainWinks" style="height: 150px">
            <ul id="swipe_ulWinks"  style="padding-left:0px;text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">

                <?php
                foreach ($my_winks as $winks) {
                    $wink_msg_id = $winks->wink_mesage_id;
                    $wink_sender_id = $winks->sender_id;
                    $wink_id = $winks->wink_id;
                    $exist_wink_message = $wpdb->get_row("SELECT * FROM $dsp_flirt_table WHERE Flirt_ID = '$wink_id'");
                    $sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$wink_sender_id'");
                    $message_sent_date = date("d/m/Y h:i", strtotime($winks->send_date));
                    $favt_mem = array();
                    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$wink_sender_id'");
                    foreach ($private_mem as $private) {
                        $favt_mem[] = $private->favourite_user_id;
                    }
                    ?>	
                    <li class="ivew-list">
                        <div >
                            <?php
                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($winks->gender == 'C') {
                                    ?>
                                    <?php if ($winks->make_private == 'Y') {
                                        ?>
                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $wink_sender_id,
                                                'pagetitle' => "view_profile", 'view' => "my_profile"), $root_link);
                                            ?>" >
                                                <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  class="img2 iviewed-img" />
                                            </a>                
                                            <?php
                                        } else {
                                            ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $wink_sender_id,
                                                'pagetitle' => "view_profile", 'view' => "my_profile"), $root_link);
                                            ?>" >				
                                                <img src="<?php echo display_members_photo($wink_sender_id, $imagepath); ?>"   class="img2 iviewed-img"/></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>

                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $wink_sender_id,
                                            'pagetitle' => "view_profile", 'view' => "my_profile"), $root_link);
                                        ?>">
                                            <img src="<?php echo display_members_photo($wink_sender_id, $imagepath); ?>"  class="img2 iviewed-img" />
                                        </a>
                                    <?php } ?>


                                <?php } else {
                                    ?>

                                    <?php if ($winks->make_private == 'Y') { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $wink_sender_id,
                                                'pagetitle' => "view_profile"), $root_link);
                                            ?>" >
                                                <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  class="img2 iviewed-img" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $wink_sender_id,
                                                'pagetitle' => "view_profile"), $root_link);
                                            ?>" >				
                                                <img src="<?php echo display_members_photo($wink_sender_id, $imagepath); ?>"  class="img2 iviewed-img"/></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>

                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $wink_sender_id,
                                            'pagetitle' => "view_profile"), $root_link);
                                        ?>">
                                            <img src="<?php echo display_members_photo($wink_sender_id, $imagepath); ?>"  class="img2 iviewed-img" />
                                        </a>
                                    <?php } ?>

                                    <?php
                                }
                            } else {
                                ?> 
                                <?php if ($winks->make_private == 'Y') { ?>

                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $wink_sender_id,
                                            'pagetitle' => "view_profile"), $root_link);
                                        ?>" >
                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  class="img2 iviewed-img" />
                                        </a>                
                                    <?php } else {
                                        ?>
                                        <a href="<?php
                                        echo add_query_arg(array('pid' => 3, 'mem_id' => $wink_sender_id,
                                            'pagetitle' => "view_profile"), $root_link);
                                        ?>" >				
                                            <img src="<?php echo display_members_photo($wink_sender_id, $imagepath); ?>"    class="img2 iviewed-img"/></a>                
                                        <?php
                                    }
                                } else {
                                    ?>

                                    <a href="<?php
                                    echo add_query_arg(array('pid' => 3, 'mem_id' => $wink_sender_id,
                                        'pagetitle' => "view_profile"), $root_link);
                                    ?>">
                                        <img src="<?php echo display_members_photo($wink_sender_id, $imagepath); ?>"  class="img2 iviewed-img" />
                                    </a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <div class="dsp_name"> 
                            &nbsp;<?php echo $sender_name->display_name ?>
                        </div>
                        <div class="dsp_name">
                            <a class="reply-btn" onclick="displayWink('<?php echo $wink_msg_id; ?>')"><?php echo language_code('DSP_VIEW_WINK') ?></a>
                        </div>

                    </li>
                    <?php
                    unset($favt_mem);
                } // foreach($my_winks as $winks)  
                ?>

            </ul>
        </div>

        <?php
    } else {
        ?>
        <div style=" text-align:center;">
            <strong><?php echo language_code('DSP_NO_WINK_MSG') ?></strong>
        </div>
        <?php
    }
}
?>