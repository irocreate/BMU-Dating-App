<?php


class Wpdating_Paypal
{

    public function __construct()
    {
        $this->load_dependencies();
        $this->define_public_hooks();
    }
    private function load_dependencies()
    {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpdating-paypal-public.php';
    }
        public function define_public_hooks()
    {
        $plugin_public = new Wpdating_Paypal_Public();
        add_action('parse_request', array($plugin_public, 'handle_api_requests'));
        add_action('dsp_api_wc_gateway_paypal', array($plugin_public, 'check_paypal_response'));
    }
}
