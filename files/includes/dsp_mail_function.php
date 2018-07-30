<?php

/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

// Written by my working partner, Marc Jones (edm-i.com) who
// says this function should augment the php mail() function
// hacker/spammer safe wrapper for php mail() function that will allow plain text and mixed html email
// version 1 does not handle attachments - I'll work on that
if(!function_exists('dsp_send_email')) {
    function dsp_send_email($to, $fromaddr, $fromname, $subject, $message_text, $message_html = "") {
        // to prevent spammers/hackers from utilising your html2server email form
        // this type of hacking is called "header injection" where the spammer will call your
        // script with the subject or message containing more header information before the message
        // allowing them to send as many mails as they like, and blacklisting your mail server as a spammer
        // they mostly change the headers, and add cc, and bcc headers.
        // The best way to stop this is to check for headers and remove them!
        $subject = preg_replace("/\nfrom\:.*?\n/i", "", $subject);
        $subject = preg_replace("/\nbcc\:.*?\n/i", "", $subject);
        $subject = preg_replace("/\ncc\:.*?\n/i", "", $subject);
        $message_text = preg_replace("/\nfrom\:.*?\n/i", "", $message_text);
        $message_text = preg_replace("/\nbcc\:.*?\n/i", "", $message_text);
        $message_text = preg_replace("/\ncc\:.*?\n/i", "", $message_text);
        $message_html = preg_replace("/\nfrom\:.*?\n/i", "", $message_html);
        $message_html = preg_replace("/\nbcc\:.*?\n/i", "", $message_html);
        $message_html = preg_replace("/\ncc\:.*?\n/i", "", $message_html);
        $headers = '';
        // create additional_parameters - this ensures that the RETURN-PATH will be properly set
        // saving the mail from being rejected by the destination mail server as spam
        // known servers that reject if RETURN-PATH domain does not match the from domain include
        // gmail, hotmail, aol, excite, yahoo, btinternet
        // most spam killers will also regard emails with
        //$additional_parameters = "-f $fromaddr";
        // create additional_headers
        //$headers = "From: $fromname <$fromaddr>\r\n";
        // specify MIME version 1.0
        //$headers .= "MIME-Version: 4.2\r\n";
        // deal with html messages
        if ($message_html != "") {
            // unique boundary
            $boundary = uniqid("sometext");
            // tell e-mail client this e-mail contains alternate versions
            $headers .= "Content-Type: multipart/alternative; boundary = $boundary\r\n\r\n";
            // plain text version of message
            $body = "--$boundary\r\n";
            $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
            $body .= "Content-Transfer-Encoding: 7 bit\r\n\r\n";
            $body .= $message_text . "\r\n\r\n";
            // HTML version of message
            $body .= "--$boundary\r\n";
            $body .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $body .= "Content-Transfer-Encoding: t bit\r\n\r\n";
            $body .= $message_html . "\r\n\r\n";
        }
        // deal with plain text only messages
        if ($message_html == "") {
            // tell e-mail client the content type
            $headers .= "Content-type: text/plain; charset=iso-8859-1\n";
            // the plain text message
            $body = $message_text;
        }
        // send message
        //return mail($to, $subject, $body, $headers, $additional_parameters);
        // l jS \of F Y \at h:i a
        $headers = "From: " . $fromname . " <" . $fromaddr . ">\n";
        $headers.= "MIME-Version: 1.0" . "\n";
        $headers.= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers.= "Sensitivity: Normal" . "\n"; //Sensitivity: Normal, Personal, Private and Company-Confidential
        $headers.="Date:" .date('Y-m-d H:i:s') . "\r\n";
        $headers.="Return-Path:<members@lovely-thai-lady.com>\n";
        $to = "<" . $to . ">";
        $body = preg_replace("#(?<!\r)\n#si", "\r\n", $body); // Fix any bare linefeeds in msg to make it RFC821 Compliant.
        //$mailsent=mail($to, $subject, $body, $headers,'-f '.$to);
        
        $mailsent = wp_mail($to, $subject, $body, $headers);
        return $mailsent;
    }
    
}