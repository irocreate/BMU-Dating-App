<?php

class Wpdating_Option
{
    public function __construct()
    {
        $this->load_dependencies();
        $this->define_admin_hooks();
    }


    /**
     *  Load the required dependencies for this plugin.
     *
     * @since 1.0.0
     * @access private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */

        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wpdating-option-admin.php';
    }


    private function define_admin_hooks()
    {
        $plugin_admin = new Wpdating_Option_Admin();
        add_action('admin_menu', array(&$plugin_admin, 'create_menu'));
    }
}