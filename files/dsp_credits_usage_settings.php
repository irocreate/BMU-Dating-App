<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author -  www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

global $wpdb;
$dsp_credits_table = $wpdb->prefix . DSP_CREDITS_TABLE;
$dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$user_table = $wpdb->prefix . DSP_USERS_TABLE;
extract($_REQUEST);

if (isset($Action)) {
    if ($Action == 'reset') {
        $wpdb->query("update $dsp_credits_table set credits_purchased='0',credit_used='0'");
        $wpdb->query("truncate table $dsp_credits_usage_table");
        ?>
        <script>
            var loc = window.location.href;
            if (loc.search("Action") > -1)
            {
                index = loc.indexOf("Action")
                loc = loc.substring(0, index - 1);
            }
            window.location.href = loc;
        </script>
        <?php
    }

    if ($Action == 'update') {
        $chk_credit_row = $wpdb->get_var("select count(*) from $dsp_credits_usage_table where user_id='$user_id'");
        $credit_row = $wpdb->get_row("select * from $dsp_credits_table");
        $emails_per_credit = !empty($credit_row->emails_per_credit) ? $credit_row->emails_per_credit : 2 ;
        $gift_per_credit = !empty($credit_row->gifts_per_credit) ? $credit_row->gifts_per_credit : 2;
        $new_emails = $credit * $emails_per_credit;
        $new_gifts = $credit * $gift_per_credit;
        if ($chk_credit_row > 0) {
            $credit_usage_row = $wpdb->get_row("select * from $dsp_credits_usage_table where user_id='$user_id'");
            $wpdb->update($dsp_credits_usage_table, array('no_of_credits' => $credit_usage_row->no_of_credits + $credit,
                'no_of_emails' => $credit_usage_row->no_of_emails + $new_emails, 'no_of_gifts' => $credit_usage_row->no_of_gifts + $new_gifts), array(
                'user_id' => $user_id));
        } else {
            $wpdb->insert($dsp_credits_usage_table, array('no_of_credits' => $credit,
                'no_of_emails' => $new_emails,'no_of_gifts' => $new_gifts, 'user_id' => $user_id));
        }
        $wpdb->query("update $dsp_credits_table set credits_purchased=credits_purchased+$credit");
        ?>
        <script>
            var loc = window.location.href;
            if (loc.search("Action") > -1)
            {
                index = loc.indexOf("Action")
                loc = loc.substring(0, index - 1);
            }
            window.location.href = loc + "&Action=none&username=<?php if (isset($username)) echo $username ?>";
        </script>
        <?php
    }
    if ($Action == 'remove') {
        $chk_credit_row = $wpdb->get_var("select count(*) from $dsp_credits_usage_table where user_id='$user_id'");
        $credit_row = $wpdb->get_row("select * from $dsp_credits_table");
        $emails_per_credit = $credit_row->emails_per_credit;
        $new_emails = $credit * $emails_per_credit;
        if ($chk_credit_row > 0) {
            $credit_usage_row = $wpdb->get_row("select * from $dsp_credits_usage_table where user_id='$user_id'");
            $wpdb->update($dsp_credits_usage_table, array('no_of_credits' => $credit_usage_row->no_of_credits - $credit,
                'no_of_emails' => $credit_usage_row->no_of_emails - $new_emails), array(
                'user_id' => $user_id));
        } else {
            //$wpdb->insert($dsp_credits_usage_table,array('no_of_credits'=>$credit,'no_of_emails'=>$new_emails,'user_id'=>$user_id));	
        }
        $wpdb->query("update $dsp_credits_table set credits_purchased=credits_purchased-$credit");
        ?>
        <script>
            var loc = window.location.href;
            if (loc.search("Action") > -1)
            {
                index = loc.indexOf("Action")
                loc = loc.substring(0, index - 1);
            }
            window.location.href = loc + "&Action=none&username=<?php if (isset($username)) echo $username ?>";
        </script>
        <?php
    }
}

$credit_row = $wpdb->get_row("select * from $dsp_credits_table");
?>
<script>
    function reset_credit() {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=reset";
        window.location.href = loc;
    }

    function credit_change(user, action) {
        var credit = document.getElementById("extend_credit_value_" + user).value;

        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=" + action + "&user_id=" + user + "&credit=" + credit + "&username=<?php if (isset($username)) echo $username ?>";

        window.location.href = loc;
    }

</script>
<div id="general" class="postbox credit-usage" >
    <h3 class="hndle"><span><?php echo language_code('DSP_CREDITS_USAGE'); ?></span></h3>

    <div class="credit-usage-box">
        <div class="credit-usage-info-box"><div class="credit-usage-row"><div class="credit-usage-left"><?php echo language_code('DSP_CREDITS_PURCHASED'); ?></div>  <div class="credit-usage-right"><span class="credit-purchased"><?php echo $credit_row->credits_purchased; ?></span>   <input type="button" value="<?php echo language_code('DSP_RESET_BUTTON'); ?>" class="button"/ onclick="reset_credit();"></div></div>
            <div class="credit-usage-row"><div class="credit-usage-left"><?php echo language_code('DSP_CREDITS_USED'); ?></div>   <div class="credit-usage-right"><?php echo $credit_row->credit_used; ?></div></div></div>

        <form method="get">
            <input type="hidden" name="page" value="dsp-admin-sub-page1" />
            <input type="hidden" name="pid" value="credits_usage" />
            <div class="credit-usage-username-search-box"> <div class="credit-usage-row"><div class="credit-usage-left"><?php echo language_code('DSP_USER_NAME'); ?></div>  <div class="credit-usage-right"><input type="text" name="username" value="<?php if (isset($username)) echo $username; ?>" /> <input type="submit"  class="button" name="user_for_credit" value="<?php echo language_code('DSP_SEARCH_BUTTON'); ?>"/></div></div></div>
        </form>

        <div class="credit-usage-user-result-box">
            <?php
            if (isset($username)) {
                $page_name = $settings_root_link . '&username=' . $username;
                if (isset($_GET['page1']))
                    $page = $_GET['page1'];
                else
                    $page = 1;

                // How many adjacent pages should be shown on each side?
                $adjacents = 2;
                $limit = 20;
                if ($page)
                    $start = ($page - 1) * $limit;    //first item to display on this page
                else
                    $start = 0;

                $total_results1 = $wpdb->get_var("select count(p.user_id) from $user_table u inner join $dsp_user_profiles p on(p.user_id=u.ID) where u.user_login like'%$username%'");

                //******************************************************************************************************************************************

                if ($page == 0)
                    $page = 1;     //if no page var is given, default to 1.
                $prev = $page - 1;
                $next = $page + 1;
                $lastpage = ceil(@$total_results1 / $limit);  //lastpage is = total pages / items per page, rounded up.
                $lpm1 = $lastpage - 1;

                /*
                  Now we apply our rules and draw the pagination object.
                  We're actually saving the code to a variable in case we want to draw it more than once.
                 */
                $pagination = "";
                if ($lastpage > 1) {
                    $pagination .= "<div class='wpse_pagination' style=\"float: none;
margin-left: 40%;\">";
                    //previous button
                    if ($page > 1)
                        $pagination.= "<div><a style='color:#474545' href=\"" . $page_name . "&page1=$prev\">previous</a></div>";
                    else
                        $pagination.= "<span  class='disabled'>previous</span>";

                    //pages	
                    if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
                        for ($counter = 1; $counter <= $lastpage; $counter++) {
                            if ($counter == $page)
                                $pagination.= "<span class='current'>$counter</span>";
                            else
                                $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
                        }
                    }
                    elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
                        //close to beginning; only hide later pages
                        if ($page < 1 + ($adjacents * 2)) {
                            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                                if ($counter == $page)
                                    $pagination.= "<span class='current'>$counter</span>";
                                else
                                    $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
                            }
                            $pagination.= "<span>...</span>";
                            $pagination.= "<div><a href=\"" . $page_name . "&page1=$lpm1\">$lpm1</a></div>";
                            $pagination.= "<div><a href=\"" . $page_name . "&page1=$lastpage\">$lastpage</a></div>";
                        }
                        //in middle; hide some front and some back
                        elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                            $pagination.= "<div><a href=\"" . $page_name . "&page1=1\">1</a></div>";
                            $pagination.= "<div><a href=\"" . $page_name . "&page1=2\">2</a></div>";
                            $pagination.= "<span>...</span>";
                            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                                if ($counter == $page)
                                    $pagination.= "<div class='current'>$counter</div>";
                                else
                                    $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
                            }
                            $pagination.= "<span>...</span>";
                            $pagination.= "<div><a href=\"" . $page_name . "&page1=$lpm1\">$lpm1</a></div>";
                            $pagination.= "<div><a href=\"" . $page_name . "&page1=$lastpage\">$lastpage</a></div>";
                        }
                        //close to end; only hide early pages
                        else {
                            $pagination.= "<div><a href=\"" . $page_name . "&page1=1\">1</a></div>";
                            $pagination.= "<div><a href=\"" . $page_name . "&page1=2\">2</a></div>";
                            $pagination.= "<span>...</span>";
                            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                                if ($counter == $page)
                                    $pagination.= "<span class='current'>$counter</span>";
                                else
                                    $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
                            }
                        }
                    }

                    //next button
                    if ($page < $counter - 1)
                        $pagination.= "<div><a style='color:#474545' href=\"" . $page_name . "&page1=$next\">next</a></div>";
                    else
                        $pagination.= "<span class='disabled'>next</span>";
                    $pagination.= "</div>\n";
                }

                $user_rows = $wpdb->get_results("select p.user_id,u.user_login from $user_table u inner join $dsp_user_profiles p on(p.user_id=u.ID)where u.user_login like'%$username%'  LIMIT $start, $limit");
                foreach ($user_rows as $credit_user_row) {
                    $creditcount = $wpdb->get_var("select no_of_credits from $dsp_credits_usage_table where user_id='" . $credit_user_row->user_id . "'");
                    if ($creditcount == null)
                        $creditcount = 0;
                    ?>
                    <div class="credit-usage-row"><div class="credit-usage-left"><?php echo $credit_user_row->user_login; ?></div>  <div class="credit-usage-right"><span class="user-credit-purchased"><?php echo $creditcount; ?></span> <input id="extend_credit_value_<?php echo $credit_user_row->user_id; ?>" type="text" name="extend_credit_value" value="" /> <input type="submit"  class="button" name="extend_credit" value="<?php echo language_code('DSP_EXTEND_BUTTON'); ?>" onclick="credit_change('<?php echo $credit_user_row->user_id; ?>', 'update');" /> <input type="submit"  class="button" name="remove" value="<?php echo language_code('DSP_REMOVE_BUTTON'); ?>" onclick="credit_change('<?php echo $credit_user_row->user_id; ?>', 'remove');" /></div></div>
                    <?php
                }
            }
            ?>

            <div class="paging-box-withbtn" style="width: 100%;">
                <?php
                // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                if (isset($pagination))
                    echo $pagination;
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                ?>
            </div>  
        </div>
    </div>

</div>