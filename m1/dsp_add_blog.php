<div role="banner" class="ui-header ui-bar-a" data-role="header">
 <?php include_once("page_back.php");?> 
 <h1 aria-level="1" role="heading" class="ui-title">
    <?php
    if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'edit') {
        echo language_code('DSP_EDIT_ALBUM');
    } else {
        echo language_code('DSP_MENU_ADD_MY_BLOGS');
    }
    ?></h1>
    <?php include_once("page_home.php");?> 

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
         
           <?php if (isset($updated)) {
            ?>

            <div class="thanks success-message">
                <?php echo $updated ?>
            </div>
            <?php } ?>
            <form id="dsp_trending" >
                <fieldset>
                  <div data-role="fieldcontain">
                    <input type="text" name="title" class="input-control" id="blog-title" placeholder="<?php echo language_code('DSP_ADD_MY_BLOGS_TITLE') ?>" value="<?php echo $my_blog_table->blog_title; ?>" />
                </div>
                <div data-role="fieldcontain">
                    <textarea name="blog_content" id="blog-content" placeholder="<?php echo language_code('DSP_ADD_MY_BLOG') ?>" class="textarea-box"  ><?php echo $my_blog_table->blog_content; ?></textarea>
                </div>
                <div data-role="fieldcontain">
                 <input type="text" class="input-control" placeholder="<?php echo language_code('DSP_ADD_MY_BLOGS_TAGS') ?>" name="tag" id="blog-tag" value="<?php echo $my_blog_table->tag; ?>" />
             </div>
             <input type="hidden" name="pagetitle" value="blogs" />
             <input type="hidden" name="mode" value="edit" />
             <input type="hidden" name="action" value="update" />
             <input type="hidden" name="subpage" value="add_blogs" />
             <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
             <input type="hidden" name="blog_id" value="<?php echo $blog_id; ?>" />
             <div class="btn-blue-wrap">
                <input type="button" name="submit" class="mam_btn btn-blue"  onclick="viewExtra(0, 'post')" value="Edit"   />
            </div>
        </fieldset>

    </form>

    
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



    <div class="ui-content" data-role="content" role="main">
        <div class="content-primary">	
            <?php
            if (isset($updated)) {
                ?>

                <div class="thanks success-message">
                    <?php echo $updated ?>
                </div>
                <?php } ?>

                <form id="dsp_trending" >
                    <fieldset>
                        <div data-role="fieldcontain">
                            <input type="text" name="title" id="blog-title" class="input-control" placeholder="<?php echo language_code('DSP_ADD_MY_BLOGS_TITLE') ?>" value="" />
                        </div>
                        <div data-role="fieldcontain">
                            <textarea name="blog_content" placeholder="<?php echo language_code('DSP_ADD_MY_BLOG') ?>" id="blog-content" class="textarea-box"></textarea>
                        </div>
                        <div data-role="fieldcontain">
                            <input type="text" name="tag" class="input-control" id="blog-tag" value="" placeholder="<?php echo language_code('DSP_ADD_MY_BLOGS_TAGS') ?>" />
                        </div>
                        <input type="hidden" name="pagetitle" value="blogs" />
                        <input type="hidden" name="mode" value="add" />
                        <input type="hidden" name="subpage" value="add_blogs" />
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                        <div class="btn-blue-wrap">
                            <input type="button" class="mam_btn btn-blue"  name="submit" class="blog-add" onclick="viewExtra(0, 'post')" value="<?php echo language_code('DSP_ADD_MY_BLOGS_ADD_BUTTON') ?>"   />
                        </div>
                    </fieldset>

                </form>
                
            </div>
            <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
        </div>






        <?php } ?>
