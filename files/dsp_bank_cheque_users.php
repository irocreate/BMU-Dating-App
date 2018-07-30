<?php
 $dsp_gateways_table = $wpdb->prefix . DSP_GATEWAYS_TABLE;
 $dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
 $dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
 $dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
 $dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
 $chequeBankwireUsers = '';
 $gateway_id = isset($_REQUEST['payment_method']) ? $_REQUEST['payment_method'] : 6;
 $action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : null;
 $upadateUserId = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : null;

// if(isset($_REQUEST['payment_method'])){
 //	if(!empty($gateway_id))
 		$chequeBankwireUsers = apply_filters('dsp_get_payments_users_by_bankwire_cheque',$gateway_id); 
//}

if(isset($action) && $action == 'update')
{
	
	$update_payment_details = $wpdb->get_row("SELECT * FROM $dsp_temp_payments_table where user_id='$upadateUserId'");
	
	$check_already_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_user_id='$upadateUserId'");
	    if ($check_already_user_exists <= 0) {
	        $wpdb->query("INSERT INTO $dsp_payments_table SET pay_user_id = '$update_payment_details->user_id',pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id',recurring_profile_status='1'");
	    } else {
	        $wpdb->query("UPDATE $dsp_payments_table SET pay_plan_id = '$update_payment_details->plan_id',pay_plan_amount ='$update_payment_details->plan_amount',pay_plan_days='$update_payment_details->plan_days',pay_plan_name='$update_payment_details->plan_name',payment_date='$update_payment_details->payment_date ',start_date='$update_payment_details->start_date',expiration_date='$update_payment_details->expiration_date',payment_status=1,recurring_profile_id='$update_payment_details->recurring_profile_id', recurring_profile_status='1'  WHERE pay_user_id = '$update_payment_details->user_id'");
	        $wpdb->query("UPDATE $dsp_temp_payments_table SET payment_status = 1  WHERE user_id = '$upadateUserId'");
	    }
	    if(dsp_issetGivenEmailSetting($upadateUserId,'payment_successful')){
	        $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='16'");
	        $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$upadateUserId'");
	        $reciver_name = $reciver_details->display_name;
	        $receiver_email_address = $reciver_details->user_email;
	        $siteurl = get_option('siteurl');
	        $email_subject = $email_template->subject;
	        $email_message = $email_template->email_body;
	        $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
	        $email_message = str_replace("<#DOMAIN_NAME#>", $siteurl, $email_message);
	        $MemberEmailMessage = $email_message;
	        $to = $receiver_email_address;
	        $subject = $email_subject;
	        $message = $MemberEmailMessage;
	        $admin_email = get_option('admin_email');
	        $from = $admin_email;
	        $headers = "From: $from";
	        // wp_mail($to, $subject, $message, $headers);
            $wpdating_email  = Wpdating_email_template::get_instance();
            $result = $wpdating_email->send_mail( $to, $subject, $message);
	    }
	 
	
}


?>
<div id="general" class="postbox">
    <h3 class="hndle"><span><?php echo language_code('DSP_BANK_CHEQUE_USERS') ?></span></h3>
   	<form name="admin.php?page=dsp-admin-sub-page1&pid=gateways_settings" method="post">
        <?php echo language_code('DSP_SELECT_PAYMENT_GATEWAYS') ?><span style=" margin-left:105px;">
            <select name="payment_method" onchange="this.form.submit();" style="width:150px;">
                <?php
                $checkValues = array('bank_wire','cheque_payment');
                $gateway_table = $wpdb->get_results("SELECT * FROM $dsp_gateways_table");
                foreach ($gateway_table as $gatway) {
                	if(in_array($gatway->gateway_name,$checkValues)):
                ?>
                    	<option value="<?php echo $gatway->gateway_id; ?>" <?php if (isset($_REQUEST['payment_method']) && $_REQUEST['payment_method'] == $gatway->gateway_id) { ?> selected="selected" <?php } ?> ><?php echo $gatway->display_name; ?></option>
                	<?php endif; ?>
                <?php } ?>
            </select>  
        </span>
    </form> 
<?php if(isset($chequeBankwireUsers) && !empty($chequeBankwireUsers)): ?>    
   <div style="margin:20px">
        <table class="wp-list-table widefat fixed users" border="0" width="100%">
        	<thead>    
                    <tr>     
                        <th><strong>Id</strong></th>
                        <th><strong><?php _e(language_code('DSP_USER_NAME')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_PLAN_NAME')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_PLAN_AMOUNT')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_PLAN_DAYS')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_TITLE_STATUS')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_GATEWAYS_EXPIRATION_DATE')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_ACTION')); ?></strong></th>
                    </tr>
			</thead> 
        	<tbody>
    			<?php foreach ($chequeBankwireUsers as  $userDetails): ?>
    				<tr>
	        			<?php foreach($userDetails as $key=>$userDetail):
    							if($key == 'user_id'){

        							$user = get_user_by('id',$userDetail);
        							$userDetail = $user->user_login;
    							}
        				?>
							<td><?php echo $userDetail;?></td>
	        			<?php endforeach; ?>
	        			<td>
	        				<?php 
	        					$status = $userDetails['payment_status']== 0 ? language_code('DSP_PAYMENT_PROCESSING') : language_code('DSP_PAYMENT_COMPLETED'); 
	        					$onclick = $userDetails['payment_status']== 0 ? '  style="cursor:pointer;" onclick="update_payment_status('.$userDetails['user_id'].') '  : '';
	        					echo '<span '.$onclick.'  class="dsp_span_pointer" ">'.$status.'</span>';
	        				 ?>

	        			</td>

        			</tr>
    			<?php endforeach; ?>

	        </tbody>
            <tfoot>    
                    <tr>     
                        <th><strong>Id</strong></th>
                        <th><strong><?php _e(language_code('DSP_USER_NAME')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_PLAN_NAME')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_PLAN_AMOUNT')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_PLAN_DAYS')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_TITLE_STATUS')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_GATEWAYS_EXPIRATION_DATE')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_ACTION')); ?></strong></th>
                    </tr>
       		</tfoot> 
        	
        </table>
    </div>
<?php endif; ?> 
</div>
<br />
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>
