<?php 
// ----------------------------------Check member privacy Settings------------------------------------
$dsp_my_blog_table = $wpdb->prefix . DSP_MY_BLOGS_TABLE;
$users_table = $wpdb->prefix . DSP_USERS_TABLE;
if ($user_id != $member_id) {
    $check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_blog_table WHERE user_id='$member_id' ");
    if ($check_my_friends_list <= 0) {   // check member is not in my friend list
        ?>

        <div class="box-border">
            <div class="box-pedding">
                <div align="center"><?php echo language_code('DSP_CANT_VIEW_MEM_FRIENDS');?></div>
            </div>
        </div>
    <?php } else {   // -----------------------------else Check member is in my friend list ---------------------------- // 
        ?>
        <form name="" method="GET" action="">
            <input type="hidden" name="pid" value="12" />
            <input type="hidden" name="pagetitle" value="my_blogs" />
            <?php //-------------------------------------START MY BLOGS -------------------------------------// ?>

            <div class="box-border">
                <div class="box-pedding">
                    <div class="heading-text dsp-clearfix"><strong><?php echo language_code('DSP_MENU_MY_BLOGS'); ?></strong></div>
                    <ul class="blog-page-heading dsp-clearfix">
                        <li><span style="float: left;width:17%; font-weight:bold;"><?php echo language_code('DSP_ADD_MY_BLOGS_TITLE'); ?></span> <span style="float: left;width:17%; font-weight:bold;"><?php echo language_code('DSP_ADD_MY_BLOGS_DATE'); ?></span>
                            <span style="float: left;width:55%; font-weight:bold;"><?php echo language_code('DSP_ADD_MY_BLOG'); ?></span></li></ul>
                    <?php
                    $blogs_table = $wpdb->get_results("SELECT * FROM $dsp_my_blog_table WHERE user_id=$member_id");
                    if(isset($blogs_table) && count($blogs_table) > 0){
                        foreach ($blogs_table as $blogs) {
                            $user_id = $blogs->user_id;
                            $users = $wpdb->get_row("SELECT user_login FROM $users_table WHERE ID=$member_id");
                            $user_login = $users->user_login;
                            $date = $blogs->Date;
                            $parts = explode(' ', $date);
                            $d = $parts[0];
                            $date1 = explode('-', $d);
                            $year = $date1[0];
                            $month = $date1[1];
                            $day = $date1[2];
                            $newdate = "$day/$month/$date1[0]";
                            $blog_id = isset($_REQUEST['blog_id']) ? $_REQUEST['blog_id'] : '';
                            $goback = $_SERVER['HTTP_REFERER'];
                            if (isset($_REQUEST['Action']) && $_REQUEST['Action'] == "Del") {   // DELETE ALBUM
                                $wpdb->query("DELETE FROM $dsp_my_blog_table WHERE blog_id = '$blog_id'");
                                $sendback = remove_query_arg(array('Action', 'blog_id'), $goback);
                                header("Location:" . $sendback);
                            }
                            if (isset($_REQUEST['Action']) && $_REQUEST['Action'] == "update") {   // DELETE ALBUM
    //echo "update";
                                //$wpdb->query("DELETE FROM $dsp_my_blog_table WHERE blog_id = '$blog_id'");  
                                //echo $sendback = remove_query_arg( array('Action', 'blog_id'), $goback);
                                //header("Location:".$sendback);
                            }
                            ?>
                        <ul class="blog-page  dsp-clearfix">
                                <li>
                                    <span style="float: left;padding-left:5px; vertical-align:top; width:17%;"><?php echo $blog_title = stripslashes($blogs->blog_title); ?></span>
                                    <span style="float: left;vertical-align:top;width:17%;"><?php echo $newdate; ?></span> <span style="float: left;vertical-align:top;width:55%;"><?php echo $blog_title = stripslashes($blogs->blog_content); ?></span>
                                </li>
                            </ul>
                        <?php } 
                        }else{ ?>
                            <ul class="blog-page">
                                <li>
                                    <span class="dsp_span_pointer"><?php echo language_code('DSP_NO_RESULT_FOUND'); ?></span><br />
                                </li>
                            </ul>
                        <?php } ?>
                </div>
            </div>
        </form>
        <?php //-------------------------------------END MY BLOGS -------------------------------------//  
        }   // ------------------------------------------------- End if Check in my friend list --------------------------------- //
    }
