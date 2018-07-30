<?php
ob_start();
global $wpdb;
$email = "activation@datingsolutions.biz";
$ip = $_SERVER['REMOTE_ADDR'];
$siteurl = get_option('siteurl');
$adminemail = get_option('admin_email');
$subject = "Plugin activation information";
$body = "The following domain just activated the Dating Plugin:\n
//Site Address (URL): $siteurl \n
//Site Admin Email Address: $adminemail  \n
//IP Address: $ip \n ";
$from = $adminemail;
$headers = "From: $from";
wp_mail($email, $subject, $body, $headers);
////ob_clean();
