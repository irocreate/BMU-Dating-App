<?php

//db parameters
$dbhost = "64.50.179.233:20081"; // this will ususally be 'localhost', but can sometimes differ  
$dbuser = "cwuser1"; // the username that you created, or were given, to access your database  
$dbpass = "SUtRr!4#3^7f"; // the password that you created, or were given, to access your database  
// first connect to the db with names of the other dbs 
mysql_connect($dbhost, $dbuser, $dbpass) or die("MySQL Error: " . mysql_error());
mysql_select_db("information_schema") or die("MySQL Error: " . mysql_error());

//select dbnames from the table with db names
$query = "SELECT `TABLE_NAME` , `INDEX_NAME` FROM `STATISTICS` WHERE `INDEX_SCHEMA` = 'dsp_dating' AND INDEX_NAME != 'PRIMARY'";
$result = mysql_query($query);
//echo "<pre>";print_r($result);die;

//loop through the results(dbname) and connect to each db 
mysql_select_db('dsdev') or die("MySQL Error: " . mysql_error());
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    //put the dbname results into a variable
    $table_name = $row['TABLE_NAME'];
    $index_name = $row['INDEX_NAME'];
    $query = "DROP INDEX `$index_name` ON `$table_name`";
    if (mysql_query($query)) {
        echo "<br /> dropped index " . $index_name . " from " . $table_name;
    } else {
        echo "<br /> failed to drop index" . $index_name . " from " . $table_name;
    }
}
