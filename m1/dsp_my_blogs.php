<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_back.php");?>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo language_code('DSP_MENU_MY_BLOGS'); ?></h1>
    <?php include_once("page_home.php");?>

</div>



<input type="hidden" name="pagetitle" value="my_blogs" />


<?php //-------------------------------------START MY BLOGS -------------------------------------// ?>




<div class="ui-content" data-role="content">
    <div class="content-primary">	
        <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">

            <?php
            $dsp_my_blog_table = $wpdb->prefix . DSP_MY_BLOGS_TABLE;

            $users_table = $wpdb->prefix . DSP_USERS_TABLE;

            if (isset($_GET['Action']) && $_GET['Action'] == "Del") {   // DELETE ALBUM
                $blog_id = $_REQUEST['blog_id'];
                $wpdb->query("DELETE FROM $dsp_my_blog_table WHERE blog_id = '$blog_id'");
            }

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

                $blog_id = isset($_REQUEST['blog_id']) ? $_REQUEST['blog_id'] : '';

                $goback = $_SERVER['HTTP_REFERER'];


                $content = $blog_title = $blogs->blog_content;
                $subContent = substr($content, 0, 10);
                ?>
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                    <ul style="list-style: none;padding-left: 0px;">

                        <li style="width:75%;float: left;word-wrap: break-word; ">
                            <a onclick="viewExtra('<?php echo $blog_id = $blogs->blog_id; ?>', 'add_blogs')">
                                <?php echo $blog_title = $blogs->blog_title; ?>
                            </a>
                        </li>
                        <li  style="text-align: right; width:25%; float: right;font-size: 10px;"><?php echo $newdate; ?></li>
                        
                        <li style="width:100%;float: left; word-wrap: break-word;">
                            <div>
                                <a onclick="viewExtra('<?php echo $blog_id = $blogs->blog_id; ?>', 'add_blogs')">
                            <?php echo $subContent . '...'; ?>
                                </a>
                            </div>
                        </li>
                        
                        <li style="float: left; width: 100%">                           
                            <a  class="reply-btn" onclick="viewExtra('<?php echo $blog_id = $blogs->blog_id; ?>', 'add_blogs')">
                                <?php echo language_code('DSP_EDIT'); ?>
                            </a> |
                            <span class="delete-btn" onclick="viewExtra('<?php echo $blog_id = $blogs->blog_id; ?>', 'my_blogs');" >
                                <?php echo language_code('DSP_DELETE'); ?>
                            </span>
                            
                        </li>
                    </ul>
                </li>
            <?php } ?>

        </ul>

    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>