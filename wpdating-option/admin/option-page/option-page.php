<?php

class Wpdating_Option_Page
{
    public function __construct()
    {
        $this->load_dependencies();
    }

    /**
     *  Load the required dependencies for this plugin.
     *
     * @since 1.0.0
     * @access private
     */
    private function load_dependencies()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'option-page/option/powered-by-settings.php';
    }
}

$wpdating_option_page = new Wpdating_Option_Page();