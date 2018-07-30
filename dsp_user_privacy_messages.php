<?php
$print_msg = $_GET['msg'];
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
?>
<div class="dsp_box-out">
    <div class="dsp_box-in">
        <div align="center">
            <?php
            if ($print_msg == 'profile') {
                echo language_code('DSP_USER_PRIVACY_MESSAGE');
            }
            ?>
        </div>
    </div>
</div>