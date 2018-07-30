<?php

class Wpdating_Option_Admin
{
    public function __construct()
    {

    }

    public function create_menu()
    {
        add_submenu_page('dsp-admin-sub-page1', 'Options', 'Options', 'manage_options', 'dsp_options',
            array($this, 'options'));
    }

    public function options()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/option-page/option-page.php';
    }
}
