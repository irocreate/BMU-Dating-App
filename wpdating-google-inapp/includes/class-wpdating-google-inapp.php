<?php

class Wpdating_google_inapp{
	
	public function __construct(){

	}

	public function define_public_hooks(){
		add_action('dsp_api_wpdating_gateway_google', 'check_google_inapp_response', 10, 0);
	}
}

