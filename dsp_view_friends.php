<div class="dsp_box-out">
    <div class="dsp_box-in" style="overflow:scroll;overflow-x:hidden; height:150px;scrollbar-arrow-color: #000000;">
        <?php
        $redirect_location = add_query_arg(array('pid' => 3, 'pagetitle' => 'view_friends'), $root_link);
        $request_Action = $_GET['Action'];
        $del_friend_Id = $_GET['friend_Id'];
        // ###########################  Reject Image ########################################

        if (($request_Action == "Del") && ($del_friend_Id != "")) {
            //echo "DELETE from $dsp_my_friends_table WHERE friend_id = '$del_friend_Id' AND user_id=$user_id";
            $wpdb->query("DELETE from $dsp_my_friends_table WHERE friend_id = '$del_friend_Id' AND user_id=$user_id");
            //wp_redirect($redirect_location, $redirect_status);
        }


        $count_added_friends = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM  $dsp_my_friends_table WHERE user_id=$user_id AND approved_status='Y'"));
        if ($count_added_friends > 0) {
            ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td><strong><?php echo language_code('DSP_MY_FRIENDS'); ?></strong></td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>

                        <table width="100%" border="0" cellspacing="5" cellpadding="0">
                            <?php
                            $added_friend_list = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table where user_id='$user_id' AND approved_status='Y' LIMIT 20");
                            $i = 0;

                            foreach ($added_friend_list as $friend) {
                                $displayed_member_name = $wpdb->get_var("SELECT * FROM $dsp_user_table WHERE ID = '$friend->friend_uid'");
                                if (($i % 4) == 0) {
                                    ?>
                                    <tr>
                                    <?php }  // End if(($i%4)==0) ?>
                                    <td align="center" width="25%">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td align="center">
                                                    <a href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 3, 'mem_id' => $friend->friend_uid), $root_link);
                                                    ?>"> <img src="<?php echo display_members_photo($friend->friend_uid, $imagepath); ?>" height="85px" class="dsp_img3" alt="<?php echo $displayed_member_name;?>" /></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dsp_name" align="center">
                                                    <span><?php $displayed_member_name ?></span>
                                                    <span onclick="delete_friend_from_list('<?php echo $friend->friend_id ?>');" class="dsp_span_pointer"><?php echo language_code('DSP_DELETE_LINK'); ?></span> 
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </tr>
                        </table>
                    </td></tr>
            </table>
        <?php } else { ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td><strong><?php echo language_code('DSP_NO_FRIENDS_MSG'); ?></strong></td></tr>
                <tr><td>&nbsp;</td></tr>
            </table>
        <?php } ?>
    </div>
</div>