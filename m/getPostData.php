<?php

include('myclass.php');

//$action = $_REQUEST['action']; // We dont need action for this tutorial, but in a complex code you need a way to determine ajax action nature
//$formData = json_decode($_REQUEST['formData']); // Decode JSON object into readable PHP object
//$username = $_REQUEST['username']; // Get username from object
//$password = $formData->password; // Get password from object]

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];

$arrSections = array();
$intCounter = 1;



$arrSections[$intCounter] = new clsSections;
$arrSections[$intCounter]->section_id = 1;
$arrSections[$intCounter]->section_title = 'usernmae=' . $username;
$arrSections[$intCounter]->section_element = 'paassword=' . $password;




echo $_GET['callback'] . '(' . json_encode($arrSections) . ')';
?>