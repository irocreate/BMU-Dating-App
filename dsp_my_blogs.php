<form name="" method="GET" action="">
    <input type="hidden" name="pid" value="12" />
    <input type="hidden" name="pagetitle" value="my_blogs" />
    <?php //-------------------------------------START MY BLOGS -------------------------------------// ?>

    <div class="box-border">
        <div class="box-pedding">
            <div class="heading-submenu dsp-blog-title"><strong><?php echo language_code('DSP_MENU_MY_BLOGS') ?></strong></div>
            <div class="row">
                <div class="dspdb_blog_head">
                    <div style="width:20%; float:left;"><strong><?php echo language_code('DSP_ADD_MY_BLOGS_TITLE') ?></strong></div>
                    <div style="width:20%; float:left;"><strong><?php echo language_code('DSP_ADD_MY_BLOGS_DATE') ?></strong></div>
                    <div style="width:60%; float:left;"><strong><?php echo language_code('DSP_ADD_MY_BLOG') ?></strong></div>
                </div>
                <div><span class="dspdp-seprator "></span></div>
                
                <?php
                $dsp_my_blog_table = $wpdb->prefix . DSP_MY_BLOGS_TABLE;
                $users_table = $wpdb->prefix . DSP_USERS_TABLE;

                $blogs_table = $wpdb->get_results("SELECT * FROM $dsp_my_blog_table WHERE user_id=$user_id");

                foreach ($blogs_table as $blogs) {
                    $user_id = $blogs->user_id;
                    $users = $wpdb->get_var("SELECT user_login FROM $users_table WHERE ID=$user_id");
                    $user_login = $users;
                    $date = $blogs->Date;
                    $parts = explode(' ', $date);
                    $d = $parts[0];
                    $date1 = explode('-', $d);
                    $year = $date1[0];
                    $month = $date1[1];
                    $day = $date1[2];
                    $newdate = "$day/$month/$date1[0]";
                    $blog_id = get('blog_id');
                    $goback = $_SERVER['HTTP_REFERER'];
                    if (get('Action') == "Del") {   // DELETE ALBUM
                        $wpdb->query("DELETE FROM $dsp_my_blog_table WHERE blog_id = '$blog_id'");
                        $sendback = $root_link . "extras/blogs/my_blogs/";
                        echo "<script type='text/javascript'> location.href='" . $sendback . "'</script>";


                        // header("Location:".$sendback);
                    }
                    ?>
                    <div class="dspdb_blog_head">
                        <div style="width:20%; float:left;"><?php echo $blog_title = stripslashes( $blogs->blog_title ); ?></div>
                        <div style="width:20%; float:left;"><?php echo $newdate; ?></div>
                        <div style="width:60%; float:left;"><?php echo stripslashes($blogs->blog_content); ?></div>
                    </div>
                    <div class="dspdb_blog_head">
                        <div style="float:right">
                            <a class="dspdp-btn dspdp-btn-info dspdp-btn-xs dsp-btn dsp-btn-default dsp-btn-xs" href="<?php echo $root_link . "extras/blogs/add_blogs/blog_id/" . $blogs->blog_id . "/mode/edit/"; ?>">Edit</a>
                            <span  onclick="delete_blog('<?php echo $blog_id = $blogs->blog_id; ?>');" class="delete-blog dspdp-btn dspdp-btn-danger dspdp-btn-xs  dsp-btn dsp-btn-default dsp-btn-xs"><?php echo language_code('DSP_DELETE'); ?></span></td>
                        </div>
                    </div>
                    
                    <div style="margin-top:0.5rem;"><span class="dspdp-seprator "></span></div>
                    
                    
                    <?php } ?>
                    
                    </div>
                            

        </div>
    </div>
</form>
