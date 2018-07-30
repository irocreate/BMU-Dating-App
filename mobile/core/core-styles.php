<style type="text/css">
    #headerbar, #wptouch-login, #wptouch-search {
        background: #FA7000; 
    }
    #headerbar-title, #headerbar-title a {
        color: #<?php echo bnc_get_header_color(); ?>;
    }
    #wptouch-menu-inner a:hover {
        color: #<?php echo bnc_get_link_color(); ?>;
    }
    #catsmenu-inner a:hover {
        color: #<?php echo bnc_get_link_color(); ?>;
    }
    #drop-fade {
        background: #<?php echo bnc_get_header_border_color(); ?>;
    }
    a, h3#com-head {
        color: #<?php echo bnc_get_link_color(); ?>;
    }
    a.h2, a.sh2, .page h2 {
        font-family: '<?php echo bnc_get_h2_font(); ?>';
    }
    <?php wptouch_thumb_reflections(); ?>
    <?php if (bnc_is_truncated_enabled()) { ?>
        a.h2{
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
    <?php } ?>
</style>