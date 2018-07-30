<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$pageURL = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';
?>
<div class="wrap"><h2><?php echo __('Dating Site Admin', 'dsp_trans_domain') ?></h2></div>
<div id="navmenu" align="left">
    <ul>
     <li <?php if ($pageURL == "edatingmoz") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
     <a href="admin.php?page=dsp-admin-sub-page5&pid=edatingmoz" title="eDatingMoz">DS Affiliate Program</a></li>
           
</div>
<?php
if ($pageURL == "seo") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_marketing_seo.php');
} else if ($pageURL == "edatingmoz") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_marketing_edatingmoz.php');
}else {
     include_once( WP_DSP_ABSPATH . 'files/dsp_marketing_edatingmoz.php');
}
?>