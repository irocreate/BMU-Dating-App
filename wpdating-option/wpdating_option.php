<?php

! defined('WPDATING_OPTION_ABSPATH') ? define('WPDATING_OPTION_ABSPATH', plugin_dir_path(__FILE__)) : null;
! defined('WPDATING_OPTION_URL') ? define('WPDATING_OPTION_URL', plugin_dir_url(__FILE__)) : null;

require_once plugin_dir_path(__FILE__) . 'includes/wpdating-option-configs.php';

require WPDATING_OPTION_ABSPATH . 'includes/class-wpdating-option.php';

function run_wpdating_options()
{
    new Wpdating_Option();
}

run_wpdating_options();

