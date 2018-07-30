<?php

/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

require_once '../../../anet_php_sdk/AuthorizeNet.php'; // The SDK 
$url = WPDATE_URL . "/direct_post.php";
$api_login_id = '9bFSTZuT68r';
$transaction_key = '36cr3cb9BU9sAt6P';
$md5_setting = '9bFSTZuT68r'; // Your MD5 Setting 
$amount = "99";
AuthorizeNetDPM::directPostDemo($url, $api_login_id, $transaction_key, $amount, $md5_setting);
