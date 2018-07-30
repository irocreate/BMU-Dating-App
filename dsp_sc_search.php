<?php
function dsp_sc_search(){

        global $wpdb;
        
        $dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;

    $output = "

            <div class=\"tabcontent\" id=\"tab-search\"> 
                                        <form name=\"frmguestsearch\" class=\"dspdp-form-horizontal\" method=\"GET\" action=\"" . get_site_url() . "/members/g_search_result\">
                                            <input type=\"hidden\" name=\"search_type\" value=\"basic_search\" />";
                                             //---------------------------------START  GENERAL SEARCH---------------------------------------  
    $output .= "
                                            <div class=\"guest-search dsp-form-container\">
                                                <p class=\"dspdp-form-group dsp-form-group clearfix\">
                                                    <span class=\"dsp_left dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3\">" .
                                                        __(language_code('DSP_I_AM')) . "
                                                    </span>
                                                    <span class=\"dspdp-col-sm-6 dsp-sm-6\">
                                                        <select name=\"gender\" class=\"dspdp-form-control dsp-form-control\">" .
                                                            get_gender_list('M') . "
                                                        </select>
                                                    </span>
                                                </p>
                                                <p class=\"dspdp-form-group dsp-form-group clearfix\">
                                                    <span class=\"dsp_left  dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3\">" .
                                                        __(language_code('DSP_SEEKING_A')) . "
                                                    </span>
                                                    <span class=\"dspdp-col-sm-6 dsp-sm-6\"><select name=\"seeking\"  class=\"dspdp-form-control dsp-form-control\">" .
                                                        get_gender_list('F') . "
                                                    </select></span>
                                                </p>	 
                                                <p class=\"dspdp-form-group dsp-form-group clearfix\">
                                                    <span class=\"dsp_left dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3\">" .
                                                        __(language_code('DSP_AGE')) . "
                                                    </span>
                                                    <span class=\"dspdp-col-sm-3 dsp-sm-3 dspdp-xs-form-group\">
                                                        <select name=\"age_from\"  class=\"dspdp-form-control dsp-form-control\">"; 
                                                        
                                                        for ($fromyear = 18; $fromyear <= 99; $fromyear++) {
                                                            if ($fromyear == 18) {
                                                               $output .= " 
                                                                    <option value=\"" . $fromyear . "\" selected=\"selected\">" . $fromyear . "</option>";
                                                            } else { 
                                                               $output .= " 
                                                                    <option value=\"" . $fromyear . "\">" . $fromyear . "</option>";
                                                                
                                                            }
                                                        }
                                                 $output .= "        
                                                    </select></span>

                                                    <span class=\"dspdp-col-sm-3 dsp-sm-3\"><select name=\"age_to\"  class=\"dspdp-form-control dsp-form-control\">";
                                                        
                                                        for ($toyear = 18; $toyear <= 99; $toyear++) {
                                                            if ($toyear == 99) {
                                                               $output .= "  
                                                                    <option value=\"" . $toyear . "\" selected=\"selected\">" . $toyear . "</option>";
                                                             } else { 
                                                               $output .= "   
                                                                    <option value=\"" . $toyear . "\">" . $toyear . "</option>";
                                                                
                                                            }
                                                        }
                                                 $output .= "         
                                                    </select></span>
                                                </p>
                                                <p class=\"dspdp-form-group dsp-form-group clearfix\">
                                                    <span class=\"dsp_left dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3\">" . __(language_code('DSP_COUNTRY')) . "</span>
                                                    <span class=\"dspdp-col-sm-6 dsp-sm-6\">
                                                        <select name=\"cmbCountry\" id=\"cmbCountry_id\"  class=\"dspdp-form-control dsp-form-control\">
                                                        <option value=\"0\">" . __(language_code('DSP_SELECT_COUNTRY')) . "</option>";
                                                        
                                                        $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
                                                        foreach ($strCountries as $rdoCountries) {
                                                            $output .= " 
                                                                <option value='" . $rdoCountries->name . "' >" . $rdoCountries->name . "</option>";
                                                        }
                                                        
                                            $output .= "             
                                                    </select></span>
                                                </p>";
                                           
                                            
                                            $output .= "  
												<p class=\"dspdp-form-group dsp-form-group clearfix\">
                                                <span class=\"dsp_left  dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3\">" . __(language_code('DSP_SEARCH_ONLINE_ONLY')) . "</span>
                                                <span class=\"dspdp-col-sm-6 dsp-sm-6\"><select name=\"Online_only\" class=\"dspdp-form-control dsp-form-control\">
                                                    <option value=\"N\">" . __(language_code('DSP_OPTION_NO')). "</option>
                                                    <option value=\"Y\">" . __(language_code('DSP_OPTION_YES')). "</option>
                                                </select></span>
                                                </p>
                                                <p class=\"dspdp-form-group dsp-form-group clearfix\">
                                                    <span class=\"dsp_left dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3\">" . __(language_code('DSP_SEARCH_WITH_PICTURE_ONLY')) . "</span>
                                                    <span class=\"dspdp-col-sm-6 dsp-sm-6\"><select name=\"Pictues_only\" class=\"dspdp-form-control dsp-form-control\">
                                                        <option value=\"P\">" . __(language_code('DSP_OPTION_NO_PREFERENCE')) . "</option>
                                                        <option value=\"N\">" . __(language_code('DSP_OPTION_NO')) . "</option>
                                                        <option value=\"Y\">" . __(language_code('DSP_OPTION_YES')) . "</option>
                                                    </select></span>
                                                </p>
                                                <p class=\"dspdp-form-group dsp-form-group clearfix\">
                                                    <span class=\"dsp_left dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3\">&nbsp;</span>
                                                    <span class=\"dsp_right dspdp-col-sm-6 dsp-sm-6\">
                                                        <input type=\"submit\" name=\"submit\" class=\"dsp_submit_button dspdp-btn dspdp-btn-default\" value=\"". __(language_code('DSP_SEARCH_BUTTON')) . "\" onclick=\"dsp_guest_search();\" />
                                                    </span>
                                                </p>
                                            </div>
                                        </form>
                                    </div>";                   

    
    return $output;
}
add_shortcode('dsp_search','dsp_sc_search');