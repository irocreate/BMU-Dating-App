<?php
include_once("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

$user_id = $_REQUEST['user_id'];

$member_id = $_REQUEST['member_id'];



$dsp_my_blog_table = $wpdb->prefix . DSP_MY_BLOGS_TABLE;

if (isset($_REQUEST['blog_id'])) {

    $blog_id = $_REQUEST['blog_id'];
    //echo "SELECT * FROM $dsp_my_blog_table WHERE user_id=$member_id  and blog_id=$blog_id";
    $blogDetail = $wpdb->get_row("SELECT * FROM $dsp_my_blog_table WHERE user_id=$member_id  and blog_id=$blog_id");

    echo $blogDetail->blog_content;
} else {
    ?>



    <div class="swipe_div" id="mainBlog" style="height:45px; ">
        <ul id="swipe_ulBlog"  style="padding-left:0px;text-align: left; top: 0px; bottom: auto; left: 0px; margin: 0px; width: 2169px; height: 82px; float: none; position: absolute; right: auto; z-index: auto;list-style:none;">

            <?php
            $blogs_table = $wpdb->get_results("SELECT * FROM $dsp_my_blog_table WHERE user_id=$member_id");


            foreach ($blogs_table as $blogs) {
                $blog_id = $blogs->blog_id;

                /* 	$user_id=$blogs->user_id;

                  $users= $wpdb->get_row("SELECT user_login FROM $users_table WHERE ID=$member_id");

                  $user_login=$users->user_login;

                  $date=$blogs->Date;
                  if(isset($date) && $date!="")
                  {
                  $now = time(); // or your date as well
                  $your_date = strtotime($date);
                  $datediff = $now - $your_date;
                  $days= floor($datediff/(60*60*24));
                  if($days==0)
                  {
                  $pub="Today";
                  }
                  else {
                  $pub=$days." days ago";
                  }
                  }
                  else
                  {
                  $pub="";
                  }
                 */
                ?>

                <li style="float:left;margin-right:16px;width:85px;word-wrap: break-word;">
                    <a onclick="viewBlog('<?php echo $blog_id; ?>')">
                        <?php
                        $blog_title = $blogs->blog_title;
                        echo substr($blog_title, 0, 10);
                        ?>
                    </a>


                </li>


            <?php } ?>


        </ul>
    </div>

<?php } ?>