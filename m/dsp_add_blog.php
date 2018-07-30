<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <div class="back-image">
        <a onclick="viewExtra(0, 'blogs');"><?php echo language_code('DSP_BACK'); ?></a>
    </div>
    <h1 aria-level="1" role="heading" class="ui-title">
        <?php
        if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'edit') {
            echo language_code('DSP_EDIT_ALBUM');
        } else {
            echo language_code('DSP_MENU_ADD_MY_BLOGS');
        }
        ?></h1>
    <a data-icon="check" href="home.html" class="ui-btn-right ui-btn ui-btn-up-a ui-shadow ui-btn-corner-all" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="a">
        <span class="ui-btn-inner ui-btn-corner-all">
            <span class="ui-btn-text"><?php echo language_code('DSP_HOME_TAB_HOME'); ?></span>
        </span>
    </a>

</div>
<?php
$dsp_my_blog_table = $wpdb->prefix . DSP_MY_BLOGS_TABLE;

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'edit') {
    $blog_id = isset($_REQUEST['blog_id']) ? $_REQUEST['blog_id'] : '';

    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update') {

        $blog_id = isset($_REQUEST['blog_id']) ? $_REQUEST['blog_id'] : '';

        $blog_title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';

        $blog_content = isset($_REQUEST['blog_content']) ? $_REQUEST['blog_content'] : '';

        $tag = isset($_REQUEST['tag']) ? $_REQUEST['tag'] : '';

        $wpdb->query("UPDATE $dsp_my_blog_table SET blog_title = '$blog_title',blog_content = '$blog_content',tag ='$tag' WHERE blog_id  = '$blog_id'");
        $updated = "Blog updated successfully.";
    }

    $my_blog_table = $wpdb->get_row("SELECT * FROM $dsp_my_blog_table WHERE blog_id= $blog_id");
    ?>

    <div class="ui-content" data-role="content">
        <div class="content-primary">	
            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                     <?php if (isset($updated)) {
                            ?>

                            <div class="thanks">
                                <p align="center" class="error"><?php echo $updated ?></p>
                            </div>
                        <?php } ?>
                    <form id="dsp_trending" class="add_blog">
                             <ul style="list-style: none; padding-left: 10%">
                            <li><span><?php echo language_code('DSP_ADD_MY_BLOGS_TITLE') ?></span> 
                            <input type="text" name="title" id="blog-title" value="<?php echo $my_blog_table->blog_title; ?>" style="width:97%;"/></li>

                            <li><span><?php echo language_code('DSP_ADD_MY_BLOG') ?></span> 
                                <textarea name="blog_content" id="blog-content" cols="30" rows="5" style="width:97%; height:85px; overflow-x: hidden; overflow-y: auto;"  ><?php echo $my_blog_table->blog_content; ?></textarea></li>
                            <li>
                                <span><?php echo language_code('DSP_ADD_MY_BLOGS_TAGS') ?></span> 
                               <input style="width:97%" type="text" name="tag" id="blog-tag" value="<?php echo $my_blog_table->tag; ?>" />
                                <input type="hidden" name="pagetitle" value="blogs" />
                                <input type="hidden" name="mode" value="edit" />
                                <input type="hidden" name="action" value="update" />
                                <input type="hidden" name="subpage" value="add_blogs" />
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                <input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>" />
                                <div>
                                <input type="button" name="submit" onclick="viewExtra(0, 'post')" value="Edit"   />
                                </div>
                            </li>

                        </ul>

                    </form>

                </li>
            </ul>
        </div>
        <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
    </div>

    <?php
} else {

    if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'add') {
        $blog_title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
        $blog_content = isset($_REQUEST['blog_content']) ? $_REQUEST['blog_content'] : '';
        $tag = isset($_REQUEST['tag']) ? $_REQUEST['tag'] : '';
        $today = date("Y-m-d H:i:s");

        $insert = $wpdb->query("INSERT INTO $dsp_my_blog_table SET user_id = $user_id,blog_title='$blog_title',blog_content='$blog_content', Date='$today',tag='$tag'");
        $updated = "Blog saved successfully.";
    }






    //---------------------------------------START ACCOUNT SETTINGS ------------------------------------// 
    ?>



    <div class="ui-content" data-role="content">
        <div class="content-primary">	
            <ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul">
                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                    <?php
                    if (isset($updated)) {
                        ?>

                        <div class="thanks">
                            <p align="center" class="error"><?php echo $updated ?></p>
                        </div>
                    <?php } ?>

                    <form id="dsp_trending" class="add_blog">

                            <ul style="list-style: none; padding-left: 10%">
                                <li><span><?php echo language_code('DSP_ADD_MY_BLOGS_TITLE') ?></span>
                                    <input type="text" name="title" id="blog-title" value="" style="width:97%;"/>
                                </li>
                                <li><span><?php echo language_code('DSP_ADD_MY_BLOG') ?></span> <textarea name="blog_content" id="blog-content" cols="30" rows="5" style="width:97%; height:85px; overflow-x: hidden; overflow-y: auto; border: 1px solid #c0c0c0;"></textarea></li>
                                <li><span><?php echo language_code('DSP_ADD_MY_BLOGS_TAGS') ?></span>
                                    <div><input type="text" name="tag" id="blog-tag" value="" style="width:97%;" /></div>
                                    <input type="hidden" name="pagetitle" value="blogs" />
                                    <input type="hidden" name="mode" value="add" />
                                    <input type="hidden" name="subpage" value="add_blogs" />
                                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                    <div>
                                        <input type="button" name="submit" class="blog-add" onclick="viewExtra(0, 'post')" value="<?php echo language_code('DSP_ADD_MY_BLOGS_ADD_BUTTON') ?>"   />
                                    </div>
                                </li>
                            </ul>

                        </form>
                </li>
            </ul>
        </div>
        <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
    </div>






<?php } ?>
