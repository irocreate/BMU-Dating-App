<?php
mysql_real_escape_string();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Capture Image</title>

        <style>
            #iGallery		{list-style:none; padding:0px; margin:0px;display: none;}
            #iGallery li	{list-style:none; padding:2px; margin:2px; border:1px solid #999; float:left}

            #tadnavi		{ top:0px; z-index:100; width:100%; opacity:0.7; padding:0px; margin:0px; }
            #tadinfo		{position:fixed; display:none; bottom:0px; width:100%; padding:5px; background-color:#333333; opacity:0.7; color:#FFFFFF; text-align:center; font-size:small; font-family:Verdana, Geneva, sans-serif}

            #imageflipimg	{ height:100%; width:100%; text-align:center;}
            #tadcontent		{padding:0px; margin:0px; position:relative; background:#000; height:100%; width:100%;}
        </style> 
    </head>
    <body  class="ui-overlay-b">
        <div data-role="page" data-add-back-btn="true"id="Gallery1" class="gallery-page">

            <div data-role="header">
                <h1>First Gallery</h1>
            </div>

            <div data-role="content" align="center">	

                <ul id="iGallery" class="gallery">

                    <li>
                        <a id="galleryImg" show='images/full/001.jpg'  ><img src="images/thumb/001.jpg" alt="Image 001" /></a></li>
                    <li><a show="images/full/002.jpg" id="galleryImg"><img src="images/thumb/002.jpg" alt="Image 002" /></a></li>
                    <li><a id="galleryImg" show='images/full/003.jpg' ><img src="images/thumb/003.jpg" alt="Image 003" /></a></li>
                    <li><a id="galleryImg" show='images/full/004.jpg' ><img src="images/thumb/004.jpg" alt="Image 004" /></a></li>
                    <li><a id="galleryImg" show='images/full/005.jpg'  ><img src="images/thumb/005.jpg" alt="Image 005" /></a></li>
                    <li><a id="galleryImg" show='images/full/006.jpg' ><img src="images/thumb/006.jpg" alt="Image 006" /></a></li>
                    <li><a id="galleryImg" show='images/full/007.jpg'><img src="images/thumb/007.jpg" alt="Image 007" /></a></li>
                    <li><a id="galleryImg" show='images/full/008.jpg'><img src="images/thumb/008.jpg" alt="Image 008" /></a></li>
                    <li><a id="galleryImg" show='images/full/009.jpg'><img src="images/thumb/009.jpg" alt="Image 009" /></a></li>
                    <li><a id="galleryImg" show='images/full/010.jpg'><img src="images/thumb/010.jpg" alt="Image 010" /></a></li>


                </ul>


                <div id="tadcontent" data-role="content" class="ui-content" role="main">
                    <div id="imageflipimg" style="text-align:center;">
                        <img id="displayImg" src='images/full/001.jpg'/>
                    </div>


                    <div id="tadnavi" data-role="navbar" class="ui-navbar ui-mini" role="navigation" >
                        <ul>
                            <li>
                                <a href="#" data-iconpos="notext" data-role="button" data-icon="delete" id="tadclose"></a>
                            </li>
                            <li><a onclick="previousPic()"  data-iconpos="notext" data-role="button" data-icon="arrow-l" id="tadbk"></a>
                            </li>
                            <li><a onclick="nextPic()"  data-iconpos="notext" data-role="button" data-icon="arrow-r" id="tadnxt"></a></li>
                            </ui>
                    </div>

                </div>






            </div>
    </body>
</html>
