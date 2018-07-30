<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$imagepath = WPDATE_URL . "/images/";
?>
<style>
    .dsnew_img{ text-decoration:none;  }
</style>
<div id="general" class="postbox" >
    <h3 class="hndle"><span><?php echo language_code('DSP_DSNEWS'); ?></span></h3>
    <div style="width:100%;margin:20px;">
        <?php
        $rss = new DOMDocument();
        $rss->load('http://www.wpdating.com/feed/');
        $feed = array();
        foreach ($rss->getElementsByTagName('item') as $node) {
            $item = array(
                'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
            );
            array_push($feed, $item);
        }
        $count = count($feed);
        $limit = 20;
        if ($count <= $limit)
            $limit = $count;
        else
            $limit = 20;
        for ($x = 0; $x < $limit; $x++) {
            $title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
            $link = $feed[$x]['link'];
            if ($title != '') {
                echo '<p style="font-size:15px;"><img src=' . $imagepath . '/news.png style="margin-right:10px;"/><a href="' . $link . '" title="' . $title . '"  class="dsnew_img" alt="'.  $title .'">' . $title . '</a><br />';
            }
        }
        ?>
    </div>	
</div>
<br />
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>