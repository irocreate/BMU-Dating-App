<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
//$get_sender_id=$user_id;
$get_receiver_id = $_GET['receiver_ID'];
if (isset($_GET['Act'])) {
    $request_Action = $_GET['Act'];
} else {
    $request_Action = "";
}
if (($request_Action == "R") && ($get_receiver_id != "")) {
    $wpdb->query("UPDATE $dsp_user_emails_table  SET message_read='Y' WHERE sender_id = '$get_receiver_id'");
} // End if 
?>
<div style="width:100%">
    <div class="dsp_mb_vertical_scrollbar" >
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <!--<tr><td class="dsp_back_inbox"><a href="<?php
            echo add_query_arg(array(
                'pid' => 14, 'pagetitle' => 'my_email', 'message_template' => 'inbox'), $root_link);
            ?>"><?php echo language_code('DSP_BACK_TO_INBOX'); ?></a></td></tr>-->
            <?php
            $mesageQuery = "SELECT * FROM $dsp_user_emails_table where (sender_id = $user_id AND receiver_id=$get_receiver_id)  Order by thread_id desc";
            $my_messages = $wpdb->get_results($mesageQuery);
//echo $mesageQuery;
            foreach ($my_messages as $message) {
                $display_receiver_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$message->receiver_id'");
                $message_date = date("Y d M g:i a", strtotime($message->sent_date));

                // check for private pic
                $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$message->receiver_id'");

                $exist_make_private->make_private;

                $favt_mem = array();

                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$message->receiver_id'");

                foreach ($private_mem as $private) {

                    $favt_mem[] = $private->favourite_user_id;
                }
                ?>
                <tr><td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border-bottom:1px solid #cccccc;">
                            <tr>
                                <td width="50px">


                                    <?php
                                    if ($check_couples_mode->setting_status == 'Y') {
                                        if ($exist_make_private->gender == 'C') {

                                            if ($exist_make_private->make_private == 'Y') {

                                                if (!in_array($current_user->ID, $favt_mem)) {
                                                    ?>

                                                    <a href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 3,
                                                        'mem_id' => $message->receiver_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >

                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:45px; height:45px;" class="img2" align="left"  />

                                                    </a>                

                                                <?php } else {
                                                    ?>

                                                    <a href="<?php
                                                    echo add_query_arg(array('pid' => 3,
                                                        'mem_id' => $message->receiver_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >				

                                                        <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>"   style="width:45px; height:45px;" class="img2" align="left" /></a>                


                                                    <?php
                                                }
                                            } // not private end 
                                            else {
                                                ?>
                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $message->receiver_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>">

                                                    <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>" style="width:45px; height:45px;" class="img2" align="left" />

                                                </a>

                                            <?php } ?>
                                            <?php
                                        } // end of check if  sender gender is couple
                                        else {
                                            if ($exist_make_private->make_private == 'Y') {

                                                if (!in_array($current_user->ID, $favt_mem)) {
                                                    ?>

                                                    <a href="<?php
                                                    echo add_query_arg(array('pid' => 3,
                                                        'mem_id' => $message->receiver_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >

                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:45px; height:45px;" class="img2" align="left" />

                                                    </a>                

                                                    <?php
                                                } else {
                                                    ?>

                                                    <a href="<?php
                                                    echo add_query_arg(array('pid' => 3,
                                                        'mem_id' => $message->receiver_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >				

                                                        <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>"    style="width:45px; height:45px;" class="img2" align="left" /></a>                

                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $message->receiver_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>">

                                                    <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>" style="width:45px; height:45px;" class="img2" align="left" />

                                                </a>

                                                <?php
                                            }
                                        } // end of else gender is not a couple 
                                    } // end of if couple mode is on 
                                    else {
                                        if ($exist_make_private->make_private == 'Y') {

                                            if (!in_array($current_user->ID, $favt_mem)) {
                                                ?>

                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $message->receiver_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>" >

                                                    <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:45px; height:45px;" class="img2" align="left"  />

                                                </a>                

                                            <?php } else {
                                                ?>

                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $message->receiver_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>" >				

                                                    <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>"    style="width:45px; height:45px;" class="img2" align="left" /></a>                

                                                <?php
                                            }
                                        }  // end of if pic is private
                                        else {
                                            ?>

                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $message->receiver_id,
                                                'pagetitle' => "view_profile"), $root_link);
                                            ?>">

                                                <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>" style="width:45px; height:45px;" class="img2" align="left" />

                                            </a>

                                        <?php } // end of else pic is not private    ?>



                                    <?php } // end of else   ?>	

                                    <!--<a href="<?php
                                    echo add_query_arg(array('pid' => 3,
                                        'mem_id' => $message->receiver_id,
                                        'pagetitle' => 'view_profile'), $root_link);
                                    ?>">
                                    <img src="<?php echo display_members_photo_mb($message->receiver_id, $image_path); ?>" width="45px" height="50px" class="dsp_img2" align="left" /></a>-->

                                </td>
                                <td>

                                    <span class="dsp_mb_name"><?php echo DSP_TO . $display_receiver_name->display_name; ?></span>
                                    <br><span class="dsp_mb_name"><?php echo DSP_SUBJECT ?>:</span>&nbsp;<?php echo $message->subject ?>
                                    <br /><?php echo $message_date ?>
                                    <br /><?php echo $message->text_message ?>
                                    <!--<?php if ($message->receiver_id != $user_id) { ?>
                                                                        <tr><td><a href="<?php
                                        echo add_query_arg(array(
                                            'pid' => 14, 'pagetitle' => 'my_email',
                                            'message_template' => 'compose',
                                            'sender_ID' => $message->sender_id, 'Act' => 'Reply'), $root_link);
                                        ?>"><?php echo language_code('DSP_MESSAGE_REPLY'); ?></a></td></tr>
                                    <?php } //end if     ?>-->

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php
                unset($favt_mem);
            } // End for loop 
            ?>
        </table>
    </div>