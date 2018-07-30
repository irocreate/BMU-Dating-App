<?php
/*

  Copyright (C) www.wpdating.com - All Rights Reserved!

  Author - MyAllenMedia, LLC

  WordPress Dating Plugin

  contact@wpdating.com
 * 

 */
global $wpdb;
extract($_REQUEST);
if (isset($sql_submit)) {



    $lines = file($_FILES['sql_file']['tmp_name']);
// Loop through each line


    foreach ($lines as $line) {
// Skip it if it's a comment
        if (substr($line, 0, 2) == '--' || $line == '')
            continue;

// Add this line to the current segment
        $templine .= $line;
// If it has a semicolon at the end, it's the end of the query
        if (substr(trim($line), -1, 1) == ';') {
            // Perform the query
            $wpdb->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
            // Reset temp variable to empty
            $templine = '';
        }
    }
    echo "Tables imported successfully";
}
?>



<div id="general" class="postbox">

    <h3 class="hndle"><span>Import SQL</span></h3>

    <form method="post" enctype="multipart/form-data">

        <input type="file" name="sql_file" class="import-sql-file" />
        <input type="submit" name="sql_submit" value="Import" class="import-sql-file-btn" />

    </form>


</div>