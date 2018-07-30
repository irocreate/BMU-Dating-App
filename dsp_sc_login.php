<?php
//Note: use shortcode "[dsp_login]" in the any page to use this feature
//same analogy goes for register and search.

function dsp_sc_login( $atts, $content = null ) {
    
    extract( shortcode_atts(
        array(
            'redirect' => 'members'
        ), $atts                
    ));
    
    $redirect_url = get_site_url() . "/{$redirect}/";
    
    if ( !is_user_logged_in() ) {
        return dsp_get_sc_login_form( $redirect_url ); //returns login form         
    } else {
        //redirect to members page by default
        //show form until redirect works and further notice from sir.
//        return dsp_get_sc_login_form( $redirect_url );
        return "<a href=\"" . wp_logout_url( home_url() ) . "\" title=\"Logout\">Logout</a>";
    }


} // End: dsp_sc_login
	
add_shortcode('dsp_login','dsp_sc_login');

function dsp_get_sc_login_form( $redirect_url ){
    
    $output = "
        <div class=\"box-border\">
            <div class=\"box-pedding\">";
        
            if (isset($msg) && $msg != '') {
                echo $msg;
            }
        
//        <!-- To hold validation results -->
    $output .= "<div class=\"dsp_login_main\">
                <form method=\"post\" action=\"" . get_bloginfo('url') . "/wp-login.php\"  class=\"dspdp-form-horizontal\" >
                    <div class=\"dspdp-form-group\"><div class=\"dsp_login_left dspdp-col-sm-3 dspdp-control-label\">" . __(language_code('DSP_USER_NAME')) . "</div>
                    <div class=\"dsp_login_right dspdp-col-sm-6\"><input class=\"dspdp-form-control\" type=\"text\" name=\"log\" value=\"\" size=\"20\" id=\"user_login\" tabindex=\"11\" /></div></div>

                                    <div class=\"dspdp-form-group\"><div class=\"dsp_login_left dspdp-col-sm-3 dspdp-control-label\">" . __(language_code('DSP_PASSWORD')) . "</div>
                    <div class=\"dsp_login_right dspdp-col-sm-6\"><input class=\"dspdp-form-control\" type=\"password\" name=\"pwd\" value=\"\" size=\"20\" id=\"user_pass\" tabindex=\"12\" /></div></div>
                    <div class=\"dspdp-form-group\"><div class=\"dsp_login_right  dspdp-col-sm-offset-3  dspdp-col-sm-6\"><input type=\"checkbox\" name=\"rememberme\" value=\"forever\" checked=\"checked\" id=\"rememberme\" tabindex=\"13\" />" . __(language_code('DSP_REMEMBER_ME')) .
                    "</div></div>
                                    <div class=\"dspdp-form-group\">
                    <div class=\"dsp_login_left dspdp-hidden\">&nbsp;</div>
                    <div class=\"dsp_login_right   dspdp-col-sm-offset-3  dspdp-col-sm-6\">" . do_action('login_form') .apply_filters('dsp_facebook_login','').
                        "<input type=\"submit\"  class=\"dspdp-btn dspdp-btn-default user-submit\" name=\"user-submit\" value=\"" . __(language_code('DSP_LOGIN')) . "\" tabindex=\"14\"  style=\"margin-left:10px\"/>
                        <input type=\"hidden\" name=\"redirect_to\" value=\"" . $redirect_url . "\" />
                        <input type=\"hidden\" name=\"user-cookie\" value=\"1\" /></div>
                    </div>

                </form>
            
                        <div class=\"dspdp-clear\"></div>
                    </div>
                </div>
            </div>";
    return $output;
}