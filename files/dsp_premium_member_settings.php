<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ********************************  Blacklist SETINGS ************************************ //
global $wpdb;
$current_user = wp_get_current_user();

$dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
$settings_root_link = get_bloginfo('url') . "/wp-admin/admin.php?page=dsp-admin-sub-page1&pid=" . $pageURL;
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
?>
<style type="text/css">#footer { position:relative; }</style>
<?php
if ($mode == "update") {
    if (isset($_REQUEST['extend'])) {
        $days = isset($_REQUEST['days']) ? $_REQUEST['days'] : '';
        $mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
       //$exp = isset($_REQUEST['exp']) ? $_REQUEST['exp'] : '';
        $exp = date('Y-m-d');
        $id = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
        $nameorip = isset($_REQUEST['nameorip']) ? $_REQUEST['nameorip'] : '';
        $wpdb->query("UPDATE $dsp_payments_table SET expiration_date=DATE_ADD('$exp', INTERVAL $days DAY), pay_plan_days= (pay_plan_days + $days )where pay_user_id = $id");
        $sendback = remove_query_arg(array('mode', 'uid','exp','nameorip'), $_SERVER['HTTP_REFERER']);
        echo '<script>window.location="' . $sendback . '";</script>';
    }
}
if ($mode == "gupdate") {
    if (isset($_POST['grant_extend_days'])) {
        $radio_membership = isset($_REQUEST['membership']) ? $_REQUEST['membership'] : '';
        if (empty($radio_membership)) {
            ?>
            <script>alert('You must select a membership');</script>
            <?php
        }
        $days = isset($_REQUEST['days']) ? $_REQUEST['days'] : '';
        $mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
        $id = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
        $today = date('Y-m-d');
        $grant_membership_user = isset($_REQUEST['grant_membership_user']) ? $_REQUEST['grant_membership_user'] : '';
        $membership = isset($_REQUEST['membership']) ? $_REQUEST['membership'] : '';

        $membership_name = $wpdb->get_row("SELECT * FROM $dsp_memberships_table WHERE membership_id=$membership");


        $wpdb->query("INSERT INTO $dsp_payments_table SET pay_user_id = $id,pay_plan_id=$membership, pay_plan_days ='$days',pay_plan_name='" . $membership_name->name . "', start_date='$today', expiration_date=DATE_ADD('$today', INTERVAL $days DAY),payment_status=1");
    }
}
?> 
<div>
    <div id="general" class="postbox">
        <h3 class="hndle"><span><?php echo language_code('DSP_SEARCH_PREMIUM_MEMBERS'); ?></span></h3>
        <div style="height:20px;"></div>
        <div class="dsp_thumbnails1" style="padding-bottom:20px;" >
            <div style="width:320px;">
                <form name="search" method="post"  action="">
                    <div style="float:none;" >
                        <input name="nameorip" type="text" style="width:170px; float:left; margin-right:20px;" />	&nbsp;	<input name="search" type="submit" value="Search" class="button" />
                    </div>
                </form>
            </div>
            <?php if ($mode == "update") { ?>
                <div style="border-bottom:1px solid #EEEEEE; border-top:1px solid #EEEEEE; padding-top:10px; padding-bottom:10px;">
                    <div style="height:20px;"></div>
                    <?php
                    $nameorip = isset($_REQUEST['nameorip']) ? $_REQUEST['nameorip'] : '';
                    $today = date('Y-m-d');
                    $users_table = $wpdb->get_results("SELECT * FROM $dsp_users_table user,$dsp_payments_table payments where  user.ID=payments.pay_user_id and user.user_login like '%$nameorip%' and payment_status= 1");
                    foreach ($users_table as $users) {
                        $pay_user_id = $users->pay_user_id;
                        ?>
                        <form name="extend" method="POST"  action="<?php
                        echo add_query_arg(array(
                            'pid' => 'premium_member', 'mode' => 'update', 'uid' => $pay_user_id,
                            'exp' => $users->expiration_date, 'nameorip' => $nameorip), $settings_root_link);
                        ?>" >
                            <table width="100%" border="0" style="border-bottom:1px solid #EEEEEE; border-top:1px solid #EEEEEE; padding-top:5px; padding-bottom:5px;  margin-bottom:-1px;">
                                <tr style="line-height: 10px;">
                                    <td width="35%"><label><?php echo $users->user_login; ?></label></td>
                                    <td width="20%"><?php echo date('m-d-Y', strtotime($users->expiration_date)); ?></td>
                                    <td width="20%"><input name="days" type="text"  style="width:50px;height: 25px;"  onclick="this.value"/></td>
                                    <td width="10%"><input name="extend" type="submit" value="Extend"  class="button" 	/></td> 	
                                    <td width="15%"><a href="<?php
                                        echo add_query_arg(array(
                                            'pid' => 'premium_member', 'mode' => 'del',
                                            'uid' => $pay_user_id,
                                            'nameorip' => $nameorip), $settings_root_link);
                                        ?>"  style="text-decoration:none;"><input name="cancel_membership" type="button"  value="Cancel Membership"  class="button"  /></a></td>
                                </tr>
                            </table>
                        </form>
                    <?php } ?>
                </div>
                <?php
            } else if ($mode == "del") {
                $id = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : '';
                $nameorip = isset($_REQUEST['nameorip']) ? $_REQUEST['nameorip'] : '';
                $wpdb->query("DELETE FROM $dsp_payments_table where pay_user_id=$id ");
                ?>
                <div style="border-bottom:1px solid #EEEEEE; border-top:1px solid #EEEEEE; padding-top:10px; padding-bottom:10px;">
                    <div style="height:20px;"></div>
                    <?php
                    $nameorip = isset($_REQUEST['nameorip']) ? $_REQUEST['nameorip'] : '';
                    $today = date('Y-m-d');
//$user_profiles_table = $wpdb->get_results("SELECT pay_user_id ,DATEDIFF(expiration_date,CURDATE()) as days FROM wp_dsp_payments ");
                    $users_table = $wpdb->get_results("SELECT * FROM $dsp_users_table user,$dsp_payments_table payments where  user.ID=payments.pay_user_id and user.user_login like '%$nameorip%' and payment_status= 1");
                    foreach ($users_table as $users) {
                        $pay_user_id = $users->pay_user_id;
                        ?>
                        <form name="extend" method="POST"  action="<?php
                        echo add_query_arg(array(
                            'pid' => 'premium_member', 'mode' => 'update', 'uid' => $pay_user_id,
                            'exp' => $users->expiration_date, 'nameorip' => $nameorip), $settings_root_link);
                        ?>" >
                            <table width="100%" border="0" style="border-bottom:1px solid #EEEEEE; border-top:1px solid #EEEEEE; padding-top:5px; padding-bottom:5px;  margin-bottom:-1px;">
                                <tr style="line-height: 10px;">
                                    <td width="35%"><label><?php echo $users->user_login; ?></label></td>
                                    <td width="20%"><?php echo date('m-d-Y', strtotime($users->expiration_date)); ?></td>
                                    <td width="20%"><input name="days" type="text"  style="width:50px;height: 25px;"  onclick="this.value"/></td>
                                    <td width="10%"><input name="extend" type="submit" value="Extend"  class="button" 	/></td>
                                    <td width="15%"><a href="<?php
                                        echo add_query_arg(array(
                                            'pid' => 'premium_member', 'mode' => 'del',
                                            'uid' => $pay_user_id,
                                            'nameorip' => $nameorip), $settings_root_link);
                                        ?>"  style="text-decoration:none;"><input name="cancel_membership" type="button"  value="Cancel Membership"  class="button" /></a></td>
                                </tr>
                            </table>
                        </form>
                    <?php }
                    ?>
                </div>
            <?php } else { ?>
			
                <div>
                 <div style="height:20px;"></div>   
                    <?php
                    if (isset($_REQUEST['search'])) {
                        $nameorip = isset($_REQUEST['nameorip']) ? $_REQUEST['nameorip'] : '';
                        $today = date('Y-m-d');
                        $users_table = $wpdb->get_results("SELECT * FROM $dsp_users_table user,$dsp_payments_table payments where  user.ID=payments.pay_user_id and user.user_login like '%$nameorip%' and payment_status != 0");
                        foreach ($users_table as $users) {
                            $pay_user_id = $users->pay_user_id;
                            ?>
                            <form name="extend" method="POST"  action="<?php
                            echo add_query_arg(array(
                                'pid' => 'premium_member', 'mode' => 'update', 'uid' => $pay_user_id,
                                'exp' => $users->expiration_date, 'nameorip' => $nameorip), $settings_root_link);
                            ?>" >
                                <table width="100%" border="0"  style="border-bottom:1px solid #EEEEEE; border-top:1px solid #EEEEEE; padding-top:5px; padding-bottom:5px;  margin-bottom:-1px;">
                                    <tr style="line-height: 10px;">
                                        <td width="35%"><label><strong><?php echo $users->user_login; ?></strong></label></td>
                                        <td width="10%"><?php echo date('m-d-Y', strtotime($users->expiration_date)); ?></td>
                                        <td width="10%"><?php echo $users->pay_plan_name; ?></td>
                                        <td width="20%"><input name="days" type="text"  style="width:100%;"  onclick="this.value" placeholder="<?php echo language_code('DSP_MEMBERSHIPS_DAYS_NO'); ?>" /></td>
                                        <td width="10%"><input name="extend" type="submit" value="Extend"  class="button" 	/></td>
                                        <td width="15%"><a href="<?php
                                            echo add_query_arg(array(
                                                'pid' => 'premium_member', 'mode' => 'del',
                                                'uid' => $pay_user_id,
                                                'nameorip' => $nameorip), $settings_root_link);
                                            ?>" style="text-decoration:none;"><input name="cancel_membership" type="button"  value="Cancel Membership"  class="button"  /></a></td>
                                    </tr>
                                </table>
                            </form>
                            <?php
                        }
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div>
        <div id="general" class="postbox" >
            <h3 class="hndle"><span><?php echo language_code('DSP_GRANT_PREMIUM_MEMBERSHIP'); ?></span></h3>
            <div style="height:20px;"></div>
            <div class="dsp_thumbnails2" style="padding-bottom:20px; padding-right:15px; width:auto;" >
                <div style="width:320px;">
                    <form name="grantsearch" method="post"  action="">
                        <div style="float:none;" >
                            <input name="grant_membership_user" type="text" style="width:170px; float:left; margin-right:20px;" />&nbsp;<input name="gsearch" type="submit" value="Search"  class="button"  />
                        </div>
                    </form>
                </div>
                <?php if ($mode == "gupdate") { ?>
                    <div>
                        <div style="height:20px;"></div>
                        <?php
                        $grant_membership_user = isset($_REQUEST['grant_membership_user']) ? $_REQUEST['grant_membership_user'] : '';
                        $today = date('Y-m-d');
                        $users_table = $wpdb->get_results("SELECT * FROM $dsp_users_table user WHERE NOT EXISTS (SELECT * FROM $dsp_payments_table payments WHERE user.ID=payments.pay_user_id ) and user.user_login like '%$grant_membership_user%' ");
                        foreach ($users_table as $users) {
                            $user_id = $users->ID;
                            ?>
                            <form name="extend" method="POST"  action="<?php
                            echo add_query_arg(array(
                                'pid' => 'premium_member', 'mode' => 'gupdate',
                                'uid' => $user_id, 'grant_membership_user' => $grant_membership_user), $settings_root_link);
                            ?>" >
                                <table width="100%" border="0"  style="border-bottom:1px solid #EEEEEE; border-top:1px solid #EEEEEE; padding-top:5px; padding-bottom:5px; margin-bottom:-1px;">
                                    <tr >
                                        <td style="width:30%;"><label><strong><?php echo $users->user_login; ?></strong></label></td>
                                        <td style="width:20%;"><input name="days" type="text"  style="width:100%"  onclick="this.value"  placeholder="<?php echo language_code('DSP_MEMBERSHIPS_DAYS_NO'); ?>" /></td>
                                        <td style="width:20%;"><input name="grant_extend_days" type="submit" value="Extend" class="button" 	/></td>
                                        <td style="width:30%;">

                                            <table width="100%" style="padding-left:10px;" >
                                                <?php
                                                $memberships_table = $wpdb->get_results("SELECT * FROM $dsp_memberships_table order by name asc");
                                                $i = 0;
                                                foreach ($memberships_table as $membership) {
                                                    if ($i % 4 == 0) {
                                                        echo "<tr>";
                                                    }
                                                    ?>
                                                    <td align="right"><input name="membership" type="radio" value="<?php echo $name = $membership->membership_id ?>" /></td>
                                                    <td ><?php echo $name = $membership->name ?></td>
                                                    <?php
                                                    $i++;
                                                }
                                                ?>
                                    </tr>
                                </table>
                                </td>
                                </tr>
                                </table>
                            </form>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div>
                        <div style="height:20px;"></div>
                        <?php
                        if (isset($_REQUEST['gsearch'])) {
                            $grant_membership_user = isset($_REQUEST['grant_membership_user']) ? $_REQUEST['grant_membership_user'] : '';
                            $today = date('Y-m-d');
                            $users_table = $wpdb->get_results("SELECT * FROM $dsp_users_table user WHERE NOT EXISTS (SELECT * FROM $dsp_payments_table payments WHERE user.ID=payments.pay_user_id ) and user.user_login like '%$grant_membership_user%' ");
                            foreach ($users_table as $users) {
                                $user_id = $users->ID;
                                ?>
                                <form name="extenddays" method="POST"  action="<?php
                                echo add_query_arg(array(
                                    'pid' => 'premium_member',
                                    'mode' => 'gupdate',
                                    'uid' => $user_id, 'grant_membership_user' => $grant_membership_user), $settings_root_link);
                                ?>" >
                                    <table  width="100%" border="0"  style="border-bottom:1px solid #EEEEEE; border-top:1px solid #EEEEEE; padding-top:5px; padding-bottom:5px; margin-bottom:-1px;">
                                        <tr >
                                            <td style="width:30%;"><label><strong><?php echo $users->user_login; ?></strong></label></td>
                                            <td style="width:20%"><input name="days" type="text"  style="width:100%"  onclick="this.value" placeholder="<?php echo language_code('DSP_MEMBERSHIPS_DAYS_NO'); ?>" /></td>
                                            <td style="width:20%;"><input name="grant_extend_days" type="submit" value="Extend"  class="button" 	/></td>
                                            <td style="width:30%;">

                                                <table width="100%" style="padding-left:10px;" >
                                                    <?php
                                                    $memberships_table = $wpdb->get_results("SELECT * FROM $dsp_memberships_table order by name asc");
                                                    $i = 0;
                                                    foreach ($memberships_table as $membership) {
                                                        if ($i % 4 == 0) {
                                                            echo "<tr>";
                                                        }
                                                        ?>
                                                        <td align="right"><input name="membership" type="radio" value="<?php echo $name = $membership->membership_id ?>" /></td>
                                                        <td ><?php echo $name = $membership->name ?></td>
                                                        <?php
                                                        $i++;
                                                    }
                                                    ?>
                                        </tr>
                                    </table>
                                    </td>
                                    </tr>
                                    </table>
                                </form>
                                <?php
                            }
                        }
                        ?>
                    </div>
                <?php } ?>
            </div>
            </table>
        </div>
    </div>
</div>